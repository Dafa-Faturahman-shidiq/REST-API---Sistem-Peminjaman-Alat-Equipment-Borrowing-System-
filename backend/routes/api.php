<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

// Routes untuk API AuthController
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Route untuk mendapatkan informasi user yang sedang login
    Route::get('/me', [AuthController::class, 'me']);
    // Route untuk logout user
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        // Route untuk mendapatkan daftar semua user (hanya untuk admin)
        Route::get('/users', [AuthController::class, 'getAllUsers']);
    });

    Route::middleware(['role:petugas'])->group(function () {
        // Route untuk mendapatkan daftar semua user (hanya untuk petugas)
        Route::get('/users', [AuthController::class, 'getAllUsers']);
    });

    Route::middleware('role:peminjam')->group(function () {
        // Route untuk mendapatkan informasi user yang sedang login (hanya untuk peminjam)
        Route::get('/user', [AuthController::class, 'getUser']);
    });
});

