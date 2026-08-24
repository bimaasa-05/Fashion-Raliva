@extends('layouts.produksi')

@section('title', 'Dashboard Produksi')

@section('header-title', 'Dashboard Produksi')
@section('header-badge', 'Atelier Aktif')
@section('header-subtitle', 'Ringkasan permintaan, proses produksi dan hasil kerja harian Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-[110px] bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-2 xl:grid-cols-3 gap-gutter">
        @for ($i = 0; $i < 6; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <div class="lg:col-span-3 h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="lg:col-span-2 h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
    <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Identitas Workshop --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg px-6 py-5 flex flex-col md:flex-row md:items-center justify-between gap-5 card-premium">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-deep-onyx text-on-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[28px]">precision_manufacturing</span>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="raliva-figure text-xl text-on-surface">Atelier Produksi Raliva</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined fill text-[12px]">verified</span>Workshop Kemang
                    </span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Raliva Atelier Jakarta • 12 staf aktif • Kapasitas 120 unit/hari</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-gutter self-start md:self-auto">
            <a href="{{ route('produksi.permintaan-produksi') }}" class="flex items-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[16px]">assignment</span>Permintaan Baru
            </a>
            <a href="{{ route('produksi.data-produksi') }}" class="flex items-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">
                <span class="material-symbols-outlined text-[16px]">add</span>Catat Produksi
            </a>
        </div>
    </section>

    {{-- Ringkasan Produksi --}}
    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Produksi</h2>
        <div data-reveal-group class="grid grid-cols-2 xl:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Permintaan Baru</span>
                <span class="raliva-figure text-[26px] text-gold-accent">4</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu konfirmasi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">assignment</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Produksi Berjalan</span>
                <span class="raliva-figure text-[26px] text-on-surface">6</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">sedang dikerjakan</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">precision_manufacturing</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu QC</span>
                <span class="raliva-figure text-[26px] text-gold-accent">3</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">perlu pemeriksaan</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">fact_check</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai Hari Ini</span>
                <span class="raliva-figure text-[26px] text-secondary">42</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">unit layak jual</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Barang Rusak</span>
                <span class="raliva-figure text-[26px] text-error">5</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">defect perlu penanganan</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">report</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Bahan Menipis</span>
                <span class="raliva-figure text-[26px] text-error">2</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">perlu isi ulang</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">inventory</span>
            </div>
        </div>
    </section>

    {{-- Grafik & Aktivitas --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Output Produksi</h2>
                <div class="inline-flex self-start sm:self-auto bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
                    <button type="button" data-chart-range="7" class="chart-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary">7 Hari</button>
                    <button type="button" data-chart-range="30" class="chart-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface">30 Hari</button>
                    <button type="button" data-chart-range="90" class="chart-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface">3 Bulan</button>
                </div>
            </div>
            <div id="chart-wrap" class="relative h-72 md:h-80">
                <canvas id="produksi-chart"></canvas>
            </div>
            <div id="chart-error" class="hidden flex-col items-center justify-center h-72 md:h-80 text-center gap-3">
                <div class="w-14 h-14 rounded-full bg-error-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-error-container">cloud_off</span>
                </div>
                <div>
                    <p class="font-title-md text-title-md text-on-surface">Data gagal dimuat</p>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Terjadi masalah saat mengambil data grafik. Silakan coba lagi.</p>
                </div>
                <button type="button" id="chart-retry" class="mt-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium">Coba Lagi</button>
            </div>
        </section>

        <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Produksi Prioritas</h2>
            <ul class="space-y-5">
                <li>
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-gold-accent">checkroom</span>
                            </div>
                            <div>
                                <p class="font-title-md text-sm text-on-surface leading-snug">Blazer Wool Premium — 40 unit</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">PRQ-0041 • Target 30 Agu</p>
                            </div>
                        </div>
                        <span class="font-label-sm text-[11px] font-bold text-secondary shrink-0">65%</span>
                    </div>
                    <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="progress-fill h-full rounded-full" data-progress="65"></div>
                    </div>
                </li>
                <li>
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-gold-accent">apparel</span>
                            </div>
                            <div>
                                <p class="font-title-md text-sm text-on-surface leading-snug">Kemeja Linen Oversized — 120 unit</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">PRQ-0040 • Menunggu QC</p>
                            </div>
                        </div>
                        <span class="font-label-sm text-[11px] font-bold text-gold-accent shrink-0">90%</span>
                    </div>
                    <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="progress-fill h-full rounded-full" data-progress="90"></div>
                    </div>
                </li>
                <li>
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-gold-accent">style</span>
                            </div>
                            <div>
                                <p class="font-title-md text-sm text-on-surface leading-snug">Silk Scarf Monogram — 80 unit</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">PRQ-0042 • Menunggu konfirmasi</p>
                            </div>
                        </div>
                        <span class="font-label-sm text-[11px] font-bold text-on-surface-variant shrink-0">0%</span>
                    </div>
                    <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="progress-fill h-full rounded-full" data-progress="0"></div>
                    </div>
                </li>
                <li>
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-gold-accent">inventory_2</span>
                            </div>
                            <div>
                                <p class="font-title-md text-sm text-on-surface leading-snug">Bahan Baku — Kain Katun Premium</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">Sisa 18 meter • Menipis</p>
                            </div>
                        </div>
                        <span class="font-label-sm text-[11px] font-bold text-error shrink-0">15%</span>
                    </div>
                    <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="15"></div>
                    </div>
                </li>
            </ul>
            <a href="{{ route('produksi.permintaan-produksi') }}" class="mt-6 w-full flex items-center justify-center gap-2 py-3 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">
                Lihat Permintaan<span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </section>
    </div>

    {{-- Aktivitas Terbaru --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Aktivitas Terbaru</h2>
            <a href="{{ route('produksi.riwayat-produksi') }}" class="text-sm font-semibold text-gold-accent hover:underline shrink-0">Lihat Riwayat</a>
        </div>
        <ul class="space-y-4">
            <li class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container shrink-0">
                        <span class="material-symbols-outlined">task_alt</span>
                    </div>
                    <div>
                        <span class="font-title-md text-base text-on-surface block">Produk Selesai</span>
                        <span class="text-on-surface-variant font-body-md text-sm">Wide Leg Trousers <span class="text-secondary font-bold">60 unit</span> siap serah ke Gudang</span>
                    </div>
                </div>
                <span class="font-label-sm text-xs text-on-surface-variant shrink-0">09:12</span>
            </li>
            <li class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center text-gold-accent shrink-0">
                        <span class="material-symbols-outlined">fact_check</span>
                    </div>
                    <div>
                        <span class="font-title-md text-base text-on-surface block">Pemeriksaan QC</span>
                        <span class="text-on-surface-variant font-body-md text-sm">Blazer Wool Premium — <span class="text-secondary font-bold">38 layak</span> / 2 defect</span>
                    </div>
                </div>
                <span class="font-label-sm text-xs text-on-surface-variant shrink-0">08:30</span>
            </li>
            <li class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-error/10 border border-error/20 flex items-center justify-center text-error shrink-0">
                        <span class="material-symbols-outlined">report</span>
                    </div>
                    <div>
                        <span class="font-title-md text-base text-on-surface block">Barang Rusak</span>
                        <span class="text-on-surface-variant font-body-md text-sm">Silk Scarf Monogram — <span class="text-error font-bold">3 unit</span> cacat printing</span>
                    </div>
                </div>
                <span class="font-label-sm text-xs text-on-surface-variant shrink-0">Kemarin</span>
            </li>
        </ul>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let produksiChart = null;
    let currentRange = '7';
    const chartWrap = document.getElementById('chart-wrap');
    const chartError = document.getElementById('chart-error');

    const rangeData = {
        '7': { labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'], output: [32, 48, 28, 52, 42, 60, 38], target: [40, 40, 40, 40, 40, 40, 40] },
        '30': { labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'], output: [182, 210, 248, 220], target: [240, 240, 240, 240] },
        '90': { labels: ['Juni', 'Juli', 'Agustus'], output: [620, 740, 860], target: [720, 720, 720] }
    };

    const salesTheme = () => {
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

    const renderProduksiChart = () => {
        if (!window.Chart) {
            chartWrap?.classList.add('hidden');
            chartError?.classList.remove('hidden');
            return;
        }
        const c = salesTheme();
        const data = rangeData[currentRange];

        if (!produksiChart) {
            produksiChart = new Chart(document.getElementById('produksi-chart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        { label: 'Output Aktual', data: data.output, borderColor: '#C9A24D', backgroundColor: 'rgba(201, 162, 77, 0.12)', fill: true, tension: 0.38, borderWidth: 2, pointBackgroundColor: '#C9A24D', pointRadius: 3 },
                        { label: 'Target Harian', data: data.target, borderColor: c.tick, borderDash: [6, 4], backgroundColor: 'transparent', fill: false, tension: 0.2, borderWidth: 1.5, pointRadius: 0 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: smoothDraw(),
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, color: c.tick, font: { family: 'Manrope', size: 12 } } },
                        tooltip: {
                            backgroundColor: c.tooltipBg, titleColor: c.tooltipText, bodyColor: c.tooltipText,
                            titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 },
                            padding: 12, cornerRadius: 0, displayColors: true,
                            callbacks: { label: (ctx) => ' ' + ctx.dataset.label + ': ' + ctx.raw + ' unit' }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: c.grid }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 } } },
                        x: { grid: { display: false }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 } } }
                    }
                }
            });
            produksiChart.options.animation = { duration: 700, easing: 'easeOutCubic' };
            return;
        }

        produksiChart.data.labels = data.labels;
        produksiChart.data.datasets[0].data = data.output;
        produksiChart.data.datasets[1].data = data.target;
        produksiChart.options.scales.y.grid.color = c.grid;
        produksiChart.options.scales.y.ticks.color = c.tick;
        produksiChart.options.scales.x.ticks.color = c.tick;
        produksiChart.options.plugins.legend.labels.color = c.tick;
        produksiChart.update();
    };

    const setActiveRangeButton = () => {
        document.querySelectorAll('.chart-range-btn').forEach((b) => {
            const isActive = b.getAttribute('data-chart-range') === currentRange;
            b.classList.toggle('bg-deep-onyx', isActive);
            b.classList.toggle('text-on-primary', isActive);
            b.classList.toggle('text-on-surface-variant', !isActive);
        });
    };

    document.querySelectorAll('[data-chart-range]').forEach((btn) => {
        btn.addEventListener('click', () => {
            currentRange = btn.getAttribute('data-chart-range');
            setActiveRangeButton();
            renderProduksiChart();
        });
    });

    document.getElementById('chart-retry')?.addEventListener('click', () => {
        chartError?.classList.add('hidden');
        chartWrap?.classList.remove('hidden');
        renderProduksiChart();
    });

    window.ralivaOnReady(() => {
        try {
            renderProduksiChart();
        } catch (e) {
            chartWrap?.classList.add('hidden');
            chartError?.classList.remove('hidden');
        }
    });
</script>
@endpush
