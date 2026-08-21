@php
    $menuGroups = [
        [
            'label' => 'Utama',
            'items' => [
                ['route' => 'admin.dashboard', 'icon' => 'space_dashboard', 'text' => 'Dashboard Operasional'],
            ],
        ],
        [
            'label' => 'Transaksi',
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.pesanan', 'icon' => 'shopping_cart', 'text' => 'Data Pesanan'],
                ['route' => 'admin.verifikasi-pembayaran', 'icon' => 'fact_check', 'text' => 'Verifikasi Pembayaran'],
                ['route' => 'admin.pengembalian-dana', 'icon' => 'assignment_return', 'text' => 'Pengembalian Dana'],
            ],
        ],
        [
            'label' => 'Pelanggan',
            'items' => [
                ['route' => 'admin.customer', 'icon' => 'person_search', 'text' => 'Data Customer'],
                ['route' => 'admin.komplain', 'icon' => 'support_agent', 'text' => 'Komplain'],
            ],
        ],
        [
            'label' => 'Katalog',
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.produk', 'icon' => 'checkroom', 'text' => 'Data Produk'],
                ['route' => 'admin.stok', 'icon' => 'inventory_2', 'text' => 'Stok'],
                ['route' => 'admin.promo', 'icon' => 'local_offer', 'text' => 'Promo'],
            ],
        ],
        [
            'label' => 'Logistik',
            'collapsible' => true,
            'items' => [
                ['route' => 'admin.pengiriman', 'icon' => 'local_shipping', 'text' => 'Pengiriman'],
                ['route' => 'admin.koordinasi-gudang', 'icon' => 'warehouse', 'text' => 'Koordinasi Gudang'],
                ['route' => 'admin.permintaan-produksi', 'icon' => 'precision_manufacturing', 'text' => 'Permintaan Produksi'],
            ],
        ],
    ];
@endphp
<div class="space-y-2">
    @foreach ($menuGroups as $group)
        @php
            $collapsible = !empty($group['collapsible']);
            $isActive = collect($group['items'])->contains(fn ($item) => request()->routeIs($item['route']));
        @endphp
        <div class="space-y-1 {{ $loop->first ? '' : 'pt-4' }}">
            @if ($collapsible)
                <button type="button" data-sidebar-group-button class="w-full flex items-center justify-between px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70 hover:text-gold-accent transition-colors">
                    <span>{{ $group['label'] }}</span>
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200 {{ $isActive ? 'rotate-180' : '' }}">keyboard_arrow_down</span>
                </button>
            @else
                <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70">{{ $group['label'] }}</div>
            @endif
            <div class="space-y-1 {{ $collapsible && !$isActive ? 'hidden' : '' }}" @if ($collapsible) data-sidebar-group @endif>
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
        </div>
    @endforeach
</div>
