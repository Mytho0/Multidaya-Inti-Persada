@extends('layouts.app')

@section('title', 'Detail Laba Bersih - Multidaya Inti Persada')
@section('page-title', 'Detail Laba Bersih')
@section('keuangan-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Detail Laba Bersih</h1>
                    <p class="text-slate-500 text-sm mt-1">Analisis lengkap laba bersih perusahaan</p>
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
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">Total Laba Bersih</p>
                <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalLaba, 0, ',', '.') }}</p>
                <div class="flex items-center gap-2 mt-3 text-sm">
                    <i class="fas {{ $trend >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    <span>{{ number_format(abs($trend), 1) }}% dari periode sebelumnya</span>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-sm">Margin Laba</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($marginLaba, 1) }}%</p>
                <p class="text-xs text-slate-400 mt-2">Dari total pendapatan</p>
            </div>
        </div>

        {{-- Chart --}}
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 mb-6">
            <h3 class="font-bold text-slate-800 mb-4">Grafik Laba Bersih {{ $tahun }}</h3>
            <canvas id="labaChart" height="100"></canvas>
        </div>

        {{-- Detail Table --}}
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-gray-50">
                <h3 class="font-bold text-slate-800">Rincian Laba per Bulan</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Bulan</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Pendapatan</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Pengeluaran</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Laba Bersih</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Margin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($detailPerBulan as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium">{{ $data['bulan'] }}</td>
                                <td class="px-6 py-3 text-right text-green-600">Rp
                                    {{ number_format($data['pendapatan'], 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-right text-red-600">Rp
                                    {{ number_format($data['pengeluaran'], 0, ',', '.') }}</td>
                                <td
                                    class="px-6 py-3 text-right font-bold {{ $data['laba'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                    Rp {{ number_format(abs($data['laba']), 0, ',', '.') }}
                                    @if ($data['laba'] < 0)
                                        <span class="text-xs">(Rugi)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span
                                        class="px-2 py-1 rounded-full text-xs {{ $data['margin'] >= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        {{ number_format($data['margin'], 1) }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);

        new Chart(document.getElementById('labaChart'), {
            type: 'bar',
            data: {
                labels: chartData.map(d => d.bulan),
                datasets: [{
                    label: 'Laba Bersih',
                    data: chartData.map(d => d.laba),
                    backgroundColor: chartData.map(d => d.laba >= 0 ? '#3b82f6' : '#ef4444'),
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `Laba: Rp ${ctx.raw.toLocaleString('id-ID')}`
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

        document.getElementById('bulanSelect').addEventListener('change', function() {
            window.location.href =
                `{{ route('keuangan.laba') }}?bulan=${this.value}&tahun=${document.getElementById('tahunSelect').value}`;
        });
        document.getElementById('tahunSelect').addEventListener('change', function() {
            window.location.href =
                `{{ route('keuangan.laba') }}?bulan=${document.getElementById('bulanSelect').value}&tahun=${this.value}`;
        });

        function cetakLaporan() {
            window.print();
        }
    </script>

    <style>
        @media print {

            button,
            select {
                display: none !important;
            }

            body {
                background: white;
            }
        }
    </style>
@endsection
