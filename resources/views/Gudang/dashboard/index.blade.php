@extends('layouts.gudang')

@section('title', 'Dashboard Gudang')

@section('header-title', 'Dashboard Gudang')
@section('header-subtitle', 'Pantau persediaan dan aktivitas gudang Anda.')

@section('content')
<div data-skeleton class="space-y-6">
    <div class="h-[76px] bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-2 xl:grid-cols-3 gap-gutter">
        @for ($i = 0; $i < 6; $i++)
            <div class="h-32 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-52 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
    <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-6">
    <section class="rise card-static bg-surface-container-lowest border border-muted-border rounded-lg px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 card-premium">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-gold-accent">warehouse</span>
            </div>
            <div>
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Gudang Aktif</p>
                <p class="font-title-md text-title-md text-on-surface leading-tight">{{ $warehouse->nama_gudang ?? 'Belum ada gudang' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @include('partials.ganti-gudang')
        </div>
    </section>

    <section class="rise">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Gudang</h2>
        <div class="grid grid-cols-2 xl:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produk</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="{{ $stats->total_produk ?? 0 }}">{{ $stats->total_produk ?? 0 }}</span></span>
                <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">inventory_2</span>SKU aktif</span>
                <div class="flex items-end gap-[3px] h-6 mt-auto">
                    <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:30%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:38%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:34%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:44%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:40%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:52%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:48%"></i>
                </div>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">checkroom</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Stok</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="{{ $stats->total_stok ?? 0 }}">{{ number_format($stats->total_stok ?? 0, 0, ',', '.') }}</span></span>
                <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">inventory_2</span>unit tersedia</span>
                <div class="flex items-end gap-[3px] h-6 mt-auto">
                    <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:35%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:40%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:38%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:46%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:44%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:54%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:50%"></i>
                </div>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">inventory_2</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Barang Masuk Hari Ini</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary"><span data-count="{{ $stats->masuk_hari_ini ?? 0 }}">{{ $stats->masuk_hari_ini ?? 0 }}</span></span>
                <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">archive</span>unit masuk</span>
                <div class="flex items-end gap-[3px] h-6 mt-auto">
                    <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:40%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:55%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:48%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:60%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:52%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:72%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:66%"></i>
                </div>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">archive</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Barang Keluar Hari Ini</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary"><span data-count="{{ $stats->keluar_hari_ini ?? 0 }}">{{ $stats->keluar_hari_ini ?? 0 }}</span></span>
                <span class="inline-flex items-center gap-1 text-xs text-error"><span class="material-symbols-outlined text-[14px]">unarchive</span>unit keluar</span>
                <div class="flex items-end gap-[3px] h-6 mt-auto">
                    <i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:48%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:44%"></i><i class="w-1.5 rounded-sm bg-gold-accent/50" style="height:52%"></i><i class="w-1.5 rounded-sm bg-gold-accent/70" style="height:46%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:58%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:50%"></i><i class="w-1.5 rounded-sm bg-gold-accent" style="height:62%"></i>
                </div>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">unarchive</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-gold-accent/25 rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Menipis & Kritis</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent"><span data-count="{{ ($stats->menipis ?? 0) + ($stats->kritis ?? 0) }}">{{ ($stats->menipis ?? 0) + ($stats->kritis ?? 0) }}</span></span>
                <span class="inline-flex items-center gap-1 text-xs text-error"><span class="material-symbols-outlined text-[14px]">north</span>perlu restock</span>
                <div class="flex items-end gap-[3px] h-6 mt-auto">
                    <i class="w-1.5 rounded-sm bg-error/40" style="height:25%"></i><i class="w-1.5 rounded-sm bg-error/40" style="height:32%"></i><i class="w-1.5 rounded-sm bg-error/60" style="height:28%"></i><i class="w-1.5 rounded-sm bg-error/60" style="height:40%"></i><i class="w-1.5 rounded-sm bg-error/80" style="height:36%"></i><i class="w-1.5 rounded-sm bg-error" style="height:48%"></i><i class="w-1.5 rounded-sm bg-error" style="height:55%"></i>
                </div>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">warning</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-error/25 rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Habis</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-error"><span data-count="{{ $stats->habis ?? 0 }}">{{ $stats->habis ?? 0 }}</span></span>
                <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">report</span>varian habis</span>
                <div class="flex items-end gap-[3px] h-6 mt-auto">
                    <i class="w-1.5 rounded-sm bg-error/30" style="height:30%"></i><i class="w-1.5 rounded-sm bg-error/30" style="height:26%"></i><i class="w-1.5 rounded-sm bg-error/50" style="height:34%"></i><i class="w-1.5 rounded-sm bg-error/50" style="height:28%"></i><i class="w-1.5 rounded-sm bg-error/70" style="height:38%"></i><i class="w-1.5 rounded-sm bg-error" style="height:30%"></i><i class="w-1.5 rounded-sm bg-error" style="height:42%"></i>
                </div>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">report</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pelanggan Request</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="{{ $stats->rusak ?? 0 }}">{{ $stats->rusak ?? 0 }}</span></span>
                <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">schedule</span>pesanan menunggu cek stok</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">assignment_ind</span>
            </div>
        </div>
    </section>

    <section class="rise">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 flex flex-col items-center text-center card-premium">
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant self-start">Target Penerimaan Hari Ini</p>
                <div data-donut='[{"value":84,"color":"#C9A24D","label":"Tercapai"},{"value":16,"color":"rgba(127,127,127,0.14)","label":""}]' data-donut-label="dari Target" data-donut-size="130" data-donut-stroke="13" data-donut-max="150" data-donut-suffix="%" data-donut-nolegend="1" class="w-full"></div>
                <p class="text-[11px] text-on-surface-variant mt-1">126 dari 150 unit hari ini</p>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 flex flex-col items-center text-center card-premium">
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant self-start">Akurasi Stok</p>
                <div data-donut='[{"value":96,"color":"#795905","label":"Akurat"},{"value":4,"color":"rgba(127,127,127,0.14)","label":""}]' data-donut-label="Akurasi" data-donut-size="130" data-donut-stroke="13" data-donut-max="150" data-donut-suffix="%" data-donut-nolegend="1" class="w-full"></div>
                <p class="text-[11px] text-on-surface-variant mt-1">Berdasarkan 6 pemeriksaan terakhir</p>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 flex flex-col items-center text-center card-premium">
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant self-start">SLA Pemenuhan Pesanan</p>
                <div data-donut='[{"value":91,"color":"#E9CE8A","label":"Tepat SLA"},{"value":9,"color":"rgba(127,127,127,0.14)","label":""}]' data-donut-label="Target 4 Jam" data-donut-size="130" data-donut-stroke="13" data-donut-max="150" data-donut-suffix="%" data-donut-nolegend="1" class="w-full"></div>
                <p class="text-[11px] text-on-surface-variant mt-1">Rata-rata proses pesanan 3 jam</p>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="rise lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium">
            <div class="flex justify-between items-center mb-6 flex-wrap gap-3">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Pergerakan Stok</h2>
                <div class="inline-flex bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
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

        <section class="rise bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Status Stok</h2>
                <span class="material-symbols-outlined text-gold-accent text-[20px]">donut_small</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-xs mb-4">Sebaran kondisi {{ $stats->total_produk ?? 0 }} produk.</p>
            <div data-donut='[{"value":{{ $statusDist['aman'] ?? 0 }},"color":"#C9A24D","label":"Aman"},{"value":{{ $statusDist['menipis'] ?? 0 }},"color":"#E9CE8A","label":"Menipis"},{"value":{{ $statusDist['kritis'] ?? 0 }},"color":"#BA1A26","label":"Kritis"},{"value":{{ $statusDist['habis'] ?? 0 }},"color":"#7f1010","label":"Habis"}]' data-donut-label="Produk"></div>
            <a href="{{ route('gudang.stok') }}" class="block text-center mt-5 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Data Stok</a>
        </section>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="rise lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Unit Masuk per Hari</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider text-gold-accent">7 Hari Terakhir</span>
            </div>
            <div class="h-48" data-bars='[{"label":"Sen","value":96},{"label":"Sel","value":118},{"label":"Rab","value":104},{"label":"Kam","value":132},{"label":"Jum","value":110},{"label":"Sab","value":148},{"label":"Min","value":126}]'></div>
            <p class="text-on-surface-variant font-body-md text-[11px] mt-5 pt-4 border-t border-muted-border flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px] text-gold-accent">insights</span>
                Kamis tertinggi dengan 132 unit — kedatangan ganda dari supplier utama.
            </p>
        </section>

        <section class="rise bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Kategori Stok Terbesar</h2>
                <span class="material-symbols-outlined text-gold-accent text-[20px]">emoji_events</span>
            </div>
            <div data-leaderboard='[{"name":"Kemeja","meta":"312 SKU • Rak A–B","display":"1.240 pcs","pct":100},{"name":"Celana","meta":"286 SKU • Rak B–C","display":"980 pcs","pct":79},{"name":"Dress","meta":"198 SKU • Rak C–D","display":"760 pcs","pct":61},{"name":"Outerwear","meta":"142 SKU • Rak D","display":"540 pcs","pct":44}]'></div>
            <a href="{{ route('gudang.stok') }}" class="block text-center mt-4 pt-4 border-t border-muted-border font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Semua Kategori</a>
        </section>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="rise lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
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
                        @forelse ($lowStock as $item)
                            <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                                <td class="p-4 text-on-surface">{{ $item->nama_produk }}</td>
                                <td class="p-4 text-center text-on-surface-variant">{{ $item->sku }}</td>
                                <td class="p-4 text-center font-bold {{ $item->status === 'kritis' || $item->status === 'habis' ? 'text-error' : 'text-on-surface' }}">{{ $item->jumlah_stok }}</td>
                                <td class="p-4 text-center text-on-surface-variant">{{ $item->stok_minimum }}</td>
                                <td class="p-4 text-center">
                                    @if ($item->status === 'kritis' || $item->status === 'habis')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">{{ $item->status === 'habis' ? 'Habis' : 'Kritis' }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Menipis</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    <button type="button" onclick="showRalivaToast('Permintaan restock untuk {{ $item->nama_produk }} dikirim ke Admin Toko.', 'local_shipping')" class="px-3 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors btn-premium">Ajukan Restock</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-8 text-center text-on-surface-variant">Tidak ada produk dengan stok menipis.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rise bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col">
            <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Aktivitas Terbaru</h2>
            <ul class="flex flex-col gap-4 flex-grow">
                @forelse ($recentActivity as $act)
                    @php
                        $icon = match ($act->tipe) {
                            'masuk', 'mutasi_masuk' => 'archive',
                            'keluar', 'mutasi_keluar' => 'unarchive',
                            'penyesuaian' => 'fact_check',
                            default => 'inventory_2',
                        };
                        $sign = in_array($act->tipe, ['masuk', 'mutasi_masuk', 'penyesuaian']) ? '+' : '-';
                        $color = in_array($act->tipe, ['keluar', 'mutasi_keluar']) ? 'text-error' : 'text-secondary';
                    @endphp
                    <li class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface shrink-0">
                                <span class="material-symbols-outlined">{{ $icon }}</span>
                            </div>
                            <div class="min-w-0">
                                <span class="font-title-md text-base text-on-surface block">{{ $act->nama_produk }}</span>
                                <span class="text-on-surface-variant font-body-md text-xs truncate block">{{ $act->alasan ?? ucfirst($act->tipe) }} <span class="{{ $color }} font-bold">{{ $sign }}{{ $act->jumlah }}</span></span>
                            </div>
                        </div>
                        <span class="font-label-sm text-xs text-on-surface-variant shrink-0">{{ $act->created_at?->format('H:i') ?? '-' }}</span>
                    </li>
                @empty
                    <li class="text-center text-on-surface-variant py-4">Belum ada aktivitas.</li>
                @endforelse
            </ul>
            <a href="{{ route('gudang.riwayat-stok') }}" class="block text-center mt-6 w-full py-3 border border-muted-border rounded-lg font-label-sm text-label-sm text-gold-accent uppercase tracking-widest hover:bg-gold-accent/10 hover:border-gold-accent/40 transition-colors">Lihat Riwayat Lengkap</a>
        </section>
    </div>
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

    const serverChart = @json($chart);

    const rangeData = {
        '7': { labels: serverChart.labels, masuk: serverChart.masuk, keluar: serverChart.keluar },
        '30': { labels: serverChart.labels, masuk: serverChart.masuk, keluar: serverChart.keluar },
        '90': { labels: serverChart.labels, masuk: serverChart.masuk, keluar: serverChart.keluar }
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
                    tooltip: { backgroundColor: tooltipBg, titleColor: tooltipText, bodyColor: tooltipText, titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 }, padding: 12, cornerRadius: 0 }
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
