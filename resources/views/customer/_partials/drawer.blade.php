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
</style>
<div id="drawer-overlay" class="fixed inset-0 bg-black/50 z-[60] opacity-0 pointer-events-none transition-opacity duration-300" onclick="closeDrawer()"></div>
<aside id="drawer-panel" class="fixed top-0 left-0 h-full w-72 max-w-[85%] bg-primary-container text-on-primary z-[70] -translate-x-full transition-transform duration-300 flex flex-col shadow-xl">
<div class="flex justify-between items-start px-md pt-md pb-sm border-b border-white/10 shrink-0">
<div>
<h2 class="font-display-lg text-headline-md tracking-widest text-secondary-fixed-dim">RALIVA</h2>
<p class="font-label-sm text-label-sm text-on-primary/60 tracking-wide mt-1">The Art of Everyday Dressing</p>
</div>
<button aria-label="Close menu" class="w-9 h-9 rounded-full hover:bg-white/10 flex items-center justify-center transition-colors shrink-0" onclick="closeDrawer()" type="button">
<span class="material-symbols-outlined" data-icon="close">close</span>
</button>
</div>
<nav class="flex-grow overflow-y-auto py-sm">
<div class="drawer-sec">
<h3 class="font-label-caps text-label-caps text-on-primary/50 uppercase tracking-widest px-lg pt-sm pb-xs">Menu Utama</h3>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.home') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">home</span>
            Home
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">shopping_bag</span>
            Shop
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.wishlist') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">favorite</span>
            Wishlist
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.search') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">search</span>
            Search
        </a>
</div>
<div class="drawer-sec">
<h3 class="font-label-caps text-label-caps text-on-primary/50 uppercase tracking-widest px-lg pt-md pb-xs">Kategori</h3>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">checkroom</span>
            Women
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">apparel</span>
            Men
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">watch</span>
            Accessories
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">steps</span>
            Shoes
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">work</span>
            Bags
        </a>
</div>
<div class="drawer-sec">
<h3 class="font-label-caps text-label-caps text-on-primary/50 uppercase tracking-widest px-lg pt-md pb-xs">Akun</h3>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.account') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">person</span>
            My Account
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.order-tracking') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">local_mall</span>
            My Orders
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.reviews') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">star_border</span>
            My Reviews
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.address') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">location_on</span>
            Addresses
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.notifications') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">notifications_none</span>
            Notifications
        </a>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.settings') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">settings</span>
            Settings
        </a>
</div>
<div class="drawer-sec">
<h3 class="font-label-caps text-label-caps text-on-primary/50 uppercase tracking-widest px-lg pt-md pb-xs">Bantuan</h3>
<a class="mx-sm px-md py-sm rounded-full font-body-lg text-body-lg text-on-primary/90 hover:bg-white/10 transition-colors flex items-center gap-sm" href="{{ route('customer.help') }}">
<span class="material-symbols-outlined text-[20px] text-on-primary/60">help_outline</span>
            Help Center
        </a>
</div>
<div class="drawer-promo px-sm pt-md pb-md">
<div class="relative overflow-hidden rounded-lg bg-secondary-container text-on-secondary-fixed p-md">
<p class="font-label-caps text-label-caps uppercase tracking-widest mb-xs">Mid-Year Sale</p>
<p class="font-body-sm text-body-sm mb-sm relative z-10">Up to 50% off selected styles.</p>
<a class="font-label-caps text-label-caps underline underline-offset-4 uppercase tracking-widest hover:opacity-80 transition-opacity relative z-10 inline-block" href="{{ route('customer.shop') }}">Shop Now</a>
<span class="material-symbols-outlined absolute -right-2 -bottom-2 text-[72px] opacity-20">sell</span>
</div>
</div>
</nav>
<div class="px-md py-sm border-t border-white/10 shrink-0">
<p class="font-label-sm text-label-sm text-on-primary/50">© 2026 RALIVA. All rights reserved.</p>
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
    </script>
