@php
    $sidebarBadges = \App\Support\SuperAdminBadgeCounter::counts();

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
                ['route' => 'superadmin.manajemen-toko', 'icon' => 'storefront', 'text' => 'Data Toko', 'badge' => 'toko'],
                ['route' => 'superadmin.moderasi-produk', 'icon' => 'inventory_2', 'text' => 'Moderasi Produk', 'badge' => 'produk'],
                ['route' => 'superadmin.perubahan-produk', 'icon' => 'edit_note', 'text' => 'Perubahan Produk', 'badge' => 'perubahan_produk'],
                ['route' => 'superadmin.kategori', 'icon' => 'category', 'text' => 'Kategori'],
                ['route' => 'superadmin.produk', 'icon' => 'checkroom', 'text' => 'Data Produk'],
                ['route' => 'superadmin.slot-produk', 'icon' => 'grid_view', 'text' => 'Slot Produk', 'badge' => 'slot'],
                ['route' => 'superadmin.store-staff', 'icon' => 'manage_accounts', 'text' => 'Staff Toko'],
            ],
        ],
        [
            'label' => 'Transaksi',
            'items' => [
                ['route' => 'superadmin.data-pesanan', 'icon' => 'shopping_cart', 'text' => 'Data Pesanan'],
                ['route' => 'superadmin.data-pembayaran', 'icon' => 'payments', 'text' => 'Data Pembayaran'],
                ['route' => 'superadmin.verifikasi-topup', 'icon' => 'account_balance_wallet', 'text' => 'Verifikasi Top Up', 'badge' => 'topup'],
                ['route' => 'superadmin.pengembalian-dana', 'icon' => 'assignment_return', 'text' => 'Pengembalian Dana', 'badge' => 'refund'],
                ['route' => 'superadmin.permintaan-penarikan', 'icon' => 'attach_money', 'text' => 'Pencairan Dana', 'badge' => 'penarikan'],
            ],
        ],
        [
            'label' => 'Keuangan',
            'items' => [
                ['route' => 'superadmin.komisi-global', 'icon' => 'currency_exchange', 'text' => 'Komisi Raliva'],
                ['route' => 'superadmin.pajak-biaya', 'icon' => 'request_quote', 'text' => 'Pajak & Biaya Layanan'],
                ['route' => 'superadmin.saldo-toko', 'icon' => 'account_balance_wallet', 'text' => 'Saldo Toko'],
            ],
        ],
        [
            'label' => 'Operasional',
            'items' => [
                ['route' => 'superadmin.pengiriman', 'icon' => 'local_shipping', 'text' => 'Pengiriman'],
                ['route' => 'superadmin.stok', 'icon' => 'inventory', 'text' => 'Stok'],
                ['route' => 'superadmin.produksi', 'icon' => 'precision_manufacturing', 'text' => 'Produksi'],
                ['route' => 'superadmin.gudang', 'icon' => 'warehouse', 'text' => 'Gudang'],
                ['route' => 'superadmin.supplier', 'icon' => 'handshake', 'text' => 'Data Supplier'],
            ],
        ],
        [
            'label' => 'Platform',
            'items' => [
                ['route' => 'superadmin.promo-slot', 'icon' => 'local_offer', 'text' => 'Promo Slot'],
                ['route' => 'superadmin.data-bank', 'icon' => 'account_balance', 'text' => 'Data Bank'],
                ['route' => 'superadmin.kurir', 'icon' => 'moped', 'text' => 'Kurir'],
            ],
        ],
        [
            'label' => 'Monitoring',
            'items' => [
                ['route' => 'superadmin.laporan', 'icon' => 'bar_chart', 'text' => 'Laporan'],
                ['route' => 'superadmin.peringkat', 'icon' => 'leaderboard', 'text' => 'Peringkat'],
                ['route' => 'superadmin.peringkat-iklan', 'icon' => 'campaign', 'text' => 'Peringkat Produk Iklan', 'badge' => 'iklan'],
                ['route' => 'superadmin.riwayat-aktivitas', 'icon' => 'history', 'text' => 'Riwayat Aktivitas', 'badge' => 'aktivitas'],
                ['route' => 'superadmin.ulasan-produk-toko', 'icon' => 'star_rate', 'text' => 'Ulasan Produk Toko'],
                ['route' => 'superadmin.komplain', 'icon' => 'support_agent', 'text' => 'Komplain', 'badge' => 'komplain'],
                ['route' => 'superadmin.notifikasi', 'icon' => 'notifications', 'text' => 'Notifikasi'],
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
                        <span class="sidebar-tip">{{ $item['text'] }}</span>
                        <span data-menu-label class="font-body-md text-[13.5px] leading-snug flex-1 min-w-0 truncate">{{ $item['text'] }}</span>
                        @if (! empty($item['badge']) && (($sidebarBadges[$item['badge']] ?? 0) > 0))
                            <span data-sidebar-badge="{{ $item['badge'] }}" class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-gold-accent text-white text-[10px] font-bold shrink-0">{{ min(99, $sidebarBadges[$item['badge']]) }}</span>
                        @elseif (! empty($item['badge']))
                            <span data-sidebar-badge="{{ $item['badge'] }}" class="hidden"></span>
                        @endif
                    </a>
                @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
(function () {
    if (window.__ralivaSidebarBadgesStarted) return;
    window.__ralivaSidebarBadgesStarted = true;

    const els = Array.from(document.querySelectorAll('[data-sidebar-badge]'));
    if (!els.length) return;

    let busy = false;
    async function refresh() {
        if (busy) return;
        busy = true;
        try {
            const res = await fetch('{{ route('superadmin.sidebar-badges') }}', {
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
