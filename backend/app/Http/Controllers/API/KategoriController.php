<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\Kategori\StoreKategoriRequest;
use App\Http\Requests\Kategori\UpdateKategoriRequest;

use App\Http\Resources\KategoriResource;

use App\Models\Kategori;

use Illuminate\Http\JsonResponse;

class KategoriController extends Controller
{
    // Mengambil daftar kategori
    public function index()
    {
        $kategori = Kategori::all();
        return response()->json([
            'message' => 'Daftar kategori berhasil diambil.',
            'status' => 'success',
            'data' => KategoriResource::collection($kategori),
        ], 200);
    }

    // Menyimpan kategori baru
    public function store(StoreKategoriRequest $request) : JsonResponse
    {
        $kategori = Kategori::create($request->validated());
        return response()->json([
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => new KategoriResource($kategori),
        ], 201);
    }

    // Menampilkan detail kategori tertentu
    public function show(Kategori $kategori) : JsonResponse
    {
        return response()->json([
            'data' => new KategoriResource($kategori),
        ]);
    }

    // Memperbarui kategori tertentu
    public function update(UpdateKategoriRequest $request, Kategori $kategori) : JsonResponse
    {
        $kategori->update($request->validated());
        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'status' => 'success',
            'data' => new KategoriResource($kategori),
        ], 200);
    }
    
    // Menghapus kategori tertentu
    public function destroy(Kategori $kategori) : JsonResponse
    {
        $kategori->delete();
        return response()->json([
            'message' => 'Kategori berhasil dihapus.',
            'status' => 'success',
        ], 200);
    }
}
