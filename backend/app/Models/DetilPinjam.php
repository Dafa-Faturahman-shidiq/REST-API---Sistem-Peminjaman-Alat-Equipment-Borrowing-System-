<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetilPinjam extends Model
{
    protected $table = 'detil_pinjam';
    protected $fillable = [
        'peminjam_id',
        'alat_id',
        'jumlah',
    ];

    // fungsi untuk mengatur tipe data dari atribut jumlah
    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
        ];
    }

    // fungsi untuk mengatur relasi antara model DetilPinjam dan model Peminjaman
    public function peminjaman(): BelongsTo {
        return $this->belongsTo(Peminjaman::class);
    }

    // fungsi untuk mengatur relasi antara model DetilPinjam dan model Alat
    public function alat(): BelongsTo {
        return $this->belongsTo(Alat::class);
    }

}
