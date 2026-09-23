<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Peminjaman;
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

    // * 2. Setujui Peminjaman
    public function setujuiPeminjaman($id)
    {
        DB::beginTransaction();
        try {
            // 🛠️ PERBAIKAN: Mengubah detailPinjams menjadi detailPinjam
            $peminjaman = Peminjaman::with('detailPinjam.alat')->findOrFail($id);

            // Cek jika statusnya bukan 'diajukan'
            if ($peminjaman->status !== 'diajukan') {
                throw new \Exception("Pengajuan peminjaman ini sudah diproses sebelumnya.");
            }

            // 🛠️ PERBAIKAN: Kurangin stok alat dengan validasi ketersediaan stok
            foreach ($peminjaman->detailPinjam as $detail) {
                $alat = Alat::findOrFail($detail->alat_id);

                if ($alat->stok < $detail->jumlah) {
                    throw new \Exception("Gagal menyetujui. Stok alat '{$alat->nama_alat}' tidak mencukupi (Sisa stok: {$alat->stok}).");
                }

                $alat->decrement('stok', $detail->jumlah);
            }

            // Ubah status peminjaman menjadi 'dipinjam'
            $peminjaman->update(['status' => 'dipinjam']);

            DB::commit();
            return redirect()->back()->with('success', 'Peminjaman berhasil disetujui dan stok alat telah dikurangi.');

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

            // Pastikan statusnya "diajukan"
            if ($peminjaman->status == 'diajukan') {
                $peminjaman->delete();
                return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil ditolak.');
            }

            return redirect()->back()->with('error', 'Status peminjaman tidak valid untuk ditolak.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    // * 3. Proses Pengembalian
    public function prosesPengembalian(Request $request, $peminjamanId)
    {
        $request->validate([
            'tgl_kembali' => 'required|date',
            'kondisi_kembali' => 'required|string',
            'denda' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::with('detailPinjam.alat')->findOrFail($peminjamanId);

            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tgl_kembali' => $request->tgl_kembali ?? now()->toDateString(),
                'kondisi_kembali' => $request->kondisi_kembali,
                'denda' => $request->denda ?? 0,
                'petugas_id' => auth()->id(),
                'deskripsi' => $request->deskripsi ?? '-'
            ]);

            // Ubah status peminjaman menjadi 'dikembalikan'
            $peminjaman->update(['status' => 'dikembalikan']);

            // Kembalikan stok alat secara otomatis
            foreach ($peminjaman->detailPinjam as $detail) {
                $alat = Alat::findOrFail($detail->alat_id);
                $alat->increment('stok', $detail->jumlah);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pengembalian berhasil diproses.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    // * Menampilkan Pemantauan Pengembalian
    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search');

        // 🛠️ PERBAIKAN: Mengubah filter status dari ['diajukan', 'telat'] menjadi ['dipinjam', 'telat']
        $peminjamans = Peminjaman::with(['peminjam', 'detailPinjam.alat', 'pengembalian']) 
            ->whereIn('status', ['dipinjam', 'telat'])
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