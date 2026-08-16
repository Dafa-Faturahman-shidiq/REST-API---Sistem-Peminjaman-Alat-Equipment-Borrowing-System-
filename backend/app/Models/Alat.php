<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Alat extends Model
{
    protected $table = 'alat';
    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'stok',
        'status_kondisi',
        'deskripsi',
        'gambar',
    ];

    // fungsi untuk mengatur tipe data dari atribut stok
    protected function casts(): array
    {
        return [
            'stok' => 'integer',
        ];
    }

    // fungsi untuk mengatur relasi antara model Alat dan model Kategori
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    // fungsi untuk mengatur relasi antara model Alat dan model Detail Peminjaman
    public function detailPinjams(): HasMany
    {
        return $this->hasMany(DetailPeminjam::class);
    }
}
