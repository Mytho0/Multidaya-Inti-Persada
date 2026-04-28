<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Multidaya Inti Persada | Dashboard Mobile Adaptive')</title>

    <!-- Tailwind CSS v3 + Font Awesome 6 + Google Fonts Inter -->
    @vite(['resources/css/app.css'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #9ca3af;
            border-radius: 10px;
        }

        .mobile-sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-overlay {
            transition: opacity 0.3s ease;
        }

        button,
        a,
        .clickable {
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        @media (max-width: 768px) {
            .stats-card:active {
                transform: scale(0.98);
            }
        }
    </style>

    @stack('styles')
</head>

<body class="bg-linear-to-br from-slate-100 to-slate-200/70 antialiased">
    <div id="app">
        @include('layouts.navbar')

        <main>
            @yield('content')
        </main>
    </div>

    <script>
        // Global sidebar toggle functions
        window.openSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.remove('sidebar-hidden');
            overlay.classList.remove('invisible', 'opacity-0');
            overlay.classList.add('visible', 'opacity-100');
            document.body.style.overflow = 'hidden';
        }

        window.closeSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.add('sidebar-hidden');
            overlay.classList.add('invisible', 'opacity-0');
            overlay.classList.remove('visible', 'opacity-100');
            document.body.style.overflow = '';
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar) sidebar.classList.remove('sidebar-hidden');
                window.closeSidebar();
            } else {
                const sidebar = document.getElementById('sidebar');
                if (sidebar && !sidebar.classList.contains('sidebar-hidden')) {
                    sidebar.classList.add('sidebar-hidden');
                }
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
