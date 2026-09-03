<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\LogAktivitas;

class PeminjamanObserver
{
    /**
     * Handle the Peminjaman "created" event.
     */
    public function created(Peminjaman $peminjaman): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id() ?? $peminjaman->user_id,
            'aktivitas' => "Menambahkan dataa peminjaman baru (ID: {$peminjaman->$id}) dengan Status: {$peminjaman->$status}."
        ]);
    }

    /**
     * Handle the Peminjaman "updated" event.
     */
    public function updated(Peminjaman $peminjaman): void
    {
        $changes = [];

        foreach ($peminjaman->getChanges() as $key => $newValue) {
            if ($key !== 'updated_at') {
                $oldValue = $peminjaman->getOriginal($key);
                $changes[] = "Kolom '{$key}' berubah dari '{$oldValue}' menjadi '{$newValue}'";
            }
        }

        $detail_perubahan = !empty($changes) ? implode(', ', $changes) : 'Memperbarui Data';

        LogAktivitas::create([
            'user_id' => auth()->id() ?? $peminjaman->user_id,
            'aktivitas' => "Memperbarui peminjaman ID {$peminjaman->$id}: {$detail_perubahan}"
        ]);
    }

    /**
     * Handle the Peminjaman "deleted" event.
     */
    public function deleted(Peminjaman $peminjaman): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id() ?? $peminjaman->user_id,
            'aktivitas' => "Menghapus Data Peminjaman (ID: {$peminjaman->id})"
        ]);
    }

    /**
     * Handle the Peminjaman "restored" event.
     */
    public function restored(Peminjaman $peminjaman): void
    {
        //
    }

    /**
     * Handle the Peminjaman "force deleted" event.
     */
    public function forceDeleted(Peminjaman $peminjaman): void
    {
        //
    }
}
