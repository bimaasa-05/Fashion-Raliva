@extends('layouts.superadmin')

@section('title', 'Data Produk')

@section('header-title', 'Data Produk')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Lihat katalog produk dari seluruh toko di platform.')

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Katalog Produk Platform</h2>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">
            <span class="material-symbols-outlined text-[14px]">visibility</span> Mode Lihat
        </span>
    </div>

    <!-- Filters -->
    <div class="mb-4 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
        </div>
        <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
        <div id="chip-group" class="flex flex-wrap gap-2">
            <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua ({{ $stats['semua'] }})</button>
            <button type="button" data-chip="pending" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu ({{ $stats['pending'] }})</button>
            <button type="button" data-chip="aktif" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Aktif ({{ $stats['aktif'] }})</button>
            <button type="button" data-chip="ditolak" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Ditolak ({{ $stats['ditolak'] }})</button>
            <button type="button" data-chip="nonaktif" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Nonaktif ({{ $stats['nonaktif'] }})</button>
            <button type="button" data-chip="draft" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Draft ({{ $stats['draft'] }})</button>
            <button type="button" data-chip="arsip" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Arsip ({{ $stats['arsip'] }})</button>
        </div>
    </div>

    <!-- Search + Result Count -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-3">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input id="produk-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama produk, toko, atau kategori..." />
            <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <p class="text-on-surface-variant font-body-md text-xs shrink-0">
            <span id="result-count">{{ $products->count() }}</span> produk
        </p>
    </div>

    <!-- Products Table -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-center w-12">No.</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Kategori</th>
                    <th class="p-4 text-left">Tipe</th>
                    <th class="p-4 text-right">Harga</th>
                    <th class="p-4 text-center">Status Moderasi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse ($products as $produk)
                    @php
                        $statusLabel = match ($produk->status) {
                            'aktif' => ['Disetujui', 'bg-secondary-container/20 text-secondary border-secondary/20'],
                            'pending' => ['Menunggu', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                            'ditolak' => ['Ditolak', 'bg-error/10 text-error border-error/20'],
                            'nonaktif' => ['Nonaktif', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                            'draft' => ['Draft', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                            'arsip' => ['Arsip', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                            default => [ucfirst($produk->status), 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                        };
                    @endphp
                    <tr data-table-row data-status="{{ $produk->status }}" data-search="{{ strtolower($produk->nama_produk.' '.($produk->store->nama_toko ?? '').' '.($produk->category->nama_kategori ?? '')) }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono row-num"></td>
                        <td class="p-4 text-on-surface">{{ $produk->nama_produk }}</td>
                        <td class="p-4 text-on-surface">{{ $produk->store->nama_toko ?? '-' }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $produk->category->nama_kategori ?? '-' }}</td>
                        <td class="p-4"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ ucfirst($produk->tipe_produk) }}</span></td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp {{ number_format((float) $produk->harga_dasar, 0, ',', '.') }}</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $statusLabel[1] }} text-[10px] font-bold uppercase border">{{ $statusLabel[0] }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-on-surface-variant">Belum ada produk terdaftar di platform.</td>
                    </tr>
                @endforelse
                <tr id="empty-search" class="hidden">
                    <td colspan="7" class="p-8 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada produk yang cocok.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const scope = document.querySelector('[data-table-scope]');
    if (!scope) return;

    const rows = Array.from(scope.querySelectorAll('[data-table-row]'));
    const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
    const searchInput = document.getElementById('produk-search');
    const clearBtn = document.getElementById('clear-search');
    const countEl = document.getElementById('result-count');
    const emptySearch = document.getElementById('empty-search');

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
                row.querySelector('.row-num').textContent = visible;
            }
        });

        countEl.textContent = visible;
        emptySearch.classList.toggle('hidden', visible > 0);

        if (rows.length === 0) {
            emptySearch.classList.add('hidden');
        }
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
