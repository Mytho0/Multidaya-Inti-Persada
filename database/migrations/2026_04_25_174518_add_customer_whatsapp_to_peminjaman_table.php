<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman', 'customer_whatsapp')) {
                $table->string('customer_whatsapp')->nullable()->after('no_telepon');
            }
            if (!Schema::hasColumn('peminjaman', 'whatsapp_sent_pengiriman')) {
                $table->boolean('whatsapp_sent_pengiriman')->default(false)->after('customer_whatsapp');
            }
            if (!Schema::hasColumn('peminjaman', 'whatsapp_sent_pengingat')) {
                $table->boolean('whatsapp_sent_pengingat')->default(false)->after('whatsapp_sent_pengiriman');
            }
            if (!Schema::hasColumn('peminjaman', 'whatsapp_pengingat_sent_at')) {
                $table->timestamp('whatsapp_pengingat_sent_at')->nullable()->after('whatsapp_sent_pengingat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn([
                'customer_whatsapp',
                'whatsapp_sent_pengiriman',
                'whatsapp_sent_pengingat',
                'whatsapp_pengingat_sent_at'
            ]);
        });
    }
};