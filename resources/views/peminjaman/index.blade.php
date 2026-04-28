@extends('layouts.app')

@section('title', 'Peminjaman - Multidaya Inti Persada')
@section('page-title', 'Data Peminjaman')
@section('peminjaman-active', 'bg-gray-100 text-gray-800 shadow-sm')

@section('main-content')
    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-800">Peminjaman</h1>
                    <p class="text-slate-500 text-sm mt-1">Kelola data peminjaman barang</p>
                </div>
                <button onclick="openTambahModal()"
                    class="bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition flex items-center gap-2 w-full sm:w-auto justify-center">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Peminjaman</span>
                </button>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-slate-200">
            <div class="flex gap-1">
                <button onclick="switchTab('aktif')" id="tabAktifBtn"
                    class="py-3 px-6 font-semibold transition-all border-b-2 border-gray-700 text-gray-700">
                    <i class="fas fa-clock mr-2"></i>Sewa Aktif
                    <span id="badgeAktif" class="ml-2 bg-gray-200 text-gray-700 text-xs px-2 py-0.5 rounded-full">0</span>
                </button>
                <button onclick="switchTab('riwayat')" id="tabRiwayatBtn"
                    class="py-3 px-6 font-semibold transition-all border-b-2 border-transparent text-slate-500 hover:text-gray-700">
                    <i class="fas fa-history mr-2"></i>Riwayat Sewa
                    <span id="badgeRiwayat" class="ml-2 bg-gray-200 text-gray-700 text-xs px-2 py-0.5 rounded-full">0</span>
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-6 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-search mr-2"></i>Cari
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="searchInput" placeholder="Cari invoice, nama penyewa, atau telepon..."
                            class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>
                <div class="w-48">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-sort mr-2"></i>Urutkan
                    </label>
                    <select id="filterSort"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <option value="default">Default (Terbaru)</option>
                        <option value="name_asc">Nama A-Z</option>
                        <option value="name_desc">Nama Z-A</option>
                        <option value="date_asc">Tanggal Terlama</option>
                        <option value="date_desc">Tanggal Terbaru</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div id="loadingIndicator" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
            <div class="bg-white rounded-lg p-6 flex items-center gap-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-700"></div>
                <span>Memuat data...</span>
            </div>
        </div>

        <!-- Table List Peminjaman -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full text-sm">
                    <thead class="bg-gray-50 border-b border-slate-200">
                        <tr>
                            <th class="w-28 px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase">ID/Invoice
                            </th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Penyewa</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Barang</th>
                            <th class="w-24 px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tgl Sewa
                            </th>
                            <th class="w-24 px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Tgl Kembali
                            </th>
                            <th class="w-28 px-3 py-3 text-right text-xs font-semibold text-slate-600 uppercase">Total</th>
                            <th class="w-20 px-3 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Status
                            </th>
                            <th class="w-44 px-3 py-3 text-center text-xs font-semibold text-slate-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="peminjamanTableBody">
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-700"></div>
                                </div>
                                <p class="text-slate-500 mt-2">Memuat data...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="paginationContainer" class="px-4 py-4 bg-gray-50 border-t border-slate-200"></div>
        </div>
    </div>

    <!-- Modal Tambah Peminjaman -->
    <div id="modalTambah" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
        onclick="if(event.target===this) closeTambahModal()">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800"><i class="fas fa-plus-circle mr-2"></i>Tambah Peminjaman</h3>
                <button onclick="closeTambahModal()" class="text-slate-400 hover:text-slate-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>

            <!-- Tombol Cek Pelanggan -->
            <div class="px-6 pt-4">
                <button onclick="openCekPelangganModal()" type="button"
                    class="w-full border-2 border-dashed border-gray-400 bg-gray-50 hover:bg-gray-100 text-gray-600 py-2 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Cek Pelanggan Berdasarkan Nama/No Telepon
                </button>
                <div class="text-center my-2 text-xs text-slate-400">atau</div>
            </div>

            <form id="formPeminjaman" class="p-6">
                @csrf
                <input type="hidden" id="pelanggan_id" name="pelanggan_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div><label class="block text-sm font-semibold mb-2">Kode Peminjaman</label><input type="text"
                            id="kode_peminjaman" class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly
                            placeholder="Auto generate"></div>
                    <div><label class="block text-sm font-semibold mb-2">Nama Penyewa *</label><input type="text"
                            name="nama_penyewa" id="nama_penyewa" required class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">No Telepon *</label><input type="text"
                            name="no_telepon" id="no_telepon" required class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Email (Opsional)</label><input type="email"
                            name="email" id="email" class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Alamat (Opsional)</label>
                        <textarea name="alamat" id="alamat" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                    </div>
                    <div><label class="block text-sm font-semibold mb-2">Tipe Pelanggan</label><select
                            name="tipe_pelanggan" id="tipe_pelanggan" class="w-full px-4 py-2 border rounded-lg">
                            <option value="perorangan">Perorangan</option>
                            <option value="perusahaan">Perusahaan</option>
                        </select></div>
                    <div><label class="block text-sm font-semibold mb-2">Nama Acara</label><input type="text"
                            name="nama_acara" id="nama_acara" class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Lokasi Acara</label><input type="text"
                            name="lokasi_acara" id="lokasi_acara" class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Tanggal Sewa *</label><input type="date"
                            name="tanggal_sewa" id="tanggal_sewa" required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div><label class="block text-sm font-semibold mb-2">Tanggal Kembali *</label><input type="date"
                            name="tanggal_kembali" id="tanggal_kembali" required
                            class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Waktu Sewa *</label><input type="time"
                            name="waktu_sewa" id="waktu_sewa" required class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Waktu Kembali *</label><input type="time"
                            name="waktu_kembali" id="waktu_kembali" required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2">Pilih Barang</label>
                    <div id="barangContainer" class="space-y-2">
                        <div class="flex gap-2 items-center barang-row">
                            <select name="barang[0][id]" class="barang-select flex-1 px-4 py-2 border rounded-lg">
                                <option value="">Pilih Barang</option>
                            </select>
                            <input type="number" name="barang[0][jumlah]" placeholder="Jml"
                                class="w-20 px-4 py-2 border rounded-lg" value="1">
                            <button type="button" onclick="removeBarang(this)"
                                class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <button type="button" onclick="addBarang()"
                        class="mt-2 text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-plus"></i> Tambah
                        Barang</button>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div><label class="block text-sm font-semibold mb-2">Diskon</label><input type="number"
                            name="diskon" id="diskon" value="0" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div><label class="block text-sm font-semibold mb-2">Status Pembayaran</label><select
                            name="status_pembayaran" class="w-full px-4 py-2 border rounded-lg">
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="dp">DP</option>
                            <option value="lunas">Lunas</option>
                        </select></div>
                </div>

                <div class="mb-4"><label class="block text-sm font-semibold mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>

                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="flex-1 bg-gray-700 text-white py-2 rounded-lg">Simpan</button>
                    <button type="button" onclick="closeTambahModal()"
                        class="flex-1 border rounded-lg py-2">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Peminjaman -->
    <div id="modalEdit" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
        onclick="if(event.target===this) closeEditModal()">
        <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800"><i class="fas fa-edit mr-2"></i>Edit Peminjaman</h3>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form id="formEditPeminjaman" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id" name="id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div><label class="block text-sm font-semibold mb-2">Kode Peminjaman</label><input type="text"
                            id="edit_invoice_number" class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                    </div>
                    <div><label class="block text-sm font-semibold mb-2">Nama Penyewa *</label><input type="text"
                            name="nama_penyewa" id="edit_nama_penyewa" required
                            class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">No Telepon *</label><input type="text"
                            name="no_telepon" id="edit_no_telepon" required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div><label class="block text-sm font-semibold mb-2">Nama Acara</label><input type="text"
                            name="nama_acara" id="edit_nama_acara" class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Lokasi Acara</label><input type="text"
                            name="lokasi_acara" id="edit_lokasi_acara" class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Tanggal Sewa *</label><input type="date"
                            name="tanggal_sewa" id="edit_tanggal_sewa" required
                            class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Tanggal Kembali *</label><input type="date"
                            name="tanggal_kembali" id="edit_tanggal_kembali" required
                            class="w-full px-4 py-2 border rounded-lg"></div>
                    <div><label class="block text-sm font-semibold mb-2">Waktu Sewa *</label><input type="time"
                            name="waktu_sewa" id="edit_waktu_sewa" required class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div><label class="block text-sm font-semibold mb-2">Waktu Kembali *</label><input type="time"
                            name="waktu_kembali" id="edit_waktu_kembali" required
                            class="w-full px-4 py-2 border rounded-lg"></div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-2">Pilih Barang</label>
                    <div id="editBarangContainer" class="space-y-2"></div>
                    <button type="button" onclick="addEditBarang()"
                        class="mt-2 text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-plus"></i> Tambah
                        Barang</button>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div><label class="block text-sm font-semibold mb-2">Diskon</label><input type="number"
                            name="diskon" id="edit_diskon" value="0" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div><label class="block text-sm font-semibold mb-2">Status Pembayaran</label><select
                            name="status_pembayaran" id="edit_status_pembayaran"
                            class="w-full px-4 py-2 border rounded-lg">
                            <option value="belum_bayar">Belum Bayar</option>
                            <option value="dp">DP</option>
                            <option value="lunas">Lunas</option>
                        </select></div>
                </div>

                <div class="mb-4"><label class="block text-sm font-semibold mb-2">Keterangan</label>
                    <textarea name="keterangan" id="edit_keterangan" rows="2" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>

                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="flex-1 bg-gray-700 text-white py-2 rounded-lg">Update</button>
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 border rounded-lg py-2">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Cek Pelanggan -->
    <div id="modalCekPelanggan" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
        onclick="if(event.target===this) closeCekPelangganModal()">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800"><i class="fas fa-search mr-2"></i>Cek Pelanggan</h3>
                <button onclick="closeCekPelangganModal()" class="text-slate-400 hover:text-slate-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6">
                <!-- Search Input -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Cari Nama Pelanggan atau Nomor
                        Telepon</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="searchPelanggan" placeholder="Ketik nama atau nomor telepon..."
                            class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500"
                            autocomplete="off">
                    </div>
                    <div id="autocompleteDropdown"
                        class="hidden absolute z-10 w-80 mt-1 bg-white border border-slate-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    </div>
                </div>

                <!-- Hasil Pencarian -->
                <div id="hasilCekPelanggan" class="hidden">
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg" id="hasilNama"></h4>
                                <p class="text-sm text-slate-500" id="hasilTelepon"></p>
                                <p class="text-sm text-slate-500" id="hasilEmail"></p>
                            </div>
                            <span id="hasilStatus" class="px-2 py-1 rounded-full text-xs font-semibold"></span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                            <div><span class="text-slate-500">Total Transaksi:</span> <strong
                                    id="hasilTotalTransaksi">0</strong></div>
                            <div><span class="text-slate-500">Total Nilai:</span> <strong id="hasilTotalNilai">Rp
                                    0</strong></div>
                        </div>
                        <div id="riwayatContainer" class="mt-3">
                            <p class="text-sm font-semibold text-slate-700 mb-2">Riwayat Peminjaman Terakhir:</p>
                            <div id="riwayatList" class="space-y-2 max-h-40 overflow-y-auto"></div>
                        </div>
                        <div class="mt-4 flex gap-2">
                            <button onclick="useExistingCustomer()"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg"><i
                                    class="fas fa-check"></i> Gunakan Data Ini</button>
                            <button onclick="openNewCustomerForm()"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg"><i
                                    class="fas fa-plus"></i> Pelanggan Baru</button>
                        </div>
                    </div>
                </div>

                <div id="pelangganNotFound" class="hidden text-center py-8">
                    <i class="fas fa-user-slash text-5xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 mb-4">Pelanggan tidak ditemukan</p>
                    <div id="suggestionsContainer" class="mb-4"></div>
                    <button onclick="openNewCustomerForm()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"><i class="fas fa-plus"></i>
                        Daftar sebagai Pelanggan Baru</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="modalDetail" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
        onclick="if(event.target===this) closeDetailModal()">
        <div class="bg-white rounded-2xl shadow-xl max-w-3xl w-full max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800"><i class="fas fa-info-circle mr-2"></i>Detail Peminjaman</h3>
                <button onclick="closeDetailModal()" class="text-slate-400 hover:text-slate-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <div id="detailContent" class="p-6"></div>
        </div>
    </div>

    <!-- Modal Form Pengembalian -->
    <div id="modalPengembalian" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4"
        onclick="if(event.target===this) closePengembalianModal()">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800"><i class="fas fa-undo-alt mr-2"></i>Form Pengembalian</h3>
                <button onclick="closePengembalianModal()" class="text-slate-400 hover:text-slate-600"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <form id="formPengembalian" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="pengembalianId" name="id">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Barang Kembali</label>
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center cursor-pointer hover:border-gray-500 transition"
                        id="dropzonePengembalian">
                        <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 mb-1"></i>
                        <p class="text-xs text-slate-500">Klik atau drag foto</p>
                        <input type="file" name="foto_pengembalian" id="fotoPengembalian" accept="image/*"
                            class="hidden">
                    </div>
                    <div id="previewPengembalian" class="hidden mt-2"><img id="previewImgPengembalian"
                            class="w-full h-24 object-cover rounded-lg"></div>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kondisi Barang</label>
                    <select name="kondisi_barang" id="kondisiBarang" class="w-full px-3 py-2 border rounded-lg"
                        onchange="toggleKerusakan()">
                        <option value="baik">Baik - Tidak ada masalah</option>
                        <option value="kurang_baik">Kurang Baik - Ada sedikit masalah</option>
                        <option value="rusak">Rusak - Perlu perbaikan</option>
                    </select>
                </div>
                <div id="kerusakanSection" class="mb-3 hidden">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kerusakan</label>
                    <textarea name="kerusakan" id="kerusakan" rows="2" class="w-full px-3 py-2 border rounded-lg"
                        placeholder="Jelaskan kerusakan..."></textarea>
                </div>
                <div id="dendaSection" class="mb-3 hidden">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Biaya Denda / Kerusakan</label>
                    <input type="number" name="biaya_kerusakan" id="biayaKerusakan"
                        class="w-full px-3 py-2 border rounded-lg" placeholder="Nominal denda" value="0">
                    <p class="text-xs text-slate-500 mt-1">*Denda keterlambatan: Rp 50.000/hari</p>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Catatan Pengembalian</label>
                    <textarea name="catatan_pengembalian" id="catatanPengembalian" rows="2"
                        class="w-full px-3 py-2 border rounded-lg"></textarea>
                </div>
                <div class="flex gap-3 pt-3">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition">Proses
                        Pengembalian</button>
                    <button type="button" onclick="closePengembalianModal()"
                        class="flex-1 border rounded-lg py-2">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-3 hidden z-50">
        <div class="flex items-center gap-2"><i id="toastIcon" class="text-lg"></i>
            <p id="toastMessage" class="text-sm"></p>
        </div>
    </div>

    <style>
        .status-badge {
            display: inline-flex;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-aktif {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-selesai {
            background-color: #f3f4f6;
            color: #374151;
        }

        .status-terlambat {
            background-color: #fee2e2;
            color: #991b1b;
        }

        td {
            white-space: nowrap;
        }

        .barang-list {
            max-width: 200px;
            white-space: normal;
            word-break: break-word;
            line-height: 1.3;
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
        let currentSearchValue = '';

        // ==================== HELPER FUNCTIONS ====================
        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        function formatDate(date) {
            return new Date(date).toLocaleDateString('id-ID');
        }

        function formatShortDate(date) {
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
            const badges = {
                aktif: 'status-aktif',
                selesai: 'status-selesai',
                terlambat: 'status-terlambat'
            };
            const texts = {
                aktif: '🟢 DISEWA',
                selesai: '✅ SELESAI',
                terlambat: '🔴 TERLAMBAT'
            };
            return `<span class="status-badge ${badges[status] || 'status-aktif'}">${texts[status] || status}</span>`;
        }

        function showToast(msg, type) {
            const t = document.getElementById('toast');
            if (!t) {
                alert(msg);
                return;
            }
            document.getElementById('toastMessage').textContent = msg;
            t.classList.remove('hidden');
            setTimeout(() => t.classList.add('hidden'), 3000);
        }

        // ==================== BARANG FUNCTIONS ====================
        async function loadBarang() {
            try {
                const response = await fetch('/api/barang-tersedia');
                if (!response.ok) throw new Error('Network error');
                const result = await response.json();
                barangList = result;
                populateBarangSelects();
                if (barangList.length === 0) showToast('Barang tidak tersedia', 'warning');
            } catch (error) {
                console.error('Error loading barang:', error);
                showToast('Gagal memuat data barang', 'error');
            }
        }

        function populateBarangSelects() {
            document.querySelectorAll('.barang-select, #editBarangContainer .barang-select').forEach(select => {
                if (!select) return;
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
            newRow.className = 'flex gap-2 items-center barang-row';
            newRow.innerHTML = `
        <select name="barang[${index}][id]" class="barang-select flex-1 px-3 py-2 border rounded-lg"><option value="">Pilih Barang</option></select>
        <input type="number" name="barang[${index}][jumlah]" placeholder="Jml" class="w-20 px-3 py-2 border rounded-lg" value="1">
        <button type="button" onclick="removeBarang(this)" class="text-red-500"><i class="fas fa-trash"></i></button>
    `;
            container.appendChild(newRow);
            populateBarangSelects();
        }

        function removeBarang(btn) {
            if (document.querySelectorAll('.barang-row').length > 1) btn.closest('.barang-row').remove();
        }

        // ==================== FETCH DATA ====================
        async function fetchData() {
            if (isLoading) return;
            isLoading = true;
            const loadingIndicator = document.getElementById('loadingIndicator');
            if (loadingIndicator) loadingIndicator.classList.remove('hidden');
            try {
                const params = new URLSearchParams({
                    page: currentPage,
                    status: currentTab,
                    sort: currentFilters.sort,
                    search: currentFilters.search,
                    pelanggan: currentFilters.pelanggan
                });
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 15000);
                const response = await fetch(`/peminjaman?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    signal: controller.signal
                });
                clearTimeout(timeoutId);
                const result = await response.json();
                renderTable(result.data);
                renderPagination(result.pagination);
                updateBadges();
            } catch (error) {
                if (error.name === 'AbortError') showToast('Request timeout, silakan coba lagi', 'error');
                else {
                    console.error('Error:', error);
                    showToast('Gagal memuat data', 'error');
                }
            } finally {
                if (loadingIndicator) loadingIndicator.classList.add('hidden');
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
                document.getElementById('badgeAktif').textContent = aktifResult.pagination?.total || 0;
                document.getElementById('badgeRiwayat').textContent = riwayatResult.pagination?.total || 0;
            } catch (e) {
                console.error(e);
            }
        }

        // ==================== RENDER TABLE ====================
        function renderTable(data) {
            const tbody = document.getElementById('peminjamanTableBody');
            if (!tbody) return;
            if (!data || data.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="8" class="px-6 py-12 text-center"><i class="fas fa-inbox text-4xl text-slate-300 mb-2 block"></i>Belum ada</td></tr>';
                return;
            }
            tbody.innerHTML = data.map(item => {
                const barangListStr = item.details?.map(d => d.nama_barang).join(', ') || '-';
                const isAktif = item.status_pengembalian === 'aktif';
                return `<tr class="hover:bg-gray-50 transition border-b border-slate-100">
            <td class="px-3 py-2.5 text-xs font-mono font-semibold">${escapeHtml(item.invoice_number)}</td>
            <td class="px-3 py-2.5 text-sm font-medium">${escapeHtml(item.nama_penyewa)}</td>
            <td class="px-3 py-2.5 text-xs text-slate-600 barang-list max-w-[200px] truncate" title="${escapeHtml(barangListStr)}">${escapeHtml(barangListStr.substring(0, 40))}${barangListStr.length > 40 ? '...' : ''}</td>
            <td class="px-3 py-2.5 text-xs">${formatShortDate(item.tanggal_sewa)}</td>
            <td class="px-3 py-2.5 text-xs">${formatShortDate(item.tanggal_kembali)}</td>
            <td class="px-3 py-2.5 text-xs font-semibold text-right">${formatRupiah(item.grand_total)}</td>
            <td class="px-3 py-2.5 text-center">${getStatusBadge(item.status_pengembalian)}</td>
            <td class="px-3 py-2.5 text-center">
                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                    <button onclick="viewDetail(${item.id})" class="text-blue-600 hover:text-blue-800 p-1" title="Detail"><i class="fas fa-eye text-sm"></i></button>
                    ${isAktif ? `<button onclick="openEditModal(${item.id})" class="text-orange-600 hover:text-orange-800 p-1" title="Edit"><i class="fas fa-edit text-sm"></i></button>` : ''}
                    <button onclick="printInvoice(${item.id})" class="text-gray-600 hover:text-gray-800 p-1" title="Invoice"><i class="fas fa-print text-sm"></i></button>
                    ${isAktif ? `
                            <button onclick="openPengembalianModal(${item.id})" class="text-green-600 hover:text-green-800 p-1" title="Pengembalian"><i class="fas fa-undo-alt text-sm"></i></button>
                            <button onclick="sendPengirimanNotif(${item.id})" class="text-purple-600 hover:text-purple-800 p-1" title="Kirim WhatsApp"><i class="fab fa-whatsapp text-sm"></i></button>
                            <button onclick="sendPengingatNotif(${item.id})" class="text-yellow-600 hover:text-yellow-800 p-1" title="Pengingat"><i class="fas fa-bell text-sm"></i></button>
                        ` : ''}
                    <button onclick="deleteData(${item.id})" class="text-red-600 hover:text-red-800 p-1" title="Hapus"><i class="fas fa-trash text-sm"></i></button>
                </div>
            </td>
        </tr>`;
            }).join('');
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
                if (start > 2) html += `<span class="px-2">...</span>`;
            }
            for (let i = start; i <= end; i++) html +=
                `<button onclick="changePage(${i})" class="px-3 py-1 text-sm rounded-lg ${i === current ? 'bg-gray-700 text-white' : 'border hover:bg-gray-100'}">${i}</button>`;
            if (end < last) {
                if (end < last - 1) html += `<span class="px-2">...</span>`;
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
            document.getElementById('modalTambah').classList.remove('hidden');
            document.getElementById('modalTambah').classList.add('flex');
        }

        function closeTambahModal() {
            document.getElementById('modalTambah').classList.add('hidden');
            document.getElementById('modalTambah').classList.remove('flex');
            document.getElementById('formPeminjaman')?.reset();
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
                    const detailsHtml = data.details.map(d =>
                        `<tr class="border-b"><td class="py-1.5 text-sm">${escapeHtml(d.nama_barang)}</td><td class="py-1.5 text-center text-sm">${d.jumlah}</td><td class="py-1.5 text-right text-sm">${formatRupiah(d.harga_sewa)}</td><td class="py-1.5 text-right text-sm font-semibold">${formatRupiah(d.subtotal)}</td></tr>`
                        ).join('');
                    document.getElementById('detailContent').innerHTML = `
                <div class="grid grid-cols-3 gap-3 mb-4 pb-3 border-b">
                    <div><p class="text-xs text-slate-500">Invoice</p><p class="font-mono font-semibold text-sm">${data.invoice_number}</p></div>
                    <div><p class="text-xs text-slate-500">Status</p>${getStatusBadge(data.status_pengembalian)}</div>
                    <div><p class="text-xs text-slate-500">Penyewa</p><p class="font-semibold text-sm">${escapeHtml(data.nama_penyewa)}</p></div>
                    <div><p class="text-xs text-slate-500">Telepon</p><p class="text-sm">${escapeHtml(data.no_telepon)}</p></div>
                    <div><p class="text-xs text-slate-500">Tanggal Sewa</p><p class="text-sm">${formatDate(data.tanggal_sewa)} | ${data.waktu_sewa}</p></div>
                    <div><p class="text-xs text-slate-500">Tanggal Kembali</p><p class="text-sm">${formatDate(data.tanggal_kembali)} | ${data.waktu_kembali}</p></div>
                </div>
                <div class="mb-3"><p class="font-semibold text-sm mb-2">Detail Barang</p><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="bg-gray-50"><th class="px-2 py-1 text-left">Barang</th><th class="px-2 py-1 text-center w-16">Jml</th><th class="px-2 py-1 text-right w-28">Harga</th><th class="px-2 py-1 text-right w-28">Subtotal</th></tr></thead><tbody>${detailsHtml}</tbody><tfoot><tr class="border-t"><td colspan="3" class="px-2 py-2 text-right font-bold">TOTAL</td><td class="px-2 py-2 text-right font-bold">${formatRupiah(data.grand_total)}</td></tr></tfoot></table></div></div>
            `;
                    document.getElementById('modalDetail').classList.remove('hidden');
                    document.getElementById('modalDetail').classList.add('flex');
                }
            } catch (error) {
                showToast('Gagal mengambil detail', 'error');
            }
        }

        function closeDetailModal() {
            document.getElementById('modalDetail').classList.add('hidden');
            document.getElementById('modalDetail').classList.remove('flex');
        }

        async function deleteData(id) {
            if (confirm('Yakin ingin menghapus peminjaman ini?')) {
                try {
                    const response = await fetch(`/peminjaman/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const result = await response.json();
                    if (result.success) {
                        showToast(result.message, 'success');
                        fetchData();
                    } else {
                        showToast(result.message, 'error');
                    }
                } catch (error) {
                    showToast('Gagal menghapus', 'error');
                }
            }
        }

        // ==================== EDIT FUNCTIONS ====================
        async function openEditModal(id) {
            try {
                const response = await fetch(`/peminjaman/${id}`);
                const result = await response.json();
                if (result.success) {
                    const data = result.data;
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_invoice_number').value = data.invoice_number;
                    document.getElementById('edit_nama_penyewa').value = data.nama_penyewa;
                    document.getElementById('edit_no_telepon').value = data.no_telepon;
                    document.getElementById('edit_nama_acara').value = data.nama_acara || '';
                    document.getElementById('edit_lokasi_acara').value = data.lokasi_acara || '';
                    document.getElementById('edit_tanggal_sewa').value = data.tanggal_sewa;
                    document.getElementById('edit_tanggal_kembali').value = data.tanggal_kembali;
                    document.getElementById('edit_waktu_sewa').value = data.waktu_sewa;
                    document.getElementById('edit_waktu_kembali').value = data.waktu_kembali;
                    document.getElementById('edit_diskon').value = data.diskon;
                    document.getElementById('edit_status_pembayaran').value = data.status_pembayaran;
                    document.getElementById('edit_keterangan').value = data.keterangan || '';
                    const container = document.getElementById('editBarangContainer');
                    container.innerHTML = '';
                    data.details.forEach((detail) => {
                        addEditBarangRow(detail.barang_id, detail.jumlah);
                    });
                    document.getElementById('modalEdit').classList.remove('hidden');
                    document.getElementById('modalEdit').classList.add('flex');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal mengambil data', 'error');
            }
        }

        function closeEditModal() {
            document.getElementById('modalEdit').classList.add('hidden');
            document.getElementById('modalEdit').classList.remove('flex');
        }

        function addEditBarang() {
            addEditBarangRow(null, 1);
        }

        function addEditBarangRow(selectedId = null, jumlah = 1) {
            const container = document.getElementById('editBarangContainer');
            const index = container.children.length;
            const newRow = document.createElement('div');
            newRow.className = 'flex gap-2 items-center barang-row mb-2';
            newRow.innerHTML =
                `<select name="barang[${index}][id]" class="barang-select flex-1 px-3 py-2 border rounded-lg"><option value="">Pilih Barang</option></select><input type="number" name="barang[${index}][jumlah]" placeholder="Jml" class="w-20 px-3 py-2 border rounded-lg" value="${jumlah}"><button type="button" onclick="removeEditBarang(this)" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>`;
            container.appendChild(newRow);
            populateBarangSelects();
            if (selectedId) {
                const select = newRow.querySelector('.barang-select');
                select.value = selectedId;
            }
        }

        function removeEditBarang(btn) {
            if (document.querySelectorAll('#editBarangContainer .barang-row').length > 1) btn.closest('.barang-row')
            .remove();
            else showToast('Minimal harus ada satu barang', 'warning');
        }

        document.getElementById('formEditPeminjaman')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('edit_id').value;
            const barang = [];
            document.querySelectorAll('#editBarangContainer .barang-row').forEach((row) => {
                const barangId = row.querySelector('[name*="[id]"]')?.value;
                const jumlah = row.querySelector('[name*="[jumlah]"]')?.value;
                if (barangId && jumlah) barang.push({
                    id: parseInt(barangId),
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
                barang: barang
            };
            try {
                const response = await fetch(`/peminjaman/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    showToast(result.message, 'success');
                    closeEditModal();
                    fetchData();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal mengupdate data', 'error');
            }
        });

        // ==================== PENGEMBALIAN FUNCTIONS ====================
        function openPengembalianModal(id) {
            document.getElementById('pengembalianId').value = id;
            document.getElementById('modalPengembalian').classList.remove('hidden');
            document.getElementById('modalPengembalian').classList.add('flex');
            resetPengembalianForm();
        }

        function closePengembalianModal() {
            document.getElementById('modalPengembalian').classList.add('hidden');
            document.getElementById('modalPengembalian').classList.remove('flex');
            resetPengembalianForm();
        }

        function resetPengembalianForm() {
            document.getElementById('formPengembalian').reset();
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
        document.getElementById('formPengembalian')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('pengembalianId').value;
            const formData = new FormData(e.target);
            try {
                const response = await fetch(`/peminjaman/${id}/pengembalian`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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

        // ==================== WHATSAPP FUNCTIONS ====================
        async function sendPengirimanNotif(id) {
            if (confirm('Kirim notifikasi pengiriman ke pelanggan?')) {
                try {
                    const response = await fetch(`/peminjaman/${id}/send-pengiriman`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const result = await response.json();
                    if (result.success) showToast(result.message, 'success');
                    else showToast(result.message, 'error');
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Gagal mengirim notifikasi', 'error');
                }
            }
        }
        async function sendPengingatNotif(id) {
            if (confirm('Kirim pengingat pengembalian ke pelanggan?')) {
                try {
                    const response = await fetch(`/peminjaman/${id}/send-pengingat`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    const result = await response.json();
                    if (result.success) showToast(result.message, 'success');
                    else showToast(result.message, 'error');
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Gagal mengirim pengingat', 'error');
                }
            }
        }

        // ==================== FORM SUBMISSIONS ====================
        document.getElementById('formPeminjaman')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = {
                nama_penyewa: formData.get('nama_penyewa'),
                no_telepon: formData.get('no_telepon'),
                customer_whatsapp: formData.get('no_telepon'),
                email: formData.get('email'),
                alamat: formData.get('alamat'),
                tipe_pelanggan: formData.get('tipe_pelanggan'),
                nama_acara: formData.get('nama_acara'),
                lokasi_acara: formData.get('lokasi_acara'),
                tanggal_sewa: formData.get('tanggal_sewa'),
                tanggal_kembali: formData.get('tanggal_kembali'),
                waktu_sewa: formData.get('waktu_sewa'),
                waktu_kembali: formData.get('waktu_kembali'),
                diskon: formData.get('diskon'),
                status_pembayaran: formData.get('status_pembayaran'),
                keterangan: formData.get('keterangan'),
                pelanggan_id: formData.get('pelanggan_id'),
                barang: []
            };
            document.querySelectorAll('#barangContainer .barang-row').forEach(row => {
                const id = row.querySelector('[name*="[id]"]')?.value;
                const jumlah = row.querySelector('[name*="[jumlah]"]')?.value;
                if (id && jumlah) data.barang.push({
                    id: parseInt(id),
                    jumlah: parseInt(jumlah)
                });
            });
            if (data.barang.length === 0) {
                showToast('Pilih minimal satu barang', 'error');
                return;
            }
            try {
                const response = await fetch('/peminjaman', {
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
                    closeTambahModal();
                    fetchData();
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Gagal menyimpan', 'error');
            }
        });

        // ==================== EVENT LISTENERS ====================
        document.getElementById('filterSort')?.addEventListener('change', (e) => {
            currentFilters.sort = e.target.value;
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

        function switchTab(tab) {
            currentTab = tab;
            currentPage = 1;
            const aktifBtn = document.getElementById('tabAktifBtn');
            const riwayatBtn = document.getElementById('tabRiwayatBtn');
            if (tab === 'aktif') {
                aktifBtn?.classList.add('border-gray-700', 'text-gray-700');
                aktifBtn?.classList.remove('border-transparent', 'text-slate-500');
                riwayatBtn?.classList.remove('border-gray-700', 'text-gray-700');
                riwayatBtn?.classList.add('border-transparent', 'text-slate-500');
            } else {
                riwayatBtn?.classList.add('border-gray-700', 'text-gray-700');
                riwayatBtn?.classList.remove('border-transparent', 'text-slate-500');
                aktifBtn?.classList.remove('border-gray-700', 'text-gray-700');
                aktifBtn?.classList.add('border-transparent', 'text-slate-500');
            }
            fetchData();
        }

        // ==================== DROPZONE FUNCTIONS ====================
        const dropzone = document.getElementById('dropzonePengembalian');
        const fileInput = document.getElementById('fotoPengembalian');
        const preview = document.getElementById('previewPengembalian');
        const previewImg = document.getElementById('previewImgPengembalian');
        if (dropzone && fileInput) {
            dropzone.addEventListener('click', () => fileInput.click());
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-gray-500', 'bg-gray-50');
            });
            dropzone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-gray-500', 'bg-gray-50');
            });
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-gray-500', 'bg-gray-50');
                const files = e.dataTransfer.files;
                if (files && files.length > 0) {
                    const file = files[0];
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                    const changeEvent = new Event('change', {
                        bubbles: true
                    });
                    fileInput.dispatchEvent(changeEvent);
                    previewImage(file);
                }
            });
        }
        fileInput?.addEventListener('change', (e) => {
            if (e.target.files && e.target.files.length > 0) previewImage(e.target.files[0]);
            else {
                preview?.classList.add('hidden');
                dropzone?.classList.remove('hidden');
                if (previewImg) previewImg.src = '';
            }
        });

        function previewImage(file) {
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                if (previewImg) previewImg.src = e.target.result;
                if (preview) preview.classList.remove('hidden');
                if (dropzone) dropzone.classList.add('hidden');
            };
            reader.onerror = function() {
                console.error('Failed to load image');
                showToast('Gagal memuat gambar', 'error');
            };
            reader.readAsDataURL(file);
        }

        // ==================== CEK PELANGGAN FUNCTIONS ====================
        function openCekPelangganModal() {
            document.getElementById('modalCekPelanggan').classList.remove('hidden');
            document.getElementById('modalCekPelanggan').classList.add('flex');
            document.getElementById('searchPelanggan').value = '';
            document.getElementById('hasilCekPelanggan').classList.add('hidden');
            document.getElementById('pelangganNotFound').classList.add('hidden');
            document.getElementById('autocompleteDropdown').classList.add('hidden');
        }

        function closeCekPelangganModal() {
            document.getElementById('modalCekPelanggan').classList.add('hidden');
            document.getElementById('modalCekPelanggan').classList.remove('flex');
        }
        document.getElementById('searchPelanggan')?.addEventListener('input', function(e) {
            const keyword = e.target.value;
            clearTimeout(searchTimeout);
            if (keyword.length < 2) {
                document.getElementById('autocompleteDropdown').classList.add('hidden');
                return;
            }
            searchTimeout = setTimeout(() => searchPelangganAutocomplete(keyword), 300);
        });
        document.getElementById('searchPelanggan')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const keyword = this.value;
                if (keyword.length >= 2) searchPelanggan(keyword);
            }
        });
        async function searchPelangganAutocomplete(keyword) {
            try {
                const response = await fetch(`/peminjaman/pelanggan-list?search=${encodeURIComponent(keyword)}`);
                const result = await response.json();
                const dropdown = document.getElementById('autocompleteDropdown');
                if (result.data && result.data.length > 0) {
                    dropdown.innerHTML = result.data.map(p =>
                        `<div onclick="selectPelangganSuggestion(${p.id}, '${escapeHtml(p.nama)}', '${escapeHtml(p.no_telepon)}')" class="px-4 py-2 hover:bg-gray-100 cursor-pointer border-b border-slate-100 last:border-0"><div class="font-medium">${escapeHtml(p.nama)}</div><div class="text-xs text-slate-500">${escapeHtml(p.no_telepon)}</div></div>`
                        ).join('');
                    dropdown.classList.remove('hidden');
                } else {
                    dropdown.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function selectPelangganSuggestion(id, nama, telepon) {
            document.getElementById('searchPelanggan').value = nama;
            document.getElementById('autocompleteDropdown').classList.add('hidden');
            searchPelanggan(nama);
        }
        async function searchPelanggan(keyword) {
            try {
                const response = await fetch('/peminjaman/cek-pelanggan', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        keyword: keyword
                    })
                });
                const result = await response.json();
                if (result.exists) {
                    document.getElementById('hasilNama').textContent = result.data.nama;
                    document.getElementById('hasilTelepon').textContent = result.data.no_telepon;
                    document.getElementById('hasilEmail').textContent = result.data.email || '-';
                    document.getElementById('hasilTotalTransaksi').textContent = result.total_transaksi;
                    document.getElementById('hasilTotalNilai').textContent = formatRupiah(result.total_nilai);
                    const statusSpan = document.getElementById('hasilStatus');
                    if (result.data.status === 'aktif') {
                        statusSpan.textContent = 'Aktif';
                        statusSpan.className =
                            'px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700';
                    } else {
                        statusSpan.textContent = 'Nonaktif';
                        statusSpan.className = 'px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700';
                    }
                    const riwayatList = document.getElementById('riwayatList');
                    if (result.riwayat && result.riwayat.length > 0) riwayatList.innerHTML = result.riwayat.map(r =>
                        `<div class="bg-white rounded-lg p-2 border border-slate-200"><div class="flex justify-between items-center"><span class="font-mono text-xs">${r.invoice_number}</span><span class="text-xs ${r.status_pengembalian === 'aktif' ? 'text-green-600' : 'text-gray-500'}">${r.status_pengembalian === 'aktif' ? '🟢 Aktif' : '✅ Selesai'}</span></div><div class="text-xs text-slate-500 mt-1">Tanggal: ${formatDate(r.tanggal_sewa)} - ${formatDate(r.tanggal_kembali)}</div><div class="text-xs font-semibold mt-1">Total: ${formatRupiah(r.grand_total)}</div></div>`
                        ).join('');
                    else riwayatList.innerHTML = '<p class="text-xs text-slate-500">Belum ada riwayat peminjaman</p>';
                    window.selectedCustomer = result.data;
                    document.getElementById('hasilCekPelanggan').classList.remove('hidden');
                    document.getElementById('pelangganNotFound').classList.add('hidden');
                } else {
                    document.getElementById('hasilCekPelanggan').classList.add('hidden');
                    document.getElementById('pelangganNotFound').classList.remove('hidden');
                    const suggestionsContainer = document.getElementById('suggestionsContainer');
                    if (result.suggestions && result.suggestions.length > 0) suggestionsContainer.innerHTML =
                        `<p class="text-sm text-slate-600 mb-2">Pelanggan dengan nama mirip:</p>${result.suggestions.map(s => `<div onclick="selectPelangganSuggestion(${s.id}, '${escapeHtml(s.nama)}', '${escapeHtml(s.no_telepon)}')" class="p-2 bg-gray-100 rounded-lg mb-2 cursor-pointer hover:bg-gray-200"><div class="font-medium">${escapeHtml(s.nama)}</div><div class="text-xs text-slate-500">${escapeHtml(s.no_telepon)}</div></div>`).join('')}`;
                    else suggestionsContainer.innerHTML = '';
                    window.selectedCustomer = null;
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal mengecek pelanggan', 'error');
            }
        }

        function useExistingCustomer() {
            if (window.selectedCustomer) {
                document.getElementById('pelanggan_id').value = window.selectedCustomer.id;
                document.getElementById('nama_penyewa').value = window.selectedCustomer.nama;
                document.getElementById('no_telepon').value = window.selectedCustomer.no_telepon;
                document.getElementById('email').value = window.selectedCustomer.email || '';
                document.getElementById('alamat').value = window.selectedCustomer.alamat || '';
                document.getElementById('tipe_pelanggan').value = window.selectedCustomer.tipe || 'perorangan';
                closeCekPelangganModal();
                showToast('Data pelanggan berhasil diisi', 'success');
            }
        }

        function openNewCustomerForm() {
            closeCekPelangganModal();
            document.getElementById('pelanggan_id').value = '';
            document.getElementById('nama_penyewa').value = '';
            document.getElementById('no_telepon').value = document.getElementById('searchPelanggan').value || '';
            document.getElementById('email').value = '';
            document.getElementById('alamat').value = '';
            document.getElementById('tipe_pelanggan').value = 'perorangan';
        }
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('autocompleteDropdown');
            const searchInput = document.getElementById('searchPelanggan');
            if (dropdown && !dropdown.contains(e.target) && e.target !== searchInput) dropdown.classList.add(
                'hidden');
        });

        // ==================== INITIALIZE ====================
        document.addEventListener('DOMContentLoaded', function() {
            loadBarang();
            fetchData();
        });
    </script>
@endsection
