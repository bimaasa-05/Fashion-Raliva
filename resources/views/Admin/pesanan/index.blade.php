@extends('layouts.admin')

@section('title', 'Data Pesanan')

@section('header-title', 'Data Pesanan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Lihat detail dan proses pesanan sesuai alur status.')

@section('content')
<section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Daftar Pesanan Toko</h2>

    <div class="mb-6 flex flex-wrap gap-2">
        <button class="px-4 py-2 bg-primary text-on-primary font-label-sm text-label-sm uppercase rounded transition-colors">Semua</button>
        <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Baru</button>
        <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Diproses</button>
        <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Dikirim</button>
        <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Selesai</button>
        <button class="px-4 py-2 bg-surface-container-low text-on-surface border border-muted-border font-label-sm text-label-sm uppercase rounded hover:bg-surface-container transition-colors">Dibatalkan</button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">ID Pesanan</th>
                    <th class="p-4 text-left">Pelanggan</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2081</td>
                    <td class="p-4">
                        <p class="text-on-surface">Sarah Jenkins</p>
                        <p class="text-on-surface-variant text-xs">sarah@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface">3 produk</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 890.000</td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Baru</span>
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <button class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Proses</button>
                        <button class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                    </td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2079</td>
                    <td class="p-4">
                        <p class="text-on-surface">Dewi Lestari</p>
                        <p class="text-on-surface-variant text-xs">dewi.l@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface">2 produk</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 1.150.000</td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Diproses</span>
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <button class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Kirim ke Gudang</button>
                        <button class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                    </td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2075</td>
                    <td class="p-4">
                        <p class="text-on-surface">Budi Santoso</p>
                        <p class="text-on-surface-variant text-xs">budi.s@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface">1 produk</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 455.000</td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Dikirim</span>
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <button class="px-3 py-1.5 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                    </td>
                </tr>
                <tr class="hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2070</td>
                    <td class="p-4">
                        <p class="text-on-surface">Maya Rossi</p>
                        <p class="text-on-surface-variant text-xs">maya.r@email.com</p>
                    </td>
                    <td class="p-4 text-on-surface">4 produk</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 2.340.000</td>
                    <td class="p-4 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Dibatalkan</span>
                    </td>
                    <td class="p-4 text-right whitespace-nowrap">
                        <button class="px-3 py-1.5 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection
