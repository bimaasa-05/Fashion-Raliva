@extends('layouts.superadmin')

@section('title', 'Pengembalian Dana')

@section('header-title', 'Pengembalian Dana')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Monitor dan tangani kasus refund yang dieskalasikan ke platform.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface">Ringkasan Refund</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 hover:border-gold-accent transition-colors">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kasus Aktif</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">7</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">perlu keputusan</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 hover:border-gold-accent transition-colors">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Disetujui Bulan Ini</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">42</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">kasus selesai</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 hover:border-gold-accent transition-colors">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Nilai Refund</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp 96,8JT</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">bulan ini</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 hover:border-gold-accent transition-colors">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Rata-rata Penyelesaian</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">2,4 hari</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">sejak eskalasi</span>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface">Kasus Dieskalasikan</h2>

        <div class="flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-primary text-on-primary font-label-sm text-label-sm uppercase rounded transition-colors">Semua</button>
            <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Menunggu Keputusan</button>
            <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Disetujui</button>
            <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Ditolak</button>
        </div>

        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg">
            <table class="w-full min-w-[950px]">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Refund</th>
                        <th class="p-4 text-left">ID Pesanan</th>
                        <th class="p-4 text-left">Pelanggan</th>
                        <th class="p-4 text-left">Alasan</th>
                        <th class="p-4 text-right">Jumlah</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">REF-2026082101</td>
                        <td class="p-4 font-mono text-on-surface-variant">RLV-20260812</td>
                        <td class="p-4">
                            <p class="text-on-surface">Sarah Jenkins</p>
                            <p class="text-on-surface-variant text-xs">Lunara Fashion</p>
                        </td>
                        <td class="p-4 text-on-surface">Barang tidak sesuai deskripsi</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 850.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu Keputusan</span>
                        </td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Setujui</button>
                            <button class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Tolak</button>
                        </td>
                    </tr>
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">REF-2026082002</td>
                        <td class="p-4 font-mono text-on-surface-variant">RLV-20260809</td>
                        <td class="p-4">
                            <p class="text-on-surface">Andi Pratama</p>
                            <p class="text-on-surface-variant text-xs">Velvet Closet</p>
                        </td>
                        <td class="p-4 text-on-surface">Paket tidak pernah tiba</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 320.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu Keputusan</span>
                        </td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Setujui</button>
                            <button class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Tolak</button>
                        </td>
                    </tr>
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">REF-2026081903</td>
                        <td class="p-4 font-mono text-on-surface-variant">RLV-20260801</td>
                        <td class="p-4">
                            <p class="text-on-surface">Dewi Lestari</p>
                            <p class="text-on-surface-variant text-xs">Atelier Rina</p>
                        </td>
                        <td class="p-4 text-on-surface">Produk rusak saat pengiriman</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 1.150.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disetujui</span>
                        </td>
                        <td class="p-4 text-right"><span class="text-on-surface-variant text-xs uppercase">Selesai</span></td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">REF-2026081804</td>
                        <td class="p-4 font-mono text-on-surface-variant">RLV-20260728</td>
                        <td class="p-4">
                            <p class="text-on-surface">Budi Santoso</p>
                            <p class="text-on-surface-variant text-xs">Urban Thread</p>
                        </td>
                        <td class="p-4 text-on-surface">Bukti pengiriman tidak valid</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 275.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Ditolak</span>
                        </td>
                        <td class="p-4 text-right"><span class="text-on-surface-variant text-xs uppercase">Selesai</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
