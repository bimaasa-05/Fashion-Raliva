@extends('layouts.superadmin')

@section('title', 'Pencairan Dana')

@section('header-title', 'Pencairan Dana')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Verifikasi dan setujui pengajuan pencairan dana Owner.')

@php
    $badgeMap = [
        'pending' => ['label' => 'Menunggu', 'class' => \App\Support\StatusStyle::badgeClass('pending')],
        'disetujui' => ['label' => 'Disetujui', 'class' => \App\Support\StatusStyle::badgeClass('disetujui')],
        'dibayar' => ['label' => 'Dibayar', 'class' => \App\Support\StatusStyle::badgeClass('dibayar')],
        'ditolak' => ['label' => 'Ditolak', 'class' => \App\Support\StatusStyle::badgeClass('ditolak')],
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
            Data pencairan diperbarui real-time
        </span>
    </div>
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Pengajuan</h2>
        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pengajuan Menunggu</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-primary break-words">{{ $stats['pending'] }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">menunggu verifikasi &amp; persetujuan</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">pending_actions</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-5 border border-gold-accent/25 rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium hover:border-gold-accent transition-colors hero-glow">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Nominal Menunggu</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold break-words">Rp {{ number_format($stats['nominal_menunggu'], 0, ',', '.') }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">diajukan Owner</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Disetujui</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface break-words">Rp {{ number_format($stats['total_semua'], 0, ',', '.') }}</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">pencairan diproses / dibayar</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">task_alt</span>
            </div>
        </div>
    </section>

    <section data-table-scope data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Pengajuan Pencairan</h2>
            <button type="button" data-filter-toggle class="md:hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[18px]">tune</span>
                Filter
                <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
            </button>
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
                    <button type="button" data-chip="pending" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menunggu ({{ $stats['pending'] }})</button>
                    <button type="button" data-chip="disetujui" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Disetujui ({{ $stats['disetujui'] }})</button>
                    <button type="button" data-chip="dibayar" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dibayar ({{ $stats['dibayar'] }})</button>
                    <button type="button" data-chip="ditolak" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Ditolak ({{ $stats['ditolak'] }})</button>
                </div>
            </div>

            <!-- Search + Result Count -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input id="penarikan-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama toko, nama pemilik, atau bank..." />
                    <button type="button" id="clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent shrink-0 whitespace-nowrap">
                    <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                    <span id="result-count">{{ $withdrawals->count() }}</span> pengajuan
                </span>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[850px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                        <th class="px-6 py-4 w-12 text-center text-[10px] font-semibold tracking-widest">No.</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Toko / Pemilik</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Detail Pengajuan</th>
                        <th class="px-6 py-4 text-[10px] font-semibold tracking-widest">Info Tujuan</th>
                        <th class="px-6 py-4 text-center text-[10px] font-semibold tracking-widest">Status</th>
                        <th class="px-6 py-4 text-center text-[10px] font-semibold tracking-widest">Dibayar</th>
                        <th class="px-6 py-4 text-center text-[10px] font-semibold tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($withdrawals as $w)
                        @php
                            $badge = $badgeMap[$w->status] ?? $badgeMap['pending'];
                            $initialStore = $w->store?->nama_toko;
                            $initial = strtoupper(substr(collect(preg_split('/\s+/', trim($initialStore ?? '')))->map(fn ($k) => mb_substr($k, 0, 1))->implode(''), 0, 2)) ?: '?';
                            $tujuanMetode = $w->tujuan_penyedia ?? '-';
                            $tujuanNomor = $w->tujuan_nomor ?? '';
                            $tujuanPemilik = $w->bankAccount?->nama_pemilik ?? '';
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors group"
                            data-table-row data-status="{{ $w->status }}" data-search="{{ strtolower(($w->store?->nama_toko ?? '').' '.($w->store?->owner?->nama_lengkap ?? '').' '.($w->tujuan_penyedia)) }}"
                            data-id="{{ $w->withdrawal_id }}" data-nama="{{ $w->store?->nama_toko ?? '-' }}" data-jumlah="{{ number_format((float) $w->jumlah, 0, ',', '.') }}"
                            data-metode="{{ $tujuanMetode }}" data-tujuan="{{ $tujuanNomor }}" data-pemilik="{{ $tujuanPemilik }}">
                            <td class="p-6 text-center text-on-surface-variant font-mono row-num"></td>
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">{{ $initial }}</div>
                                    <div>
                                        <p class="font-title-md text-title-md text-primary">{{ $w->store?->nama_toko ?? '-' }}</p>
                                        <p class="text-on-surface-variant">{{ $w->store?->owner?->nama_lengkap ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <p class="font-title-md text-title-md text-gold-accent">Rp {{ number_format((float) $w->jumlah, 0, ',', '.') }}</p>
                                <p class="text-on-surface-variant">{{ $w->diajukan_pada?->translatedFormat('d M Y') }}</p>
                            </td>
                            <td class="p-6">
                                <p class="text-primary">{{ $w->tujuan_penyedia ?: '-' }}</p>
                                <p class="text-on-surface-variant">**** **** {{ substr($w->tujuan_nomor, -4) }}</p>
                            </td>
                            <td class="p-6 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded {{ $badge['class'] }} text-xs uppercase">{{ $badge['label'] }}</span>
                            </td>
                            <td class="p-6 text-center">
                                @if ($w->dibayar_pada)
                                    <p class="text-on-surface-variant text-xs">{{ \Carbon\Carbon::parse($w->dibayar_pada)->locale('id')->diffForHumans() }}</p>
                                @else
                                    <span class="text-on-surface-variant text-xs">-</span>
                                @endif
                                @if ($w->file_bukti)
                                    <a href="{{ asset('storage/' . $w->file_bukti) }}" target="_blank" rel="noopener"
                                        class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-secondary hover:underline">
                                        <span class="material-symbols-outlined text-[13px]">visibility</span>Lihat Bukti
                                    </a>
                                @endif
                            </td>
                            <td class="p-6 text-right">
                                @if ($w->status === 'pending')
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
                                @elseif ($w->status === 'disetujui')
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
                        <tr><td colspan="7" class="py-12 text-center text-on-surface-variant">Tidak ada pengajuan pencairan tercatat.</td></tr>
                    @endforelse
                    <tr id="empty-search" class="hidden">
                        <td colspan="7" class="p-8 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pengajuan yang cocok.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile: kartu per pengajuan -->
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($withdrawals as $w)
                @php
                    $badge = $badgeMap[$w->status] ?? $badgeMap['pending'];
                    $initialStore = $w->store?->nama_toko;
                    $initial = strtoupper(substr(collect(preg_split('/\s+/', trim($initialStore ?? '')))->map(fn ($k) => mb_substr($k, 0, 1))->implode(''), 0, 2)) ?: '?';
                    $tujuanMetode = $w->tujuan_penyedia ?? '-';
                    $tujuanNomor = $w->tujuan_nomor ?? '';
                    $tujuanPemilik = $w->bankAccount?->nama_pemilik ?? '';
                @endphp
                <article data-table-row data-status="{{ $w->status }}" data-search="{{ strtolower(($w->store?->nama_toko ?? '').' '.($w->store?->owner?->nama_lengkap ?? '').' '.($w->tujuan_penyedia)) }}" data-id="{{ $w->withdrawal_id }}" data-nama="{{ $w->store?->nama_toko ?? '-' }}" data-jumlah="{{ number_format((float) $w->jumlah, 0, ',', '.') }}"
                    data-metode="{{ $tujuanMetode }}" data-tujuan="{{ $tujuanNomor }}" data-pemilik="{{ $tujuanPemilik }}" class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">{{ $initial }}</div>
                            <div class="min-w-0">
                                <p class="font-title-md text-title-md text-primary truncate">{{ $w->store?->nama_toko ?? '-' }}</p>
                                <p class="text-on-surface-variant truncate">{{ $w->store?->owner?->nama_lengkap ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded {{ $badge['class'] }} text-[10px] font-bold uppercase shrink-0">{{ $badge['label'] }}</span>
                    </div>

                    <dl class="space-y-2 font-body-md text-sm mb-4">
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Jumlah</dt>
                            <dd class="font-title-md text-gold-accent text-right">Rp {{ number_format((float) $w->jumlah, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Diajukan</dt>
                            <dd class="text-on-surface text-right">{{ $w->diajukan_pada?->translatedFormat('d M Y') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Tujuan</dt>
                            <dd class="text-on-surface text-right">{{ $w->tujuan_penyedia ?: '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Nomor</dt>
                            <dd class="font-mono text-on-surface-variant text-right">**** **** {{ substr($w->tujuan_nomor, -4) }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Dibayar</dt>
                            <dd class="text-on-surface text-right">
                                {{ $w->dibayar_pada ? \Carbon\Carbon::parse($w->dibayar_pada)->locale('id')->diffForHumans() : '-' }}
                                @if ($w->file_bukti)
                                    <a href="{{ asset('storage/' . $w->file_bukti) }}" target="_blank" rel="noopener"
                                        class="mt-1 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-secondary hover:underline">
                                        <span class="material-symbols-outlined text-[13px]">visibility</span>Lihat Bukti
                                    </a>
                                @endif
                            </dd>
                        </div>
                    </dl>

                    @if ($w->status === 'pending')
                        <div class="flex gap-gutter">
                            <button type="button" onclick="openRejectDialog(this.closest('article'))" class="flex-1 min-h-11 inline-flex items-center justify-center gap-1.5 border border-error/20 bg-error/10 text-error font-label-sm text-[10px] uppercase tracking-wider rounded-full shadow-sm hover:bg-error/20 hover:shadow hover:-translate-y-px transition-all duration-200">
                                <span class="material-symbols-outlined text-[16px] leading-none">block</span>Tolak
                            </button>
                            <button type="button" onclick="openApproveDialog(this.closest('article'))" class="flex-1 min-h-11 inline-flex items-center justify-center gap-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-wider rounded-full border border-deep-onyx shadow-sm hover:shadow-md hover:-translate-y-px hover:bg-black transition-all duration-200 btn-premium">
                                <span class="material-symbols-outlined text-[16px] leading-none">task_alt</span>Setujui
                            </button>
                        </div>
                    @elseif ($w->status === 'disetujui')
                        <button type="button" onclick="openPaidDialog(this.closest('article'))" class="w-full min-h-11 inline-flex items-center justify-center gap-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-wider rounded-full border border-deep-onyx shadow-sm hover:shadow-md hover:-translate-y-px hover:bg-black transition-all duration-200 btn-premium">
                            <span class="material-symbols-outlined text-[16px] leading-none">payments</span>Tandai Dibayar
                        </button>
                    @else
                        <span class="block text-center text-on-surface-variant text-xs uppercase py-3">&mdash;</span>
                    @endif
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Tidak ada pengajuan pencairan tercatat.</p>
            @endforelse
            <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada pengajuan yang cocok.</p>
        </div>
    </section>
</div>

<!-- Dialogs -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'paid-dialog',
    'dataModal' => true,
    'zIndex' => 60,
    'icon' => 'local_atm',
    'title' => 'Tandai Sudah Dibayar',
])
    <form method="POST" action="" id="paid-form" enctype="multipart/form-data" onsubmit="hideDialog('paid-dialog')">
        @csrf
        <p class="font-body-md text-body-md text-on-surface-variant mb-2">Konfirmasikan bahwa dana sebesar <span id="paid-nominal" class="font-title-md text-gold-accent">-</span> untuk <span id="paid-toko" class="font-bold text-on-surface">-</span> telah dikirim ke rekening tujuan.</p>
        <div class="mt-5 space-y-4">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Bukti Transfer <span class="text-error">*</span></label>
                <input type="file" name="file_bukti" required accept=".jpg,.jpeg,.png,.pdf"
                    class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:cursor-pointer" />
                <p class="text-on-surface-variant text-[11px] mt-2 inline-flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">info</span>Wajib dilampirkan sebagai bukti transparansi (JPG, PNG, atau PDF, maks 5MB).</p>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Deskripsi / No. Referensi (opsional)</label>
                <input type="text" name="deskripsi_bukti" maxlength="1000" placeholder="Contoh: Transfer BCA dari rekening platform Raliva"
                    class="w-full border border-muted-border bg-surface-container-low p-3 font-body-md text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" />
            </div>
        </div>
        @slot('footer')
            <div class="flex justify-end gap-4">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost"><span class="material-symbols-outlined text-[16px] leading-none">close</span>Batal</button>
                <button type="submit" form="paid-form" class="btn-modal btn-modal-primary"><span class="material-symbols-outlined text-[16px] leading-none">payments</span>Ya, Sudah Dibayar</button>
            </div>
        @endslot
    </form>
@endcomponent

@component('SuperAdmin.partials.premium-confirm', [
    'id' => 'approve-dialog',
    'dataModal' => true,
    'zIndex' => 60,
    'icon' => 'task_alt',
    'iconBox' => 'bg-gold-accent/20 border-gold-accent/30',
    'iconColor' => 'text-gold-accent',
])
    <form method="POST" action="" id="approve-form" enctype="multipart/form-data" onsubmit="hideDialog('approve-dialog')">
        @csrf
        <div class="p-6">
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Konfirmasi Pencairan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-5 text-center">Anda akan menyetujui pencairan sebesar <span id="approve-nominal" class="font-title-md text-gold-accent">-</span> ke <span id="approve-toko" class="font-bold text-on-surface">-</span>. Saldo toko akan langsung dipotong.
            <div class="space-y-3 rounded-xl border border-muted-border bg-surface-container-low p-4">
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
            <div class="mt-5 space-y-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Bukti Transfer <span class="text-error">*</span></label>
                    <input type="file" name="file_bukti" id="approve-file" required accept=".jpg,.jpeg,.png,.pdf"
                        class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:cursor-pointer" />
                    <p class="text-on-surface-variant text-[11px] mt-2 inline-flex items-center gap-1"><span class="material-symbols-outlined text-[13px]">info</span>Wajib dilampirkan sebagai bukti transparansi (JPG, PNG, atau PDF, maks 5MB).</p>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Deskripsi / No. Referensi (opsional)</label>
                    <input type="text" name="deskripsi_bukti" id="approve-deskripsi" maxlength="1000" placeholder="Contoh: Transfer BCA dari rekening platform Raliva"
                        class="w-full border border-muted-border bg-surface-container-low p-3 font-body-md text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" />
                </div>
            </div>
        </div>
    </form>
    @slot('footer')
        <div class="flex justify-end gap-4">
            <button type="button" data-modal-close class="btn-modal btn-modal-ghost flex-1"><span class="material-symbols-outlined text-[16px] leading-none">close</span>Batal</button>
            <button type="submit" form="approve-form" id="approve-submit" disabled class="btn-modal btn-modal-success flex-1 disabled:opacity-40 disabled:cursor-not-allowed"><span class="material-symbols-outlined text-[16px] leading-none">task_alt</span>Setujui &amp; Bayar</button>
        </div>
    @endslot
@endcomponent

@component('SuperAdmin.partials.premium-confirm', [
    'id' => 'reject-dialog',
    'dataModal' => true,
    'zIndex' => 60,
    'icon' => 'gpp_bad',
])
    <form method="POST" action="" id="reject-form" onsubmit="hideDialog('reject-dialog')">
        @csrf
        <div class="p-6">
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Tolak Pencairan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-4 text-center">Anda yakin ingin menolak pengajuan dari <span id="reject-toko" class="font-bold text-on-surface">-</span>?</p>
            <div class="mb-6">
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Alasan Penolakan</label>
                <textarea name="alasan" required minlength="10" maxlength="1000"
                    class="w-full border border-muted-border bg-surface-container-low p-3 font-body-md text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary h-24"
                    placeholder="Tulis alasan... (minimal 10 karakter)"></textarea>
            </div>
        </div>
    </form>
    @slot('footer')
        <div class="flex justify-end gap-4">
            <button type="button" data-modal-close class="btn-modal btn-modal-ghost flex-1"><span class="material-symbols-outlined text-[16px] leading-none">close</span>Batal</button>
            <button type="submit" form="reject-form" class="btn-modal btn-modal-danger flex-1"><span class="material-symbols-outlined text-[16px] leading-none">block</span>Tolak Pengajuan</button>
        </div>
    @endslot
@endcomponent
@endsection

@push('scripts')
<script>
    const withdrawalUrls = {
        setujui: (id) => '{{ route('superadmin.permintaan-penarikan.setujui', ':id:') }}'.replace(':id:', id),
        tolak: (id) => '{{ route('superadmin.permintaan-penarikan.tolak', ':id:') }}'.replace(':id:', id),
        dibayar: (id) => '{{ route('superadmin.permintaan-penarikan.tandai-dibayar', ':id:') }}'.replace(':id:', id)
    };

    function openPaidDialog(row) {
        document.getElementById('paid-toko').textContent = row.dataset.nama;
        document.getElementById('paid-nominal').textContent = 'Rp ' + row.dataset.jumlah;
        document.getElementById('paid-form').action = withdrawalUrls.dibayar(row.dataset.id);
        showDialog('paid-dialog');
    }

    function openApproveDialog(row) {
        document.getElementById('approve-toko').textContent = row.dataset.nama;
        document.getElementById('approve-nominal').textContent = 'Rp ' + row.dataset.jumlah;
        document.getElementById('approve-form').action = withdrawalUrls.setujui(row.dataset.id);
        document.getElementById('approve-metode').textContent = row.dataset.metode || '-';
        document.getElementById('approve-tujuan').textContent = row.dataset.tujuan || '-';
        document.getElementById('approve-pemilik').textContent = row.dataset.pemilik ? 'a.n. ' + row.dataset.pemilik : '';
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
        document.getElementById('reject-toko').textContent = row.dataset.nama;
        document.getElementById('reject-form').action = withdrawalUrls.tolak(row.dataset.id);
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

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { hideDialog('approve-dialog'); hideDialog('reject-dialog'); hideDialog('paid-dialog'); }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-table-scope]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('tr[data-table-row]'));
        const cards = Array.from(scope.querySelectorAll('article[data-table-row]'));
        const chipBtns = document.querySelectorAll('#chip-group .chip-btn');
        const searchInput = document.getElementById('penarikan-search');
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
</script>
@endpush
