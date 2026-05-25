@extends('layouts.app')

@section('title', 'Laporan Laba Rugi - Multidaya Inti Persada')
@section('page-title', 'Laporan Laba / Rugi')
@section('keuangan-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-5xl mx-auto">
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Laporan Laba / Rugi</h1>
                    <p class="text-slate-500 text-sm mt-1">Periode {{ $bulanNama }} {{ $tahun }}</p>
                </div>
                <div class="flex gap-3">
                    <select id="bulanSelect" class="px-4 py-2 border border-slate-300 rounded-lg">
                        @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $i => $nama)
                            <option value="{{ $i + 1 }}" {{ $bulan == $i + 1 ? 'selected' : '' }}>{{ $nama }}
                            </option>
                        @endforeach
                    </select>
                    <select id="tahunSelect" class="px-4 py-2 border border-slate-300 rounded-lg">
                        @foreach ([2023, 2024, 2025, 2026] as $thn)
                            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}
                            </option>
                        @endforeach
                    </select>
                    <button onclick="cetakLaporan()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>

        {{-- Laporan Laba Rugi Format Akuntansi --}}
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-gray-50">
                <h3 class="font-bold text-slate-800 text-center">PT MULTIDAYA INTI PERSADA</h3>
                <p class="text-center text-sm text-slate-500">Laporan Laba/Rugi</p>
                <p class="text-center text-sm text-slate-500">Periode {{ $bulanNama }} {{ $tahun }}</p>
            </div>

            <div class="p-6">
                {{-- PENDAPATAN --}}
                <div class="mb-6">
                    <h4 class="font-bold text-green-600 border-b border-green-200 pb-2 mb-3">PENDAPATAN</h4>
                    <table class="w-full">
                        <tr class="border-b border-slate-100">
                            <td class="py-2">Pendapatan Sewa Alat Berat</td>
                            <td class="py-2 text-right">Rp {{ number_format($pendapatanSewa, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-2">Pendapatan Sewa Lainnya</td>
                            <td class="py-2 text-right">Rp {{ number_format($pendapatanLain, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-green-50 font-bold">
                            <td class="py-2">TOTAL PENDAPATAN</td>
                            <td class="py-2 text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                {{-- BIAYA OPERASIONAL --}}
                <div class="mb-6">
                    <h4 class="font-bold text-red-600 border-b border-red-200 pb-2 mb-3">BIAYA OPERASIONAL</h4>
                    <table class="w-full">
                        @foreach ($biayaOperasional as $biaya)
                            <tr class="border-b border-slate-100">
                                <td class="py-2">{{ $biaya->kategori }}</td>
                                <td class="py-2 text-right">Rp {{ number_format($biaya->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-red-50 font-bold">
                            <td class="py-2">TOTAL BIAYA OPERASIONAL</td>
                            <td class="py-2 text-right">Rp {{ number_format($totalOperasional, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                {{-- BIAYA PROMOSI --}}
                @if ($totalPromosi > 0)
                    <div class="mb-6">
                        <h4 class="font-bold text-red-600 border-b border-red-200 pb-2 mb-3">BIAYA PROMOSI</h4>
                        <table class="w-full">
                            @foreach ($biayaPromosi as $biaya)
                                <tr class="border-b border-slate-100">
                                    <td class="py-2">{{ $biaya->kategori }}</td>
                                    <td class="py-2 text-right">Rp {{ number_format($biaya->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-red-50 font-bold">
                                <td class="py-2">TOTAL BIAYA PROMOSI</td>
                                <td class="py-2 text-right">Rp {{ number_format($totalPromosi, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                @endif

                {{-- BIAYA INVENTARIS --}}
                @if ($totalInventaris > 0)
                    <div class="mb-6">
                        <h4 class="font-bold text-red-600 border-b border-red-200 pb-2 mb-3">BIAYA INVENTARIS</h4>
                        <table class="w-full">
                            @foreach ($biayaInventaris as $biaya)
                                <tr class="border-b border-slate-100">
                                    <td class="py-2">{{ $biaya->kategori }}</td>
                                    <td class="py-2 text-right">Rp {{ number_format($biaya->total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-red-50 font-bold">
                                <td class="py-2">TOTAL BIAYA INVENTARIS</td>
                                <td class="py-2 text-right">Rp {{ number_format($totalInventaris, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                @endif

                {{-- TOTAL BIAYA --}}
                <div class="mb-6">
                    <div class="bg-gray-100 p-3 rounded-lg">
                        <table class="w-full">
                            <tr class="font-bold">
                                <td class="py-2">TOTAL BIAYA</td>
                                <td class="py-2 text-right">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- LABA/RUGI BERSIH --}}
                <div
                    class="mt-4 p-4 rounded-lg {{ $labaBersih >= 0 ? 'bg-blue-50 border border-blue-200' : 'bg-red-50 border border-red-200' }}">
                    <table class="w-full">
                        <tr class="font-bold text-lg">
                            <td class="py-2 {{ $labaBersih >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                                {{ $labaBersih >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}
                            </td>
                            <td class="py-2 text-right {{ $labaBersih >= 0 ? 'text-blue-700' : 'text-red-700' }}">
                                Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Rasio --}}
                <div class="mt-6 pt-4 border-t border-slate-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-500">Margin Laba Kotor:</span>
                            <span class="font-bold">{{ number_format($marginLabaKotor, 1) }}%</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Margin Laba Bersih:</span>
                            <span class="font-bold">{{ number_format($marginLabaBersih, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('bulanSelect').addEventListener('change', function() {
            window.location.href =
                `{{ route('keuangan.laporan-laba-rugi') }}?bulan=${this.value}&tahun=${document.getElementById('tahunSelect').value}`;
        });
        document.getElementById('tahunSelect').addEventListener('change', function() {
            window.location.href =
                `{{ route('keuangan.laporan-laba-rugi') }}?bulan=${document.getElementById('bulanSelect').value}&tahun=${this.value}`;
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

            .shadow-md {
                box-shadow: none !important;
            }
        }
    </style>
@endsection
