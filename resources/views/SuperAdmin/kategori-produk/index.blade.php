@extends('layouts.superadmin')

@section('title', 'Kategori')

@section('header-title', 'Kategori')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola semua kategori global yang digunakan oleh semua toko')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Toolbar -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 md:p-8 hero-glow">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-gold-accent/10 blur-3xl pointer-events-none"></div>
        <span class="material-symbols-outlined absolute -bottom-8 -right-4 text-[140px] text-gold-accent/5 pointer-events-none select-none">category</span>
        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0 shadow-sm"><span class="material-symbols-outlined text-gold-accent text-[22px]">category</span></div>
                <div>
                    <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Semua Kategori</h2>
                    <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Kelola semua kategori global yang digunakan semua toko.</p>
                </div>
            </div>
            <button type="button" id="kategori-toolbar-btn" onclick="openKategoriForm()" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0 shadow-sm">
                <span id="kategori-toolbar-icon" class="material-symbols-outlined text-[18px]">add</span> <span id="kategori-toolbar-label">Tambah Kategori</span>
            </button>
        </div>
    </section>

    <!-- Tab navigation -->
    <div data-kategori-tabs class="bg-surface-container-low border border-muted-border rounded-xl p-1.5 flex flex-wrap gap-1.5">
        <button type="button" data-tab="produk" class="kategori-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Kategori Produk<span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full bg-gold-accent/25 text-gold-accent text-[10px] font-bold">{{ $stats['total'] }}</span></button>
        <button type="button" data-tab="komplain" class="kategori-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Kategori Komplain<span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold">{{ $kategoriKomplain->count() }}</span></button>
        <button type="button" data-tab="pengeluaran" class="kategori-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Kategori Pengeluaran<span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold">{{ $kategoriPengeluaran->count() }}</span></button>
        <button type="button" data-tab="toko" class="kategori-tab-btn inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Kategori Toko<span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold">{{ $kategoriToko->count() }}</span></button>
    </div>

    <!-- Categories Grid -->
    <section data-tab-panel="produk" data-table-scope class="space-y-gutter">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Kategori</h2>
            <div class="flex items-center flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[10px] font-bold uppercase tracking-wider"><span class="material-symbols-outlined text-[14px]">check_circle</span>{{ $stats['aktif'] }} aktif</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/15 text-secondary border border-secondary/25 text-[10px] font-bold uppercase tracking-wider"><span class="material-symbols-outlined text-[14px]">account_tree</span>{{ $stats['induk'] }} induk</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-lowest text-on-surface-variant border border-muted-border text-[10px] font-bold uppercase tracking-wider"><span class="material-symbols-outlined text-[14px]">category</span>total {{ $stats['total'] }}</span>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 space-y-4 card-premium">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Kategori</span>
            </div>
            <div id="kategori-chip-group" class="flex flex-wrap gap-2">
                <button type="button" data-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua ({{ $stats['total'] }})</button>
                <button type="button" data-chip="induk" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Induk ({{ $stats['induk'] }})</button>
                <button type="button" data-chip="sub" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Sub ({{ $stats['total'] - $stats['induk'] }})</button>
                <button type="button" data-chip="nonaktif" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Nonaktif ({{ $stats['total'] - $stats['aktif'] }})</button>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input id="kategori-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent focus:bg-surface-container-lowest transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama kategori atau deskripsi..." />
                    <button type="button" id="kategori-clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                    <span id="kategori-result-count">{{ $categories->count() }}</span> kategori
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            @forelse ($categories as $kategori)
                <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-5 flex flex-col gap-4 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5"
                    data-table-row
                    data-filter="{{ $kategori->parent_id ? 'sub' : 'induk' }}"
                    data-status="{{ $kategori->status }}"
                    data-search="{{ strtolower($kategori->nama_kategori.' '.($kategori->deskripsi ?? '').' '.($kategori->parent->nama_kategori ?? '')) }}"
                    data-id="{{ $kategori->category_id }}"
                    data-nama="{{ $kategori->nama_kategori }}"
                    data-deskripsi="{{ $kategori->deskripsi }}"
                    data-parent="{{ $kategori->parent_id ?? '' }}"
                    data-produk="{{ $kategori->products_count }}"
                    data-sub="{{ $kategori->children_count }}">
                    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-gold-accent via-gold-accent/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <span class="material-symbols-outlined absolute -right-5 -top-5 text-[110px] text-gold-accent/5 select-none pointer-events-none">{{ $kategori->parent_id ? 'category' : 'inventory_2' }}</span>
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                    <div class="relative flex items-start gap-4">
                        <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-white text-[28px]">{{ $kategori->parent_id ? 'subdirectory_arrow_right' : 'folder' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">{{ $kategori->nama_kategori }}</h3>
                                @if ($kategori->status !== \App\Models\Category::STATUS_AKTIF)
                                    <span class="inline-flex px-2 py-0.5 rounded-full bg-error/10 text-error border border-error/20 text-[9px] font-bold uppercase">Nonaktif</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 text-sm mt-1.5 flex-wrap">
                                <span class="inline-flex items-center gap-1 text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-gold-accent">inventory_2</span><span class="font-bold text-gold-accent">{{ $kategori->products_count }}</span>&nbsp;produk</span>
                                @if ($kategori->children_count > 0)
                                    <span class="inline-flex items-center gap-1 text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-gold-accent">account_tree</span><span class="font-bold text-gold-accent">{{ $kategori->children_count }}</span>&nbsp;sub-kategori</span>
                                @endif
                            </div>
                            @if ($kategori->deskripsi)
                                <p class="text-on-surface-variant/80 text-xs mt-1.5 line-clamp-2" title="{{ $kategori->deskripsi }}">{{ \Illuminate\Support\Str::limit($kategori->deskripsi, 70) }}</p>
                            @endif
                            @if ($kategori->parent)
                                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant mt-2 inline-flex items-center gap-1"><span class="material-symbols-outlined text-[12px] text-gold-accent">account_tree</span>Sub dari {{ $kategori->parent->nama_kategori }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="relative flex items-center justify-between pt-4 border-t border-muted-border mt-auto">
                        <button type="button" onclick="openKategoriForm(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button type="button" onclick="openHapusKategori(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center bg-surface-container-lowest border border-dashed border-muted-border rounded-xl">
                    <div class="w-16 h-16 rounded-2xl bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mb-4 shadow-sm"><span class="material-symbols-outlined text-gold-accent text-[32px]">category</span></div>
                    <p class="font-title-md text-title-md text-on-surface mb-1">Belum ada kategori</p>
                    <p class="text-on-surface-variant text-sm mb-6 max-w-sm">Tambahkan kategori pertama Anda agar toko dapat mengelompokkan produknya dengan rapi.</p>
                    <button type="button" onclick="openKategoriForm()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium"><span class="material-symbols-outlined text-[18px]">add</span> Tambah Kategori</button>
                </div>
            @endforelse
        </div>
        <p id="kategori-empty-search" class="hidden flex flex-col items-center justify-center text-center text-on-surface-variant font-body-md text-sm py-14">
            <span class="material-symbols-outlined text-[40px] text-gold-accent/30 mb-3">search_off</span>
            Tidak ada kategori yang cocok.
        </p>
    </section>

    <!-- Panel: Kategori Komplain -->
    <section data-tab-panel="komplain" class="hidden space-y-gutter">
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 card-premium">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">category</span></div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Kategori Komplain</p>
                        <p class="font-title-md text-title-md text-on-surface">{{ $kategoriKomplain->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 card-premium">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-lg bg-secondary-container/20 border border-secondary/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-secondary text-[20px]">report</span></div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Total Komplain</p>
                        <p class="font-title-md text-title-md text-on-surface">{{ $kategoriKomplain->sum('total') }}</p>
                    </div>
                </div>
            </div>
            <div class="hidden lg:flex bg-surface-container-lowest border border-muted-border rounded-xl p-5 card-premium">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">priority_high</span></div>
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Top Kategori</p>
                        <p class="font-title-md text-title-md text-on-surface capitalize truncate">{{ $kategoriKomplain->sortByDesc('total')->first()?->kategori ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden card-premium">
            <table class="w-full premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-center w-12">No</th>
                        <th class="p-4 text-left">Kategori Komplain</th>
                        <th class="p-4 text-center">Total Komplain</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($kategoriKomplain as $k)
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-center text-on-surface-variant font-mono">{{ $loop->iteration }}</td>
                            <td class="p-4 text-on-surface capitalize font-semibold">{{ $k->kategori }}</td>
                            <td class="p-4 text-center font-bold"><span class="inline-flex items-center justify-center min-w-[40px] px-2.5 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-xs">{{ $k->total }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-10 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[36px] text-gold-accent/30 align-middle mr-2">inbox</span>Belum ada data kategori komplain.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Panel: Kategori Pengeluaran -->
    <section data-tab-panel="pengeluaran" class="hidden space-y-gutter">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 card-premium">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">category</span></div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Kategori Pengeluaran</p>
                        <p class="font-title-md text-title-md text-on-surface">{{ $kategoriPengeluaran->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 card-premium">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-lg bg-secondary-container/20 border border-secondary/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-secondary text-[20px]">receipt_long</span></div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Total Transaksi</p>
                        <p class="font-title-md text-title-md text-on-surface">{{ $kategoriPengeluaran->sum('total') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 card-premium">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">payments</span></div>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">Total Nominal</p>
                        <p class="font-title-md text-title-md text-on-surface">Rp {{ number_format((float) $kategoriPengeluaran->sum('total_nominal'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden card-premium">
            <table class="w-full premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-center w-12">No</th>
                        <th class="p-4 text-left">Kategori Pengeluaran</th>
                        <th class="p-4 text-center">Total Transaksi</th>
                        <th class="p-4 text-right">Total Nominal</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($kategoriPengeluaran as $k)
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-center text-on-surface-variant font-mono">{{ $loop->iteration }}</td>
                            <td class="p-4 text-on-surface font-semibold">{{ $k->kategori }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center justify-center min-w-[40px] px-2.5 py-1 rounded-full bg-secondary-container/20 text-secondary text-xs font-bold">{{ $k->total }}</span></td>
                            <td class="p-4 text-right text-on-surface font-bold">Rp {{ number_format((float) $k->total_nominal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[36px] text-gold-accent/30 align-middle mr-2">inbox</span>Belum ada data kategori pengeluaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Panel: Kategori Toko -->
    <section data-tab-panel="toko" class="hidden space-y-gutter">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Master Kategori Toko</h2>
            <div class="flex items-center flex-wrap gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/15 text-secondary border border-secondary/25 text-[10px] font-bold uppercase tracking-wider"><span class="material-symbols-outlined text-[14px]">check_circle</span>{{ $kategoriToko->where('status', \App\Models\StoreCategory::STATUS_AKTIF)->count() }} aktif</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-lowest text-on-surface-variant border border-muted-border text-[10px] font-bold uppercase tracking-wider"><span class="material-symbols-outlined text-[14px]">storefront</span>total {{ $kategoriToko->count() }}</span>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 space-y-4 card-premium">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Kategori Toko</span>
            </div>
            <div id="toko-chip-group" class="flex flex-wrap gap-2">
                <button type="button" data-toko-chip="semua" class="chip-btn px-4 py-2 rounded-lg bg-deep-onyx border border-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua ({{ $kategoriToko->count() }})</button>
                <button type="button" data-toko-chip="aktif" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Aktif ({{ $kategoriToko->where('status', \App\Models\StoreCategory::STATUS_AKTIF)->count() }})</button>
                <button type="button" data-toko-chip="nonaktif" class="chip-btn px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Nonaktif ({{ $kategoriToko->where('status', \App\Models\StoreCategory::STATUS_NONAKTIF)->count() }})</button>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input id="toko-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent focus:bg-surface-container-lowest transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama atau deskripsi kategori toko..." />
                    <button type="button" id="toko-clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>
                <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                    <span id="toko-result-count">{{ $kategoriToko->count() }}</span> kategori toko
                </p>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden card-premium">
            <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-center w-12">No</th>
                        <th class="p-4 text-left">Kategori Toko</th>
                        <th class="p-4 text-left">Deskripsi</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Total Toko</th>
                        <th class="p-4 text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($kategoriToko as $k)
                        <tr data-table-row data-status="{{ $k->status }}" data-search="{{ strtolower($k->nama_kategori.' '.($k->deskripsi ?? '')) }}" data-id="{{ $k->store_category_id }}" data-nama="{{ $k->nama_kategori }}" data-deskripsi="{{ $k->deskripsi }}" data-toko="{{ $k->stores_count }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-center text-on-surface-variant font-mono">{{ $loop->iteration }}</td>
                            <td class="p-4 text-on-surface capitalize font-semibold">{{ $k->nama_kategori }}</td>
                            <td class="p-4 text-on-surface-variant">{{ \Illuminate\Support\Str::limit($k->deskripsi ?? '-', 60) }}</td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $k->status === \App\Models\StoreCategory::STATUS_AKTIF ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-error/10 text-error border-error/20' }}">
                                    <span class="material-symbols-outlined fill text-[12px]">{{ $k->status === \App\Models\StoreCategory::STATUS_AKTIF ? 'check_circle' : 'block' }}</span>{{ $k->status === \App\Models\StoreCategory::STATUS_AKTIF ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="p-4 text-center text-on-surface font-bold">{{ $k->stores_count }}</td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" onclick="openKategoriTokoForm(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                                    <button type="button" onclick="openHapusKategoriToko(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus"><span class="material-symbols-outlined text-[20px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[36px] text-gold-accent/30 align-middle mr-2">storefront</span>Belum ada kategori toko.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
        <p id="toko-empty-search" class="hidden flex flex-col items-center justify-center text-center text-on-surface-variant font-body-md text-sm py-14">
            <span class="material-symbols-outlined text-[40px] text-gold-accent/30 mb-3">search_off</span>
            Tidak ada kategori toko yang cocok.
        </p>
    </section>
</div>

<!-- Modal Form Kategori (Tambah/Edit) -->
<form method="POST" action="" id="kategori-form" onsubmit="closeKategoriModal()">
    @csrf
    <div id="modal-form-kategori" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close onclick="closeKategoriModal()"></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 id="kategori-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Kategori Baru</h3>
                    <p id="kategori-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Kategori berlaku untuk seluruh toko di platform.</p>
                </div>
                <button type="button" onclick="closeKategoriModal()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="namaKategori">Nama Kategori</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="namaKategori" name="nama_kategori" type="text" maxlength="100" placeholder="Misal: Pakaian, Aksesoris" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="parentId">Kategori Induk (opsional)</label>
                    <select name="parent_id" id="parentId" class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors">
                        <option value="">— Tanpa induk (kategori utama) —</option>
                        @foreach ($parents as $induk)
                            <option value="{{ $induk->category_id }}">{{ $induk->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="deskripsiKategori">Deskripsi</label>
                    <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="deskripsiKategori" name="deskripsi" rows="3" maxlength="500" placeholder="Deskripsi kategori..."></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" onclick="closeKategoriModal()" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" id="kategori-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Kategori</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Hapus Kategori -->
<form method="POST" action="" id="hapus-kategori-form" onsubmit="closeHapusModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="hapusKategoriModal" onclick="if (event.target === this) closeHapusModal()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">delete_forever</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus Kategori</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Kategori <span id="hapus-nama" class="font-bold text-on-surface">-</span> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                <div id="hapus-warning" class="hidden mb-4"></div>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeHapusModal()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Form Kategori Toko (Tambah/Edit) -->
<form method="POST" action="" id="kategori-toko-form" onsubmit="closeKategoriTokoModal()">
    @csrf
    <div id="modal-form-kategori-toko" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close onclick="closeKategoriTokoModal()"></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 id="kategori-toko-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Kategori Toko</h3>
                    <p id="kategori-toko-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Kategori toko yang owner harus pilih saat mengisi data toko.</p>
                </div>
                <button type="button" onclick="closeKategoriTokoModal()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="namaKategoriToko">Nama Kategori Toko</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="namaKategoriToko" name="nama_kategori" type="text" maxlength="100" placeholder="Misal: Fashion & Lifestyle" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="deskripsiKategoriToko">Deskripsi</label>
                    <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="deskripsiKategoriToko" name="deskripsi" rows="3" maxlength="500" placeholder="Deskripsi kategori toko..."></textarea>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="statusKategoriToko">Status</label>
                    <select name="status" id="statusKategoriToko" class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" onclick="closeKategoriTokoModal()" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" id="kategori-toko-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Kategori Toko</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Hapus Kategori Toko -->
<form method="POST" action="" id="hapus-kategori-toko-form" onsubmit="closeHapusKategoriTokoModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="hapusKategoriTokoModal" onclick="if (event.target === this) closeHapusKategoriTokoModal()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">delete_forever</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus Kategori Toko</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Kategori toko <span id="hapus-toko-nama" class="font-bold text-on-surface">-</span> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                <div id="hapus-toko-warning" class="hidden mb-4"></div>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeHapusKategoriTokoModal()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const kategoriUrls = {
        store: '{{ route('superadmin.kategori.store') }}',
        update: (id) => '{{ route('superadmin.kategori.update', ':id:') }}'.replace(':id:', id),
        hapus: (id) => '{{ route('superadmin.kategori.hapus', ':id:') }}'.replace(':id:', id)
    };

    function openKategoriForm(card = null) {
        const isEdit = !!card;
        const form = document.getElementById('kategori-form');

        if (isEdit) {
            const d = card.dataset;
            document.getElementById('kategori-modal-title').textContent = 'Ubah Kategori';
            document.getElementById('kategori-modal-sub').textContent = 'Perubahan berlaku pada seluruh produk dalam kategori ini.';
            document.getElementById('namaKategori').value = d.nama;
            document.getElementById('deskripsiKategori').value = d.deskripsi || '';
            document.getElementById('parentId').value = d.parent || '';
            form.action = kategoriUrls.update(d.id);
            document.getElementById('kategori-submit-btn').textContent = 'Simpan Perubahan';
        } else {
            document.getElementById('kategori-modal-title').textContent = 'Tambah Kategori Baru';
            document.getElementById('kategori-modal-sub').textContent = 'Kategori berlaku untuk seluruh toko di platform.';
            document.getElementById('namaKategori').value = '';
            document.getElementById('deskripsiKategori').value = '';
            document.getElementById('parentId').value = '';
            form.action = kategoriUrls.store;
            document.getElementById('kategori-submit-btn').textContent = 'Tambah Kategori';
        }

        document.getElementById('modal-form-kategori').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('namaKategori').focus(), 100);
    }

    function closeKategoriModal() {
        document.getElementById('modal-form-kategori').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openHapusKategori(card) {
        const d = card.dataset;
        const warningBox = document.getElementById('hapus-warning');
        document.getElementById('hapus-nama').textContent = d.nama;

        if (parseInt(d.produk) > 0 || parseInt(d.sub) > 0) {
            const alasan = [];
            if (parseInt(d.produk) > 0) alasan.push(d.produk + ' produk');
            if (parseInt(d.sub) > 0) alasan.push(d.sub + ' sub-kategori');
            warningBox.className = 'mb-4 bg-error/5 border border-error/25 rounded-lg p-3 text-xs text-on-surface';
            warningBox.textContent = '⚠️ Kategori ini masih ' + alasan.join(' dan ') + '. Penghapusan akan ditolak sistem.';
        } else {
            warningBox.className = 'hidden';
            warningBox.textContent = '';
        }

        document.getElementById('hapus-kategori-form').action = kategoriUrls.hapus(d.id);
        const modal = document.getElementById('hapusKategoriModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeHapusModal() {
        const modal = document.getElementById('hapusKategoriModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeKategoriModal(); closeHapusModal(); }
    });
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const scope = document.querySelector('[data-table-scope]');
    if (!scope) return;

    const rows = Array.from(scope.querySelectorAll('[data-table-row]'));
    const chipBtns = document.querySelectorAll('#kategori-chip-group .chip-btn');
    const searchInput = document.getElementById('kategori-search');
    const clearBtn = document.getElementById('kategori-clear-search');
    const countEl = document.getElementById('kategori-result-count');
    const emptySearch = document.getElementById('kategori-empty-search');

    const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
    const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

    let activeChip = 'semua';

    function applyFilter() {
        const term = searchInput.value.trim().toLowerCase();
        let visible = 0;

        rows.forEach((row) => {
            const matchChip = activeChip === 'semua'
                || (activeChip === 'nonaktif' ? row.getAttribute('data-status') === 'nonaktif' : row.getAttribute('data-filter') === activeChip);
            const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
            const show = matchChip && matchSearch;
            row.classList.toggle('hidden', !show);
            if (show) visible++;
        });

        countEl.textContent = visible;
        emptySearch.classList.toggle('hidden', visible > 0 || rows.length === 0);
    }

    chipBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            chipBtns.forEach((b) => {
                b.classList.remove(...activeClasses);
                b.classList.add(...idleClasses, 'hover:bg-surface-container-high');
            });
            btn.classList.remove(...idleClasses, 'hover:bg-surface-container-high');
            btn.classList.add(...activeClasses);
            activeChip = btn.getAttribute('data-chip');
            applyFilter();
        });
    });

    let debounce;
    searchInput.addEventListener('input', () => {
        clearBtn.classList.toggle('opacity-0', !searchInput.value);
        clearTimeout(debounce);
        debounce = setTimeout(applyFilter, 200);
    });

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearBtn.classList.add('opacity-0');
        applyFilter();
    });

    applyFilter();
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabBtns = document.querySelectorAll('[data-kategori-tabs] [data-tab]');
    const panels = document.querySelectorAll('[data-tab-panel]');
    const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
    const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

    const activateTab = (id) => {
        panels.forEach((panel) => panel.classList.toggle('hidden', panel.dataset.tabPanel !== id));
        tabBtns.forEach((btn) => {
            const active = btn.dataset.tab === id;
            btn.classList.remove(...activeClasses, ...idleClasses, 'hover:bg-surface-container-high');
            btn.classList.add(...(active ? activeClasses : [...idleClasses, 'hover:bg-surface-container-high']));
        });

        const toolbarBtn = document.getElementById('kategori-toolbar-btn');
        if (toolbarBtn) {
            const label = document.getElementById('kategori-toolbar-label');
            const isToko = id === 'toko';
            toolbarBtn.onclick = isToko ? () => openKategoriTokoForm() : () => openKategoriForm();
            if (label) label.textContent = isToko ? 'Tambah Kategori Toko' : 'Tambah Kategori';
        }
    };

    tabBtns.forEach((btn) => btn.addEventListener('click', () => activateTab(btn.dataset.tab)));
    activateTab('produk');
});
</script>
@endpush

@push('scripts')
<script>
    const kategoriTokoUrls = {
        store: '{{ route('superadmin.kategori-toko.store') }}',
        update: (id) => '{{ route('superadmin.kategori-toko.update', ':id:') }}'.replace(':id:', id),
        hapus: (id) => '{{ route('superadmin.kategori-toko.hapus', ':id:') }}'.replace(':id:', id)
    };

    function openKategoriTokoForm(row = null) {
        const isEdit = !!row;
        const form = document.getElementById('kategori-toko-form');

        if (isEdit) {
            const d = row.dataset;
            document.getElementById('kategori-toko-modal-title').textContent = 'Ubah Kategori Toko';
            document.getElementById('kategori-toko-modal-sub').textContent = 'Perubahan akan berpengaruh ke dropdown kategori di halaman Owner.';
            document.getElementById('namaKategoriToko').value = d.nama;
            document.getElementById('deskripsiKategoriToko').value = d.deskripsi || '';
            document.getElementById('statusKategoriToko').value = d.status || 'aktif';
            form.action = kategoriTokoUrls.update(d.id);
            document.getElementById('kategori-toko-submit-btn').textContent = 'Simpan Perubahan';
        } else {
            document.getElementById('kategori-toko-modal-title').textContent = 'Tambah Kategori Toko';
            document.getElementById('kategori-toko-modal-sub').textContent = 'Kategori toko yang owner harus pilih saat mengisi data toko.';
            document.getElementById('namaKategoriToko').value = '';
            document.getElementById('deskripsiKategoriToko').value = '';
            document.getElementById('statusKategoriToko').value = 'aktif';
            form.action = kategoriTokoUrls.store;
            document.getElementById('kategori-toko-submit-btn').textContent = 'Tambah Kategori Toko';
        }

        document.getElementById('modal-form-kategori-toko').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('namaKategoriToko').focus(), 100);
    }

    function closeKategoriTokoModal() {
        document.getElementById('modal-form-kategori-toko').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openHapusKategoriToko(row) {
        const d = row.dataset;
        const warningBox = document.getElementById('hapus-toko-warning');
        document.getElementById('hapus-toko-nama').textContent = d.nama;

        if (parseInt(d.toko) > 0) {
            warningBox.className = 'mb-4 bg-error/5 border border-error/25 rounded-lg p-3 text-xs text-on-surface';
            warningBox.textContent = '⚠️ Kategori ini masih dipakai ' + d.toko + ' toko. Penghapusan akan ditolak sistem.';
        } else {
            warningBox.className = 'hidden';
            warningBox.textContent = '';
        }

        document.getElementById('hapus-kategori-toko-form').action = kategoriTokoUrls.hapus(d.id);
        const modal = document.getElementById('hapusKategoriTokoModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeHapusKategoriTokoModal() {
        const modal = document.getElementById('hapusKategoriTokoModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const scope = document.querySelector('[data-tab-panel="toko"]');
        if (!scope) return;

        const rows = Array.from(scope.querySelectorAll('[data-table-row]'));
        const chipBtns = document.querySelectorAll('#toko-chip-group [data-toko-chip]');
        const searchInput = document.getElementById('toko-search');
        const clearBtn = document.getElementById('toko-clear-search');
        const countEl = document.getElementById('toko-result-count');
        const emptySearch = document.getElementById('toko-empty-search');

        const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
        const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

        let activeChip = 'semua';

        function applyTokoFilter() {
            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;

            rows.forEach((row) => {
                const matchChip = activeChip === 'semua' || row.getAttribute('data-status') === activeChip;
                const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
                const show = matchChip && matchSearch;
                row.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            countEl.textContent = visible;
            emptySearch.classList.toggle('hidden', visible > 0 || rows.length === 0);
        }

        chipBtns.forEach((btn) => btn.addEventListener('click', () => {
            activeChip = btn.dataset.tokoChip;
            chipBtns.forEach((b) => {
                b.classList.remove(...activeClasses, ...idleClasses, 'hover:bg-surface-container-high');
                b.classList.add(...(b === btn ? activeClasses : [...idleClasses, 'hover:bg-surface-container-high']));
            });
            applyTokoFilter();
        }));

        let debounce;
        searchInput.addEventListener('input', () => {
            clearBtn.classList.toggle('opacity-0', !searchInput.value);
            clearTimeout(debounce);
            debounce = setTimeout(applyTokoFilter, 200);
        });

        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            clearBtn.classList.add('opacity-0');
            applyTokoFilter();
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeKategoriTokoModal(); closeHapusKategoriTokoModal(); }
    });
</script>
@endpush
