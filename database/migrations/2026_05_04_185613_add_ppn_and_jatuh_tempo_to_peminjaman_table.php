<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Pastikan kolom ppn ada terlebih dahulu (jika perlu)
            if (!Schema::hasColumn('peminjaman', 'ppn')) {
                $table->decimal('ppn', 15, 2)->default(0.11)->after('diskon');
            }

            // Tambah kolom total_ppn setelah ppn
            if (!Schema::hasColumn('peminjaman', 'total_ppn')) {
                $table->decimal('total_ppn', 15, 2)->default(0)->after('ppn');
            }

            // Tambah kolom grand_total_with_ppn
            if (!Schema::hasColumn('peminjaman', 'grand_total_with_ppn')) {
                $table->decimal('grand_total_with_ppn', 15, 2)->default(0)->after('total_ppn');
            }

            // Tambah kolom jatuh_tempo_pembayaran
            if (!Schema::hasColumn('peminjaman', 'jatuh_tempo_pembayaran')) {
                $table->date('jatuh_tempo_pembayaran')->nullable()->after('grand_total_with_ppn');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $columns = ['total_ppn', 'grand_total_with_ppn', 'jatuh_tempo_pembayaran', 'ppn'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('peminjaman', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
