@extends('layouts.superadmin')

@section('title', 'Slot Produk')
@section('header-title', 'Slot Produk')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Atur kuota slot produk per toko, kelola paket slot, dan proses permintaan tambah slot dari pemilik toko.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .slot-row { transition: all 0.2s ease; }
    .slot-row:hover { background-color: rgba(139, 30, 63, 0.04); }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">grid_view</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase tracking-wider border border-gold-accent/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-gold-accent"></span>{{ $summary['totals']['toko'] }} Toko
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>{{ number_format($summary['totals']['kuota']) }} Slot
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase tracking-wider border border-success/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-success"></span>{{ number_format($summary['totals']['used']) }} Terpakai
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wider border border-outline-variant">
                    {{ $pendingCount }} Permintaan
                </span>
            </div>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-lg">Setiap toko memiliki kuota slot untuk produk yang dapat tampil. Kelola kuota gratis, paket berbayar, dan proses pembelian slot.</p>
        </div>
    </section>

    <!-- Tabs -->
    <section>
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-2 card-premium flex overflow-x-auto no-scrollbar">
            @php
                $tabs = [
                    'kuota' => ['label' => 'Kuota Toko', 'icon' => 'storefront'],
                    'paket' => ['label' => 'Paket Slot', 'icon' => 'sell'],
                    'permintaan' => ['label' => 'Permintaan', 'icon' => 'request_quote', 'badge' => $pendingCount],
                ];
            @endphp
            @foreach ($tabs as $key => $tab)
                <a href="{{ route('superadmin.slot-produk', ['section' => $key]) }}"
                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 min-h-11 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ $section === $key ? 'bg-gold-accent/15 text-gold-accent border border-gold-accent/30' : 'text-on-surface-variant hover:text-on-surface border border-transparent' }}">
                    <span class="material-symbols-outlined text-[18px]">{{ $tab['icon'] }}</span>
                    {{ $tab['label'] }}
                    @if (!empty($tab['badge']) && $tab['badge'] > 0)
                        <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-error text-on-error text-[10px] font-bold">{{ $tab['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </section>

    @if ($section === 'kuota')
        <!-- KUOTA TOKO -->
        <section class="rise rise-d1" data-table-scope>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Kuota Per Toko</h2>
                        <p class="text-xs text-on-surface-variant mt-0.5">Terpakai dihitung dari produk berstatus aktif. Kuota = gratis + pembelian + tambahan manual.</p>
                    </div>
                </div>

                <!-- Slot Awal Toko Baru (default global) -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 bg-surface-container-low border border-muted-border rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">workspace_premium</span>
                        <div>
                            <p class="font-bold text-on-surface">Slot Awal Toko Baru</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Kuota gratis otomatis untuk toko yang baru disetujui. Saat ini <span class="font-bold text-on-surface">{{ number_format($slotAwalDefault) }} slot</span>. Kuota toko yang sudah ada tidak terpengaruh.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('superadmin.slot-produk.default') }}" onsubmit="return openConfirmSlot(event, 'Slot awal toko baru akan diubah. Lanjutkan?')" class="flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="number" name="slot_awal" min="0" max="100000" value="{{ $slotAwalDefault }}" required class="w-28 bg-transparent border border-muted-border rounded-lg px-3 py-2 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" aria-label="Jumlah slot awal" />
                        <button type="submit" class="py-2 px-4 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium whitespace-nowrap">Simpan</button>
                    </form>
                </div>

                <!-- Harga Per Slot (default global) -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 bg-surface-container-low border border-muted-border rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">payments</span>
                        <div>
                            <p class="font-bold text-on-surface">Harga Per Slot Fleksibel</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Harga satuan untuk pembelian slot fleksibel oleh Owner. Saat ini <span class="font-bold text-on-surface">Rp {{ number_format($hargaPerSlot) }}</span> per slot. Total = jumlah slot × harga satuan.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('superadmin.slot-produk.harga-per-slot') }}" onsubmit="return openConfirmSlot(event, 'Harga per slot akan diubah. Lanjutkan?')" class="flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="number" name="harga_per_slot" min="100" max="100000" value="{{ $hargaPerSlot }}" required class="w-32 bg-transparent border border-muted-border rounded-lg px-3 py-2 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" aria-label="Harga per slot" />
                        <button type="submit" class="py-2 px-4 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium whitespace-nowrap">Simpan</button>
                    </form>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('superadmin.slot-produk') }}" class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
                    <input type="hidden" name="section" value="kuota" />
                    <div class="relative flex-1 min-w-[220px]">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                        <input type="text" id="searchInput" name="q" value="{{ request('q') }}" placeholder="Cari nama toko..." oninput="if(this.value.length > 2 || this.value.length === 0) this.form.submit()" class="w-full bg-transparent border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
                    </div>
                    <button type="submit" class="py-2.5 px-4 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium whitespace-nowrap">Cari</button>
                    @if (request('q'))
                        <a href="{{ route('superadmin.slot-produk', ['section' => 'kuota']) }}" class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</a>
                    @endif
                </form>

                <!-- Table -->
                <div id="table-wrap" class="overflow-x-auto hidden md:block">
                    <table class="w-full min-w-[820px] font-body-md text-sm">
                        <thead>
                            <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                                <th class="p-4 text-center w-12">No</th>
                                <th class="p-4 text-left">Toko</th>
                                <th class="p-4 text-center">Kuota</th>
                                <th class="p-4 text-center">Terpakai</th>
                                <th class="p-4 text-center">Sisa</th>
                                <th class="p-4 text-center">Progres</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stores as $store)
                                @php $s = $summary['by_store'][$store->store_id]; @endphp
                                <tr class="slot-row border-b border-muted-border last:border-0"
                                    data-name="{{ strtolower($store->nama_toko) }}">
                                    <td class="py-3.5 px-4 text-on-surface-variant text-center">{{ $loop->iteration }}</td>
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0">
                                                <span class="material-symbols-outlined text-[18px] text-gold-accent">storefront</span>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-on-surface truncate">{{ $store->nama_toko }}</p>
                                                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant mt-0.5">Kuota Gratis: {{ number_format($s['free_quota']) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-semibold text-on-surface">{{ number_format($s['total']) }}</td>
                                    <td class="py-3.5 px-4 text-center text-on-surface-variant">{{ number_format($s['used']) }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold {{ $s['available'] < 1 ? 'bg-error/10 text-error' : 'bg-success/10 text-success' }}">{{ number_format($s['available']) }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="inline-flex items-center gap-2 min-w-[120px]">
                                            <div class="w-20 h-1.5 bg-surface-container-high rounded-full overflow-hidden"><div class="h-full bg-gold-accent rounded-full" style="width: {{ min(100, $s['progress']) }}%"></div></div>
                                            <span class="text-xs text-on-surface-variant">{{ $s['progress'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button type="button" onclick="openTambahModal({{ $store->store_id }}, '{{ addslashes($store->nama_toko) }}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-deep-onyx text-on-primary text-xs font-semibold hover:bg-black transition-colors btn-premium">
                                                <span class="material-symbols-outlined text-[15px]">add</span>Tambah
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="py-12 text-center text-on-surface-variant">Belum ada toko.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile: card grid -->
                <div id="mobile-grid" class="md:hidden grid grid-cols-1 gap-gutter mb-6">
                    @forelse ($stores as $store)
                        @php $s = $summary['by_store'][$store->store_id]; @endphp
                        <article class="slot-row bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium" data-name="{{ strtolower($store->nama_toko) }}">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[20px] text-gold-accent">storefront</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-title-md text-title-md text-on-surface truncate">{{ $store->nama_toko }}</p>
                                        <p class="text-xs text-on-surface-variant">Kuota Gratis: {{ number_format($s['free_quota']) }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold {{ $s['available'] < 1 ? 'bg-error/10 text-error' : 'bg-success/10 text-success' }}">Sisa {{ number_format($s['available']) }}</span>
                            </div>
                            <div class="mb-3">
                                <div class="flex justify-between text-xs text-on-surface-variant mb-1">
                                    <span>{{ number_format($s['used']) }} / {{ number_format($s['total']) }} terpakai</span>
                                    <span>{{ $s['progress'] }}%</span>
                                </div>
                                <div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden"><div class="h-full bg-gold-accent rounded-full" style="width: {{ min(100, $s['progress']) }}%"></div></div>
                            </div>
                            <div class="grid grid-cols-1 gap-2">
                                <button type="button" onclick="openTambahModal({{ $store->store_id }}, '{{ addslashes($store->nama_toko) }}')" class="min-h-11 inline-flex items-center justify-center gap-2 rounded-lg bg-deep-onyx text-on-primary text-xs font-semibold hover:bg-black transition-colors btn-premium">
                                    <span class="material-symbols-outlined text-[16px]">add</span>Tambah Slot
                                </button>
                            </div>
                        </article>
                    @empty
                        <p class="text-center text-on-surface-variant py-10">Tidak ada toko yang cocok.</p>
                    @endforelse
                </div>

                @if ($stores->hasPages())
                    <div class="mt-6 flex justify-center">{{ $stores->links() }}</div>
                @endif
            </div>
        </section>
    @endif

    @if ($section === 'paket')
        <!-- PAKET SLOT -->
        <section class="rise rise-d1">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Paket Slot Berbayar</h2>
                        <p class="text-xs text-on-surface-variant mt-0.5">Paket berbayar yang dapat dibeli pemilik toko saat kuota habis.</p>
                    </div>
                    <button type="button" onclick="openModal('modal-tambah-paket')" class="py-2.5 px-5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center gap-2 shrink-0">
                        <span class="material-symbols-outlined text-[18px]">add</span>Tambah Paket
                    </button>
                </div>

                <form method="GET" action="{{ route('superadmin.slot-produk') }}" class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
                    <input type="hidden" name="section" value="paket" />
                    <div class="relative flex-1 min-w-[220px]">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama paket..." oninput="if(this.value.length > 2 || this.value.length === 0) this.form.submit()" class="w-full bg-transparent border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
                    </div>
                    <button type="submit" class="py-2.5 px-4 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium whitespace-nowrap">Cari</button>
                    @if (request('q'))
                        <a href="{{ route('superadmin.slot-produk', ['section' => 'paket']) }}" class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</a>
                    @endif
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
                    @forelse ($packages as $paket)
                        <article class="bg-surface-container-low border border-muted-border rounded-lg p-5 card-premium flex flex-col {{ $paket->status === \App\Models\ProductSlotPackage::STATUS_NONAKTIF ? 'opacity-70' : '' }}">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <h3 class="font-title-md text-title-md text-on-surface">{{ $paket->nama_paket }}</h3>
                                    <p class="text-xs text-on-surface-variant mt-0.5">{{ number_format($paket->durasi_hari) }} hari berlaku • {{ $paket->subscriptions_count }} pelanggan</p>
                                </div>
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border {{ $paket->status === \App\Models\ProductSlotPackage::STATUS_AKTIF ? 'bg-success/10 text-success border-success/20' : 'bg-error/10 text-error border-error/30' }}">{{ $paket->status }}</span>
                            </div>
                            <div class="text-2xl font-bold text-gold-accent mb-4">Rp {{ number_format($paket->harga, 0, ',', '.') }}</div>
                            <ul class="space-y-2 font-body-md text-sm text-on-surface-variant flex-1 mb-5">
                                <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check_circle</span>{{ number_format($paket->jumlah_slot) }} slot produk</li>
                                <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check_circle</span>{{ number_format($paket->durasi_hari) }} hari masa berlaku</li>
                            </ul>
                            <form method="POST" action="{{ route('superadmin.slot-produk.paket.toggle', $paket->slot_package_id) }}" onsubmit="return openConfirmSlot(event, '{{ $paket->status === \App\Models\ProductSlotPackage::STATUS_AKTIF ? 'Nonaktifkan paket ini?' : 'Aktifkan paket ini?' }}')">
                                @csrf
                                <button type="submit" class="w-full min-h-11 rounded-lg border text-xs font-semibold transition-colors {{ $paket->status === \App\Models\ProductSlotPackage::STATUS_AKTIF ? 'border-error/40 text-error hover:bg-error/10' : 'border-success/40 text-success hover:bg-success/10' }}">
                                    {{ $paket->status === \App\Models\ProductSlotPackage::STATUS_AKTIF ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </article>
                    @empty
                        <p class="col-span-full text-center text-on-surface-variant py-12">Belum ada paket slot. Tambahkan paket berbayar untuk pemilik toko.</p>
                    @endforelse
                </div>
                @if ($packages->hasPages())
                    <div class="mt-6 flex justify-center">{{ $packages->links() }}</div>
                @endif
            </div>
        </section>
    @endif

    @if ($section === 'permintaan')
        <!-- PERMINTAAN BELI -->
        <section class="rise rise-d1">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
                <div>
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Permintaan Tambah Slot</h2>
                    <p class="text-xs text-on-surface-variant mt-0.5">Pemilik toko mengajukan pembelian slot saat kuota habis. Pastikan bukti pembayaran sebelum menyetujui.</p>
                </div>

                <form method="GET" action="{{ route('superadmin.slot-produk') }}" class="flex flex-col sm:flex-row sm:items-center gap-3 mt-6 mb-6">
                    <input type="hidden" name="section" value="permintaan" />
                    <div class="relative flex-1 min-w-[220px]">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari toko, alasan, atau jumlah slot..." oninput="if(this.value.length > 2 || this.value.length === 0) this.form.submit()" class="w-full bg-transparent border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
                    </div>
                    <button type="submit" class="py-2.5 px-4 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium whitespace-nowrap">Cari</button>
                    @if (request('q'))
                        <a href="{{ route('superadmin.slot-produk', ['section' => 'permintaan']) }}" class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</a>
                    @endif
                </form>

                <div class="mt-6 space-y-gutter">
                    @forelse ($purchaseRequests as $rmt)
                        @php
                            $isPending = $rmt->status === \App\Models\SlotPurchaseRequest::STATUS_PENDING;
                            $badge = match ($rmt->status) {
                                \App\Models\SlotPurchaseRequest::STATUS_DISETUJUI => 'bg-success/10 text-success border-success/20',
                                \App\Models\SlotPurchaseRequest::STATUS_DITOLAK => 'bg-error/10 text-error border-error/30',
                                default => 'bg-gold-accent/10 text-gold-accent border-gold-accent/20',
                            };
                            $payVerified = $rmt->payment_status === \App\Models\SlotPurchaseRequest::PEMBAYARAN_TERVERIFIKASI;
                            $payBadge = match ($rmt->payment_status) {
                                \App\Models\SlotPurchaseRequest::PEMBAYARAN_TERVERIFIKASI => 'bg-success/10 text-success border-success/20',
                                \App\Models\SlotPurchaseRequest::PEMBAYARAN_DITOLAK => 'bg-error/10 text-error border-error/30',
                                default => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                            };
                        @endphp
                        <article class="slot-row border border-muted-border rounded-lg p-5 card-premium" data-status="{{ $rmt->status }}">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <div class="flex items-start gap-3 min-w-0 lg:max-w-xl">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[20px] text-gold-accent">request_quote</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="font-title-md text-title-md text-on-surface">{{ $rmt->store->nama_toko ?? '-' }}</p>
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border {{ $badge }}">{{ $rmt->status }}</span>
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border {{ $payBadge }}">Bayar: {{ str_replace('_', ' ', $rmt->payment_status ?? '-') }}</span>
                                        </div>
                                        <p class="text-sm text-on-surface mt-1">
                                            <span class="font-semibold">{{ number_format($rmt->jumlah_slot) }} slot</span>
                                            • total <span class="font-bold text-gold-accent">Rp {{ number_format((float) $rmt->total_harga, 0, ',', '.') }}</span>
                                            (satuan Rp {{ number_format((float) $rmt->harga_per_slot, 0, ',', '.') }})
                                            @if ($rmt->metode_pembayaran) • {{ $rmt->metode_pembayaran }} @endif
                                        </p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">diajukan {{ $rmt->diajukan_pada?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
                                        @if ($rmt->alasan)
                                            <p class="text-sm text-on-surface-variant mt-1">Alasan: {{ $rmt->alasan }}</p>
                                        @endif
                                        @if ($rmt->alasan_penolakan)
                                            <p class="text-sm text-error mt-1">Ditolak: {{ $rmt->alasan_penolakan }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                                            @if ($rmt->file_bukti)
                                                <a href="{{ asset($rmt->file_bukti) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-gold-accent hover:underline">
                                                    <span class="material-symbols-outlined text-[15px]">receipt_long</span>Lihat Bukti Bayar
                                                </a>
                                            @else
                                                <span class="text-xs text-on-surface-variant">Tanpa bukti bayar</span>
                                            @endif
                                            @if ($rmt->handler)
                                                <span class="text-xs text-on-surface-variant">• ditangani {{ $rmt->handler->nama_lengkap }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if ($isPending)
                                    <div class="flex flex-col sm:flex-row gap-2 shrink-0">
                                        @if (! $payVerified)
                                            <form method="POST" action="{{ route('superadmin.slot-produk.permintaan.verifikasi', $rmt->slot_purchase_id) }}" onsubmit="return openConfirmSlot(event, 'Verifikasi pembayaran ini?')">
                                                @csrf
                                                <button type="submit" class="min-h-11 px-4 rounded-lg border border-gold-accent/40 text-gold-accent text-xs font-semibold hover:bg-gold-accent/10 transition-colors inline-flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                                    <span class="material-symbols-outlined text-[15px]">done_all</span>Verifikasi Bayar
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" onclick="openTolakModal({{ $rmt->slot_purchase_id }}, '{{ addslashes($rmt->store->nama_toko ?? '-') }}')" class="min-h-11 px-4 rounded-lg border border-error/40 text-error text-xs font-semibold hover:bg-error/10 transition-colors inline-flex items-center justify-center gap-1.5 w-full sm:w-auto">
                                            <span class="material-symbols-outlined text-[15px]">block</span>Tolak
                                        </button>
                                        <form method="POST" action="{{ route('superadmin.slot-produk.permintaan.setujui', $rmt->slot_purchase_id) }}" onsubmit="return openConfirmSlot(event, 'Setujui dan tambahkan {{ $rmt->jumlah_slot }} slot (Rp {{ number_format((float) $rmt->total_harga, 0, ',', '.') }}) untuk toko ini?')">
                                            @csrf
                                            <button type="submit" class="w-full min-h-11 px-4 rounded-lg bg-deep-onyx text-on-primary text-xs font-semibold hover:bg-black transition-colors btn-premium inline-flex items-center justify-center gap-1.5 {{ $payVerified ? '' : 'opacity-50 cursor-not-allowed' }}" {{ $payVerified ? '' : 'disabled' }}>
                                                <span class="material-symbols-outlined text-[15px]">check_circle</span>Setujui & Tambah Slot
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="text-center text-on-surface-variant py-12">Belum ada permintaan tambah slot.</p>
                    @endforelse
                </div>
                @if ($purchaseRequests->hasPages())
                    <div class="mt-6 flex justify-center">{{ $purchaseRequests->links() }}</div>
                @endif
            </div>
        </section>
    @endif
</div>

<!-- Tambah Manual Modal -->
<div id="modal-tambah" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-tambah')"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Tambah Slot Manual</p>
                <h3 id="tambah-toko-nama" class="font-title-md text-title-md text-on-surface premium-heading mt-1">-</h3>
            </div>
            <button type="button" onclick="closeModal('modal-tambah')" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="tambah-form" method="POST" action="" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Jumlah Slot</label>
                <input type="number" name="jumlah_slot" min="1" max="100000" required class="w-full bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
            </div>
            <div>
                <label class="block raliva-label mb-2">Keterangan (opsional)</label>
                <textarea name="keterangan" rows="2" maxlength="500" class="w-full bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" onclick="closeModal('modal-tambah')" class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[16px]">add</span>Tambah Slot</button>
            </div>
        </form>
    </div>
</div>

<!-- Tambah Paket Modal -->
<div id="modal-tambah-paket" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-tambah-paket')"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Paket Slot</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">Tambah Paket Baru</h3>
            </div>
            <button type="button" onclick="closeModal('modal-tambah-paket')" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('superadmin.slot-produk.paket.store') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Nama Paket</label>
                <input type="text" name="nama_paket" maxlength="100" required class="w-full bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="contoh: Growth 10 Slot" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block raliva-label mb-2">Harga (Rp)</label>
                    <input type="number" name="harga" min="0" step="0.01" required class="w-full bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Jumlah Slot</label>
                    <input type="number" name="jumlah_slot" min="1" required class="w-full bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
                </div>
            </div>
            <div>
                <label class="block raliva-label mb-2">Durasi Berlaku (hari)</label>
                <input type="number" name="durasi_hari" min="1" required class="w-full bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" onclick="closeModal('modal-tambah-paket')" class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[16px]">add</span>Tambah Paket</button>
            </div>
        </form>
    </div>
</div>

<!-- Tolak Permintaan Modal -->
<div id="modal-tolak" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-tolak')"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Tolak Permintaan</p>
                <h3 id="tolak-toko-nama" class="font-title-md text-title-md text-on-surface premium-heading mt-1">-</h3>
            </div>
            <button type="button" onclick="closeModal('modal-tolak')" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="tolak-form" method="POST" action="" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Alasan Penolakan</label>
                <textarea name="alasan" rows="3" minlength="10" maxlength="1000" required class="w-full bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-error focus:ring-1 focus:ring-error transition-colors" placeholder="Minimal 10 karakter"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" onclick="closeModal('modal-tolak')" class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-error text-on-error text-sm font-semibold rounded flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[16px]">block</span>Tolak</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Slot (reusable) -->
<div id="confirmSlotModal" class="fixed inset-0 z-[75] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" onclick="if (event.target === this) closeConfirmSlot()">
    <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
        <div class="p-8">
            <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-gold-accent text-[28px]">help</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Konfirmasi</h3>
            <p id="confirm-slot-desc" class="text-on-surface-variant text-sm text-center mb-4">Lanjutkan aksi ini?</p>
            <div class="flex space-x-3">
                <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeConfirmSlot()">Batal</button>
                <button type="button" id="confirm-slot-submit" class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id)?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id)?.classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="modal-"]').forEach(m => {
                if (!m.classList.contains('hidden')) { m.classList.add('hidden'); document.body.style.overflow = ''; }
            });
            closeConfirmSlot();
        }
    });

    function openTambahModal(id, nama) {
        document.getElementById('tambah-toko-nama').textContent = nama;
        document.getElementById('tambah-form').action = '{{ route('superadmin.slot-produk.tambah-manual', ':id:') }}'.replace(':id:', id);
        openModal('modal-tambah');
    }
    function openTolakModal(id, nama) {
        document.getElementById('tolak-toko-nama').textContent = nama;
        document.getElementById('tolak-form').action = '{{ route('superadmin.slot-produk.permintaan.tolak', ':id:') }}'.replace(':id:', id);
        openModal('modal-tolak');
    }

    let _pendingSlotForm = null;
    function openConfirmSlot(e, msg) {
        e.preventDefault();
        _pendingSlotForm = e.target;
        document.getElementById('confirm-slot-desc').textContent = msg;
        const m = document.getElementById('confirmSlotModal');
        m.classList.remove('hidden'); m.classList.add('flex');
        document.body.style.overflow = 'hidden';
        return false;
    }
    function closeConfirmSlot() {
        const m = document.getElementById('confirmSlotModal');
        if (m) { m.classList.add('hidden'); m.classList.remove('flex'); document.body.style.overflow = ''; }
        _pendingSlotForm = null;
    }
    document.getElementById('confirm-slot-submit')?.addEventListener('click', () => {
        if (_pendingSlotForm) _pendingSlotForm.submit();
        closeConfirmSlot();
    });

    </script>
@endpush
