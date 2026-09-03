<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - @yield('title', 'Gudang')</title>
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
            @php
                $user = Auth::user();
                $notifications = $user ? \App\Models\Notification::where('user_id', $user->user_id)
                    ->orderByDesc('created_at')
                    ->take(3)
                    ->get()
                    ->map(function ($n) {
                        return [
                            'icon' => match ($n->tipe) {
                                \App\Models\Notification::TIPE_ORDER => 'archive',
                                \App\Models\Notification::TIPE_PENGIRIMAN => 'local_shipping',
                                \App\Models\Notification::TIPE_PEMBAYARAN => 'payments',
                                \App\Models\Notification::TIPE_KOMPLAIN => 'support_agent',
                                \App\Models\Notification::TIPE_WALLET => 'account_balance_wallet',
                                \App\Models\Notification::TIPE_PROMO => 'local_offer',
                                default => 'notifications',
                            },
                            'html' => $n->pesan,
                            'time' => $n->created_at?->diffForHumans() ?? '-',
                        ];
                    })->all() : [];
            @endphp
            @include('partials.notification-panel', ['items' => $notifications, 'lihatSemuaRoute' => 'gudang.notifikasi'])
            @include('partials.profile-menu', ['compact' => true, 'name' => $user?->nama_lengkap ?? 'User', 'role' => $user?->role?->nama_role ?? 'Gudang', 'profilRoute' => 'gudang.profil', 'showPengaturan' => false])
        </div>
    </header>

    <!-- Side Navigation Drawer -->
    <aside id="sidebar" class="flex fixed md:sticky top-0 left-0 z-50 flex-col h-screen pt-4 pb-[88px] md:pb-section-gap px-container-margin w-72 border-r border-sidebar-border bg-sidebar -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="sidebar-head flex items-center justify-between gap-3 pt-1 pb-3">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ asset('images/logo-raliva.png') }}" alt="Logo Raliva" class="w-11 h-11 rounded-xl shrink-0" />
                <div data-sidebar-text>
                    <span class="font-display-lg text-title-md text-on-sidebar tracking-widest block leading-tight">RALIVA</span>
                    <span class="text-gold-accent/80 font-label-sm text-[10px] uppercase tracking-wider">Gudang</span>
                </div>
            </div>
            <button type="button" id="sidebar-collapse" aria-expanded="true" aria-label="Perkecil menu sidebar" class="sidebar-collapse-btn hidden md:inline-flex w-8 h-8 rounded-lg border border-transparent hover:border-gold-accent/40 hover:bg-gold-accent/10 text-gold-accent/70 hover:text-gold-accent items-center justify-center transition-colors shrink-0">
                <span class="material-symbols-outlined icon-chevron text-[18px] transition-transform duration-300">chevron_left</span>
            </button>
        </div>
        <div class="h-px bg-sidebar-border/70 mx-2 my-2 shrink-0" aria-hidden="true"></div>
        <div class="sidebar-profile flex items-center gap-3 px-4 py-3.5 mx-2 rounded-xl bg-surface-container-low border border-sidebar-border/60 shadow-sm shrink-0">
            @php
                $sbUserG = Auth::user();
                $sbNameG = $sbUserG?->nama_lengkap ?? 'Gudang';
                $sbRoleG = $sbUserG?->role?->nama_role ?? 'Gudang';
                $wG = preg_split('/\s+/', trim($sbNameG));
                $iG = '';
                if (!empty($wG[0])) $iG .= mb_substr($wG[0], 0, 1);
                if (isset($wG[1])) $iG .= mb_substr($wG[1], 0, 1);
                elseif (mb_strlen($wG[0] ?? '') > 1) $iG .= mb_substr($wG[0], 1, 1);
                $initG = strtoupper(mb_substr($iG, 0, 2)) ?: '?';
            @endphp
            <div class="w-11 h-11 rounded-full bg-gold-accent text-white flex items-center justify-center font-bold text-[15px] shrink-0 border-2 border-white shadow-sm ring-1 ring-gold-accent/20">{{ $initG }}</div>
            <div class="min-w-0 flex-1" data-sidebar-text>
                <h4 class="text-[13px] font-bold text-on-sidebar truncate leading-tight">{{ $sbNameG }}</h4>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gold-accent text-white text-[10px] font-bold uppercase tracking-wider truncate shadow-sm mt-1">{{ $sbRoleG }}</span>
            </div>
        </div>
        <nav class="sidebar-scroll flex-1 overflow-y-auto">
            @include('partials.sidebar-menu-gudang')
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
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">@yield('header-subtitle', 'Pantau persediaan dan aktivitas gudang Anda.')</p>
            </div>
            <div class="flex items-center gap-6">
                <button type="button" class="theme-toggle text-on-surface hover:text-secondary transition-colors" aria-label="Ganti tema">
                    <span class="material-symbols-outlined icon-moon">dark_mode</span>
                    <span class="material-symbols-outlined icon-sun hidden">light_mode</span>
                </button>
                @include('partials.notification-panel', ['items' => $notifications, 'lihatSemuaRoute' => 'gudang.notifikasi'])
                @include('partials.profile-menu', ['name' => $user?->nama_lengkap ?? 'User', 'role' => $user?->role?->nama_role ?? 'Gudang', 'profilRoute' => 'gudang.profil', 'showPengaturan' => false])
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
            <p class="text-on-surface-variant font-body-md mt-1">@yield('header-subtitle', 'Pantau persediaan dan aktivitas gudang Anda.')</p>
        </div>

        <div class="page-enter px-container-margin pt-8 pb-section-gap flex flex-col gap-6 max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Nav Bar (Mobile) -->
    @include('partials.bottom-nav', ['items' => [
        ['route' => 'gudang.dashboard', 'icon' => 'space_dashboard', 'label' => 'Beranda'],
        ['route' => 'gudang.stok', 'icon' => 'inventory_2', 'label' => 'Stok'],
        ['route' => 'gudang.pelanggan-request', 'icon' => 'assignment_ind', 'label' => 'Request'],
        ['route' => 'gudang.riwayat-stok', 'icon' => 'history', 'label' => 'Riwayat'],
        ['route' => 'gudang.profil', 'icon' => 'person', 'label' => 'Profil'],
    ]])

    @include('partials.layout-scripts')
    @include('partials.ui-scripts')

    @stack('scripts')
</body>
</html>
