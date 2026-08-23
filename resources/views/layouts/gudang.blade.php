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
            @include('partials.notification-panel', ['items' => [
                ['icon' => 'inventory_2', 'html' => 'Stok <span class="font-bold">Silk Scarf</span> tinggal 5 unit.', 'time' => '10 menit lalu'],
                ['icon' => 'archive', 'html' => 'Barang masuk <span class="font-bold">BM-0012</span> menunggu pemeriksaan.', 'time' => '30 menit lalu'],
                ['icon' => 'swap_horiz', 'html' => 'Pemindahan stok <span class="font-bold">PM-0004</span> telah diterima.', 'time' => '2 jam lalu'],
            ], 'lihatSemuaRoute' => 'gudang.notifikasi'])
            @include('partials.profile-menu', ['compact' => true, 'name' => 'Andi Pratama', 'role' => 'Staf Gudang', 'profilRoute' => 'gudang.profil', 'showPengaturan' => false])
        </div>
    </header>

    <!-- Side Navigation Drawer -->
    <aside id="sidebar" class="flex fixed md:sticky top-0 left-0 z-50 flex-col h-screen pt-section-gap pb-[88px] md:pb-section-gap px-container-margin w-64 border-r border-sidebar-border bg-sidebar -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="mb-12 flex items-center gap-3">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo Raliva" class="w-11 h-11 rounded-xl shrink-0" />
            <div>
                <span class="font-display-lg text-title-md text-on-sidebar tracking-widest block leading-tight">RALIVA</span>
                <span class="text-gold-accent/80 font-label-sm text-[10px] uppercase tracking-wider">Gudang</span>
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
                    <h1 class="font-title-md text-title-md text-on-surface uppercase tracking-wider">@yield('header-title', 'Dashboard')</h1>
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
                @include('partials.notification-panel', ['items' => [
                    ['icon' => 'inventory_2', 'html' => 'Stok <span class="font-bold">Silk Scarf</span> tinggal 5 unit.', 'time' => '10 menit lalu'],
                    ['icon' => 'archive', 'html' => 'Barang masuk <span class="font-bold">BM-0012</span> menunggu pemeriksaan.', 'time' => '30 menit lalu'],
                    ['icon' => 'swap_horiz', 'html' => 'Pemindahan stok <span class="font-bold">PM-0004</span> telah diterima.', 'time' => '2 jam lalu'],
                ], 'lihatSemuaRoute' => 'gudang.notifikasi'])
                @include('partials.profile-menu', ['name' => 'Andi Pratama', 'role' => 'Staf Gudang', 'profilRoute' => 'gudang.profil', 'showPengaturan' => false])
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

        <div class="px-container-margin pt-8 pb-section-gap flex flex-col gap-section-gap max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Nav Bar (Mobile) -->
    @include('partials.bottom-nav', ['items' => [
        ['route' => 'gudang.dashboard', 'icon' => 'space_dashboard', 'label' => 'Beranda'],
        ['route' => 'gudang.stok', 'icon' => 'inventory_2', 'label' => 'Stok'],
        ['route' => 'gudang.barang-masuk', 'icon' => 'archive', 'label' => 'Masuk'],
        ['route' => 'gudang.riwayat-stok', 'icon' => 'history', 'label' => 'Riwayat'],
        ['route' => 'gudang.profil', 'icon' => 'person', 'label' => 'Profil'],
    ]])

    <!-- Toast -->
    <div id="raliva-toast" class="hidden fixed bottom-24 md:bottom-8 right-4 md:right-8 z-[90] items-center gap-3 bg-inverse-surface text-inverse-on-surface pl-4 pr-5 py-3 rounded-lg shadow-xl max-w-[calc(100vw-2rem)]">
        <span class="material-symbols-outlined text-[20px]" data-toast-icon>check_circle</span>
        <p class="font-body-md text-sm" data-toast-message></p>
    </div>

    @include('partials.layout-scripts')

    <script>
        const ralivaToast = document.getElementById('raliva-toast');
        let ralivaToastTimer;

        window.showRalivaToast = (message, icon = 'check_circle') => {
            if (!ralivaToast) return;
            ralivaToast.querySelector('[data-toast-message]').textContent = message;
            ralivaToast.querySelector('[data-toast-icon]').textContent = icon;
            ralivaToast.classList.remove('hidden');
            ralivaToast.classList.add('flex');
            clearTimeout(ralivaToastTimer);
            ralivaToastTimer = setTimeout(() => {
                ralivaToast.classList.add('hidden');
                ralivaToast.classList.remove('flex');
            }, 2800);
        };

        const closeAllOverlays = () => {
            document.querySelectorAll('[data-modal]').forEach((m) => m.classList.add('hidden'));
            document.querySelectorAll('[data-drawer-panel]').forEach((d) => d.classList.add('translate-x-full'));
            document.getElementById('drawer-overlay')?.classList.add('opacity-0');
            const overlay = document.getElementById('drawer-overlay');
            if (overlay) setTimeout(() => overlay.classList.add('hidden'), 300);
            document.body.style.overflow = '';
        };

        document.querySelectorAll('[data-modal-open]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const modal = document.getElementById(btn.getAttribute('data-modal-open'));
                modal?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        document.querySelectorAll('[data-modal-close]').forEach((el) => {
            el.addEventListener('click', () => {
                el.closest('[data-modal]')?.classList.add('hidden');
                document.body.style.overflow = '';
            });
        });

        document.querySelectorAll('[data-drawer-open]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const drawer = document.getElementById(btn.getAttribute('data-drawer-open'));
                const overlay = document.getElementById('drawer-overlay');
                drawer?.classList.remove('translate-x-full');
                overlay?.classList.remove('hidden');
                requestAnimationFrame(() => overlay?.classList.remove('opacity-0'));
                document.body.style.overflow = 'hidden';
            });
        });

        document.querySelectorAll('[data-drawer-close]').forEach((el) => {
            el.addEventListener('click', () => {
                document.querySelectorAll('[data-drawer-panel]').forEach((d) => d.classList.add('translate-x-full'));
                const overlay = document.getElementById('drawer-overlay');
                overlay?.classList.add('opacity-0');
                if (overlay) setTimeout(() => overlay.classList.add('hidden'), 300);
                document.body.style.overflow = '';
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeAllOverlays();
        });

        document.querySelectorAll('[data-dropdown-toggle]').forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                btn.parentElement.querySelector('[data-dropdown-menu]')?.classList.toggle('hidden');
            });
        });

        document.addEventListener('click', (e) => {
            document.querySelectorAll('[data-dropdown-menu]').forEach((menu) => {
                if (!menu.parentElement.contains(e.target)) menu.classList.add('hidden');
            });
        });

        document.querySelectorAll('form[data-toast-message]').forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                window.showRalivaToast(form.getAttribute('data-toast-message'));
                form.closest('[data-modal]')?.classList.add('hidden');
                document.body.style.overflow = '';
                form.reset();
            });
        });

        const applyTableFilter = () => {
            document.querySelectorAll('[data-table-scope]').forEach((scope) => {
                const search = scope.querySelector('[data-table-search]');
                const filters = scope.querySelectorAll('[data-table-filter]');
                let visible = 0;
                scope.querySelectorAll('[data-table-row]').forEach((row) => {
                    let show = true;
                    if (search && search.value.trim() !== '') {
                        show = row.textContent.toLowerCase().includes(search.value.trim().toLowerCase());
                    }
                    if (show) {
                        filters.forEach((filter) => {
                            const key = filter.getAttribute('data-table-filter');
                            const value = filter.value;
                            if (value !== '' && value !== 'semua' && row.getAttribute('data-' + key) !== value) {
                                show = false;
                            }
                        });
                    }
                    row.classList.toggle('hidden', !show);
                    if (show) visible++;
                });
                scope.querySelector('[data-table-wrap]')?.classList.toggle('hidden', false);
                const empty = scope.querySelector('[data-empty-state]');
                empty?.classList.toggle('hidden', visible > 0);
                empty?.classList.toggle('flex', visible === 0);
                scope.querySelector('[data-pagination]')?.classList.toggle('hidden', visible === 0);
            });
        };

        document.querySelectorAll('[data-table-search]').forEach((input) => input.addEventListener('input', applyTableFilter));
        document.querySelectorAll('[data-table-filter]').forEach((select) => select.addEventListener('change', applyTableFilter));

        document.querySelectorAll('[data-filter-reset]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const scope = btn.closest('[data-table-scope]');
                if (!scope) return;
                const searchInput = scope.querySelector('[data-table-search]');
                if (searchInput) searchInput.value = '';
                scope.querySelectorAll('[data-table-filter]').forEach((s) => (s.value = s.querySelector('option').value));
                applyTableFilter();
            });
        });

        setTimeout(() => {
            document.querySelectorAll('[data-skeleton]').forEach((el) => el.classList.add('hidden'));
            document.querySelectorAll('[data-real]').forEach((el) => el.classList.remove('hidden'));
        }, 700);
    </script>
    @stack('scripts')
</body>
</html>
