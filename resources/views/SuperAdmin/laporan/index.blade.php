@extends('layouts.superadmin')

@section('title', 'Laporan')

@section('header-title', 'Laporan & Analitik')
@section('header-badge', 'Lihat')

@section('header-subtitle', 'Laporan transaksi, komisi, toko, pengguna, refund, dan pencairan.')

@php
    $rangeLabels = ['7' => '7 Hari Terakhir', '30' => '1 Bulan Terakhir', '365' => '1 Tahun Terakhir'];
    $trendLabel = $rangeLabels[$activeRange] ?? '1 Bulan Terakhir';
@endphp

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<section>
    <h2 data-reveal class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Laporan</h2>
    <div data-reveal-group class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-gold-accent/25 rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium hero-glow">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pendapatan</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">Rp <span data-count="{{ $totalPendapatan }}" data-count-decimals="0">{{ number_format($totalPendapatan, 0, ',', '.') }}</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>Semua transaksi berhasil</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:55%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:48%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:66%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:58%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:74%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:68%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:88%"></i>
            </div>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
        </div>

        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pesanan</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight"><span data-count="{{ $totalPesanan }}">{{ number_format($totalPesanan, 0, ',', '.') }}</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-gold-accent">shopping_bag</span>Pesanan berhasil</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:50%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:45%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:60%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:55%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:70%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:64%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:80%"></i>
            </div>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
        </div>

        <div class="bg-surface-container-lowest p-4 border border-gold-accent/25 rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium hero-glow">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komisi Raliva</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold leading-tight">Rp <span data-count="{{ $komisiRaliva }}" data-count-decimals="0">{{ number_format($komisiRaliva, 0, ',', '.') }}</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>Total komisi terkumpul</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:40%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:52%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:46%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:60%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:55%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:72%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:84%"></i>
            </div>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">percent</span>
        </div>

        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Toko Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight"><span data-count="{{ $tokoAktif }}">{{ $tokoAktif }}</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-gold-accent">storefront</span>Toko terverifikasi</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:30%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:35%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:32%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:42%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:40%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:48%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:52%"></i>
            </div>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">storefront</span>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-section-gap">
    <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Tren Pendapatan</h2>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider text-gold-accent">
                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                    <span id="trend-badge-text">{{ $trendLabel }}</span>
                </span>
            </div>
            <div class="flex items-center gap-3">
                <span id="trend-total" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gold-accent/10 border border-gold-accent/25 font-title-md text-sm text-gold-accent whitespace-nowrap">Rp 0</span>
                <div class="w-40 relative" id="trendRange-dd">
                    <button type="button" data-dd-trigger id="trendRange-trigger" onclick="toggleDropdown('trendRange')" aria-haspopup="listbox" aria-expanded="false"
                        class="w-full flex items-center justify-between gap-2 bg-surface-container-lowest border border-muted-border rounded-lg pl-3.5 pr-2.5 py-2 font-body-md text-xs text-on-surface focus:outline-none focus:border-gold-accent focus:ring-4 focus:ring-gold-accent/10 transition-all duration-200 cursor-pointer text-left">
                        <span id="trendRange-label" class="truncate">{{ $rangeLabels[$activeRange] ?? $activeRange }}</span>
                        <span class="material-symbols-outlined text-[16px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="trendRange-chevron">expand_more</span>
                    </button>
                    <div id="trendRange-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                        class="hidden absolute left-0 top-full mt-2 w-full min-w-[160px] bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden py-1">
                        @foreach ($rangeLabels as $rKey => $rLabel)
                            <button type="button" role="option" aria-selected="{{ $activeRange === $rKey ? 'true' : 'false' }}" data-dd-option="{{ $rKey }}" onclick="selectTrendRange('{{ $rKey }}')" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-xs text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                {{ $rLabel }}<span data-dd-check class="material-symbols-outlined text-[16px] text-gold-accent {{ $activeRange === $rKey ? '' : 'hidden' }}">check</span>
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="trend-range" value="{{ $activeRange }}" />
                </div>
            </div>
        </div>
        <div id="trend-chart-wrap" class="h-[280px] w-full relative">
            <canvas id="revenueChart"></canvas>
        </div>
        <div id="trend-empty" class="hidden flex flex-col items-center justify-center py-16 text-center gap-2 text-on-surface-variant">
            <span class="material-symbols-outlined text-[32px] opacity-50">monitoring</span>
            <p class="font-body-md text-sm">Belum ada transaksi pada periode ini.</p>
        </div>
        <div id="trend-error" class="hidden flex-col items-center justify-center py-16 text-center gap-2 text-on-surface-variant">
            <span class="material-symbols-outlined text-[32px] opacity-50">cloud_off</span>
            <p class="font-body-md text-sm">Gagal memuat grafik. Pastikan CDN Chart.js dapat diakses.</p>
            <button id="trend-retry" class="mt-2 px-4 py-2 rounded-lg border border-gold-accent/30 text-gold-accent text-xs font-bold uppercase tracking-wider hover:bg-gold-accent/10 transition-colors">Muat Ulang</button>
        </div>
        <p class="text-on-surface-variant font-body-md text-[11px] mt-5 pt-4 border-t border-muted-border flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[14px] text-gold-accent">insights</span>
            <span id="trend-insight-text">Pilih rentang waktu untuk melihat ringkasan.</span>
        </p>
    </section>

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Toko Teratas</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">emoji_events</span>
        </div>
        <div data-leaderboard='@json($topToko)' class="flex-1"></div>
        <div class="flex items-center justify-center gap-6 mt-4 pt-4 border-t border-muted-border">
            <a href="{{ route('superadmin.peringkat') }}#toko" class="font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Peringkat Lengkap</a>
            <span class="w-px h-4 bg-muted-border"></span>
            <a href="{{ route('superadmin.manajemen-toko') }}" class="font-label-sm text-[11px] text-on-surface-variant uppercase tracking-widest hover:underline">Kelola Semua Toko</a>
        </div>
    </section>
</div>

<!-- Recent Transactions Table -->
<section data-reveal class="mb-section-gap">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
        <h3 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Transaksi Terbaru</h3>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider text-gold-accent">
                <span class="material-symbols-outlined text-[14px]">receipt_long</span>
                {{ $recentTransactions->count() }} transaksi
            </span>
            <a href="{{ route('superadmin.laporan.export', ['period' => request('period', 30)]) }}" class="inline-flex items-center gap-2 px-3 py-2 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[18px]">download</span> Export CSV
            </a>
        </div>
    </div>
    <div class="border border-muted-border bg-surface-container-lowest rounded-xl overflow-x-auto hidden md:block card-premium">
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
            <article class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">receipt_long</span>
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
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('SuperAdmin.partials.dd-helpers')
<script>
    let revenueChart = null;
    let currentTrendRange = @json($activeRange);
    const rangeData = @json($rangeData);
    const trendRangeLabels = @json($rangeLabels);

    function selectTrendRange(v) {
        document.getElementById('trend-range').value = v;
        ddSet('trendRange', v, trendRangeLabels[v] ?? v);
        currentTrendRange = v;
        if (badgeText) badgeText.textContent = trendRangeLabels[v] ?? v;
        renderTrendChart();
    }

    const chartWrap = document.getElementById('trend-chart-wrap');
    const chartEmpty = document.getElementById('trend-empty');
    const chartError = document.getElementById('trend-error');
    const insightText = document.getElementById('trend-insight-text');
    const totalEl = document.getElementById('trend-total');
    const badgeText = document.getElementById('trend-badge-text');

    const formatRupiahShort = (value) => {
        if (value >= 1000000000) return (value / 1000000000).toFixed(1).replace('.', ',') + ' M';
        if (value >= 1000000) return (value / 1000000).toFixed(1).replace('.', ',') + ' jt';
        if (value >= 1000) return Math.round(value / 1000) + ' rb';
        return value;
    };

    const formatRupiah = (value) => new Intl.NumberFormat('id-ID').format(Math.round(value));

    const chartTheme = () => {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            grid: isDark ? '#333333' : '#E9E8E7',
            tick: isDark ? '#BAB8B8' : '#747878',
            tooltipBg: isDark ? '#F0EEEE' : '#1b1c1c',
            tooltipText: isDark ? '#111111' : '#ffffff'
        };
    };

    const smoothDraw = (total = 950) => ({
        x: { type: 'number', duration: total, easing: 'easeOutQuart', from: (ctx) => (ctx.chart && ctx.chart.chartArea ? ctx.chart.chartArea.left : 0) },
        y: { type: 'number', duration: total, easing: 'easeOutQuart' }
    });

    const renderTrendChart = () => {
        if (typeof Chart === 'undefined') {
            chartWrap?.classList.add('hidden');
            chartEmpty?.classList.add('hidden');
            chartError?.classList.remove('hidden');
            return;
        }
        chartError?.classList.add('hidden');

        const c = chartTheme();
        const data = rangeData[currentTrendRange] || rangeData['30'];

        if (totalEl) totalEl.textContent = 'Rp ' + formatRupiah(data.total || 0);

        const hasData = (data.data || []).some((v) => v > 0);
        chartEmpty?.classList.toggle('hidden', hasData);
        chartWrap?.classList.toggle('hidden', !hasData);

        if (!hasData) {
            if (revenueChart) { revenueChart.destroy(); revenueChart = null; }
            if (insightText) insightText.textContent = 'Belum ada transaksi pada rentang waktu ini.';
            return;
        }

        if (data.best && data.best.value > 0) {
            insightText.textContent = 'Pendapatan tertinggi pada ' + data.best.label + ' sebesar Rp ' + formatRupiah(data.best.value) + '.';
        } else {
            insightText.textContent = 'Pendapatan periode ini: Rp ' + formatRupiah(data.total || 0) + '.';
        }

        if (!revenueChart) {
            revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Pendapatan',
                        data: data.data,
                        borderColor: '#8B1E3F',
                        backgroundColor: 'rgba(139, 30, 63, 0.12)',
                        fill: true,
                        tension: 0.38,
                        borderWidth: 2,
                        pointBackgroundColor: '#8B1E3F',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: smoothDraw(),
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: c.tooltipBg, titleColor: c.tooltipText, bodyColor: c.tooltipText,
                            titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 },
                            padding: 12, cornerRadius: 0, displayColors: false,
                            callbacks: { label: (ctx) => ' Pendapatan: Rp ' + formatRupiah(ctx.raw) }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: c.grid }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 }, callback: (v) => formatRupiahShort(v) } },
                        x: { grid: { display: false }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 }, maxTicksLimit: currentTrendRange === '365' ? 12 : 8, maxRotation: 0, autoSkip: true } }
                    }
                }
            });
            revenueChart.options.animation = { duration: 700, easing: 'easeOutCubic' };
            return;
        }

        revenueChart.data.labels = data.labels;
        revenueChart.data.datasets[0].data = data.data;
        revenueChart.options.scales.y.grid.color = c.grid;
        revenueChart.options.scales.y.ticks.color = c.tick;
        revenueChart.options.scales.x.ticks.color = c.tick;
        revenueChart.update();
    };

    document.getElementById('trend-retry')?.addEventListener('click', () => {
        renderTrendChart();
    });

    window.ralivaOnReady(() => {
        try {
            renderTrendChart();
        } catch (e) {
            chartWrap?.classList.add('hidden');
            chartEmpty?.classList.add('hidden');
            chartError?.classList.remove('hidden');
        }
    });
</script>
@endpush