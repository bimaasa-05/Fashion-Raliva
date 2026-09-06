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
    @if(! $store)
        <div class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm text-on-surface">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Anda belum memiliki toko. Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk melihat dashboard, produk, dan pesanan. Jelajahi dulu halaman dashboard untuk mengenal fiturnya.</p>
            </div>
        </div>
    @endif
    {{-- Identitas Toko — selalu tampil, placeholder jika belum punya toko --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg px-6 py-5 flex flex-col md:flex-row md:items-center justify-between gap-5 card-premium {{ ! $store ? 'opacity-60 pointer-events-none' : '' }}">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl overflow-hidden border border-outline-variant shrink-0 bg-surface-container-high flex items-center justify-center {{ ! $store ? 'grayscale' : '' }}">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo {{ $store?->nama_toko ?? 'Toko' }}" class="w-full h-full object-cover" />
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <p class="raliva-figure text-xl {{ ! $store ? 'text-on-surface-variant' : 'text-on-surface' }}">{{ $store?->nama_toko ?? 'Belum punya toko' }}</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $store?->status === 'aktif' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }} text-[10px] font-bold uppercase border">
                        <span class="material-symbols-outlined fill text-[12px]">{{ $store?->status === 'aktif' ? 'verified' : 'schedule' }}</span>{{ $store ? ucfirst($store->status) : 'Menunggu' }}
                    </span>
                    @if(! $store)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[10px] font-bold uppercase"><span class="material-symbols-outlined text-[12px]">lock</span>Ajukan dulu</span>
                    @endif
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">{{ $store?->alamat ?? 'Ajukan toko di Pengajuan Toko untuk mengaktifkan' }} • {{ $store ? 'Aktif sejak ' . optional($store->created_at)->translatedFormat('M Y') : 'Belum ada toko' }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-gutter self-start md:self-auto">
            <div class="flex items-center gap-2 px-3 py-2 bg-surface-container-low rounded-lg border border-muted-border">
                <span class="material-symbols-outlined text-[18px] text-gold-accent fill">star</span>
                <span class="font-title-md text-sm text-on-surface">{{ number_format($rating, 1, ',', '.') }}</span>
                <span class="text-[11px] text-on-surface-variant">{{ $ratingCount }} ulasan</span>
            </div>
            <a href="{{ route('owner.laporan') }}" class="flex items-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">
                <span class="material-symbols-outlined text-[16px]">monitoring</span>Laporan
            </a>
        </div>
    </section>

    {{-- Ringkasan Toko --}}
    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Toko</h2>
        <div data-reveal-group class="grid grid-cols-2 xl:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Penjualan Hari Ini</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ 'Rp ' . number_format($penjualanHariIni, 0, ',', '.') }}</span>
                <span class="font-label-sm text-[11px] text-secondary flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">trending_up</span>penjualan hari ini</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">payments</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pesanan Baru</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $pesananBaru }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu konfirmasi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Produk Aktif</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $produkAktif }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">dari 200 slot paket</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">checkroom</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Saldo Tersedia</span>
                <span class="raliva-figure text-[26px] text-secondary">{{ 'Rp ' . number_format($saldoTersedia, 0, ',', '.') }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">+ {{ 'Rp ' . number_format($saldoTertahan, 0, ',', '.') }} tertunda</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Produk Pending</span>
                <span class="raliva-figure text-[26px] text-gold-accent">{{ $produkPending }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu moderasi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">warning</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komplain Terbuka</span>
                <span class="raliva-figure text-[26px] text-error">{{ $komplainTerbuka }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">butuh respons Anda</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">support_agent</span>
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
                @forelse ($aktivitas as $a)
                    <li>
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-lg bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px] text-gold-accent">{{ $a['icon'] }}</span>
                                </div>
                                <div>
                                    <p class="font-title-md text-sm text-on-surface leading-snug">{{ $a['title'] }}</p>
                                    <p class="text-on-surface-variant text-xs mt-0.5">{{ $a['subtitle'] }}</p>
                                </div>
                            </div>
                            <span class="font-label-sm text-[11px] font-bold text-secondary shrink-0">{{ $a['progress'] }}%</span>
                        </div>
                        <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                            <div class="progress-fill h-full bg-gold-accent rounded-full" data-progress="{{ $a['progress'] }}"></div>
                        </div>
                    </li>
                @empty
                    <li class="text-on-surface-variant text-sm py-4 text-center">Tidak ada aktivitas berjalan saat ini.</li>
                @endforelse
            </ul>
            <p class="mt-6 w-full flex items-center justify-center gap-2 py-3 border border-muted-border rounded-lg text-xs font-semibold text-on-surface-variant">
                Kapasitas Slot Terkunci<span class="material-symbols-outlined text-[16px]">lock</span>
            </p>
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
                    @forelse ($pesananTerbaru as $order)
                        @php
                            $tipe = match($order->status) {
                                'selesai' => 'selesai',
                                'dibatalkan' => 'batal',
                                'dikirim' => 'kirim',
                                'diproses' => 'proses',
                                default => 'pending',
                            };
                            $customer = $order->checkout?->user;
                        @endphp
                        <tr data-table-row data-status="{{ $tipe }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $order->nomor_order }}</td>
                            <td class="py-3.5 px-4 text-on-surface">{{ $customer?->name ?? 'Customer' }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $order->created_at?->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent">{{ 'Rp ' . number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($tipe === 'selesai')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $order->status }}</span>
                                @elseif ($tipe === 'batal')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">{{ $order->status }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $order->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-on-surface-variant">Belum ada pesanan.</td></tr>
                    @endforelse
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
            @forelse ($ulasanTerbaru as $review)
                <article data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-3 card-premium">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1 text-gold-accent">
                            @for ($i = 0; $i < 5; $i++)
                                <span class="material-symbols-outlined text-[16px] {{ $i < $review->rating ? 'fill' : '' }}">star</span>
                            @endfor
                        </div>
                        <span class="font-label-sm text-[10px] text-on-surface-variant uppercase">{{ $review->created_at?->diffForHumans() ?? '' }}</span>
                    </div>
                    <p class="font-body-md text-sm text-on-surface leading-relaxed">“{{ $review->isi_review ?? $review->review ?? '' }}”</p>
                    <div class="pt-3 border-t border-muted-border">
                        <p class="font-title-md text-sm text-on-surface">{{ $review->user?->name ?? 'Customer' }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">di {{ $review->product?->nama_produk ?? '-' }}</p>
                    </div>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm">Belum ada ulasan.</p>
            @endforelse
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

    const rangeData = @json($chart);

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
