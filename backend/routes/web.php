<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WEB\adminController;
use App\Http\Controllers\WEB\petugasController;
use App\Http\Controllers\WEB\peminjamController;
use App\Http\Controllers\WEB\kategoriController;

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
    Route::get('/alat/create', [adminController::class, 'createAlat'])->name('alat.create');
    Route::get('/alat/{id}/edit', [adminController::class, 'editAlat'])->name('alat.edit');
    Route::put('/alat/{id}', [adminController::class, 'updateAlat'])->name('alat.update');
    Route::delete('/alat/{id}', [adminController::class, 'destroyAlat'])->name('alat.destroy');

    // CRUD User
    Route::get('/users', [adminController::class, 'indexUser'])->name('users.index');
    Route::get('/users/create', [adminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [adminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [adminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [adminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [adminController::class, 'destroyUser'])->name('users.destroy');

    // CRUD Kategori
    Route::get('/kategori', [adminController::class, 'indexKategori'])->name('kategori.index');
    Route::get('/kategori/create', [adminController::class, 'createKategori'])->name('kategori.create');
    Route::post('/kategori', [adminController::class, 'storeKategori'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [adminController::class, 'editKategori'])->name('kategori.edit');
    Route::put('/kategori/{id}', [adminController::class, 'updateKategori'])->name('kategori.update');
    Route::delete('/kategori/{id}', [adminController::class, 'destroyKategori'])->name('kategori.destroy');

    // CRUD PEMINJAMAN
    Route::get('/peminjaman', [adminController::class, 'indexPeminjaman'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [adminController::class, 'createPeminjaman'])->name('peminjaman.create');
    Route::post('/peminjaman', [adminController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::put('/peminjaman/{id}/status', [adminController::class, 'updateStatusPeminjaman'])->name('peminjaman.updateStatus');
    Route::delete('/peminjaman/{id}', [adminController::class, 'destroyPeminjaman'])->name('peminjaman.destroy');
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