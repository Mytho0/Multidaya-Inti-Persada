<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek apakah kolom 'pelanggan_id' sudah ada sebelum menambahkan
        if (!Schema::hasColumn('peminjaman', 'pelanggan_id')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                // Pastikan juga foreign key belum ada
                if (!Schema::getConnection()->getSchemaBuilder()->hasForeignKey('peminjaman_pelanggan_id_foreign', 'peminjaman')) {
                    $table->foreignId('pelanggan_id')
                        ->nullable()
                        ->after('id')
                        ->constrained('pelanggan')
                        ->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Cek apakah foreign key ada sebelum menghapus
            if (Schema::getConnection()->getSchemaBuilder()->hasForeignKey('peminjaman_pelanggan_id_foreign', 'peminjaman')) {
                $table->dropForeign(['pelanggan_id']);
            }

            if (Schema::hasColumn('peminjaman', 'pelanggan_id')) {
                $table->dropColumn('pelanggan_id');
            }
        });
    }
};
