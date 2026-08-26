<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - @yield('title', 'Staf Produksi')</title>
    @include('partials.theme-head')
    <style>
        [data-reveal] { opacity: 0; transform: translateY(12px); transition: opacity 0.45s ease-out, transform 0.45s ease-out; transition-delay: var(--reveal-delay, 0ms); }
        [data-reveal].revealed { opacity: 1; transform: translateY(0); }
        .progress-fill { width: 0%; transition: width 0.9s ease-out var(--reveal-delay, 150ms); }
        @media (prefers-reduced-motion: reduce) {
            [data-reveal] { opacity: 1 !important; transform: none !important; transition: none !important; }
            .progress-fill { transition: none !important; }
        }
    </style>
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
                ['icon' => 'assignment', 'html' => 'Permintaan baru <span class="font-bold">PRQ-0043</span> menunggu konfirmasi produksi.', 'time' => '8 menit lalu'],
                ['icon' => 'fact_check', 'html' => 'Pemeriksaan <span class="font-bold">QC-0012</span> selesai: 38 layak, 2 defect.', 'time' => '45 menit lalu'],
                ['icon' => 'task_alt', 'html' => 'Produk selesai <span class="font-bold">45 unit</span> siap serah ke Gudang.', 'time' => '2 jam lalu'],
            ], 'lihatSemuaRoute' => 'produksi.notifikasi'])
            @include('partials.profile-menu', ['compact' => true, 'name' => 'Rini Kusuma', 'role' => 'Staf Produksi', 'profilRoute' => 'produksi.profil', 'showPengaturan' => false])
        </div>
    </header>

    <!-- Side Navigation Drawer -->
    <aside id="sidebar" class="flex fixed md:sticky top-0 left-0 z-50 flex-col h-screen pt-section-gap pb-[88px] md:pb-section-gap px-container-margin w-64 border-r border-sidebar-border bg-sidebar -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="sidebar-head mb-12 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo Raliva" class="w-11 h-11 rounded-xl shrink-0" />
                <div data-sidebar-text>
                    <span class="font-display-lg text-title-md text-on-sidebar tracking-widest block leading-tight">RALIVA</span>
                    <span class="text-gold-accent/80 font-label-sm text-[10px] uppercase tracking-wider">Produksi</span>
                </div>
            </div>
            <button type="button" id="sidebar-collapse" aria-expanded="true" aria-label="Perkecil menu sidebar" class="sidebar-collapse-btn hidden md:inline-flex w-8 h-8 rounded-lg border border-transparent hover:border-gold-accent/40 hover:bg-gold-accent/10 text-gold-accent/70 hover:text-gold-accent items-center justify-center transition-colors shrink-0">
                <span class="material-symbols-outlined icon-chevron text-[18px] transition-transform duration-300">chevron_left</span>
            </button>
        </div>
        <nav class="sidebar-scroll flex-1 overflow-y-auto">
            @include('partials.sidebar-menu-produksi')
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
                    <h1 class="font-title-md text-title-md text-on-surface">@yield('header-title', 'Dashboard Produksi')</h1>
                    @hasSection('header-badge')
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-label-sm uppercase tracking-wider">@yield('header-badge')</span>
                    @endif
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">@yield('header-subtitle', 'Ringkasan permintaan, proses dan hasil produksi hari ini.')</p>
            </div>
            <div class="flex items-center gap-6">
                <button type="button" class="theme-toggle text-on-surface hover:text-secondary transition-colors" aria-label="Ganti tema">
                    <span class="material-symbols-outlined icon-moon">dark_mode</span>
                    <span class="material-symbols-outlined icon-sun hidden">light_mode</span>
                </button>
                @include('partials.notification-panel', ['items' => [
                    ['icon' => 'assignment', 'html' => 'Permintaan baru <span class="font-bold">PRQ-0043</span> menunggu konfirmasi produksi.', 'time' => '8 menit lalu'],
                    ['icon' => 'fact_check', 'html' => 'Pemeriksaan <span class="font-bold">QC-0012</span> selesai: 38 layak, 2 defect.', 'time' => '45 menit lalu'],
                    ['icon' => 'task_alt', 'html' => 'Produk selesai <span class="font-bold">45 unit</span> siap serah ke Gudang.', 'time' => '2 jam lalu'],
                ], 'lihatSemuaRoute' => 'produksi.notifikasi'])
                @include('partials.profile-menu', ['name' => 'Rini Kusuma', 'role' => 'Staf Produksi', 'profilRoute' => 'produksi.profil', 'showPengaturan' => false])
            </div>
        </header>

        <!-- Mobile Greeting -->
        <div class="md:hidden px-container-margin py-6">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="{{ request()->routeIs('produksi.dashboard') ? 'font-headline-lg-mobile text-headline-lg-mobile' : 'raliva-figure text-[24px]' }} text-on-surface">@yield('header-title', 'Dashboard Produksi')</h1>
                @hasSection('header-badge')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">@yield('header-badge')</span>
                @endif
            </div>
            <p class="text-on-surface-variant font-body-md mt-1">@yield('header-subtitle', 'Ringkasan permintaan, proses dan hasil produksi hari ini.')</p>
        </div>

        <div class="px-container-margin pt-8 pb-section-gap flex flex-col gap-section-gap max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Nav Bar (Mobile) -->
    @include('partials.bottom-nav', ['items' => [
        ['route' => 'produksi.dashboard', 'icon' => 'space_dashboard', 'label' => 'Beranda'],
        ['route' => 'produksi.permintaan-produksi', 'icon' => 'assignment', 'label' => 'Permintaan'],
        ['route' => 'produksi.data-produksi', 'icon' => 'precision_manufacturing', 'label' => 'Produksi'],
        ['route' => 'produksi.riwayat-produksi', 'icon' => 'history', 'label' => 'Riwayat'],
        ['route' => 'produksi.profil', 'icon' => 'person', 'label' => 'Profil'],
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

        /* ===== Raliva Motion â€” satu animasi entrance seragam ===== */
        window.matchReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        const PROGRESS_COLORS = ['bg-secondary', 'bg-gold-accent', 'bg-deep-onyx'];

        /* Semantik warna progress:
           - mode "quota" (slot/kuota/kapasitas): sisa banyak = terang, hampir habis = gelap
           - mode "task" (progres pekerjaan): netral gold, selesai 100% = gelap */
        const setProgressFill = (el) => {
            const target = Math.min(Math.max(parseFloat(el.getAttribute('data-progress')) || 0, 0), 100);
            const mode = el.getAttribute('data-progress-mode') || 'task';
            el.classList.remove(...PROGRESS_COLORS);
            let color;
            if (mode === 'quota') {
                color = target >= 90 ? 'bg-deep-onyx' : (target >= 70 ? 'bg-gold-accent' : 'bg-secondary');
            } else {
                color = target >= 100 ? 'bg-deep-onyx' : 'bg-gold-accent';
            }
            el.classList.add(color);
            requestAnimationFrame(() => { el.style.width = target + '%'; });
        };

        /* Chart mulai 120ms setelah konten siap agar tidak tabrakan dengan reveal */
        window.ralivaReadyQueue = [];
        window.__ralivaContentReady = false;
        window.ralivaOnReady = (fn) => {
            if (window.__ralivaContentReady) setTimeout(fn, 120);
            else window.ralivaReadyQueue.push(fn);
        };
        const flushReadyQueue = () => {
            window.__ralivaContentReady = true;
            while (window.ralivaReadyQueue.length) {
                const fn = window.ralivaReadyQueue.shift();
                setTimeout(fn, 120);
            }
        };

        window.initRalivaReveal = () => {
            document.querySelectorAll('[data-reveal-group]').forEach((group) => {
                Array.from(group.children).forEach((child, index) => {
                    if (!child.hasAttribute('data-reveal')) child.setAttribute('data-reveal', '');
                    if (!child.style.getPropertyValue('--reveal-delay')) {
                        child.style.setProperty('--reveal-delay', Math.min(index * 40, 200) + 'ms');
                    }
                });
            });

            const targets = new Set();
            document.querySelectorAll('[data-reveal], [data-progress]').forEach((el) => targets.add(el));
            if (!targets.size) return;

            if (!('IntersectionObserver' in window) || window.matchReducedMotion()) {
                targets.forEach((el) => {
                    if (el.hasAttribute('data-reveal')) el.classList.add('revealed');
                    if (el.hasAttribute('data-progress')) setProgressFill(el);
                });
                return;
            }

            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    const el = entry.target;
                    if (el.hasAttribute('data-reveal')) el.classList.add('revealed');
                    if (el.hasAttribute('data-progress')) setProgressFill(el);
                    io.unobserve(el);
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -32px 0px' });

            targets.forEach((el) => io.observe(el));
        };

        const hasSkeletons = document.querySelectorAll('[data-skeleton]').length > 0;
        if (!hasSkeletons) flushReadyQueue();

        window.initRalivaReveal();

        setTimeout(() => {
            document.querySelectorAll('[data-skeleton]').forEach((el) => el.classList.add('hidden'));
            document.querySelectorAll('[data-real]').forEach((el) => el.classList.remove('hidden'));
            window.initRalivaReveal();
            flushReadyQueue();
        }, 420);
    </script>
    @stack('scripts')
</body>
</html>

