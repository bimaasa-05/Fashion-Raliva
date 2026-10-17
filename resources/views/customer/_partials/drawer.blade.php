{{-- NAVIGATION DRAWER — disamakan dengan sidebar role lain (SuperAdmin/Admin/Owner/Gudang/Produksi):
     profile card, mini sidebar (perkecil/perbesar), toggle tema di bawah --}}
<style>
    @keyframes drawerItemIn {
        from { opacity: 0; transform: translateX(-14px); }
        to { opacity: 1; transform: translateX(0); }
    }
    #drawer-panel.open .drawer-sec {
        animation: drawerItemIn .4s ease both;
    }
    #drawer-panel.open .drawer-sec:nth-of-type(1) { animation-delay: .04s; }
    #drawer-panel.open .drawer-sec:nth-of-type(2) { animation-delay: .09s; }
    #drawer-panel.open .drawer-sec:nth-of-type(3) { animation-delay: .14s; }
    #drawer-panel.open .drawer-sec:nth-of-type(4) { animation-delay: .19s; }
    :root {
        --gold-wash: rgba(139,30,30,.10);
        --dw-card-bg: #f5f3f3;
        --dw-tip-bg: #1b1c1c;
        --dw-tip-fg: #ffffff;
    }
    html.theme-dark {
        --gold-wash: rgba(163,38,38,.16);
        --dw-card-bg: #242221;
        --dw-tip-bg: #f0eeee;
        --dw-tip-fg: #111111;
    }
    /* Item menu aktif — pola sama dengan sidebar role lain (aksen kiri + wash) */
    .drawer-link-active {
        background-color: var(--gold-wash);
        color: var(--chrome-accent);
        font-weight: 600;
        border-left-color: var(--chrome-accent) !important;
    }
    .drawer-link-active .material-symbols-outlined {
        color: var(--chrome-accent);
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    /* Switch tema (mengikuti tema terang/gelap customer: class theme-dark) */
    .tt-track { background-color: var(--chrome-border); }
    html.theme-dark .tt-track { background-color: var(--chrome-accent); opacity: .55; }
    html.theme-dark .theme-toggle .tt-knob { transform: translateX(1rem); }
    /* Global Hide Scrollbar — customer (semua halaman yang include drawer) */
    ::-webkit-scrollbar {
      display: none !important;
      width: 0 !important;
      height: 0 !important;
    }
    * {
      -ms-overflow-style: none !important;
      scrollbar-width: none !important;
    }
    /* ===== Mini sidebar (perkecil/perbesar) — desktop saja ===== */
    #drawer-panel { transition: transform .3s ease, width .3s ease; }
    #drawer-panel.sidebar-collapsed { width: 4.75rem; }
    #drawer-panel.sidebar-collapsed [data-sidebar-text],
    #drawer-panel.sidebar-collapsed [data-menu-label],
    #drawer-panel.sidebar-collapsed [data-group-label] { display: none !important; }
    #drawer-panel.sidebar-collapsed .sidebar-head { flex-direction: column; align-items: center; }
    #drawer-panel.sidebar-collapsed nav a.drawer-link { justify-content: center; gap: 0; padding-left: 0; padding-right: 0; }
    #drawer-panel.sidebar-collapsed .theme-toggle { justify-content: center; padding-left: 0; padding-right: 0; }
    #drawer-panel.sidebar-collapsed .sidebar-profile { justify-content: center; padding: 10px 0; margin-left: 0; margin-right: 0; background: transparent !important; border-color: transparent !important; box-shadow: none !important; }
    #drawer-panel.sidebar-collapsed .sidebar-collapse-btn .icon-chevron { transform: rotate(180deg); }
    /* Konten ikut menyesuaikan saat sidebar mengecil (desktop) */
    body { transition: padding-left .3s ease; }
    @media (min-width: 1024px) {
        html.customer-sidebar-collapsed .lg\:pl-72 { padding-left: 4.75rem; }
        /* Header/footer/overlay fixed ikut menggeser (bukan hanya konten) */
        html.customer-sidebar-collapsed .lg\:left-72 { left: 4.75rem; }
        .fixed.lg\:left-72:not(#filter-sheet) { transition: left .3s ease; }
        #filter-sheet { transition: transform .3s ease, left .3s ease; }
    }
    /* Tooltip mini sidebar (fixed, bebas terpotong overflow) */
    #customer-sidebar-tip {
        position: fixed;
        transform: translateY(-50%) translateX(-4px);
        white-space: nowrap;
        padding: 6px 10px;
        border-radius: 8px;
        background: var(--dw-tip-bg);
        color: var(--dw-tip-fg);
        font-family: 'Manrope', sans-serif;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        border: 1px solid rgba(139, 30, 63, .45);
        box-shadow: 0 8px 24px rgba(0, 0, 0, .28);
        opacity: 0;
        pointer-events: none;
        transition: opacity .18s ease-out, transform .18s ease-out;
        z-index: 80;
    }
    #customer-sidebar-tip.visible { opacity: 1; transform: translateY(-50%) translateX(0); }
</style>
<div id="drawer-overlay" class="fixed inset-0 bg-black/50 z-[60] opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" onclick="closeDrawer()"></div>
<aside id="drawer-panel" class="fixed top-0 left-0 h-full w-72 max-w-[85%] bg-[var(--chrome-bg)] text-[var(--chrome-text)] z-[70] -translate-x-full transition-transform duration-300 flex flex-col shadow-xl lg:translate-x-0 lg:max-w-full lg:shadow-none lg:border-r lg:border-[var(--chrome-border)]">
{{-- Head — brand + tombol perkecil (desktop) / tutup (mobile) --}}
<div class="sidebar-head flex items-center justify-between gap-2 px-md pt-md pb-sm border-b border-[var(--chrome-border)] shrink-0">
<div class="flex-1 min-w-0" data-sidebar-text>
<h2 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase">{{ brand() }}</h2>
<p class="font-label-sm text-label-sm text-[var(--chrome-text-dim)] tracking-wide mt-1">{{ __('The Art of Everyday Dressing') }}</p>
</div>
<div class="flex items-center gap-xs shrink-0">
<button type="button" id="sidebar-collapse" aria-expanded="true" aria-label="{{ __('Perkecil menu sidebar') }}" class="sidebar-collapse-btn hidden lg:inline-flex w-8 h-8 rounded-lg border border-transparent hover:border-[var(--chrome-border)] hover:bg-[var(--chrome-hover)] text-[var(--chrome-accent)] items-center justify-center transition-colors shrink-0">
<span class="material-symbols-outlined icon-chevron text-[18px] transition-transform duration-300">chevron_left</span>
</button>
<button aria-label="{{ __('Close menu') }}" class="w-9 h-9 rounded-full hover:bg-[var(--chrome-hover)] flex items-center justify-center transition-colors lg:hidden" onclick="closeDrawer()" type="button">
<span class="material-symbols-outlined" data-icon="close">close</span>
</button>
</div>
</div>
{{-- Profile card — pola sama dengan sidebar role lain --}}
<div class="drawer-sec shrink-0 px-md pt-sm">
    @if(auth()->check())
        @php
            $dname = auth()->user()->nama_lengkap ?? 'Customer';
            $drole = auth()->user()->role?->nama_role ?? 'Customer';
            $dw = preg_split('/\s+/', trim($dname));
            $di = '';
            if(!empty($dw[0])) $di .= mb_substr($dw[0],0,1);
            if(isset($dw[1])) $di .= mb_substr($dw[1],0,1);
            elseif(mb_strlen($dw[0]??'')>1) $di .= mb_substr($dw[0],1,1);
            $dinit = strtoupper(mb_substr($di,0,2)) ?: '?';
        @endphp
        <a href="{{ route('customer.account') }}" class="sidebar-profile flex items-center gap-3 px-4 py-3.5 rounded-xl bg-[var(--dw-card-bg)] border border-[var(--chrome-border)] shadow-sm group">
            <span class="w-11 h-11 rounded-full bg-[var(--chrome-accent)] text-white flex items-center justify-center font-bold text-[15px] shrink-0 border-2 border-white/80 shadow-sm ring-1 ring-[var(--gold-wash)] overflow-hidden">
                @if(auth()->user()->foto_profil_url)
                    <img src="{{ auth()->user()->foto_profil_url }}" alt="{{ $dname }}" class="w-full h-full object-cover" />
                @else
                    {{ $dinit }}
                @endif
            </span>
            <span class="min-w-0 flex-1" data-sidebar-text>
                <span class="block text-[13px] font-bold text-[var(--chrome-text)] truncate leading-tight">{{ $dname }}</span>
                <span class="inline-flex items-center max-w-full px-2.5 py-1 mt-1 rounded-full bg-[var(--chrome-accent)] text-white text-[10px] font-bold uppercase tracking-wider truncate shadow-sm">{{ $drole }}</span>
            </span>
        </a>
    @else
        <a href="{{ route('login') }}" class="sidebar-profile flex items-center gap-3 px-4 py-3.5 rounded-xl bg-[var(--dw-card-bg)] border border-[var(--chrome-border)] shadow-sm group">
            <span class="w-11 h-11 rounded-full bg-[var(--chrome-hover)] flex items-center justify-center ring-1 ring-[var(--gold-wash)] shrink-0">
                <span class="material-symbols-outlined text-[22px] text-[var(--chrome-text-dim)]">person_outline</span>
            </span>
            <span class="min-w-0 flex-1" data-sidebar-text>
                <span class="block text-[13px] font-bold text-[var(--chrome-text)] leading-tight">{{ __('Masuk / Daftar') }}</span>
                <span class="block text-[11px] text-[var(--chrome-text-dim)] truncate mt-0.5">{{ __('Akun :brand Anda', ['brand' => brand()]) }}</span>
            </span>
            <span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-faint)] group-hover:text-[var(--chrome-accent)] transition-colors" data-sidebar-text>chevron_right</span>
        </a>
    @endif
</div>
@php
    $drawerGroups = [
        [
            'label' => 'Menu Utama',
            'items' => [
                ['route' => 'customer.home', 'icon' => 'home', 'text' => 'Home'],
                ['route' => 'customer.shop', 'aliases' => ['customer.shop.produk-detail', 'customer.shop.store', 'customer.shop.store.riviews', 'customer.shop.store.about'], 'icon' => 'shopping_bag', 'text' => 'Shop'],
                ['route' => 'customer.orders', 'aliases' => ['customer.order-tracking'], 'icon' => 'local_mall', 'text' => 'Pesanan', 'auth' => true],
                ['route' => 'customer.wishlist', 'icon' => 'favorite', 'text' => 'Wishlist', 'auth' => true],
                ['route' => 'customer.search', 'icon' => 'search', 'text' => 'Search'],
            ],
        ],
        [
            'label' => 'Akun',
            'items' => [
                ['route' => 'customer.account', 'aliases' => ['customer.account.edit', 'customer.account.password', 'customer.address.index', 'customer.address.create', 'customer.address.edit'], 'icon' => 'person', 'text' => 'My Account'],
            ],
        ],
        [
            'label' => 'Bantuan',
            'items' => [
                ['route' => 'customer.help', 'icon' => 'help_outline', 'text' => 'Help Center'],
            ],
        ],
    ];
@endphp
<nav class="flex-grow min-h-0 overflow-y-auto py-sm px-xs">
@foreach ($drawerGroups as $group)
<div class="drawer-sec {{ $loop->first ? '' : 'pt-2 border-t border-[var(--chrome-border)] mt-2' }}">
<h3 data-group-label class="font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] opacity-70 px-3 pt-sm pb-xs">{{ __($group['label']) }}</h3>
<div class="space-y-1">
@foreach ($group['items'] as $item)
    @php
        $itemActive = request()->routeIs($item['route'], ...($item['aliases'] ?? []));
        $itemHref = (! empty($item['auth']) && ! auth()->check()) ? route('customer.account', ['notice' => 'login']) : route($item['route']);
    @endphp
    <a href="{{ $itemHref }}" class="drawer-link flex items-center gap-2.5 py-2.5 px-3 rounded-lg border-l-[3px] border-transparent text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)] transition-all duration-200 {{ $itemActive ? 'drawer-link-active' : '' }}">
        <span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)] transition-colors">{{ $item['icon'] }}</span>
        <span data-menu-label class="text-[13.5px] leading-snug flex-1 min-w-0 truncate">{{ __($item['text']) }}</span>
    </a>
@endforeach
</div>
</div>
@endforeach
</nav>
{{-- Footer: toggle tema (pola role lain) + copyright --}}
<div class="shrink-0 border-t border-[var(--chrome-border)] mt-2 pt-2 px-xs pb-sm">
<button type="button" class="theme-toggle w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)] transition-colors" aria-label="{{ __('Ganti tema') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-accent)] opacity-80 shrink-0" data-theme-icon>light_mode</span>
<span data-sidebar-text class="min-w-0 flex-1 text-left">
<span class="block text-[13px] font-semibold leading-tight">{{ __('Tampilan') }}</span>
<span class="block text-[11px] opacity-60">{{ __('Terang / Gelap') }}</span>
</span>
<span data-sidebar-text class="tt-track relative shrink-0 w-10 h-6 rounded-full transition-colors" aria-hidden="true">
<span class="tt-knob absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow transition-transform"></span>
</span>
</button>
<p data-sidebar-text class="font-label-sm text-label-sm text-[var(--chrome-text-faint)] px-3 mt-2">© 2026 {{ brand() }}. {{ __('All rights reserved.') }}</p>
</div>
</aside>
<script>
        function openDrawer() {
            var panel = document.getElementById('drawer-panel');
            panel.classList.remove('-translate-x-full');
            panel.classList.add('open');
            var overlay = document.getElementById('drawer-overlay');
            overlay.classList.remove('opacity-0');
            overlay.classList.add('pointer-events-auto');
        }
        function closeDrawer() {
            var panel = document.getElementById('drawer-panel');
            panel.classList.add('-translate-x-full');
            panel.classList.remove('open');
            var overlay = document.getElementById('drawer-overlay');
            overlay.classList.add('opacity-0');
            overlay.classList.remove('pointer-events-auto');
        }

        /* ===== Tema terang/gelap — pola sama dengan sidebar role lain ===== */
        function applyThemeIcon() {
            var dark = document.documentElement.classList.contains('theme-dark');
            document.querySelectorAll('.theme-toggle [data-theme-icon]').forEach(function (icon) {
                icon.textContent = dark ? 'dark_mode' : 'light_mode';
                icon.setAttribute('data-icon', dark ? 'dark_mode' : 'light_mode');
            });
        }
        document.querySelectorAll('.theme-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var dark = document.documentElement.classList.toggle('theme-dark');
                localStorage.setItem('raliva-theme', dark ? 'dark' : 'light');
                applyThemeIcon();
            });
        });
        applyThemeIcon();
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                var dark = localStorage.getItem('raliva-theme') === 'dark';
                document.documentElement.classList.toggle('theme-dark', dark);
                applyThemeIcon();
            }
        });

        /* ===== Mini sidebar (perkecil/perbesar) — desktop saja, pola role lain ===== */
        (function () {
            var panel = document.getElementById('drawer-panel');
            var collapseBtn = document.getElementById('sidebar-collapse');
            if (!panel || !collapseBtn) return;

            var isDesktop = function () { return window.matchMedia('(min-width: 1024px)').matches; };

            var tipEl = null;
            var hideTip = function () { if (tipEl) tipEl.classList.remove('visible'); };
            var ensureTip = function () {
                if (!tipEl) {
                    tipEl = document.createElement('div');
                    tipEl.id = 'customer-sidebar-tip';
                    document.body.appendChild(tipEl);
                }
                return tipEl;
            };
            var showTipAt = function (r, text) {
                text = (text || '').trim();
                if (!text) return;
                var el = ensureTip();
                el.textContent = text;
                el.style.top = Math.round(r.top + r.height / 2) + 'px';
                el.style.left = Math.round(r.right + 12) + 'px';
                requestAnimationFrame(function () { el.classList.add('visible'); });
            };
            var showMenuTip = function (anchor) {
                if (!isDesktop() || !panel.classList.contains('sidebar-collapsed')) return;
                var icon = anchor.querySelector('.material-symbols-outlined');
                var label = anchor.querySelector('[data-menu-label]');
                if (!icon || !label) return;
                showTipAt(icon.getBoundingClientRect(), label.textContent);
            };

            var applyCollapse = function (collapsed) {
                panel.classList.toggle('sidebar-collapsed', collapsed);
                document.documentElement.classList.toggle('customer-sidebar-collapsed', collapsed);
                collapseBtn.setAttribute('aria-expanded', String(!collapsed));
                hideTip();
            };

            var sync = function () {
                if (!isDesktop()) { applyCollapse(false); return; }
                applyCollapse(localStorage.getItem('ralivaSidebarCollapsed') === '1');
            };

            collapseBtn.addEventListener('click', function () {
                var collapsed = !panel.classList.contains('sidebar-collapsed');
                localStorage.setItem('ralivaSidebarCollapsed', collapsed ? '1' : '0');
                applyCollapse(collapsed);
            });

            window.addEventListener('resize', sync);
            sync();

            panel.querySelectorAll('nav a.drawer-link').forEach(function (a) {
                a.addEventListener('mouseenter', function () { showMenuTip(a); });
                a.addEventListener('mouseleave', hideTip);
            });
            var nav = panel.querySelector('nav');
            if (nav) nav.addEventListener('scroll', hideTip, { passive: true });

            var profile = panel.querySelector('.sidebar-profile');
            if (profile) {
                profile.addEventListener('mouseenter', function () {
                    if (!isDesktop() || !panel.classList.contains('sidebar-collapsed')) return;
                    var nameEl = profile.querySelector('[data-sidebar-text] span');
                    if (!nameEl) return;
                    showTipAt(profile.getBoundingClientRect(), nameEl.textContent);
                });
                profile.addEventListener('mouseleave', hideTip);
            }
        })();
    </script>
@include('customer._partials.wishlist-script')
@include('customer._partials.cart-script')
