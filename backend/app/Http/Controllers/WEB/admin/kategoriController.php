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

class kategoriController extends Controller
{
       //! ======================= CRUD KATEGORI =======================

    // * CRUD KATEGORI : Menampilkan halaman daftar kategori
    public function indexKategori(Request $request)
    {
        $search = $request->input('search', '');

        $kategoris = Kategori::when($search, function ($query, $search) {
            return $query->where('nama_kategori', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(5) // Tampilkan 5 data per halaman  
            ->withQueryString(); // Agar query string tetap ada saat berpindah halaman pagination

        return view('admin.kategori.index', compact('kategoris'));
    }

    // * CRUD KATEGORI : Menampilkan halaman form untuk membuat kategori baru
    public function createKategori()
    {
        return view('admin.kategori.create');
    }

    // * CRUD KATEGORI : Menyimpan data kategori baru ke database
    public function storeKategori(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori',
        ]);

        // 2. Menyimpan data kategori baru ke database
        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // * CRUD KATEGORI : Menampilkan halaman form untuk mengedit kategori
    public function editKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    // * CRUD KATEGORI : Menyimpan perubahan data kategori ke database
    public function updateKategori(Request $request, $id)
    {
        // 1. Ambil data kategori berdasarkan ID
        $kategori = Kategori::findOrFail($id);

        // 2. Validasi input dari form
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id,
        ]);

        // 3. Update data kategori di database
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // * CRUD KATEGORI : Menghapus kategori dari database
    public function destroyKategori($id)
    {
        // 1. Ambil data kategori berdasarkan ID
        $kategori = Kategori::findOrFail($id);

        // 2. Hapus data kategori dari database
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
