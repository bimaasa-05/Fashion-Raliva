@extends('layouts.superadmin')

@php
/** @var \Illuminate\Support\Collection<int, array{deskripsi:string,waktu:string}> $aktivitas */
@endphp

@section('title', 'Dashboard Admin Utama')

@section('header-title', 'Selamat datang, Super Admin')
@section('header-badge', 'Kelola & Lihat')

@section('header-subtitle', 'Ringkasan kondisi seluruh platform Raliva.')

@section('content')
@php
    $jamSekarang = now()->hour;
    $sapaan = $jamSekarang < 11 ? 'Selamat pagi' : ($jamSekarang < 15 ? 'Selamat siang' : ($jamSekarang < 18 ? 'Selamat sore' : 'Selamat malam'));
    $tanggalPanjang = now()->locale('id')->translatedFormat('l, d F Y');
    $shortRp = fn ($v) => $v >= 1e9 ? 'Rp '.number_format($v / 1e9, 1, ',', '.').' M'
        : ($v >= 1e6 ? 'Rp '.number_format($v / 1e6, 1, ',', '.').' jt'
        : ($v >= 1e3 ? 'Rp '.number_format($v / 1e3, 0, ',', '.').' rb' : 'Rp '.number_format($v, 0, ',', '.')));
@endphp

<style>
    /* Banner hero dashboard — gaya identik dengan banner Data Bank */
    .sa-hero .banner-desc { font-size: 13px; color: rgba(255, 255, 255, 0.72); }
    .sa-hero .banner-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 9999px; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #fff; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.18); }
    .sa-hero .banner-badge .dot { width: 7px; height: 7px; border-radius: 9999px; background: #4ade80; animation: saHeroBeat 1.6s ease-in-out infinite; }
    .sa-hero .sa-mini { background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.16); border-radius: 14px; }
    .sa-hero .sa-mini-delta { font-size: 11px; font-weight: 700; letter-spacing: 0.02em; color: rgba(255, 255, 255, 0.78); }
    @keyframes saHeroBeat { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.5); opacity: 0.55; } }
</style>

<section data-reveal class="sa-hero banner-gradient relative overflow-hidden rounded-2xl border border-muted-border p-5 md:p-7 card-premium">
    <span class="banner-glow banner-glow-1"></span>
    <span class="banner-glow banner-glow-2"></span>
    <div class="relative flex flex-col xl:flex-row xl:items-center gap-5">
        <div class="flex items-start gap-4 min-w-0 flex-1">
            <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[26px] text-white">space_dashboard</span>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="font-headline-md text-headline-md text-white tracking-wide">{{ $sapaan }}, Super Admin</h2>
                    <span class="banner-badge"><span class="dot"></span>Live</span>
                </div>
                <p class="banner-desc mt-1.5">{{ $tanggalPanjang }}</p>
                <p class="banner-desc opacity-70 mt-0.5">Ringkasan langsung seluruh platform Raliva.</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 sm:gap-3 shrink-0">
            @foreach ([
                ['label' => 'Pesanan', 'trend' => 'pesanan', 'icon' => 'shopping_bag', 'rp' => false],
                ['label' => 'Omzet', 'trend' => 'nilai', 'icon' => 'payments', 'rp' => true],
                ['label' => 'Pengguna Baru', 'trend' => 'pelanggan', 'icon' => 'person_add', 'rp' => false],
            ] as $mini)
                @php
                    $mt = $tr[$mini['trend']];
                    $diff = $mt['today'] - $mt['yesterday'];
                    $angka = $mini['rp'] ? $shortRp($mt['today']) : number_format($mt['today'], 0, ',', '.');
                @endphp
                <div class="sa-mini px-3 py-2.5 min-w-[92px]">
                    <div class="flex items-center gap-1.5 text-white/70">
                        <span class="material-symbols-outlined text-[14px]">{{ $mini['icon'] }}</span>
                        <span class="font-label-sm text-[9px] uppercase tracking-widest truncate">{{ $mini['label'] }}</span>
                    </div>
                    <p class="font-title-md text-title-md text-white mt-1 truncate">{{ $angka }}</p>
                    <p class="sa-mini-delta mt-0.5">
                        @if ($diff > 0)↑ {{ number_format($diff, 0, ',', '.') }}
                        @elseif ($diff < 0)↓ {{ number_format(abs($diff), 0, ',', '.') }}
                        @else — @endif
                        <span class="font-normal opacity-70">vs kemarin</span>
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section>
    <div data-reveal class="flex items-end justify-between mb-6 gap-3 flex-wrap">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Ringkasan Platform</h2>
        <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant">7 hari terakhir vs 7 hari sebelumnya</span>
    </div>
    @php
        $kpiCards = [
            ['label' => 'Total Pelanggan', 'value' => $kpi['pelanggan'], 'trend' => 'pelanggan', 'note' => number_format($kpi['akun_internal'], 0, ',', '.').' akun internal', 'icon' => 'group'],
            ['label' => 'Total Toko', 'value' => $kpi['toko'], 'trend' => 'toko', 'note' => number_format($perhatian['toko'] ?? 0, 0, ',', '.').' menunggu verifikasi', 'icon' => 'storefront'],
            ['label' => 'Total Pesanan', 'value' => $kpi['pesanan'], 'trend' => 'pesanan', 'note' => number_format($kpi['pesanan_proses'], 0, ',', '.').' proses · '.number_format($kpi['pesanan_batal'], 0, ',', '.').' batal/refund', 'icon' => 'shopping_bag'],
            ['label' => 'Total Produk', 'value' => $kpi['produk'], 'trend' => 'produk', 'note' => 'real-time', 'icon' => 'checkroom'],
            ['label' => 'Nilai Transaksi', 'value' => $kpi['nilai_transaksi'], 'trend' => 'nilai', 'note' => 'akumulasi', 'icon' => 'payments', 'rp' => true, 'featured' => true],
            ['label' => 'Komisi Raliva', 'value' => $kpi['komisi'], 'trend' => 'komisi', 'note' => 'akumulasi', 'icon' => 'percent', 'rp' => true, 'featured' => true, 'gold' => true],
            ['label' => 'Pendapatan Iklan', 'value' => $kpi['pendapatan_iklan'], 'trend' => 'iklan', 'note' => number_format($kpi['iklan_aktif'], 0, ',', '.').' slot aktif/terjadwal', 'icon' => 'campaign', 'rp' => true, 'featured' => true, 'gold' => true, 'link' => 'superadmin.peringkat-iklan'],
            ['label' => 'Pajak Terkumpul', 'value' => $kpi['pajak'], 'trend' => 'pajak', 'note' => 'akumulasi', 'icon' => 'account_balance', 'rp' => true],
        ];
    @endphp
    <div data-reveal-group class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
        @foreach ($kpiCards as $ci => $card)
            @php
                $tt = $tr[$card['trend']];
                $naik = $tt['pct'] !== null && $tt['pct'] > 0;
                $turun = $tt['pct'] !== null && $tt['pct'] < 0;
            @endphp
            <div class="bg-surface-container-lowest p-4 border {{ ($card['featured'] ?? false) ? 'border-gold-accent/25 hover:border-gold-accent transition-colors hero-glow' : 'border-muted-border' }} rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <div class="flex items-start justify-between gap-2 relative z-10">
                    <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ $card['label'] }}</span>
                    @if ($tt['pct'] !== null)
                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md text-[11px] font-bold whitespace-nowrap {{ $naik ? 'bg-success/10 text-success' : ($turun ? 'bg-error/10 text-error' : 'bg-surface-container-high text-on-surface-variant') }}"
                              title="7 hari terakhir {{ number_format($tt['now'], 0, ',', '.') }} vs sebelumnya {{ number_format($tt['prev'], 0, ',', '.') }}">
                            <span class="material-symbols-outlined text-[13px]">{{ $naik ? 'arrow_upward' : ($turun ? 'arrow_downward' : 'remove') }}</span>
                            {{ number_format(abs($tt['pct']), 1, ',', '.') }}%
                        </span>
                    @else
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[11px] font-bold whitespace-nowrap bg-surface-container-high text-on-surface-variant" title="Belum ada data pembanding 7 hari sebelumnya">Baru</span>
                    @endif
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile {{ ($card['gold'] ?? false) ? 'text-gradient-gold' : 'text-on-surface' }} relative z-10">@if ($card['rp'] ?? false)Rp @endif<span data-count="{{ $card['value'] }}">{{ number_format($card['value'], 0, ',', '.') }}</span></span>
                <span class="text-xs text-on-surface-variant relative z-10">{{ $card['note'] }}</span>
                <div class="mt-auto pt-1 relative z-10">
                    @include('SuperAdmin.partials.sparkline', ['values' => $tt['spark'], 'id' => 'kpi'.$ci])
                </div>
                @if (!empty($card['link']))
                    <a href="{{ route($card['link']) }}" class="absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true"><span class="material-symbols-outlined">{{ $card['icon'] }}</span></a>
                @else
                    <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">{{ $card['icon'] }}</span>
                @endif
            </div>
        @endforeach
    </div>
</section>

<section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
        <div>
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Kinerja Operasional</h2>
            <p class="text-on-surface-variant font-body-md text-xs mt-1">Target omzet, kepuasan pelanggan, dan kecepatan respons komplain.</p>
        </div>
        <span class="material-symbols-outlined text-gold-accent text-[20px]">monitor_heart</span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="flex flex-col items-center text-center p-3 rounded-xl bg-surface-container-low border border-muted-border">
            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Omzet Bulan Ini vs Bulan Lalu</p>
            <div data-donut='[{"value":{{ $targetOmzet['persen'] }},"color":"#8B1E3F","label":"Tercapai"},{"value":{{ 100 - $targetOmzet['persen'] }},"color":"rgba(127,127,127,0.14)","label":""}]' data-donut-label="dari Bulan Lalu" data-donut-size="130" data-donut-stroke="13" data-donut-max="150" data-donut-suffix="%" data-donut-nolegend="1" class="w-full"></div>
            <p class="text-[11px] text-on-surface-variant mt-1">Rp {{ number_format($targetOmzet['bulanIni'], 0, ',', '.') }} dari Rp {{ number_format($targetOmzet['bulanLalu'], 0, ',', '.') }} bulan lalu</p>
        </div>
        <div class="flex flex-col items-center text-center p-3 rounded-xl bg-surface-container-low border border-muted-border">
            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Kepuasan Pelanggan</p>
            <div data-donut='[{"value":{{ $kepuasan['persen'] }},"color":"#8B1E3F","label":"Puas"},{"value":{{ 100 - $kepuasan['persen'] }},"color":"rgba(127,127,127,0.14)","label":""}]' data-donut-label="Rating {{ $kepuasan['rata'] > 0 ? number_format($kepuasan['rata'], 1, ',', '.') : '-' }} / 5" data-donut-size="130" data-donut-stroke="13" data-donut-max="150" data-donut-suffix="%" data-donut-nolegend="1" class="w-full"></div>
            <p class="text-[11px] text-on-surface-variant mt-1">Dari {{ number_format($kepuasan['total'], 0, ',', '.') }} ulasan</p>
        </div>
        <div class="flex flex-col items-center text-center p-3 rounded-xl bg-surface-container-low border border-muted-border">
            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">SLA Respons Komplain</p>
            <div data-donut='[{"value":{{ $sla['total'] > 0 ? $sla['persen'] : 0 }},"color":"#c03a5a","label":"Tepat SLA"},{"value":{{ $sla['total'] > 0 ? 100 - $sla['persen'] : 100 }},"color":"rgba(127,127,127,0.14)","label":""}]' data-donut-label="{{ $sla['total'] > 0 ? 'Target 24 Jam' : 'Belum Ada Data' }}" data-donut-size="130" data-donut-stroke="13" data-donut-max="150" data-donut-suffix="%" data-donut-nolegend="1" class="w-full"></div>
            <p class="text-[11px] text-on-surface-variant mt-1">{{ $sla['total'] > 0 ? 'Rata-rata balasan dalam '.$sla['rataJam'].' jam • '.number_format($sla['total'], 0, ',', '.').' komplain' : 'Belum ada komplain yang dibalas — tidak dihitung.' }}</p>
        </div>
    </div>
    <p class="text-on-surface-variant font-body-md text-[11px] mt-5 pt-4 border-t border-muted-border flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[14px] text-gold-accent">insights</span>
        Omzet dihitung dari seluruh pesanan berstatus pendapatan pada bulan berjalan.
    </p>
</section>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Volume Pesanan &amp; Transaksi</h2>
            <div class="inline-flex self-start bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
                <button type="button" data-order-range="7" class="order-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary">7 Hari</button>
                <button type="button" data-order-range="30" class="order-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface">30 Hari</button>
                <button type="button" data-order-range="90" class="order-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface">3 Bulan</button>
            </div>
        </div>
        <div id="order-bars-holder" class="h-48 min-h-0"></div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4 pt-4 border-t border-muted-border">
            <div class="flex flex-col">
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Total Pesanan</span>
                <span id="ob-total-pesanan" class="font-title-md text-title-md text-on-surface">–</span>
            </div>
            <div class="flex flex-col">
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Total Transaksi</span>
                <span id="ob-total-transaksi" class="font-title-md text-title-md text-on-surface">–</span>
            </div>
            <div class="flex flex-col">
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Rata-rata per Periode</span>
                <span id="ob-rata" class="font-title-md text-title-md text-on-surface">–</span>
            </div>
            <div class="flex flex-col">
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Periode Tertinggi</span>
                <span id="ob-puncak" class="font-title-md text-title-md text-on-surface">–</span>
            </div>
        </div>
        <p class="text-on-surface-variant font-body-md text-[11px] mt-3 flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[14px] text-gold-accent">insights</span>
            <span id="order-bars-summary">Memuat data…</span>
        </p>
    </section>

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Toko</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">emoji_events</span>
        </div>
        <div data-leaderboard='@json($topToko)'></div>
        <div class="flex items-center justify-center gap-6 mt-4 pt-4 border-t border-muted-border">
            <a href="{{ route('superadmin.peringkat') }}#toko" class="font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Peringkat Lengkap</a>
            <span class="w-px h-4 bg-muted-border"></span>
            <a href="{{ route('superadmin.manajemen-toko') }}" class="font-label-sm text-[11px] text-on-surface-variant uppercase tracking-widest hover:underline">Kelola Semua Toko</a>
        </div>
    </section>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading whitespace-nowrap">Kinerja Platform</h2>
            <div class="inline-flex self-start sm:self-auto bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
                <button type="button" data-chart-range="7" class="chart-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary">7 Hari</button>
                <button type="button" data-chart-range="30" class="chart-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface">30 Hari</button>
                <button type="button" data-chart-range="90" class="chart-range-btn px-3 py-1.5 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface">3 Bulan</button>
            </div>
        </div>
        <div id="chart-wrap" class="relative h-72 md:h-80">
            <canvas id="sales-chart"></canvas>
        </div>
        <div class="flex flex-wrap gap-2 mt-4">
            <span class="inline-flex items-baseline gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low border border-muted-border">
                <span class="material-symbols-outlined text-[15px] text-gold-accent relative top-0.5">payments</span>
                <span class="text-on-surface-variant text-xs">Transaksi</span>
                <strong id="cp-total-transaksi" class="font-title-md text-title-sm text-on-surface">–</strong>
            </span>
            <span class="inline-flex items-baseline gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low border border-muted-border">
                <span class="material-symbols-outlined text-[15px] text-gold-accent relative top-0.5">shopping_bag</span>
                <span class="text-on-surface-variant text-xs">Pesanan</span>
                <strong id="cp-total-pesanan" class="font-title-md text-title-sm text-on-surface">–</strong>
            </span>
            <span class="inline-flex items-baseline gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container-low border border-muted-border">
                <span class="material-symbols-outlined text-[15px] text-gold-accent relative top-0.5">leaderboard</span>
                <span class="text-on-surface-variant text-xs">Puncak</span>
                <strong id="cp-puncak" class="font-title-md text-title-sm text-on-surface">–</strong>
            </span>
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

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-2">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Komposisi Toko</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">donut_small</span>
        </div>
        <p class="text-on-surface-variant font-body-md text-xs mb-4">Sebaran status seluruh toko terdaftar.</p>
        <div data-donut='@json($komposisiTokoDonut)' data-donut-label="Toko Terdaftar"></div>
        <p class="text-on-surface-variant font-body-md text-[11px] mt-5 pt-4 border-t border-muted-border flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[14px] text-gold-accent">sync</span>
            Sinkron dengan Total Toko di ringkasan atas.
        </p>
    </section>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @php
        $perhatianDef = [
            ['key' => 'toko', 'label' => 'Verifikasi Toko', 'route' => 'superadmin.manajemen-toko', 'params' => ['status' => 'pending'], 'icon' => 'store_mall_directory', 'bg' => 'bg-secondary-container', 'tx' => 'text-white', 'text' => 'permintaan menunggu'],
            ['key' => 'produk', 'label' => 'Moderasi Produk', 'route' => 'superadmin.moderasi-produk', 'params' => [], 'icon' => 'inventory_2', 'bg' => 'bg-surface-container-high', 'tx' => 'text-on-surface', 'text' => 'item ditandai'],
            ['key' => 'perubahan_produk', 'label' => 'Perubahan Produk', 'route' => 'superadmin.perubahan-produk', 'params' => [], 'icon' => 'edit_note', 'bg' => 'bg-gold-accent/10', 'tx' => 'text-gold-accent', 'text' => 'menunggu tinjauan'],
            ['key' => 'slot', 'label' => 'Permintaan Slot', 'route' => 'superadmin.slot-produk', 'params' => [], 'icon' => 'grid_view', 'bg' => 'bg-secondary-container', 'tx' => 'text-white', 'text' => 'menunggu verifikasi'],
            ['key' => 'komplain', 'label' => 'Komplain Terbuka', 'route' => 'superadmin.komplain', 'params' => [], 'icon' => 'support_agent', 'bg' => 'bg-surface-container-high', 'tx' => 'text-on-surface', 'text' => 'perlu ditindak'],
            ['key' => 'iklan', 'label' => 'Iklan Ditunda', 'route' => 'superadmin.peringkat-iklan', 'params' => [], 'icon' => 'campaign', 'bg' => 'bg-gold-accent/10', 'tx' => 'text-gold-accent', 'text' => 'pengajuan ditunda'],
            ['key' => 'refund', 'label' => 'Permintaan Refund', 'route' => 'superadmin.pengembalian-dana', 'params' => [], 'icon' => 'currency_exchange', 'bg' => 'bg-error-container', 'tx' => 'text-on-error-container', 'text' => 'menunggu tinjauan'],
            ['key' => 'penarikan', 'label' => 'Penarikan Toko', 'route' => 'superadmin.permintaan-penarikan', 'params' => [], 'icon' => 'account_balance_wallet', 'bg' => 'bg-secondary-container', 'tx' => 'text-white', 'text' => 'menunggu diproses'],
            ['key' => 'topup', 'label' => 'Top-Up Pelanggan', 'route' => 'superadmin.verifikasi-topup', 'params' => [], 'icon' => 'add_card', 'bg' => 'bg-surface-container-high', 'tx' => 'text-on-surface', 'text' => 'menunggu verifikasi'],
            ['key' => 'penarikan_saldo', 'label' => 'Penarikan Saldo Pelanggan', 'route' => 'superadmin.verifikasi-penarikan-saldo', 'params' => [], 'icon' => 'payments', 'bg' => 'bg-error-container', 'tx' => 'text-on-error-container', 'text' => 'menunggu verifikasi'],
        ];
        $perhatianTerlihat = collect($perhatianDef)
            ->filter(fn ($def) => ($perhatian[$def['key']] ?? 0) > 0)
            ->sortByDesc(fn ($def) => $perhatian[$def['key']] ?? 0)
            ->values();
        $totalAntrean = (int) $perhatianTerlihat->sum(fn ($def) => $perhatian[$def['key']] ?? 0);
    @endphp
    <section data-reveal class="lg:col-span-1 bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium">
        <div class="flex items-center justify-between mb-6 gap-3 flex-wrap">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Perlu Perhatian</h2>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-error-container text-on-error-container font-label-sm text-[11px] uppercase tracking-widest">
                <span class="material-symbols-outlined text-[14px]">error</span>
                {{ number_format($totalAntrean, 0, ',', '.') }} antrean
            </span>
        </div>
        @if($perhatianTerlihat->isNotEmpty())
            <ul class="flex flex-col gap-2">
                @foreach($perhatianTerlihat as $def)
                    <li class="{{ $loop->first ? 'bg-gold-accent/5 border border-gold-accent/25 rounded-xl' : '' }}">
                        <a href="{{ route($def['route'], $def['params']) }}" class="flex items-center justify-between group cursor-pointer p-3 {{ $loop->first ? '' : '-m-1' }} rounded-lg hover:bg-surface-container-low transition-colors">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-11 h-11 rounded-full {{ $def['bg'] }} flex items-center justify-center {{ $def['tx'] }} shrink-0 shadow-sm">
                                    <span class="material-symbols-outlined">{{ $def['icon'] }}</span>
                                </div>
                                <div class="min-w-0">
                                    <span class="font-title-md text-title-md text-on-surface block">{{ $def['label'] }}</span>
                                    <span class="text-on-surface-variant font-body-md text-sm">{{ $def['text'] }}</span>
                                    @if($loop->first)
                                        <span class="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 rounded-md bg-gold-accent/15 text-gold-accent font-label-sm text-[10px] uppercase tracking-widest">
                                            <span class="material-symbols-outlined text-[12px]">priority_high</span>
                                            Prioritas
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="flex items-center gap-2 shrink-0">
                                <span class="inline-flex items-center justify-center min-w-[2.25rem] px-2 py-1 rounded-lg bg-surface-container-high text-on-surface font-title-md text-title-sm">{{ number_format($perhatian[$def['key']], 0, ',', '.') }}</span>
                                <span class="material-symbols-outlined text-outline-variant group-hover:text-gold-accent group-hover:translate-x-0.5 transition-all">chevron_right</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="flex flex-col items-center justify-center text-center gap-3 py-10">
                <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center">
                    <span class="material-symbols-outlined text-gold-accent">verified</span>
                </div>
                <p class="font-title-md text-title-md text-on-surface">Semua aman</p>
                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada permintaan yang menunggu tindakan Anda.</p>
            </div>
        @endif
    </section>

    <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Aktivitas Terbaru</h2>
        <ul class="flex flex-col">
            @forelse($aktivitas as $act)
                @php
                    $prefix = explode('.', $act['deskripsi'])[0] ?? 'system';
                    $iconMap = ['user' => 'person_add', 'store' => 'storefront', 'product' => 'inventory_2', 'order' => 'shopping_cart', 'setting' => 'settings'];
                    $toneMap = [
                        'user' => 'bg-secondary-container text-white',
                        'store' => 'bg-gold-accent/10 border border-gold-accent/25 text-gold-accent',
                        'order' => 'bg-success/10 text-success',
                        'product' => 'bg-surface-container-high text-on-surface',
                        'setting' => 'bg-surface-container-low border border-muted-border text-on-surface-variant',
                    ];
                    $icon = $iconMap[$prefix] ?? 'info';
                    $tone = $toneMap[$prefix] ?? 'bg-surface-container-low border border-muted-border text-on-surface-variant';
                @endphp
                <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-9 h-9 rounded-full {{ $tone }} flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-sm">{{ $icon }}</span>
                        </div>
                        <div>
                            <p class="font-body-md text-on-surface">{!! $act['deskripsi'] ?? '-' !!}</p>
                            <p class="text-on-surface-variant text-sm mt-0.5">{{ $act['waktu'] }}</p>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-4 text-center text-on-surface-variant">Belum ada aktivitas terbaru.</li>
            @endforelse
        </ul>
    </section>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Kategori</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">category</span>
        </div>
        <div data-leaderboard='@json($topKategori)'></div>
        <a href="{{ route('superadmin.peringkat') }}#kategori" class="block text-center mt-4 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Peringkat Lengkap</a>
    </section>

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Pelanggan</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">military_tech</span>
        </div>
        <div data-leaderboard='@json($topPelanggan)'></div>
        <a href="{{ route('superadmin.peringkat') }}#pelanggan" class="block text-center mt-4 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Peringkat Lengkap</a>
    </section>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Produk</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">local_mall</span>
        </div>
        <div data-leaderboard='@json($topProduk)'></div>
        <a href="{{ route('superadmin.produk') }}" class="block text-center mt-4 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Kelola Semua Produk</a>
    </section>

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Produk Iklan</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">campaign</span>
        </div>
        <div data-leaderboard='@json($topProdukIklan)'></div>
        <a href="{{ route('superadmin.peringkat-iklan') }}" class="block text-center mt-4 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Iklan Lengkap</a>
    </section>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let platformChart = null;
    let currentRange = '7';
    const chartWrap = document.getElementById('chart-wrap');
    const chartError = document.getElementById('chart-error');

    const rangeData = @json($rangeData);

    const formatRupiahShort = (value) => {
        if (value >= 1000000000) return (value / 1000000000).toFixed(1).replace('.', ',') + ' M';
        if (value >= 1000000) return (value / 1000000).toFixed(1).replace('.', ',') + ' jt';
        if (value >= 1000) return Math.round(value / 1000) + ' rb';
        return value;
    };

    const chartTheme = () => {
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

    const renderPlatformChart = () => {
        if (!window.Chart) {
            chartWrap?.classList.add('hidden');
            chartError?.classList.remove('hidden');
            return;
        }
        const c = chartTheme();
        const data = rangeData[currentRange];

        if (!platformChart) {
            platformChart = new Chart(document.getElementById('sales-chart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        { label: 'Volume Transaksi', data: data.transaksi, borderColor: '#8B1E3F', backgroundColor: 'rgba(139, 30, 63, 0.12)', fill: true, tension: 0.38, borderWidth: 2, pointBackgroundColor: '#8B1E3F', pointRadius: 3 },
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
                            callbacks: { label: (ctx) => ctx.datasetIndex === 0 ? ' Volume Transaksi: Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) : ' Pesanan: ' + new Intl.NumberFormat('id-ID').format(ctx.raw) }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, position: 'left', grid: { color: c.grid }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 }, callback: (v) => formatRupiahShort(v) } },
                        y1: { beginAtZero: true, position: 'right', grid: { display: false }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 }, callback: (v) => new Intl.NumberFormat('id-ID').format(v) } },
                        x: { grid: { display: false }, ticks: { color: c.tick, font: { family: 'Manrope', size: 11 } } }
                    }
                }
            });
            /* Setelah render pertama: pakai transisi standar agar ganti rentang tetap glide */
            platformChart.options.animation = { duration: 700, easing: 'easeOutCubic' };
            return;
        }

        /* Transisi mulus saat ganti rentang: elemen meluncur dari nilai lama ke baru */
        platformChart.data.labels = data.labels;
        platformChart.data.datasets[0].data = data.transaksi;
        platformChart.data.datasets[1].data = data.pesanan;
        platformChart.data.datasets[1].borderColor = c.tick;
        platformChart.data.datasets[1].pointBackgroundColor = c.tick;
        platformChart.options.scales.y.grid.color = c.grid;
        platformChart.options.scales.y.ticks.color = c.tick;
        platformChart.options.scales.y1.ticks.color = c.tick;
        platformChart.options.scales.x.ticks.color = c.tick;
        platformChart.options.plugins.legend.labels.color = c.tick;
        platformChart.update();
    };

    const cpTotalTransaksi = document.getElementById('cp-total-transaksi');
    const cpTotalPesanan = document.getElementById('cp-total-pesanan');
    const cpPuncak = document.getElementById('cp-puncak');

    const renderPlatformStats = () => {
        const data = rangeData[currentRange];
        const labels = (data && data.labels) || [];
        const pesanan = (data && data.pesanan) || [];
        const transaksi = (data && data.transaksi) || [];
        if (!labels.length || !pesanan.length) {
            [cpTotalTransaksi, cpTotalPesanan, cpPuncak].forEach((el) => { if (el) el.textContent = '\u2013'; });
            return;
        }
        const totalP = pesanan.reduce((a, v) => a + (Number(v) || 0), 0);
        const totalT = transaksi.reduce((a, v) => a + (Number(v) || 0), 0);
        let maxIdx = 0;
        pesanan.forEach((v, i) => { if ((Number(v) || 0) > (Number(pesanan[maxIdx]) || 0)) maxIdx = i; });
        if (cpTotalTransaksi) cpTotalTransaksi.textContent = obFmtRp.format(totalT);
        if (cpTotalPesanan) cpTotalPesanan.textContent = obFmtNum.format(totalP);
        if (cpPuncak) cpPuncak.textContent = labels[maxIdx] || '–';
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
            renderPlatformChart();
            renderPlatformStats();
        });
    });

    document.getElementById('chart-retry')?.addEventListener('click', () => {
        chartError?.classList.add('hidden');
        chartWrap?.classList.remove('hidden');
        renderPlatformChart();
    });

    window.ralivaOnReady(() => {
        try {
            renderPlatformChart();
        } catch (e) {
            chartWrap?.classList.add('hidden');
            chartError?.classList.remove('hidden');
        }
        renderPlatformStats();
    });

    /* ===== Kartu "Volume Pesanan & Transaksi" — filter 7/30/90 hari ===== */
    const orderBarsHolder = document.getElementById('order-bars-holder');
    const orderBarsSummary = document.getElementById('order-bars-summary');
    const obTotalPesanan = document.getElementById('ob-total-pesanan');
    const obTotalTransaksi = document.getElementById('ob-total-transaksi');
    const obRata = document.getElementById('ob-rata');
    const obPuncak = document.getElementById('ob-puncak');
    const obFmtNum = new Intl.NumberFormat('id-ID');
    const obFmtRp = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 });
    let currentOrderRange = '7';

    const resetOrderStats = () => {
        [obTotalPesanan, obTotalTransaksi, obRata, obPuncak].forEach((el) => { if (el) el.textContent = '\u2013'; });
    };

    const setActiveOrderRangeButton = () => {
        document.querySelectorAll('[data-order-range]').forEach((b) => {
            const isActive = b.getAttribute('data-order-range') === currentOrderRange;
            b.classList.toggle('bg-deep-onyx', isActive);
            b.classList.toggle('text-on-primary', isActive);
            b.classList.toggle('text-on-surface-variant', !isActive);
        });
    };

    const buildOrderBars = () => {
        if (!orderBarsHolder) return;
        const data = rangeData[currentOrderRange];
        const segs = (data && data.labels)
            ? data.labels.map((label, i) => ({ label, value: Number(data.pesanan[i]) || 0 }))
            : [];

        orderBarsHolder.innerHTML = '';
        if (!segs.length) {
            orderBarsHolder.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-center gap-2 text-on-surface-variant">'
                + '<span class="material-symbols-outlined text-[28px] opacity-50">bar_chart</span>'
                + '<p class="font-body-md text-sm">Belum ada data pesanan.</p></div>';
            if (orderBarsSummary) orderBarsSummary.textContent = 'Belum ada data pesanan pada rentang ini.';
            resetOrderStats();
            return;
        }

        const el = document.createElement('div');
        el.className = 'w-full h-full';
        el.setAttribute('data-bars', JSON.stringify(segs));
        el.setAttribute('data-bars-suffix', '');
        orderBarsHolder.appendChild(el);
        if (window.ralivaBars) window.ralivaBars(el);

        let maxIdx = 0;
        segs.forEach((s, i) => { if (s.value > (segs[maxIdx].value || 0)) maxIdx = i; });

        if (orderBarsSummary) {
            if ((segs[maxIdx].value || 0) > 0) {
                orderBarsSummary.textContent = 'Periode ' + (segs[maxIdx].label || '-') + ' tertinggi dengan '
                    + obFmtNum.format(segs[maxIdx].value) + ' pesanan.';
            } else {
                orderBarsSummary.textContent = 'Belum ada data pesanan pada rentang ini.';
            }
        }

        const totalP = segs.reduce((a, s) => a + (s.value || 0), 0);
        const totalT = (data.transaksi || []).reduce((a, v) => a + (Number(v) || 0), 0);
        if (obTotalPesanan) obTotalPesanan.textContent = obFmtNum.format(totalP);
        if (obTotalTransaksi) obTotalTransaksi.textContent = obFmtRp.format(totalT);
        if (obRata) obRata.textContent = (Math.round((totalP / segs.length) * 10) / 10).toLocaleString('id-ID');
        if (obPuncak) obPuncak.textContent = segs[maxIdx].label || '-';
    };

    document.querySelectorAll('[data-order-range]').forEach((btn) => {
        btn.addEventListener('click', () => {
            currentOrderRange = btn.getAttribute('data-order-range');
            setActiveOrderRangeButton();
            buildOrderBars();
        });
    });

    window.ralivaOnReady(() => {
        setActiveOrderRangeButton();
        buildOrderBars();
    });
</script>
@endpush
