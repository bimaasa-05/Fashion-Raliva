@extends('layouts.superadmin')

@section('title', 'Laporan')

@section('header-title', 'Laporan & Analitik')
@section('header-badge', 'Kelola & Lihat')

@section('header-subtitle', 'Laporan transaksi, komisi, toko, pengguna, refund, dan pencairan.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="flex justify-end">
    <button class="bg-deep-onyx text-on-primary font-label-sm px-6 py-3 uppercase tracking-widest hover:bg-surface-tint transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[16px]">download</span>
        Ekspor Laporan
    </button>
</div>

<!-- Filters -->
<div class="flex overflow-x-auto no-scrollbar gap-2 mb-10 pb-2 border-b border-muted-border">
    <button class="px-4 py-2 font-label-sm text-secondary border-b-2 border-secondary whitespace-nowrap uppercase tracking-widest">Ringkasan</button>
    <button class="px-4 py-2 font-label-sm text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap uppercase tracking-widest">Transaksi</button>
    <button class="px-4 py-2 font-label-sm text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap uppercase tracking-widest">Komisi</button>
    <button class="px-4 py-2 font-label-sm text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap uppercase tracking-widest">Toko</button>
    <button class="px-4 py-2 font-label-sm text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap uppercase tracking-widest">Pengguna</button>
    <button class="px-4 py-2 font-label-sm text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap uppercase tracking-widest">Refund</button>
    <button class="px-4 py-2 font-label-sm text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap uppercase tracking-widest">Pencairan</button>
</div>

<!-- Date Range Filter -->
<div class="flex items-center gap-2 mb-8 overflow-x-auto no-scrollbar">
    <button class="px-4 py-1.5 border border-muted-border bg-surface text-on-surface font-label-sm rounded-none hover:bg-surface-container-low transition-colors whitespace-nowrap">HARI INI</button>
    <button class="px-4 py-1.5 border border-muted-border bg-surface text-on-surface font-label-sm rounded-none hover:bg-surface-container-low transition-colors whitespace-nowrap">7D</button>
    <button class="px-4 py-1.5 border border-secondary bg-secondary-container/10 text-secondary font-label-sm rounded-none whitespace-nowrap">30D</button>
    <button class="px-4 py-1.5 border border-muted-border bg-surface text-on-surface font-label-sm rounded-none hover:bg-surface-container-low transition-colors flex items-center gap-1 whitespace-nowrap">
        KUSTOM <span class="material-symbols-outlined text-[16px]">calendar_today</span>
    </button>
</div>

<!-- Summary Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-section-gap">
    <div class="border border-muted-border p-6 bg-surface flex flex-col gap-2">
        <div class="flex justify-between items-start text-on-surface-variant">
            <span class="font-label-sm uppercase tracking-widest">Total Pendapatan</span>
            <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
        </div>
        <div class="font-title-md text-on-background text-2xl mt-2">Rp 245.890.000</div>
        <div class="flex items-center gap-1 text-sm mt-1">
            <span class="material-symbols-outlined text-[16px] text-[#4ade80]">trending_up</span>
            <span class="text-[#4ade80] font-medium">+12.5%</span>
            <span class="text-on-surface-variant text-xs">vs 30 hari terakhir</span>
        </div>
    </div>
    <div class="border border-muted-border p-6 bg-surface flex flex-col gap-2">
        <div class="flex justify-between items-start text-on-surface-variant">
            <span class="font-label-sm uppercase tracking-widest">Total Pesanan</span>
            <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
        </div>
        <div class="font-title-md text-on-background text-2xl mt-2">1,248</div>
        <div class="flex items-center gap-1 text-sm mt-1">
            <span class="material-symbols-outlined text-[16px] text-[#4ade80]">trending_up</span>
            <span class="text-[#4ade80] font-medium">+8.2%</span>
            <span class="text-on-surface-variant text-xs">vs 30 hari terakhir</span>
        </div>
    </div>
    <div class="border border-muted-border p-6 bg-surface flex flex-col gap-2">
        <div class="flex justify-between items-start text-on-surface-variant">
            <span class="font-label-sm uppercase tracking-widest">Komisi</span>
            <span class="material-symbols-outlined text-[20px]">payments</span>
        </div>
        <div class="font-title-md text-on-background text-2xl mt-2">Rp 12.294.500</div>
        <div class="flex items-center gap-1 text-sm mt-1">
            <span class="material-symbols-outlined text-[16px] text-[#4ade80]">trending_up</span>
            <span class="text-[#4ade80] font-medium">+15.3%</span>
            <span class="text-on-surface-variant text-xs">vs 30 hari terakhir</span>
        </div>
    </div>
    <div class="border border-muted-border p-6 bg-surface flex flex-col gap-2">
        <div class="flex justify-between items-start text-on-surface-variant">
            <span class="font-label-sm uppercase tracking-widest">Toko Aktif</span>
            <span class="material-symbols-outlined text-[20px]">storefront</span>
        </div>
        <div class="font-title-md text-on-background text-2xl mt-2">42</div>
        <div class="flex items-center gap-1 text-sm mt-1">
            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">trending_flat</span>
            <span class="text-on-surface-variant font-medium">0%</span>
            <span class="text-on-surface-variant text-xs">vs 30 hari terakhir</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-section-gap">
    <div class="lg:col-span-2 border border-muted-border p-6 bg-surface">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-title-md text-on-background uppercase tracking-wider">Tren Pendapatan</h3>
            <button class="text-on-surface-variant hover:text-on-surface transition-colors p-1">
                <span class="material-symbols-outlined">more_horiz</span>
            </button>
        </div>
        <div class="h-[300px] w-full relative">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="border border-muted-border p-6 bg-surface flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-title-md text-on-background uppercase tracking-wider">Toko Teratas</h3>
            <a class="font-label-sm text-secondary hover:underline uppercase tracking-widest" href="#">Lihat Semua</a>
        </div>
        <div class="flex flex-col gap-4 flex-grow">
            <div class="flex items-center justify-between group cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-surface-container-high rounded-full overflow-hidden flex-shrink-0">
                        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5RuuoiXQIXOOZUEodSRLvehbN-Hm4tiy4NSVknMMLorGe6t-45PGCBJGxlKjfSquwaWiuAFgrgXTnjsIFq1uBQSJoJ0XiUOmi7_djXgHsMwmNrRUrTNY2TYbappCeg-7Qf7VfLbwQkr63GtrA0d5JnHA10iOfG6nZGBOH2OMv2jh25vMmAHXsZz-t4D9500lcXiJW6wOtQH4s0jYOURrh2aqewG6ofCwB9-XkyaXX7K2ovgXOO2FqKg" />
                    </div>
                    <div>
                        <div class="font-title-md text-sm text-on-background group-hover:text-secondary transition-colors">LUNARA Fashion</div>
                        <div class="font-body-md text-xs text-on-surface-variant">342 pesanan</div>
                    </div>
                </div>
                <div class="font-title-md text-sm text-on-background">Rp 85M</div>
            </div>
            <hr class="border-muted-border border-t-1 w-full" />
            <div class="flex items-center justify-between group cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-surface-container-high rounded-full overflow-hidden flex-shrink-0 bg-deep-onyx text-on-primary flex items-center justify-center">
                        <span class="font-headline-lg text-sm">N</span>
                    </div>
                    <div>
                        <div class="font-title-md text-sm text-on-background group-hover:text-secondary transition-colors">NOIRÉ Studio</div>
                        <div class="font-body-md text-xs text-on-surface-variant">218 pesanan</div>
                    </div>
                </div>
                <div class="font-title-md text-sm text-on-background">Rp 62M</div>
            </div>
            <hr class="border-muted-border border-t-1 w-full" />
            <div class="flex items-center justify-between group cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#f5ebe0] rounded-full overflow-hidden flex-shrink-0 flex items-center justify-center text-secondary">
                        <span class="font-headline-lg text-sm">K</span>
                    </div>
                    <div>
                        <div class="font-title-md text-sm text-on-background group-hover:text-secondary transition-colors">KAYANA Apparel</div>
                        <div class="font-body-md text-xs text-on-surface-variant">195 pesanan</div>
                    </div>
                </div>
                <div class="font-title-md text-sm text-on-background">Rp 48M</div>
            </div>
            <hr class="border-muted-border border-t-1 w-full" />
            <div class="flex items-center justify-between group cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#e3d5ca] rounded-full overflow-hidden flex-shrink-0 flex items-center justify-center text-on-surface">
                        <span class="font-headline-lg text-sm">M</span>
                    </div>
                    <div>
                        <div class="font-title-md text-sm text-on-background group-hover:text-secondary transition-colors">MAÉVA House</div>
                        <div class="font-body-md text-xs text-on-surface-variant">156 pesanan</div>
                    </div>
                </div>
                <div class="font-title-md text-sm text-on-background">Rp 35M</div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions Table -->
<div class="mb-section-gap">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-title-md text-on-background uppercase tracking-wider">Transaksi Terbaru</h3>
        <a class="font-label-sm text-secondary hover:underline uppercase tracking-widest" href="#">Lihat Semua</a>
    </div>
    <div class="border border-muted-border bg-surface overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low/50">
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">ID Transaksi</th>
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Tanggal</th>
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Toko</th>
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Jumlah</th>
                    <th class="p-4 font-label-sm text-on-surface-variant uppercase tracking-widest font-semibold whitespace-nowrap">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-muted-border hover:bg-surface-container-low/30 transition-colors">
                    <td class="p-4 font-body-md text-sm text-on-background">#TRX-8924-A</td>
                    <td class="p-4 font-body-md text-sm text-on-surface-variant">24 Okt 2023 - 14.32</td>
                    <td class="p-4 font-body-md text-sm text-on-background">LUNARA Fashion</td>
                    <td class="p-4 font-title-md text-sm text-on-background">Rp 1.250.000</td>
                    <td class="p-4"><span class="inline-flex items-center px-2 py-1 bg-[#dcfce7] text-[#166534] text-xs font-semibold tracking-wide uppercase border border-[#bbf7d0]">Selesai</span></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low/30 transition-colors">
                    <td class="p-4 font-body-md text-sm text-on-background">#TRX-8923-B</td>
                    <td class="p-4 font-body-md text-sm text-on-surface-variant">24 Okt 2023 - 11.15</td>
                    <td class="p-4 font-body-md text-sm text-on-background">NOIRÉ Studio</td>
                    <td class="p-4 font-title-md text-sm text-on-background">Rp 850.000</td>
                    <td class="p-4"><span class="inline-flex items-center px-2 py-1 bg-[#dcfce7] text-[#166534] text-xs font-semibold tracking-wide uppercase border border-[#bbf7d0]">Selesai</span></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low/30 transition-colors">
                    <td class="p-4 font-body-md text-sm text-on-background">#TRX-8922-C</td>
                    <td class="p-4 font-body-md text-sm text-on-surface-variant">24 Okt 2023 - 09.45</td>
                    <td class="p-4 font-body-md text-sm text-on-background">KAYANA Apparel</td>
                    <td class="p-4 font-title-md text-sm text-on-background">Rp 420.000</td>
                    <td class="p-4"><span class="inline-flex items-center px-2 py-1 bg-[#fef9c3] text-[#854d0e] text-xs font-semibold tracking-wide uppercase border border-[#fef08a]">Menunggu</span></td>
                </tr>
                <tr class="border-b border-muted-border hover:bg-surface-container-low/30 transition-colors">
                    <td class="p-4 font-body-md text-sm text-on-background">#TRX-8921-D</td>
                    <td class="p-4 font-body-md text-sm text-on-surface-variant">23 Okt 2023 - 18.20</td>
                    <td class="p-4 font-body-md text-sm text-on-background">MAÉVA House</td>
                    <td class="p-4 font-title-md text-sm text-on-background">Rp 2.100.000</td>
                    <td class="p-4"><span class="inline-flex items-center px-2 py-1 bg-[#fee2e2] text-[#991b1b] text-xs font-semibold tracking-wide uppercase border border-[#fecaca]">Direfund</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        Chart.defaults.font.family = 'Manrope, sans-serif';
        Chart.defaults.color = '#747878';
        Chart.defaults.scale.grid.color = '#E9E8E7';
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1 Okt', '5 Okt', '10 Okt', '15 Okt', '20 Okt', '25 Okt', '30 Okt'],
                datasets: [{
                    label: 'Pendapatan',
                    data: [120, 190, 150, 220, 180, 280, 245],
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
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1b1c1c', titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 }, padding: 12, cornerRadius: 0, displayColors: false, callbacks: { label: function (context) { return 'Rp ' + context.parsed.y + 'M'; } } } },
                scales: { x: { grid: { display: false, drawBorder: false }, ticks: { font: { size: 11 } } }, y: { grid: { drawBorder: false, borderDash: [4, 4] }, ticks: { callback: function (value) { return value + 'M'; }, font: { size: 11 }, maxTicksLimit: 6 }, beginAtZero: true } },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    });
</script>
@endpush