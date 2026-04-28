<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Kosongkan tabel detail_peminjaman dan peminjaman
        DB::table('detail_peminjaman')->truncate();
        DB::table('peminjaman')->truncate();
        
        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();
        
        // Data peminjaman dari Januari - April 2026
        $peminjamanData = [
            // ==================== JANUARI 2026 ====================
            // Januari - Peminjaman Aktif
            [
                'invoice_number' => 'INV/MIP/2026/01/0001',
                'nama_penyewa' => 'Carissa Fathinah',
                'no_telepon' => '081234567891',
                'nama_acara' => 'Pernikahan',
                'lokasi_acara' => 'Gedung Serbaguna, Bandung',
                'tanggal_sewa' => '2026-01-05',
                'tanggal_kembali' => '2026-01-07',
                'waktu_sewa' => '08:00:00',
                'waktu_kembali' => '22:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 1850000,
                'diskon' => 50000,
                'grand_total' => 1800000,
                'keterangan' => 'Pernikahan di gedung serbaguna',
                'tanggal_pengembalian_real' => '2026-01-07 22:30:00',
                'catatan_pengembalian' => 'Barang dalam kondisi baik',
                'created_at' => '2026-01-03 10:00:00',
                'updated_at' => '2026-01-07 22:30:00',
                'detail' => [
                    ['kode_barang' => 'TV005', 'jumlah' => 1],
                    ['kode_barang' => 'PRJ001', 'jumlah' => 1],
                    ['kode_barang' => 'SCR002', 'jumlah' => 1]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/01/0002',
                'nama_penyewa' => 'Budi Santoso',
                'no_telepon' => '081234567892',
                'nama_acara' => 'Presentasi Perusahaan',
                'lokasi_acara' => 'Grand Indonesia, Jakarta',
                'tanggal_sewa' => '2026-01-10',
                'tanggal_kembali' => '2026-01-12',
                'waktu_sewa' => '09:00:00',
                'waktu_kembali' => '17:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 1150000,
                'diskon' => 0,
                'grand_total' => 1150000,
                'keterangan' => 'Presentasi klien asing',
                'tanggal_pengembalian_real' => '2026-01-12 17:30:00',
                'catatan_pengembalian' => 'Barang lengkap',
                'created_at' => '2026-01-08 14:00:00',
                'updated_at' => '2026-01-12 17:30:00',
                'detail' => [
                    ['kode_barang' => 'TV003', 'jumlah' => 1],
                    ['kode_barang' => 'PRJ002', 'jumlah' => 1],
                    ['kode_barang' => 'SCR001', 'jumlah' => 1]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/01/0003',
                'nama_penyewa' => 'Dewi Lestari',
                'no_telepon' => '081234567893',
                'nama_acara' => 'Seminar Pendidikan',
                'lokasi_acara' => 'Convention Hall, Surabaya',
                'tanggal_sewa' => '2026-01-15',
                'tanggal_kembali' => '2026-01-17',
                'waktu_sewa' => '07:00:00',
                'waktu_kembali' => '18:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 2200000,
                'diskon' => 100000,
                'grand_total' => 2100000,
                'keterangan' => 'Seminar nasional pendidikan',
                'tanggal_pengembalian_real' => '2026-01-17 18:30:00',
                'catatan_pengembalian' => 'Semua barang baik',
                'created_at' => '2026-01-12 09:00:00',
                'updated_at' => '2026-01-17 18:30:00',
                'detail' => [
                    ['kode_barang' => 'TV004', 'jumlah' => 2],
                    ['kode_barang' => 'PRJ003', 'jumlah' => 2],
                    ['kode_barang' => 'SCR002', 'jumlah' => 2],
                    ['kode_barang' => 'KBL004', 'jumlah' => 4]
                ]
            ],
            
            // ==================== FEBRUARI 2026 ====================
            [
                'invoice_number' => 'INV/MIP/2026/02/0001',
                'nama_penyewa' => 'Rudi Hartono',
                'no_telepon' => '081234567894',
                'nama_acara' => 'Launching Product',
                'lokasi_acara' => 'Mall Taman Anggrek, Jakarta',
                'tanggal_sewa' => '2026-02-01',
                'tanggal_kembali' => '2026-02-03',
                'waktu_sewa' => '10:00:00',
                'waktu_kembali' => '21:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 1550000,
                'diskon' => 50000,
                'grand_total' => 1500000,
                'keterangan' => 'Launching product elektronik',
                'tanggal_pengembalian_real' => '2026-02-03 21:30:00',
                'catatan_pengembalian' => 'Barang baik',
                'created_at' => '2026-01-28 11:00:00',
                'updated_at' => '2026-02-03 21:30:00',
                'detail' => [
                    ['kode_barang' => 'TV003', 'jumlah' => 1],
                    ['kode_barang' => 'PRJ001', 'jumlah' => 1],
                    ['kode_barang' => 'SCR001', 'jumlah' => 1],
                    ['kode_barang' => 'KBL002', 'jumlah' => 2]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/02/0002',
                'nama_penyewa' => 'Siti Nurhaliza',
                'no_telepon' => '081234567895',
                'nama_acara' => 'Pernikahan',
                'lokasi_acara' => 'Gedung Pertemuan, Depok',
                'tanggal_sewa' => '2026-02-10',
                'tanggal_kembali' => '2026-02-12',
                'waktu_sewa' => '08:00:00',
                'waktu_kembali' => '23:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 2500000,
                'diskon' => 150000,
                'grand_total' => 2350000,
                'keterangan' => 'Resepsi pernikahan',
                'tanggal_pengembalian_real' => '2026-02-12 23:30:00',
                'catatan_pengembalian' => 'Semua lengkap',
                'created_at' => '2026-02-05 09:00:00',
                'updated_at' => '2026-02-12 23:30:00',
                'detail' => [
                    ['kode_barang' => 'TV005', 'jumlah' => 2],
                    ['kode_barang' => 'PRJ002', 'jumlah' => 1],
                    ['kode_barang' => 'SCR002', 'jumlah' => 2],
                    ['kode_barang' => 'KBL002', 'jumlah' => 3],
                    ['kode_barang' => 'KBL004', 'jumlah' => 3]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/02/0003',
                'nama_penyewa' => 'Ahmad Riansyah',
                'no_telepon' => '081234567896',
                'nama_acara' => 'Workshop Digital',
                'lokasi_acara' => 'Co-working Space, Bandung',
                'tanggal_sewa' => '2026-02-20',
                'tanggal_kembali' => '2026-02-22',
                'waktu_sewa' => '09:00:00',
                'waktu_kembali' => '17:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 950000,
                'diskon' => 0,
                'grand_total' => 950000,
                'keterangan' => 'Workshop marketing',
                'tanggal_pengembalian_real' => '2026-02-22 17:30:00',
                'catatan_pengembalian' => 'Baik',
                'created_at' => '2026-02-18 10:00:00',
                'updated_at' => '2026-02-22 17:30:00',
                'detail' => [
                    ['kode_barang' => 'TV001', 'jumlah' => 1],
                    ['kode_barang' => 'PRJ001', 'jumlah' => 1],
                    ['kode_barang' => 'KBL001', 'jumlah' => 2]
                ]
            ],
            
            // ==================== MARET 2026 ====================
            [
                'invoice_number' => 'INV/MIP/2026/03/0001',
                'nama_penyewa' => 'Mega Permata',
                'no_telepon' => '081234567897',
                'nama_acara' => 'Konser Musik',
                'lokasi_acara' => 'Lapangan Banteng, Jakarta',
                'tanggal_sewa' => '2026-03-05',
                'tanggal_kembali' => '2026-03-07',
                'waktu_sewa' => '12:00:00',
                'waktu_kembali' => '23:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 3250000,
                'diskon' => 150000,
                'grand_total' => 3100000,
                'keterangan' => 'Konser band indie',
                'tanggal_pengembalian_real' => '2026-03-07 23:30:00',
                'catatan_pengembalian' => 'Barang lengkap',
                'created_at' => '2026-03-01 08:00:00',
                'updated_at' => '2026-03-07 23:30:00',
                'detail' => [
                    ['kode_barang' => 'TV005', 'jumlah' => 2],
                    ['kode_barang' => 'PRJ001', 'jumlah' => 2],
                    ['kode_barang' => 'SCR002', 'jumlah' => 3],
                    ['kode_barang' => 'KBL002', 'jumlah' => 5],
                    ['kode_barang' => 'KBL004', 'jumlah' => 5]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/03/0002',
                'nama_penyewa' => 'Carissa Fathinah',
                'no_telepon' => '081234567891',
                'nama_acara' => 'Sikrab',
                'lokasi_acara' => 'Jl. Lodaya II Nomor 3, Bandung',
                'tanggal_sewa' => '2026-03-15',
                'tanggal_kembali' => '2026-03-17',
                'waktu_sewa' => '14:00:00',
                'waktu_kembali' => '10:30:00',
                'status_pembayaran' => 'belum_bayar',
                'status_pengembalian' => 'terlambat',
                'total_harga' => 600000,
                'diskon' => 0,
                'grand_total' => 600000,
                'keterangan' => 'Sewa untuk acara sikrab (Belum dikembalikan)',
                'tanggal_pengembalian_real' => null,
                'catatan_pengembalian' => null,
                'created_at' => '2026-03-10 13:00:00',
                'updated_at' => '2026-03-17 10:30:00',
                'detail' => [
                    ['kode_barang' => 'TV002', 'jumlah' => 1],
                    ['kode_barang' => 'PRJ001', 'jumlah' => 2]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/03/0003',
                'nama_penyewa' => 'Budi Santoso',
                'no_telepon' => '081234567892',
                'nama_acara' => 'Presentasi Perusahaan',
                'lokasi_acara' => 'Grand Indonesia, Jakarta',
                'tanggal_sewa' => '2026-03-20',
                'tanggal_kembali' => '2026-03-22',
                'waktu_sewa' => '09:00:00',
                'waktu_kembali' => '17:00:00',
                'status_pembayaran' => 'dp',
                'status_pengembalian' => 'aktif',
                'total_harga' => 1150000,
                'diskon' => 50000,
                'grand_total' => 1100000,
                'keterangan' => 'Presentasi (DP sudah dibayar)',
                'tanggal_pengembalian_real' => null,
                'catatan_pengembalian' => null,
                'created_at' => '2026-03-18 10:00:00',
                'updated_at' => '2026-03-22 17:00:00',
                'detail' => [
                    ['kode_barang' => 'TV003', 'jumlah' => 1],
                    ['kode_barang' => 'PRJ002', 'jumlah' => 1],
                    ['kode_barang' => 'SCR001', 'jumlah' => 1]
                ]
            ],
            
            // ==================== APRIL 2026 ====================
            [
                'invoice_number' => 'INV/MIP/2026/04/0001',
                'nama_penyewa' => 'Dewi Lestari',
                'no_telepon' => '081234567893',
                'nama_acara' => 'Seminar Nasional',
                'lokasi_acara' => 'Convention Hall, Bali',
                'tanggal_sewa' => '2026-04-01',
                'tanggal_kembali' => '2026-04-03',
                'waktu_sewa' => '08:00:00',
                'waktu_kembali' => '18:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 2200000,
                'diskon' => 100000,
                'grand_total' => 2100000,
                'keterangan' => 'Seminar pendidikan',
                'tanggal_pengembalian_real' => '2026-04-03 18:30:00',
                'catatan_pengembalian' => 'Semua baik',
                'created_at' => '2026-03-28 09:00:00',
                'updated_at' => '2026-04-03 18:30:00',
                'detail' => [
                    ['kode_barang' => 'TV004', 'jumlah' => 2],
                    ['kode_barang' => 'PRJ003', 'jumlah' => 2],
                    ['kode_barang' => 'SCR002', 'jumlah' => 2],
                    ['kode_barang' => 'KBL005', 'jumlah' => 2]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/04/0002',
                'nama_penyewa' => 'Rudi Hartono',
                'no_telepon' => '081234567894',
                'nama_acara' => 'Launching Product',
                'lokasi_acara' => 'Mall Taman Anggrek, Jakarta',
                'tanggal_sewa' => '2026-04-05',
                'tanggal_kembali' => '2026-04-07',
                'waktu_sewa' => '10:00:00',
                'waktu_kembali' => '21:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 1550000,
                'diskon' => 50000,
                'grand_total' => 1500000,
                'keterangan' => 'Launching product',
                'tanggal_pengembalian_real' => '2026-04-07 21:30:00',
                'catatan_pengembalian' => 'Baik',
                'created_at' => '2026-04-01 10:00:00',
                'updated_at' => '2026-04-07 21:30:00',
                'detail' => [
                    ['kode_barang' => 'TV003', 'jumlah' => 1],
                    ['kode_barang' => 'PRJ001', 'jumlah' => 1],
                    ['kode_barang' => 'SCR001', 'jumlah' => 1],
                    ['kode_barang' => 'KBL002', 'jumlah' => 2]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/04/0003',
                'nama_penyewa' => 'Siti Nurhaliza',
                'no_telepon' => '081234567895',
                'nama_acara' => 'Pernikahan',
                'lokasi_acara' => 'Gedung Serbaguna, Depok',
                'tanggal_sewa' => '2026-04-10',
                'tanggal_kembali' => '2026-04-12',
                'waktu_sewa' => '08:00:00',
                'waktu_kembali' => '23:00:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 1850000,
                'diskon' => 100000,
                'grand_total' => 1750000,
                'keterangan' => 'Resepsi pernikahan',
                'tanggal_pengembalian_real' => '2026-04-12 23:30:00',
                'catatan_pengembalian' => 'Lengkap',
                'created_at' => '2026-04-05 14:00:00',
                'updated_at' => '2026-04-12 23:30:00',
                'detail' => [
                    ['kode_barang' => 'TV005', 'jumlah' => 1],
                    ['kode_barang' => 'PRJ003', 'jumlah' => 1],
                    ['kode_barang' => 'SCR002', 'jumlah' => 2],
                    ['kode_barang' => 'KBL002', 'jumlah' => 3]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/04/0004',
                'nama_penyewa' => 'Mega Permata',
                'no_telepon' => '081234567897',
                'nama_acara' => 'Konser Musik',
                'lokasi_acara' => 'Lapangan Banteng, Jakarta',
                'tanggal_sewa' => '2026-04-18',
                'tanggal_kembali' => '2026-04-20',
                'waktu_sewa' => '12:00:00',
                'waktu_kembali' => '23:00:00',
                'status_pembayaran' => 'dp',
                'status_pengembalian' => 'aktif',
                'total_harga' => 3250000,
                'diskon' => 250000,
                'grand_total' => 3000000,
                'keterangan' => 'Konser (DP sudah dibayar)',
                'tanggal_pengembalian_real' => null,
                'catatan_pengembalian' => null,
                'created_at' => '2026-04-14 09:00:00',
                'updated_at' => '2026-04-20 23:00:00',
                'detail' => [
                    ['kode_barang' => 'TV005', 'jumlah' => 2],
                    ['kode_barang' => 'PRJ001', 'jumlah' => 2],
                    ['kode_barang' => 'SCR002', 'jumlah' => 3],
                    ['kode_barang' => 'KBL002', 'jumlah' => 5],
                    ['kode_barang' => 'KBL004', 'jumlah' => 5]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/04/0005',
                'nama_penyewa' => 'Andi Wijaya',
                'no_telepon' => '081234567898',
                'nama_acara' => 'Pesta Ulang Tahun',
                'lokasi_acara' => 'Kemang, Jakarta Selatan',
                'tanggal_sewa' => '2026-04-22',
                'tanggal_kembali' => '2026-04-24',
                'waktu_sewa' => '15:00:00',
                'waktu_kembali' => '12:00:00',
                'status_pembayaran' => 'belum_bayar',
                'status_pengembalian' => 'aktif',
                'total_harga' => 850000,
                'diskon' => 0,
                'grand_total' => 850000,
                'keterangan' => 'Pesta ultah (Belum bayar)',
                'tanggal_pengembalian_real' => null,
                'catatan_pengembalian' => null,
                'created_at' => '2026-04-19 11:00:00',
                'updated_at' => '2026-04-24 12:00:00',
                'detail' => [
                    ['kode_barang' => 'TV001', 'jumlah' => 1],
                    ['kode_barang' => 'KBL001', 'jumlah' => 2],
                    ['kode_barang' => 'KBL003', 'jumlah' => 1]
                ]
            ],
            [
                'invoice_number' => 'INV/MIP/2026/04/0006',
                'nama_penyewa' => 'Carissa Fathinah',
                'no_telepon' => '081234567891',
                'nama_acara' => 'Sikrab',
                'lokasi_acara' => 'Jl. Lodaya II Nomor 3, Bandung',
                'tanggal_sewa' => '2026-04-25',
                'tanggal_kembali' => '2026-04-27',
                'waktu_sewa' => '14:00:00',
                'waktu_kembali' => '10:30:00',
                'status_pembayaran' => 'belum_bayar',
                'status_pengembalian' => 'aktif',
                'total_harga' => 1200000,
                'diskon' => 0,
                'grand_total' => 1200000,
                'keterangan' => 'Sewa terbaru',
                'tanggal_pengembalian_real' => null,
                'catatan_pengembalian' => null,
                'created_at' => '2026-04-23 14:00:00',
                'updated_at' => '2026-04-25 10:00:00',
                'detail' => [
                    ['kode_barang' => 'TV003', 'jumlah' => 1],
                    ['kode_barang' => 'PRJ002', 'jumlah' => 1],
                    ['kode_barang' => 'SCR001', 'jumlah' => 1],
                    ['kode_barang' => 'KBL002', 'jumlah' => 2]
                ]
            ]
        ];
        
        // Helper function untuk get barang id by kode
        $getBarangId = function($kode) {
            return Barang::where('kode_barang', $kode)->value('id');
        };
        
        $totalPeminjaman = 0;
        
        foreach ($peminjamanData as $data) {
            $details = $data['detail'];
            unset($data['detail']);
            
            // Create peminjaman
            $peminjaman = Peminjaman::create($data);
            $totalPeminjaman++;
            
            // Create detail peminjaman
            foreach ($details as $detail) {
                $barangId = $getBarangId($detail['kode_barang']);
                if ($barangId) {
                    $barang = Barang::find($barangId);
                    
                    DetailPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        'barang_id' => $barangId,
                        'nama_barang' => $barang->nama_barang,
                        'jenis_barang' => $barang->jenis,
                        'harga_sewa' => $barang->harga_sewa,
                        'jumlah' => $detail['jumlah'],
                        'subtotal' => $barang->harga_sewa * $detail['jumlah']
                    ]);
                    
                    // Update stok barang untuk peminjaman aktif/terlambat
                    if ($peminjaman->status_pengembalian == 'aktif' || $peminjaman->status_pengembalian == 'terlambat') {
                        $barang->decrement('tersedia', $detail['jumlah']);
                        $barang->increment('disewa', $detail['jumlah']);
                    }
                }
            }
        }
        
        $this->command->info('✅ Peminjaman seeded successfully!');
        $this->command->info('📊 Total: ' . $totalPeminjaman . ' peminjaman');
        $this->command->info('   - Januari 2026: 3 peminjaman');
        $this->command->info('   - Februari 2026: 3 peminjaman');
        $this->command->info('   - Maret 2026: 3 peminjaman');
        $this->command->info('   - April 2026: 6 peminjaman');
        $this->command->info('');
        $this->command->info('📋 Status:');
        $this->command->info('   - Aktif: ' . Peminjaman::where('status_pengembalian', 'aktif')->count() . ' peminjaman');
        $this->command->info('   - Selesai: ' . Peminjaman::where('status_pengembalian', 'selesai')->count() . ' peminjaman');
        $this->command->info('   - Terlambat: ' . Peminjaman::where('status_pengembalian', 'terlambat')->count() . ' peminjaman');
    }
}