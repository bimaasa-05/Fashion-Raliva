@php
    $storeLocked = \App\Support\StoreGate::isLocked();
    $sidebarBadges = \App\Support\GudangBadgeCounter::counts();
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
                ['route' => 'gudang.pemindahan', 'icon' => 'swap_horiz', 'text' => 'Pemindahan Stok', 'badge' => 'pemindahan'],
                ['route' => 'gudang.pemeriksaan', 'icon' => 'fact_check', 'text' => 'Pemeriksaan Stok', 'badge' => 'stok_menipis'],
                ['route' => 'gudang.stok-rusak', 'icon' => 'report', 'text' => 'Stok Rusak'],
                ['route' => 'gudang.riwayat-stok', 'icon' => 'history', 'text' => 'Riwayat Stok'],
            ],
        ],
        [
            'label' => 'Operasional',
            'items' => [
                ['route' => 'gudang.permintaan', 'icon' => 'send', 'text' => 'Permintaan', 'badge' => 'permintaan'],
                ['route' => 'gudang.notifikasi', 'icon' => 'notifications', 'text' => 'Notifikasi', 'badge' => 'notifikasi'],
            ],
        ],
        [
            'label' => 'Akun',
            'items' => [
                ['route' => 'gudang.profil', 'icon' => 'person', 'text' => 'Profil'],
            ],
        ],
    ];
@endphp
<div class="space-y-2">
    @foreach ($menuGroups as $group)
        @php
            $collapsible = count($group['items']) >= 3;
            $isActive = collect($group['items'])->contains(fn ($item) => request()->routeIs($item['route']));
            $groupBadgeKeys = collect($group['items'])->filter(fn ($item) => ! empty($item['badge']))->pluck('badge')->values();
            $groupHasBadges = $groupBadgeKeys->contains(fn ($key) => ($sidebarBadges[$key] ?? 0) > 0);
        @endphp
        <div class="space-y-1 {{ $loop->first ? '' : 'pt-4' }}">
            @if ($collapsible)
                <button type="button" data-sidebar-group-button aria-expanded="{{ $isActive ? 'true' : 'false' }}" class="w-full flex items-center justify-between px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70 hover:text-gold-accent transition-colors">
                    <span class="flex items-center gap-2 min-w-0">
                        <span data-group-label>{{ $group['label'] }}</span>
                        <span data-sidebar-group-dot data-group-badges="{{ $groupBadgeKeys->implode(',') }}" class="w-2 h-2 rounded-full bg-error shrink-0 {{ $groupHasBadges ? '' : 'hidden' }}"></span>
                    </span>
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-200 {{ $isActive ? 'rotate-180' : '' }}">keyboard_arrow_down</span>
                </button>
            @else
                <div class="px-2 py-2 text-[10px] font-label-sm uppercase tracking-widest text-gold-accent/70"><span data-group-label>{{ $group['label'] }}</span></div>
            @endif
            <div class="{{ $collapsible ? 'grid transition-[grid-template-rows] duration-300 ease-out ' . ($isActive ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]') : 'space-y-1' }}" @if ($collapsible) data-sidebar-group @endif>
                <div class="{{ $collapsible ? 'min-h-0 overflow-hidden' : '' }}">
                    <div class="{{ $collapsible ? 'space-y-1' : '' }}">
                @foreach ($group['items'] as $item)
                    @php
                        $locked = $storeLocked && ! in_array($item['route'], ['gudang.dashboard', 'gudang.profil'], true);
                        $isActive = request()->routeIs($item['route']);
                    @endphp
                    @if ($locked)
                        <div class="group flex items-center gap-2.5 py-2.5 px-3 rounded-lg text-on-sidebar/35 border-l-[3px] border-transparent cursor-not-allowed" title="Menu terkunci — toko sedang ditangguhkan">
                            <span class="material-symbols-outlined text-[20px] text-on-sidebar/30">{{ $item['icon'] }}</span>
                            <span data-menu-label class="font-body-md text-[13.5px] leading-snug flex-1 min-w-0 truncate">{{ $item['text'] }}</span>
                            <span class="material-symbols-outlined text-[16px] text-gold-accent/60">lock</span>
                        </div>
                    @else
                    <a class="group flex items-center gap-2.5 py-2.5 transition-all duration-200
                        @if($isActive)
                            pl-3 pr-[28px] mr-[-16px] rounded-l-lg bg-gold-accent/10 text-gold-accent border-l-[3px] border-gold-accent
                        @else
                            px-3 rounded-lg text-on-sidebar/80 hover:bg-sidebar-hover hover:text-on-sidebar border-l-[3px] border-transparent
                        @endif"
                        href="{{ route($item['route']) }}">
                        <span class="material-symbols-outlined text-[20px] @if($isActive) fill text-gold-accent @else text-on-sidebar/60 @endif transition-colors">
                            {{ $item['icon'] }}
                        </span>
                        <span class="sidebar-tip">{{ $item['text'] }}</span>
                        <span data-menu-label class="font-body-md text-[13.5px] leading-snug flex-1 min-w-0 truncate">{{ $item['text'] }}</span>
                        @if (! empty($item['badge']) && (($sidebarBadges[$item['badge']] ?? 0) > 0))
                            <span data-sidebar-badge="{{ $item['badge'] }}" class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-gold-accent text-white text-[10px] font-bold shrink-0">{{ min(99, $sidebarBadges[$item['badge']]) }}</span>
                        @elseif (! empty($item['badge']))
                            <span data-sidebar-badge="{{ $item['badge'] }}" class="hidden"></span>
                        @endif
                    </a>
                    @endif
                @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
(function () {
    if (window.__ralivaSidebarBadgesGudang) return;
    window.__ralivaSidebarBadgesGudang = true;

    const els = Array.from(document.querySelectorAll('[data-sidebar-badge]'));
    if (!els.length) return;

    let busy = false;
    async function refresh() {
        if (busy) return;
        busy = true;
        try {
            const res = await fetch('{{ route('gudang.sidebar-badges') }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            els.forEach((el) => {
                const val = Number(data[el.dataset.sidebarBadge] || 0);
                if (val > 0) {
                    el.textContent = Math.min(99, val);
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
            document.querySelectorAll('[data-sidebar-group-dot]').forEach((dot) => {
                const keys = (dot.dataset.groupBadges || '').split(',').filter(Boolean);
                const total = keys.reduce((sum, key) => sum + Number(data[key] || 0), 0);
                dot.classList.toggle('hidden', total === 0);
            });
        } catch (e) { /* jangan ganggu polling berikutnya */ }
        busy = false;
    }

    refresh();
    setInterval(refresh, 30000);
})();
</script>
