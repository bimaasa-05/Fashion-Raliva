@php
    $menuGroups = [
        [
            'label' => 'Utama',
            'items' => [
                ['route' => 'owner.dashboard', 'icon' => 'space_dashboard', 'text' => 'Dashboard Toko'],
            ],
        ],
        [
            'label' => 'Toko Saya',
            'items' => [
                ['route' => 'owner.data-toko', 'icon' => 'storefront', 'text' => 'Data Toko'],
                ['route' => 'owner.pengajuan-toko', 'icon' => 'fact_check', 'text' => 'Pengajuan Toko'],
                ['route' => 'owner.pengaturan-toko', 'icon' => 'tune', 'text' => 'Pengaturan Toko'],
            ],
        ],
        [
            'label' => 'Katalog',
            'items' => [
                ['route' => 'owner.produk', 'icon' => 'checkroom', 'text' => 'Data Produk'],
                ['route' => 'owner.moderasi-produk', 'icon' => 'verified', 'text' => 'Moderasi Produk'],
                ['route' => 'owner.paket-slot', 'icon' => 'grid_view', 'text' => 'Paket Slot Produk'],
            ],
        ],
        [
            'label' => 'Penjualan',
            'items' => [
                ['route' => 'owner.pesanan', 'icon' => 'shopping_bag', 'text' => 'Data Pesanan'],
                ['route' => 'owner.promo', 'icon' => 'local_offer', 'text' => 'Promo Toko'],
                ['route' => 'owner.ulasan', 'icon' => 'star', 'text' => 'Ulasan & Penilaian'],
            ],
        ],
        [
            'label' => 'Operasional',
            'items' => [
                ['route' => 'owner.produksi', 'icon' => 'precision_manufacturing', 'text' => 'Produksi'],
                ['route' => 'owner.gudang', 'icon' => 'warehouse', 'text' => 'Gudang'],
                ['route' => 'owner.pengiriman', 'icon' => 'local_shipping', 'text' => 'Pengiriman'],
            ],
        ],
        [
            'label' => 'Keuangan',
            'items' => [
                ['route' => 'owner.saldo', 'icon' => 'account_balance_wallet', 'text' => 'Saldo Toko'],
                ['route' => 'owner.pencairan-dana', 'icon' => 'payments', 'text' => 'Pencairan Dana'],
                ['route' => 'owner.pengembalian-dana', 'icon' => 'assignment_return', 'text' => 'Pengembalian Dana'],
                ['route' => 'owner.komplain', 'icon' => 'support_agent', 'text' => 'Komplain'],
            ],
        ],
        [
            'label' => 'Tim & Laporan',
            'items' => [
                ['route' => 'owner.karyawan', 'icon' => 'groups', 'text' => 'Karyawan'],
                ['route' => 'owner.laporan', 'icon' => 'monitoring', 'text' => 'Laporan Toko'],
            ],
        ],
        [
            'label' => 'Akun',
            'items' => [
                ['route' => 'owner.profil', 'icon' => 'person', 'text' => 'Profil'],
            ],
        ],
    ];
@endphp
<div class="space-y-2">
    @foreach ($menuGroups as $group)
        @php
            $collapsible = count($group['items']) >= 3;
            $isActive = collect($group['items'])->contains(fn ($item) => request()->routeIs($item['route']));
        @endphp
        <div class="space-y-1 {{ $loop->first ? '' : 'pt-4' }}">
            @if ($collapsible)
                <button type="button" data-sidebar-group-button class="w-full flex items-center justify-between px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70 hover:text-gold-accent transition-colors">
                    <span data-group-label>{{ $group['label'] }}</span>
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200 {{ $isActive ? 'rotate-180' : '' }}">keyboard_arrow_down</span>
                </button>
            @else
                <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70"><span data-group-label>{{ $group['label'] }}</span></div>
            @endif
            <div class="{{ $collapsible ? 'grid transition-[grid-template-rows] duration-300 ease-out ' . ($isActive ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]') : 'space-y-1' }}" @if ($collapsible) data-sidebar-group @endif>
                <div class="{{ $collapsible ? 'min-h-0 overflow-hidden' : '' }}">
                    <div class="{{ $collapsible ? 'space-y-1' : '' }}">
                @foreach ($group['items'] as $item)
                    <a class="group flex items-center gap-2.5 py-2.5 transition-all duration-200
                        @if(request()->routeIs($item['route']))
                            pl-3 pr-[28px] mr-[-16px] rounded-l-lg bg-gold-accent/10 text-gold-accent border-l-[3px] border-gold-accent
                        @else
                            px-3 rounded-lg text-on-sidebar/80 hover:bg-sidebar-hover hover:text-on-sidebar border-l-[3px] border-transparent
                        @endif"
                        href="{{ route($item['route']) }}">
                        <span class="material-symbols-outlined text-[20px] @if(request()->routeIs($item['route'])) fill text-gold-accent @else text-on-sidebar/60 group-hover:text-on-sidebar transition-colors @endif">
                            {{ $item['icon'] }}
                        </span>
                        <span class="sidebar-tip">{{ $item['text'] }}</span>
                        <span data-menu-label class="font-body-md text-[13.5px] leading-snug flex-1 min-w-0 truncate">{{ $item['text'] }}</span>
                    </a>
                @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
