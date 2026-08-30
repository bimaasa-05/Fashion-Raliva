@extends('layouts.owner')

@section('title', 'Laporan Toko')

@section('header-title', 'Laporan Toko')
@section('header-subtitle', 'Analisis penjualan, produk, pesanan, refund, saldo, dan pencairan.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
    <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan Periode --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @foreach ([['Pendapatan Bersih', 'Rp '.number_format($pendapatan,0,',','.'), 'trending_up', 'secondary', 'total order selesai'], ['Pesanan Selesai', $pesananSelesai, 'shopping_bag', 'on-surface', 'akumulasi'], ['Nilai Refund', 'Rp '.number_format($refund,0,',','.'), 'money_off', 'error', 'refund selesai'], ['Dana Dicairkan', 'Rp '.number_format($dicairkan,0,',','.'), 'payments', 'on-surface', 'withdrawal selesai']] as $stat)
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-1 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">{{ $stat[0] }}</span>
                <span class="raliva-figure text-2xl text-{{ $stat[3] }}">{{ $stat[1] }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">{{ $stat[2] }}</span>{{ $stat[4] }}</span>
            </div>
        @endforeach
    </section>

    {{-- Grafik --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Pendapatan &amp; Refund</h2>
                <div class="inline-flex self-start sm:self-auto bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
                    <button type="button" data-lr-range="30" class="lr-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary">30 Hari</button>
                    <button type="button" data-lr-range="90" class="lr-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant">3 Bulan</button>
                    <button type="button" data-lr-range="365" class="lr-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant">12 Bulan</button>
                </div>
            </div>
            <div id="chart-wrap" class="relative h-72 md:h-80"><canvas id="revenue-chart"></canvas></div>
        </section>

        <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Produk Terlaris</h2>
            <div id="chart-top" class="relative h-72 md:h-80"><canvas id="top-products-chart"></canvas></div>
        </section>
    </div>

    {{-- Tabel Laporan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Laporan Periode</h2>
                <p class="text-xs text-on-surface-variant mt-1">Rekap pendapatan, refund, dan pencairan per periode.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <select data-report-filter class="raliva-select" onchange="window.location.href='?period='+this.value">
                    <option value="7" @selected($period === 7)>1 Minggu</option>
                    <option value="30" @selected($period === 30)>30 Hari</option>
                    <option value="90" @selected($period === 90)>3 Bulan</option>
                    <option value="365" @selected($period === 365)>1 Tahun</option>
                </select>
                <a href="{{ route('owner.laporan.export', ['period' => $period]) }}" class="flex items-center justify-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0">
                    <span class="material-symbols-outlined text-[16px]">download</span>CSV
                </a>
                <button type="button" onclick="window.print()" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium shrink-0">
                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>PDF
                </button>
            </div>
        </div>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[820px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Periode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Pendapatan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Refund</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Pencairan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $row)
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface whitespace-nowrap">{{ $row['periode'] }}</td>
                            <td class="py-3.5 px-4 text-right text-on-surface">{{ $row['pesanan'] }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format($row['pendapatan'],0,',','.') }}</td>
                            <td class="py-3.5 px-4 text-right text-error whitespace-nowrap">Rp {{ number_format($row['refund'],0,',','.') }}</td>
                            <td class="py-3.5 px-4 text-right text-on-surface-variant whitespace-nowrap">Rp {{ number_format($row['pencairan'],0,',','.') }}</td>
                            <td class="py-3.5 px-4 text-right text-on-surface font-bold whitespace-nowrap">Rp {{ number_format($row['pendapatan'] - $row['refund'] - $row['pencairan'],0,',','.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-6 text-center text-on-surface-variant">Belum ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-outline-variant">
                        <td class="py-3.5 px-4 font-bold text-on-surface">Total</td>
                        <td class="py-3.5 px-4 text-right font-bold text-on-surface">{{ $totals['pesanan'] }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format($totals['pendapatan'],0,',','.') }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-error whitespace-nowrap">Rp {{ number_format($totals['refund'],0,',','.') }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-on-surface-variant whitespace-nowrap">Rp {{ number_format($totals['pencairan'],0,',','.') }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-on-surface whitespace-nowrap">Rp {{ number_format($totals['pendapatan'] - $totals['refund'] - $totals['pencairan'],0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let revenueChart = null;
    const chartWrap = document.getElementById('chart-wrap');

    const revenueData = @json($chartData);

    const themeColors = () => {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            grid: isDark ? '#333333' : '#E9E8E7',
            tick: isDark ? '#BAB8B8' : '#747878',
            tooltipBg: isDark ? '#F0EEEE' : '#1b1c1c',
            tooltipText: isDark ? '#111111' : '#ffffff'
        };
    };

    const renderRevenueChart = () => {
        if (!window.Chart) return;
        const c = themeColors();
        const data = revenueData[window.currentLrRange || '30'];

        if (!revenueChart) {
            revenueChart = new Chart(document.getElementById('revenue-chart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [
                        { label: 'Pendapatan', data: data.pendapatan, backgroundColor: 'rgba(201, 162, 77, 0.85)', borderRadius: 4, maxBarThickness: 34 },
                        { type: 'line', label: 'Refund', data: data.refund, borderColor: '#BA1A1A', backgroundColor: 'transparent', tension: 0.35, borderWidth: 2, pointBackgroundColor: '#BA1A1A', pointRadius: 3 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, color: c.tick, font: { family: 'Manrope', size: 12 } } },
                        tooltip: {
                            backgroundColor: c.tooltipBg, titleColor: c.tooltipText, bodyColor: c.tooltipText,
                            titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 },
                            padding: 12, cornerRadius: 0,
                            callbacks: { label: (ctx) => ' ' + ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: c.grid }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 }, callback: (v) => (v / 1000000) + ' jt' } },
                        x: { grid: { display: false }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 } } }
                    }
                }
            });
            return;
        }

        /* Transisi mulus saat ganti periode: batang resize & garis meluncur */
        revenueChart.data.labels = data.labels;
        revenueChart.data.datasets[0].data = data.pendapatan;
        revenueChart.data.datasets[1].data = data.refund;
        revenueChart.options.scales.y.grid.color = c.grid;
        revenueChart.options.scales.y.ticks.color = c.tick;
        revenueChart.options.scales.x.ticks.color = c.tick;
        revenueChart.options.plugins.legend.labels.color = c.tick;
        revenueChart.update();
    };

    document.querySelectorAll('[data-lr-range]').forEach((btn) => {
        btn.addEventListener('click', () => {
            window.currentLrRange = btn.getAttribute('data-lr-range');
            document.querySelectorAll('[data-lr-range]').forEach((b) => {
                const active = b === btn;
                b.classList.toggle('bg-deep-onyx', active);
                b.classList.toggle('text-on-primary', active);
                b.classList.toggle('text-on-surface-variant', !active);
            });
            renderRevenueChart();
        });
    });

    window.ralivaOnReady(() => {
        try {
            if (window.Chart) {
                const c = themeColors();
                new Chart(document.getElementById('top-products-chart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json(array_column($top, 'nama')),
                        datasets: [{ label: 'Terjual (pcs)', data: @json(array_column($top, 'terjual')), backgroundColor: ['#C9A24D', 'rgba(201,162,77,.75)', 'rgba(201,162,77,.55)', 'rgba(201,162,77,.4)', 'rgba(201,162,77,.25)'], borderRadius: 4 }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 800, easing: 'easeOutQuart', delay: (ctx) => ctx.dataIndex * 90 },
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: c.tooltipBg, titleColor: c.tooltipText, bodyColor: c.tooltipText, padding: 12, cornerRadius: 0, callbacks: { label: (ctx) => ' ' + ctx.raw + ' pcs terjual' } }
                        },
                        scales: {
                            x: { beginAtZero: true, grid: { color: c.grid }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 } } },
                            y: { grid: { display: false }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 } } }
                        }
                    }
                });
            }
            renderRevenueChart();
        } catch (e) {}
    });
</script>
@endpush
