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
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pesanan Selesai</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $pesananDiproses ?? 0 }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pendapatan</span>
                <span class="raliva-figure text-[26px] text-secondary">Rp {{ number_format($pendapatan ?? 0,0,',','.') }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">payments</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pengeluaran</span>
                <span class="raliva-figure text-[26px] text-error">Rp {{ number_format($totalPengeluaran ?? 0,0,',','.') }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">receipt_long</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Bersih</span>
                <span class="raliva-figure text-[26px] {{ ($totalBersih ?? 0) >=0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($totalBersih ?? 0,0,',','.') }}</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            </div>
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Rincian per Toko (Scope Admin)</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-center">Pesanan</th>
                        <th class="p-4 text-center">Pendapatan</th>
                        <th class="p-4 text-center">Pengeluaran</th>
                        <th class="p-4 text-center">Bersih</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse($perToko ?? collect() as $t)
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-on-surface">{{ $t->nama_toko }}</td>
                            <td class="p-4 text-center text-on-surface">{{ $t->pesanan }}</td>
                            <td class="p-4 text-center text-secondary">Rp {{ number_format($t->pendapatan,0,',','.') }}</td>
                            <td class="p-4 text-center text-error">Rp {{ number_format($t->pengeluaran,0,',','.') }}</td>
                            <td class="p-4 text-center font-bold {{ $t->bersih>=0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($t->bersih,0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-on-surface-variant">Tidak ada data toko yang ditugaskan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="text-xs text-on-surface-variant mt-2">Data hanya untuk toko yang ditugaskan kepada Anda (AdminContext).</p>
    </section>
</div>
@endsection
