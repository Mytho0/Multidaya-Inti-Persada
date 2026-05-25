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
                                    @if (isset($item->type))
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
                                    @else
                                        📋 PENGINGAT
                                    @endif
                                </span>
                                {{-- Menampilkan invoice dan nomor telepon --}}
                                <div class="flex items-center justify-between flex-wrap gap-1">
                                    <p class="text-slate-900 font-black text-[11px]">
                                        {{ $item->invoice ?? ($item->customer ?? '-') }}
                                    </p>
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-phone-alt text-[9px] text-gray-500"></i>
                                        <span class="text-[10px] font-semibold text-emerald-700">
                                            {{ $item->no_telepon ?? ($item->customer_whatsapp ?? '-') }}
                                        </span>
                                        @if ($item->no_telepon ?? ($item->customer_whatsapp ?? ''))
                                            <a href="https://wa.me/62{{ preg_replace('/^0/', '', $item->no_telepon ?? $item->customer_whatsapp) }}"
                                                target="_blank" class="text-green-600 hover:text-green-700 ml-1">
                                                <i class="fab fa-whatsapp text-[10px]"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-slate-600 text-[10px] leading-tight mt-1">
                                    {{ $item->description ?? ($item->barang ?? '-') }}
                                </p>
                                <p class="text-slate-400 text-[9px] mt-1">Due:
                                    {{ $item->due_date ?? ($item->waktu ?? '-') }}</p>
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
                <div class="flex flex-wrap gap-3 mt-2">
                    <div class="flex items-center gap-1.5"><span class="w-3 h-1 bg-[#a855f7] rounded-full"></span><span
                            class="text-[9px] font-black text-slate-400 uppercase">Screen</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-1 bg-[#3b82f6] rounded-full"></span><span
                            class="text-[9px] font-black text-slate-400 uppercase">Proyektor</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-1 bg-[#10b981] rounded-full"></span><span
                            class="text-[9px] font-black text-slate-400 uppercase">TV</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-1 bg-[#f59e0b] rounded-full"></span><span
                            class="text-[9px] font-black text-slate-400 uppercase">Kabel</span></div>
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
                            <tr class="text-slate-600 text-[11px] mr-2 uppercase tracking-wider border-b border-slate-50">
                                <th class="pb-4 font-black">Waktu</th>
                                <th class="pb-4 pl-4 font-black">Jenis Aktivitas</th>
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
                                <span
                                    class="text-[10px] font-black text-{{ $index == 0 ? 'amber-600' : ($index == 1 ? 'emerald-600' : ($index == 2 ? 'indigo-600' : 'fuchsia-600')) }} bg-{{ $index == 0 ? 'amber-50' : ($index == 1 ? 'emerald-50' : ($index == 2 ? 'indigo-50' : 'fuchsia-50')) }} px-2 py-1 rounded-lg">{{ $product->popularity ?? 0 }}%</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-slate-400 py-4">Belum ada data produk</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL REKOMENDASI AI (INITIAL) ==================== --}}
    <div id="modalRekomendasiAwal"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeRekomendasiAwal()">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[85vh] flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
            <div
                class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-brain text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">AI Business Advisor</h3>
                        <p class="text-[10px] opacity-80 font-medium uppercase tracking-wider mt-0.5">Rekomendasi berbasis
                            analisis data real-time</p>
                    </div>
                </div>
                <button onclick="closeRekomendasiAwal()"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20 transition-all"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-6 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Card Pengadaan Barang --}}
                    <div onclick="openRekomendasiBarang()" class="group cursor-pointer">
                        <div
                            class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl p-6 hover:shadow-xl hover:from-indigo-100 hover:to-indigo-200 transition-all duration-300 border-2 border-transparent hover:border-indigo-300 h-full">
                            <div
                                class="w-16 h-16 bg-indigo-500 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-boxes text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-black text-slate-800 mb-2">Rekomendasi Pengadaan Barang</h4>
                            <p class="text-xs text-slate-500 mb-3">Analisis stok dan permintaan pelanggan untuk rekomendasi
                                tambah inventaris</p>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-indigo-600">Lihat Rekomendasi →</span>
                                <span id="badgeBarangCount"
                                    class="bg-indigo-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden">0</span>
                            </div>
                        </div>
                    </div>

                    {{-- Card Promo --}}
                    <div onclick="openRekomendasiPromo()" class="group cursor-pointer">
                        <div
                            class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 hover:shadow-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-300 border-2 border-transparent hover:border-purple-300 h-full">
                            <div
                                class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center text-white mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-percent text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-black text-slate-800 mb-2">Rekomendasi Promo</h4>
                            <p class="text-xs text-slate-500 mb-3">Analisis waktu terbaik untuk promo berdasarkan pola
                                peminjaman</p>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-purple-600">Lihat Rekomendasi →</span>
                                <span id="badgePromoCount"
                                    class="bg-purple-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full hidden">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL REKOMENDASI BARANG ==================== --}}
    <div id="modalRekomendasiBarang"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeRekomendasiBarang()">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[85vh] flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
            <div
                class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-boxes text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">Rekomendasi Pengadaan Barang</h3>
                        <p class="text-[10px] opacity-80 font-medium mt-0.5">Berdasarkan analisis stok & permintaan</p>
                    </div>
                </div>
                <button onclick="closeRekomendasiBarang()"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20 transition-all"><i
                        class="fas fa-times"></i></button>
            </div>
            <div id="rekomendasiBarangList" class="p-6 overflow-y-auto space-y-4">
                <div class="text-center py-8 text-slate-400">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2 block"></i>
                    <p>Memuat rekomendasi...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL REKOMENDASI PROMO ==================== --}}
    <div id="modalRekomendasiPromo"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeRekomendasiPromo()">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[85vh] flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
            <div
                class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-purple-600 text-white sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-percent text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold tracking-tight">Rekomendasi Promo</h3>
                        <p class="text-[10px] opacity-80 font-medium mt-0.5">Berdasarkan analisis pola peminjaman</p>
                    </div>
                </div>
                <button onclick="closeRekomendasiPromo()"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20 transition-all"><i
                        class="fas fa-times"></i></button>
            </div>
            <div id="rekomendasiPromoList" class="p-6 overflow-y-auto space-y-4">
                <div class="text-center py-8 text-slate-400">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2 block"></i>
                    <p>Memuat rekomendasi...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Template Card Rekomendasi Barang --}}
    <template id="templateCardBarang">
        <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-indigo-600 text-sm"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-base"></h4>
                </div>
                <div class="bg-indigo-50 text-indigo-600 text-[9px] font-black px-2 py-1 rounded-full">Skor AI: <span
                        class="score-value"></span>%</div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="bg-slate-50 rounded-lg p-2 text-center">
                    <p class="text-[9px] text-slate-400 uppercase">Stok Saat Ini</p>
                    <p class="text-sm font-bold text-slate-800 stock-current">0 unit</p>
                </div>
                <div class="bg-slate-50 rounded-lg p-2 text-center">
                    <p class="text-[9px] text-slate-400 uppercase">Permintaan</p>
                    <p class="text-sm font-bold text-indigo-600 demand-value">0 unit/bulan</p>
                </div>
            </div>
            <div class="bg-indigo-50 rounded-xl p-3 mb-3">
                <p class="text-[9px] font-bold text-indigo-600 uppercase mb-1">💡 Rekomendasi AI</p>
                <p class="text-xs text-slate-600 leading-relaxed description"></p>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                <div class="bg-emerald-50 px-3 py-1.5 rounded-lg">
                    <span class="text-[10px] font-bold text-emerald-600">🎯 Saran Tambah: </span>
                    <span class="text-xs font-black text-emerald-700 quantity-suggest"></span>
                </div>
                <button onclick="applyRekomendasi(this)" data-id="" data-type="barang"
                    class="apply-rekomendasi bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-all">
                    <i class="fas fa-check-circle mr-1"></i> Konfirmasi Pengadaan
                </button>
            </div>
        </div>
    </template>

    {{-- Template Card Rekomendasi Promo --}}
    <template id="templateCardPromo">
        <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-lg transition-all duration-300">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-purple-600 text-sm"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 text-base"></h4>
                </div>
                <div class="bg-purple-50 text-purple-600 text-[9px] font-black px-2 py-1 rounded-full">Skor AI: <span
                        class="score-value"></span>%</div>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="bg-slate-50 rounded-lg p-2 text-center">
                    <p class="text-[9px] text-slate-400 uppercase">Analisis</p>
                    <p class="text-xs font-bold text-slate-800 analysis-type"></p>
                </div>
                <div class="bg-slate-50 rounded-lg p-2 text-center">
                    <p class="text-[9px] text-slate-400 uppercase">Potensi</p>
                    <p class="text-xs font-bold text-emerald-600 potential-gain"></p>
                </div>
            </div>
            <div class="bg-purple-50 rounded-xl p-3 mb-3">
                <p class="text-[9px] font-bold text-purple-600 uppercase mb-1">💡 Saran Promo AI</p>
                <p class="text-xs text-slate-600 leading-relaxed description"></p>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                <div class="bg-amber-50 px-3 py-1.5 rounded-lg">
                    <span class="text-[10px] font-bold text-amber-600">💰 Estimasi Pendapatan (semua stok): </span>
                    <span class="text-xs font-black text-amber-700 revenue-estimate"></span>
                </div>
                <button onclick="applyRekomendasi(this)" data-id="" data-type="promo"
                    class="apply-rekomendasi bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-all">
                    <i class="fas fa-check-circle mr-1"></i> Konfirmasi Promo
                </button>
            </div>
        </div>
    </template>

    {{-- ==================== MODAL TAMBAH PROMO ==================== --}}
    <div id="modalTambahPromo"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeTambahPromoModal()">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-md w-full max-h-[85vh] overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white">
                <div class="flex items-center gap-2"><i class="fas fa-percent"></i><span class="font-bold">Buat Promo
                        (Rekomendasi AI)</span></div>
                <button onclick="closeTambahPromoModal()"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-white/20 transition-all"><i
                        class="fas fa-times text-sm"></i></button>
            </div>
            <form id="formPromoCepat" class=" p-6 space-y-4" onsubmit="submitPromoCepat(event)">
                @csrf
                <div><label class="block text-xs font-bold text-slate-600 mb-1">Nama Promo</label><input type="text"
                        name="nama_promo" id="nama_promo" required
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                <div><label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi Promo</label>
                    <textarea name="deskripsi" id="deskripsi_promo" rows="2"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-bold text-slate-600 mb-1">Jenis Diskon</label><select
                            name="jenis_diskon" id="jenis_diskon"
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                            <option value="persen">Persentase (%)</option>
                            <option value="nominal">Nominal (Rp)</option>
                        </select></div>
                    <div><label class="block text-xs font-bold text-slate-600 mb-1">Nilai Diskon</label><input
                            type="number" name="nilai_diskon" id="diskon_promo" required
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-bold text-slate-600 mb-1">Tanggal Mulai</label><input
                            type="date" name="tanggal_mulai" id="tanggal_mulai" required
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                    <div><label class="block text-xs font-bold text-slate-600 mb-1">Tanggal Selesai</label><input
                            type="date" name="tanggal_selesai" id="tanggal_selesai" required
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                </div>
                <div><label class="block text-xs font-bold text-slate-600 mb-1">Pilih Barang</label><select
                        name="barang_id" id="barang_id_promo" required
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                        <option value="">Pilih Barang</option>
                    </select></div>
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition-all">Simpan
                    Promo</button>
            </form>
        </div>
    </div>

    {{-- ==================== MODAL TAMBAH INVENTARIS ==================== --}}
    <div id="modalTambahInventaris"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeTambahInventarisModal()">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-md w-full max-h-[85vh] overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-indigo-600 text-white">
                <div class="flex items-center gap-2"><i class="fas fa-boxes"></i><span class="font-bold">Tambah
                        Inventaris (Rekomendasi AI)</span></div>
                <button onclick="closeTambahInventarisModal()"
                    class="w-6 h-6 flex items-center justify-center rounded-full hover:bg-white/20 transition-all"><i
                        class="fas fa-times text-sm"></i></button>
            </div>
            <form id="formInventarisCepat" class="p-6 space-y-4" onsubmit="submitInventarisCepat(event)">
                @csrf
                <div><label class="block text-xs font-bold text-slate-600 mb-1">Nama Barang</label><input type="text"
                        name="nama_barang" id="nama_barang_inventaris" required
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                <div><label class="block text-xs font-bold text-slate-600 mb-1">Kode Barang</label><input type="text"
                        name="kode_barang" id="kode_barang_inventaris"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm" placeholder="Auto generate">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-bold text-slate-600 mb-1">Jenis Barang</label><select
                            name="jenis" id="jenis_barang_inventaris" required
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm">
                            <option value="screen">Screen/LED</option>
                            <option value="proyektor">Proyektor</option>
                            <option value="tv">TV</option>
                            <option value="kabel">Kabel/Aksesoris</option>
                            <option value="sound">Sound System</option>
                        </select></div>
                    <div><label class="block text-xs font-bold text-slate-600 mb-1">Harga Sewa</label><input
                            type="number" name="harga_sewa" id="harga_sewa_inventaris" required
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                </div>
                <div><label class="block text-xs font-bold text-slate-600 mb-1">Stok Awal</label><input type="number"
                        name="tersedia" id="tersedia_inventaris" required value="1"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></div>
                <div><label class="block text-xs font-bold text-slate-600 mb-1">Deskripsi Barang</label>
                    <textarea name="deskripsi" id="deskripsi_inventaris" rows="2"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm"></textarea>
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg transition-all">Simpan
                    Inventaris</button>
            </form>
        </div>
    </div>

    {{-- ==================== TOAST NOTIFICATION ==================== --}}
    <div id="toast" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-3 hidden z-50">
        <div class="flex items-center gap-2"><i id="toastIcon" class="text-lg"></i>
            <p id="toastMessage" class="text-sm"></p>
        </div>
    </div>

    {{-- ==================== FLOATING WHATSAPP ==================== --}}
    <div
        class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-[100] flex flex-col items-end w-[calc(100%-2rem)] sm:w-auto">
        <div id="waFormCard"
            class="hidden mb-4 w-full sm:w-80 bg-white shadow-[0_20px_50px_rgba(0,0,0,0.2)] rounded-[2rem] border border-slate-100 overflow-hidden animate-slide-up flex flex-col max-h-[75vh] sm:max-h-none">
            <div class="bg-emerald-500 p-5 text-white shrink-0">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 p-2 rounded-lg"><i class="fab fa-whatsapp text-lg"></i></div>
                        <div>
                            <h3 class="font-bold text-sm leading-none">WhatsApp Blast</h3>
                            <p class="text-[10px] opacity-80 mt-1">Kirim pengingat cepat</p>
                        </div>
                    </div>
                    <button onclick="toggleWAForm()"
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-black/10 transition-all"><i
                            class="fas fa-times text-xs"></i></button>
                </div>
            </div>
            <div class="p-5 overflow-y-auto custom-scrollbar">
                <form class="space-y-4">
                    <div><label class="text-[10px] font-black text-slate-400 mb-1.5 block uppercase tracking-widest">Nomor
                            WhatsApp</label>
                        <div class="relative group"><span
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">+62</span><input
                                type="number" id="waNumber" placeholder="812345678"
                                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none font-bold text-slate-700 transition-all">
                        </div>
                    </div>
                    <div><label class="text-[10px] font-black text-slate-400 mb-1.5 block uppercase tracking-widest">Pilih
                            Template</label>
                        <div class="relative"><select id="waType" onchange="updateWATemplate()"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none cursor-pointer font-bold text-slate-700 appearance-none transition-all">
                                <option value="custom">✍️ Pesan Custom</option>
                                <option value="pengingat">🔔 Pengingat Sewa (H-1)</option>
                                <option value="pengembalian">📦 Pengembalian (H-1)</option>
                            </select><i
                                class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>
                    <div><label class="text-[10px] font-black text-slate-400 mb-1.5 block uppercase tracking-widest">Isi
                            Pesan</label>
                        <textarea id="waText" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none resize-none font-medium text-slate-600 leading-relaxed transition-all"
                            placeholder="Tulis pesan..."></textarea>
                    </div>
                    <button type="button" onclick="sendToWhatsApp()"
                        class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black py-3.5 rounded-xl text-[11px] shadow-lg shadow-emerald-100 active:scale-95 transition-all flex items-center justify-center gap-2 uppercase tracking-widest mt-2"><i
                            class="fab fa-whatsapp text-base"></i><span>Kirim Sekarang</span></button>
                </form>
            </div>
        </div>
        <button onclick="toggleWAForm()"
            class="bg-emerald-500 text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-90 transition-all border-4 border-white"><i
                class="fab fa-whatsapp text-2xl"></i></button>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .animate-slide-up {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data dari controller
        const calendarEventsData = @json($calendarEvents ?? []);

        @php
            $defaultMingguan = [['label' => 'Screen', 'data' => [12, 19, 15, 8, 22, 30, 25], 'color' => '#a855f7'], ['label' => 'Proyektor', 'data' => [25, 20, 30, 22, 40, 45, 50], 'color' => '#3b82f6'], ['label' => 'TV', 'data' => [15, 10, 18, 12, 20, 25, 22], 'color' => '#10b981'], ['label' => 'Kabel', 'data' => [5, 8, 4, 10, 6, 12, 15], 'color' => '#f59e0b']];
            $defaultBulanan = [['label' => 'Screen', 'data' => [120, 150, 180, 140, 200, 250], 'color' => '#a855f7'], ['label' => 'Proyektor', 'data' => [200, 250, 220, 300, 400, 450], 'color' => '#3b82f6']];
        @endphp

        const chartMingguanData = @json($chartMingguanData ?? $defaultMingguan);
        const chartBulananData = @json($chartBulananData ?? $defaultBulanan);
        const basePrice = {{ $hargaNormal ?? 150000 }};

        // Konversi calendarEventsData ke format yang mudah diakses
        const schedulesData = {};
        if (calendarEventsData && Array.isArray(calendarEventsData)) {
            calendarEventsData.forEach(event => {
                const dateKey = event.date;
                if (dateKey) {
                    if (!schedulesData[dateKey]) {
                        schedulesData[dateKey] = [];
                    }
                    schedulesData[dateKey].push(event);
                }
            });
        }

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
                        <div class="flex gap-0.5 mt-0.5 justify-center">${markers}</div>
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
                            <p class="text-xs text-slate-600">No. Telepon: ${event.no_telepon || '-'}</p>
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

            const datasets = chartMingguanData.map(item => ({
                label: item.label,
                data: item.data,
                borderColor: item.color,
                backgroundColor: item.color + '20',
                fill: true,
                tension: 0.4
            }));

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
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
            const datasets = range === 'mingguan' ? chartMingguanData.map(item => ({
                label: item.label,
                data: item.data,
                borderColor: item.color,
                backgroundColor: item.color + '20',
                fill: true,
                tension: 0.4
            })) : chartBulananData.map(item => ({
                label: item.label,
                data: item.data,
                borderColor: item.color,
                backgroundColor: item.color + '20',
                fill: true,
                tension: 0.4
            }));
            const labels = range === 'mingguan' ? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] : ['Jan', 'Feb', 'Mar',
                'Apr', 'Mei', 'Jun'
            ];
            salesChart.data = {
                labels: labels,
                datasets: datasets
            };
            salesChart.update();
            const isMingguan = range === 'mingguan';
            const btnMingguan = document.getElementById('btnMingguan');
            const btnTahunan = document.getElementById('btnTahunan');
            if (btnMingguan) btnMingguan.className = isMingguan ?
                'px-4 py-1.5 rounded-lg text-[10px] font-black uppercase bg-white shadow-sm text-indigo-600' :
                'px-4 py-1.5 rounded-lg text-[10px] font-black uppercase text-slate-400';
            if (btnTahunan) btnTahunan.className = !isMingguan ?
                'px-4 py-1.5 rounded-lg text-[10px] font-black uppercase bg-white shadow-sm text-indigo-600' :
                'px-4 py-1.5 rounded-lg text-[10px] font-black uppercase text-slate-400';
        }

        // ==================== REKOMENDASI AI FUNCTIONS ====================
        let currentBarangList = [];
        let currentPromoList = [];

        async function loadRecommendations() {
            try {
                const response = await fetch('{{ route('dashboard.recommendations.list') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();

                if (result.success) {
                    currentBarangList = result.barang || [];
                    currentPromoList = result.promo || [];

                    // Update badge counts
                    const badgeBarang = document.getElementById('badgeBarangCount');
                    const badgePromo = document.getElementById('badgePromoCount');
                    const btnBadge = document.getElementById('rekomendasiBadgeCount');

                    if (badgeBarang) {
                        if (currentBarangList.length > 0) {
                            badgeBarang.classList.remove('hidden');
                            badgeBarang.textContent = currentBarangList.length;
                        } else {
                            badgeBarang.classList.add('hidden');
                        }
                    }

                    if (badgePromo) {
                        if (currentPromoList.length > 0) {
                            badgePromo.classList.remove('hidden');
                            badgePromo.textContent = currentPromoList.length;
                        } else {
                            badgePromo.classList.add('hidden');
                        }
                    }

                    const totalRekomendasi = currentBarangList.length + currentPromoList.length;
                    if (btnBadge && totalRekomendasi > 0) {
                        btnBadge.classList.remove('hidden');
                        btnBadge.textContent = totalRekomendasi;
                    } else if (btnBadge) {
                        btnBadge.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading recommendations:', error);
            }
        }

        function openRekomendasiModal() {
            loadRecommendations();
            document.getElementById('modalRekomendasiAwal').classList.remove('hidden');
            document.getElementById('modalRekomendasiAwal').classList.add('flex');
        }

        function closeRekomendasiAwal() {
            document.getElementById('modalRekomendasiAwal').classList.add('hidden');
            document.getElementById('modalRekomendasiAwal').classList.remove('flex');
        }

        function openRekomendasiBarang() {
            closeRekomendasiAwal();
            renderRekomendasiBarang();
            document.getElementById('modalRekomendasiBarang').classList.remove('hidden');
            document.getElementById('modalRekomendasiBarang').classList.add('flex');
        }

        function closeRekomendasiBarang() {
            document.getElementById('modalRekomendasiBarang').classList.add('hidden');
            document.getElementById('modalRekomendasiBarang').classList.remove('flex');
        }

        function openRekomendasiPromo() {
            closeRekomendasiAwal();
            renderRekomendasiPromo();
            document.getElementById('modalRekomendasiPromo').classList.remove('hidden');
            document.getElementById('modalRekomendasiPromo').classList.add('flex');
        }

        function closeRekomendasiPromo() {
            document.getElementById('modalRekomendasiPromo').classList.add('hidden');
            document.getElementById('modalRekomendasiPromo').classList.remove('flex');
        }

        function renderRekomendasiBarang() {
            const container = document.getElementById('rekomendasiBarangList');
            if (!container) return;

            if (currentBarangList.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-slate-400">
                        <i class="fas fa-check-circle text-4xl mb-2 block text-emerald-400"></i>
                        <p class="font-bold">Stok Semua Barang Aman</p>
                        <p class="text-xs mt-1">Tidak ada barang yang perlu ditambah saat ini</p>
                    </div>`;
                return;
            }

            container.innerHTML = '';
            currentBarangList.forEach(item => {
                const isKritis = item.demand_label === 'CRITICAL';
                const color = isKritis ? 'red' : 'amber';
                const icon = isKritis ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';
                const badge = isKritis ? 'KRITIS' : 'PERLU DIPERHATIKAN';

                const card = document.createElement('div');
                card.className =
                    'bg-white rounded-xl border border-slate-200 p-5 hover:shadow-lg transition-all duration-300';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-${color}-100 rounded-lg flex items-center justify-center">
                                <i class="fas ${icon} text-${color}-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">${item.nama_barang || item.title}</h4>
                                <span class="text-[9px] font-black text-${color}-600 bg-${color}-50 px-2 py-0.5 rounded-full">${badge}</span>
                            </div>
                        </div>
                        <div class="bg-indigo-50 text-indigo-600 text-[9px] font-black px-2 py-1 rounded-full">
                            Skor: ${item.score}%
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div class="bg-slate-50 rounded-lg p-2 text-center">
                            <p class="text-[8px] text-slate-400 uppercase">Utilisasi</p>
                            <p class="text-sm font-black text-${color}-600">
                                ${Math.round((item.utilisasi_rate || 0) * 100)}%
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-2 text-center">
                            <p class="text-[8px] text-slate-400 uppercase">Tersedia</p>
                            <p class="text-sm font-bold text-slate-800">
                                ${item.potential_gain?.replace('+', '').replace(' Unit', '') || '?'} sisa
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-2 text-center">
                            <p class="text-[8px] text-slate-400 uppercase">Urgensi</p>
                            <p class="text-[10px] font-black text-${color}-600">${item.analysis_type || '-'}</p>
                        </div>
                    </div>

                    <div class="bg-${color}-50 rounded-xl p-3 mb-3">
                        <p class="text-[9px] font-bold text-${color}-600 uppercase mb-1">💡 Analisis ML</p>
                        <p class="text-xs text-slate-600 leading-relaxed">${item.description}</p>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                        <div class="bg-emerald-50 px-3 py-1.5 rounded-lg">
                            <span class="text-[9px] font-bold text-emerald-600">📈 Est. Revenue: </span>
                            <span class="text-xs font-black text-emerald-700">${item.revenue_estimate || '-'}</span>
                        </div>
                        <button onclick="applyRekomendasi(this)"
                            data-id="${item.id}"
                            data-barang-id="${item.barang_id}"
                            data-type="barang"
                            data-nama="${item.nama_barang || item.title}"
                            class="apply-rekomendasi bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition-all">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah Stok
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        function renderRekomendasiPromo() {
            const container = document.getElementById('rekomendasiPromoList');
            const template = document.getElementById('templateCardPromo');
            if (!container) return;

            if (currentPromoList.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-slate-400">
                        <i class="fas fa-inbox text-4xl mb-2 block"></i>
                        <p>Belum ada rekomendasi promo</p>
                    </div>`;
                return;
            }

            container.innerHTML = '';

            currentPromoList.forEach(item => {
                const clone = template.content.cloneNode(true);

                clone.querySelector('h4').textContent = item.nama_barang || item.title;
                clone.querySelector('.score-value').textContent = item.score || 80;
                clone.querySelector('.analysis-type').textContent = item.analysis_type || 'Analisis ML';
                clone.querySelector('.potential-gain').textContent = item.potential_gain || '-';
                clone.querySelector('.description').textContent = item.description;
                clone.querySelector('.revenue-estimate').textContent = item.revenue_estimate || '-';

                const btn = clone.querySelector('.apply-rekomendasi');
                btn.setAttribute('data-id', item.id);
                btn.setAttribute('data-type', 'promo');
                btn.setAttribute('data-nama', item.nama_barang || item.title);
                btn.setAttribute('data-diskon', item.nilai_diskon || 15);
                btn.setAttribute('data-jenis-diskon', item.jenis_diskon || 'persen');
                btn.setAttribute('data-deskripsi', item.description);
                btn.setAttribute('data-barang-id', item.barang_id || '');

                const iconWrap = clone.querySelector('.w-8.h-8');
                const icon = clone.querySelector('.fa-clock');
                if (item.jenis_promo === 'Diskon 30%') {
                    iconWrap.className = 'w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center';
                    icon.className = 'fas fa-fire text-red-600 text-sm';
                } else if (item.jenis_promo === 'Bundle Deal') {
                    iconWrap.className = 'w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center';
                    icon.className = 'fas fa-box text-amber-600 text-sm';
                } else {
                    iconWrap.className = 'w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center';
                    icon.className = 'fas fa-star text-green-600 text-sm';
                }

                container.appendChild(clone);
            });
        }

        async function applyRekomendasi(button) {
            const id = button.getAttribute('data-id');
            const barangId = button.getAttribute('data-barang-id');
            const type = button.getAttribute('data-type');
            const nama = button.getAttribute('data-nama');

            const rec = type === 'barang' ?
                currentBarangList.find(r => r.barang_id == barangId) :
                currentPromoList.find(r => r.id == id);

            try {
                const response = await fetch('{{ route('dashboard.recommendations.apply') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id
                    })
                });
                const result = await response.json();

                if (result.success) {
                    if (type === 'barang') {
                        if (rec) {
                            const namaBarang = rec.nama_barang ||
                                rec.title
                                .replace('Stok Kritis: ', '')
                                .replace('Pertimbangkan Tambah Stok: ', '');

                            document.getElementById('nama_barang_inventaris').value = namaBarang;
                            document.getElementById('deskripsi_inventaris').value = rec.description;

                            if (rec.harga_sewa) {
                                document.getElementById('harga_sewa_inventaris').value = rec.harga_sewa;
                            }

                            const jenisMap = {
                                'Proyektor': 'proyektor',
                                'Layar': 'screen',
                                'TV': 'tv',
                                'Kabel': 'kabel',
                            };
                            const jenisSelect = document.getElementById('jenis_barang_inventaris');
                            if (jenisSelect && rec.jenis_barang) {
                                jenisSelect.value = jenisMap[rec.jenis_barang] || 'proyektor';
                            }
                        }
                        document.getElementById('formInventarisCepat')
                            .setAttribute('data-barang-id', rec.barang_id);

                        closeRekomendasiBarang();
                        openTambahInventarisModal();

                    } else if (type === 'promo') {
                        if (rec) {
                            document.getElementById('nama_promo').value = `Promo AI - ${nama}`;
                            document.getElementById('deskripsi_promo').value = rec.description;
                            document.getElementById('jenis_diskon').value = rec.jenis_diskon || 'persen';
                            document.getElementById('diskon_promo').value = rec.nilai_diskon || 15;

                            const today = new Date().toISOString().split('T')[0];
                            const end = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
                            document.getElementById('tanggal_mulai').value = today;
                            document.getElementById('tanggal_selesai').value = end;
                        }
                        closeRekomendasiPromo();
                        openTambahPromoModal(rec ? rec.barang_id : null);
                    }

                    showToast(result.message || 'Berhasil!', 'success');
                    loadRecommendations();

                } else {
                    showToast(result.message || 'Gagal menerapkan rekomendasi', 'error');
                }

            } catch (error) {
                console.error('Apply error:', error);
                showToast('Gagal menerapkan rekomendasi', 'error');
            }
        }

        function openTambahPromoModal(preSelectedBarangId = null) {
            const select = document.getElementById('barang_id_promo');
            if (select) {
                select.innerHTML = '<option value="">Pilih Barang</option>';
                fetch('{{ route('dashboard.recommendations.list') }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(result => {
                        if (result.barangList) {
                            result.barangList.forEach(b => {
                                const opt = document.createElement('option');
                                opt.value = b.id;
                                opt.textContent = `${b.nama_barang} (Tersedia: ${b.tersedia})`;
                                select.appendChild(opt);
                            });
                            if (preSelectedBarangId) {
                                select.value = preSelectedBarangId;
                            }
                        }
                    });
            }
            document.getElementById('modalTambahPromo').classList.remove('hidden');
            document.getElementById('modalTambahPromo').classList.add('flex');
        }

        function closeTambahPromoModal() {
            document.getElementById('modalTambahPromo').classList.add('hidden');
            document.getElementById('modalTambahPromo').classList.remove('flex');
            document.getElementById('formPromoCepat').reset();
        }

        async function submitPromoCepat(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            const barangId = document.getElementById('barang_id_promo').value;
            if (!barangId) {
                showToast('Pilih barang terlebih dahulu!', 'error');
                return;
            }

            try {
                const res = await fetch('{{ route('promo.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                const result = await res.json();
                if (result.success) {
                    showToast(result.message, 'success');
                    closeTambahPromoModal();
                    loadRecommendations();
                } else {
                    showToast(result.message || 'Gagal menyimpan promo', 'error');
                }
            } catch (error) {
                showToast('Gagal menyimpan promo', 'error');
            }
        }

        function openTambahInventarisModal() {
            document.getElementById('modalTambahInventaris').classList.remove('hidden');
            document.getElementById('modalTambahInventaris').classList.add('flex');
        }

        function closeTambahInventarisModal() {
            document.getElementById('modalTambahInventaris').classList.add('hidden');
            document.getElementById('modalTambahInventaris').classList.remove('flex');
            document.getElementById('formInventarisCepat').reset();
        }

        async function submitInventarisCepat(e) {
            e.preventDefault();

            const barangId = document.getElementById('formInventarisCepat')
                .getAttribute('data-barang-id');
            const tambahStok = parseInt(document.getElementById('tersedia_inventaris').value) || 1;
            const hargaSewa = parseInt(document.getElementById('harga_sewa_inventaris').value) || 0;
            const deskripsi = document.getElementById('deskripsi_inventaris').value;

            if (!barangId) {
                showToast('Barang ID tidak ditemukan', 'error');
                return;
            }

            try {
                const res = await fetch(`/barang/${barangId}/tambah-stok`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        tambah_stok: tambahStok,
                        harga_sewa: hargaSewa,
                        deskripsi: deskripsi,
                        catat_biaya: true
                    })
                });
                const result = await res.json();
                if (result.success) {
                    showToast(result.message || 'Stok berhasil ditambahkan!', 'success');
                    closeTambahInventarisModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message || 'Gagal menyimpan', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal menyimpan', 'error');
            }
        }

        function toggleWAForm() {
            document.getElementById('waFormCard')?.classList.toggle('hidden');
        }

        function updateWATemplate() {
            const t = {
                pengingat: "Halo, ini pengingat sewa besok. Mohon persiapkan diri Anda. Terima kasih.",
                pengembalian: "Halo, ini pengingat pengembalian barang besok. Harap kembalikan tepat waktu."
            };
            document.getElementById('waText').value = t[document.getElementById('waType')?.value] || "";
        }

        function sendToWhatsApp() {
            let num = document.getElementById('waNumber')?.value;
            let text = document.getElementById('waText')?.value;
            if (!num) return alert("Isi nomor!");
            window.open(`https://api.whatsapp.com/send?phone=62${num}&text=${encodeURIComponent(text)}`, '_blank');
        }

        function showToast(message, type) {
            const toast = document.getElementById('toast');
            if (!toast) {
                alert(message);
                return;
            }
            const icon = document.getElementById('toastIcon');
            const msg = document.getElementById('toastMessage');
            icon.className = type === 'success' ? 'fas fa-check-circle text-green-500 text-lg' :
                'fas fa-exclamation-circle text-red-500 text-lg';
            msg.textContent = message;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closePopup();
                closeRekomendasiAwal();
                closeRekomendasiBarang();
                closeRekomendasiPromo();
            }
        });

        async function populateBarangDropdown() {
            try {
                const res = await fetch('{{ route('dashboard.recommendations.list') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await res.json();
                const select = document.getElementById('barang_id_promo');
                if (!select || !result.barangList) return;

                select.innerHTML = '<option value="">Pilih Barang</option>';
                result.barangList.forEach(b => {
                    const opt = document.createElement('option');
                    opt.value = b.id;
                    opt.textContent = `${b.nama_barang} (Tersedia: ${b.tersedia})`;
                    select.appendChild(opt);
                });
            } catch (e) {
                console.error('Gagal load daftar barang:', e);
            }
        }

        window.onload = () => {
            generateCalendar(currentMonth, currentYear);
            initChart();
            updateWATemplate();
            loadRecommendations();

            fetch('{{ route('promo.check') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).catch(e => console.log('Promo check:', e));
        };
    </script>
@endsection
