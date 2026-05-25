<?php

namespace App\Http\Controllers;

use App\Models\BiayaOperasional;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil pendapatan dari peminjaman yang sudah selesai (status_pengembalian = 'selesai')
        $totalPendapatan = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $bulan)
            ->whereYear('tanggal_pengembalian_real', $tahun)
            ->sum('grand_total_with_ppn');

        $totalPengeluaran = BiayaOperasional::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');

        $labaBersih = $totalPendapatan - $totalPengeluaran;

        // Hitung growth
        $lastMonthPendapatan = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $bulan - 1)
            ->whereYear('tanggal_pengembalian_real', $bulan == 1 ? $tahun - 1 : $tahun)
            ->sum('grand_total_with_ppn');

        $pendapatanGrowth = $lastMonthPendapatan > 0
            ? (($totalPendapatan - $lastMonthPendapatan) / $lastMonthPendapatan) * 100
            : 0;

        $lastMonthPengeluaran = BiayaOperasional::whereMonth('tanggal', $bulan - 1)
            ->whereYear('tanggal', $bulan == 1 ? $tahun - 1 : $tahun)
            ->sum('jumlah');

        $pengeluaranGrowth = $lastMonthPengeluaran > 0
            ? (($totalPengeluaran - $lastMonthPengeluaran) / $lastMonthPengeluaran) * 100
            : 0;

        $lastMonthLaba = $lastMonthPendapatan - $lastMonthPengeluaran;
        $labaGrowth = $lastMonthLaba != 0
            ? (($labaBersih - $lastMonthLaba) / abs($lastMonthLaba)) * 100
            : 0;

        $monthlyData = $this->getMonthlyData($tahun);

        $pengeluaranByKategori = BiayaOperasional::select('sumber', DB::raw('SUM(jumlah) as total'))
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('sumber')
            ->get();

        // Recent transactions
        $recentTransactions = collect();

        // Pendapatan recent dari peminjaman yang sudah selesai
        $pendapatanRecent = Peminjaman::with('pelanggan')
            ->where('status_pengembalian', 'selesai')
            ->whereNotNull('tanggal_pengembalian_real')
            ->orderBy('tanggal_pengembalian_real', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'jenis' => 'pendapatan',
                    'deskripsi' => 'Penyewaan - ' . ($item->nama_acara ?? $item->invoice_number),
                    'tanggal' => $item->tanggal_pengembalian_real,
                    'kategori' => 'Sewa Alat',
                    'sumber' => $item->pelanggan->nama ?? $item->nama_penyewa,
                    'jumlah' => $item->grand_total_with_ppn
                ];
            });

        $pengeluaranRecent = BiayaOperasional::with('creator')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'jenis' => 'pengeluaran',
                    'deskripsi' => $item->deskripsi,
                    'tanggal' => $item->tanggal,
                    'kategori' => $item->kategori,
                    'sumber' => $item->sumber,
                    'jumlah' => $item->jumlah
                ];
            });

        $recentTransactions = $pendapatanRecent->concat($pengeluaranRecent)
            ->sortByDesc('tanggal')
            ->take(10);

        $riwayatBiaya = BiayaOperasional::with('creator')
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('keuangan.index', compact(
            'bulan',
            'tahun',
            'monthlyData',
            'totalPendapatan',
            'totalPengeluaran',
            'labaBersih',
            'pendapatanGrowth',
            'pengeluaranGrowth',
            'labaGrowth',
            'pengeluaranByKategori',
            'recentTransactions',
            'riwayatBiaya'
        ));
    }

    public function pendapatan(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil peminjaman yang sudah selesai (status_pengembalian = 'selesai')
        $pendapatans = Peminjaman::with(['pelanggan', 'details.barang'])
            ->where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $bulan)
            ->whereYear('tanggal_pengembalian_real', $tahun)
            ->orderBy('tanggal_pengembalian_real', 'desc')
            ->paginate(20);

        $totalPendapatan = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $bulan)
            ->whereYear('tanggal_pengembalian_real', $tahun)
            ->sum('grand_total_with_ppn');

        $totalTransaksi = $pendapatans->total();
        $rataRata = $totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0;

        // Kategori dari barang (opsional)
        $kategoris = \App\Models\Barang::distinct()->pluck('jenis');

        // Chart data per bulan
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $pendapatan = Peminjaman::where('status_pengembalian', 'selesai')
                ->whereMonth('tanggal_pengembalian_real', $i)
                ->whereYear('tanggal_pengembalian_real', $tahun)
                ->sum('grand_total_with_ppn');
            $chartData[] = [
                'bulan' => substr($this->getBulanName($i), 0, 3),
                'pendapatan' => $pendapatan
            ];
        }

        return view('keuangan.pendapatan', compact(
            'bulan',
            'tahun',
            'pendapatans',
            'totalPendapatan',
            'totalTransaksi',
            'rataRata',
            'kategoris',
            'chartData'
        ));
    }

    public function pengeluaran(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        $pengeluarans = BiayaOperasional::with('creator')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        $totalOperasional = BiayaOperasional::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('sumber', 'operasional')
            ->sum('jumlah');

        $totalPromosi = BiayaOperasional::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('sumber', 'promosi')
            ->sum('jumlah');

        $totalInventaris = BiayaOperasional::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('sumber', 'inventaris')
            ->sum('jumlah');

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $pengeluaran = BiayaOperasional::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');
            $chartData[] = [
                'bulan' => substr($this->getBulanName($i), 0, 3),
                'pengeluaran' => $pengeluaran
            ];
        }

        $komposisi = [
            'operasional' => $totalOperasional,
            'promosi' => $totalPromosi,
            'inventaris' => $totalInventaris,
            'total' => $totalOperasional + $totalPromosi + $totalInventaris
        ];

        return view('keuangan.pengeluaran', compact(
            'bulan',
            'tahun',
            'pengeluarans',
            'totalOperasional',
            'totalPromosi',
            'totalInventaris',
            'chartData',
            'komposisi'
        ));
    }

    public function laba(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));

        $detailPerBulan = [];
        $chartData = [];
        $totalLaba = 0;

        for ($i = 1; $i <= 12; $i++) {
            $pendapatan = Peminjaman::where('status_pengembalian', 'selesai')
                ->whereMonth('tanggal_pengembalian_real', $i)
                ->whereYear('tanggal_pengembalian_real', $tahun)
                ->sum('grand_total_with_ppn');

            $pengeluaran = BiayaOperasional::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');

            $laba = $pendapatan - $pengeluaran;
            $margin = $pendapatan > 0 ? ($laba / $pendapatan) * 100 : 0;

            $bulanNama = $this->getBulanName($i);
            $detailPerBulan[] = [
                'bulan' => $bulanNama,
                'pendapatan' => $pendapatan,
                'pengeluaran' => $pengeluaran,
                'laba' => $laba,
                'margin' => $margin
            ];
            $chartData[] = [
                'bulan' => substr($bulanNama, 0, 3),
                'laba' => $laba
            ];
            $totalLaba += $laba;
        }

        $totalPendapatanTahunan = array_sum(array_column($detailPerBulan, 'pendapatan'));
        $marginLaba = $totalPendapatanTahunan > 0 ? ($totalLaba / $totalPendapatanTahunan) * 100 : 0;

        $currentMonthLaba = $detailPerBulan[$bulan - 1]['laba'];
        $prevMonthLaba = $bulan > 1 ? $detailPerBulan[$bulan - 2]['laba'] : $detailPerBulan[11]['laba'];
        $trend = $prevMonthLaba != 0 ? (($currentMonthLaba - $prevMonthLaba) / abs($prevMonthLaba)) * 100 : 0;

        return view('keuangan.laba', compact(
            'bulan',
            'tahun',
            'detailPerBulan',
            'chartData',
            'totalLaba',
            'trend',
            'marginLaba'
        ));
    }

    public function laporanLabaRugi(Request $request)
    {
        $bulan = $request->input('bulan', date('n'));
        $tahun = $request->input('tahun', date('Y'));
        $bulanNama = $this->getBulanName($bulan);

        $totalPendapatan = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $bulan)
            ->whereYear('tanggal_pengembalian_real', $tahun)
            ->sum('grand_total_with_ppn');

        $pendapatanPerBarang = Peminjaman::with('details.barang')
            ->where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $bulan)
            ->whereYear('tanggal_pengembalian_real', $tahun)
            ->get();

        $biayaOperasional = BiayaOperasional::select('kategori', DB::raw('SUM(jumlah) as total'))
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('sumber', 'operasional')
            ->groupBy('kategori')
            ->get();

        $biayaPromosi = BiayaOperasional::select('kategori', DB::raw('SUM(jumlah) as total'))
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('sumber', 'promosi')
            ->groupBy('kategori')
            ->get();

        $biayaInventaris = BiayaOperasional::select('kategori', DB::raw('SUM(jumlah) as total'))
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('sumber', 'inventaris')
            ->groupBy('kategori')
            ->get();

        $totalOperasional = $biayaOperasional->sum('total');
        $totalPromosi = $biayaPromosi->sum('total');
        $totalInventaris = $biayaInventaris->sum('total');
        $totalBiaya = $totalOperasional + $totalPromosi + $totalInventaris;
        $labaBersih = $totalPendapatan - $totalBiaya;

        $marginLabaKotor = $totalPendapatan > 0 ? (($totalPendapatan - $totalOperasional) / $totalPendapatan) * 100 : 0;
        $marginLabaBersih = $totalPendapatan > 0 ? ($labaBersih / $totalPendapatan) * 100 : 0;

        return view('keuangan.laporan_laba_rugi', compact(
            'bulan',
            'tahun',
            'bulanNama',
            'totalPendapatan',
            'pendapatanPerBarang',
            'biayaOperasional',
            'biayaPromosi',
            'biayaInventaris',
            'totalOperasional',
            'totalPromosi',
            'totalInventaris',
            'totalBiaya',
            'labaBersih',
            'marginLabaKotor',
            'marginLabaBersih'
        ));
    }

    // ==================== CRUD BIAYA ====================

    public function store(Request $request)
    {
        try {
            $request->validate([
                'sumber' => 'required|in:operasional,promosi,inventaris',
                'kategori' => 'required|string|max:100',
                'deskripsi' => 'required|string',
                'jumlah' => 'required|numeric|min:0',
                'tanggal' => 'required|date',
                'referensi' => 'nullable|string|max:100',
                'keterangan' => 'nullable|string'
            ]);

            $biaya = BiayaOperasional::create([
                'kode_biaya' => $this->generateKodeBiaya(),
                'sumber' => $request->sumber,
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'referensi' => $request->referensi,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->id()
            ]);

            return response()->json(['success' => true, 'message' => 'Biaya berhasil ditambahkan', 'data' => $biaya]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan biaya: ' . $e->getMessage()], 500);
        }
    }

    public function showBiaya($id)
    {
        $biaya = BiayaOperasional::with('creator')->findOrFail($id);
        return response()->json(['success' => true, 'data' => $biaya]);
    }

    public function destroyBiaya($id)
    {
        try {
            $biaya = BiayaOperasional::findOrFail($id);
            $biaya->delete();
            return response()->json(['success' => true, 'message' => 'Biaya berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus biaya'], 500);
        }
    }

    public function riwayatJson(Request $request)
    {
        $query = BiayaOperasional::query();

        if ($request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }
        if ($request->sumber && $request->sumber !== 'all') {
            $query->where('sumber', $request->sumber);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();
        $total = $data->sum('jumlah');

        return response()->json(['success' => true, 'data' => $data, 'total' => $total]);
    }

    // ==================== PRIVATE METHODS ====================

    private function getMonthlyData($tahun)
    {
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $pendapatan = Peminjaman::where('status_pengembalian', 'selesai')
                ->whereMonth('tanggal_pengembalian_real', $i)
                ->whereYear('tanggal_pengembalian_real', $tahun)
                ->sum('grand_total_with_ppn') ?? 0;

            $pengeluaran = BiayaOperasional::whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah') ?? 0;

            $data[] = [
                'bulan' => substr($this->getBulanName($i), 0, 3),
                'pendapatan' => $pendapatan,
                'pengeluaran' => $pengeluaran
            ];
        }
        return $data;
    }

    private function getBulanName($bulan)
    {
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $namaBulan[$bulan - 1];
    }

    private function generateKodeBiaya()
    {
        $last = BiayaOperasional::latest('id')->first();
        $number = $last ? intval(substr($last->kode_biaya, -4)) + 1 : 1;
        return 'BIAYA-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
