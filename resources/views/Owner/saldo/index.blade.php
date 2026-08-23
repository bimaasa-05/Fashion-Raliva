@extends('layouts.owner')

@section('title', 'Saldo Toko')

@section('header-title', 'Saldo Toko')
@section('header-subtitle', 'Pantau saldo tersedia, saldo tertunda, dan riwayat perubahannya.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Kartu Saldo --}}
    <section data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        {{-- Saldo Tersedia --}}
        <div data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 relative overflow-hidden flex flex-col">
            <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            <p class="text-xs font-semibold text-gold-accent relative">Saldo Tersedia</p>
            <p class="raliva-figure text-[34px] md:text-[42px] mt-4 relative">Rp 32.500.000</p>
            <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                <p class="font-body-md text-xs text-inverse-on-surface/60">Siap dicairkan kapan saja</p>
                <a href="{{ route('owner.pencairan-dana') }}" class="py-2.5 px-5 bg-gold-accent text-[#111] text-xs font-semibold rounded btn-premium shrink-0">Cairkan</a>
            </div>
        </div>

        {{-- Saldo Tertunda --}}
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
            <p class="text-xs font-medium text-on-surface-variant relative">Saldo Tertunda</p>
            <p class="raliva-figure text-[26px] mt-4 text-on-surface relative">Rp 7.100.000</p>
            <p class="text-on-surface-variant font-body-md text-xs mt-auto pt-6 relative">Dana dilepas otomatis menjadi saldo tersedia H+2 setelah pesanan selesai.</p>
        </div>

        {{-- Total Dicairkan --}}
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">savings</span>
            <p class="text-xs font-medium text-on-surface-variant relative">Total Dicairkan</p>
            <p class="raliva-figure text-[26px] mt-4 text-secondary relative">Rp 184.500.000</p>
            <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                <p class="font-body-md text-xs text-on-surface-variant">92 pencairan sejak Mar 2026</p>
                <a href="{{ route('owner.pencairan-dana') }}" class="py-2.5 px-5 border border-muted-border text-xs font-semibold rounded-lg hover:border-gold-accent transition-colors shrink-0">Riwayat</a>
            </div>
        </div>
    </section>

    {{-- Grafik Tren Saldo + Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Tren Saldo — 6 Bulan Terakhir</h2>
            <div id="chart-wrap" class="relative h-64 md:h-72"><canvas id="saldo-chart"></canvas></div>
        </section>

        <section data-reveal-group class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Bulan Ini</h2>
            <ul class="space-y-4 font-body-md text-sm">
                @foreach ([['Pesanan Selesai', 'Rp 41.250.000', 'add_circle', 'secondary'], ['Diskon & Komisi Platform', '− Rp 4.950.000', 'remove_circle', 'error'], ['Refund ke Customer', '− Rp 1.890.000', 'remove_circle', 'error'], ['Biaya Pencairan', '− Rp 15.000', 'remove_circle', 'error']] as $item)
                    <li data-reveal class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                        <span class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px] {{ $item[3] === 'secondary' ? 'text-secondary fill' : 'text-error fill' }}">{{ $item[2] }}</span>{{ $item[0] }}
                        </span>
                        <span class="{{ $item[3] === 'secondary' ? 'text-secondary' : 'text-error' }} font-bold whitespace-nowrap">{{ $item[1] }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-6 pt-5 border-t border-muted-border flex items-center justify-between">
                <span class="font-title-md text-sm text-on-surface">Perubahan Bersih</span>
                <span class="font-title-md text-base text-secondary">+ Rp 34.395.000</span>
            </div>
        </section>
    </div>

    {{-- Riwayat Perubahan Saldo --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Riwayat Perubahan Saldo</h2>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[820px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Waktu</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Keterangan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Ref</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Perubahan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['tgl' => '22 Agu 2026, 15:02', 'ket' => 'Pencairan dana ke BCA ****8821', 'ref' => 'WD-0092', 'mutasi' => '-25.000.000', 'masuk' => false, 'akhir' => '32.500.000'],
                        ['tgl' => '22 Agu 2026, 11:40', 'ket' => 'Pesanan selesai — Nadia Putri', 'ref' => '#RLV-2089', 'mutasi' => '+1.890.000', 'masuk' => true, 'akhir' => '57.500.000'],
                        ['tgl' => '21 Agu 2026, 19:55', 'ket' => 'Komplain selesai — refund parsial', 'ref' => 'CMP-0034', 'mutasi' => '-450.000', 'masuk' => false, 'akhir' => '55.610.000'],
                        ['tgl' => '21 Agu 2026, 08:20', 'ket' => 'Pesanan selesai — Kevin Sanjaya', 'ref' => '#RLV-2090', 'mutasi' => '+459.000', 'masuk' => true, 'akhir' => '56.060.000'],
                        ['tgl' => '20 Agu 2026, 17:33', 'ket' => 'Pesanan selesai — Raka Aditya', 'ref' => '#RLV-2087', 'mutasi' => '+3.420.000', 'masuk' => true, 'akhir' => '55.601.000'],
                        ['tgl' => '19 Agu 2026, 14:10', 'ket' => 'Biaya layanan platform Agustus', 'ref' => 'INV-BIAYA-08', 'mutasi' => '-1.240.000', 'masuk' => false, 'akhir' => '52.181.000'],
                    ] as $row)
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['tgl'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface">{{ $row['ket'] }}</td>
                            <td class="py-3.5 px-4 font-bold text-on-surface whitespace-nowrap">{{ $row['ref'] }}</td>
                            <td class="py-3.5 px-4 text-right font-bold whitespace-nowrap {{ $row['masuk'] ? 'text-secondary' : 'text-error' }}">{{ $row['mutasi'] }}</td>
                            <td class="py-3.5 px-4 text-right text-on-surface whitespace-nowrap">{{ $row['akhir'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div data-pagination class="flex items-center justify-between pt-6 mt-2 border-t border-muted-border">
            <p class="text-xs text-on-surface-variant">Menampilkan 6 dari 214 mutasi</p>
            <button type="button" onclick="showRalivaToast('Memuat mutasi berikutnya (demo).')" class="text-xs font-semibold text-gold-accent hover:underline">Muat Lebih Banyak</button>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.ralivaOnReady(() => {
        try {
            const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#333333' : '#E9E8E7';
        const tickColor = isDark ? '#BAB8B8' : '#747878';
        const tooltipBg = isDark ? '#F0EEEE' : '#1b1c1c';
        const tooltipText = isDark ? '#111111' : '#ffffff';

        /* Seluruh titik meluncur serentak dari kiri -> garis terbuka mulus tanpa patah */
        const drawAnim = {
            x: { type: 'number', duration: 950, easing: 'easeOutQuart', from: (ctx) => (ctx.chart && ctx.chart.chartArea ? ctx.chart.chartArea.left : 0) },
            y: { type: 'number', duration: 950, easing: 'easeOutQuart' }
        };

        new Chart(document.getElementById('saldo-chart').getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu'],
                datasets: [
                    { label: 'Saldo Akhir Bulan', data: [18200000, 24600000, 28900000, 34200000, 38100000, 32500000], borderColor: '#C9A24D', backgroundColor: 'rgba(201, 162, 77, 0.12)', fill: true, tension: 0.35, borderWidth: 2, pointBackgroundColor: '#C9A24D', pointRadius: 3 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: drawAnim,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, color: tickColor, font: { family: 'Manrope', size: 12 } } },
                    tooltip: {
                        backgroundColor: tooltipBg, titleColor: tooltipText, bodyColor: tooltipText,
                        titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 },
                        padding: 12, cornerRadius: 0,
                        callbacks: { label: (ctx) => ' Saldo: Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, font: { family: 'Manrope', size: 11 }, callback: (v) => (v / 1000000) + ' jt' } },
                    x: { grid: { display: false }, ticks: { color: tickColor, font: { family: 'Manrope', size: 11 } } }
                }
            }
        });
        } catch (e) {}
    });
</script>
@endpush
