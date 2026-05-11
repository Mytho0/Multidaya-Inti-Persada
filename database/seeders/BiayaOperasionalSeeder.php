<?php

namespace Database\Seeders;

use App\Models\BiayaOperasional;
use Illuminate\Database\Seeder;

class BiayaOperasionalSeeder extends Seeder
{
    public function run(): void
    {
        BiayaOperasional::truncate();

        $biaya = [
            [
                'kode_biaya' => 'BY/2026/05/0001',
                'sumber' => 'operasional',
                'kategori' => 'Gaji Karyawan',
                'deskripsi' => 'Pembayaran gaji karyawan bulan Mei 2026 (5 orang)',
                'jumlah' => 15000000,
                'tanggal' => '2026-05-05',
                'referensi' => 'SLIP/GJ/05/2026',
                'keterangan' => 'Gaji 5 karyawan tetap',
                'status' => 'approved',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => '2026-05-05 10:00:00',
                'updated_at' => now(),
            ],
            [
                'kode_biaya' => 'BY/2026/05/0002',
                'sumber' => 'operasional',
                'kategori' => 'Listrik',
                'deskripsi' => 'Pembayaran listrik kantor bulan Mei 2026',
                'jumlah' => 1850000,
                'tanggal' => '2026-05-04',
                'referensi' => 'PLN/05/2026',
                'keterangan' => 'Tagihan listrik kantor',
                'status' => 'approved',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => '2026-05-04 09:30:00',
                'updated_at' => now(),
            ],
            [
                'kode_biaya' => 'BY/2026/05/0003',
                'sumber' => 'operasional',
                'kategori' => 'Internet',
                'deskripsi' => 'Pembayaran internet kantor',
                'jumlah' => 850000,
                'tanggal' => '2026-05-03',
                'referensi' => 'ISP/05/2026',
                'keterangan' => 'Tagihan internet IndiHome',
                'status' => 'approved',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => '2026-05-03 14:20:00',
                'updated_at' => now(),
            ],
            [
                'kode_biaya' => 'BY/2026/04/0001',
                'sumber' => 'operasional',
                'kategori' => 'Gaji Karyawan',
                'deskripsi' => 'Pembayaran gaji karyawan bulan April 2026',
                'jumlah' => 15000000,
                'tanggal' => '2026-04-05',
                'referensi' => 'SLIP/GJ/04/2026',
                'keterangan' => 'Gaji 5 karyawan tetap',
                'status' => 'approved',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => '2026-04-05 10:00:00',
                'updated_at' => now(),
            ],
            [
                'kode_biaya' => 'BY/2026/04/0002',
                'sumber' => 'promosi',
                'kategori' => 'Iklan Online',
                'deskripsi' => 'Biaya iklan Google Ads dan Instagram',
                'jumlah' => 3500000,
                'tanggal' => '2026-04-15',
                'referensi' => 'ADS/04/2026',
                'keterangan' => 'Iklan untuk promosi penyewaan LED screen',
                'status' => 'approved',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => '2026-04-15 13:45:00',
                'updated_at' => now(),
            ],
            [
                'kode_biaya' => 'BY/2026/04/0003',
                'sumber' => 'inventaris',
                'kategori' => 'Pembelian Barang',
                'deskripsi' => 'Pembelian LED screen baru 2 unit',
                'jumlah' => 12500000,
                'tanggal' => '2026-04-20',
                'referensi' => 'PO/LED/04/2026',
                'keterangan' => 'LED screen 3x4 meter',
                'status' => 'approved',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now(),
                'created_at' => '2026-04-20 09:00:00',
                'updated_at' => now(),
            ],
        ];

        foreach ($biaya as $data) {
            BiayaOperasional::create($data);
        }

        $this->command->info('✅ BiayaOperasionalSeeder berhasil: ' . count($biaya) . ' data ditambahkan');
    }
}
