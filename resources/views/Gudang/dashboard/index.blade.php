@extends('layouts.gudang')

@section('title', 'Dashboard Gudang')

@section('header-title', 'Dashboard Gudang')
@section('header-subtitle', 'Pantau persediaan dan aktivitas gudang Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-[76px] bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-2 xl:grid-cols-3 gap-gutter">
        @for ($i = 0; $i < 6; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <div class="lg:col-span-3 h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="lg:col-span-2 h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
    <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    <section class="rise bg-surface-container-lowest border border-muted-border rounded-lg px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 card-premium">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gold-accent">warehouse</span>
            </div>
            <div>
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Gudang Aktif</p>
                <p class="font-title-md text-title-md text-on-surface leading-tight">Gudang Utama Bandung</p>
            </div>
        </div>
        <div class="relative self-start sm:self-auto">
            <button type="button" data-dropdown-toggle class="w-full sm:w-auto flex items-center justify-between gap-2 border border-muted-border rounded-lg px-4 py-2.5 font-body-md text-sm text-on-surface hover:border-gold-accent transition-colors bg-surface-container-lowest min-w-[180px]">
                <span>Ganti Gudang</span>
                <span class="material-symbols-outlined text-[18px]">expand_more</span>
            </button>
            <div data-dropdown-menu class="hidden absolute right-0 top-full mt-2 w-full sm:w-72 bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-muted-border flex items-center gap-3 bg-surface-container-low">
                    <span class="material-symbols-outlined text-[20px] text-secondary fill">check_circle</span>
                    <div class="flex-1">
                        <p class="font-body-md text-sm text-on-surface">Gudang Utama Bandung</p>
                        <p class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant mt-0.5">Toko Raliva Store Bandung</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20 shrink-0">Aktif</span>
                </div>
                <button type="button" disabled class="w-full px-4 py-3 flex items-center gap-3 opacity-60 cursor-not-allowed text-left">
                    <span class="material-symbols-outlined text-[20px] text-on-surface-variant">lock</span>
                    <div class="flex-1">
                        <p class="font-body-md text-sm text-on-surface-variant">Gudang Cabang Cimahi</p>
                        <p class="font-label-sm text-[10px] uppercase tracking-wider text-error mt-0.5">Tidak Ada Akses</p>
                    </div>
                </button>
            </div>
        </div>
    </section>

    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Gudang</h2>
        <div class="grid grid-cols-2 xl:grid-cols-3 gap-gutter">
            <div class="rise bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produk</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="248">248</span></span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">checkroom</span>
            </div>
            <div class="rise bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Stok</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="8426">8.426</span></span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">inventory_2</span>
            </div>
            <div class="rise bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Barang Masuk Hari Ini</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary"><span data-count="126">126</span></span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">archive</span>
            </div>
            <div class="rise bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Barang Keluar Hari Ini</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary"><span data-count="84">84</span></span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">unarchive</span>
            </div>
            <div class="rise bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Menipis</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent"><span data-count="12">12</span></span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">warning</span>
            </div>
            <div class="rise bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Barang Rusak</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-error"><span data-count="7">7</span></span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">report</span>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <section class="rise lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading whitespace-nowrap">Pergerakan Stok</h2>
                <div class="inline-flex self-start sm:self-auto bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
                    <button type="button" data-chart-range="7" class="chart-range-btn px-3 py-1.5 rounded-md font-label-sm text-[11px] uppercase tracking-wide transition-colors bg-deep-onyx text-on-primary">7 Hari</button>
                    <button type="button" data-chart-range="30" class="chart-range-btn px-3 py-1.5 rounded-md font-label-sm text-[11px] uppercase tracking-wide transition-colors text-on-surface-variant hover:text-on-surface">30 Hari</button>
                    <button type="button" data-chart-range="90" class="chart-range-btn px-3 py-1.5 rounded-md font-label-sm text-[11px] uppercase tracking-wide transition-colors text-on-surface-variant hover:text-on-surface">3 Bulan</button>
                </div>
            </div>
            <div id="chart-wrap" class="relative h-72 md:h-80">
                <canvas id="stok-movement-chart"></canvas>
            </div>
            <div id="chart-error" class="hidden flex-col items-center justify-center h-72 md:h-80 text-center gap-3">
                <div class="w-14 h-14 rounded-full bg-error-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-error-container">cloud_off</span>
                </div>
                <div>
                    <p class="font-title-md text-title-md text-on-surface">Data gagal dimuat</p>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Terjadi masalah saat mengambil data grafik. Silakan coba lagi.</p>
                </div>
                <button type="button" id="chart-retry" class="mt-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Coba Lagi</button>
            </div>
        </section>

        <section class="rise lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Aktivitas Terbaru</h2>
            <ul class="flex flex-col gap-4">
                <li class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container shrink-0">
                            <span class="material-symbols-outlined">archive</span>
                        </div>
                        <div>
                            <span class="font-title-md text-base text-on-surface block">Barang Masuk</span>
                            <span class="text-on-surface-variant font-body-md text-sm">Oversized Linen Shirt <span class="text-secondary font-bold">+50</span></span>
                        </div>
                    </div>
                    <span class="font-label-sm text-xs text-on-surface-variant shrink-0">09:32</span>
                </li>
                <li class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface shrink-0">
                            <span class="material-symbols-outlined">unarchive</span>
                        </div>
                        <div>
                            <span class="font-title-md text-base text-on-surface block">Barang Keluar</span>
                            <span class="text-on-surface-variant font-body-md text-sm">Knit Cardigan Rajut <span class="text-error font-bold">-20</span></span>
                        </div>
                    </div>
                    <span class="font-label-sm text-xs text-on-surface-variant shrink-0">10:15</span>
                </li>
                <li class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center text-gold-accent shrink-0">
                            <span class="material-symbols-outlined">swap_horiz</span>
                        </div>
                        <div>
                            <span class="font-title-md text-base text-on-surface block">Pemindahan Stok</span>
                            <span class="text-on-surface-variant font-body-md text-sm">Pleated Skirt â€¢ 30 unit ke Gudang Cabang</span>
                        </div>
                    </div>
                    <span class="font-label-sm text-xs text-on-surface-variant shrink-0">11:20</span>
                </li>
                <li class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-error-container flex items-center justify-center text-on-error-container shrink-0">
                            <span class="material-symbols-outlined">fact_check</span>
                        </div>
                        <div>
                            <span class="font-title-md text-base text-on-surface block">Pemeriksaan Stok</span>
                            <span class="text-on-surface-variant font-body-md text-sm">Silk Scarf â€¢ Selisih <span class="text-error font-bold">-2</span></span>
                        </div>
                    </div>
                    <span class="font-label-sm text-xs text-on-surface-variant shrink-0">13:40</span>
                </li>
            </ul>
            <a href="{{ route('gudang.riwayat-stok') }}" class="block text-center mt-6 w-full py-3 border border-muted-border rounded-lg font-label-sm text-label-sm text-gold-accent uppercase tracking-widest hover:bg-gold-accent/10 hover:border-gold-accent/40 transition-colors">Lihat Riwayat Lengkap</a>
        </section>
    </div>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Stok Menipis</h2>
            <a href="{{ route('gudang.stok') }}" class="font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline self-start sm:self-auto">Lihat Semua Stok</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[750px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">SKU</th>
                        <th class="p-4 text-center">Stok</th>
                        <th class="p-4 text-center">Minimum Stok</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @foreach ([['Oversized Linen Shirt', 'KEM-001', 8, 20, 'Menipis'], ['Silk Scarf', 'SYL-004', 5, 15, 'Kritis'], ['Straight Fit Pants', 'CEL-014', 12, 25, 'Menipis'], ['Long Sleeve Polo', 'KSL-017', 9, 20, 'Menipis']] as $item)
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-on-surface">{{ $item[0] }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $item[1] }}</td>
                            <td class="p-4 text-center font-bold {{ $item[4] === 'Kritis' ? 'text-error' : 'text-on-surface' }}">{{ $item[2] }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $item[3] }}</td>
                            <td class="p-4 text-center">
                                @if ($item[4] === 'Kritis')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Kritis</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Menipis</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <button type="button" onclick="showRalivaToast('Permintaan restock untuk {{ $item[0] }} dikirim ke Admin Toko.', 'local_shipping')" class="px-3 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors btn-premium">Ajukan Restock</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartCanvas = document.getElementById('stok-movement-chart');
    const chartWrap = document.getElementById('chart-wrap');
    const chartError = document.getElementById('chart-error');
    let movementChart = null;
    let currentRange = '7';

    const rangeData = {
        '7': { labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'], masuk: [120, 180, 145, 210, 165, 240, 190], keluar: [80, 120, 95, 150, 110, 170, 130] },
        '30': { labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'], masuk: [720, 845, 910, 880], keluar: [520, 610, 640, 590] },
        '90': { labels: ['Juni', 'Juli', 'Agustus'], masuk: [2650, 2980, 3120], keluar: [1980, 2210, 2340] }
    };

    const renderMovementChart = () => {
        if (!window.Chart) {
            chartWrap?.classList.add('hidden');
            chartError?.classList.remove('hidden');
            return;
        }
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#333333' : '#E9E8E7';
        const tickColor = isDark ? '#BAB8B8' : '#747878';
        const tooltipBg = isDark ? '#F0EEEE' : '#1b1c1c';
        const tooltipText = isDark ? '#111111' : '#ffffff';
        const data = rangeData[currentRange];
        if (movementChart) movementChart.destroy();
        movementChart = new Chart(chartCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    { label: 'Barang Masuk', data: data.masuk, borderColor: '#C9A24D', backgroundColor: 'rgba(201, 162, 77, 0.1)', fill: true, tension: 0.35, borderWidth: 2, pointBackgroundColor: '#C9A24D', pointRadius: 3 },
                    { label: 'Barang Keluar', data: data.keluar, borderColor: tickColor, backgroundColor: 'transparent', fill: false, tension: 0.35, borderWidth: 2, pointBackgroundColor: tickColor, pointRadius: 3 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, color: tickColor, font: { family: 'Manrope', size: 12 } } },
                    tooltip: { backgroundColor: tooltipBg, titleColor: tooltipText, bodyColor: tooltipText, titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 }, padding: 12, cornerRadius: 0, displayColors: true }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, font: { family: 'Manrope', size: 11 } } },
                    x: { grid: { display: false }, ticks: { color: tickColor, font: { family: 'Manrope', size: 11 } } }
                }
            }
        });
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
            renderMovementChart();
        });
    });

    document.getElementById('chart-retry')?.addEventListener('click', () => {
        chartError?.classList.add('hidden');
        chartWrap?.classList.remove('hidden');
        renderMovementChart();
    });

    try {
        renderMovementChart();
    } catch (e) {
        chartWrap?.classList.add('hidden');
        chartError?.classList.remove('hidden');
    }
</script>
@endpush
