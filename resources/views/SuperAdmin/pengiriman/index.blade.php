@extends('layouts.superadmin')

@section('title', 'Pengiriman')

@section('header-title', 'Pengiriman')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau pengiriman dari seluruh toko di platform.')

@section('content')
<section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Monitor Pengiriman Platform</h2>
        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">
            <span class="material-symbols-outlined text-[14px]">visibility</span> Mode Pantau
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">ID Pesanan</th>
                    <th class="p-4 text-left">Toko</th>
                    <th class="p-4 text-left">Kurir</th>
                    <th class="p-4 text-left">No. Resi</th>
                    <th class="p-4 text-left">Tujuan</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2075</td>
                    <td class="p-4 text-on-surface">LUNARA Fashion</td>
                    <td class="p-4 text-on-surface">JNE Reguler</td>
                    <td class="p-4 font-mono text-on-surface">JNE2608210041</td>
                    <td class="p-4 text-on-surface">Bandung</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Dalam Perjalanan</span></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2071</td>
                    <td class="p-4 text-on-surface">Velvet Closet</td>
                    <td class="p-4 text-on-surface">SiCepat YES</td>
                    <td class="p-4 font-mono text-on-surface">SC2608200233</td>
                    <td class="p-4 text-on-surface">Jakarta Selatan</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Dalam Perjalanan</span></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2066</td>
                    <td class="p-4 text-on-surface">KAYANA Apparel</td>
                    <td class="p-4 text-on-surface">AntareAja</td>
                    <td class="p-4 font-mono text-on-surface">AA2608190117</td>
                    <td class="p-4 text-on-surface">Surabaya</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-primary-fixed-dim text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Terkirim</span></td>
                </tr>
                <tr class="hover:bg-surface-container-low transition-colors">
                    <td class="p-4 font-mono text-on-surface">#RLV-2062</td>
                    <td class="p-4 text-on-surface">Atelier Rina</td>
                    <td class="p-4 text-on-surface">JNE Reguler</td>
                    <td class="p-4 font-mono text-on-surface">JNE2608180090</td>
                    <td class="p-4 text-on-surface">Semarang</td>
                    <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Terlambat</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
@endsection
