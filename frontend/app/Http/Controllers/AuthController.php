<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

    //* Menampilkan halaman form register
    public function showRegister()
    {
        return view('auth.register');
    }

    //* Memproses data pendaftaran dan menembak API backend
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        // Tembak endpoint /api/register di backend Docker
        $response = Http::post(env('API_BASE_URL') . '/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        if ($response->successful()) {
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
        }

        return back()->withErrors([
            'error' => $response->json()['message'] ?? 'Terjadi kesalahan saat registrasi.'
        ])->withInput();
    }

    //* Menampilkan halaman form login
    public function showLogin()
    {
        return view('auth.login');
    }

    //* Memproses data form login dan menembak API backend
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //* Tembak endpoint /api/login di backend Docker (app-laravel:8000)
        $response = Http::post(env('API_BASE_URL') . '/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $result = $response->json();

            //* Simpan access_token dan data user ke Session frontend
            session([
                'api_token' => $result['access_token'] ?? null,
                'user_data' => $result['data'] ?? null,
            ]);

            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        }

        // Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'error' => $response->json()['message'] ?? 'Email atau password salah.'
        ])->withInput();
    }

    //* Memproses logout
    public function logout()
    {
        $token = session('api_token');
        
        //* Tembak endpoint /api/logout backend jika token ada
        if ($token) {
            Http::withToken($token)->post(env('API_BASE_URL') . '/logout');
        }

        //* Hapus semua data session di frontend
        session()->flush();

        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }
}