@extends('layouts.superadmin')

@section('title', 'Pencairan Dana')

@section('header-title', 'Pencairan Dana')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Verifikasi dan setujui pengajuan pencairan dana Owner.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface">Ringkasan Pengajuan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-container-margin">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-surface-container-high flex items-center justify-center rounded-full">
                        <span class="material-symbols-outlined text-on-surface">pending_actions</span>
                    </div>
                    <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Pengajuan Menunggu</h3>
                </div>
                <p class="font-headline-lg-mobile text-headline-lg-mobile text-primary">24</p>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">Menunggu verifikasi dan persetujuan</p>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-container-margin">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-secondary-container flex items-center justify-center rounded-full">
                        <span class="material-symbols-outlined text-secondary">account_balance_wallet</span>
                    </div>
                    <h3 class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Total Nominal Menunggu</h3>
                </div>
                <p class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp 124.500.000</p>
                <p class="font-body-md text-body-md text-on-surface-variant mt-2">Total nominal diajukan Owner</p>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-element-gap">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface">Daftar Pengajuan Pencairan</h2>
            <div class="flex gap-4">
                <button class="border border-outline px-4 py-2 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">filter_list</span> Filter
                </button>
                <button class="bg-deep-onyx text-on-primary px-4 py-2 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">download</span> Ekspor
                </button>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low">
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">Toko / Pemilik</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">Detail Pengajuan</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">Info Bank</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase">Status</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-on-surface-variant uppercase text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-muted-border font-body-md text-sm">
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm shrink-0">LF</div>
                                    <div>
                                        <p class="font-title-md text-title-md text-primary">LUNARA Fashion</p>
                                        <p class="text-on-surface-variant">Sarah Jenkins</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-title-md text-title-md text-gold-accent">Rp 12.500.000</p>
                                <p class="text-on-surface-variant">21 Agu 2026</p>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-primary">BCA</p>
                                <p class="text-on-surface-variant">**** **** 4321</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high border border-outline-variant font-label-sm text-xs text-on-surface uppercase">Menunggu</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="w-8 h-8 flex items-center justify-center border border-outline text-on-surface hover:bg-error hover:text-on-error hover:border-error transition-colors"
                                        onclick="document.getElementById('reject-dialog').classList.remove('hidden')" title="Tolak">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                    <button class="w-8 h-8 flex items-center justify-center bg-deep-onyx text-on-primary hover:opacity-80 transition-opacity"
                                        onclick="document.getElementById('approve-dialog').classList.remove('hidden')" title="Setujui">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-variant text-on-surface flex items-center justify-center font-label-sm shrink-0">NS</div>
                                    <div>
                                        <p class="font-title-md text-title-md text-primary">NOIRÉ Studio</p>
                                        <p class="text-on-surface-variant">David Chen</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-title-md text-title-md text-gold-accent">Rp 8.750.000</p>
                                <p class="text-on-surface-variant">20 Agu 2026</p>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-primary">Mandiri</p>
                                <p class="text-on-surface-variant">**** **** 9876</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high border border-outline-variant font-label-sm text-xs text-on-surface uppercase">Menunggu</span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="w-8 h-8 flex items-center justify-center border border-outline text-on-surface hover:bg-error hover:text-on-error hover:border-error transition-colors" title="Tolak">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                    <button class="w-8 h-8 flex items-center justify-center bg-deep-onyx text-on-primary hover:opacity-80 transition-opacity" title="Setujui">
                                        <span class="material-symbols-outlined text-sm">check</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#f5ebe0] text-secondary flex items-center justify-center font-label-sm shrink-0">KA</div>
                                    <div>
                                        <p class="font-title-md text-title-md text-primary">KAYANA Apparel</p>
                                        <p class="text-on-surface-variant">Maya Rossi</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-title-md text-title-md text-gold-accent">Rp 5.200.000</p>
                                <p class="text-on-surface-variant">19 Agu 2026</p>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-primary">BRI</p>
                                <p class="text-on-surface-variant">**** **** 1122</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-xs uppercase border border-secondary/20">Diproses</span>
                            </td>
                            <td class="py-4 px-6 text-right"><span class="text-on-surface-variant text-xs uppercase">—</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-scrim/50 backdrop-blur-sm" id="approve-dialog">
    <div class="bg-surface-container-lowest border border-muted-border p-section-gap max-w-md w-full shadow-2xl rounded-lg">
        <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-primary mb-4">Konfirmasi Pencairan</h3>
        <p class="font-body-md text-body-md text-on-surface-variant mb-8">Anda akan menyetujui pencairan sebesar <span class="font-title-md text-gold-accent">Rp 12.500.000</span> ke LUNARA Fashion. Tindakan ini akan menandai pengajuan sebagai diproses.</p>
        <div class="flex justify-end gap-4">
            <button class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors"
                onclick="document.getElementById('approve-dialog').classList.add('hidden')">Batal</button>
            <button class="bg-deep-onyx text-on-primary px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity">Konfirmasi Persetujuan</button>
        </div>
    </div>
</div>

<div class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 bg-scrim/50 backdrop-blur-sm" id="reject-dialog">
    <div class="bg-surface-container-lowest border border-error/20 p-section-gap max-w-md w-full shadow-2xl rounded-lg">
        <h3 class="font-headline-lg-mobile text-headline-lg-mobile text-error mb-4">Tolak Pencairan</h3>
        <p class="font-body-md text-body-md text-on-surface-variant mb-4">Apakah Anda yakin ingin menolak pengajuan pencairan dari LUNARA Fashion?</p>
        <div class="mb-8">
            <label class="block font-label-sm text-label-sm text-on-surface-variant mb-2 uppercase">Alasan Penolakan</label>
            <textarea class="w-full border border-muted-border bg-surface-container-low p-3 font-body-md text-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary h-24" placeholder="Tulis alasan..."></textarea>
        </div>
        <div class="flex justify-end gap-4">
            <button class="border border-outline px-6 py-3 text-primary font-label-sm text-label-sm uppercase hover:bg-surface-container transition-colors"
                onclick="document.getElementById('reject-dialog').classList.add('hidden')">Batal</button>
            <button class="bg-error text-on-error px-6 py-3 font-label-sm text-label-sm uppercase hover:opacity-90 transition-opacity"
                onclick="document.getElementById('reject-dialog').classList.add('hidden')">Tolak Pengajuan</button>
        </div>
    </div>
</div>
@endsection
