<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Get current month and year
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        
        // ==================== PENDAPATAN DARI PEMINJAMAN (SEWA) ====================
        // Ambil semua peminjaman yang sudah selesai di bulan ini untuk pendapatan
        $peminjamanSelesai = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $bulan)
            ->whereYear('tanggal_pengembalian_real', $tahun)
            ->get();
        
        // Sinkronisasi ke tabel keuangan untuk pendapatan sewa
        foreach ($peminjamanSelesai as $peminjaman) {
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
                    'tanggal' => $peminjaman->tanggal_pengembalian_real,
                    'referensi' => $peminjaman->invoice_number,
                    'status' => 'verified',
                    'created_by' => Auth::id()
                ]);
            }
        }
        
        // ==================== PENGELUARAN (Operasional, Promosi, Inventaris) ====================
        $pengeluaran = Keuangan::pengeluaran()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();
        
        // Calculate totals
        $totalPendapatan = Keuangan::pendapatan()->dariSewa()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah');
        
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $labaBersih = $totalPendapatan - $totalPengeluaran;
        
        // Get previous month data
        $previousMonth = date('m', strtotime("-1 month", strtotime("$tahun-$bulan-01")));
        $previousYear = $bulan == 1 ? $tahun - 1 : $tahun;
        
        $previousPendapatan = Keuangan::pendapatan()->dariSewa()
            ->whereMonth('tanggal', $previousMonth)
            ->whereYear('tanggal', $previousYear)
            ->sum('jumlah');
        
        $previousPengeluaran = Keuangan::pengeluaran()
            ->whereMonth('tanggal', $previousMonth)
            ->whereYear('tanggal', $previousYear)
            ->sum('jumlah');
        
        // Calculate growth percentages
        $pendapatanGrowth = $previousPendapatan > 0 
            ? (($totalPendapatan - $previousPendapatan) / $previousPendapatan) * 100 
            : 0;
        
        $pengeluaranGrowth = $previousPengeluaran > 0 
            ? (($totalPengeluaran - $previousPengeluaran) / $previousPengeluaran) * 100 
            : 0;
        
        $labaGrowth = ($previousPendapatan - $previousPengeluaran) > 0
            ? (($labaBersih - ($previousPendapatan - $previousPengeluaran)) / ($previousPendapatan - $previousPengeluaran)) * 100
            : 0;
        
        // Get monthly data for chart
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyPendapatan = Keuangan::pendapatan()->dariSewa()
                ->whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');
            
            $monthlyPengeluaran = Keuangan::pengeluaran()
                ->whereMonth('tanggal', $i)
                ->whereYear('tanggal', $tahun)
                ->sum('jumlah');
            
            $monthlyData[] = [
                'bulan' => date('M', mktime(0, 0, 0, $i, 1)),
                'pendapatan' => $monthlyPendapatan,
                'pengeluaran' => $monthlyPengeluaran
            ];
        }
        
        // Get top performing barang
        $topBarang = DetailPeminjaman::select('nama_barang', DB::raw('SUM(jumlah) as total_sewa'))
            ->whereYear('created_at', $tahun)
            ->groupBy('nama_barang')
            ->orderBy('total_sewa', 'desc')
            ->limit(5)
            ->get();
        
        // Get pengeluaran by kategori
        $pengeluaranByKategori = Keuangan::pengeluaran()
            ->select('sumber', 'kategori', DB::raw('SUM(jumlah) as total'))
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('sumber', 'kategori')
            ->get();
        
        // Get transaksi terbaru
        $recentTransactions = Keuangan::orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();
        
        return view('keuangan.index', compact(
            'totalPendapatan', 'totalPengeluaran', 'labaBersih',
            'pendapatanGrowth', 'pengeluaranGrowth', 'labaGrowth',
            'monthlyData', 'topBarang', 'pengeluaranByKategori',
            'recentTransactions', 'bulan', 'tahun'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sumber' => 'required|in:operasional,promosi,inventaris',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            $keuangan = Keuangan::create([
                'jenis' => 'pengeluaran',
                'sumber' => $request->sumber,
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'referensi' => $request->referensi ?? null,
                'status' => 'verified',
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Biaya berhasil ditambahkan',
                'data' => $keuangan
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $keuangan->delete();
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Laporan Laba Rugi
     */
    public function laporanLabaRugi(Request $request)
    {
        $startDate = $request->start ? date('Y-m-d', strtotime($request->start . '-01')) : date('Y-m-01');
        $endDate = $request->end ? date('Y-m-t', strtotime($request->end . '-01')) : date('Y-m-t');
        
        // Pendapatan dari peminjaman selesai
        $pendapatanUsaha = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereBetween('tanggal_pengembalian_real', [$startDate, $endDate])
            ->sum('grand_total');
        
        // HPP (Harga Pokok Penjualan) dari barang
        $hpp = DetailPeminjaman::whereBetween('created_at', [$startDate, $endDate])
            ->sum('subtotal');
        
        // Pembelian inventaris baru
        $pembelian = Keuangan::pengeluaran()
            ->dariInventaris()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');
        
        // Biaya operasional
        $biayaOperasional = Keuangan::pengeluaran()
            ->dariOperasional()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');
        
        // Pendapatan lain (promosi/saldo)
        $pendapatanLain = Keuangan::pendapatan()
            ->where('sumber', 'promosi')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');
        
        // Biaya lain
        $biayaLain = Keuangan::pengeluaran()
            ->dariPromosi()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah');
        
        $labaKotor = $pendapatanUsaha - ($hpp + $pembelian);
        $totalPendapatanUsaha = $labaKotor - $biayaOperasional;
        $totalPendapatanLuar = $pendapatanLain - $biayaLain;
        $labaRugiBersih = $totalPendapatanUsaha + $totalPendapatanLuar;
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'pendapatan_usaha' => $pendapatanUsaha,
                    'hpp' => $hpp,
                    'pembelian' => $pembelian,
                    'laba_kotor' => $labaKotor,
                    'biaya_operasional' => $biayaOperasional,
                    'pendapatan_lain' => $pendapatanLain,
                    'biaya_lain' => $biayaLain,
                    'laba_rugi_bersih' => $labaRugiBersih
                ],
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
        }
        
        return view('keuangan.laporan_laba_rugi');
    }
}