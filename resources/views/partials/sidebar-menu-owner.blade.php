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
            ],
        ],
        [
            'label' => 'Operasional',
            'items' => [
                ['route' => 'owner.produk', 'icon' => 'checkroom', 'text' => 'Data Produk'],
                ['route' => 'owner.moderasi-produk', 'icon' => 'inventory_2', 'text' => 'Moderasi Produk'],
                ['route' => 'owner.promo', 'icon' => 'local_offer', 'text' => 'Promo Toko'],
                ['route' => 'owner.data-pelanggan', 'icon' => 'groups', 'text' => 'Data Pelanggan'],
                ['route' => 'owner.kelola-slot', 'aliases' => ['owner.paket-slot'], 'icon' => 'storage', 'text' => 'Management Slot'],
                ['route' => 'owner.peringkat-iklan', 'icon' => 'workspace_premium', 'text' => 'Iklan Peringkat'],
            ],
        ],
        [
            'label' => 'Penjualan',
            'items' => [
                ['route' => 'owner.pesanan', 'icon' => 'shopping_bag', 'text' => 'Data Pesanan'],
                ['route' => 'owner.ulasan', 'icon' => 'star', 'text' => 'Ulasan & Penilaian'],
                ['route' => 'owner.komplain', 'aliases' => ['owner.komplain.messages', 'owner.pengembalian-dana'], 'icon' => 'move_up', 'text' => 'Eskalasi'],
            ],
        ],
        [
            'label' => 'Keuangan',
            'items' => [
                ['route' => 'owner.keuangan', 'icon' => 'account_balance_wallet', 'text' => 'Keuangan'],
                ['route' => 'owner.data-bank', 'icon' => 'account_balance', 'text' => 'Data Bank'],
                ['route' => 'owner.pencairan-dana', 'icon' => 'payments', 'text' => 'Pencairan Dana'],
            ],
        ],
        [
            'label' => 'Tim & Laporan',
            'items' => [
                ['route' => 'owner.karyawan', 'icon' => 'manage_accounts', 'text' => 'Karyawan'],
                ['route' => 'owner.laporan', 'icon' => 'monitoring', 'text' => 'Laporan Toko'],
            ],
        ],
    ];
@endphp
<div class="space-y-2">
    @foreach ($menuGroups as $group)
        @php
            $collapsible = count($group['items']) >= 3;
            $isActive = collect($group['items'])->contains(fn ($item) => request()->routeIs($item['route'], ...($item['aliases'] ?? [])));
        @endphp
        <div class="space-y-1 {{ $loop->first ? '' : 'pt-4' }}">
            @if ($collapsible)
                <button type="button" data-sidebar-group-button aria-expanded="{{ $isActive ? 'true' : 'false' }}" class="w-full flex items-center justify-between px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70 hover:text-gold-accent transition-colors">
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
                        @if(request()->routeIs($item['route'], ...($item['aliases'] ?? [])))
                            pl-3 pr-[28px] mr-[-16px] rounded-l-lg bg-gold-accent/10 text-gold-accent border-l-[3px] border-gold-accent
                        @else
                            px-3 rounded-lg text-on-sidebar/80 hover:bg-sidebar-hover hover:text-on-sidebar border-l-[3px] border-transparent
                        @endif"
                        href="{{ route($item['route']) }}">
                        <span class="material-symbols-outlined text-[20px] @if(request()->routeIs($item['route'], ...($item['aliases'] ?? []))) fill text-gold-accent @else text-on-sidebar/60 @endif transition-colors">
                            {{ $item['icon'] }}
                        </span>
                        <span class="sidebar-tip">{{ $item['text'] }}</span>
                        <span data-menu-label class="font-body-md text-[13.5px] leading-snug flex-1 min-w-0 truncate" title="{{ $item['text'] }}">{{ $item['text'] }}</span>
                    </a>
                @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
