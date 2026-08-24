@extends('layouts.superadmin')

@section('title', 'Saldo Toko')

@section('header-title', 'Saldo Toko')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau saldo dan mutasi keuangan seluruh toko di platform.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Saldo per Toko</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">LUNARA Fashion</span>
                    <span class="material-symbols-outlined text-gold-accent">account_balance_wallet</span>
                </div>
                <div class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp 45.280.000</div>
                <div class="pt-4 border-t border-muted-border flex justify-between font-body-md text-sm">
                    <span class="text-on-surface-variant">Menunggu Cair</span>
                    <span class="text-on-surface font-bold">Rp 12.500.000</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">Velvet Closet</span>
                    <span class="material-symbols-outlined text-gold-accent">account_balance_wallet</span>
                </div>
                <div class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp 21.760.000</div>
                <div class="pt-4 border-t border-muted-border flex justify-between font-body-md text-sm">
                    <span class="text-on-surface-variant">Menunggu Cair</span>
                    <span class="text-on-surface font-bold">Rp 0</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">KAYANA Apparel</span>
                    <span class="material-symbols-outlined text-gold-accent">account_balance_wallet</span>
                </div>
                <div class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp 8.940.000</div>
                <div class="pt-4 border-t border-muted-border flex justify-between font-body-md text-sm">
                    <span class="text-on-surface-variant">Menunggu Cair</span>
                    <span class="text-on-surface font-bold">Rp 5.200.000</span>
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Mutasi Terbaru</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[800px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-left">Keterangan</th>
                        <th class="p-4 text-right">Nominal</th>
                        <th class="p-4 text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-on-surface">LUNARA Fashion</td>
                        <td class="p-4 text-on-surface">Pendapatan pesanan #RLV-2075</td>
                        <td class="p-4 text-right font-bold text-secondary">+ Rp 1.250.000</td>
                        <td class="p-4 text-on-surface-variant">Hari ini, 10.20</td>
                    </tr>
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-on-surface">KAYANA Apparel</td>
                        <td class="p-4 text-on-surface">Refund #REF-2585</td>
                        <td class="p-4 text-right font-bold text-error">− Rp 780.000</td>
                        <td class="p-4 text-on-surface-variant">Kemarin, 16.40</td>
                    </tr>
                    <tr class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 text-on-surface">Velvet Closet</td>
                        <td class="p-4 text-on-surface">Komisi platform dipotong</td>
                        <td class="p-4 text-right font-bold text-error">− Rp 117.000</td>
                        <td class="p-4 text-on-surface-variant">Kemarin, 09.05</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
