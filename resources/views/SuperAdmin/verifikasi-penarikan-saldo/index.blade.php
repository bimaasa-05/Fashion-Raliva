@extends('layouts.superadmin')

@section('title', 'Verifikasi Penarikan Saldo')

@section('header-title', 'Penarikan Saldo')
@section('header-badge', 'Super Admin')

@section('header-subtitle', 'Verifikasi pengajuan penarikan saldo akun Customer ke bank/e-wallet.')

@php
    $badgeMap = [
        'pending' => ['label' => 'Menunggu', 'class' => 'bg-surface-container-high text-on-surface border-outline-variant'],
        'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'dibayar' => ['label' => 'Dibayar', 'class' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20'],
        'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-error/10 text-error border-error/20'],
        'dibatalkan' => ['label' => 'Dibatalkan', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
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
            Data penarikan diperbarui real-time
        </span>
    </div>
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Penarikan</h2>
        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-gutter">
            <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Verifikasi</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-primary break-words">{{ $stats['pending'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">pengajuan belum diproses</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">pending_actions</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-5 border border-gold-accent/25 rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium hover:border-gold-accent transition-colors hero-glow">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Nominal Menunggu (Bersih)</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold break-words">Rp {{ number_format($stats['nominal_menunggu'], 0, ',', '.') }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">belum ditransfer</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Sudah Dibayar</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface break-words">{{ $stats['dibayar'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">pengajuan selesai ditransfer</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">task_alt</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-5 border border-gold-accent/25 rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium hover:border-gold-accent transition-colors hero-glow">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Fee Terkumpul</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold break-words">Rp {{ number_format($stats['fee_terkumpul'], 0, ',', '.') }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">dari penarikan yang dibayar</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">percent</span>
            </div>
        </div>
    </section>

    <section data-table-scope data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Penarikan Saldo</h2>
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
                <button type="button" data-chip="pending" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu ({{ $stats['pending'] }})</button>
                <button type="button" data-chip="disetujui" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Disetujui ({{ $stats['disetujui'] }})</button>
                <button type="button" data-chip="dibayar" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dibayar ({{ $stats['dibayar'] }})</button>
                <button type="button" data-chip="ditolak" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Ditolak ({{ $stats['ditolak'] }})</button>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input id="penarikan-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama customer, email, atau nomor penarikan..." />
                <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent shrink-0 whitespace-nowrap">
                    <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                    <span id="result-count">{{ $penarikans->count() }}</span> penarikan
                </span>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                        <th class="px-6 py-4 w-12 text-center text-[10px] font-semibold tracking-widest">No.</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Customer</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Detail Penarikan</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Tujuan</th>
                        <th class="px-6 py-4 text-center text-[10px] font-semibold tracking-widest">Status</th>
                        <th class="px-6 py-4 text-center text-[10px] font-semibold tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($penarikans as $wd)
                        @php
                            $badge = $badgeMap[$wd->status] ?? $badgeMap['pending'];
                            $initial = strtoupper(substr($wd->user?->nama_lengkap ?? ($wd->user?->email ?? '?'), 0, 2));
                            $tujuan = $wd->tipe_tujuan === 'bank' ? (($wd->bank?->nama_bank ?? 'Bank') . ' • ' . $wd->nomor_tujuan) : (($wd->penyedia ?? 'E-Wallet') . ' • ' . $wd->nomor_tujuan);
                            $tujuanMetode = $wd->tipe_tujuan === 'bank' ? ($wd->bank?->nama_bank ?? 'Bank') : ($wd->penyedia ?? 'E-Wallet');
                            $tujuanNomor = $wd->nomor_tujuan ?? '';
                            $tujuanPemilik = $wd->nama_pemilik ?? '';
                            $searchData = strtolower(($wd->user?->nama_lengkap ?? '').' '.($wd->user?->email ?? '').' '.$wd->customer_withdrawal_id);
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors group"
                            data-table-row data-status="{{ $wd->status }}" data-search="{{ $searchData }}"
                            data-id="{{ $wd->customer_withdrawal_id }}" data-customer="{{ $wd->user?->nama_lengkap ?? $wd->user?->email ?? '-' }}" data-nominal="{{ number_format((float) $wd->jumlah_bersih, 0, ',', '.') }}"
                            data-metode="{{ $tujuanMetode }}" data-tujuan="{{ $tujuanNomor }}" data-pemilik="{{ $tujuanPemilik }}">
                            <td class="p-6 text-center text-on-surface-variant font-mono row-num"></td>
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">{{ $initial }}</div>
                                    <div class="min-w-0">
                                        <p class="font-title-md text-title-md text-primary truncate">{{ $wd->user?->nama_lengkap ?? '-' }}</p>
                                        <p class="text-on-surface-variant truncate">{{ $wd->user?->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <p class="font-title-md text-title-md text-gold-accent">Rp {{ number_format((float) $wd->jumlah_bersih, 0, ',', '.') }}</p>
                                <p class="text-on-surface-variant">#{{ $wd->customer_withdrawal_id }} • {{ $wd->diajukan_pada?->translatedFormat('d M Y, H:i') }}</p>
                                <p class="text-on-surface-variant text-xs">Nominal Rp {{ number_format((float) $wd->jumlah, 0, ',', '.') }} • Fee Rp {{ number_format((float) $wd->fee, 0, ',', '.') }}</p>
                            </td>
                            <td class="p-6">
                                <p class="text-primary">{{ $tujuan }}</p>
                                <p class="text-on-surface-variant">{{ $wd->nama_pemilik ?? '' }}</p>
                                @if ($wd->file_bukti)
                                    <a href="{{ asset('storage/' . ltrim($wd->file_bukti, '/')) }}" target="_blank" rel="noopener"
                                        class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-emerald-600 hover:underline">
                                        <span class="material-symbols-outlined text-[13px]">receipt_long</span>Bukti Transfer
                                    </a>
                                @endif
                            </td>
                            <td class="p-6 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded {{ $badge['class'] }} text-xs uppercase">{{ $badge['label'] }}</span>
                            </td>
                            <td class="p-6 text-right">
                                @if ($wd->status === 'pending')
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
                                @elseif ($wd->status === 'disetujui')
                                    <button type="button" onclick="openPaidDialog(this.closest('tr'))"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-wider rounded-full border border-deep-onyx shadow-sm hover:shadow-md hover:-translate-y-px hover:bg-black active:translate-y-0 transition-all duration-200 btn-premium">
                                        <span class="material-symbols-outlined text-[14px] leading-none">payments</span>
                                        Tandai Dibayar
                                    </button>
                                @else
                                    <span class="text-on-surface-variant text-xs uppercase">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-on-surface-variant">Tidak ada penarikan tercatat.</td></tr>
                    @endforelse
                    <tr id="empty-search" class="hidden">
                        <td colspan="6" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada penarikan yang cocok.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile -->
        <div class="md:hidden grid grid-cols-1 gap-gutter" id="mobile-list">
            @forelse ($penarikans as $wd)
                @php
                    $badge = $badgeMap[$wd->status] ?? $badgeMap['pending'];
                    $initial = strtoupper(substr($wd->user?->nama_lengkap ?? ($wd->user?->email ?? '?'), 0, 2));
                    $tujuan = $wd->tipe_tujuan === 'bank' ? (($wd->bank?->nama_bank ?? 'Bank') . ' • ' . $wd->nomor_tujuan) : (($wd->penyedia ?? 'E-Wallet') . ' • ' . $wd->nomor_tujuan);
                    $tujuanMetode = $wd->tipe_tujuan === 'bank' ? ($wd->bank?->nama_bank ?? 'Bank') : ($wd->penyedia ?? 'E-Wallet');
                    $tujuanNomor = $wd->nomor_tujuan ?? '';
                    $tujuanPemilik = $wd->nama_pemilik ?? '';
                @endphp
                <article data-table-row data-status="{{ $wd->status }}" data-search="{{ strtolower(($wd->user?->nama_lengkap ?? '').' '.($wd->user?->email ?? '').' '.$wd->customer_withdrawal_id) }}"
                    data-id="{{ $wd->customer_withdrawal_id }}" data-customer="{{ $wd->user?->nama_lengkap ?? $wd->user?->email ?? '-' }}" data-nominal="{{ number_format((float) $wd->jumlah_bersih, 0, ',', '.') }}"
                    data-metode="{{ $tujuanMetode }}" data-tujuan="{{ $tujuanNomor }}" data-pemilik="{{ $tujuanPemilik }}" class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">payments</span>
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">{{ $initial }}</div>
                            <div class="min-w-0">
                                <p class="font-title-md text-title-md text-primary truncate">{{ $wd->user?->nama_lengkap ?? '-' }}</p>
                                <p class="text-on-surface-variant truncate">{{ $wd->user?->email ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded {{ $badge['class'] }} text-xs uppercase shrink-0">{{ $badge['label'] }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
<div>
                                    <p class="font-title-md text-title-md text-gold-accent">Rp {{ number_format((float) $wd->jumlah_bersih, 0, ',', '.') }}</p>
                                    <p class="text-on-surface-variant text-xs">#{{ $wd->customer_withdrawal_id }} • {{ $tujuan }}</p>
                                    @if ($wd->file_bukti)
                                        <a href="{{ asset('storage/' . ltrim($wd->file_bukti, '/')) }}" target="_blank" rel="noopener"
                                            class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-emerald-600 hover:underline">
                                            <span class="material-symbols-outlined text-[13px]">receipt_long</span>Bukti Transfer
                                        </a>
                                    @endif
                                </div>
                        <div class="text-right">
                            @if ($wd->status === 'pending')
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
                            @elseif ($wd->status === 'disetujui')
                                <button type="button" onclick="openPaidDialog(this.closest('article'))"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-wider rounded-full border border-deep-onyx hover:bg-black transition-all duration-200 btn-premium">
                                    <span class="material-symbols-outlined text-[14px] leading-none">payments</span>Tandai Dibayar
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-12">Tidak ada penarikan tercatat.</p>
            @endforelse
        </div>
    </section>
</div>

<!-- Approve Dialog -->
<div class="hidden fixed inset-0 z-[60] flex items-start justify-center overflow-y-auto p-4 bg-black/50 backdrop-blur-sm" id="approve-dialog">
    <form method="POST" action="" id="approve-form" enctype="multipart/form-data" onsubmit="hideDialog('approve-dialog')" class="my-auto w-full max-w-md">
        @csrf
        <div class="bg-surface-container-lowest border border-gold-accent/25 p-6 max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-gold-accent text-[28px]">task_alt</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4 text-center">Konfirmasi Persetujuan Penarikan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-5 text-center">Penarikan sebesar <span id="approve-nominal" class="font-title-md text-gold-accent">-</span> untuk <span id="approve-customer" class="font-bold text-on-surface">-</span> akan disetujui dan ditandai dibayar.</p>
            <div class="mb-6 space-y-3 rounded-xl border border-muted-border bg-surface-container-low p-4">
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Detail Transfer</p>
                <div class="flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px] mt-0.5">account_balance_wallet</span>
                    <div class="min-w-0">
                        <p class="text-on-surface-variant text-xs">Metode Tujuan</p>
                        <p id="approve-metode" class="font-bold text-on-surface break-words">-</p>
                    </div>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-on-surface-variant text-[18px] mt-0.5">payments</span>
                    <div class="min-w-0">
                        <p class="text-on-surface-variant text-xs">Nomor Tujuan</p>
                        <p id="approve-tujuan" class="font-bold text-on-surface break-words font-mono">-</p>
                        <p id="approve-pemilik" class="text-on-surface-variant text-xs"></p>
                    </div>
                </div>
            </div>
            <div class="mb-6 space-y-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Bukti Transfer <span class="text-error">*</span></label>
                    <input type="file" name="file_bukti" id="approve-file" required accept=".jpg,.jpeg,.png,.pdf"
                        class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:font-label-sm file:uppercase file:tracking-widest file:cursor-pointer border border-muted-border rounded-lg p-1 focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent" />
                    <p class="text-on-surface-variant text-[11px] mt-2 inline-flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">info</span>Wajib dilampirkan sebagai bukti transparansi (JPG, PNG, atau PDF, maks 5MB).</p>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Deskripsi / No. Referensi (opsional)</label>
                    <input type="text" name="deskripsi_bukti" id="approve-deskripsi" maxlength="1000" placeholder="Contoh: Transfer BCA dari rekening platform Raliva"
                        class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent placeholder-on-surface-variant/50" />
                </div>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" class="inline-flex items-center gap-1.5 border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase tracking-wider rounded-full hover:bg-surface-container transition-colors" onclick="hideDialog('approve-dialog')"><span class="material-symbols-outlined text-[16px] leading-none">close</span>Batal</button>
                <button type="submit" id="approve-submit" disabled class="inline-flex items-center gap-1.5 bg-deep-onyx text-on-primary px-6 py-3 font-label-sm text-label-sm uppercase tracking-wider rounded-full border border-deep-onyx shadow-sm hover:shadow-md hover:-translate-y-px hover:bg-black transition-all duration-200 btn-premium disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none disabled:hover:bg-deep-onyx"><span class="material-symbols-outlined text-[16px] leading-none">task_alt</span>Setujui &amp; Bayar</button>
            </div>
        </div>
    </form>
</div>

<!-- Reject Dialog -->
<div class="hidden fixed inset-0 z-[60] flex items-start justify-center overflow-y-auto p-4 bg-black/50 backdrop-blur-sm" id="reject-dialog">
    <form method="POST" action="" id="reject-form" onsubmit="hideDialog('reject-dialog')" class="my-auto w-full max-w-md">
        @csrf
        <div class="bg-surface-container-lowest border border-error/25 p-6 max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-error mb-4 text-center">Tolak Penarikan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-4 text-center">Anda yakin ingin menolak penarikan dari <span id="reject-customer" class="font-bold text-on-surface">-</span>? Saldo akan dikembalikan.</p>
            <div class="mb-6">
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Alasan Penolakan</label>
                <textarea name="alasan" required minlength="10" maxlength="1000"
                    class="w-full border border-muted-border bg-surface-container-low p-3 font-body-md text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary h-24"
                    placeholder="Tulis alasan... (minimal 10 karakter)"></textarea>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" class="inline-flex items-center gap-1.5 border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase tracking-wider rounded-full hover:bg-surface-container transition-colors" onclick="hideDialog('reject-dialog')"><span class="material-symbols-outlined text-[16px] leading-none">close</span>Batal</button>
                <button type="submit" class="inline-flex items-center gap-1.5 bg-error text-on-error px-6 py-3 font-label-sm text-label-sm uppercase tracking-wider rounded-full shadow-sm hover:shadow-md hover:-translate-y-px hover:opacity-90 transition-all duration-200"><span class="material-symbols-outlined text-[16px] leading-none">block</span>Tolak Penarikan</button>
            </div>
        </div>
    </form>
</div>

<!-- Paid Dialog -->
<div class="hidden fixed inset-0 z-[60] flex items-start justify-center overflow-y-auto p-4 bg-black/50 backdrop-blur-sm" id="paid-dialog">
    <form method="POST" action="" id="paid-form" onsubmit="hideDialog('paid-dialog')" class="my-auto">
        @csrf
        <div class="bg-surface-container-lowest border border-gold-accent/25 p-6 max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-gold-accent text-[28px]">payments</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4 text-center">Tandai Sudah Dibayar</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-8 text-center">Pastikan dana <span id="paid-nominal" class="font-title-md text-gold-accent">-</span> sudah ditransfer ke <span id="paid-customer" class="font-bold text-on-surface">-</span>.</p>
            <div class="flex justify-end gap-4">
                <button type="button" class="inline-flex items-center gap-1.5 border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase tracking-wider rounded-full hover:bg-surface-container transition-colors" onclick="hideDialog('paid-dialog')"><span class="material-symbols-outlined text-[16px] leading-none">close</span>Batal</button>
                <button type="submit" class="inline-flex items-center gap-1.5 bg-deep-onyx text-on-primary px-6 py-3 font-label-sm text-label-sm uppercase tracking-wider rounded-full border border-deep-onyx shadow-sm hover:shadow-md hover:-translate-y-px hover:bg-black transition-all duration-200 btn-premium"><span class="material-symbols-outlined text-[16px] leading-none">payments</span>Sudah Dibayar</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const penarikanUrls = {
        setujui: (id) => '{{ route('superadmin.verifikasi-penarikan-saldo.setujui', ':id:') }}'.replace(':id:', id),
        tolak: (id) => '{{ route('superadmin.verifikasi-penarikan-saldo.tolak', ':id:') }}'.replace(':id:', id),
        dibayar: (id) => '{{ route('superadmin.verifikasi-penarikan-saldo.tandai-dibayar', ':id:') }}'.replace(':id:', id)
    };

    function openApproveDialog(row) {
        document.getElementById('approve-customer').textContent = row.getAttribute('data-customer') || '-';
        document.getElementById('approve-nominal').textContent = 'Rp ' + (row.getAttribute('data-nominal') || '-');
        document.getElementById('approve-form').action = penarikanUrls.setujui(row.getAttribute('data-id'));
        document.getElementById('approve-metode').textContent = row.getAttribute('data-metode') || '-';
        document.getElementById('approve-tujuan').textContent = row.getAttribute('data-tujuan') || '-';
        document.getElementById('approve-pemilik').textContent = row.getAttribute('data-pemilik') ? 'a.n. ' + row.getAttribute('data-pemilik') : '';
        document.getElementById('approve-file').value = '';
        document.getElementById('approve-deskripsi').value = '';
        document.getElementById('approve-submit').disabled = true;
        showDialog('approve-dialog');
    }

    document.getElementById('approve-file')?.addEventListener('change', function () {
        const submit = document.getElementById('approve-submit');
        if (submit) submit.disabled = !this.files.length;
    });

    function openRejectDialog(row) {
        document.getElementById('reject-customer').textContent = row.getAttribute('data-customer') || '-';
        document.getElementById('reject-form').action = penarikanUrls.tolak(row.getAttribute('data-id'));
        document.querySelector('#reject-form textarea').value = '';
        showDialog('reject-dialog');
    }

    function openPaidDialog(row) {
        document.getElementById('paid-customer').textContent = row.getAttribute('data-customer') || '-';
        document.getElementById('paid-nominal').textContent = 'Rp ' + (row.getAttribute('data-nominal') || '-');
        document.getElementById('paid-form').action = penarikanUrls.dibayar(row.getAttribute('data-id'));
        showDialog('paid-dialog');
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

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { hideDialog('approve-dialog'); hideDialog('reject-dialog'); hideDialog('paid-dialog'); }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-table-scope]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('tr[data-table-row], article[data-table-row]'));
        const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
        const searchInput = document.getElementById('penarikan-search');
        const clearBtn = document.getElementById('clear-search');
        const countEl = document.getElementById('result-count');
        const emptySearch = document.getElementById('empty-search');
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
