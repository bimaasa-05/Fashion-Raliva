@extends('layouts.gudang')

@section('title', 'Data Stok')

@section('header-title', 'Data Stok')
@section('header-badge', $warehouse->nama_gudang ?? 'Gudang')
@section('header-subtitle', 'Kelola dan pantau stok produk di gudang Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[520px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter & Pencarian</span>
            </div>
            <form method="GET" class="flex flex-col lg:flex-row lg:items-center gap-gutter">
                <div class="relative flex-1 min-w-0">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari produk, SKU..." class="raliva-search" />
                </div>
                <div class="flex flex-wrap gap-gutter">
                    <select name="kategori" aria-label="Filter kategori" class="raliva-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ ($filters['kategori'] ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <select name="status" aria-label="Filter status stok" class="raliva-select">
                        <option value="">Semua Status</option>
                        <option value="aman" {{ ($filters['status'] ?? '') === 'aman' ? 'selected' : '' }}>Aman</option>
                        <option value="menipis" {{ ($filters['status'] ?? '') === 'menipis' ? 'selected' : '' }}>Menipis</option>
                        <option value="kritis" {{ ($filters['status'] ?? '') === 'kritis' ? 'selected' : '' }}>Kritis</option>
                        <option value="habis" {{ ($filters['status'] ?? '') === 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                    <select name="sort" aria-label="Urutkan" class="raliva-select">
                        <option value="terbaru" {{ ($filters['sort'] ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                        <option value="nama" {{ ($filters['sort'] ?? '') === 'nama' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="stok_kecil" {{ ($filters['sort'] ?? '') === 'stok_kecil' ? 'selected' : '' }}>Stok Terkecil</option>
                        <option value="stok_besar" {{ ($filters['sort'] ?? '') === 'stok_besar' ? 'selected' : '' }}>Stok Terbanyak</option>
                    </select>
                    <button type="submit" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Terapkan</button>
                    <a href="{{ route('gudang.stok') }}" class="px-3 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Reset</a>
                </div>
            </form>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[1000px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant text-xs font-medium">
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">SKU</th>
                        <th class="p-4 text-center">Variasi</th>
                        <th class="p-4 text-center">Stok Tersedia</th>
                        <th class="p-4 text-center">Minimum Stok</th>
                        <th class="p-4 text-right">HPP</th>
                        <th class="p-4 text-right">Harga Jual</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Updated</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($products as $row)
                        @php
                            $statusKey = $row->status;
                            $stockClass = in_array($statusKey, ['kritis', 'habis']) ? 'text-error' : ($statusKey === 'menipis' ? 'text-gold-accent' : 'text-on-surface');
                            $badgeClass = [
                                'aman' => 'bg-secondary-container/20 text-secondary border-secondary/20',
                                'menipis' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                                'kritis' => 'bg-error/10 text-error border-error/20',
                                'habis' => 'bg-error text-on-error border-error',
                            ][$statusKey];
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-row data-status="{{ $statusKey }}">
                            <td class="p-4 text-on-surface">{{ $row->produk->nama_produk }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $row->sku }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $row->variasi ?: '-' }}</td>
                            <td class="p-4 text-center font-bold {{ $stockClass }}">{{ $row->total_stok }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $row->stok_minimum }}</td>
                            <td class="p-4 text-right font-title-md text-sm text-on-surface whitespace-nowrap">Rp {{ number_format($row->hpp, 0, ',', '.') }}</td>
                            <td class="p-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format($row->harga_jual, 0, ',', '.') }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass }} text-[10px] font-bold uppercase border">{{ ucfirst($statusKey) }}</span></td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('d M Y') : '-' }}</td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" data-modal-open="stok-detail-{{ $loop->iteration }}" title="Lihat Detail" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                    <a href="{{ route('gudang.riwayat-stok') }}" title="Riwayat" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">history</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="p-10 text-center text-on-surface-variant">Belum ada data stok pada gudang ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
            <p class="text-xs text-on-surface-variant">Menampilkan {{ $products->count() }} produk • {{ $warehouse->nama_gudang ?? '' }}</p>
        </div>
    </section>

    @foreach ($products as $row)
        <div id="stok-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-2xl bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
                <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">{{ $row->produk->nama_produk }}</h3>
                        <p class="text-on-surface-variant text-xs uppercase tracking-wider mt-1">SKU {{ $row->sku }} • {{ $row->produk->category->nama_kategori ?? '-' }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-2 gap-gutter">
                        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                            <p class="raliva-label">Total Stok Tersedia</p>
                            <p class="raliva-figure text-[26px] {{ in_array($row->status, ['kritis', 'habis']) ? 'text-error' : 'text-on-surface' }}">{{ $row->total_stok }}</p>
                        </div>
                        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                            <p class="raliva-label">Minimum Stok</p>
                            <p class="raliva-figure text-[26px] text-on-surface">{{ $row->stok_minimum }}</p>
                        </div>
                    </div>

                    <div class="bg-surface-container-low border border-muted-border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 border-b border-muted-border flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-gold-accent">inventory_2</span>
                            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant font-semibold">Detail per Variasi</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-muted-border bg-surface-container-high/50">
                                        <th class="p-3 text-left font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Variasi</th>
                                        <th class="p-3 text-left font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">SKU</th>
                                        <th class="p-3 text-right font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Harga</th>
                                        <th class="p-3 text-center font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Stok</th>
                                        <th class="p-3 text-center font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Min.</th>
                                        <th class="p-3 text-center font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-muted-border">
                                    @forelse ($row->produk->variants as $v)
                                        @php
                                            $vStok = $v->warehouseStocks->sum('jumlah_stok');
                                            $vMin = $v->warehouseStocks->min('stok_minimum') ?? 0;
                                            $vStatusLabel = $vStok <= 0 ? 'habis' : ($vStok <= $vMin ? ($vStok <= (int) round($vMin / 2) ? 'kritis' : 'menipis') : 'aman');
                                            $vBadge = [
                                                'aman' => 'bg-secondary-container/20 text-secondary border-secondary/20',
                                                'menipis' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                                                'kritis' => 'bg-error/10 text-error border-error/20',
                                                'habis' => 'bg-error text-on-error border-error',
                                            ][$vStatusLabel];
                                            $vLabel = trim(($v->warna ?? '') . ' ' . ($v->ukuran ?? '')) ?: '-';
                                        @endphp
                                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                                            <td class="p-3 text-on-surface font-medium">{{ $vLabel }}</td>
                                            <td class="p-3 text-on-surface-variant font-mono text-xs">{{ $v->sku ?? '-' }}</td>
                                            <td class="p-3 text-right text-on-surface whitespace-nowrap">Rp {{ number_format($v->harga ?? 0, 0, ',', '.') }}</td>
                                            <td class="p-3 text-center font-bold {{ $vStok <= 0 ? 'text-error' : ($vStok <= $vMin ? 'text-gold-accent' : 'text-on-surface') }}">{{ $vStok }}</td>
                                            <td class="p-3 text-center text-on-surface-variant">{{ $vMin }}</td>
                                            <td class="p-3 text-center"><span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $vBadge }} text-[9px] font-bold uppercase border">{{ ucfirst($vStatusLabel) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="p-4 text-center text-on-surface-variant">Tidak ada variasi.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-gold-accent/10 to-transparent border border-gold-accent/25 rounded-lg p-4 flex items-center justify-between gap-3">
                        <div>
                            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Nilai Persediaan</p>
                            <p class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent leading-tight">Rp {{ number_format($row->hpp * $row->total_stok, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <dl class="space-y-4 font-body-md text-sm">
                        <div class="flex justify-between gap-4 pb-4 border-b border-muted-border">
                            <dt class="text-on-surface-variant">Status</dt>
                            <dd><span class="inline-flex items-center px-2 py-1 rounded-full {{ ['aman' => 'bg-secondary-container/20 text-secondary border-secondary/20', 'menipis' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30', 'kritis' => 'bg-error/10 text-error border-error/20', 'habis' => 'bg-error text-on-error border-error'][$row->status] }} text-[10px] font-bold uppercase border">{{ ucfirst($row->status) }}</span></dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-on-surface-variant">Terakhir Diperbarui</dt>
                            <dd class="text-on-surface">{{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('d M Y H:i') : '-' }}</dd>
                        </div>
                    </dl>

                    <div class="flex gap-gutter pt-2">
                        <a href="{{ route('gudang.riwayat-stok') }}" class="flex-1 text-center py-3 border border-muted-border rounded-lg text-sm font-semibold text-gold-accent hover:bg-gold-accent/10 hover:border-gold-accent/40 transition-colors">Lihat Riwayat</a>
                        <button type="button" data-modal-close class="flex-1 py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
