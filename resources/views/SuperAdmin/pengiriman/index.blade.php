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
    <div data-reveal class="flex flex-wrap items-center gap-3 -mt-2 mb-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent">
            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
        </span>
        <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
            Pengiriman diperbarui real-time
        </span>
    </div>
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

    <section data-table-scope data-reveal class="rise rise-d1 bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
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
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent shrink-0 whitespace-nowrap">
                    <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                    <span id="pengiriman-result-count">{{ $shipments->total() }}</span> pengiriman
                </span>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto hidden md:block">
                <table class="w-full min-w-full bg-surface-container-lowest rounded-xl overflow-hidden premium-table">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                            <th class="px-6 py-4 w-12 text-center text-[10px] font-semibold tracking-widest">No.</th>
                            <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">ID Pesanan</th>
                            <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Toko</th>
                            <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Kurir</th>
                            <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">No. Resi</th>
                            <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Ongkir</th>
                            <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Status</th>
                            <th class="px-6 py-4 text-right text-[10px] font-semibold tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @forelse($shipments as $s)
                            @php
$pelanggan = $s->order?->checkout?->user;
                                $pelNama = $pelanggan?->nama_lengkap ?? $s->order?->checkout?->nama_penerima ?? '-';
                                $statusClass = match($s->status) { 'diterima' => 'text-success border-success/30', 'dikirim' => 'text-secondary border-secondary/30', 'diproses' => 'text-info border-info/30', 'gagal' => 'text-error border-error/30', default => 'text-on-surface-variant border-outline-variant' };
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
                                        <div class="relative" id="kirim-{{ $s->shipment_id }}-dd">
                                            <button type="button" data-dd-trigger id="kirim-{{ $s->shipment_id }}-trigger" onclick="toggleDropdown('kirim-{{ $s->shipment_id }}')" aria-haspopup="listbox" aria-expanded="false"
                                                class="flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg px-2 py-1 text-[10px] font-bold uppercase focus:outline-none focus:border-gold-accent cursor-pointer text-left min-w-[120px] {{ $statusClass }}">
                                                <span id="kirim-{{ $s->shipment_id }}-label" class="truncate">{{ ucfirst($s->status) }}</span>
                                                <span class="material-symbols-outlined text-[14px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="kirim-{{ $s->shipment_id }}-chevron">expand_more</span>
                                            </button>
                                            <div id="kirim-{{ $s->shipment_id }}-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                                                class="hidden absolute left-0 top-full mt-1 w-full min-w-[140px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden py-1">
                                                <button type="button" role="option" aria-selected="{{ $s->status === 'pending' ? 'true' : 'false' }}" data-dd-option="pending" onclick="openConfirmPengiriman('kirim', {{ $s->shipment_id }}, 'pending')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                                    Pending<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'pending' ? '' : 'hidden' }}">check</span>
                                                </button>
                                                <button type="button" role="option" aria-selected="{{ $s->status === 'diproses' ? 'true' : 'false' }}" data-dd-option="diproses" onclick="openConfirmPengiriman('kirim', {{ $s->shipment_id }}, 'diproses')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                                    Diproses<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'diproses' ? '' : 'hidden' }}">check</span>
                                                </button>
                                                <button type="button" role="option" aria-selected="{{ $s->status === 'dikirim' ? 'true' : 'false' }}" data-dd-option="dikirim" onclick="openConfirmPengiriman('kirim', {{ $s->shipment_id }}, 'dikirim')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                                    Dikirim<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'dikirim' ? '' : 'hidden' }}">check</span>
                                                </button>
                                                <button type="button" role="option" aria-selected="{{ $s->status === 'diterima' ? 'true' : 'false' }}" data-dd-option="diterima" onclick="openConfirmPengiriman('kirim', {{ $s->shipment_id }}, 'diterima')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                                    Diterima<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'diterima' ? '' : 'hidden' }}">check</span>
                                                </button>
                                                <button type="button" role="option" aria-selected="{{ $s->status === 'gagal' ? 'true' : 'false' }}" data-dd-option="gagal" onclick="openConfirmPengiriman('kirim', {{ $s->shipment_id }}, 'gagal')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                                    Gagal<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'gagal' ? '' : 'hidden' }}">check</span>
                                                </button>
                                            </div>
                                            <input type="hidden" name="status" value="{{ $s->status }}" />
                                        </div>
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
                                        data-pelanggan="{{ $pelNama }}">
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
                        $pelNama = $pelanggan?->nama_lengkap ?? $s->order?->checkout?->nama_penerima ?? '-';
                        $statusClass = match($s->status) { 'diterima' => 'text-success border-success/30', 'dikirim' => 'text-secondary border-secondary/30', 'diproses' => 'text-info border-info/30', 'gagal' => 'text-error border-error/30', default => 'text-on-surface-variant border-outline-variant' };
                    @endphp
                    <article data-table-row data-status="{{ $s->status }}" data-search="{{ strtolower(($s->order->nomor_order ?? '').' '.($s->order->store->nama_toko ?? '').' '.($s->courier->nama_kurir ?? '').' '.($s->nomor_resi ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">local_shipping</span>
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="min-w-0">
                                <p class="font-mono font-bold text-on-surface leading-tight">{{ $s->order->nomor_order ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $s->order->store->nama_toko ?? '-' }}</p>
                            </div>
                            <form method="POST" action="{{ route('superadmin.pengiriman.status', $s->shipment_id) }}" class="shrink-0">
                                @csrf
                                @method('PUT')
                                <div class="relative" id="kirim-m-{{ $s->shipment_id }}-dd">
                                    <button type="button" data-dd-trigger id="kirim-m-{{ $s->shipment_id }}-trigger" onclick="toggleDropdown('kirim-m-{{ $s->shipment_id }}')" aria-haspopup="listbox" aria-expanded="false"
                                        class="flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg px-2 py-1 text-[10px] font-bold uppercase focus:outline-none focus:border-gold-accent cursor-pointer text-left min-w-[120px] {{ $statusClass }}">
                                        <span id="kirim-m-{{ $s->shipment_id }}-label" class="truncate">{{ ucfirst($s->status) }}</span>
                                        <span class="material-symbols-outlined text-[14px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="kirim-m-{{ $s->shipment_id }}-chevron">expand_more</span>
                                    </button>
                                    <div id="kirim-m-{{ $s->shipment_id }}-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                                        class="hidden absolute left-0 top-full mt-1 w-full min-w-[140px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden py-1">
                                        <button type="button" role="option" aria-selected="{{ $s->status === 'pending' ? 'true' : 'false' }}" data-dd-option="pending" onclick="openConfirmPengiriman('kirim-m', {{ $s->shipment_id }}, 'pending')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                            Pending<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'pending' ? '' : 'hidden' }}">check</span>
                                        </button>
                                        <button type="button" role="option" aria-selected="{{ $s->status === 'diproses' ? 'true' : 'false' }}" data-dd-option="diproses" onclick="openConfirmPengiriman('kirim-m', {{ $s->shipment_id }}, 'diproses')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                            Diproses<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'diproses' ? '' : 'hidden' }}">check</span>
                                        </button>
                                        <button type="button" role="option" aria-selected="{{ $s->status === 'dikirim' ? 'true' : 'false' }}" data-dd-option="dikirim" onclick="openConfirmPengiriman('kirim-m', {{ $s->shipment_id }}, 'dikirim')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                            Dikirim<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'dikirim' ? '' : 'hidden' }}">check</span>
                                        </button>
                                        <button type="button" role="option" aria-selected="{{ $s->status === 'diterima' ? 'true' : 'false' }}" data-dd-option="diterima" onclick="openConfirmPengiriman('kirim-m', {{ $s->shipment_id }}, 'diterima')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                            Diterima<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'diterima' ? '' : 'hidden' }}">check</span>
                                        </button>
                                        <button type="button" role="option" aria-selected="{{ $s->status === 'gagal' ? 'true' : 'false' }}" data-dd-option="gagal" onclick="openConfirmPengiriman('kirim-m', {{ $s->shipment_id }}, 'gagal')" class="w-full flex items-center justify-between gap-2 text-left px-3 py-2 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                            Gagal<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $s->status === 'gagal' ? '' : 'hidden' }}">check</span>
                                        </button>
                                    </div>
                                    <input type="hidden" name="status" value="{{ $s->status }}" />
                                </div>
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
                                <dd class="text-on-surface text-right">{{ $pelNama }}</dd>
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
                            data-pelanggan="{{ $pelNama }}">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>Detail
                        </button>
                    </article>
                @empty
                    <p class="text-center text-on-surface-variant py-10">Belum ada data pengiriman.</p>
                @endforelse
                <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada pengiriman yang cocok.</p>
            </div>

            @if ($shipments->hasPages())
                <div class="mt-6 flex justify-center">{{ $shipments->links() }}</div>
            @endif

            <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            SA dapat mengubah status pengiriman untuk keperluan darurat. Perubahan status tercatat di riwayat aktivitas.
        </p>
    </section>
</div>

<!-- Detail Modal -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'detail-modal',
    'dataModal' => true,
    'close' => 'closeModal',
    'icon' => 'local_shipping',
    'title' => 'Detail Pengiriman',
    'subtitle' => '<span id="d-order">-</span>',
    'subtitleRaw' => true,
])
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
    @slot('footer')
        <button type="button" onclick="closeModal()" class="btn-modal btn-modal-ghost w-full">Tutup</button>
    @endslot
@endcomponent

<!-- Modal Konfirmasi Ubah Status Pengiriman -->
@component('SuperAdmin.partials.premium-confirm', [
    'id' => 'confirmPengirimanModal',
    'icon' => 'local_shipping',
    'iconBox' => 'bg-gold-accent/20 border-gold-accent/30',
    'iconColor' => 'text-gold-accent',
    'zIndex' => 75,
    'close' => 'closeConfirmPengiriman',
    'dataModal' => true,
])
    <div class="p-6">
        <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Ubah status pengiriman?</h3>
        <p class="text-on-surface-variant text-sm text-center mb-6">Status akan diubah menjadi <span id="confirm-pengiriman-status" class="font-bold text-on-surface">-</span>.</p>
    </div>
    @slot('footer')
        <div class="flex space-x-3">
            <button type="button" class="flex-1 btn-modal btn-modal-ghost" onclick="closeConfirmPengiriman()">Batal</button>
            <button type="button" id="confirm-pengiriman-submit" class="flex-1 btn-modal btn-modal-primary">Ya, Ubah</button>
        </div>
    @endslot
@endcomponent
@endsection

@push('scripts')
@include('SuperAdmin.partials.dd-helpers')
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
            return show;
        };

        document.querySelectorAll('#table-body tr[data-table-row]').forEach((row) => {
            if (each(row)) {
                visible++;
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

    const pengStatusLabels = { pending: 'Pending', diproses: 'Diproses', dikirim: 'Dikirim', diterima: 'Diterima', gagal: 'Gagal' };
    const pengStatusTokens = {
        diterima: ['text-success', 'border-success/30'],
        dikirim: ['text-secondary', 'border-secondary/30'],
        diproses: ['text-info', 'border-info/30'],
        gagal: ['text-error', 'border-error/30'],
        pending: ['text-on-surface-variant', 'border-outline-variant'],
    };
    function applyPengStatusClass(suffix, id, status) {
        const trigger = document.getElementById(suffix + '-' + id + '-trigger');
        if (!trigger) return;
        const known = Object.values(pengStatusTokens).flat();
        known.forEach((c) => trigger.classList.remove(c));
        (pengStatusTokens[status] ?? pengStatusTokens.pending).forEach((c) => trigger.classList.add(c));
    }
    let _pendingPengiriman = null;
    function openConfirmPengiriman(suffix, id, value) {
        if (_pendingPengiriman && (_pendingPengiriman.suffix !== suffix || _pendingPengiriman.id !== id)) {
            const p = _pendingPengiriman;
            ddSet(p.suffix + '-' + p.id, p.prev, pengStatusLabels[p.prev] ?? p.prev);
            applyPengStatusClass(p.suffix, p.id, p.prev);
        }
        const root = document.getElementById(suffix + '-' + id + '-dd');
        if (!root) return;
        const pre = root.querySelector('[data-dd-value]');
        _pendingPengiriman = { suffix, id, prev: pre ? pre.value : 'pending', value };
        ddSet(suffix + '-' + id, value, pengStatusLabels[value] ?? value);
        applyPengStatusClass(suffix, id, value);
        document.getElementById('confirm-pengiriman-status').textContent = pengStatusLabels[value] ?? value;
        const m = document.getElementById('confirmPengirimanModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeConfirmPengiriman() {
        const m = document.getElementById('confirmPengirimanModal');
        if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
        if (_pendingPengiriman) {
            const { suffix, id, prev } = _pendingPengiriman;
            ddSet(suffix + '-' + id, prev, pengStatusLabels[prev] ?? prev);
            applyPengStatusClass(suffix, id, prev);
            _pendingPengiriman = null;
        }
    }
    document.getElementById('confirm-pengiriman-submit')?.addEventListener('click', () => {
        if (_pendingPengiriman) {
            const root = document.getElementById(_pendingPengiriman.suffix + '-' + _pendingPengiriman.id + '-dd');
            const f = root?.closest('form');
            _pendingPengiriman = null;
            if (f) f.submit();
        }
        const m = document.getElementById('confirmPengirimanModal');
        if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeAllDropdowns(); closeModal(); closeConfirmPengiriman(); }
    });
</script>
@endpush
