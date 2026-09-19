<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Menampilkan nama peminjam jika relasi 'user' telah dimuat, jika tidak, menampilkan null
            'peminjam' => $this->whenLoaded('peminjam', function () {
                return [
                    $this->peminjam?->name
                ];
            }),
            'tgl_pinjam' => $this->tgl_pinjam?->format('Y-m-d'),
            'tgl_kembali_plan' => $this->tgl_kembali_plan?->format('Y-m-d'),
            'status' => $this->status,
            // Menampilkan detail item yang dipinjam jika relasi 'detailPinjam' telah dimuat
            'item_dipinjam' => $this->whenLoaded('detailPinjam', function () {
                return $this->detailPinjam->map(function ($detail) {
                    return [
                        'nama_alat' => $detail->alat?->nama_alat ?? 'Alat Dihapus/Tidak Ditemukan',
                        'jumlah' => (int) $detail->jumlah,
                    ];
                });
            }),
            // Menampilkan info pengembalian jika relasi 'pengembalian' telah dimuat
            'info_pengembalian' => $this->whenLoaded('pengembalian', function () {
                if (!$this->pengembalian) return null;

                    return [
                        'tgl_kembali' => $this->pengembalian?->tgl_kembali?->format('Y-m-d'),
                        'kondisi_alat' => $this->pengembalian?->kondisi_alat,
                        'denda' => (int) $this->pengembalian?->denda,
                        'petugas_penerima' => $this->pengembalian?->petugas?->name ?? 'Sistem',
                    ];
            }),
        ];
    }
}
