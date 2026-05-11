<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel
        DB::table('pelanggan')->truncate();

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        $pelanggan = [
            // Perorangan
            [
                'nama' => 'Dewi Sartika',
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'tipe' => 'perorangan',
                'npwp' => null,
                'total_transaksi' => 0,
                'total_nilai_transaksi' => 0,
                'status' => 'aktif',
                'keterangan' => 'Pelanggan tetap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi Santoso',
                'no_telepon' => '081298765432',
                'alamat' => 'Jl. Sudirman No. 45, Jakarta Selatan',
                'tipe' => 'perorangan',
                'npwp' => null,
                'total_transaksi' => 0,
                'total_nilai_transaksi' => 0,
                'status' => 'aktif',
                'keterangan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Carissa Fathinah',
                'no_telepon' => '081345678901',
                'alamat' => 'Jl. Gatot Subroto No. 78, Jakarta Selatan',
                'tipe' => 'perorangan',
                'npwp' => null,
                'total_transaksi' => 0,
                'total_nilai_transaksi' => 0,
                'status' => 'aktif',
                'keterangan' => 'Pelanggan baru',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Perusahaan
            [
                'nama' => 'PT. Maju Bersama',
                'no_telepon' => '02198765432',
                'alamat' => 'Jl. Pemuda No. 34, Jakarta Timur',
                'tipe' => 'perusahaan',
                'npwp' => '01.234.567.8-901.000',
                'total_transaksi' => 0,
                'total_nilai_transaksi' => 0,
                'status' => 'aktif',
                'keterangan' => 'Event Organizer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'CV. Kreatif Mandiri',
                'no_telepon' => '02212345678',
                'alamat' => 'Jl. Ahmad Yani No. 56, Bandung',
                'tipe' => 'perusahaan',
                'npwp' => '02.345.678.9-012.000',
                'total_transaksi' => 0,
                'total_nilai_transaksi' => 0,
                'status' => 'aktif',
                'keterangan' => 'Wedding Organizer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($pelanggan as $data) {
            Pelanggan::create($data);
        }

        $this->command->info('✅ PelangganSeeder berhasil: ' . count($pelanggan) . ' data ditambahkan');
    }
}
