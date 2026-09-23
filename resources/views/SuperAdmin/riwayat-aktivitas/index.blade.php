@extends('layouts.superadmin')

@section('title', 'Riwayat Aktivitas')

@section('header-title', 'Riwayat Aktivitas')
@section('header-badge', 'Lihat')

@section('header-subtitle', 'Catatan audit tindakan penting pengguna dan admin sistem.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .icon-fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filter-scroll::-webkit-scrollbar { height: 4px; }
    .filter-scroll::-webkit-scrollbar-track { background: transparent; }
    .filter-scroll::-webkit-scrollbar-thumb { background-color: #e3e2e2; border-radius: 4px; }
    .timeline-line::before { content: ''; position: absolute; left: 20px; top: 48px; bottom: -24px; width: 1px; background: linear-gradient(to bottom, rgba(139, 30, 63,0.55), rgba(139, 30, 63,0.06)); z-index: 0; }
    .timeline-item:last-child .timeline-line::before { display: none; }
</style>
@endpush

@section('content')

@php
    $kategoriAktif = request('kategori', 'semua');
    $iconMap = [
        'user' => 'person',
        'store' => 'storefront',
        'product' => 'inventory_2',
        'order' => 'shopping_cart',
        'withdrawal' => 'account_balance',
        'refund' => 'currency_exchange',
        'commission' => 'percent',
        'wallet' => 'account_balance_wallet',
        'setting' => 'settings',
        'system' => 'settings',
        'payment' => 'payments',
        'promo' => 'local_offer',
        'slot' => 'grid_view',
        'adslot' => 'campaign',
        'complaint' => 'support_agent',
    ];
    $kategoriTagMap = [
        'user' => 'Pengguna',
        'store' => 'Toko',
        'product' => 'Produk',
        'order' => 'Pesanan',
        'withdrawal' => 'Keuangan',
        'refund' => 'Keuangan',
        'commission' => 'Keuangan',
        'wallet' => 'Keuangan',
        'setting' => 'Sistem',
        'system' => 'Sistem',
        'payment' => 'Keuangan',
        'promo' => 'Promo',
        'slot' => 'Produk',
        'adslot' => 'Iklan',
        'complaint' => 'Komplain',
    ];
    $kategoriTabMap = [
        'user' => 'pengguna',
        'store' => 'toko',
        'product' => 'produk',
        'order' => 'keuangan',
        'withdrawal' => 'keuangan',
        'refund' => 'keuangan',
        'commission' => 'keuangan',
        'wallet' => 'keuangan',
        'setting' => 'sistem',
        'system' => 'sistem',
        'payment' => 'keuangan',
        'promo' => 'sistem',
        'slot' => 'produk',
        'adslot' => 'produk',
        'complaint' => 'sistem',
    ];
@endphp

<!-- Filters -->
<div class="mb-8">
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Kategori</span>
            <a href="{{ route('superadmin.riwayat-aktivitas.export', ['kategori' => request('kategori')]) }}" class="ml-auto inline-flex items-center gap-1.5 px-3 py-1.5 border border-muted-border rounded-lg text-[11px] font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[16px]">download</span> Export CSV
            </a>
        </div>
        <div class="flex overflow-x-auto filter-scroll pb-2 -mx-gutter px-gutter md:mx-0 md:px-0 space-x-4">
            @foreach(['semua' => 'Semua Aktivitas', 'pengguna' => 'Pengguna', 'toko' => 'Toko', 'produk' => 'Produk', 'keuangan' => 'Keuangan', 'sistem' => 'Sistem'] as $key => $label)
            <a href="{{ route('superadmin.riwayat-aktivitas', array_filter(['kategori' => $key === 'semua' ? null : $key])) }}"
               class="whitespace-nowrap px-4 py-2 border-b-2 {{ $kategoriAktif === $key ? 'border-primary text-on-surface' : 'border-transparent text-on-surface-variant hover:text-on-surface' }} font-label-sm text-label-sm uppercase transition-colors">
                {{ $label }}
            </a>
            @endforeach
        </div>
        <div class="h-[1px] w-full bg-muted-border -mt-[1px]"></div>
    </div>
</div>

<!-- Timeline -->
<div class="timeline space-y-6" data-since="{{ optional($logs->first())->activity_log_id ?? 0 }}">
    @forelse($logs as $log)
        @php
            $prefix = explode('.', $log->aksi)[0] ?? 'system';
            $icon = $iconMap[$prefix] ?? 'info';
            $tag = $kategoriTagMap[$prefix] ?? ucfirst($prefix);
            $tabKategori = $kategoriTabMap[$prefix] ?? 'sistem';
            $waktu = $log->created_at ? \Carbon\Carbon::parse($log->created_at)->locale('id')->diffForHumans() : '-';
        @endphp
        <div data-kategori="{{ $tabKategori }}" class="timeline-item relative timeline-line">
            <div class="flex items-start">
                <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(139, 30, 63,0.08)] mt-1">
                    <span class="material-symbols-outlined text-gold-accent text-sm">{{ $icon }}</span>
                </div>
                <div class="ml-element-gap flex-grow">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1">
                        <span class="font-title-md text-title-md text-on-surface">{{ $log->aksi }}</span>
                        <span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">{{ $waktu }}</span>
                    </div>
<div class="p-4 bg-surface-container-low border border-muted-border rounded-lg mt-2 card-premium">
                        <div class="text-sm">
                            @if($log->user)
                                <span class="font-bold text-on-surface">{{ $log->user->nama_lengkap }}</span>
                            @endif
                            {{ $log->deskripsi ?? $log->aksi }}
                        </div>
                        @if($log->nilai_lama || $log->nilai_baru)
                            <div class="mt-2 text-sm text-on-surface-variant bg-surface p-2 border border-muted-border rounded-sm">
                                @if($log->nilai_lama && $log->nilai_baru)
                                    Perubahan data tercatat.
                                @elseif($log->nilai_baru)
                                    Data baru ditambahkan.
                                @endif
                            </div>
                        @endif
                        <div class="mt-3 flex gap-2">
                            <span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-full">{{ $tag }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-on-surface-variant font-body-md text-sm py-8">
            Belum ada aktivitas tercatat.
        </div>
    @endforelse

    @if($logs->hasPages())
        <div class="pt-4">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    const wrap = document.querySelector('.timeline');
    if (!wrap) return;

    const list = wrap;
    let since = Number(wrap.dataset.since || 0);
    const emptyBox = wrap.querySelector('div.text-center');

    function itemHtml(item) {
        const user = item.user ? '<span class="font-bold text-on-surface">' + item.user + '</span> ' : '';
        const perubahan = item.ada_perubahan ? '<div class="mt-2 text-sm text-on-surface-variant bg-surface p-2 border border-muted-border rounded-sm">Perubahan data tercatat.</div>' : '';
        return '<div class="timeline-item relative timeline-line">' +
            '<div class="flex items-start">' +
            '<div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(139,30,63,0.08)] mt-1">' +
            '<span class="material-symbols-outlined text-gold-accent text-sm">' + item.icon + '</span></div>' +
            '<div class="ml-element-gap flex-grow">' +
            '<div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1">' +
            '<span class="font-title-md text-title-md text-on-surface">' + item.aksi + '</span>' +
            '<span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">' + item.waktu + '</span></div>' +
            '<div class="p-4 bg-surface-container-low border border-muted-border rounded-lg mt-2 card-premium">' +
            '<div class="text-sm">' + user + item.deskripsi + '</div>' + perubahan +
            '<div class="mt-3 flex gap-2">' +
            '<span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-full">' + item.tag + '</span>' +
            '</div></div></div></div></div>';
    }

    async function refresh() {
        try {
            const res = await fetch('{{ route('superadmin.riwayat-aktivitas.baru') }}?since=' + since, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const items = await res.json();
            if (!items || !items.length) return;

            since = Math.max(since, ...items.map(i => Number(i.id)));

            if (emptyBox) emptyBox.remove();

            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'timeline-item-fresh';
                div.innerHTML = itemHtml(item);
                list.insertBefore(div.firstChild, list.firstChild);
            });
        } catch (e) { /* abaikan */ }
    }

    setInterval(refresh, 30000);
})();
</script>
@endpush
