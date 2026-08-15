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
    public function indexAlat(Request $request)
    {

        $keyword = $request->input('search', '');

        if (!empty($keyword)) {
            // 2. Jika ada kata kunci, cari via Elasticsearch lalu paginate (misal 10 data per halaman)
            $alats = Alat::search($keyword)->paginate(10);
            
            // Agar kata kunci pencarian tetap ada di URL saat berpindah halaman pagination
            $alats->appends(['search' => $keyword]);
        } else {
            // 3. Jika tidak mencari, tampilkan data biasa dengan paginasi database
            $alats = Alat::with('kategori')->paginate(10);
        }
        
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
    public function indexUser(Request $request)
    {
        $keyword = $request->input('search', '');

        if (!empty($keyword)) {
            $users = User::where('name', 'like', "%{$keyword}%")->paginate(10);
            $users->appends(['search' => $keyword]);
        } else {
            $users = User::paginate(10);
        }

        return view('admin.user.index', compact('users'));
    }

    // CRUD USER : Menampilkan halaman form untuk membuat user baru
    public function createUser()
    {
        return view('admin.user.create');
    }
}
