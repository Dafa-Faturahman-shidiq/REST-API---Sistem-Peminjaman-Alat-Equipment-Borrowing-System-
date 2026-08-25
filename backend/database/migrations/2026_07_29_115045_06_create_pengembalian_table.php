<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_kembali');
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->string('deskripsi');
            $table->integer('denda')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreignId('petugas_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
