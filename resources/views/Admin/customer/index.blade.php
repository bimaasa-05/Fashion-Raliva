@extends('layouts.admin')

@section('title', 'Data Customer')

@section('header-title', 'Data Customer')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Data customer yang berhubungan dengan toko dan riwayat pesanannya.')

@section('content')
<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Customer</h2>
        <div class="relative md:w-72">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input data-table-search class="raliva-search" placeholder="Cari nama atau email..." type="text" />
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[750px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">Customer</th>
                    <th class="p-4 text-left">Bergabung</th>
                    <th class="p-4 text-center">Total Pesanan</th>
                    <th class="p-4 text-right">Total Belanja</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse ($customers as $c)
                    <tr data-table-row data-search="{{ $c->nama_lengkap }} {{ $c->email }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4">
                            <p class="text-on-surface">{{ $c->nama_lengkap }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $c->email }}</p>
                        </td>
                        <td class="p-4 text-on-surface-variant">{{ $c->created_at?->translatedFormat('M Y') }}</td>
                        <td class="p-4 text-center text-on-surface">{{ $c->total_pesanan }}</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp {{ number_format($c->orders_sum_grand_total ?? 0, 0, ',', '.') }}</td>
                        <td class="p-4 text-right"><button type="button" onclick="showRalivaToast('Riwayat pesanan customer (demo).', 'history')" class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Riwayat</button></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-8 text-center text-on-surface-variant">Belum ada customer terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
