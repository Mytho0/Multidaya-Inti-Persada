@extends('layouts.app')

@section('title', 'Laporan Laba Rugi - Multidaya Inti Persada')
@section('page-title', 'Laporan Laba Rugi')
@section('keuangan-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
<div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-5xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Laporan Laba Rugi</h1>
                <p class="text-slate-500 text-sm mt-1">PT. MULTIDAYA INTI PERSADA</p>
            </div>
            <div class="flex gap-3 flex-wrap">
                <div class="flex gap-2">
                    <input type="month" id="periodeStart" class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500" value="2026-01">
                    <span class="flex items-center">s.d</span>
                    <input type="month" id="periodeEnd" class="px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500" value="2026-04">
                </div>
                <button onclick="filterLaporan()" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fas fa-filter"></i>
                    <span>Tampilkan</span>
                </button>
                <button onclick="cetakLaporan()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition flex items-center gap-2">
                    <i class="fas fa-print"></i>
                    <span>Cetak</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Laporan Laba Rugi -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden" id="laporanContainer">
        <div class="p-6">
            <!-- Header Perusahaan -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-slate-800">PT. MULTIDAYA INTI PERSADA</h2>
                <h3 class="text-xl font-semibold text-slate-700 mt-1">Laporan Laba Rugi</h3>
                <p class="text-slate-500 mt-1">Periode: <span id="periodeText">01 Januari 2026 s.d 30 April 2026</span></p>
                <p class="text-slate-500">Klasifikasi: Semua</p>
            </div>

            <!-- Tabel Laporan -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="laporanTable">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-2 font-semibold text-slate-700">Akun</th>
                            <th class="text-right py-3 px-2 font-semibold text-slate-700 w-48">Saldo</th>
                        </tr>
                    </thead>
                    <tbody id="laporanBody">
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Loading -->
            <div id="loadingIndicator" class="hidden text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-700 mx-auto"></div>
                <p class="text-slate-500 mt-2">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-4 hidden z-50">
    <div class="flex items-center gap-3">
        <i id="toastIcon" class="text-xl"></i>
        <p id="toastMessage"></p>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #laporanContainer, #laporanContainer * {
        visibility: visible;
    }
    #laporanContainer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        margin: 0;
        padding: 20px;
        box-shadow: none;
    }
    button, .no-print {
        display: none !important;
    }
}
</style>

<script>
// Format Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(angka);
}

// Format tanggal
function formatTanggal(tanggal) {
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    return new Date(tanggal).toLocaleDateString('id-ID', options);
}

// Fetch data laporan
async function fetchLaporan() {
    const startDate = document.getElementById('periodeStart').value;
    const endDate = document.getElementById('periodeEnd').value;
    
    document.getElementById('loadingIndicator').classList.remove('hidden');
    document.getElementById('laporanBody').innerHTML = '';
    
    try {
        const response = await fetch(`/keuangan/laporan-lab-rugi?start=${startDate}&end=${endDate}`);
        const result = await response.json();
        
        if (result.success) {
            renderLaporan(result.data);
            document.getElementById('periodeText').innerText = 
                `${formatTanggal(result.start_date)} s.d ${formatTanggal(result.end_date)}`;
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Gagal memuat data laporan', 'error');
    } finally {
        document.getElementById('loadingIndicator').classList.add('hidden');
    }
}

// Render laporan
function renderLaporan(data) {
    const tbody = document.getElementById('laporanBody');
    
    const rows = [
        // PENDAPATAN
        { jenis: 'header', text: '4.0.00 - PENDAPATAN', class: 'font-bold bg-gray-50' },
        { jenis: 'subheader', text: '4.1.00 - PENDAPATAN USAHA', class: 'font-semibold pl-4' },
        { jenis: 'akun', kode: '4.1.01', nama: 'Pendapatan', nilai: data.pendapatan_usaha, class: 'pl-8' },
        { jenis: 'total', text: 'TOTAL PENDAPATAN', nilai: data.pendapatan_usaha, class: 'font-bold border-t border-slate-200' },
        { jenis: 'spacer' },
        
        // HARGA POKOK PENJUALAN
        { jenis: 'header', text: '5.0.00 - HARGA POKOK PENJUALAN', class: 'font-bold bg-gray-50' },
        { jenis: 'subheader', text: '5.1.00 - HARGA POKOK PENJUALAN', class: 'font-semibold pl-4' },
        { jenis: 'akun', kode: '5.1.01', nama: 'Harga Pokok Penjualan', nilai: data.hpp, class: 'pl-8' },
        { jenis: 'subheader', text: '5.2.00 - HARGA POKOK BARANG DAGANG', class: 'font-semibold pl-4' },
        { jenis: 'akun', kode: '5.2.01', nama: 'Pembelian (Inventaris)', nilai: data.pembelian, class: 'pl-8' },
        { jenis: 'total', text: 'TOTAL HARGA POKOK PENJUALAN', nilai: data.hpp + data.pembelian, class: 'font-bold border-t border-slate-200' },
        
        // LABA KOTOR
        { jenis: 'laba_kotor', text: 'LABA KOTOR', nilai: data.laba_kotor, class: 'font-bold text-lg border-t-2 border-slate-300' },
        { jenis: 'keterangan', text: '(TOTAL PENDAPATAN - TOTAL HARGA POKOK PENJUALAN)', nilai: '', class: 'text-xs text-slate-400 pl-8' },
        { jenis: 'spacer' },
        
        // BIAYA USAHA
        { jenis: 'header', text: '6.0.00 - BIAYA', class: 'font-bold bg-gray-50' },
        { jenis: 'subheader', text: '6.1.00 - BIAYA USAHA', class: 'font-semibold pl-4' },
        { jenis: 'akun', kode: '6.1.01', nama: 'Biaya Umum dan Administratif (Operasional)', nilai: data.biaya_operasional, class: 'pl-8' },
        { jenis: 'total', text: 'TOTAL BIAYA', nilai: data.biaya_operasional, class: 'font-bold border-t border-slate-200' },
        
        // TOTAL PENDAPATAN USAHA
        { jenis: 'total_pendapatan_usaha', text: 'TOTAL PENDAPATAN USAHA', nilai: data.laba_kotor - data.biaya_operasional, class: 'font-bold text-lg border-t-2 border-slate-300' },
        { jenis: 'keterangan', text: '(TOTAL LABA KOTOR - TOTAL BIAYA)', nilai: '', class: 'text-xs text-slate-400 pl-8' },
        { jenis: 'spacer' },
        
        // PENDAPATAN LAINNYA
        { jenis: 'header', text: '7.0.00 - PENDAPATAN LAINNYA', class: 'font-bold bg-gray-50' },
        { jenis: 'subheader', text: '7.1.00 - PENDAPATAN DILUAR USAHA', class: 'font-semibold pl-4' },
        { jenis: 'akun', kode: '7.1.01', nama: 'Pendapatan Lainnya', nilai: data.pendapatan_lain, class: 'pl-8' },
        { jenis: 'total', text: 'TOTAL PENDAPATAN LAINNYA', nilai: data.pendapatan_lain, class: 'font-bold border-t border-slate-200' },
        { jenis: 'spacer' },
        
        // BIAYA LAINNYA
        { jenis: 'header', text: '8.0.00 - BIAYA LAINNYA', class: 'font-bold bg-gray-50' },
        { jenis: 'subheader', text: '8.1.00 - BIAYA DILUAR USAHA', class: 'font-semibold pl-4' },
        { jenis: 'akun', kode: '8.1.01', nama: 'Biaya Lainnya', nilai: data.biaya_lain, class: 'pl-8' },
        { jenis: 'total', text: 'TOTAL BIAYA LAINNYA', nilai: data.biaya_lain, class: 'font-bold border-t border-slate-200' },
        
        // TOTAL PENDAPATAN DILUAR USAHA
        { jenis: 'total_pendapatan_luar', text: 'TOTAL PENDAPATAN DILUAR USAHA', nilai: data.pendapatan_lain - data.biaya_lain, class: 'font-semibold border-t-2 border-slate-300' },
        { jenis: 'keterangan', text: '(TOTAL PENDAPATAN LAINNYA - TOTAL BIAYA LAINNYA)', nilai: '', class: 'text-xs text-slate-400 pl-8' },
        { jenis: 'spacer' },
        
        // LABA/RUGI BERSIH
        { jenis: 'laba_rugi_bersih', text: 'LABA/RUGI BERSIH', nilai: (data.laba_kotor - data.biaya_operasional) + (data.pendapatan_lain - data.biaya_lain), class: 'font-bold text-xl border-t-2 border-slate-800 bg-green-50' },
        { jenis: 'keterangan', text: '(TOTAL PENDAPATAN USAHA + TOTAL PENDAPATAN DILUAR USAHA)', nilai: '', class: 'text-xs text-slate-400 pl-8' }
    ];
    
    let html = '';
    for (const row of rows) {
        if (row.jenis === 'spacer') {
            html += '<tr><td colspan="2" style="height: 10px;"></td></tr>';
        } else if (row.jenis === 'header') {
            html += `
                <tr class="${row.class}">
                    <td class="py-2 px-2 font-bold">${row.text}</td>
                    <td class="py-2 px-2 text-right font-bold"></td>
                </tr>
            `;
        } else if (row.jenis === 'subheader') {
            html += `
                <tr class="${row.class}">
                    <td class="py-1.5 px-2">${row.text}</td>
                    <td class="py-1.5 px-2 text-right"></td>
                </tr>
            `;
        } else if (row.jenis === 'akun') {
            html += `
                <tr class="${row.class}">
                    <td class="py-1.5 px-2">${row.kode} - ${row.nama}</td>
                    <td class="py-1.5 px-2 text-right">${formatRupiah(row.nilai)}</td>
                </tr>
            `;
        } else if (row.jenis === 'total') {
            html += `
                <tr class="${row.class}">
                    <td class="py-2 px-2 font-semibold">${row.text}</td>
                    <td class="py-2 px-2 text-right font-semibold">${formatRupiah(row.nilai)}</td>
                </tr>
            `;
        } else if (row.jenis === 'laba_kotor') {
            html += `
                <tr class="${row.class}">
                    <td class="py-2 px-2 font-bold">${row.text}</td>
                    <td class="py-2 px-2 text-right font-bold text-green-700">${formatRupiah(row.nilai)}</td>
                </tr>
            `;
        } else if (row.jenis === 'total_pendapatan_usaha') {
            html += `
                <tr class="${row.class}">
                    <td class="py-2 px-2 font-bold">${row.text}</td>
                    <td class="py-2 px-2 text-right font-bold text-green-700">${formatRupiah(row.nilai)}</td>
                </tr>
            `;
        } else if (row.jenis === 'total_pendapatan_luar') {
            const nilai = row.nilai;
            const colorClass = nilai >= 0 ? 'text-green-700' : 'text-red-700';
            html += `
                <tr class="${row.class}">
                    <td class="py-2 px-2 font-semibold">${row.text}</td>
                    <td class="py-2 px-2 text-right font-semibold ${colorClass}">${formatRupiah(nilai)}</td>
                </tr>
            `;
        } else if (row.jenis === 'laba_rugi_bersih') {
            const nilai = row.nilai;
            const colorClass = nilai >= 0 ? 'text-green-700' : 'text-red-700';
            html += `
                <tr class="${row.class}">
                    <td class="py-3 px-2 font-bold">${row.text}</td>
                    <td class="py-3 px-2 text-right font-bold ${colorClass} text-xl">${formatRupiah(nilai)}</td>
                </tr>
            `;
        } else if (row.jenis === 'keterangan') {
            html += `
                <tr class="${row.class}">
                    <td colspan="2" class="py-0 px-2">${row.text}</td>
                </tr>
            `;
        }
    }
    
    tbody.innerHTML = html;
}

// Filter laporan
function filterLaporan() {
    fetchLaporan();
}

// Cetak laporan
function cetakLaporan() {
    window.print();
}

// Show toast
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const icon = document.getElementById('toastIcon');
    const msg = document.getElementById('toastMessage');
    icon.className = type === 'success' ? 'fas fa-check-circle text-green-500 text-xl' : 'fas fa-exclamation-circle text-red-500 text-xl';
    msg.textContent = message;
    toast.classList.remove('hidden');
    setTimeout(() => toast.classList.add('hidden'), 3000);
}

// Initial load
document.addEventListener('DOMContentLoaded', () => {
    fetchLaporan();
});
</script>
@endsection