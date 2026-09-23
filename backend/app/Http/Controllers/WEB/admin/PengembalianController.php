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

class PengembalianController extends Controller
{
     // * KELOLA PENGEMBALIAN : Menampilkan halaman utama pengembalian
    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search', '');

        // 1. Ambil data riwayat pengembalian yang sudah selesai diproses
        $pengembalians = Pengembalian::with('peminjaman.peminjam', 'petugas', 'peminjaman.detailPinjam.alat')
            ->when($search, function ($query, $search) {
                return $query->whereHas('peminjaman.peminjam', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('kondisi_kembali', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // 2. Ambil daftar peminjaman yang statusnya sedang 'dipinjam' agar admin tahu barang apa saja yang belum kembali
        $peminjamanDipinjam = Peminjaman::with('peminjam', 'detailPinjam.alat')
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        return view('admin.pengembalian.index', compact('pengembalians', 'peminjamanDipinjam', 'search'));
    }

    // * PENGEMBALIAN : Menampilkan form pengembalian alat
    public function createPengembalian($id)
    {
        $peminjaman = Peminjaman::with('peminjam', 'detailPinjam.alat')->findOrFail($id);
        
        // Pastikan hanya peminjaman yang berstatus 'dipinjam' yang bisa dikembalikan
        if ($peminjaman->status != 'dipinjam') {
            return redirect()->route('admin.peminjaman.index')->with('error', 'Peminjaman ini tidak dalam status dipinjam.');
        }

        return view('admin.pengembalian.create', compact('peminjaman'));
    }

   // * PENGEMBALIAN : Memproses data pengembalian alat
    public function storePengembalian(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('detailPinjam.alat')->findOrFail($id);

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
