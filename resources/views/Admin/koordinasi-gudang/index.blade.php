@extends('layouts.admin')

@section('title', 'Koordinasi Gudang')

@section('header-title', 'Koordinasi Gudang')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kirim kebutuhan pemenuhan pesanan ke Gudang.')

@section('content')
<div class="space-y-section-gap">
    @if (session('success'))
        <div class="bg-secondary-container/15 border border-secondary/30 text-secondary rounded-lg px-4 py-3 text-sm font-body-md">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-error/10 border border-error/30 text-error rounded-lg px-4 py-3 text-sm font-body-md">{{ session('error') }}</div>
    @endif

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Pesanan Perlu Diambil di Gudang</h2>
        <div class="space-y-gutter">
            @forelse ($pesananDiambil as $o)
            <div class="border border-muted-border rounded-lg p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <p class="font-mono text-sm text-on-surface-variant">{{ $o->kode_pesanan ?? ('#'.$o->order_id) }} • {{ $o->checkout?->user?->nama_lengkap ?? '-' }}</p>
                    <p class="font-title-md text-title-md text-on-surface mt-1">
                        @foreach ($o->items as $it)
                            {{ $it->productVariant?->product?->nama_produk ?? '-' }} ({{ $it->jumlah }})@if (!$loop->last), @endif
                        @endforeach
                    </p>
                    <p class="font-body-md text-sm text-on-surface-variant mt-1">Status: {{ ucfirst($o->status) }}</p>
                </div>
                <button type="button" data-modal-open="modal-kirim-{{ $o->order_id }}" class="px-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium whitespace-nowrap">Kirim Permintaan</button>
            </div>

            <div id="modal-kirim-{{ $o->order_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                <form method="POST" action="{{ route('admin.koordinasi-gudang.kirim') }}" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $o->order_id }}" />
                    <p class="raliva-label text-gold-accent">Kirim Permintaan</p>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">Pesanan {{ $o->kode_pesanan ?? ('#'.$o->order_id) }}</h3>
                    <p class="text-sm text-on-surface-variant mt-3">Kirim permintaan pengambilan barang ke Gudang untuk pesanan ini?</p>
                    <div class="flex gap-3 mt-6">
                        <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Ya, Kirim</button>
                    </div>
                </form>
            </div>
            @empty
            <p class="text-on-surface-variant text-sm py-6 text-center">Tidak ada pesanan yang perlu diambil.</p>
            @endforelse
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Status Permintaan</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Transfer</th>
                        <th class="p-4 text-left">Tujuan</th>
                        <th class="p-4 text-center">Status Gudang</th>
                        <th class="p-4 text-left">Diminta</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($riwayat as $t)
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">#TRF-{{ $t->stock_transfer_id }}</td>
                        <td class="p-4 text-on-surface">{{ $t->toWarehouse?->nama_gudang ?? '-' }}</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ ucfirst($t->status) }}</span></td>
                        <td class="p-4 text-on-surface-variant">{{ optional($t->diminta_pada)->translatedFormat('d M Y, H.i') ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-6 text-center text-on-surface-variant text-sm">Belum ada permintaan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
