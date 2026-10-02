<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlumniRequest;
use App\Http\Requests\UpdateAlumniRequest;
use App\Models\Alumni;
use App\Models\Dokumen;
use App\Models\Ijazah;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AlumniController extends Controller
{
    /**
     * Show the public alumni registration form.
     */
    public function showRegister()
    {
        return view('public.register');
    }

    /**
     * Store a newly created alumni in storage (and related document/ijazah records).
     */
    public function register(StoreAlumniRequest $request)
    {
        try {
            DB::beginTransaction();

            // Store pas foto to public disk
            $pasFotoPath = $request->file('pas_foto')->store('pas_foto', 'public');
            // Store berkas persyaratan PDF to public disk
            $berkasPersyaratanPath = $request->file('berkas_persyaratan')->store('berkas_persyaratan', 'public');

            // Create Alumnus
            $alumni = Alumni::create([
                'nama' => $request->nama,
                'nim' => $request->nim,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'program_studi' => $request->program_studi,
                'jurusan' => $request->jurusan,
                'tahun_masuk' => $request->tahun_masuk,
                'tahun_lulus' => $request->tahun_lulus,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'status_verifikasi' => 'Belum Diverifikasi',
            ]);

            // Create Dokumen relation with pas foto path & berkas persyaratan PDF path
            Dokumen::create([
                'alumni_id' => $alumni->id,
                'pas_foto' => $pasFotoPath,
                'pas_foto_status' => 'Menunggu Verifikasi',
                'berkas_persyaratan' => $berkasPersyaratanPath,
                'berkas_persyaratan_status' => 'Menunggu Verifikasi',
            ]);

            // Create Ijazah relation
            Ijazah::create([
                'alumni_id' => $alumni->id,
                'status' => 'Belum Diambil',
            ]);

            // Create Pengembalian record for 6 Graduation Document Checklist (Bagian B)
            Pengembalian::create([
                'alumni_id' => $alumni->id,
                'foto_ijazah_status' => 'Belum Siap',
                'bukti_transkrip_status' => 'Belum Siap',
                'sertifikat_profesi_status' => 'Belum Siap',
                'skpi_status' => 'Belum Siap',
                'kartu_ikapode_status' => 'Belum Siap',
                'foto_wisuda_status' => 'Belum Siap',
            ]);

            DB::commit();

            return redirect()->route('public.register.sukses', $alumni->id)
                ->with('toast_success', 'Data alumni dan berkas persyaratan berhasil dikirim!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Cleanup uploaded files on failure
            if (isset($pasFotoPath)) Storage::disk('public')->delete($pasFotoPath);
            if (isset($berkasPersyaratanPath)) Storage::disk('public')->delete($berkasPersyaratanPath);

            return back()->withInput()->with('toast_error', 'Gagal mendaftar: ' . $e->getMessage());
        }
    }

    /**
     * Show registration success screen.
     */
    public function showSuccess($id)
    {
        $alumni = Alumni::with('dokumen', 'ijazah')->findOrFail($id);
        return view('public.success', compact('alumni'));
    }

    /**
     * Admin: List all alumni with sorting, search, filter, and pagination.
     */
    public function adminIndex(Request $request)
    {
        $query = Alumni::with('dokumen', 'ijazah');

        // Search realtime
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter Program Studi
        if ($request->filled('program_studi')) {
            $query->where('program_studi', $request->program_studi);
        }

        // Filter Status Verifikasi
        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        $allowedSorts = ['nama', 'nim', 'program_studi', 'email', 'created_at', 'status_verifikasi'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $alumniList = $query->paginate(10)->withQueryString();

        // Get unique program studi for filtering options
        $programStudis = Alumni::select('program_studi')->distinct()->pluck('program_studi')->toArray();

        return view('admin.alumni.index', compact('alumniList', 'programStudis'));
    }

    /**
     * Admin: Show detail of alumnus with PDF view support.
     */
    public function adminShow(Alumni $alumni)
    {
        $alumni->load('dokumen', 'ijazah', 'pengembalian');
        $berkasConfig = Dokumen::getBerkasConfig();
        $documentConfig = Pengembalian::getDocumentItemsConfig();

        return view('admin.alumni.show', compact('alumni', 'berkasConfig', 'documentConfig'));
    }

    /**
     * Admin: Verify Alumnus.
     */
    public function adminVerify(UpdateAlumniRequest $request, Alumni $alumni)
    {
        $alumni->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // If approving alumni, mark all 6 documents in Dokumen as Diverifikasi
        if ($request->status_verifikasi === 'Sudah Diverifikasi' && $alumni->dokumen) {
            $berkasConfig = Dokumen::getBerkasConfig();
            $dokUpdates = [];
            foreach ($berkasConfig as $item) {
                $dokUpdates[$item['status_field']] = 'Diverifikasi';
                $dokUpdates[$item['catatan_field']] = null;
            }
            $alumni->dokumen->update($dokUpdates);
        }

        return back()->with('toast_success', 'Status verifikasi ' . $alumni->nama . ' berhasil diperbarui (' . $request->status_verifikasi . ').');
    }

    /**
     * Admin: Export to CSV (Excel compatible).
     */
    public function exportExcel(Request $request)
    {
        $query = Alumni::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('program_studi')) {
            $query->where('program_studi', $request->program_studi);
        }

        if ($request->filled('status_verifikasi')) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        $alumniList = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data-alumni-' . date('Y-m-d_H-i') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($alumniList) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM (Byte Order Mark) for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Header with Semicolon divider
            fputcsv($file, [
                'Nomor Registrasi',
                'NIM',
                'Nama Lengkap',
                'NIK',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Jenis Kelamin',
                'Program Studi',
                'Jurusan',
                'Tahun Masuk',
                'Tahun Lulus',
                'Email',
                'No HP',
                'Alamat',
                'Status Verifikasi',
                'Catatan Admin',
                'Tanggal Daftar'
            ], ';');

            foreach ($alumniList as $alumni) {
                fputcsv($file, [
                    $alumni->nomor_registrasi,
                    $alumni->nim,
                    $alumni->nama,
                    $alumni->nik,
                    $alumni->tempat_lahir,
                    $alumni->tanggal_lahir ? $alumni->tanggal_lahir->format('Y-m-d') : '',
                    $alumni->jenis_kelamin,
                    $alumni->program_studi,
                    $alumni->jurusan,
                    $alumni->tahun_masuk,
                    $alumni->tahun_lulus,
                    $alumni->email,
                    $alumni->no_hp,
                    $alumni->alamat,
                    $alumni->status_verifikasi,
                    $alumni->catatan_admin,
                    $alumni->created_at->format('Y-m-d H:i:s')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
