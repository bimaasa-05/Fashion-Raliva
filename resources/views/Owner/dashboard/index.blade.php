@extends('layouts.owner')

@section('title', 'Dashboard Toko')

@section('header-title', 'Dashboard Toko')
@section('header-badge', 'Terverifikasi')
@section('header-subtitle', 'Ringkasan performa penjualan dan aktivitas toko Anda.')

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
    {{-- Identitas Toko --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg px-6 py-5 flex flex-col md:flex-row md:items-center justify-between gap-5 card-premium">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl overflow-hidden border border-outline-variant shrink-0 bg-surface-container-high flex items-center justify-center">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo Raliva Atelier Jakarta" class="w-full h-full object-cover" />
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="raliva-figure text-xl text-on-surface">Raliva Atelier Jakarta</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined fill text-[12px]">verified</span>Terverifikasi
                    </span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Jl. Kemang Raya No. 21, Jakarta Selatan • Aktif sejak Mar 2024</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-gutter self-start md:self-auto">
            <a href="{{ route('owner.laporan') }}" class="flex items-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[16px]">monitoring</span>Laporan
            </a>
            <a href="{{ route('owner.saldo') }}#pencairan" class="flex items-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">
                <span class="material-symbols-outlined text-[16px]">payments</span>Cairkan Dana
            </a>
        </div>
    </section>

    {{-- Ringkasan Toko --}}
    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Toko</h2>
        <div data-reveal-group class="grid grid-cols-2 xl:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Penjualan Hari Ini</span>
                <span class="raliva-figure text-[26px] text-on-surface">Rp 4.820.000</span>
                <span class="font-label-sm text-[11px] text-secondary flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">trending_up</span>+12,4% vs kemarin</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">payments</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pesanan Baru</span>
                <span class="raliva-figure text-[26px] text-on-surface">18</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu konfirmasi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Produk Aktif</span>
                <span class="raliva-figure text-[26px] text-on-surface">142</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">dari 200 slot paket</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">checkroom</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Saldo Tersedia</span>
                <span class="raliva-figure text-[26px] text-secondary">Rp 32.500.000</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">+ Rp 7.100.000 tertunda</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Menipis</span>
                <span class="raliva-figure text-[26px] text-gold-accent">9</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">produk perlu di-restock</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">warning</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komplain Terbuka</span>
                <span class="raliva-figure text-[26px] text-error">3</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">butuh respons Anda</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">support_agent</span>
            </div>
        </div>
    </section>

    {{-- Grafik & Pekerjaan Toko --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Performa Penjualan</h2>
                <div class="inline-flex self-start sm:self-auto bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
                    <button type="button" data-chart-range="7" class="chart-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary">7 Hari</button>
                    <button type="button" data-chart-range="30" class="chart-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface">30 Hari</button>
                    <button type="button" data-chart-range="90" class="chart-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface">3 Bulan</button>
                </div>
            </div>
            <div id="chart-wrap" class="relative h-72 md:h-80">
                <canvas id="sales-chart"></canvas>
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
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Aktivitas Berjalan</h2>
            <ul class="space-y-5">
                <li>
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-gold-accent">precision_manufacturing</span>
                            </div>
                            <div>
                                <p class="font-title-md text-sm text-on-surface leading-snug">Permintaan Produksi PRQ-0041</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">Blazer Wool Premium — 40 unit</p>
                            </div>
                        </div>
                        <span class="font-label-sm text-[11px] font-bold text-secondary shrink-0">65%</span>
                    </div>
                    <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="progress-fill h-full bg-gold-accent rounded-full" data-progress="65"></div>
                    </div>
                </li>
                <li>
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-gold-accent">local_shipping</span>
                            </div>
                            <div>
                                <p class="font-title-md text-sm text-on-surface leading-snug">Pengiriman #RLV-2087</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">12 paket dalam perjalanan — JNE Reguler</p>
                            </div>
                        </div>
                        <span class="font-label-sm text-[11px] font-bold text-secondary shrink-0">40%</span>
                    </div>
                    <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="progress-fill h-full bg-gold-accent rounded-full" data-progress="40"></div>
                    </div>
                </li>
                <li>
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-gold-accent">local_offer</span>
                            </div>
                            <div>
                                <p class="font-title-md text-sm text-on-surface leading-snug">Promo GAJIAN25 Berjalan</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">Diskon 25% — sisa 5 hari, terpakai 84×</p>
                            </div>
                        </div>
                        <span class="font-label-sm text-[11px] font-bold text-secondary shrink-0">56%</span>
                    </div>
                    <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="56"></div>
                    </div>
                </li>
                <li>
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px] text-gold-accent">grid_view</span>
                            </div>
                            <div>
                                <p class="font-title-md text-sm text-on-surface leading-snug">Slot Paket Growth</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">142 dari 200 slot produk terpakai</p>
                            </div>
                        </div>
                        <span class="font-label-sm text-[11px] font-bold text-secondary shrink-0">71%</span>
                    </div>
                    <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="71"></div>
                    </div>
                </li>
            </ul>
            <a href="{{ route('owner.kelola-slot') }}" class="mt-6 w-full flex items-center justify-center gap-2 py-3 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">
                Kelola Slot<span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </section>
    </div>

    {{-- Pesanan Terbaru --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Pesanan Terbaru</h2>
            <a href="{{ route('owner.pesanan') }}" class="text-sm font-semibold text-gold-accent hover:underline shrink-0">Lihat Semua</a>
        </div>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[720px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Customer</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Total</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['kode' => '#RLV-2093', 'customer' => 'Sarah Jenkins', 'tanggal' => '22 Agu 2026', 'total' => 'Rp 1.240.000', 'status' => 'Baru', 'tipe' => 'pending'],
                        ['kode' => '#RLV-2092', 'customer' => 'Dimas Anggara', 'tanggal' => '22 Agu 2026', 'total' => 'Rp 689.000', 'status' => 'Diproses', 'tipe' => 'proses'],
                        ['kode' => '#RLV-2091', 'customer' => 'Aulia Rahma', 'tanggal' => '21 Agu 2026', 'total' => 'Rp 2.150.000', 'status' => 'Dikirim', 'tipe' => 'kirim'],
                        ['kode' => '#RLV-2090', 'customer' => 'Kevin Sanjaya', 'tanggal' => '21 Agu 2026', 'total' => 'Rp 459.000', 'status' => 'Diproses', 'tipe' => 'proses'],
                        ['kode' => '#RLV-2089', 'customer' => 'Nadia Putri', 'tanggal' => '20 Agu 2026', 'total' => 'Rp 1.890.000', 'status' => 'Selesai', 'tipe' => 'selesai'],
                        ['kode' => '#RLV-2088', 'customer' => 'Raka Aditya', 'tanggal' => '20 Agu 2026', 'total' => 'Rp 320.000', 'status' => 'Dibatalkan', 'tipe' => 'batal'],
                    ] as $row)
                        <tr data-table-row data-status="{{ $row['tipe'] }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $row['kode'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface">{{ $row['customer'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $row['tanggal'] }}</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent">{{ $row['total'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row['tipe'] === 'selesai')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $row['status'] }}</span>
                                @elseif ($row['tipe'] === 'batal')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">{{ $row['status'] }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $row['status'] }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div data-empty-state class="hidden flex-col items-center py-10 text-center gap-2">
            <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pesanan yang cocok.</p>
        </div>
    </section>

    {{-- Ulasan Terbaru --}}
    <section>
        <div class="flex items-center justify-between gap-4 mb-6">
            <h2 data-reveal class="font-title-md text-title-md text-on-surface premium-heading">Ulasan Terbaru</h2>
            <a data-reveal href="{{ route('owner.ulasan') }}" class="text-sm font-semibold text-gold-accent hover:underline shrink-0">Lihat Semua</a>
        </div>
        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            @foreach ([
                ['nama' => 'Sarah Jenkins', 'produk' => 'Trench Coat Signature', 'rating' => 5, 'isi' => 'Bahan premium, jahitan rapi. Pengiriman cepat dan packaging elegan!', 'waktu' => '2 jam lalu'],
                ['nama' => 'Dimas Anggara', 'produk' => 'Blazer Wool Premium', 'rating' => 4, 'isi' => 'Pas di badan, warnanya sesuai foto. Akan repeat order lagi.', 'waktu' => '5 jam lalu'],
                ['nama' => 'Aulia Rahma', 'produk' => 'Silk Scarf Monogram', 'rating' => 5, 'isi' => 'Hadiah untuk ibu, beliau sangat suka. Terima kasih Raliva!', 'waktu' => 'Kemarin'],
            ] as $review)
                <article data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-3 card-premium">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1 text-gold-accent">
                            @for ($i = 0; $i < 5; $i++)
                                <span class="material-symbols-outlined text-[16px] {{ $i < $review['rating'] ? 'fill' : '' }}">star</span>
                            @endfor
                        </div>
                        <span class="font-label-sm text-[10px] text-on-surface-variant uppercase">{{ $review['waktu'] }}</span>
                    </div>
                    <p class="font-body-md text-sm text-on-surface leading-relaxed">“{{ $review['isi'] }}”</p>
                    <div class="pt-3 border-t border-muted-border">
                        <p class="font-title-md text-sm text-on-surface">{{ $review['nama'] }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">di {{ $review['produk'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let salesChart = null;
    let currentRange = '7';
    const chartWrap = document.getElementById('chart-wrap');
    const chartError = document.getElementById('chart-error');

    const rangeData = {
        '7': { labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'], penjualan: [3200000, 2850000, 4100000, 3650000, 5200000, 4820000, 2950000], pesanan: [14, 11, 19, 16, 24, 18, 12] },
        '30': { labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'], penjualan: [18400000, 21650000, 24820000, 27300000], pesanan: [86, 102, 118, 134] },
        '90': { labels: ['Juni', 'Juli', 'Agustus'], penjualan: [68200000, 75400000, 82300000], pesanan: [312, 356, 389] }
    };

    const formatRupiahShort = (value) => {
        if (value >= 1000000) return (value / 1000000).toFixed(1).replace('.', ',') + ' jt';
        if (value >= 1000) return Math.round(value / 1000) + ' rb';
        return value;
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

    /* Seluruh titik meluncur serentak dari kiri -> garis terbuka mulus tanpa patah */
    const smoothDraw = (total = 950) => ({
        x: { type: 'number', duration: total, easing: 'easeOutQuart', from: (ctx) => (ctx.chart && ctx.chart.chartArea ? ctx.chart.chartArea.left : 0) },
        y: { type: 'number', duration: total, easing: 'easeOutQuart' }
    });

    const renderSalesChart = () => {
        if (!window.Chart) {
            chartWrap?.classList.add('hidden');
            chartError?.classList.remove('hidden');
            return;
        }
        const c = salesTheme();
        const data = rangeData[currentRange];

        if (!salesChart) {
            salesChart = new Chart(document.getElementById('sales-chart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        { label: 'Penjualan', data: data.penjualan, borderColor: '#C9A24D', backgroundColor: 'rgba(201, 162, 77, 0.12)', fill: true, tension: 0.38, borderWidth: 2, pointBackgroundColor: '#C9A24D', pointRadius: 3 },
                        { label: 'Jumlah Pesanan', data: data.pesanan, borderColor: c.tick, backgroundColor: 'transparent', fill: false, tension: 0.38, borderWidth: 2, pointBackgroundColor: c.tick, pointRadius: 3, yAxisID: 'y1' }
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
                            callbacks: { label: (ctx) => ctx.datasetIndex === 0 ? ' Penjualan: Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) : ' Pesanan: ' + ctx.raw }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, position: 'left', grid: { color: c.grid }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 }, callback: (v) => formatRupiahShort(v) } },
                        y1: { beginAtZero: true, position: 'right', grid: { display: false }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 } } },
                        x: { grid: { display: false }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 } } }
                    }
                }
            });
            /* Setelah render pertama: pakai transisi standar agar ganti rentang tetap glide */
            salesChart.options.animation = { duration: 700, easing: 'easeOutCubic' };
            return;
        }

        /* Transisi mulus saat ganti rentang: elemen meluncur dari nilai lama ke baru */
        salesChart.data.labels = data.labels;
        salesChart.data.datasets[0].data = data.penjualan;
        salesChart.data.datasets[1].data = data.pesanan;
        salesChart.data.datasets[1].borderColor = c.tick;
        salesChart.data.datasets[1].pointBackgroundColor = c.tick;
        salesChart.options.scales.y.grid.color = c.grid;
        salesChart.options.scales.y.ticks.color = c.tick;
        salesChart.options.scales.y1.ticks.color = c.tick;
        salesChart.options.scales.x.ticks.color = c.tick;
        salesChart.options.plugins.legend.labels.color = c.tick;
        salesChart.update();
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
            renderSalesChart();
        });
    });

    document.getElementById('chart-retry')?.addEventListener('click', () => {
        chartError?.classList.add('hidden');
        chartWrap?.classList.remove('hidden');
        renderSalesChart();
    });

    window.ralivaOnReady(() => {
        try {
            renderSalesChart();
        } catch (e) {
            chartWrap?.classList.add('hidden');
            chartError?.classList.remove('hidden');
        }
    });
</script>
@endpush
