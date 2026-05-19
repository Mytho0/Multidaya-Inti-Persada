<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class BarangRecommendationService
{
    public function runAndSave(): array
    {
        // STEP 1: Ambil data barang + histori
        $barangData = $this->collectData();
        if (empty($barangData)) return [];

        // STEP 2: Feature Engineering
        $data = $this->featureEngineering($barangData);

        // STEP 3: Hitung Moving Average & tren
        $data = $this->hitungTren($data);

        // STEP 4: Prediksi kebutuhan (Linear Regression sederhana)
        $data = $this->prediksiKebutuhan($data);

        // STEP 5: Labeling & generate rekomendasi
        $results = $this->generateRekomendasi($data);

        // STEP 6: Simpan ke DB
        $this->saveToDatabase($results);

        return $results;
    }

    private function collectData(): array
    {
        return DB::select("
            SELECT
                b.id AS barang_id,
                b.nama_barang,
                b.jenis AS jenis_barang,
                b.harga_sewa,
                b.stok,
                b.tersedia,
                b.disewa,
                COUNT(DISTINCT dp.peminjaman_id)     AS total_transaksi,
                COALESCE(SUM(dp.jumlah), 0)          AS total_unit_dipinjam,
                COALESCE(SUM(dp.subtotal), 0)        AS total_revenue,
                COUNT(DISTINCT MONTH(p.tanggal_sewa)) AS jumlah_bulan_aktif,
                MAX(p.tanggal_sewa)                  AS transaksi_terakhir
            FROM barang b
            LEFT JOIN detail_peminjaman dp ON dp.barang_id = b.id
            LEFT JOIN peminjaman p ON p.id = dp.peminjaman_id
            WHERE b.status = 'aktif'
            GROUP BY b.id, b.nama_barang, b.jenis,
                     b.harga_sewa, b.stok, b.tersedia, b.disewa
        ");
    }

    private function featureEngineering(array $data): array
    {
        $result = [];
        foreach ($data as $b) {
            $stok        = max((int)$b->stok, 1);
            $tersedia    = (int)$b->tersedia;
            $disewa      = (int)$b->disewa;
            $totalUnit   = (int)$b->total_unit_dipinjam;
            $bulanAktif  = max((int)$b->jumlah_bulan_aktif, 1);

            $utilisasiRate    = $disewa / $stok;
            $shortageRisk     = $stok > 0 ? $disewa / $stok : 0;
            $avgDemandMonthly = $totalUnit / $bulanAktif;
            $stokCoverage     = $avgDemandMonthly > 0 ? $tersedia / $avgDemandMonthly : 99;
            $revenuePerUnit   = $totalUnit > 0 ? (float)$b->total_revenue / $totalUnit : 0;

            $result[] = [
                'barang_id'          => $b->barang_id,
                'nama_barang'        => $b->nama_barang,
                'jenis_barang'       => $b->jenis_barang,
                'harga_sewa'         => (float)$b->harga_sewa,
                'stok'               => $stok,
                'tersedia'           => $tersedia,
                'disewa'             => $disewa,
                'total_transaksi'    => (int)$b->total_transaksi,
                'total_unit'         => $totalUnit,
                'total_revenue'      => (float)$b->total_revenue,
                'jumlah_bulan_aktif' => $bulanAktif,
                'transaksi_terakhir' => $b->transaksi_terakhir,
                'utilisasi_rate'     => $utilisasiRate,
                'shortage_risk'      => $shortageRisk,
                'avg_demand_monthly' => $avgDemandMonthly,
                'stok_coverage'      => $stokCoverage,
                'revenue_per_unit'   => $revenuePerUnit,
            ];
        }
        return $result;
    }

    private function hitungTren(array $data): array
    {
        // Ambil data tren per bulan per barang (3 bulan terakhir)
        $trenData = DB::select("
            SELECT
                dp.barang_id,
                YEAR(p.tanggal_sewa)  AS tahun,
                MONTH(p.tanggal_sewa) AS bulan,
                SUM(dp.jumlah)        AS unit_dipinjam
            FROM detail_peminjaman dp
            JOIN peminjaman p ON p.id = dp.peminjaman_id
            WHERE p.tanggal_sewa >= DATE_SUB(NOW(), INTERVAL 3 MONTH)
            GROUP BY dp.barang_id, tahun, bulan
            ORDER BY dp.barang_id, tahun, bulan
        ");

        // Kelompokkan per barang_id
        $trenPerBarang = [];
        foreach ($trenData as $t) {
            $trenPerBarang[$t->barang_id][] = (int)$t->unit_dipinjam;
        }

        foreach ($data as &$row) {
            $tren = $trenPerBarang[$row['barang_id']] ?? [];

            if (count($tren) >= 2) {
                // Moving average 3 bulan
                $movingAvg = array_sum($tren) / count($tren);

                // Growth rate: (bulan terakhir - bulan pertama) / bulan pertama
                $first = $tren[0] ?: 1;
                $last  = end($tren);
                $growthRate = ($last - $first) / $first;
            } else {
                $movingAvg  = $row['avg_demand_monthly'];
                $growthRate = 0;
            }

            $row['moving_avg_3bulan'] = round($movingAvg, 2);
            $row['demand_growth']     = round($growthRate, 4);
            $row['tren_data']         = $tren;
        }
        return $data;
    }

    private function prediksiKebutuhan(array $data): array
    {
        foreach ($data as &$row) {
            // Prediksi permintaan bulan depan
            // Formula: avg_demand * (1 + growth_rate) * safety_factor
            $safetyFactor       = 1.2; // buffer 20%
            $prediksiPermintaan = $row['moving_avg_3bulan'] * (1 + $row['demand_growth']) * $safetyFactor;
            $prediksiPermintaan = max($prediksiPermintaan, $row['avg_demand_monthly']);

            // Kebutuhan tambah stok = prediksi - tersedia (kalau negatif = tidak perlu)
            $kebutuhanTambah = ceil($prediksiPermintaan - $row['tersedia']);
            $kebutuhanTambah = max($kebutuhanTambah, 0);

            // Estimasi revenue tambahan kalau stok ditambah
            $estimasiRevenueTambahan = $kebutuhanTambah * $row['harga_sewa'] * $row['jumlah_bulan_aktif'];

            $row['prediksi_permintaan']       = round($prediksiPermintaan, 2);
            $row['kebutuhan_tambah_stok']     = $kebutuhanTambah;
            $row['estimasi_revenue_tambahan'] = $estimasiRevenueTambahan;
        }
        return $data;
    }

    private function generateRekomendasi(array $data): array
    {
        $results = [];

        foreach ($data as $row) {
            $util     = $row['utilisasi_rate'];
            $coverage = $row['stok_coverage'];
            $growth   = $row['demand_growth'];
            $tambah   = $row['kebutuhan_tambah_stok'];
            $tersedia = $row['tersedia'];
            $nama     = $row['nama_barang'];

            // Tentukan label & skor
            if ($util >= 0.8 || $tersedia <= 1) {
                $label   = 'CRITICAL';
                $score   = 95;
                $title   = "Stok Kritis: {$nama}";
                $desc    = "{$nama} utilisasi {$this->pct($util)}% dengan hanya {$tersedia} unit tersedia. " .
                           "Prediksi permintaan bulan depan: {$row['prediksi_permintaan']} unit. " .
                           "Rekomendasikan tambah {$tambah} unit segera untuk menghindari kehabisan stok.";
                $urgency = 'Segera';

            } elseif ($util >= 0.6 || $coverage < 2) {
                $label   = 'WARNING';
                $score   = 78;
                $title   = "Pertimbangkan Tambah Stok: {$nama}";
                $desc    = "{$nama} utilisasi {$this->pct($util)}% dengan stok cukup untuk {$this->round2($coverage)} bulan ke depan. " .
                           ($growth > 0 ? "Tren permintaan naik {$this->pct($growth)}%. " : '') .
                           "Disarankan tambah {$tambah} unit dalam 1-2 bulan ke depan.";
                $urgency = '1-2 Bulan';

            } elseif ($util < 0.2 && $tersedia > 5) {
                $label   = 'OVERSTOCK';
                $score   = 40;
                $title   = "Stok Berlebih: {$nama}";
                $desc    = "{$nama} utilisasi hanya {$this->pct($util)}% dengan {$tersedia} unit menganggur. " .
                           "Pertimbangkan untuk mengurangi pengadaan atau menerapkan promo untuk mengoptimalkan utilisasi.";
                $urgency = 'Tidak Perlu Tambah';
                $tambah  = 0;

            } else {
                $label   = 'NORMAL';
                $score   = 60;
                $title   = "Stok Normal: {$nama}";
                $desc    = "{$nama} utilisasi {$this->pct($util)}% dengan stok cukup untuk {$this->round2($coverage)} bulan. " .
                           "Tidak diperlukan penambahan stok saat ini.";
                $urgency = 'Normal';
                $tambah  = 0;
            }

            // Hanya rekomendasikan yang CRITICAL dan WARNING
            if (in_array($label, ['CRITICAL', 'WARNING'])) {
                $results[] = array_merge($row, [
                    'label'                   => $label,
                    'ai_score'                => $score,
                    'title'                   => $title,
                    'description'             => $desc,
                    'urgency'                 => $urgency,
                    'saran_tambah_stok'       => $tambah,
                    'estimasi_revenue_tambahan' => 'Rp ' . number_format($row['estimasi_revenue_tambahan'], 0, ',', '.'),
                ]);
            }
        }

        // Urutkan: CRITICAL dulu, lalu WARNING, lalu skor tertinggi
        usort($results, function ($a, $b) {
            if ($a['label'] !== $b['label']) {
                return $a['label'] === 'CRITICAL' ? -1 : 1;
            }
            return $b['ai_score'] - $a['ai_score'];
        });

        return $results;
    }

    private function saveToDatabase(array $results): void
    {
        $now = now();

        foreach ($results as $r) {
            DB::table('recommendations')->updateOrInsert(
                [
                    'barang_id' => $r['barang_id'],
                    'type'      => 'barang',
                    'source'    => 'ml',
                ],
                [
                    'title'           => $r['title'],
                    'description'     => $r['description'],
                    'reason'          => $r['description'],
                    'score'           => $r['ai_score'],
                    'status'          => 'pending',
                    'jenis_barang'    => $r['jenis_barang'],
                    'demand_label'    => $r['label'],
                    'analysis_type'   => $r['urgency'],
                    'potential_gain'  => '+' . $r['saran_tambah_stok'] . ' Unit',
                    'revenue_estimate'=> $r['estimasi_revenue_tambahan'],
                    'utilisasi_rate'  => round($r['utilisasi_rate'], 4),
                    'idle_rate'       => round(1 - $r['utilisasi_rate'], 4),
                    'updated_at'      => $now,
                ]
            );
        }
    }

    private function pct(float $val): string
    {
        return round($val * 100, 1);
    }

    private function round2(float $val): string
    {
        return round($val, 2);
    }
}
