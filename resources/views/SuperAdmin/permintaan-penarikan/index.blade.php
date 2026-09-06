@extends('layouts.superadmin')

@section('title', 'Pencairan Dana')

@section('header-title', 'Pencairan Dana')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Verifikasi dan setujui pengajuan pencairan dana Owner.')

@php
    $badgeMap = [
        'pending' => ['label' => 'Menunggu', 'class' => 'bg-surface-container-high text-on-surface border-outline-variant'],
        'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'dibayar' => ['label' => 'Dibayar', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-error/10 text-error border-error/20'],
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
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Pengajuan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-surface-container-high flex items-center justify-center rounded-full">
                        <span class="material-symbols-outlined text-on-surface">pending_actions</span>
                    </div>
                    <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Pengajuan Menunggu</h3>
                </div>
                <p class="font-headline-lg-mobile text-headline-lg-mobile text-primary">{{ $stats['pending'] }}</p>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">Menunggu verifikasi dan persetujuan</p>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-secondary-container flex items-center justify-center rounded-full">
                        <span class="material-symbols-outlined text-secondary">account_balance_wallet</span>
                    </div>
                    <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Total Nominal Menunggu</h3>
                </div>
                <p class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp {{ number_format($stats['nominal_menunggu'], 0, ',', '.') }}</p>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">Total nominal diajukan Owner</p>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gold-accent/10 flex items-center justify-center rounded-full">
                        <span class="material-symbols-outlined text-gold-accent">task_alt</span>
                    </div>
                    <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Total Disetujui</h3>
                </div>
                <p class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp {{ number_format($stats['total_semua'], 0, ',', '.') }}</p>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">Akumulasi pencairan diproses / dibayar</p>
            </div>
        </div>
    </section>

    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
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
                <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                    <span id="result-count">{{ $withdrawals->count() }}</span> pengajuan
                </p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[850px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-sm uppercase">
                        <th class="p-6 w-12 text-center">No.</th>
                        <th class="p-6">Toko / Pemilik</th>
                        <th class="p-6">Detail Pengajuan</th>
                        <th class="p-6">Info Bank</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6 text-center">Dibayar</th>
                        <th class="p-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($withdrawals as $w)
                        @php
                            $badge = $badgeMap[$w->status];
                            $initial = strtoupper(substr(collect(preg_split('/\s+/', trim($w->store->nama_toko ?? '')))->map(fn ($k) => mb_substr($k, 0, 1))->implode(''), 0, 2));
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors group"
                            data-table-row data-status="{{ $w->status }}" data-search="{{ strtolower(($w->store->nama_toko ?? '').' '.($w->store->owner->nama_lengkap ?? '').' '.($w->bankAccount?->bank?->nama_bank ?? '')) }}"
                            data-id="{{ $w->withdrawal_id }}" data-nama="{{ $w->store->nama_toko }}" data-jumlah="{{ number_format((float) $w->jumlah, 0, ',', '.') }}">
                            <td class="p-6 text-center text-on-surface-variant font-mono row-num"></td>
                            <td class="p-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">{{ $initial }}</div>
                                    <div>
                                        <p class="font-title-md text-title-md text-primary">{{ $w->store->nama_toko }}</p>
                                        <p class="text-on-surface-variant">{{ $w->store->owner->nama_lengkap ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <p class="font-title-md text-title-md text-gold-accent">Rp {{ number_format((float) $w->jumlah, 0, ',', '.') }}</p>
                                <p class="text-on-surface-variant">{{ $w->diajukan_pada?->translatedFormat('d M Y') }}</p>
                            </td>
                            <td class="p-6">
                                <p class="text-primary">{{ $w->bankAccount?->bank?->nama_bank ?? '-' }}</p>
                                <p class="text-on-surface-variant">**** **** {{ substr($w->bankAccount?->nomor_rekening ?? '', -4) }}</p>
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
                                    <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity">
                                        <button type="button" onclick="openRejectDialog(this.closest('tr'))" title="Tolak"
                                            class="w-8 h-8 flex items-center justify-center border border-outline text-on-surface hover:bg-error hover:text-on-error hover:border-error transition-colors">
                                            <span class="material-symbols-outlined text-sm">close</span>
                                        </button>
                                        <button type="button" onclick="openApproveDialog(this.closest('tr'))" title="Setujui"
                                            class="w-8 h-8 flex items-center justify-center bg-deep-onyx text-on-primary hover:opacity-80 transition-opacity">
                                            <span class="material-symbols-outlined text-sm">check</span>
                                        </button>
                                    </div>
                                @elseif ($w->status === 'disetujui')
                                    <button type="button" onclick="openPaidDialog(this.closest('tr'))"
                                        class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:opacity-80 transition-opacity btn-premium">Tandai Dibayar</button>
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
                    $badge = $badgeMap[$w->status];
                    $initial = strtoupper(substr(collect(preg_split('/\s+/', trim($w->store->nama_toko ?? '')))->map(fn ($k) => mb_substr($k, 0, 1))->implode(''), 0, 2));
                @endphp
                <article data-table-row data-status="{{ $w->status }}" data-search="{{ strtolower(($w->store->nama_toko ?? '').' '.($w->store->owner->nama_lengkap ?? '').' '.($w->bankAccount?->bank?->nama_bank ?? '')) }}" data-id="{{ $w->withdrawal_id }}" data-nama="{{ $w->store->nama_toko }}" data-jumlah="{{ number_format((float) $w->jumlah, 0, ',', '.') }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">{{ $initial }}</div>
                            <div class="min-w-0">
                                <p class="font-title-md text-title-md text-primary truncate">{{ $w->store->nama_toko }}</p>
                                <p class="text-on-surface-variant truncate">{{ $w->store->owner->nama_lengkap ?? '-' }}</p>
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
                            <dt class="text-on-surface-variant">Bank</dt>
                            <dd class="text-on-surface text-right">{{ $w->bankAccount?->bank?->nama_bank ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Rekening</dt>
                            <dd class="font-mono text-on-surface-variant text-right">**** **** {{ substr($w->bankAccount?->nomor_rekening ?? '', -4) }}</dd>
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
                            <button type="button" onclick="openRejectDialog(this.closest('article'))" class="flex-1 min-h-11 inline-flex items-center justify-center gap-2 border border-outline text-on-surface hover:bg-error hover:text-on-error hover:border-error transition-colors rounded-lg">
                                <span class="material-symbols-outlined text-[16px]">close</span>Tolak
                            </button>
                            <button type="button" onclick="openApproveDialog(this.closest('article'))" class="flex-1 min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary hover:opacity-80 transition-opacity rounded-lg btn-premium">
                                <span class="material-symbols-outlined text-[16px]">check</span>Setujui
                            </button>
                        </div>
                    @elseif ($w->status === 'disetujui')
                        <button type="button" onclick="openPaidDialog(this.closest('article'))" class="w-full min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:opacity-80 transition-opacity btn-premium">
                            <span class="material-symbols-outlined text-[16px]">local_atm</span>Tandai Dibayar
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
<div class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="paid-dialog">
    <form method="POST" action="" id="paid-form" enctype="multipart/form-data" onsubmit="hideDialog('paid-dialog')">
        @csrf
        <div class="bg-surface-container-lowest border border-gold-accent/25 p-6 max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-secondary-container/30 border border-secondary/25 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-secondary text-[28px]">local_atm</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4 text-center">Tandai Sudah Dibayar</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-2 text-center">Konfirmasikan bahwa dana sebesar <span id="paid-nominal" class="font-title-md text-gold-accent">-</span> untuk <span id="paid-toko" class="font-bold text-on-surface">-</span> telah dikirim ke rekening tujuan.</p>
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
            <div class="flex justify-end gap-4 mt-6">
                <button type="button" class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors" onclick="hideDialog('paid-dialog')">Batal</button>
                <button type="submit" class="bg-deep-onyx text-on-primary px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity btn-premium">Ya, Sudah Dibayar</button>
            </div>
        </div>
    </form>
</div>

<div class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="approve-dialog">
    <form method="POST" action="" id="approve-form" onsubmit="hideDialog('approve-dialog')">
        @csrf
        <div class="bg-surface-container-lowest border border-gold-accent/25 p-6 max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-gold-accent text-[28px]">task_alt</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4 text-center">Konfirmasi Pencairan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-8 text-center">Anda akan menyetujui pencairan sebesar <span id="approve-nominal" class="font-title-md text-gold-accent">-</span> ke <span id="approve-toko" class="font-bold text-on-surface">-</span>. Saldo toko akan dikunci untuk proses pembayaran.</p>
            <div class="flex justify-end gap-4">
                <button type="button" class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors" onclick="hideDialog('approve-dialog')">Batal</button>
                <button type="submit" class="bg-deep-onyx text-on-primary px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity">Konfirmasi Persetujuan</button>
            </div>
        </div>
    </form>
</div>

<div class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="reject-dialog">
    <form method="POST" action="" id="reject-form" onsubmit="hideDialog('reject-dialog')" class="w-full max-w-md">
        @csrf
        <div class="bg-surface-container-lowest border border-error/25 p-6 max-w-md w-full shadow-2xl rounded-xl">
            <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
            </div>
            <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-error mb-4 text-center">Tolak Pencairan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mb-4 text-center">Anda yakin ingin menolak pengajuan dari <span id="reject-toko" class="font-bold text-on-surface">-</span>?</p>
            <div class="mb-6">
                <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Alasan Penolakan</label>
                <textarea name="alasan" required minlength="10" maxlength="1000"
                    class="w-full border border-muted-border bg-surface-container-low p-3 font-body-md text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary h-24"
                    placeholder="Tulis alasan... (minimal 10 karakter)"></textarea>
            </div>
            <div class="flex justify-end gap-4">
                <button type="button" class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors" onclick="hideDialog('reject-dialog')">Batal</button>
                <button type="submit" class="bg-error text-on-error px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity">Tolak Pengajuan</button>
            </div>
        </div>
    </form>
</div>
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
        showDialog('approve-dialog');
    }

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

        const rows = Array.from(scope.querySelectorAll('tr[data-table-row], article[data-table-row]'));
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
</script>
@endpush
