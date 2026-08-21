@php
    $menuGroups = [
        [
            'label' => 'Utama',
            'items' => [
                ['route' => 'superadmin.dashboard', 'icon' => 'dashboard', 'text' => 'Dashboard'],
            ],
        ],
        [
            'label' => 'Manajemen',
            'items' => [
                ['route' => 'superadmin.manajemen-pengguna', 'icon' => 'group', 'text' => 'Data Pengguna'],
                ['route' => 'superadmin.manajemen-toko', 'icon' => 'storefront', 'text' => 'Data Toko'],
                ['route' => 'superadmin.moderasi-produk', 'icon' => 'inventory_2', 'text' => 'Moderasi Produk'],
                ['route' => 'superadmin.kategori-produk', 'icon' => 'category', 'text' => 'Kategori Produk'],
                ['route' => 'superadmin.paket-slot-produk', 'icon' => 'package_2', 'text' => 'Paket Slot Produk'],
            ],
        ],
        [
            'label' => 'Transaksi',
            'items' => [
                ['route' => 'superadmin.data-pesanan', 'icon' => 'shopping_cart', 'text' => 'Data Pesanan'],
                ['route' => 'superadmin.data-pembayaran', 'icon' => 'payments', 'text' => 'Data Pembayaran'],
                ['route' => 'superadmin.pengembalian-dana', 'icon' => 'assignment_return', 'text' => 'Pengembalian Dana'],
                ['route' => 'superadmin.permintaan-penarikan', 'icon' => 'attach_money', 'text' => 'Pencairan Dana'],
            ],
        ],
        [
            'label' => 'Keuangan',
            'items' => [
                ['route' => 'superadmin.komisi-global', 'icon' => 'currency_exchange', 'text' => 'Komisi Raliva'],
                ['route' => 'superadmin.pajak-biaya', 'icon' => 'request_quote', 'text' => 'Pajak & Biaya Layanan'],
            ],
        ],
        [
            'label' => 'Platform',
            'items' => [
                ['route' => 'superadmin.promo-platform', 'icon' => 'local_offer', 'text' => 'Promo Platform'],
                ['route' => 'superadmin.data-bank', 'icon' => 'account_balance', 'text' => 'Data Bank'],
                ['route' => 'superadmin.kurir', 'icon' => 'local_shipping', 'text' => 'Kurir'],
            ],
        ],
        [
            'label' => 'Monitoring',
            'items' => [
                ['route' => 'superadmin.laporan', 'icon' => 'bar_chart', 'text' => 'Laporan'],
                ['route' => 'superadmin.riwayat-aktivitas', 'icon' => 'history', 'text' => 'Riwayat Aktivitas'],
                ['route' => 'superadmin.pengaturan-sistem', 'icon' => 'settings', 'text' => 'Pengaturan Sistem'],
            ],
        ],
    ];
@endphp
<div class="space-y-2">
    @foreach ($menuGroups as $group)
        <div class="space-y-1 {{ $loop->first ? '' : 'pt-4' }}">
            <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70">{{ $group['label'] }}</div>
            @foreach ($group['items'] as $item)
                <a class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200
                    @if(request()->routeIs($item['route']))
                        bg-gold-accent/10 text-gold-accent border-l-[3px] border-gold-accent
                    @else
                        text-white/80 hover:bg-white/5 hover:text-white
                    @endif"
                    href="{{ route($item['route']) }}">
                    <span class="material-symbols-outlined text-[20px] @if(request()->routeIs($item['route'])) fill text-gold-accent @else text-white/70 @endif transition-colors">
                        {{ $item['icon'] }}
                    </span>
                    <span class="font-body-md text-sm">{{ $item['text'] }}</span>
                </a>
            @endforeach
        </div>
    @endforeach
</div>
