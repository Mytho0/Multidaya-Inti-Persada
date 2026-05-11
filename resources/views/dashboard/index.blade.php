@extends('layouts.app')

@section('title', 'Multidaya Inti Persada | Dashboard')
@section('page-title', 'Dashboard Overview')
@section('dashboard-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto animate-fade-in relative">
        {{-- ==================== SECTION 1: TOP STATS ==================== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Sewa Aktif --}}
            <div class="group bg-white rounded-4xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                onclick="window.location.href='{{ route('peminjaman.index') }}'">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sewa Aktif</p>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">
                            {{ $sewaAktif ?? 0 }} <span
                                class="text-xs font-bold text-slate-400 ml-1 uppercase tracking-tighter">Transaksi</span>
                        </h2>
                    </div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-boxes text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Total Income --}}
            <div class="group bg-white rounded-4xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                onclick="window.location.href='{{ route('keuangan.index') }}'">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Income</p>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight">
                            Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}
                        </h2>
                    </div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Pengeluaran --}}
            <div class="group bg-white rounded-4xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-rose-500/10 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
                onclick="window.location.href='{{ route('keuangan.index') }}'">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pengeluaran</p>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-rose-600 tracking-tight">
                            Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                        </h2>
                    </div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i class="fas fa-receipt text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== SECTION 2: CALENDAR & REMINDER ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-start">
            <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm p-4 relative overflow-hidden">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 id="monthTitle" class="text-base font-black text-slate-800">{{ $bulanNama ?? 'Mei' }}
                            {{ $tahun ?? date('Y') }}</h3>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-tighter">Live Schedule</p>
                    </div>
                    <div class="flex gap-1">
                        <button onclick="changeMonth(-1)"
                            class="w-7 h-7 rounded-full bg-slate-50 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-all"><i
                                class="fas fa-chevron-left text-[11px]"></i></button>
                        <button onclick="changeMonth(1)"
                            class="w-7 h-7 rounded-full bg-slate-50 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-all"><i
                                class="fas fa-chevron-right text-[11px]"></i></button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-px text-center mb-1">
                    <div class="text-[9px] font-black text-slate-300 uppercase">Min</div>
                    <div class="text-[9px] font-black text-slate-300 uppercase">Sen</div>
                    <div class="text-[9px] font-black text-slate-300 uppercase">Sel</div>
                    <div class="text-[9px] font-black text-slate-300 uppercase">Rab</div>
                    <div class="text-[9px] font-black text-slate-300 uppercase">Kam</div>
                    <div class="text-[9px] font-black text-slate-300 uppercase">Jum</div>
                    <div class="text-[9px] font-black text-slate-300 uppercase">Sab</div>
                </div>

                <div id="calendarGrid" class="grid grid-cols-7 gap-1.5"></div>

                <div class="flex gap-3 mt-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Sewa Mulai</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Jatuh Tempo</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Pengembalian</span>
                    </div>
                </div>

                {{-- POPUP DETAIL KALENDER --}}
                <div id="schedulePopup"
                    class="hidden absolute inset-0 z-20 bg-white/98 backdrop-blur-md p-6 flex-col animate-fade-in shadow-2xl rounded-2xl">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h4 class="font-black text-lg text-slate-800 uppercase leading-none">Detail Sewa</h4>
                            <p id="popupDateLabel"
                                class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mt-1"></p>
                        </div>
                        <button onclick="closePopup()"
                            class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition-all"><i
                                class="fas fa-times text-xs"></i></button>
                    </div>
                    <div id="popupContent" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                </div>
            </div>

            {{-- Kolom Reminder & Tombol Rekomendasi --}}
            <div class="flex flex-col gap-4">
                {{-- Reminder Card --}}
                <div style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);"
                    class="rounded-2xl p-4 shadow-lg relative overflow-hidden flex flex-col">
                    <h3 class="text-slate-900 font-black text-[12px] mb-3 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-red-600 rounded-full animate-pulse"></span>
                        Pengingat Hari Ini
                    </h3>
                    <div class="space-y-2 max-h-[300px] overflow-y-auto">
                        @forelse($reminders ?? [] as $item)
                            <div class="bg-white/60 p-2.5 rounded-xl border border-white/20 backdrop-blur-sm">
                                <span class="text-[11px] font-black text-slate-800 uppercase tracking-tighter block mb-0.5">
                                    @if ($item->type == 'return_overdue')
                                        ⚠️ TERLAMBAT
                                    @elseif($item->type == 'return_due')
                                        📅 JATUH TEMPO
                                    @elseif($item->type == 'payment_overdue')
                                        💰 PEMBAYARAN TERLAMBAT
                                    @elseif($item->type == 'payment_due')
                                        💳 PEMBAYARAN JATUH TEMPO
                                    @else
                                        📋 PENGINGAT
                                    @endif
                                </span>
                                <p class="text-slate-900 font-black text-[11px]">
                                    {{ $item->customer ?? ($item->customer ?? '') }}</p>
                                <p class="text-slate-600 text-[10px] leading-tight">{{ $item->description ?? '' }}</p>
                                <p class="text-slate-400 text-[9px] mt-1">Due: {{ $item->due_date ?? '' }}</p>
                            </div>
                        @empty
                            <div class="bg-white/60 p-4 rounded-xl text-center">
                                <p class="text-[11px] text-slate-500">Tidak ada pengingat hari ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Tombol Rekomendasi AI --}}
                <button onclick="openRekomendasiModal()" id="btnRekomendasiAI"
                    class="group relative overflow-hidden bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 shadow-lg shadow-indigo-200 flex items-center justify-center gap-2">
                    <div
                        class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/25 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                    </div>
                    <i class="fas fa-lightbulb text-sm group-hover:rotate-12 transition-transform duration-300"></i>
                    <span class="text-sm font-black uppercase tracking-wider">Rekomendasi AI</span>
                    <span id="rekomendasiBadgeCount"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full hidden">0</span>
                </button>
            </div>
        </div>

        {{-- ==================== SECTION 3: ANALYTICS & AI ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mt-6">
            <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-base font-black text-slate-800 uppercase tracking-wider">Analisis Penyewaan</h3>
                    <div class="inline-flex p-1 bg-slate-50 rounded-xl">
                        <button onclick="updateChartRange('mingguan')" id="btnMingguan"
                            class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase bg-white shadow-sm text-indigo-600">Mingguan</button>
                        <button onclick="updateChartRange('tahunan')" id="btnTahunan"
                            class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase text-slate-400">Tahunan</button>
                    </div>
                </div>
                <div class="relative h-[300px]"><canvas id="salesChart"></canvas></div>
            </div>

            {{-- AI Insights Card --}}
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-5 border border-indigo-100">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-robot text-indigo-500 text-xl"></i>
                    <h3 class="font-black text-slate-800 text-sm uppercase tracking-wider">AI Insights</h3>
                </div>
                <div class="space-y-3">
                    <div class="bg-white/60 rounded-xl p-3">
                        <p class="text-[9px] font-bold text-indigo-600 uppercase">💡 Terpopuler</p>
                        <p class="text-xs font-bold text-slate-700 mt-1">
                            {{ $produkProfitAI->nama_barang ?? 'Belum ada data' }}</p>
                        <p class="text-[10px] text-slate-500">Stok: {{ $produkProfitAI->tersedia ?? 0 }} unit</p>
                    </div>
                    <div class="bg-white/60 rounded-xl p-3">
                        <p class="text-[9px] font-bold text-amber-600 uppercase">⚠️ Stok Menipis</p>
                        <p class="text-xs font-bold text-slate-700 mt-1">
                            {{ $produkStokAI->nama_barang ?? 'Belum ada data' }}</p>
                        <p class="text-[10px] text-slate-500">Sisa: {{ $produkStokAI->tersedia ?? 0 }} unit</p>
                    </div>
                    <div class="bg-white/60 rounded-xl p-3">
                        <p class="text-[9px] font-bold text-emerald-600 uppercase">📊 Rata-rata Transaksi</p>
                        <p class="text-xs font-bold text-slate-700 mt-1">{{ number_format($avgDailyTransaksi ?? 0, 1) }}
                            transaksi/hari</p>
                        <p class="text-[10px] text-slate-500">3 bulan terakhir</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== SECTION 4: ACTIVITY & TOP PRODUCTS ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6">Riwayat Aktivitas Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-slate-400 text-[11px] uppercase tracking-wider border-b border-slate-50">
                                <th class="pb-4 font-black">Waktu</th>
                                <th class="pb-4 font-black">Jenis Aktivitas</th>
                                <th class="pb-4 font-black">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="text-[12px]">
                            @forelse($activities ?? [] as $activity)
                                <tr class="border-b border-slate-50">
                                    <td class="py-4 text-slate-500 font-medium">{{ $activity->time ?? '' }}</td>
                                    <td
                                        class="py-4 {{ $activity->type == 'Peminjaman' ? 'text-indigo-600' : 'text-emerald-600' }} font-bold">
                                        {{ $activity->type ?? '' }}</td>
                                    <td class="py-4 text-slate-600">{{ $activity->description ?? '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-slate-400">Belum ada aktivitas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6">Top Products</h3>
                <div class="space-y-6">
                    <div
                        class="grid grid-cols-6 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2">
                        <div class="col-span-1">#</div>
                        <div class="col-span-2">Name</div>
                        <div class="col-span-2">Popularity</div>
                        <div class="col-span-1 text-right">Sales</div>
                    </div>
                    @forelse($topProducts ?? [] as $index => $product)
                        <div class="grid grid-cols-6 items-center gap-2">
                            <div class="text-[11px] font-bold text-slate-400">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                            <div class="col-span-2 text-[11px] font-bold text-slate-700 truncate">
                                {{ $product->name ?? '' }}</div>
                            <div class="col-span-2">
                                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-{{ $index == 0 ? 'amber-500' : ($index == 1 ? 'emerald-500' : ($index == 2 ? 'indigo-500' : 'fuchsia-500')) }} h-full rounded-full"
                                        style="width: {{ $product->popularity ?? 0 }}%"></div>
                                </div>
                            </div>
                            <div class="col-span-1 text-right">
                                <span class="text-[10px] font-black">Rp
                                    {{ number_format($product->sales ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-slate-400 py-4">Belum ada data produk</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== SCRIPT ==================== --}}
    <script>
        // Data dari server untuk calendar
        const calendarEventsData = @json($calendarEvents ?? []);

        // Konversi data calendar ke format yang mudah diakses
        const schedulesData = {};
        calendarEventsData.forEach(event => {
            const dateKey = event.date;
            if (!schedulesData[dateKey]) {
                schedulesData[dateKey] = [];
            }
            schedulesData[dateKey].push(event);
        });

        let currentMonth = {{ $currentMonth ?? date('n') - 1 }};
        let currentYear = {{ $currentYear ?? date('Y') }};
        let salesChart;

        // Calendar Logic
        function generateCalendar(month, year) {
            const grid = document.getElementById('calendarGrid');
            const title = document.getElementById('monthTitle');
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
                "Oktober", "November", "Desember"
            ];

            if (!grid) return;
            grid.innerHTML = '';
            if (title) title.innerText = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            // Adjust firstDay (0 = Minggu)
            for (let i = 0; i < firstDay; i++) {
                grid.innerHTML += `<div></div>`;
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const dateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const events = schedulesData[dateKey] || [];

                // Create markers for events
                let markers = '';
                if (events.length > 0) {
                    const hasStart = events.some(e => e.type === 'rental_start');
                    const hasDue = events.some(e => e.type === 'due_date');
                    const hasReturn = events.some(e => e.type === 'returned');
                    if (hasStart) markers += '<div class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></div>';
                    if (hasDue) markers += '<div class="w-1.5 h-1.5 bg-amber-500 rounded-full"></div>';
                    if (hasReturn) markers += '<div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>';
                }

                const isToday = day === new Date().getDate() && month === new Date().getMonth() && year === new Date()
                    .getFullYear() ?
                    'bg-slate-900 text-white shadow-lg' :
                    'bg-slate-50 text-slate-600 hover:bg-slate-100';

                grid.innerHTML += `
                    <div onclick="showDetail('${dateKey}')" class="h-10 flex flex-col items-center justify-center rounded-xl cursor-pointer transition-all ${isToday} font-bold text-[10px]">
                        ${day}
                        <div class="flex gap-0.5 mt-0.5">${markers}</div>
                    </div>
                `;
            }
        }

        function changeMonth(delta) {
            let newMonth = currentMonth + delta;
            let newYear = currentYear;
            if (newMonth < 0) {
                newMonth = 11;
                newYear--;
            }
            if (newMonth > 11) {
                newMonth = 0;
                newYear++;
            }
            currentMonth = newMonth;
            currentYear = newYear;
            generateCalendar(currentMonth, currentYear);
        }

        function showDetail(dateKey) {
            const events = schedulesData[dateKey] || [];
            if (events.length === 0) return;

            const popup = document.getElementById('schedulePopup');
            const dateLabel = document.getElementById('popupDateLabel');
            const content = document.getElementById('popupContent');

            if (dateLabel) dateLabel.innerText = dateKey;
            if (content) {
                let html = '';
                events.forEach(event => {
                    let color = event.type === 'rental_start' ? 'indigo' : (event.type === 'due_date' ? 'amber' :
                        'emerald');
                    let icon = event.type === 'rental_start' ? '🚚' : (event.type === 'due_date' ? '⏰' : '✅');
                    let title = event.type === 'rental_start' ? 'Pengantaran Barang' : (event.type === 'due_date' ?
                        'Jatuh Tempo Pengembalian' : 'Pengembalian Barang');

                    html += `
                        <div class="bg-${color}-50 p-4 rounded-2xl">
                            <p class="text-[9px] font-black text-${color}-400 uppercase mb-1">${icon} ${title}</p>
                            <p class="text-sm font-black text-slate-800">${event.title || '-'}</p>
                            <p class="text-xs text-slate-600 mt-1">Customer: ${event.customer || '-'}</p>
                            <p class="text-xs text-slate-600">Invoice: ${event.invoice || '-'}</p>
                        </div>
                    `;
                });
                content.innerHTML = `<div class="space-y-4">${html}</div>`;
            }
            popup.classList.remove('hidden');
        }

        function closePopup() {
            document.getElementById('schedulePopup')?.classList.add('hidden');
        }

        // Chart Logic
        function initChart() {
            const ctx = document.getElementById('salesChart')?.getContext('2d');
            if (!ctx) return;

            const weeklyLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            const monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            // Sample data - replace with actual data from controller
            const datasets = [{
                    label: 'Screen',
                    data: [12, 19, 15, 8, 22, 30, 25],
                    borderColor: '#a855f7',
                    backgroundColor: '#a855f720',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Proyektor',
                    data: [25, 20, 30, 22, 40, 45, 50],
                    borderColor: '#3b82f6',
                    backgroundColor: '#3b82f620',
                    fill: true,
                    tension: 0.4
                }
            ];

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weeklyLabels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function updateChartRange(range) {
            if (!salesChart) return;
            const labels = range === 'mingguan' ?
                ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] :
                ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            salesChart.data.labels = labels;
            salesChart.update();

            const btnMingguan = document.getElementById('btnMingguan');
            const btnTahunan = document.getElementById('btnTahunan');
            if (btnMingguan && btnTahunan) {
                const isMingguan = range === 'mingguan';
                btnMingguan.className = isMingguan ?
                    'px-4 py-1.5 rounded-lg text-[10px] font-black uppercase bg-white shadow-sm text-indigo-600' :
                    'px-4 py-1.5 rounded-lg text-[10px] font-black uppercase text-slate-400';
                btnTahunan.className = !isMingguan ?
                    'px-4 py-1.5 rounded-lg text-[10px] font-black uppercase bg-white shadow-sm text-indigo-600' :
                    'px-4 py-1.5 rounded-lg text-[10px] font-black uppercase text-slate-400';
            }
        }

        // Load rekomendasi
        async function loadRecommendations() {
            try {
                const response = await fetch('{{ route('dashboard.recommendations.list') }}');
                const result = await response.json();
                if (result.success) {
                    const total = (result.barang?.length || 0) + (result.promo?.length || 0);
                    const badge = document.getElementById('rekomendasiBadgeCount');
                    if (badge && total > 0) {
                        badge.classList.remove('hidden');
                        badge.textContent = total;
                    } else if (badge) {
                        badge.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading recommendations:', error);
            }
        }

        function openRekomendasiModal() {
            alert('Fitur rekomendasi AI akan segera hadir!');
        }

        // Event listener for Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closePopup();
            }
        });

        // Initialize on load
        window.onload = () => {
            generateCalendar(currentMonth, currentYear);
            initChart();
            loadRecommendations();
        };
    </script>
@endsection
