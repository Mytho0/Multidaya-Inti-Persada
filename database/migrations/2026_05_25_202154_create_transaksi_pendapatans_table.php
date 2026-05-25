<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_pendapatans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('sumber')->default('sewa_alat'); // sewa_alat, denda, lainnya
            $table->string('kategori');
            $table->string('deskripsi');
            $table->decimal('jumlah', 15, 0);
            $table->date('tanggal');
            $table->string('referensi')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['tanggal', 'sumber']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_pendapatans');
    }
};
