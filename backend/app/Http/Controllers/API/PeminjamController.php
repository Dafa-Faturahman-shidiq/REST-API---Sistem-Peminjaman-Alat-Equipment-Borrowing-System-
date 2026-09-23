<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use App\Http\Resources\PeminjamanResource;
use Illuminate\Support\Facades\DB;

class PeminjamController extends Controller
{
    // GET /api/peminjam/katalog
    public function indexKatalog(Request $request)
    {
        $alats = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->when($request->search, fn($q) => $q->where('nama_alat', 'like', "%{$request->search}%"))
            ->latest()
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar alat tersedia berhasil dimuat.',
            'data'    => $alats
        ], 200);
    }

    // POST /api/peminjam/peminjaman
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
            $peminjaman = Peminjaman::create([
                'user_id'          => auth()->id(),
                'tgl_pinjam'       => now()->toDateString(),
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status'           => 'diajukan',
            ]);

            foreach ($request->items as $item) {
                $alat = Alat::lockForUpdate()->findOrFail($item['alat_id']);

                if ($alat->stok < $item['jumlah']) {
                    throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi.");
                }

                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id'       => $item['alat_id'],
                    'jumlah'        => $item['jumlah'],
                ]);
            }

            DB::commit();
            $peminjaman->load(['peminjam', 'detailPinjam.alat']);

            return response()->json([
                'status'  => 'success',
                'message' => 'Peminjaman berhasil diajukan.',
                'data'    => new PeminjamanResource($peminjaman)
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $th->getMessage()
            ], 422);
        }
    }

    // GET /api/peminjam/riwayat
    public function riwayatPeminjaman()
    {
        $riwayat = Peminjaman::with(['peminjam', 'detailPinjam.alat', 'pengembalian'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Riwayat peminjaman berhasil dimuat.',
            'data'    => PeminjamanResource::collection($riwayat)
        ], 200);
    }
}