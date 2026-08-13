<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LogAktivitas;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;


class adminController extends Controller
{
    // 1. Menampilkan halaman dashboard admin dan log aktivitas
    public function index()
    {
        $logs = LogAktivitas::latest()->take(10)->get();
        return view('admin.dashboard', compact('logs'));
    }

    // CRUD ALAT : Menampilkan halaman daftar alat
    public function indexAlat()
    {
        $alats = Alat::with('kategori')->get();
        return view('admin.alat.index', compact('alats'));
    }

    // MENYIMMPAN ALAT : Menyimpan data alat baru ke database
    public function storeAlat(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'nama_alat' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'status_kondisi' => 'required|string|max:255',
        ]);

        Alat::create($request->all());

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menambahkan alat baru: ' . $request->nama_alat,
        ]);

        return redirect()->back()->with('success', 'Alat berhasil ditambahkan.');
    }

    // CRUD USER : Menampilkan halaman daftar user
    public function indexUser()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }
}
