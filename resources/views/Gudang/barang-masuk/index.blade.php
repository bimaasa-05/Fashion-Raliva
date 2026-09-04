@extends('layouts.gudang')

@section('title', 'Barang Masuk')

@section('header-title', 'Barang Masuk')
@section('header-badge', $warehouse->nama_gudang ?? 'Gudang')
@section('header-subtitle', 'Catat dan kelola penerimaan barang di gudang Anda.')

@section('content')
@php
    $badgeClass = [
        'Diterima' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'Menunggu Pemeriksaan' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'Selesai' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
    ];
    $sumberLabel = [
        'production_result' => 'Produksi',
        'stock_transfer' => 'Gudang Lain',
        'order_item' => 'Pesanan',
        'manual' => 'Manual',
    ];
    $sumberIcon = [
        'production_result' => 'precision_manufacturing',
        'stock_transfer' => 'warehouse',
        'order_item' => 'shopping_bag',
        'manual' => 'edit_note',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
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
                    <button type="button" data-modal-open="modal-barang-masuk" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0 min-h-11">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Catat
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
                        <th class="p-4 text-left">Nomor Transaksi</th>
                        <th class="p-4 text-center">Sumber</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Jumlah</th>
                        <th class="p-4 text-left">Catatan</th>
                        <th class="p-4 text-center">Tanggal</th>
                        <th class="p-4 text-center">Petugas</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($items as $m)
                        @php
                            $sumber = $sumberLabel[$m->sumber_tipe] ?? 'Manual';
                            $status = $m->sumber_tipe === 'stock_transfer' ? 'Diterima' : 'Selesai';
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-row>
                            <td class="p-4 text-center text-on-surface-variant">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                            <td class="p-4"><span class="font-bold text-on-surface">BM-{{ str_pad($m->stock_movement_id, 4, '0', STR_PAD_LEFT) }}</span><span class="block text-xs text-on-surface-variant mt-0.5">Ref: {{ $m->sumber_tipe }}{{ $m->sumber_id ? ' #'.$m->sumber_id : '' }}</span></td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center gap-1.5 text-on-surface">
                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">{{ $sumberIcon[$m->sumber_tipe] ?? 'edit_note' }}</span>
                                    {{ $sumber }}
                                </span>
                            </td>
                            <td class="p-4 text-on-surface">{{ $m->productVariant?->product?->nama_produk ?? '-' }}</td>
                            <td class="p-4 text-center font-bold text-secondary">+{{ $m->jumlah }}</td>
                            <td class="p-4 text-on-surface-variant max-w-[220px]">{{ $m->alasan ?: '-' }}</td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $m->created_at?->format('d M Y • H:i') ?? '-' }}</td>
                            <td class="p-4 text-center text-on-surface">{{ $m->creator->nama_lengkap ?? '-' }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$status] }} text-[10px] font-bold uppercase border">{{ $status }}</span></td>
                            <td class="p-4 text-center">
                                <button type="button" data-modal-open="bm-detail-{{ $loop->iteration }}" title="Lihat Detail" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="p-10 text-center text-on-surface-variant">Belum ada catatan barang masuk pada gudang ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile: kartu per transaksi --}}
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($items as $m)
                @php
                    $sumberM = $sumberLabel[$m->sumber_tipe] ?? 'Manual';
                    $statusM = $m->sumber_tipe === 'stock_transfer' ? 'Diterima' : 'Selesai';
                @endphp
                <article class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <p class="font-bold text-on-surface leading-tight">BM-{{ str_pad($m->stock_movement_id, 4, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $m->created_at?->format('d M Y • H:i') ?? '-' }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full {{ $badgeClass[$statusM] }} text-[10px] font-bold uppercase border shrink-0">{{ $statusM }}</span>
                    </div>

                    <p class="font-bold text-on-surface leading-tight mb-3">{{ $m->productVariant?->product?->nama_produk ?? '-' }}</p>

                    <div class="flex items-center justify-between gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 text-on-surface text-sm">
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">{{ $sumberIcon[$m->sumber_tipe] ?? 'edit_note' }}</span>
                            {{ $sumberM }}
                        </span>
                        <span class="font-bold text-secondary">+{{ $m->jumlah }} unit</span>
                    </div>

                    <dl class="space-y-2 font-body-md text-sm mb-4">
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Catatan</dt>
                            <dd class="text-on-surface text-right">{{ $m->alasan ?: '-' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Petugas</dt>
                            <dd class="text-on-surface text-right">{{ $m->creator->nama_lengkap ?? '-' }}</dd>
                        </div>
                    </dl>

                    <button type="button" data-modal-open="bm-detail-{{ $loop->iteration }}" class="w-full min-h-11 inline-flex items-center justify-center gap-2 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">
                        <span class="material-symbols-outlined text-[18px]">visibility</span>Lihat Detail
                    </button>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Belum ada catatan barang masuk pada gudang ini.</p>
            @endforelse
        </div>

        @if ($items->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
                <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ $items->firstItem() }}–{{ $items->lastItem() }} dari {{ $items->total() }} transaksi • {{ $warehouse->nama_gudang ?? '' }}</p>
                <div class="flex items-center gap-1">{{ $items->withQueryString()->links() }}</div>
            </div>
        @endif
    </section>

    @foreach ($items as $m)
        @php $sumber = $sumberLabel[$m->sumber_tipe] ?? 'Manual'; @endphp
        <div id="bm-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">Detail BM-{{ str_pad($m->stock_movement_id, 4, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1">Sumber: {{ $sumber }} • Ref {{ $m->sumber_tipe }}{{ $m->sumber_id ? ' #'.$m->sumber_id : '' }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Produk</dt><dd class="text-on-surface text-right">{{ $m->productVariant?->product?->nama_produk ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Jumlah</dt><dd class="text-secondary font-bold">+{{ $m->jumlah }} unit</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Tanggal &amp; Waktu</dt><dd class="text-on-surface">{{ $m->created_at?->format('d M Y H:i') ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Petugas</dt><dd class="text-on-surface">{{ $m->creator->nama_lengkap ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant">Catatan</dt><dd class="text-on-surface text-right">{{ $m->alasan ?: '-' }}</dd></div>
                </dl>
                <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
            </div>
        </div>
    @endforeach

    <div id="modal-barang-masuk" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Catat Barang Masuk</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Gudang Aktif: {{ $warehouse->nama_gudang ?? '-' }}</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('gudang.barang-masuk.store') }}" method="POST" class="p-6 space-y-5">
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
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Jumlah</label>
                    <input type="number" name="jumlah" min="1" value="1" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Catatan</label>
                    <textarea name="alasan" rows="3" placeholder="Catatan kondisi barang, kelengkapan dokumen, dll. (opsional)" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent resize-none"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
