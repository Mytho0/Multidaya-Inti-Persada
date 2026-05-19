<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->string('nama_promo');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_diskon', ['persen', 'nominal']);
            $table->decimal('nilai_diskon', 10, 2);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->decimal('utilisasi_target', 5, 2)->default(70.00); // nonaktif kalau utilisasi > 70%
            $table->enum('status', ['aktif', 'nonaktif', 'expired'])->default('aktif');
            $table->enum('sumber', ['manual', 'ml'])->default('ml');
            $table->timestamps();

            $table->foreign('barang_id')->references('id')->on('barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
