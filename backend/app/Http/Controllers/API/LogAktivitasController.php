<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogAktivitasResource;
use App\Models\LogAktivitas;
use Illuminate\Http\JsonResponse;

class LogAktivitasController extends Controller
{
    public function index(): JsonResponse
    {
        $logs = LogAktivitas::with('user')->latest()->get();

        return response()->json([
            'message' => 'Data log aktivitas berhasil diambil',
            'total_data' => $logs->count(),
            'data' => LogAktivitasResource::collection($logs),
        ]);
    }
}
