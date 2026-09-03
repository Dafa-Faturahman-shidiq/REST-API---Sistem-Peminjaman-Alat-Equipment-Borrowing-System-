<?php

namespace App\Observers;

use App\Models\Alat;
use App\Models\LogAktivitas;

class AlatObserver
{
    /**
     * Handle the Alat "created" event.
     */
    public function created(Alat $alat): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menambahkan dataa Alat baru: {$alat->nama_alat} (stok: {$alat->stok}, kondisi: {$alat->status_kondisi})."
        ]);
    }

    /**
     * Handle the Alat "updated" event.
     */
    public function updated(Alat $alat): void
    {
        $changes = [];

        foreach ($alat->getChanges() as $key => $newValue) {
            if ($key !== 'updated_at') {
                $oldValue = $alat->getOriginal($key);
                $changes[] = "Kolom '{$key}' berubah dari '{$oldValue}' menjadi '{$newValue}'";
            }
        }

        $detail_perubahan = !empty($changes) ? implode(', ', $changes) : 'Memperbarui Data';

        LogAktivitas::create([
            'user_id' => auth()->id() ,
            'aktivitas' => "Memperbarui Data Alat {$alat->nama_alat}:  {$detail_perubahan}"
        ]);
    }

    /**
     * Handle the Alat "deleted" event.
     */
    public function deleted(Alat $alat): void
    {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => "Menghapus Data Alat: '{$alat->nama_alat}'"
        ]);
    }

    /**
     * Handle the Alat "restored" event.
     */
    public function restored(Alat $alat): void
    {
        //
    }

    /**
     * Handle the Alat "force deleted" event.
     */
    public function forceDeleted(Alat $alat): void
    {
        //
    }
}
