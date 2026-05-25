@extends('layouts.app')

@section('title', 'Detail Pendapatan - Multidaya Inti Persada')
@section('page-title', 'Detail Pendapatan Sewa')
@section('keuangan-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Detail Pendapatan Sewa</h1>
                    <p class="text-slate-500 text-sm mt-1">Seluruh pendapatan dari transaksi penyewaan barang yang telah
                        selesai</p>
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
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                <p class="text-sm opacity-90">Total Pendapatan</p>
                <p class="text-2xl font-bold mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                <p class="text-xs opacity-75 mt-1">Sudah termasuk PPN 11%</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-sm">Rata-rata per Transaksi</p>
                <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($rataRata, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <p class="text-slate-500 text-sm">Total Transaksi Selesai</p>
                <p class="text-2xl font-bold text-slate-800 mt-2">{{ number_format($totalTransaksi) }}</p>
                <p class="text-xs text-slate-400 mt-1">Peminjaman dengan status selesai</p>
            </div>
        </div>

        {{-- Chart --}}
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-6 mb-6">
            <h3 class="font-bold text-slate-800 mb-4">Grafik Pendapatan {{ $tahun }}</h3>
            <canvas id="pendapatanChart" height="100"></canvas>
        </div>

        {{-- Daftar Transaksi Pendapatan --}}
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-gray-50 flex justify-between items-center flex-wrap gap-3">
                <h3 class="font-bold text-slate-800">Daftar Transaksi Pendapatan</h3>
                <div class="flex gap-2 flex-wrap">
                    <input type="text" id="searchInput" placeholder="Cari invoice/pelanggan..."
                        class="px-3 py-1 border rounded-lg text-sm w-48">
                    <select id="statusFilter" class="px-3 py-1 border rounded-lg text-sm">
                        <option value="all">Semua Status</option>
                        <option value="lunas">Lunas</option>
                        <option value="dp">DP</option>
                        <option value="belum_bayar">Belum Bayar</option>
                    </select>
                    <button onclick="resetFilters()" class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
                        <i class="fas fa-undo-alt"></i> Reset
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Tgl Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">No. Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Acara</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Total Harga</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Diskon</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">PPN 11%</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Grand Total</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pendapatanTableBody" class="divide-y divide-slate-100">
                        @forelse ($pendapatans as $p)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-sm whitespace-nowrap">
                                    {{ $p->tanggal_pengembalian_real ? \Carbon\Carbon::parse($p->tanggal_pengembalian_real)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-3 text-sm font-mono font-semibold text-blue-600">
                                    {{ $p->invoice_number }}
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    <div class="font-medium text-slate-800">{{ $p->nama_penyewa }}</div>
                                    <div class="text-xs text-slate-400">{{ $p->no_telepon }}</div>
                                </td>
                                <td class="px-6 py-3 text-sm">
                                    {{ $p->nama_acara ?? '-' }}
                                    <div class="text-xs text-slate-400">{{ $p->lokasi_acara ?? '' }}</div>
                                </td>
                                <td class="px-6 py-3 text-sm text-right">
                                    Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-sm text-right text-red-500">
                                    {{ $p->diskon > 0 ? 'Rp ' . number_format($p->diskon, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-6 py-3 text-sm text-right">
                                    Rp {{ number_format($p->total_ppn, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-sm text-right font-bold text-green-600">
                                    Rp {{ number_format($p->grand_total_with_ppn, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if ($p->status_pembayaran == 'lunas')
                                        <span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs">
                                            <i class="fas fa-check-circle mr-1"></i> Lunas
                                        </span>
                                    @elseif($p->status_pembayaran == 'dp')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs">
                                            <i class="fas fa-clock mr-1"></i> DP
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs">
                                            <i class="fas fa-times-circle mr-1"></i> Belum Bayar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <button onclick="showDetail({{ $p->id }})"
                                        class="text-blue-600 hover:text-blue-800 p-1" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </button>
                                    <button onclick="printInvoice({{ $p->id }})"
                                        class="text-gray-600 hover:text-gray-800 p-1" title="Cetak Invoice">
                                        <i class="fas fa-print text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                    Belum ada transaksi pendapatan untuk periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-slate-200">
                        <tr>
                            <td colspan="7" class="px-6 py-3 text-right font-bold text-slate-700">TOTAL PENDAPATAN</td>
                            <td class="px-6 py-3 text-right font-bold text-green-600 text-lg">
                                Rp {{ number_format($pendapatans->sum('grand_total_with_ppn'), 0, ',', '.') }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $pendapatans->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Detail Peminjaman --}}
    <div id="detailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
        onclick="if(event.target===this) closeModal()">
        <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[85vh] overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-white sticky top-0">
                <h3 class="text-xl font-bold text-slate-800">
                    <i class="fas fa-receipt mr-2 text-green-600"></i>Detail Peminjaman
                </h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="detailContent" class="p-6 overflow-y-auto">
                <!-- Content will be loaded here -->
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-green-600"></i>
                    <p class="mt-2 text-slate-500">Memuat data...</p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-gray-50 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition">
                    Tutup
                </button>
                <button id="printModalBtn"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-print mr-1"></i> Cetak Invoice
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);

        new Chart(document.getElementById('pendapatanChart'), {
            type: 'line',
            data: {
                labels: chartData.map(d => d.bulan),
                datasets: [{
                    label: 'Pendapatan',
                    data: chartData.map(d => d.pendapatan),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.1)',
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
                            label: (ctx) => `Pendapatan: Rp ${ctx.raw.toLocaleString('id-ID')}`
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

        // Filter functions
        document.getElementById('bulanSelect').addEventListener('change', function() {
            window.location.href =
                `{{ route('keuangan.pendapatan') }}?bulan=${this.value}&tahun=${document.getElementById('tahunSelect').value}`;
        });
        document.getElementById('tahunSelect').addEventListener('change', function() {
            window.location.href =
                `{{ route('keuangan.pendapatan') }}?bulan=${document.getElementById('bulanSelect').value}&tahun=${this.value}`;
        });

        function exportData() {
            const bulan = document.getElementById('bulanSelect').value;
            const tahun = document.getElementById('tahunSelect').value;
            window.open(`/keuangan/pendapatan/export?bulan=${bulan}&tahun=${tahun}`, '_blank');
        }

        // Live search and filter
        document.getElementById('searchInput').addEventListener('keyup', function() {
            filterTable();
        });
        document.getElementById('statusFilter').addEventListener('change', function() {
            filterTable();
        });

        function filterTable() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const status = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#pendapatanTableBody tr');

            rows.forEach(row => {
                if (row.cells.length < 9) return;

                const invoice = row.cells[1]?.innerText.toLowerCase() || '';
                const customer = row.cells[2]?.innerText.toLowerCase() || '';
                const rowStatus = row.cells[8]?.innerText.toLowerCase() || '';

                let show = invoice.includes(search) || customer.includes(search);

                if (status !== 'all') {
                    let statusMatch = false;
                    if (status === 'lunas' && rowStatus.includes('lunas')) statusMatch = true;
                    if (status === 'dp' && rowStatus.includes('dp')) statusMatch = true;
                    if (status === 'belum_bayar' && rowStatus.includes('belum')) statusMatch = true;
                    show = show && statusMatch;
                }

                row.style.display = show ? '' : 'none';
            });
        }

        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            filterTable();
        }

        // Modal functions
        let currentPeminjamanId = null;

        function showDetail(id) {
            currentPeminjamanId = id;
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-2xl text-green-600"></i>
                    <p class="mt-2 text-slate-500">Memuat data...</p>
                </div>
            `;

            fetch(`/peminjaman/${id}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        displayDetail(result.data);
                    } else {
                        content.innerHTML = `
                            <div class="text-center py-8 text-red-500">
                                <i class="fas fa-exclamation-circle text-2xl"></i>
                                <p class="mt-2">Gagal memuat detail peminjaman</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-circle text-2xl"></i>
                            <p class="mt-2">Terjadi kesalahan saat memuat data</p>
                        </div>
                    `;
                });
        }

        function displayDetail(data) {
            const content = document.getElementById('detailContent');

            const itemsHtml = data.details ? data.details.map(item => `
                <tr class="border-b border-slate-100">
                    <td class="py-2">${item.nama_barang}</td>
                    <td class="py-2 text-center">${item.jumlah}</td>
                    <td class="py-2 text-right">Rp ${new Intl.NumberFormat('id-ID').format(item.harga_sewa)}</td>
                    <td class="py-2 text-right font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</td>
                </tr>
            `).join('') : '<tr><td colspan="4" class="text-center py-4">Tidak ada detail barang</td></tr>';

            content.innerHTML = `
                <div class="space-y-4">
                    {{-- Informasi Peminjaman --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-500"></i> Informasi Peminjaman
                        </h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div><span class="text-slate-500">No. Invoice:</span></div>
                            <div class="font-semibold">${data.invoice_number || '-'}</div>
                            <div><span class="text-slate-500">Tanggal Sewa:</span></div>
                            <div>${data.tanggal_sewa ? new Date(data.tanggal_sewa).toLocaleDateString('id-ID') : '-'}</div>
                            <div><span class="text-slate-500">Tanggal Kembali:</span></div>
                            <div>${data.tanggal_kembali ? new Date(data.tanggal_kembali).toLocaleDateString('id-ID') : '-'}</div>
                            <div><span class="text-slate-500">Tanggal Kembali Real:</span></div>
                            <div class="font-medium text-green-600">${data.tanggal_pengembalian_real ? new Date(data.tanggal_pengembalian_real).toLocaleDateString('id-ID') : '-'}</div>
                            <div><span class="text-slate-500">Status Pengembalian:</span></div>
                            <div><span class="px-2 py-1 bg-green-100 text-green-600 rounded-full text-xs">${data.status_pengembalian || '-'}</span></div>
                        </div>
                    </div>
                    
                    {{-- Informasi Pelanggan --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-user text-blue-500"></i> Informasi Pelanggan
                        </h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div><span class="text-slate-500">Nama:</span></div>
                            <div class="font-semibold">${data.nama_penyewa || '-'}</div>
                            <div><span class="text-slate-500">No. Telepon:</span></div>
                            <div>${data.no_telepon || '-'}</div>
                            <div><span class="text-slate-500">Email:</span></div>
                            <div>${data.email || '-'}</div>
                            <div><span class="text-slate-500">Alamat:</span></div>
                            <div>${data.alamat || '-'}</div>
                        </div>
                    </div>
                    
                    {{-- Detail Barang --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-boxes text-blue-500"></i> Detail Barang
                        </h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-200">
                                        <th class="text-left py-2">Nama Barang</th>
                                        <th class="text-center py-2">Jumlah</th>
                                        <th class="text-right py-2">Harga Sewa/Hari</th>
                                        <th class="text-right py-2">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${itemsHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    {{-- Ringkasan Keuangan --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-calculator text-blue-500"></i> Ringkasan Keuangan
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Total Harga:</span>
                                <span>Rp ${new Intl.NumberFormat('id-ID').format(data.total_harga || 0)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Diskon:</span>
                                <span class="text-red-500">- Rp ${new Intl.NumberFormat('id-ID').format(data.diskon || 0)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Subtotal:</span>
                                <span>Rp ${new Intl.NumberFormat('id-ID').format(data.grand_total || 0)}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">PPN 11%:</span>
                                <span>Rp ${new Intl.NumberFormat('id-ID').format(data.total_ppn || 0)}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-slate-200 font-bold">
                                <span>Grand Total:</span>
                                <span class="text-green-600 text-lg">Rp ${new Intl.NumberFormat('id-ID').format(data.grand_total_with_ppn || 0)}</span>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-slate-500">Status Pembayaran:</span>
                                <span class="px-2 py-1 rounded-full text-xs ${
                                    data.status_pembayaran === 'lunas' ? 'bg-green-100 text-green-600' :
                                    data.status_pembayaran === 'dp' ? 'bg-yellow-100 text-yellow-600' :
                                    'bg-red-100 text-red-600'
                                }">${data.status_pembayaran || 'belum_bayar'}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Set print button
            document.getElementById('printModalBtn').onclick = () => printInvoice(data.id);
        }

        function printInvoice(id) {
            window.open(`/peminjaman/${id}/invoice`, '_blank');
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentPeminjamanId = null;
        }
    </script>

    <style>
        @media print {

            button,
            select,
            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
