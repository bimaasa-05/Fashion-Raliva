@extends('layouts.admin')

@section('title', 'Dashboard Operasional')

@section('header-title', 'Dashboard Operasional')
@section('header-badge', 'Lihat')

@section('header-subtitle', 'Prioritaskan pekerjaan harian tokomu hari ini.')

@php
    /* ===== DUMMY DATA (sementara) â€” ganti sumber DB hingga backend siap ===== */
    $stores = collect([
        (object) ['nama_toko' => 'Toko Modis'],
        (object) ['nama_toko' => 'Atelier Raliva'],
    ]);
    $stats = [
        'pesanan_baru' => 5,
        'menunggu_verifikasi' => 3,
        'siap_dikirim' => 4,
        'sedang_dikirim' => 2,
        'komplain_terbuka' => 2,
    ];
    $omzetMingguan = [
        'total' => 18500000,
        'bars' => [2.1, 1.8, 2.4, 1.5, 2.9, 2.2, 3.1],
    ];
    $produkTerlaris = [
        ['label' => 'Trench Coat Signature', 'value' => 42],
        ['label' => 'Knit Sweater', 'value' => 38],
        ['label' => 'Silk Scarf', 'value' => 29],
        ['label' => 'Linen Shirt', 'value' => 21],
    ];
    $distribusiStatus = [
        ['label' => 'Baru', 'value' => 5],
        ['label' => 'Diproses', 'value' => 4],
        ['label' => 'Dikirim', 'value' => 3],
        ['label' => 'Selesai', 'value' => 12],
        ['label' => 'Dibatalkan', 'value' => 2],
    ];
    $komplainTerbaru = collect([
        (object) ['subjek' => 'Cek bans kancing copot', 'status' => \App\Models\Complaint::STATUS_OPEN, 'user' => (object) ['nama_lengkap' => 'Rina Maharani'], 'store' => (object) ['nama_toko' => 'Raliva Atelier Jakarta'], 'dibuat_pada' => now()->subDays(1)],
        (object) ['subjek' => 'Warna tidak sesuai foto', 'status' => \App\Models\Complaint::STATUS_OPEN, 'user' => (object) ['nama_lengkap' => 'Putra Wijaya'], 'store' => (object) ['nama_toko' => 'Raliva Outlet Senayan'], 'dibuat_pada' => now()->subHours(6)],
    ]);
    $pesananTerbaru = collect([
        (object) ['status' => \App\Models\Order::STATUS_DIBAYAR, 'nomor_order' => '#RLV-3102', 'checkout' => (object) ['user' => (object) ['nama_lengkap' => 'Sarah Jenkins']], 'items' => collect([(object) ['id' => 1], (object) ['id' => 2]]), 'grand_total' => 1240000, 'created_at' => now()->subHours(2)],
        (object) ['status' => \App\Models\Order::STATUS_DIPROSES, 'nomor_order' => '#RLV-3101', 'checkout' => (object) ['user' => (object) ['nama_lengkap' => 'Dewi Lestari']], 'items' => collect([(object) ['id' => 3]]), 'grand_total' => 689000, 'created_at' => now()->subHours(5)],
        (object) ['status' => \App\Models\Order::STATUS_DIKIRIM, 'nomor_order' => '#RLV-3098', 'checkout' => (object) ['user' => (object) ['nama_lengkap' => 'Andi Pratama']], 'items' => collect([(object) ['id' => 4], (object) ['id' => 5]]), 'grand_total' => 459000, 'created_at' => now()->subDay()],
    ]);
    $omzetJuta = number_format($omzetMingguan['total'] / 1000000, 1, ',', '.');
@endphp

@section('content')
@include('partials.flash-toast')

<div class="flex flex-wrap items-center gap-3 -mt-2 mb-2">
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent">
        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
        {{ now()->translatedFormat('l, d F Y') }}
    </span>
    <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
        Scope: {{ \Illuminate\Support\Str::limit($stores->pluck('nama_toko')->implode(', '), 50) }}
    </span>
</div>

<section>
    <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Hari Ini</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pesanan Baru</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $stats['pesanan_baru'] }}</span>
            <a href="{{ route('admin.pesanan', ['status' => \App\Models\Order::STATUS_DIBAYAR]) }}" class="inline-flex items-center gap-1 text-xs text-secondary hover:underline"><span class="material-symbols-outlined text-[14px]">arrow_forward</span>proses sekarang</a>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Verifikasi</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['menunggu_verifikasi'] }}</span>
            <a href="{{ route('admin.verifikasi-pembayaran') }}" class="inline-flex items-center gap-1 text-xs text-on-surface-variant hover:text-gold-accent hover:underline">perlu ditinjau hari ini<span class="material-symbols-outlined text-[14px]">arrow_forward</span></a>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">fact_check</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Siap Kirim</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $stats['siap_dikirim'] }}</span>
            <a href="{{ route('admin.pengiriman') }}" class="inline-flex items-center gap-1 text-xs text-on-surface-variant hover:text-gold-accent hover:underline">paket menunggu resi<span class="material-symbols-outlined text-[14px]">arrow_forward</span></a>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-error/25 rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komplain Aktif</span>
            <span class="raliva-figure text-[26px] text-error">{{ $stats['komplain_terbuka'] }}</span>
            <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant"><span class="material-symbols-outlined text-[14px]">schedule</span>respons maks 24 jam</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-error/10 pointer-events-none select-none" aria-hidden="true">support_agent</span>
        </div>
    </div>
</section>

<section>
    <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Pekerjaan Tertunda</h2>
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium space-y-6">
        @php
            $pekerjaan = [
                ['icon' => 'fact_check', 'label' => 'Verifikasi Pembayaran', 'sub' => $stats['menunggu_verifikasi'] . ' bukti menunggu tinjauan', 'pct' => min(100, $stats['menunggu_verifikasi'] * 20), 'href' => route('admin.verifikasi-pembayaran'), 'error' => false],
                ['icon' => 'local_shipping', 'label' => 'Input Resi Pengiriman', 'sub' => $stats['siap_dikirim'] . ' paket belum beresi &#8226; ' . $stats['sedang_dikirim'] . ' sedang dikirim', 'pct' => min(100, $stats['siap_dikirim'] * 15), 'href' => route('admin.pengiriman'), 'error' => false],
                ['icon' => 'support_agent', 'label' => 'Tangani Komplain', 'sub' => $stats['komplain_terbuka'] . ' komplain aktif', 'pct' => min(100, $stats['komplain_terbuka'] * 25), 'href' => route('admin.komplain'), 'error' => true],
            ];
        @endphp
        @foreach ($pekerjaan as $tugas)
            <a href="{{ $tugas['href'] }}" class="group block">
                <div class="flex items-center justify-between pb-2">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full {{ $tugas['error'] ? 'bg-error-container text-on-error-container' : 'bg-gold-accent/10 border border-gold-accent/25 text-gold-accent' }} flex items-center justify-center shrink-0 shadow-sm">
                            <span class="material-symbols-outlined">{{ $tugas['icon'] }}</span>
                        </div>
                        <div>
                            <span class="font-title-md text-title-md text-on-surface block">{{ $tugas['label'] }}</span>
                            <span class="text-on-surface-variant font-body-md text-sm">{{ $tugas['sub'] }}</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant group-hover:text-gold-accent group-hover:translate-x-0.5 transition-all">chevron_right</span>
                </div>
                <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r {{ $tugas['error'] ? 'from-error/60 to-error' : 'from-gold-accent/70 to-gold-accent' }} rounded-full transition-all duration-500" style="width: {{ max(4, $tugas['pct']) }}%"></div>
                </div>
            </a>
        @endforeach
    </div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    <section class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Omzet 7 Hari Terakhir</h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider text-gold-accent">Total Rp {{ $omzetJuta }}JT</span>
        </div>
        <div class="h-48" data-bars='@json($omzetMingguan['bars'])' data-bars-suffix=" JT"></div>
        <p class="text-on-surface-variant font-body-md text-[11px] mt-5 pt-4 border-t border-muted-border flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[14px] text-gold-accent">insights</span>
            Total omzet 7 hari terakhir dari pesanan valid di toko yang Anda tugaskan.
        </p>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Produk Terlaris</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">emoji_events</span>
        </div>
        @if (count($produkTerlaris))
            <div data-leaderboard='@json($produkTerlaris)'></div>
        @else
            <p class="text-on-surface-variant text-sm py-8 text-center">Belum ada penjualan.</p>
        @endif
        <a href="{{ route('admin.produk') }}" class="block text-center mt-4 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Katalog Produk</a>
    </section>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter items-stretch">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Status Pesanan</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">donut_small</span>
        </div>
        <p class="text-on-surface-variant font-body-md text-xs mb-4">Distribusi seluruh pesanan scope toko.</p>
        @if (count($distribusiStatus))
            <div data-donut='@json($distribusiStatus)' data-donut-label="Pesanan" data-donut-max="220" class="flex-1 flex items-center justify-center min-h-[240px]"></div>
        @else
            <p class="text-on-surface-variant text-sm py-8 text-center">Belum ada pesanan.</p>
        @endif
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Komplain Terbaru</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">support_agent</span>
        </div>
        <ul class="flex flex-col flex-1 overflow-y-auto premium-scroll max-h-80">
                @forelse ($komplainTerbaru as $komplain)
                    <li class="py-3 border-b last:border-b-0 border-muted-border">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-body-md text-sm text-on-surface truncate">{{ $komplain->subjek }}</p>
                            <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border {{ $komplain->status === \App\Models\Complaint::STATUS_OPEN ? 'bg-error/10 text-error border-error/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }}">{{ $komplain->status === \App\Models\Complaint::STATUS_OPEN ? 'Terbuka' : ucfirst($komplain->status) }}</span>
                        </div>
                        <p class="text-xs text-on-surface-variant mt-1">{{ $komplain->user?->nama_lengkap ?? '-' }} &#8226; {{ $komplain->store?->nama_toko ?? '-' }} &#8226; {{ $komplain->dibuat_pada?->translatedFormat('d M') }}</p>
                    </li>
                @empty
                    <li class="py-8 text-center text-on-surface-variant text-sm">Tidak ada komplain.</li>
                @endforelse
            </ul>
            <a href="{{ route('admin.komplain') }}" class="block text-center mt-4 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Kelola Komplain</a>
        </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Pesanan Masuk</h2>
            <a href="{{ route('admin.pesanan') }}" class="font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Semua</a>
        </div>
        <ul class="flex flex-col flex-1 overflow-y-auto premium-scroll max-h-80">
                @forelse ($pesananTerbaru as $pesanan)
                    <li class="p-4 {{ ! $loop->last ? 'border-b border-muted-border' : '' }} hover:bg-surface-container-low transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-9 h-9 rounded-full {{ $pesanan->status === \App\Models\Order::STATUS_DIBAYAR ? 'bg-secondary-container/30' : 'bg-surface-container-high' }} flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-sm {{ $pesanan->status === \App\Models\Order::STATUS_DIBAYAR ? 'text-secondary' : 'text-on-surface' }}">shopping_bag</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-body-md text-on-surface truncate"><span class="font-bold">{{ $pesanan->nomor_order }}</span> &#8226; {{ $pesanan->checkout?->user?->nama_lengkap ?? '-' }} &#8226; {{ $pesanan->items->count() }} produk</p>
                                <p class="text-on-surface-variant text-sm mt-0.5">Rp {{ number_format((float) $pesanan->grand_total, 0, ',', '.') }} &#8226; {{ $pesanan->created_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-8 text-center text-on-surface-variant text-sm">Belum ada pesanan.</li>
                @endforelse
            </ul>
        </section>
</div>
@endsection
