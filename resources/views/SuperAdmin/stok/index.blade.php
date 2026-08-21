@extends('layouts.superadmin')

@section('title', 'Stok')

@section('header-title', 'Stok')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau ketersediaan stok dari seluruh toko di platform.')

@section('content')
<section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Monitor Stok Platform</h2>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">
            <span class="material-symbols-outlined text-[14px]">visibility</span> Mode Pantau
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-center">Stok</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4">
                        <p class="text-on-surface">Oversized Linen Shirt</p>
                        <p class="text-on-surface-variant text-xs">Kemeja • S, M, L, XL</p>
                    </td>
                    <td class="p-4 text-on-surface">LUNARA Fashion</td>
                    <td class="p-4 text-center font-bold text-on-surface">24</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aman</span></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4">
                        <p class="text-on-surface">Straight Fit Pants</p>
                        <p class="text-on-surface-variant text-xs">Celana • 28–34</p>
                    </td>
                    <td class="p-4 text-on-surface">LUNARA Fashion</td>
                    <td class="p-4 text-center font-bold text-error">3</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Menipis</span></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4">
                        <p class="text-on-surface">Velvet Midi Dress</p>
                        <p class="text-on-surface-variant text-xs">Dress • S, M</p>
                    </td>
                    <td class="p-4 text-on-surface">Velvet Closet</td>
                    <td class="p-4 text-center font-bold text-on-surface">17</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aman</span></td>
                </tr>
                <tr class="hover:bg-surface-container-low transition-colors">
                    <td class="p-4">
                        <p class="text-on-surface">Denim Jacket Vintage</p>
                        <p class="text-on-surface-variant text-xs">Outerwear • M, L, XL</p>
                    </td>
                    <td class="p-4 text-on-surface">KAYANA Apparel</td>
                    <td class="p-4 text-center font-bold text-error">0</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Habis</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection
