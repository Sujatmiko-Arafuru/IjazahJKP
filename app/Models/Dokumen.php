<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    protected $table = 'dokumen';

    protected $fillable = [
        'alumni_id',
        'pas_foto',
        'pas_foto_status',
        'pas_foto_catatan',
        'berkas_persyaratan',
        'berkas_persyaratan_status',
        'berkas_persyaratan_catatan',
        'drive_link',
        'drive_link_status',
        'drive_link_catatan',
        'tracer_study',
        'tracer_study_status',
        'tracer_study_catatan',
        'surat_bebas_pustaka',
        'surat_bebas_pustaka_status',
        'surat_bebas_pustaka_catatan',
        'keabsahan_data',
        'keabsahan_data_status',
        'keabsahan_data_catatan',
        'pengembalian_toga',
        'pengembalian_toga_status',
        'pengembalian_toga_catatan',
        'bank_ijazah',
        'bank_ijazah_status',
        'bank_ijazah_catatan',
        'tracer_kemenkes',
        'tracer_poltekkes'
    ];

    /**
     * Relationship to Alumni
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }

    /**
     * Map of Verification Documents / Items (Bagian A)
     */
    public static function getBerkasConfig(): array
    {
        return [
            'pas_foto' => [
                'title' => 'Pas Foto Resmi Alumni',
                'description' => 'Pas foto resmi terbaru (latar merah/biru disarankan, Maks 1 MB)',
                'file_field' => 'pas_foto',
                'status_field' => 'pas_foto_status',
                'catatan_field' => 'pas_foto_catatan',
                'format' => 'Foto (JPG/PNG)',
                'is_link' => false,
            ],
            'berkas_persyaratan' => [
                'title' => '5 Berkas Persyaratan (1 File PDF)',
                'description' => 'Satu file PDF gabungan 5 berkas persyaratan (Tracer Study, Bebas Pustaka, Keabsahan Data, Pengembalian Toga, Bank Ijazah, Maks 1 MB)',
                'file_field' => 'berkas_persyaratan',
                'status_field' => 'berkas_persyaratan_status',
                'catatan_field' => 'berkas_persyaratan_catatan',
                'format' => 'Dokumen (PDF)',
                'is_link' => false,
            ],
        ];
    }
}
