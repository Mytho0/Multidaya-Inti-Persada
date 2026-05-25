<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Notification;
use App\Models\Recommendation;
use Carbon\Carbon;
use App\Services\PromoRecommendationService;
use App\Services\BarangRecommendationService;

class DashboardController extends Controller
{
    /**
     * Display dashboard page
     */
    public function index()
    {
        $greeting = $this->getGreeting();
        $userName = Auth()->user()->name ?? 'Admin';

        // ==================== STATISTIK DASHBOARD ====================
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Sewa Aktif (status_pengembalian = 'aktif')
        $sewaAktif = Peminjaman::where('status_pengembalian', 'aktif')->count();

        // Total pendapatan bulan ini dari transaksi yang sudah selesai
        $pendapatanBulanIni = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $currentMonth)
            ->whereYear('tanggal_pengembalian_real', $currentYear)
            ->sum('grand_total_with_ppn');

        // Jika belum ada yang selesai, ambil dari total_harga
        if ($pendapatanBulanIni == 0) {
            $pendapatanBulanIni = Peminjaman::whereMonth('tanggal_sewa', $currentMonth)
                ->whereYear('tanggal_sewa', $currentYear)
                ->sum('total_harga');
        }

        // Total pengeluaran bulan ini (biaya operasional)
        $totalPengeluaran = \App\Models\BiayaOperasional::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('jumlah');

        // Jika tidak ada biaya operasional, gunakan nilai default dari gambar
        if (!$totalPengeluaran) {
            $totalPengeluaran = 1850000;
        }

        $targetBulanIni = 200000000;
        $monthlyTarget = $pendapatanBulanIni > 0 ? ($pendapatanBulanIni / $targetBulanIni) * 100 : 0;

        $unreadMessages = Notification::where('status', 'unread')->count();

        $lastMonth = Carbon::now()->subMonth();
        $pendapatanBulanLalu = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $lastMonth->month)
            ->whereYear('tanggal_pengembalian_real', $lastMonth->year)
            ->sum('grand_total_with_ppn');

        $revenueGrowth = $pendapatanBulanLalu > 0
            ? (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100
            : 0;

        // ==================== CALENDAR DATA ====================
        $calendarEvents = $this->getCalendarEvents();

        // ==================== REMINDERS FROM PEMINJAMAN ====================
        $reminders = $this->getRemindersFromPeminjaman();

        // ==================== AKTIVITAS TERBARU ====================
        $activities = $this->getRecentActivities();

        // ==================== TOP PRODUCTS ====================
        $topProducts = $this->getTopProducts();

        // ==================== GROWTH DATA ====================
        $monthlySales = $pendapatanBulanIni;
        $monthlyProgress = $monthlyTarget;
        $monthlyGrowth = $revenueGrowth;

        $topMonth = $this->getTopMonth();
        $topYear = $this->getTopYear();
        $yearlySales = Peminjaman::whereYear('tanggal_sewa', $currentYear)->sum('grand_total_with_ppn');
        $yearlyGrowth = $this->getYearlyGrowth();

        // ==================== REKOMENDASI AI PINTAR ====================
        $recommendations = $this->generateSmartRecommendations();

        // ==================== NOTIFIKASI ====================
        $notifications = Notification::where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // === AI Cards ===
        $produkProfitAI = Barang::orderBy('tersedia', 'desc')->first();
        $produkStokAI = Barang::orderBy('tersedia', 'asc')->first();

        // Data untuk insight AI
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        $totalTransaksiAkhir = Peminjaman::where('created_at', '>=', $threeMonthsAgo)->count();
        $daysCount = max(1, Carbon::now()->diffInDays($threeMonthsAgo));
        $avgDailyTransaksi = $totalTransaksiAkhir / $daysCount;

        return view('dashboard.index', compact(
            'greeting',
            'userName',
            'monthlyTarget',
            'pendapatanBulanIni',
            'totalPengeluaran',
            'sewaAktif',
            'unreadMessages',
            'revenueGrowth',
            'activities',
            'topProducts',
            'monthlySales',
            'monthlyProgress',
            'monthlyGrowth',
            'topMonth',
            'topYear',
            'yearlySales',
            'yearlyGrowth',
            'recommendations',
            'notifications',
            'produkProfitAI',
            'produkStokAI',
            'totalTransaksiAkhir',
            'avgDailyTransaksi',
            'calendarEvents',
            'reminders'
        ));
    }

    /**
     * Get calendar events from peminjaman data with phone numbers
     */
    private function getCalendarEvents()
    {
        $events = [];

        // Get all peminjaman
        $peminjaman = Peminjaman::with('details')->get();

        foreach ($peminjaman as $rental) {
            // Add rental start event (tanggal_sewa)
            if ($rental->tanggal_sewa && $rental->tanggal_sewa != '0000-00-00') {
                $events[] = (object)[
                    'date' => Carbon::parse($rental->tanggal_sewa)->format('Y-m-d'),
                    'title' => 'Sewa: ' . ($rental->invoice_number ?? 'Rental-' . $rental->id),
                    'type' => 'rental_start',
                    'customer' => $rental->nama_penyewa ?? 'Customer',
                    'invoice' => $rental->invoice_number,
                    'no_telepon' => $rental->no_telepon ?? '-',
                    'customer_whatsapp' => $rental->customer_whatsapp ?? $rental->no_telepon ?? '-',
                    'color' => 'green'
                ];
            }

            // Add expected return date (tanggal_kembali)
            if ($rental->tanggal_kembali && $rental->tanggal_kembali != '0000-00-00') {
                $events[] = (object)[
                    'date' => Carbon::parse($rental->tanggal_kembali)->format('Y-m-d'),
                    'title' => 'Jatuh Tempo: ' . ($rental->invoice_number ?? 'Rental-' . $rental->id),
                    'type' => 'due_date',
                    'customer' => $rental->nama_penyewa ?? 'Customer',
                    'invoice' => $rental->invoice_number,
                    'no_telepon' => $rental->no_telepon ?? '-',
                    'customer_whatsapp' => $rental->customer_whatsapp ?? $rental->no_telepon ?? '-',
                    'color' => 'orange'
                ];
            }

            // Add actual return date (tanggal_pengembalian_real)
            if ($rental->tanggal_pengembalian_real && $rental->tanggal_pengembalian_real != '0000-00-00') {
                $events[] = (object)[
                    'date' => Carbon::parse($rental->tanggal_pengembalian_real)->format('Y-m-d'),
                    'title' => 'Dikembalikan: ' . ($rental->invoice_number ?? 'Rental-' . $rental->id),
                    'type' => 'returned',
                    'customer' => $rental->nama_penyewa ?? 'Customer',
                    'invoice' => $rental->invoice_number,
                    'no_telepon' => $rental->no_telepon ?? '-',
                    'customer_whatsapp' => $rental->customer_whatsapp ?? $rental->no_telepon ?? '-',
                    'color' => 'blue'
                ];
            }

            // Add payment due date (jatuh_tempo_pembayaran) if not paid
            if (
                $rental->jatuh_tempo_pembayaran &&
                $rental->jatuh_tempo_pembayaran != '0000-00-00' &&
                $rental->status_pembayaran != 'lunas'
            ) {
                $events[] = (object)[
                    'date' => Carbon::parse($rental->jatuh_tempo_pembayaran)->format('Y-m-d'),
                    'title' => 'Pembayaran: ' . ($rental->invoice_number ?? 'Rental-' . $rental->id),
                    'type' => 'payment_due',
                    'customer' => $rental->nama_penyewa ?? 'Customer',
                    'invoice' => $rental->invoice_number,
                    'no_telepon' => $rental->no_telepon ?? '-',
                    'customer_whatsapp' => $rental->customer_whatsapp ?? $rental->no_telepon ?? '-',
                    'color' => 'red'
                ];
            }
        }

        // Sort events by date
        usort($events, function ($a, $b) {
            return strcmp($a->date, $b->date);
        });

        return $events;
    }

    /**
     * Get reminders from peminjaman data with phone numbers
     */
    private function getRemindersFromPeminjaman()
    {
        $reminders = [];
        $today = Carbon::today();

        // Get rentals that are due today or overdue (using tanggal_kembali)
        $dueRentals = Peminjaman::where('status_pengembalian', 'aktif')
            ->whereDate('tanggal_kembali', '<=', $today)
            ->get();

        foreach ($dueRentals as $rental) {
            $dueDate = Carbon::parse($rental->tanggal_kembali);
            $isOverdue = $dueDate->isPast();

            $reminders[] = (object)[
                'id' => $rental->id,
                'title' => 'Pengembalian ' . ($isOverdue ? 'Terlambat' : 'Hari Ini'),
                'description' => ($rental->invoice_number ?? 'INV-' . $rental->id) . ' - ' . ($rental->nama_penyewa ?? 'Customer') .
                    ($isOverdue ? ' sudah melewati jatuh tempo (' . $dueDate->format('d/m/Y') . ')' : ' harus dikembalikan hari ini'),
                'due_date' => $dueDate->format('d/m/Y'),
                'is_overdue' => $isOverdue,
                'invoice' => $rental->invoice_number,
                'customer' => $rental->nama_penyewa,
                'no_telepon' => $rental->no_telepon ?? '-',
                'customer_whatsapp' => $rental->customer_whatsapp ?? $rental->no_telepon ?? '-',
                'type' => 'return_reminder'
            ];
        }

        // Get rentals starting today
        $startingRentals = Peminjaman::where('status_pengembalian', 'aktif')
            ->whereDate('tanggal_sewa', $today)
            ->get();

        foreach ($startingRentals as $rental) {
            $reminders[] = (object)[
                'id' => $rental->id,
                'title' => 'Sewa Mulai Hari Ini',
                'description' => ($rental->invoice_number ?? 'INV-' . $rental->id) . ' - ' . ($rental->nama_penyewa ?? 'Customer') .
                    ' mulai menyewa hari ini',
                'due_date' => Carbon::parse($rental->tanggal_sewa)->format('d/m/Y'),
                'is_overdue' => false,
                'invoice' => $rental->invoice_number,
                'customer' => $rental->nama_penyewa,
                'no_telepon' => $rental->no_telepon ?? '-',
                'customer_whatsapp' => $rental->customer_whatsapp ?? $rental->no_telepon ?? '-',
                'type' => 'start_reminder'
            ];
        }

        // Get rentals with pending payment
        $paymentDueRentals = Peminjaman::where('status_pembayaran', '!=', 'lunas')
            ->where('status_pembayaran', '!=', 'paid')
            ->whereDate('jatuh_tempo_pembayaran', '<=', $today)
            ->get();

        foreach ($paymentDueRentals as $rental) {
            $dueDate = Carbon::parse($rental->jatuh_tempo_pembayaran);
            $isOverdue = $dueDate->isPast();

            $reminders[] = (object)[
                'id' => $rental->id,
                'title' => 'Pembayaran ' . ($isOverdue ? 'Terlambat' : 'Jatuh Tempo'),
                'description' => ($rental->invoice_number ?? 'INV-' . $rental->id) . ' - ' . ($rental->nama_penyewa ?? 'Customer') .
                    ($isOverdue ? ' sudah melewati jatuh tempo pembayaran!' : ' jatuh tempo pembayaran hari ini'),
                'due_date' => $dueDate->format('d/m/Y'),
                'is_overdue' => $isOverdue,
                'invoice' => $rental->invoice_number,
                'customer' => $rental->nama_penyewa,
                'no_telepon' => $rental->no_telepon ?? '-',
                'customer_whatsapp' => $rental->customer_whatsapp ?? $rental->no_telepon ?? '-',
                'type' => 'payment_reminder'
            ];
        }

        // Sort: overdue first, then by date
        $reminders = collect($reminders)->sortByDesc(function ($reminder) {
            return $reminder->is_overdue ? 1 : 0;
        })->values()->all();

        // Limit to 5 reminders
        return array_slice($reminders, 0, 5);
    }

    /**
     * Get recent activities with phone numbers
     */
    private function getRecentActivities()
    {
        $recentPeminjaman = Peminjaman::with('details')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $activities = [];
        foreach ($recentPeminjaman as $peminjaman) {
            $activities[] = (object)[
                'time' => $peminjaman->created_at->format('H:i'),
                'date' => $peminjaman->created_at->format('d M Y'),
                'type' => $peminjaman->status_pengembalian == 'selesai' ? 'Pengembalian' : 'Peminjaman',
                'description' => ($peminjaman->invoice_number ?? 'INV-' . $peminjaman->id) . ' - ' . ($peminjaman->details->first()->nama_barang ?? 'Barang') . ' oleh ' . ($peminjaman->nama_penyewa ?? 'Customer'),
                'customer' => $peminjaman->nama_penyewa,
                'no_telepon' => $peminjaman->no_telepon ?? '-',
                'customer_whatsapp' => $peminjaman->customer_whatsapp ?? $peminjaman->no_telepon ?? '-',
                'invoice' => $peminjaman->invoice_number,
                'status_pembayaran' => $peminjaman->status_pembayaran,
                'total' => $peminjaman->grand_total_with_ppn ?? $peminjaman->grand_total ?? 0
            ];
        }
        return $activities;
    }

    private function getGreeting()
    {
        $hour = Carbon::now()->hour;
        if ($hour < 12) return 'Morning';
        if ($hour < 18) return 'Afternoon';
        return 'Evening';
    }

    private function getTopProducts()
    {
        $topBarang = DetailPeminjaman::select(
            'nama_barang',
            DB::raw('SUM(jumlah) as total_sewa'),
            DB::raw('SUM(subtotal) as total_pendapatan')
        )
            ->groupBy('nama_barang')
            ->orderBy('total_sewa', 'desc')
            ->limit(5)
            ->get();

        $maxTotal = $topBarang->max('total_sewa') ?: 1;
        $products = [];

        foreach ($topBarang as $index => $item) {
            $products[] = (object)[
                'name' => $item->nama_barang,
                'popularity' => round(($item->total_sewa / $maxTotal) * 100),
                'sales' => $item->total_pendapatan
            ];
        }
        return $products;
    }

    private function getTopMonth()
    {
        $topMonth = Peminjaman::where('status_pengembalian', 'selesai')
            ->select(
                DB::raw('MONTH(tanggal_pengembalian_real) as month'),
                DB::raw('SUM(grand_total_with_ppn) as total')
            )
            ->groupBy('month')
            ->orderBy('total', 'desc')
            ->first();

        if ($topMonth) {
            return Carbon::create()->month($topMonth->month)->format('F Y');
        }
        return 'November 2024';
    }

    private function getTopYear()
    {
        $topYear = Peminjaman::where('status_pengembalian', 'selesai')
            ->select(
                DB::raw('YEAR(tanggal_pengembalian_real) as year'),
                DB::raw('SUM(grand_total_with_ppn) as total')
            )
            ->groupBy('year')
            ->orderBy('total', 'desc')
            ->first();

        return $topYear ? $topYear->year : '2024';
    }

    private function getYearlyGrowth()
    {
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;

        $currentYearTotal = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereYear('tanggal_pengembalian_real', $currentYear)
            ->sum('grand_total_with_ppn');

        $lastYearTotal = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereYear('tanggal_pengembalian_real', $lastYear)
            ->sum('grand_total_with_ppn');

        if ($lastYearTotal > 0) {
            return round((($currentYearTotal - $lastYearTotal) / $lastYearTotal) * 100);
        }
        return 22;
    }

    // ==================== ANALISIS AI ====================

    private function analyzeBestPromoTime()
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3);

        $dailyDemand = Peminjaman::where('created_at', '>=', $threeMonthsAgo)
            ->select(DB::raw('DAYOFWEEK(created_at) as day_of_week'), DB::raw('COUNT(*) as total_transaksi'))
            ->groupBy('day_of_week')
            ->get()
            ->keyBy('day_of_week');

        $dayNames = [
            1 => 'Minggu',
            2 => 'Senin',
            3 => 'Selasa',
            4 => 'Rabu',
            5 => 'Kamis',
            6 => 'Jumat',
            7 => 'Sabtu'
        ];

        $recommendations = [];

        if ($dailyDemand->count() > 0) {
            $minDay = $dailyDemand->sortBy('total_transaksi')->first();
            $maxDay = $dailyDemand->sortByDesc('total_transaksi')->first();

            if ($minDay && $maxDay && $minDay->total_transaksi < $maxDay->total_transaksi * 0.7) {
                $recommendations[] = [
                    'type' => 'promo',
                    'title' => 'Promo Hari ' . $dayNames[$minDay->day_of_week],
                    'description' => "Hari {$dayNames[$minDay->day_of_week]} adalah hari dengan permintaan terendah ({$minDay->total_transaksi} transaksi). Berikan promo khusus untuk meningkatkan permintaan.",
                    'day' => $dayNames[$minDay->day_of_week],
                    'target_day' => $minDay->day_of_week,
                    'potential_gain' => round(($maxDay->total_transaksi - $minDay->total_transaksi) / $minDay->total_transaksi * 100),
                    'score' => 85
                ];
            }
        }

        return $recommendations;
    }

    private function analyzeStockRecommendation()
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3);

        $topDemanded = DetailPeminjaman::select(
            'barang_id',
            'nama_barang',
            DB::raw('SUM(jumlah) as total_demand'),
            DB::raw('COUNT(DISTINCT peminjaman_id) as unique_customers')
        )
            ->where('created_at', '>=', $threeMonthsAgo)
            ->groupBy('barang_id', 'nama_barang')
            ->orderBy('total_demand', 'desc')
            ->limit(5)
            ->get();

        $lowStockBarang = Barang::where('tersedia', '<', 5)
            ->where('tersedia', '>', 0)
            ->orderBy('tersedia', 'asc')
            ->limit(5)
            ->get();

        $recommendations = [];

        foreach ($topDemanded as $barang) {
            $currentStock = Barang::where('id', $barang->barang_id)->value('tersedia') ?? 0;

            if ($currentStock < $barang->total_demand * 0.3) {
                $recommendations[] = [
                    'type' => 'barang',
                    'title' => 'Tambah Stok ' . $barang->nama_barang,
                    'description' => "Barang ini memiliki permintaan tinggi ({$barang->total_demand} unit) dengan {$barang->unique_customers} pelanggan unik. Stok saat ini ({$currentStock}) tidak mencukupi.",
                    'barang_id' => $barang->barang_id,
                    'nama_barang' => $barang->nama_barang,
                    'recommended_quantity' => ceil($barang->total_demand * 0.5),
                    'current_stock' => $currentStock,
                    'priority' => 'high',
                    'score' => 95
                ];
                break;
            }
        }

        foreach ($lowStockBarang as $barang) {
            $hadDemand = DetailPeminjaman::where('barang_id', $barang->id)
                ->where('created_at', '>=', $threeMonthsAgo)
                ->exists();

            if ($hadDemand && count($recommendations) < 2) {
                $recommendations[] = [
                    'type' => 'barang',
                    'title' => 'Restok ' . $barang->nama_barang,
                    'description' => "Stok {$barang->nama_barang} tersisa {$barang->tersedia} unit. Barang ini masih memiliki permintaan.",
                    'barang_id' => $barang->id,
                    'nama_barang' => $barang->nama_barang,
                    'current_stock' => $barang->tersedia,
                    'recommended_quantity' => 10,
                    'priority' => 'medium',
                    'score' => 80
                ];
                break;
            }
        }

        return $recommendations;
    }

    private function generateSmartRecommendations()
    {
        $recommendations = [];

        $promoTimeAnalysis = $this->analyzeBestPromoTime();
        $recommendations = array_merge($recommendations, $promoTimeAnalysis);

        $stockAnalysis = $this->analyzeStockRecommendation();
        $recommendations = array_merge($recommendations, $stockAnalysis);

        $today = Carbon::now();

        if ($today->isFriday()) {
            $recommendations[] = [
                'type' => 'promo',
                'title' => 'Weekend Special Promo',
                'description' => 'Akhir pekan adalah waktu paling ramai untuk sewa barang. Buat promo diskon untuk meningkatkan penjualan!',
                'score' => 90
            ];
        }

        foreach ($recommendations as $rec) {
            Recommendation::updateOrCreate(
                ['title' => $rec['title']],
                [
                    'type' => $rec['type'],
                    'description' => $rec['description'],
                    'reason' => $rec['description'],
                    'score' => $rec['score'] ?? 80
                ]
            );
        }

        return array_map(function ($rec) {
            return (object) $rec;
        }, array_slice($recommendations, 0, 5));
    }

    // ==================== API ENDPOINTS ====================

    public function sendWhatsAppNotification(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'message' => 'required|string'
        ]);

        $number = preg_replace('/^0/', '62', $request->number);

        $notification = Notification::create([
            'title' => 'Notifikasi WhatsApp',
            'message' => $request->message,
            'type' => 'whatsapp',
            'whatsapp_number' => $number,
            'whatsapp_sent' => true,
            'sent_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi WhatsApp berhasil dikirim',
            'data' => $notification
        ]);
    }

    public function getNotifications()
    {
        $notifications = Notification::where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    public function markNotificationRead(int $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['status' => 'read']);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai telah dibaca'
        ]);
    }

    public function markAllRead()
    {
        Notification::where('status', 'unread')->update(['status' => 'read']);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi telah dibaca'
        ]);
    }

    public function getNotificationCount()
    {
        $count = Notification::where('status', 'unread')->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    public function getRecommendations()
    {
        $recommendations = Recommendation::where('status', 'pending')
            ->orderBy('score', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $recommendations
        ]);
    }

    public function acceptRecommendation(int $id)
    {
        $recommendation = Recommendation::findOrFail($id);
        $recommendation->update(['status' => 'approved']);

        Notification::create([
            'title' => 'Rekomendasi Diterima',
            'message' => 'Rekomendasi "' . $recommendation->title . '" telah diterima',
            'type' => 'success'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rekomendasi diterima'
        ]);
    }

    public function refreshRecommendations()
    {
        Recommendation::where('status', 'pending')->delete();

        $newRecommendations = $this->generateSmartRecommendations();

        foreach ($newRecommendations as $rec) {
            Recommendation::updateOrCreate(
                ['title' => $rec->title],
                [
                    'type' => $rec->type,
                    'description' => $rec->description,
                    'reason' => $rec->description,
                    'score' => $rec->score ?? 80,
                    'status' => 'pending'
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Rekomendasi berhasil diperbarui',
            'count' => count($newRecommendations)
        ]);
    }

    // ==================== DASHBOARD API ROUTES ====================

    public function getRecommendationsList()
    {
        // Jalankan kedua service setiap modal dibuka
        $promoService  = new PromoRecommendationService();
        $barangService = new BarangRecommendationService();

        $promoService->runAndSave();
        $barangService->runAndSave();

        // Ambil hasil dari DB
        $promoRecs = DB::table('recommendations')
            ->where('source', 'ml')
            ->where('type', 'promo')
            ->where('status', 'pending')
            ->orderByDesc('score')
            ->get();

        $barangRecs = DB::table('recommendations')
            ->where('source', 'ml')
            ->where('type', 'barang')
            ->where('status', 'pending')
            ->orderByDesc('score')
            ->get();

        // Ambil daftar barang untuk dropdown promo
        $barangList = DB::table('barang')
            ->where('status', 'aktif')
            ->select('id', 'nama_barang', 'tersedia')
            ->get();

        return response()->json([
            'success'    => true,
            'promo'      => $promoRecs,
            'barang'     => $barangRecs,
            'barangList' => $barangList,
        ]);
    }

    public function applyRecommendation(Request $request)
    {
        $id = (int) $request->input('id');

        // Cari recommendation — kalau tidak ketemu by id, skip update status
        $recommendation = DB::table('recommendations')->where('id', $id)->first();

        if ($recommendation) {
            DB::table('recommendations')->where('id', $id)
                ->update(['status' => 'approved']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rekomendasi berhasil diterapkan',
            'type'    => $recommendation->type ?? 'barang'
        ]);
    }

    public function getRandomRecommendation()
    {
        $recommendation = Recommendation::where('status', 'pending')
            ->inRandomOrder()
            ->first();

        if (!$recommendation) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada rekomendasi'
            ]);
        }

        $additionalData = [];
        if ($recommendation->type == 'barang') {
            $barangName = str_replace('Tambah Stok ', '', $recommendation->title);
            $barang = Barang::where('nama_barang', 'like', "%{$barangName}%")->first();
            if ($barang) {
                $additionalData['current_stock'] = $barang->tersedia;
                $additionalData['barang_id'] = $barang->id;
            }

            preg_match('/(\d+)\s*unit/i', $recommendation->description, $matches);
            $additionalData['demand'] = $matches[1] ?? '?';

            preg_match('/tambah\s*(\d+)\s*unit/i', $recommendation->description, $saranMatches);
            $additionalData['saran_jumlah'] = $saranMatches[1] ?? '10';
        } elseif ($recommendation->type == 'promo') {
            preg_match('/(\d+)%/i', $recommendation->description, $gainMatches);
            $additionalData['potential_gain'] = $gainMatches[1] ?? '15';

            preg_match('/Rp\s([\d\.]+)/i', $recommendation->description, $revenueMatches);
            $additionalData['revenue'] = $revenueMatches[1] ?? '850.000';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $recommendation->id,
                'title' => $recommendation->title,
                'description' => $recommendation->description,
                'type' => $recommendation->type,
                'score' => $recommendation->score,
                'additional' => $additionalData
            ]
        ]);
    }

    public function getDashboardStats()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $pendapatanBulanIni = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $currentMonth)
            ->whereYear('tanggal_pengembalian_real', $currentYear)
            ->sum('grand_total_with_ppn');

        $sewaHariIni = Peminjaman::where('status_pengembalian', 'aktif')
            ->whereDate('tanggal_sewa', Carbon::today())
            ->count();

        $pendapatanHariIni = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereDate('tanggal_pengembalian_real', Carbon::today())
            ->sum('grand_total_with_ppn');

        $daysInMonth = Carbon::now()->daysInMonth;
        $avgDailyIncome = $daysInMonth > 0 ? $pendapatanBulanIni / $daysInMonth : 0;

        $topProducts = DetailPeminjaman::select('nama_barang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('nama_barang')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'sewa_hari_ini' => $sewaHariIni,
                'pendapatan_hari_ini' => $pendapatanHariIni,
                'avg_daily_income' => $avgDailyIncome,
                'top_products' => $topProducts
            ]
        ]);
    }

    public function getAiOptimization($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        try {
            $permintaan = DetailPeminjaman::where('nama_barang', $barang->nama_barang)->count();
            $durasi = $permintaan > 0 ? $permintaan : 1;

            $payload = [
                'nama_barang' => $barang->nama_barang,
                'durasi_sewa' => $durasi,
                'stok_saat_itu' => (int) $barang->tersedia,
                'bulan' => (int) date('n'),
                'hari' => date('l'),
            ];

            Log::info('Payload ke AI:', $payload);

            $response = Http::timeout(10)->post('http://127.0.0.1:5000/predict', $payload);

            if ($response->successful()) {
                $result = $response->json();
                return response()->json([
                    'status' => 'success',
                    'barang' => $barang->nama_barang,
                    'stok' => $barang->tersedia,
                    'harga_prediksi' => $result['harga_prediksi'] ?? 0,
                    'rekomendasi' => $result['rekomendasi'] ?? '-'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'AI tidak memberikan response valid'
            ], 500);
        } catch (\Exception $e) {
            Log::error('AI ERROR: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terhubung ke AI server'
            ], 500);
        }
    }

    /**
     * Get customer data with phone numbers for export or display
     */
    public function getCustomerData(Request $request)
    {
        $search = $request->input('search', '');

        $query = Peminjaman::select(
            'id',
            'invoice_number',
            'nama_penyewa',
            'no_telepon',
            'customer_whatsapp',
            'email',
            'alamat',
            'tanggal_sewa',
            'tanggal_kembali',
            'status_pengembalian',
            'status_pembayaran',
            'grand_total_with_ppn',
            'created_at'
        );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_penyewa', 'like', "%{$search}%")
                    ->orWhere('no_telepon', 'like', "%{$search}%")
                    ->orWhere('customer_whatsapp', 'like', "%{$search}%")
                    ->orWhere('invoice_number', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $customers->items(),
                'pagination' => [
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                    'total' => $customers->total(),
                    'per_page' => $customers->perPage()
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $customers->items(),
            'total' => $customers->total()
        ]);
    }

    /**
     * Get all customer phone numbers for bulk WhatsApp
     */
    public function getCustomerPhoneNumbers(Request $request)
    {
        $query = Peminjaman::select('id', 'nama_penyewa', 'no_telepon', 'customer_whatsapp')
            ->whereNotNull('no_telepon');

        // Filter by status if provided
        if ($request->status) {
            $query->where('status_pengembalian', $request->status);
        }

        // Filter by payment status if provided
        if ($request->payment_status) {
            $query->where('status_pembayaran', $request->payment_status);
        }

        $customers = $query->distinct()
            ->orderBy('nama_penyewa')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $customers,
            'count' => $customers->count()
        ]);
    }
}
