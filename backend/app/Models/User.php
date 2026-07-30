<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
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

}
