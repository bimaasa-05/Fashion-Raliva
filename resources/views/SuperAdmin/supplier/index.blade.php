@extends('layouts.superadmin')

@section('title', 'Data Supplier')

@section('header-title', 'Data Supplier')
@section('header-badge', 'Pantau')
@section('header-subtitle', 'Pantau data supplier pemasok bahan baku di platform.')

@php
    $statusBadgeMap = [
        'aktif' => ['label' => 'Aktif', 'class' => \App\Support\StatusStyle::badgeClass('aktif')],
        'verifikasi' => ['label' => 'Verifikasi', 'class' => \App\Support\StatusStyle::badgeClass('verifikasi')],
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
        Data supplier diperbarui real-time
    </span>
</div>
<section data-table-scope data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Data Supplier Platform</h2>
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
                    $chipStatuses = ['semua', 'aktif', 'verifikasi', 'nonaktif'];
                @endphp
                @foreach ($chipStatuses as $chipStatus)
                    @php $isActive = $activeStatus === $chipStatus; @endphp
                    <a href="{{ route('superadmin.supplier', ['status' => $chipStatus, 'q' => $q]) }}" data-chip="{{ $chipStatus }}" class="chip-btn px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $isActive ? 'bg-deep-onyx border border-deep-onyx text-on-primary' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">
                        {{ ucfirst($chipStatus) }} ({{ $stats[$chipStatus] }})
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Search + Result Count -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <form method="GET" action="{{ route('superadmin.supplier') }}" class="relative flex-1">
                <input type="hidden" name="status" value="{{ $activeStatus }}">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] pointer-events-none">search</span>
                <input name="q" value="{{ $q }}" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama supplier, kontak, kota, jenis, atau bahan..." />
                <a href="{{ route('superadmin.supplier', ['status' => $activeStatus]) }}" aria-label="Hapus pencarian" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent transition-opacity {{ $q ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </a>
            </form>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent shrink-0 whitespace-nowrap">
                <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                <span id="result-count">{{ $suppliers->total() }}</span> supplier
            </span>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto hidden md:block">
        <table class="w-full min-w-[1080px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="px-4 py-4 text-center w-12 text-[10px] font-semibold tracking-widest">No.</th>
                    <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Supplier</th>
                    <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Kontak</th>
                    <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Kota</th>
                    <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Bahan</th>
                    <th class="px-4 py-4 text-center text-[10px] font-semibold tracking-widest">Status</th>
                    <th class="px-4 py-4 text-center text-[10px] font-semibold tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($suppliers as $supplier)
                    @php
                        $badge = $statusBadgeMap[$supplier->status] ?? ['label' => $supplier->status, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
                        $bahanJson = json_encode($supplier->bahans->map(fn ($b) => ['nama' => $b->nama_bahan, 'satuan' => $b->satuan ?? null])->values()->all());
                    @endphp
                    <tr data-table-row class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono">{{ ($suppliers->firstItem() ?? 0) + $loop->index }}</td>
                        <td class="p-4">
                            <p class="text-on-surface">{{ $supplier->nama_supplier }}</p>
                            <p class="text-on-surface-variant text-xs capitalize">{{ $supplier->jenis ?? '-' }}</p>
                        </td>
                        <td class="p-4">
                            <p class="text-on-surface">{{ $supplier->kontak ?? '-' }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $supplier->email ?? '-' }}</p>
                        </td>
                        <td class="p-4 text-on-surface">{{ $supplier->kota ?? '-' }}</td>
                        <td class="p-4">
                            @if ($supplier->bahans->count() > 0)
                                <div class="flex flex-wrap gap-1.5 max-w-[280px]">
                                    @foreach ($supplier->bahans as $bahan)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[10px] font-semibold" title="{{ $bahan->nama_bahan }}{{ $bahan->satuan ? ' • '.$bahan->satuan : '' }}">
                                            {{ $bahan->nama_bahan }}{{ $bahan->satuan ? ' • '.$bahan->satuan : '' }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-on-surface-variant text-xs">-</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border">{{ $badge['label'] }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <button type="button" data-supplier-detail="{{ $supplier->supplier_id }}"
                                data-nama="{{ $supplier->nama_supplier }}"
                                data-jenis="{{ $supplier->jenis ?? '' }}"
                                data-kota="{{ $supplier->kota ?? '' }}"
                                data-kontak="{{ $supplier->kontak ?? '' }}"
                                data-email="{{ $supplier->email ?? '' }}"
                                data-alamat="{{ $supplier->alamat ?? '' }}"
                                data-catatan="{{ $supplier->catatan ?? '' }}"
                                data-status="{{ $supplier->status }}"
                                data-bahan-json="{{ $bahanJson }}"
                                data-modal-open="detail-supplier" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-deep-onyx text-on-primary rounded-full text-[11px] font-bold uppercase tracking-widest border border-gold-accent/30 btn-premium focus:ring-2 focus:ring-gold-accent/20 focus:outline-none active:scale-[0.98]">
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
                                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada supplier yang cocok.</p>
                            </div>
                            @else
                            <p class="text-on-surface-variant">Belum ada data supplier.</p>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile: kartu per supplier -->
    <div class="md:hidden grid grid-cols-1 gap-gutter">
        @forelse($suppliers as $supplier)
            @php
                $badge = $statusBadgeMap[$supplier->status] ?? ['label' => $supplier->status, 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
                $bahanJson = json_encode($supplier->bahans->map(fn ($b) => ['nama' => $b->nama_bahan, 'satuan' => $b->satuan ?? null])->values()->all());
            @endphp
            <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">partner_exchange</span>
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="min-w-0">
                        <p class="font-title-md text-title-md text-on-surface leading-tight">{{ $supplier->nama_supplier }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5 capitalize">{{ $supplier->jenis ?? '-' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $badge['class'] }} text-[10px] font-bold uppercase border shrink-0">{{ $badge['label'] }}</span>
                </div>
                <dl class="space-y-2 font-body-md text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Kontak</dt>
                        <dd class="text-on-surface text-right">{{ $supplier->kontak ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Email</dt>
                        <dd class="text-on-surface text-right break-all">{{ $supplier->email ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Kota</dt>
                        <dd class="text-on-surface text-right">{{ $supplier->kota ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3 items-start">
                        <dt class="text-on-surface-variant shrink-0">Bahan</dt>
                        <dd class="text-right">
                            @if ($supplier->bahans->count() > 0)
                            <div class="flex flex-wrap gap-1.5 justify-end">
                                @foreach ($supplier->bahans as $bahan)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[10px] font-semibold">{{ $bahan->nama_bahan }}{{ $bahan->satuan ? ' • '.$bahan->satuan : '' }}</span>
                                @endforeach
                            </div>
                            @else
                            <span class="text-on-surface-variant text-xs">-</span>
                            @endif
                        </dd>
                    </div>
                </dl>
                <button type="button" data-supplier-detail="{{ $supplier->supplier_id }}"
                    data-nama="{{ $supplier->nama_supplier }}"
                    data-jenis="{{ $supplier->jenis ?? '' }}"
                    data-kota="{{ $supplier->kota ?? '' }}"
                    data-kontak="{{ $supplier->kontak ?? '' }}"
                    data-email="{{ $supplier->email ?? '' }}"
                    data-alamat="{{ $supplier->alamat ?? '' }}"
                    data-catatan="{{ $supplier->catatan ?? '' }}"
                    data-status="{{ $supplier->status }}"
                    data-bahan-json="{{ $bahanJson }}"
                    data-modal-open="detail-supplier" class="mt-4 w-full min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary rounded-lg font-label-sm text-[11px] uppercase tracking-widest border border-gold-accent/30 btn-premium focus:ring-2 focus:ring-gold-accent/20 focus:outline-none active:scale-[0.98]">
                    <span class="material-symbols-outlined text-sm">visibility</span> Detail
                </button>
            </article>
        @empty
            <p class="text-center text-on-surface-variant py-10">
                @if ($activeStatus !== 'semua' || $q)
                Tidak ada supplier yang cocok.
                @else
                Belum ada data supplier.
                @endif
            </p>
        @endforelse
    </div>

    @if ($suppliers->hasPages())
        <div class="mt-6 flex justify-center">{{ $suppliers->links() }}</div>
    @endif
</section>

<!-- Modal Detail Supplier -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'detail-supplier',
    'dataModal' => true,
    'icon' => 'local_shipping',
    'title' => 'Detail Supplier',
    'subtitle' => '<span data-slot="nama">-</span>',
    'subtitleRaw' => true,
    'size' => 'xl',
])
    <section>
        <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-3"><span class="material-symbols-outlined text-[16px]">local_shipping</span> Info Supplier</p>
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 md:p-5">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 font-body-md text-sm">
                <div>
                    <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">category</span> Jenis</dt>
                    <dd class="text-on-surface capitalize"><span data-slot="jenis">-</span></dd>
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
                    <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">person</span> Kontak</dt>
                    <dd class="text-on-surface"><span data-slot="kontak">-</span></dd>
                </div>
                <div>
                    <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">mail</span> Email</dt>
                    <dd class="text-on-surface break-words"><span data-slot="email">-</span></dd>
                </div>
                <div>
                    <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">location_city</span> Kota</dt>
                    <dd class="text-on-surface"><span data-slot="kota">-</span></dd>
                </div>
                <div>
                    <dt class="flex items-center gap-1.5 text-on-surface-variant text-[10px] uppercase tracking-widest mb-1.5"><span class="material-symbols-outlined text-gold-accent text-[16px]">notes</span> Catatan</dt>
                    <dd class="text-on-surface"><span data-slot="catatan">-</span></dd>
                </div>
            </dl>
        </div>
    </section>

    <section>
        <p class="flex items-center gap-1.5 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent mb-3"><span class="material-symbols-outlined text-[16px]">inventory_2</span> Bahan Baku <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[9px] font-bold" id="bahan-count">-</span></p>
        <div class="flex flex-wrap gap-2" data-bahan-wrap>
            <span class="text-on-surface-variant text-xs">-</span>
        </div>
    </section>

    @slot('footer')
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="button" id="supplier-copy" class="btn-modal btn-modal-ghost flex-1">
                <span class="material-symbols-outlined text-[16px]">content_copy</span> Salin Kontak
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
            'verifikasi': ['Verifikasi', 'bg-gold-accent/10 text-gold-accent border-gold-accent/30'],
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

        const saFillText = (modal, key, value) => {
            modal.querySelectorAll('[data-slot="' + key + '"]').forEach((el) => { el.textContent = value; });
        };

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-supplier-detail]');
            if (!btn) return;
            const modal = document.getElementById('detail-supplier');
            if (!modal) return;

            saFillText(modal, 'nama', btn.dataset.nama || '-');
            saFillText(modal, 'jenis', btn.dataset.jenis || '-');
            saFillText(modal, 'alamat', btn.dataset.alamat || '-');
            saFillText(modal, 'kontak', btn.dataset.kontak || '-');
            saFillText(modal, 'email', btn.dataset.email || '-');
            saFillText(modal, 'kota', btn.dataset.kota || '-');
            saFillText(modal, 'catatan', btn.dataset.catatan || '-');

            const badge = modal.querySelector('[data-slot="status-badge"]');
            if (badge) {
                const [label, cls] = saStatusBadge(btn.dataset.status || 'nonaktif');
                saSetBadge(badge, label, cls);
            }

            const wrap = modal.querySelector('[data-bahan-wrap]');
            const countEl = document.getElementById('bahan-count');
            if (wrap) {
                wrap.innerHTML = '';
                let bahan = [];
                try { bahan = JSON.parse(btn.dataset.bahanJson || '[]'); } catch (err) { bahan = []; }
                if (bahan.length) {
                    bahan.forEach((b) => {
                        const chip = document.createElement('span');
                        chip.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-high text-on-surface text-xs border border-outline-variant shadow-sm';
                        const ic = document.createElement('span');
                        ic.className = 'material-symbols-outlined text-gold-accent text-[14px]';
                        ic.textContent = 'inventory_2';
                        chip.appendChild(ic);
                        chip.appendChild(document.createTextNode(b.nama + (b.satuan ? ' • ' + b.satuan : '')));
                        wrap.appendChild(chip);
                    });
                } else {
                    wrap.innerHTML = '<span class="text-on-surface-variant text-xs">Belum ada bahan tercatat.</span>';
                }
                if (countEl) countEl.textContent = String(bahan.length);
            }

            modal.dataset.supplierNama = btn.dataset.nama || '';
            modal.dataset.supplierKontak = btn.dataset.kontak || '';
            modal.dataset.supplierEmail = btn.dataset.email || '';
        });

        const copyBtn = document.getElementById('supplier-copy');
        if (copyBtn) {
            copyBtn.addEventListener('click', () => {
                const modal = document.getElementById('detail-supplier');
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
    });
</script>
@endpush