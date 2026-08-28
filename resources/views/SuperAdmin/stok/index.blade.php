@extends('layouts.superadmin')

@section('title', 'Stok')

@section('header-title', 'Stok')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau ketersediaan stok dari seluruh toko di platform.')

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Monitor Stok Platform</h2>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">
            <span class="material-symbols-outlined text-[14px]">visibility</span> Mode Pantau
        </span>
    </div>

    <div class="mb-6 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
        </div>
        <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
        <div data-chip-group data-chip-key="status" class="flex flex-wrap gap-2">
            <button type="button" data-chip="semua" class="px-4 py-2 rounded-lg bg-deep-onyx text-on-primary border border-deep-onyx font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua</button>
            <button type="button" data-chip="aman" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Aman</button>
            <button type="button" data-chip="menipis" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Menipis</button>
            <button type="button" data-chip="habis" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Habis</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-center w-12">No.</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-left">SKU</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-center">Stok</th>
                    <th class="p-4 text-center">Direservasi</th>
                    <th class="p-4 text-center">Minimum</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($stocks as $stock)
                    @php $rowNumber = $loop->iteration + ($stocks->currentPage() - 1) * $stocks->perPage(); @endphp
                    <tr data-table-row data-status="{{ $stock->status_stok }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono">{{ $rowNumber }}</td>
                        <td class="p-4">
                            <p class="text-on-surface">{{ $stock->nama_produk }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $stock->warna ? $stock->warna.' • ' : '' }}{{ $stock->ukuran ?? '-' }}</p>
                        </td>
                        <td class="p-4 font-mono text-on-surface-variant text-xs">{{ $stock->sku }}</td>
                        <td class="p-4 text-on-surface">{{ $stock->nama_toko }}</td>
                        <td class="p-4 text-center font-bold {{ $stock->status_stok === 'habis' || $stock->status_stok === 'menipis' ? 'text-error' : 'text-on-surface' }}">{{ $stock->jumlah_stok }}</td>
                        <td class="p-4 text-center text-on-surface-variant">{{ $stock->jumlah_direservasi }}</td>
                        <td class="p-4 text-center text-on-surface-variant">{{ $stock->stok_minimum }}</td>
                        <td class="p-4 text-center">
                            @if($stock->status_stok === 'aman')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aman</span>
                            @elseif($stock->status_stok === 'menipis')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Menipis</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Habis</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-on-surface-variant">Belum ada data stok tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($stocks->hasPages())
        <div class="pt-4">
            {{ $stocks->links() }}
        </div>
    @endif
</section>
@endsection
