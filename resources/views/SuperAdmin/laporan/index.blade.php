@extends('layouts.superadmin')

@section('title', 'Laporan')

@section('header-title', 'Laporan & Analitik')
@section('header-badge', 'Lihat')

@section('header-subtitle', 'Laporan transaksi, komisi, toko, pengguna, refund, dan pencairan.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<!-- Summary Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-section-gap">
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
        <div class="flex justify-between items-start">
            <span class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gold-accent text-[20px]">account_balance_wallet</span>
            </span>
        </div>
        <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant mt-2">Total Pendapatan</span>
        <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">Rp {{ number_format($totalPendapatan / 1000, 0, ',', '.') }}JT</span>
        <span class="text-on-surface-variant text-xs">Semua transaksi berhasil</span>
    </div>

    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
        <div class="flex justify-between items-start">
            <span class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gold-accent text-[20px]">shopping_bag</span>
            </span>
        </div>
        <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant mt-2">Total Pesanan</span>
        <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">{{ number_format($totalPesanan) }}</span>
        <span class="text-on-surface-variant text-xs">Pesanan berhasil</span>
    </div>

    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
        <div class="flex justify-between items-start">
            <span class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gold-accent text-[20px]">payments</span>
            </span>
        </div>
        <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant mt-2">Komisi Raliva</span>
        <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold leading-tight">Rp {{ number_format($komisiRaliva / 1000, 0, ',', '.') }}JT</span>
        <span class="text-on-surface-variant text-xs">Total komisi terkumpul</span>
    </div>

    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
        <div class="flex justify-between items-start">
            <span class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gold-accent text-[20px]">storefront</span>
            </span>
        </div>
        <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant mt-2">Toko Aktif</span>
        <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">{{ $tokoAktif }}</span>
        <span class="text-on-surface-variant text-xs">Toko terverifikasi</span>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-section-gap">
    <div class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-title-md text-on-surface uppercase tracking-wider premium-heading">Tren Pendapatan</h3>
        </div>
        <div class="h-[300px] w-full relative">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-title-md text-on-surface uppercase tracking-wider premium-heading">Toko Teratas</h3>
        </div>
        <div data-leaderboard='@json($topToko)'></div>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="mb-section-gap">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-title-md text-on-surface uppercase tracking-wider premium-heading">Transaksi Terbaru</h3>
    </div>
    <div class="border border-muted-border bg-surface-container-lowest rounded-lg overflow-x-auto hidden md:block card-premium">
        <table class="w-full text-left border-collapse premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low/50">
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap text-center w-12">No.</th>
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Nomor Order</th>
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Tanggal</th>
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Toko</th>
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Jumlah</th>
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $tx)
                    @php $rowNumber = $loop->iteration; @endphp
                    @php
                        $statusMap = [
                            'dibayar' => ['Dibayar', 'bg-info/10 text-info'],
                            'diproses' => ['Diproses', 'bg-secondary-container/20 text-secondary'],
                            'dikirim' => ['Dikirim', 'bg-secondary-container/20 text-secondary'],
                            'selesai' => ['Selesai', 'bg-success/10 text-success'],
                        ];
                        $st = $statusMap[$tx->status] ?? [$tx->status, 'bg-surface-container-high text-on-surface-variant'];
                    @endphp
                    <tr class="border-b border-muted-border last:border-0 hover:bg-surface-container-low/30 transition-colors">
                        <td class="p-4 text-center text-on-surface-variant font-mono">{{ $rowNumber }}</td>
                        <td class="p-4 font-mono text-sm text-on-surface">{{ $tx->nomor_order }}</td>
                        <td class="p-4 font-body-md text-sm text-on-surface-variant">{{ $tx->created_at ? \Carbon\Carbon::parse($tx->created_at)->locale('id')->translatedFormat('d M Y - H.i') : '-' }}</td>
                        <td class="p-4 font-body-md text-sm text-on-surface">{{ $tx->store->nama_toko ?? '-' }}</td>
                        <td class="p-4 font-title-md text-sm text-on-surface">Rp {{ number_format((float)$tx->grand_total, 0, ',', '.') }}</td>
                        <td class="p-4"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase border">{{ $st[0] }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-on-surface-variant">Belum ada transaksi tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile: kartu transaksi terbaru -->
    <div class="md:hidden grid grid-cols-1 gap-gutter">
        @forelse($recentTransactions as $tx)
            @php
                $statusMap = [
                    'dibayar' => ['Dibayar', 'bg-info/10 text-info'],
                    'diproses' => ['Diproses', 'bg-secondary-container/20 text-secondary'],
                    'dikirim' => ['Dikirim', 'bg-secondary-container/20 text-secondary'],
                    'selesai' => ['Selesai', 'bg-success/10 text-success'],
                ];
                $st = $statusMap[$tx->status] ?? [$tx->status, 'bg-surface-container-high text-on-surface-variant'];
            @endphp
            <article class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <p class="font-mono text-sm text-on-surface leading-tight">{{ $tx->nomor_order }}</p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase border shrink-0">{{ $st[0] }}</span>
                </div>
                <dl class="space-y-2 font-body-md text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Toko</dt>
                        <dd class="text-on-surface text-right">{{ $tx->store->nama_toko ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Tanggal</dt>
                        <dd class="text-on-surface-variant text-xs text-right">{{ $tx->created_at ? \Carbon\Carbon::parse($tx->created_at)->locale('id')->translatedFormat('d M Y - H.i') : '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-on-surface-variant">Jumlah</dt>
                        <dd class="text-on-surface font-bold text-right">Rp {{ number_format((float)$tx->grand_total, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </article>
        @empty
            <p class="text-center text-on-surface-variant py-10">Belum ada transaksi tercatat.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#333333' : '#E9E8E7';
        const tickColor = isDark ? '#BAB8B8' : '#747878';
        const tooltipBg = isDark ? '#F0EEEE' : '#1b1c1c';
        const tooltipText = isDark ? '#111111' : '#ffffff';
        Chart.defaults.font.family = 'Manrope, sans-serif';
        Chart.defaults.color = tickColor;
        Chart.defaults.scale.grid.color = gridColor;
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($chartData),
                    borderColor: '#C9A24D',
                    backgroundColor: 'rgba(201, 162, 77, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#C9A24D',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: tooltipBg, titleColor: tooltipText, bodyColor: tooltipText, titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 }, padding: 12, cornerRadius: 0, displayColors: false, callbacks: { label: function (context) { return 'Rp ' + context.parsed.y + 'M'; } } } },
                scales: { x: { grid: { display: false, drawBorder: false }, ticks: { font: { size: 11 } } }, y: { grid: { drawBorder: false, borderDash: [4, 4] }, ticks: { callback: function (value) { return value + 'M'; }, font: { size: 11 }, maxTicksLimit: 6 }, beginAtZero: true } },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    });
</script>
@endpush
