<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keuangan', function (Blueprint $table) {
            // Tambah kolom untuk relasi
            $table->foreignId('peminjaman_id')->nullable()->after('id')->constrained('peminjaman')->nullOnDelete();
            $table->foreignId('barang_id')->nullable()->after('peminjaman_id')->constrained('barang')->nullOnDelete();
            $table->enum('sumber', ['sewa', 'operasional', 'promosi', 'inventaris'])->default('operasional')->after('jenis');
            $table->string('referensi')->nullable()->after('sumber');
        });
    }

    public function down(): void
    {
        Schema::table('keuangan', function (Blueprint $table) {
            $table->dropForeign(['peminjaman_id']);
            $table->dropForeign(['barang_id']);
            $table->dropColumn(['peminjaman_id', 'barang_id', 'sumber', 'referensi']);
        });
    }
};