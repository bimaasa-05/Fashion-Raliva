{{-- NAVIGATION DRAWER --}}
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
    #drawer-panel.open .drawer-promo {
        animation: drawerItemIn .4s ease .24s both;
    }
    :root {
        --gold-wash: rgba(253,209,119,.45);
    }
    html.theme-dark {
        --gold-wash: rgba(235,193,104,.16);
    }
    .drawer-link-active {
        background-color: var(--gold-wash);
        color: var(--chrome-accent);
        font-weight: 600;
    }
    .drawer-link-active .material-symbols-outlined {
        color: var(--chrome-accent);
        font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
</style>
<div id="drawer-overlay" class="fixed inset-0 bg-black/50 z-[60] opacity-0 pointer-events-none transition-opacity duration-300" onclick="closeDrawer()"></div>
<aside id="drawer-panel" class="fixed top-0 left-0 h-full w-72 max-w-[85%] bg-[var(--chrome-bg)] text-[var(--chrome-text)] z-[70] -translate-x-full transition-transform duration-300 flex flex-col shadow-xl">
<div class="flex justify-between items-start px-md pt-md pb-sm border-b border-[var(--chrome-border)] shrink-0">
<div>
<h2 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)]">RALIVA</h2>
<p class="font-label-sm text-label-sm text-[var(--chrome-text-dim)] tracking-wide mt-1">{{ __('The Art of Everyday Dressing') }}</p>
</div>
<div class="flex items-center gap-xs shrink-0">
<button aria-label="Toggle theme" class="w-9 h-9 rounded-full hover:bg-[var(--chrome-hover)] flex items-center justify-center transition-colors" onclick="toggleTheme()" type="button">
<span class="material-symbols-outlined" data-icon="dark_mode" id="theme-icon">dark_mode</span>
</button>
<button aria-label="Close menu" class="w-9 h-9 rounded-full hover:bg-[var(--chrome-hover)] flex items-center justify-center transition-colors" onclick="closeDrawer()" type="button">
<span class="material-symbols-outlined" data-icon="close">close</span>
</button>
</div>
</div>
<nav class="flex-grow overflow-y-auto py-sm">
<div class="drawer-sec">
<h3 class="font-label-caps text-label-caps text-[var(--chrome-text-faint)] uppercase tracking-widest px-lg pt-sm pb-xs">Menu Utama</h3>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.home') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.home') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">home</span>
            {{ __('Home') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.shop') || request()->routeIs('customer.shop.produk-detail') || request()->routeIs('customer.shop.store-detail') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">shopping_bag</span>
            {{ __('Shop') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.wishlist') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.login', ['redirect' => route('customer.wishlist')]) }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">favorite</span>
            {{ __('Wishlist') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.search') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.search') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">search</span>
            {{ __('Search') }}
        </a>
</div>
<div class="drawer-sec">
<h3 class="font-label-caps text-label-caps text-[var(--chrome-text-faint)] uppercase tracking-widest px-lg pt-md pb-xs">Kategori</h3>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)] transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">checkroom</span>
            {{ __('Women') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)] transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">apparel</span>
            {{ __('Men') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)] transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">watch</span>
            {{ __('Accessories') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)] transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">steps</span>
            {{ __('Shoes') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)] transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">work</span>
            {{ __('Bags') }}
        </a>
</div>
<div class="drawer-sec">
<h3 class="font-label-caps text-label-caps text-[var(--chrome-text-faint)] uppercase tracking-widest px-lg pt-md pb-xs">Akun</h3>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.account') || request()->routeIs('customer.account.edit') || request()->routeIs('customer.account.password') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.login', ['redirect' => route('customer.account')]) }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">person</span>
            {{ __('My Account') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.order-tracking') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.order-tracking') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">local_mall</span>
            {{ __('My Orders') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.reviews') || request()->routeIs('customer.reviews.create') || request()->routeIs('customer.reviews.edit') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.reviews') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">star_border</span>
            {{ __('My Reviews') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.address') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.address') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">location_on</span>
            {{ __('Addresses') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.notifications') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.notifications') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">notifications_none</span>
            {{ __('Notifications') }}
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.settings') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.settings') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">settings</span>
            {{ __('Settings') }}
        </a>
</div>
<div class="drawer-sec">
<h3 class="font-label-caps text-label-caps text-[var(--chrome-text-faint)] uppercase tracking-widest px-lg pt-md pb-xs">Bantuan</h3>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg transition-colors flex items-center gap-sm {{ request()->routeIs('customer.help') ? 'drawer-link-active' : 'text-[var(--chrome-text)] hover:bg-[var(--chrome-hover)]' }}" href="{{ route('customer.help') }}">
<span class="material-symbols-outlined text-[20px] text-[var(--chrome-text-dim)]">help_outline</span>
            {{ __('Help Center') }}
        </a>
</div>
<div class="drawer-promo px-sm pt-md pb-md">
<div class="relative overflow-hidden rounded-lg bg-secondary-container text-on-secondary-fixed p-md">
<p class="font-label-caps text-label-caps uppercase tracking-widest mb-xs">{{ __('Mid-Year Sale') }}</p>
<p class="font-body-sm text-body-sm mb-sm relative z-10">{{ __('Up to 50% off selected styles.') }}</p>
<a class="font-label-caps text-label-caps underline underline-offset-4 uppercase tracking-widest hover:opacity-80 transition-opacity relative z-10 inline-block" href="{{ route('customer.shop') }}">{{ __('Shop Now') }}</a>
<span class="material-symbols-outlined absolute -right-2 -bottom-2 text-[72px] opacity-20">sell</span>
</div>
</div>
</nav>
<div class="px-md py-sm border-t border-[var(--chrome-border)] shrink-0">
<p class="font-label-sm text-label-sm text-[var(--chrome-text-faint)]">© 2026 RALIVA. {{ __('All rights reserved.') }}</p>
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
        function applyThemeIcon() {
            var icon = document.getElementById('theme-icon');
            if (!icon) return;
            var dark = document.documentElement.classList.contains('theme-dark');
            icon.textContent = dark ? 'light_mode' : 'dark_mode';
            icon.setAttribute('data-icon', dark ? 'light_mode' : 'dark_mode');
        }
        function toggleTheme() {
            var dark = document.documentElement.classList.toggle('theme-dark');
            localStorage.setItem('raliva-theme', dark ? 'dark' : 'light');
            applyThemeIcon();
        }
        applyThemeIcon();
    </script>
