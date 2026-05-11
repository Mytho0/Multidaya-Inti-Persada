<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Pelanggan;
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

        // Kosongkan tabel
        DB::table('detail_peminjaman')->truncate();
        DB::table('peminjaman')->truncate();

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();

        // Ambil ID pelanggan yang sudah ada
        $pelangganIds = Pelanggan::pluck('id')->toArray();

        // Ambil data barang untuk referensi
        $barangList = Barang::all();

        // Reset auto increment
        DB::statement('ALTER TABLE peminjaman AUTO_INCREMENT = 1');

        // Buat data peminjaman dengan invoice number manual
        $tahun = date('Y');
        $bulan = date('m');

        $peminjamanData = [
            [
                'invoice_number' => 'INV/MIP/' . $tahun . '/' . $bulan . '/0001',
                'pelanggan_id' => $pelangganIds[0] ?? 1,
                'nama_penyewa' => 'Dewi Sartika',
                'no_telepon' => '081234567890',
                'customer_whatsapp' => '6281234567890',
                'nama_acara' => 'Wedding Anniversary',
                'lokasi_acara' => 'Grand Ballroom Hotel Mulia',
                'tanggal_sewa' => '2026-05-15',
                'tanggal_kembali' => '2026-05-17',
                'waktu_sewa' => '08:00',
                'waktu_kembali' => '22:00',
                'status_pembayaran' => 'dp',
                'status_pengembalian' => 'aktif',
                'total_harga' => 0,
                'diskon' => 0,
                'grand_total' => 0,
                'ppn' => 0.11,
                'total_ppn' => 0,
                'grand_total_with_ppn' => 0,
                'jatuh_tempo_pembayaran' => '2026-05-22',
                'keterangan' => 'Acara pernikahan, perlu proyektor dan sound system',
                'created_by' => 1,
                'created_at' => '2026-05-10 10:00:00',
                'updated_at' => now(),
            ],
            [
                'invoice_number' => 'INV/MIP/' . $tahun . '/' . $bulan . '/0002',
                'pelanggan_id' => $pelangganIds[1] ?? 2,
                'nama_penyewa' => 'Budi Santoso',
                'no_telepon' => '081298765432',
                'customer_whatsapp' => '6281298765432',
                'nama_acara' => 'Seminar Digital Marketing',
                'lokasi_acara' => 'Hotel Santika',
                'tanggal_sewa' => '2026-04-20',
                'tanggal_kembali' => '2026-04-22',
                'waktu_sewa' => '09:00',
                'waktu_kembali' => '18:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'selesai',
                'total_harga' => 0,
                'diskon' => 0,
                'grand_total' => 0,
                'ppn' => 0.11,
                'total_ppn' => 0,
                'grand_total_with_ppn' => 0,
                'jatuh_tempo_pembayaran' => '2026-04-27',
                'keterangan' => 'Seminar untuk 100 peserta',
                'tanggal_pengembalian_real' => '2026-04-22 18:30:00',
                'kondisi_barang' => 'baik',
                'denda' => 0,
                'created_by' => 1,
                'created_at' => '2026-04-15 14:30:00',
                'updated_at' => now(),
            ],
            [
                'invoice_number' => 'INV/MIP/' . $tahun . '/' . $bulan . '/0003',
                'pelanggan_id' => $pelangganIds[2] ?? 3,
                'nama_penyewa' => 'Carissa Fathinah',
                'no_telepon' => '081345678901',
                'customer_whatsapp' => '6281345678901',
                'nama_acara' => 'Birthday Party',
                'lokasi_acara' => 'Rumah Pribadi',
                'tanggal_sewa' => '2026-05-01',
                'tanggal_kembali' => '2026-05-03',
                'waktu_sewa' => '10:00',
                'waktu_kembali' => '20:00',
                'status_pembayaran' => 'belum_bayar',
                'status_pengembalian' => 'terlambat',
                'total_harga' => 0,
                'diskon' => 0,
                'grand_total' => 0,
                'ppn' => 0.11,
                'total_ppn' => 0,
                'grand_total_with_ppn' => 0,
                'jatuh_tempo_pembayaran' => '2026-05-08',
                'keterangan' => 'Party ulang tahun',
                'created_by' => 1,
                'created_at' => '2026-04-28 09:00:00',
                'updated_at' => now(),
            ],
            [
                'invoice_number' => 'INV/MIP/' . $tahun . '/' . $bulan . '/0004',
                'pelanggan_id' => $pelangganIds[3] ?? 4,
                'nama_penyewa' => 'PT. Maju Bersama',
                'no_telepon' => '02198765432',
                'customer_whatsapp' => '6282198765432',
                'nama_acara' => 'Corporate Gathering',
                'lokasi_acara' => 'Hotel Indonesia Kempinski',
                'tanggal_sewa' => '2026-05-18',
                'tanggal_kembali' => '2026-05-20',
                'waktu_sewa' => '08:00',
                'waktu_kembali' => '22:00',
                'status_pembayaran' => 'lunas',
                'status_pengembalian' => 'aktif',
                'total_harga' => 0,
                'diskon' => 0,
                'grand_total' => 0,
                'ppn' => 0.11,
                'total_ppn' => 0,
                'grand_total_with_ppn' => 0,
                'jatuh_tempo_pembayaran' => '2026-05-25',
                'keterangan' => 'Event gathering perusahaan',
                'created_by' => 1,
                'created_at' => '2026-05-12 11:00:00',
                'updated_at' => now(),
            ],
        ];

        // Insert peminjaman
        foreach ($peminjamanData as $index => $data) {
            // Ambil data peminjaman tanpa invoice_number dulu
            $peminjaman = Peminjaman::create($data);

            // Detail barang untuk setiap peminjaman
            $detailsForThis = $this->getDetailsForPeminjaman($index + 1, $barangList);

            $totalHarga = 0;
            foreach ($detailsForThis as $detail) {
                $detail['peminjaman_id'] = $peminjaman->id;
                DetailPeminjaman::create($detail);
                $totalHarga += $detail['subtotal'];

                // Update stok barang
                $barang = Barang::find($detail['barang_id']);
                if ($barang) {
                    $barang->decrement('tersedia', $detail['jumlah']);
                    $barang->increment('disewa', $detail['jumlah']);
                }
            }

            // Update total harga dan grand total
            $grandTotal = $totalHarga - ($data['diskon'] ?? 0);
            $totalPpn = round($grandTotal * 0.11);
            $grandTotalWithPpn = $grandTotal + $totalPpn;

            $peminjaman->update([
                'total_harga' => $totalHarga,
                'grand_total' => $grandTotal,
                'total_ppn' => $totalPpn,
                'grand_total_with_ppn' => $grandTotalWithPpn,
            ]);

            // Update total_nilai_transaksi pelanggan
            $pelanggan = Pelanggan::find($data['pelanggan_id']);
            if ($pelanggan) {
                $pelanggan->increment('total_transaksi');
                $pelanggan->increment('total_nilai_transaksi', $grandTotalWithPpn);
            }
        }

        $this->command->info('✅ PeminjamanSeeder berhasil: ' . count($peminjamanData) . ' data peminjaman ditambahkan');
    }

    private function getDetailsForPeminjaman($index, $barangList)
    {
        // Mapping barang berdasarkan kode_barang
        $barangMap = [];
        foreach ($barangList as $b) {
            $barangMap[$b->kode_barang] = $b;
        }

        $details = [
            1 => [ // Peminjaman 1: Dewi - 2 proyektor + 1 layar
                [
                    'barang_id' => $barangMap['PRJ001']->id ?? 1,
                    'nama_barang' => 'EPSON 6000 LUMENS',
                    'jenis_barang' => 'Proyektor',
                    'harga_sewa' => 1000000,
                    'jumlah' => 1,
                    'subtotal' => 1000000,
                ],
                [
                    'barang_id' => $barangMap['PRJ002']->id ?? 2,
                    'nama_barang' => 'BENQ 4000 LUMENS',
                    'jenis_barang' => 'Proyektor',
                    'harga_sewa' => 750000,
                    'jumlah' => 1,
                    'subtotal' => 750000,
                ],
                [
                    'barang_id' => $barangMap['SCR001']->id ?? 3,
                    'nama_barang' => 'SCREEN 2x3',
                    'jenis_barang' => 'Layar',
                    'harga_sewa' => 300000,
                    'jumlah' => 1,
                    'subtotal' => 300000,
                ],
            ],
            2 => [ // Peminjaman 2: Budi - 2 TV + kabel
                [
                    'barang_id' => $barangMap['TV003']->id ?? 4,
                    'nama_barang' => 'TV 55 INCH',
                    'jenis_barang' => 'TV',
                    'harga_sewa' => 550000,
                    'jumlah' => 2,
                    'subtotal' => 1100000,
                ],
                [
                    'barang_id' => $barangMap['KBL001']->id ?? 5,
                    'nama_barang' => 'KABEL HDMI 2 METER',
                    'jenis_barang' => 'Kabel',
                    'harga_sewa' => 25000,
                    'jumlah' => 2,
                    'subtotal' => 50000,
                ],
            ],
            3 => [ // Peminjaman 3: Carissa - 1 TV + kabel
                [
                    'barang_id' => $barangMap['TV001']->id ?? 6,
                    'nama_barang' => 'TV 43 INCH',
                    'jenis_barang' => 'TV',
                    'harga_sewa' => 300000,
                    'jumlah' => 1,
                    'subtotal' => 300000,
                ],
                [
                    'barang_id' => $barangMap['KBL004']->id ?? 7,
                    'nama_barang' => 'KABEL POWER EXTENSION',
                    'jenis_barang' => 'Kabel',
                    'harga_sewa' => 15000,
                    'jumlah' => 2,
                    'subtotal' => 30000,
                ],
            ],
            4 => [ // Peminjaman 4: PT Maju Bersama - 1 proyektor + screen + sound
                [
                    'barang_id' => $barangMap['PRJ003']->id ?? 8,
                    'nama_barang' => 'PANASONIC 5000 LUMENS',
                    'jenis_barang' => 'Proyektor',
                    'harga_sewa' => 850000,
                    'jumlah' => 1,
                    'subtotal' => 850000,
                ],
                [
                    'barang_id' => $barangMap['SCR002']->id ?? 9,
                    'nama_barang' => 'SCREEN 3x4',
                    'jenis_barang' => 'Layar',
                    'harga_sewa' => 400000,
                    'jumlah' => 1,
                    'subtotal' => 400000,
                ],
                [
                    'barang_id' => $barangMap['TV005']->id ?? 10,
                    'nama_barang' => 'TV 75 INCH',
                    'jenis_barang' => 'TV',
                    'harga_sewa' => 1200000,
                    'jumlah' => 1,
                    'subtotal' => 1200000,
                ],
                [
                    'barang_id' => $barangMap['KBL002']->id ?? 11,
                    'nama_barang' => 'KABEL HDMI 5 METER',
                    'jenis_barang' => 'Kabel',
                    'harga_sewa' => 35000,
                    'jumlah' => 3,
                    'subtotal' => 105000,
                ],
            ],
        ];

        return $details[$index] ?? [];
    }
}
