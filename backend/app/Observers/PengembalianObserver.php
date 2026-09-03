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
        LogAktivitas::create([
            'user_id' => auth()->id() ?? $pengembaliann->user_id,
            'aktivitas' => "Memproses Pengembalian Alat untuk Peminjaman ID: {$pengembalian->$peminjaman_id} 
            dengan kondisi '{$pengembalian->kondisi_kembali}'
            dan denda Rp " . number_format($pengembalian->denda, 0, '0', '.') . "."
        ]);
        
    }

    /**
     * Handle the Pengembalian "updated" event.
     */
    public function updated(Pengembalian $pengembalian): void
    {
        $changes = [];

        foreach ($pengembaliann->getChanges() as $key => $newValue) {
            if ($key !== 'updated_at') {
                $oldValue = $pengembalian->getOriginal($key);
                $changes[] = "Kolom '{$key}' berubah dari '{$oldValue}' menjadi '{$newValue}'";
            }
        }

        $detail_perubahan = !empty($changes) ? implode(', ', $changes) : 'Memperbarui Data';

        LogAktivitas::create([
            'user_id' => auth()->id() ,
            'aktivitas' => "Memperbarui peminjaman ID {$pengembalian->$id}: {$detail_perubahan}"
        ]);
    }

    /**
     * Handle the Pengembalian "deleted" event.
     */
    public function deleted(Pengembalian $pengembalian): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus Data Peminjaman (ID: {$peminjaman->id})"
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
