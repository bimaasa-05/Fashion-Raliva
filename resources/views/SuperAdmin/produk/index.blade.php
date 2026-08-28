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
                    <th class="p-4 text-center w-12">No.</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Kategori</th>
                    <th class="p-4 text-left">Tipe</th>
                    <th class="p-4 text-right">Harga</th>
                    <th class="p-4 text-center">Status Moderasi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse ($products as $produk)
                    @php $rowNumber = $loop->iteration + ($products->currentPage() - 1) * $products->perPage(); @endphp
                    @php
                        $statusLabel = match ($produk->status) {
                            'aktif' => ['Disetujui', 'bg-secondary-container/20 text-secondary border-secondary/20'],
                            'pending' => ['Menunggu', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                            'ditolak' => ['Ditolak', 'bg-error/10 text-error border-error/20'],
                            'nonaktif' => ['Nonaktif', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                            'draft' => ['Draft', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                            'arsip' => ['Arsip', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                            default => [ucfirst($produk->status), 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                        };
                    @endphp
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono">{{ $rowNumber }}</td>
                        <td class="p-4 text-on-surface">{{ $produk->nama_produk }}</td>
                        <td class="p-4 text-on-surface">{{ $produk->store->nama_toko ?? '-' }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $produk->category->nama_kategori ?? '-' }}</td>
                        <td class="p-4"><span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ ucfirst($produk->tipe_produk) }}</span></td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp {{ number_format((float) $produk->harga_dasar, 0, ',', '.') }}</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $statusLabel[1] }} text-[10px] font-bold uppercase border">{{ $statusLabel[0] }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-on-surface-variant">Belum ada produk terdaftar di platform.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
