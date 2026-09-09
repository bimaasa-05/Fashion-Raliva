@extends('layouts.superadmin')

@section('title', 'Gudang')

@section('header-title', 'Gudang')
@section('header-badge', 'Pantau')
@section('header-subtitle', 'Pantau data gudang dari seluruh toko di platform.')

@php
    $statusBadgeMap = [
        'aktif' => ['label' => 'Aktif', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'nonaktif' => ['label' => 'Nonaktif', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
    ];
@endphp

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Monitor Gudang Platform</h2>
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
                <button type="button" data-chip="aktif" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Aktif ({{ $stats['aktif'] }})</button>
                <button type="button" data-chip="nonaktif" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Nonaktif ({{ $stats['nonaktif'] }})</button>
            </div>
        </div>

        <!-- Search + Result Count -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input id="gudang-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama gudang, toko, atau alamat..." />
                <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                <span id="result-count">{{ $warehouses->count() }}</span> gudang
            </p>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto hidden md:block">
        <table class="w-full min-w-[950px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-center w-12">No.</th>
                    <th class="p-4 text-left">Nama Gudang</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Alamat</th>
                    <th class="p-4 text-center">Total Item</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($warehouses as $wh)
                    @php $badge = $statusBadgeMap[$wh->status] ?? ['label' => $wh->status, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant']; @endphp
                    <tr data-table-row data-status="{{ $wh->status }}" data-search="{{ strtolower($wh->nama_gudang.' '.($wh->store->nama_toko ?? '').' '.($wh->alamat ?? '')) }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono row-num"></td>
                        <td class="p-4 text-on-surface font-bold">{{ $wh->nama_gudang }}</td>
                        <td class="p-4 text-on-surface">{{ $wh->store->nama_toko ?? '-' }}</td>
                        <td class="p-4 text-on-surface-variant text-xs">{{ Str::limit($wh->alamat, 40) }}</td>
                        <td class="p-4 text-center text-on-surface">{{ $wh->stocks_count }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border">{{ $badge['label'] }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <button type="button" data-gudang-detail="{{ $wh->warehouse_id }}" data-modal-open="detail-gudang" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-deep-onyx text-on-primary rounded-full text-[11px] font-bold uppercase tracking-widest border border-gold-accent/30 btn-premium focus:ring-2 focus:ring-gold-accent/20 focus:outline-none active:scale-[0.98]">
                                <span class="material-symbols-outlined text-sm">visibility</span> Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-on-surface-variant">Belum ada data gudang.</td>
                    </tr>
                @endforelse
                <tr id="empty-search" class="hidden">
                    <td colspan="7" class="p-8 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada data gudang yang cocok.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile: kartu per gudang -->
    <div class="md:hidden grid grid-cols-1 gap-gutter">
        @forelse($warehouses as $wh)
            @php $badge = $statusBadgeMap[$wh->status] ?? ['label' => $wh->status, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant']; @endphp
            <article data-table-row data-status="{{ $wh->status }}" data-search="{{ strtolower($wh->nama_gudang.' '.($wh->store->nama_toko ?? '').' '.($wh->alamat ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="min-w-0">
                        <p class="font-title-md text-title-md text-on-surface leading-tight">{{ $wh->nama_gudang }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">{{ $wh->store->nama_toko ?? '-' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border shrink-0">{{ $badge['label'] }}</span>
                </div>
                <dl class="space-y-2 font-body-md text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Alamat</dt>
                        <dd class="text-on-surface text-right">{{ Str::limit($wh->alamat, 40) }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Total Item</dt>
                        <dd class="text-on-surface text-right">{{ $wh->stocks_count }}</dd>
                    </div>
                </dl>
                <button type="button" data-gudang-detail="{{ $wh->warehouse_id }}" data-modal-open="detail-gudang" class="mt-4 w-full min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary rounded-lg font-label-sm text-[11px] uppercase tracking-widest border border-gold-accent/30 btn-premium focus:ring-2 focus:ring-gold-accent/20 focus:outline-none active:scale-[0.98]">
                    <span class="material-symbols-outlined text-sm">visibility</span> Detail
                </button>
            </article>
        @empty
            <p class="text-center text-on-surface-variant py-10">Belum ada data gudang.</p>
        @endforelse
        <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada data gudang yang cocok.</p>
    </div>
</section>

<!-- Modal Detail Gudang -->
<div id="detail-gudang" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-2xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-2xl flex flex-col max-h-[90vh] border-t-4 border-t-gold-accent/60">
        <!-- Header -->
        <div class="flex items-start justify-between gap-4 px-6 pt-5 pb-4 border-b border-muted-border rounded-t-xl">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-gold-accent">warehouse</span>
                </div>
                <div class="min-w-0">
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading uppercase">Detail Gudang</h3>
                    <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1 truncate"><span data-slot="nama">-</span></p>
                </div>
            </div>
            <button type="button" data-modal-close class="p-1 -mr-1 rounded-full text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors shrink-0">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Scrollable body -->
        <div class="overflow-y-auto px-6 py-5 space-y-6">
            <!-- Info Gudang -->
            <section>
                <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-3"><span class="material-symbols-outlined text-[16px]">warehouse</span> Info Gudang</p>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 md:p-5">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 font-body-md text-sm">
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">storefront</span> Toko</dt>
                            <dd class="text-on-surface break-words"><span data-slot="toko">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">verified</span> Status</dt>
                            <dd><span data-slot="status-badge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border bg-surface-container-high text-on-surface-variant border-outline-variant"></span></dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">location_on</span> Alamat</dt>
                            <dd class="text-on-surface break-words"><span data-slot="alamat">-</span></dd>
                        </div>
                        <div>
                            <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">call</span> Telepon</dt>
                            <dd class="text-on-surface break-words"><span data-slot="telepon">-</span></dd>
                        </div>
                    </dl>
                </div>
            </section>

            <!-- Ringkasan -->
            <section>
                <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-3"><span class="material-symbols-outlined text-[16px]">analytics</span> Ringkasan</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
                    <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4">
                        <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/15 fill pointer-events-none">inventory_2</span>
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Total Item</p>
                        <p class="font-headline-lg text-headline-lg-mobile text-gold-accent leading-tight mt-1"><span data-slot="totalitem">-</span></p>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col justify-between">
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Dibuat Pada</p>
                        <p class="text-on-surface font-bold text-sm mt-1"><span data-slot="created">-</span></p>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col justify-between">
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Diperbarui Pada</p>
                        <p class="text-on-surface font-bold text-sm mt-1"><span data-slot="updated">-</span></p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Staff Gudang</p>
                    <div class="text-on-surface flex flex-wrap gap-1.5" data-staffs-wrap></div>
                </div>
            </section>

            <!-- Daftar Stok -->
            <section>
                <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-3"><span class="material-symbols-outlined text-[16px]">inventory_2</span> Daftar Stok <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[9px] font-bold" id="stok-count">-</span></p>
                <div class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium card-static">
                    <table class="w-full text-sm premium-table">
                        <thead class="bg-surface-container-high">
                            <tr class="border-b border-muted-border text-on-surface-variant font-label-sm text-label-sm uppercase">
                                <th class="p-3 text-left">Produk</th>
                                <th class="p-3 text-left">SKU / Varian</th>
                                <th class="p-3 text-center">Stok</th>
                                <th class="p-3 text-center">Reservasi</th>
                            </tr>
                        </thead>
                        <tbody class="font-body-md text-sm" data-stocks-tbody></tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <div class="shrink-0 border-t border-muted-border rounded-b-xl px-6 py-4 bg-surface-container-lowest flex flex-col sm:flex-row gap-3">
            <button type="button" id="gudang-copy" class="flex-1 inline-flex items-center justify-center gap-2 py-3 border border-muted-border rounded-lg text-on-surface font-label-sm text-[11px] uppercase tracking-widest hover:border-gold-accent hover:text-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[16px]">content_copy</span> Salin Alamat
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
        const searchInput = document.getElementById('gudang-search');
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

    const saStatusBadge = (status) => ({
        'aktif': ['Aktif', 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'nonaktif': ['Nonaktif', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
    }[status] ?? [status, 'bg-surface-container-high text-on-surface-variant border-outline-variant']);

    const saSetBadge = (el, label, cls) => {
        if (!el) return;
        el.textContent = '';
        const dot = document.createElement('span');
        dot.className = 'w-1.5 h-1.5 rounded-full bg-current opacity-80';
        el.appendChild(dot);
        el.appendChild(document.createTextNode(label));
        el.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border ' + cls;
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

    const saBuildCellNumMuted = (text) => {
        const td = document.createElement('td');
        td.className = 'p-3 text-center text-on-surface-variant text-xs font-mono';
        td.textContent = text;
        return td;
    };

    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-gudang-detail]');
        if (!btn) return;
        const modal = document.getElementById('detail-gudang');
        if (!modal) return;

        const id = btn.getAttribute('data-gudang-detail');
        modal.querySelectorAll('[data-slot]').forEach((el) => { el.textContent = '-'; });
        const stocksTbody = modal.querySelector('[data-stocks-tbody]');
        const staffsWrap = modal.querySelector('[data-staffs-wrap]');
        const countEl = document.getElementById('stok-count');
        if (countEl) countEl.textContent = '-';

        if (stocksTbody) {
            stocksTbody.innerHTML = '<tr><td colspan="4"><div class="space-y-2 p-3">' +
                '<div class="h-3 rounded bg-surface-container-high animate-pulse"></div>'.repeat(3) +
                '</div></td></tr>';
        }
        if (staffsWrap) staffsWrap.innerHTML = '<div class="space-y-1.5"><div class="h-3 w-40 rounded bg-surface-container-high animate-pulse"></div></div>';

        try {
            const res = await fetch(`/superadmin/gudang/${id}/detail`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();
            const w = data.warehouse || {};

            saFillText(modal, 'nama', w.nama ?? '-');
            saFillText(modal, 'toko', w.toko ?? '-');
            saFillText(modal, 'alamat', w.alamat ?? '-');
            saFillText(modal, 'telepon', w.telepon ?? '-');
            saFillText(modal, 'totalitem', w.total_item ?? '-');
            saFillText(modal, 'created', w.created ?? '-');
            saFillText(modal, 'updated', w.updated ?? '-');

            const badge = modal.querySelector('[data-slot="status-badge"]');
            if (badge) {
                const [label, cls] = saStatusBadge(w.status ?? '-');
                saSetBadge(badge, label, cls);
            }
            if (countEl) countEl.textContent = String(data.stocks?.length ?? 0);

            if (staffsWrap) {
                const staffs = data.staffs || [];
                staffsWrap.innerHTML = '';
                if (staffs.length) {
                    staffs.forEach((n) => {
                        const item = document.createElement('span');
                        item.className = 'inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface text-xs border border-outline-variant shadow-sm';
                        const av = document.createElement('span');
                        av.className = 'w-5 h-5 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold flex items-center justify-center shrink-0';
                        av.textContent = saInitial(n);
                        item.appendChild(av);
                        item.appendChild(document.createTextNode(n));
                        staffsWrap.appendChild(item);
                    });
                } else {
                    staffsWrap.innerHTML = '<span class="text-on-surface-variant text-xs">Tidak ada staff terdaftar.</span>';
                }
            }

            if (stocksTbody) {
                const stocks = data.stocks || [];
                stocksTbody.innerHTML = '';
                if (stocks.length) {
                    const frag = document.createDocumentFragment();
                    stocks.forEach((s) => {
                        const tr = document.createElement('tr');
                        tr.appendChild(saBuildCell(s.nama ?? '-'));
                        tr.appendChild(saBuildCellMuted([s.sku ?? '-', s.varian ? ' • ' + s.varian : ''].join('')));
                        tr.appendChild(saBuildCellNum(String(s.jumlah ?? 0)));
                        tr.appendChild(saBuildCellNumMuted(String(s.reservasi ?? 0)));
                        frag.appendChild(tr);
                    });
                    stocksTbody.appendChild(frag);
                } else {
                    stocksTbody.innerHTML = '<tr><td colspan="4" class="p-6 text-center"><div class="flex flex-col items-center gap-2"><span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">inventory_2</span><p class="text-on-surface-variant font-body-md text-sm">Belum ada stok tercatat.</p></div></td></tr>';
                }
            }
        } catch (err) {
            if (stocksTbody) stocksTbody.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-on-surface-variant">Gagal memuat detail.</td></tr>';
            if (staffsWrap) staffsWrap.innerHTML = '<span class="text-on-surface-variant text-xs">Gagal memuat.</span>';
        }
    const copyBtn = document.getElementById('gudang-copy');
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            const text = (document.querySelector('#detail-gudang [data-slot="alamat"]')?.textContent || '').trim();
            if (!text || text === '-') return;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => window.showRalivaToast?.('Alamat disalin', 'content_copy'));
            }
        });
    }
});
</script>
@endpush
