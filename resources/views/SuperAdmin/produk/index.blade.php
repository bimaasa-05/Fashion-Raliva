@extends('layouts.superadmin')

@section('title', 'Data Produk')

@section('header-title', 'Data Produk')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Lihat katalog produk dari seluruh toko di platform.')

@section('content')
<section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Katalog Produk Platform</h2>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">
            <span class="material-symbols-outlined text-[14px]">visibility</span> Mode Lihat
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Kategori</th>
                    <th class="p-4 text-right">Harga</th>
                    <th class="p-4 text-center">Status Moderasi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 text-on-surface">Oversized Linen Shirt</td>
                    <td class="p-4 text-on-surface">LUNARA Fashion</td>
                    <td class="p-4 text-on-surface-variant">Atasan</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 289.000</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disetujui</span></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 text-on-surface">Straight Fit Pants</td>
                    <td class="p-4 text-on-surface">LUNARA Fashion</td>
                    <td class="p-4 text-on-surface-variant">Bawahan</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 329.000</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disetujui</span></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 text-on-surface">Velvet Midi Dress</td>
                    <td class="p-4 text-on-surface">Velvet Closet</td>
                    <td class="p-4 text-on-surface-variant">Dress</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 549.000</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu</span></td>
                </tr>
                <tr class="hover:bg-surface-container-low transition-colors">
                    <td class="p-4 text-on-surface">Denim Jacket Vintage</td>
                    <td class="p-4 text-on-surface">KAYANA Apparel</td>
                    <td class="p-4 text-on-surface-variant">Outerwear</td>
                    <td class="p-4 text-right font-bold text-gold-accent">Rp 479.000</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Ditolak</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection
