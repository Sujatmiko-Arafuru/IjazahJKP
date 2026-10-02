<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with real-time statistics.
     */
    public function index()
    {
        $totalAlumni = Alumni::count();
        $belumDiverifikasiTahap1 = Alumni::where('status_verifikasi', 'Belum Diverifikasi')->count();
        $sudahDiverifikasiTahap1 = Alumni::where('status_verifikasi', 'Sudah Diverifikasi')->count();

        // Count 6 Document Items status (Tahap 2)
        $fotoIjazahSelesai = Pengembalian::where('foto_ijazah_status', 'Diverifikasi')->count();
        $buktiTranskripSelesai = Pengembalian::where('bukti_transkrip_status', 'Diverifikasi')->count();
        $sertifikatProfesiSelesai = Pengembalian::where('sertifikat_profesi_status', 'Diverifikasi')->count();
        $skpiSelesai = Pengembalian::where('skpi_status', 'Diverifikasi')->count();
        $fotoWisudaSelesai = Pengembalian::where('foto_wisuda_status', 'Diverifikasi')->count();
        $kartuIkapodeSelesai = Pengembalian::where('kartu_ikapode_status', 'Diverifikasi')->count();

        // Count pending verifications for Tahap 2
        $pengembalianPending = Pengembalian::where(function($q) {
            $q->where('foto_ijazah_status', 'Menunggu Verifikasi')
              ->orWhere('bukti_transkrip_status', 'Menunggu Verifikasi')
              ->orWhere('sertifikat_profesi_status', 'Menunggu Verifikasi')
              ->orWhere('skpi_status', 'Menunggu Verifikasi')
              ->orWhere('foto_wisuda_status', 'Menunggu Verifikasi')
              ->orWhere('kartu_ikapode_status', 'Menunggu Verifikasi');
        })->count();

        // Alumni per Program Studi
        $prodiStats = Alumni::select('program_studi')
            ->selectRaw('count(*) as total')
            ->groupBy('program_studi')
            ->get()
            ->pluck('total', 'program_studi')
            ->toArray();

        $prodiLabels = array_keys($prodiStats);
        $prodiValues = array_values($prodiStats);

        // Dokumen Tahap 2 status summary labels & values
        $dokumenLabels = ['Foto Ijazah', 'Transkrip', 'Profesi', 'SKPI', 'Wisuda', 'IKAPODE'];
        $dokumenValues = [
            $fotoIjazahSelesai,
            $buktiTranskripSelesai,
            $sertifikatProfesiSelesai,
            $skpiSelesai,
            $fotoWisudaSelesai,
            $kartuIkapodeSelesai
        ];

        return view('admin.dashboard', compact(
            'totalAlumni',
            'belumDiverifikasiTahap1',
            'sudahDiverifikasiTahap1',
            'pengembalianPending',
            'prodiLabels',
            'prodiValues',
            'dokumenLabels',
            'dokumenValues'
        ));
    }
}
