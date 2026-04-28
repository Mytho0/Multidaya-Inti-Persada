<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Kosongkan tabel
        DB::table('barang')->truncate();
        
        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();
        
        $barang = [
            // ==================== PROYEKTOR (3 item) ====================
            [
                'kode_barang' => 'PRJ001',
                'nama_barang' => 'EPSON 6000 LUMENS',
                'jenis' => 'Proyektor',
                'harga_sewa' => 1000000,
                'stok' => 5,
                'tersedia' => 3,
                'disewa' => 2,
                'status' => 'aktif',
                'deskripsi' => 'Proyektor Epson 6000 lumens, cocok untuk presentasi di ruangan besar dengan cahaya terang. Resolusi Full HD, kontras tinggi.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'PRJ002',
                'nama_barang' => 'BENQ 4000 LUMENS',
                'jenis' => 'Proyektor',
                'harga_sewa' => 750000,
                'stok' => 4,
                'tersedia' => 2,
                'disewa' => 2,
                'status' => 'aktif',
                'deskripsi' => 'Proyektor BenQ 4000 lumens, cocok untuk presentasi di ruangan sedang. Resolusi WXGA, warna tajam.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'PRJ003',
                'nama_barang' => 'PANASONIC 5000 LUMENS',
                'jenis' => 'Proyektor',
                'harga_sewa' => 850000,
                'stok' => 3,
                'tersedia' => 1,
                'disewa' => 2,
                'status' => 'aktif',
                'deskripsi' => 'Proyektor Panasonic 5000 lumens, cocok untuk ruangan besar dan acara outdoor. Resolusi Full HD, tahan lama.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // ==================== LAYAR / SCREEN (2 item) ====================
            [
                'kode_barang' => 'SCR001',
                'nama_barang' => 'SCREEN 2x3',
                'jenis' => 'Layar',
                'harga_sewa' => 300000,
                'stok' => 10,
                'tersedia' => 7,
                'disewa' => 3,
                'status' => 'aktif',
                'deskripsi' => 'Screen/Layar proyektor ukuran 2x3 meter, ideal untuk presentasi medium. Mudah dipasang dan dibawa.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'SCR002',
                'nama_barang' => 'SCREEN 3x4',
                'jenis' => 'Layar',
                'harga_sewa' => 400000,
                'stok' => 8,
                'tersedia' => 5,
                'disewa' => 3,
                'status' => 'aktif',
                'deskripsi' => 'Screen/Layar proyektor ukuran 3x4 meter, untuk presentasi large. Kualitas bahan tinggi, tidak mudah robek.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // ==================== TV (5 item) ====================
            [
                'kode_barang' => 'TV001',
                'nama_barang' => 'TV 43 INCH',
                'jenis' => 'TV',
                'harga_sewa' => 300000,
                'stok' => 6,
                'tersedia' => 4,
                'disewa' => 2,
                'status' => 'aktif',
                'deskripsi' => 'TV 43 inch - Panasonic/LG, Smart TV dengan kualitas gambar HD. Dilengkapi remote control.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'TV002',
                'nama_barang' => 'TV 50 INCH',
                'jenis' => 'TV',
                'harga_sewa' => 450000,
                'stok' => 6,
                'tersedia' => 3,
                'disewa' => 3,
                'status' => 'aktif',
                'deskripsi' => 'TV 50 inch - Panasonic/LG, Smart TV 4K UHD. Gambar jernih, suara jernih.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'TV003',
                'nama_barang' => 'TV 55 INCH',
                'jenis' => 'TV',
                'harga_sewa' => 550000,
                'stok' => 5,
                'tersedia' => 2,
                'disewa' => 3,
                'status' => 'aktif',
                'deskripsi' => 'TV 55 inch - Panasonic/LG, Smart TV 4K UHD dengan HDR. Cocok untuk home theater.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'TV004',
                'nama_barang' => 'TV 65 INCH',
                'jenis' => 'TV',
                'harga_sewa' => 700000,
                'stok' => 4,
                'tersedia' => 2,
                'disewa' => 2,
                'status' => 'aktif',
                'deskripsi' => 'TV 65 inch - Panasonic/LG, Smart TV 4K UHD dengan OLED panel. Kualitas gambar premium.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'TV005',
                'nama_barang' => 'TV 75 INCH',
                'jenis' => 'TV',
                'harga_sewa' => 1200000,
                'stok' => 3,
                'tersedia' => 1,
                'disewa' => 2,
                'status' => 'aktif',
                'deskripsi' => 'TV 75 inch - Panasonic/LG, Smart TV 4K UHD dengan teknologi terbaru. Layar besar, pengalaman sinema.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            
            // ==================== KABEL (6 item) ====================
            [
                'kode_barang' => 'KBL001',
                'nama_barang' => 'KABEL HDMI 2 METER',
                'jenis' => 'Kabel',
                'harga_sewa' => 25000,
                'stok' => 20,
                'tersedia' => 15,
                'disewa' => 5,
                'status' => 'aktif',
                'deskripsi' => 'Kabel HDMI panjang 2 meter, untuk koneksi TV ke device. Kualitas tinggi, support 4K.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'KBL002',
                'nama_barang' => 'KABEL HDMI 5 METER',
                'jenis' => 'Kabel',
                'harga_sewa' => 35000,
                'stok' => 15,
                'tersedia' => 10,
                'disewa' => 5,
                'status' => 'aktif',
                'deskripsi' => 'Kabel HDMI panjang 5 meter, untuk koneksi jarak jauh. Support 4K dan 3D.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'KBL003',
                'nama_barang' => 'KABEL VGA 3 METER',
                'jenis' => 'Kabel',
                'harga_sewa' => 20000,
                'stok' => 15,
                'tersedia' => 12,
                'disewa' => 3,
                'status' => 'aktif',
                'deskripsi' => 'Kabel VGA panjang 3 meter, untuk koneksi proyektor ke laptop.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'KBL004',
                'nama_barang' => 'KABEL POWER EXTENSION',
                'jenis' => 'Kabel',
                'harga_sewa' => 15000,
                'stok' => 25,
                'tersedia' => 18,
                'disewa' => 7,
                'status' => 'aktif',
                'deskripsi' => 'Kabel extension power panjang 5 meter. Stopkontak 4 lubang.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'KBL005',
                'nama_barang' => 'KABEL LAN 10 METER',
                'jenis' => 'Kabel',
                'harga_sewa' => 15000,
                'stok' => 10,
                'tersedia' => 8,
                'disewa' => 2,
                'status' => 'aktif',
                'deskripsi' => 'Kabel LAN untuk koneksi internet. Kecepatan tinggi, CAT6.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_barang' => 'KBL006',
                'nama_barang' => 'KABEL AUDIO JACK',
                'jenis' => 'Kabel',
                'harga_sewa' => 10000,
                'stok' => 20,
                'tersedia' => 15,
                'disewa' => 5,
                'status' => 'aktif',
                'deskripsi' => 'Kabel audio 3.5mm jack untuk koneksi sound system ke laptop/HP.',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($barang as $item) {
            Barang::create($item);
        }
        
        $this->command->info('✅ Data barang berhasil ditambahkan!');
        $this->command->info('📊 Total: ' . count($barang) . ' barang');
        $this->command->info('   - Proyektor: 3 item');
        $this->command->info('   - Layar/Screen: 2 item');
        $this->command->info('   - TV: 5 item (43, 50, 55, 65, 75 inch)');
        $this->command->info('   - Kabel: 6 item');
    }
}