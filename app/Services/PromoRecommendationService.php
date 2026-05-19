<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PromoRecommendationService
{
    public function runAndSave(): array
    {
        // STEP 1: Ambil data barang + histori peminjaman
        $barangList = DB::select("
            SELECT
                b.id AS barang_id,
                b.nama_barang,
                b.jenis AS jenis_barang,
                b.harga_sewa,
                b.stok,
                b.tersedia,
                b.disewa,
                COUNT(dp.id)                  AS frekuensi_dipinjam,
                COALESCE(SUM(dp.jumlah), 0)   AS total_unit_dipinjam,
                COALESCE(SUM(dp.subtotal), 0) AS total_revenue
            FROM barang b
            LEFT JOIN detail_peminjaman dp ON dp.barang_id = b.id
            WHERE b.status = 'aktif'
            GROUP BY b.id, b.nama_barang, b.jenis,
                     b.harga_sewa, b.stok, b.tersedia, b.disewa
        ");

        if (empty($barangList)) return [];

        // STEP 2: Feature Engineering
        $data = [];
        foreach ($barangList as $b) {
            $stok   = max($b->stok, 1);
            $data[] = [
                'barang_id'          => $b->barang_id,
                'nama_barang'        => $b->nama_barang,
                'jenis_barang'       => $b->jenis_barang,
                'harga_sewa'         => $b->harga_sewa,
                'stok'               => $b->stok,
                'tersedia'           => $b->tersedia,
                'frekuensi_dipinjam' => $b->frekuensi_dipinjam,
                'total_revenue'      => $b->total_revenue,
                'utilisasi_rate'     => $b->disewa / $stok,
                'idle_rate'          => $b->tersedia / $stok,
                'revenue_per_stok'   => $b->total_revenue / $stok,
                'permintaan_score'   => $b->frekuensi_dipinjam / $stok,
            ];
        }

        // STEP 3: Normalisasi Min-Max
        $data = $this->minMaxNormalize($data,
            ['utilisasi_rate', 'idle_rate', 'revenue_per_stok', 'permintaan_score']
        );

        // STEP 4: K-Means k=3
        $data = $this->kMeans($data, 3,
            ['utilisasi_rate_scaled', 'idle_rate_scaled',
             'revenue_per_stok_scaled', 'permintaan_score_scaled']
        );

        // STEP 5: Auto-label cluster
        $data = $this->autoLabelClusters($data);

        // STEP 6: Generate rekomendasi
        $results = $this->generateRekomendasi($data);

        // STEP 7: Simpan ke DB
        $this->saveToDatabase($results);

        return $results;
    }

    private function minMaxNormalize(array $data, array $features): array
    {
        foreach ($features as $f) {
            $values = array_column($data, $f);
            $min    = min($values);
            $max    = max($values);
            $range  = ($max - $min) ?: 1;
            foreach ($data as &$row) {
                $row[$f . '_scaled'] = ($row[$f] - $min) / $range;
            }
        }
        return $data;
    }

    private function kMeans(array $data, int $k, array $features, int $maxIter = 100): array
    {
        $n = count($data);
        if ($n <= $k) {
            foreach ($data as $i => &$row) $row['cluster'] = $i % $k;
            return $data;
        }

        // Init centroids deterministik
        $centroids = [];
        $step      = max(1, (int)($n / $k));
        for ($i = 0; $i < $k; $i++) {
            $idx           = min($i * $step, $n - 1);
            $centroids[$i] = array_map(fn($f) => $data[$idx][$f], $features);
        }

        for ($iter = 0; $iter < $maxIter; $iter++) {
            // Assign ke centroid terdekat
            foreach ($data as &$row) {
                $minDist = PHP_FLOAT_MAX;
                $best    = 0;
                foreach ($centroids as $ci => $c) {
                    $dist = 0;
                    foreach ($features as $fi => $f) {
                        $dist += ($row[$f] - $c[$fi]) ** 2;
                    }
                    if ($dist < $minDist) {
                        $minDist = $dist;
                        $best    = $ci;
                    }
                }
                $row['cluster'] = $best;
            }
            unset($row);

            // Update centroids
            $sums   = array_fill(0, $k, array_fill(0, count($features), 0));
            $counts = array_fill(0, $k, 0);
            foreach ($data as $row) {
                $ci = $row['cluster'];
                $counts[$ci]++;
                foreach ($features as $fi => $f) {
                    $sums[$ci][$fi] += $row[$f];
                }
            }

            $converged    = true;
            $newCentroids = $centroids;
            for ($ci = 0; $ci < $k; $ci++) {
                if ($counts[$ci] > 0) {
                    foreach ($features as $fi => $f) {
                        $newVal = $sums[$ci][$fi] / $counts[$ci];
                        if (abs($newVal - $centroids[$ci][$fi]) > 1e-6) {
                            $converged = false;
                        }
                        $newCentroids[$ci][$fi] = $newVal;
                    }
                }
            }
            $centroids = $newCentroids;
            if ($converged) break;
        }

        return $data;
    }

    private function autoLabelClusters(array $data): array
    {
        $clusterStats = [];
        foreach ($data as $row) {
            $c = $row['cluster'];
            $clusterStats[$c]['sum']   = ($clusterStats[$c]['sum']   ?? 0) + $row['utilisasi_rate'];
            $clusterStats[$c]['count'] = ($clusterStats[$c]['count'] ?? 0) + 1;
        }

        $avgUtil = [];
        foreach ($clusterStats as $c => $s) {
            $avgUtil[$c] = $s['sum'] / $s['count'];
        }
        asort($avgUtil);

        $labels     = ['LOW_DEMAND', 'MEDIUM_DEMAND', 'HIGH_DEMAND'];
        $clusterMap = [];
        foreach (array_keys($avgUtil) as $i => $clusterId) {
            $clusterMap[$clusterId] = $labels[$i];
        }

        foreach ($data as &$row) {
            $row['demand_label'] = $clusterMap[$row['cluster']];
        }
        return $data;
    }

    private function generateRekomendasi(array $data): array
    {
        $results = [];
        foreach ($data as $row) {
            $label     = $row['demand_label'];
            $nama      = $row['nama_barang'];
            $stok      = $row['stok'];
            $tersedia  = $row['tersedia'];
            $frek      = (int) $row['frekuensi_dipinjam'];
            $harga     = (int) $row['harga_sewa'];
            $utilisasi = round($row['utilisasi_rate'] * 100, 1);
            $idleRate  = round($row['idle_rate'] * 100, 1);

            if ($label === 'LOW_DEMAND' && $tersedia >= 2) {
                $nilaiDiskon = min(25, max(5, (int)($idleRate * 0.25)));
                $hargaDiskon = (int)($harga * (1 - $nilaiDiskon/100));

                $results[] = array_merge($row, [
                    'ai_score'        => 92,
                    'analysis_type'   => 'Stok Idle',
                    'potential_gain'  => '+' . (int)($nilaiDiskon * 1.5) . '% Utilisasi',
                    'jenis_promo'     => "Diskon {$nilaiDiskon}%",
                    'nilai_diskon'    => $nilaiDiskon,
                    'jenis_diskon'    => 'persen',
                    'title'           => "Promo {$nama}",
                    'description'     => "{$nama} memiliki {$stok} unit stok namun utilisasi hanya {$utilisasi}%. " .
                        ($frek > 0 ? "Tercatat {$frek}x dipinjam. " : "Belum ada histori peminjaman. ") .
                        "Harga normal Rp " . number_format($harga, 0, ',', '.') .
                        " → harga promo Rp " . number_format($hargaDiskon, 0, ',', '.') .
                        " (diskon {$nilaiDiskon}% berdasarkan idle rate {$idleRate}%).",
                    'revenue_estimate' => 'Rp ' . number_format($harga * (1 - $nilaiDiskon/100) * $tersedia, 0, ',', '.'),
                ]);

            } elseif ($label === 'MEDIUM_DEMAND' && $tersedia >= 1) {
                $nilaiDiskon = min(10, max(5, (int)($idleRate * 0.12)));
                $hargaDiskon = (int)($harga * (1 - $nilaiDiskon/100));

                $results[] = array_merge($row, [
                    'ai_score'        => 75,
                    'analysis_type'   => 'Permintaan Sedang',
                    'potential_gain'  => '+25% Permintaan',
                    'jenis_promo'     => "Bundle Deal {$nilaiDiskon}%",
                    'nilai_diskon'    => $nilaiDiskon,
                    'jenis_diskon'    => 'persen',
                    'title'           => "Bundle {$nama}",
                    'description'     => "{$nama} utilisasi {$utilisasi}% dengan {$tersedia} unit tersedia. " .
                        "Harga normal Rp " . number_format($harga, 0, ',', '.') .
                        " → harga promo Rp " . number_format($hargaDiskon, 0, ',', '.') .
                        " (diskon {$nilaiDiskon}%). Bundle dengan aksesori dapat meningkatkan daya tarik.",
                    'revenue_estimate' => 'Rp ' . number_format($harga * (1 - $nilaiDiskon/100) * 2, 0, ',', '.'),
                ]);

            } elseif ($label === 'HIGH_DEMAND') {
                $nilaiDiskon = 5;
                $hargaDiskon = (int)($harga * (1 - $nilaiDiskon/100));

                $results[] = array_merge($row, [
                    'ai_score'        => 60,
                    'analysis_type'   => 'Permintaan Tinggi',
                    'potential_gain'  => '+10% Retensi',
                    'jenis_promo'     => 'Loyalty Reward 5%',
                    'nilai_diskon'    => $nilaiDiskon,
                    'jenis_diskon'    => 'persen',
                    'title'           => "Loyalty {$nama}",
                    'description'     => "{$nama} sangat diminati dengan utilisasi {$utilisasi}%. " .
                        "Harga normal Rp " . number_format($harga, 0, ',', '.') .
                        " → harga loyalty Rp " . number_format($hargaDiskon, 0, ',', '.') .
                        " (diskon {$nilaiDiskon}%). Program loyalty untuk pelanggan repeat order.",
                    'revenue_estimate' => 'Rp ' . number_format($harga * (1 - $nilaiDiskon/100) * max($frek, 1), 0, ',', '.'),
                ]);
            }
        }

        usort($results, fn($a, $b) => $b['ai_score'] - $a['ai_score']);
        return $results;
    }

    private function saveToDatabase(array $results): void
    {
        $now = now();

        foreach ($results as $r) {
            DB::table('recommendations')->updateOrInsert(
                [
                    'barang_id' => $r['barang_id'],
                    'type'      => 'promo',
                    'source'    => 'ml',
                ],
                [
                    'title'           => $r['title'],
                    'description'     => $r['description'],
                    'reason'          => $r['description'],
                    'score'           => $r['ai_score'],
                    'status'          => 'pending',
                    'jenis_barang'    => $r['jenis_barang'],
                    'demand_label'    => $r['demand_label'],
                    'jenis_promo'     => $r['jenis_promo'],
                    'analysis_type'   => $r['analysis_type'],
                    'potential_gain'  => $r['potential_gain'],
                    'revenue_estimate'=> $r['revenue_estimate'],
                    'nilai_diskon'    => $r['nilai_diskon'],
                    'jenis_diskon'    => $r['jenis_diskon'],
                    'utilisasi_rate'  => round($r['utilisasi_rate'], 4),
                    'idle_rate'       => round($r['idle_rate'], 4),
                    'cluster'         => $r['cluster'],
                    'updated_at'      => $now,
                ]
            );
        }
    }
}
