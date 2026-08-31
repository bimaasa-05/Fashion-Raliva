@extends('layouts.superadmin')

@section('title', 'Produksi')

@section('header-title', 'Produksi')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau permintaan dan status produksi dari seluruh toko.')

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Monitor Produksi Platform</h2>
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
            <a href="{{ route('superadmin.produksi') }}" class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }} font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua</a>
            @foreach(['requested' => 'Requested', 'diproses' => 'Diproses', 'menunggu_qc' => 'Menunggu QC', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $val => $label)
                <a href="{{ route('superadmin.produksi', ['status' => $val]) }}" class="px-4 py-2 rounded-lg {{ request('status') === $val ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }} font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    @php
        $statusMap = [
            'requested' => ['Requested', 'bg-surface-container-high text-on-surface-variant'],
            'diproses' => ['Diproduksi', 'bg-secondary-container/20 text-secondary'],
            'menunggu_qc' => ['Menunggu QC', 'bg-info/10 text-info'],
            'selesai' => ['Selesai', 'bg-success/10 text-success'],
            'dibatalkan' => ['Dibatalkan', 'bg-error/10 text-error'],
        ];
    @endphp

    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-center w-12">No.</th>
                    <th class="p-4 text-left">Nomor Produksi</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-center">Jumlah</th>
                    <th class="p-4 text-center">Prioritas</th>
                    <th class="p-4 text-left">Catatan</th>
                    <th class="p-4 text-left">Dimulai</th>
                    <th class="p-4 text-left">Selesai</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($productions as $prod)
                    @php $rowNumber = $loop->iteration + ($productions->currentPage() - 1) * $productions->perPage(); @endphp
                    @php $st = $statusMap[$prod->status] ?? [$prod->status, 'bg-surface-container-high text-on-surface-variant']; @endphp
                    <tr data-table-row data-status="{{ $prod->status }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono">{{ $rowNumber }}</td>
                        <td class="p-4 font-mono text-on-surface">{{ $prod->nomor_produksi }}</td>
                        <td class="p-4 text-on-surface">{{ $prod->store->nama_toko ?? '-' }}</td>
                        <td class="p-4 text-on-surface">{{ $prod->items->first()?->productVariant?->product?->nama_produk ?? '-' }}</td>
                        <td class="p-4 text-center text-on-surface">{{ $prod->items->sum('jumlah_diminta') }}</td>
                        <td class="p-4 text-center">
                            @php
                                $prioMap = [
                                    'rendah' => ['Rendah', 'bg-surface-container-high text-on-surface-variant'],
                                    'normal' => ['Normal', 'bg-info/10 text-info'],
                                    'tinggi' => ['Tinggi', 'bg-secondary-container/20 text-secondary'],
                                    'urgent' => ['Urgent', 'bg-error/10 text-error'],
                                ];
                                $prio = $prioMap[$prod->prioritas] ?? ['-', 'bg-surface-container-high text-on-surface-variant'];
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $prio[1] }} text-[10px] font-bold uppercase border">{{ $prio[0] }}</span>
                        </td>
                        <td class="p-4 text-on-surface-variant text-xs max-w-[200px]" title="{{ $prod->catatan }}">{{ \Illuminate\Support\Str::limit($prod->catatan ?? '-', 40) }}</td>
                        <td class="p-4 text-on-surface-variant text-xs">{{ $prod->dimulai_pada ? \Carbon\Carbon::parse($prod->dimulai_pada)->locale('id')->translatedFormat('d M Y') : '-' }}</td>
                        <td class="p-4 text-on-surface-variant text-xs">{{ $prod->selesai_pada ? \Carbon\Carbon::parse($prod->selesai_pada)->locale('id')->translatedFormat('d M Y') : '-' }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase border">{{ $st[0] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="p-8 text-center text-on-surface-variant">Belum ada data produksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($productions->hasPages())
        <div class="pt-4">
            {{ $productions->links() }}
        </div>
    @endif
</section>
@endsection
