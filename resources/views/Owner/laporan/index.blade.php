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
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    {{-- Ringkasan Periode — besarin & tebalkan icon (visible white/dark) --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @foreach ([['Pendapatan Bersih', 'Rp '.number_format($pendapatan,0,',','.'), 'trending_up', 'secondary', 'total order selesai'], ['Pesanan Selesai', $pesananSelesai, 'shopping_bag', 'on-surface', 'akumulasi'], ['Nilai Refund', 'Rp '.number_format($refund,0,',','.'), 'money_off', 'error', 'refund selesai'], ['Dana Dicairkan', 'Rp '.number_format($dicairkan,0,',','.'), 'payments', 'on-surface', 'withdrawal selesai']] as $stat)
            <div data-reveal class="bg-surface-container-lowest p-6 md:p-8 border border-muted-border rounded-xl flex flex-col gap-1.5 relative overflow-hidden card-premium">
                <span class="material-symbols-outlined absolute right-3 bottom-3 text-[84px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none fill" aria-hidden="true">{{ $stat[2] }}</span>
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider relative">{{ $stat[0] }}</span>
                <span class="raliva-figure text-[28px] md:text-[30px] text-{{ $stat[3] }} relative">{{ $stat[1] }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant flex items-center gap-1.5 relative"><span class="material-symbols-outlined text-[18px] fill text-gold-accent">{{ $stat[2] }}</span>{{ $stat[4] }}</span>
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
                    <button type="button" data-lr-range="1825" class="lr-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant">5 Tahun</button>
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
                    <option value="1825" @selected($period === 1825)>5 Tahun</option>
                </select>
                @php $lapNoStore = ! \App\Support\OwnerContext::currentStore(); @endphp
                <a href="{{ route('owner.laporan.export-excel', ['period' => $period]) }}" @if($lapNoStore) aria-disabled="true" tabindex="-1" title="Ajukan toko dulu" @endif class="flex items-center justify-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0 {{ $lapNoStore ? 'opacity-60 pointer-events-none' : '' }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $lapNoStore ? 'lock' : 'download' }}</span>Excell
                </a>
                <a href="{{ route('owner.laporan.cetak', ['period' => $period]) }}" target="_blank" @if($lapNoStore) aria-disabled="true" tabindex="-1" title="Ajukan toko dulu" @endif class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium shrink-0 {{ $lapNoStore ? 'opacity-60 pointer-events-none' : '' }}">
                    <span class="material-symbols-outlined text-[16px]">{{ $lapNoStore ? 'lock' : 'picture_as_pdf' }}</span>PDF
                </a>
            </div>
        </div>
        <div data-table-wrap class="overflow-x-auto hidden md:block">
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
        <div class="md:hidden grid grid-cols-1 gap-gutter mt-6">
            @forelse ($report as $row)
                <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <p class="font-bold text-on-surface">{{ $row['periode'] }}</p>
                    <div class="grid grid-cols-2 gap-3 mt-3 pt-3 border-t border-muted-border text-sm">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Pesanan</p>
                            <p class="font-bold text-on-surface mt-0.5">{{ $row['pesanan'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Pendapatan</p>
                            <p class="font-bold text-gold-accent mt-0.5">Rp {{ number_format($row['pendapatan'],0,',','.') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Refund</p>
                            <p class="font-bold text-error mt-0.5">Rp {{ number_format($row['refund'],0,',','.') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Pencairan</p>
                            <p class="font-bold text-on-surface-variant mt-0.5">Rp {{ number_format($row['pencairan'],0,',','.') }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Saldo Akhir</p>
                            <p class="font-bold text-on-surface mt-0.5">Rp {{ number_format($row['pendapatan'] - $row['refund'] - $row['pencairan'],0,',','.') }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-6 text-center">Belum ada data pada periode ini.</p>
            @endforelse
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
                        { label: 'Pendapatan', data: data.pendapatan, backgroundColor: 'rgba(139, 30, 63, 0.85)', borderRadius: 4, maxBarThickness: 34 },
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
                            callbacks: { label: (ctx) => ' ' + ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(Number(ctx.raw)) }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: c.grid }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 }, callback: (v) => window.ralivaShortRp ? window.ralivaShortRp(v) : ((v / 1000000).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + ' jt') } },
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
                        datasets: [{ label: 'Terjual (pcs)', data: @json(array_column($top, 'terjual')), backgroundColor: ['#8B1E3F', 'rgba(139,30,63,.75)', 'rgba(139,30,63,.55)', 'rgba(139,30,63,.4)', 'rgba(139,30,63,.25)'], borderRadius: 4 }]
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
