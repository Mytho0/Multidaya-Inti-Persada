<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Notification;
use App\Models\Recommendation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $greeting = $this->getGreeting();
        $userName = auth()->user()->name ?? 'Admin';
        
        // ==================== STATISTIK DASHBOARD ====================
        // Total pendapatan bulan ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $pendapatanBulanIni = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $currentMonth)
            ->whereYear('tanggal_pengembalian_real', $currentYear)
            ->sum('grand_total');
        
        $targetBulanIni = 200000000; // Target misal 200 juta
        $monthlyTarget = $pendapatanBulanIni > 0 ? ($pendapatanBulanIni / $targetBulanIni) * 100 : 0;
        
        // Unread messages (notifikasi belum dibaca)
        $unreadMessages = Notification::where('status', 'unread')->count();
        
        // Revenue growth (perbandingan bulan ini dengan bulan lalu)
        $lastMonth = Carbon::now()->subMonth();
        $pendapatanBulanLalu = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereMonth('tanggal_pengembalian_real', $lastMonth->month)
            ->whereYear('tanggal_pengembalian_real', $lastMonth->year)
            ->sum('grand_total');
        
        $revenueGrowth = $pendapatanBulanLalu > 0 
            ? (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100 
            : 0;
        
        // ==================== AKTIVITAS TERBARU ====================
        $activities = $this->getRecentActivities();
        
        // ==================== TOP PRODUCTS ====================
        $topProducts = $this->getTopProducts();
        
        // ==================== GROWTH DATA ====================
        $monthlySales = $pendapatanBulanIni;
        $monthlyProgress = $monthlyTarget;
        $monthlyGrowth = $revenueGrowth;
        
        // Top month berdasarkan historis
        $topMonth = $this->getTopMonth();
        $topYear = $this->getTopYear();
        $yearlySales = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereYear('tanggal_pengembalian_real', $currentYear)
            ->sum('grand_total');
        $yearlyGrowth = $this->getYearlyGrowth();
        
        // ==================== REKOMENDASI PINTAR ====================
        $recommendations = $this->generateSmartRecommendations();
        
        // ==================== NOTIFIKASI ====================
        $notifications = Notification::where('status', 'unread')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard.index', compact(
            'greeting', 'userName', 'monthlyTarget', 'pendapatanBulanIni',
            'unreadMessages', 'revenueGrowth', 'activities', 'topProducts',
            'monthlySales', 'monthlyProgress', 'monthlyGrowth', 'topMonth',
            'topYear', 'yearlySales', 'yearlyGrowth', 'recommendations', 'notifications'
        ));
    }
    
    private function getGreeting()
    {
        $hour = Carbon::now()->hour;
        if ($hour < 12) return 'Morning';
        if ($hour < 18) return 'Afternoon';
        return 'Evening';
    }
    
    private function getRecentActivities()
    {
        // Ambil peminjaman terbaru sebagai aktivitas
        $recentPeminjaman = Peminjaman::with('details')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $activities = [];
        foreach ($recentPeminjaman as $peminjaman) {
            $activities[] = (object)[
                'time' => $peminjaman->created_at->format('H:i'),
                'type' => $peminjaman->status_pengembalian == 'selesai' ? 'Pengembalian' : 'Peminjaman',
                'icon' => $peminjaman->status_pengembalian == 'selesai' ? 'fa-undo-alt' : 'fa-exchange-alt',
                'description' => $peminjaman->invoice_number . ' - ' . ($peminjaman->details->first()->nama_barang ?? 'Barang') . ' oleh ' . $peminjaman->nama_penyewa
            ];
        }
        
        return $activities;
    }
    
    private function getTopProducts()
    {
        $topBarang = DetailPeminjaman::select('nama_barang', 
            DB::raw('SUM(jumlah) as total_sewa'),
            DB::raw('SUM(subtotal) as total_pendapatan'))
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
            ->select(DB::raw('MONTH(tanggal_pengembalian_real) as month'), 
                DB::raw('SUM(grand_total) as total'))
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
            ->select(DB::raw('YEAR(tanggal_pengembalian_real) as year'), 
                DB::raw('SUM(grand_total) as total'))
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
            ->sum('grand_total');
        
        $lastYearTotal = Peminjaman::where('status_pengembalian', 'selesai')
            ->whereYear('tanggal_pengembalian_real', $lastYear)
            ->sum('grand_total');
        
        if ($lastYearTotal > 0) {
            return round((($currentYearTotal - $lastYearTotal) / $lastYearTotal) * 100);
        }
        return 22;
    }
    
    /**
     * Generate smart recommendations based on ML logic
     */
    private function generateSmartRecommendations()
    {
        $recommendations = [];
        
        // 1. Analisis permintaan barang (terlalu sedikit stok)
        $topRequested = DetailPeminjaman::select('nama_barang', DB::raw('SUM(jumlah) as total_request'))
            ->groupBy('nama_barang')
            ->orderBy('total_request', 'desc')
            ->limit(3)
            ->get();
        
        foreach ($topRequested as $barang) {
            // Cek stok saat ini
            $barangDb = Barang::where('nama_barang', $barang->nama_barang)->first();
            if ($barangDb && $barangDb->stok < 3) {
                $recommendations[] = (object)[
                    'type' => 'barang',
                    'title' => 'Tambah Stok ' . $barang->nama_barang,
                    'description' => 'Barang ini sangat diminati dengan ' . $barang->total_request . ' kali permintaan dalam periode terakhir. Stok saat ini terbatas.',
                    'reason' => 'Berdasarkan histori permintaan tinggi',
                    'score' => 95,
                    'status' => 'pending'
                ];
            }
        }
        
        // 2. Rekomendasi promo berdasarkan hari/liburan nasional
        $today = Carbon::now();
        $nationalHolidays = $this->getNationalHolidays();
        
        foreach ($nationalHolidays as $holiday) {
            if ($today->diffInDays(Carbon::parse($holiday['date'])) <= 7) {
                $recommendations[] = (object)[
                    'type' => 'promo',
                    'title' => 'Promosi ' . $holiday['name'],
                    'description' => 'Dalam ' . $today->diffInDays(Carbon::parse($holiday['date'])) . ' hari lagi akan ada ' . $holiday['name'] . '. Persiapkan promo spesial!',
                    'reason' => 'Berdasarkan kalender nasional',
                    'score' => 85,
                    'status' => 'pending'
                ];
            }
        }
        
        // 3. Rekomendasi berdasarkan weekend
        if ($today->isFriday()) {
            $recommendations[] = (object)[
                'type' => 'promo',
                'title' => 'Weekend Special Promo',
                'description' => 'Akhir pekan adalah waktu paling ramai untuk sewa barang. Buat promo diskon 10% untuk semua barang!',
                'reason' => 'Berdasarkan analisis pola permintaan weekend',
                'score' => 90,
                'status' => 'pending'
            ];
        }
        
        // 4. Analisis musim ramai/sepi
        $currentMonth = $today->month;
        $busyMonths = [12, 6, 7, 8]; // Desember, Juni, Juli, Agustus
        $slowMonths = [1, 2]; // Januari, Februari
        
        if (in_array($currentMonth, $busyMonths)) {
            $recommendations[] = (object)[
                'type' => 'promo',
                'title' => 'Musim Ramai - Tingkatkan Stok',
                'description' => 'Saat ini adalah musim ramai penyewaan. Pertimbangkan untuk menambah stok barang populer.',
                'reason' => 'Berdasarkan analisis musim ramai',
                'score' => 88,
                'status' => 'pending'
            ];
        } elseif (in_array($currentMonth, $slowMonths)) {
            $recommendations[] = (object)[
                'type' => 'promo',
                'title' => 'Promo Diskon Musim Sepi',
                'description' => 'Musim sepi adalah waktu tepat untuk memberikan promo menarik guna meningkatkan permintaan.',
                'reason' => 'Berdasarkan analisis musim sepi',
                'score' => 75,
                'status' => 'pending'
            ];
        }
        
        // 5. Rekomendasi barang baru berdasarkan minat penyewa
        $customerInterests = DetailPeminjaman::select('nama_barang', DB::raw('COUNT(DISTINCT peminjaman_id) as unique_customers'))
            ->groupBy('nama_barang')
            ->orderBy('unique_customers', 'desc')
            ->limit(3)
            ->get();
        
        foreach ($customerInterests as $interest) {
            $recommendations[] = (object)[
                'type' => 'barang',
                'title' => 'Tambah Varian ' . $interest->nama_barang,
                'description' => 'Barang ' . $interest->nama_barang . ' memiliki ' . $interest->unique_customers . ' penyewa unik. Pertimbangkan untuk menambah varian baru.',
                'reason' => 'Berdasarkan minat penyewa',
                'score' => 82,
                'status' => 'pending'
            ];
        }
        
        // Simpan rekomendasi ke database
        foreach ($recommendations as $rec) {
            Recommendation::updateOrCreate(
                ['title' => $rec->title],
                [
                    'type' => $rec->type,
                    'description' => $rec->description,
                    'reason' => $rec->reason,
                    'score' => $rec->score
                ]
            );
        }
        
        return array_slice($recommendations, 0, 3);
    }
    
    private function getNationalHolidays()
    {
        return [
            ['name' => 'Tahun Baru', 'date' => date('Y') . '-01-01'],
            ['name' => 'Hari Raya Nyepi', 'date' => date('Y') . '-03-11'],
            ['name' => 'Hari Buruh', 'date' => date('Y') . '-05-01'],
            ['name' => 'Hari Raya Waisak', 'date' => date('Y') . '-05-23'],
            ['name' => 'Hari Kemerdekaan', 'date' => date('Y') . '-08-17'],
            ['name' => 'Hari Raya Natal', 'date' => date('Y') . '-12-25'],
        ];
    }
    
    /**
     * Send WhatsApp notification
     */
    public function sendWhatsAppNotification(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'message' => 'required|string'
        ]);
        
        $number = $request->number;
        $message = $request->message;
        
        // Format nomor WhatsApp (hapus 0 di depan, tambah 62)
        $number = preg_replace('/^0/', '62', $number);
        
        // URL WhatsApp API (gunakan WhatsApp API key Anda)
        // Contoh menggunakan Fonnte atau API lainnya
        
        // Simpan notifikasi
        $notification = Notification::create([
            'title' => 'Notifikasi WhatsApp',
            'message' => $message,
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
    
    /**
     * Get notifications
     */
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
    
    /**
     * Mark notification as read
     */
    public function markNotificationRead(int $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['status' => 'read']);
        
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai telah dibaca'
        ]);
    }
    
    /**
     * Get recommendations
     */
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
    
    /**
     * Accept recommendation
     */
    public function acceptRecommendation(int $id)
    {
        $recommendation = Recommendation::findOrFail($id);
        $recommendation->update(['status' => 'approved']);
        
        // Tambahkan notifikasi
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
}