<?php

use Illuminate\Support\Facades\Route;

// CONTROLLER ADMIN
use App\Http\Controllers\WEB\admin\AdminController;
use App\Http\Controllers\WEB\admin\AlatController;
use App\Http\Controllers\WEB\admin\UserController;
use App\Http\Controllers\WEB\admin\KategoriController;
use App\Http\Controllers\WEB\admin\PeminjamanController;
use App\Http\Controllers\WEB\admin\PengembalianController;

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
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // CRUD Alat (Rute statis /create diletakkan DI ATAS rute berparameter {id})
    Route::get('/alat', [AlatController::class, 'indexAlat'])->name('alat.index');
    Route::get('/alat/create', [AlatController::class, 'createAlat'])->name('alat.create');
    Route::post('/alat', [AlatController::class, 'storeAlat'])->name('alat.store');
    Route::get('/alat/{id}/edit', [AlatController::class, 'editAlat'])->name('alat.edit');
    Route::put('/alat/{id}', [AlatController::class, 'updateAlat'])->name('alat.update');
    Route::delete('/alat/{id}', [AlatController::class, 'destroyAlat'])->name('alat.destroy');

    // CRUD User \
    Route::get('/users', [UserController::class, 'indexUser'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'createUser'])->name('users.create');
    Route::post('/users', [UserController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroyUser'])->name('users.destroy');

    // CRUD Kategori 
    Route::get('/kategori', [KategoriController::class, 'indexKategori'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'createKategori'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'storeKategori'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'editKategori'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'updateKategori'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroyKategori'])->name('kategori.destroy');

    // CRUD PEMINJAMAN 
    Route::get('/peminjaman', [PeminjamanController::class, 'indexPeminjaman'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'createPeminjaman'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::put('/peminjaman/{id}/status', [PeminjamanController::class, 'updateStatusPeminjaman'])->name('peminjaman.updateStatus');
    Route::post('/peminjaman/{id}/tolak', [PeminjamanController::class, 'tolakPeminjaman'])->name('peminjaman.tolak');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroyPeminjaman'])->name('peminjaman.destroy');

    // CRUD Pengembalian
    Route::get('/pengembalian', [PengembalianController::class, 'indexPengembalian'])->name('pengembalian.index');
    Route::get('/peminjaman/{id}/kembali', [PengembalianController::class, 'createPengembalian'])->name('peminjaman.kembali');
    Route::post('/pengembalian/{id}', [PengembalianController::class, 'storePengembalian'])->name('pengembalian.store');
    Route::delete('/pengembalian/{id}', [PengembalianController::class, 'destroyPengembalian'])->name('pengembalian.destroy');
});

// * ROUTES UNTUK PETUGAS
Route::middleware(['auth', 'role:petugas,admin'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/peminjaman', [petugasController::class, 'indexPeminjaman'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/setujui', [petugasController::class, 'setujuiPeminjaman'])->name('peminjaman.setujui');
    Route::post('/peminjaman/{id}/tolak', [petugasController::class, 'tolakPeminjaman'])->name('peminjaman.tolak');

    Route::get('/pengembalian', [petugasController::class, 'indexPengembalian'])->name('pengembalian.index');
    Route::post('/pengembalian/{id}', [petugasController::class, 'prosesPengembalian'])->name('pengembalian.proses');

    Route::get('/laporan', [petugasController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan/cetak', [petugasController::class, 'cetakLaporan'])->name('laporan.cetak');
});

//* PEMINJAM
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/katalog', [PeminjamanController::class, 'indexKatalog'])->name('katalog.index');
    Route::post('/peminjaman/ajukan', [PeminjamanController::class, 'ajukanPeminjaman'])->name('peminjaman.ajukan');
    Route::get('/riwayat', [PeminjamanController::class, 'riwayatPeminjaman'])->name('riwayat.index');
});