<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Keuangan;
use App\Models\Peminjaman;

class KeuanganSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Kosongkan tabel keuangan
        DB::table('keuangan')->truncate();
        
        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();
        
        // ==================== PENDAPATAN DARI PEMINJAMAN (SESUAI DENGAN PEMINJAMAN YANG SELESAI) ====================
        // Peminjaman yang sudah selesai (status_pengembalian = 'selesai')
        // Data ini akan tersinkronisasi otomatis dari peminjaman yang selesai
        
        $peminjamanSelesai = Peminjaman::where('status_pengembalian', 'selesai')->get();
        
        foreach ($peminjamanSelesai as $peminjaman) {
            // Cek apakah sudah ada di tabel keuangan
            $exists = Keuangan::where('peminjaman_id', $peminjaman->id)
                ->where('sumber', 'sewa')
                ->exists();
            
            if (!$exists) {
                Keuangan::create([
                    'peminjaman_id' => $peminjaman->id,
                    'jenis' => 'pendapatan',
                    'sumber' => 'sewa',
                    'kategori' => 'Penyewaan Barang',
                    'deskripsi' => 'Pembayaran sewa - ' . $peminjaman->invoice_number,
                    'jumlah' => $peminjaman->grand_total,
                    'tanggal' => $peminjaman->tanggal_pengembalian_real ?? $peminjaman->tanggal_kembali,
                    'referensi' => $peminjaman->invoice_number,
                    'status' => 'verified',
                    'created_at' => $peminjaman->tanggal_pengembalian_real ?? $peminjaman->tanggal_kembali,
                    'updated_at' => now(),
                    'created_by' => 1
                ]);
            }
        }
        
        // ==================== BIAYA OPERASIONAL ====================
        $biayaOperasional = [
            // Januari 2026
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Gaji Karyawan',
                'deskripsi' => 'Gaji karyawan Januari 2026',
                'jumlah' => 15000000,
                'tanggal' => '2026-01-25',
                'status' => 'verified',
                'created_at' => '2026-01-25 10:00:00',
                'updated_at' => '2026-01-25 10:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Listrik dan Air',
                'deskripsi' => 'Pembayaran listrik dan air Januari',
                'jumlah' => 2500000,
                'tanggal' => '2026-01-20',
                'status' => 'verified',
                'created_at' => '2026-01-20 09:00:00',
                'updated_at' => '2026-01-20 09:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Internet dan Telepon',
                'deskripsi' => 'Tagihan internet dan telepon Januari',
                'jumlah' => 1000000,
                'tanggal' => '2026-01-15',
                'status' => 'verified',
                'created_at' => '2026-01-15 11:00:00',
                'updated_at' => '2026-01-15 11:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Transportasi',
                'deskripsi' => 'Biaya pengiriman barang Januari',
                'jumlah' => 1500000,
                'tanggal' => '2026-01-10',
                'status' => 'verified',
                'created_at' => '2026-01-10 14:00:00',
                'updated_at' => '2026-01-10 14:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Sewa Tempat',
                'deskripsi' => 'Sewa ruko/gudang Januari',
                'jumlah' => 5000000,
                'tanggal' => '2026-01-05',
                'status' => 'verified',
                'created_at' => '2026-01-05 08:00:00',
                'updated_at' => '2026-01-05 08:00:00'
            ],
            
            // Februari 2026
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Gaji Karyawan',
                'deskripsi' => 'Gaji karyawan Februari 2026',
                'jumlah' => 15000000,
                'tanggal' => '2026-02-25',
                'status' => 'verified',
                'created_at' => '2026-02-25 10:00:00',
                'updated_at' => '2026-02-25 10:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Listrik dan Air',
                'deskripsi' => 'Pembayaran listrik dan air Februari',
                'jumlah' => 2800000,
                'tanggal' => '2026-02-20',
                'status' => 'verified',
                'created_at' => '2026-02-20 09:00:00',
                'updated_at' => '2026-02-20 09:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Transportasi',
                'deskripsi' => 'Biaya pengiriman barang Februari',
                'jumlah' => 1800000,
                'tanggal' => '2026-02-15',
                'status' => 'verified',
                'created_at' => '2026-02-15 14:00:00',
                'updated_at' => '2026-02-15 14:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Sewa Tempat',
                'deskripsi' => 'Sewa ruko/gudang Februari',
                'jumlah' => 5000000,
                'tanggal' => '2026-02-05',
                'status' => 'verified',
                'created_at' => '2026-02-05 08:00:00',
                'updated_at' => '2026-02-05 08:00:00'
            ],
            
            // Maret 2026
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Gaji Karyawan',
                'deskripsi' => 'Gaji karyawan Maret 2026',
                'jumlah' => 15000000,
                'tanggal' => '2026-03-25',
                'status' => 'verified',
                'created_at' => '2026-03-25 10:00:00',
                'updated_at' => '2026-03-25 10:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Listrik dan Air',
                'deskripsi' => 'Pembayaran listrik dan air Maret',
                'jumlah' => 2600000,
                'tanggal' => '2026-03-20',
                'status' => 'verified',
                'created_at' => '2026-03-20 09:00:00',
                'updated_at' => '2026-03-20 09:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Perawatan',
                'deskripsi' => 'Service dan perawatan barang elektronik',
                'jumlah' => 3500000,
                'tanggal' => '2026-03-18',
                'status' => 'verified',
                'created_at' => '2026-03-18 13:00:00',
                'updated_at' => '2026-03-18 13:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Transportasi',
                'deskripsi' => 'Biaya pengiriman barang Maret',
                'jumlah' => 2000000,
                'tanggal' => '2026-03-10',
                'status' => 'verified',
                'created_at' => '2026-03-10 14:00:00',
                'updated_at' => '2026-03-10 14:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Sewa Tempat',
                'deskripsi' => 'Sewa ruko/gudang Maret',
                'jumlah' => 5000000,
                'tanggal' => '2026-03-05',
                'status' => 'verified',
                'created_at' => '2026-03-05 08:00:00',
                'updated_at' => '2026-03-05 08:00:00'
            ],
            
            // April 2026
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Gaji Karyawan',
                'deskripsi' => 'Gaji karyawan April 2026',
                'jumlah' => 15000000,
                'tanggal' => '2026-04-25',
                'status' => 'verified',
                'created_at' => '2026-04-25 10:00:00',
                'updated_at' => '2026-04-25 10:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Listrik dan Air',
                'deskripsi' => 'Pembayaran listrik dan air April',
                'jumlah' => 2700000,
                'tanggal' => '2026-04-20',
                'status' => 'verified',
                'created_at' => '2026-04-20 09:00:00',
                'updated_at' => '2026-04-20 09:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Transportasi',
                'deskripsi' => 'Biaya pengiriman barang April',
                'jumlah' => 2200000,
                'tanggal' => '2026-04-15',
                'status' => 'verified',
                'created_at' => '2026-04-15 14:00:00',
                'updated_at' => '2026-04-15 14:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'operasional',
                'kategori' => 'Sewa Tempat',
                'deskripsi' => 'Sewa ruko/gudang April',
                'jumlah' => 5000000,
                'tanggal' => '2026-04-05',
                'status' => 'verified',
                'created_at' => '2026-04-05 08:00:00',
                'updated_at' => '2026-04-05 08:00:00'
            ]
        ];
        
        // Insert biaya operasional
        foreach ($biayaOperasional as $item) {
            Keuangan::create($item);
        }
        
        // ==================== BIAYA PROMOSI ====================
        $biayaPromosi = [
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'promosi',
                'kategori' => 'Iklan Online',
                'deskripsi' => 'Iklan Google Ads Januari',
                'jumlah' => 2000000,
                'tanggal' => '2026-01-10',
                'status' => 'verified',
                'created_at' => '2026-01-10 12:00:00',
                'updated_at' => '2026-01-10 12:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'promosi',
                'kategori' => 'Iklan Online',
                'deskripsi' => 'Iklan Google Ads Februari',
                'jumlah' => 2000000,
                'tanggal' => '2026-02-10',
                'status' => 'verified',
                'created_at' => '2026-02-10 12:00:00',
                'updated_at' => '2026-02-10 12:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'promosi',
                'kategori' => 'Iklan Online',
                'deskripsi' => 'Iklan Google Ads Maret',
                'jumlah' => 2500000,
                'tanggal' => '2026-03-10',
                'status' => 'verified',
                'created_at' => '2026-03-10 12:00:00',
                'updated_at' => '2026-03-10 12:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'promosi',
                'kategori' => 'Iklan Online',
                'deskripsi' => 'Iklan Instagram Ads April',
                'jumlah' => 3000000,
                'tanggal' => '2026-04-10',
                'status' => 'verified',
                'created_at' => '2026-04-10 12:00:00',
                'updated_at' => '2026-04-10 12:00:00'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'promosi',
                'kategori' => 'Event',
                'deskripsi' => 'Sponsorship event teknologi',
                'jumlah' => 5000000,
                'tanggal' => '2026-03-15',
                'status' => 'verified',
                'created_at' => '2026-03-15 15:00:00',
                'updated_at' => '2026-03-15 15:00:00'
            ]
        ];
        
        foreach ($biayaPromosi as $item) {
            Keuangan::create($item);
        }
        
        // ==================== BIAYA INVENTARIS (Pembelian Barang Baru) ====================
        $biayaInventaris = [
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'inventaris',
                'kategori' => 'Pembelian Barang',
                'deskripsi' => 'Pembelian TV LG 75 inch baru',
                'jumlah' => 4500000,
                'tanggal' => '2026-02-20',
                'status' => 'verified',
                'created_at' => '2026-02-20 11:00:00',
                'updated_at' => '2026-02-20 11:00:00',
                'referensi' => 'PO-001'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'inventaris',
                'kategori' => 'Pembelian Barang',
                'deskripsi' => 'Pembelian Proyektor Epson baru',
                'jumlah' => 8500000,
                'tanggal' => '2026-03-10',
                'status' => 'verified',
                'created_at' => '2026-03-10 11:00:00',
                'updated_at' => '2026-03-10 11:00:00',
                'referensi' => 'PO-002'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'inventaris',
                'kategori' => 'Pembelian Barang',
                'deskripsi' => 'Pembelian Kabel HDMI dan VGA',
                'jumlah' => 1500000,
                'tanggal' => '2026-04-05',
                'status' => 'verified',
                'created_at' => '2026-04-05 11:00:00',
                'updated_at' => '2026-04-05 11:00:00',
                'referensi' => 'PO-003'
            ],
            [
                'jenis' => 'pengeluaran',
                'sumber' => 'inventaris',
                'kategori' => 'Pembelian Barang',
                'deskripsi' => 'Pembelian Screen 3x4 baru',
                'jumlah' => 1200000,
                'tanggal' => '2026-04-15',
                'status' => 'verified',
                'created_at' => '2026-04-15 11:00:00',
                'updated_at' => '2026-04-15 11:00:00',
                'referensi' => 'PO-004'
            ]
        ];
        
        foreach ($biayaInventaris as $item) {
            Keuangan::create($item);
        }
        
        // ==================== PENDAPATAN LAIN-LAIN ====================
        $pendapatanLain = [
            [
                'jenis' => 'pendapatan',
                'sumber' => 'promosi',
                'kategori' => 'Jasa Instalasi',
                'deskripsi' => 'Biaya instalasi dan setting barang',
                'jumlah' => 500000,
                'tanggal' => '2026-03-20',
                'status' => 'verified',
                'created_at' => '2026-03-20 16:00:00',
                'updated_at' => '2026-03-20 16:00:00'
            ],
            [
                'jenis' => 'pendapatan',
                'sumber' => 'promosi',
                'kategori' => 'Jasa Instalasi',
                'deskripsi' => 'Biaya instalasi untuk event',
                'jumlah' => 750000,
                'tanggal' => '2026-04-18',
                'status' => 'verified',
                'created_at' => '2026-04-18 16:00:00',
                'updated_at' => '2026-04-18 16:00:00'
            ]
        ];
        
        foreach ($pendapatanLain as $item) {
            Keuangan::create($item);
        }
        
        $this->command->info('✅ Keuangan seeded successfully!');
        $this->command->info('');
        $this->command->info('📊 Ringkasan Data Keuangan:');
        $this->command->info('   - Pendapatan dari Sewa: ' . Keuangan::pendapatan()->dariSewa()->count() . ' transaksi');
        $this->command->info('   - Biaya Operasional: ' . Keuangan::pengeluaran()->dariOperasional()->count() . ' transaksi');
        $this->command->info('   - Biaya Promosi: ' . Keuangan::pengeluaran()->dariPromosi()->count() . ' transaksi');
        $this->command->info('   - Biaya Inventaris: ' . Keuangan::pengeluaran()->dariInventaris()->count() . ' transaksi');
        $this->command->info('   - Pendapatan Lain: ' . Keuangan::pendapatan()->where('sumber', 'promosi')->count() . ' transaksi');
        $this->command->info('');
        $this->command->info('💰 Total Pendapatan: Rp ' . number_format(Keuangan::pendapatan()->sum('jumlah'), 0, ',', '.'));
        $this->command->info('📉 Total Pengeluaran: Rp ' . number_format(Keuangan::pengeluaran()->sum('jumlah'), 0, ',', '.'));
        $this->command->info('📈 Laba Bersih: Rp ' . number_format(Keuangan::pendapatan()->sum('jumlah') - Keuangan::pengeluaran()->sum('jumlah'), 0, ',', '.'));
    }
}