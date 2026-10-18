@extends('layouts.admin')

@section('title', __('Laporan Operasional'))

@section('header-title', __('Laporan Operasional'))
@section('header-badge', __('Terbatas'))
@section('header-subtitle', __('Laporan operasional toko yang kamu tugaskan.'))

@section('content')
@php $noStore = !empty($tanpaToko); @endphp
<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">{{ __('Laporan ini hanya mencakup operasional toko yang kamu tugaskan, sesuai permission dari Owner.') }}</p>
    </div>
    @if(!empty($tanpaToko))
        <div class="flex items-start gap-3 p-4 border border-amber-500/40 bg-amber-500/10 rounded-lg">
            <span class="material-symbols-outlined text-amber-600 text-[20px] mt-0.5">info</span>
            <p class="font-body-md text-sm text-on-surface">Anda belum ditugaskan ke toko aktif mana pun, sehingga angka laporan masih kosong. Minta Owner menugaskan Anda lewat menu Karyawan.</p>
        </div>
    @endif

    <div class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 overflow-x-auto shrink-0">
        <button type="button" data-lap-tab="operasional" class="lap-tab px-4 py-2 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">{{ __('Operasional Toko') }}</button>
        <button type="button" data-lap-tab="penjualan" class="lap-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">{{ __('Penjualan Saya') }}</button>
    </div>

    <div data-lap-type="operasional" class="space-y-section-gap">
    {{-- Toolbar: rentang tanggal + export --}}
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 card-premium">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">{{ __('Kinerja Operasional') }}</h2>
                <p class="text-xs text-on-surface-variant mt-1">{{ $dari->translatedFormat('d M Y') }} &mdash; {{ $sampai->translatedFormat('d M Y') }}</p>
            </div>
            <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                <div class="flex items-center gap-1.5">
                    @foreach([7 => __('7 Hari'), 30 => __('30 Hari'), 90 => __('90 Hari')] as $hari => $label)
                        <a href="{{ route('admin.laporan', ['dari' => now()->subDays($hari - 1)->toDateString(), 'sampai' => now()->toDateString()]) }}"
                           class="px-3 py-1.5 rounded-md text-xs font-medium border transition-colors whitespace-nowrap {{ ($presetAktif ?? null) === $hari ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:border-gold-accent' }}">{{ $label }}</a>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <form method="GET" class="flex items-center gap-2 flex-wrap">
                        <input type="date" name="dari" value="{{ $dari->toDateString() }}" max="{{ date('Y-m-d') }}" class="raliva-input text-xs py-2 w-auto" aria-label="{{ __('Dari tanggal') }}" title="{{ __('Dari tanggal') }}" />
                        <span class="text-on-surface-variant text-xs">{{ __('s/d') }}</span>
                        <input type="date" name="sampai" value="{{ $sampai->toDateString() }}" max="{{ date('Y-m-d') }}" class="raliva-input text-xs py-2 w-auto" aria-label="{{ __('Sampai tanggal') }}" title="{{ __('Sampai tanggal') }}" />
                        <button type="submit" class="px-4 py-2 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium">{{ __('Terapkan') }}</button>
                    </form>

                    <a href="{{ route('admin.laporan.export-excel', ['dari' => $dari->toDateString(), 'sampai' => $sampai->toDateString()]) }}"
                       @if($noStore) aria-disabled="true" tabindex="-1" title="{{ __('Belum ada toko untuk diekspor.') }}" @endif
                       class="flex items-center justify-center gap-2 px-4 py-2 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0 {{ $noStore ? 'opacity-60 pointer-events-none' : '' }}">
                        <span class="material-symbols-outlined text-[16px]">{{ $noStore ? 'lock' : 'download' }}</span>{{ __('Excel') }}
                    </a>
                    <a href="{{ route('admin.laporan.cetak', ['dari' => $dari->toDateString(), 'sampai' => $sampai->toDateString()]) }}" target="_blank"
                       @if($noStore) aria-disabled="true" tabindex="-1" title="{{ __('Belum ada toko untuk diekspor.') }}" @endif
                       class="flex items-center justify-center gap-2 px-4 py-2 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium shrink-0 {{ $noStore ? 'opacity-60 pointer-events-none' : '' }}">
                        <span class="material-symbols-outlined text-[16px]">{{ $noStore ? 'lock' : 'picture_as_pdf' }}</span>PDF
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Pesanan Berpendapatan') }}</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $pesananDiproses ?? 0 }}</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Total Pendapatan') }}</span>
                <span class="raliva-figure text-[26px] {{ ($pendapatan ?? 0) >= 0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($pendapatan ?? 0,0,',','.') }}</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">payments</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Total Pengeluaran') }}</span>
                <span class="raliva-figure text-[26px] text-error">Rp {{ number_format($totalPengeluaran ?? 0,0,',','.') }}</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">receipt_long</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Total Bersih') }}</span>
                <span class="raliva-figure text-[26px] {{ ($totalBersih ?? 0) >=0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($totalBersih ?? 0,0,',','.') }}</span>
                <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            </div>
        </div>
    </section>

    {{-- Grafik: Omzet (kiri) + Metode Terbanyak (kanan) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
        <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
            <div class="mb-6">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">{{ __('Grafik Tren Omzet') }}</h2>
                <p class="text-xs text-on-surface-variant mt-1">{{ __('Pendapatan harian pada rentang tanggal terpilih.') }}</p>
            </div>
            @if(array_sum(array_column($omzetBars ?? [], 'value')) > 0)
                <div class="relative h-56 md:h-64">
                    <canvas id="admin-revenue-chart" role="img" aria-label="{{ __('Grafik tren omzet harian') }}"></canvas>
                </div>
            @else
                <div class="h-56 md:h-64 flex flex-col items-center justify-center text-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[32px] opacity-50">show_chart</span>
                    <p class="font-body-md text-sm">{{ __('Belum ada pendapatan pada periode ini.') }}</p>
                </div>
            @endif
            @php $stat = $ringkasanHarian ?? ['total' => 0, 'rata' => 0, 'maks' => 0, 'tanggal_maks' => null]; @endphp
            <div class="grid grid-cols-3 gap-3 mt-6 pt-5 border-t border-muted-border">
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">{{ __('Total') }}</p>
                    <p class="text-sm font-bold text-on-surface mt-1">Rp {{ number_format($stat['total'], 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">{{ __('Rata-rata/Hari') }}</p>
                    <p class="text-sm font-bold text-on-surface mt-1">Rp {{ number_format($stat['rata'], 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">{{ __('Hari Terbaik') }}</p>
                    <p class="text-sm font-bold text-gold-accent mt-1">{{ $stat['tanggal_maks'] ?? '-' }}</p>
                    <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $stat['maks'] > 0 ? 'Rp '.number_format($stat['maks'], 0, ',', '.') : '' }}</p>
                </div>
            </div>
        </section>

        <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">{{ __('Metode Pembayaran Terbanyak') }}</h2>
                <span class="material-symbols-outlined text-gold-accent text-[20px]">donut_small</span>
            </div>
            @if(!empty($metodeTerbanyak))
                <div class="flex items-center gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg mb-4">
                    <span class="material-symbols-outlined text-gold-accent text-[28px]">workspace_premium</span>
                    <div>
                        <p class="font-bold text-on-surface">{{ $metodeTerbanyak->nama_metode }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $metodeTerbanyak->jumlah_transaksi }} {{ __('transaksi') }} · Rp {{ number_format($metodeTerbanyak->total,0,',','.') }}</p>
                    </div>
                </div>
                <p class="text-on-surface-variant font-body-md text-xs mb-4">{{ __('Metode yang paling sering dipakai customer pada periode ini adalah') }} <span class="font-semibold text-on-surface">{{ $metodeTerbanyak->nama_metode }}</span>. {{ __('Visualisasi distribusi seluruh metode ada di bawah.') }}</p>
            @else
                <p class="text-on-surface-variant text-sm py-4">{{ __('Belum ada pembayaran terverifikasi pada periode ini.') }}</p>
            @endif
            @if (count($distribusiMetode ?? []))
                <div data-donut='@json($distribusiMetode)' data-donut-label="Transaksi" class="flex-1 flex items-center justify-center min-h-[220px]"></div>
            @endif
        </section>
    </div>

    {{-- Tabel Pesanan Selesai (income) --}}
    <section class="space-y-gutter">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">{{ __('Pesanan Selesai') }}</h2>
                <p class="text-xs text-on-surface-variant mt-1">{{ __('10 pesanan selesai terbaru pada periode filter — pesanan yang sudah menjadi pendapatan.') }}</p>
            </div>
            <a href="{{ route('admin.pesanan') }}" class="px-4 py-2 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">{{ __('Lihat Semua') }}</a>
        </div>
        <div class="overflow-x-auto hidden md:block bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[720px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">{{ __('Nomor Order') }}</th>
                        <th class="p-4 text-left">{{ __('Tanggal') }}</th>
                        <th class="p-4 text-left">{{ __('Customer') }}</th>
                        <th class="p-4 text-left">{{ __('Metode Bayar') }}</th>
                        <th class="p-4 text-center">{{ __('Total') }}</th>
                        <th class="p-4 text-center">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse($pesananSelesai ?? collect() as $p)
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-on-surface font-semibold">{{ $p->nomor_order ?? ('#'.$p->order_id) }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $p->created_at?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td class="p-4 text-on-surface">{{ $p->checkout?->user?->nama_lengkap ?? '-' }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $p->checkout?->payment?->paymentMethod?->nama_metode ?? '-' }}</td>
                            <td class="p-4 text-center text-secondary font-semibold">Rp {{ number_format($p->grand_total,0,',','.') }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-0.5 rounded-full bg-tertiary-container/20 text-tertiary border border-tertiary/25 font-label-sm text-[10px] uppercase tracking-wider">{{ __('Selesai') }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center text-on-surface-variant">{{ __('Belum ada pesanan selesai pada periode ini.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse($pesananSelesai ?? collect() as $p)
                <article class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-bold text-on-surface">{{ $p->nomor_order ?? ('#'.$p->order_id) }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $p->created_at?->translatedFormat('d M Y') ?? '-' }} · {{ $p->checkout?->user?->nama_lengkap ?? '-' }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $p->checkout?->payment?->paymentMethod?->nama_metode ?? '-' }}</p>
                        </div>
                        <p class="shrink-0 font-bold text-secondary mt-0.5">Rp {{ number_format($p->grand_total,0,',','.') }}</p>
                    </div>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-6 text-center">{{ __('Belum ada pesanan selesai pada periode ini.') }}</p>
            @endforelse
        </div>
    </section>

    </div>

    <div data-lap-type="penjualan" class="space-y-section-gap hidden">
    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">{{ __('Laporan Penjualan Saya') }}</h2>
        <p class="text-xs text-on-surface-variant -mt-2">{{ __('Order yang pembayarannya kamu verifikasi sendiri — sebagai kasir, kontribusimu terpisah dari angka toko.') }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Pesanan Saya') }}</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $saya['pesanan'] ?? 0 }}</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Pendapatan Saya') }}</span>
                <span class="raliva-figure text-[26px] {{ ($saya['pendapatan'] ?? 0) >= 0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($saya['pendapatan'] ?? 0,0,',','.') }}</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Pengeluaran Saya') }}</span>
                <span class="raliva-figure text-[26px] text-error">Rp {{ number_format($saya['pengeluaran'] ?? 0,0,',','.') }}</span>
            </div>
            <div class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Bersih Saya') }}</span>
                <span class="raliva-figure text-[26px] {{ ($saya['bersih'] ?? 0) >= 0 ? 'text-secondary' : 'text-error' }}">Rp {{ number_format($saya['bersih'] ?? 0,0,',','.') }}</span>
            </div>
        </div>
        <p class="text-xs text-on-surface-variant mt-2">{{ __('Pengeluaran saya = refund yang saya selesaikan (hanya refund berstatus selesai) + pengeluaran toko yang saya catat. Berlaku untuk toko yang ditugaskan (AdminContext).') }}</p>
    </section>
    </div>

    <div data-lap-type="operasional" class="space-y-section-gap">
    <section class="space-y-gutter">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">{{ __('Pendapatan per Metode Pembayaran') }}</h2>
            <div class="overflow-x-auto hidden md:block bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
                <table class="w-full min-w-[480px] premium-table">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                            <th class="p-4 text-left">{{ __('Metode') }}</th>
                            <th class="p-4 text-center">{{ __('Transaksi') }}</th>
                            <th class="p-4 text-center">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-md text-sm">
                        @forelse($perMetode ?? collect() as $m)
                            <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                                <td class="p-4 text-on-surface">{{ $m->nama_metode }}</td>
                                <td class="p-4 text-center text-on-surface">{{ $m->jumlah_transaksi }}</td>
                                <td class="p-4 text-center text-secondary">Rp {{ number_format($m->total,0,',','.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-8 text-center text-on-surface-variant">{{ __('Belum ada pembayaran terverifikasi.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="md:hidden grid grid-cols-1 gap-gutter">
                @forelse($perMetode ?? collect() as $m)
                    <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="font-bold text-on-surface">{{ $m->nama_metode }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $m->jumlah_transaksi }} {{ __('transaksi') }}</p>
                            </div>
                            <p class="shrink-0 font-bold text-secondary mt-0.5">Rp {{ number_format($m->total,0,',','.') }}</p>
                        </div>
                    </article>
                @empty
                    <p class="text-on-surface-variant text-sm py-6 text-center">{{ __('Belum ada pembayaran terverifikasi.') }}</p>
                @endforelse
            </div>
            <p class="text-xs text-on-surface-variant mt-2">{{ __('Berdasarkan pembayaran berstatus terverifikasi pada toko yang ditugaskan kepada Anda.') }}</p>
        </section>
    </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.querySelectorAll('[data-lap-tab]').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('[data-lap-tab]').forEach(t => {
                t.classList.remove('bg-deep-onyx', 'text-on-primary');
                t.classList.add('text-on-surface-variant', 'hover:text-on-surface');
            });
            tab.classList.add('bg-deep-onyx', 'text-on-primary');
            tab.classList.remove('text-on-surface-variant', 'hover:text-on-surface');
            const mode = tab.dataset.lapTab;
            document.querySelectorAll('[data-lap-type]').forEach(el => {
                el.classList.toggle('hidden', el.dataset.lapType !== mode);
            });
        });
    });

    const adminRevenueData = @json($omzetBars ?? []);
    const adminChartReady = window.ralivaOnReady || ((fn) => {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    });
    let adminRevenueChart = null;

    const adminThemeColors = () => {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            grid: isDark ? '#333333' : '#E9E8E7',
            tick: isDark ? '#BAB8B8' : '#747878',
            tooltipBg: isDark ? '#F0EEEE' : '#1b1c1c',
            tooltipText: isDark ? '#111111' : '#ffffff'
        };
    };

    const renderAdminRevenueChart = () => {
        const canvas = document.getElementById('admin-revenue-chart');
        if (!canvas || !window.Chart || adminRevenueChart || !adminRevenueData.length) return;

        const c = adminThemeColors();
        const ctx = canvas.getContext('2d');
        const tinggi = (canvas.parentElement && canvas.parentElement.clientHeight) || 260;
        const gradient = ctx.createLinearGradient(0, 0, 0, tinggi);
        gradient.addColorStop(0, 'rgba(139, 30, 63, 0.35)');
        gradient.addColorStop(1, 'rgba(139, 30, 63, 0.02)');

        adminRevenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: adminRevenueData.map((row) => row.label),
                datasets: [{
                    label: '{{ __('Pendapatan') }}',
                    data: adminRevenueData.map((row) => Number(row.value) || 0),
                    borderColor: '#8B1E3F',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointBackgroundColor: '#8B1E3F',
                    pointBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                animation: { duration: 800, easing: 'easeOutQuart' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: c.tooltipBg,
                        titleColor: c.tooltipText,
                        bodyColor: c.tooltipText,
                        titleFont: { family: 'Manrope', size: 12, weight: '700' },
                        bodyFont: { family: 'Manrope', size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: (item) => 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(item.raw))
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: c.grid },
                        border: { display: false },
                        ticks: {
                            color: c.tick,
                            maxTicksLimit: 5,
                            font: { family: 'Manrope', size: 11 },
                            callback: (value) => window.ralivaShortRp ? window.ralivaShortRp(value) : value
                        }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: c.tick,
                            font: { family: 'Manrope', size: 11 },
                            autoSkip: true,
                            maxRotation: 0,
                            maxTicksLimit: 8
                        }
                    }
                }
            }
        });
    };

    adminChartReady(() => {
        try { renderAdminRevenueChart(); } catch (e) {}
    });
</script>
@endpush
@endsection
