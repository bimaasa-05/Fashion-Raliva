@extends('layouts.gudang')

@section('title', 'Pemeriksaan Stok')

@section('header-title', 'Pemeriksaan Stok')
@section('header-badge', $warehouse->nama_gudang ?? 'Gudang')
@section('header-subtitle', 'Bandingkan stok fisik dengan catatan sistem secara berkala.')

@section('content')
@php
    $badgeClass = [
        'Sesuai' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'Selisih' => 'bg-error/10 text-error border-error/20',
        'Selesai' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-20 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[400px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/5 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent mt-0.5">info</span>
        <div>
            <p class="font-body-md text-sm font-bold text-on-surface">Lakukan pemeriksaan rutin untuk menjaga akurasi stok.</p>
            <p class="text-on-surface-variant text-sm mt-0.5">Catat hasil hitungan fisik, lalu sistem akan menyoroti selisih terhadap stok tercatat.</p>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex flex-col gap-4 mb-6">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant hidden md:block">Filter &amp; Pencarian</span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" data-filter-toggle class="md:hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors btn-premium">
                        <span class="material-symbols-outlined text-[18px]" data-filter-icon>tune</span>
                        Filter
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
                    </button>
                    <button type="button" data-modal-open="modal-pemeriksaan" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0 min-h-11">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Periksa
                    </button>
                </div>
            </div>
            <div data-filter-panel class="hidden md:block bg-surface-container-low border border-muted-border rounded-lg p-4">
                <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-gutter">
                    <div class="relative flex-1 min-w-0">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari produk..." class="raliva-search" />
                    </div>
                    <button type="submit" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Cari</button>
                </form>
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[980px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-center w-12">No.</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Stok Sistem</th>
                        <th class="p-4 text-center">Stok Minimum</th>
                        <th class="p-4 text-center">Kategori</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($items as $s)
                        @php
                            $status = $s->jumlah_stok <= 0 ? 'Selisih' : ($s->jumlah_stok <= $s->stok_minimum ? 'Selisih' : 'Sesuai');
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-row>
                            <td class="p-4 text-center text-on-surface-variant">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                            <td class="p-4"><span class="text-on-surface">{{ $s->productVariant?->product?->nama_produk ?? '-' }}</span><span class="block text-xs text-on-surface-variant mt-0.5">{{ $s->productVariant?->sku ?? '' }}</span></td>
                            <td class="p-4 text-center text-on-surface font-bold">{{ $s->jumlah_stok }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $s->stok_minimum }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $s->productVariant?->product?->category?->nama_kategori ?? '-' }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$status] }} text-[10px] font-bold uppercase border">{{ $status }}</span></td>
                            <td class="p-4 text-center">
                                <button type="button" data-modal-open="ps-detail-{{ $loop->iteration }}" title="Periksa" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">fact_check</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-10 text-center text-on-surface-variant">Belum ada stok untuk diperiksa pada gudang ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile: kartu per stok --}}
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($items as $s)
                @php
                    $statusM = $s->jumlah_stok <= 0 ? 'Selisih' : ($s->jumlah_stok <= $s->stok_minimum ? 'Selisih' : 'Sesuai');
                @endphp
                <article class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <p class="font-bold text-on-surface leading-tight">{{ $s->productVariant?->product?->nama_produk ?? '-' }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $s->productVariant?->sku ?? '' }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $badgeClass[$statusM] }} text-[10px] font-bold uppercase border shrink-0">{{ $statusM }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-gutter mb-4">
                        <div class="bg-surface-container-low border border-muted-border rounded-lg p-3">
                            <p class="raliva-label">Stok Sistem</p>
                            <p class="font-title-md text-lg text-on-surface leading-tight">{{ $s->jumlah_stok }}</p>
                        </div>
                        <div class="bg-surface-container-low border border-muted-border rounded-lg p-3">
                            <p class="raliva-label">Min. Stok</p>
                            <p class="font-title-md text-lg text-on-surface leading-tight">{{ $s->stok_minimum }}</p>
                        </div>
                    </div>

                    <dl class="space-y-2 font-body-md text-sm mb-4">
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Kategori</dt>
                            <dd class="text-on-surface text-right">{{ $s->productVariant?->product?->category?->nama_kategori ?? '-' }}</dd>
                        </div>
                    </dl>

                    <button type="button" data-modal-open="ps-detail-{{ $loop->iteration }}" class="w-full min-h-11 inline-flex items-center justify-center gap-2 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">
                        <span class="material-symbols-outlined text-[18px]">fact_check</span>Periksa
                    </button>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Belum ada stok untuk diperiksa pada gudang ini.</p>
            @endforelse
        </div>

        @if ($items->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
                <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} stok • {{ $warehouse->nama_gudang ?? '' }}</p>
                <div class="flex items-center gap-1">{{ $items->withQueryString()->links() }}</div>
            </div>
        @endif
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-[20px] text-gold-accent">history</span>
            <h3 class="font-label-sm text-[12px] uppercase tracking-widest text-on-surface">Riwayat Pemeriksaan Stok</h3>
        </div>

        <div data-table-wrap class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-center w-12">No.</th>
                        <th class="p-4 text-center">Waktu</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Stok Sistem</th>
                        <th class="p-4 text-center">Stok Fisik</th>
                        <th class="p-4 text-center">Selisih</th>
                        <th class="p-4 text-left">Catatan</th>
                        <th class="p-4 text-center">Petugas</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($history as $h)
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-row>
                            <td class="p-4 text-center text-on-surface-variant">{{ ($history->currentPage() - 1) * $history->perPage() + $loop->iteration }}</td>
                            <td class="p-4 text-center">{{ $h->created_at?->format('d M Y • H:i') ?? '-' }}</td>
                            <td class="p-4 text-on-surface">{{ $h->productVariant?->product?->nama_produk ?? '-' }}<span class="block text-xs text-on-surface-variant mt-0.5">{{ $h->productVariant?->sku ?? '' }}</span></td>
                            <td class="p-4 text-center text-on-surface">{{ $h->stok_sistem }}</td>
                            <td class="p-4 text-center text-on-surface">{{ $h->stok_fisik }}</td>
                            <td class="p-4 text-center font-bold {{ $h->selisih > 0 ? 'text-secondary' : ($h->selisih < 0 ? 'text-error' : 'text-on-surface-variant') }}">{{ $h->selisih > 0 ? '+'.$h->selisih : $h->selisih }}</td>
                            <td class="p-4 text-on-surface-variant max-w-[220px]">{{ $h->catatan ?: '-' }}</td>
                            <td class="p-4 text-center">{{ $h->creator?->nama_lengkap ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-10 text-center text-on-surface-variant">Belum ada riwayat pemeriksaan pada gudang ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile: kartu per riwayat --}}
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($history as $h)
                <article class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <p class="font-bold text-on-surface leading-tight">{{ $h->productVariant?->product?->nama_produk ?? '-' }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $h->productVariant?->sku ?? '' }}</p>
                        </div>
                        <span class="text-xs text-on-surface-variant shrink-0 text-right">{{ $h->created_at?->format('d M Y • H:i') ?? '-' }}</span>
                    </div>

                    <div class="grid grid-cols-3 gap-gutter mb-4">
                        <div class="bg-surface-container-low border border-muted-border rounded-lg p-3 text-center">
                            <p class="raliva-label">Sistem</p>
                            <p class="font-title-md text-lg text-on-surface leading-tight">{{ $h->stok_sistem }}</p>
                        </div>
                        <div class="bg-surface-container-low border border-muted-border rounded-lg p-3 text-center">
                            <p class="raliva-label">Fisik</p>
                            <p class="font-title-md text-lg text-on-surface leading-tight">{{ $h->stok_fisik }}</p>
                        </div>
                        <div class="bg-surface-container-low border border-muted-border rounded-lg p-3 text-center">
                            <p class="raliva-label">Selisih</p>
                            <p class="font-title-md text-lg leading-tight {{ $h->selisih > 0 ? 'text-secondary' : ($h->selisih < 0 ? 'text-error' : 'text-on-surface-variant') }}">{{ $h->selisih > 0 ? '+'.$h->selisih : $h->selisih }}</p>
                        </div>
                    </div>

                    <dl class="space-y-2 font-body-md text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Catatan</dt>
                            <dd class="text-on-surface text-right">{{ $h->catatan ?: '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Petugas</dt>
                            <dd class="text-on-surface text-right">{{ $h->creator?->nama_lengkap ?? '-' }}</dd>
                        </div>
                    </dl>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Belum ada riwayat pemeriksaan pada gudang ini.</p>
            @endforelse
        </div>

        @if ($history->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
                <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ $history->firstItem() }}–{{ $history->lastItem() }} dari {{ $history->total() }} pemeriksaan • {{ $warehouse->nama_gudang ?? '' }}</p>
                <div class="flex items-center gap-1">{{ $history->withQueryString()->links() }}</div>
            </div>
        @endif
    </section>

    @foreach ($items as $s)
        <div id="ps-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">Periksa Stok</h3>
                        <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1">{{ $s->productVariant?->product?->nama_produk ?? '-' }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-gutter mb-6">
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Stok Sistem</p>
                        <p class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $s->jumlah_stok }}</p>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Minimum Stok</p>
                        <p class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $s->stok_minimum }}</p>
                    </div>
                </div>
                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">SKU</dt><dd class="text-on-surface">{{ $s->productVariant?->sku ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Kategori</dt><dd class="text-on-surface">{{ $s->productVariant?->product?->category?->nama_kategori ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant">Catatan</dt><dd class="text-on-surface text-right">Stok tercatat sistem. Lakukan hitung fisik untuk menemukan selisih.</dd></div>
                </dl>
                <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
            </div>
        </div>
    @endforeach

    <div id="modal-pemeriksaan" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Pemeriksaan Baru</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Gudang Aktif: {{ $warehouse->nama_gudang ?? '-' }}</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('gudang.pemeriksaan.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Produk</label>
                    <select name="product_variant_id" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                        <option value="">Pilih Produk</option>
                        @foreach ($products as $ws)
                            @php
                                $pv = $ws->productVariant;
                                $label = $pv?->product?->nama_produk ?? '-';
                                $variantText = trim(($pv?->warna ?? '').' '.($pv?->ukuran ?? ''));
                                $label .= ' — '.($variantText ?: ($pv?->sku ?? 'Variant #'.$ws->product_variant_id));
                            @endphp
                            <option value="{{ $ws->product_variant_id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Stok Fisik (Hasil Hitung)</label>
                    <input type="number" name="stok_fisik" min="0" value="0" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                    <p class="text-xs text-on-surface-variant mt-1.5">Jumlah stok aktual yang Anda hitung secara fisik.</p>
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Catatan</label>
                    <textarea name="catatan" rows="3" placeholder="Kondisi barang, lokasi rak, temuan lain (opsional)" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent resize-none"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Pemeriksaan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
