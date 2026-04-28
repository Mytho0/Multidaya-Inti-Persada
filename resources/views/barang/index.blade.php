@extends('layouts.app')

@section('title', 'Barang - Multidaya Inti Persada')
@section('page-title', 'Manajemen Barang')
@section('barang-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Barang</h1>
                    <p class="text-slate-500 text-sm mt-1">Kelola data inventaris barang</p>
                </div>
                <button onclick="openModal()"
                    class="bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition flex items-center gap-2 w-full sm:w-auto justify-center">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Barang</span>
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-6 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-filter mr-2"></i>Tampilkan
                    </label>
                    <select id="filterJenis"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 bg-white">
                        <option value="all">Semua Barang</option>
                        <option value="Proyektor">Proyektor</option>
                        <option value="Layar">Layar</option>
                        <option value="TV">TV</option>
                        <option value="Kabel">Kabel</option>
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-sort mr-2"></i>Urutkan
                    </label>
                    <select id="filterSort"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 bg-white">
                        <option value="default">Default (Terbaru)</option>
                        <option value="name_asc">Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                        <option value="price_asc">Harga Terendah</option>
                        <option value="price_desc">Harga Tertinggi</option>
                        <option value="stock_asc">Stok Sedikit</option>
                        <option value="stock_desc">Stok Terbanyak</option>
                    </select>
                </div>

                <div class="flex-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-search mr-2"></i>Cari
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="searchInput" placeholder="Cari nama barang, kode, atau jenis..."
                            class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards (Global Statistics) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-slate-500">Total Barang</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-800" id="totalBarang">0</p>
                    </div>
                    <i class="fas fa-boxes text-2xl sm:text-3xl text-gray-400"></i>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-slate-500">Total Stok</p>
                        <p class="text-xl sm:text-2xl font-bold text-slate-800" id="totalStok">0</p>
                    </div>
                    <i class="fas fa-database text-2xl sm:text-3xl text-gray-400"></i>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-slate-500">Tersedia</p>
                        <p class="text-xl sm:text-2xl font-bold text-green-600" id="totalTersedia">0</p>
                    </div>
                    <i class="fas fa-check-circle text-2xl sm:text-3xl text-green-400"></i>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-slate-500">Disewa</p>
                        <p class="text-xl sm:text-2xl font-bold text-blue-600" id="totalDisewa">0</p>
                    </div>
                    <i class="fas fa-hand-holding-usd text-2xl sm:text-3xl text-blue-400"></i>
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
    <div id="modalForm" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
        onclick="closeModalOnBackdrop(event)">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto"
            onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitle" class="text-xl font-bold text-slate-800">Tambah Unit</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="barangForm" class="p-6" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="barangId">

                <!-- Upload Image Area -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Barang</label>
                    <div id="dropzone"
                        class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center cursor-pointer hover:border-gray-500 transition">
                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-2"></i>
                        <p class="text-slate-500 text-sm">Drop gambar di sini, atau</p>
                        <p class="text-gray-600 font-semibold text-sm">Klik untuk pilih file</p>
                        <input type="file" id="gambar" name="gambar" accept="image/*" class="hidden">
                        <p class="text-xs text-slate-400 mt-2">Format: JPG, PNG (Max 1MB)</p>
                    </div>
                    <div id="imagePreview" class="hidden mt-3 relative">
                        <img id="previewImg" class="w-full h-32 object-cover rounded-lg">
                        <button type="button" onclick="removeImage()"
                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                    <div id="currentImage" class="hidden mt-3">
                        <p class="text-xs text-slate-500 mb-1">Gambar saat ini:</p>
                        <div class="relative">
                            <img id="currentImg" class="w-full h-32 object-cover rounded-lg">
                            <button type="button" onclick="removeCurrentImage()"
                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Nama Barang -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang *</label>
                    <input type="text" name="nama_barang" id="nama_barang" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>

                <!-- Jenis -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis *</label>
                    <select name="jenis" id="jenis" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <option value="">Pilih Jenis</option>
                        <option value="Proyektor">Proyektor</option>
                        <option value="Layar">Layar</option>
                        <option value="TV">TV</option>
                        <option value="Kabel">Kabel</option>
                    </select>
                </div>

                <!-- Status & Kondisi -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                        <select name="status" id="status"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kondisi</label>
                        <select name="kondisi" id="kondisi"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <option value="baik">Baik</option>
                            <option value="sedang">Sedang</option>
                            <option value="rusak">Rusak</option>
                        </select>
                    </div>
                </div>

                <!-- Stok, Tersedia, Disewa -->
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Stok *</label>
                        <input type="number" name="stok" id="stok" required min="0" value="0"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tersedia *</label>
                        <input type="number" name="tersedia" id="tersedia" required min="0" value="0"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Disewa *</label>
                        <input type="number" name="disewa" id="disewa" required min="0" value="0"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>

                <!-- Harga Sewa -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Sewa (Rp) *</label>
                    <input type="number" name="harga_sewa" id="harga_sewa" required min="0"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>

                <!-- Spesifikasi -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Spesifikasi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500"
                        placeholder="Masukkan spesifikasi barang..."></textarea>
                </div>

                <!-- Tombol -->
                <div class="flex gap-3 pt-4 border-t border-slate-200">
                    <button type="submit"
                        class="flex-1 bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Simpan
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="flex-1 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition py-2">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Barang -->
    <div id="modalDetail" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
        onclick="closeDetailModalOnBackdrop(event)">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto"
            onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 id="detailNamaBarang" class="text-xl font-bold text-slate-800"></h3>
                        <p id="detailStatus" class="text-sm mt-1"></p>
                    </div>
                    <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Gambar Barang -->
                <div class="flex justify-center mb-6">
                    <div id="detailImageContainer"
                        class="w-32 h-32 bg-gray-100 rounded-2xl flex items-center justify-center overflow-hidden">
                        <img id="detailImage" class="w-full h-full object-cover hidden">
                        <i id="detailIcon" class="fas fa-tv text-5xl text-gray-400"></i>
                    </div>
                </div>

                <!-- Informasi Barang -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">Jenis</span>
                        <span id="detailJenis" class="text-slate-800 font-medium text-sm"></span>
                    </div>

                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">Kode Barang</span>
                        <span id="detailKode" class="text-slate-800 font-medium text-sm font-mono"></span>
                    </div>

                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">Harga Sewa</span>
                        <span id="detailHarga" class="text-slate-800 font-bold text-sm"></span>
                    </div>

                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">Stok</span>
                        <span id="detailStok" class="text-slate-800 font-medium text-sm"></span>
                    </div>

                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">Tersedia</span>
                        <span id="detailTersedia" class="text-green-600 font-medium text-sm"></span>
                    </div>

                    <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">Disewa</span>
                        <span id="detailDisewa" class="text-blue-600 font-medium text-sm"></span>
                    </div>

                    <div class="pt-2">
                        <span class="text-slate-500 text-sm block mb-2">Spesifikasi</span>
                        <p id="detailSpesifikasi" class="text-slate-700 text-sm leading-relaxed"></p>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-3 mt-8 pt-4 border-t border-slate-200">
                    <button onclick="editFromDetail()"
                        class="flex-1 bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Edit
                    </button>
                    <button onclick="closeDetailModal()"
                        class="flex-1 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition py-2">
                        Batal
                    </button>
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
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
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
        function openDetailModal(id) {
            fetch(`/barang/${id}`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const data = result.data;

                        document.getElementById('detailNamaBarang').textContent = data.nama_barang;
                        document.getElementById('detailKode').textContent = data.kode_barang;
                        document.getElementById('detailJenis').textContent = data.jenis;
                        document.getElementById('detailHarga').textContent = formatRupiah(data.harga_sewa);
                        document.getElementById('detailStok').textContent = data.stok;
                        document.getElementById('detailTersedia').textContent = data.tersedia;
                        document.getElementById('detailDisewa').textContent = data.disewa;
                        document.getElementById('detailSpesifikasi').textContent = data.deskripsi ||
                            'Tidak ada spesifikasi';

                        // Set status badge
                        const statusBadge = document.getElementById('detailStatus');
                        let statusClass = '';
                        let statusText = '';

                        if (data.disewa > 0) {
                            statusClass = 'bg-blue-100 text-blue-700';
                            statusText = `Status : Disewa (${data.disewa} unit)`;
                        } else if (data.tersedia > 0) {
                            statusClass = 'bg-green-100 text-green-700';
                            statusText = 'Status : Tersedia';
                        } else if (data.stok === 0) {
                            statusClass = 'bg-red-100 text-red-700';
                            statusText = 'Status : Habis';
                        } else {
                            statusClass = 'bg-yellow-100 text-yellow-700';
                            statusText = 'Status : Tersedia Terbatas';
                        }

                        statusBadge.innerHTML =
                            `<span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold ${statusClass}">${statusText}</span>`;

                        // Set gambar
                        const detailImg = document.getElementById('detailImage');
                        const detailIcon = document.getElementById('detailIcon');

                        if (data.gambar) {
                            detailImg.src = `/storage/${data.gambar}`;
                            detailImg.classList.remove('hidden');
                            detailIcon.classList.add('hidden');
                        } else {
                            detailImg.classList.add('hidden');
                            detailIcon.classList.remove('hidden');
                            detailIcon.className = `fas ${getIconByJenis(data.jenis)} text-5xl text-gray-400`;
                        }

                        document.getElementById('modalDetail').setAttribute('data-id', data.id);
                        document.getElementById('modalDetail').classList.remove('hidden');
                        document.getElementById('modalDetail').classList.add('flex');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Gagal mengambil data detail', 'error');
                });
        }

        function closeDetailModal() {
            document.getElementById('modalDetail').classList.add('hidden');
            document.getElementById('modalDetail').classList.remove('flex');
        }

        function closeDetailModalOnBackdrop(event) {
            if (event.target === event.currentTarget) {
                closeDetailModal();
            }
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

        async function deleteData(id) {
            if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                try {
                    const response = await fetch(`/barang/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const result = await response.json();

                    if (result.success) {
                        showToast(result.message, 'success');
                        fetchGlobalStats();
                        fetchData();
                    } else {
                        showToast(result.message, 'error');
                    }
                } catch (error) {
                    showToast('Gagal menghapus data', 'error');
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
