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

    <div class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 overflow-x-auto shrink-0">
        <button type="button" data-lap-tab="operasional" class="lap-tab px-4 py-2 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Operasional Toko</button>
        <button type="button" data-lap-tab="penjualan" class="lap-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Penjualan Saya</button>
    </div>

    <div data-lap-type="operasional" class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Kinerja Operasional 30 Hari</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pesanan Selesai</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $pesananDiproses ?? 0 }}</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pendapatan</span>
                <span class="raliva-figure text-[26px] {{ ($pendapatan ?? 0) >= 0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($pendapatan ?? 0,0,',','.') }}</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">payments</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pengeluaran</span>
                <span class="raliva-figure text-[26px] text-error">Rp {{ number_format($totalPengeluaran ?? 0,0,',','.') }}</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">receipt_long</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Bersih</span>
                <span class="raliva-figure text-[26px] {{ ($totalBersih ?? 0) >=0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($totalBersih ?? 0,0,',','.') }}</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            </div>
        </div>
    </section>

    {{-- Chart Omzet 30 Hari --}}
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Grafik Tren Omzet 30 Hari Terakhir</h2>
                <p class="text-xs text-on-surface-variant mt-1">Penjualan harian dalam jutaan rupiah untuk toko scope Anda.</p>
            </div>
            <form method="GET" class="flex items-center gap-2 flex-wrap">
                <input type="date" name="dari" value="{{ $dari->toDateString() }}" max="{{ date('Y-m-d') }}" class="raliva-input text-xs py-2 w-auto" title="Dari tanggal" />
                <span class="text-on-surface-variant text-xs">s/d</span>
                <input type="date" name="sampai" value="{{ $sampai->toDateString() }}" max="{{ date('Y-m-d') }}" class="raliva-input text-xs py-2 w-auto" title="Sampai tanggal" />
                <button type="submit" class="px-4 py-2 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium">Terapkan</button>
            </form>
        </div>
        <div class="h-48" data-bars='@json($omzetBars ?? [])' data-bars-suffix=" JT"></div>
    </section>

    </div>

    <div data-lap-type="penjualan" class="space-y-section-gap hidden">
    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Laporan Penjualan Saya</h2>
        <p class="text-xs text-on-surface-variant -mt-2">Order yang pembayarannya kamu verifikasi sendiri — sebagai kasir, kontribusimu terpisah dari angka toko.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pesanan Saya</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $saya['pesanan'] ?? 0 }}</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pendapatan Saya</span>
                <span class="raliva-figure text-[26px] {{ ($saya['pendapatan'] ?? 0) >= 0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($saya['pendapatan'] ?? 0,0,',','.') }}</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pengeluaran Saya</span>
                <span class="raliva-figure text-[26px] text-error">Rp {{ number_format($saya['pengeluaran'] ?? 0,0,',','.') }}</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Bersih Saya</span>
                <span class="raliva-figure text-[26px] {{ ($saya['bersih'] ?? 0) >= 0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($saya['bersih'] ?? 0,0,',','.') }}</span>
            </div>
        </div>
        <p class="text-xs text-on-surface-variant mt-2">Pengeluaran saya = refund yang saya selesaikan (hanya refund berstatus selesai) + pengeluaran toko yang saya catat. Berlaku untuk toko yang ditugaskan (AdminContext).</p>
    </section>
    </div>

    <div data-lap-type="operasional" class="space-y-section-gap">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter items-start">
        <section class="lg:col-span-2 space-y-gutter">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Pendapatan per Metode Pembayaran</h2>
            <div class="overflow-x-auto hidden md:block bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
                <table class="w-full min-w-[480px] premium-table">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                            <th class="p-4 text-left">Metode</th>
                            <th class="p-4 text-center">Transaksi</th>
                            <th class="p-4 text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-md text-sm">
                        @forelse($perMetode ?? collect() as $m)
                            <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                                <td class="p-4 text-on-surface">{{ $m->nama_metode }}</td>
                                <td class="p-4 text-center text-on-surface">{{ $m->jumlah_transaksi }}</td>
                                <td class="p-4 text-center text-secondary">Rp {{ number_format($m->total,0,',','.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-8 text-center text-on-surface-variant">Belum ada pembayaran terverifikasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="md:hidden grid grid-cols-1 gap-gutter">
                @forelse($perMetode ?? collect() as $m)
                    <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface">{{ $m->nama_metode }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $m->jumlah_transaksi }} transaksi</p>
                            </div>
                            <p class="shrink-0 font-bold text-secondary mt-0.5">Rp {{ number_format($m->total,0,',','.') }}</p>
                        </div>
                    </article>
                @empty
                    <p class="text-on-surface-variant text-sm py-6 text-center">Belum ada pembayaran terverifikasi.</p>
                @endforelse
            </div>
            <p class="text-xs text-on-surface-variant mt-2">Berdasarkan pembayaran berstatus terverifikasi pada toko yang ditugaskan kepada Anda.</p>
        </section>

        <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Proporsi Metode</h2>
                <span class="material-symbols-outlined text-gold-accent text-[20px]">donut_small</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-xs mb-4">Visualisasi distribusi transaksi per metode bayar.</p>
            @if (count($distribusiMetode ?? []))
                <div data-donut='@json($distribusiMetode)' data-donut-label="Transaksi" class="flex-1 flex items-center justify-center min-h-[220px]"></div>
            @else
                <p class="text-on-surface-variant text-sm py-8 text-center">Belum ada transaksi.</p>
            @endif
        </section>
    </div>
    </div>
</div>
@push('scripts')
<script>
    document.querySelectorAll('[data-lap-tab]').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('[data-lap-tab]').forEach(t => {
                t.classList.remove('bg-deep-onyx', 'text-on-primary');
                t.classList.add('text-on-surface-variant', 'hover:text-on-surface');
            });
            tab.classList.add('bg-deep-onyx', 'text-on-primary');
            tab.classList.remove('text-on-surface-variant', 'hover:text-on-surface');
            const mode = tab.dataset.lapTab;
            document.querySelectorAll('[data-lap-type]').forEach(el => {
                el.classList.toggle('hidden', el.dataset.lapType !== mode);
            });
        });
    });
</script>
@endpush
@endsection
