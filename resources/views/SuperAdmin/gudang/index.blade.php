@extends('layouts.superadmin')

@section('title', 'Gudang')

@section('header-title', 'Gudang')
@section('header-badge', 'Pantau')
@section('header-subtitle', 'Pantau data gudang dari seluruh toko di platform.')

@php
    $statusBadgeMap = [
        'aktif' => ['label' => 'Aktif', 'class' => \App\Support\StatusStyle::badgeClass('aktif')],
        'nonaktif' => ['label' => 'Nonaktif', 'class' => \App\Support\StatusStyle::badgeClass('nonaktif')],
    ];
@endphp

@section('content')
<div data-reveal class="flex flex-wrap items-center gap-3 -mt-2 mb-2">
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent">
        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
    </span>
    <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
        Data gudang diperbarui real-time
    </span>
</div>
<section data-table-scope data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
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
                @php
                    $chipStatuses = ['semua', 'aktif', 'nonaktif'];
                @endphp
                @foreach ($chipStatuses as $chipStatus)
                    @php $isActive = $activeStatus === $chipStatus; @endphp
                    <a href="{{ route('superadmin.gudang', ['status' => $chipStatus, 'q' => $q]) }}" data-chip="{{ $chipStatus }}" class="chip-btn px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $isActive ? 'bg-deep-onyx border border-deep-onyx text-on-primary' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">
                        {{ ucfirst($chipStatus) }} ({{ $stats[$chipStatus] }})
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Search + Result Count -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <form method="GET" action="{{ route('superadmin.gudang') }}" class="relative flex-1">
                <input type="hidden" name="status" value="{{ $activeStatus }}">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] pointer-events-none">search</span>
                <input name="q" value="{{ $q }}" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama gudang, toko, atau alamat..." />
                <a href="{{ route('superadmin.gudang', ['status' => $activeStatus]) }}" aria-label="Hapus pencarian" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent transition-opacity {{ $q ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </a>
            </form>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent shrink-0 whitespace-nowrap">
                <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                <span id="result-count">{{ $warehouses->total() }}</span> gudang
            </span>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto hidden md:block">
        <table class="w-full min-w-[950px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="px-4 py-4 text-center w-12 text-[10px] font-semibold tracking-widest">No.</th>
                    <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Nama Gudang</th>
                    <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Toko</th>
                    <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Alamat</th>
                    <th class="px-4 py-4 text-center text-[10px] font-semibold tracking-widest">Total Item</th>
                    <th class="px-4 py-4 text-center text-[10px] font-semibold tracking-widest">Status</th>
                    <th class="px-4 py-4 text-center text-[10px] font-semibold tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($warehouses as $wh)
                    @php $badge = $statusBadgeMap[$wh->status] ?? ['label' => $wh->status, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant']; @endphp
                    <tr data-table-row data-status="{{ $wh->status }}" data-search="{{ strtolower($wh->nama_gudang.' '.($wh->store->nama_toko ?? '').' '.($wh->alamat ?? '')) }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono">{{ ($warehouses->firstItem() ?? 0) + $loop->index }}</td>
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
                        <td colspan="7" class="p-8 text-center">
                            @if ($activeStatus !== 'semua' || $q)
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada data gudang yang cocok.</p>
                            </div>
                            @else
                            <p class="text-on-surface-variant">Belum ada data gudang.</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile: kartu per gudang -->
    <div class="md:hidden grid grid-cols-1 gap-gutter">
        @forelse($warehouses as $wh)
            @php $badge = $statusBadgeMap[$wh->status] ?? ['label' => $wh->status, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant']; @endphp
            <article data-table-row data-status="{{ $wh->status }}" data-search="{{ strtolower($wh->nama_gudang.' '.($wh->store->nama_toko ?? '').' '.($wh->alamat ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">warehouse</span>
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
            <p class="text-center text-on-surface-variant py-10">
                @if ($activeStatus !== 'semua' || $q)
                Tidak ada data gudang yang cocok.
                @else
                Belum ada data gudang.
                @endif
            </p>
        @endforelse
    </div>

    @if ($warehouses->hasPages())
        <div class="mt-6 flex justify-center">{{ $warehouses->links() }}</div>
    @endif
</section>

<!-- Modal Detail Gudang -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'detail-gudang',
    'dataModal' => true,
    'icon' => 'warehouse',
    'title' => 'Detail Gudang',
    'subtitle' => '<span data-slot="nama">-</span>',
    'subtitleRaw' => true,
    'size' => 'xl',
])
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

    @slot('footer')
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="button" id="gudang-copy" class="btn-modal btn-modal-ghost flex-1">
                <span class="material-symbols-outlined text-[16px]">content_copy</span> Salin Alamat
            </button>
            <button type="button" data-modal-close class="btn-modal btn-modal-primary flex-1">
                <span class="material-symbols-outlined text-[16px]">close</span> Tutup
            </button>
        </div>
    @endslot
@endcomponent
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const saStatusBadge = (status) => ({
        'aktif': ['Aktif', 'bg-success/10 text-success border-success/20'],
        'nonaktif': ['Nonaktif', 'bg-error/10 text-error border-error/20'],
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
