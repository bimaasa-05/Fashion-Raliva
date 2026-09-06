@extends('layouts.owner')

@php
    $fmt = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $isMasuk = fn ($jenis) => in_array($jenis, [
        \App\Models\WalletTransaction::JENIS_PENJUALAN_MASUK,
        \App\Models\WalletTransaction::JENIS_KOMISI_MASUK,
    ]);
@endphp

@section('title', 'Keuangan')
@section('header-title', 'Keuangan')
@section('header-badge', 'Keuangan')
@section('header-subtitle', 'Kelola saldo, pencairan dana, dan pengembalian dana toko Anda dalam satu tempat.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-12 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    @if (! $wallet)
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-12 text-center card-premium">
            <span class="material-symbols-outlined text-[48px] text-on-surface-variant">account_balance_wallet</span>
            <p class="mt-4 font-title-md text-title-md text-on-surface">Belum ada dompet toko</p>
            <p class="text-on-surface-variant font-body-md text-sm mt-1">Data keuangan akan muncul setelah ada transaksi pada toko ini.</p>
        </div>
    @else
    {{-- Tab Switcher --}}
    <div class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 overflow-x-auto max-w-full">
        <button type="button" data-saldo-tab="ringkasan" class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Ringkasan</button>
        <button type="button" data-saldo-tab="pengeluaran" class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Pengeluaran</button>
    </div>

    {{-- ============ PANEL: RINGKASAN ============ --}}
    <div data-saldo-panel="ringkasan" class="space-y-section-gap">
        {{-- Kartu Saldo --}}
        <section data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
            <div data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 relative overflow-hidden flex flex-col">
                <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
                <p class="raliva-label text-gold-accent relative">Saldo Tersedia</p>
                <p class="raliva-figure text-[34px] md:text-[42px] mt-4 relative">{{ $fmt($wallet->saldo_tersedia) }}</p>
                <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                    <p class="font-body-md text-xs text-inverse-on-surface/60">Siap dicairkan kapan saja</p>
                    <a href="#pencairan" class="py-2.5 px-5 bg-gold-accent text-[#111] text-xs font-semibold rounded btn-premium shrink-0">Cairkan</a>
                </div>
            </div>

            <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
                <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
                <p class="raliva-label relative">Saldo Tertunda</p>
                <p class="raliva-figure text-[26px] mt-4 text-on-surface relative">{{ $fmt($wallet->saldo_tertahan) }}</p>
                <p class="text-on-surface-variant font-body-md text-xs mt-auto pt-6 relative">Dana dilepas otomatis menjadi saldo tersedia H+2 setelah pesanan selesai.</p>
            </div>

            <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
                <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">savings</span>
                <p class="raliva-label relative">Total Dicairkan</p>
                <p class="raliva-figure text-[26px] mt-4 text-secondary relative">{{ $fmt($totalDicairkan) }}</p>
                <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                    <p class="font-body-md text-xs text-on-surface-variant">{{ $withdrawals->count() }} pencairan tercatat</p>
                    <a href="#pencairan" class="py-2.5 px-5 border border-muted-border text-xs font-semibold rounded-lg hover:border-gold-accent transition-colors shrink-0">Riwayat</a>
                </div>
            </div>
        </section>

        {{-- Estimasi Margin (5 lapis) — bahasa awam --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Perkiraan Keuntungan Toko</h2>
                    <p class="text-xs text-on-surface-variant mt-1">Estimasi laba dari total penjualan, setelah potong HPP dan pajak.</p>
                </div>
                <span class="text-[10px] uppercase tracking-wider text-on-surface-variant bg-surface-container-low px-2 py-1 rounded">Asumsi: HPP 60% · Pajak 25%</span>
            </div>
            <div data-reveal-group class="grid grid-cols-2 md:grid-cols-5 gap-gutter">
                <div data-reveal class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 relative overflow-hidden">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">Total Omzet</span>
                    <span class="raliva-figure text-[20px] text-on-surface">{{ $fmt($margin['revenue']) }}</span>
                    <span class="text-[10px] text-on-surface-variant">Semua pendapatan kotor</span>
                </div>
                <div data-reveal class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 relative overflow-hidden">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">Laba Kotor</span>
                    <span class="raliva-figure text-[20px] text-secondary">{{ $fmt($margin['gross']) }}</span>
                    <span class="text-[10px] text-on-surface-variant">Setelah potong HPP barang</span>
                </div>
                <div data-reveal class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 relative overflow-hidden">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">Laba Operasional</span>
                    <span class="raliva-figure text-[20px] text-on-surface">{{ $fmt($margin['ebitda']) }}</span>
                    <span class="text-[10px] text-on-surface-variant">Setelah biaya operasional</span>
                </div>
                <div data-reveal class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 relative overflow-hidden">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">Laba Sebelum Pajak</span>
                    <span class="raliva-figure text-[20px] text-on-surface">{{ $fmt($margin['ebt']) }}</span>
                    <span class="text-[10px] text-on-surface-variant">Setelah biaya pendanaan</span>
                </div>
                <div data-reveal class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 relative overflow-hidden">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">Laba Bersih</span>
                    <span class="raliva-figure text-[20px] text-gold-accent">{{ $fmt($margin['net']) }}</span>
                    <span class="text-[10px] text-on-surface-variant">Uang riil di kantong</span>
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
                    <li data-reveal class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                        <span class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px] text-secondary fill">add_circle</span>Pemasukan
                        </span>
                        <span class="text-secondary font-bold whitespace-nowrap">{{ $fmt($summary['pemasukan']) }}</span>
                    </li>
                    <li data-reveal class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                        <span class="flex items-center gap-3 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px] text-error fill">remove_circle</span>Pengeluaran
                        </span>
                        <span class="text-error font-bold whitespace-nowrap">− {{ $fmt($summary['pengeluaran']) }}</span>
                    </li>
                </ul>
                <div class="mt-6 pt-5 border-t border-muted-border flex items-center justify-between">
                    <span class="font-title-md text-sm text-on-surface">Perubahan Bersih</span>
                    <span class="font-title-md text-base {{ $summary['bersih'] >= 0 ? 'text-secondary' : 'text-error' }}">
                        {{ $summary['bersih'] >= 0 ? '+' : '−' }} {{ $fmt(abs($summary['bersih'])) }}
                    </span>
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
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Perubahan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mutations as $row)
                            @php $masuk = $isMasuk($row->jenis_transaksi); @endphp
                            <tr class="border-b border-muted-border last:border-0">
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-3.5 px-4 text-on-surface">{{ $row->keterangan }}</td>
                                <td class="py-3.5 px-4 text-right font-bold whitespace-nowrap {{ $masuk ? 'text-secondary' : 'text-error' }}">
                                    {{ $masuk ? '+' : '−' }} {{ $fmt(abs($row->jumlah)) }}
                                </td>
                                <td class="py-3.5 px-4 text-right text-on-surface whitespace-nowrap">{{ $fmt($row->saldo_sesudah) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada mutasi saldo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between pt-6 mt-2 border-t border-muted-border">
                <p class="text-xs text-on-surface-variant">Menampilkan {{ $mutations->count() }} dari {{ $mutations->total() }} mutasi</p>
                {{ $mutations->links() }}
            </div>
        </section>
    </div>

        {{-- ============ PANEL: PENGELUARAN ============ --}}
    <div data-saldo-panel="pengeluaran" class="hidden space-y-section-gap">
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Catat Pengeluaran Toko</h2>
            <form method="POST" action="{{ route('owner.keuangan.pengeluaran.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block raliva-label mb-2">Nama Pengeluaran</label>
                    <input name="nama" type="text" required placeholder="cth. Listrik Toko" class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Kategori</label>
                    <input name="kategori" type="text" list="kategori-list" required placeholder="cth. Operasional" class="raliva-input" />
                    <datalist id="kategori-list">
                        @foreach ($expenses->pluck('kategori')->unique() as $k)
                            <option value="{{ $k }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Nominal (Rp)</label>
                    <input name="nominal" type="number" min="1" required placeholder="500000" class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Tanggal</label>
                    <input name="tanggal" type="date" required value="{{ date('Y-m-d') }}" class="raliva-input" />
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">add</span>Catat Pengeluaran
                    </button>
                </div>
            </form>
        </section>

        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Daftar Pengeluaran</h2>
            <div data-table-wrap class="overflow-x-auto">
                <table class="premium-table w-full min-w-[720px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Nama</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kategori</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expenses as $ex)
                            <tr data-table-row class="border-b border-muted-border last:border-0">
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $ex->tanggal->format('d M Y') }}</td>
                                <td class="py-3.5 px-4 text-on-surface">{{ $ex->nama }}</td>
                                <td class="py-3.5 px-4 text-on-surface-variant">{{ $ex->kategori }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-error whitespace-nowrap">- {{ $fmt($ex->nominal) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada pengeluaran tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  if (!document.querySelector('[data-real]')) return;
  // Check if no store banner exists (means no store)
  const noStore = document.body.innerHTML.includes('Belum punya toko');
  if (!noStore) return;
  // Disable all primary action buttons except Ajukan Toko
  document.querySelectorAll('[data-modal-open], button[type="submit"], a[href*="pengajuan-toko"]:not([href*="ajukan"])').forEach(el=>{
    // Keep Ajukan Toko enabled
    if (el.textContent.includes('Ajukan Toko') || el.getAttribute('data-modal-open')?.includes('modal-tambah')) {
      // For tambah buttons, disable if no store
      el.setAttribute('disabled','');
      el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
      el.title = 'Ajukan toko dulu';
    }
  });
  // More generic: disable all buttons in data-real except those inside pengajuan
  document.querySelectorAll('[data-real] button, [data-real] a.btn-premium').forEach(el=>{
    if (el.closest('[data-modal]')) return;
    if (el.textContent.trim().includes('Ajukan')) return;
    el.setAttribute('disabled','');
    el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
  });
});
</script>
@endpush

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* ===== Tab Saldo: Ringkasan | Pencairan | Pengembalian ===== */
    const setSaldoTab = (name) => {
        document.querySelectorAll('[data-saldo-tab]').forEach((b) => {
            const isActive = b.getAttribute('data-saldo-tab') === name;
            b.classList.toggle('bg-deep-onyx', isActive);
            b.classList.toggle('text-on-primary', isActive);
            b.classList.toggle('text-on-surface-variant', !isActive);
        });
        document.querySelectorAll('[data-saldo-panel]').forEach((p) => {
            p.classList.toggle('hidden', p.getAttribute('data-saldo-panel') !== name);
        });
    };

    document.querySelectorAll('[data-saldo-tab]').forEach((b) => {
        b.addEventListener('click', () => {
            history.replaceState(null, '', '#' + b.getAttribute('data-saldo-tab'));
            setSaldoTab(b.getAttribute('data-saldo-tab'));
        });
    });

    const initSaldoFromHash = () => {
        const h = location.hash.replace('#', '');
        if (['pengeluaran'].includes(h)) setSaldoTab(h);
    };
    window.addEventListener('hashchange', initSaldoFromHash);

    window.ralivaOnReady(() => {
        initSaldoFromHash();
        try {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#333333' : '#E9E8E7';
            const tickColor = isDark ? '#BAB8B8' : '#747878';
            const tooltipBg = isDark ? '#F0EEEE' : '#1b1c1c';
            const tooltipText = isDark ? '#111111' : '#ffffff';

            const chartLabels = @json($chart->pluck('label'));
            const chartData = @json($chart->pluck('saldo')->map(fn ($v) => (float) $v));

            const drawAnim = {
                x: { type: 'number', duration: 950, easing: 'easeOutQuart', from: (ctx) => (ctx.chart && ctx.chart.chartArea ? ctx.chart.chartArea.left : 0) },
                y: { type: 'number', duration: 950, easing: 'easeOutQuart' }
            };

            new Chart(document.getElementById('saldo-chart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [
                        { label: 'Saldo Akhir Bulan', data: chartData, borderColor: '#C9A24D', backgroundColor: 'rgba(201, 162, 77, 0.12)', fill: true, tension: 0.35, borderWidth: 2, pointBackgroundColor: '#C9A24D', pointRadius: 3 }
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

    document.querySelectorAll('[data-quick]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = document.getElementById('wd-nominal');
            if (input) input.value = btn.getAttribute('data-quick');
        });
    });
</script>
@endpush
