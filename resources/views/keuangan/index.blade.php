@extends('layouts.app')

@section('title', 'Keuangan - Multidaya Inti Persada')
@section('page-title', 'Laporan Keuangan')
@section('keuangan-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">

        {{-- ==================== HEADER ==================== --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Laporan Keuangan</h1>
                    <p class="text-slate-500 text-sm mt-1">Ringkasan pendapatan dari penyewaan dan biaya operasional</p>
                </div>
                <div class="flex gap-3 flex-wrap">
                    <select id="bulanSelect"
                        class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-gray-500">
                        @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $i => $nama)
                            <option value="{{ $i + 1 }}" {{ $bulan == $i + 1 ? 'selected' : '' }}>{{ $nama }}
                            </option>
                        @endforeach
                    </select>
                    <select id="tahunSelect"
                        class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-gray-500">
                        @foreach ([2023, 2024, 2025, 2026] as $thn)
                            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}
                            </option>
                        @endforeach
                    </select>
                    <button onclick="cetakLaporan()"
                        class="group relative overflow-hidden bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-black uppercase tracking-[0.15em] py-3 px-6 rounded-2xl transition-all duration-300 flex items-center gap-3">
                        <div
                            class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/25 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                        </div>
                        <i class="fas fa-print text-xs"></i>
                        <span>Cetak Laporan</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ==================== STATS CARDS ==================== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Card Pendapatan --}}
            <div onclick="filterByPendapatan()"
                class="group bg-white rounded-4xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pendapatan Sewa</p>
                        </div>
                        <p class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </p>
                        <div
                            class="flex items-center gap-1.5 {{ $pendapatanGrowth >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold text-xs">
                            <i class="fas {{ $pendapatanGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            <span>{{ number_format(abs($pendapatanGrowth), 1) }}%</span>
                            <span class="text-slate-400 font-medium italic">vs bln lalu</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Card Total Biaya --}}
            <div onclick="filterByBiaya()"
                class="group bg-white rounded-4xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-rose-500/10 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pengeluaran</p>
                        </div>
                        <p class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight">
                            Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                        </p>
                        <div
                            class="flex items-center gap-1.5 {{ $pengeluaranGrowth <= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold text-xs">
                            <i class="fas {{ $pengeluaranGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            <span>{{ number_format(abs($pengeluaranGrowth), 1) }}%</span>
                            <span class="text-slate-400 font-medium italic">vs bln lalu</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all duration-500">
                        <i class="fas fa-receipt text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Card Laba Bersih --}}
            <div onclick="filterByLaba()"
                class="group bg-white rounded-4xl p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Laba Bersih</p>
                        </div>
                        <p class="text-2xl sm:text-3xl font-black text-blue-600 tracking-tight">
                            Rp {{ number_format($labaBersih, 0, ',', '.') }}
                        </p>
                        <div
                            class="flex items-center gap-1.5 {{ $labaGrowth >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold text-xs">
                            <i class="fas {{ $labaGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            <span>{{ number_format(abs($labaGrowth), 1) }}%</span>
                            <span class="text-slate-400 font-medium italic">vs bln lalu</span>
                        </div>
                    </div>
                    <div
                        class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all duration-500">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== CHART & RECENT TRANSACTIONS ==================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Chart --}}
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4">Grafik Keuangan {{ $tahun }}</h3>
                <canvas id="keuanganChart" height="200"></canvas>
                <div class="mt-4 flex justify-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div><span>Pendapatan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div><span>Pengeluaran</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div><span>Laba Bersih</span>
                    </div>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4"><i class="fas fa-history mr-2"></i>Transaksi Terbaru</h3>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($recentTransactions as $transaksi)
                        <div class="flex justify-between items-center p-2 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full {{ $transaksi->jenis == 'pendapatan' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                                    <i
                                        class="fas {{ $transaksi->jenis == 'pendapatan' ? 'fa-arrow-down' : 'fa-arrow-up' }} {{ $transaksi->jenis == 'pendapatan' ? 'text-green-600' : 'text-red-600' }}"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $transaksi->deskripsi }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }} •
                                        {{ $transaksi->kategori }} •
                                        <span class="capitalize">{{ $transaksi->sumber }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p
                                    class="font-semibold {{ $transaksi->jenis == 'pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaksi->jenis == 'pendapatan' ? '+' : '-' }} Rp
                                    {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-center py-4">Belum ada transaksi</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ==================== RINCIAN BIAYA ==================== --}}
        <div
            class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 hover:shadow-xl transition-all duration-500 mb-6">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center shadow-sm">
                        <i class="fas fa-chart-pie text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Rincian Biaya</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Alokasi
                            Pengeluaran
                            Kas</p>
                    </div>
                </div>
                <button onclick="openTambahBiayaModal()"
                    class="group relative overflow-hidden bg-slate-800 hover:bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest py-2.5 px-4 rounded-xl transition-all duration-300 flex items-center gap-2">
                    <div
                        class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700">
                    </div>
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Biaya</span>
                </button>
            </div>

            <div class="space-y-6">
                @php
                    $kelompokBiaya = [
                        'operasional' => ['label' => 'Operasional', 'icon' => 'fa-building', 'color' => 'rose'],
                        'promosi' => ['label' => 'Promosi', 'icon' => 'fa-bullhorn', 'color' => 'violet'],
                        'inventaris' => ['label' => 'Inventaris', 'icon' => 'fa-boxes', 'color' => 'amber'],
                    ];
                    $colorHex = [
                        'rose' => 'from-rose-500 to-pink-500',
                        'violet' => 'from-violet-500 to-purple-500',
                        'amber' => 'from-amber-500 to-orange-500',
                    ];
                @endphp

                @foreach ($kelompokBiaya as $key => $info)
                    @php
                        $total = $pengeluaranByKategori->where('sumber', $key)->sum('total');
                        $persen = $totalPengeluaran > 0 ? ($total / $totalPengeluaran) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-{{ $info['color'] }}-50 text-{{ $info['color'] }}-500 flex items-center justify-center text-xs group-hover:bg-{{ $info['color'] }}-500 group-hover:text-white transition-all">
                                    <i class="fas {{ $info['icon'] }}"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700">{{ $info['label'] }}</span>
                            </div>
                            <span class="text-sm font-black text-slate-800 tracking-tight">Rp
                                {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r {{ $colorHex[$info['color']] }} h-full rounded-full transition-all duration-1000 shadow-sm"
                                style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                @endforeach

                @if ($totalPengeluaran > 0)
                    <div class="mt-8 pt-6 border-t border-dashed border-slate-200">
                        <div class="flex justify-between items-center p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Total
                                Akumulasi</span>
                            <span class="text-lg font-black text-rose-600 tracking-tight">Rp
                                {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ==================== RIWAYAT BIAYA OPERASIONAL ==================== --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all hover:shadow-md">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center"><i
                            class="fas fa-history"></i></div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">Riwayat Biaya Operasional</h3>
                        <p class="text-xs text-slate-400">Daftar lengkap semua pengeluaran perusahaan</p>
                    </div>
                </div>
                <div class="flex gap-2 flex-wrap">
                    <input type="date" id="filterStartDate"
                        class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <input type="date" id="filterEndDate"
                        class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <select id="filterSumber" class="px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white">
                        <option value="all">Semua Jenis</option>
                        <option value="operasional">Operasional</option>
                        <option value="promosi">Promosi</option>
                        <option value="inventaris">Inventaris</option>
                    </select>
                    <button onclick="filterRiwayat()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"><i
                            class="fas fa-search"></i> Filter</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full w-full text-sm">
                    <thead class="bg-slate-50/90">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Deskripsi</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Referensi</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="riwayatBiayaTableBody" class="divide-y divide-slate-100">
                        @forelse($riwayatBiaya as $biaya)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 text-xs font-mono font-semibold text-slate-600">
                                    {{ $biaya->kode_biaya }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ \Carbon\Carbon::parse($biaya->tanggal)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    @if ($biaya->sumber == 'operasional')
                                        <span class="bg-rose-100 text-rose-600 text-xs px-2 py-1 rounded-full"><i
                                                class="fas fa-building mr-1"></i>Operasional</span>
                                    @elseif($biaya->sumber == 'promosi')
                                        <span class="bg-violet-100 text-violet-600 text-xs px-2 py-1 rounded-full"><i
                                                class="fas fa-bullhorn mr-1"></i>Promosi</span>
                                    @else
                                        <span class="bg-amber-100 text-amber-600 text-xs px-2 py-1 rounded-full"><i
                                                class="fas fa-boxes mr-1"></i>Inventaris</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $biaya->kategori }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600 max-w-xs truncate"
                                    title="{{ $biaya->deskripsi }}">{{ Str::limit($biaya->deskripsi, 40) }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-red-600 text-right">Rp
                                    {{ number_format($biaya->jumlah, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-sm text-slate-500">{{ $biaya->referensi ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="viewBiayaDetail({{ $biaya->id }})"
                                        class="text-blue-600 hover:text-blue-800 p-1" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                    <button onclick="deleteBiaya({{ $biaya->id }})"
                                        class="text-red-600 hover:text-red-800 p-1" title="Hapus">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                                    <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                    Belum ada data biaya operasional
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-200">
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right font-bold text-slate-700">TOTAL PENGELUARAN
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-red-600 text-lg">Rp
                                {{ number_format($riwayatBiaya->sum('jumlah'), 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50/30 border-t border-slate-100">{{ $riwayatBiaya->links() }}</div>
        </div>

    </div>

    {{-- ==================== MODAL TAMBAH BIAYA ==================== --}}
    <div id="modalSidebar" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" onclick="closeTambahBiayaModal()"></div>
        <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-out"
            id="sidebarContent">
            <div class="h-full flex flex-col">
                <div class="flex justify-between items-center p-6 border-b border-slate-200 bg-gray-50">
                    <h3 class="text-xl font-bold text-slate-800"><i
                            class="fas fa-plus-circle mr-2 text-gray-600"></i>Tambah Biaya</h3>
                    <button onclick="closeTambahBiayaModal()" class="text-slate-400 hover:text-slate-600 transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>
                <form id="formBiaya" class="flex-1 overflow-y-auto p-6">
                    @csrf
                    <div class="space-y-4">
                        <div><label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Biaya *</label>
                            <select name="sumber" id="sumber" required
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-gray-500">
                                <option value="operasional">Operasional</option>
                                <option value="promosi">Promosi</option>
                                <option value="inventaris">Inventaris / Stok Barang</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-1">Kategori *</label>
                            <input type="text" name="kategori" id="kategori" required
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                                placeholder="Contoh: Gaji, Listrik, Iklan">
                        </div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi *</label>
                            <input type="text" name="deskripsi" id="deskripsi" required
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                                placeholder="Keterangan detail">
                        </div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-1">Jumlah (Rp) *</label>
                            <input type="number" name="jumlah" id="jumlah" required min="0"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                                placeholder="0">
                        </div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal *</label>
                            <input type="date" name="tanggal" id="tanggal" required
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-1">Referensi (Opsional)</label>
                            <input type="text" name="referensi" id="referensi"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                                placeholder="No. Invoice / Nota">
                        </div>
                        <div><label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                                placeholder="Catatan tambahan..."></textarea>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-6 pt-4 border-t border-slate-200">
                        <button type="submit"
                            class="flex-1 bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2.5 rounded-lg transition"><i
                                class="fas fa-save mr-1"></i>Simpan</button>
                        <button type="button" onclick="closeTambahBiayaModal()"
                            class="flex-1 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition py-2.5">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL DETAIL BIAYA ==================== --}}
    <div id="modalDetailBiaya"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeDetailBiayaModal()">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-hidden flex flex-col animate-in fade-in zoom-in duration-200">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2"><span
                            class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="fas fa-receipt"></i></span>Detail
                        Biaya Operasional</h3>
                    <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mt-1">Informasi lengkap
                        pengeluaran</p>
                </div>
                <button onclick="closeDetailBiayaModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all"><i
                        class="fas fa-times"></i></button>
            </div>
            <div id="detailBiayaContent" class="p-6 overflow-y-auto custom-scrollbar space-y-4"></div>
            <div class="p-4 border-t border-slate-100 bg-white flex justify-end gap-3">
                <button onclick="closeDetailBiayaModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold rounded-xl text-sm transition-all">Tutup</button>
                <button id="printBiayaBtn" onclick="printBiayaDetail()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition-all"><i
                        class="fas fa-print mr-1"></i>Cetak</button>
            </div>
        </div>
    </div>

    {{-- ==================== TOAST NOTIFICATION ==================== --}}
    <div id="toast" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-4 hidden z-50">
        <div class="flex items-center gap-3"><i id="toastIcon" class="text-xl"></i>
            <p id="toastMessage"></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data Chart
        const monthlyDataRaw = @json($monthlyData);
        const bulanLabels = monthlyDataRaw.map(item => item.bulan);
        const pendapatanData = monthlyDataRaw.map(item => item.pendapatan);
        const pengeluaranData = monthlyDataRaw.map(item => item.pengeluaran);
        const labaData = pendapatanData.map((p, i) => p - pengeluaranData[i]);

        // Initialize Chart
        new Chart(document.getElementById('keuanganChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: bulanLabels,
                datasets: [{
                        label: 'Pendapatan',
                        data: pendapatanData,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Pengeluaran',
                        data: pengeluaranData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Laba Bersih',
                        data: labaData,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        tension: 0.4,
                        fill: true,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label}: Rp ${ctx.raw.toLocaleString('id-ID')}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (val) => 'Rp ' + val.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });

        // Filter handlers
        document.getElementById('bulanSelect').addEventListener('change', function() {
            window.location.href = `?bulan=${this.value}&tahun=${document.getElementById('tahunSelect').value}`;
        });
        document.getElementById('tahunSelect').addEventListener('change', function() {
            window.location.href = `?bulan=${document.getElementById('bulanSelect').value}&tahun=${this.value}`;
        });

        // Filter functions - Redirect ke halaman detail
        function filterByPendapatan() {
            const bulan = document.getElementById('bulanSelect').value;
            const tahun = document.getElementById('tahunSelect').value;
            window.location.href = `{{ route('keuangan.pendapatan') }}?bulan=${bulan}&tahun=${tahun}`;
        }

        function filterByBiaya() {
            const bulan = document.getElementById('bulanSelect').value;
            const tahun = document.getElementById('tahunSelect').value;
            window.location.href = `{{ route('keuangan.pengeluaran') }}?bulan=${bulan}&tahun=${tahun}`;
        }

        function filterByLaba() {
            const bulan = document.getElementById('bulanSelect').value;
            const tahun = document.getElementById('tahunSelect').value;
            window.location.href = `{{ route('keuangan.laba') }}?bulan=${bulan}&tahun=${tahun}`;
        }

        // Sidebar Modal
        function openTambahBiayaModal() {
            document.getElementById('modalSidebar').classList.remove('hidden');
            setTimeout(() => document.getElementById('sidebarContent').classList.remove('translate-x-full'), 10);
        }

        function closeTambahBiayaModal() {
            const s = document.getElementById('sidebarContent');
            s.classList.add('translate-x-full');
            setTimeout(() => {
                document.getElementById('modalSidebar').classList.add('hidden');
                document.getElementById('formBiaya').reset();
            }, 300);
        }

        // Form submission
        document.getElementById('formBiaya').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            try {
                const response = await fetch('{{ route('keuangan.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    showToast(result.message, 'success');
                    closeTambahBiayaModal();
                    setTimeout(() => location.reload(), 500);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Gagal menyimpan data', 'error');
            }
        });

        function cetakLaporan() {
            window.print();
        }

        function showToast(message, type) {
            const t = document.getElementById('toast');
            document.getElementById('toastIcon').className = type === 'success' ?
                'fas fa-check-circle text-green-500 text-xl' : 'fas fa-exclamation-circle text-red-500 text-xl';
            document.getElementById('toastMessage').textContent = message;
            t.classList.remove('hidden');
            setTimeout(() => t.classList.add('hidden'), 3000);
        }

        // Riwayat Biaya Functions
        function filterRiwayat() {
            const startDate = document.getElementById('filterStartDate').value;
            const endDate = document.getElementById('filterEndDate').value;
            const sumber = document.getElementById('filterSumber').value;
            let url = `{{ route('keuangan.riwayat-json') }}?`;
            if (startDate) url += `start_date=${startDate}&`;
            if (endDate) url += `end_date=${endDate}&`;
            if (sumber && sumber !== 'all') url += `sumber=${sumber}`;
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(r => r.json()).then(result => {
                if (result.success) updateRiwayatTable(result.data, result.total);
            }).catch(e => console.error(e));
        }

        function updateRiwayatTable(data, total) {
            const tbody = document.getElementById('riwayatBiayaTableBody');
            if (!tbody) return;
            if (data.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="8" class="px-4 py-12 text-center text-slate-400"><i class="fas fa-inbox text-4xl mb-2 block"></i>Belum ada data biaya operasional</td></tr>';
                return;
            }
            let html = '';
            data.forEach(b => {
                const badge = b.sumber === 'operasional' ?
                    '<span class="bg-rose-100 text-rose-600 text-xs px-2 py-1 rounded-full"><i class="fas fa-building mr-1"></i>Operasional</span>' :
                    (b.sumber === 'promosi' ?
                        '<span class="bg-violet-100 text-violet-600 text-xs px-2 py-1 rounded-full"><i class="fas fa-bullhorn mr-1"></i>Promosi</span>' :
                        '<span class="bg-amber-100 text-amber-600 text-xs px-2 py-1 rounded-full"><i class="fas fa-boxes mr-1"></i>Inventaris</span>'
                    );
                html +=
                    `<tr>
                        <td class="px-4 py-3 text-xs font-mono font-semibold">${escapeHtml(b.kode_biaya)}</td>
                        <td class="px-4 py-3 text-sm">${new Date(b.tanggal).toLocaleDateString('id-ID')}</td>
                        <td class="px-4 py-3">${badge}</td>
                        <td class="px-4 py-3 text-sm">${escapeHtml(b.kategori)}</td>
                        <td class="px-4 py-3 text-sm max-w-xs truncate" title="${escapeHtml(b.deskripsi)}">${escapeHtml(b.deskripsi.substring(0,40))}${b.deskripsi.length>40?'...':''}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-red-600 text-right">Rp ${new Intl.NumberFormat('id-ID').format(b.jumlah)}</td>
                        <td class="px-4 py-3 text-sm">${escapeHtml(b.referensi) || '-'}</td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="viewBiayaDetail(${b.id})" class="text-blue-600 hover:text-blue-800 p-1" title="Lihat Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            <button onclick="deleteBiaya(${b.id})" class="text-red-600 hover:text-red-800 p-1" title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </td>
                    </tr>`;
            });
            const tfoot = tbody.parentElement.querySelector('tfoot');
            if (tfoot) {
                tfoot.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-right font-bold text-slate-700">TOTAL PENGELUARAN</td>
                        <td class="px-4 py-3 text-right font-bold text-red-600 text-lg">Rp ${new Intl.NumberFormat('id-ID').format(total)}</td>
                        <td colspan="2"></td>
                    </tr>`;
            }
            tbody.innerHTML = html;
        }

        function viewBiayaDetail(id) {
            fetch(`{{ url('keuangan') }}/${id}`).then(r => r.json()).then(r => {
                if (r.success) {
                    const d = r.data;
                    document.getElementById('detailBiayaContent').innerHTML =
                        `<div class="grid grid-cols-2 gap-4 border-b pb-4">
                            <div><p class="text-xs text-slate-400">Kode</p><p class="font-mono font-semibold">${escapeHtml(d.kode_biaya)}</p></div>
                            <div><p class="text-xs text-slate-400">Tanggal</p><p>${new Date(d.tanggal).toLocaleDateString('id-ID')}</p></div>
                            <div><p class="text-xs text-slate-400">Jenis</p><p class="capitalize">${escapeHtml(d.sumber)}</p></div>
                            <div><p class="text-xs text-slate-400">Kategori</p><p>${escapeHtml(d.kategori)}</p></div>
                            <div class="col-span-2"><p class="text-xs text-slate-400">Deskripsi</p><p>${escapeHtml(d.deskripsi)}</p></div>
                            <div><p class="text-xs text-slate-400">Jumlah</p><p class="text-xl font-bold text-red-600">Rp ${new Intl.NumberFormat('id-ID').format(d.jumlah)}</p></div>
                            <div><p class="text-xs text-slate-400">Referensi</p><p>${escapeHtml(d.referensi) || '-'}</p></div>
                            <div class="col-span-2"><p class="text-xs text-slate-400">Keterangan</p><p>${escapeHtml(d.keterangan) || '-'}</p></div>
                            <div><p class="text-xs text-slate-400">Dibuat oleh</p><p>${escapeHtml(d.creator?.name) || '-'}</p></div>
                            <div><p class="text-xs text-slate-400">Status</p><p><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-xs">Disetujui</span></p></div>
                        </div>`;
                    document.getElementById('modalDetailBiaya').classList.remove('hidden');
                }
            }).catch(e => console.error(e));
        }

        function closeDetailBiayaModal() {
            document.getElementById('modalDetailBiaya').classList.add('hidden');
        }

        function printBiayaDetail() {
            const w = window.open('', '_blank');
            w.document.write(
                `<!DOCTYPE html>
                <html>
                <head>
                    <title>Detail Biaya</title>
                    <style>
                        body{font-family:Arial,sans-serif;padding:20px}
                        .print-content{max-width:600px;margin:0 auto}
                        table{width:100%;border-collapse:collapse}
                        td{padding:8px;border-bottom:1px solid #ddd}
                        .label{font-weight:bold;width:40%}
                    </style>
                </head>
                <body>
                    <div class="print-content">
                        <h2 style="text-align:center">Detail Biaya Operasional</h2>
                        ${document.getElementById('detailBiayaContent').innerHTML}
                        <p style="text-align:center;margin-top:30px;font-size:12px;color:#999">Dicetak pada: ${new Date().toLocaleString()}</p>
                    </div>
                </body>
                </html>`
            );
            w.document.close();
            w.print();
        }

        function deleteBiaya(id) {
            if (confirm('Yakin ingin menghapus data biaya ini?')) {
                fetch(`{{ url('keuangan') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(r => r.json()).then(r => {
                    if (r.success) {
                        showToast(r.message, 'success');
                        location.reload();
                    } else {
                        showToast(r.message, 'error');
                    }
                }).catch(e => showToast('Gagal menghapus data', 'error'));
            }
        }

        // Set default date filter
        const today = new Date();
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        document.getElementById('filterStartDate').value = firstDayOfMonth.toISOString().split('T')[0];
        document.getElementById('filterEndDate').value = today.toISOString().split('T')[0];

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>

    <style>
        @media print {

            .no-print,
            button,
            select,
            #modalSidebar {
                display: none !important;
            }

            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .shadow-lg,
            .shadow-md {
                box-shadow: none !important;
            }
        }

        #modalSidebar {
            transition: all 0.3s ease;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
@endsection
