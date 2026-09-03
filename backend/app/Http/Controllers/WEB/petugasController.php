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
    // * 1. Menampilkan daftar pengajuan peminjaman dari siswa/peminjam
    public function indexPeminjaman(Request $request)
    {

        $search = $request->input('search');

        $peminjamans = Peminjaman::with(['user', 'detailPinjams.alat']) 
            ->where('status', 'diajukan')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "{%{$search}%}");
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
            $peminjaman = Peminjaman::with('detailPinjams')->findOrFail($id);
            $peminjaman->update(['status' => 'dipinjam']);

            // kurangin stok alat secara otomatis
            foreach($peminjaman->detailPinjams as $detail){
                $alat = Alat::findOrFail($detail->alat_id);
                $alat->stok -= $detail->jumlah;
                $alat->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Peminjaman disetujui dan stok alat dikurangi.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }

        return redirect()->route('petugas.peminjaman.index')->with('success', 'Peminjaman telah disetujui.');
    }

    //* Menolak Peminjaman (Menghapus Pengajuan agar siswa bisa mengajukan ulang)
    public function tolakPeminjamana()
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            // Pastikan statusnya "Diajukan"
            if ($peminjaman->status == 'diajukan') {
                $peminjaman->delete();
                return redirect()->back()->with('success', 'Pegajuan Peminjaman berhasil ditolak.');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    // * 3. Proses Pengembalian
    public function prosesPengembalian(Request $request, $peminjamanId)
    {
        // Validasi input
        $request->validate([
            'tgl_kembali' => 'required|date',
            'denda' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // ambil data peminjaman beserta detailnya
            $peminjaman = Peminjaman::with('detailPinjams')->findOrFail($peminjamanId);

            // buat data pengembalian
            $pengembalian = Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tgl_kembali' => now(),
                'kondisi_kembali' => $request->kondisi_kembali,
                'denda' => $request->denda ?? 0,
                'petugas_id' => auth()->user()->id,
            ]);

            // update status peminjaman menjadi 'dikembalikan'
            $peminjaman->update(['status' => 'selesai']);

            // kembalikan stok alat secara otomatis
            foreach($peminjaman->detailPinjams as $detail){
                $alat = Alat::findOrFail($detail->alat_id);
                $alat->stok += $detail->jumlah;
                $alat->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pengembalian berhasil diproses.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    // Menampilkan Pemantauan Pengembalian
    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with(['user', 'detailPinjams.alat', 'pengembalian']) 
            ->where('status', ['diajukan', 'telat'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "{%{$search}%}");
                });
            })
            ->latest()
            ->get();

        return view('petugas.pengembalian.index', compact('peminjamans', 'search'));
    }

    // menampilakn halaman laporan
    public function laporan(Request $request)
{
    $status = $request->input('status');
    $dari_tanggal = $request->input('dari_tanggal');
    $sampai_tanggal = $request->input('sampai_tanggal');

    $laporans = Peminjaman::with(['user', 'detailPinjams.alat', 'pengembalian'])
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

// Menampilkan halaman khusus cetak (print preview)
public function cetakLaporan(Request $request)
{
    $status = $request->input('status');
    $dari_tanggal = $request->input('dari_tanggal');
    $sampai_tanggal = $request->input('sampai_tanggal');

    $laporans = Peminjaman::with(['user', 'detailPinjams.alat', 'pengembalian'])
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
