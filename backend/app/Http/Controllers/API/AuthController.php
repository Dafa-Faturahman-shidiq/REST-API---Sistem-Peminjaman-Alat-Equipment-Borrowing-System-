<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;

use App\Models\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //* Method untuk registrasi user baru
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            return $user = DB::transaction(function () use ($request) {
                // Membuat user baru dengan data yang valid
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'peminjam',
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                ]);

                // Membuat token untuk user yang baru dibuat
                $token = $user->createToken('auth_token')->plainTextToken;

                // Mengembalikan response JSON dengan token dan data user
                return response()->json([
                    'message' => 'Registrasi berhasil',
                    'data' => new UserResource($user),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 201);
                
            });
        } catch (\Throwable $e) {
            //! Jika terjadi kesalahan, kembalikan response JSON dengan pesan error
            return response()->json([
                'message' => 'Terjadi Kesalahan saat Registrasi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //* Method untuk login user
    public function login(LoginRequest $request): JsonResponse
    {
        // Mencari user berdasarkan email yang diberikan
        $user = User::where('email', $request->email)->first();

        // Jika user tidak ditemukan atau password tidak cocok, lemparkan ValidationException
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan data kami'],
            ]);
        }

        // Jika login berhasil, buat token baru untuk user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Kembalikan response JSON dengan token dan data user
        return response()->json([
            'message' => 'Login berhasil',
            'data' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // * Method untuk mendapatkan data user yang sedang login
    public function me(Request $request): JsonResponse
    {
        // Mengembalikan data user yang sedang login
        return response()->json([
            'message' => 'Data user berhasil diambil',
            'data' => new UserResource($request->user()),
        ]);
    }

    // * Method untuk logout user
    public function logout(Request $request): JsonResponse
    {
        // Menghapus token yang digunakan untuk logout
        $request->user()->currentAccessToken()->delete();

        // Kembalikan response JSON dengan pesan sukses
        return response()->json([
            'message' => 'Logout berhasil. Token telah dihapus.',
        ]);
    }
}
