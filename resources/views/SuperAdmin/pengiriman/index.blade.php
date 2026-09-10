@extends('layouts.superadmin')

@section('title', 'Pengiriman')

@section('header-title', 'Pengiriman')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Pantau dan ubah status pengiriman dari seluruh toko di platform.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            {{ $stats['semua'] }} Total
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wider border border-outline-variant">
                            {{ $stats['pending'] }} Pending
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-info/10 text-info text-[10px] font-bold uppercase tracking-wider border border-info/20">
                            {{ $stats['diproses'] }} Diproses
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            {{ $stats['dikirim'] }} Dikirim
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase tracking-wider border border-success/20">
                            {{ $stats['diterima'] }} Diterima
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase tracking-wider border border-error/20">
                            {{ $stats['gagal'] }} Gagal
                        </span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg">Pantau dan kelola pengiriman dari seluruh toko. SA dapat mengubah status pengiriman untuk keperluan darurat.</p>
                </div>
            </div>
        </div>
    </section>

    <section data-table-scope class="rise rise-d1 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Pengiriman</h2>
            <button type="button" data-filter-toggle data-filter-target="#pengiriman-filter" class="md:hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Filter
                <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
            </button>
        </div>

        <!-- Filters -->
        <div id="pengiriman-filter" data-filter-panel class="hidden md:block mb-6">
            <div class="mb-4 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
                <div class="flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
                </div>
                <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
                <div id="chip-group" class="flex flex-wrap gap-2">
                    <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua Pengiriman</button>
                    <button type="button" data-chip="pending" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Pending</button>
                    <button type="button" data-chip="diproses" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Diproses</button>
                    <button type="button" data-chip="dikirim" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dikirim</button>
                    <button type="button" data-chip="diterima" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Diterima</button>
                    <button type="button" data-chip="gagal" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Gagal</button>
                </div>
            </div>

            <!-- Search + Result Count -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input id="searchInput" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nomor pesanan, toko, kurir, atau resi..." />
                    <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                    <span id="pengiriman-result-count">{{ $shipments->count() }}</span> pengiriman
                </p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto hidden md:block">
                <table class="w-full min-w-full bg-surface-container-lowest rounded-lg overflow-hidden premium-table">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                            <th class="p-6 w-12 text-center">No.</th>
                            <th class="p-6">ID Pesanan</th>
                            <th class="p-6">Toko</th>
                            <th class="p-6">Kurir</th>
                            <th class="p-6">No. Resi</th>
                            <th class="p-6">Ongkir</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @forelse($shipments as $s)
                            @php
                                $pelanggan = $s->order?->checkout?->user;
                            @endphp
                            <tr data-table-row
                                        data-status="{{ $s->status }}"
                                        data-search="{{ strtolower(($s->order->nomor_order ?? '').' '.($s->order->store->nama_toko ?? '').' '.($s->courier->nama_kurir ?? '').' '.($s->nomor_resi ?? '')) }}"
                                        class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                                <td class="p-6 text-center text-on-surface-variant font-mono row-num"></td>
                                <td class="p-6 font-mono">{{ $s->order->nomor_order ?? '-' }}</td>
                                <td class="p-6">{{ $s->order->store->nama_toko ?? '-' }}</td>
                                <td class="p-6">{{ $s->courier->nama_kurir ?? '-' }}</td>
                                <td class="p-6 font-mono text-on-surface-variant text-xs">{{ $s->nomor_resi ?? '-' }}</td>
                                <td class="p-6 text-right text-on-surface">Rp {{ number_format((float) $s->ongkir, 0, ',', '.') }}</td>
                                <td class="p-6">
                                    <form method="POST" action="{{ route('superadmin.pengiriman.status', $s->shipment_id) }}" class="inline-flex">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" data-prev="{{ $s->status }}" onchange="openConfirmPengiriman(this)" class="bg-transparent border border-muted-border rounded-lg px-2 py-1 text-[10px] font-bold uppercase focus:outline-none focus:border-gold-accent cursor-pointer {{ match($s->status) { 'diterima' => 'text-success border-success/30', 'dikirim' => 'text-secondary border-secondary/30', 'diproses' => 'text-info border-info/30', 'gagal' => 'text-error border-error/30', default => 'text-on-surface-variant border-outline-variant', } }}">
                                            <option value="pending" {{ $s->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="diproses" {{ $s->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="dikirim" {{ $s->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                            <option value="diterima" {{ $s->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                                            <option value="gagal" {{ $s->status === 'gagal' ? 'selected' : '' }}>Gagal</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="p-6 text-right">
                                    <button type="button" onclick="openDetail(this)" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors"
                                        data-order="{{ $s->order->nomor_order ?? '-' }}"
                                        data-toko="{{ $s->order->store->nama_toko ?? '-' }}"
                                        data-kurir="{{ $s->courier->nama_kurir ?? '-' }}"
                                        data-resi="{{ $s->nomor_resi ?? '-' }}"
                                        data-ongkir="Rp {{ number_format((float) $s->ongkir, 0, ',', '.') }}"
                                        data-estimasi="{{ $s->estimasi_tiba ? \Carbon\Carbon::parse($s->estimasi_tiba)->locale('id')->translatedFormat('d M Y') : '-' }}"
                                        data-dikirim="{{ $s->dikirim_pada ? \Carbon\Carbon::parse($s->dikirim_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}"
                                        data-diterima="{{ $s->diterima_pada ? \Carbon\Carbon::parse($s->diterima_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}"
                                        data-status="{{ ucfirst($s->status) }}"
                                        data-pelanggan="{{ $pelanggan->nama_lengkap ?? '-' }}">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">inbox</span>
                                        <p class="text-on-surface-variant font-body-md text-sm">Belum ada data pengiriman.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <tr id="empty-search" class="hidden">
                            <td colspan="8" class="p-8 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pengiriman yang cocok.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile: kartu per pengiriman -->
            <div id="card-grid" class="md:hidden grid grid-cols-1 gap-gutter">
                @forelse($shipments as $s)
                    @php
                        $pelanggan = $s->order?->checkout?->user;
                    @endphp
                    <article data-table-row data-status="{{ $s->status }}" data-search="{{ strtolower(($s->order->nomor_order ?? '').' '.($s->order->store->nama_toko ?? '').' '.($s->courier->nama_kurir ?? '').' '.($s->nomor_resi ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="min-w-0">
                                <p class="font-mono font-bold text-on-surface leading-tight">{{ $s->order->nomor_order ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $s->order->store->nama_toko ?? '-' }}</p>
                            </div>
                            <form method="POST" action="{{ route('superadmin.pengiriman.status', $s->shipment_id) }}" class="shrink-0">
                                @csrf
                                @method('PUT')
                                <select name="status" data-prev="{{ $s->status }}" onchange="openConfirmPengiriman(this)" class="bg-transparent border border-muted-border rounded-lg px-2 py-1 text-[10px] font-bold uppercase focus:outline-none focus:border-gold-accent cursor-pointer {{ match($s->status) { 'diterima' => 'text-success border-success/30', 'dikirim' => 'text-secondary border-secondary/30', 'diproses' => 'text-info border-info/30', 'gagal' => 'text-error border-error/30', default => 'text-on-surface-variant border-outline-variant', } }}">
                                    <option value="pending" {{ $s->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diproses" {{ $s->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="dikirim" {{ $s->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="diterima" {{ $s->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="gagal" {{ $s->status === 'gagal' ? 'selected' : '' }}>Gagal</option>
                                </select>
                            </form>
                        </div>

                        <dl class="space-y-2 font-body-md text-sm mb-4">
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Kurir</dt>
                                <dd class="text-on-surface text-right">{{ $s->courier->nama_kurir ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">No. Resi</dt>
                                <dd class="font-mono text-on-surface-variant text-right text-xs">{{ $s->nomor_resi ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Ongkir</dt>
                                <dd class="text-on-surface text-right">Rp {{ number_format((float) $s->ongkir, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Pelanggan</dt>
                                <dd class="text-on-surface text-right">{{ $pelanggan->nama_lengkap ?? '-' }}</dd>
                            </div>
                        </dl>

                        <button type="button" onclick="openDetail(this)" class="w-full min-h-11 inline-flex items-center justify-center gap-2 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors"
                            data-order="{{ $s->order->nomor_order ?? '-' }}"
                            data-toko="{{ $s->order->store->nama_toko ?? '-' }}"
                            data-kurir="{{ $s->courier->nama_kurir ?? '-' }}"
                            data-resi="{{ $s->nomor_resi ?? '-' }}"
                            data-ongkir="Rp {{ number_format((float) $s->ongkir, 0, ',', '.') }}"
                            data-estimasi="{{ $s->estimasi_tiba ? \Carbon\Carbon::parse($s->estimasi_tiba)->locale('id')->translatedFormat('d M Y') : '-' }}"
                            data-dikirim="{{ $s->dikirim_pada ? \Carbon\Carbon::parse($s->dikirim_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}"
                            data-diterima="{{ $s->diterima_pada ? \Carbon\Carbon::parse($s->diterima_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}"
                            data-status="{{ ucfirst($s->status) }}"
                            data-pelanggan="{{ $pelanggan->nama_lengkap ?? '-' }}">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>Detail
                        </button>
                    </article>
                @empty
                    <p class="text-center text-on-surface-variant py-10">Belum ada data pengiriman.</p>
                @endforelse
                <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada pengiriman yang cocok.</p>
            </div>

            <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            SA dapat mengubah status pengiriman untuk keperluan darurat. Perubahan status tercatat di riwayat aktivitas.
        </p>
    </section>
</div>

<!-- Detail Modal -->
<div id="detail-modal" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Detail Pengiriman</p>
                <h3 id="d-order" class="font-title-md text-title-md text-on-surface premium-heading mt-1">-</h3>
            </div>
            <button type="button" onclick="closeModal()" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Toko</label>
                    <p id="d-toko" class="text-sm font-semibold text-on-surface">-</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Pelanggan</label>
                    <p id="d-pelanggan" class="text-sm font-semibold text-on-surface">-</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Kurir</label>
                    <p id="d-kurir" class="text-sm font-semibold text-on-surface">-</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">No. Resi</label>
                    <p id="d-resi" class="text-sm font-semibold text-on-surface font-mono">-</p>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-on-surface-variant mb-1">Ongkir</label>
                <p id="d-ongkir" class="text-sm font-bold text-gold-accent">-</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Estimasi Tiba</label>
                    <p id="d-estimasi" class="text-sm font-semibold text-on-surface">-</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Status</label>
                    <p id="d-status" class="text-sm font-semibold">-</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Dikirim Pada</label>
                    <p id="d-dikirim" class="text-sm font-semibold text-on-surface">-</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Diterima Pada</label>
                    <p id="d-diterima" class="text-sm font-semibold text-on-surface">-</p>
                </div>
            </div>
        </div>
        <div class="px-6 pb-6">
            <button type="button" onclick="closeModal()" class="w-full py-3 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Ubah Status Pengiriman -->
<div id="confirmPengirimanModal" class="fixed inset-0 z-[75] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" onclick="if (event.target === this) closeConfirmPengiriman()">
    <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
        <div class="p-8">
            <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-gold-accent text-[28px]">local_shipping</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Ubah status pengiriman?</h3>
            <p class="text-on-surface-variant text-sm text-center mb-4">Status akan diubah menjadi <span id="confirm-pengiriman-status" class="font-bold text-on-surface">-</span>.</p>
            <div class="flex space-x-3">
                <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeConfirmPengiriman()">Batal</button>
                <button type="button" id="confirm-pengiriman-submit" class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">Ya, Ubah</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // === FILTER & SEARCH ===
    const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
    const idleClasses = ['border-muted-border', 'text-on-surface-variant'];
    let activeStatus = 'semua';

    function applyFilter() {
        const term = document.getElementById('searchInput').value.trim().toLowerCase();
        let visible = 0;

        const each = (el) => {
            const matchStatus = activeStatus === 'semua' || el.getAttribute('data-status') === activeStatus;
            const matchSearch = !term || (el.getAttribute('data-search') || '').includes(term);
            const show = matchStatus && matchSearch;
            el.classList.toggle('hidden', !show);
            if (show) visible++;
            return show;
        };

        document.querySelectorAll('#table-body tr[data-table-row]').forEach((row) => {
            if (each(row)) {
                const num = row.querySelector('.row-num');
                if (num) num.textContent = visible;
            }
        });
        document.querySelectorAll('#card-grid article[data-table-row]').forEach(each);

        document.getElementById('pengiriman-result-count').textContent = visible;
        document.getElementById('empty-search').classList.toggle('hidden', visible > 0);
        const em = document.getElementById('empty-search-mobile');
        if (em) em.classList.toggle('hidden', visible > 0);

        if (document.querySelectorAll('#table-body tr[data-table-row]').length === 0) {
            document.getElementById('empty-search').classList.add('hidden');
        }
    }

    function resetFilter() {
        document.getElementById('searchInput').value = '';
        const clearBtn = document.getElementById('clear-search');
        if (clearBtn) clearBtn.classList.add('opacity-0');
        activateChip(document.querySelector('#chip-group [data-chip="semua"]'));
        applyFilter();
    }

    function activateChip(chip) {
        if (!chip) return;
        document.querySelectorAll('#chip-group .chip-btn').forEach((c) => {
            c.classList.remove(...activeClasses);
            c.classList.add(...idleClasses, 'hover:bg-surface-container-high');
        });
        chip.classList.remove(...idleClasses, 'hover:bg-surface-container-high');
        chip.classList.add(...activeClasses);
        activeStatus = chip.getAttribute('data-chip');
    }

    document.querySelectorAll('#chip-group .chip-btn').forEach(function (chip) {
        chip.addEventListener('click', function () {
            activateChip(chip);
            applyFilter();
        });
    });

    if (document.getElementById('clear-search')) {
        document.getElementById('clear-search').addEventListener('click', function () {
            document.getElementById('searchInput').value = '';
            this.classList.add('opacity-0');
            applyFilter();
        });
    }

    let debounce;
    document.getElementById('searchInput').addEventListener('input', function () {
        const clearBtn = document.getElementById('clear-search');
        if (clearBtn) clearBtn.classList.toggle('opacity-0', !this.value);
        clearTimeout(debounce);
        debounce = setTimeout(applyFilter, 200);
    });

    // === DETAIL MODAL ===
    function openDetail(btn) {
        const d = btn.dataset;
        document.getElementById('d-order').textContent = d.order;
        document.getElementById('d-toko').textContent = d.toko;
        document.getElementById('d-pelanggan').textContent = d.pelanggan;
        document.getElementById('d-kurir').textContent = d.kurir;
        document.getElementById('d-resi').textContent = d.resi;
        document.getElementById('d-ongkir').textContent = d.ongkir;
        document.getElementById('d-estimasi').textContent = d.estimasi;
        document.getElementById('d-dikirim').textContent = d.dikirim;
        document.getElementById('d-diterima').textContent = d.diterima;
        const statusEl = document.getElementById('d-status');
        statusEl.textContent = d.status;
        const statusColors = { 'Diterima': 'text-success', 'Dikirim': 'text-secondary', 'Diproses': 'text-info', 'Gagal': 'text-error', 'Pending': 'text-on-surface-variant' };
        statusEl.className = 'text-sm font-semibold ' + (statusColors[d.status] || 'text-on-surface');
        document.getElementById('detail-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('detail-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    let _pendingPengirimanSelect = null;
    function openConfirmPengiriman(sel) {
        _pendingPengirimanSelect = sel;
        const label = sel.options[sel.selectedIndex]?.text?.trim() || sel.value;
        document.getElementById('confirm-pengiriman-status').textContent = label;
        const m = document.getElementById('confirmPengirimanModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeConfirmPengiriman() {
        const m = document.getElementById('confirmPengirimanModal');
        if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
        if (_pendingPengirimanSelect) {
            _pendingPengirimanSelect.value = _pendingPengirimanSelect.dataset.prev;
            _pendingPengirimanSelect = null;
        }
    }
    document.getElementById('confirm-pengiriman-submit')?.addEventListener('click', () => {
        if (_pendingPengirimanSelect) {
            const f = _pendingPengirimanSelect.closest('form');
            _pendingPengirimanSelect = null;
            if (f) f.submit();
        }
        const m = document.getElementById('confirmPengirimanModal');
        if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeModal(); closeConfirmPengiriman(); }
    });
</script>
@endpush
