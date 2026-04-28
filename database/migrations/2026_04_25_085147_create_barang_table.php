<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->string('jenis');
            $table->decimal('harga_sewa', 15, 2);
            $table->integer('stok')->default(0);
            $table->integer('tersedia')->default(0);
            $table->integer('disewa')->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            
            // Index
            $table->index('kode_barang');
            $table->index('nama_barang');
            $table->index('jenis');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};