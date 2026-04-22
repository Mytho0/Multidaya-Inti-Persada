<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
  <title>Multidaya Inti Persada | Dashboard Mobile Adaptive</title>
  <!-- Tailwind CSS v3 + Font Awesome 6 + Google Fonts Inter -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    ::-webkit-scrollbar { width: 4px; height: 4px; }
    ::-webkit-scrollbar-track { background: #e5e7eb; border-radius: 10px; }
    ::-webkit-scrollbar-thumb { background: #9ca3af; border-radius: 10px; }
    /* smooth transition untuk sidebar mobile */
    .mobile-sidebar {
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sidebar-hidden {
      transform: translateX(-100%);
    }
    /* overlay untuk mobile sidebar */
    .sidebar-overlay {
      transition: opacity 0.3s ease;
    }
    /* touch-friendly */
    button, a, .clickable {
      cursor: pointer;
      -webkit-tap-highlight-color: transparent;
    }
    /* card hover effect tetap ringan untuk mobile */
    @media (max-width: 768px) {
      .stats-card:active {
        transform: scale(0.98);
      }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-slate-100 to-slate-200/70 antialiased">

  <!-- Mobile Overlay (untuk sidebar) -->
  <div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-20 opacity-0 invisible transition-all duration-300 sidebar-overlay lg:hidden"></div>

  <div class="flex min-h-screen relative">
    <!-- ================= SIDEBAR (Desktop & Mobile dengan toggle) ================= -->
    <aside id="sidebar" class="mobile-sidebar fixed lg:relative z-30 w-72 bg-white shadow-xl flex flex-col border-r border-slate-200 h-full overflow-y-auto sidebar-hidden lg:translate-x-0 transition-transform duration-300">
      <!-- Brand Area - Multidaya Inti Persada -->
      <div class="px-6 pt-8 pb-6 border-b border-slate-100">
        <div class="flex items-center gap-2">
          <div class="h-9 w-9 bg-gray-800 rounded-xl flex items-center justify-center shadow-md">
            <i class="fas fa-chart-line text-white text-sm"></i>
          </div>
          <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">Multidaya</h1>
            <p class="text-xs font-semibold text-gray-500 -mt-0.5">Inti Persada</p>
          </div>
        </div>
      </div>
      
      <!-- Navigation -->
      <nav class="flex-1 px-4 py-6 space-y-1.5">
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl bg-gray-100 text-gray-800 shadow-sm transition">
          <i class="fas fa-tachometer-alt w-5 text-gray-600"></i>
          <span>Dashboard</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-600 hover:bg-gray-100 transition">
          <i class="fas fa-hand-holding-usd w-5 text-gray-500"></i>
          <span>Peminjaman</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-600 hover:bg-gray-100 transition">
          <i class="fas fa-boxes w-5 text-gray-500"></i>
          <span>Barang</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-600 hover:bg-gray-100 transition">
          <i class="fas fa-coins w-5 text-gray-500"></i>
          <span>Keuangan</span>
        </a>
      </nav>
      
      <!-- Promo / Inventaris button -->
      <div class="p-5 border-t border-slate-100">
        <button class="w-full flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2.5 px-4 rounded-xl shadow-md transition-all duration-200">
          <i class="fas fa-plus-circle"></i>
          <span>Tambah Promo/Inventaris</span>
        </button>
      </div>
    </aside>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 w-full min-w-0">
      <!-- Top header dengan mobile menu toggle -->
      <div class="bg-white/80 backdrop-blur-sm sticky top-0 z-20 border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-3 sm:py-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <!-- Mobile Menu Toggle Button -->
          <button id="mobileMenuBtn" class="lg:hidden p-2 -ml-2 rounded-lg text-slate-600 hover:bg-slate-100 transition">
            <i class="fas fa-bars text-xl"></i>
          </button>
          <div>
            <h2 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight">Dashboard Overview</h2>
            <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mt-0.5">
              <i class="far fa-calendar-alt"></i>
              <span id="currentDateSpan">Tue, 14 Mar 2026, 11.30 AM</span>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-3 sm:gap-4">
          <div class="relative">
            <i class="far fa-bell text-slate-400 text-lg sm:text-xl hover:text-slate-600 cursor-pointer"></i>
            <span class="absolute -top-1 -right-1.5 h-2.5 w-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
          </div>
          <div class="flex items-center gap-2 bg-white rounded-full shadow-sm pl-2 pr-3 py-1 border border-slate-200">
            <div class="h-7 w-7 sm:h-8 sm:w-8 rounded-full bg-gradient-to-br from-gray-500 to-gray-700 flex items-center justify-center text-white text-xs font-bold">AD</div>
            <span class="text-xs sm:text-sm font-medium text-slate-700 hidden sm:inline-block">Admin</span>
            <span class="text-xs sm:text-sm font-medium text-slate-700 sm:hidden">AD</span>
          </div>
        </div>
      </div>

      <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-7xl mx-auto">
        <!-- Greeting Section - Responsive Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
          <!-- Welcome card -->
          <div class="lg:col-span-2 bg-gradient-to-r from-gray-700 to-gray-800 rounded-2xl shadow-xl p-5 sm:p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 opacity-10 text-7xl sm:text-9xl -mr-4 -mt-4"><i class="fas fa-chart-line"></i></div>
            <p class="text-gray-200 text-xs sm:text-sm font-medium flex items-center gap-2"><i class="far fa-sun"></i> Today's Statistics</p>
            <h3 class="text-2xl sm:text-3xl font-bold mt-2">Good Morning</h3>
            <p class="text-gray-200 text-xs sm:text-sm mt-1 max-w-xs">Continue Your Journey And Achieve Your Target</p>
            <div class="mt-4 sm:mt-5 flex gap-3 sm:gap-4">
              <div class="bg-white/20 rounded-xl px-3 sm:px-4 py-2 backdrop-blur-sm">
                <p class="text-xs font-medium">Target Bulan Ini</p>
                <p class="text-base sm:text-xl font-bold">78%</p>
              </div>
              <div class="bg-white/20 rounded-xl px-3 sm:px-4 py-2 backdrop-blur-sm">
                <p class="text-xs font-medium">Pencapaian</p>
                <p class="text-base sm:text-xl font-bold">Rp 124Jt</p>
              </div>
            </div>
          </div>
          
          <!-- Chats & Revenues Cards - Responsive Stack -->
          <div class="flex flex-col gap-3 sm:gap-4">
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-5 flex justify-between items-center">
              <div>
                <div class="flex items-center gap-2 text-slate-500 text-xs sm:text-sm"><i class="fab fa-rocketchat text-gray-500"></i> <span>Chats</span></div>
                <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">2 <span class="text-xs sm:text-sm font-normal text-slate-400">unread messages</span></p>
                <a href="#" class="text-gray-600 text-xs font-semibold inline-flex items-center gap-1 mt-2 hover:underline">All messages →</a>
              </div>
              <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600"><i class="fas fa-comment-dots text-base sm:text-xl"></i></div>
            </div>
            <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-5 flex justify-between items-center">
              <div>
                <div class="flex items-center gap-2 text-slate-500 text-xs sm:text-sm"><i class="fas fa-chart-simple text-gray-500"></i> <span>Revenues</span></div>
                <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">15% <span class="text-xs sm:text-sm font-medium text-green-600"><i class="fas fa-arrow-up"></i> increase</span></p>
                <p class="text-xs text-slate-400 mt-1">compared to last week</p>
                <a href="#" class="text-gray-600 text-xs font-semibold inline-flex items-center gap-1 mt-2 hover:underline">Revenues report →</a>
              </div>
              <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600"><i class="fas fa-dollar-sign text-base sm:text-xl"></i></div>
            </div>
          </div>
        </div>

        <!-- Riwayat Aktivitas + Top Products - Responsive -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
          <!-- Riwayat Aktivitas Terbaru -->
          <div class="lg:col-span-2 bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 flex justify-between items-center flex-wrap gap-2">
              <h3 class="font-bold text-slate-800 text-base sm:text-lg"><i class="fas fa-history text-gray-500 mr-2"></i> Riwayat Aktivitas Terbaru</h3>
              <span class="text-xs text-slate-400 bg-gray-100 px-2 sm:px-3 py-1 rounded-full">Hari ini</span>
            </div>
            <div class="overflow-x-auto">
              <table class="min-w-[500px] sm:min-w-full w-full">
                <thead class="bg-slate-50/70">
                  <tr>
                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr class="hover:bg-slate-50 transition">
                    <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-mono text-slate-700">10:32</td>
                    <td class="px-3 sm:px-6 py-2 sm:py-3"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold"><i class="fas fa-exchange-alt text-[10px]"></i> Penyewaan</span></td>
                    <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm text-slate-600">PB274 - TV LG 2024 oleh Carissa</td>
                  </tr>
                  <tr class="hover:bg-slate-50 transition">
                    <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-mono text-slate-700">08:10</td>
                    <td class="px-3 sm:px-6 py-2 sm:py-3"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold"><i class="fas fa-receipt"></i> Pengeluaran</span></td>
                    <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm text-slate-600">Pembelian kabel HDMI</td>
                  </tr>
                  <tr class="hover:bg-slate-50 transition">
                    <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-mono text-slate-700">10:32</td>
                    <td class="px-3 sm:px-6 py-2 sm:py-3"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold"><i class="fas fa-undo-alt"></i> Pengembalian</span></td>
                    <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm text-slate-600">PB270 - Epson EB-S300</td>
                  </tr>
                  <tr class="hover:bg-slate-50 transition">
                    <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm font-mono text-slate-700">10:32</td>
                    <td class="px-3 sm:px-6 py-2 sm:py-3"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold"><i class="fas fa-edit"></i> Edit</span></td>
                    <td class="px-3 sm:px-6 py-2 sm:py-3 text-xs sm:text-sm text-slate-600">Update tanggal kembali ID PB289</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="px-4 sm:px-6 py-2 sm:py-3 bg-slate-50/40 border-t border-slate-100 text-xs text-slate-500 flex justify-end">
              <a href="#" class="text-gray-600 hover:underline">Lihat semua →</a>
            </div>
          </div>

          <!-- Top Products - Responsive -->
          <div class="bg-white rounded-2xl shadow-md border border-slate-200 overflow-hidden">
            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-slate-100">
              <h3 class="font-bold text-slate-800 text-base sm:text-lg"><i class="fas fa-chart-simple text-gray-500 mr-2"></i> Top Products</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="min-w-[300px] w-full text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500">
                  <tr>
                    <th class="px-3 sm:px-5 py-2 text-left">#</th>
                    <th class="px-2 py-2 text-left">Name</th>
                    <th class="px-2 py-2 text-left">Popularity</th>
                    <th class="px-3 sm:px-5 py-2 text-left">Sales</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr class="hover:bg-slate-50">
                    <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-600">01</td>
                    <td class="px-2 py-2 sm:py-3 font-medium text-slate-700">LG Smart TV</td>
                    <td class="px-2 py-2 sm:py-3"><div class="flex items-center gap-1 sm:gap-2"><span class="text-xs font-bold text-gray-600">46%</span><div class="w-12 sm:w-16 bg-slate-200 rounded-full h-1.5"><div class="bg-gray-600 h-1.5 rounded-full w-[46%]"></div></div></div></td>
                    <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-800">100k</td>
                  </tr>
                  <tr class="hover:bg-slate-50">
                    <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-600">02</td>
                    <td class="px-2 py-2 sm:py-3 font-medium text-slate-700">Disney Princess</td>
                    <td class="px-2 py-2 sm:py-3"><div class="flex items-center gap-1 sm:gap-2"><span class="text-xs font-bold text-gray-600">17%</span><div class="w-12 sm:w-16 bg-slate-200 rounded-full h-1.5"><div class="bg-gray-500 h-1.5 rounded-full w-[17%]"></div></div></div></td>
                    <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-800">50k</td>
                  </tr>
                  <tr class="hover:bg-slate-50">
                    <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-600">03</td>
                    <td class="px-2 py-2 sm:py-3 font-medium text-slate-700">Bathroom Essentials</td>
                    <td class="px-2 py-2 sm:py-3"><div class="flex items-center gap-1 sm:gap-2"><span class="text-xs font-bold text-gray-600">19%</span><div class="w-12 sm:w-16 bg-slate-200 rounded-full h-1.5"><div class="bg-gray-500 h-1.5 rounded-full w-[19%]"></div></div></div></td>
                    <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-800">20k</td>
                  </tr>
                  <tr class="hover:bg-slate-50">
                    <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-600">04</td>
                    <td class="px-2 py-2 sm:py-3 font-medium text-slate-700">Apple Smartwatch</td>
                    <td class="px-2 py-2 sm:py-3"><div class="flex items-center gap-1 sm:gap-2"><span class="text-xs font-bold text-gray-500">29%</span><div class="w-12 sm:w-16 bg-slate-200 rounded-full h-1.5"><div class="bg-gray-400 h-1.5 rounded-full w-[29%]"></div></div></div></td>
                    <td class="px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-400">0</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- GROWTH SECTION Responsive -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
          <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-5">
            <div class="flex justify-between items-start">
              <div>
                <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-2 py-1 rounded-full"><i class="fas fa-chart-line mr-1"></i> Growth</span>
                <h4 class="text-base sm:text-lg font-bold text-slate-800 mt-2">Top month</h4>
                <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">November 2019</p>
                <div class="mt-3 flex items-baseline gap-2 flex-wrap">
                  <span class="text-xs sm:text-sm text-slate-500">📊 Pengeluaran:</span>
                  <span class="font-bold text-slate-800 text-sm sm:text-base">96K sold so far</span>
                </div>
                <div class="mt-2 w-full bg-slate-100 rounded-full h-2 max-w-[180px] sm:max-w-[200px]">
                  <div class="bg-gray-600 h-2 rounded-full w-[72%]"></div>
                </div>
                <p class="text-xs text-slate-400 mt-2">+12% dari periode sebelumnya</p>
              </div>
              <i class="fas fa-calendar-alt text-2xl sm:text-3xl text-slate-300"></i>
            </div>
          </div>
          <div class="bg-white rounded-2xl shadow-md border border-slate-200 p-4 sm:p-5">
            <div class="flex justify-between items-start">
              <div>
                <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-2 py-1 rounded-full"><i class="fas fa-trophy mr-1"></i> Rekor</span>
                <h4 class="text-base sm:text-lg font-bold text-slate-800 mt-2">Top year</h4>
                <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-1">2023</p>
                <div class="mt-3 flex items-baseline gap-2 flex-wrap">
                  <span class="text-xs sm:text-sm text-slate-500">📈 Pendapatan Tahunan:</span>
                  <span class="font-bold text-slate-800 text-sm sm:text-base">+96K sold</span>
                </div>
                <div class="mt-2 flex gap-2 text-xs text-slate-500">
                  <span><i class="fas fa-chart-simple text-gray-400"></i> Pertumbuhan 22%</span>
                </div>
                <p class="text-xs text-slate-400 mt-2">Berdasarkan data historis</p>
              </div>
              <i class="fas fa-chart-column text-2xl sm:text-3xl text-slate-300"></i>
            </div>
          </div>
        </div>

        <!-- Tambah Promo/Inventaris Card Responsive -->
        <div class="bg-gradient-to-r from-gray-100 to-slate-100 rounded-2xl border border-gray-200 p-4 sm:p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-700"><i class="fas fa-gift text-base sm:text-xl"></i></div>
            <div>
              <h4 class="font-bold text-slate-800 text-sm sm:text-base">Tambah Promo / Inventaris Baru</h4>
              <p class="text-xs sm:text-sm text-slate-500">Tingkatkan penjualan dengan diskon atau stok baru</p>
            </div>
          </div>
          <button class="bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2 px-4 sm:px-6 rounded-xl shadow-md transition flex items-center gap-2 text-sm sm:text-base w-full sm:w-auto justify-center">
            <i class="fas fa-plus-circle"></i> Tambah Sekarang
          </button>
        </div>

        <!-- Footer Responsive -->
        <div class="mt-6 sm:mt-8 text-xs text-slate-400 flex flex-col sm:flex-row justify-between items-center gap-3 border-t border-slate-200 pt-5">
          <div class="flex items-center gap-2">
            <i class="fas fa-database"></i>
            <span>Multidaya Inti Persada · Dashboard Mobile</span>
          </div>
          <div class="flex gap-4">
            <a href="#" class="hover:text-gray-600"><i class="fab fa-whatsapp"></i> Support</a>
            <a href="#" class="hover:text-gray-600"><i class="fas fa-chart-pie"></i> Laporan</a>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Set tanggal statis sesuai desain
    document.getElementById('currentDateSpan').innerText = 'Tue, 14 Mar 2026, 11.30 AM';

    // Mobile Sidebar Toggle Logic
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobileOverlay');
    const menuBtn = document.getElementById('mobileMenuBtn');
    
    function openSidebar() {
      sidebar.classList.remove('sidebar-hidden');
      overlay.classList.remove('invisible', 'opacity-0');
      overlay.classList.add('visible', 'opacity-100');
      document.body.style.overflow = 'hidden';
    }
    
    function closeSidebar() {
      sidebar.classList.add('sidebar-hidden');
      overlay.classList.add('invisible', 'opacity-0');
      overlay.classList.remove('visible', 'opacity-100');
      document.body.style.overflow = '';
    }
    
    if (menuBtn) {
      menuBtn.addEventListener('click', openSidebar);
    }
    
    overlay.addEventListener('click', closeSidebar);
    
    // Tutup sidebar saat klik link di dalam sidebar (opsional untuk user experience)
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        if (window.innerWidth < 1024) {
          e.preventDefault();
          alert(`Demo navigasi: ${link.innerText} (Mobile responsive ready)`);
          closeSidebar();
        }
      });
    });
    
    // Tombol-tombol lain demo
    const tambahBtns = document.querySelectorAll('button:has(.fa-plus-circle)');
    tambahBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        alert("⚡ Multidaya Inti Persada - Tambah Promo/Inventaris (Demo)");
      });
    });
    
    const revenueLinks = document.querySelectorAll('a[href="#"]');
    revenueLinks.forEach(link => {
      if(link.innerText.includes('All messages') || link.innerText.includes('Revenues report') || link.innerText.includes('Lihat semua')) {
        link.addEventListener('click', (e) => {
          e.preventDefault();
          alert(`Demo: ${link.innerText}`);
        });
      }
    });
    
    // Resize handler: jika window diresize ke desktop, pastikan sidebar visible dan overlay tertutup
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 1024) {
        sidebar.classList.remove('sidebar-hidden');
        closeSidebar();
      } else {
        // pada mobile pastikan sidebar hidden saat resize dari desktop ke mobile
        if (!sidebar.classList.contains('sidebar-hidden')) {
          sidebar.classList.add('sidebar-hidden');
        }
      }
    });
  </script>
</body>
</html>