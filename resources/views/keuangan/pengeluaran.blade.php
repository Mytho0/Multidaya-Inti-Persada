@extends('layouts.app')

@section('title', 'Detail Pengeluaran - Multidaya Inti Persada')
@section('page-title', 'Detail Pengeluaran Operasional')
@section('keuangan-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Detail Pengeluaran</h1>
                    <p class="text-slate-500 text-sm mt-1">Seluruh biaya operasional, promosi, dan inventaris</p>
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
                    </button>
                </div>
            </div>
        </div>

        {{-- Stats Cards by Category --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-rose-500 to-pink-500 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Operasional</p>
                        <p class="text-xl font-bold mt-2">Rp {{ number_format($totalOperasional, 0, ',', '.') }}</p>
                    </div>
                    <i class="fas fa-building text-3xl opacity-50"></i>
                </div>
            </div>
            <div class="bg-gradient-to-br from-violet-500 to-purple-500 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Promosi</p>
                        <p class="text-xl font-bold mt-2">Rp {{ number_format($totalPromosi, 0, ',', '.') }}</p>
                    </div>
                    <i class="fas fa-bullhorn text-3xl opacity-50"></i>
                </div>
            </div>
            <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Inventaris</p>
                        <p class="text-xl font-bold mt-2">Rp {{ number_format($totalInventaris, 0, ',', '.') }}</p>
                    </div>
                    <i class="fas fa-boxes text-3xl opacity-50"></i>
                </div>
            </div>
        </div>

        {{-- Chart --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4">Grafik Pengeluaran {{ $tahun }}</h3>
                <canvas id="pengeluaranChart" height="200"></canvas>
            </div>
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4">Komposisi Pengeluaran</h3>
                <canvas id="komposisiChart" height="200"></canvas>
            </div>
        </div>

        {{-- Daftar Pengeluaran --}}
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-gray-50 flex justify-between items-center flex-wrap gap-3">
                <h3 class="font-bold text-slate-800">Daftar Semua Pengeluaran</h3>
                <div class="flex gap-2 flex-wrap">
                    <input type="text" id="searchInput" placeholder="Cari deskripsi..."
                        class="px-3 py-1 border rounded-lg text-sm">
                    <select id="jenisFilter" class="px-3 py-1 border rounded-lg text-sm">
                        <option value="all">Semua Jenis</option>
                        <option value="operasional">Operasional</option>
                        <option value="promosi">Promosi</option>
                        <option value="inventaris">Inventaris</option>
                    </select>
                    <input type="date" id="startDate" class="px-3 py-1 border rounded-lg text-sm">
                    <input type="date" id="endDate" class="px-3 py-1 border rounded-lg text-sm">
                    <button onclick="filterTable()"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-lg text-sm">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Deskripsi</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Referensi</th>
                        </tr>
                    </thead>
                    <tbody id="pengeluaranTableBody" class="divide-y divide-slate-100">
                        @foreach ($pengeluarans as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-xs font-mono">{{ $p->kode_biaya }}</td>
                                <td class="px-6 py-3 text-sm">{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-3">
                                    @if ($p->sumber == 'operasional')
                                        <span class="bg-rose-100 text-rose-600 px-2 py-1 rounded-full text-xs"><i
                                                class="fas fa-building mr-1"></i>Operasional</span>
                                    @elseif($p->sumber == 'promosi')
                                        <span class="bg-violet-100 text-violet-600 px-2 py-1 rounded-full text-xs"><i
                                                class="fas fa-bullhorn mr-1"></i>Promosi</span>
                                    @else
                                        <span class="bg-amber-100 text-amber-600 px-2 py-1 rounded-full text-xs"><i
                                                class="fas fa-boxes mr-1"></i>Inventaris</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-sm">{{ $p->kategori }}</td>
                                <td class="px-6 py-3 text-sm max-w-xs truncate" title="{{ $p->deskripsi }}">
                                    {{ Str::limit($p->deskripsi, 40) }}</td>
                                <td class="px-6 py-3 text-sm text-right font-semibold text-red-600">Rp
                                    {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-sm">{{ $p->referensi ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="5" class="px-6 py-3 text-right font-bold">TOTAL PENGELUARAN</td>
                            <td class="px-6 py-3 text-right font-bold text-red-600 text-lg">Rp
                                {{ number_format($pengeluarans->sum('jumlah'), 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $pengeluarans->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);
        const komposisi = @json($komposisi);

        // Line chart
        new Chart(document.getElementById('pengeluaranChart'), {
            type: 'line',
            data: {
                labels: chartData.map(d => d.bulan),
                datasets: [{
                    label: 'Pengeluaran',
                    data: chartData.map(d => d.pengeluaran),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `Pengeluaran: Rp ${ctx.raw.toLocaleString('id-ID')}`
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

        // Pie chart for composition
        new Chart(document.getElementById('komposisiChart'), {
            type: 'doughnut',
            data: {
                labels: ['Operasional', 'Promosi', 'Inventaris'],
                datasets: [{
                    data: [komposisi.operasional, komposisi.promosi, komposisi.inventaris],
                    backgroundColor: ['#f43f5e', '#8b5cf6', '#f59e0b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) =>
                                `${ctx.label}: Rp ${ctx.raw.toLocaleString('id-ID')} (${((ctx.raw / komposisi.total) * 100).toFixed(1)}%)`
                        }
                    }
                }
            }
        });

        document.getElementById('bulanSelect').addEventListener('change', function() {
            window.location.href =
                `{{ route('keuangan.pengeluaran') }}?bulan=${this.value}&tahun=${document.getElementById('tahunSelect').value}`;
        });
        document.getElementById('tahunSelect').addEventListener('change', function() {
            window.location.href =
                `{{ route('keuangan.pengeluaran') }}?bulan=${document.getElementById('bulanSelect').value}&tahun=${this.value}`;
        });

        function exportData() {
            const bulan = document.getElementById('bulanSelect').value;
            const tahun = document.getElementById('tahunSelect').value;
            window.open(`/keuangan/pengeluaran/export?bulan=${bulan}&tahun=${tahun}`, '_blank');
        }

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const jenis = document.getElementById('jenisFilter').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const rows = document.querySelectorAll('#pengeluaranTableBody tr');

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const rowJenis = row.cells[2]?.innerText.toLowerCase() || '';
                const rowDate = row.cells[1]?.innerText.split('/').reverse().join('-') || '';

                let show = text.includes(search);
                if (jenis !== 'all') {
                    show = show && rowJenis.includes(jenis.toLowerCase());
                }
                if (startDate && rowDate < startDate) show = false;
                if (endDate && rowDate > endDate) show = false;

                row.style.display = show ? '' : 'none';
            });
        }
    </script>
@endsection
