<!-- Mobile Overlay -->
<div id="mobileOverlay"
    class="fixed inset-0 bg-black/50 z-20 opacity-0 invisible transition-all duration-300 sidebar-overlay lg:hidden"
    onclick="closeSidebar()"></div>

<div class="flex min-h-screen relative">
    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="mobile-sidebar fixed lg:relative z-30 w-72 bg-white shadow-xl flex flex-col border-r border-slate-200 h-full overflow-y-auto sidebar-hidden lg:translate-x-0 transition-transform duration-300">
        <!-- Brand Area -->
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

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-1.5">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl @yield('dashboard-active', 'text-slate-600 hover:bg-gray-100') @if (Request::routeIs('dashboard')) bg-gray-100 text-gray-800 shadow-sm @endif transition">
                <i class="fas fa-tachometer-alt w-5 text-gray-600"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('peminjaman.index') }}"
                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-600 hover:bg-gray-100 transition">
                <i class="fas fa-hand-holding-usd w-5 text-gray-500"></i>
                <span>Peminjaman</span>
            </a>
            <a href="{{ route('barang.index') }}"
                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-600 hover:bg-gray-100 transition">
                <i class="fas fa-boxes w-5 text-gray-500"></i>
                <span>Barang</span>
            </a>
            <a href="{{ route('keuangan.index') }}"
                class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-600 hover:bg-gray-100 transition">
                <i class="fas fa-coins w-5 text-gray-500"></i>
                <span>Keuangan</span>
            </a>
        </nav>

        <!-- Promo Button -->
        <div class="p-5 border-t border-slate-100">
            <button onclick="showPromoModal()"
                class="w-full flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2.5 px-4 rounded-xl shadow-md transition-all duration-200">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Promo/Inventaris</span>
            </button>
        </div>
    </aside>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="flex-1 w-full min-w-0">
        <!-- Top Header -->
        <div
            class="bg-white/80 backdrop-blur-sm sticky top-0 z-20 border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 py-3 sm:py-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <button id="mobileMenuBtn"
                    class="lg:hidden p-2 -ml-2 rounded-lg text-slate-600 hover:bg-slate-100 transition"
                    onclick="openSidebar()">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-slate-800 tracking-tight">@yield('page-title', 'Dashboard Overview')</h2>
                    <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mt-0.5">
                        <i class="far fa-calendar-alt"></i>
                        <span id="currentDateSpan"></span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Notifications -->
                <div class="relative cursor-pointer" onclick="showNotifications()">
                    <i class="far fa-bell text-slate-400 text-lg sm:text-xl hover:text-slate-600"></i>
                    <span
                        class="absolute -top-1 -right-1.5 h-2.5 w-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                </div>
                
                <!-- User Dropdown -->
                <div class="relative group">
                    <div
                        class="flex items-center gap-2 bg-white rounded-full shadow-sm pl-2 pr-3 py-1 border border-slate-200 cursor-pointer">
                        <div
                            class="h-7 w-7 sm:h-8 sm:w-8 rounded-full bg-gradient-to-br from-gray-500 to-gray-700 flex items-center justify-center text-white text-xs font-bold">
                            {{ Auth::user() ? strtoupper(substr(Auth::user()->username, 0, 2)) : 'GU' }}
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-xs sm:text-sm font-medium text-slate-700">
                                {{ Auth::user() ? Auth::user()->name : 'Guest' }}
                            </span>
                            <span class="text-xs text-slate-400 block -mt-0.5">
                                @ {{ Auth::user() ? Auth::user()->username : 'guest' }}
                            </span>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                    </div>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-800">{{ Auth::user() ? Auth::user()->name : '' }}</p>
                            <p class="text-xs text-slate-500">@ {{ Auth::user() ? Auth::user()->username : '' }}</p>
                        </div>
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                            <i class="fas fa-user-circle"></i> Profile
                        </a>
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt"></i> Logout
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