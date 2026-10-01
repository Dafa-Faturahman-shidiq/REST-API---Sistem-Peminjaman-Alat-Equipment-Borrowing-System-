<?php

namespace App\Observers;

use App\Models\Pengembalian;
use App\Models\LogAktivitas;

class PengembalianObserver
{
    /**
     * Handle the Pengembalian "created" event.
     */
    public function created(Pengembalian $pengembalian): void
    {
        $dendaFormatted = number_format($pengembalian->denda, 0, ',', '.');

        LogAktivitas::create([
            'user_id' => auth()->id() ?? $pengembalian->petugas_id, 
            'aktivitas' => "Memproses pengembalian alat untuk Peminjaman ID #{$pengembalian->peminjaman_id} (Kondisi: {$pengembalian->kondisi_kembali}, Denda: Rp {$dendaFormatted})"
        ]);
    }

    /**
     * Handle the Pengembalian "updated" event.
     */
    public function updated(Pengembalian $pengembalian): void
    {
        $changes = [];

        foreach ($pengembalian->getChanges() as $key => $newValue) {
            if ($key !== 'updated_at') {
                $oldValue = $pengembalian->getOriginal($key);
                $changes[] = "Kolom '{$key}' berubah dari '{$oldValue}' menjadi '{$newValue}'";
            }
        }

        $detail_perubahan = !empty($changes) ? implode(', ', $changes) : 'Memperbarui Data';

        LogAktivitas::create([
            'user_id' => auth()->id() ?? $pengembalian->petugas_id,
            'aktivitas' => "Memperbarui data pengembalian ID #{$pengembalian->id}: {$detail_perubahan}"
        ]);
    }

    /**
     * Handle the Pengembalian "deleted" event.
     */
    public function deleted(Pengembalian $pengembalian): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id() ?? $pengembalian->petugas_id,
            'aktivitas' => "Menghapus data pengembalian (ID: {$pengembalian->id})" // 🛠️ FIX: $peminjaman->id -> $pengembalian->id
        ]);
    }

    /**
     * Handle the Pengembalian "restored" event.
     */
    public function restored(Pengembalian $pengembalian): void
    {
        //
    }

    /**
     * Handle the Pengembalian "force deleted" event.
     */
    public function forceDeleted(Pengembalian $pengembalian): void
    {
        //
    }
}