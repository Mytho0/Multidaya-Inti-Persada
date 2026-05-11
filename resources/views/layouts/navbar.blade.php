<!-- Mobile Overlay -->
<div id="mobileOverlay"
    class="fixed inset-0 bg-black/50 z-20 opacity-0 invisible transition-all duration-300 sidebar-overlay lg:hidden"
    onclick="closeSidebar()"></div>

<div class="flex min-h-screen relative bg-[#f8fbff]">
    <!-- SIDEBAR - Compact & Minimalis (Warna Putih Kebiruan) -->
    <aside id="sidebar"
        class="mobile-sidebar fixed lg:sticky top-0 z-30 w-64 bg-white shadow-xl flex flex-col border-r border-[#cfe1f4] h-screen overflow-y-auto sidebar-hidden lg:translate-x-0 transition-transform duration-300">

        <!-- Brand Area - Compact -->
        <div class="px-4 pt-5 pb-4 border-b border-[#cfe1f4]">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2.5 group relative overflow-hidden rounded-lg">
                <div
                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700">
                </div>

                <div
                    class="h-8 w-8 flex items-center justify-center overflow-hidden rounded-lg shadow-sm group-hover:shadow-md transition-all duration-200">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Multidaya" class="h-full w-full object-contain">
                </div>

                <div>
                    <h1 class="text-base font-extrabold tracking-tight text-slate-800 leading-tight">Multidaya</h1>
                    <p class="text-[8px] font-bold text-[#0a3d84] uppercase tracking-widest -mt-0.5">Inti Persada</p>
                </div>
            </a>
        </div>

        <!-- Navigation Menu - Compact -->
        <nav class="flex-1 px-3 py-4 space-y-0.5">
            <!-- Dashboard Menu -->
            <a href="{{ route('dashboard') }}"
                class="relative overflow-hidden flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg transition-all duration-300 group
                @if (Request::routeIs('dashboard')) bg-[#213c61] text-white shadow-md @else text-slate-600 hover:bg-[#213c61] hover:text-white @endif">
                <div
                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none">
                </div>
                <i
                    class="fas fa-tachometer-alt w-4 relative z-10 text-sm @if (Request::routeIs('dashboard')) text-white @else text-[#4d7cbf] group-hover:text-white @endif"></i>
                <span class="relative z-10">Dashboard</span>
                @if (Request::routeIs('dashboard'))
                    <span class="ml-auto w-1.5 h-1.5 bg-blue-400 rounded-full animate-pulse"></span>
                @endif
            </a>

            <!-- Peminjaman Menu -->
            <a href="{{ route('peminjaman.index') }}"
                class="relative overflow-hidden flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg transition-all duration-300 group
                @if (Request::routeIs('peminjaman.index')) bg-[#213c61] text-white shadow-md @else text-slate-600 hover:bg-[#213c61] hover:text-white @endif">
                <div
                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none">
                </div>
                <i
                    class="fas fa-hand-holding-usd w-4 relative z-10 text-sm @if (Request::routeIs('peminjaman.index')) text-white @else text-[#4d7cbf] group-hover:text-white @endif"></i>
                <span class="relative z-10">Peminjaman</span>
            </a>

            <!-- Barang Menu -->
            <a href="{{ route('barang.index') }}"
                class="relative overflow-hidden flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg transition-all duration-300 group
                @if (Request::routeIs('barang.index')) bg-[#213c61] text-white shadow-md @else text-slate-600 hover:bg-[#213c61] hover:text-white @endif">
                <div
                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none">
                </div>
                <i
                    class="fas fa-boxes w-4 relative z-10 text-sm @if (Request::routeIs('barang.index')) text-white @else text-[#4d7cbf] group-hover:text-white @endif"></i>
                <span class="relative z-10">Barang</span>
            </a>

            <!-- Keuangan Menu -->
            <a href="{{ route('keuangan.index') }}"
                class="relative overflow-hidden flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg transition-all duration-300 group
                @if (Request::routeIs('keuangan.index')) bg-[#213c61] text-white shadow-md @else text-slate-600 hover:bg-[#213c61] hover:text-white @endif">
                <div
                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700 pointer-events-none">
                </div>
                <i
                    class="fas fa-coins w-4 relative z-10 text-sm @if (Request::routeIs('keuangan.index')) text-white @else text-[#4d7cbf] group-hover:text-white @endif"></i>
                <span class="relative z-10">Keuangan</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex-1 w-full min-w-0">
        <!-- Top Header - Compact & Minimalis -->
        <div
            class="bg-white/80 backdrop-blur-sm sticky top-0 z-20 border-b border-[#cfe1f4] px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <button id="mobileMenuBtn"
                    class="lg:hidden p-1.5 -ml-2 rounded-lg text-slate-600 hover:bg-[#cfe1f4]/50 transition-all duration-200"
                    onclick="openSidebar()">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div>
                    <h2 class="text-sm sm:text-base font-semibold text-slate-800 tracking-tight">@yield('page-title', 'Dashboard Overview')</h2>
                    <div class="flex items-center gap-2 text-[10px] sm:text-xs text-[#4d7cbf] mt-0.5 font-medium">
                        <i class="far fa-calendar-alt"></i>
                        <span id="currentDateSpan"></span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Notifications -->
                <div class="relative cursor-pointer group" onclick="showNotifications()">
                    <i
                        class="far fa-bell text-slate-400 text-base sm:text-lg group-hover:text-[#4d7cbf] transition-all duration-200"></i>
                    <span class="absolute -top-1 -right-1.5 h-2 w-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                </div>

                <!-- User Dropdown - Compact -->
                <div class="relative group">
                    <div
                        class="flex items-center gap-2 bg-white rounded-full shadow-sm pl-2 pr-2.5 py-0.5 border border-[#cfe1f4] cursor-pointer hover:border-[#4d7cbf] transition-all duration-200">
                        <div
                            class="h-6 w-6 sm:h-7 sm:w-7 rounded-full bg-[#4d7cbf] flex items-center justify-center text-white text-[10px] font-bold">
                            {{ Auth::user() ? strtoupper(substr(Auth::user()->username, 0, 2)) : 'GU' }}
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-xs font-medium text-slate-700">
                                {{ Auth::user() ? Auth::user()->name : 'Guest' }}
                            </span>
                            <span class="text-[9px] text-slate-400 block -mt-0.5">
                                @ {{ Auth::user() ? Auth::user()->username : 'guest' }}
                            </span>
                        </div>
                        <i
                            class="fas fa-chevron-down text-[9px] text-slate-400 group-hover:text-[#4d7cbf] transition-colors duration-200"></i>
                    </div>

                    <!-- Dropdown Menu -->
                    <div
                        class="absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-[#cfe1f4] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="px-3 py-2 border-b border-[#cfe1f4]">
                            <p class="text-xs font-semibold text-slate-800">{{ Auth::user() ? Auth::user()->name : '' }}
                            </p>
                            <p class="text-[9px] text-slate-500">@ {{ Auth::user() ? Auth::user()->username : '' }}</p>
                        </div>
                        <a href="#"
                            class="flex items-center gap-2 px-3 py-1.5 text-xs text-slate-700 hover:bg-[#cfe1f4]/30 transition-colors duration-200">
                            <i class="fas fa-user-circle text-[#4d7cbf] w-3"></i> Profile
                        </a>
                        <hr class="my-1 border-[#cfe1f4]">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left flex items-center gap-2 px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt w-3"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        @yield('main-content')
    </div>
</div>

<script>
    // Set current date
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        const dateStr = now.toLocaleDateString('id-ID', options);
        const timeStr = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
        const dateTimeElement = document.getElementById('currentDateSpan');
        if (dateTimeElement) {
            dateTimeElement.innerText = `${dateStr}, ${timeStr}`;
        }
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);

    // Mobile sidebar functions
    function openSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        if (sidebar && overlay) {
            sidebar.classList.remove('sidebar-hidden');
            overlay.classList.remove('invisible', 'opacity-0');
            overlay.classList.add('visible', 'opacity-100');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        if (sidebar && overlay) {
            sidebar.classList.add('sidebar-hidden');
            overlay.classList.add('invisible', 'opacity-0');
            overlay.classList.remove('visible', 'opacity-100');
            document.body.style.overflow = '';
        }
    }

    function showPromoModal() {
        alert("⚡ Multidaya Inti Persada - Tambah Promo/Inventaris");
    }

    function showNotifications() {
        alert("📢 Notifikasi: Belum ada notifikasi baru");
    }

    // Mobile menu button
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', openSidebar);
    }

    // Close sidebar when clicking links on mobile
    document.querySelectorAll('#sidebar a, #sidebar button').forEach(link => {
        link.addEventListener('click', (e) => {
            if (window.innerWidth < 1024 && !e.target.closest('button')) {
                setTimeout(closeSidebar, 150);
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.remove('sidebar-hidden');
            }
            closeSidebar();
        } else {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && !sidebar.classList.contains('sidebar-hidden')) {
                sidebar.classList.add('sidebar-hidden');
            }
        }
    });

    // Close sidebar when pressing ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.innerWidth < 1024) {
            closeSidebar();
        }
    });

    // Overlay click handler
    const overlay = document.getElementById('mobileOverlay');
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
</script>
