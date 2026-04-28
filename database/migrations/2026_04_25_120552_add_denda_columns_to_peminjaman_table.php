<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->decimal('denda', 15, 2)->default(0)->after('grand_total');
            $table->text('kondisi_barang')->nullable()->after('catatan_pengembalian');
            $table->text('kerusakan')->nullable()->after('kondisi_barang');
            $table->decimal('biaya_kerusakan', 15, 2)->default(0)->after('kerusakan');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['denda', 'kondisi_barang', 'kerusakan', 'biaya_kerusakan']);
        });
    }
};