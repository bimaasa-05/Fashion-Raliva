@extends('layouts.superadmin')

@section('title', 'Manajemen Toko')

@section('header-title', 'Data Toko')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Verifikasi, tolak, tangguhkan, dan aktifkan kembali toko penjual.')

@php
    $badgeMap = [
        \App\Models\Store::STATUS_AKTIF => ['label' => 'Aktif', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        \App\Models\Store::STATUS_PENDING => ['label' => 'Menunggu', 'class' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30'],
        \App\Models\Store::STATUS_NONAKTIF => ['label' => 'Ditangguhkan', 'class' => 'bg-error/10 text-error border-error/20'],
        \App\Models\Store::STATUS_DITOLAK => ['label' => 'Ditolak', 'class' => 'bg-error/10 text-error border-error/20'],
    ];

    $tabs = [
        'semua' => 'Semua',
        \App\Models\Store::STATUS_PENDING => 'Menunggu',
        \App\Models\Store::STATUS_AKTIF => 'Aktif',
        \App\Models\Store::STATUS_NONAKTIF => 'Ditangguhkan',
        \App\Models\Store::STATUS_DITOLAK => 'Ditolak',
    ];
@endphp

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.filled { font-variation-settings: 'FILL' 1; }

    .toko-card { transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease; }
    .toko-card:hover .toko-detail-hint { opacity: 1; transform: translateX(0); }
    .toko-detail-hint { opacity: 0; transform: translateX(-6px); transition: all 0.25s ease; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium mb-6">
    <div class="flex items-center gap-2 mb-3">
        <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
        <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status Toko</span>
    </div>
    <div id="toko-tabs" class="flex flex-wrap gap-3 border-b border-muted-border pb-4">
        @foreach ($tabs as $key => $label)
            <a href="{{ route('superadmin.manajemen-toko', $key === 'semua' ? [] : ['status' => $key]) }}"
                class="px-4 py-2 border-b-2 font-label-sm uppercase tracking-wider transition-colors {{ $activeStatus === $key
                    ? 'border-primary text-primary'
                    : 'border-transparent text-on-surface-variant hover:text-primary' }}">
                {{ $label }} <span class="opacity-60">({{ $stats[$key] ?? 0 }})</span>
            </a>
        @endforeach
    </div>
</div>

<section class="px-gutter md:px-container-margin py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($stores as $item)
            @php
                $store = $item->model;
                $badge = $badgeMap[$store->status];
                $isSuspended = $store->status === \App\Models\Store::STATUS_NONAKTIF;
                $isPending = $store->status === \App\Models\Store::STATUS_PENDING;
            @endphp
            <article
                data-id="{{ $store->store_id }}"
                data-status="{{ $store->status }}"
                data-name="{{ $store->nama_toko }}"
                data-initial="{{ $item->initial }}"
                data-owner="{{ $item->owner_nama }}"
                data-joined="{{ $item->joined }}"
                data-location="{{ $item->location }}"
                data-products="{{ $item->products_count }}"
                data-orders="{{ $item->orders_count }}"
                data-rating="{{ $item->rating ?? '--' }}"
                data-desc="{{ $store->deskripsi }}"
                data-reason="{{ $store->alasan_penolakan }}"
                data-phone="{{ $store->nomor_telepon ?? '-' }}"
                onclick="openStoreModal(this)"
                class="toko-card group bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden cursor-pointer card-premium">
                <div class="h-1 w-full {{ $isSuspended || $store->status === \App\Models\Store::STATUS_DITOLAK
                    ? 'bg-gradient-to-r from-error/50 via-error/20 to-transparent'
                    : 'bg-gradient-to-r from-gold-accent via-gold-accent/40 to-transparent' }}"></div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden ring-2 ring-gold-accent/25 flex-shrink-0 {{ $isSuspended ? 'bg-surface-container-high ring-error/25 grayscale flex items-center justify-center' : ($store->logo ? '' : 'bg-surface-container-high ring-gold-accent/25 flex items-center justify-center') }}">
                                @if ($store->logo)
                                    <img class="w-full h-full object-cover" alt="Logo {{ $store->nama_toko }}" src="{{ asset($store->logo) }}" />
                                @else
                                    <span class="font-title-md text-on-surface-variant">{{ $item->initial }}</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-title-md text-title-md text-on-surface truncate {{ $isSuspended ? 'line-through decoration-on-surface-variant' : '' }}">{{ $store->nama_toko }}</h3>
                                <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mt-1 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isPending ? 'bg-gold-accent animate-pulse' : ($isSuspended ? 'bg-error' : 'bg-secondary') }}"></span>{{ $item->owner_nama }}
                                </p>
                            </div>
                        </div>
                        <span data-badge class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold tracking-widest uppercase border rounded-full shrink-0 {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3 mb-5">
                        <div class="bg-surface-container-low rounded-lg py-3 text-center">
                            <span class="block font-title-md {{ $isSuspended ? 'text-on-surface-variant' : 'text-on-surface' }}">{{ $item->products_count }}</span>
                            <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Produk</span>
                        </div>
                        <div class="bg-surface-container-low rounded-lg py-3 text-center">
                            <span class="block font-title-md {{ $isSuspended ? 'text-on-surface-variant' : 'text-on-surface' }}">{{ $item->orders_count }}</span>
                            <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Pesanan</span>
                        </div>
                        <div class="bg-surface-container-low rounded-lg py-3 text-center">
                            <span class="block font-title-md {{ $isSuspended ? 'text-on-surface-variant' : 'text-on-surface' }} flex items-center justify-center gap-1">{{ $item->rating ?? '--' }} @if($item->rating)<span class="material-symbols-outlined text-[14px] filled text-secondary">star</span>@endif</span>
                            <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Rating</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                        <span class="toko-detail-hint font-label-sm text-[11px] uppercase tracking-widest text-gold-accent inline-flex items-center gap-1">Lihat Detail <span class="material-symbols-outlined text-[14px]">arrow_forward</span></span>
                        <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">place</span>{{ $item->location }}</span>
                        <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">call</span>{{ $item->model->nomor_telepon ?? '-' }}</span>
                    </div>
                </div>
            </article>
        @empty
            <p id="toko-kosong" class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada toko pada status ini.</p>
        @endforelse
    </div>
</section>
@endsection

@push('scripts')
<script>
    const statusMeta = {
        aktif: {
            chipLabel: 'Aktif',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-secondary-container/20 text-secondary border-secondary/20',
            verification: 'Terverifikasi'
        },
        pending: {
            chipLabel: 'Menunggu Tinjauan',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-gold-accent/10 text-gold-accent border-gold-accent/30',
            verification: 'Dokumen lengkap • Menunggu review'
        },
        nonaktif: {
            chipLabel: 'Ditangguhkan',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-error/10 text-error border-error/20',
            verification: 'Ditangguhkan oleh Admin'
        },
        ditolak: {
            chipLabel: 'Ditolak',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-error/10 text-error border-error/20',
            verification: 'Pengajuan ditolak • Perbaiki lalu ajukan ulang'
        }
    };

    let activeCard = null;

    const actionUrls = {
        setujui: (id) => '{{ route('superadmin.manajemen-toko.setujui', ':id:') }}'.replace(':id:', id),
        tolak: (id) => '{{ route('superadmin.manajemen-toko.tolak', ':id:') }}'.replace(':id:', id),
        tangguhkan: (id) => '{{ route('superadmin.manajemen-toko.tangguhkan', ':id:') }}'.replace(':id:', id),
        aktifkan: (id) => '{{ route('superadmin.manajemen-toko.aktifkan', ':id:') }}'.replace(':id:', id)
    };

    function openStoreModal(card) {
        activeCard = card;
        const d = card.dataset;
        const meta = statusMeta[d.status];

        document.getElementById('store-avatar').textContent = d.initial;
        document.getElementById('modal-title').textContent = d.name;
        const chip = document.getElementById('store-status-chip');
        chip.textContent = meta.chipLabel;
        chip.className = meta.chipClass;
        document.getElementById('store-meta').textContent = 'Bergabung ' + d.joined + ' \u2022 ' + d.location;
        document.getElementById('stat-products').textContent = d.products;
        document.getElementById('stat-orders').textContent = d.orders;
        document.getElementById('stat-rating').textContent = d.rating;
        document.getElementById('store-desc').textContent = d.desc;
        document.getElementById('info-owner').textContent = d.owner;
        document.getElementById('info-location').textContent = d.location;
        document.getElementById('info-joined').textContent = d.joined;
        document.getElementById('info-verification').textContent = meta.verification;
        document.getElementById('info-phone').textContent = d.phone || '-';

        const reasonBox = document.getElementById('reject-reason-box');
        if (d.status === 'ditolak' && d.reason) {
            reasonBox.classList.remove('hidden');
            document.getElementById('reject-reason-text').textContent = d.reason;
        } else {
            reasonBox.classList.add('hidden');
        }

        const mainBtn = document.getElementById('store-action-main');
        const rejectBtn = document.getElementById('store-action-reject');
        const mainForm = document.getElementById('store-action-form');

        if (d.status === 'pending') {
            mainBtn.textContent = 'Setujui Toko';
            mainBtn.classList.remove('hidden');
            mainForm.action = actionUrls.setujui(d.id);
            rejectBtn.classList.remove('hidden');
        } else if (d.status === 'aktif') {
            mainBtn.textContent = 'Tangguhkan Toko';
            mainBtn.classList.remove('hidden');
            mainForm.action = actionUrls.tangguhkan(d.id);
            rejectBtn.classList.add('hidden');
        } else if (d.status === 'nonaktif') {
            mainBtn.textContent = 'Aktifkan Kembali';
            mainBtn.classList.remove('hidden');
            mainForm.action = actionUrls.aktifkan(d.id);
            rejectBtn.classList.add('hidden');
        } else {
            mainBtn.classList.add('hidden');
            rejectBtn.classList.add('hidden');
        }

        document.getElementById('store-modal-scroll').scrollTop = 0;
        const modal = document.getElementById('store-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeStoreModal() {
        const modal = document.getElementById('store-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function openRejectModal() {
        if (!activeCard) return;
        document.getElementById('reject-store-name').textContent = activeCard.dataset.name;
        document.getElementById('reject-form-real').action = actionUrls.tolak(activeCard.dataset.id);
        const textarea = document.getElementById('reject-alasan-input');
        textarea.value = '';
        const modal = document.getElementById('reject-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeStoreModal(); closeRejectModal(); }
    });
</script>
@endpush

@push('modals')
<div aria-labelledby="modal-title" aria-modal="true" role="dialog" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 md:p-6 bg-black/50 backdrop-blur-sm" id="store-modal" onclick="if (event.target === this) closeStoreModal()">
    <div class="relative z-10 w-full max-w-3xl h-[min(795px,92vh)] bg-surface-container-lowest rounded-xl border border-muted-border shadow-2xl flex flex-col overflow-hidden">

        <div class="shrink-0 relative border-b border-muted-border">
            <div class="absolute inset-0 bg-gradient-to-r from-gold-accent/15 via-gold-accent/5 to-transparent pointer-events-none"></div>
            <div class="relative flex items-start justify-between gap-4 p-6 md:p-7">
                <div class="flex items-center gap-5 min-w-0">
                    <div id="store-avatar" class="w-16 h-16 rounded-2xl bg-surface-container-high border-2 border-gold-accent/40 shadow-lg flex items-center justify-center font-title-md text-lg text-on-surface shrink-0">NS</div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="font-display-lg text-headline-lg-mobile md:text-headline-lg truncate" id="modal-title">Nama Toko</h2>
                        </div>
                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                            <span id="store-status-chip" class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-gold-accent/10 text-gold-accent border-gold-accent/30">Menunggu Tinjauan</span>
                            <span id="store-meta" class="text-xs text-on-surface-variant">Bergabung - • -</span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="closeStoreModal()" class="text-on-surface-variant hover:text-on-surface transition-colors p-2 -mr-2 shrink-0"><span class="material-symbols-outlined">close</span></button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8" id="store-modal-scroll">
            <div id="reject-reason-box" class="hidden bg-error/5 border border-error/25 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-error text-[18px] mt-0.5">gpp_bad</span>
                    <div>
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-error mb-1">Alasan Penolakan</p>
                        <p id="reject-reason-text" class="text-sm text-on-surface"></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-gutter">
                <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                    <span class="block font-headline-lg-mobile text-headline-lg-mobile text-on-surface" id="stat-products">-</span>
                    <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Produk Aktif</span>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/10 pointer-events-none select-none">checkroom</span>
                </div>
                <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                    <span class="block font-headline-lg-mobile text-headline-lg-mobile text-on-surface" id="stat-orders">-</span>
                    <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Total Pesanan</span>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/10 pointer-events-none select-none">shopping_bag</span>
                </div>
                <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                    <span class="block font-headline-lg-mobile text-headline-lg-mobile text-on-surface flex items-center justify-center gap-1"><span id="stat-rating">-</span><span class="material-symbols-outlined text-[18px] filled text-secondary">star</span></span>
                    <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Rating Toko</span>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/10 pointer-events-none select-none">reviews</span>
                </div>
            </div>

            <section>
                <h4 class="font-title-md text-title-md mb-3 uppercase tracking-wider text-on-surface premium-heading">Deskripsi Toko</h4>
                <p id="store-desc" class="font-body-md text-body-md text-on-surface-variant leading-relaxed max-w-2xl">-</p>
            </section>

            <section>
                <h4 class="font-title-md text-title-md mb-4 uppercase tracking-wider text-on-surface premium-heading">Informasi Toko</h4>
                <div class="grid sm:grid-cols-2 gap-gutter">
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">person</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Pemilik</span><span id="info-owner" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">calendar_month</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Bergabung</span><span id="info-joined" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">place</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Lokasi</span><span id="info-location" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">fact_check</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Verifikasi</span><span id="info-verification" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">call</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Telepon</span><span id="info-phone" class="font-title-md text-title-md text-on-surface block truncate">-</span></div>
                    </div>
                </div>
            </section>
        </div>

        <div class="shrink-0 border-t border-muted-border bg-surface/95 backdrop-blur px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px] text-gold-accent">history</span>
                Keputusan tercatat di riwayat aktivitas
            </p>
            <div class="flex gap-3 w-full sm:w-auto">
                <button id="store-action-reject" type="button" onclick="openRejectModal()" class="flex-1 sm:flex-none px-6 py-3 border border-error/40 text-error font-label-sm text-label-sm uppercase tracking-wider rounded-lg hover:bg-error/10 transition-colors">Tolak</button>
                <form id="store-action-form" method="POST" action="" onsubmit="closeStoreModal()">
                    @csrf
                    <button id="store-action-main" type="submit" class="w-full sm:w-auto px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-wider rounded-lg btn-premium">Setujui Toko</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div aria-labelledby="reject-title" aria-modal="true" role="dialog" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="reject-modal" onclick="if (event.target === this) closeRejectModal()">
    <form id="reject-form-real" method="POST" action="" onsubmit="closeRejectModal(); closeStoreModal()">
        @csrf
        <div class="relative z-10 bg-surface-container-lowest w-full max-w-md border border-muted-border rounded-xl shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
                </div>
                <h3 class="font-display-lg text-headline-lg-mobile text-center mb-2" id="reject-title">Tolak Toko</h3>
                <p class="text-on-surface-variant text-sm text-center mb-6">Berikan alasan penolakan untuk <span id="reject-store-name" class="font-bold text-on-surface">-</span>. Pesan ini akan dikirim ke pemilik toko.</p>
                <textarea required minlength="10" maxlength="1000" name="alasan" id="reject-alasan-input" class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-sm focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-6 min-h-[120px] resize-none" placeholder="Misal: Dokumen izin usaha belum lengkap... (minimal 10 karakter)"></textarea>
                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2.5 text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider hover:text-on-surface transition-colors" onclick="closeRejectModal()">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-error text-on-error font-label-sm text-[11px] uppercase tracking-wider rounded-lg hover:opacity-90 transition-opacity btn-premium">Konfirmasi Penolakan</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endpush
