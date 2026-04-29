# Multidaya Inti Persada - Sistem Manajemen Persewaan Barang

## 📋 Tentang Project

**Multidaya Inti Persada** adalah sebuah aplikasi web manajemen persewaan barang berbasis Laravel yang dirancang untuk membantu perusahaan dalam mengelola proses peminjaman barang, inventaris, keuangan, dan pelanggan secara terintegrasi. Aplikasi ini dilengkapi dengan fitur notifikasi WhatsApp, laporan keuangan, serta sistem rekomendasi pintar.

## ✨ Fitur Utama

### 1. Manajemen Peminjaman
- ✅ Tambah, edit, hapus peminjaman
- ✅ Tracking status peminjaman (Aktif/Selesai/Terlambat)
- ✅ Filter berdasarkan status dan tipe pelanggan
- ✅ Cek pelanggan berdasarkan nama/telepon dengan autocomplete
- ✅ Form pengembalian dengan upload foto dan perhitungan denda
- ✅ Generate invoice PDF
- ✅ Notifikasi WhatsApp pengiriman & pengingat pengembalian

### 2. Manajemen Barang
- ✅ CRUD barang (Tambah, Edit, Hapus)
- ✅ Upload gambar barang (drag & drop)
- ✅ Tracking stok (Stok, Tersedia, Disewa)
- ✅ Filter berdasarkan jenis barang
- ✅ Sorting berdasarkan nama, harga, stok
- ✅ Statistik barang real-time

### 3. Manajemen Keuangan
- ✅ Dashboard keuangan dengan grafik
- ✅ Pendapatan dari peminjaman
- ✅ Pengeluaran operasional (tambah manual)
- ✅ Laporan Laba Rugi (Profit & Loss)
- ✅ Filter berdasarkan periode (bulan/tahun)
- ✅ Cetak laporan

### 4. Dashboard & Rekomendasi
- ✅ Statistik real-time
- ✅ Grafik pendapatan & pengeluaran
- ✅ Top produk terlaris
- ✅ Rekomendasi pintar (tambah barang/promo)
- ✅ Notifikasi WhatsApp ke admin

### 5. Manajemen Pelanggan
- ✅ Auto-create pelanggan baru berdasarkan no telepon
- ✅ Riwayat peminjaman per pelanggan
- ✅ Total transaksi dan nilai pelanggan
- ✅ Filter pelanggan baru/lama

## 🛠 Teknologi yang Digunakan

| Teknologi | Versi | Keterangan |
|-----------|-------|-------------|
| Laravel | 12.53.0 | Framework PHP |
| PHP | 8.3.10 | Bahasa pemrograman |
| MySQL | 8.0+ | Database |
| Tailwind CSS | 3.x | Framework CSS |
| Font Awesome | 6.0 | Icon library |
| Chart.js | 3.x | Library grafik |
| DomPDF | 2.x | Generate PDF invoice |
| WhatsApp API | Fonnte/Wablas | Notifikasi WhatsApp |

## 📁 Struktur Database

### Tabel Utama
1. **users** - Data pengguna/admin
2. **pelanggan** - Data pelanggan
3. **barang** - Data inventaris barang
4. **peminjaman** - Data transaksi peminjaman
5. **detail_peminjaman** - Detail barang yang dipinjam
6. **keuangan** - Data pendapatan & pengeluaran
7. **notifications** - Data notifikasi
8. **recommendations** - Data rekomendasi pintar

### Relasi Database
```
pelanggan (1) ----< (M) peminjaman (1) ----< (M) detail_peminjaman (M) ----< (1) barang
                     |
                     +---- (1) keuangan
                     +---- (1) notifications
```

## 🚀 Panduan Instalasi

### Prasyarat
- PHP >= 8.1
- Composer
- MySQL
- Node.js & NPM (optional)

### Langkah Instalasi

1. **Clone repository**
```bash
git clone https://github.com/your-repo/multidaya-inti-persada.git
cd multidaya-inti-persada
```

2. **Install dependencies**
```bash
composer install
npm install  # optional, jika menggunakan Vite
```

3. **Konfigurasi Environment**
```bash
cp .env.example .env
```


# WhatsApp API Configuration
WHATSAPP_API_URL=https://api.fonnte.com/send
WHATSAPP_API_KEY=your_api_key_here
```

4. **Generate key**
```bash
php artisan key:generate
```

5. **Jalankan migration & seeder**
```bash
php artisan migrate
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=BarangSeeder
php artisan db:seed --class=PeminjamanSeeder
php artisan db:seed --class=KeuanganSeeder
```

6. **Buat storage link (untuk upload gambar)**
```bash
php artisan storage:link
```

7. **Jalankan server**
```bash
php artisan serve
```

8. **Akses aplikasi**
```
http://localhost:8000
```

## 📂 Struktur Folder

```
multidaya-inti-persada/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── LoginController.php
│   │   │   ├── BarangController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── KeuanganController.php
│   │   │   └── PeminjamanController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── Barang.php
│   │   ├── DetailPeminjaman.php
│   │   ├── Keuangan.php
│   │   ├── Pelanggan.php
│   │   ├── Peminjaman.php
│   │   └── User.php
│   └── Services/
│       └── WhatsAppService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── auth/
│       │   └── login.blade.php
│       ├── barang/
│       │   └── index.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── keuangan/
│       │   ├── index.blade.php
│       │   └── laporan_laba_rugi.blade.php
│       └── peminjaman/
│           ├── index.blade.php
│           └── invoice.blade.php
├── routes/
│   └── web.php
└── public/
    └── storage/ (symlink)
```

## 🔧 Fitur & Cara Penggunaan

### 1. Login
- Buka `http://localhost:8000/login`
- Masukkan username dan password

### 2. Dashboard
- Melihat statistik pendapatan, transaksi, dan rekomendasi
- Grafik pendapatan bulanan
- Top barang terlaris
- Riwayat aktivitas terbaru

### 3. Manajemen Barang
- **Tambah Barang**: Klik "Tambah Barang" → isi form → upload gambar → simpan
- **Edit Barang**: Klik icon edit pada tabel → ubah data → simpan
- **Detail Barang**: Klik icon eye untuk melihat detail lengkap
- **Hapus Barang**: Klik icon trash → konfirmasi

### 4. Manajemen Peminjaman
- **Tambah Peminjaman**: 
  1. Klik "Tambah Peminjaman"
  2. Cek pelanggan (jika sudah ada)
  3. Isi data penyewa
  4. Pilih barang dan jumlah
  5. Simpan → otomatis kirim notifikasi WhatsApp

- **Pengembalian Barang**:
  1. Klik icon pengembalian
  2. Upload foto barang kembali
  3. Pilih kondisi barang
  4. Input denda jika rusak/terlambat
  5. Proses pengembalian

- **Generate Invoice**: Klik icon printer untuk download PDF

### 5. Laporan Keuangan
- **Dashboard Keuangan**: Lihat ringkasan pendapatan & pengeluaran
- **Tambah Biaya**: Klik "Tambah Biaya Operasional"
- **Laporan Laba Rugi**: Buka menu "Laporan Laba Rugi"
- **Filter Periode**: Pilih bulan dan tahun
- **Cetak Laporan**: Klik "Cetak Laporan"

### 6. Notifikasi WhatsApp
- **Kirim Notifikasi Pengiriman**: Klik icon WhatsApp pada peminjaman aktif
- **Kirim Pengingat Pengembalian**: Klik icon bell

## 📊 Laporan & Statistik

| Halaman | Fitur |
|---------|-------|
| Dashboard | Grafik pendapatan, top produk, rekomendasi |
| Keuangan | Pendapatan, pengeluaran, laba bersih |
| Laporan Laba Rugi | Laporan keuangan lengkap dengan format akuntansi |

## 🔐 Keamanan

- ✅ Authentication dengan session-based
- ✅ CSRF Protection
- ✅ XSS Prevention (escape output)
- ✅ SQL Injection Protection (Eloquent ORM)
- ✅ Password hashing dengan bcrypt

## 🐛 Troubleshooting

### Error "Class not found"
```bash
composer dump-autoload
```

### Error storage link
```bash
php artisan storage:link
rm public/storage  # jika perlu reset
```

### Error migration
```bash
php artisan migrate:fresh --seed
```

### Error Vite manifest
```bash
npm install
npm run build
# atau hapus @vite dari layout
```

### Error WhatsApp notification
- Pastikan API key valid
- Cek koneksi internet
- Format nomor telepon: 62xxxxxxxxxx

## 🤝 Kontribusi

1. Fork repository
2. Buat branch baru (`git checkout -b feature/amazing-feature`)
3. Commit perubahan (`git commit -m 'Add some amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buat Pull Request

## 📝 Lisensi

Copyright © 2024 Multidaya Inti Persada. All rights reserved.

## 👨‍💻 Developer

**Multidaya Inti Persada Team**
- Website: [www.multidaya.com](http://www.multidaya.com)
- Email: info@multidaya.com
- WhatsApp: 08123456789

## 🙏 Ucapan Terima Kasih

- Laravel Community
- Tailwind CSS
- Font Awesome
- DomPDF
- Semua kontributor yang telah membantu

---

**Made with ❤️ by Multidaya Inti Persada Team**
