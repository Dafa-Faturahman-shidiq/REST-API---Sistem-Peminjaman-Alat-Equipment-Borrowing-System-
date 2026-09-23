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

class PeminjamanController extends Controller
{
    //! ======================= CRUD PEMINJAMAN =======================

    // * CRUD PEMINJAMAN : Menampilkan halaman daftar peminjaman
    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search', '');

        $peminjamans = Peminjaman::with('peminjam', 'detailPinjam.alat') 
            ->when($search, function ($query, $search) {
                return $query->whereHas('peminjam', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })->orWhereHas('detailPinjam.alat', function ($query) use ($search) { 
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
        $users = User::where('role', 'peminjam')->get(); 
        $alats = Alat::where('stok', '>', 0)->get();
        $id = null;
        return view('admin.peminjaman.create', compact('users', 'alats', 'id'));
    }

    // * CRUD PEMINJAMAN : Menyimpan data peminjaman baru ke database
    public function storePeminjaman(Request $request)
    {
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
            $peminjaman = Peminjaman::create([
                'user_id' => $request->user_id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan',
            ]);

            foreach ($request->alat_id as $index => $alat_id) {
                $jumlah_pinjam = $request->jumlah[$index];
                $alat = Alat::findOrFail($alat_id);

                if ($alat->stok < $jumlah_pinjam) {
                    throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi (Sisa stok: {$alat->stok}).");
                }

                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alat_id,
                    'jumlah' => $jumlah_pinjam,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil ditambahkan.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // * CRUD PEMINJAMAN : Memperbarui status peminjaman
    public function updateStatusPeminjaman(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detailPinjam.alat')->findOrFail($id);

        // 🛠️ PERBAIKAN: Mengubah 'selesai' menjadi 'dikembalikan' sesuai Enum DB
        $request->validate([
            'status' => 'required|in:diajukan,dipinjam,dikembalikan,telat'
        ]);

        DB::beginTransaction();

        try {
            $statuslama = $peminjaman->status;
            $statusbaru = $request->status;

            // Logika pengelolaan stok otomatis 
            if ($statuslama != 'dipinjam' && $statusbaru == 'dipinjam') {

                // 🛠️ PERBAIKAN: Mengubah detailPinjams menjadi detailPinjam
                foreach ($peminjaman->detailPinjam as $detail) {
                    $alat = $detail->alat;

                    if ($alat->stok < $detail->jumlah) {
                        throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi.");
                    }

                    $alat->decrement('stok', $detail->jumlah);
                }
            } elseif ($statuslama == 'dipinjam' && ($statusbaru === 'dikembalikan' || $statusbaru === 'telat')) {
                // 🛠️ PERBAIKAN: Mengubah detailPinjams menjadi detailPinjam
                foreach ($peminjaman->detailPinjam as $detail) {
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

    // * CRUD PEMINJAMAN : Menghapus data peminjaman
    public function destroyPeminjaman($id)
    {
        $peminjaman = Peminjaman::with('detailPinjam.alat')->findOrFail($id);

        // Jika statusnya sedang dipinjam, kembalikan stok terlebih dahulu sebelum dihapus
        if ($peminjaman->status == 'dipinjam') {
            // 🛠️ PERBAIKAN: Mengubah detailPinjams menjadi detailPinjam
            foreach ($peminjaman->detailPinjam as $detail) {
                $detail->alat->increment('stok', $detail->jumlah);
            }
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}