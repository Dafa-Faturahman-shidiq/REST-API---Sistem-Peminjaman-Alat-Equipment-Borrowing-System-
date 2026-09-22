<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Pengembalian\StorePengembalianRequest;
use App\Http\Requests\Pengembalian\UpdatePengembalianRequest;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Exception;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $user = auth()->user();$query = Pengembalian::with(['peminjaman.peminjam', 'peminjaman.detailPinjam.alat', 'petugas']);

        if ($user->role === 'peminjam') {$query->whereHas('peminjaman', function ($q) use ($user) {
                $q->where('user_id',$user->id);
            });
        }

        $pengembalian =$query->latest()->get();

        return response()->json([
            'message' => 'Riwayat pengembalian berhasil diambil.',
            'data' => $pengembalian
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePengembalianRequest $request)
    {
        try {
            // 🛠️ PERBAIKAN: Ditambahkan kata kunci `return` sebelum DB::transaction
            return DB::transaction(function () use ($request) {

                $peminjaman = Peminjaman::with('detailPinjam')->lockForUpdate()->findOrFail($request->peminjaman_id);

                if ($peminjaman->status != 'dipinjam') {
                    throw new Exception("Data ditolak. Peminjaman ini berstatus '{$peminjaman->status}', bukan 'dipinjam'.");
                }

                $tglKembaliPlan = Carbon::parse($peminjaman->tgl_kembali_plan)->startOfDay();$hariIni = Carbon::now()->startOfDay();

                $statusPeminjamanBaru = $hariIni->greaterThan($tglKembaliPlan) ? 'telat' : 'dikembalikan';

                $pengembalian = Pengembalian::create([
                    'peminjaman_id'   => $peminjaman->id,
                    'tgl_kembali'     => now()->toDateString(),
                    'kondisi_kembali' => $request->kondisi_kembali,
                    'denda'           => $request->denda ?? 0,
                    'petugas_id'      => auth()->id(),
                    'deskripsi'       => $request->deskripsi ?? '-'
                ]);

                $peminjaman->update(['status' =>$statusPeminjamanBaru]);

                foreach ($peminjaman->detailPinjam as$detail) {
                    $alat = Alat::lockForUpdate()->find($detail->alat_id);
                    $alat->increment('stok',$detail->jumlah);
                }

                auth()->user()->logAktivitas()?->create([
                    'aktivitas' => "Memproses pengembalian peminjaman ID: #{$peminjaman->id} dengan status akhir: {$statusPeminjamanBaru}."
                ]);

                $pengembalian->load(['peminjaman.peminjam', 'petugas']);

                return response()->json([
                    'message' => 'Proses pengembalian alat berhasil diselesaikan.',
                    'data'    => $pengembalian
                ], 201);
            });

        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'file'    => $e->getFile(), 
                'line'    => $e->getLine(), 
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    // 🛠️ PERBAIKAN: Mengubah tipe parameter menjadi Route Model Binding (Pengembalian $pengembalian)
    public function show(Pengembalian $pengembalian)
    {
        $user = auth()->user();$pengembalian->load(['peminjaman.peminjam', 'peminjaman.detailPinjam.alat', 'petugas']);

        if ($user->role === 'peminjam' && $pengembalian->peminjaman->user_id !==$user->id) {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk melakukan tindakan ini.'], 403);
        }

        return response()->json([
            'message' => 'Detail pengembalian berhasil diambil.',
            'data'    => $pengembalian
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePengembalianRequest $request, Pengembalian$pengembalian)
    {
        $pengembalian->update([
            'kondisi_kembali' => $request->kondisi_kembali,
            'denda'           => $request->denda ?? $pengembalian->denda,
        ]);

        return response()->json([
            'message' => 'Data pengembalian berhasil diperbarui.',
            'data'    => $pengembalian->load(['peminjaman.peminjam', 'petugas'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengembalian $pengembalian)
    {
        try {
            // 🛠️ PERBAIKAN: Seluruh alur pengurangan stok dan pembatalan dipindah ke dalam DB::transaction
            DB::transaction(function () use ($pengembalian) {
                $peminjaman = Peminjaman::with('detailPinjam')->lockForUpdate()->findOrFail($pengembalian->peminjaman_id);

                foreach ($peminjaman->detailPinjam as$detail) {
                    $alat = Alat::lockForUpdate()->findOrFail($detail->alat_id);

                    if ($alat->stok <$detail->jumlah) {
                        throw new Exception("Gagal membatalkan pengembalian. Stok alat '{$alat->nama_alat}' saat ini tidak mencukupi untuk ditarik kembali.");
                    }

                    $alat->decrement('stok',$detail->jumlah);
                }

                $peminjaman->update(['status' => 'dipinjam']);

                auth()->user()->logAktivitas()?->create([
                    'aktivitas' => "Membatalkan pengembalian ID: #{$pengembalian->id}"
                ]);

                $pengembalian->delete();
            });

            return response()->json([
                'message' => 'Data pengembalian berhasil dihapus. Stok dan status peminjaman telah dikembalikan ke kondisi semula.'
            ]);

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}