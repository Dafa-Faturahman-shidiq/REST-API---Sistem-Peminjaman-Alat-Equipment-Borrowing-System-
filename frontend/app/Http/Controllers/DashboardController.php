<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Pengecekan keamanan: Jika belum ada token, lempar balik ke halaman login
        if (!session()->has('api_token')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('dashboard.index');
    }
}
