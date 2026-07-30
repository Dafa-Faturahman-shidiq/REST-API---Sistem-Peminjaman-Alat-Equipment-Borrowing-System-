<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    protected $fillable = [
        'user_id',
        'aktivitas',
    ];

    // fungsi untuk mengatur relasi antara model LogAktivitas dan model User
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
