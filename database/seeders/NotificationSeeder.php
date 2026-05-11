<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        Notification::truncate();

        $notifications = [
            [
                'title' => 'Transaksi Baru',
                'message' => 'Peminjaman baru dari Dewi Sartika (INV/MIP/2026/05/0001)',
                'type' => 'info',
                'status' => 'unread',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Pengembalian Barang',
                'message' => 'Barang telah dikembalikan oleh PT. Maju Bersama',
                'type' => 'success',
                'status' => 'unread',
                'created_at' => now()->subHours(2),
                'updated_at' => now(),
            ],
            [
                'title' => 'Stok Menipis',
                'message' => 'Stok Epson EB-X500 tersisa 1 unit, segera restok!',
                'type' => 'warning',
                'status' => 'unread',
                'created_at' => now()->subDay(),
                'updated_at' => now(),
            ],
        ];

        foreach ($notifications as $data) {
            Notification::create($data);
        }

        $this->command->info('✅ NotificationSeeder berhasil: ' . count($notifications) . ' data ditambahkan');
    }
}
