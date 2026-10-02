<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $fillable = [
        'alumni_id',

        // 3 Menu Wajib Pengembalian / Pengambilan
        'pengambilan_ijazah_file',
        'pengambilan_ijazah_status',
        'pengambilan_ijazah_catatan',

        'pengembalian_toga_file',
        'pengembalian_toga_status',
        'pengembalian_toga_catatan',

        'bank_ijazah_file',
        'bank_ijazah_status',
        'bank_ijazah_catatan',

        // 6 Menu Verifikasi Dokumen
        'foto_ijazah_file',
        'foto_ijazah_status',
        'foto_ijazah_catatan',

        'bukti_transkrip_file',
        'bukti_transkrip_status',
        'bukti_transkrip_catatan',

        'sertifikat_profesi_file',
        'sertifikat_profesi_status',
        'sertifikat_profesi_catatan',

        'skpi_file',
        'skpi_status',
        'skpi_catatan',

        'foto_wisuda_file',
        'foto_wisuda_status',
        'foto_wisuda_catatan',

        'kartu_ikapode_file',
        'kartu_ikapode_status',
        'kartu_ikapode_catatan',
    ];

    /**
     * Relationship to Alumni
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }

    /**
     * Map of 3 Mandatory Items
     */
    public static function getMandatoryItemsConfig(): array
    {
        return [
            'pengambilan_ijazah' => [
                'title' => 'Pengambilan Ijazah',
                'description' => 'Bukti/Foto Pengambilan Ijazah',
                'file_field' => 'pengambilan_ijazah_file',
                'status_field' => 'pengambilan_ijazah_status',
                'catatan_field' => 'pengambilan_ijazah_catatan',
                'icon' => 'academic-cap'
            ],
            'pengembalian_toga' => [
                'title' => 'Foto Pengembalian Toga bersama Petugas',
                'description' => 'Foto penyerahan/pengembalian toga bersama petugas kampus',
                'file_field' => 'pengembalian_toga_file',
                'status_field' => 'pengembalian_toga_status',
                'catatan_field' => 'pengembalian_toga_catatan',
                'icon' => 'camera'
            ],
            'bank_ijazah' => [
                'title' => 'Screenshot Pengisian Bank Ijazah',
                'description' => 'Bukti/Screenshoot telah melakukan pengisian data pada bank ijazah',
                'file_field' => 'bank_ijazah_file',
                'status_field' => 'bank_ijazah_status',
                'catatan_field' => 'bank_ijazah_catatan',
                'icon' => 'bank'
            ]
        ];
    }

    /**
     * Map of 6 Graduation Document Items (Bagian B: Pengambilan Dokumen Kelulusan)
     */
    public static function getDocumentItemsConfig(): array
    {
        return [
            'foto_ijazah' => [
                'title' => 'Ijazah Asli',
                'description' => 'Dokumen Ijazah Resmi Kelulusan Poltekkes Denpasar',
                'file_field' => 'foto_ijazah_file',
                'status_field' => 'foto_ijazah_status',
                'catatan_field' => 'foto_ijazah_catatan',
                'icon' => 'document'
            ],
            'bukti_transkrip' => [
                'title' => 'Transkrip Nilai',
                'description' => 'Dokumen Transkrip Nilai Akademik Lengkap',
                'file_field' => 'bukti_transkrip_file',
                'status_field' => 'bukti_transkrip_status',
                'catatan_field' => 'bukti_transkrip_catatan',
                'icon' => 'clipboard'
            ],
            'sertifikat_profesi' => [
                'title' => 'Sertifikat Profesi',
                'description' => 'Dokumen Sertifikat Profesi / Uji Kompetensi',
                'file_field' => 'sertifikat_profesi_file',
                'status_field' => 'sertifikat_profesi_status',
                'catatan_field' => 'sertifikat_profesi_catatan',
                'icon' => 'badge'
            ],
            'skpi' => [
                'title' => 'SKPI',
                'description' => 'Surat Keterangan Pendamping Ijazah',
                'file_field' => 'skpi_file',
                'status_field' => 'skpi_status',
                'catatan_field' => 'skpi_catatan',
                'icon' => 'shield'
            ],
            'kartu_ikapode' => [
                'title' => 'Kartu Alumni (IKAPODE)',
                'description' => 'Kartu Keanggotaan Ikatan Alumni Poltekkes Denpasar',
                'file_field' => 'kartu_ikapode_file',
                'status_field' => 'kartu_ikapode_status',
                'catatan_field' => 'kartu_ikapode_catatan',
                'icon' => 'card'
            ],
            'foto_wisuda' => [
                'title' => 'Foto Wisuda',
                'description' => 'Dokumen Foto Resmi Pelaksanaan Wisuda',
                'file_field' => 'foto_wisuda_file',
                'status_field' => 'foto_wisuda_status',
                'catatan_field' => 'foto_wisuda_catatan',
                'icon' => 'user'
            ]
        ];
    }
}
