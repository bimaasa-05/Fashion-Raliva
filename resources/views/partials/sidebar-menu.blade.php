<div class="space-y-2">
    <!-- Management Group -->
    <div class="space-y-1">
        <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70">Manajemen</div>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.dashboard')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.dashboard') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.dashboard')) fill @endif 
                @if(request()->routeIs('superadmin.dashboard')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                dashboard
            </span>
            <span class="font-body-md text-sm">Dashboard</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.manajemen-pengguna')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.manajemen-pengguna') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.manajemen-pengguna')) fill @endif 
                @if(request()->routeIs('superadmin.manajemen-pengguna')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                group
            </span>
            <span class="font-body-md text-sm">Pengguna</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.manajemen-toko')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.manajemen-toko') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.manajemen-toko')) fill @endif 
                @if(request()->routeIs('superadmin.manajemen-toko')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                storefront
            </span>
            <span class="font-body-md text-sm">Toko</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.moderasi-produk')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.moderasi-produk') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.moderasi-produk')) fill @endif 
                @if(request()->routeIs('superadmin.moderasi-produk')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                inventory_2
            </span>
            <span class="font-body-md text-sm">Produk Moderasi</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.kategori-produk')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.kategori-produk') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.kategori-produk')) fill @endif 
                @if(request()->routeIs('superadmin.kategori-produk')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                category
            </span>
            <span class="font-body-md text-sm">Kategori Produk</span>
        </a>
    </div>

    <!-- Finance Group -->
    <div class="space-y-1 pt-4">
        <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70">Keuangan</div>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.komisi-global')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.komisi-global') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.komisi-global')) fill @endif 
                @if(request()->routeIs('superadmin.komisi-global')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                currency_exchange
            </span>
            <span class="font-body-md text-sm">Komisi Global</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.permintaan-penarikan')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.permintaan-penarikan') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.permintaan-penarikan')) fill @endif 
                @if(request()->routeIs('superadmin.permintaan-penarikan')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                attach_money
            </span>
            <span class="font-body-md text-sm">Penarikan</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.pajak-biaya')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.pajak-biaya') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.pajak-biaya')) fill @endif 
                @if(request()->routeIs('superadmin.pajak-biaya')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                request_quote
            </span>
            <span class="font-body-md text-sm">Pajak & Biaya</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.data-bank')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.data-bank') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.data-bank')) fill @endif 
                @if(request()->routeIs('superadmin.data-bank')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                account_balance
            </span>
            <span class="font-body-md text-sm">Data Bank</span>
        </a>
    </div>

    <!-- Platform Group -->
    <div class="space-y-1 pt-4">
        <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70">Platform</div>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.promo-platform')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.promo-platform') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.promo-platform')) fill @endif 
                @if(request()->routeIs('superadmin.promo-platform')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                local_offer
            </span>
            <span class="font-body-md text-sm">Promo Platform</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.kurir')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.kurir') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.kurir')) fill @endif 
                @if(request()->routeIs('superadmin.kurir')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                local_shipping
            </span>
            <span class="font-body-md text-sm">Kurir</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.data-pesanan')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.data-pesanan') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.data-pesanan')) fill @endif 
                @if(request()->routeIs('superadmin.data-pesanan')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                shopping_cart
            </span>
            <span class="font-body-md text-sm">Data Pesanan</span>
        </a>
    </div>

    <!-- Monitoring Group -->
    <div class="space-y-1 pt-4">
        <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70">Monitoring</div>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.laporan')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.laporan') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.laporan')) fill @endif 
                @if(request()->routeIs('superadmin.laporan')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                bar_chart
            </span>
            <span class="font-body-md text-sm">Laporan</span>
        </a>
        
        <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
            @if(request()->routeIs('superadmin.riwayat-aktivitas')) 
                bg-gold-accent/10 text-gold-accent border-l-3 border-gold-accent 
            @else 
                text-white/80 hover:bg-white/5 hover:text-white 
            @endif"
            href="{{ route('superadmin.riwayat-aktivitas') }}">
            <span class="material-symbols-outlined text-[20px] @if(request()->routeIs('superadmin.riwayat-aktivitas')) fill @endif 
                @if(request()->routeIs('superadmin.riwayat-aktivitas')) text-gold-accent @else text-white/70 @endif 
                transition-colors">
                history
            </span>
            <span class="font-body-md text-sm">Riwayat Aktivitas</span>
        </a>
    </div>
</div>