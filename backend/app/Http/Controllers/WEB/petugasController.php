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
    public function indexPeminjaman()
    {
        $peminjamans = Peminjaman::with(['user', 'detailPinjams.alat'])->get();
        return view('petugas.peminjaman.index', compact('peminjamans'));
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
}
