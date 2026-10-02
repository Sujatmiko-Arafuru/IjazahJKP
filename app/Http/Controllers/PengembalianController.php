<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Dokumen;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengembalianController extends Controller
{
    /**
     * Show public page for Mahasiswa to check verification status and graduation document readiness.
     */
    public function showMahasiswaForm(Request $request)
    {
        $alumni = null;
        $dokumen = null;
        $pengembalian = null;
        $searched = false;

        if ($request->filled('nomor_registrasi')) {
            $searched = true;
            $searchReg = trim($request->nomor_registrasi);
            $alumni = Alumni::with(['dokumen', 'pengembalian', 'ijazah'])
                ->where('nomor_registrasi', $searchReg)
                ->orWhere('nomor_registrasi', 'like', "%{$searchReg}")
                ->first();

            if ($alumni) {
                $dokumen = $alumni->dokumen ?: Dokumen::firstOrCreate(['alumni_id' => $alumni->id]);
                $pengembalian = $alumni->pengembalian ?: Pengembalian::firstOrCreate(['alumni_id' => $alumni->id]);
            }
        } elseif ($request->filled('nim') && $request->filled('tanggal_lahir')) {
            $searched = true;
            $tgl = trim($request->tanggal_lahir);
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $tgl, $m)) {
                $tgl = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
            }

            $alumni = Alumni::with(['dokumen', 'pengembalian', 'ijazah'])
                ->where('nim', trim($request->nim))
                ->where(function ($q) use ($tgl, $request) {
                    $q->where('tanggal_lahir', $tgl)
                      ->orWhere('tanggal_lahir', $request->tanggal_lahir);
                })
                ->first();

            if ($alumni) {
                $dokumen = $alumni->dokumen ?: Dokumen::firstOrCreate(['alumni_id' => $alumni->id]);
                $pengembalian = $alumni->pengembalian ?: Pengembalian::firstOrCreate(['alumni_id' => $alumni->id]);
            }
        }

        $berkasConfig = Dokumen::getBerkasConfig();
        $documentConfig = Pengembalian::getDocumentItemsConfig();

        return view('public.pengembalian', compact(
            'alumni',
            'dokumen',
            'pengembalian',
            'searched',
            'berkasConfig',
            'documentConfig'
        ));
    }

    /**
     * Re-upload a rejected document by Mahasiswa (Bagian A).
     * Students are only allowed to re-upload if the document was rejected by Admin.
     */
    public function uploadMahasiswaItem(Request $request)
    {
        $request->validate([
            'alumni_id' => 'required|exists:alumni,id',
            'item_key' => 'required|string',
        ]);

        $alumni = Alumni::with(['dokumen', 'pengembalian'])->findOrFail($request->alumni_id);
        $dokumen = $alumni->dokumen ?: Dokumen::firstOrCreate(['alumni_id' => $alumni->id]);
        $berkasConfig = Dokumen::getBerkasConfig();

        if (!array_key_exists($request->item_key, $berkasConfig)) {
            return back()->with('toast_error', 'Tipe berkas tidak valid.');
        }

        $config = $berkasConfig[$request->item_key];
        $fileField = $config['file_field'];
        $statusField = $config['status_field'];
        $catatanField = $config['catatan_field'];

        // Strict Check: Editing/re-uploading is ONLY allowed when status is 'Ditolak'
        if ($dokumen->$statusField !== 'Ditolak') {
            return back()->with('toast_error', 'Berkas ' . $config['title'] . ' sedang ditinjau atau telah disetujui, sehingga tidak dapat diubah.');
        }

        if ($request->item_key === 'drive_link') {
            $request->validate([
                'drive_link' => 'required|string|url|max:500',
            ], [
                'drive_link.required' => 'Mohon masukkan Link Google Drive Berkas Persyaratan.',
                'drive_link.url' => 'Format Link Google Drive tidak valid. Harus diawali dengan http:// atau https://.',
            ]);

            $dokumen->drive_link = $request->drive_link;
        } else {
            $request->validate([
                'file' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120',
            ], [
                'file.required' => 'Mohon pilih foto untuk diunggah.',
                'file.mimes' => 'Pas foto harus berformat JPG, JPEG, PNG, atau PDF.',
                'file.max' => 'Ukuran pas foto maksimal 5 MB.',
            ]);

            $storedPath = $request->file('file')->store('pas_foto', 'public');

            // Delete old file if exists
            if ($dokumen->$fileField && Storage::disk('public')->exists($dokumen->$fileField)) {
                Storage::disk('public')->delete($dokumen->$fileField);
            }

            $dokumen->$fileField = $storedPath;
        }

        // Update document status back to Menunggu Verifikasi & clear rejection notes
        $dokumen->$statusField = 'Menunggu Verifikasi';
        $dokumen->$catatanField = null;
        $dokumen->save();

        // Check if there are still any other rejected documents
        $hasOtherRejections = false;
        foreach ($berkasConfig as $key => $item) {
            $stField = $item['status_field'];
            if ($dokumen->$stField === 'Ditolak') {
                $hasOtherRejections = true;
                break;
            }
        }

        // If no rejected documents remain, reset overall alumni status to Belum Diverifikasi
        if (!$hasOtherRejections && $alumni->status_verifikasi === 'Ditolak') {
            $alumni->update([
                'status_verifikasi' => 'Belum Diverifikasi',
                'catatan_admin' => null
            ]);
        }

        $redirectParams = [];
        if ($request->filled('nomor_registrasi')) {
            $redirectParams['nomor_registrasi'] = $alumni->nomor_registrasi;
        } else {
            $redirectParams['nim'] = $alumni->nim;
            $redirectParams['tanggal_lahir'] = $alumni->tanggal_lahir ? $alumni->tanggal_lahir->format('Y-m-d') : '';
        }

        return redirect()->route('public.pengembalian', $redirectParams)
            ->with('toast_success', 'Berkas ' . $config['title'] . ' berhasil diperbarui! Menunggu verifikasi admin.');
    }

    /**
     * Admin: List Graduation Documents (Bagian B) for all alumni.
     */
    public function adminDokumen(Request $request)
    {
        $query = Alumni::with(['pengembalian', 'dokumen']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('nomor_registrasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('program_studi')) {
            $query->where('program_studi', $request->program_studi);
        }

        $alumniList = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $documentConfig = Pengembalian::getDocumentItemsConfig();
        $programStudis = Alumni::select('program_studi')->distinct()->pluck('program_studi')->toArray();

        return view('admin.pengembalian.dokumen', compact('alumniList', 'documentConfig', 'programStudis'));
    }

    /**
     * Admin: Show detail view for Graduation Document Verification (Bagian B).
     */
    public function adminShowDokumen(Alumni $alumni)
    {
        $alumni->load(['dokumen', 'pengembalian']);
        $pengembalian = Pengembalian::firstOrCreate(['alumni_id' => $alumni->id]);
        $documentConfig = Pengembalian::getDocumentItemsConfig();

        return view('admin.pengembalian.show', compact('alumni', 'pengembalian', 'documentConfig'));
    }

    /**
     * Admin: Verify an item in Bagian A (Dokumen) OR Bagian B (Pengembalian).
     */
    public function adminVerifyItem(Request $request, $alumniId)
    {
        $request->validate([
            'item_key' => 'required|string',
            'action' => 'required|in:approve,reject',
            'catatan_admin' => 'nullable|string|required_if:action,reject',
        ], [
            'catatan_admin.required_if' => 'Catatan / alasan penolakan wajib diisi jika dokumen ditolak.',
        ]);

        $alumni = Alumni::with(['dokumen', 'pengembalian'])->findOrFail($alumniId);
        $berkasConfig = Dokumen::getBerkasConfig();
        $graduationConfig = Pengembalian::getDocumentItemsConfig();

        // 1. Check if verifying Bagian A (Dokumen Persyaratan Mahasiswa)
        if (array_key_exists($request->item_key, $berkasConfig)) {
            $dokumen = $alumni->dokumen ?: Dokumen::firstOrCreate(['alumni_id' => $alumni->id]);
            $config = $berkasConfig[$request->item_key];
            $statusField = $config['status_field'];
            $catatanField = $config['catatan_field'];

            if ($request->action === 'approve') {
                $dokumen->$statusField = 'Diverifikasi';
                $dokumen->$catatanField = null;
                $message = 'Dokumen ' . $config['title'] . ' untuk ' . $alumni->nama . ' berhasil disetujui (Diverifikasi).';
            } else {
                $dokumen->$statusField = 'Ditolak';
                $dokumen->$catatanField = $request->catatan_admin ?: 'Berkas tidak sesuai/buram. Mohon unggah ulang.';
                $message = 'Dokumen ' . $config['title'] . ' untuk ' . $alumni->nama . ' telah ditolak dengan catatan.';
            }
            $dokumen->save();

            // Evaluate overall alumni status
            $allApproved = true;
            $anyRejected = false;
            foreach ($berkasConfig as $item) {
                $st = $dokumen->{$item['status_field']};
                if ($st !== 'Diverifikasi') {
                    $allApproved = false;
                }
                if ($st === 'Ditolak') {
                    $anyRejected = true;
                }
            }

            if ($allApproved) {
                $alumni->update(['status_verifikasi' => 'Sudah Diverifikasi', 'catatan_admin' => null]);
            } elseif ($anyRejected) {
                $alumni->update(['status_verifikasi' => 'Ditolak', 'catatan_admin' => $request->catatan_admin ?: 'Terdapat berkas yang ditolak.']);
            }

            return back()->with('toast_success', $message);
        }

        // 2. Check if verifying Bagian B (Pengambilan Dokumen Kelulusan - Percentangan Admin)
        if (array_key_exists($request->item_key, $graduationConfig)) {
            $pengembalian = $alumni->pengembalian ?: Pengembalian::firstOrCreate(['alumni_id' => $alumni->id]);
            $config = $graduationConfig[$request->item_key];
            $statusField = $config['status_field'];
            $catatanField = $config['catatan_field'];

            if ($request->action === 'approve') {
                $pengembalian->$statusField = 'Diverifikasi';
                $pengembalian->$catatanField = null;
                $message = 'Dokumen ' . $config['title'] . ' untuk ' . $alumni->nama . ' berhasil dicentang (Sudah Diambil).';
            } else {
                $pengembalian->$statusField = 'Ditolak';
                $pengembalian->$catatanField = $request->catatan_admin ?: 'Belum diambil oleh mahasiswa.';
                $message = 'Dokumen ' . $config['title'] . ' untuk ' . $alumni->nama . ' ditandai Belum Diambil.';
            }
            $pengembalian->save();

            return back()->with('toast_success', $message);
        }

        return back()->with('toast_error', 'Tipe dokumen tidak valid.');
    }

    /**
     * Stream file inline for student & admin file viewing (Supports both Dokumen & Pengembalian).
     */
    public function viewFile($alumniId, $itemKey)
    {
        $alumni = Alumni::with(['dokumen', 'pengembalian'])->findOrFail($alumniId);
        $path = null;

        $berkasConfig = Dokumen::getBerkasConfig();
        $graduationConfig = Pengembalian::getDocumentItemsConfig();

        if (array_key_exists($itemKey, $berkasConfig) && $alumni->dokumen) {
            $fileField = $berkasConfig[$itemKey]['file_field'];
            $path = $alumni->dokumen->$fileField;
        } elseif (array_key_exists($itemKey, $graduationConfig) && $alumni->pengembalian) {
            $fileField = $graduationConfig[$itemKey]['file_field'];
            $path = $alumni->pengembalian->$fileField;
        }

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'File belum diunggah atau tidak ditemukan.');
        }

        $fileContent = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return response($fileContent, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }
}

