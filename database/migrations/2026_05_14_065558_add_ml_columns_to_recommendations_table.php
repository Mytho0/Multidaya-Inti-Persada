<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->unsignedBigInteger('barang_id')->nullable()->after('id');
            $table->string('jenis_barang')->nullable()->after('barang_id');
            $table->string('demand_label')->nullable()->after('jenis_barang');
            $table->string('jenis_promo')->nullable()->after('demand_label');
            $table->string('analysis_type')->nullable()->after('jenis_promo');
            $table->string('potential_gain')->nullable()->after('analysis_type');
            $table->string('revenue_estimate')->nullable()->after('potential_gain');
            $table->integer('nilai_diskon')->nullable()->after('revenue_estimate');
            $table->string('jenis_diskon')->nullable()->after('nilai_diskon');
            $table->decimal('utilisasi_rate', 5, 4)->nullable()->after('jenis_diskon');
            $table->decimal('idle_rate', 5, 4)->nullable()->after('utilisasi_rate');
            $table->integer('cluster')->nullable()->after('idle_rate');
            $table->string('source')->default('manual')->after('cluster');
        });
    }

    public function down(): void
    {
        Schema::table('recommendations', function (Blueprint $table) {
            $table->dropColumn([
                'barang_id', 'jenis_barang', 'demand_label', 'jenis_promo',
                'analysis_type', 'potential_gain', 'revenue_estimate',
                'nilai_diskon', 'jenis_diskon', 'utilisasi_rate', 'idle_rate',
                'cluster', 'source'
            ]);
        });
    }
};
