<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->nullable()->after('alasan_penolakan');
            $table->decimal('denda', 10, 2)->default(0)->after('kondisi_kembali');
        });
    }

    public function down(): void
    {
        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->dropColumn(['kondisi_kembali', 'denda']);
        });
    }
};