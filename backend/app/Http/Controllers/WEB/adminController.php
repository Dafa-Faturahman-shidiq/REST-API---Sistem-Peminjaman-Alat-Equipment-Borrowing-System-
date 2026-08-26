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


    //! ======================= CRUD ALAT =======================
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

    //! ======================= CRUD PEMINJAMAN =======================

    // * CRUD PEMINJAMAN : Menampilkan halaman daftar peminjaman
    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search', '');

        $peminjamans = Peminjaman::with('user', 'detailPinjams.alat') 
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })->orWhereHas('detailPinjams.alat', function ($query) use ($search) { // PERBAIKAN DI SINI
                    $query->where('nama_alat', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.peminjaman.index', compact('peminjamans', 'search'));
    }

    // * CRUD PEMINJAMAN : Menampilkan halaman form untuk membuat peminjaman baru
    public function createPeminjaman()
    {
        $users = User::where('role', 'peminjam')->get(); // Ambil semua user dengan role peminjam
        $alats = Alat::where('stok', '>', 0)->get();
        return view('admin.peminjaman.create', compact('users', 'alats'));
    }

    // * CRUD PEMINJAMAN : Menyimpan data peminjaman baru ke database
    public function storePeminjaman(Request $request)
    {
        // 1. Validasi disesuaikan dengan nama input di form Blade
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_plan' => 'required|date|after_or_equal:tgl_pinjam',
            'alat_id' => 'required|array',
            'alat_id.*' => 'required|exists:alat,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 2. Buat transaksi peminjaman baru (sesuai nama kolom form: tgl_pinjam)
            $peminjaman = Peminjaman::create([
                'user_id' => $request->user_id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan', // Status awal peminjaman
            ]);

            // 3. Simpan detail alat yang dipinjam (Looping array dari name="alat_id[]" dan name="jumlah[]")
            foreach ($request->alat_id as $index => $alat_id) {
                $jumlah_pinjam = $request->jumlah[$index];

                $alat = Alat::findOrFail($alat_id);

                // Validasi stok alat
                if ($alat->stok < $jumlah_pinjam) {
                    throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi (Sisa stok: {$alat->stok}).");
                }

                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alat_id,
                    'jumlah' => $jumlah_pinjam,
                ]);
            }

            // 4. PERBAIKAN UTAMA: Commit dan Redirect diletakkan di LUAR looping foreach!
            DB::commit();

            return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil ditambahkan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // * CRUD PEMINJAMAN : // 4. Memperbarui status peminjaman (Misal: dari diajukani ke disetujui, dipinjam, dikembalikan, dll.)
    public function updateStatusPeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detailPinjams.alat')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:diajukan,dipinjam,selesai,telat'
        ]);

        DB::beginTransaction();

        try {
            $statuslama= $peminjaman->status;
            $statusbaru= $request->status;

            // logika pengelolaan stock otomatis 
            if ($statuslama != 'dipinjam' && $statusbaru == 'dipinjam') {

                // kurangi stock karena barang resmi di pinjam
                foreach ($peminjaman->detailPinjams as $detail) {
                    $alat = $detail->alat;

                    if ($alat->stok < $detail->jumlah) {
                        throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi.");
                    }

                   $alat->decrement('stok', $detail->jumlah);
                }
            } elseif ($statuslama == 'dipinjam' && ($statusbaru === 'selesai' || $statusbaru === 'telat')) {
                // Kembalikan stok karena barang sudah dikembalikan (selesai)
                foreach ($peminjaman->detailPinjams as $detail) {
                    $detail->alat->increment('stok', $detail->jumlah);
                }
            }  
            
            $peminjaman->update(['status' => $statusbaru]);

            DB::commit();
            return redirect()->route('admin.peminjaman.index')->with('success', 'Status peminjaman berhasil diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    //* CRUD PEMIJAMAM Menghapus data peminjaman

    public function destroyPeminjaman($id)
    {
        $peminjaman = Peminjaman::with('detailPinjams')->findOrFail($id);

        // Jika statusnya sedang dipinjam, kembalikan stok terlebih dahulu sebelum dihapus
        if ($peminjaman->status == 'dipinjam') {
            foreach ($peminjaman->detailPinjams as $detail) {
                $detail->alat->increment('stok', $detail->jumlah);
            };
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    // * KELOLA PENGEMBALIAN : Menampilkan halaman utama pengembalian
    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search', '');

        // 1. Ambil data riwayat pengembalian yang sudah selesai diproses
        $pengembalians = Pengembalian::with('peminjaman.user', 'petugas', 'peminjaman.detailPinjams.alat')
            ->when($search, function ($query, $search) {
                return $query->whereHas('peminjaman.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('kondisi_kembali', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // 2. Ambil daftar peminjaman yang statusnya sedang 'dipinjam' agar admin tahu barang apa saja yang belum kembali
        $peminjamanDipinjam = Peminjaman::with('user', 'detailPinjams.alat')
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        return view('admin.pengembalian.index', compact('pengembalians', 'peminjamanDipinjam', 'search'));
    }

    // * PENGEMBALIAN : Menampilkan form pengembalian alat
    public function createPengembalian($id)
    {
        $peminjaman = Peminjaman::with('user', 'detailPinjams.alat')->findOrFail($id);
        
        // Pastikan hanya peminjaman yang berstatus 'dipinjam' yang bisa dikembalikan
        if ($peminjaman->status != 'dipinjam') {
            return redirect()->route('admin.peminjaman.index')->with('error', 'Peminjaman ini tidak dalam status dipinjam.');
        }

        return view('admin.pengembalian.create', compact('peminjaman'));
    }

   // * PENGEMBALIAN : Memproses data pengembalian alat
    public function storePengembalian(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detailPinjams.alat')->findOrFail($id);

        // 1. Validasi Input 
        $request->validate([
            'tgl_kembali' => 'required|date',
            'kondisi_kembali' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'deskripsi' => 'nullable|string',
        ]);

        // 2. HITUNG DENDA KETERLAMBATAN (Ambil format Y-m-d agar jam diabaikan)
        $tgl_kembali_aktual = \Carbon\Carbon::parse($request->tgl_kembali)->format('Y-m-d');
        $tgl_kembali_plan = \Carbon\Carbon::parse($peminjaman->tgl_kembali_plan)->format('Y-m-d');
        
        $carbon_aktual = \Carbon\Carbon::parse($tgl_kembali_aktual);
        $carbon_plan = \Carbon\Carbon::parse($tgl_kembali_plan);
        
        $denda_telat = 0;
        
        if ($carbon_aktual->gt($carbon_plan)) {
            $hari_terlambat = $carbon_plan->diffInDays($carbon_aktual);
            $denda_telat = $hari_terlambat * 2000; // Rp 2.000 per hari
        }

        // 3. HITUNG DENDA KONDISI (Kerusakan/Kehilangan)
        $denda_kondisi = 0;
        switch ($request->kondisi_kembali) {
            case 'rusak_ringan': $denda_kondisi = 20000; break;
            case 'rusak_berat':  $denda_kondisi = 50000; break;
            case 'hilang':       $denda_kondisi = 100000; break;
            default:             $denda_kondisi = 0; break; // Kondisi 'baik'
        }

        // 4. AKUMULASI TOTAL DENDA
        $total_denda = $denda_telat + $denda_kondisi;

        DB::beginTransaction();

        try {
            // Simpan ke database
            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'petugas_id' => auth()->id(),
                'tgl_kembali' => $request->tgl_kembali,
                'kondisi_kembali' => $request->kondisi_kembali,
                'deskripsi' => $request->deskripsi, // Simpan deskripsi
                'denda' => $total_denda, // Simpan total denda final
            ]);

            $peminjaman->update(['status' => 'dikembalikan']);

            // Kembalikan stok alat (Jika hilang, stok TIDAK dikembalikan)
            if ($request->kondisi_kembali != 'hilang') {
                foreach ($peminjaman->detailPinjams as $detail) {
                    $detail->alat->increment('stok', $detail->jumlah);
                }
            }

            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Memproses pengembalian peminjaman ID: ' . $peminjaman->id . ' | Denda: Rp ' . number_format($total_denda, 0, ',', '.'),
            ]);

            DB::commit();
            return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian diproses. Total Denda: Rp ' . number_format($total_denda, 0, ',', '.'));

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // * KELOLA PENGEMBALIAN : Menghapus riwayat pengembalian
    public function destroyPengembalian($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->delete();

        return redirect()->route('admin.pengembalian.index')->with('success', 'Riwayat pengembalian berhasil dihapus.');
    }
 }
