@extends('layouts.admin')

@section('title', 'Koordinasi Gudang')

@section('header-title', 'Koordinasi Gudang')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kirim kebutuhan pemenuhan pesanan ke Gudang.')

@section('content')
<div class="space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Pesanan Perlu Diambil di Gudang</h2>
        <div class="space-y-gutter">
            <div class="border border-muted-border rounded-lg p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <p class="font-mono text-sm text-on-surface-variant">#RLV-2079 • Dewi Lestari</p>
                    <p class="font-title-md text-title-md text-on-surface mt-1">Relaxed Blazer (M) + Straight Fit Pants (30)</p>
                    <p class="font-body-md text-sm text-on-surface-variant mt-1">Rak: B-12 • Siap dipacking</p>
                </div>
                <button class="px-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium whitespace-nowrap">Kirim Permintaan</button>
            </div>
            <div class="border border-muted-border rounded-lg p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <p class="font-mono text-sm text-on-surface-variant">#RLV-2081 • Sarah Jenkins</p>
                    <p class="font-title-md text-title-md text-on-surface mt-1">Oversized Linen Shirt (L) x3</p>
                    <p class="font-body-md text-sm text-on-surface-variant mt-1">Rak: A-04 • Stok tersedia</p>
                </div>
                <button class="px-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium whitespace-nowrap">Kirim Permintaan</button>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Status Permintaan</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Pesanan</th>
                        <th class="p-4 text-left">Barang</th>
                        <th class="p-4 text-center">Status Gudang</th>
                        <th class="p-4 text-left">Diperbarui</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">#RLV-2077</td>
                        <td class="p-4 text-on-surface">Oversized Linen Shirt (M)</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Disiapkan</span></td>
                        <td class="p-4 text-on-surface-variant">Hari ini, 08.45</td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">#RLV-2074</td>
                        <td class="p-4 text-on-surface">Straight Fit Pants (32)</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Diterima</span></td>
                        <td class="p-4 text-on-surface-variant">Kemarin, 17.20</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
