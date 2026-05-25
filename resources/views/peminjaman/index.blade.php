@extends('layouts.app')

@section('title', 'Peminjaman - Multidaya Inti Persada')
@section('page-title', 'Data Peminjaman')
@section('peminjaman-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">

        {{-- Header Section --}}
        <div class="mb-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        Peminjaman
                    </h1>
                    <p class="text-slate-500 text-[11px] mt-1 ml-1">
                        Kelola data transaksi barang secara real-time.
                    </p>
                </div>

                <button onclick="openTambahModal()"
                    class="group relative overflow-hidden bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2.5 px-5 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 shadow-[0_4px_12px_rgba(79,70,229,0.3)] hover:shadow-[0_8px_20px_rgba(79,70,229,0.4)] flex items-center gap-2 justify-center">
                    <div
                        class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700">
                    </div>
                    <i class="fas fa-plus-circle text-xs group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>Tambah Transaksi</span>
                </button>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="mb-6">
            <div class="inline-flex p-1 bg-slate-100 rounded-xl">
                <button onclick="switchTab('aktif')" id="tabAktifBtn"
                    class="flex items-center gap-2 py-2 px-6 rounded-lg font-bold text-sm transition-all duration-300 bg-white text-indigo-600 shadow-sm border border-slate-200/50">
                    <i class="fas fa-clock text-xs"></i>
                    <span>Sewa Aktif</span>
                    <span id="badgeAktif"
                        class="bg-indigo-100 text-indigo-600 text-[10px] px-1.5 py-0.5 rounded-md">0</span>
                </button>

                <button onclick="switchTab('riwayat')" id="tabRiwayatBtn"
                    class="flex items-center gap-2 py-2 px-6 rounded-lg font-bold text-sm transition-all duration-300 text-slate-500 hover:text-slate-700">
                    <i class="fas fa-history text-xs"></i>
                    <span>Riwayat Sewa</span>
                    <span id="badgeRiwayat"
                        class="bg-slate-200 text-slate-500 text-[10px] px-1.5 py-0.5 rounded-md">0</span>
                </button>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-8 transition-all hover:shadow-md">
            <div class="flex flex-col md:flex-row items-end gap-5">
                <div class="flex-1 w-full">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pencarian
                        Data</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i
                                class="fas fa-search text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <input type="text" id="searchInput" placeholder="Cari invoice, nama penyewa, atau telepon..."
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all placeholder:text-slate-400">
                    </div>
                </div>

                <div class="w-full md:w-64">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Urutan
                        Tampilan</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i
                                class="fas fa-sort-amount-down text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                        <select id="filterSort"
                            class="w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                            <option value="default">Terbaru (Default)</option>
                            <option value="name_asc">Nama A-Z</option>
                            <option value="name_desc">Nama Z-A</option>
                            <option value="date_asc">Tanggal Terlama</option>
                            <option value="date_desc">Tanggal Terbaru</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i
                                class="fas fa-chevron-down text-[10px] text-slate-400 group-focus-within:text-indigo-500"></i>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-48">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Tipe
                        Pelanggan</label>
                    <select id="filterPelanggan"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                        <option value="all">Semua Pelanggan</option>
                        <option value="new">Pelanggan Baru (≤ 1 transaksi)</option>
                        <option value="old">Pelanggan Lama (> 1 transaksi)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Table Peminjaman --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all hover:shadow-md">
            <div class="overflow-x-auto text-slate-700">
                <table class="min-w-full w-full text-sm border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th
                                class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                ID/Invoice</th>
                            <th
                                class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                Penyewa</th>
                            <th
                                class="px-6 py-4 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                Barang</th>
                            <th
                                class="px-10 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                Periode Sewa</th>
                            <th
                                class="px-6 py-4 text-right text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                Total Tagihan</th>
                            <th
                                class="px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                Status</th>
                            <th
                                class="px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 w-300">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="peminjamanTableBody">
                        <tr>
                            <td colspan="7" class="px-6 py-24 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <div class="relative flex items-center justify-center w-12 h-12 mb-4">
                                        <div class="absolute w-full h-full border-4 border-indigo-100 rounded-full"></div>
                                        <div
                                            class="absolute w-full h-full border-4 border-indigo-600 rounded-full border-t-transparent animate-spin">
                                        </div>
                                    </div>
                                    <h3 class="text-slate-800 font-bold">Sedang Mengambil Data</h3>
                                    <p class="text-slate-400 text-xs mt-1">Harap tunggu sebentar...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="paginationContainer" class="px-6 py-5 bg-slate-50/30 border-t border-slate-100"></div>
        </div>
    </div>

    {{-- ==================== MODAL TAMBAH PEMINJAMAN ==================== --}}
    <div id="modalTambah"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeTambahModal()">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[92vh] flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                            <i class="fas fa-plus-circle"></i>
                        </span>
                        Tambah Peminjaman Baru
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mt-1">Input data penyewaan
                        barang</p>
                </div>
                <button onclick="closeTambahModal()"
                    class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="overflow-y-auto custom-scrollbar">
                <div class="px-8 pt-6">
                    <button onclick="openCekPelangganModal()" type="button"
                        class="group w-full border-2 border-dashed border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-300 text-slate-500 hover:text-indigo-600 py-3.5 rounded-2xl transition-all flex items-center justify-center gap-3">
                        <div
                            class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas fa-search text-xs"></i>
                        </div>
                        <span class="text-sm font-bold">Cari Data Pelanggan Lama</span>
                    </button>
                    <div class="relative flex py-4 items-center">
                        <div class="grow border-t border-slate-100"></div>
                        <span class="shrink mx-4 text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Atau
                            Input Manual</span>
                        <div class="grow border-t border-slate-100"></div>
                    </div>
                </div>

                <form id="formPeminjaman" class="px-8 pb-8" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="pelanggan_id" name="pelanggan_id">

                    {{-- Info Pelanggan Banner --}}
                    <div id="newCustomerInfo" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-xl">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-star text-yellow-500"></i>
                            <span class="text-sm font-semibold text-yellow-700">Pelanggan Baru!</span>
                            <span class="text-xs text-yellow-600">Data akan disimpan sebagai pelanggan baru</span>
                        </div>
                    </div>
                    <div id="oldCustomerInfo" class="hidden mb-4 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-blue-500"></i>
                            <span class="text-sm font-semibold text-blue-700">Pelanggan Lama</span>
                            <span class="text-xs text-blue-600" id="oldCustomerDetail"></span>
                        </div>
                    </div>

                    {{-- Section 1: Informasi Penyewa --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 text-indigo-600">
                            <i class="fas fa-user-circle"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Informasi Penyewa</span>
                            <div class="h-px flex-1 bg-slate-100"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Kode Peminjaman</label>
                                <input type="text" id="kode_peminjaman"
                                    class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-mono font-bold text-slate-500 cursor-not-allowed"
                                    readonly placeholder="Auto generate">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Tipe Pelanggan</label>
                                <select name="tipe_pelanggan" id="tipe_pelanggan"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold">
                                    <option value="perorangan">Perorangan</option>
                                    <option value="perusahaan">Perusahaan</option>
                                </select>
                            </div>
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Nama Penyewa <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="nama_penyewa" id="nama_penyewa" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">No Telepon <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="no_telepon" id="no_telepon" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Alamat Lengkap</label>
                                <textarea name="alamat" id="alamat" rows="2"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Waktu & Acara --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 text-indigo-600">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Detail Waktu & Acara</span>
                            <div class="h-[1px] flex-1 bg-slate-100"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div
                                class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100/50 grid grid-cols-2 gap-3">
                                <div class="col-span-2 text-[10px] font-black text-indigo-400 uppercase mb-1">Jadwal
                                    Pengambilan</div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Tanggal</label>
                                    <input type="date" name="tanggal_sewa" id="tanggal_sewa" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Jam</label>
                                    <input type="time" name="waktu_sewa" id="waktu_sewa" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold focus:border-indigo-500">
                                </div>
                            </div>
                            <div class="bg-rose-50/50 p-4 rounded-2xl border border-rose-100/50 grid grid-cols-2 gap-3">
                                <div class="col-span-2 text-[10px] font-black text-rose-400 uppercase mb-1">Jadwal
                                    Pengembalian</div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Tanggal</label>
                                    <input type="date" name="tanggal_kembali" id="tanggal_kembali" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold focus:border-rose-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase">Jam</label>
                                    <input type="time" name="waktu_kembali" id="waktu_kembali" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold focus:border-rose-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Nama Acara</label>
                                <input type="text" name="nama_acara" id="nama_acara"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold"
                                    placeholder="Contoh: Wedding, Seminar...">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Lokasi Acara</label>
                                <input type="text" name="lokasi_acara" id="lokasi_acara"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold"
                                    placeholder="Nama Gedung / Tempat">
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Daftar Barang Sewa --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 text-indigo-600">
                            <i class="fas fa-box-open"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Daftar Barang Sewa</span>
                            <div class="h-[1px] flex-1 bg-slate-100"></div>
                        </div>

                        <div id="barangContainer"
                            class="space-y-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                            <div class="flex gap-3 items-center barang-row animate-in slide-in-from-left-2 duration-200">
                                <div class="flex-1 relative group">
                                    <select name="barang[0][id]"
                                        class="barang-select w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                        <option value="">Pilih Barang...</option>
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                        <i class="fas fa-chevron-down text-[10px]"></i>
                                    </div>
                                </div>
                                <div class="w-28 relative">
                                    <input type="number" name="barang[0][jumlah]" placeholder="Jml"
                                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all"
                                        value="1" onchange="hitungOtomatis()">
                                    <span
                                        class="absolute -top-2 left-3 px-1 bg-white text-[9px] font-black text-slate-400 uppercase">Qty</span>
                                </div>
                                <button type="button" onclick="removeBarang(this)"
                                    class="w-10 h-10 flex items-center justify-center text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <button type="button" onclick="addBarang()"
                            class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl text-xs font-black transition-all active:scale-95 shadow-sm shadow-indigo-100">
                            <i class="fas fa-plus"></i> TAMBAH BARANG LAIN
                        </button>
                    </div>

                    {{-- Section 4: Upload Bukti Pembayaran --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 text-indigo-600">
                            <i class="fas fa-credit-card"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Bukti Pembayaran</span>
                            <div class="h-[1px] flex-1 bg-slate-100"></div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                            <div class="relative">
                                <div id="dropzoneBuktiPembayaran"
                                    class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition-all duration-300">
                                    <div
                                        class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-600">Klik untuk Upload Bukti Transfer</p>
                                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG (Maks 2MB)</p>
                                    <input type="file" name="bukti_pembayaran" id="buktiPembayaranInput"
                                        accept="image/*" class="hidden">
                                </div>

                                <div id="previewBuktiPembayaran"
                                    class="hidden mt-4 animate-in fade-in slide-in-from-top-2">
                                    <div class="relative inline-block w-full text-center">
                                        <img id="previewImgBukti"
                                            class="w-48 h-48 object-cover rounded-2xl border-4 border-white shadow-md mx-auto">
                                        <button type="button" onclick="removeBuktiPembayaran()"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                        <div
                                            class="absolute bottom-2 left-1/2 -translate-x-1/2 px-3 py-1 bg-indigo-600 text-white text-[10px] font-black rounded-lg uppercase">
                                            Preview</div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-3 italic">*Upload bukti transfer/DP untuk memudahkan
                                verifikasi pembayaran</p>
                        </div>
                    </div>

                    {{-- Section 5: Pembayaran & Keterangan --}}
                    <div class="mb-4 bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-6 text-white shadow-xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Potongan Diskon
                                    (Rp)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-sm">Rp</span>
                                    <input type="number" name="diskon" id="diskon" value="0"
                                        class="w-full pl-12 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-bold text-white focus:border-indigo-500 focus:ring-0"
                                        oninput="hitungOtomatis()">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Status Pembayaran</label>
                                <select name="status_pembayaran" id="status_pembayaran"
                                    class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-bold text-white focus:border-indigo-500 focus:ring-0">
                                    <option value="belum_bayar">Belum Bayar</option>
                                    <option value="dp">Down Payment (DP)</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Keterangan Tambahan</label>
                                <textarea name="keterangan" rows="2"
                                    class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-medium text-white focus:border-indigo-500 focus:ring-0"
                                    placeholder="Catatan khusus peminjaman..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Section 6: PPN & Jatuh Tempo --}}
                    <div class="mb-4 bg-white rounded-3xl p-6 shadow-xl border border-gray-200">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fas fa-calculator text-lg text-gray-700"></i>
                            <span class="text-xs font-black uppercase tracking-widest text-gray-700">Perhitungan PPN &
                                Jatuh Tempo</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">PPN (%)</label>
                                <input type="number" id="ppn_persen" value="11" readonly
                                    class="w-full px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-xl text-sm font-bold text-gray-800 cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Total PPN
                                    (Rp)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-sm">Rp</span>
                                    <input type="text" id="tambah_total_ppn_display" value="0" readonly
                                        class="w-full pl-12 pr-4 py-2.5 bg-gray-100 border border-gray-300 rounded-xl text-sm font-bold text-gray-800 cursor-not-allowed">
                                    <input type="hidden" id="total_ppn" name="total_ppn" value="0">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase">Grand Total +
                                    PPN</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-sm">Rp</span>
                                    <input type="text" id="tambah_grand_total_with_ppn_display" value="0"
                                        readonly
                                        class="w-full pl-12 pr-4 py-2.5 bg-gray-100 border border-gray-300 rounded-xl text-sm font-bold text-gray-800 cursor-not-allowed">
                                    <input type="hidden" id="grand_total_with_ppn" name="grand_total_with_ppn"
                                        value="0">
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-gray-600 uppercase tracking-wider">Jatuh Tempo
                                    Pembayaran</span>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                    <input type="date" id="jatuh_tempo_pembayaran" name="jatuh_tempo_pembayaran"
                                        readonly
                                        class="bg-gray-100 border border-gray-300 rounded-lg px-3 py-1.5 text-sm font-semibold text-gray-700 cursor-not-allowed">
                                    <span class="text-[10px] text-gray-400">(H+7 dari tanggal sewa)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <button type="submit"
                            class="flex-[2] bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigo-200 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i> SIMPAN TRANSAKSI
                        </button>
                        <button type="button" onclick="closeTambahModal()"
                            class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-4 rounded-2xl transition-all flex items-center justify-center">
                            BATAL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL EDIT PEMINJAMAN ==================== --}}
    <div id="modalEdit"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeEditModal()">
        <div
            class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[92vh] flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                            <i class="fas fa-edit"></i>
                        </span>
                        Edit Data Peminjaman
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mt-1">Perbarui informasi
                        transaksi pelanggan</p>
                </div>
                <button onclick="closeEditModal()"
                    class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="overflow-y-auto custom-scrollbar">
                <form id="formEditPeminjaman" class="px-8 py-8" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">

                    {{-- Section Identitas --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 text-amber-600">
                            <i class="fas fa-id-card"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Identitas & Penyewa</span>
                            <div class="h-px flex-1 bg-slate-100"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Nomor Invoice (Read
                                    Only)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-amber-600">
                                        <i class="fas fa-hashtag text-xs"></i>
                                    </span>
                                    <input type="text" id="edit_invoice_number"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-mono font-bold text-slate-600 cursor-not-allowed"
                                        readonly>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Nama Penyewa <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="nama_penyewa" id="edit_nama_penyewa" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-semibold outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">No Telepon <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="no_telepon" id="edit_no_telepon" required
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-semibold outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Nama Acara</label>
                                <input type="text" name="nama_acara" id="edit_nama_acara"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-semibold outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 mb-2 ml-1">Lokasi Acara</label>
                                <input type="text" name="lokasi_acara" id="edit_lokasi_acara"
                                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-semibold outline-none">
                            </div>
                        </div>
                    </div>

                    {{-- Section Upload Bukti Pembayaran di Edit --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 text-amber-600">
                            <i class="fas fa-credit-card"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Bukti Pembayaran</span>
                            <div class="h-px flex-1 bg-slate-100"></div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                            <div class="relative">
                                <div id="dropzoneEditBuktiPembayaran"
                                    class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center cursor-pointer hover:border-amber-400 hover:bg-amber-50/30 transition-all duration-300">
                                    <div
                                        class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-600">Upload Bukti Pembayaran Baru</p>
                                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG (Maks 2MB)</p>
                                    <input type="file" name="bukti_pembayaran" id="editBuktiPembayaranInput"
                                        accept="image/*" class="hidden">
                                </div>

                                <div id="previewEditBuktiPembayaran"
                                    class="hidden mt-4 animate-in fade-in slide-in-from-top-2">
                                    <div class="relative inline-block w-full text-center">
                                        <img id="previewImgEditBukti"
                                            class="w-48 h-48 object-cover rounded-2xl border-4 border-white shadow-md mx-auto">
                                        <button type="button" onclick="removeEditBuktiPembayaran()"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                        <div
                                            class="absolute bottom-2 left-1/2 -translate-x-1/2 px-3 py-1 bg-amber-600 text-white text-[10px] font-black rounded-lg uppercase">
                                            Preview Baru</div>
                                    </div>
                                </div>

                                <div id="existingBuktiInfo" class="mt-3 p-3 bg-blue-50 rounded-xl border border-blue-100">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-file-image text-blue-500"></i>
                                        <span class="text-xs font-semibold text-blue-700">Bukti saat ini:</span>
                                        <a id="existingBuktiLink" href="#" target="_blank"
                                            class="text-xs text-blue-600 underline">Lihat Bukti</a>
                                    </div>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-3 italic">*Upload ulang untuk mengganti bukti
                                pembayaran yang ada</p>
                        </div>
                    </div>

                    {{-- Section Jadwal --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 text-amber-600">
                            <i class="fas fa-clock"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Penjadwalan Ulang</span>
                            <div class="h-px flex-1 bg-slate-100"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div
                                class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100/50 grid grid-cols-2 gap-3">
                                <div
                                    class="col-span-2 text-[10px] font-black text-indigo-400 uppercase mb-1 flex items-center gap-1">
                                    <i class="fas fa-sign-out-alt"></i> Pengambilan
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-tighter">Tanggal</label>
                                    <input type="date" name="tanggal_sewa" id="edit_tanggal_sewa" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold focus:border-indigo-500 outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-tighter">Jam</label>
                                    <input type="time" name="waktu_sewa" id="edit_waktu_sewa" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold focus:border-indigo-500 outline-none">
                                </div>
                            </div>
                            <div class="bg-rose-50/50 p-4 rounded-2xl border border-rose-100/50 grid grid-cols-2 gap-3">
                                <div
                                    class="col-span-2 text-[10px] font-black text-rose-400 uppercase mb-1 flex items-center gap-1">
                                    <i class="fas fa-sign-in-alt"></i> Pengembalian
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-tighter">Tanggal</label>
                                    <input type="date" name="tanggal_kembali" id="edit_tanggal_kembali" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold focus:border-rose-500 outline-none">
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-tighter">Jam</label>
                                    <input type="time" name="waktu_kembali" id="edit_waktu_kembali" required
                                        class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold focus:border-rose-500 outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Detail Barang --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-5 text-amber-600">
                            <i class="fas fa-boxes"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Detail Barang Sewa</span>
                            <div class="h-px flex-1 bg-slate-100"></div>
                        </div>

                        <div id="editBarangContainer"
                            class="space-y-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-100 min-h-[50px]"></div>
                        <button type="button" onclick="addEditBarang()"
                            class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-xl text-xs font-black transition-all active:scale-95 shadow-sm shadow-amber-100">
                            <i class="fas fa-plus"></i> TAMBAH BARANG LAIN
                        </button>
                    </div>

                    {{-- Section Pembayaran & Diskon --}}
                    <div
                        class="mb-4 bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-6 text-white shadow-xl shadow-slate-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-400 mb-2 ml-1 uppercase tracking-widest">Penyesuaian
                                    Diskon</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-sm">Rp</span>
                                    <input type="number" name="diskon" id="edit_diskon" value="0"
                                        class="w-full pl-12 pr-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-bold text-white focus:border-amber-500 focus:ring-0 outline-none"
                                        oninput="hitungOtomatisEdit()">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-400 mb-2 ml-1 uppercase tracking-widest">Update
                                    Pembayaran</label>
                                <select name="status_pembayaran" id="edit_status_pembayaran"
                                    class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-bold text-white focus:border-amber-500 focus:ring-0 outline-none cursor-pointer">
                                    <option value="belum_bayar">Belum Bayar</option>
                                    <option value="dp">Down Payment (DP)</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-xs font-bold text-slate-400 mb-2 ml-1 uppercase tracking-widest">Catatan
                                    Internal / Keterangan</label>
                                <textarea name="keterangan" id="edit_keterangan" rows="2"
                                    class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-sm font-medium text-white focus:border-amber-500 focus:ring-0 outline-none"
                                    placeholder="Masukkan catatan tambahan..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Section PPN & Jatuh Tempo di Edit --}}
                    <div class="mb-4 bg-gradient-to-r from-emerald-800 to-teal-800 rounded-3xl p-6 text-white shadow-xl">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fas fa-calculator text-lg"></i>
                            <span class="text-xs font-black uppercase tracking-widest">Perhitungan PPN & Jatuh Tempo</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold text-emerald-200 mb-1 uppercase">PPN (%)</label>
                                <input type="text" id="edit_ppn_persen" value="11%" readonly
                                    class="w-full px-4 py-2.5 bg-emerald-900/50 border border-emerald-700 rounded-xl text-sm font-bold text-white cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-emerald-200 mb-1 uppercase">Total PPN
                                    (Rp)</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-300 font-bold text-sm">Rp</span>
                                    <input type="text" id="edit_total_ppn_display" readonly
                                        class="w-full pl-12 pr-4 py-2.5 bg-emerald-900/50 border border-emerald-700 rounded-xl text-sm font-bold text-white cursor-not-allowed">
                                    <input type="hidden" id="edit_total_ppn" name="total_ppn">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-emerald-200 mb-1 uppercase">Grand Total +
                                    PPN</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-300 font-bold text-sm">Rp</span>
                                    <input type="text" id="edit_grand_total_with_ppn_display" value="0" readonly
                                        class="w-full pl-12 pr-4 py-2.5 bg-emerald-900/50 border border-emerald-700 rounded-xl text-sm font-bold text-white cursor-not-allowed">
                                    <input type="hidden" id="edit_grand_total_with_ppn" name="grand_total_with_ppn"
                                        value="0">
                                </div>
                                <p id="edit_infoJumlahHari" class="text-[10px] font-bold text-emerald-200 mt-1"></p>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-emerald-700/50">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-bold text-emerald-200 uppercase tracking-wider">Jatuh Tempo
                                    Pembayaran</span>
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-calendar-alt text-emerald-300"></i>
                                    <input type="date" id="edit_jatuh_tempo_pembayaran" name="jatuh_tempo_pembayaran"
                                        readonly
                                        class="bg-emerald-900/50 border border-emerald-700 rounded-lg px-3 py-1.5 text-sm font-semibold text-white cursor-not-allowed">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 mt-8">
                        <button type="submit"
                            class="flex-[2] bg-amber-500 hover:bg-amber-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-amber-200 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2 uppercase tracking-wider">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <button type="button" onclick="closeEditModal()"
                            class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-4 rounded-2xl transition-all flex items-center justify-center uppercase tracking-wider text-xs">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL CEK PELANGGAN ==================== --}}
    <div id="modalCekPelanggan"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeCekPelangganModal()">
        <div
            class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full max-h-[85vh] overflow-hidden flex flex-col animate-in fade-in zoom-in duration-200">
            <div class="sticky top-0 bg-white border-b border-slate-100 px-8 py-6 flex justify-between items-center z-10">
                <div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                        <span class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-search text-lg"></i>
                        </span>
                        Cek Database Pelanggan
                    </h3>
                    <p class="text-xs text-slate-400 font-medium mt-1 uppercase tracking-widest ml-13">Verifikasi data
                        penyewa lama</p>
                </div>
                <button onclick="closeCekPelangganModal()"
                    class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar">
                <div class="mb-8">
                    <label class="block text-xs font-black text-slate-500 mb-3 ml-1 uppercase tracking-wider">Parameter
                        Pencarian</label>
                    <div class="relative group">
                        <div
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <i class="fas fa-search"></i>
                        </div>
                        <input type="text" id="searchPelanggan" placeholder="Masukkan Nama atau No. Telepon..."
                            class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-semibold focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none"
                            autocomplete="off">
                        <div id="autocompleteDropdown"
                            class="hidden absolute z-20 w-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl max-h-64 overflow-y-auto custom-scrollbar divide-y divide-slate-50">
                        </div>
                    </div>
                    <p class="mt-2 text-[10px] text-slate-400 ml-2 font-medium italic">*Sistem akan mencari kecocokan data
                        secara otomatis saat Anda mengetik.</p>
                </div>

                {{-- STATE: Pelanggan Ditemukan (Lama) --}}
                <div id="hasilCekPelanggan" class="hidden animate-in slide-in-from-bottom-4 duration-300">
                    <div
                        class="bg-gradient-to-br from-white to-slate-50 border border-slate-200 rounded-[1.5rem] p-6 shadow-sm">
                        <div class="mb-4 flex items-center gap-2 px-4 py-2.5 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="w-7 h-7 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-check text-white text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-blue-700 uppercase tracking-wider">Pelanggan Ditemukan
                                </p>
                                <p class="text-[10px] text-blue-500">Data pelanggan sudah tersimpan di sistem</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-start mb-6">
                            <div class="flex gap-4 items-center">
                                <div
                                    class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-indigo-200">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-800 text-xl leading-none mb-1" id="hasilNama"></h4>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm text-slate-500 font-medium flex items-center gap-1">
                                            <i class="fas fa-phone text-green-500"></i>
                                            <span id="hasilTelepon"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <span id="hasilStatus"
                                class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-tighter"></span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white border border-slate-100 p-4 rounded-2xl">
                                <span class="block text-[10px] font-black text-slate-400 uppercase mb-1">Total
                                    Transaksi</span>
                                <strong class="text-xl text-slate-800" id="hasilTotalTransaksi">0</strong>
                                <span class="text-[10px] text-slate-400 font-bold ml-1">KALI</span>
                            </div>
                            <div class="bg-white border border-slate-100 p-4 rounded-2xl">
                                <span class="block text-[10px] font-black text-slate-400 uppercase mb-1">Total Nilai</span>
                                <strong class="text-xl text-indigo-600" id="hasilTotalNilai">Rp 0</strong>
                            </div>
                        </div>

                        <div id="riwayatContainer" class="space-y-3">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="h-[1px] flex-1 bg-slate-100"></div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">
                                    Riwayat Terakhir</p>
                                <div class="h-[1px] flex-1 bg-slate-100"></div>
                            </div>
                            <div id="riwayatList" class="space-y-2 max-h-44 overflow-y-auto pr-2 custom-scrollbar"></div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button onclick="useExistingCustomer()"
                                class="group flex-[2] bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-indigo-100 flex items-center justify-center gap-2 active:scale-95">
                                <i class="fas fa-check-circle group-hover:scale-110 transition-transform"></i>
                                GUNAKAN DATA PELANGGAN
                            </button>
                            <button onclick="openNewCustomerForm()"
                                class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-black py-4 rounded-2xl transition-all flex items-center justify-center gap-2 text-xs active:scale-95">
                                <i class="fas fa-plus text-[10px]"></i>
                                BARU
                            </button>
                        </div>
                    </div>
                </div>

                {{-- STATE: Pelanggan Tidak Ditemukan --}}
                <div id="pelangganNotFound" class="hidden animate-in fade-in duration-500">
                    <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-yellow-50 border border-yellow-200 rounded-2xl">
                        <div
                            class="w-9 h-9 bg-yellow-400 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="fas fa-user-plus text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-yellow-800">Pelanggan Tidak Ditemukan</p>
                            <p class="text-[10px] text-yellow-600">Silakan daftarkan sebagai pelanggan baru</p>
                        </div>
                        <span
                            class="ml-auto px-2.5 py-1 bg-yellow-200 text-yellow-800 text-[10px] font-black rounded-lg uppercase tracking-wider">BARU</span>
                    </div>

                    <div class="py-8 text-center">
                        <div class="relative inline-block mb-5">
                            <div class="w-20 h-20 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto">
                                <i class="fas fa-user-slash text-4xl text-slate-200"></i>
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-8 h-8 bg-white border-4 border-white rounded-full flex items-center justify-center shadow-sm">
                                <i class="fas fa-question text-[10px] text-slate-400"></i>
                            </div>
                        </div>
                        <h4 class="text-lg font-black text-slate-800 mb-1 tracking-tight">Tidak Ada di Database</h4>
                        <p class="text-slate-400 text-sm mb-6 max-w-[280px] mx-auto font-medium leading-relaxed">Silakan
                            periksa kembali atau daftarkan sebagai pelanggan baru.</p>
                        <div id="suggestionsContainer" class="mb-6 flex flex-wrap justify-center gap-2"></div>
                        <button onclick="openNewCustomerForm()"
                            class="inline-flex items-center gap-3 bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-yellow-100 active:scale-95">
                            <i class="fas fa-user-plus"></i> DAFTARKAN PELANGGAN BARU
                        </button>
                    </div>
                </div>

                {{-- STATE: Belum ada pencarian (default) --}}
                <div id="pelangganDefault" class="py-10 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-2xl text-slate-300"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-400">Ketik nama atau nomor telepon untuk mencari</p>
                    <p class="text-[10px] text-slate-300 mt-1">Sistem akan otomatis mencari saat Anda mengetik</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL DETAIL PEMINJAMAN ==================== --}}
    <div id="modalDetail"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closeDetailModal()">
        <div
            class="bg-white rounded-4xl shadow-2xl max-w-3xl w-full max-h-[85vh] overflow-hidden flex flex-col animate-in fade-in zoom-in duration-200">
            <div
                class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-slate-100 px-8 py-5 flex justify-between items-center z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-invoice text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Detail Peminjaman</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Informasi lengkap
                            transaksi</p>
                    </div>
                </div>
                <button onclick="closeDetailModal()"
                    class="w-10 h-10 flex items-center justify-center rounded-2xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all active:scale-90">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="detailContent" class="p-8 overflow-y-auto custom-scrollbar bg-slate-50/30"></div>
            <div class="p-6 border-t border-slate-100 bg-white flex justify-end gap-3">
                <button onclick="closeDetailModal()"
                    class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-sm transition-all active:scale-95">
                    Tutup Window
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL PENGEMBALIAN ==================== --}}
    <div id="modalPengembalian"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
        onclick="if(event.target===this) closePengembalianModal()">
        <div
            class="bg-white rounded-4xl shadow-2xl max-w-lg w-full max-h-[92vh] flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                            <i class="fas fa-undo-alt"></i>
                        </span>
                        Proses Pengembalian
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider mt-1">Verifikasi kondisi
                        barang kembali</p>
                </div>
                <button onclick="closePengembalianModal()"
                    class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="overflow-y-auto custom-scrollbar">
                <form id="formPengembalian" class="p-8" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="pengembalianId" name="id">

                    <div class="mb-6">
                        <label class="block text-xs font-black text-slate-500 mb-3 ml-1 uppercase tracking-widest">Bukti
                            Foto Barang</label>
                        <div class="relative group">
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center cursor-pointer group-hover:border-emerald-400 group-hover:bg-emerald-50/30 transition-all duration-300"
                                id="dropzonePengembalian">
                                <div
                                    class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-camera text-xl text-slate-400 group-hover:text-emerald-500"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-600">Klik atau Drag Foto</p>
                                <p class="text-[10px] text-slate-400 mt-1 uppercase font-semibold">Format: JPG, PNG (Maks
                                    2MB)</p>
                                <input type="file" name="foto_pengembalian" id="fotoPengembalian" accept="image/*"
                                    class="hidden">
                            </div>
                        </div>
                        <div id="previewPengembalian" class="hidden mt-4 animate-in fade-in slide-in-from-top-2">
                            <div class="relative inline-block w-full">
                                <img id="previewImgPengembalian"
                                    class="w-full h-40 object-cover rounded-2xl border-4 border-white shadow-md">
                                <div
                                    class="absolute top-2 right-2 px-3 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-lg uppercase">
                                    Preview</div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-xs font-black text-slate-500 mb-2 ml-1 uppercase tracking-widest">Kondisi
                                Fisik Barang</label>
                            <div class="relative">
                                <select name="kondisi_barang" id="kondisiBarang"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all appearance-none cursor-pointer"
                                    onchange="toggleKerusakan()">
                                    <option value="baik">Baik, Tidak ada masalah</option>
                                    <option value="kurang_baik">Kurang Baik, Ada sedikit masalah</option>
                                    <option value="rusak">Rusak, Perlu perbaikan</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div id="kerusakanSection" class="hidden animate-in slide-in-from-top-2 duration-300">
                            <label
                                class="block text-xs font-black text-rose-500 mb-2 ml-1 uppercase tracking-widest">Detail
                                Kerusakan</label>
                            <textarea name="kerusakan" id="kerusakan" rows="2"
                                class="w-full px-4 py-3 bg-rose-50/30 border border-rose-100 rounded-xl text-sm font-medium focus:ring-4 focus:ring-rose-500/10 focus:border-rose-400 transition-all outline-none"
                                placeholder="Jelaskan bagian mana yang rusak..."></textarea>
                        </div>

                        <div id="dendaSection" class="hidden animate-in slide-in-from-top-2 duration-300">
                            <label class="block text-xs font-black text-rose-500 mb-2 ml-1 uppercase tracking-widest">Biaya
                                Denda / Perbaikan</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-rose-500 font-bold text-sm">Rp</span>
                                <input type="number" name="biaya_kerusakan" id="biayaKerusakan"
                                    class="w-full pl-12 pr-4 py-3 bg-rose-50/30 border border-rose-100 rounded-xl text-sm font-black text-rose-600 focus:ring-0 outline-none"
                                    placeholder="0">
                            </div>
                            <div class="flex items-center gap-2 mt-2 ml-1">
                                <i class="fas fa-info-circle text-[10px] text-slate-400"></i>
                                <p class="text-[10px] text-slate-500 font-bold italic">Denda keterlambatan sistem: Rp
                                    50.000/hari</p>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black text-slate-500 mb-2 ml-1 uppercase tracking-widest">Catatan
                                Tambahan</label>
                            <textarea name="catatan_pengembalian" id="catatanPengembalian" rows="2"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all outline-none"
                                placeholder="Catatan opsional..."></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 mt-10">
                        <button type="submit"
                            class="flex-2 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-200 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2 uppercase tracking-wider">
                            Konfirmasi
                        </button>
                        <button type="button" onclick="closePengembalianModal()"
                            class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-4 rounded-2xl transition-all flex items-center justify-center uppercase tracking-wider text-xs">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL DELETE CONFIRMATION ==================== --}}
    <div id="modalDeleteConfirm" class="fixed inset-0 z-100 hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()">
        </div>
        <div
            class="relative bg-white w-full max-w-sm rounded-4xl shadow-2xl overflow-hidden transform transition-all animate-in fade-in zoom-in duration-200">
            <div class="p-8 text-center">
                <div
                    class="w-20 h-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6 transform rotate-3">
                    <i class="fas fa-trash-alt text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-2">Hapus Data?</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Tindakan ini akan menghapus data peminjaman secara
                    permanen. Data yang sudah dihapus tidak dapat dipulihkan kembali.</p>
            </div>
            <div class="flex p-6 pt-0 gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-3 text-xs font-bold text-slate-400 bg-slate-50 hover:bg-slate-100 rounded-2xl transition-all uppercase tracking-widest">Batal</button>
                <button id="confirmDeleteBtn"
                    class="flex-[1.5] px-4 py-3 text-xs font-black text-white bg-rose-500 hover:bg-rose-600 rounded-2xl shadow-lg shadow-rose-200 transition-all transform active:scale-95 uppercase tracking-widest">Ya,
                    Hapus Data</button>
            </div>
        </div>
    </div>

    {{-- ==================== TOAST NOTIFICATION ==================== --}}
    <div id="toast" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-3 hidden z-50">
        <div class="flex items-center gap-2">
            <i id="toastIcon" class="text-lg"></i>
            <p id="toastMessage" class="text-sm"></p>
        </div>
    </div>

    <style>
        .animate-in {
            animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        #autocompleteDropdown div {
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 600;
            font-size: 14px;
            color: #475569;
        }

        #autocompleteDropdown div:hover {
            background-color: #f8fafc;
            color: #4f46e5;
            padding-left: 25px;
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

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        #modalTambah,
        #modalEdit,
        #modalDetail,
        #modalPengembalian,
        #modalCekPelanggan {
            display: none;
        }

        #modalTambah.flex,
        #modalEdit.flex,
        #modalDetail.flex,
        #modalPengembalian.flex,
        #modalCekPelanggan.flex {
            display: flex !important;
        }

        #modalTambah.hidden,
        #modalEdit.hidden,
        #modalDetail.hidden,
        #modalPengembalian.hidden,
        #modalCekPelanggan.hidden {
            display: none !important;
        }
    </style>

    <script>
        // ==================== VARIABLES ====================
        let currentTab = 'aktif';
        let currentPage = 1;
        let currentFilters = {
            sort: 'default',
            search: '',
            pelanggan: 'all'
        };
        let barangList = [];
        let isLoading = false;
        let searchTimeout;
        let selectedCustomer = null;
        let currentEditId = null;
        let currentDeleteId = null;

        // ==================== HELPER FUNCTIONS ====================
        function formatRupiah(amount) {
            if (amount === undefined || amount === null) amount = 0;
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        function formatDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('id-ID');
        }

        function formatShortDate(date) {
            if (!date) return '-';
            return new Date(date).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: '2-digit'
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function getStatusBadge(status) {
            const config = {
                aktif: {
                    container: 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    dot: 'bg-emerald-500',
                    ping: 'bg-emerald-400',
                    icon: '',
                    text: 'Disewa'
                },
                selesai: {
                    container: 'bg-slate-100 text-slate-600 border-slate-200',
                    dot: 'hidden',
                    ping: 'hidden',
                    icon: '<i class="fas fa-check-circle text-[10px]"></i>',
                    text: 'Selesai'
                },
                terlambat: {
                    container: 'bg-rose-50 text-rose-600 border-rose-100 animate-pulse',
                    dot: 'bg-rose-500',
                    ping: 'bg-rose-400',
                    icon: '<i class="fas fa-exclamation-triangle text-[10px]"></i>',
                    text: 'Terlambat'
                }
            };
            const s = config[status] || config.aktif;
            return `<span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border text-[10px] font-black uppercase tracking-widest transition-all duration-300 hover:shadow-sm ${s.container}">
                <span class="relative flex h-2 w-2 ${status === 'selesai' ? 'hidden' : ''}">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full ${s.ping} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 ${s.dot}"></span>
                </span>
                ${s.icon}
                <span class="leading-none">${s.text}</span>
            </span>`;
        }

        function showToast(msg, type) {
            const toast = document.getElementById('toast');
            if (!toast) {
                alert(msg);
                return;
            }
            const toastIcon = document.getElementById('toastIcon');
            if (type === 'success') {
                toastIcon.className = 'fas fa-check-circle text-green-500 text-lg';
            } else if (type === 'error') {
                toastIcon.className = 'fas fa-exclamation-circle text-red-500 text-lg';
            } else {
                toastIcon.className = 'fas fa-info-circle text-blue-500 text-lg';
            }
            document.getElementById('toastMessage').textContent = msg;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        // ==================== HITUNG OTOMATIS MODAL TAMBAH ====================
        function hitungOtomatis() {
            let totalHarga = 0;

            const tanggalSewa = document.getElementById('tanggal_sewa')?.value;
            const tanggalKembali = document.getElementById('tanggal_kembali')?.value;
            let jumlahHari = 1;

            if (tanggalSewa && tanggalKembali) {
                const start = new Date(tanggalSewa);
                const end = new Date(tanggalKembali);
                const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                jumlahHari = diff > 0 ? diff : 1;
            }

            document.querySelectorAll('#barangContainer .barang-row').forEach(row => {
                const select = row.querySelector('.barang-select');
                const jumlah = parseInt(row.querySelector('input[name*="[jumlah]"]')?.value || 0);

                if (select && select.selectedIndex > 0 && jumlah > 0) {
                    let harga = 0;
                    if (select.dataset.hargaPromo) {
                        harga = parseInt(select.dataset.hargaPromo);
                    } else {
                        const optionText = select.options[select.selectedIndex].text;
                        const match = optionText.match(/\(Rp\s([\d.,]+)\)/);
                        if (match) {
                            harga = parseInt(match[1].replace(/\./g, '').replace(/,/g, ''));
                        }
                    }
                    totalHarga += harga * jumlah * jumlahHari;
                }
            });

            const diskon = parseInt(document.getElementById('diskon')?.value || 0);
            const grandTotal = totalHarga - diskon;
            const ppnPersen = 0.11;
            const totalPpn = grandTotal * ppnPersen;
            const grandTotalWithPpn = grandTotal + totalPpn;

            const elPpnDisplay = document.getElementById('tambah_total_ppn_display');
            const elPpn = document.getElementById('total_ppn');
            const elGtDisplay = document.getElementById('tambah_grand_total_with_ppn_display');
            const elGt = document.getElementById('grand_total_with_ppn');

            if (elPpnDisplay) elPpnDisplay.value = formatRupiahInput(totalPpn);
            if (elPpn) elPpn.value = totalPpn;
            if (elGtDisplay) elGtDisplay.value = formatRupiahInput(grandTotalWithPpn);
            if (elGt) elGt.value = grandTotalWithPpn;

            if (tanggalSewa) {
                const jatuhTempo = new Date(tanggalSewa);
                jatuhTempo.setDate(jatuhTempo.getDate() + 7);
                const el = document.getElementById('jatuh_tempo_pembayaran');
                if (el) el.value = jatuhTempo.toISOString().split('T')[0];
            }
        }

        // ==================== HITUNG OTOMATIS MODAL EDIT ====================
        function hitungOtomatisEdit() {
            let totalHarga = 0;

            const tanggalSewa = document.getElementById('edit_tanggal_sewa')?.value;
            const tanggalKembali = document.getElementById('edit_tanggal_kembali')?.value;
            let jumlahHari = 1;

            if (tanggalSewa && tanggalKembali) {
                const start = new Date(tanggalSewa);
                const end = new Date(tanggalKembali);
                const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                jumlahHari = diff > 0 ? diff : 1;
            }

            document.querySelectorAll('#editBarangContainer .barang-row').forEach(row => {
                const select = row.querySelector('.barang-select');
                const jumlah = parseInt(row.querySelector('input[name*="[jumlah]"]')?.value || 0);

                if (select && select.selectedIndex > 0 && jumlah > 0) {
                    let harga = 0;
                    if (select.dataset.hargaPromo) {
                        harga = parseInt(select.dataset.hargaPromo);
                    } else {
                        const optionText = select.options[select.selectedIndex].text;
                        const match = optionText.match(/\(Rp\s([\d.,]+)\)/);
                        if (match) {
                            harga = parseInt(match[1].replace(/\./g, '').replace(/,/g, ''));
                        }
                    }
                    totalHarga += harga * jumlah * jumlahHari;
                }
            });

            const diskon = parseInt(document.getElementById('edit_diskon')?.value || 0);
            const grandTotal = totalHarga - diskon;
            const ppnPersen = 0.11;
            const totalPpn = grandTotal * ppnPersen;
            const grandTotalWithPpn = grandTotal + totalPpn;

            const elPpnDisplay = document.getElementById('edit_total_ppn_display');
            const elPpn = document.getElementById('edit_total_ppn');
            const elGtDisplay = document.getElementById('edit_grand_total_with_ppn_display');
            const elGt = document.getElementById('edit_grand_total_with_ppn');
            const elInfo = document.getElementById('edit_infoJumlahHari');

            if (elPpnDisplay) elPpnDisplay.value = formatRupiahInput(totalPpn);
            if (elPpn) elPpn.value = totalPpn;
            if (elGtDisplay) elGtDisplay.value = formatRupiahInput(grandTotalWithPpn);
            if (elGt) elGt.value = grandTotalWithPpn;

            if (elInfo) {
                elInfo.textContent = (tanggalSewa && tanggalKembali) ?
                    `Durasi sewa: ${jumlahHari} hari` :
                    '';
            }

            if (tanggalSewa) {
                const jatuhTempo = new Date(tanggalSewa);
                jatuhTempo.setDate(jatuhTempo.getDate() + 7);
                const el = document.getElementById('edit_jatuh_tempo_pembayaran');
                if (el) el.value = jatuhTempo.toISOString().split('T')[0];
            }
        }

        function formatRupiahInput(angka) {
            return new Intl.NumberFormat('id-ID').format(Math.round(angka));
        }

        // ==================== BARANG FUNCTIONS ====================
        async function loadBarang() {
            try {
                const response = await fetch('/api/barang-tersedia');
                if (!response.ok) throw new Error('Network error');
                const result = await response.json();
                barangList = result;
                populateBarangSelects();
            } catch (error) {
                console.error('Error loading barang:', error);
            }
        }

        function populateBarangSelects() {
            const selects = document.querySelectorAll('.barang-select, #editBarangContainer .barang-select');
            selects.forEach(select => {
                const currentValue = select.value;
                select.innerHTML = '<option value="">Pilih Barang</option>';
                barangList.forEach(barang => {
                    if (barang.tersedia > 0) {
                        const option = document.createElement('option');
                        option.value = barang.id;
                        option.textContent =
                            `${barang.kode_barang} - ${barang.nama_barang} (${formatRupiah(barang.harga_sewa)}) - Tersedia: ${barang.tersedia}`;
                        select.appendChild(option);
                    }
                });
                if (currentValue) select.value = currentValue;
            });
        }

        function addBarang() {
            const container = document.getElementById('barangContainer');
            const index = container.children.length;
            const newRow = document.createElement('div');
            newRow.className = 'flex gap-3 items-center barang-row animate-in slide-in-from-left-2 duration-200';
            newRow.innerHTML = `
                <div class="flex-1 relative group">
                    <select name="barang[${index}][id]" class="barang-select w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <option value="">Pilih Barang...</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
                <div class="w-28 relative">
                    <input type="number" name="barang[${index}][jumlah]" placeholder="Jml" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" value="1">
                    <span class="absolute -top-2 left-3 px-1 bg-white text-[9px] font-black text-slate-400 uppercase">Qty</span>
                </div>
                <button type="button" onclick="removeBarang(this)" class="w-10 h-10 flex items-center justify-center text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                    <i class="fas fa-trash-alt text-sm"></i>
                </button>
            `;
            container.appendChild(newRow);
            populateBarangSelects();
            setTimeout(() => {
                attachBarangChangeEvent();
                hitungOtomatis();
            }, 100);
        }

        function removeBarang(btn) {
            const rows = document.querySelectorAll('.barang-row');
            if (rows.length > 1) btn.closest('.barang-row').remove();
            setTimeout(hitungOtomatis, 100);
        }

        function attachBarangChangeEvent() {
            document.querySelectorAll('#barangContainer .barang-row').forEach(row => {
                const select = row.querySelector('.barang-select');
                const jumlahInput = row.querySelector('input[name*="[jumlah]"]');

                if (select) {
                    select.removeEventListener('change', hitungOtomatis);
                    select.addEventListener('change', function() {
                        cekPromoBarang(this.value, row);
                        hitungOtomatis();
                    });
                }
                if (jumlahInput) {
                    jumlahInput.removeEventListener('change', hitungOtomatis);
                    jumlahInput.addEventListener('change', hitungOtomatis);
                }
            });
        }

        function attachEditBarangChangeEvent() {
            document.querySelectorAll('#editBarangContainer .barang-row').forEach(row => {
                const select = row.querySelector('.barang-select');
                const jumlahInput = row.querySelector('input[name*="[jumlah]"]');

                if (select) {
                    select.removeEventListener('change', hitungOtomatisEdit);
                    select.addEventListener('change', hitungOtomatisEdit);
                }
                if (jumlahInput) {
                    jumlahInput.removeEventListener('change', hitungOtomatisEdit);
                    jumlahInput.addEventListener('change', hitungOtomatisEdit);
                }
            });
        }

        // ==================== FETCH DATA ====================
        async function fetchData() {
            if (isLoading) return;
            isLoading = true;
            try {
                const params = new URLSearchParams({
                    page: currentPage,
                    status: currentTab,
                    sort: currentFilters.sort,
                    search: currentFilters.search,
                    pelanggan: currentFilters.pelanggan
                });
                const response = await fetch(`/peminjaman?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!response.ok) throw new Error('HTTP error! status: ' + response.status);
                const result = await response.json();
                if (result.data) {
                    renderTable(result.data);
                    if (result.pagination) renderPagination(result.pagination);
                } else {
                    document.getElementById('peminjamanTableBody').innerHTML =
                        '<tr><td colspan="7" class="px-6 py-12 text-center text-slate-500">Belum ada data peminjaman</td></tr>';
                }
                updateBadges();
            } catch (error) {
                document.getElementById('peminjamanTableBody').innerHTML =
                    `<tr><td colspan="7" class="px-6 py-12 text-center text-red-500">Error: ${error.message}</td></tr>`;
            } finally {
                isLoading = false;
            }
        }

        async function updateBadges() {
            try {
                const aktifRes = await fetch('/peminjaman?status=aktif&per_page=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const aktifResult = await aktifRes.json();
                const riwayatRes = await fetch('/peminjaman?status=riwayat&per_page=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const riwayatResult = await riwayatRes.json();
                const badgeAktif = document.getElementById('badgeAktif');
                const badgeRiwayat = document.getElementById('badgeRiwayat');
                if (badgeAktif) badgeAktif.textContent = (aktifResult.pagination?.total) || 0;
                if (badgeRiwayat) badgeRiwayat.textContent = (riwayatResult.pagination?.total) || 0;
            } catch (e) {
                console.error('Error updating badges:', e);
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('peminjamanTableBody');
            if (!tbody) return;
            if (!data || data.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="7" class="px-6 py-12 text-center"><i class="fas fa-inbox text-4xl text-slate-300 mb-2 block"></i>Belum ada data</td></tr>';
                return;
            }
            let html = '';
            data.forEach(item => {
                const barangListStr = item.details?.length ? item.details.map(d => d.nama_barang).join(', ') : '-';
                const isAktif = item.status_pengembalian === 'aktif';
                const totalTransaksi = item.pelanggan?.total_transaksi || 0;
                const isPelangganBaru = totalTransaksi <= 1;
                const badgePelanggan = isPelangganBaru ?
                    '<span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full ml-1"><i class="fas fa-star fa-xs mr-1"></i>Baru</span>' :
                    '<span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full ml-1"><i class="fas fa-check-circle fa-xs mr-1"></i>Lama</span>';
                html += `<tr class="hover:bg-slate-50 transition border-b border-slate-100">
                    <td class="px-6 py-3 text-xs font-mono font-semibold">${escapeHtml(item.invoice_number)}</td>
                    <td class="px-6 py-3 text-sm font-medium">${escapeHtml(item.nama_penyewa)}${badgePelanggan}</td>
                    <td class="px-6 py-3 text-xs text-slate-600">${escapeHtml(barangListStr.substring(0, 40))}${barangListStr.length > 40 ? '...' : ''}</td>
                    <td class="px-6 py-3 text-xs text-center">${formatShortDate(item.tanggal_sewa)} - ${formatShortDate(item.tanggal_kembali)}</td>
                    <td class="px-6 py-3 text-xs font-semibold text-right">${formatRupiah(item.grand_total_with_ppn || item.grand_total)}</td>
                    <td class="px-6 py-3 text-center">${getStatusBadge(item.status_pengembalian)}</td>
                    <td class="px-6 py-3 text-center"><div class="flex items-center justify-center gap-1.5 flex-wrap">
                        <button onclick="viewDetail(${item.id})" class="text-blue-600 hover:text-blue-800 p-1" title="Detail"><i class="fas fa-eye text-sm"></i></button>
                        ${isAktif ? `<button onclick="openEditModal(${item.id})" class="text-orange-600 hover:text-orange-800 p-1" title="Edit"><i class="fas fa-edit text-sm"></i></button>` : ''}
                        <button onclick="printInvoice(${item.id})" class="text-gray-600 hover:text-gray-800 p-1" title="Invoice"><i class="fas fa-print text-sm"></i></button>
                        ${isAktif ? `<button onclick="openPengembalianModal(${item.id})" class="text-green-600 hover:text-green-800 p-1" title="Pengembalian"><i class="fas fa-undo-alt text-sm"></i></button>` : ''}
                        <button onclick="deleteData(${item.id})" class="text-red-600 hover:text-red-800 p-1" title="Hapus"><i class="fas fa-trash text-sm"></i></button>
                    </div></td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }

        function renderPagination(pagination) {
            const container = document.getElementById('paginationContainer');
            if (!container) return;
            if (!pagination || pagination.last_page <= 1) {
                container.innerHTML = '';
                return;
            }
            let html = '<div class="flex justify-center gap-1">';
            const current = pagination.current_page;
            const last = pagination.last_page;
            let start = Math.max(1, current - 2);
            let end = Math.min(last, current + 2);
            if (start > 1) {
                html += `<button onclick="changePage(1)" class="px-3 py-1 text-sm rounded-lg border">1</button>`;
                if (start > 2) html += '<span class="px-2">...</span>';
            }
            for (let i = start; i <= end; i++) {
                html +=
                    `<button onclick="changePage(${i})" class="px-3 py-1 text-sm rounded-lg ${i === current ? 'bg-gray-700 text-white' : 'border hover:bg-gray-100'}">${i}</button>`;
            }
            if (end < last) {
                if (end < last - 1) html += '<span class="px-2">...</span>';
                html +=
                    `<button onclick="changePage(${last})" class="px-3 py-1 text-sm rounded-lg border">${last}</button>`;
            }
            html += '</div>';
            container.innerHTML = html;
        }

        function changePage(page) {
            currentPage = page;
            fetchData();
        }

        // ==================== CRUD FUNCTIONS ====================
        function openTambahModal() {
            const modal = document.getElementById('modalTambah');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeTambahModal() {
            const modal = document.getElementById('modalTambah');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            const form = document.getElementById('formPeminjaman');
            if (form) form.reset();
            document.getElementById('previewBuktiPembayaran')?.classList.add('hidden');
            const buktiInput = document.getElementById('buktiPembayaranInput');
            if (buktiInput) buktiInput.value = '';
            document.getElementById('newCustomerInfo')?.classList.add('hidden');
            document.getElementById('oldCustomerInfo')?.classList.add('hidden');
            selectedCustomer = null;
        }

        function printInvoice(id) {
            window.open(`/peminjaman/${id}/invoice`, '_blank');
        }
        async function viewDetail(id) {
            try {
                const response = await fetch(`/peminjaman/${id}`);
                const result = await response.json();
                if (result.success) {
                    const data = result.data;
                    let detailsHtml = '';
                    data.details.forEach(d => {
                        detailsHtml +=
                            `<tr class="border-b"><td class="py-1.5 text-sm">${escapeHtml(d.nama_barang)}</td><td class="py-1.5 text-center text-sm">${d.jumlah}</td><td class="py-1.5 text-right text-sm">${formatRupiah(d.harga_sewa)}</td><td class="py-1.5 text-right text-sm font-semibold">${formatRupiah(d.subtotal)}</td></tr>`;
                    });

                    // TAMPILKAN BUKTI PEMBAYARAN
                    let buktiPembayaranHtml = '';
                    if (data.bukti_pembayaran) {
                        buktiPembayaranHtml = `
                    <div class="mb-4">
                        <p class="font-semibold text-sm mb-2 flex items-center gap-2">
                            <i class="fas fa-credit-card text-indigo-500"></i> Bukti Pembayaran:
                        </p>
                        <div class="relative inline-block">
                            <img src="/storage/${data.bukti_pembayaran}" 
                                 class="w-64 h-64 object-cover rounded-xl border-2 border-slate-200 shadow-md cursor-pointer hover:opacity-90 transition-all" 
                                 onclick="window.open('/storage/${data.bukti_pembayaran}', '_blank')">
                            <div class="absolute bottom-2 right-2 bg-black/50 text-white text-[10px] px-2 py-1 rounded-lg">
                                Klik untuk perbesar
                            </div>
                        </div>
                    </div>
                `;
                    } else {
                        buktiPembayaranHtml = `
                    <div class="mb-4 p-3 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center">
                        <i class="fas fa-file-image text-gray-300 text-2xl mb-1"></i>
                        <p class="text-xs text-gray-400">Belum ada bukti pembayaran</p>
                    </div>
                `;
                    }

                    const buktiPengembalianHtml = data.foto_pengembalian ?
                        `<div class="mb-4"><p class="font-semibold text-sm mb-2">Dokumentasi Pengembalian:</p><img src="/storage/${data.foto_pengembalian}" class="w-48 h-48 object-cover rounded-lg border cursor-pointer" onclick="window.open('/storage/${data.foto_pengembalian}', '_blank')"></div>` :
                        '';
                    const infoPengembalianHtml = data.status_pengembalian === 'selesai' ?
                        `<div class="mt-4 p-3 bg-gray-50 rounded-lg"><h4 class="font-semibold text-sm mb-2">Informasi Pengembalian:</h4><p class="text-sm">Tanggal Kembali Real: ${formatDate(data.tanggal_pengembalian_real) || '-'}</p><p class="text-sm">Kondisi Barang: ${data.kondisi_barang || '-'}</p>${data.kerusakan ? `<p class="text-sm">Kerusakan: ${data.kerusakan}</p>` : ''}${data.denda > 0 ? `<p class="text-sm">Denda: ${formatRupiah(data.denda)}</p>` : ''}${data.catatan_pengembalian ? `<p class="text-sm">Catatan: ${data.catatan_pengembalian}</p>` : ''}</div>` :
                        '';
                    document.getElementById('detailContent').innerHTML = `
                <div class="grid grid-cols-2 gap-3 mb-4 pb-3 border-b">
                    <div><p class="text-xs text-slate-500">Invoice</p><p class="font-mono font-semibold text-sm">${data.invoice_number}</p></div>
                    <div><p class="text-xs text-slate-500">Status</p>${getStatusBadge(data.status_pengembalian)}</div>
                    <div><p class="text-xs text-slate-500">Penyewa</p><p class="font-semibold text-sm">${escapeHtml(data.nama_penyewa)}</p></div>
                    <div><p class="text-xs text-slate-500">Telepon</p><p class="text-sm">${escapeHtml(data.no_telepon)}</p></div>
                    <div><p class="text-xs text-slate-500">Tanggal Sewa</p><p class="text-sm">${formatDate(data.tanggal_sewa)} | ${data.waktu_sewa}</p></div>
                    <div><p class="text-xs text-slate-500">Tanggal Kembali</p><p class="text-sm">${formatDate(data.tanggal_kembali)} | ${data.waktu_kembali}</p></div>
                    <div><p class="text-xs text-slate-500">Status Pembayaran</p><p class="text-sm">${data.status_pembayaran || '-'}</p></div>
                    <div><p class="text-xs text-slate-500">Subtotal</p><p class="text-sm">${formatRupiah(data.total_harga)}</p></div>
                    <div><p class="text-xs text-slate-500">Diskon</p><p class="text-sm">${formatRupiah(data.diskon || 0)}</p></div>
                    <div><p class="text-xs text-slate-500">Grand Total</p><p class="text-sm font-bold">${formatRupiah(data.grand_total)}</p></div>
                    <div><p class="text-xs text-slate-500">PPN 11%</p><p class="text-sm font-semibold text-emerald-600">${formatRupiah(data.total_ppn || 0)}</p></div>
                    <div class="col-span-2 bg-indigo-50 p-2 rounded-lg"><p class="text-xs text-slate-500">Grand Total + PPN</p><p class="text-lg font-bold text-indigo-600">${formatRupiah(data.grand_total_with_ppn || data.grand_total)}</p></div>
                    <div><p class="text-xs text-slate-500">Jatuh Tempo Pembayaran</p><p class="text-sm font-semibold text-rose-600">${formatDate(data.jatuh_tempo_pembayaran) || '-'}</p></div>
                </div>
                ${buktiPembayaranHtml}
                <div class="mb-3"><p class="font-semibold text-sm mb-2">Detail Barang:</p><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="bg-gray-50"><th class="px-2 py-1 text-left">Barang</th><th class="px-2 py-1 text-center w-16">Jml</th><th class="px-2 py-1 text-right w-28">Harga</th><th class="px-2 py-1 text-right w-28">Subtotal</th></tr></thead><tbody>${detailsHtml}</tbody><tfoot><tr class="border-t"><td colspan="3" class="px-2 py-2 text-right font-bold">TOTAL</td><td class="px-2 py-2 text-right font-bold">${formatRupiah(data.total_harga)}</td></tr></tfoot></table></div></div>
                ${buktiPengembalianHtml}
                ${infoPengembalianHtml}
            `;
                    const modal = document.getElementById('modalDetail');
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal mengambil detail', 'error');
            }
        }

        function closeDetailModal() {
            const modal = document.getElementById('modalDetail');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function deleteData(id) {
            currentDeleteId = id;
            const modal = document.getElementById('modalDeleteConfirm');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('confirmDeleteBtn').onclick = async () => await executeDelete();
        }

        async function executeDelete() {
            if (!currentDeleteId) return;
            const btn = document.getElementById('confirmDeleteBtn');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Menghapus...';
            try {
                const response = await fetch(`/peminjaman/${currentDeleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                if (result.success) {
                    showToast(result.message || 'Data berhasil dihapus', 'success');
                    fetchData();
                    closeDeleteModal();
                } else {
                    showToast(result.message || 'Gagal menghapus data', 'error');
                }
            } catch (error) {
                showToast('Gagal menghubungi server', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        }

        function closeDeleteModal() {
            const modal = document.getElementById('modalDeleteConfirm');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentDeleteId = null;
        }

        // ==================== EDIT FUNCTIONS ====================
        async function openEditModal(id) {
            currentEditId = id;
            try {
                const response = await fetch(`/peminjaman/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server tidak mengembalikan JSON. Status: ' + response.status);
                }

                const result = await response.json();
                if (!result.success) {
                    showToast(result.message || 'Gagal mengambil data', 'error');
                    return;
                }

                const data = result.data;

                function setVal(id, val) {
                    const el = document.getElementById(id);
                    if (el) el.value = val ?? '';
                }

                setVal('edit_id', data.id);
                setVal('edit_invoice_number', data.invoice_number);
                setVal('edit_nama_penyewa', data.nama_penyewa);
                setVal('edit_no_telepon', data.no_telepon);
                setVal('edit_nama_acara', data.nama_acara || '');
                setVal('edit_lokasi_acara', data.lokasi_acara || '');
                setVal('edit_waktu_sewa', data.waktu_sewa || '');
                setVal('edit_waktu_kembali', data.waktu_kembali || '');
                setVal('edit_diskon', data.diskon || 0);
                setVal('edit_status_pembayaran', data.status_pembayaran || 'belum_bayar');
                setVal('edit_keterangan', data.keterangan || '');

                if (data.tanggal_sewa) {
                    setVal('edit_tanggal_sewa', new Date(data.tanggal_sewa).toISOString().split('T')[0]);
                }
                if (data.tanggal_kembali) {
                    setVal('edit_tanggal_kembali', new Date(data.tanggal_kembali).toISOString().split('T')[0]);
                }
                if (data.jatuh_tempo_pembayaran) {
                    setVal('edit_jatuh_tempo_pembayaran', data.jatuh_tempo_pembayaran);
                }

                setVal('edit_total_ppn_display', formatRupiahInput(data.total_ppn || 0));
                setVal('edit_total_ppn', data.total_ppn || 0);
                setVal('edit_grand_total_with_ppn_display', formatRupiahInput(data.grand_total_with_ppn || data
                    .grand_total || 0));
                setVal('edit_grand_total_with_ppn', data.grand_total_with_ppn || data.grand_total || 0);

                // Set existing bukti pembayaran
                if (data.bukti_pembayaran) {
                    const existingInfo = document.getElementById('existingBuktiInfo');
                    const existingLink = document.getElementById('existingBuktiLink');
                    if (existingInfo && existingLink) {
                        existingLink.href = `/storage/${data.bukti_pembayaran}`;
                        existingInfo.classList.remove('hidden');
                    }
                } else {
                    const existingInfo = document.getElementById('existingBuktiInfo');
                    if (existingInfo) existingInfo.classList.add('hidden');
                }

                const container = document.getElementById('editBarangContainer');
                container.innerHTML = '';
                if (data.details?.length) {
                    data.details.forEach(d => addEditBarangRow(d.barang_id, d.jumlah));
                } else {
                    addEditBarangRow(null, 1);
                }

                setTimeout(() => attachEditBarangChangeEvent(), 200);

                const modal = document.getElementById('modalEdit');
                modal.classList.remove('hidden');
                modal.classList.add('flex');

            } catch (error) {
                console.error('openEditModal error:', error);
                showToast('Gagal mengambil data: ' + error.message, 'error');
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('modalEdit');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Reset edit form bukti preview
            document.getElementById('previewEditBuktiPembayaran')?.classList.add('hidden');
            document.getElementById('editBuktiPembayaranInput').value = '';
            currentEditId = null;
        }

        function addEditBarang() {
            addEditBarangRow(null, 1);
            setTimeout(() => attachEditBarangChangeEvent(), 100);
        }

        function addEditBarangRow(selectedId, jumlah) {
            const container = document.getElementById('editBarangContainer');
            const index = container.children.length;
            const newRow = document.createElement('div');
            newRow.className = 'flex gap-3 items-center barang-row mb-2';
            newRow.innerHTML = `
                <div class="flex-1 relative group">
                    <select name="barang[${index}][id]" class="barang-select w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <option value="">Pilih Barang...</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </div>
                </div>
                <div class="w-28 relative">
                    <input type="number" name="barang[${index}][jumlah]" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" value="${jumlah || 1}">
                    <span class="absolute -top-2 left-3 px-1 bg-white text-[9px] font-black text-slate-400 uppercase">Qty</span>
                </div>
                <button type="button" onclick="removeEditBarang(this)" class="w-10 h-10 flex items-center justify-center text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                    <i class="fas fa-trash-alt text-sm"></i>
                </button>
            `;
            container.appendChild(newRow);
            populateBarangSelects();
            if (selectedId) {
                setTimeout(() => {
                    newRow.querySelector('.barang-select').value = selectedId;
                }, 100);
            }
        }

        function removeEditBarang(btn) {
            const rows = document.querySelectorAll('#editBarangContainer .barang-row');
            if (rows.length > 1) btn.closest('.barang-row').remove();
            else showToast('Minimal harus ada satu barang', 'warning');
            setTimeout(hitungOtomatisEdit, 100);
        }

        // ==================== EDIT BUKTI PEMBAYARAN FUNCTIONS ====================
        const editDropzoneBukti = document.getElementById('dropzoneEditBuktiPembayaran');
        const editBuktiInput = document.getElementById('editBuktiPembayaranInput');
        const editPreviewBukti = document.getElementById('previewEditBuktiPembayaran');
        const editPreviewImgBukti = document.getElementById('previewImgEditBukti');

        if (editDropzoneBukti && editBuktiInput) {
            editDropzoneBukti.addEventListener('click', () => editBuktiInput.click());
            editBuktiInput.addEventListener('change', (e) => {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        editPreviewImgBukti.src = ev.target.result;
                        editPreviewBukti.classList.remove('hidden');
                        editDropzoneBukti.classList.add('hidden');
                        const existingInfo = document.getElementById('existingBuktiInfo');
                        if (existingInfo) existingInfo.classList.add('hidden');
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }

        function removeEditBuktiPembayaran() {
            editPreviewBukti.classList.add('hidden');
            editDropzoneBukti.classList.remove('hidden');
            editBuktiInput.value = '';
            editPreviewImgBukti.src = '';
            const existingInfo = document.getElementById('existingBuktiInfo');
            const existingLink = document.getElementById('existingBuktiLink');
            if (existingInfo && existingLink && existingLink.href !== '#') {
                existingInfo.classList.remove('hidden');
            }
        }

        // ==================== PENGEMBALIAN FUNCTIONS ====================
        function openPengembalianModal(id) {
            document.getElementById('pengembalianId').value = id;
            const modal = document.getElementById('modalPengembalian');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            resetPengembalianForm();
        }

        function closePengembalianModal() {
            const modal = document.getElementById('modalPengembalian');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            resetPengembalianForm();
        }

        function resetPengembalianForm() {
            const form = document.getElementById('formPengembalian');
            if (form) form.reset();
            document.getElementById('kerusakanSection').classList.add('hidden');
            document.getElementById('dendaSection').classList.add('hidden');
            document.getElementById('previewPengembalian').classList.add('hidden');
            document.getElementById('dropzonePengembalian').classList.remove('hidden');
        }

        function toggleKerusakan() {
            const kondisi = document.getElementById('kondisiBarang').value;
            if (kondisi === 'rusak') {
                document.getElementById('kerusakanSection').classList.remove('hidden');
                document.getElementById('dendaSection').classList.remove('hidden');
            } else if (kondisi === 'kurang_baik') {
                document.getElementById('kerusakanSection').classList.remove('hidden');
                document.getElementById('dendaSection').classList.add('hidden');
            } else {
                document.getElementById('kerusakanSection').classList.add('hidden');
                document.getElementById('dendaSection').classList.add('hidden');
            }
        }

        // ==================== CEK PELANGGAN FUNCTIONS ====================
        function openCekPelangganModal() {
            const modal = document.getElementById('modalCekPelanggan');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('searchPelanggan').value = '';
            document.getElementById('hasilCekPelanggan').classList.add('hidden');
            document.getElementById('pelangganNotFound').classList.add('hidden');
            document.getElementById('pelangganDefault').classList.remove('hidden');
            document.getElementById('autocompleteDropdown').classList.add('hidden');
        }

        function closeCekPelangganModal() {
            const modal = document.getElementById('modalCekPelanggan');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        async function searchPelangganAutocomplete(keyword) {
            try {
                const response = await fetch(`/api/pelanggan/list?search=${encodeURIComponent(keyword)}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    document.getElementById('autocompleteDropdown').classList.add('hidden');
                    return;
                }

                const result = await response.json();
                const dropdown = document.getElementById('autocompleteDropdown');
                if (result.data?.length) {
                    let html = '';
                    result.data.forEach(p => {
                        html += `<div onclick="selectPelangganSuggestion(${p.id}, '${escapeHtml(p.nama)}', '${escapeHtml(p.no_telepon)}')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-slate-100 last:border-0">
                            <div class="font-medium">${escapeHtml(p.nama)}</div>
                            <div class="text-xs text-slate-500">${escapeHtml(p.no_telepon)}</div>
                        </div>`;
                    });
                    dropdown.innerHTML = html;
                    dropdown.classList.remove('hidden');
                } else {
                    dropdown.classList.add('hidden');
                }
            } catch (error) {
                console.error('Autocomplete error:', error);
                document.getElementById('autocompleteDropdown').classList.add('hidden');
            }
        }

        function selectPelangganSuggestion(id, nama, telepon) {
            document.getElementById('searchPelanggan').value = nama;
            document.getElementById('autocompleteDropdown').classList.add('hidden');
            searchPelanggan(nama);
        }

        async function searchPelanggan(keyword) {
            document.getElementById('hasilCekPelanggan').classList.add('hidden');
            document.getElementById('pelangganNotFound').classList.add('hidden');
            document.getElementById('pelangganDefault').classList.add('hidden');

            try {
                const response = await fetch('/api/pelanggan/cek', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        keyword: keyword
                    })
                });

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    showToast('Sesi habis. Silakan refresh halaman.', 'error');
                    return;
                }

                const result = await response.json();

                if (result.exists) {
                    const totalTransaksi = result.total_transaksi || 0;
                    const isPelangganLama = totalTransaksi > 1;
                    const isPelangganBaru = totalTransaksi <= 1;
                    const statusText = isPelangganBaru ? 'Pelanggan Baru' : 'Pelanggan Lama';
                    const statusColor = isPelangganBaru ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700';

                    document.getElementById('hasilNama').innerHTML =
                        `${escapeHtml(result.data.nama)} <span class="text-xs ${statusColor} px-2 py-0.5 rounded-full ml-2">${statusText}</span>`;
                    document.getElementById('hasilTelepon').textContent = result.data.no_telepon;
                    document.getElementById('hasilTotalTransaksi').innerHTML =
                        `<span class="text-lg font-bold">${totalTransaksi}</span> <span class="text-sm ${isPelangganBaru ? 'text-green-600' : 'text-blue-600'} ml-1">(transaksi)</span>`;
                    document.getElementById('hasilTotalNilai').innerHTML =
                        `<span class="text-lg font-bold">${formatRupiah(result.total_nilai || 0)}</span>`;

                    const statusSpan = document.getElementById('hasilStatus');
                    if (result.data.status === 'aktif') {
                        statusSpan.textContent = 'Aktif';
                        statusSpan.className =
                            'px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-tighter bg-green-100 text-green-700';
                    } else {
                        statusSpan.textContent = 'Nonaktif';
                        statusSpan.className =
                            'px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-tighter bg-red-100 text-red-700';
                    }

                    const riwayatList = document.getElementById('riwayatList');
                    if (result.riwayat?.length) {
                        let riwayatHtml = '';
                        result.riwayat.forEach(r => {
                            riwayatHtml += `<div class="bg-white rounded-lg p-2 border border-slate-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-mono text-xs font-semibold">${r.invoice_number}</span>
                                    <span class="text-xs ${r.status_pengembalian === 'aktif' ? 'text-green-600' : 'text-gray-500'}">${r.status_pengembalian === 'aktif' ? '🟢 Aktif' : '✅ Selesai'}</span>
                                </div>
                                <div class="text-xs text-slate-500 mt-1">Tanggal: ${formatDate(r.tanggal_sewa)} - ${formatDate(r.tanggal_kembali)}</div>
                                <div class="text-xs font-semibold mt-1">Total: ${formatRupiah(r.grand_total_with_ppn || r.grand_total)}</div>
                            </div>`;
                        });
                        riwayatList.innerHTML = riwayatHtml;
                    } else {
                        riwayatList.innerHTML = '<p class="text-xs text-slate-500">Belum ada riwayat peminjaman</p>';
                    }

                    selectedCustomer = result.data;
                    document.getElementById('hasilCekPelanggan').classList.remove('hidden');
                } else {
                    selectedCustomer = null;
                    document.getElementById('pelangganNotFound').classList.remove('hidden');

                    const suggestionsContainer = document.getElementById('suggestionsContainer');
                    if (result.suggestions?.length) {
                        let suggestionsHtml =
                            '<p class="text-sm text-slate-600 mb-2 font-semibold">📋 Pelanggan dengan nama mirip:</p>';
                        result.suggestions.forEach(s => {
                            suggestionsHtml += `<div onclick="selectPelangganSuggestion(${s.id}, '${escapeHtml(s.nama)}', '${escapeHtml(s.no_telepon)}')"
                                class="p-3 bg-white border border-slate-200 rounded-xl mb-2 cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition-all">
                                <div class="font-semibold text-slate-800">${escapeHtml(s.nama)}</div>
                                <div class="text-xs text-slate-500">📞 ${escapeHtml(s.no_telepon)}</div>
                            </div>`;
                        });
                        suggestionsContainer.innerHTML = suggestionsHtml;
                    } else {
                        suggestionsContainer.innerHTML =
                            '<p class="text-sm text-slate-500">Tidak ada saran pelanggan mirip.</p>';
                    }
                }
            } catch (error) {
                console.error('searchPelanggan error:', error);
                showToast('Gagal mengecek pelanggan: ' + error.message, 'error');
                document.getElementById('pelangganDefault').classList.remove('hidden');
            }
        }

        function useExistingCustomer() {
            if (!selectedCustomer) {
                showToast('Tidak ada data pelanggan yang dipilih', 'error');
                return;
            }

            document.getElementById('pelanggan_id').value = selectedCustomer.id;
            document.getElementById('nama_penyewa').value = selectedCustomer.nama;
            document.getElementById('no_telepon').value = selectedCustomer.no_telepon;
            document.getElementById('alamat').value = selectedCustomer.alamat || '';
            document.getElementById('tipe_pelanggan').value = selectedCustomer.tipe || 'perorangan';

            document.getElementById('newCustomerInfo').classList.add('hidden');
            const oldInfo = document.getElementById('oldCustomerInfo');
            oldInfo.classList.remove('hidden');
            document.getElementById('oldCustomerDetail').textContent =
                `${selectedCustomer.nama} — sudah ${selectedCustomer.total_transaksi || 0} transaksi`;

            closeCekPelangganModal();
            showToast('Data pelanggan lama berhasil diisi', 'success');
        }

        function openNewCustomerForm() {
            const keyword = document.getElementById('searchPelanggan').value || '';
            closeCekPelangganModal();

            document.getElementById('pelanggan_id').value = '';
            document.getElementById('nama_penyewa').value = keyword;
            document.getElementById('no_telepon').value = '';
            document.getElementById('alamat').value = '';
            document.getElementById('tipe_pelanggan').value = 'perorangan';

            document.getElementById('oldCustomerInfo').classList.add('hidden');
            document.getElementById('newCustomerInfo').classList.remove('hidden');

            document.getElementById('nama_penyewa').focus();
        }

        function switchTab(tab) {
            currentTab = tab;
            currentPage = 1;
            const aktifBtn = document.getElementById('tabAktifBtn'),
                riwayatBtn = document.getElementById('tabRiwayatBtn');
            const activeClasses = ['bg-white', 'text-indigo-600', 'shadow-sm', 'border-slate-200/50'],
                inactiveClasses = ['text-slate-500'];
            if (tab === 'aktif') {
                aktifBtn?.classList.add(...activeClasses);
                aktifBtn?.classList.remove(...inactiveClasses);
                riwayatBtn?.classList.remove(...activeClasses);
                riwayatBtn?.classList.add(...inactiveClasses);
            } else {
                riwayatBtn?.classList.add(...activeClasses);
                riwayatBtn?.classList.remove(...inactiveClasses);
                aktifBtn?.classList.remove(...activeClasses);
                aktifBtn?.classList.add(...inactiveClasses);
            }
            fetchData();
        }

        async function cekPromoBarang(barangId, row) {
            if (!barangId) return;
            const select = row.querySelector('.barang-select');
            if (select) delete select.dataset.hargaPromo;
            try {
                const res = await fetch(`/promo/aktif/${barangId}`);
                const result = await res.json();
                const existingBadge = row.querySelector('.promo-badge');
                if (existingBadge) existingBadge.remove();
                if (result.success && result.promo) {
                    const p = result.promo;
                    if (select) select.dataset.hargaPromo = p.harga_diskon;
                    const badge = document.createElement('div');
                    badge.className =
                        'promo-badge mt-1 px-2 py-1 bg-purple-50 border border-purple-200 rounded-lg text-xs flex items-center gap-1 flex-wrap';
                    badge.innerHTML = `
                        <span class="text-purple-600 font-bold">🎉 ${p.nama_promo}</span>
                        <span class="text-slate-400 line-through">Rp ${p.harga_asli.toLocaleString('id-ID')}</span>
                        <span class="text-purple-700 font-bold">→ Rp ${p.harga_diskon.toLocaleString('id-ID')}</span>
                        <span class="text-emerald-600 font-bold">(Hemat Rp ${p.hemat.toLocaleString('id-ID')})</span>
                        <span class="text-slate-400">s/d ${p.berlaku_hingga}</span>
                    `;
                    row.appendChild(badge);
                }
                hitungOtomatis();
            } catch (e) {
                console.error('Cek promo error:', e);
                hitungOtomatis();
            }
        }

        // ==================== DOM READY ====================
        document.addEventListener('DOMContentLoaded', () => {
            loadBarang();
            fetchData();

            document.getElementById('filterSort')?.addEventListener('change', (e) => {
                currentFilters.sort = e.target.value;
                currentPage = 1;
                fetchData();
            });
            document.getElementById('filterPelanggan')?.addEventListener('change', (e) => {
                currentFilters.pelanggan = e.target.value;
                currentPage = 1;
                fetchData();
            });
            document.getElementById('searchInput')?.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentFilters.search = e.target.value;
                    currentPage = 1;
                    fetchData();
                }, 500);
            });
            document.getElementById('searchPelanggan')?.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const val = e.target.value.trim();
                if (val.length < 2) {
                    document.getElementById('autocompleteDropdown').classList.add('hidden');
                    if (val.length === 0) {
                        document.getElementById('hasilCekPelanggan').classList.add('hidden');
                        document.getElementById('pelangganNotFound').classList.add('hidden');
                        document.getElementById('pelangganDefault').classList.remove('hidden');
                    }
                    return;
                }
                searchTimeout = setTimeout(() => {
                    searchPelangganAutocomplete(val);
                    setTimeout(() => searchPelanggan(val), 200);
                }, 350);
            });

            document.getElementById('tanggal_sewa')?.addEventListener('change', hitungOtomatis);
            document.getElementById('tanggal_kembali')?.addEventListener('change', hitungOtomatis);
            document.getElementById('diskon')?.addEventListener('input', hitungOtomatis);
            attachBarangChangeEvent();

            document.getElementById('edit_tanggal_sewa')?.addEventListener('change', hitungOtomatisEdit);
            document.getElementById('edit_tanggal_kembali')?.addEventListener('change', hitungOtomatisEdit);

            // ==================== SUBMIT FORM TAMBAH ====================
            document.getElementById('formPeminjaman')?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(e.target);
                const barang = [];
                document.querySelectorAll('#barangContainer .barang-row').forEach(row => {
                    const id = row.querySelector('[name*="[id]"]')?.value;
                    const jumlah = row.querySelector('[name*="[jumlah]"]')?.value;
                    if (id && jumlah) barang.push({
                        id: parseInt(id),
                        jumlah: parseInt(jumlah)
                    });
                });
                if (barang.length === 0) {
                    showToast('Pilih minimal satu barang', 'error');
                    return;
                }
                formData.append('barang', JSON.stringify(barang));

                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalContent = submitBtn?.innerHTML;
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<i class="fas fa-circle-notch fa-spin mr-2"></i> Menyimpan...';
                }

                try {
                    const response = await fetch('/peminjaman', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        let message = result.message;
                        if (result.promos_diterapkan?.length > 0) {
                            const promoNames = result.promos_diterapkan.map(p => p.nama_promo).join(
                                ', ');
                            const totalHemat = result.promos_diterapkan.reduce((sum, p) => sum + p
                                .hemat, 0);
                            message +=
                                ` 🎉 Promo: ${promoNames}. Hemat Rp ${totalHemat.toLocaleString('id-ID')}!`;
                        }
                        showToast(message, 'success');
                        closeTambahModal();
                        fetchData();
                    } else {
                        showToast(result.message, 'error');
                    }
                } catch (error) {
                    showToast('Gagal menyimpan', 'error');
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalContent;
                    }
                }
            });

            // ==================== SUBMIT FORM EDIT ====================
            document.getElementById('formEditPeminjaman')?.addEventListener('submit', async (e) => {
                e.preventDefault();

                const id = document.getElementById('edit_id').value;
                if (!id) {
                    showToast('ID peminjaman tidak ditemukan', 'error');
                    return;
                }

                const barang = [];
                document.querySelectorAll('#editBarangContainer .barang-row').forEach(row => {
                    const idBarang = row.querySelector('[name*="[id]"]')?.value;
                    const jumlah = row.querySelector('[name*="[jumlah]"]')?.value;
                    if (idBarang && jumlah) barang.push({
                        id: parseInt(idBarang),
                        jumlah: parseInt(jumlah)
                    });
                });
                if (barang.length === 0) {
                    showToast('Pilih minimal satu barang', 'error');
                    return;
                }

                const data = {
                    nama_penyewa: document.getElementById('edit_nama_penyewa').value,
                    no_telepon: document.getElementById('edit_no_telepon').value,
                    customer_whatsapp: document.getElementById('edit_no_telepon').value,
                    nama_acara: document.getElementById('edit_nama_acara').value,
                    lokasi_acara: document.getElementById('edit_lokasi_acara').value,
                    tanggal_sewa: document.getElementById('edit_tanggal_sewa').value,
                    tanggal_kembali: document.getElementById('edit_tanggal_kembali').value,
                    waktu_sewa: document.getElementById('edit_waktu_sewa').value,
                    waktu_kembali: document.getElementById('edit_waktu_kembali').value,
                    diskon: document.getElementById('edit_diskon').value,
                    status_pembayaran: document.getElementById('edit_status_pembayaran').value,
                    keterangan: document.getElementById('edit_keterangan').value,
                    total_ppn: document.getElementById('edit_total_ppn').value,
                    grand_total_with_ppn: document.getElementById('edit_grand_total_with_ppn')
                        .value,
                    barang: barang
                };

                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalContent = submitBtn?.innerHTML;
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<i class="fas fa-circle-notch fa-spin mr-2"></i> Menyimpan...';
                }

                try {
                    const response = await fetch(`/peminjaman/${id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(data)
                    });

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server mengembalikan bukan JSON. Status: ' + response.status);
                    }

                    const result = await response.json();
                    if (result.success) {
                        showToast(result.message || 'Data berhasil diperbarui', 'success');
                        closeEditModal();
                        fetchData();
                    } else {
                        showToast(result.message || 'Gagal memperbarui data', 'error');
                    }
                } catch (error) {
                    console.error('Submit edit error:', error);
                    showToast('Gagal mengupdate: ' + error.message, 'error');
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalContent;
                    }
                }
            });

            // ==================== SUBMIT FORM PENGEMBALIAN ====================
            document.getElementById('formPengembalian')?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const id = document.getElementById('pengembalianId').value;
                const formData = new FormData(e.target);
                try {
                    const response = await fetch(`/peminjaman/${id}/pengembalian`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        showToast(result.message, 'success');
                        closePengembalianModal();
                        fetchData();
                    } else {
                        showToast(result.message, 'error');
                    }
                } catch (error) {
                    showToast('Gagal memproses', 'error');
                }
            });

            // ==================== DROPZONE PENGEMBALIAN ====================
            const dropzone = document.getElementById('dropzonePengembalian'),
                fileInput = document.getElementById('fotoPengembalian'),
                preview = document.getElementById('previewPengembalian'),
                previewImg = document.getElementById('previewImgPengembalian');

            if (dropzone && fileInput) {
                dropzone.addEventListener('click', () => fileInput.click());
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('border-gray-500', 'bg-gray-50');
                });
                dropzone.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-gray-500', 'bg-gray-50');
                });
                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-gray-500', 'bg-gray-50');
                    const file = e.dataTransfer.files[0];
                    if (file) {
                        const dt = new DataTransfer();
                        dt.items.add(file);
                        fileInput.files = dt.files;
                        const reader = new FileReader();
                        reader.onload = (ev) => {
                            previewImg.src = ev.target.result;
                            preview.classList.remove('hidden');
                            dropzone.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });
                fileInput.addEventListener('change', (e) => {
                    if (e.target.files?.length) {
                        const reader = new FileReader();
                        reader.onload = (ev) => {
                            previewImg.src = ev.target.result;
                            preview.classList.remove('hidden');
                            dropzone.classList.add('hidden');
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }
        });
    </script>
@endsection