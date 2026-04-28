<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Tambah kolom jika belum ada
            if (!Schema::hasColumn('notifications', 'status')) {
                $table->enum('status', ['unread', 'read'])->default('unread');
            }
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->enum('type', ['info', 'warning', 'success', 'whatsapp'])->default('info');
            }
            if (!Schema::hasColumn('notifications', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable();
            }
            if (!Schema::hasColumn('notifications', 'whatsapp_sent')) {
                $table->boolean('whatsapp_sent')->default(false);
            }
            if (!Schema::hasColumn('notifications', 'sent_at')) {
                $table->timestamp('sent_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['status', 'type', 'whatsapp_number', 'whatsapp_sent', 'sent_at']);
        });
    }
};