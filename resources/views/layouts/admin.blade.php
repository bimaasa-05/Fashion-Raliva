<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="user-id" content="{{ Auth::id() }}" />
    <title>RALIVA - @yield('title', 'Admin Toko')</title>
    @include('partials.theme-head')
    <style>
        /* Admin RALIVA burgundy — scoped only to this layout (hanya Admin) — mirror Customer/Super Admin/Owner #8B1E3F */
        :root {
            --c-secondary: 139 30 63;
            --color-secondary-container: #8B1E3F;
            --color-secondary-fixed: #8B1E3F;
            --color-secondary-fixed-dim: #8B1E3F;
            --color-on-secondary-fixed-variant: #6D1428;
            --color-on-secondary-fixed: #6D1428;
            --color-on-secondary-container: #6D1428;
            --color-gold-accent: #8B1E3F;
        }
        .dark {
            --c-secondary: 139 30 63;
        }
        .card-premium:hover { border-color: rgb(139 30 63 / 0.45) !important; }
        .premium-heading::before { background: #8B1E3F !important; }
        .premium-table tbody tr td:first-child::before { background: #8B1E3F !important; }
        .text-gradient-gold { background: linear-gradient(115deg, #6D1428 0%, #8B1E3F 35%, #c03a5a 55%, #8B1E3F 80%, #6D1428 100%) !important; -webkit-background-clip: text !important; background-clip: text !important; color: transparent !important; }
        .hero-glow::before { background: radial-gradient(circle at 70% 30%, rgba(139, 30, 63, 0.14), transparent 45%), radial-gradient(circle at 15% 85%, rgba(139, 30, 63, 0.08), transparent 40%) !important; }
        .gauge-progress { filter: drop-shadow(0 0 6px rgba(139, 30, 63, 0.45)) !important; }
        #sidebar-tip-global { border-color: rgba(139, 30, 63, .45) !important; }
        .bg-gold-accent { background-color: #8B1E3F !important; }
        .bg-gold-accent\/5 { background-color: rgba(139,30,63,0.05) !important; }
        .bg-gold-accent\/10 { background-color: rgba(139,30,63,0.1) !important; }
        .bg-gold-accent\/15 { background-color: rgba(139,30,63,0.15) !important; }
        .bg-gold-accent\/20 { background-color: rgba(139,30,63,0.2) !important; }
        .bg-gold-accent\/25 { background-color: rgba(139,30,63,0.25) !important; }
        .bg-gold-accent\/30 { background-color: rgba(139,30,63,0.3) !important; }
        .bg-gold-accent\/40 { background-color: rgba(139,30,63,0.4) !important; }
        .bg-gold-accent\/50 { background-color: rgba(139,30,63,0.5) !important; }
        .bg-gold-accent\/60 { background-color: rgba(139,30,63,0.6) !important; }
        .bg-gold-accent\/70 { background-color: rgba(139,30,63,0.7) !important; }
        .text-gold-accent { color: #8B1E3F !important; }
        .text-gold-accent\/5 { color: rgba(139,30,63,0.05) !important; }
        .text-gold-accent\/10 { color: rgba(139,30,63,0.1) !important; }
        .text-gold-accent\/15 { color: rgba(139,30,63,0.15) !important; }
        .text-gold-accent\/20 { color: rgba(139,30,63,0.2) !important; }
        .text-gold-accent\/25 { color: rgba(139,30,63,0.25) !important; }
        .text-gold-accent\/30 { color: rgba(139,30,63,0.3) !important; }
        .text-gold-accent\/40 { color: rgba(139,30,63,0.4) !important; }
        .text-gold-accent\/50 { color: rgba(139,30,63,0.5) !important; }
        .text-gold-accent\/60 { color: rgba(139,30,63,0.6) !important; }
        .text-gold-accent\/70 { color: rgba(139,30,63,0.7) !important; }
        .text-gold-accent\/80 { color: rgba(139,30,63,0.8) !important; }
        .border-gold-accent { border-color: #8B1E3F !important; }
        .border-gold-accent\/10 { border-color: rgba(139,30,63,0.1) !important; }
        .border-gold-accent\/15 { border-color: rgba(139,30,63,0.15) !important; }
        .border-gold-accent\/20 { border-color: rgba(139,30,63,0.2) !important; }
        .border-gold-accent\/25 { border-color: rgba(139,30,63,0.25) !important; }
        .border-gold-accent\/30 { border-color: rgba(139,30,63,0.3) !important; }
        .border-gold-accent\/40 { border-color: rgba(139,30,63,0.4) !important; }
        .border-gold-accent\/50 { border-color: rgba(139,30,63,0.5) !important; }
        .border-gold-accent\/60 { border-color: rgba(139,30,63,0.6) !important; }
        .border-gold-accent\/70 { border-color: rgba(139,30,63,0.7) !important; }
        .border-t-gold-accent\/70 { border-top-color: rgba(139,30,63,0.7) !important; }
        .text-gold-accent\/\[0\.06\] { color: rgba(139,30,63,0.06) !important; }
        .bg-gold-accent\/\[0\.06\] { background-color: rgba(139,30,63,0.06) !important; }
        .bg-secondary { background-color: rgb(139 30 63) !important; }
        .bg-secondary\/10 { background-color: rgba(139,30,63,0.1) !important; }
        .bg-secondary\/15 { background-color: rgba(139,30,63,0.15) !important; }
        .bg-secondary\/20 { background-color: rgba(139,30,63,0.2) !important; }
        .bg-secondary\/30 { background-color: rgba(139,30,63,0.3) !important; }
        .bg-secondary-container { background-color: #8B1E3F !important; }
        .bg-secondary-container\/10 { background-color: rgba(139,30,63,0.1) !important; }
        .bg-secondary-container\/15 { background-color: rgba(139,30,63,0.15) !important; }
        .bg-secondary-container\/20 { background-color: rgba(139,30,63,0.2) !important; }
        .bg-secondary-container\/25 { background-color: rgba(139,30,63,0.25) !important; }
        .bg-secondary-container\/30 { background-color: rgba(139,30,63,0.3) !important; }
        .bg-secondary-fixed { background-color: #8B1E3F !important; }
        .bg-secondary-fixed-dim { background-color: #8B1E3F !important; }
        .text-secondary { color: rgb(139 30 63) !important; }
        .text-secondary\/70 { color: rgba(139,30,63,0.7) !important; }
        .text-on-secondary-container { color: #6D1428 !important; }
        .text-on-secondary-fixed { color: #6D1428 !important; }
        .text-on-secondary-fixed-variant { color: #6D1428 !important; }
        .border-secondary { border-color: rgb(139 30 63) !important; }
        .border-secondary\/20 { border-color: rgba(139,30,63,0.2) !important; }
        .border-secondary\/30 { border-color: rgba(139,30,63,0.3) !important; }
        .border-secondary-container { border-color: #8B1E3F !important; }
        .border-secondary-container\/20 { border-color: rgba(139,30,63,0.2) !important; }
        .border-secondary-container\/30 { border-color: rgba(139,30,63,0.3) !important; }
        .hover\:bg-gold-accent:hover { background-color: #8B1E3F !important; }
        .hover\:text-gold-accent:hover { color: #8B1E3F !important; }
        .hover\:border-gold-accent:hover { border-color: #8B1E3F !important; }
        .focus\:border-gold-accent:focus { border-color: #8B1E3F !important; }
        .focus\:ring-gold-accent:focus { --tw-ring-color: rgba(139,30,63,0.1) !important; }
        .group:hover .group-hover\:text-gold-accent { color: #8B1E3F !important; }
        .group:hover .group-hover\:border-gold-accent { border-color: #8B1E3F !important; }
        .group\/row:hover .group-hover\/row\:text-gold-accent { color: #8B1E3F !important; }
        .from-gold-accent { --tw-gradient-from: #8B1E3F !important; --tw-gradient-to: rgb(139 30 63 / 0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-gold-accent\/5 { --tw-gradient-from: rgba(139,30,63,0.05) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-gold-accent\/10 { --tw-gradient-from: rgba(139,30,63,0.1) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-gold-accent\/15 { --tw-gradient-from: rgba(139,30,63,0.15) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-gold-accent\/20 { --tw-gradient-from: rgba(139,30,63,0.2) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-gold-accent\/25 { --tw-gradient-from: rgba(139,30,63,0.25) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .via-gold-accent\/10 { --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), rgba(139,30,63,0.1), var(--tw-gradient-to) !important; }
        .from-gold-accent\/45 { --tw-gradient-from: rgba(139,30,63,0.45) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-gold-accent\/40 { --tw-gradient-from: rgba(139,30,63,0.4) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-gold-accent\/60 { --tw-gradient-from: rgba(139,30,63,0.6) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-secondary-container { --tw-gradient-from: #8B1E3F !important; --tw-gradient-to: rgb(139 30 63 / 0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-secondary-container\/15 { --tw-gradient-from: rgba(139,30,63,0.15) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-secondary-container\/20 { --tw-gradient-from: rgba(139,30,63,0.2) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .from-secondary-container\/30 { --tw-gradient-from: rgba(139,30,63,0.3) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .to-gold-accent { --tw-gradient-to: #8B1E3F !important; }
        .to-gold-accent\/5 { --tw-gradient-to: rgba(139,30,63,0.05) !important; }
        .via-gold-accent\/5 { --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), rgba(139,30,63,0.05), var(--tw-gradient-to) !important; }
        .via-gold-accent\/40 { --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), rgba(139,30,63,0.4), var(--tw-gradient-to) !important; }
        .from-secondary-container\/5 { --tw-gradient-from: rgba(139,30,63,0.05) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .to-secondary-container\/5 { --tw-gradient-to: rgba(139,30,63,0.05) !important; }
        .hover\:from-gold-accent\/70:hover { --tw-gradient-from: rgba(139,30,63,0.7) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
        .hover\:shadow-\[0_0_12px_rgba\(201\,162\,77\,0\.35\)\]:hover { --tw-shadow: 0 0 12px rgba(139,30,63,0.35) !important; --tw-shadow-colored: 0 0 12px rgba(139,30,63,0.35) !important; box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow) !important; }
        .drop-shadow-\[0_0_6px_rgba\(201\,162\,77\,0\.35\)\] { --tw-drop-shadow: drop-shadow(0 0 6px rgba(139,30,63,0.35)) !important; filter: var(--tw-blur) var(--tw-brightness) var(--tw-contrast) var(--tw-grayscale) var(--tw-hue-rotate) var(--tw-invert) var(--tw-saturate) var(--tw-sepia) var(--tw-drop-shadow) !important; }
        .timeline-line::before { background: linear-gradient(to bottom, rgba(139,30,63,0.55), rgba(139,30,63,0.06)) !important; }
        .border-l-gold-accent { border-left-color: #8B1E3F !important; }
        .shadow-\[0_0_0_3px_rgba\(201\,162\,77\,0\.08\)\] { --tw-shadow: 0 0 0 3px rgba(139,30,63,0.08) !important; --tw-shadow-colored: 0 0 0 3px rgba(139,30,63,0.08) !important; box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow) !important; }
        .from-gold-accent\/70 { --tw-gradient-from: rgba(139,30,63,0.7) !important; --tw-gradient-to: rgba(139,30,63,0) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important; }
    </style>
</head>
<body class="text-on-background font-body-md antialiased min-h-screen flex flex-col">
    <div id="app-shell" class="flex-1 min-w-0 flex flex-col md:flex-row">
    <!-- Mobile Nav (TopAppBar) -->
    <header class="md:hidden flex justify-between items-center w-full px-container-margin h-16 bg-surface border-b border-outline-variant sticky top-0 z-40">
        <button id="sidebar-toggle" class="text-on-surface hover:opacity-80 transition-opacity">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <span class="font-display-lg text-headline-md tracking-widest text-on-surface">RALIVA</span>
        <div class="flex items-center gap-2">
            <button type="button" class="theme-toggle text-on-surface hover:opacity-80 transition-opacity" aria-label="Ganti tema">
                <span class="material-symbols-outlined" data-theme-icon>light_mode</span>
            </button>
            @include('partials.notification-panel', ['lihatSemuaRoute' => 'admin.notifikasi'])
            @php $adminHeaderUserM = Auth::user(); @endphp
            @include('partials.profile-menu', ['compact' => true, 'name' => $adminHeaderUserM?->nama_lengkap ?? 'Admin Toko', 'role' => $adminHeaderUserM?->role?->nama_role ?? 'Admin Toko', 'profilRoute' => 'admin.profil', 'showPengaturan' => false])
        </div>
    </header>

    <!-- Side Navigation Drawer -->
    <aside id="sidebar" class="flex fixed md:sticky md:self-start top-0 left-0 z-50 flex-col h-screen shrink-0 pt-4 pb-[88px] md:pb-section-gap px-container-margin w-72 border-r border-sidebar-border bg-sidebar -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out">
        <div class="sidebar-head flex items-center justify-between gap-3 pt-1 pb-3">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ asset('images/logo-raliva.png') }}" alt="Logo Raliva" class="w-11 h-11 rounded-xl shrink-0" />
                <div data-sidebar-text>
                    <span class="font-display-lg text-title-md text-on-sidebar tracking-widest block leading-tight">RALIVA</span>
                    <span class="text-gold-accent/80 font-label-sm text-[10px] uppercase tracking-wider">Admin Toko</span>
                </div>
            </div>
            <button type="button" id="sidebar-collapse" aria-expanded="true" aria-label="Perkecil menu sidebar" class="sidebar-collapse-btn hidden md:inline-flex w-8 h-8 rounded-lg border border-transparent hover:border-gold-accent/40 hover:bg-gold-accent/10 text-gold-accent/70 hover:text-gold-accent items-center justify-center transition-colors shrink-0">
                <span class="material-symbols-outlined icon-chevron text-[18px] transition-transform duration-300">chevron_left</span>
            </button>
        </div>
        {{-- Lane pembatas brand vs profile --}}
        <div class="h-px bg-sidebar-border/70 mx-2 my-2 shrink-0" aria-hidden="true"></div>
        {{-- Sidebar Profile — optimize --}}
        <div class="sidebar-profile flex items-center gap-3 px-4 py-3.5 mx-2 rounded-xl bg-surface-container-low border border-sidebar-border/60 shadow-sm shrink-0">
            @php
                $sbUserA = Auth::user();
                $sbNameA = $sbUserA?->nama_lengkap ?? 'Admin Toko';
                $sbRoleA = $sbUserA?->role?->nama_role ?? 'Admin Toko';
                $wA = preg_split('/\s+/', trim($sbNameA));
                $iA = '';
                if (!empty($wA[0])) $iA .= mb_substr($wA[0], 0, 1);
                if (isset($wA[1])) $iA .= mb_substr($wA[1], 0, 1);
                elseif (mb_strlen($wA[0] ?? '') > 1) $iA .= mb_substr($wA[0], 1, 1);
                $initA = strtoupper(mb_substr($iA, 0, 2)) ?: '?';
            @endphp
            <div class="w-11 h-11 rounded-full bg-gold-accent text-white flex items-center justify-center font-bold text-[15px] shrink-0 border-2 border-white shadow-sm ring-1 ring-gold-accent/20">{{ $initA }}</div>
            <div class="min-w-0 flex-1" data-sidebar-text>
                <h4 class="text-[13px] font-bold text-on-sidebar truncate leading-tight">{{ $sbNameA }}</h4>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gold-accent text-white text-[10px] font-bold uppercase tracking-wider truncate shadow-sm mt-1">{{ $sbRoleA }}</span>
            </div>
        </div>
        <nav class="sidebar-scroll flex-1 min-h-0 overflow-y-auto">
            @include('partials.sidebar-menu-admin')
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
                    <h1 class="font-title-md text-title-md text-on-surface">@yield('header-title', 'Dashboard')</h1>
                    @hasSection('header-badge')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-label-sm uppercase tracking-wider">@yield('header-badge')</span>
                    @endif
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">@yield('header-subtitle', 'Ini yang terjadi hari ini.')</p>
            </div>
            <div class="flex items-center gap-6">
                <button type="button" class="theme-toggle text-on-surface hover:text-secondary transition-colors" aria-label="Ganti tema">
                    <span class="material-symbols-outlined" data-theme-icon>light_mode</span>
                </button>
                @include('partials.notification-panel', ['lihatSemuaRoute' => 'admin.notifikasi'])
                @php $adminHeaderUserD = Auth::user(); @endphp
                @include('partials.profile-menu', ['name' => $adminHeaderUserD?->nama_lengkap ?? 'Admin Toko', 'role' => $adminHeaderUserD?->role?->nama_role ?? 'Admin Toko', 'profilRoute' => 'admin.profil', 'showPengaturan' => false])
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

        <div class="px-container-margin pt-8 pb-section-gap flex flex-col gap-section-gap max-w-[1500px] mx-auto w-full">
            @yield('content')
        </div>
    </main>
    </div>

    <!-- Bottom Nav Bar (Mobile) -->
    @include('partials.bottom-nav', ['items' => [
        ['route' => 'admin.dashboard', 'icon' => 'space_dashboard', 'label' => 'Beranda'],
        ['route' => 'admin.pesanan', 'icon' => 'shopping_cart', 'label' => 'Pesanan'],
        ['route' => 'admin.pengiriman', 'icon' => 'local_shipping', 'label' => 'Kirim'],
        ['route' => 'admin.komplain', 'icon' => 'support_agent', 'label' => 'Komplain'],
        ['route' => 'admin.profil', 'icon' => 'person', 'label' => 'Profil'],
    ]])

    @stack('modals')
    @include('partials.notification-popup')
    @include('partials.layout-scripts')
    @include('partials.ui-scripts')
    @stack('scripts')
</body>
</html>
