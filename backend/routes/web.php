<?php

use Illuminate\Support\Facades\Route;

// CONTROLLER ADMIN
use App\Http\Controllers\WEB\admin\adminController;
use App\Http\Controllers\WEB\admin\alatController;
use App\Http\Controllers\WEB\admin\userController;
use App\Http\Controllers\WEB\admin\kategoriController;
use App\Http\Controllers\WEB\admin\peminjamanController;
use App\Http\Controllers\WEB\admin\pengembalianController;

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

    // CRUD Alat (Rute statis /create diletakkan DI ATAS rute berparameter {id})
    Route::get('/alat', [alatController::class, 'indexAlat'])->name('alat.index');
    Route::get('/alat/create', [alatController::class, 'createAlat'])->name('alat.create');
    Route::post('/alat', [alatController::class, 'storeAlat'])->name('alat.store');
    Route::get('/alat/{id}/edit', [alatController::class, 'editAlat'])->name('alat.edit');
    Route::put('/alat/{id}', [alatController::class, 'updateAlat'])->name('alat.update');
    Route::delete('/alat/{id}', [alatController::class, 'destroyAlat'])->name('alat.destroy');

    // CRUD User \
    Route::get('/users', [userController::class, 'indexUser'])->name('users.index');
    Route::get('/users/create', [userController::class, 'createUser'])->name('users.create');
    Route::post('/users', [userController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [userController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [userController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [userController::class, 'destroyUser'])->name('users.destroy');

    // CRUD Kategori 
    Route::get('/kategori', [kategoriController::class, 'indexKategori'])->name('kategori.index');
    Route::get('/kategori/create', [kategoriController::class, 'createKategori'])->name('kategori.create');
    Route::post('/kategori', [kategoriController::class, 'storeKategori'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [kategoriController::class, 'editKategori'])->name('kategori.edit');
    Route::put('/kategori/{id}', [kategoriController::class, 'updateKategori'])->name('kategori.update');
    Route::delete('/kategori/{id}', [kategoriController::class, 'destroyKategori'])->name('kategori.destroy');

    // CRUD PEMINJAMAN 
    Route::get('/peminjaman', [peminjamanController::class, 'indexPeminjaman'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [peminjamanController::class, 'createPeminjaman'])->name('peminjaman.create');
    Route::post('/peminjaman', [peminjamanController::class, 'storePeminjaman'])->name('peminjaman.store');
    Route::put('/peminjaman/{id}/status', [peminjamanController::class, 'updateStatusPeminjaman'])->name('peminjaman.updateStatus');
    Route::post('/peminjaman/{id}/tolak', [peminjamanController::class, 'tolakPeminjaman'])->name('peminjaman.tolak');
    Route::delete('/peminjaman/{id}', [peminjamanController::class, 'destroyPeminjaman'])->name('peminjaman.destroy');

    // CRUD Pengembalian
    Route::get('/pengembalian', [pengembalianController::class, 'indexPengembalian'])->name('pengembalian.index');
    Route::get('/peminjaman/{id}/kembali', [pengembalianController::class, 'createPengembalian'])->name('peminjaman.kembali');
    Route::post('/pengembalian/{id}', [pengembalianController::class, 'storePengembalian'])->name('pengembalian.store');
    Route::delete('/pengembalian/{id}', [pengembalianController::class, 'destroyPengembalian'])->name('pengembalian.destroy');
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
    Route::get('/katalog', [peminjamController::class, 'indexKatalog'])->name('katalog.index');
    Route::post('/peminjaman/ajukan', [peminjamController::class, 'ajukanPeminjaman'])->name('peminjaman.ajukan');
    Route::get('/riwayat', [peminjamController::class, 'riwayatPeminjaman'])->name('riwayat.index');
});