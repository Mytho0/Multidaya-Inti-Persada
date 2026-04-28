<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('nama_penyewa');
            $table->string('no_telepon');
            $table->string('nama_acara');
            $table->text('lokasi_acara');
            $table->date('tanggal_sewa');
            $table->date('tanggal_kembali');
            $table->time('waktu_sewa');
            $table->time('waktu_kembali');
            $table->enum('status_pembayaran', ['belum_bayar', 'dp', 'lunas'])->default('belum_bayar');
            $table->enum('status_pengembalian', ['aktif', 'selesai', 'terlambat'])->default('aktif');
            $table->decimal('total_harga', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->text('keterangan')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->string('foto_pengembalian')->nullable();
            $table->text('catatan_pengembalian')->nullable();
            $table->datetime('tanggal_pengembalian_real')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            $table->index('invoice_number');
            $table->index('status_pengembalian');
            $table->index('tanggal_sewa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};