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

        <!-- Search -->
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
            <input id="stok-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama produk, SKU, toko, gudang, atau supplier..." />
            <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto hidden md:block">
        <table class="w-full min-w-[1180px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-center w-12">No.</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-left">SKU</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Supplier</th>
                    <th class="p-4 text-center">Stok</th>
                    <th class="p-4 text-center">Direservasi</th>
                    <th class="p-4 text-center">Minimum</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($stocks as $stock)
                    @php $badge = $statusBadgeMap[$stock->status_stok] ?? ['label' => $stock->status_stok, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant']; @endphp
                    <tr data-table-row data-status="{{ $stock->status_stok }}" data-search="{{ strtolower($stock->nama_produk.' '.($stock->sku ?? '').' '.($stock->warna ?? '').' '.($stock->ukuran ?? '').' '.$stock->nama_toko.' '.($stock->nama_gudang ?? '').' '.($stock->nama_supplier ?? '')) }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono row-num"></td>
                        <td class="p-4">
                            <p class="text-on-surface">{{ $stock->nama_produk }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $stock->warna ? $stock->warna.' • ' : '' }}{{ $stock->ukuran ?? '-' }}</p>
                        </td>
                        <td class="p-4 font-mono text-on-surface-variant text-xs">{{ $stock->sku }}</td>
                        <td class="p-4 text-on-surface">{{ $stock->nama_toko }}</td>
                        <td class="p-4">
                            @if ($stock->nama_supplier)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[10px] font-bold uppercase max-w-[150px] truncate" title="{{ $stock->nama_supplier }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-80 shrink-0"></span>{{ $stock->nama_supplier }}
                            </span>
                            @else
                            <span class="text-on-surface-variant text-xs">-</span>
                            @endif
                        </td>
                        <td class="p-4 text-center font-bold {{ $stock->status_stok === 'habis' || $stock->status_stok === 'menipis' ? 'text-error' : 'text-on-surface' }}">{{ $stock->jumlah_stok }}</td>
                        <td class="p-4 text-center text-on-surface-variant">{{ $stock->jumlah_direservasi }}</td>
                        <td class="p-4 text-center text-on-surface-variant">{{ $stock->stok_minimum }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border">{{ $badge['label'] }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <button type="button" data-stok-detail="{{ $stock->warehouse_stock_id }}" data-modal-open="detail-stok" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-deep-onyx text-on-primary rounded-full text-[11px] font-bold uppercase tracking-widest border border-gold-accent/30 btn-premium focus:ring-2 focus:ring-gold-accent/20 focus:outline-none active:scale-[0.98]">
                                <span class="material-symbols-outlined text-sm">visibility</span> Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="p-8 text-center text-on-surface-variant">Belum ada data stok tercatat.</td>
                    </tr>
                @endforelse
                <tr id="empty-search" class="hidden">
                    <td colspan="10" class="p-8 text-center">
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
            <article data-table-row data-status="{{ $stock->status_stok }}" data-search="{{ strtolower($stock->nama_produk.' '.($stock->sku ?? '').' '.($stock->warna ?? '').' '.($stock->ukuran ?? '').' '.$stock->nama_toko.' '.($stock->nama_gudang ?? '').' '.($stock->nama_supplier ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
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
                        <dt class="text-on-surface-variant">Supplier</dt>
                        <dd class="text-right">
                            @if ($stock->nama_supplier)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[10px] font-bold uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-80"></span>{{ $stock->nama_supplier }}
                            </span>
                            @else
                            <span class="text-on-surface-variant text-xs">-</span>
                            @endif
                        </dd>
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
                <button type="button" data-stok-detail="{{ $stock->warehouse_stock_id }}" data-modal-open="detail-stok" class="mt-4 w-full min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary rounded-lg font-label-sm text-[11px] uppercase tracking-widest border border-gold-accent/30 btn-premium focus:ring-2 focus:ring-gold-accent/20 focus:outline-none active:scale-[0.98]">
                    <span class="material-symbols-outlined text-sm">visibility</span> Detail
                </button>
            </article>
        @empty
            <p class="text-center text-on-surface-variant py-10">Belum ada data stok tercatat.</p>
        @endforelse
        <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada data stok yang cocok.</p>
    </div>
</section>

<!-- Modal Detail Stok -->
<div id="detail-stok" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-2xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-2xl flex flex-col max-h-[90vh] border-t-4 border-t-gold-accent/60">
        <!-- Header -->
        <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-muted-border rounded-t-xl">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-gold-accent">inventory_2</span>
                </div>
                <div class="min-w-0">
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading uppercase">Detail Stok</h3>
                    <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1 truncate"><span data-slot="judul-nama">-</span></p>
                </div>
            </div>
            <button type="button" data-modal-close class="p-1 -mr-1 rounded-full text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors shrink-0">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Scrollable body -->
        <div class="overflow-y-auto px-6 py-5 space-y-6">
            <!-- Info Stok -->
            <section>
                <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-3"><span class="material-symbols-outlined text-[16px]">inventory_2</span> Info Stok</p>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 md:p-5">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 font-body-md text-sm">
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">storefront</span> Toko</dt>
                            <dd class="text-on-surface break-words"><span data-slot="toko">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">warehouse</span> Gudang</dt>
                            <dd class="text-on-surface break-words"><span data-slot="gudang">-</span></dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">tag</span> SKU / Varian</dt>
                            <dd class="font-mono text-on-surface break-words"><span data-slot="sku">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">warehouse</span> Stok Tersedia</dt>
                            <dd class="font-bold text-on-surface"><span data-slot="jumlah">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">event_available</span> Direservasi</dt>
                            <dd class="text-on-surface"><span data-slot="reservasi">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">warning</span> Minimum</dt>
                            <dd class="text-on-surface"><span data-slot="minimum">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">verified</span> Status</dt>
                            <dd><span data-slot="status-badge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border bg-surface-container-high text-on-surface-variant border-outline-variant"></span></dd>
                        </div>
                    </dl>
                </div>
            </section>

            <!-- Supplier Terakhir -->
            <section>
                <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-3"><span class="material-symbols-outlined text-[16px]">local_shipping</span> Supplier Terakhir</p>
                <div data-supplier-wrap>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 md:p-5 space-y-2">
                        <div class="h-3 rounded bg-surface-container-high animate-pulse"></div>
                        <div class="h-3 w-2/3 rounded bg-surface-container-high animate-pulse"></div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <div class="shrink-0 border-t border-muted-border rounded-b-xl px-6 py-4 bg-surface-container-lowest flex flex-col sm:flex-row gap-3">
            <button type="button" id="stok-copy-supplier" class="flex-1 inline-flex items-center justify-center gap-2 py-3 border border-muted-border rounded-lg text-on-surface font-label-sm text-[11px] uppercase tracking-widest hover:border-gold-accent hover:text-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[16px]">content_copy</span> Salin Kontak Supplier
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

        const desktopRows = Array.from(scope.querySelectorAll('tr[data-table-row]'));
        const mobileRows = Array.from(scope.querySelectorAll('article[data-table-row]'));
        const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
        const searchInput = document.getElementById('stok-search');
        const clearBtn = document.getElementById('clear-search');
        const emptySearch = document.getElementById('empty-search');
        const emptySearchMobile = document.getElementById('empty-search-mobile');

        const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
        const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

        let activeStatus = 'semua';

        function applyFilter() {
            const term = searchInput.value.trim().toLowerCase();
            let desktopVisible = 0;
            let mobileVisible = 0;

            desktopRows.forEach((row) => {
                const matchStatus = activeStatus === 'semua' || row.getAttribute('data-status') === activeStatus;
                const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
                const show = matchStatus && matchSearch;
                row.classList.toggle('hidden', !show);
                if (show) {
                    desktopVisible++;
                    const num = row.querySelector('.row-num');
                    if (num) num.textContent = desktopVisible;
                }
            });

            mobileRows.forEach((row) => {
                const matchStatus = activeStatus === 'semua' || row.getAttribute('data-status') === activeStatus;
                const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
                const show = matchStatus && matchSearch;
                row.classList.toggle('hidden', !show);
                if (show) mobileVisible++;
            });

            const hasResults = desktopVisible > 0 || mobileVisible > 0;
            if (emptySearch) emptySearch.classList.toggle('hidden', hasResults);
            if (emptySearchMobile) emptySearchMobile.classList.toggle('hidden', hasResults);
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

        const saStatusBadge = (status) => ({
            'aman': ['Aman', 'bg-secondary-container/20 text-secondary border-secondary/20'],
            'menipis': ['Menipis', 'bg-error/10 text-error border-error/20'],
            'habis': ['Habis', 'bg-error/10 text-error border-error/20'],
        }[status] ?? [status, 'bg-surface-container-high text-on-surface-variant border-outline-variant']);

        const saSupplierStatusBadge = (status) => ({
            'aktif': ['Aktif', 'bg-secondary-container/20 text-secondary border-secondary/20'],
            'verifikasi': ['Verifikasi', 'bg-gold-accent/10 text-gold-accent border-gold-accent/30'],
        }[status] ?? ['Non-aktif', 'bg-error/10 text-error border-error/25']);

        const saSetBadge = (el, label, cls) => {
            if (!el) return;
            el.textContent = '';
            const dot = document.createElement('span');
            dot.className = 'w-1.5 h-1.5 rounded-full bg-current opacity-80';
            el.appendChild(dot);
            el.appendChild(document.createTextNode(label));
            el.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border ' + cls;
        };

        const saFillText = (modal, key, value) => {
            modal.querySelectorAll('[data-slot="' + key + '"]').forEach((el) => { el.textContent = value; });
        };

        const saBuildDtRow = (icon, label, value) => {
            const div = document.createElement('div');
            const dt = document.createElement('dt');
            dt.className = 'flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5';
            const ic = document.createElement('span');
            ic.className = 'material-symbols-outlined text-gold-accent text-[16px]';
            ic.textContent = icon || 'person';
            dt.appendChild(ic);
            dt.appendChild(document.createTextNode(label));
            const dd = document.createElement('dd');
            dd.className = 'text-on-surface break-words';
            dd.textContent = value || '-';
            div.appendChild(dt);
            div.appendChild(dd);
            return div;
        };

        const saBuildSupplierCard = (s) => {
            const card = document.createElement('div');
            card.className = 'bg-surface-container-low border border-muted-border rounded-lg p-4 md:p-5';

            const head = document.createElement('div');
            head.className = 'flex flex-wrap items-center gap-2 mb-1.5';
            const nama = document.createElement('p');
            nama.className = 'font-title-md text-title-md text-on-surface leading-tight break-words';
            nama.textContent = s.nama || '-';
            head.appendChild(nama);
            const jenis = document.createElement('span');
            jenis.className = 'px-2 py-1 rounded bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wide shrink-0';
            jenis.textContent = s.jenis || '-';
            head.appendChild(jenis);
            const status = document.createElement('span');
            const [label, cls] = saSupplierStatusBadge(s.status || 'nonaktif');
            status.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border shrink-0 ' + cls;
            status.textContent = label;
            head.appendChild(status);
            card.appendChild(head);

            const alamat = document.createElement('p');
            alamat.className = 'text-on-surface-variant text-xs mb-4';
            alamat.textContent = s.alamat && s.alamat !== '-' ? s.alamat + (s.kota && s.kota !== '-' ? ', ' + s.kota : '') : (s.kota && s.kota !== '-' ? s.kota : '-');
            card.appendChild(alamat);

            const dl = document.createElement('dl');
            dl.className = 'grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 font-body-md text-sm';
            dl.appendChild(saBuildDtRow('person', 'Kontak', s.kontak));
            dl.appendChild(saBuildDtRow('mail', 'Email', s.email));
            dl.appendChild(saBuildDtRow('schedule', 'Masuk Terakhir', s.masuk_pada));
            dl.appendChild(saBuildDtRow('location_on', 'Kota', s.kota));
            card.appendChild(dl);

            if (s.catatan && s.catatan !== '-') {
                const catatan = document.createElement('div');
                catatan.className = 'mt-4 border-l-4 border-l-gold-accent/40 pl-3';
                const catLabel = document.createElement('p');
                catLabel.className = 'text-on-surface-variant text-[10px] uppercase tracking-widest mb-0.5';
                catLabel.textContent = 'Catatan';
                const catBody = document.createElement('p');
                catBody.className = 'text-on-surface text-xs whitespace-pre-line';
                catBody.textContent = s.catatan;
                catatan.appendChild(catLabel);
                catatan.appendChild(catBody);
                card.appendChild(catatan);
            }

            return card;
        };

        const saBuildSupplierEmpty = () => {
            const box = document.createElement('div');
            box.className = 'bg-surface-container-low border border-muted-border rounded-lg p-6 text-center';
            const ic = document.createElement('span');
            ic.className = 'material-symbols-outlined text-on-surface-variant/50 text-[32px]';
            ic.textContent = 'local_shipping';
            const p = document.createElement('p');
            p.className = 'text-on-surface-variant font-body-md text-sm mt-2';
            p.textContent = 'Belum ada riwayat supplier untuk stok ini.';
            box.appendChild(ic);
            box.appendChild(p);
            return box;
        };

        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('[data-stok-detail]');
            if (!btn) return;
            const modal = document.getElementById('detail-stok');
            if (!modal) return;

            const id = btn.getAttribute('data-stok-detail');
            modal.querySelectorAll('[data-slot]').forEach((el) => { el.textContent = '-'; });
            const supplierWrap = modal.querySelector('[data-supplier-wrap]');
            if (supplierWrap) {
                supplierWrap.innerHTML = '<div class="bg-surface-container-low border border-muted-border rounded-lg p-4 md:p-5 space-y-2">' +
                    '<div class="h-3 rounded bg-surface-container-high animate-pulse"></div>' +
                    '<div class="h-3 w-2/3 rounded bg-surface-container-high animate-pulse"></div>' +
                    '</div>';
            }

            try {
                const res = await fetch(`/superadmin/stok/${id}/detail`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                const s = data.stock || {};
                const w = data.warehouse || {};

                saFillText(modal, 'judul-nama', [s.nama ?? '-', s.varian ? ` \u2022 ${s.varian}` : ''].join(''));
                saFillText(modal, 'toko', w.toko ?? '-');
                saFillText(modal, 'gudang', w.nama ?? '-');
                saFillText(modal, 'sku', [s.sku ?? '-', s.varian ? ` (${s.varian})` : ''].join(''));
                saFillText(modal, 'jumlah', String(s.jumlah ?? 0));
                saFillText(modal, 'reservasi', String(s.reservasi ?? 0));
                saFillText(modal, 'minimum', String(s.minimum ?? 0));

                const jumlahEl = modal.querySelectorAll('[data-slot="jumlah"]');
                const stokBad = (s.status === 'habis' || s.status === 'menipis');
                jumlahEl.forEach((el) => el.classList.toggle('text-error', stokBad));

                const badge = modal.querySelector('[data-slot="status-badge"]');
                if (badge) {
                    const [label, cls] = saStatusBadge(s.status ?? '-');
                    saSetBadge(badge, label, cls);
                }

                if (supplierWrap) {
                    supplierWrap.innerHTML = '';
                    if (data.supplier) {
                        supplierWrap.appendChild(saBuildSupplierCard(data.supplier));
                    } else {
                        supplierWrap.appendChild(saBuildSupplierEmpty());
                    }
                }
                modal.dataset.supplierNama = data.supplier?.nama || '';
                modal.dataset.supplierKontak = data.supplier?.kontak || '';
                modal.dataset.supplierEmail = data.supplier?.email || '';
            } catch (err) {
                if (supplierWrap) supplierWrap.innerHTML = '<div class="bg-surface-container-low border border-muted-border rounded-lg p-6 text-center text-on-surface-variant text-sm">Gagal memuat detail.</div>';
            }
        });

        const copySupplierBtn = document.getElementById('stok-copy-supplier');
        if (copySupplierBtn) {
            copySupplierBtn.addEventListener('click', () => {
                const modal = document.getElementById('detail-stok');
                const nama = modal?.dataset.supplierNama || '';
                const kontak = modal?.dataset.supplierKontak || '';
                const email = modal?.dataset.supplierEmail || '';
                const text = [nama, kontak, email].filter(Boolean).join(' \u2022 ');
                if (!text) return;
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(text).then(() => window.showRalivaToast?.('Kontak supplier disalin', 'content_copy'));
                }
            });
        }

        applyFilter();
    });
</script>
@endpush
