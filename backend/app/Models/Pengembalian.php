<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';
    protected $fillable = [
        'peminjam_id',
        'tgl_kembali',
        'kondisi_kembali',
        'denda',
        'petugas_id'
    ];

    // fungsi untuk mengatur tipe data dari atribut tgl_kembali dan denda
    protected function casts(): array
    {
        return [
            'tgl_kembali' => 'date:Y-m-d',
            'denda' => 'integer',
        ];
    }

    // fungsi untuk mengatur relasi antara model Pengembalian dan model Peminjaman
    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    // fungsi untuk mengatur relasi antara model Pengembalian dan model User (petugas)
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
