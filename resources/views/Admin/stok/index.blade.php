@extends('layouts.admin')

@section('title', 'Stok')

@section('header-title', 'Stok')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Lihat atau perbarui stok sesuai pembagian tugas dengan Gudang.')

@section('content')
<div class="space-y-section-gap">
    @if (session('success'))
        <div class="bg-secondary-container/15 border border-secondary/30 text-secondary rounded-lg px-4 py-3 text-sm font-body-md">{{ session('success') }}</div>
    @endif

    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Pembaruan stok besar (restock massal) dikoordinasikan dengan Gudang. Kamu dapat menyesuaikan stok satuan untuk mendukung proses order.</p>
    </div>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Stok</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter mb-8">
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low">
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Varian Terdata</p>
                <p class="font-title-md text-title-md text-on-surface mt-1">{{ $stocks->total() ?? $stocks->count() }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low">
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Total Unit</p>
                <p class="font-title-md text-title-md text-gold-accent mt-1">{{ number_format($stocks->sum('jumlah_stok'), 0, ',', '.') }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low">
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Stok Menipis</p>
                <p class="font-title-md text-title-md text-error mt-1">{{ $stocks->filter(fn($w) => $w->jumlah_stok <= ($w->stok_minimum ?: 5))->count() }}</p>
            </div>
            <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low">
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Gudang Terlibat</p>
                <p class="font-title-md text-title-md text-on-surface mt-1">{{ $stocks->pluck('warehouse_id')->unique()->count() }}</p>
            </div>
        </div>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Kelola Stok Produk</h2>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Gudang</th>
                        <th class="p-4 text-center">Stok Saat Ini</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Perbarui</th>
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
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aman</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.stok.update', $ws) }}" class="flex items-center justify-center gap-2">
                                @csrf
                                <input name="jumlah_stok" class="w-20 bg-transparent border border-muted-border rounded p-2 text-center font-body-md text-sm focus:outline-none focus:border-gold-accent" type="number" value="{{ $ws->jumlah_stok }}" min="0" />
                                <button type="submit" class="px-3 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors">Simpan</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-8 text-center text-on-surface-variant text-sm">Belum ada data stok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $stocks->links() }}</div>
    </section>
</div>
@endsection
