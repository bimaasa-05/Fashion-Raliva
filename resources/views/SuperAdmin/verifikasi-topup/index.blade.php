@extends('layouts.superadmin')

@section('title', 'Verifikasi Top Up Saldo')

@section('header-title', 'Verifikasi Top Up')
@section('header-badge', 'Super Admin')

@section('header-subtitle', 'Verifikasi bukti top up saldo akun Customer dan terbitkan saldo ke wallet.')

@php
    $badgeMap = [
        'pending' => ['label' => 'Menunggu Bayar', 'class' => \App\Support\StatusStyle::badgeClass('pending')],
        'menunggu_verifikasi' => ['label' => 'Menunggu Verifikasi', 'class' => \App\Support\StatusStyle::badgeClass('menunggu_verifikasi')],
        'terverifikasi' => ['label' => 'Terverifikasi', 'class' => \App\Support\StatusStyle::badgeClass('terverifikasi')],
        'ditolak' => ['label' => 'Ditolak', 'class' => \App\Support\StatusStyle::badgeClass('ditolak')],
        'dibatalkan' => ['label' => 'Dibatalkan', 'class' => \App\Support\StatusStyle::badgeClass('dibatalkan')],
        'kadaluarsa' => ['label' => 'Kadaluarsa', 'class' => \App\Support\StatusStyle::badgeClass('kadaluarsa')],
    ];
@endphp

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
            Data top up diperbarui real-time
        </span>
    </div>
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Top Up</h2>
        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Verifikasi</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-primary break-words">{{ $stats['menunggu'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">top up bukti belum diverifikasi</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">pending_actions</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-5 border border-gold-accent/25 rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium hover:border-gold-accent transition-colors hero-glow">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Nominal Menunggu</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold break-words">Rp {{ number_format($stats['nominal_menunggu'], 0, ',', '.') }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">belum diterbitkan</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Terverifikasi</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface break-words">Rp {{ number_format($stats['total_terverifikasi'], 0, ',', '.') }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">akumulasi saldo diterbitkan</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">task_alt</span>
            </div>
        </div>
    </section>

    <section data-table-scope data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Top Up Saldo</h2>
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
                <button type="button" data-chip="menunggu_verifikasi" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu ({{ $stats['menunggu'] }})</button>
                <button type="button" data-chip="pending" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Belum Bayar ({{ $stats['pending'] }})</button>
                <button type="button" data-chip="terverifikasi" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Terverifikasi ({{ $stats['terverifikasi'] }})</button>
                <button type="button" data-chip="ditolak" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Ditolak ({{ $stats['ditolak'] }})</button>
                <button type="button" data-chip="dibatalkan" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dibatalkan ({{ $stats['dibatalkan'] }})</button>
                <button type="button" data-chip="kadaluarsa" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Kadaluarsa ({{ $stats['kadaluarsa'] }})</button>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input id="topup-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama customer, email, atau nomor top up..." />
                <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent shrink-0 whitespace-nowrap">
                    <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                    <span id="result-count">{{ $topups->count() }}</span> top up
                </span>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[850px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                        <th class="px-6 py-4 w-12 text-center text-[10px] font-semibold tracking-widest">No.</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Detail Top Up</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Metode</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Bukti</th>
                        <th class="px-6 py-4 text-center text-[10px] font-semibold tracking-widest">Status</th>
                        <th class="px-6 py-4 text-center text-[10px] font-semibold tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($topups as $tp)
                        @php
                            $badge = $badgeMap[$tp->status] ?? $badgeMap['pending'];
                            $initial = strtoupper(substr($tp->user?->nama_lengkap ?? ($tp->user?->email ?? '?'), 0, 2));
                            $proof = $tp->payment?->proofs->last();
                            $searchData = strtolower(($tp->user?->nama_lengkap ?? '').' '.($tp->user?->email ?? '').' '.$tp->customer_topup_id);
                            $akunTujuan = $tp->payment?->account;
$metodeTujuan = $tp->payment?->paymentMethod?->nama_metode ?? '-';
                            $metodeTujuan = $akunTujuan?->nama ? $metodeTujuan . ' — ' . $akunTujuan->nama : $metodeTujuan;
                            $rekBank = $akunTujuan?->bank?->nama_bank ?? '';
                            $rekNomor = $akunTujuan?->nomor_rekening ?? '';
                            $rekPemilik = $akunTujuan?->nama_pemilik ?? '';
                            $proofFile = $proof?->file_bukti ?? '';
                            $proofUrl = $proofFile ? asset('storage/' . ltrim($proofFile, '/')) : '';
                            $proofIsImage = $proofFile ? in_array(strtolower(pathinfo($proofFile, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) : false;
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors group"
                            data-table-row data-status="{{ $tp->status }}" data-search="{{ $searchData }}"
                            data-id="{{ $tp->customer_topup_id }}" data-customer="{{ $tp->user?->nama_lengkap ?? $tp->user?->email ?? '-' }}" data-nominal="{{ number_format((float) $tp->jumlah, 0, ',', '.') }}"
                            data-metode="{{ $metodeTujuan }}" data-rek-bank="{{ $rekBank }}" data-rek-nomor="{{ $rekNomor }}" data-rek-pemilik="{{ $rekPemilik }}"
                            data-bukti-url="{{ $proofUrl }}" data-bukti-is-image="{{ $proofIsImage ? 1 : 0 }}">
                            <td class="p-6 text-center text-on-surface-variant font-mono row-num"></td>
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">{{ $initial }}</div>
                                    <div class="min-w-0">
                                        <p class="font-title-md text-title-md text-primary truncate">{{ $tp->user?->nama_lengkap ?? '-' }}</p>
                                        <p class="text-on-surface-variant truncate">{{ $tp->user?->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <p class="font-title-md text-title-md text-gold-accent">Rp {{ number_format((float) $tp->jumlah, 0, ',', '.') }}</p>
                                <p class="text-on-surface-variant">#{{ $tp->customer_topup_id }} • {{ $tp->created_at?->translatedFormat('d M Y, H:i') }}</p>
                                @if ($tp->dibayar_pada)
                                    <p class="text-emerald-600 text-xs">Diterbitkan {{ \Carbon\Carbon::parse($tp->dibayar_pada)->translatedFormat('d M Y, H:i') }}</p>
                                @endif
                            </td>
                            <td class="p-6">
                                <p class="text-primary">{{ $tp->payment?->paymentMethod?->nama_metode ?? '-' }}</p>
                                <p class="text-on-surface-variant">{{ $tp->payment?->account?->nama ?? '' }}</p>
                            </td>
                            <td class="p-6">
                                @if ($proof)
                                    <a href="{{ asset('storage/' . ltrim($proof->file_bukti, '/')) }}" target="_blank" rel="noopener"
                                        class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-secondary hover:underline">
                                        <span class="material-symbols-outlined text-[13px]">visibility</span>Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-on-surface-variant text-xs">&mdash;</span>
                                @endif
                            </td>
                            <td class="p-6 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded {{ $badge['class'] }} text-xs uppercase">{{ $badge['label'] }}</span>
                            </td>
                            <td class="p-6 text-right">
                                @if ($tp->status === 'menunggu_verifikasi')
                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick="openRejectDialog(this.closest('tr'))"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase tracking-wider rounded-full shadow-sm hover:bg-error/20 hover:shadow hover:-translate-y-px active:translate-y-0 transition-all duration-200">
                                            <span class="material-symbols-outlined text-[14px] leading-none">block</span>
                                            Tolak
                                        </button>
                                        <button type="button" onclick="openApproveDialog(this.closest('tr'))"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-wider rounded-full border border-deep-onyx shadow-sm hover:shadow-md hover:-translate-y-px hover:bg-black active:translate-y-0 transition-all duration-200 btn-premium">
                                            <span class="material-symbols-outlined text-[14px] leading-none">task_alt</span>
                                            Setujui
                                        </button>
                                    </div>
                                @else
                                    <span class="text-on-surface-variant text-xs uppercase">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-12 text-center text-on-surface-variant">Tidak ada top up tercatat.</td></tr>
                    @endforelse
                    <tr id="empty-search" class="hidden">
                        <td colspan="7" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada top up yang cocok.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile -->
        <div class="md:hidden grid grid-cols-1 gap-gutter" id="mobile-list">
            @forelse ($topups as $tp)
                @php
                    $badge = $badgeMap[$tp->status] ?? $badgeMap['pending'];
                    $initial = strtoupper(substr($tp->user?->nama_lengkap ?? ($tp->user?->email ?? '?'), 0, 2));
                    $proof = $tp->payment?->proofs->last();
                    $akunTujuan = $tp->payment?->account;
$metodeTujuan = $tp->payment?->paymentMethod?->nama_metode ?? '-';
                    $metodeTujuan = $akunTujuan?->nama ? $metodeTujuan . ' — ' . $akunTujuan->nama : $metodeTujuan;
                    $rekBank = $akunTujuan?->bank?->nama_bank ?? '';
                    $rekNomor = $akunTujuan?->nomor_rekening ?? '';
                    $rekPemilik = $akunTujuan?->nama_pemilik ?? '';
                    $proofFile = $proof?->file_bukti ?? '';
                    $proofUrl = $proofFile ? asset('storage/' . ltrim($proofFile, '/')) : '';
                    $proofIsImage = $proofFile ? in_array(strtolower(pathinfo($proofFile, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'], true) : false;
                @endphp
                <article data-table-row data-status="{{ $tp->status }}" data-search="{{ strtolower(($tp->user?->nama_lengkap ?? '').' '.($tp->user?->email ?? '').' '.$tp->customer_topup_id) }}"
                    data-id="{{ $tp->customer_topup_id }}" data-customer="{{ $tp->user?->nama_lengkap ?? $tp->user?->email ?? '-' }}" data-nominal="{{ number_format((float) $tp->jumlah, 0, ',', '.') }}"
                    data-metode="{{ $metodeTujuan }}" data-rek-bank="{{ $rekBank }}" data-rek-nomor="{{ $rekNomor }}" data-rek-pemilik="{{ $rekPemilik }}"
                    data-bukti-url="{{ $proofUrl }}" data-bukti-is-image="{{ $proofIsImage ? 1 : 0 }}" class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">payments</span>
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">{{ $initial }}</div>
                            <div class="min-w-0">
                                <p class="font-title-md text-title-md text-primary truncate">{{ $tp->user?->nama_lengkap ?? '-' }}</p>
                                <p class="text-on-surface-variant truncate">{{ $tp->user?->email ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded {{ $badge['class'] }} text-xs uppercase shrink-0">{{ $badge['label'] }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="font-title-md text-title-md text-gold-accent">Rp {{ number_format((float) $tp->jumlah, 0, ',', '.') }}</p>
                            <p class="text-on-surface-variant text-xs">#{{ $tp->customer_topup_id }} • {{ $tp->created_at?->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                            @if ($tp->status === 'menunggu_verifikasi')
                                <div class="flex gap-2">
                                    <button type="button" onclick="openRejectDialog(this.closest('article'))"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase tracking-wider rounded-full hover:bg-error/20 transition-all duration-200">
                                        <span class="material-symbols-outlined text-[14px] leading-none">block</span>Tolak
                                    </button>
                                    <button type="button" onclick="openApproveDialog(this.closest('article'))"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-wider rounded-full border border-deep-onyx hover:bg-black transition-all duration-200 btn-premium">
                                        <span class="material-symbols-outlined text-[14px] leading-none">task_alt</span>Setujui
                                    </button>
                                </div>
                            @endif
                            @if ($proof)
                                <a href="{{ asset('storage/' . ltrim($proof->file_bukti, '/')) }}" target="_blank" rel="noopener"
                                    class="mt-2 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-secondary hover:underline">
                                    <span class="material-symbols-outlined text-[13px]">visibility</span>Lihat Bukti
                                </a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-12">Tidak ada top up tercatat.</p>
            @endforelse
        </div>
    </section>
</div>

<!-- Approve Dialog -->
<div class="hidden fixed inset-0 z-[60] flex items-start justify-center overflow-y-auto p-4 bg-black/50 backdrop-blur-sm" id="approve-dialog">
    <form method="POST" action="" id="approve-form" onsubmit="hideDialog('approve-dialog')" class="my-auto w-full max-w-md">
        @csrf
        <div class="bg-surface-container-lowest border border-gold-accent/25 p-6 max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-gold-accent text-[28px]">task_alt</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4 text-center">Konfirmasi Persetujuan Top Up</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-5 text-center">Saldo sebesar <span id="approve-nominal" class="font-title-md text-gold-accent">-</span> akan diterbitkan ke wallet <span id="approve-customer" class="font-bold text-on-surface">-</span>.</p>
            <div class="mb-6 space-y-3 rounded-xl border border-muted-border bg-surface-container-low p-4">
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Detail Transfer</p>
                <div class="flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px] mt-0.5">account_balance_wallet</span>
                    <div class="min-w-0">
                        <p class="text-on-surface-variant text-xs">Metode Pembayaran</p>
                        <p id="approve-metode" class="font-bold text-on-surface break-words">-</p>
                    </div>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px] mt-0.5">payments</span>
                    <div class="min-w-0">
                        <p class="text-on-surface-variant text-xs">Tujuan Transfer Customer</p>
                        <p id="approve-rekening" class="font-bold text-on-surface break-words font-mono">-</p>
                        <p id="approve-pemilik" class="text-on-surface-variant text-xs"></p>
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant mb-2">Bukti Transfer Customer</p>
                <img id="approve-bukti-img" src="" alt="Bukti transfer customer" class="hidden w-full max-h-72 object-contain rounded-lg border border-muted-border bg-black/5 cursor-zoom-in" onclick="openBuktiLightbox()" />
                <p id="approve-bukti-pdf" class="hidden flex-col items-center justify-center gap-2 rounded-lg border border-muted-border bg-surface-container-low p-6 text-center text-on-surface-variant text-xs">
                    <span class="material-symbols-outlined text-[28px] leading-none">picture_as_pdf</span>
                    Bukti berupa PDF &mdash; file tidak dipratinjau.
                </p>
                <p id="approve-bukti-empty" class="hidden flex-col items-center justify-center gap-2 rounded-lg border border-muted-border bg-surface-container-low p-6 text-center text-on-surface-variant text-xs">
                    <span class="material-symbols-outlined text-[28px] leading-none">image_not_supported</span>
                    Customer belum mengunggah bukti transfer.
                </p>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" class="inline-flex items-center gap-1.5 border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase tracking-wider rounded-full hover:bg-surface-container transition-colors" onclick="hideDialog('approve-dialog')"><span class="material-symbols-outlined text-[16px] leading-none">close</span>Batal</button>
                <button type="submit" id="approve-submit" class="inline-flex items-center gap-1.5 bg-deep-onyx text-on-primary px-6 py-3 font-label-sm text-label-sm uppercase tracking-wider rounded-full border border-deep-onyx shadow-sm hover:shadow-md hover:-translate-y-px hover:bg-black transition-all duration-200 btn-premium"><span class="material-symbols-outlined text-[16px] leading-none">task_alt</span>Terbitkan Saldo</button>
            </div>
        </div>
    </form>
</div>

<!-- Bukti Lightbox -->
<div class="hidden fixed inset-0 z-[999] flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm" id="bukti-lightbox" onclick="if(event.target===this){closeBuktiLightbox();}">
    <button type="button" title="Tutup" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 text-white hover:bg-white/20 flex items-center justify-center transition-colors" onclick="event.stopPropagation(); closeBuktiLightbox();">
        <span class="material-symbols-outlined text-[24px]">close</span>
    </button>
    <img id="bukti-lightbox-img" src="" alt="Bukti transfer customer" class="max-h-[85vh] max-w-full object-contain rounded-lg shadow-2xl" onclick="event.stopPropagation()" />
</div>

<!-- Reject Dialog -->
<div class="hidden fixed inset-0 z-[60] flex items-start justify-center overflow-y-auto p-4 bg-black/50 backdrop-blur-sm" id="reject-dialog">
    <form method="POST" action="" id="reject-form" onsubmit="hideDialog('reject-dialog')" class="my-auto w-full max-w-md">
        @csrf
        <div class="bg-surface-container-lowest border border-error/25 p-6 max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-error mb-4 text-center">Tolak Top Up</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-4 text-center">Anda yakin ingin menolak top up dari <span id="reject-customer" class="font-bold text-on-surface">-</span>?</p>
            <div class="mb-6">
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Alasan Penolakan</label>
                <textarea name="alasan" required minlength="10" maxlength="1000"
                    class="w-full border border-muted-border bg-surface-container-low p-3 font-body-md text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary h-24"
                    placeholder="Tulis alasan... (minimal 10 karakter)"></textarea>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" class="inline-flex items-center gap-1.5 border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase tracking-wider rounded-full hover:bg-surface-container transition-colors" onclick="hideDialog('reject-dialog')"><span class="material-symbols-outlined text-[16px] leading-none">close</span>Batal</button>
                <button type="submit" class="inline-flex items-center gap-1.5 bg-error text-on-error px-6 py-3 font-label-sm text-label-sm uppercase tracking-wider rounded-full shadow-sm hover:shadow-md hover:-translate-y-px hover:opacity-90 transition-all duration-200"><span class="material-symbols-outlined text-[16px] leading-none">block</span>Tolak Top Up</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const topupUrls = {
        setujui: (id) => '{{ route('superadmin.verifikasi-topup.setujui', ':id:') }}'.replace(':id:', id),
        tolak: (id) => '{{ route('superadmin.verifikasi-topup.tolak', ':id:') }}'.replace(':id:', id)
    };

    function openApproveDialog(row) {
        document.getElementById('approve-customer').textContent = row.getAttribute('data-customer') || '-';
        document.getElementById('approve-nominal').textContent = 'Rp ' + (row.getAttribute('data-nominal') || '-');
        document.getElementById('approve-form').action = topupUrls.setujui(row.getAttribute('data-id'));
        document.getElementById('approve-metode').textContent = row.getAttribute('data-metode') || '-';
        const rekBank = row.getAttribute('data-rek-bank') || '';
        const rekNomor = row.getAttribute('data-rek-nomor') || '';
        document.getElementById('approve-rekening').textContent = [rekBank, rekNomor].filter(Boolean).join(' ') || '-';
        const rekPemilik = row.getAttribute('data-rek-pemilik') || '';
        document.getElementById('approve-pemilik').textContent = rekPemilik ? 'a.n. ' + rekPemilik : '';
        const img = document.getElementById('approve-bukti-img');
        const pdf = document.getElementById('approve-bukti-pdf');
        const empty = document.getElementById('approve-bukti-empty');
        img.classList.add('hidden');
        pdf.classList.add('hidden');
        empty.classList.add('hidden');
        const buktiUrl = row.getAttribute('data-bukti-url') || '';
        const buktiIsImage = row.getAttribute('data-bukti-is-image') === '1';
        if (buktiUrl && buktiIsImage) {
            img.src = buktiUrl;
            img.classList.remove('hidden');
        } else if (buktiUrl) {
            pdf.classList.remove('hidden');
        } else {
            empty.classList.remove('hidden');
        }
        showDialog('approve-dialog');
    }

    function openRejectDialog(row) {
        document.getElementById('reject-customer').textContent = row.getAttribute('data-customer') || '-';
        document.getElementById('reject-form').action = topupUrls.tolak(row.getAttribute('data-id'));
        document.querySelector('#reject-form textarea').value = '';
        showDialog('reject-dialog');
    }

    function showDialog(id) {
        const el = document.getElementById(id);
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function hideDialog(id) {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function openBuktiLightbox() {
        const src = document.getElementById('approve-bukti-img')?.src;
        if (!src) return;
        const lb = document.getElementById('bukti-lightbox');
        const lbImg = document.getElementById('bukti-lightbox-img');
        lbImg.src = src;
        lb.classList.remove('hidden');
        lb.classList.add('flex');
    }

    function closeBuktiLightbox() {
        const lb = document.getElementById('bukti-lightbox');
        lb.classList.add('hidden');
        lb.classList.remove('flex');
        document.getElementById('bukti-lightbox-img').src = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeBuktiLightbox(); hideDialog('approve-dialog'); hideDialog('reject-dialog'); }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-table-scope]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('tr[data-table-row]'));
        const cards = Array.from(scope.querySelectorAll('article[data-table-row]'));
        const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
        const searchInput = document.getElementById('topup-search');
        const clearBtn = document.getElementById('clear-search');
        const countEl = document.getElementById('result-count');
        const emptySearch = document.getElementById('empty-search');
        const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
        const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

        let activeStatus = 'semua';

        function applyFilter() {
            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;

            const each = (el) => {
                const matchStatus = activeStatus === 'semua' || el.getAttribute('data-status') === activeStatus;
                const matchSearch = !term || (el.getAttribute('data-search') || '').includes(term);
                const show = matchStatus && matchSearch;
                el.classList.toggle('hidden', !show);
                return show;
            };

            rows.forEach((row) => {
                if (each(row)) {
                    visible++;
                    const num = row.querySelector('.row-num');
                    if (num) num.textContent = visible;
                }
            });
            cards.forEach(each);

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