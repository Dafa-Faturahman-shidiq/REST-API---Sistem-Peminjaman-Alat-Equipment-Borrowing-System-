<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\LogAktivitas;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use App\Models\Kategori;


class adminController extends Controller
{
    //* 1. Menampilkan halaman dashboard admin dan log aktivitas
    public function index()
    {
        $logs = LogAktivitas::latest()->take(10)->get();
        return view('admin.dashboard', compact('logs'));
    }


    // ======================= CRUD ALAT =======================
    //* CRUD ALAT : Menampilkan halaman daftar alat
    public function indexAlat(Request $request)
    {
        $search = $request->input('search', '');

        $alats = Alat::with('kategori') // Eager load kategori untuk menghindari N+1 problem
            ->when($search, function ($query, $search) {
                return $query->where('nama_alat', 'like', "%{$search}%")
                             ->orWhereHas('kategori', function ($query) use ($search) {
                                 $query->where('nama_kategori', 'like', "%{$search}%");
                             });
            })
            ->latest()
            ->paginate(10) // Tampilkan 10 data per halaman  
            ->withQueryString(); // Agar query string tetap ada saat berpindah halaman pagination

        return view('admin.alat.index', compact('alats'));
    }

    // * CRUD ALAT : Menampilkan form alat baru
    public function createAlat()
    {
        $kategoris = Kategori::all(); // Ambil semua kategori untuk dropdown
        return view('admin.alat.create', compact('kategoris'));
    }

    //* MENYIMMPAN ALAT : Menyimpan data alat baru ke database
    public function storeAlat(Request $request)
    {

        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer|min:0',
            'status_kondisi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        $data = $request->all();
        
        // handle file upload jika ada
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/alats'), $filename);
            $data['gambar'] = $filename;
        }
            
        Alat::create($data);

        //1. Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menambahkan alat baru: ' . $request->nama_alat,
        ]);

        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    // * CRUD ALAT : Menampilkan halaman form untuk mengedit alat
    public function editAlat($id)
    {
        $alat = Alat::findOrFail($id);
        $kategoris = Kategori::all(); // Ambil semua kategori untuk dropdown
        return view('admin.alat.edit', compact('alat', 'kategoris'));
    }

    // * CRUD ALAT : Menyimpan perubahan data alat ke database
    public function updateAlat(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer|min:0',
            'status_kondisi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        $data = $request->all();

        // handle file upload jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($alat->gambar && file_exists(public_path('storage/alats/' . $alat->gambar))) {
                unlink(public_path('storage/alats/' . $alat->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/alats'), $filename);
            $data['gambar'] = $filename;
        }

        $alat->update($data);

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Mengupdate alat: ' . $alat->nama_alat,
        ]);

        return redirect()->route('admin.alat.index')->with('success', 'Data alat berhasil diperbarui.');
    }

    // * CRUD ALAT : Menghapus alat dari database
    public function destroyAlat($id)
    {
        $alat = Alat::findOrFail($id);

        // Hapus file gambar fisik jika ada
        if ($alat->gambar && file_exists(public_path('storage/alats/' . $alat->gambar))) {
            unlink(public_path('storage/alats/' . $alat->gambar));
        }

        $alat->delete();

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menghapus alat: ' . $alat->nama_alat,
        ]);

        return redirect()->route('admin.alat.index')->with('success', 'Data alat berhasil dihapus.');
    }

    // ======================= CRUD USER =======================

    //* CRUD USER : Menampilkan halaman daftar user
    public function indexUser(Request $request)
    {
        $search = $request->input('search', '');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10) // Tampilkan 10 data per halaman  
            ->withQueryString(); // Agar query string tetap ada saat berpindah halaman pagination

        return view('admin.user.index', compact('users'));
    }

    //* CRUD USER : Menampilkan halaman form untuk membuat user baru
    public function createUser()
    {
        return view('admin.user.create');
    }

    //* CRUD USER : Menyimpan data user baru ke database
    public function storeUser(Request $request)
    {
        //1. Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        //2. Menyimpan data user baru ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ]);

        //3. Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menambahkan user baru: ' . $request->name,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    // * CRUD USER : Menampilkan halaman form untuk mengedit user
    public function editUser($id)
    {
        // 1. Ambil data user berdasarkan ID
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user')); // 2. Tampilkan halaman edit user dengan data user yang diambil
    }

    // * CRUD USER : Menyimpan perubahan data user ke database
    public function updateUser(Request $request, $id)
    {
        // 1. Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // 2. Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id, // Pastikan email unik kecuali untuk user yang sedang diedit
            'role' => 'required|in:admin,petugas,peminjam',
            'no_hp' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // 3. Update data user di database
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ];

        // 4. Jika password diisi, maka hash password baru dan simpan
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // 5. Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Mengupdate user: ' . $user->name,
        ]);

        // 6. Update data user
        $user->update($data);

        // 7. Redirect kembali ke halaman daftar user dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    // * CRUD USER : Menghapus user dari database
    public function destroyUser($id)
    {
        // 1. Ambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // 2. Hapus user dari database
        $user->delete();

        // 3. Catat log aktivitas sebelum menghapus user
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menghapus user: ' . $user->name,
        ]);

        // 4. Redirect kembali ke halaman daftar user dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }

    // ======================= CRUD KATEGORI =======================

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

        // 3. Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menambahkan kategori baru: ' . $request->nama_kategori,
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

        // 4. Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Mengupdate kategori: ' . $kategori->nama_kategori,
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

        // 3. Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menghapus kategori: ' . $kategori->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
