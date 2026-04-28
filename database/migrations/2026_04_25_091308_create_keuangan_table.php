<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['pendapatan', 'pengeluaran']);
            $table->string('kategori');
            $table->string('deskripsi');
            $table->decimal('jumlah', 15, 2);
            $table->date('tanggal');
            $table->string('sumber_dana')->nullable();
            $table->string('bukti')->nullable();
            $table->enum('status', ['pending', 'verified', 'cancelled'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Index
            $table->index('jenis');
            $table->index('kategori');
            $table->index('tanggal');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};