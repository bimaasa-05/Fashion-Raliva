<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - @yield('title', 'Admin Toko')</title>
    @include('partials.theme-head')
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
            @include('partials.notification-panel')
            @include('partials.profile-menu', ['compact' => true, 'name' => 'Sinta Maharani', 'role' => 'Admin Toko', 'profilRoute' => 'admin.profil', 'showPengaturan' => false])
        </div>
    </header>

    <!-- Side Navigation Drawer -->
    <aside id="sidebar" class="flex fixed md:sticky top-0 left-0 z-50 flex-col h-screen pt-section-gap pb-[88px] md:pb-section-gap px-container-margin w-72 border-r border-sidebar-border bg-sidebar -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="sidebar-head mb-12 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo Raliva" class="w-11 h-11 rounded-xl shrink-0" />
                <div data-sidebar-text>
                    <span class="font-display-lg text-title-md text-on-sidebar tracking-widest block leading-tight">RALIVA</span>
                    <span class="text-gold-accent/80 font-label-sm text-[10px] uppercase tracking-wider">Admin Toko</span>
                </div>
            </div>
            <button type="button" id="sidebar-collapse" aria-expanded="true" aria-label="Perkecil menu sidebar" class="sidebar-collapse-btn hidden md:inline-flex w-8 h-8 rounded-lg border border-transparent hover:border-gold-accent/40 hover:bg-gold-accent/10 text-gold-accent/70 hover:text-gold-accent items-center justify-center transition-colors shrink-0">
                <span class="material-symbols-outlined icon-chevron text-[18px] transition-transform duration-300">chevron_left</span>
            </button>
        </div>
        <nav class="sidebar-scroll flex-1 overflow-y-auto">
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
                    <span class="material-symbols-outlined icon-moon">dark_mode</span>
                    <span class="material-symbols-outlined icon-sun hidden">light_mode</span>
                </button>
                @include('partials.notification-panel')
                @include('partials.profile-menu', ['name' => 'Sinta Maharani', 'role' => 'Admin Toko', 'profilRoute' => 'admin.profil', 'showPengaturan' => false])
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

    <!-- Bottom Nav Bar (Mobile) -->
    @include('partials.bottom-nav', ['items' => [
        ['route' => 'admin.dashboard', 'icon' => 'space_dashboard', 'label' => 'Beranda'],
        ['route' => 'admin.pesanan', 'icon' => 'shopping_cart', 'label' => 'Pesanan'],
        ['route' => 'admin.pengiriman', 'icon' => 'local_shipping', 'label' => 'Kirim'],
        ['route' => 'admin.komplain', 'icon' => 'support_agent', 'label' => 'Komplain'],
        ['route' => 'admin.profil', 'icon' => 'person', 'label' => 'Profil'],
    ]])

    @stack('modals')
    @include('partials.layout-scripts')
    @include('partials.ui-scripts')
    @stack('scripts')
</body>
</html>
