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
            <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70">{{ $group['label'] }}</div>
            <div class="space-y-1">
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
