@extends('layouts.superadmin')

@section('title', 'Saldo Toko')

@section('header-title', 'Saldo Toko')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Pantau saldo dan mutasi keuangan seluruh toko di platform.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Saldo</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Saldo Tersedia</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp {{ number_format($totalTersedia, 0, ',', '.') }}</span>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Saldo Tertahan</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">lock</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp {{ number_format($totalTertahan, 0, ',', '.') }}</span>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Jumlah Toko</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">storefront</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $jumlahToko }}</span>
            </div>
        </div>
    </section>

    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Saldo per Toko</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            @forelse($wallets as $wallet)
                <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                    <div class="flex items-center justify-between">
                        <span class="font-title-md text-title-md text-on-surface">{{ $wallet->store->nama_toko ?? '-' }}</span>
                        <span class="material-symbols-outlined text-gold-accent">account_balance_wallet</span>
                    </div>
                    <div class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">Rp {{ number_format((float)$wallet->saldo_tersedia, 0, ',', '.') }}</div>
                    <div class="pt-4 border-t border-muted-border flex justify-between font-body-md text-sm">
                        <span class="text-on-surface-variant">Menunggu Cair</span>
                        <span class="text-on-surface font-bold">Rp {{ number_format((float)$wallet->saldo_tertahan, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-8">
                    Belum ada data saldo toko.
                </div>
            @endforelse
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Mutasi Terbaru</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-left">Jenis Transaksi</th>
                        <th class="p-4 text-left">Keterangan</th>
                        <th class="p-4 text-right">Nominal</th>
                        <th class="p-4 text-right">Saldo Akhir</th>
                        <th class="p-4 text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse($transactions as $tx)
                        @php
                            $isPositive = in_array($tx->jenis_transaksi, ['penjualan_masuk', 'komisi_masuk', 'penyesuaian']);
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-on-surface">{{ $tx->wallet->store->nama_toko ?? '-' }}</td>
                            <td class="p-4 text-on-surface-variant text-xs uppercase">{{ str_replace('_', ' ', $tx->jenis_transaksi) }}</td>
                            <td class="p-4 text-on-surface">{{ $tx->keterangan ?? '-' }}</td>
                            <td class="p-4 text-right font-bold {{ $isPositive ? 'text-secondary' : 'text-error' }}">
                                {{ $isPositive ? '+' : '-' }} Rp {{ number_format(abs((float)$tx->jumlah), 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-right text-on-surface-variant">Rp {{ number_format((float)$tx->saldo_sesudah, 0, ',', '.') }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $tx->created_at ? \Carbon\Carbon::parse($tx->created_at)->locale('id')->diffForHumans() : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-on-surface-variant">Belum ada mutasi tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
