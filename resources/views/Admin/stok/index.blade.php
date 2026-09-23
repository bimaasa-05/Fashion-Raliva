@extends('layouts.admin')

@section('title', 'Stok')

@section('header-title', 'Stok')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau stok produk — stok menipis tampil di atas.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">visibility</span>
        <p class="font-body-md text-sm text-on-surface">Mode lihat saja. Pengelolaan stok dilakukan oleh Gudang. Stok menipis otomatis tampil paling atas.</p>
    </div>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Stok</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter mb-8">
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden">
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill pointer-events-none select-none" aria-hidden="true">inventory_2</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Varian Terdata</p>
                <p class="font-title-md text-title-md text-on-surface mt-1">{{ $stocks->total() ?? $stocks->count() }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden">
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill pointer-events-none select-none" aria-hidden="true">check_circle</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Total Unit</p>
                <p class="font-title-md text-title-md text-gold-accent mt-1">{{ number_format($stocks->sum('jumlah_stok'), 0, ',', '.') }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden">
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill pointer-events-none select-none" aria-hidden="true">warning</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Stok Menipis</p>
                <p class="font-title-md text-title-md text-error mt-1">{{ $stocks->filter(fn($w) => $w->jumlah_stok <= ($w->stok_minimum ?: 5))->count() }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low relative overflow-hidden">
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill pointer-events-none select-none" aria-hidden="true">category</span>
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Gudang Terlibat</p>
                <p class="font-title-md text-title-md text-on-surface mt-1">{{ $stocks->pluck('warehouse_id')->unique()->count() }}</p>
            </div>
        </div>

        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Daftar Stok Produk</h2>
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[600px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Gudang</th>
                        <th class="p-4 text-center">Stok</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($stocks as $ws)
                        @php
                            $low = $ws->jumlah_stok <= ($ws->stok_minimum ?: 5);
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4">
                                <p class="text-on-surface">{{ $ws->productVariant?->product?->nama_produk ?? '-' }}</p>
                                <p class="text-on-surface-variant text-xs">{{ $ws->productVariant?->sku ?? '' }}</p>
                            </td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $ws->warehouse?->nama_gudang ?? '-' }}</td>
                            <td class="p-4 text-center font-bold {{ $low ? 'text-error' : 'text-on-surface' }}">{{ $ws->jumlah_stok }}</td>
                            <td class="p-4 text-center">
                                @if ($low)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Menipis</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-bold uppercase border border-green-200">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-8 text-center text-on-surface-variant text-sm">Belum ada data stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($stocks as $ws)
                @php
                    $low = $ws->jumlah_stok <= ($ws->stok_minimum ?: 5);
                @endphp
                <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-on-surface font-bold">{{ $ws->productVariant?->product?->nama_produk ?? '-' }}</p>
                            <p class="text-on-surface-variant text-xs mt-0.5">{{ $ws->productVariant?->sku ?? '' }}</p>
                        </div>
                        @if ($low)
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Menipis</span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-bold uppercase border border-green-200">Aman</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-3 pt-3 border-t border-muted-border">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Gudang</p>
                            <p class="text-sm text-on-surface mt-0.5">{{ $ws->warehouse?->nama_gudang ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Stok</p>
                            <p class="font-bold {{ $low ? 'text-error' : 'text-on-surface' }} mt-0.5">{{ $ws->jumlah_stok }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-6 text-center">Belum ada data stok.</p>
            @endforelse
        </div>
        <div class="md:hidden mt-4">{{ $stocks->links() }}</div>
    </section>
</div>
@endsection
