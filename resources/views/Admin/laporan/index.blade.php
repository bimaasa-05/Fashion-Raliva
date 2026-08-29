@extends('layouts.admin')

@section('title', 'Laporan Operasional')

@section('header-title', 'Laporan Operasional')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Laporan operasional toko yang kamu tugaskan.')

@section('content')
<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Laporan ini hanya mencakup operasional toko yang kamu tugaskan, sesuai permission dari Owner.</p>
    </div>

    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Kinerja Operasional 30 Hari</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pesanan Diproses</span>
                <span class="raliva-figure text-[26px] text-on-surface">186</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pengiriman Tepat Waktu</span>
                <span class="raliva-figure text-[26px] text-secondary">94%</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_shipping</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komplain Ditangani</span>
                <span class="raliva-figure text-[26px] text-on-surface">22</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">support_agent</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Refund Diproses</span>
                <span class="raliva-figure text-[26px] text-on-surface">9</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">assignment_return</span>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Rincian per Toko</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-center">Pesanan Diproses</th>
                        <th class="p-4 text-center">Dikirim</th>
                        <th class="p-4 text-center">Komplain</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-on-surface">LUNARA Fashion</td>
                        <td class="p-4 text-center text-on-surface">124</td>
                        <td class="p-4 text-center text-on-surface">118</td>
                        <td class="p-4 text-center text-on-surface">14</td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-on-surface">Velvet Closet</td>
                        <td class="p-4 text-center text-on-surface">62</td>
                        <td class="p-4 text-center text-on-surface">60</td>
                        <td class="p-4 text-center text-on-surface">8</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
