@extends('layouts.gudang')

@section('title', 'Data Stok')

@section('header-title', 'Data Stok')
@section('header-badge', 'Gudang Utama Bandung')
@section('header-subtitle', 'Kelola dan pantau stok produk di gudang Anda — lengkap dengan HPP & Harga Jual.')

@section('content')
@php
    $products = [
        ['Oversized Linen Shirt', 'KEM-001', 'Kemeja', 'S, M, L, XL', 8, 20, 'Rp 180.000', 'Rp 289.000', 'Menipis', '21 Agu 2026'],
        ['Straight Fit Pants', 'CEL-014', 'Celana', '28–34', 12, 25, 'Rp 210.000', 'Rp 329.000', 'Menipis', '21 Agu 2026'],
        ['Relaxed Blazer', 'BLZ-021', 'Blazer', 'M, L', 46, 15, 'Rp 320.000', 'Rp 549.000', 'Aman', '20 Agu 2026'],
        ['Knit Cardigan Rajut', 'RDG-003', 'Cardigan', 'S, M, L', 34, 12, 'Rp 185.000', 'Rp 299.000', 'Aman', '20 Agu 2026'],
        ['Midi Dress Linen', 'DRS-008', 'Dress', 'S, M, L', 58, 18, 'Rp 220.000', 'Rp 389.000', 'Aman', '19 Agu 2026'],
        ['Basic T-Shirt Cotton', 'KSL-002', 'Kaos', 'S, M, L, XL, XXL', 142, 40, 'Rp 55.000', 'Rp 99.000', 'Aman', '22 Agu 2026'],
        ['Denim Jacket Classic', 'JKT-009', 'Jaket', 'M, L, XL', 0, 10, 'Rp 280.000', 'Rp 459.000', 'Habis', '18 Agu 2026'],
        ['Pleated Skirt', 'RKT-005', 'Rok', 'S, M, L', 27, 12, 'Rp 165.000', 'Rp 275.000', 'Aman', '19 Agu 2026'],
        ['Wide Leg Trousers', 'CEL-022', 'Celana', '28–36', 4, 15, 'Rp 175.000', 'Rp 295.000', 'Kritis', '21 Agu 2026'],
        ['Hoodie Fleece Premium', 'HDD-011', 'Hoodie', 'M, L, XL', 63, 20, 'Rp 210.000', 'Rp 359.000', 'Aman', '17 Agu 2026'],
        ['Silk Scarf', 'SYL-004', 'Aksesori', 'One Size', 5, 15, 'Rp 95.000', 'Rp 185.000', 'Kritis', '22 Agu 2026'],
        ['Leather Belt', 'IKT-006', 'Aksesori', '85–105 cm', 88, 25, 'Rp 110.000', 'Rp 199.000', 'Aman', '16 Agu 2026'],
        ['Cargo Shorts', 'CEL-031', 'Celana', '30–34', 41, 12, 'Rp 130.000', 'Rp 229.000', 'Aman', '18 Agu 2026'],
        ['Long Sleeve Polo', 'KSL-017', 'Polo', 'M, L, XL', 9, 20, 'Rp 95.000', 'Rp 169.000', 'Menipis', '21 Agu 2026'],
        ['Maxi Dress Floral', 'DRS-015', 'Dress', 'S, M, L', 72, 20, 'Rp 245.000', 'Rp 429.000', 'Aman', '20 Agu 2026'],
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[520px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center gap-gutter mb-6">
            <div class="relative flex-1 min-w-0">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" data-table-search placeholder="Cari produk, SKU..." class="raliva-search" />
            </div>
            <div class="flex flex-wrap gap-gutter">
                <select data-table-filter="kategori" aria-label="Filter kategori" class="raliva-select">
                    <option value="semua">Semua Kategori</option>
                    <option value="kemeja">Kemeja</option>
                    <option value="celana">Celana</option>
                    <option value="dress">Dress</option>
                    <option value="kaos">Kaos & Polo</option>
                    <option value="jaket">Jaket & Hoodie</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                <select data-table-filter="status" aria-label="Filter status stok" class="raliva-select">
                    <option value="semua">Semua Status</option>
                    <option value="aman">Aman</option>
                    <option value="menipis">Menipis</option>
                    <option value="kritis">Kritis</option>
                    <option value="habis">Habis</option>
                </select>
                <select aria-label="Urutkan" class="raliva-select">
                    <option>Terbaru</option>
                    <option>Nama A-Z</option>
                    <option>Stok Terkecil</option>
                    <option>Stok Terbanyak</option>
                </select>
                <button type="button" data-filter-reset class="px-3 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Reset</button>
            </div>
        </div>

        <div class="border border-gold-accent/20 bg-gold-accent/5 rounded-lg px-4 py-3 flex items-start gap-3 mb-6">
            <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">info</span>
            <p class="text-on-surface-variant font-body-md text-xs leading-relaxed">Kolom <span class="font-bold text-on-surface">HPP</span> & <span class="font-bold text-gold-accent">Harga Jual</span> membantu valuasi inventori. Klik ikon mata untuk melihat detail harga.</p>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[1100px] premium-table">
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
                    @foreach ($products as $product)
                        @php
                            $statusKey = strtolower($product[8]);
                            $stockClass = in_array($statusKey, ['kritis', 'habis']) ? 'text-error' : ($statusKey === 'menipis' ? 'text-gold-accent' : 'text-on-surface');
                            $badgeClass = [
                                'Aman' => 'bg-secondary-container/20 text-secondary border-secondary/20',
                                'Menipis' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                                'Kritis' => 'bg-error/10 text-error border-error/20',
                                'Habis' => 'bg-error text-on-error border-error',
                            ][$product[8]];
                            $kategoriKey = match ($product[2]) {
                                'Kemeja' => 'kemeja',
                                'Celana' => 'celana',
                                'Dress' => 'dress',
                                'Kaos', 'Polo' => 'kaos',
                                'Jaket', 'Hoodie' => 'jaket',
                                default => 'lainnya',
                            };
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-table-row data-kategori="{{ $kategoriKey }}" data-status="{{ $statusKey }}">
                            <td class="p-4 text-on-surface">{{ $product[0] }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $product[1] }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $product[3] }}</td>
                            <td class="p-4 text-center font-bold {{ $stockClass }}">{{ $product[4] }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $product[5] }}</td>
                            <td class="p-4 text-right font-bold text-on-surface-variant whitespace-nowrap">{{ $product[6] }}</td>
                            <td class="p-4 text-right font-bold text-gold-accent whitespace-nowrap">{{ $product[7] }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass }} text-[10px] font-bold uppercase border">{{ $product[8] }}</span></td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $product[9] }}</td>
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
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center justify-center py-16 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-on-surface-variant">inventory_2</span>
            </div>
            <p class="font-title-md text-title-md text-on-surface">Belum Ada Data Stok</p>
            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Belum terdapat data persediaan pada gudang ini yang sesuai dengan pencarian atau filter yang dipilih.</p>
            <button type="button" data-filter-reset class="mt-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium">Reset Filter</button>
        </div>

        <div data-pagination class="flex flex-wrap items-center justify-between gap-4 mt-6">
            <p class="text-xs text-on-surface-variant">Menampilkan 1–{{ count($products) }} dari {{ count($products) }} produk • Gudang Utama Bandung</p>
            <div class="flex items-center gap-1">
                <button type="button" class="min-w-[36px] h-9 px-2 rounded border border-muted-border text-sm text-on-surface-variant opacity-50 cursor-not-allowed" disabled>
                    <span class="material-symbols-outlined text-[18px] align-middle">chevron_left</span>
                </button>
                <button type="button" class="min-w-[36px] h-9 px-3 rounded bg-deep-onyx text-on-primary text-sm font-bold">1</button>
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-3 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">2</button>
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-2 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">
                    <span class="material-symbols-outlined text-[18px] align-middle">chevron_right</span>
                </button>
            </div>
        </div>
    </section>

    @foreach ($products as $product)
        <div id="stok-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">{{ $product[0] }}</h3>
                        <p class="text-on-surface-variant text-xs uppercase tracking-wider mt-1">SKU {{ $product[1] }} • {{ $product[2] }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-gutter mb-6">
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                        <p class="raliva-label">Stok Tersedia</p>
                        <p class="raliva-figure text-[26px] {{ in_array($product[8], ['Kritis', 'Habis']) ? 'text-error' : 'text-on-surface' }}">{{ $product[4] }}</p>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                        <p class="raliva-label">Minimum Stok</p>
                        <p class="raliva-figure text-[26px] text-on-surface">{{ $product[5] }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-gutter mb-6">
                    <div class="bg-gold-accent/5 border border-gold-accent/20 rounded-lg p-4">
                        <p class="raliva-label">HPP (Modal)</p>
                        <p class="font-title-md text-lg text-on-surface-variant mt-1">{{ $product[6] }}</p>
                    </div>
                    <div class="bg-secondary-container/10 border border-secondary/20 rounded-lg p-4">
                        <p class="raliva-label">Harga Jual</p>
                        <p class="font-title-md text-lg text-gold-accent mt-1">{{ $product[7] }}</p>
                    </div>
                </div>
                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border">
                        <dt class="text-on-surface-variant">Status</dt>
                        <dd><span class="inline-flex items-center px-2 py-1 rounded-full {{ ['Aman' => 'bg-secondary-container/20 text-secondary border-secondary/20', 'Menipis' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30', 'Kritis' => 'bg-error/10 text-error border-error/20', 'Habis' => 'bg-error text-on-error border-error'][$product[8]] }} text-[10px] font-bold uppercase border">{{ $product[8] }}</span></dd>
                    </div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border">
                        <dt class="text-on-surface-variant">Variasi</dt>
                        <dd class="text-on-surface text-right">{{ $product[3] }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border">
                        <dt class="text-on-surface-variant">Lokasi Rak</dt>
                        <dd class="text-on-surface">Rak {{ ['A', 'B', 'C', 'D'][$loop->iteration % 4] }}-{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-on-surface-variant">Terakhir Diperbarui</dt>
                        <dd class="text-on-surface">{{ $product[9] }}</dd>
                    </div>
                </dl>
                <div class="flex gap-gutter mt-6">
                    <a href="{{ route('gudang.riwayat-stok') }}" class="flex-1 text-center py-3 border border-muted-border rounded-lg text-sm font-semibold text-gold-accent hover:bg-gold-accent/10 hover:border-gold-accent/40 transition-colors">Lihat Riwayat</a>
                    <button type="button" data-modal-close class="flex-1 py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Tutup</button>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
