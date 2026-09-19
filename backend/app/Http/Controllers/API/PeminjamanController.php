<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Peminjaman\StorePeminjamanRequest;
use App\Http\Resources\PeminjamanResource;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Peminjaman::with(['peminjam', 'detailPinjam.alat', 'pengembalian']);

        if ($user->role === 'peminjam') {
            $query->where('user_id', $user->id);
        }

        $peminjaman = $query->latest()->get();

        return response()->json([
            'message' => 'Daftar peminjaman berhasil diambil.',
            'data' => PeminjamanResource::collection($peminjaman)
        ], 200);
    }

    public function store(StorePeminjamanRequest $request)
    {
        try {
            $peminjaman = DB::transaction(function () use ($request) {
                $user = auth()->user();

                $peminjaman = Peminjaman::create([
                    'user_id' => $user->id,
                    'tgl_pinjam' => now()->toDateString(),
                    'tgl_kembali_plan' => $request->tgl_kembali_plan,
                    'status' => 'diajukan',
                ]);

                foreach ($request->items as $item) {
                    $alat = Alat::lockForUpdate()->findOrFail($item['alat_id']);

                    if ($alat->stok < $item['jumlah']) {
                        throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi. Sisa stok: {$alat->stok}");
                    }

                    DetailPinjam::create([
                        'peminjaman_id' => $peminjaman->id,
                        'alat_id' => $item['alat_id'],
                        'jumlah' => $item['jumlah'],
                    ]);
                }

                return $peminjaman->load(['peminjam', 'detailPinjam.alat']);
            });

            return response()->json([
                'message' => 'Peminjaman berhasil diajukan, Menunggu persetujuan petugas',
                'data' => new PeminjamanResource($peminjaman)
            ], 201);

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
            // return response()->json([
            //     'message' => $e->getMessage(),
            //     'file'    => $e->getFile(), 
            //     'line'    => $e->getLine(), 
            // ], 422);
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        $user = auth()->user();

        if ($user->role === 'peminjam' && $peminjaman->user_id !== $user->id) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke data ini.'], 403);
        }

        return response()->json([
            'message' => 'Detail peminjaman berhasil diambil.',
            'data' => new PeminjamanResource($peminjaman->load(['peminjam', 'detailPinjam.alat', 'pengembalian']))
        ], 200);
    }

    public function update(StorePeminjamanRequest $request, Peminjaman $peminjaman)
    {
        $user = auth()->user();

        if ($user->role === 'peminjam' && $peminjaman->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        if ($peminjaman->status !== 'diajukan') {
            return response()->json([
                'message' => "Peminjaman tidak dapat diubah karena status saat ini: {$peminjaman->status}."
            ], 400);
        }

        try {
            DB::transaction(function () use ($request, $peminjaman) {
                $peminjaman->update([
                    'tgl_kembali_plan' => $request->tgl_kembali_plan,
                ]);

                $peminjaman->detailPinjam()->delete();

                foreach ($request->items as $item) {
                    $alat = Alat::lockForUpdate()->findOrFail($item['alat_id']);

                    if ($alat->stok < $item['jumlah']) {
                        // 🛠️ PERBAIKAN: Ditambahkan backslash (\Exception)
                        throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi. Sisa stok: {$alat->stok}");
                    }

                    DetailPinjam::create([
                        'peminjaman_id' => $peminjaman->id,
                        'alat_id' => $item['alat_id'],
                        'jumlah' => $item['jumlah'],
                    ]);
                }
            });

            return response()->json([
                'message' => 'Peminjaman berhasil diperbarui.',
                'data' => new PeminjamanResource($peminjaman->load(['peminjam', 'detailPinjam.alat']))
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $user = auth()->user();

        if ($user->role === 'peminjam' && $peminjaman->user_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        if ($peminjaman->status !== 'diajukan') {
            return response()->json([
                'message' => "Peminjaman tidak dapat dihapus karena status saat ini: {$peminjaman->status}."
            ], 400);
        }

        try {
            DB::transaction(function () use ($peminjaman) {
                $peminjaman->detailPinjam()->delete();
                $peminjaman->delete();
            });

            return response()->json([
                'message' => 'Peminjaman berhasil dihapus.'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function approve(Peminjaman $peminjaman)
    {
        $user = auth()->user();

        if (!in_array($user->role, ['petugas', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        if ($peminjaman->status !== 'diajukan') {
            return response()->json([
                'message' => "Peminjaman tidak dapat disetujui karena status saat ini: {$peminjaman->status}."
            ], 400);
        }

        try {
            DB::transaction(function () use ($peminjaman) {
                $peminjaman->update(['status' => 'dipinjam']);
            });

            return response()->json([
                'message' => 'Peminjaman berhasil disetujui.',
                'data' => new PeminjamanResource($peminjaman->load(['peminjam', 'detailPinjam.alat']))
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function riwayat()
    {
        $riwayat = Peminjaman::with(['detailPinjam.alat', 'pengembalian'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Riwayat peminjaman berhasil diambil.',
            'data' => PeminjamanResource::collection($riwayat)
        ], 200);
    }
}