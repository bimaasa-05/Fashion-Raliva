@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('header-title', 'Verifikasi Pembayaran')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Periksa bukti pembayaran dan setujui atau tolak dengan alasan.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Menunggu Verifikasi</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-mono text-sm text-on-surface-variant">#RLV-2081 • Sarah Jenkins</p>
                        <p class="font-title-md text-title-md text-gold-accent mt-1">Rp 890.000</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Transfer BCA</span>
                </div>
                <div class="border border-muted-border rounded-lg bg-surface-container-low p-4 flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-on-surface-variant">receipt_long</span>
                        <span class="font-body-md text-sm text-on-surface">bukti-transfer-bca-0821.jpg</span>
                    </div>
                    <button class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Lihat Bukti</button>
                </div>
                <div class="flex gap-3">
                    <button class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Setujui</button>
                    <button class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Tolak</button>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="font-mono text-sm text-on-surface-variant">#RLV-2078 • Andi Pratama</p>
                        <p class="font-title-md text-title-md text-gold-accent mt-1">Rp 320.000</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">E-Wallet</span>
                </div>
                <div class="border border-muted-border rounded-lg bg-surface-container-low p-4 flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-on-surface-variant">receipt_long</span>
                        <span class="font-body-md text-sm text-on-surface">bukti-ewallet-2078.png</span>
                    </div>
                    <button class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Lihat Bukti</button>
                </div>
                <div class="flex gap-3">
                    <button class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Setujui</button>
                    <button class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Tolak</button>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Riwayat Verifikasi</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[800px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Pesanan</th>
                        <th class="p-4 text-left">Pelanggan</th>
                        <th class="p-4 text-right">Nominal</th>
                        <th class="p-4 text-center">Keputusan</th>
                        <th class="p-4 text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">#RLV-2076</td>
                        <td class="p-4 text-on-surface">Dewi Lestari</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 1.150.000</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disetujui</span></td>
                        <td class="p-4 text-on-surface-variant">Hari ini, 09.15</td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">#RLV-2072</td>
                        <td class="p-4 text-on-surface">Maya Rossi</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 780.000</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Ditolak</span></td>
                        <td class="p-4 text-on-surface-variant">Kemarin, 16.40</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
