<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use Illuminate\Support\Facades\DB;

class PeminjamController extends Controller
{
    // 1. Menampilkan Katalog Alat
    public function indexKatalog(Request $request)
    {
        $search = $request->input('search');
        $kategori_id = $request->input('kategori_id');

        $kategoris = Kategori::all();

        $alats = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->when($search, function ($query, $search) {
                return $query->where('nama_alat', 'like', "%{$search}%");
            })
            ->when($kategori_id, function ($query, $kategori_id) {
                return $query->where('kategori_id', $kategori_id);
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('peminjam.katalog.index', compact('alats', 'kategoris', 'search', 'kategori_id'));
    }

    // 2. Memproses Pengajuan Peminjaman
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'tgl_kembali_plan' => 'required|date|after_or_equal:today',
            'items'            => 'required|array|min:1',
            'items.*.alat_id'  => 'required|exists:alat,id',
            'items.*.jumlah'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Buat transaksi peminjaman utama
            $peminjaman = Peminjaman::create([
                'user_id'          => auth()->id(),
                'tgl_pinjam'       => now()->toDateString(),
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status'           => 'diajukan',
            ]);

            // Cek ketersediaan stok & simpan detail item
            foreach ($request->items as $item) {
                $alat = Alat::findOrFail($item['alat_id']);

                if ($alat->stok < $item['jumlah']) {
                    throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi (Sisa: {$alat->stok}).");
                }

                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id'       => $item['alat_id'],
                    'jumlah'        => $item['jumlah'],
                ]);
            }

            DB::commit();
            return redirect()->route('peminjam.riwayat.index')->with('success', 'Pengajuan peminjaman berhasil dibuat, menunggu persetujuan petugas.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mengajukan peminjaman: ' . $th->getMessage());
        }
    }

    // 3. Menampilkan Riwayat Peminjaman Milik User Login
    public function riwayatPeminjaman(Request $request)
    {
        $status = $request->input('status');

        $peminjamans = Peminjaman::with(['detailPinjam.alat', 'pengembalian.petugas'])
            ->where('user_id', auth()->id())
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjam.riwayat.index', compact('peminjamans', 'status'));
    }
}