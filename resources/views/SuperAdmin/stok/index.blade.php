@extends('layouts.superadmin')

@section('title', 'Stok')

@section('header-title', 'Stok')
@section('header-badge', 'Pantau')
@section('header-subtitle', 'Pantau ketersediaan stok dari seluruh toko di platform.')

@php
    $statusBadgeMap = [
        'aman' => ['label' => 'Aman', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'menipis' => ['label' => 'Menipis', 'class' => 'bg-error/10 text-error border-error/20'],
        'habis' => ['label' => 'Habis', 'class' => 'bg-error/10 text-error border-error/20'],
    ];
@endphp

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Monitor Stok Platform</h2>
        <div class="flex items-center gap-3 flex-wrap">
            <button type="button" data-filter-toggle class="md:hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Filter
                <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
            </button>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">
                <span class="material-symbols-outlined text-[14px]">visibility</span> Mode Pantau
            </span>
        </div>
    </div>

    <!-- Filters -->
    <div data-filter-panel class="hidden md:block mb-6">
        <div class="mb-4 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
            </div>
            <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
            <div id="chip-group" class="flex flex-wrap gap-2">
                <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua ({{ $stats['semua'] }})</button>
                <button type="button" data-chip="aman" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Aman ({{ $stats['aman'] }})</button>
                <button type="button" data-chip="menipis" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menipis ({{ $stats['menipis'] }})</button>
                <button type="button" data-chip="habis" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Habis ({{ $stats['habis'] }})</button>
            </div>
        </div>

        <!-- Search + Result Count -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input id="stok-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama produk, SKU, toko, atau gudang..." />
                <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                <span id="result-count">{{ $stocks->count() }}</span> item stok
            </p>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto hidden md:block">
        <table class="w-full min-w-[850px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-center w-12">No.</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-left">SKU</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-center">Stok</th>
                    <th class="p-4 text-center">Direservasi</th>
                    <th class="p-4 text-center">Minimum</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($stocks as $stock)
                    @php $badge = $statusBadgeMap[$stock->status_stok] ?? ['label' => $stock->status_stok, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant']; @endphp
                    <tr data-table-row data-status="{{ $stock->status_stok }}" data-search="{{ strtolower($stock->nama_produk.' '.($stock->sku ?? '').' '.($stock->warna ?? '').' '.($stock->ukuran ?? '').' '.$stock->nama_toko.' '.($stock->nama_gudang ?? '')) }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono row-num"></td>
                        <td class="p-4">
                            <p class="text-on-surface">{{ $stock->nama_produk }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $stock->warna ? $stock->warna.' • ' : '' }}{{ $stock->ukuran ?? '-' }}</p>
                        </td>
                        <td class="p-4 font-mono text-on-surface-variant text-xs">{{ $stock->sku }}</td>
                        <td class="p-4 text-on-surface">{{ $stock->nama_toko }}</td>
                        <td class="p-4 text-center font-bold {{ $stock->status_stok === 'habis' || $stock->status_stok === 'menipis' ? 'text-error' : 'text-on-surface' }}">{{ $stock->jumlah_stok }}</td>
                        <td class="p-4 text-center text-on-surface-variant">{{ $stock->jumlah_direservasi }}</td>
                        <td class="p-4 text-center text-on-surface-variant">{{ $stock->stok_minimum }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border">{{ $badge['label'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-on-surface-variant">Belum ada data stok tercatat.</td>
                    </tr>
                @endforelse
                <tr id="empty-search" class="hidden">
                    <td colspan="8" class="p-8 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada data stok yang cocok.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile: kartu per item stok -->
    <div class="md:hidden grid grid-cols-1 gap-gutter">
        @forelse($stocks as $stock)
            @php $badge = $statusBadgeMap[$stock->status_stok] ?? ['label' => $stock->status_stok, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant']; @endphp
            <article data-table-row data-status="{{ $stock->status_stok }}" data-search="{{ strtolower($stock->nama_produk.' '.($stock->sku ?? '').' '.($stock->warna ?? '').' '.($stock->ukuran ?? '').' '.$stock->nama_toko.' '.($stock->nama_gudang ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="min-w-0">
                        <p class="font-title-md text-title-md text-on-surface leading-tight">{{ $stock->nama_produk }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">{{ $stock->warna ? $stock->warna.' • ' : '' }}{{ $stock->ukuran ?? '-' }}</p>
                        <p class="font-mono text-on-surface-variant text-[10px] mt-0.5">{{ $stock->sku }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border shrink-0">{{ $badge['label'] }}</span>
                </div>
                <dl class="space-y-2 font-body-md text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Toko</dt>
                        <dd class="text-on-surface text-right">{{ $stock->nama_toko }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Stok</dt>
                        <dd class="font-bold {{ $stock->status_stok === 'habis' || $stock->status_stok === 'menipis' ? 'text-error' : 'text-on-surface' }} text-right">{{ $stock->jumlah_stok }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Direservasi</dt>
                        <dd class="text-on-surface text-right">{{ $stock->jumlah_direservasi }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Minimum</dt>
                        <dd class="text-on-surface text-right">{{ $stock->stok_minimum }}</dd>
                    </div>
                </dl>
            </article>
        @empty
            <p class="text-center text-on-surface-variant py-10">Belum ada data stok tercatat.</p>
        @endforelse
        <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada data stok yang cocok.</p>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-table-scope]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('tr[data-table-row], article[data-table-row]'));
        const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
        const searchInput = document.getElementById('stok-search');
        const clearBtn = document.getElementById('clear-search');
        const countEl = document.getElementById('result-count');
        const emptySearch = document.getElementById('empty-search');
        const emptySearchMobile = document.getElementById('empty-search-mobile');

        const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
        const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

        let activeStatus = 'semua';

        function applyFilter() {
            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach((row) => {
                const matchStatus = activeStatus === 'semua' || row.getAttribute('data-status') === activeStatus;
                const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
                const show = matchStatus && matchSearch;
                row.classList.toggle('hidden', !show);
                if (show) {
                    visible++;
                    const num = row.querySelector('.row-num');
                    if (num) num.textContent = visible;
                }
            });

            countEl.textContent = visible;
            emptySearch.classList.toggle('hidden', visible > 0);
            if (emptySearchMobile) emptySearchMobile.classList.toggle('hidden', visible > 0);
        }

        chipBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                chipBtns.forEach((b) => {
                    b.classList.remove(...activeClasses);
                    b.classList.add(...idleClasses, 'hover:bg-surface-container-high');
                });
                btn.classList.remove(...idleClasses, 'hover:bg-surface-container-high');
                btn.classList.add(...activeClasses);
                activeStatus = btn.getAttribute('data-chip');
                applyFilter();
            });
        });

        let debounce;
        searchInput.addEventListener('input', () => {
            clearBtn.classList.toggle('opacity-0', !searchInput.value);
            clearTimeout(debounce);
            debounce = setTimeout(applyFilter, 200);
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('opacity-0');
            applyFilter();
        });

        applyFilter();
    });
</script>
@endpush
