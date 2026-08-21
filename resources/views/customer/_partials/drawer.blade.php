{{-- NAVIGATION DRAWER --}}
<div id="drawer-overlay" class="fixed inset-0 bg-black/50 z-[60] hidden" onclick="closeDrawer()"></div>
<aside id="drawer-panel" class="fixed top-0 left-0 h-full w-72 max-w-[80%] bg-surface z-[70] -translate-x-full transition-transform duration-300 flex flex-col shadow-xl">
<div class="flex justify-between items-center px-md h-16 border-b border-outline-variant shrink-0">
<h2 class="font-display-lg text-headline-md tracking-widest text-on-surface">RALIVA</h2>
<button aria-label="Close menu" class="hover:opacity-80 transition-opacity flex" onclick="closeDrawer()" type="button">
<span class="material-symbols-outlined" data-icon="close">close</span>
</button>
</div>
<nav class="flex-grow overflow-y-auto pb-md">
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest px-md pt-lg pb-xs">Menu Utama</h3>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.home') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">home</span>
            Home
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">shopping_bag</span>
            Shop
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.wishlist') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">favorite</span>
            Wishlist
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.search') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">search</span>
            Search
        </a>
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest px-md pt-lg pb-xs">Kategori</h3>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">checkroom</span>
            Women
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">apparel</span>
            Men
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">watch</span>
            Accessories
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">steps</span>
            Shoes
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">work</span>
            Bags
        </a>
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest px-md pt-lg pb-xs">Akun</h3>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.account') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">person</span>
            My Account
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.order-tracking') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">local_mall</span>
            My Orders
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.reviews') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">star_border</span>
            My Reviews
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.address') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">location_on</span>
            Addresses
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.notifications') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">notifications_none</span>
            Notifications
        </a>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.settings') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">settings</span>
            Settings
        </a>
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest px-md pt-lg pb-xs">Bantuan</h3>
<a class="flex items-center gap-sm px-md py-sm font-body-lg text-body-lg text-on-surface hover:bg-surface-container-low transition-colors" href="{{ route('customer.help') }}">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">help_outline</span>
            Help Center
        </a>
</nav>
<div class="px-md py-sm border-t border-outline-variant shrink-0">
<p class="font-label-sm text-label-sm text-on-surface-variant">© 2026 RALIVA. All rights reserved.</p>
</div>
</aside>
<script>
        function openDrawer() {
            document.getElementById('drawer-panel').classList.remove('-translate-x-full');
            document.getElementById('drawer-overlay').classList.remove('hidden');
        }
        function closeDrawer() {
            document.getElementById('drawer-panel').classList.add('-translate-x-full');
            document.getElementById('drawer-overlay').classList.add('hidden');
        }
    </script>
