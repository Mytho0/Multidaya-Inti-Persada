<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use Illuminate\Database\Seeder;

class RecommendationSeeder extends Seeder
{
    public function run(): void
    {
        Recommendation::truncate();

        $recommendations = [
            [
                'title' => 'Tambah Stok Epson EB-X500',
                'type' => 'barang',
                'description' => 'Barang ini memiliki permintaan tinggi (15 unit) dengan 8 pelanggan unik. Stok saat ini (3) tidak mencukupi. Rekomendasi tambah 10 unit.',
                'reason' => 'Berdasarkan histori permintaan tinggi dalam 3 bulan terakhir',
                'score' => 95,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Promo Hari Kamis',
                'type' => 'promo',
                'description' => 'Hari Kamis adalah hari dengan permintaan terendah (rata-rata 3 transaksi). Berikan promo diskon 15% untuk meningkatkan permintaan hingga target +133%.',
                'reason' => 'Berdasarkan analisis pola peminjaman 3 bulan terakhir',
                'score' => 88,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Restok Kabel HDMI',
                'type' => 'barang',
                'description' => 'Stok Kabel HDMI tersisa 4 unit. Barang ini sering disewa bersamaan dengan TV/LED screen.',
                'reason' => 'Berdasarkan analisis stok dan permintaan bundling',
                'score' => 75,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Weekend Special Promo',
                'type' => 'promo',
                'description' => 'Akhir pekan adalah waktu paling ramai untuk sewa barang. Buat promo diskon 10% untuk semua barang!',
                'reason' => 'Berdasarkan analisis pola permintaan weekend',
                'score' => 90,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($recommendations as $data) {
            Recommendation::create($data);
        }

        $this->command->info('✅ RecommendationSeeder berhasil: ' . count($recommendations) . ' data ditambahkan');
    }
}
