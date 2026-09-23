<?php

namespace App\Http\Controllers\WEB\admin;

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


class UserController extends Controller
{
    //! ======================= CRUD USER =======================

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

        // 4. Redirect kembali ke halaman daftar user dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
