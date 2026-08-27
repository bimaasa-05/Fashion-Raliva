@extends('layouts.superadmin')

@section('title', 'Dashboard Admin Utama')

@section('header-title', 'Selamat datang, Super Admin')
@section('header-badge', 'Kelola & Lihat')

@section('header-subtitle', 'Ini yang terjadi hari ini.')

@section('content')
<div data-reveal class="flex flex-wrap items-center gap-3 -mt-2 mb-2">
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent">
        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
        Sabtu, 22 Agustus 2026
    </span>
    <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
        Data platform diperbarui real-time
    </span>
</div>

<section>
    <h2 data-reveal class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Platform</h2>
    <div data-reveal-group class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pengguna</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="24.5" data-count-suffix="K">24.5K</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>+8,2%</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:45%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:60%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:55%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:70%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:62%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:85%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:78%"></i>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">group</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Toko</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="1.2" data-count-suffix="K">1.2K</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>+5,4%</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:30%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:35%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:32%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:40%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:38%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:45%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:42%"></i>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">storefront</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pesanan</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="8.4" data-count-suffix="K">8.4K</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>+11,9%</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:50%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:45%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:60%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:55%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:70%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:64%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:80%"></i>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produk</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="45.2" data-count-suffix="K">45.2K</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>+6,7%</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:25%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:30%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:28%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:35%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:33%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:40%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:38%"></i>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">checkroom</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-gold-accent/25 rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium col-span-2 md:col-span-1 hover:border-gold-accent transition-colors hero-glow">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Nilai Transaksi</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp <span data-count="12.5" data-count-suffix="B">12.5B</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>+12,5%</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:55%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:48%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:66%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:58%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:74%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:68%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:88%"></i>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">payments</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-gold-accent/25 rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium col-span-2 md:col-span-1 hover:border-gold-accent transition-colors hero-glow">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komisi Raliva</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold">Rp <span data-count="1.2" data-count-suffix="B">1.2B</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>+15,3%</span>
            <div class="flex items-end gap-[3px] h-6 mt-auto">
                <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:40%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:52%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:46%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:60%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:55%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:72%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:84%"></i>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">percent</span>
        </div>
    </div>
</section>

<section>
    <div data-reveal-group class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 flex flex-col items-center text-center card-premium">
            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant self-start">Target Omzet Bulan Ini</p>
            <div data-donut='[{"value":87,"color":"#C9A24D","label":"Tercapai"},{"value":13,"color":"rgba(127,127,127,0.14)","label":""}]' data-donut-label="dari Target" data-donut-size="130" data-donut-stroke="13" data-donut-max="150" data-donut-suffix="%" data-donut-nolegend="1" class="w-full"></div>
            <p class="text-[11px] text-on-surface-variant mt-1">Rp 10,9B dari target Rp 12,5B</p>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 flex flex-col items-center text-center card-premium">
            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant self-start">Kepuasan Pelanggan</p>
            <div data-donut='[{"value":92,"color":"#795905","label":"Puas"},{"value":8,"color":"rgba(127,127,127,0.14)","label":""}]' data-donut-label="Rating 4,8 / 5" data-donut-size="130" data-donut-stroke="13" data-donut-max="150" data-donut-suffix="%" data-donut-nolegend="1" class="w-full"></div>
            <p class="text-[11px] text-on-surface-variant mt-1">Dari 2.140 ulasan bulan ini</p>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 flex flex-col items-center text-center card-premium">
            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant self-start">SLA Respons Komplain</p>
            <div data-donut='[{"value":78,"color":"#E9CE8A","label":"Tepat SLA"},{"value":22,"color":"rgba(127,127,127,0.14)","label":""}]' data-donut-label="Target 24 Jam" data-donut-size="130" data-donut-stroke="13" data-donut-max="150" data-donut-suffix="%" data-donut-nolegend="1" class="w-full"></div>
            <p class="text-[11px] text-on-surface-variant mt-1">Rata-rata balasan dalam 5 jam</p>
        </div>
    </div>
</section>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Pesanan per Bulan</h2>
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider text-gold-accent">6 Bulan Terakhir</span>
        </div>
        <div class="h-48" data-bars='[{"label":"Mar","value":820},{"label":"Apr","value":910},{"label":"Mei","value":1050},{"label":"Jun","value":980},{"label":"Jul","value":1180},{"label":"Agu","value":1248}]' data-bars-suffix=""></div>
        <p class="text-on-surface-variant font-body-md text-[11px] mt-5 pt-4 border-t border-muted-border flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[14px] text-gold-accent">insights</span>
            Pertumbuhan konsisten — Agustus tertinggi dengan 1.248 pesanan (+5,8% vs Juli).
        </p>
    </section>

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Toko</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">emoji_events</span>
        </div>
        <div data-leaderboard='[{"name":"LUNARA Fashion","meta":"892 pesanan • Rating 4.9","display":"Rp 245JT","pct":100},{"name":"NOIRÉ Studio","meta":"412 pesanan • Rating 4.8","display":"Rp 158JT","pct":64},{"name":"KAYANA Apparel","meta":"318 pesanan • Rating 3.2","display":"Rp 121JT","pct":49},{"name":"Velvet Closet","meta":"264 pesanan • Rating 4.6","display":"Rp 98JT","pct":40}]'></div>
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
        <div data-donut='[{"value":1104,"color":"#C9A24D","label":"Aktif"},{"value":68,"color":"#E9CE8A","label":"Menunggu"},{"value":28,"color":"#BA1A26","label":"Ditangguhkan"}]' data-donut-label="Toko Terdaftar"></div>
        <p class="text-on-surface-variant font-body-md text-[11px] mt-5 pt-4 border-t border-muted-border flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[14px] text-gold-accent">sync</span>
            Sinkron dengan Total Toko di ringkasan atas.
        </p>
    </section>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <section data-reveal class="lg:col-span-1 bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Perlu Perhatian</h2>
        <ul class="flex flex-col gap-4">
            <li class="flex items-center justify-between group cursor-pointer pb-4 border-b border-muted-border last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container shrink-0 shadow-sm">
                        <span class="material-symbols-outlined">store_mall_directory</span>
                    </div>
                    <div>
                        <span class="font-title-md text-title-md text-on-surface block">Verifikasi Toko</span>
                        <span class="text-on-surface-variant font-body-md text-sm"><span data-count="12">12</span> permintaan menunggu</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-outline-variant group-hover:text-gold-accent group-hover:translate-x-0.5 transition-all">chevron_right</span>
            </li>
            <li class="flex items-center justify-between group cursor-pointer pb-4 border-b border-muted-border last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface shrink-0 shadow-sm">
                        <span class="material-symbols-outlined">inventory_2</span>
                    </div>
                    <div>
                        <span class="font-title-md text-title-md text-on-surface block">Moderasi Produk</span>
                        <span class="text-on-surface-variant font-body-md text-sm"><span data-count="45">45</span> item ditandai</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-outline-variant group-hover:text-gold-accent group-hover:translate-x-0.5 transition-all">chevron_right</span>
            </li>
            <li class="flex items-center justify-between group cursor-pointer pb-4 border-b border-muted-border last:border-0 last:pb-0">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-error-container flex items-center justify-center text-on-error-container shrink-0 shadow-sm">
                        <span class="material-symbols-outlined">currency_exchange</span>
                    </div>
                    <div>
                        <span class="font-title-md text-title-md text-on-surface block">Permintaan Refund</span>
                        <span class="text-on-surface-variant font-body-md text-sm"><span data-count="8">8</span> menunggu tinjauan</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-outline-variant group-hover:text-gold-accent group-hover:translate-x-0.5 transition-all">chevron_right</span>
            </li>
        </ul>
    </section>

    <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Aktivitas Terbaru</h2>
        <ul class="flex flex-col">
            <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-full bg-secondary-container/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-secondary">person_add</span>
                    </div>
                    <div>
                        <p class="font-body-md text-on-surface"><span class="font-bold">Sarah Jenkins</span> mendaftar sebagai pengguna baru.</p>
                        <p class="text-on-surface-variant text-sm mt-0.5">2 menit lalu</p>
                    </div>
                </div>
            </li>
            <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-gold-accent">store</span>
                    </div>
                    <div>
                        <p class="font-body-md text-on-surface">Toko <span class="font-bold">LUNARA Fashion</span> terverifikasi.</p>
                        <p class="text-on-surface-variant text-sm mt-0.5">15 menit lalu</p>
                    </div>
                </div>
            </li>
            <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-full bg-surface-container-high flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-on-surface">shopping_cart</span>
                    </div>
                    <div>
                        <p class="font-body-md text-on-surface">Pesanan besar #RLV-2405 telah selesai.</p>
                        <p class="text-on-surface-variant text-sm mt-0.5">1 jam lalu</p>
                    </div>
                </div>
            </li>
            <li class="p-4 hover:bg-surface-container-low transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-full bg-error-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-on-error-container">warning</span>
                    </div>
                    <div>
                        <p class="font-body-md text-on-surface">Produk <span class="font-bold">Oversized Linen Shirt</span> ditandai untuk ditinjau.</p>
                        <p class="text-on-surface-variant text-sm mt-0.5">2 jam lalu</p>
                    </div>
                </div>
            </li>
        </ul>
    </section>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Kategori</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">category</span>
        </div>
        <div data-leaderboard='[{"name":"Pakaian","meta":"18.420 terjual • 240 toko aktif","display":"Rp 4,2M","pct":100},{"name":"Dress","meta":"9.310 terjual • 186 toko aktif","display":"Rp 2,8M","pct":67},{"name":"Celana","meta":"8.140 terjual • 202 toko aktif","display":"Rp 2,1M","pct":50},{"name":"Aksesori","meta":"6.020 terjual • 154 toko aktif","display":"Rp 1,4M","pct":33}]'></div>
        <a href="{{ route('superadmin.peringkat') }}#kategori" class="block text-center mt-4 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Peringkat Lengkap</a>
    </section>

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Top Pelanggan</h2>
            <span class="material-symbols-outlined text-gold-accent text-[20px]">military_tech</span>
        </div>
        <div data-leaderboard='[{"name":"Sarah Jenkins","meta":"14 pesanan • Loyal sejak Mar 2025","display":"Rp 12,4JT","pct":100},{"name":"Dewi Lestari","meta":"9 pesanan • Loyal sejak Jan 2026","display":"Rp 8,7JT","pct":70},{"name":"Andi Pratama","meta":"6 pesanan • Loyal sejak Jul 2025","display":"Rp 3,9JT","pct":31},{"name":"Maya Rossi","meta":"5 pesanan • Loyal sejak Des 2025","display":"Rp 3,2JT","pct":26}]'></div>
        <a href="{{ route('superadmin.peringkat') }}#pelanggan" class="block text-center mt-4 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Peringkat Lengkap</a>
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

    const rangeData = {
        '7': { labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'], transaksi: [45200000, 38500000, 61000000, 56500000, 82000000, 78200000, 49500000], pesanan: [212, 186, 294, 268, 376, 341, 205] },
        '30': { labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'], transaksi: [284000000, 316500000, 348200000, 373000000], pesanan: [1286, 1402, 1518, 1634] },
        '90': { labels: ['Juni', 'Juli', 'Agustus'], transaksi: [682000000, 754000000, 823000000], pesanan: [3120, 3560, 3890] }
    };

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
                        { label: 'Volume Transaksi', data: data.transaksi, borderColor: '#C9A24D', backgroundColor: 'rgba(201, 162, 77, 0.12)', fill: true, tension: 0.38, borderWidth: 2, pointBackgroundColor: '#C9A24D', pointRadius: 3 },
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
    });
</script>
@endpush
