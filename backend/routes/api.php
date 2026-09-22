<?php

use App\Http\Controllers\API\AlatController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\PeminjamanController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PengembalianController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 0. ROUTE PUBLIC (Tanpa Login / Bebas Akses)
// ==========================================
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // ==========================================
    // 1. ROUTE UMUM (Semua Role yang Login)
    // ==========================================
    Route::get('/me', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/katalog', [AlatController::class, 'katalog']); // Katalog umum
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show']); // Detail transaksi (Auth check di Controller)

    // ==========================================
    // 2. ROLE PEMINJAM (Pengajuan & Riwayat)
    // ==========================================
    Route::middleware('role.peminjam')->group(function () {
        Route::post('/peminjaman', [PeminjamanController::class, 'store']);
        Route::get('/riwayat-pinjam', [PeminjamanController::class, 'riwayat']);
        Route::put('/peminjaman/{peminjaman}', [PeminjamanController::class, 'update']);
        Route::delete('/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy']);
    });

    // ==========================================
    // 3. ROLE PETUGAS (Verifikasi & Peminjaman)
    // ==========================================
    Route::middleware('role.petugas')->group(function () {
        Route::get('/petugas/users', [AuthController::class, 'getAllUsers']);
        Route::get('/petugas/peminjaman', [PeminjamanController::class, 'index']); // Untuk melihat daftar pengajuan
        Route::post('/petugas/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve']);
        Route::post('/pengembalian', [PengembalianController::class, 'store']);
    });

    // ==========================================
    // 4. ROLE ADMIN (Master Data & Full Management)
    // ==========================================
    Route::middleware('role.admin')->group(function () {
        // ROUTE KATEGORI, ALAT, USERS
        Route::apiResource('kategori', KategoriController::class);
        Route::apiResource('alat', AlatController::class);
        Route::apiResource('users', UserController::class);

        // ROUTE PEMINJAMAN ADMIN
        Route::get('/admin/peminjaman', [PeminjamanController::class, 'index']);
        Route::post('/admin/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve']);
        Route::put('/admin/peminjaman/{peminjaman}', [PeminjamanController::class, 'update']);
        Route::delete('/admin/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy']);

        // ROUTE PENGEMBALIAN
        Route::get('/pengembalian', [PengembalianController::class, 'index']);
        Route::get('/pengembalian/{pengembalian}', [PengembalianController::class, 'show']);
        Route::put('/pengembalian/{pengembalian}', [PengembalianController::class, 'update']);
        Route::delete('/pengembalian/{pengembalian}', [PengembalianController::class, 'destroy']);
    });

});