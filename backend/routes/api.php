<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\AlatController;
use App\Http\Controllers\API\UserController;

// Routes untuk API AuthController
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Route untuk mendapatkan informasi user yang sedang login
    Route::get('/me', [AuthController::class, 'me']);
    // Route untuk logout user
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role.admin')->group(function () {
        // Route untuk resource kategori 
        Route::apiResource('kategori', KategoriController::class);
        // Route untuk resource alat
        Route::apiResource('alat', AlatController::class);
        // Route untuk katalog alat
        Route::get('/katalog',[AlatController::class, 'katalog']);
        // Route untuk resource user
        Route::apiResource('users', UserController::class);
    });

    Route::middleware(['role.petugas'])->group(function () {
        // Route untuk mendapatkan daftar semua user (hanya untuk petugas)
        Route::get('/petugas/users', [AuthController::class, 'getAllUsers']);
    });

    Route::middleware('role.peminjam')->group(function () {
        // Route untuk mendapatkan informasi user yang sedang login (hanya untuk peminjam)
        Route::get('/peminjam/profile', [AuthController::class, 'getUser']);
        // Route untuk katalog alat
        Route::get('/katalog', [AlatController::class, 'katalog']);
    });
});

