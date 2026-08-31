@extends('layouts.superadmin')

@section('title', 'Produksi')

@section('header-title', 'Produksi')
@section('header-badge', 'Pantau')
@section('header-subtitle', 'Pantau permintaan dan status produksi dari seluruh toko.')

@php
    $statusBadgeMap = [
        'requested' => ['label' => 'Requested', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
        'diproses' => ['label' => 'Diproduksi', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'menunggu_qc' => ['label' => 'Menunggu QC', 'class' => 'bg-info/10 text-info border-info/20'],
        'selesai' => ['label' => 'Selesai', 'class' => 'bg-success/10 text-success border-success/20'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-error/10 text-error border-error/20'],
    ];
    $prioMap = [
        'rendah' => ['label' => 'Rendah', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
        'normal' => ['label' => 'Normal', 'class' => 'bg-info/10 text-info border-info/20'],
        'tinggi' => ['label' => 'Tinggi', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'urgent' => ['label' => 'Urgent', 'class' => 'bg-error/10 text-error border-error/20'],
    ];
@endphp

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Monitor Produksi Platform</h2>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">
            <span class="material-symbols-outlined text-[14px]">visibility</span> Mode Pantau
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
            <button type="button" data-chip="requested" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Requested ({{ $stats['requested'] }})</button>
            <button type="button" data-chip="diproses" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Diproses ({{ $stats['diproses'] }})</button>
            <button type="button" data-chip="menunggu_qc" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu QC ({{ $stats['menunggu_qc'] }})</button>
            <button type="button" data-chip="selesai" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Selesai ({{ $stats['selesai'] }})</button>
            <button type="button" data-chip="dibatalkan" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dibatalkan ({{ $stats['dibatalkan'] }})</button>
        </div>
    </div>

    <!-- Search + Result Count -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center gap-3">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input id="produksi-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nomor produksi, toko, atau nama produk..." />
            <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <p class="text-on-surface-variant font-body-md text-xs shrink-0">
            <span id="result-count">{{ $productions->count() }}</span> produksi
        </p>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-center w-12">No.</th>
                    <th class="p-4 text-left">Nomor Produksi</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-center">Jumlah</th>
                    <th class="p-4 text-center">Prioritas</th>
                    <th class="p-4 text-left">Catatan</th>
                    <th class="p-4 text-left">Dimulai</th>
                    <th class="p-4 text-left">Selesai</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($productions as $prod)
                    @php
                        $badge = $statusBadgeMap[$prod->status] ?? ['label' => $prod->status, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
                        $prio = $prioMap[$prod->prioritas] ?? ['label' => '-', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
                    @endphp
                    <tr data-table-row data-status="{{ $prod->status }}" data-search="{{ strtolower(($prod->nomor_produksi ?? '').' '.($prod->store->nama_toko ?? '').' '.($prod->items->first()?->productVariant?->product?->nama_produk ?? '')) }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono row-num"></td>
                        <td class="p-4 font-mono text-on-surface">{{ $prod->nomor_produksi }}</td>
                        <td class="p-4 text-on-surface">{{ $prod->store->nama_toko ?? '-' }}</td>
                        <td class="p-4 text-on-surface">{{ $prod->items->first()?->productVariant?->product?->nama_produk ?? '-' }}</td>
                        <td class="p-4 text-center text-on-surface">{{ $prod->items->sum('jumlah_diminta') }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $prio['class'] }} text-[10px] font-bold uppercase border">{{ $prio['label'] }}</span>
                        </td>
                        <td class="p-4 text-on-surface-variant text-xs max-w-[200px]" title="{{ $prod->catatan }}">{{ \Illuminate\Support\Str::limit($prod->catatan ?? '-', 40) }}</td>
                        <td class="p-4 text-on-surface-variant text-xs">{{ $prod->dimulai_pada ? \Carbon\Carbon::parse($prod->dimulai_pada)->locale('id')->translatedFormat('d M Y') : '-' }}</td>
                        <td class="p-4 text-on-surface-variant text-xs">{{ $prod->selesai_pada ? \Carbon\Carbon::parse($prod->selesai_pada)->locale('id')->translatedFormat('d M Y') : '-' }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border">{{ $badge['label'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="p-8 text-center text-on-surface-variant">Belum ada data produksi.</td>
                    </tr>
                @endforelse
                <tr id="empty-search" class="hidden">
                    <td colspan="10" class="p-8 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada data produksi yang cocok.</p>
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
        const searchInput = document.getElementById('produksi-search');
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
