@extends('layouts.app')

@section('title', 'Multidaya Inti Persada | Dashboard')
@section('page-title', 'Dashboard Overview')
@section('dashboard-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
<div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">
    
    <!-- Header with Notifications -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
            <p class="text-slate-500 text-sm">Selamat datang kembali, {{ Auth::user()->name }}</p>
        </div>
        <div class="relative">
            <button onclick="toggleNotificationPanel()" class="relative p-2 bg-white rounded-full shadow-md hover:shadow-lg transition">
                <i class="fas fa-bell text-gray-600 text-xl"></i>
                <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 min-w-[18px] text-center">{{ $notifications->count() }}</span>
            </button>
            
            <!-- Notification Panel -->
            <div id="notificationPanel" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-200 z-50">
                <div class="p-3 border-b border-slate-200 bg-gray-50 rounded-t-xl">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-slate-800">Notifikasi</h3>
                        <button onclick="markAllRead()" class="text-xs text-blue-600 hover:text-blue-800">Tandai semua dibaca</button>
                    </div>
                </div>
                <div id="notificationList" class="max-h-96 overflow-y-auto">
                    @forelse($notifications as $notif)
                    <div class="p-3 border-b border-slate-100 hover:bg-gray-50 cursor-pointer notification-item" data-id="{{ $notif->id }}" onclick="markRead({{ $notif->id }})">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full {{ $notif->type == 'whatsapp' ? 'bg-green-100' : 'bg-blue-100' }} flex items-center justify-center">
                                <i class="fas {{ $notif->type == 'whatsapp' ? 'fa-whatsapp text-green-600' : 'fa-bell text-blue-600' }} text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-800">{{ $notif->title }}</p>
                                <p class="text-xs text-slate-500">{{ $notif->message }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-slate-500">
                        <i class="fas fa-inbox text-3xl mb-2 block"></i>
                        <p class="text-sm">Tidak ada notifikasi</p>
                    </div>
                    @endforelse
                </div>
                <div class="p-3 border-t border-slate-200 bg-gray-50 rounded-b-xl">
                    <button onclick="openWhatsAppModal()" class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fab fa-whatsapp"></i> Kirim Notifikasi WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Greeting Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Welcome Card -->
        <div class="lg:col-span-2 bg-gradient-to-r from-gray-700 to-gray-800 rounded-2xl shadow-xl p-5 sm:p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 opacity-10 text-7xl sm:text-9xl -mr-4 -mt-4">
                <i class="fas fa-chart-line"></i>
            </div>
            <p class="text-gray-200 text-xs sm:text-sm font-medium flex items-center gap-2">
                <i class="far fa-sun"></i> Today's Statistics
            </p>
            <h3 class="text-2xl sm:text-3xl font-bold mt-2">Good {{ $greeting ?? 'Morning' }}, {{ $userName ?? 'Admin' }}!</h3>
            <p class="text-gray-200 text-xs sm:text-sm mt-1 max-w-xs">Continue your journey and achieve your target</p>
            <div class="mt-4 sm:mt-5 flex gap-3 sm:gap-4">
                <div class="bg-white/20 rounded-xl px-3 sm:px-4 py-2 backdrop-blur-sm">
                    <p class="text-xs font-medium">Target Bulan Ini</p>
                    <p class="text-base sm:text-xl font-bold">{{ number_format($monthlyTarget, 1) }}%</p>
                </div>
                <div class="bg-white/20 rounded-xl px-3 sm:px-4 py-2 backdrop-blur-sm">
                    <p class="text-xs font-medium">Pencapaian</p>
                    <p class="text-base sm:text-xl font-bold">Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="flex flex-col gap-3 sm:gap-4">
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-5 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 text-slate-500 text-xs sm:text-sm">
                        <i class="fab fa-rocketchat text-gray-500"></i>
                        <span>Notifikasi</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">
                        {{ $unreadMessages ?? 0 }}
                        <span class="text-xs sm:text-sm font-normal text-slate-400">belum dibaca</span>
                    </p>
                </div>
                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600">
                    <i class="fas fa-bell text-base sm:text-xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-5 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 text-slate-500 text-xs sm:text-sm">
                        <i class="fas fa-chart-simple text-gray-500"></i>
                        <span>Pendapatan</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">
                        {{ number_format($revenueGrowth ?? 0, 1) }}%
                        <span class="text-xs sm:text-sm font-medium {{ ($revenueGrowth ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas {{ ($revenueGrowth ?? 0) >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> 
                            {{ ($revenueGrowth ?? 0) >= 0 ? 'increase' : 'decrease' }}
                        </span>
                    </p>
                    <p class="text-xs text-slate-400 mt-1">dibanding bulan lalu</p>
                </div>
                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600">
                    <i class="fas fa-dollar-sign text-base sm:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Smart Recommendations Section -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-bold text-slate-800 text-lg">
                <i class="fas fa-robot text-blue-500 mr-2"></i>Rekomendasi Pintar
            </h3>
            <button onclick="refreshRecommendations()" class="text-xs text-blue-600 hover:text-blue-800">
                <i class="fas fa-sync-alt mr-1"></i>Refresh
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="recommendationsContainer">
            @forelse($recommendations as $rec)
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-200 recommendation-card" data-id="{{ $loop->index }}">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $rec->type == 'barang' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                        <i class="fas {{ $rec->type == 'barang' ? 'fa-box' : 'fa-tag' }} mr-1"></i>
                        {{ $rec->type == 'barang' ? 'Tambah Barang' : 'Promo' }}
                    </span>
                    <span class="text-xs text-gray-500">
                        <i class="fas fa-chart-line mr-1"></i>Skor: {{ $rec->score }}%
                    </span>
                </div>
                <h4 class="font-bold text-slate-800 mb-1">{{ $rec->title }}</h4>
                <p class="text-xs text-slate-600 mb-2">{{ $rec->description }}</p>
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                    <i class="fas fa-lightbulb text-yellow-500"></i>
                    <span>{{ $rec->reason }}</span>
                </div>
                <div class="flex gap-2">
                    <button onclick="acceptRecommendation(this)" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-1.5 rounded-lg transition">
                        <i class="fas fa-check mr-1"></i>Terapkan
                    </button>
                    <button onclick="dismissRecommendation(this)" class="flex-1 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-semibold py-1.5 rounded-lg transition">
                        <i class="fas fa-times mr-1"></i>Tolak
                    </button>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8 bg-gray-50 rounded-xl">
                <i class="fas fa-robot text-4xl text-gray-300 mb-2"></i>
                <p class="text-slate-500">Belum ada rekomendasi. Data akan muncul setelah ada aktivitas peminjaman.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Activity & Products Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Activity History -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 flex justify-between items-center flex-wrap gap-2">
                <h3 class="font-bold text-slate-800 text-base sm:text-lg">
                    <i class="fas fa-history text-gray-500 mr-2"></i> Riwayat Aktivitas Terbaru
                </h3>
                <span class="text-xs text-slate-400 bg-gray-100 px-2 sm:px-3 py-1 rounded-full">Hari ini</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[500px] sm:min-w-full w-full">
                    <thead class="bg-slate-50/70">
                        <tr>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-slate-500 uppercase">Waktu</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-slate-500 uppercase">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($activities as $activity)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-mono text-slate-700">{{ $activity->time }}</td>
                                <td class="px-3 sm:px-6 py-2 sm:py-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                        <i class="fas {{ $activity->icon }} text-[10px]"></i> {{ $activity->type }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm text-slate-600">{{ $activity->description }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-4 text-center text-slate-500">Belum ada aktivitas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-base sm:text-lg">
                    <i class="fas fa-chart-simple text-gray-500 mr-2"></i> Top Products
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[300px] w-full text-xs sm:text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-3 sm:px-5 py-2 text-left">#</th>
                            <th class="px-2 py-2 text-left">Name</th>
                            <th class="px-2 py-2 text-left">Popularity</th>
                            <th class="px-3 sm:px-5 py-2 text-left">Sales</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topProducts as $index => $product)
                            <tr class="hover:bg-slate-50">
                                <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-600">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-2 py-2 sm:py-3 font-medium text-slate-700">{{ $product->name }}</td>
                                <td class="px-2 py-2 sm:py-3">
                                    <div class="flex items-center gap-1 sm:gap-2">
                                        <span class="text-xs font-bold text-gray-600">{{ $product->popularity }}%</span>
                                        <div class="w-12 sm:w-16 bg-slate-200 rounded-full h-1.5">
                                            <div class="bg-gray-600 h-1.5 rounded-full" style="width: {{ $product->popularity }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-800">
                                    {{ number_format($product->sales, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-4 text-center text-slate-500">Belum ada data produk</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Growth Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-5">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-2 py-1 rounded-full">
                        <i class="fas fa-chart-line mr-1"></i> Growth
                    </span>
                    <h4 class="text-base sm:text-lg font-bold text-slate-800 mt-2">Top Month</h4>
                    <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">{{ $topMonth ?? 'Belum ada data' }}</p>
                    <div class="mt-3 flex items-baseline gap-2 flex-wrap">
                        <span class="text-xs sm:text-sm text-slate-500">📊 Pendapatan:</span>
                        <span class="font-bold text-slate-800 text-sm sm:text-base">Rp {{ number_format($monthlySales ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-2 w-full bg-slate-100 rounded-full h-2 max-w-[180px] sm:max-w-[200px]">
                        <div class="bg-gray-600 h-2 rounded-full" style="width: {{ min($monthlyProgress ?? 0, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400 mt-2">{{ ($monthlyGrowth ?? 0) >= 0 ? '+' : '' }}{{ number_format($monthlyGrowth ?? 0, 1) }}% dari periode sebelumnya</p>
                </div>
                <i class="fas fa-calendar-alt text-2xl sm:text-3xl text-slate-300"></i>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-5">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-2 py-1 rounded-full">
                        <i class="fas fa-trophy mr-1"></i> Rekor
                    </span>
                    <h4 class="text-base sm:text-lg font-bold text-slate-800 mt-2">Top Year</h4>
                    <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">{{ $topYear ?? 'Belum ada data' }}</p>
                    <div class="mt-3 flex items-baseline gap-2 flex-wrap">
                        <span class="text-xs sm:text-sm text-slate-500">📈 Pendapatan Tahunan:</span>
                        <span class="font-bold text-slate-800 text-sm sm:text-base">Rp {{ number_format($yearlySales ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-2 flex gap-2 text-xs text-slate-500">
                        <span><i class="fas fa-chart-simple text-gray-400"></i> Pertumbuhan {{ number_format($yearlyGrowth ?? 0, 1) }}%</span>
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Berdasarkan data historis peminjaman</p>
                </div>
                <i class="fas fa-chart-column text-2xl sm:text-3xl text-slate-300"></i>
            </div>
        </div>
    </div>

    <!-- WhatsApp Modal -->
    <div id="whatsappModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4" onclick="if(event.target===this) closeWhatsAppModal()">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">
                    <i class="fab fa-whatsapp text-green-500 mr-2"></i>Kirim Notifikasi WhatsApp
                </h3>
                <button onclick="closeWhatsAppModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="whatsappForm" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor WhatsApp</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-slate-300 rounded-l-lg text-slate-500">+62</span>
                        <input type="tel" name="number" id="waNumber" required 
                               class="flex-1 px-4 py-2 border border-slate-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-gray-500"
                               placeholder="81234567890">
                    </div>
                    <p class="text-xs text-slate-400 mt-1">*Masukkan nomor tanpa 0 di depan (contoh: 81234567890)</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pesan</label>
                    <textarea name="message" id="waMessage" rows="4" required 
                              class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500"
                              placeholder="Tulis pesan notifikasi..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-lg transition">
                        <i class="fab fa-whatsapp mr-1"></i>Kirim
                    </button>
                    <button type="button" onclick="closeWhatsAppModal()" class="flex-1 border border-slate-300 rounded-lg py-2">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Notification Panel
function toggleNotificationPanel() {
    const panel = document.getElementById('notificationPanel');
    panel.classList.toggle('hidden');
}

function markRead(id) {
    fetch(`/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              document.querySelector(`.notification-item[data-id="${id}"]`).style.opacity = '0.5';
              updateNotificationBadge();
          }
      });
}

function markAllRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }).then(() => {
        document.querySelectorAll('.notification-item').forEach(item => {
            item.style.opacity = '0.5';
        });
        updateNotificationBadge();
    });
}

function updateNotificationBadge() {
    fetch('/notifications/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
}

// WhatsApp Modal
function openWhatsAppModal() {
    document.getElementById('whatsappModal').classList.remove('hidden');
    document.getElementById('whatsappModal').classList.add('flex');
}

function closeWhatsAppModal() {
    document.getElementById('whatsappModal').classList.add('hidden');
    document.getElementById('whatsappModal').classList.remove('flex');
    document.getElementById('whatsappForm').reset();
}

// Send WhatsApp
document.getElementById('whatsappForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = {
        number: formData.get('number'),
        message: formData.get('message')
    };
    
    try {
        const response = await fetch('/send-whatsapp', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success) {
            alert('Notifikasi WhatsApp berhasil dikirim!');
            closeWhatsAppModal();
            // Refresh notifikasi
            location.reload();
        } else {
            alert('Gagal mengirim: ' + result.message);
        }
    } catch (error) {
        alert('Gagal mengirim notifikasi');
    }
});

// Recommendations
function refreshRecommendations() {
    fetch('/recommendations/refresh')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
}

function acceptRecommendation(btn) {
    const card = btn.closest('.recommendation-card');
    const title = card.querySelector('h4').textContent;
    
    fetch('/recommendations/accept', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ title: title })
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              card.style.opacity = '0.5';
              alert('Rekomendasi diterima!');
          }
      });
}

function dismissRecommendation(btn) {
    const card = btn.closest('.recommendation-card');
    card.remove();
}

// Auto refresh notifikasi setiap 30 detik
setInterval(() => {
    fetch('/notifications/count')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.classList.remove('hidden');
            }
        });
}, 30000);

// Auto refresh rekomendasi setiap 5 menit
setInterval(() => {
    fetch('/recommendations/refresh')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.has_new) {
                location.reload();
            }
        });
}, 300000);
</script>
@endsection