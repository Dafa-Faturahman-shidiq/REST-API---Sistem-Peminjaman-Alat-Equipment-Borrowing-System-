<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\LogAktivitas;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use App\Models\Kategori;
use App\Models\DetailPinjam;


class adminController extends Controller
{
    //* 1. Menampilkan halaman dashboard admin dan log aktivitas
    public function index()
    {
        // Mengambil data untuk ringkasan dashboard
        $total_alat = Alat::count(); 
        $stok_tersedia = Alat::sum('stok'); 

        // Status disesuaikan dengan enum/kolom di database Anda
        $sedang_dipinjam = Peminjaman::where('status', 'dipinjam')->count(); 
        $menunggu_persetujuan = Peminjaman::where('status', 'diajukan')->count();

        // Mengambil log aktivitas
        $logs = LogAktivitas::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'total_alat', 
            'stok_tersedia', 
            'sedang_dipinjam', 
            'menunggu_persetujuan',
            'logs'
        ));
    }
 }
