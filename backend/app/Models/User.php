<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Laravel\Scout\Searchable; // 1. Import Scout Searchable

class User extends Authenticatable
{
    use Searchable; // 2. Gunakan trait Searchable di sini
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'no_hp', 
        'alamat',
        'foto_profil',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // fungsi untuk mengatur tipe data dari atribut password
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // 3. Tentukan kolom apa saja yang masuk ke index pencarian Elasticsearch
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'nama_alat' => $this->nama_alat,
            'status_kondisi' => $this->status_kondisi,
        ];
    }

    // fungsi untuk mengatur relasi antara model User dan model Peminjaman
    public function peminjaman() 
    {
        return $this->hasMany(Peminjaman::class);
    }

    // fungsi untuk mengatur relasi antara model User dan model logAktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    // fungsi untuk scope query untuk mengambil data alat yang tersedia (stok > 0 dan status_kondisi = 'baik')
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0)->where('status_kondisi', 'baik');
    }
}
