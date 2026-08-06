<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AlatController;

// 1. Arahkan halaman utama (root) langsung ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route Autentikasi (Login & Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');

// Route Registrasi
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.proses');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Route Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 4. Route Kategori (Akan kita gunakan nanti untuk CRUD Kategori via API)
// Route::resource('kategori', KategoriController::class);

// 5. Route Alat (Akan kita gunakan nanti untuk CRUD Alat via API)
// Route::resource('alat', AlatController::class);