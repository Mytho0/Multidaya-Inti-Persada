<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('biaya_operasional')) {
            Schema::create('biaya_operasional', function (Blueprint $table) {
                $table->id();
                $table->string('kode_biaya')->unique();
                $table->string('sumber'); // operasional, promosi, inventaris
                $table->string('kategori');
                $table->string('deskripsi');
                $table->decimal('jumlah', 15, 2);
                $table->date('tanggal');
                $table->string('referensi')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('foto_bukti')->nullable();
                $table->string('status')->default('approved');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();

                // Index untuk performa
                $table->index('sumber');
                $table->index('tanggal');
                $table->index('kode_biaya');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_operasional');
    }
};
