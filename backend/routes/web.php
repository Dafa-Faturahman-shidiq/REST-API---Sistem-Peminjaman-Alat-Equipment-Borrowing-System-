<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WEB\adminController;
use App\Http\Controllers\WEB\petugasController;
use App\Http\Controllers\WEB\peminjamController;

use App\Http\Controllers\WEB\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// * ROUTES UNTUK AUTHENTIKASI
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// * ROUTES UNTUK LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//* ROUTES UNTUK ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [adminController::class, 'index'])->name('dashboard');

    // CRUD Alat
    Route::get('/alat', [adminController::class, 'indexAlat'])->name('alat.index');
    Route::post('/alat', [adminController::class, 'storeAlat'])->name('alat.store');

    // CRUD User
    Route::get('/users', [adminController::class, 'indexUser'])->name('users.index');
});

// * ROUTES UNTUK PETUGAS
Route::middleware(['auth', 'role:petugas,admin'])->prefix('petugas')->name('petugas.')->group(function () {
    // * PEMINJAMAN DAN PERSETUJUAN
    Route::get('/peminjaman', [petugasController::class, 'indexPeminjaman'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/setujui', [petugasController::class, 'setujuiPeminjaman'])->name('peminjaman.setujui');
    
    // * PENGEMBALIAN & DENDA
    Route::post('/pengembalian/{id}', [petugasController::class, 'prosesPengembalian'])->name('pengembalian.proses');
});

//* PEMINJAM
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    // * KATALOG & PEMINJAMAN
    Route::get('/katalog', [peminjamController::class, 'indexKatalog'])->name('katalog.index');
    Route::post('/peminjaman/ajukan', [peminjamController::class, 'ajukanPeminjaman'])->name('peminjaman.ajukan');

    // * RIWAYAT
    Route::get('/riwayat', [peminjamController::class, 'riwayatPeminjaman'])->name('riwayat.index');
});