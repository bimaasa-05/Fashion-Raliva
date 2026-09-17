@extends('layouts.superadmin')

@section('title', 'Data Bank')

@section('header-title', 'Data Bank')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola rekening bank, e-wallet, dan QRIS platform')

@push('styles')
<style>
    .banner-gradient { background-image: linear-gradient(118deg, #141414 0%, #1f0c10 55%, #421329 100%); }
    .banner-glow { position: absolute; border-radius: 9999px; pointer-events: none; }
    .banner-glow-1 { top: -90px; right: -50px; width: 260px; height: 260px; background: rgba(139, 30, 63, 0.4); }
    .banner-glow-2 { bottom: -120px; left: -60px; width: 220px; height: 220px; background: rgba(139, 30, 63, 0.24); }
    .banner-desc { font-size: 14px; color: rgba(255, 255, 255, 0.72); }
    .banner-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 9999px; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #fff; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.18); }
    .banner-badge .dot { width: 7px; height: 7px; border-radius: 9999px; animation: beat 1.6s ease-in-out infinite; }
    .banner-badge.is-on .dot { background: #4ade80; }
    @keyframes beat { 0%, 100% { opacity: 1; } 50% { opacity: 0.35; } }

    .stat-chip { display: flex; flex-direction: column; gap: 2px; min-width: 112px; padding: 10px 14px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.16); background: rgba(255, 255, 255, 0.08); }
    .stat-chip-label { font-size: 10px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255, 255, 255, 0.55); }
    .stat-chip-value { font-size: 16px; font-weight: 700; color: #fff; font-variant-numeric: tabular-nums; }

    .tab-btn { display: inline-flex; align-items: center; gap: 8px; padding: 9px 18px; border-radius: 9999px; font-size: 11px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; white-space: nowrap; color: var(--color-on-surface-variant); transition: all 0.18s ease; }
    .tab-btn:hover { color: var(--color-on-surface); }
    .tab-btn.active { background: #141414; color: #fff; box-shadow: 0 8px 20px -10px rgb(17 17 17 / 0.45); }
    .tab-btn.active .material-symbols-outlined { color: var(--color-gold-accent); }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

@php
    $ewalletCount = $ewallets->count();
@endphp

<div class="space-y-section-gap w-full">
    {{--=== Banner hero ===--}}
    <section class="banner-gradient relative overflow-hidden rounded-2xl border border-muted-border p-6 md:p-8 card-premium">
        <span class="banner-glow banner-glow-1"></span>
        <span class="banner-glow banner-glow-2"></span>
        <div class="relative flex flex-col lg:flex-row lg:items-center gap-6">
            <div class="flex items-start gap-4 min-w-0">
                <div class="w-12 h-12 rounded-xl bg-secondary-container/20 border border-secondary/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[26px] text-white">account_balance</span>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="font-headline-md text-headline-md text-white tracking-wide">Data Bank Platform</h2>
                        <span class="banner-badge is-on"><span class="dot"></span>{{ $stats['aktif'] }} Bank Aktif</span>
                    </div>
                    <p class="banner-desc mt-2 max-w-2xl">Kelola rekening penerimaan pembayaran — bank transfer, e-wallet, dan QRIS yang dipakai pelanggan saat checkout.</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3 lg:ml-auto shrink-0">
                <div class="stat-chip">
                    <span class="stat-chip-label">Bank Terdaftar</span>
                    <span class="stat-chip-value">{{ $stats['total'] }}</span>
                </div>
                <div class="stat-chip">
                    <span class="stat-chip-label">Rekening Platform</span>
                    <span class="stat-chip-value">{{ $stats['rekening'] }}</span>
                </div>
                <div class="stat-chip">
                    <span class="stat-chip-label">E-Wallet</span>
                    <span class="stat-chip-value">{{ $ewalletCount }}</span>
                </div>
            </div>
        </div>
    </section>

    {{--=== Tabs premium ===--}}
    <div class="flex flex-wrap items-center gap-1.5 rounded-xl border border-muted-border bg-surface-container-lowest shadow-sm p-1.5 w-fit no-scrollbar overflow-x-auto max-w-full">
        <button class="tab-btn active" data-tab="bank" onclick="switchTab('bank')">
            <span class="material-symbols-outlined text-[18px]">account_balance</span> Bank Transfer
        </button>
        <button class="tab-btn" data-tab="ewallet" onclick="switchTab('ewallet')">
            <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span> E-Wallet
        </button>
        <button class="tab-btn" data-tab="qris" onclick="switchTab('qris')">
            <span class="material-symbols-outlined text-[18px]">qr_code_2</span> QRIS
        </button>
    </div>

    {{--=== Panel: Bank Transfer ===--}}
    <div id="panel-bank" class="tab-panel">
        <div class="rounded-2xl border border-muted-border bg-surface-container-lowest shadow-sm card-premium overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-muted-border">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-gold-accent">account_balance</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Bank Transfer</h3>
                        <p class="text-on-surface-variant font-body-md text-xs mt-0.5">Rekening bank tujuan transfer pembayaran pelanggan.</p>
                    </div>
                </div>
                <button type="button" onclick="openBankForm()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded-lg btn-premium w-fit">
                    <span class="material-symbols-outlined text-[18px]">add</span> Tambah Bank
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="premium-table w-full text-sm">
                    <thead>
                        <tr class="text-left text-on-surface-variant border-b border-muted-border">
                            <th class="py-3 px-5 font-label-sm text-[10px] uppercase tracking-wider">Bank</th>
                            <th class="py-3 px-4 font-label-sm text-[10px] uppercase tracking-wider">No. Rekening</th>
                            <th class="py-3 px-4 font-label-sm text-[10px] uppercase tracking-wider">Pemilik</th>
                            <th class="py-3 px-4 font-label-sm text-[10px] uppercase tracking-wider">Status</th>
                            <th class="py-3 px-5 font-label-sm text-[10px] uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banks as $bank)
                            @php $rek = $bank->platformBankAccounts->first(); @endphp
                            <tr class="border-b border-muted-border/70 last:border-0 hover:bg-surface-container-low/50">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[20px] text-gold-accent">account_balance</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-on-surface truncate">{{ $bank->nama_bank }}</p>
                                            <p class="text-on-surface-variant text-xs mt-0.5 uppercase tracking-wider">{{ $bank->kode_bank }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-on-surface tabular-nums">{{ $rek?->nomor_rekening ?? '-' }}</td>
                                <td class="py-4 px-4 text-on-surface">{{ $rek?->nama_pemilik ?? '-' }}</td>
                                <td class="py-4 px-4">
                                    @if ($bank->status === 'aktif')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-secondary-container/20 text-secondary border-secondary/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-error/10 text-error border-error/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-1 justify-end">
                                        <button onclick="editBank({{ $bank->bank_id }})" title="Edit bank" class="w-9 h-9 rounded-lg border border-transparent hover:border-gold-accent/40 hover:bg-gold-accent/10 text-on-surface-variant hover:text-gold-accent flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button type="button" onclick="confirmDeleteBank({{ $bank->bank_id }}, @js($bank->nama_bank))" title="Hapus bank" class="w-9 h-9 rounded-lg border border-transparent hover:border-error/30 hover:bg-error/10 text-on-surface-variant hover:text-error flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-14 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-muted-border flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[26px] text-on-surface-variant">account_balance</span>
                                        </div>
                                        <p class="font-body-md text-sm text-on-surface-variant">Belum ada bank terdaftar. Klik "Tambah Bank" untuk membuat metode transfer baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{--=== Panel: E-Wallet ===--}}
    <div id="panel-ewallet" class="tab-panel hidden">
        <div class="rounded-2xl border border-muted-border bg-surface-container-lowest shadow-sm card-premium overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-muted-border">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-gold-accent">account_balance_wallet</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">E-Wallet</h3>
                        <p class="text-on-surface-variant font-body-md text-xs mt-0.5">Dompet digital yang bisa dipilih pelanggan saat checkout.</p>
                    </div>
                </div>
                <button type="button" onclick="openEwalletForm()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded-lg btn-premium w-fit">
                    <span class="material-symbols-outlined text-[18px]">add</span> Tambah E-Wallet
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="premium-table w-full text-sm">
                    <thead>
                        <tr class="text-left text-on-surface-variant border-b border-muted-border">
                            <th class="py-3 px-5 font-label-sm text-[10px] uppercase tracking-wider">Nama</th>
                            <th class="py-3 px-4 font-label-sm text-[10px] uppercase tracking-wider">No. Telepon</th>
                            <th class="py-3 px-4 font-label-sm text-[10px] uppercase tracking-wider">Pemilik</th>
                            <th class="py-3 px-4 font-label-sm text-[10px] uppercase tracking-wider">Status</th>
                            <th class="py-3 px-5 font-label-sm text-[10px] uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ewallets as $ew)
                            <tr class="border-b border-muted-border/70 last:border-0 hover:bg-surface-container-low/50">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-secondary-container/20 border border-secondary/20 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[20px] text-secondary">account_balance_wallet</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-on-surface truncate">{{ $ew->nama }}</p>
                                            <p class="text-on-surface-variant text-xs mt-0.5 uppercase tracking-wider">{{ $ew->kode }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-on-surface tabular-nums">{{ $ew->nomor_rekening ?? '-' }}</td>
                                <td class="py-4 px-4 text-on-surface">{{ $ew->nama_pemilik ?? '-' }}</td>
                                <td class="py-4 px-4">
                                    @if ($ew->status === 'aktif')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-secondary-container/20 text-secondary border-secondary/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-error/10 text-error border-error/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-1 justify-end">
                                        <button onclick="editEwallet({{ $ew->platform_bank_account_id }})" title="Edit e-wallet" class="w-9 h-9 rounded-lg border border-transparent hover:border-gold-accent/40 hover:bg-gold-accent/10 text-on-surface-variant hover:text-gold-accent flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>
                                        <button type="button" onclick="confirmDeleteEwallet({{ $ew->platform_bank_account_id }}, @js($ew->nama))" title="Hapus e-wallet" class="w-9 h-9 rounded-lg border border-transparent hover:border-error/30 hover:bg-error/10 text-on-surface-variant hover:text-error flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-14 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-muted-border flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[26px] text-on-surface-variant">account_balance_wallet</span>
                                        </div>
                                        <p class="font-body-md text-sm text-on-surface-variant">Belum ada e-wallet terdaftar. Klik "Tambah E-Wallet" untuk menambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{--=== Panel: QRIS ===--}}
    <div id="panel-qris" class="tab-panel hidden">
        <div class="rounded-2xl border border-muted-border bg-surface-container-lowest shadow-sm card-premium overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-muted-border">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px] text-gold-accent">qr_code_2</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">QRIS</h3>
                        <p class="text-on-surface-variant font-body-md text-xs mt-0.5">Kode QR pembayaran universal yang dipakai pelanggan.</p>
                    </div>
                </div>
                @if ($qris)
                    <button type="button" onclick="editQris({{ $qris->platform_bank_account_id }})" class="inline-flex items-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded-lg btn-premium w-fit">
                        <span class="material-symbols-outlined text-[18px]">edit</span> Edit QRIS
                    </button>
                @else
                    <button type="button" onclick="openQrisForm()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded-lg btn-premium w-fit">
                        <span class="material-symbols-outlined text-[18px]">add</span> Tambah QRIS
                    </button>
                @endif
            </div>
            @if ($qris)
                <div class="flex flex-col sm:flex-row gap-6 p-5 md:p-6">
                    @if ($qris->file_gambar)
                        <div class="shrink-0">
                            <img src="{{ asset('storage/' . ltrim($qris->file_gambar, '/')) }}" alt="{{ $qris->nama }}" class="w-44 h-44 md:w-52 md:h-52 object-contain rounded-xl border border-muted-border bg-surface-container-low p-3 shadow-sm" />
                        </div>
                    @endif
                    <div class="flex-1 space-y-3 min-w-0">
                        <div class="flex items-center gap-3 flex-wrap">
                            <h4 class="font-title-md text-title-md text-on-surface">{{ $qris->nama }}</h4>
                            @if ($qris->status === 'aktif')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-secondary-container/20 text-secondary border-secondary/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-error/10 text-error border-error/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Nonaktif
                                </span>
                            @endif
                        </div>
                        @if ($qris->deskripsi)
                            <p class="text-on-surface-variant text-sm">{{ $qris->deskripsi }}</p>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            <div class="rounded-xl border border-muted-border bg-surface-container-low px-4 py-3">
                                <p class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Nama Pemilik</p>
                                <p class="text-sm font-semibold text-on-surface mt-0.5">{{ $qris->nama_pemilik ?? '-' }}</p>
                            </div>
                            <div class="rounded-xl border border-muted-border bg-surface-container-low px-4 py-3">
                                <p class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Kode</p>
                                <p class="text-sm font-semibold text-on-surface mt-0.5 uppercase">{{ $qris->kode }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center gap-3 py-14 px-5 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-muted-border flex items-center justify-center">
                        <span class="material-symbols-outlined text-[26px] text-on-surface-variant">qr_code_2</span>
                    </div>
                    <div>
                        <p class="font-body-md text-sm text-on-surface">Belum ada akun QRIS</p>
                        <p class="font-body-md text-xs text-on-surface-variant mt-1">Klik "Tambah QRIS" untuk membuat kode QR pembayaran platform.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Bank Form -->
<div id="modal-bank" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBankForm()"></div>
    <div class="relative w-full max-w-md m-auto max-h-[88dvh] overflow-y-auto no-scrollbar">
        <div class="overflow-hidden rounded-2xl border border-muted-border shadow-2xl card-premium bg-surface-container-lowest">
            <div class="relative overflow-hidden banner-gradient px-6 py-5">
                <span class="banner-glow banner-glow-1"></span>
                <span class="banner-glow banner-glow-2"></span>
                <div class="relative flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px] text-white">account_balance</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 id="bank-modal-title" class="font-title-md text-title-md text-white">Tambah Bank</h3>
                        <p class="font-body-md text-xs text-white/70 mt-0.5">Rekening tujuan transfer pembayaran pelanggan.</p>
                    </div>
                    <button type="button" onclick="closeBankForm()" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 flex items-center justify-center text-white shrink-0 transition-colors"><span class="material-symbols-outlined text-[18px]">close</span></button>
                </div>
            </div>
            <form id="form-bank" method="POST" action="{{ route('superadmin.data-bank.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" value="POST" id="bank-method" />
                <input type="hidden" name="bank_id" id="bank-id" />
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Nama Bank</label>
                    <input type="text" name="nama_bank" id="bank-nama" required maxlength="100" class="raliva-input" placeholder="Bank Central Asia (BCA)" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Kode Bank</label>
                    <input type="text" name="kode_bank" id="bank-kode" required maxlength="20" class="raliva-input" placeholder="bca" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Nomor Rekening</label>
                    <input type="text" name="nomor_rekening" id="bank-rekening" required maxlength="50" class="raliva-input" placeholder="1234567890" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Nama Pemilik</label>
                    <input type="text" name="nama_pemilik" id="bank-pemilik" required maxlength="150" class="raliva-input" placeholder="RALIVA Fashion" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Status</label>
                    <select name="status" id="bank-status" class="raliva-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-muted-border">
                    <button type="button" onclick="closeBankForm()" class="px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface-variant hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-gold-accent to-secondary text-on-primary text-sm font-bold uppercase tracking-widest btn-premium inline-flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">save</span>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: E-Wallet Form -->
<div id="modal-ewallet" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEwalletForm()"></div>
    <div class="relative w-full max-w-md m-auto max-h-[88dvh] overflow-y-auto no-scrollbar">
        <div class="overflow-hidden rounded-2xl border border-muted-border shadow-2xl card-premium bg-surface-container-lowest">
            <div class="relative overflow-hidden banner-gradient px-6 py-5">
                <span class="banner-glow banner-glow-1"></span>
                <span class="banner-glow banner-glow-2"></span>
                <div class="relative flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px] text-white">account_balance_wallet</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 id="ewallet-modal-title" class="font-title-md text-title-md text-white">Tambah E-Wallet</h3>
                        <p class="font-body-md text-xs text-white/70 mt-0.5">Dompet digital yang ditampilkan ke pelanggan.</p>
                    </div>
                    <button type="button" onclick="closeEwalletForm()" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 flex items-center justify-center text-white shrink-0 transition-colors"><span class="material-symbols-outlined text-[18px]">close</span></button>
                </div>
            </div>
            <form id="form-ewallet" method="POST" action="{{ route('superadmin.data-bank.account.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="jenis" value="ewallet" />
                <input type="hidden" name="account_id" id="ewallet-id" />
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Nama</label>
                    <input type="text" name="nama" id="ewallet-nama" required maxlength="100" class="raliva-input" placeholder="DANA" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Kode</label>
                    <input type="text" name="kode" id="ewallet-kode" required maxlength="50" class="raliva-input" placeholder="dana" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">No. Telepon</label>
                    <input type="text" name="nomor_rekening" id="ewallet-rekening" maxlength="50" class="raliva-input" placeholder="08XXXXXXXXXX" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Nama Pemilik</label>
                    <input type="text" name="nama_pemilik" id="ewallet-pemilik" maxlength="150" class="raliva-input" value="RALIVA Fashion" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="ewallet-deskripsi" rows="2" maxlength="255" class="raliva-textarea"></textarea>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Logo</label>
                    <div class="flex items-center gap-3">
                        <input type="file" name="file_gambar" accept="image/*" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-surface-container-low file:text-sm file:text-gold-accent file:cursor-pointer file:font-semibold hover:file:bg-surface-container-high" />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Status</label>
                    <select name="status" id="ewallet-status" class="raliva-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-muted-border">
                    <button type="button" onclick="closeEwalletForm()" class="px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface-variant hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-gold-accent to-secondary text-on-primary text-sm font-bold uppercase tracking-widest btn-premium inline-flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">save</span>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: QRIS Form -->
<div id="modal-qris" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeQrisForm()"></div>
    <div class="relative w-full max-w-md m-auto max-h-[88dvh] overflow-y-auto no-scrollbar">
        <div class="overflow-hidden rounded-2xl border border-muted-border shadow-2xl card-premium bg-surface-container-lowest">
            <div class="relative overflow-hidden banner-gradient px-6 py-5">
                <span class="banner-glow banner-glow-1"></span>
                <span class="banner-glow banner-glow-2"></span>
                <div class="relative flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px] text-white">qr_code_2</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 id="qris-modal-title" class="font-title-md text-title-md text-white">Tambah QRIS</h3>
                        <p class="font-body-md text-xs text-white/70 mt-0.5">Kode QR pembayaran universal untuk pelanggan.</p>
                    </div>
                    <button type="button" onclick="closeQrisForm()" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 flex items-center justify-center text-white shrink-0 transition-colors"><span class="material-symbols-outlined text-[18px]">close</span></button>
                </div>
            </div>
            <form id="form-qris" method="POST" action="{{ route('superadmin.data-bank.account.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="jenis" value="qris" />
                <input type="hidden" name="account_id" id="qris-id" />
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Nama</label>
                    <input type="text" name="nama" id="qris-nama" required maxlength="100" class="raliva-input" value="QRIS RALIVA" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Kode</label>
                    <input type="text" name="kode" id="qris-kode" required maxlength="50" class="raliva-input" value="qris" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Nama Pemilik</label>
                    <input type="text" name="nama_pemilik" id="qris-pemilik" maxlength="150" class="raliva-input" value="RALIVA Fashion" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="qris-deskripsi" rows="2" maxlength="255" class="raliva-textarea">Scan kode QR dengan aplikasi apa pun (GoPay, OVO, DANA, ShopeePay, m-Banking).</textarea>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Gambar QR</label>
                    <input type="file" name="file_gambar" accept="image/*" class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-surface-container-low file:text-sm file:text-gold-accent file:cursor-pointer file:font-semibold hover:file:bg-surface-container-high" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Status</label>
                    <select name="status" id="qris-status" class="raliva-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-muted-border">
                    <button type="button" onclick="closeQrisForm()" class="px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface-variant hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-gold-accent to-secondary text-on-primary text-sm font-bold uppercase tracking-widest btn-premium inline-flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">save</span>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Konfirmasi Hapus Bank -->
<div id="modal-hapus-bank" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteBank()"></div>
    <div class="relative w-full max-w-sm m-auto">
        <div class="overflow-hidden rounded-2xl border border-muted-border shadow-2xl card-premium bg-surface-container-lowest">
            <div class="relative overflow-hidden banner-gradient px-6 py-5">
                <span class="banner-glow banner-glow-1"></span>
                <span class="banner-glow banner-glow-2"></span>
                <div class="relative flex items-center justify-center">
                    <div class="w-12 h-12 rounded-full bg-error/20 border border-error/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px] text-error">delete_forever</span>
                    </div>
                </div>
            </div>
            <form id="form-hapus-bank" method="POST" action="" class="p-6 space-y-4">
                @csrf
                <div class="text-center">
                    <h3 class="font-title-md text-title-md text-on-surface">Hapus Bank Ini?</h3>
                    <p id="hapus-bank-text" class="font-body-md text-sm text-on-surface-variant mt-2"></p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button type="button" onclick="closeDeleteBank()" class="px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface-variant hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-error text-white text-sm font-bold uppercase tracking-widest btn-premium inline-flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">delete</span>Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Konfirmasi Hapus E-Wallet -->
<div id="modal-hapus-ewallet" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteEwallet()"></div>
    <div class="relative w-full max-w-sm m-auto">
        <div class="overflow-hidden rounded-2xl border border-muted-border shadow-2xl card-premium bg-surface-container-lowest">
            <div class="relative overflow-hidden banner-gradient px-6 py-5">
                <span class="banner-glow banner-glow-1"></span>
                <span class="banner-glow banner-glow-2"></span>
                <div class="relative flex items-center justify-center">
                    <div class="w-12 h-12 rounded-full bg-error/20 border border-error/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px] text-error">delete_forever</span>
                    </div>
                </div>
            </div>
            <form id="form-hapus-ewallet" method="POST" action="" class="p-6 space-y-4">
                @csrf
                <div class="text-center">
                    <h3 class="font-title-md text-title-md text-on-surface">Hapus E-Wallet Ini?</h3>
                    <p id="hapus-ewallet-text" class="font-body-md text-sm text-on-surface-variant mt-2"></p>
                </div>
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button type="button" onclick="closeDeleteEwallet()" class="px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface-variant hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-error text-white text-sm font-bold uppercase tracking-widest btn-premium inline-flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">delete</span>Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function switchTab(tab) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.getElementById('panel-' + tab).classList.remove('hidden');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelector('[data-tab="' + tab + '"]').classList.add('active');
    }

    // Bank modal
    function openBankForm() {
        document.getElementById('bank-modal-title').textContent = 'Tambah Bank';
        document.getElementById('form-bank').action = '{{ route('superadmin.data-bank.store') }}';
        document.getElementById('bank-id').value = '';
        document.getElementById('bank-nama').value = '';
        document.getElementById('bank-kode').value = '';
        document.getElementById('bank-rekening').value = '';
        document.getElementById('bank-pemilik').value = '';
        document.getElementById('bank-status').value = 'aktif';
        document.getElementById('modal-bank').classList.remove('hidden');
        document.getElementById('modal-bank').classList.add('flex');
    }

    function closeBankForm() {
        document.getElementById('modal-bank').classList.add('hidden');
        document.getElementById('modal-bank').classList.remove('flex');
    }

    function editBank(id) {
        fetch(`/superadmin/data-bank/${id}/edit`)
            .then(r => r.json())
            .then(d => {
                document.getElementById('bank-modal-title').textContent = 'Edit Bank';
                document.getElementById('form-bank').action = '{{ route('superadmin.data-bank.update', ['bank' => ':ID']) }}'.replace(':ID', id);
                document.getElementById('bank-id').value = id;
                document.getElementById('bank-nama').value = d.nama_bank;
                document.getElementById('bank-kode').value = d.kode_bank;
                document.getElementById('bank-rekening').value = d.rekening || '';
                document.getElementById('bank-pemilik').value = d.pemilik || '';
                document.getElementById('bank-status').value = d.status;
                document.getElementById('modal-bank').classList.remove('hidden');
                document.getElementById('modal-bank').classList.add('flex');
            })
            .catch(() => window.showRalivaToast('Gagal memuat data bank.', 'error'));
    }

    // E-Wallet modal
    function openEwalletForm() {
        document.getElementById('ewallet-modal-title').textContent = 'Tambah E-Wallet';
        document.getElementById('form-ewallet').action = '{{ route('superadmin.data-bank.account.store') }}';
        document.getElementById('ewallet-id').value = '';
        document.getElementById('ewallet-nama').value = '';
        document.getElementById('ewallet-kode').value = '';
        document.getElementById('ewallet-rekening').value = '';
        document.getElementById('ewallet-pemilik').value = 'RALIVA Fashion';
        document.getElementById('ewallet-deskripsi').value = '';
        document.getElementById('ewallet-status').value = 'aktif';
        document.getElementById('modal-ewallet').classList.remove('hidden');
        document.getElementById('modal-ewallet').classList.add('flex');
    }

    function closeEwalletForm() {
        document.getElementById('modal-ewallet').classList.add('hidden');
        document.getElementById('modal-ewallet').classList.remove('flex');
    }

    function editEwallet(id) {
        fetch(`/superadmin/data-bank/account/${id}/edit`)
            .then(r => r.json())
            .then(d => {
                document.getElementById('ewallet-modal-title').textContent = 'Edit E-Wallet';
                document.getElementById('form-ewallet').action = '{{ route('superadmin.data-bank.account.update', ['account' => ':ID']) }}'.replace(':ID', id);
                document.getElementById('ewallet-id').value = id;
                document.getElementById('ewallet-nama').value = d.nama;
                document.getElementById('ewallet-kode').value = d.kode;
                document.getElementById('ewallet-rekening').value = d.nomor_rekening || '';
                document.getElementById('ewallet-pemilik').value = d.nama_pemilik || 'RALIVA Fashion';
                document.getElementById('ewallet-deskripsi').value = d.deskripsi || '';
                document.getElementById('ewallet-status').value = d.status;
                document.getElementById('modal-ewallet').classList.remove('hidden');
                document.getElementById('modal-ewallet').classList.add('flex');
            })
            .catch(() => window.showRalivaToast('Gagal memuat data e-wallet.', 'error'));
    }

    // QRIS modal
    function openQrisForm() {
        document.getElementById('qris-modal-title').textContent = 'Tambah QRIS';
        document.getElementById('form-qris').action = '{{ route('superadmin.data-bank.account.store') }}';
        document.getElementById('qris-id').value = '';
        document.getElementById('modal-qris').classList.remove('hidden');
        document.getElementById('modal-qris').classList.add('flex');
    }

    function closeQrisForm() {
        document.getElementById('modal-qris').classList.add('hidden');
        document.getElementById('modal-qris').classList.remove('flex');
    }

    function editQris(id) {
        fetch(`/superadmin/data-bank/account/${id}/edit`)
            .then(r => r.json())
            .then(d => {
                document.getElementById('qris-modal-title').textContent = 'Edit QRIS';
                document.getElementById('form-qris').action = '{{ route('superadmin.data-bank.account.update', ['account' => ':ID']) }}'.replace(':ID', id);
                document.getElementById('qris-id').value = id;
                document.getElementById('qris-nama').value = d.nama;
                document.getElementById('qris-kode').value = d.kode;
                document.getElementById('qris-pemilik').value = d.nama_pemilik || 'RALIVA Fashion';
                document.getElementById('qris-deskripsi').value = d.deskripsi || '';
                document.getElementById('qris-status').value = d.status;
                document.getElementById('modal-qris').classList.remove('hidden');
                document.getElementById('modal-qris').classList.add('flex');
            })
            .catch(() => window.showRalivaToast('Gagal memuat data QRIS.', 'error'));
    }

    // Hapus Bank
    function confirmDeleteBank(id, nama) {
        document.getElementById('form-hapus-bank').action = '{{ route('superadmin.data-bank.hapus', ['bank' => ':ID']) }}'.replace(':ID', id);
        document.getElementById('hapus-bank-text').textContent = 'Bank "' + nama + '" beserta rekening platform-nya akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.';
        document.getElementById('modal-hapus-bank').classList.remove('hidden');
        document.getElementById('modal-hapus-bank').classList.add('flex');
    }

    function closeDeleteBank() {
        document.getElementById('modal-hapus-bank').classList.add('hidden');
        document.getElementById('modal-hapus-bank').classList.remove('flex');
    }

    // Hapus E-Wallet
    function confirmDeleteEwallet(id, nama) {
        document.getElementById('form-hapus-ewallet').action = '{{ route('superadmin.data-bank.account.hapus', ['account' => ':ID']) }}'.replace(':ID', id);
        document.getElementById('hapus-ewallet-text').textContent = 'E-wallet "' + nama + '" akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.';
        document.getElementById('modal-hapus-ewallet').classList.remove('hidden');
        document.getElementById('modal-hapus-ewallet').classList.add('flex');
    }

    function closeDeleteEwallet() {
        document.getElementById('modal-hapus-ewallet').classList.add('hidden');
        document.getElementById('modal-hapus-ewallet').classList.remove('flex');
    }
</script>
@endpush
@endsection