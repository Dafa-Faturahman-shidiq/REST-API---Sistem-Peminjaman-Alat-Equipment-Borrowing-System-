<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Peminjaman;
use App\Models\DetailPinjam; // 🛠️ Model utama yang digunakan
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Support\Facades\DB;

class petugasController extends Controller
{
    // * 1. Menampilkan daftar pengajuan peminjaman dari peminjam
    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with(['peminjam', 'detailPinjam.alat']) 
            ->where('status', 'diajukan')
            ->when($search, function ($query, $search) {
                return $query->whereHas('peminjam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('petugas.peminjaman.index', compact('peminjamans', 'search'));
    }

    // * 2. Setujui Peminjaman (Mendukung Persetujuan Parsial & Full)
    public function setujuiPeminjaman(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailPinjam.alat')->findOrFail($id);

            // Cek jika statusnya bukan 'diajukan'
            if ($peminjaman->status !== 'diajukan') {
                throw new \Exception("Pengajuan peminjaman ini sudah diproses sebelumnya.");
            }

            // A. JIKA DIPROSES LEWAT MODAL PARSIAL
            if ($request->has('items')) {
                $request->validate([
                    'items' => 'required|array',
                    'items.*.detail_id' => 'required|exists:detail_peminjaman,id',
                    'items.*.status' => 'required|in:disetujui,ditolak',
                    'items.*.alasan_penolakan' => 'nullable|string',
                ]);

                $totalDisetujui = 0;
                $totalDitolak = 0;

                foreach ($request->items as $itemData) {
                    $detail = DetailPinjam::findOrFail($itemData['detail_id']);
                    $alat = Alat::findOrFail($detail->alat_id);

                    if ($itemData['status'] === 'disetujui') {
                        if ($alat->stok < $detail->jumlah) {
                            throw new \Exception("Gagal menyetujui. Stok alat '{$alat->nama_alat}' tidak mencukupi (Sisa stok: {$alat->stok}).");
                        }

                        $alat->decrement('stok', $detail->jumlah);
                        $detail->update([
                            'status' => 'disetujui',
                            'alasan_penolakan' => null
                        ]);
                        $totalDisetujui++;
                    } else {
                        $detail->update([
                            'status' => 'ditolak',
                            'alasan_penolakan' => $itemData['alasan_penolakan'] ?? 'Stok alat tidak mencukupi'
                        ]);
                        $totalDitolak++;
                    }
                }

                if ($totalDisetujui > 0 && $totalDitolak > 0) {
                    $statusAkhir = 'disetujui_parsial';
                } elseif ($totalDisetujui > 0 && $totalDitolak === 0) {
                    $statusAkhir = 'dipinjam';
                } else {
                    $statusAkhir = 'ditolak';
                }

                $peminjaman->update(['status' => $statusAkhir]);

            } else {
                // B. PERSETUJUAN SEMUA BARANG
                foreach ($peminjaman->detailPinjam as $detail) {
                    $alat = Alat::findOrFail($detail->alat_id);

                    if ($alat->stok < $detail->jumlah) {
                        throw new \Exception("Gagal menyetujui. Stok alat '{$alat->nama_alat}' tidak mencukupi (Sisa stok: {$alat->stok}).");
                    }

                    $alat->decrement('stok', $detail->jumlah);
                    if (isset($detail->status)) {
                        $detail->update(['status' => 'disetujui']);
                    }
                }

                $peminjaman->update(['status' => 'dipinjam']);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Persetujuan peminjaman berhasil diproses.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    // * Menolak Peminjaman
    public function tolakPeminjaman($id)
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status == 'diajukan') {
                $peminjaman->update(['status' => 'ditolak']);
                return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil ditolak.');
            }

            return redirect()->back()->with('error', 'Status peminjaman tidak valid untuk ditolak.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    // * 3. Proses Pengembalian Per-Item
    public function prosesPengembalian(Request $request, $peminjamanId)
    {
        $request->validate([
            'tgl_kembali' => 'required|date',
            'items' => 'required|array',
            'items.*.detail_id' => 'required|exists:detail_peminjaman,id',
            'items.*.kondisi_kembali' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'items.*.denda' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailPinjam.alat')->findOrFail($peminjamanId);

            $totalDendaKerusakan = 0;

            foreach ($request->items as $itemData) {
                // 🛠️ FIX: Menggunakan DetailPinjam (bukan DetailPeminjaman)
                $detail = DetailPinjam::findOrFail($itemData['detail_id']);

                $dendaItem = $itemData['denda'] ?? 0;

                // Update kondisi & denda per item
                $detail->update([
                    'kondisi_kembali' => $itemData['kondisi_kembali'],
                    'denda' => $dendaItem,
                ]);

                $totalDendaKerusakan += $dendaItem;

                // Kembalikan stok alat HANYA jika barang TIDAK HILANG
                if ($itemData['kondisi_kembali'] !== 'hilang') {
                    $alat = Alat::findOrFail($detail->alat_id);
                    $alat->increment('stok', $detail->jumlah);
                }
            }

            // Catat di tabel pengembalian utama
            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tgl_kembali' => $request->tgl_kembali ?? now()->toDateString(),
                'kondisi_kembali' => $totalDendaKerusakan > 0 ? 'rusak_ringan' : 'baik',
                'denda' => $totalDendaKerusakan,
                'petugas_id' => auth()->id(),
                'deskripsi' => 'Pengembalian alat diproses oleh petugas'
            ]);

            // Ubah status peminjaman menjadi 'dikembalikan'
            $peminjaman->update(['status' => 'dikembalikan']);

            DB::commit();
            return redirect()->back()->with('success', 'Pengembalian berhasil diproses dan stok telah diperbarui!');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pengembalian: ' . $th->getMessage());
        }
    }

    // * Menampilkan Pemantauan Pengembalian
    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with(['peminjam', 'detailPinjam.alat', 'pengembalian']) 
            ->whereIn('status', ['dipinjam', 'disetujui_parsial', 'telat'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('peminjam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('petugas.pengembalian.index', compact('peminjamans', 'search'));
    }

    // * Menampilkan halaman laporan
    public function laporan(Request $request)
    {
        $status = $request->input('status');
        $dari_tanggal = $request->input('dari_tanggal');
        $sampai_tanggal = $request->input('sampai_tanggal');

        $laporans = Peminjaman::with(['peminjam', 'detailPinjam.alat', 'pengembalian'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dari_tanggal && $sampai_tanggal, function ($query) use ($dari_tanggal, $sampai_tanggal) {
                return $query->whereBetween('tgl_pinjam', [$dari_tanggal, $sampai_tanggal]);
            })
            ->latest()
            ->get();

        return view('petugas.laporan.index', compact('laporans', 'status', 'dari_tanggal', 'sampai_tanggal'));
    }

    // * Menampilkan halaman khusus cetak
    public function cetakLaporan(Request $request)
    {
        $status = $request->input('status');
        $dari_tanggal = $request->input('dari_tanggal');
        $sampai_tanggal = $request->input('sampai_tanggal');

        $laporans = Peminjaman::with(['peminjam', 'detailPinjam.alat', 'pengembalian'])
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($dari_tanggal && $sampai_tanggal, function ($query) use ($dari_tanggal, $sampai_tanggal) {
                return $query->whereBetween('tgl_pinjam', [$dari_tanggal, $sampai_tanggal]);
            })
            ->latest()
            ->get();

        return view('petugas.laporan.cetak', compact('laporans', 'status', 'dari_tanggal', 'sampai_tanggal'));
    }
}