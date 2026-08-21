<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - @yield('title', 'Super Admin')</title>
    <script>
        (function () {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-background": "rgb(var(--c-on-surface) / <alpha-value>)",
                        "on-surface": "rgb(var(--c-on-surface) / <alpha-value>)",
                        "surface-container": "rgb(var(--c-sc) / <alpha-value>)",
                        "surface-variant": "rgb(var(--c-sc-highest) / <alpha-value>)",
                        "tertiary-fixed": "rgb(var(--c-sc-highest) / <alpha-value>)",
                        "secondary-container": "#fdd177",
                        "gold-accent": "#C9A24D",
                        "on-error": "rgb(var(--c-on-error) / <alpha-value>)",
                        "background": "rgb(var(--c-background) / <alpha-value>)",
                        "on-tertiary-container": "rgb(var(--c-on-muted) / <alpha-value>)",
                        "on-secondary-fixed-variant": "#5c4300",
                        "on-primary-fixed": "#1c1b1b",
                        "surface-dim": "rgb(var(--c-surface-dim) / <alpha-value>)",
                        "surface-container-high": "rgb(var(--c-sc-high) / <alpha-value>)",
                        "on-primary-fixed-variant": "#474646",
                        "on-secondary-fixed": "#261a00",
                        "error": "rgb(var(--c-error) / <alpha-value>)",
                        "on-primary-container": "rgb(var(--c-on-muted) / <alpha-value>)",
                        "tertiary": "rgb(var(--c-primary) / <alpha-value>)",
                        "surface-container-lowest": "rgb(var(--c-sc-lowest) / <alpha-value>)",
                        "on-tertiary-fixed-variant": "#464745",
                        "on-error-container": "rgb(var(--c-on-error-container) / <alpha-value>)",
                        "primary-fixed-dim": "rgb(var(--c-primary-fixed-dim) / <alpha-value>)",
                        "error-container": "rgb(var(--c-error-container) / <alpha-value>)",
                        "surface-container-highest": "rgb(var(--c-sc-highest) / <alpha-value>)",
                        "surface-container-low": "rgb(var(--c-sc-low) / <alpha-value>)",
                        "primary-container": "#1c1b1b",
                        "outline-variant": "rgb(var(--c-outline-variant) / <alpha-value>)",
                        "inverse-primary": "rgb(var(--c-primary-fixed-dim) / <alpha-value>)",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed-dim": "#ebc168",
                        "warm-bg": "rgb(var(--c-background) / <alpha-value>)",
                        "on-primary": "rgb(var(--c-on-primary) / <alpha-value>)",
                        "primary": "rgb(var(--c-primary) / <alpha-value>)",
                        "tertiary-container": "rgb(var(--c-tertiary-container) / <alpha-value>)",
                        "secondary": "rgb(var(--c-secondary) / <alpha-value>)",
                        "surface": "rgb(var(--c-surface) / <alpha-value>)",
                        "secondary-fixed": "#ffdf9f",
                        "inverse-on-surface": "rgb(var(--c-inverse-on-surface) / <alpha-value>)",
                        "on-tertiary-fixed": "#1a1c1a",
                        "tertiary-fixed-dim": "#c7c6c4",
                        "surface-bright": "rgb(var(--c-background) / <alpha-value>)",
                        "muted-border": "rgb(var(--c-border) / <alpha-value>)",
                        "surface-tint": "rgb(var(--c-surface-tint) / <alpha-value>)",
                        "outline": "rgb(var(--c-outline) / <alpha-value>)",
                        "primary-fixed": "rgb(var(--c-primary-fixed) / <alpha-value>)",
                        "deep-onyx": "rgb(var(--c-deep-onyx) / <alpha-value>)",
                        "on-surface-variant": "rgb(var(--c-on-surface-variant) / <alpha-value>)",
                        "on-secondary": "#ffffff",
                        "inverse-surface": "rgb(var(--c-inverse-surface) / <alpha-value>)",
                        "on-secondary-container": "#775804",
                        "sidebar": "rgb(var(--c-sidebar) / <alpha-value>)",
                        "scrim": "rgb(var(--c-scrim) / <alpha-value>)"
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
        :root {
            --c-background: 251 249 249;
            --c-surface: 251 249 249;
            --c-surface-dim: 219 218 217;
            --c-sc-lowest: 255 255 255;
            --c-sc-low: 245 243 243;
            --c-sc: 239 237 237;
            --c-sc-high: 233 232 231;
            --c-sc-highest: 227 226 226;
            --c-primary-fixed: 229 226 225;
            --c-primary-fixed-dim: 200 198 197;
            --c-inverse-on-surface: 242 240 240;
            --c-inverse-surface: 48 48 49;
            --c-on-surface: 27 28 28;
            --c-on-surface-variant: 68 71 72;
            --c-on-muted: 133 131 131;
            --c-outline: 116 120 120;
            --c-outline-variant: 196 199 199;
            --c-border: 233 232 231;
            --c-primary: 0 0 0;
            --c-deep-onyx: 17 17 17;
            --c-on-primary: 255 255 255;
            --c-secondary: 121 89 5;
            --c-error: 186 26 26;
            --c-on-error: 255 255 255;
            --c-error-container: 255 218 214;
            --c-on-error-container: 147 0 10;
            --c-tertiary-container: 26 28 26;
            --c-surface-tint: 95 94 94;
            --c-sidebar: 17 17 17;
            --c-scrim: 0 0 0;
        }

        .dark {
            --c-background: 14 14 14;
            --c-surface: 14 14 14;
            --c-surface-dim: 8 8 8;
            --c-sc-lowest: 20 20 20;
            --c-sc-low: 26 26 26;
            --c-sc: 33 33 33;
            --c-sc-high: 41 41 41;
            --c-sc-highest: 51 51 51;
            --c-primary-fixed: 44 44 44;
            --c-primary-fixed-dim: 64 64 64;
            --c-inverse-on-surface: 30 30 30;
            --c-inverse-surface: 238 236 236;
            --c-on-surface: 240 238 238;
            --c-on-surface-variant: 186 184 184;
            --c-on-muted: 178 176 176;
            --c-outline: 138 141 141;
            --c-outline-variant: 46 46 46;
            --c-border: 34 34 34;
            --c-primary: 240 238 238;
            --c-deep-onyx: 240 238 238;
            --c-on-primary: 17 17 17;
            --c-secondary: 235 193 104;
            --c-error: 255 179 171;
            --c-on-error: 60 14 12;
            --c-error-container: 93 26 22;
            --c-on-error-container: 255 218 214;
            --c-tertiary-container: 74 74 74;
            --c-surface-tint: 170 168 168;
            --c-sidebar: 28 28 28;
        }

        body { background-color: rgb(var(--c-background)); }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        @keyframes drawLine { to { stroke-dashoffset: 0; } }
        .animate-line { stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: drawLine 2s ease-out forwards; }
        .sidebar-scroll { scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.18) transparent; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.18); border-radius: 9999px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
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
        <div class="flex items-center gap-2">
            <button type="button" class="theme-toggle text-on-surface hover:opacity-80 transition-opacity" aria-label="Ganti tema">
                <span class="material-symbols-outlined icon-moon">dark_mode</span>
                <span class="material-symbols-outlined icon-sun hidden">light_mode</span>
            </button>
            <button class="relative text-on-surface hover:text-secondary transition-colors" aria-label="Notifikasi">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full"></span>
            </button>
            @include('partials.profile-menu', ['compact' => true])
        </div>
    </header>

    <!-- Side Navigation Drawer -->
    <aside id="sidebar" class="flex fixed md:sticky top-0 left-0 z-50 flex-col h-screen pt-section-gap pb-[88px] md:pb-section-gap px-container-margin w-64 border-r border-white/10 bg-sidebar -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="mb-12 flex items-center gap-3">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo Raliva" class="w-11 h-11 rounded-xl shrink-0" />
            <div>
                <span class="font-display-lg text-title-md text-white tracking-widest block leading-tight">RALIVA</span>
                <span class="text-gold-accent/80 font-label-sm text-[10px] uppercase tracking-wider">Super Admin</span>
            </div>
        </div>
        <nav class="sidebar-scroll flex-1 overflow-y-auto">
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
                <button type="button" class="theme-toggle text-on-surface hover:text-secondary transition-colors" aria-label="Ganti tema">
                    <span class="material-symbols-outlined icon-moon">dark_mode</span>
                    <span class="material-symbols-outlined icon-sun hidden">light_mode</span>
                </button>
                <button class="relative text-on-surface hover:text-secondary transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full"></span>
                </button>
                @include('partials.profile-menu')
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
    @php
        $bottomNav = [
            ['route' => 'superadmin.dashboard', 'icon' => 'dashboard', 'label' => 'Beranda'],
            ['route' => 'superadmin.data-pesanan', 'icon' => 'shopping_cart', 'label' => 'Pesanan'],
            ['route' => 'superadmin.moderasi-produk', 'icon' => 'inventory_2', 'label' => 'Moderasi'],
            ['route' => 'superadmin.laporan', 'icon' => 'bar_chart', 'label' => 'Laporan'],
            ['route' => 'superadmin.profil', 'icon' => 'person', 'label' => 'Profil'],
        ];
    @endphp
    <nav class="md:hidden flex justify-around items-center w-full h-[72px] bg-surface border-t border-outline-variant px-xs pb-safe fixed bottom-0 z-50 shadow-sm">
        @foreach ($bottomNav as $item)
            @php
                $isActive = request()->routeIs($item['route']);
            @endphp
            <a class="flex flex-col items-center justify-center {{ $isActive ? 'text-secondary' : 'text-on-surface-variant hover:text-secondary transition-colors' }}" href="{{ route($item['route']) }}">
                <span class="material-symbols-outlined {{ $isActive ? 'fill' : '' }}">{{ $item['icon'] }}</span>
                <span class="font-label-sm text-label-sm mt-1">{{ $item['label'] }}</span>
            </a>
        @endforeach
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
            if (e.key === 'Escape') {
                closeSidebar();
                document.querySelectorAll('[data-profile-menu]').forEach((m) => m.classList.add('hidden'));
            }
        });

        const allProfileMenus = document.querySelectorAll('[data-profile-menu]');

        document.querySelectorAll('[data-profile-toggle]').forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const menu = btn.parentElement.querySelector('[data-profile-menu]');
                allProfileMenus.forEach((m) => {
                    if (m !== menu) m.classList.add('hidden');
                });
                menu?.classList.toggle('hidden');
            });
        });

        document.addEventListener('click', (e) => {
            document.querySelectorAll('[data-profile-container]').forEach((container) => {
                if (!container.contains(e.target)) {
                    container.querySelector('[data-profile-menu]')?.classList.add('hidden');
                }
            });
        });

        const updateThemeIcons = () => {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.theme-toggle .icon-moon').forEach((el) => el.classList.toggle('hidden', isDark));
            document.querySelectorAll('.theme-toggle .icon-sun').forEach((el) => el.classList.toggle('hidden', !isDark));
        };

        document.querySelectorAll('.theme-toggle').forEach((btn) => {
            btn.addEventListener('click', () => {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                updateThemeIcons();
            });
        });

        updateThemeIcons();

        document.querySelectorAll('[data-sidebar-group-button]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const group = btn.parentElement.querySelector('[data-sidebar-group]');
                group?.classList.toggle('hidden');
                btn.querySelector('.material-symbols-outlined')?.classList.toggle('rotate-180');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>