<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - @yield('title', 'Super Admin')</title>
    @include('partials.theme-head')
    <style>
        /* Raliva Motion — reveal on scroll (sama dengan dashboard owner).
           Elemen fixed/sticky (drawer, overlay, modal) TIDAK boleh diganggu:
           transform reveal akan merusak posisi & interaksi mereka. */
        [data-reveal]:not(.fixed):not(.sticky) { opacity: 0; transform: translateY(12px); transition: opacity 0.45s ease-out, transform 0.45s ease-out; transition-delay: var(--reveal-delay, 0ms); }
        /* transform:none (bukan translateY(0)) — agar elemen fixed di dalamnya
           (modal/drawer) kembali relatif ke viewport setelah reveal selesai */
        [data-reveal].revealed:not(.fixed):not(.sticky) { opacity: 1; transform: none; }
        /* Elemen hidden tidak boleh kena opacity/transform reveal —
           display:none bikin computed position jadi 'static', jadi fixed class terlewat */
        [data-reveal][hidden] { opacity: 0 !important; pointer-events: none !important; }
        [data-reveal][style*="display: none"] { opacity: 0 !important; pointer-events: none !important; }
        [data-reveal][style*="display:none"] { opacity: 0 !important; pointer-events: none !important; }

        /* Isi widget (progress/bar/donut) tetap kosong sampai card-nya ter-reveal */
        [data-reveal]:not(.revealed) .raliva-lb-fill { width: 0% !important; }
        [data-reveal]:not(.revealed) .raliva-bar { height: 0% !important; }
        [data-reveal]:not(.revealed) .raliva-donut-seg { stroke-dasharray: 0 10000 !important; }

        @media (prefers-reduced-motion: reduce) {
            [data-reveal] { opacity: 1 !important; transform: none !important; transition: none !important; }
            [data-reveal] .raliva-lb-fill, [data-reveal] .raliva-bar, [data-reveal] .raliva-donut-seg { transition: none !important; }
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
            @include('partials.notification-panel')
            @include('partials.profile-menu', ['compact' => true])
        </div>
    </header>

    <!-- Side Navigation Drawer -->
    <aside id="sidebar" class="flex fixed md:sticky top-0 left-0 z-50 flex-col h-screen pt-section-gap pb-[88px] md:pb-section-gap px-container-margin w-72 border-r border-sidebar-border bg-sidebar -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="sidebar-head mb-12 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo Raliva" class="w-11 h-11 rounded-xl shrink-0" />
                <div data-sidebar-text>
                    <span class="font-display-lg text-title-md text-on-sidebar tracking-widest block leading-tight">RALIVA</span>
                    <span class="text-gold-accent/80 font-label-sm text-[10px] uppercase tracking-wider">Super Admin</span>
                </div>
            </div>
            <button type="button" id="sidebar-collapse" aria-expanded="true" aria-label="Perkecil menu sidebar" class="sidebar-collapse-btn hidden md:inline-flex w-8 h-8 rounded-lg border border-transparent hover:border-gold-accent/40 hover:bg-gold-accent/10 text-gold-accent/70 hover:text-gold-accent items-center justify-center transition-colors shrink-0">
                <span class="material-symbols-outlined icon-chevron text-[18px] transition-transform duration-300">chevron_left</span>
            </button>
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

        <div class="page-enter px-container-margin pt-8 pb-section-gap flex flex-col gap-6 max-w-7xl mx-auto w-full">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Nav Bar (Mobile) -->
    @include('partials.bottom-nav', ['items' => [
        ['route' => 'superadmin.dashboard', 'icon' => 'dashboard', 'label' => 'Beranda'],
        ['route' => 'superadmin.data-pesanan', 'icon' => 'shopping_cart', 'label' => 'Pesanan'],
        ['route' => 'superadmin.moderasi-produk', 'icon' => 'inventory_2', 'label' => 'Moderasi'],
        ['route' => 'superadmin.laporan', 'icon' => 'bar_chart', 'label' => 'Laporan'],
        ['route' => 'superadmin.profil', 'icon' => 'person', 'label' => 'Profil'],
    ]])

    @stack('modals')
    @include('partials.layout-scripts')
    @include('partials.ui-scripts')
    <script>
        /* ===== Raliva Motion — reveal on scroll seragam untuk semua halaman Super Admin ===== */
        if (!window.matchReducedMotion) {
            window.matchReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        }

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

        /* Elemen konten yang layak di-stagger di dalam sebuah card/section */
        const REVEAL_CONTENT_SELECTOR = ':scope > div, :scope > section, :scope > article, :scope > h1, :scope > h2, :scope > h3, :scope > h4, :scope > p, :scope > ul, :scope > ol, :scope > table, :scope > form, :scope > a';

        /* Drawer/overlay/modal (fixed) dan action bar (sticky) tidak di-reveal:
           transform & opacity reveal akan merusak perilaku mereka.
           Elemen interaktif (button/a/input/select + yang punya handler onclick)
           JUGA dikecualikan: mereka harus SELALU bisa diklik, tidak boleh
           terkunci di opacity:0 oleh reveal sebelum observer memicu.
           Elemen hidden (display:none) juga dikecualikan — computed position
           jadi 'static' saat display:none, sehingga modal/fixed terlewat. */
        const isRevealExempt = (el) => {
            if (el.hidden || el.style.display === 'none' || getComputedStyle(el).display === 'none') return true;
            const pos = getComputedStyle(el).position;
            if (pos === 'fixed' || pos === 'sticky') return true;
            const tag = el.tagName;
            if (tag === 'BUTTON' || tag === 'A' || tag === 'INPUT' || tag === 'SELECT' || tag === 'TEXTAREA' || tag === 'LABEL') return true;
            if (el.hasAttribute('onclick') || el.getAttribute('role') === 'button') return true;
            return false;
        };

        /* Count-up ditahan sampai card-nya terlihat di viewport */
        const pendingCounts = [];
        if (window.ralivaCountUp) {
            const origCountUp = window.ralivaCountUp;
            window.ralivaCountUp = (el, target, suffix, duration) => {
                const host = el ? el.closest('[data-reveal]') : null;
                if (!host || host.classList.contains('revealed')) return origCountUp(el, target, suffix, duration);
                pendingCounts.push({ el, target, suffix, duration });
            };
        }
        const flushPendingCounts = (scope) => {
            for (let i = pendingCounts.length - 1; i >= 0; i--) {
                if (scope.contains(pendingCounts[i].el)) {
                    const c = pendingCounts.splice(i, 1)[0];
                    window.ralivaCountUp(c.el, c.target, c.suffix, c.duration);
                }
            }
        };

        window.initRalivaReveal = () => {
            /* Grup eksplisit: anak-anaknya dapat delay berurutan.
               page-enter TIDAK lagi auto jadi reveal-group —
               view yang pakai reveal harus pasang data-reveal-group sendiri. */
            document.querySelectorAll('[data-reveal-group]').forEach((group) => {
                Array.from(group.children).forEach((child, index) => {
                    if (isRevealExempt(child)) return;
                    if (!child.hasAttribute('data-reveal')) child.setAttribute('data-reveal', '');
                    if (!child.style.getPropertyValue('--reveal-delay')) {
                        child.style.setProperty('--reveal-delay', Math.min(index * 40, 200) + 'ms');
                    }
                });
            });

            /* Konten di dalam card juga ter-reveal berurutan, berlapis sampai 3 tingkat */
            let pending = Array.from(document.querySelectorAll('[data-reveal]:not([data-reveal-done])')).map((el) => ({ el, depth: 0 }));
            while (pending.length) {
                const next = [];
                pending.forEach(({ el, depth }) => {
                    el.setAttribute('data-reveal-done', '');
                    if (depth >= 3 || el.hasAttribute('data-reveal-skip-children')) return;
                    Array.from(el.querySelectorAll(REVEAL_CONTENT_SELECTOR)).forEach((child, index) => {
                        if (isRevealExempt(child)) {
                            /* Jangan ditandai & jangan turuni subtree-nya */
                            child.setAttribute('data-reveal-done', '');
                            return;
                        }
                        const isNew = !child.hasAttribute('data-reveal');
                        if (isNew) child.setAttribute('data-reveal', '');
                        if (!child.style.getPropertyValue('--reveal-delay')) {
                            child.style.setProperty('--reveal-delay', Math.min(index * 45 + 50, 320) + 'ms');
                        }
                        if (isNew) next.push({ el: child, depth: depth + 1 });
                    });
                });
                pending = next;
            }

            const targets = new Set();
            document.querySelectorAll('[data-reveal]').forEach((el) => targets.add(el));
            if (!targets.size) return;

            if (!('IntersectionObserver' in window) || window.matchReducedMotion()) {
                targets.forEach((el) => el.classList.add('revealed'));
                flushPendingCounts(document);
                return;
            }

            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('revealed');
                    /* Card terlihat: jalankan count-up & lepaskan gate bar/progress/donut */
                    flushPendingCounts(entry.target);
                    io.unobserve(entry.target);
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -32px 0px' });

            targets.forEach((el) => {
                if (!el.classList.contains('revealed')) io.observe(el);
            });
        };

        window.initRalivaReveal();
        flushReadyQueue();
    </script>
    @stack('scripts')
</body>
</html>
