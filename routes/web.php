<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KeuanganController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PromoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json(['message' => 'OK']);
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Guest routes (tidak perlu login)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Authenticated routes (memerlukan login)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Support & Reports (static pages)
    Route::view('/support', 'support.index')->name('support');
    Route::view('/reports', 'reports.index')->name('reports.index');
    Route::view('/messages', 'dashboard.index')->name('messages.index');
    Route::view('/reports/revenue', 'dashboard.index')->name('reports.revenue');
    Route::view('/activities', 'dashboard.index')->name('activities.index');
    Route::view('/promo/create', 'dashboard.index')->name('promo.create');

    // ==================== PEMINJAMAN ROUTES ====================
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [PeminjamanController::class, 'index'])->name('index');
        Route::post('/', [PeminjamanController::class, 'store'])->name('store');
        Route::get('/{id}', [PeminjamanController::class, 'show'])->name('show');
        Route::put('/{id}', [PeminjamanController::class, 'update'])->name('update');
        Route::put('/{id}/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('pengembalian');
        Route::post('/{id}/upload-bukti', [PeminjamanController::class, 'uploadBukti'])->name('upload-bukti');
        Route::get('/{id}/invoice', [PeminjamanController::class, 'generateInvoice'])->name('invoice');
        Route::delete('/{id}', [PeminjamanController::class, 'destroy'])->name('destroy');

        // WhatsApp Notification Routes
        Route::post('/{id}/send-pengiriman', [PeminjamanController::class, 'sendPengirimanNotification'])->name('send-pengiriman');
        Route::post('/{id}/send-pengingat', [PeminjamanController::class, 'sendPengingatPengembalian'])->name('send-pengingat');

        // Customer Check Routes
        Route::post('/cek-pelanggan', [PeminjamanController::class, 'cekPelanggan'])->name('cek-pelanggan');
        Route::get('/pelanggan-list', [PeminjamanController::class, 'getPelangganList'])->name('pelanggan-list');
    });

    // ==================== BARANG ROUTES ====================
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::get('/all', [BarangController::class, 'getAllData'])->name('all');
        Route::get('/stats', [BarangController::class, 'getStats'])->name('stats');
        Route::post('/', [BarangController::class, 'store'])->name('store');
        Route::get('/{id}', [BarangController::class, 'show'])->name('show');
        Route::put('/{id}', [BarangController::class, 'update'])->name('update');
        Route::delete('/{id}', [BarangController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/stock', [BarangController::class, 'updateStock'])->name('update-stock');
    });
    // Keuangan Routes
    Route::prefix('keuangan')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('keuangan.index');
        Route::get('/laba', [KeuanganController::class, 'laba'])->name('keuangan.laba');
        Route::get('/pendapatan', [KeuanganController::class, 'pendapatan'])->name('keuangan.pendapatan');
        Route::get('/pengeluaran', [KeuanganController::class, 'pengeluaran'])->name('keuangan.pengeluaran');
        Route::get('/laporan-laba-rugi', [KeuanganController::class, 'laporanLabaRugi'])->name('keuangan.laporan-laba-rugi');
        Route::get('/riwayat-json', [KeuanganController::class, 'riwayatJson'])->name('keuangan.riwayat-json');
        Route::post('/', [KeuanganController::class, 'store'])->name('keuangan.store');
        Route::get('/{id}', [KeuanganController::class, 'show'])->name('keuangan.show');
        Route::delete('/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');
    });

    // ==================== API ROUTES ====================
    Route::get('/api/barang-tersedia', function () {
        return response()->json(
            App\Models\Barang::where('status', 'aktif')
                ->where('tersedia', '>', 0)
                ->get(['id', 'kode_barang', 'nama_barang', 'harga_sewa', 'tersedia'])
        );
    })->name('api.barang.tersedia');

    // ==================== DASHBOARD ROUTES (Semua dalam 1 group) ====================
    Route::prefix('dashboard')->name('dashboard.')->group(function () {

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [DashboardController::class, 'getNotifications'])->name('index');
            Route::get('/count', [DashboardController::class, 'getNotificationCount'])->name('count');
            Route::post('/{id}/read', [DashboardController::class, 'markNotificationRead'])->name('read');
            Route::post('/mark-all-read', [DashboardController::class, 'markAllRead'])->name('mark-all-read');
        });

        // WhatsApp
        Route::post('/send-whatsapp', [DashboardController::class, 'sendWhatsAppNotification'])->name('send-whatsapp');

        // Recommendations (AI)
        Route::prefix('recommendations')->name('recommendations.')->group(function () {
            Route::get('/', [DashboardController::class, 'getRecommendations'])->name('index');
            Route::get('/list', [DashboardController::class, 'getRecommendationsList'])->name('list');
            Route::get('/random', [DashboardController::class, 'getRandomRecommendation'])->name('random');
            Route::post('/accept', [DashboardController::class, 'acceptRecommendation'])->name('accept');
            Route::post('/apply', [DashboardController::class, 'applyRecommendation'])->name('apply');
            Route::get('/refresh', [DashboardController::class, 'refreshRecommendations'])->name('refresh');
        });

        // Calendar Events (dari PeminjamanController)
        Route::get('/calendar-events', [PeminjamanController::class, 'getCalendarEvents'])->name('calendar-events');

        // Reminders (dari PeminjamanController)
        Route::get('/reminders', [PeminjamanController::class, 'getReminders'])->name('reminders');

        // Statistics
        Route::get('/stats', [DashboardController::class, 'getDashboardStats'])->name('stats');
        Route::get('/peminjaman-stats', [PeminjamanController::class, 'getDashboardStats'])->name('peminjaman-stats');

        // AI Optimization
        Route::get('/ai-optimization/{id}', [DashboardController::class, 'getAiOptimization'])->name('ai-optimization');
    });

    // ==================== PROMO ROUTES ====================
    Route::middleware(['auth'])->group(function () {
        Route::post('/promo', [PromoController::class, 'store'])->name('promo.store');
        Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');
        Route::get('/promo/aktif/{barangId}', [PromoController::class, 'getPromoAktif'])->name('promo.aktif');
        Route::post('/promo/check-status', [PromoController::class, 'checkAndUpdateStatus'])->name('promo.check');
    });
});

// Route untuk tambah stok barang (dipanggil dari modal tambah stok)
Route::post('/barang/{id}/tambah-stok', [BarangController::class, 'tambahStok']);
