@php
    $menuGroups = [
        [
            'label' => 'Utama',
            'items' => [
                ['route' => 'gudang.dashboard', 'icon' => 'space_dashboard', 'text' => 'Dashboard Gudang'],
            ],
        ],
        [
            'label' => 'Persediaan',
            'items' => [
                ['route' => 'gudang.stok', 'icon' => 'inventory_2', 'text' => 'Data Stok'],
                ['route' => 'gudang.barang-masuk', 'icon' => 'archive', 'text' => 'Barang Masuk'],
                ['route' => 'gudang.barang-keluar', 'icon' => 'unarchive', 'text' => 'Barang Keluar'],
                ['route' => 'gudang.pemindahan', 'icon' => 'swap_horiz', 'text' => 'Pemindahan Stok'],
                ['route' => 'gudang.pemeriksaan', 'icon' => 'fact_check', 'text' => 'Pemeriksaan Stok'],
                ['route' => 'gudang.stok-rusak', 'icon' => 'report', 'text' => 'Stok Rusak'],
                ['route' => 'gudang.riwayat-stok', 'icon' => 'history', 'text' => 'Riwayat Stok'],
            ],
        ],
    ];
@endphp
<div class="space-y-2">
    @foreach ($menuGroups as $group)
        <div class="space-y-1 {{ $loop->first ? '' : 'pt-4' }}">
            @if ($collapsible)
                <button type="button" data-sidebar-group-button class="w-full flex items-center justify-between px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70 hover:text-gold-accent transition-colors">
                    <span>{{ $group['label'] }}</span>
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200 {{ $isActive ? 'rotate-180' : '' }}">keyboard_arrow_down</span>
                </button>
            @else
                <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70">{{ $group['label'] }}</div>
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
                        <span class="material-symbols-outlined text-[20px] @if(request()->routeIs($item['route'])) fill text-gold-accent @else text-on-sidebar/60 @endif transition-colors">
                            {{ $item['icon'] }}
                        </span>
                        <span class="font-body-md text-[13.5px] leading-snug flex-1 min-w-0 truncate" title="{{ $item['text'] }}">{{ $item['text'] }}</span>
                    </a>
                @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
