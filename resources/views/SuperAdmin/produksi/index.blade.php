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
                <button type="button" data-chip="requested" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Requested ({{ $stats['requested'] }})</button>
                <button type="button" data-chip="diproses" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Diproses ({{ $stats['diproses'] }})</button>
                <button type="button" data-chip="menunggu_qc" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu QC ({{ $stats['menunggu_qc'] }})</button>
                <button type="button" data-chip="selesai" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Selesai ({{ $stats['selesai'] }})</button>
                <button type="button" data-chip="dibatalkan" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dibatalkan ({{ $stats['dibatalkan'] }})</button>
            </div>
        </div>

        <!-- Search + Result Count -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
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
    </div>

    <!-- Table -->
    <div class="overflow-x-auto hidden md:block">
        <table class="w-full min-w-[1050px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-center w-12">No.</th>
                    <th class="p-4 text-left">Nomor Produksi</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-center">Jumlah</th>
                    <th class="p-4 text-center">Prioritas</th>
                    <th class="p-4 text-left">Periode</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
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
                        <td class="p-4 text-on-surface max-w-[220px]">{{ \Illuminate\Support\Str::limit($prod->items->first()?->productVariant?->product?->nama_produk ?? '-', 30) }}</td>
                        <td class="p-4 text-center text-on-surface">{{ $prod->items->sum('jumlah_diminta') }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $prio['class'] }} text-[10px] font-bold uppercase border">{{ $prio['label'] }}</span>
                        </td>
                        <td class="p-4 text-on-surface-variant text-xs">
                            {{ $prod->dimulai_pada ? \Carbon\Carbon::parse($prod->dimulai_pada)->locale('id')->translatedFormat('d M Y') : '-' }} — {{ $prod->selesai_pada ? \Carbon\Carbon::parse($prod->selesai_pada)->locale('id')->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border">{{ $badge['label'] }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <button type="button" data-produksi-detail="{{ $prod->production_order_id }}" data-modal-open="detail-produksi" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-deep-onyx text-on-primary rounded-full text-[11px] font-bold uppercase tracking-widest border border-gold-accent/30 btn-premium focus:ring-2 focus:ring-gold-accent/20 focus:outline-none active:scale-[0.98]">
                                <span class="material-symbols-outlined text-sm">visibility</span> Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-on-surface-variant">Belum ada data produksi.</td>
                    </tr>
                @endforelse
                <tr id="empty-search" class="hidden">
                    <td colspan="9" class="p-8 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada data produksi yang cocok.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile: kartu per produksi -->
    <div class="md:hidden grid grid-cols-1 gap-gutter">
        @forelse($productions as $prod)
            @php
                $badge = $statusBadgeMap[$prod->status] ?? ['label' => $prod->status, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
                $prio = $prioMap[$prod->prioritas] ?? ['label' => '-', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
            @endphp
            <article data-table-row data-status="{{ $prod->status }}" data-search="{{ strtolower(($prod->nomor_produksi ?? '').' '.($prod->store->nama_toko ?? '').' '.($prod->items->first()?->productVariant?->product?->nama_produk ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="min-w-0">
                        <p class="font-mono font-bold text-on-surface leading-tight">{{ $prod->nomor_produksi }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">{{ $prod->store->nama_toko ?? '-' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border shrink-0">{{ $badge['label'] }}</span>
                </div>
                <dl class="space-y-2 font-body-md text-sm mb-3">
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Produk</dt>
                        <dd class="text-on-surface text-right">{{ $prod->items->first()?->productVariant?->product?->nama_produk ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Jumlah</dt>
                        <dd class="text-on-surface text-right">{{ $prod->items->sum('jumlah_diminta') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Prioritas</dt>
                        <dd class="text-right"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $prio['class'] }} text-[10px] font-bold uppercase border">{{ $prio['label'] }}</span></dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Dimulai</dt>
                        <dd class="text-on-surface text-right">{{ $prod->dimulai_pada ? \Carbon\Carbon::parse($prod->dimulai_pada)->locale('id')->translatedFormat('d M Y') : '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Selesai</dt>
                        <dd class="text-on-surface text-right">{{ $prod->selesai_pada ? \Carbon\Carbon::parse($prod->selesai_pada)->locale('id')->translatedFormat('d M Y') : '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3 items-start">
                        <dt class="text-on-surface-variant shrink-0">Catatan</dt>
                        <dd class="text-on-surface text-right">{{ \Illuminate\Support\Str::limit($prod->catatan ?? '-', 40) }}</dd>
                    </div>
                </dl>
                <button type="button" data-produksi-detail="{{ $prod->production_order_id }}" data-modal-open="detail-produksi" class="mt-2 w-full min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary rounded-lg font-label-sm text-[11px] uppercase tracking-widest border border-gold-accent/30 btn-premium focus:ring-2 focus:ring-gold-accent/20 focus:outline-none active:scale-[0.98]">
                    <span class="material-symbols-outlined text-sm">visibility</span> Detail
                </button>
            </article>
        @empty
            <p class="text-center text-on-surface-variant py-10">Belum ada data produksi.</p>
        @endforelse
        <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada data produksi yang cocok.</p>
    </div>
</section>

<!-- Modal Detail Produksi -->
<div id="detail-produksi" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-2xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-2xl flex flex-col max-h-[90vh] border-t-4 border-t-gold-accent/60">
        <!-- Header -->
        <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-muted-border rounded-t-xl">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-gold-accent">factory</span>
                </div>
                <div class="min-w-0">
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading uppercase">Detail Produksi</h3>
                    <p class="font-mono text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1 truncate"><span data-slot="nomor">-</span></p>
                </div>
            </div>
            <button type="button" data-modal-close class="p-1 -mr-1 rounded-full text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors shrink-0">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Scrollable body -->
        <div class="overflow-y-auto px-6 py-5 space-y-6">
            <div class="flex items-center gap-2 flex-wrap">
                <span data-prioritas-badge class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase border bg-surface-container-high text-on-surface-variant border-outline-variant shadow-sm">Prioritas: -</span>
                <span data-status-badge class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase border bg-surface-container-high text-on-surface-variant border-outline-variant shadow-sm">-</span>
            </div>

            <!-- Info Order -->
            <section>
                <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-3"><span class="material-symbols-outlined text-[16px]">assignment</span> Info Order</p>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 md:p-5">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 font-body-md text-sm">
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">storefront</span> Toko</dt>
                            <dd class="text-on-surface break-words"><span data-slot="toko">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">warehouse</span> Target Gudang</dt>
                            <dd class="text-on-surface break-words"><span data-slot="gudang">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">calendar_month</span> Periode</dt>
                            <dd class="text-on-surface"><span data-slot="periode">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">person</span> Pemohon</dt>
                            <dd class="text-on-surface break-words"><span data-slot="pemohon">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">engineering</span> Penanggung Jawab</dt>
                            <dd class="text-on-surface break-words"><span data-slot="pelaksana">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">event</span> Dibuat Pada</dt>
                            <dd class="text-on-surface"><span data-slot="dibuat">-</span></dd>
                        </div>
                    </dl>
                </div>
            </section>

            <!-- Catatan -->
            <div class="bg-surface-container-lowest border border-muted-border border-l-4 border-l-gold-accent/40 rounded-lg p-4 md:p-5">
                <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-2"><span class="material-symbols-outlined text-[16px]">notes</span> Catatan</p>
                <p class="text-on-surface whitespace-pre-line break-words font-body-md text-sm"><span data-slot="catatan">-</span></p>
            </div>

            <!-- Item Produksi -->
            <section>
                <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-3"><span class="material-symbols-outlined text-[16px]">checklist</span> Item Produksi <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[9px] font-bold" id="item-count">-</span></p>
                <div class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium card-static">
                    <table class="w-full text-sm premium-table">
                        <thead class="bg-surface-container-high">
                            <tr class="border-b border-muted-border text-on-surface-variant font-label-sm text-label-sm uppercase">
                                <th class="p-3 text-left">Produk</th>
                                <th class="p-3 text-left">SKU / Varian</th>
                                <th class="p-3 text-center">Diminta</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-sm" data-items-tbody></tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <div class="shrink-0 border-t border-muted-border rounded-b-xl px-6 py-4 bg-surface-container-lowest flex flex-col sm:flex-row gap-3">
            <button type="button" id="produksi-copy" class="flex-1 inline-flex items-center justify-center gap-2 py-3 border border-muted-border rounded-lg text-on-surface font-label-sm text-[11px] uppercase tracking-widest hover:border-gold-accent hover:text-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[16px]">content_copy</span> Salin No. Order
            </button>
            <button type="button" data-modal-close class="flex-1 inline-flex items-center justify-center gap-2 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded-lg btn-premium">
                <span class="material-symbols-outlined text-[16px]">close</span> Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-table-scope]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('tr[data-table-row], article[data-table-row]'));
        const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
        const searchInput = document.getElementById('produksi-search');
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

    const SA_PRIO_BADGES = {
        'rendah': ['Rendah', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
        'normal': ['Normal', 'bg-info/10 text-info border-info/20'],
        'tinggi': ['Tinggi', 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'urgent': ['Urgent', 'bg-error/10 text-error border-error/20'],
    };
    const SA_STATUS_BADGES = {
        'requested': ['Requested', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
        'diproses': ['Diproduksi', 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'menunggu_qc': ['Menunggu QC', 'bg-info/10 text-info border-info/20'],
        'selesai': ['Selesai', 'bg-success/10 text-success border-success/20'],
        'dibatalkan': ['Dibatalkan', 'bg-error/10 text-error border-error/20'],
    };
    const saBadgeFor = (map, key) => map[key] ?? [key, 'bg-surface-container-high text-on-surface-variant border-outline-variant'];

    const saSetBadge = (el, label, cls) => {
        if (!el) return;
        el.textContent = '';
        const dot = document.createElement('span');
        dot.className = 'w-1.5 h-1.5 rounded-full bg-current opacity-80';
        el.appendChild(dot);
        el.appendChild(document.createTextNode(label));
        el.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase border shadow-sm ' + cls;
    };

    const saInitial = (text) => (text || '?').trim().charAt(0).toUpperCase() || '?';

    const saFillText = (modal, key, value) => {
        modal.querySelectorAll('[data-slot="' + key + '"]').forEach((el) => { el.textContent = value; });
    };

    const saBuildCell = (text) => {
        const td = document.createElement('td');
        td.className = 'p-3';
        const wrap = document.createElement('div');
        wrap.className = 'flex items-center gap-2.5 min-w-0';
        const av = document.createElement('span');
        av.className = 'w-8 h-8 rounded-lg bg-gold-accent/10 border border-gold-accent/20 text-gold-accent text-[11px] font-bold flex items-center justify-center shrink-0';
        av.textContent = saInitial(text);
        const sp = document.createElement('span');
        sp.className = 'text-on-surface break-words';
        sp.textContent = text;
        wrap.appendChild(av);
        wrap.appendChild(sp);
        td.appendChild(wrap);
        return td;
    };

    const saBuildCellMuted = (text) => {
        const td = document.createElement('td');
        td.className = 'p-3 text-on-surface-variant text-xs';
        td.textContent = text;
        return td;
    };

    const saBuildCellNum = (text) => {
        const td = document.createElement('td');
        td.className = 'p-3 text-center';
        const v = document.createElement('span');
        v.className = 'inline-flex items-center px-2 py-0.5 rounded-md bg-surface-container-high text-on-surface font-bold text-xs';
        v.textContent = text;
        td.appendChild(v);
        return td;
    };

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-produksi-detail]');
        if (!btn) return;
        const modal = document.getElementById('detail-produksi');
        if (!modal) return;

        const id = btn.getAttribute('data-produksi-detail');
        modal.querySelectorAll('[data-slot]').forEach((el) => { el.textContent = '-'; });
        const itemsTbody = modal.querySelector('[data-items-tbody]');
        const countEl = document.getElementById('item-count');
        if (countEl) countEl.textContent = '-';
        if (itemsTbody) {
            itemsTbody.innerHTML = '<tr><td colspan="3"><div class="space-y-2 p-3">' +
                '<div class="h-3 rounded bg-surface-container-high animate-pulse"></div>'.repeat(3) +
                '</div></td></tr>';
        }

        try {
            const res = await fetch(`/superadmin/produksi/${id}/detail`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();
            const o = data.order || {};

            saFillText(modal, 'nomor', o.nomor ?? '-');
            saFillText(modal, 'toko', o.toko ?? '-');
            saFillText(modal, 'gudang', o.gudang ?? '-');
            saFillText(modal, 'periode', [o.dimulai, o.selesai].join(' — '));
            saFillText(modal, 'catatan', o.catatan ?? '-');
            saFillText(modal, 'pemohon', o.pemohon ?? '-');
            saFillText(modal, 'pelaksana', o.pelaksana ?? '-');
            saFillText(modal, 'dibuat', o.dibuat ?? '-');

            const [prioLabel, prioCls] = saBadgeFor(SA_PRIO_BADGES, o.prioritas ?? '-');
            saSetBadge(modal.querySelector('[data-prioritas-badge]'), 'Prioritas: ' + prioLabel, prioCls);

            const [statusLabel, statusCls] = saBadgeFor(SA_STATUS_BADGES, o.status ?? '-');
            saSetBadge(modal.querySelector('[data-status-badge]'), statusLabel, statusCls);
            if (countEl) countEl.textContent = String(data.items?.length ?? 0);

            if (itemsTbody) {
                const items = data.items || [];
                itemsTbody.innerHTML = '';
                if (items.length) {
                    const frag = document.createDocumentFragment();
                    items.forEach((i) => {
                        const tr = document.createElement('tr');
                        tr.appendChild(saBuildCell(i.nama ?? '-'));
                        tr.appendChild(saBuildCellMuted([i.sku ?? '-', i.varian ? ' • ' + i.varian : ''].join('')));
                        tr.appendChild(saBuildCellNum(String(i.jumlah ?? 0)));
                        frag.appendChild(tr);
                    });
                    itemsTbody.appendChild(frag);
                } else {
                    itemsTbody.innerHTML = '<tr><td colspan="3" class="p-6 text-center"><div class="flex flex-col items-center gap-2"><span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">checklist</span><p class="text-on-surface-variant font-body-md text-sm">Belum ada item tercatat.</p></div></td></tr>';
                }
            }
} catch (err) {
            if (itemsTbody) itemsTbody.innerHTML = '<tr><td colspan="3" class="p-6 text-center text-on-surface-variant">Gagal memuat detail.</td></tr>';
        }
    });

    const copyBtn = document.getElementById('produksi-copy');
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            const text = (document.querySelector('#detail-produksi [data-slot="nomor"]')?.textContent || '').trim();
            if (!text || text === '-') return;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => window.showRalivaToast?.('Nomor order disalin', 'content_copy'));
            }
        });
    }
</script>
@endpush
