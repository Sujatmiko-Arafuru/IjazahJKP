<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'alumni_id',
    'status',
    'tanggal_siap',
    'tanggal_diambil',
    'lokasi_pengambilan',
    'jam_operasional',
    'keterangan'
])]
class Ijazah extends Model
{
    protected $table = 'ijazah';

    protected function casts(): array
    {
        return [
            'tanggal_siap' => 'date',
            'tanggal_diambil' => 'date',
        ];
    }

    /**
     * Get the alumnus that owns the certificate.
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class, 'alumni_id');
    }
}
