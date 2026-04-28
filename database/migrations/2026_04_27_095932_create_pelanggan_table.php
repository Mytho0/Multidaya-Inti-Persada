<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_telepon')->unique();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('tipe', ['perorangan', 'perusahaan'])->default('perorangan');
            $table->string('npwp')->nullable();
            $table->integer('total_transaksi')->default(0);
            $table->decimal('total_nilai_transaksi', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};