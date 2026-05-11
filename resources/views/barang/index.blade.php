@extends('layouts.app')

@section('title', 'Barang - Multidaya Inti Persada')
@section('page-title', 'Manajemen Barang')
@section('barang-active', 'bg-gray-100 text-gray-800 shadow-sm')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">

        <!-- Header Section: Barang  -->
    <div class="mb-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <!-- Text Content -->
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                    Barang
                </h1>
                <p class="text-slate-500 text-[11px] mt-1 ml-1">
                    Kelola data inventaris dan ketersediaan barang secara real-time.
                </p>
            </div>

            <!-- Action Button: Interactive Edition -->
            <button onclick="openModal()"
                class="group relative overflow-hidden bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2.5 px-5 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 shadow-[0_4px_12px_rgba(79,70,229,0.3)] hover:shadow-[0_8px_20px_rgba(79,70,229,0.4)] flex items-center gap-2 justify-center">

                <!-- Efek Cahaya (Glow Effect on Hover) -->
                <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>

                <i class="fas fa-plus-circle text-xs group-hover:rotate-90 transition-transform duration-300"></i>
                <span>Tambah Barang</span>
            </button>
        </div>
    </div>

            <!-- Stats Cards (Global Statistics): Modern Interactive Edition -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

                <!-- Total Barang -->
                <div class="group bg-white rounded-[2rem] p-5 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Barang</p>
                            </div>
                            <p class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight" id="totalBarang">0</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500 shadow-inner">
                            <i class="fas fa-boxes text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Stok -->
                <div class="group bg-white rounded-[2rem] p-5 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Stok</p>
                            </div>
                            <p class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight" id="totalStok">0</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-all duration-500 shadow-inner">
                            <i class="fas fa-database text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Tersedia -->
                <div class="group bg-white rounded-[2rem] p-5 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-emerald-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tersedia</p>
                            </div>
                            <p class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight" id="totalTersedia">0</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500 shadow-inner">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Disewa -->
                <div class="group bg-white rounded-[2rem] p-5 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-rose-500/5 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Disewa</p>
                            </div>
                            <p class="text-2xl sm:text-3xl font-black text-rose-600 tracking-tight" id="totalDisewa">0</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:bg-rose-500 group-hover:text-white transition-all duration-500 shadow-inner">
                            <i class="fas fa-hand-holding-heart text-xl"></i>
                        </div>
                    </div>
                </div>

            </div>


        <!-- Filter & Search Section: Modern Interactive -->
        <div class="bg-white/80 backdrop-blur-md rounded-[2rem] shadow-sm border border-slate-100 p-6 mb-8 transition-all hover:shadow-md">
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Filter Kategori -->
                <div class="flex-1 group">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fas fa-filter mr-1.5"></i> Kategori
                    </label>
                    <div class="relative">
                        <select id="filterJenis"
                            class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 appearance-none focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer">
                            <option value="all">Semua Barang</option>
                            <option value="Proyektor">Proyektor</option>
                            <option value="Layar">Layar</option>
                            <option value="TV">TV</option>
                            <option value="Kabel">Kabel</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>

                <!-- Sort / Urutan -->
                <div class="flex-1 group">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fas fa-sort-amount-down mr-1.5"></i> Urutan
                    </label>
                    <div class="relative">
                        <select id="filterSort"
                            class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 appearance-none focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all cursor-pointer">
                            <option value="default">Default (Terbaru)</option>
                            <option value="name_asc">Nama A-Z</option>
                            <option value="name_desc">Nama Z-A</option>
                            <option value="price_asc">Harga Terendah</option>
                            <option value="price_desc">Harga Tertinggi</option>
                            <option value="stock_asc">Stok Sedikit</option>
                            <option value="stock_desc">Stok Terbanyak</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <i class="fas fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="flex-[1.5] group">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 group-focus-within:text-indigo-600 transition-colors">
                        <i class="fas fa-search mr-1.5"></i> Pencarian Cepat
                    </label>
                    <div class="relative">
                        <input type="text" id="searchInput"
                            placeholder="Cari nama, kode, atau kategori barang..."
                            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 placeholder:text-slate-400 placeholder:font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <i class="fas fa-search"></i>
                        </div>

                        <!-- Kbd shortcut hint (opsional, hanya untuk tampilan desktop) -->
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 hidden sm:flex items-center gap-1">
                            <span class="px-1.5 py-0.5 border border-slate-200 rounded text-[10px] text-slate-400 font-mono">/</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full">
                    <thead class="bg-gray-50 border-b border-slate-200">
                        <tr>
                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Gambar
                            </th>
                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Kode</th>
                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Nama
                                Barang</th>
                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Jenis
                            </th>
                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Harga
                            </th>
                            <th class="px-3 sm:px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Stok
                            </th>
                            <th class="px-3 sm:px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">
                                Tersedia</th>
                            <th class="px-3 sm:px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Disewa
                            </th>
                            <th class="px-3 sm:px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="barangTableBody">
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-700"></div>
                                </div>
                                <p class="text-slate-500 mt-2">Memuat data...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="paginationContainer" class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-slate-200"></div>
        </div>
    </div>

    <!-- Modal Form Tambah/Edit Barang -->
<!-- Modal Form Tambah/Edit Barang -->
<div id="modalForm" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
    onclick="closeModalOnBackdrop(event)">

    <!-- Perlebar max-w menjadi 2xl atau 3xl agar nyaman saat nyamping -->
    <div class="bg-white rounded-[2.5rem] shadow-2xl max-w-3xl w-full max-h-[95vh] overflow-hidden flex flex-col animate-in fade-in zoom-in duration-200"
        onclick="event.stopPropagation()">

        <!-- Header Tetap -->
        <div class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-slate-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i id="headerIcon" class="fas fa-plus-circle text-lg"></i>
                </div>
                <div>
                    <h3 id="modalTitle" class="text-xl font-black text-slate-800 tracking-tight">Tambah Unit</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Lengkapi data inventaris</p>
                </div>
            </div>
            <button onclick="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-2xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Form dibuat Grid 2 Kolom -->
        <form id="barangForm" class="flex-1 overflow-y-auto custom-scrollbar p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="barangId">

            <!-- KOLOM KIRI: Visual & Deskripsi -->
            <div class="space-y-6">
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Visual Barang</label>
                    <div id="dropzone" class="group border-2 border-dashed border-slate-200 rounded-[2rem] p-6 text-center cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition-all duration-300">
                        <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mx-auto mb-2 group-hover:scale-110 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-all">
                            <i class="fas fa-cloud-upload-alt text-lg"></i>
                        </div>
                        <p class="text-xs font-bold text-slate-600">Klik atau Drop Gambar</p>
                        <input type="file" id="gambar" name="gambar" accept="image/*" class="hidden">
                    </div>

                    <div id="imagePreview" class="hidden relative group">
                        <img id="previewImg" class="w-full h-32 object-cover rounded-[1.5rem] shadow-md border-4 border-white">
                        <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-lg p-1.5 shadow-lg">
                            <i class="fas fa-trash-alt text-[10px]"></i>
                        </button>
                    </div>

                    <div id="currentImage" class="hidden">
                        <div class="relative group">
                            <img id="currentImg" class="w-full h-32 object-cover rounded-[1.5rem] shadow-md border-4 border-indigo-50">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 rounded-[1.5rem] transition-opacity flex items-center justify-center">
                                 <button type="button" onclick="removeCurrentImage()" class="bg-white/20 backdrop-blur-md text-white px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 transition-colors">
                                    Ganti
                                 </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Spesifikasi Detail</label>
                    <textarea name="deskripsi" id="deskripsi" rows="5" placeholder="Detail spesifikasi..."
                        class="w-full mt-2 px-5 py-4 bg-slate-50 border-transparent rounded-[1.5rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-600 text-sm italic"></textarea>
                </div>
            </div>

            <!-- KOLOM KANAN: Detail & Stok -->
            <div class="space-y-6">
                <div class="group">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Perangkat</label>
                    <input type="text" name="nama_barang" id="nama_barang" required placeholder="Nama barang..."
                        class="w-full mt-2 px-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700">
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div class="group">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori</label>
                        <select name="jenis" id="jenis" required
                            class="w-full mt-2 px-4 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700">
                            <option value="">Pilih...</option>
                            <option value="Proyektor">Proyektor</option>
                            <option value="Layar">Layar</option>
                            <option value="TV">TV</option>
                            <option value="Kabel">Kabel</option>
                        </select>
                    </div>
                    <div class="group">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga Sewa</label>
                        <div class="relative mt-2">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">Rp</span>
                            <input type="number" name="harga_sewa" id="harga_sewa" required
                                class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700 text-lg">
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50/50 p-6 rounded-[2rem] border border-indigo-100/50 space-y-4">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="space-y-1 text-center">
                            <label class="text-[9px] font-black text-slate-400 uppercase">Stok</label>
                            <input type="number" name="stok" id="stok" required
                                class="w-full text-center py-2 bg-white rounded-xl border-none font-black text-slate-700">
                        </div>
                        <div class="space-y-1 text-center">
                            <label class="text-[9px] font-black text-slate-400 uppercase">Ready</label>
                            <input type="number" name="tersedia" id="tersedia" required
                                class="w-full text-center py-2 bg-white rounded-xl border-none font-black text-emerald-600">
                        </div>
                        <div class="space-y-1 text-center">
                            <label class="text-[9px] font-black text-slate-400 uppercase">Sewa</label>
                            <input type="number" name="disewa" id="disewa" required
                                class="w-full text-center py-2 bg-white rounded-xl border-none font-black text-blue-600">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <select name="status" id="status" class="bg-white px-3 py-2 rounded-xl text-[10px] font-black uppercase text-slate-600 outline-none">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        <select name="kondisi" id="kondisi" class="bg-white px-3 py-2 rounded-xl text-[10px] font-black uppercase text-slate-600 outline-none">
                            <option value="baik">Baik</option>
                            <option value="sedang">Sedang</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <!-- Footer Actions Tetap -->
        <div class="p-6 bg-white border-t border-slate-100 flex gap-3">
            <button type="submit" form="barangForm"
                class="flex-[2] bg-slate-900 hover:bg-indigo-600 text-white font-black text-[11px] uppercase tracking-[0.2em] py-4 rounded-2xl transition-all shadow-lg active:scale-95">
                Simpan Data
            </button>
            <button type="button" onclick="closeModal()"
                class="flex-1 bg-white border-2 border-slate-100 text-slate-400 hover:text-slate-600 font-black text-[11px] uppercase tracking-widest py-4 rounded-2xl transition-all">
                Batal
            </button>
        </div>
    </div>
</div>

    <!-- Modal Detail Barang -->
    <!-- Modal Detail Barang -->
<div id="modalDetail" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all duration-300"
    onclick="if(event.target===this) closeDetailModal()">

    <div class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full max-h-[85vh] overflow-hidden flex flex-col animate-in fade-in zoom-in duration-200">
        <!-- Header: Consistent with Peminjaman -->
        <div class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-slate-100 px-8 py-5 flex justify-between items-center z-10">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-box-open text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Detail Informasi Barang</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Manajemen Aset & Inventaris</p>
                </div>
            </div>

            <button onclick="closeDetailModal()"
                class="w-10 h-10 flex items-center justify-center rounded-2xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all active:scale-90">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Content Area: Data akan di-inject ke sini -->
        <div id="detailContent" class="p-8 overflow-y-auto custom-scrollbar bg-slate-50/30">
            <!-- Loading State Placeholder -->
            <div class="flex flex-col items-center justify-center py-12 text-slate-300">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500 mb-4"></div>
                <p class="text-xs font-bold uppercase tracking-widest">Menyiapkan Data...</p>
            </div>
        </div>
    </div>
</div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-4 hidden z-50">
        <div class="flex items-center gap-3">
            <i id="toastIcon" class="text-xl"></i>
            <p id="toastMessage"></p>
        </div>
    </div>

    <style>
        #modalDetail {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        #modalDetail .bg-white {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <script>
        // ==================== VARIABLES ====================
        let currentPage = 1;
        let currentFilters = {
            jenis: 'all',
            sort: 'default',
            search: ''
        };
        let currentImageDeleted = false;

        // ==================== DROPZONE IMAGE UPLOAD ====================
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('gambar');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const currentImageDiv = document.getElementById('currentImage');
        const currentImg = document.getElementById('currentImg');

        function previewImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
                dropzone.classList.add('hidden');
                if (currentImageDiv) currentImageDiv.classList.add('hidden');
                currentImageDeleted = true;
            };
            reader.readAsDataURL(file);
        }

        function removeImage() {
            imagePreview.classList.add('hidden');
            dropzone.classList.remove('hidden');
            fileInput.value = '';
            currentImageDeleted = true;
        }

        function removeCurrentImage() {
            if (confirm('Hapus gambar ini?')) {
                currentImageDiv.classList.add('hidden');
                dropzone.classList.remove('hidden');
                currentImageDeleted = true;
            }
        }

        if (dropzone) {
            dropzone.addEventListener('click', () => fileInput.click());
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('border-gray-500', 'bg-gray-50');
            });
            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('border-gray-500', 'bg-gray-50');
            });
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-gray-500', 'bg-gray-50');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    previewImage(files[0]);
                }
            });
        }

        fileInput?.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                previewImage(e.target.files[0]);
            }
        });

        // ==================== FETCH STATISTIK GLOBAL ====================
        async function fetchGlobalStats() {
            try {
                const response = await fetch('/barang/stats');
                const result = await response.json();

                if (result.success) {
                    document.getElementById('totalBarang').textContent = result.data.total_barang;
                    document.getElementById('totalStok').textContent = result.data.total_stok;
                    document.getElementById('totalTersedia').textContent = result.data.total_tersedia;
                    document.getElementById('totalDisewa').textContent = result.data.total_disewa;
                }
            } catch (error) {
                console.error('Error fetching global stats:', error);
            }
        }

        // ==================== FETCH DATA TABEL (DENGAN FILTER) ====================
        async function fetchData() {
            const params = new URLSearchParams({
                page: currentPage,
                jenis: currentFilters.jenis,
                sort: currentFilters.sort,
                search: currentFilters.search
            });

            try {
                const response = await fetch(`/barang?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const result = await response.json();
                renderTable(result.data);
                renderPagination(result.pagination);
            } catch (error) {
                console.error('Error fetching data:', error);
                showToast('Gagal memuat data', 'error');
            }
        }

        // ==================== RENDER TABLE ====================
        function getIconByJenis(jenis) {
            const icons = {
                'TV': 'fa-tv',
                'Proyektor': 'fa-video',
                'Layar': 'fa-film',
                'Kabel': 'fa-plug'
            };
            return icons[jenis] || 'fa-box';
        }

        function renderTable(data) {
            const tbody = document.getElementById('barangTableBody');

            if (!data || data.length === 0) {
                tbody.innerHTML =
                    `<tr><td colspan="9" class="px-6 py-12 text-center text-slate-500"><i class="fas fa-inbox text-4xl mb-2 block"></i>Belum ada data barang</td></tr>`;
                return;
            }

            tbody.innerHTML = data.map(item => {
                const iconClass = getIconByJenis(item.jenis);
                let imageHtml = '';

                if (item.gambar) {
                    imageHtml =
                        `<img src="/storage/${item.gambar}" class="w-full h-full object-cover" onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas ${iconClass} text-gray-400\'></i>';">`;
                } else {
                    imageHtml = `<i class="fas ${iconClass} text-gray-400"></i>`;
                }

                return `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-3 sm:px-4 py-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden">
                        ${imageHtml}
                    </div>
                </td>
                <td class="px-3 sm:px-4 py-3 text-xs font-mono font-semibold text-slate-700">${escapeHtml(item.kode_barang)}</td>
                <td class="px-3 sm:px-4 py-3 text-sm font-medium text-slate-700">${escapeHtml(item.nama_barang)}</td>
                <td class="px-3 sm:px-4 py-3 text-sm"><span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">${escapeHtml(item.jenis)}</span></td>
                <td class="px-3 sm:px-4 py-3 text-sm font-semibold text-slate-700">${formatRupiah(item.harga_sewa)}</td>
                <td class="px-3 sm:px-4 py-3 text-sm text-center text-slate-600">${item.stok}</td>
                <td class="px-3 sm:px-4 py-3 text-sm text-center text-green-600 font-semibold">${item.tersedia}</td>
                <td class="px-3 sm:px-4 py-3 text-sm text-center text-blue-600 font-semibold">${item.disewa}</td>
                <td class="px-3 sm:px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="openDetailModal(${item.id})" class="text-blue-600 hover:text-blue-800" title="Detail"><i class="fas fa-eye"></i></button>
                        <button onclick="editData(${item.id})" class="text-green-600 hover:text-green-800" title="Edit"><i class="fas fa-edit"></i></button>
                        <button onclick="deleteData(${item.id})" class="text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
             </tr>
        `;
            }).join('');
        }

        // ==================== RENDER PAGINATION ====================
        function renderPagination(pagination) {
            const container = document.getElementById('paginationContainer');
            if (!pagination || pagination.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '<div class="flex justify-center gap-2">';
            for (let i = 1; i <= pagination.last_page; i++) {
                html +=
                    `<button onclick="changePage(${i})" class="px-3 py-1 rounded-lg transition ${i === pagination.current_page ? 'bg-gray-700 text-white' : 'border border-slate-300 text-slate-600 hover:bg-gray-100'}">${i}</button>`;
            }
            html += '</div>';
            container.innerHTML = html;
        }

        // ==================== HELPER FUNCTIONS ====================
        function formatRupiah(angka) {
            if (angka === null || angka === undefined) return 'Rp 0';

            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                // Set bagian ini ke 0 untuk menghapus angka di belakang koma
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(angka);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toastIcon');
            const msgEl = document.getElementById('toastMessage');

            icon.className = type === 'success' ? 'fas fa-check-circle text-green-500 text-xl' :
                'fas fa-exclamation-circle text-red-500 text-xl';
            msgEl.textContent = message;

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // ==================== CRUD OPERATIONS ====================
        function openModal(id = null) {
            const modal = document.getElementById('modalForm');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            imagePreview.classList.add('hidden');
            currentImageDiv.classList.add('hidden');
            dropzone.classList.remove('hidden');
            fileInput.value = '';
            currentImageDeleted = false;

            if (id) {
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Unit';
            } else {
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle mr-2"></i>Tambah Unit';
                document.getElementById('barangForm').reset();
                document.getElementById('barangId').value = '';
            }
        }

        function closeModal() {
            document.getElementById('modalForm').classList.add('hidden');
            document.getElementById('modalForm').classList.remove('flex');
            document.getElementById('barangForm').reset();
            imagePreview.classList.add('hidden');
            dropzone.classList.remove('hidden');
            currentImageDiv.classList.add('hidden');
        }

        function closeModalOnBackdrop(event) {
            if (event.target === event.currentTarget) {
                closeModal();
            }
        }

        // ==================== MODAL DETAIL FUNCTIONS ====================
        // ==================== MODAL DETAIL FUNCTIONS ====================
function openDetailModal(id) {
    // Tampilkan loading state sederhana sebelum fetch selesai
    const content = document.getElementById('detailContent');
    content.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500 mb-4"></div>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Mengambil Data...</p>
        </div>
    `;

    // Buka modal dulu (agar user tau ada proses)
    document.getElementById('modalDetail').classList.remove('hidden');
    document.getElementById('modalDetail').classList.add('flex');

    fetch(`/barang/${id}`)
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                const data = result.data;

                // Tentukan Status Badge
                let statusClass = '';
                let statusText = '';
                if (data.disewa > 0) {
                    statusClass = 'bg-blue-50 text-blue-600 border border-blue-100';
                    statusText = `Disewa (${data.disewa} unit)`;
                } else if (data.tersedia > 0) {
                    statusClass = 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                    statusText = 'Tersedia';
                } else {
                    statusClass = 'bg-rose-50 text-rose-600 border border-rose-100';
                    statusText = 'Stok Habis';
                }

                // Inject UI Modern ke dalam detailContent
                content.innerHTML = `
                    <div class="flex flex-col md:flex-row gap-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <!-- Sisi Kiri: Visual -->
                        <div class="w-full md:w-1/3 flex flex-col items-center">
                            <div class="w-full aspect-square rounded-[2.5rem] bg-white border border-slate-100 shadow-sm overflow-hidden flex items-center justify-center p-2">
                                ${data.gambar
                                    ? `<img src="/storage/${data.gambar}" class="w-full h-full object-cover rounded-[2rem]">`
                                    : `<div class="w-full h-full bg-slate-50 rounded-[2rem] flex items-center justify-center">
                                         <i class="fas ${getIconByJenis(data.jenis)} text-5xl text-slate-200"></i>
                                       </div>`}
                            </div>
                            <div class="mt-6">
                                <span class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest ${statusClass}">
                                    ${statusText}
                                </span>
                            </div>
                        </div>

                        <!-- Sisi Kanan: Informasi -->
                        <div class="w-full md:w-2/3 space-y-6">
                            <div>
                                <h4 class="text-2xl font-black text-slate-800 tracking-tight leading-none">${data.nama_barang}</h4>
                                <div class="flex items-center gap-2 mt-3">
                                    <span class="text-indigo-600 font-mono font-bold text-xs bg-indigo-50 px-2 py-0.5 rounded">${data.kode_barang}</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-tighter">${data.jenis}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Harga Sewa</p>
                                    <p class="text-lg font-black text-indigo-600">${formatRupiah(data.harga_sewa)}</p>
                                </div>
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Unit</p>
                                    <p class="text-lg font-black text-slate-700">${data.stok} <span class="text-[10px] text-slate-300 uppercase">Aset</span></p>
                                </div>
                            </div>

                            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <i class="fas fa-quote-right text-3xl"></i>
                                </div>
                                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-3">Deskripsi & Spesifikasi</p>
                                <p class="text-sm text-slate-600 leading-relaxed font-medium italic">
                                    ${data.deskripsi || 'Tidak ada spesifikasi tambahan untuk item ini.'}
                                </p>
                            </div>

                            <div class="flex items-center gap-8 px-2">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Ready</span>
                                    <span class="text-sm font-black text-emerald-600">${data.tersedia}</span>
                                </div>
                                <div class="h-8 w-px bg-slate-100"></div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">On Rental</span>
                                    <span class="text-sm font-black text-blue-600">${data.disewa}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Simpan ID di modal untuk keperluan tombol Edit
                document.getElementById('modalDetail').setAttribute('data-id', data.id);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="text-center py-12">
                    <i class="fas fa-exclamation-circle text-rose-500 text-3xl mb-3"></i>
                    <p class="text-sm font-bold text-slate-700">Gagal mengambil data detail</p>
                    <p class="text-xs text-slate-400 mt-1">Silakan coba beberapa saat lagi</p>
                </div>
            `;
        });
}

function closeDetailModal() {
    const modal = document.getElementById('modalDetail');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

        function editFromDetail() {
            const id = document.getElementById('modalDetail').getAttribute('data-id');
            closeDetailModal();
            editData(parseInt(id));
        }

        async function editData(id) {
            try {
                const response = await fetch(`/barang/${id}`);
                const result = await response.json();

                if (result.success) {
                    const data = result.data;
                    document.getElementById('barangId').value = data.id;
                    document.getElementById('nama_barang').value = data.nama_barang;
                    document.getElementById('jenis').value = data.jenis;
                    document.getElementById('stok').value = data.stok;
                    document.getElementById('tersedia').value = data.tersedia;
                    document.getElementById('disewa').value = data.disewa;
                    document.getElementById('harga_sewa').value = data.harga_sewa;
                    document.getElementById('status').value = data.status;
                    document.getElementById('deskripsi').value = data.deskripsi || '';

                    // Tampilkan gambar saat ini jika ada
                    if (data.gambar) {
                        currentImg.src = `/storage/${data.gambar}`;
                        currentImageDiv.classList.remove('hidden');
                        dropzone.classList.add('hidden');
                        imagePreview.classList.add('hidden');
                    } else {
                        currentImageDiv.classList.add('hidden');
                        dropzone.classList.remove('hidden');
                    }

                    openModal(id);
                }
            } catch (error) {
                showToast('Gagal mengambil data', 'error');
            }
        }

        // ==================== DELETE POPUP ====================
        async function deleteData(id) {
            const result = await Swal.fire({
                title: 'Hapus Data?',
                text: "Data ini tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#374151',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',

                // --- PENGATURAN UKURAN ---
                width: '320px', // Ukuran lebih kecil & compact
                padding: '1.25rem',
                iconColor: '#f8bb86',
                // -------------------------

                customClass: {
                    popup: 'rounded-xl shadow-lg', // Corner lebih rapi
                    title: 'text-lg font-bold pt-2', // Ukuran teks judul diperkecil
                    htmlContainer: 'text-sm', // Ukuran teks deskripsi diperkecil
                    confirmButton: 'text-sm py-2 px-4',
                    cancelButton: 'text-sm py-2 px-4'
                }
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/barang/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Notifikasi sukses juga dibuat kecil & dipojok (Toast)
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Data berhasil dihapus'
                        });

                        fetchData();
                        fetchGlobalStats();
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: error.message,
                        width: '320px'
                    });
                }
            }
    }

        // ==================== FORM SUBMISSION ====================
        document.getElementById('barangForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const id = document.getElementById('barangId').value;
            const formData = new FormData(e.target);

            // Validasi stok
            const stok = parseInt(formData.get('stok'));
            const tersedia = parseInt(formData.get('tersedia'));
            const disewa = parseInt(formData.get('disewa'));

            if (tersedia + disewa > stok) {
                showToast('Jumlah tersedia + disewa tidak boleh melebihi stok', 'error');
                return;
            }

            if (id) {
                formData.append('_method', 'PUT');
            }

            const url = id ? `/barang/${id}` : '/barang';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showToast(result.message, 'success');
                    closeModal();
                    fetchGlobalStats();
                    fetchData();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal menyimpan data', 'error');
            }
        });

        // ==================== FILTER HANDLERS ====================
        document.getElementById('filterJenis').addEventListener('change', (e) => {
            currentFilters.jenis = e.target.value;
            currentPage = 1;
            fetchData();
        });

        document.getElementById('filterSort').addEventListener('change', (e) => {
            currentFilters.sort = e.target.value;
            currentPage = 1;
            fetchData();
        });

        document.getElementById('searchInput').addEventListener('input', (e) => {
            currentFilters.search = e.target.value;
            currentPage = 1;
            fetchData();
        });

        function changePage(page) {
            currentPage = page;
            fetchData();
        }

        // ==================== INITIALIZE ====================
        fetchGlobalStats();
        fetchData();
    </script>
@endsection
