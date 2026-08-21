<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - @yield('title', 'Super Admin')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-background": "#1b1c1c",
                        "on-surface": "#1b1c1c",
                        "surface-container": "#efeded",
                        "surface-variant": "#e3e2e2",
                        "tertiary-fixed": "#e3e2df",
                        "secondary-container": "#fdd177",
                        "gold-accent": "#C9A24D",
                        "on-error": "#ffffff",
                        "background": "#fbf9f9",
                        "on-tertiary-container": "#848482",
                        "on-secondary-fixed-variant": "#5c4300",
                        "on-primary-fixed": "#1c1b1b",
                        "surface-dim": "#dbdad9",
                        "surface-container-high": "#e9e8e7",
                        "on-primary-fixed-variant": "#474646",
                        "on-secondary-fixed": "#261a00",
                        "error": "#ba1a1a",
                        "on-primary-container": "#858383",
                        "tertiary": "#000000",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed-variant": "#464745",
                        "on-error-container": "#93000a",
                        "primary-fixed-dim": "#c8c6c5",
                        "error-container": "#ffdad6",
                        "surface-container-highest": "#e3e2e2",
                        "surface-container-low": "#f5f3f3",
                        "primary-container": "#1c1b1b",
                        "outline-variant": "#c4c7c7",
                        "inverse-primary": "#c8c6c5",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed-dim": "#ebc168",
                        "warm-bg": "#FBF9F9",
                        "on-primary": "#ffffff",
                        "primary": "#000000",
                        "tertiary-container": "#1a1c1a",
                        "secondary": "#795905",
                        "surface": "#fbf9f9",
                        "secondary-fixed": "#ffdf9f",
                        "inverse-on-surface": "#f2f0f0",
                        "on-tertiary-fixed": "#1a1c1a",
                        "tertiary-fixed-dim": "#c7c6c4",
                        "surface-bright": "#fbf9f9",
                        "muted-border": "#E9E8E7",
                        "surface-tint": "#5f5e5e",
                        "outline": "#747878",
                        "primary-fixed": "#e5e2e1",
                        "deep-onyx": "#111111",
                        "on-surface-variant": "#444748",
                        "on-secondary": "#ffffff",
                        "inverse-surface": "#303031",
                        "on-secondary-container": "#775804"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "element-gap": "12px",
                        "gutter": "16px",
                        "unit": "4px",
                        "section-gap": "64px",
                        "container-margin": "24px"
                    },
                    fontFamily: {
                        "label-sm": ["Manrope"],
                        "body-md": ["Manrope"],
                        "headline-lg": ["Playfair Display"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "title-md": ["Manrope"],
                        "display-lg": ["Playfair Display"]
                    },
                    fontSize: {
                        "label-sm": ["12px", { lineHeight: "1.0", letterSpacing: "0.1em", fontWeight: "700" }],
                        "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
                        "headline-lg": ["32px", { lineHeight: "1.2", fontWeight: "500" }],
                        "headline-lg-mobile": ["28px", { lineHeight: "1.2", fontWeight: "500" }],
                        "title-md": ["18px", { lineHeight: "1.4", letterSpacing: "0.01em", fontWeight: "600" }],
                        "display-lg": ["48px", { lineHeight: "1.1", letterSpacing: "-0.02em", fontWeight: "600" }]
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #fbf9f9; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        @keyframes drawLine { to { stroke-dashoffset: 0; } }
        .animate-line { stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: drawLine 2s ease-out forwards; }
    </style>
    @stack('styles')
</head>
<body class="text-on-background font-body-md antialiased min-h-screen flex flex-col md:flex-row">
    <!-- Mobile Nav (TopAppBar) -->
    <header class="md:hidden flex justify-between items-center w-full px-container-margin h-16 bg-surface border-b border-outline-variant sticky top-0 z-40">
        <button id="sidebar-toggle" class="text-on-surface hover:opacity-80 transition-opacity">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <span class="font-display-lg text-headline-md tracking-widest text-on-surface">RALIVA</span>
        <button class="text-on-surface hover:opacity-80 transition-opacity">
            <span class="material-symbols-outlined">search</span>
        </button>
    </header>

    <!-- Side Navigation Drawer -->
    <aside id="sidebar" class="flex fixed md:sticky top-0 left-0 z-50 flex-col h-screen pt-section-gap pb-[88px] md:pb-section-gap px-container-margin w-64 border-r border-white/10 bg-deep-onyx -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="mb-12">
            <span class="font-display-lg text-display-lg text-white tracking-widest block mb-8">RALIVA</span>
        </div>
        <nav class="flex-1 overflow-y-auto">
            @include('partials.sidebar-menu')
        </nav>
    </aside>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 md:hidden hidden opacity-0 transition-opacity duration-300"></div>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 pb-[72px] md:pb-0">
        <!-- Desktop Header -->
        <header class="hidden md:flex sticky top-0 z-40 justify-between items-center px-container-margin h-20 bg-surface-container-lowest border-b border-outline-variant">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="font-title-md text-title-md text-on-surface uppercase tracking-wider">@yield('header-title', 'Dashboard')</h1>
                    @hasSection('header-badge')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-label-sm uppercase tracking-wider">@yield('header-badge')</span>
                    @endif
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">@yield('header-subtitle', 'Ini yang terjadi hari ini.')</p>
            </div>
            <div class="flex items-center gap-6">
                <button class="relative text-on-surface hover:text-secondary transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full"></span>
                </button>
                <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant overflow-hidden">
                    <img alt="Admin Avatar" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuUf094pPsvMlxNz9CEzztLZIPfB4q2FE_6HM73O8sFoIt42FkBx43D1cxFlylMdSolVSJZNCBDrc8ttYGcVUIYXcsS0AUGBhcZYBAFGqcAXzmuJyVyjyJY6CXvyxdr0Zwzlwi2Tw3Djm9F2wtwaOLZklTUYLsRg7NCbF9hgI1uCTcTdgGi-0zShSJMzVkR1HYp_C02xOHHVWnGLI4_rrhbWQnSlrZ2VpmUbZL0Gc18YDjNwDrrkAcPg" />
                </div>
            </div>
        </header>

        <!-- Mobile Greeting -->
        <div class="md:hidden px-container-margin py-6">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">@yield('header-title', 'Dashboard')</h1>
                @hasSection('header-badge')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">@yield('header-badge')</span>
                @endif
            </div>
            <p class="text-on-surface-variant font-body-md mt-1">@yield('header-subtitle', 'Ini yang terjadi hari ini.')</p>
        </div>

        <div class="px-container-margin pt-8 pb-section-gap flex flex-col gap-section-gap max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Nav Bar (Mobile) -->
    <nav class="md:hidden flex justify-around items-center w-full h-[72px] bg-surface border-t border-outline-variant px-xs pb-safe fixed bottom-0 z-50 shadow-sm">
        <a class="flex flex-col items-center justify-center text-secondary" href="{{ route('superadmin.dashboard') }}">
            <span class="material-symbols-outlined fill">dashboard</span>
            <span class="font-label-sm text-label-sm mt-1">Home</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors" href="{{ route('superadmin.manajemen-toko') }}">
            <span class="material-symbols-outlined">storefront</span>
            <span class="font-label-sm text-label-sm mt-1">Stores</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors" href="{{ route('superadmin.laporan') }}">
            <span class="material-symbols-outlined">receipt_long</span>
            <span class="font-label-sm text-label-sm mt-1">Orders</span>
        </a>
        <a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors" href="{{ route('superadmin.manajemen-pengguna') }}">
            <span class="material-symbols-outlined">person</span>
            <span class="font-label-sm text-label-sm mt-1">Account</span>
        </a>
    </nav>

    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            requestAnimationFrame(() => sidebarOverlay.classList.remove('opacity-0'));
        };

        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('opacity-0');
            setTimeout(() => sidebarOverlay.classList.add('hidden'), 300);
        };

        sidebarToggle?.addEventListener('click', () => {
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });

        sidebarOverlay?.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>
    @stack('scripts')
</body>
</html>