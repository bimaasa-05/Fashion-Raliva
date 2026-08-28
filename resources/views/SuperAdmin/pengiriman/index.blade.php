@extends('layouts.superadmin')

@section('title', 'Pengiriman')

@section('header-title', 'Pengiriman')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau pengiriman dari seluruh toko di platform.')

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Monitor Pengiriman Platform</h2>
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
            <a href="{{ route('superadmin.pengiriman') }}" class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }} font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua</a>
            @foreach(['pending' => 'Pending', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'diterima' => 'Diterima', 'gagal' => 'Gagal'] as $val => $label)
                <a href="{{ route('superadmin.pengiriman', ['status' => $val]) }}" class="px-4 py-2 rounded-lg {{ request('status') === $val ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }} font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    @php
        $statusMap = [
            'pending' => ['Pending', 'bg-surface-container-high text-on-surface-variant'],
            'diproses' => ['Diproses', 'bg-info/10 text-info'],
            'dikirim' => ['Dikirim', 'bg-secondary-container/20 text-secondary'],
            'diterima' => ['Diterima', 'bg-success/10 text-success'],
            'gagal' => ['Gagal', 'bg-error/10 text-error'],
        ];
    @endphp

    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">ID Pesanan</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Kurir</th>
                    <th class="p-4 text-left">No. Resi</th>
                    <th class="p-4 text-left">Estimasi Tiba</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($shipments as $shipment)
                    @php $st = $statusMap[$shipment->status] ?? [$shipment->status, 'bg-surface-container-high text-on-surface-variant']; @endphp
                    <tr data-table-row data-status="{{ $shipment->status }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">{{ $shipment->order->nomor_order ?? '-' }}</td>
                        <td class="p-4 text-on-surface">{{ $shipment->order->store->nama_toko ?? '-' }}</td>
                        <td class="p-4 text-on-surface">{{ $shipment->courier->nama_kurir ?? '-' }}</td>
                        <td class="p-4 font-mono text-on-surface-variant text-xs">{{ $shipment->nomor_resi ?? '-' }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $shipment->estimasi_tiba ? \Carbon\Carbon::parse($shipment->estimasi_tiba)->locale('id')->translatedFormat('d M Y') : '-' }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase border">{{ $st[0] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-on-surface-variant">Belum ada data pengiriman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($shipments->hasPages())
        <div class="pt-4">
            {{ $shipments->links() }}
        </div>
    @endif
</section>
@endsection
