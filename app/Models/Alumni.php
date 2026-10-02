<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'nomor_registrasi',
    'nama',
    'nim',
    'nik',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'program_studi',
    'jurusan',
    'tahun_masuk',
    'tahun_lulus',
    'email',
    'no_hp',
    'alamat',
    'status_verifikasi',
    'catatan_admin'
])]
class Alumni extends Model
{
    protected $table = 'alumni';

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    /**
     * Master mapping of Jurusan and Program Studi in Poltekkes Kemenkes Denpasar
     */
    public static function getJurusanProdiMap(): array
    {
        return [
            'Keperawatan' => [
                'D3 Keperawatan',
                'D4/Str Keperawatan',
                'Profesi Ners',
            ],
            'Kebidanan' => [
                'D3 Kebidanan',
                'D4/Str Kebidanan',
                'Profesi Bidan',
            ],
            'Kesehatan Gigi' => [
                'D3 Kesehatan Gigi',
            ],
            'Gizi' => [
                'D3 Gizi',
                'Str Gizi',
                'Dietisien Gizi',
            ],
            'Teknologi Laboratorium Medis' => [
                'D3 TLM',
                'Str TLM',
            ],
            'Kesehatan Lingkungan' => [
                'D3 Sanitasi',
                'Str Kesehatan Lingkungan',
            ],
        ];
    }

    /**
     * Boot function to auto-generate nomor_registrasi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $year = date('Y');
            
            // Find latest record for the current year or matching prefix
            $latest = static::where('nomor_registrasi', 'like', "ALM-{$year}-%")
                ->orderBy('nomor_registrasi', 'desc')
                ->first();

            if ($latest) {
                // Extract sequence number from e.g. ALM-2026-000001
                $parts = explode('-', $latest->nomor_registrasi);
                $sequence = isset($parts[2]) ? intval($parts[2]) : 0;
                $newSequence = $sequence + 1;
            } else {
                $newSequence = 1;
            }

            $model->nomor_registrasi = 'ALM-' . $year . '-' . str_pad($newSequence, 6, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Get the documents associated with the alumnus.
     */
    public function dokumen(): HasOne
    {
        return $this->hasOne(Dokumen::class, 'alumni_id');
    }

    /**
     * Get the certificate distribution details associated with the alumnus.
     */
    public function ijazah(): HasOne
    {
        return $this->hasOne(Ijazah::class, 'alumni_id');
    }

    /**
     * Get the return/verification details (3 mandatory & 6 docs) associated with the alumnus.
     */
    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'alumni_id');
    }
}
