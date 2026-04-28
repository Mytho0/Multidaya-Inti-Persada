<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KeuanganController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

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

    // Profile Routes (dikomentari sementara)
    // Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    // Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');

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
        Route::put('/{id}', [PeminjamanController::class, 'update'])->name('update'); // EDIT
        Route::put('/{id}/pengembalian', [PeminjamanController::class, 'pengembalian'])->name('pengembalian');
        Route::post('/{id}/upload-bukti', [PeminjamanController::class, 'uploadBukti'])->name('upload-bukti');
        Route::get('/{id}/invoice', [PeminjamanController::class, 'generateInvoice'])->name('invoice');
        Route::delete('/{id}', [PeminjamanController::class, 'destroy'])->name('destroy');

        // WhatsApp Notification Routes
        Route::post('/{id}/send-pengiriman', [PeminjamanController::class, 'sendPengirimanNotification'])->name('send-pengiriman');
        Route::post('/{id}/send-pengingat', [PeminjamanController::class, 'sendPengingatPengembalian'])->name('send-pengingat');
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

    // ==================== KEUANGAN ROUTES ====================
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('index');
        Route::post('/', [KeuanganController::class, 'store'])->name('store');
        Route::delete('/{id}', [KeuanganController::class, 'destroy'])->name('destroy');
        Route::get('/laporan-lab-rugi', [KeuanganController::class, 'laporanLabaRugi'])->name('laporan-lab-rugi');
    });

    // ==================== API ROUTES (untuk dropdown) ====================
    Route::get('/api/barang-tersedia', function () {
        return response()->json(
            App\Models\Barang::where('status', 'aktif')
                ->where('tersedia', '>', 0)
                ->get(['id', 'kode_barang', 'nama_barang', 'harga_sewa', 'tersedia'])
        );
    })->name('api.barang.tersedia');

    // ==================== DASHBOARD NOTIFICATIONS & RECOMMENDATIONS ====================
    Route::post('/send-whatsapp', [DashboardController::class, 'sendWhatsAppNotification'])->name('send-whatsapp');
    Route::get('/notifications', [DashboardController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/{id}/read', [DashboardController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/count', [DashboardController::class, 'getNotificationCount'])->name('notifications.count');
    Route::get('/recommendations', [DashboardController::class, 'getRecommendations'])->name('recommendations');
    Route::post('/recommendations/accept', [DashboardController::class, 'acceptRecommendation'])->name('recommendations.accept');
    Route::get('/recommendations/refresh', [DashboardController::class, 'refreshRecommendations'])->name('recommendations.refresh');
});

