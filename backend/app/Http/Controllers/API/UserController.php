<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data pengguna dari database, dan diurutkan dari waktu
        $user = User::latest()->get();
        return response()->json([
            'message' => 'Data Pengguna berhasil diambil',
            'data' => UserResource::collection($user)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($request, $data) {
            // Menghash password sebelum menyimpan ke database
            $data['password'] = Hash::make($data['password']);

            // jika ada file foto_profile yang diunggah, disimpan ke storage dan path-nya disimpan ke database
            if ($request->hasFile('foto_profil')) {
                $data['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
            }

            // membuat pengguna baru dengan data yang telah divalidasi dan diubah
            return User::create($data);
        });

        return response()->json([
            'message' => 'Pengguna berhasil ditambahkan',
            'data' => new UserResource($user)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'data'=> new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = collect($request->validated());

        $user = DB::transaction(function () use ($request, $data, $user) {
            // Jika ada file foto_profil yang diunggah, simpan ke storage dan hapus file lama jika ada
            if ($request->hasFile('foto_profil')) {
                if ($user->foto_profil) {
                    Storage::disk('public')->delete($user->foto_profil);
                }
                $data['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
            }

            // Jika password tidak kosong, hash password sebelum menyimpan ke database
            if ($data->has('password') && !empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                // Jika password kosong, hapus dari data agar tidak mengubah password
                $data->forget('password');
            }

            // update pengguna dengan data yang telah divalidasi dan diubah
            $user->update($data->toArray());

            return $user;
        });

        return response()->json([
            'message' => 'Pengguna berhasil diperbarui',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            if ($user->foto_profile) {
                Storage::disk('public')->delete($user->foto_profile);
            }

            $user->delete();
        });

        return response()->json([
            'message' => 'Pengguna berhasil dihapus.'
        ]);

    }
}
