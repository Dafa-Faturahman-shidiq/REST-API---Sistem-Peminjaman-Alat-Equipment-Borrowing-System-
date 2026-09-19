<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\Alat\StoreAlatRequest;
use App\Http\Requests\Alat\UpdateAlatRequest;
use App\Http\Resources\AlatResource;

use App\Models\Alat;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Http\Request;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tetap menggunakan eager loading untuk mencegah N+1 Query
        $alat = Alat::with('kategori')->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar alat berhasil diambil.',
            'data' => AlatResource::collection($alat),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlatRequest $request)
    {
        $data = $request->validated();

        // Gunakan transaksi database untuk memastikan integritas data
        $alat = DB::transaction(function () use ($data, $request){
            if ($request->hasFile('gambar')) {
                $data['gambar'] = $request->file('gambar')->store('alat', 'public');
            }

            return Alat::create($data);
        });

        // Gunakan AlatResource Untuk mengembalikan data yang telah diformat dengan benar
        return response()->json([
            'success' => true,
            'message' => 'Alat berhasil ditambahkan.',
            'data' => new AlatResource($alat),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat)
    {
        // Menggunakan Route Model Binding ($alat) ikombinasikan dengan load()
        return response()->json([
            'data' => new AlatResource($alat->load('kategori')),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlatRequest $request, Alat $alat)
    {
        // Validasi data yang masuk menggunkan UpdateAlatRequest
        $data = $request->validated();

        // Gunakan transaksi Database untuk memastikan integrtas sata
        DB::transaction(function () use ($data, $alat, $request) {
            if ($request->hasFile('gambar')) {
                if ($alat->gambar) {
                    Storage::disk('public')->delete($alat->gambar);
                }
                $data['gambar'] = $request->file('gambar')->store('alat', 'public');
            }

            $alat->update($data);
        });

        // Mengembalikan reponse Json 
        return response()->json([
            'success' => true,
            'message' => 'Alat berhasil diperbarui.',
            'data' => new AlatResource($alat),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        // Gunakan transaksi database untuk memastikan integritas data
        DB::transaction(function () use ($alat) {
            if ($alat->gambar) {
                Storage::disk('public')->delete($alat->gambar);
            }
            $alat->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Alat berhasil dihapus.',
        ], 200);
    }

    // Fungsi untuk menampilakan katalog alat dengan kategori terkait
    public function katalog() : JsonResponse
    {
        $alat = Alat::with('kategori')->where('stok', '>', 0)->latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Katalog alat berhasil diambil.',
            'data' => AlatResource::collection($alat),
        ], 200);
    }
}
