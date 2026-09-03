<?php

namespace App\Providers;

// MODELS
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;

// OBSERVER
use App\Observers\PeminjamanObserver;
use App\Observers\PengembalianObserver;
use App\Observers\AlatObserver;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Peminjaman::observe(PeminjamanObserver::class);
        Pengembalian::observe(PengembalianObserver::class);
        Alat::observe(AlatObserver::class);
    }
}
