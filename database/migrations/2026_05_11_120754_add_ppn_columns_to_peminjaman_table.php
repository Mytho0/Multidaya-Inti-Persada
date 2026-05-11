<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Kolom untuk PPN
            $table->decimal('ppn', 10, 2)->default(0.11)->after('grand_total');
            $table->decimal('total_ppn', 15, 2)->default(0)->after('ppn');
            $table->decimal('grand_total_with_ppn', 15, 2)->default(0)->after('total_ppn');
            
            // Kolom untuk Jatuh Tempo
            $table->date('jatuh_tempo_pembayaran')->nullable()->after('grand_total_with_ppn');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['ppn', 'total_ppn', 'grand_total_with_ppn', 'jatuh_tempo_pembayaran']);
        });
    }
};