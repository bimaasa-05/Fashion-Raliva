@extends('layouts.superadmin')

@section('title', 'Gudang')

@section('header-title', 'Gudang')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau data gudang dari seluruh toko di platform.')

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Monitor Gudang Platform</h2>
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
            <a href="{{ route('superadmin.gudang') }}" class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }} font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua</a>
            <a href="{{ route('superadmin.gudang', ['status' => 'aktif']) }}" class="px-4 py-2 rounded-lg {{ request('status') === 'aktif' ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }} font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Aktif</a>
            <a href="{{ route('superadmin.gudang', ['status' => 'nonaktif']) }}" class="px-4 py-2 rounded-lg {{ request('status') === 'nonaktif' ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }} font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Nonaktif</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[850px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">Nama Gudang</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Alamat</th>
                    <th class="p-4 text-center">Total Item</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse($warehouses as $wh)
                    <tr data-table-row data-status="{{ $wh->status }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-on-surface font-bold">{{ $wh->nama_gudang }}</td>
                        <td class="p-4 text-on-surface">{{ $wh->store->nama_toko ?? '-' }}</td>
                        <td class="p-4 text-on-surface-variant text-xs">{{ Str::limit($wh->alamat, 40) }}</td>
                        <td class="p-4 text-center text-on-surface">{{ $wh->stocks_count }}</td>
                        <td class="p-4 text-center">
                            @if($wh->status === 'aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-on-surface-variant">Belum ada data gudang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($warehouses->hasPages())
        <div class="pt-4">
            {{ $warehouses->links() }}
        </div>
    @endif
</section>
@endsection
