@extends('layouts.admin')

@section('title', 'Data Produk')
@section('header-title', 'Data Produk')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Kelola produk sesuai permission yang diberikan Owner.')

@section('content')
@include('partials.flash-toast')
@if (session('success'))
    <div class="bg-secondary-container/15 border border-secondary/30 text-secondary rounded-lg px-4 py-3 text-sm font-body-md">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="bg-error/10 border border-error/30 text-error rounded-lg px-4 py-3 text-sm font-body-md">{{ session('error') }}</div>
@endif
<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Akses terbatas: produk yang kamu tambahkan akan diajukan dan menunggu persetujuan Super Admin (status <b>pending</b>).</p>
    </div>

    <section data-reveal-group class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">inventory_2</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Total Produk</span>
            <span class="raliva-figure text-[26px] text-on-surface relative">{{ $stats['total'] ?? $products->total() }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">check_circle</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Aktif</span>
            <span class="raliva-figure text-[26px] text-secondary relative">{{ $stats['aktif'] ?? 0 }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Menunggu Persetujuan</span>
            <span class="raliva-figure text-[26px] text-gold-accent relative">{{ $stats['pending'] ?? 0 }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">cancel</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Ditolak</span>
            <span class="raliva-figure text-[26px] text-error relative">{{ $stats['ditolak'] ?? 0 }}</span>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Katalog Produk Toko</h2>
            <div class="flex items-center gap-3">
                <form method="GET" class="relative w-full md:w-56">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="raliva-search" />
                </form>
                <button type="button" data-modal-open="modal-form-produk" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                    <span class="material-symbols-outlined text-[18px]">add</span> Tambah
                </button>
            </div>
        </div>

        <div class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 mb-6 overflow-x-auto max-w-full">
            <button type="button" data-adm-tab="semua" class="adm-tab px-4 py-2 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Semua</button>
            <button type="button" data-adm-tab="pending" class="adm-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Menunggu</button>
            <button type="button" data-adm-tab="disetujui" class="adm-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Disetujui</button>
            <button type="button" data-adm-tab="ditolak" class="adm-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Ditolak</button>
        </div>

        @if ($products->isEmpty())
            <p class="text-on-surface-variant text-sm py-10 text-center">Tidak ada produk ditemukan.</p>
        @else
        <div data-reveal-group class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-gutter">
            @forelse ($products as $p)
                @php
                    $skuA = $p->variants->first()?->sku ?? '-';
                    $statusA = $p->status;
                    $pengajuanTerkunci = in_array($p->product_id, $pendingUpdateIds ?? [], true);
                    $normFotoA = function ($raw) {
                        if (filter_var($raw, FILTER_VALIDATE_URL)) return $raw;
                        $raw = ltrim($raw, '/');
                        if (str_starts_with($raw, 'assets/')) return asset($raw);
                        return asset('storage/' . $raw);
                    };
                    $fotosA = $p->images->map(fn ($img) => $normFotoA($img->file_gambar))->values()->all();
                    $editFotosJson = json_encode($p->images->map(fn ($img) => ['id' => $img->product_image_id, 'url' => $normFotoA($img->file_gambar)])->values()->all());
                    $editVarianJson = json_encode($p->variants->map(fn ($v) => ['id' => $v->product_variant_id, 'ukuran' => $v->ukuran, 'warna' => $v->warna, 'hex' => (\App\Support\WarnaPalet::resolve($v->warna_hex, $v->warna) ?? ''), 'stok' => (int) $v->warehouseStocks->sum('jumlah_stok')])->values()->all());
                    $resepRows = $p->materialRequirements->map(fn ($row) => [
                        'nama' => $row->nama_bahan,
                        'satuan' => $row->satuan,
                        'jumlah' => (float) $row->jumlah_per_unit,
                        'biaya' => 'Rp '.number_format((float) $row->biaya_per_unit, 0, ',', '.'),
                        'total' => 'Rp '.number_format((float) $row->jumlah_per_unit * (float) $row->biaya_per_unit, 0, ',', '.'),
                    ])->values()->all();
                    $hppA = (float) ($p->modal_produksi ?? 0);
                    $marginA = (float) $p->harga_dasar - $hppA;
                    $marginPersenA = (float) $p->harga_dasar > 0 ? ($marginA / (float) $p->harga_dasar) * 100 : 0;
                    $resepRingkasan = $resepRows !== []
                        ? count($resepRows).' bahan • HPP Rp '.number_format($hppA, 0, ',', '.').' • Margin Rp '.number_format($marginA, 0, ',', '.').' ('.number_format($marginPersenA, 2, ',', '.').'%)'
                        : 'Belum ada bahan produksi.';
                    $operasionalRows = $p->operationalCosts->map(fn ($row) => [
                        'nama' => $row->nama_biaya,
                        'nominal' => 'Rp '.number_format((float) $row->nominal, 0, ',', '.'),
                    ])->values()->all();
                @endphp
                <article data-reveal data-adm-row data-status="{{ $statusA === 'aktif' ? 'disetujui' : $statusA }}" data-produk-id="{{ $p->product_id }}" data-produk-nama="{{ $p->nama_produk }}" data-produk-sku="{{ $skuA }}" data-produk-created="{{ $p->created_at?->translatedFormat('d M Y') }}" data-produk-harga="Rp {{ number_format((float) $p->harga_dasar, 0, ',', '.') }}" data-produk-kategori="{{ $p->category?->nama_kategori ?? '-' }}" data-produk-tipe="{{ ucfirst($p->tipe_produk ?? 'regular') }}" data-produk-varian="{{ $p->variants->map(fn ($v) => trim(($v->warna ?? '') . ' ' . ($v->ukuran ?? '')))->filter()->implode(', ') }}" data-produk-deskripsi="{{ $p->deskripsi }}" data-produk-status="{{ $statusA }}" data-produk-alasan="{{ $statusA === 'ditolak' ? ($p->alasan_penolakan ?? '') : '' }}" data-produk-images='@json($fotosA)' data-resep-ringkasan="{{ $resepRingkasan }}" data-resep-rows='@json($resepRows)' data-operasional-rows='@json($operasionalRows)' class="group bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium flex flex-col">
                    <div class="relative aspect-[3/4] bg-surface-container-low overflow-hidden max-h-48" data-produk-gallery>
                        @if (count($fotosA))
                            <div class="block w-full h-full" data-produk-main>
                                <img src="{{ $fotosA[0] }}" alt="{{ $p->nama_produk }}" data-produk-main-img class="w-full h-full object-cover transition-opacity duration-300" loading="lazy" />
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined text-[40px]">inventory_2</span></div>
                        @endif
                        <div class="absolute top-2 right-2">
                            @if ($statusA === 'aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-success/10 text-success text-[9px] font-bold uppercase border border-success/20">Disetujui</span>
                            @elseif ($statusA === 'ditolak')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Ditolak</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30">Menunggu</span>
                            @endif
                        </div>
                    </div>
                    @if (count($fotosA) > 1)
                        <div class="flex gap-2 px-4 pt-3 overflow-x-auto" data-produk-strip>
                            @foreach ($fotosA as $i => $f)
                                <button type="button" data-produk-pin="{{ $i }}" aria-label="Tampilkan foto {{ $i + 1 }} dari {{ $p->nama_produk }}" aria-pressed="{{ $i === 0 ? 'true' : 'false' }}" class="h-16 w-20 shrink-0 rounded-md overflow-hidden border transition-colors {{ $i === 0 ? 'border-gold-accent ring-2 ring-gold-accent/30' : 'border-outline-variant hover:border-gold-accent' }}">
                                    <img src="{{ $f }}" alt="" class="w-full h-full object-cover" loading="lazy" />
                                </button>
                            @endforeach
                        </div>
                    @endif
                    <div class="px-4 pb-4 pt-2 flex flex-col gap-1 flex-1">
                        <h3 class="font-bold text-on-surface leading-tight truncate">{{ $p->nama_produk }}</h3>
                        <p class="text-xs text-on-surface-variant">{{ $skuA }} &#8226; {{ $p->store?->nama_toko ?? '-' }}</p>
                        <p class="font-body-md text-gold-accent font-bold mt-1">Rp {{ number_format((float) $p->harga_dasar, 0, ',', '.') }}</p>
                        <div class="flex flex-col gap-2 mt-3 pt-3 border-t border-muted-border sm:flex-row sm:items-center sm:justify-between sm:flex-wrap">
                            <span class="text-xs text-on-surface-variant truncate">{{ $p->category?->nama_kategori ?? '-' }}</span>
                            @if ($pengajuanTerkunci)
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30 whitespace-nowrap">Pengajuan Pending</span>
                            @endif
                            <div class="flex items-center gap-1.5">
                                <button type="button" data-produk-detail class="inline-flex items-center gap-1 px-2.5 py-1 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap"><span class="material-symbols-outlined text-[14px]">visibility</span>Detail</button>
                                <button type="button" data-produk-edit @disabled($pengajuanTerkunci) title="{{ $pengajuanTerkunci ? 'Menunggu keputusan Super Admin' : 'Ajukan perubahan produk' }}" data-action="{{ route('admin.produk.update', $p) }}" data-nama="{{ $p->nama_produk }}" data-kategori="{{ $p->category_id }}" data-kategori-nama="{{ $p->category?->nama_kategori ?? '' }}" data-harga="{{ $p->harga_dasar }}" data-hpp="{{ $p->modal_produksi ?? '' }}" data-tipe="{{ $p->tipe_produk }}" data-deskripsi="{{ $p->deskripsi }}" data-fotos="{{ $editFotosJson }}" data-varian="{{ $editVarianJson }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-gold-accent/10 border border-gold-accent/30 rounded-lg text-xs font-semibold text-gold-accent hover:bg-gold-accent/20 transition-colors whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"><span class="material-symbols-outlined text-[14px]">edit</span>Edit</button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <p class="col-span-full text-on-surface-variant text-sm py-8 text-center">Tidak ada produk ditemukan.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $products->links() }}</div>
        <div data-empty-state class="hidden flex-col items-center py-8 text-center gap-3">
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada produk pada status ini.</p>
        </div>
        @endif
    </section>
</div>

{{-- Modal Detail Produk (shared, read-only) --}}
<div id="modal-detail-produk" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-2xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto" style="overscroll-behavior: contain;">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div class="min-w-0">
                <h3 id="detail-nama" class="font-title-md text-title-md text-on-surface premium-heading">-</h3>
                <p id="detail-sub" class="text-on-surface-variant font-body-md text-xs mt-1">-</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-5">
            <div id="detail-main-wrap" class="rounded-lg overflow-hidden border border-outline-variant bg-surface-container-low aspect-[3/4] max-h-[60vh] w-full flex items-center justify-center">
                <img id="detail-main-img" class="w-full h-full object-cover" src="" alt="Foto produk" />
                <div id="detail-noimg" class="hidden flex-col items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[40px]">inventory_2</span>
                    <p class="text-xs">Belum ada foto produk</p>
                </div>
            </div>
            <div id="detail-gallery" class="grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div><dt class="raliva-label">Harga Dasar</dt><dd id="detail-harga" class="font-bold text-on-surface mt-1">-</dd></div>
                <div><dt class="raliva-label">Kategori</dt><dd id="detail-kategori" class="text-on-surface mt-1">-</dd></div>
                <div><dt class="raliva-label">Tipe</dt><dd id="detail-tipe" class="text-on-surface mt-1">-</dd></div>
                <div><dt class="raliva-label">Varian</dt><dd id="detail-varian" class="text-on-surface mt-1">-</dd></div>
                <div class="sm:col-span-2"><dt class="raliva-label">Deskripsi</dt><dd id="detail-deskripsi" class="text-on-surface mt-1 whitespace-pre-line">-</dd></div>
                <div class="sm:col-span-2">
                    <dt class="raliva-label">Rencana Produksi</dt>
                    <dd id="detail-resep-ringkasan" class="text-on-surface mt-1">-</dd>
                    <div id="detail-resep-wrap" class="hidden overflow-x-auto mt-2">
                        <table class="w-full min-w-[520px] text-xs">
                            <thead>
                                <tr class="text-left uppercase tracking-wider text-on-surface-variant">
                                    <th class="py-1 pr-2">Bahan</th>
                                    <th class="py-1 pr-2">Jumlah</th>
                                    <th class="py-1 pr-2">Biaya</th>
                                    <th class="py-1 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="detail-resep-rows"></tbody>
                        </table>
                    </div>
                    <div id="detail-operasional-wrap" class="hidden overflow-x-auto mt-2">
                        <table class="w-full min-w-[520px] text-xs">
                            <thead>
                                <tr class="text-left uppercase tracking-wider text-on-surface-variant">
                                    <th class="py-1 pr-2">Biaya Operasional</th>
                                    <th class="py-1 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody id="detail-operasional-rows"></tbody>
                        </table>
                    </div>
                </div>
            </dl>
            <div id="detail-reason-box" class="hidden rounded-lg border border-error/20 bg-error/5 px-4 py-3">
                <p class="font-label-sm text-[10px] uppercase tracking-wider text-error">Alasan Penolakan</p>
                <p id="detail-reason" class="text-sm text-on-surface mt-1">-</p>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Produk (full parity dengan form tambah: teks + foto + varian + stok) --}}
<div id="modal-edit-produk" data-modal class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form id="form-edit-produk" method="POST" action="" enctype="multipart/form-data" class="relative mx-auto w-full max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto" style="overscroll-behavior: contain; scrollbar-gutter: stable;">
        @csrf
        @method('PUT')
        <div class="sticky top-0 bg-surface-container-lowest z-10 flex items-center justify-between px-6 py-5 border-b border-muted-border">
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Edit Produk</h3>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6 space-y-6">
            {{-- Foto: lama (centang untuk hapus) + tambah baru --}}
            <div>
                <label class="block raliva-label mb-2">Foto Saat Ini <span class="normal-case font-normal">(centang untuk hapus)</span></label>
                <div id="edit-foto-lama" class="grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                <p id="edit-foto-kosong" class="hidden text-xs text-on-surface-variant">Belum ada foto.</p>
                <label class="block raliva-label mt-4 mb-2">Tambah Foto Baru <span class="normal-case font-normal">(maks. total 5)</span></label>
                <div id="edit-foto-slot-grid" class="grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                <p class="text-xs text-on-surface-variant mt-2"><span id="edit-foto-count">0</span> foto lama + <span id="edit-foto-baru-count">0</span> baru (maks. total 5).</p>
            </div>
            <div class="space-y-4">
                <p class="text-xs font-medium text-gold-accent pt-2 border-t border-muted-border">Informasi Dasar</p>
                <div>
                    <label class="block text-xs uppercase text-on-surface-variant mb-1 font-semibold">Nama Produk *</label>
                    <input type="text" id="edit-nama-produk" name="nama_produk" required class="raliva-input w-full" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs uppercase text-on-surface-variant mb-1 font-semibold">Kategori <span class="text-error">*</span></label>
                        @include('partials.kategori-combobox', ['prefix' => 'edit', 'categories' => $categories, 'selectedId' => '', 'selectedName' => ''])
                    </div>
                    <div>
                        <label class="block text-xs uppercase text-on-surface-variant mb-1 font-semibold">Tipe Produk</label>
                        <select id="edit-tipe-produk" name="tipe_produk" class="raliva-select w-full">
                            <option value="regular">Regular</option>
                            <option value="preorder">Pre-Order</option>
                            <option value="made_to_order">Made to Order</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs uppercase text-on-surface-variant mb-1 font-semibold">HPP / Modal (Rp) *</label>
                        <div class="flex items-stretch">
                            <span class="inline-flex items-center px-4 text-sm font-bold text-on-surface-variant bg-surface-container-low border border-muted-border rounded-l-lg border-r-0 select-none">Rp</span>
                            <input type="text" id="edit-hpp" name="hpp" required inputmode="numeric" data-rupiah placeholder="650.000" class="raliva-input w-full" style="border-top-left-radius:0;border-bottom-left-radius:0;" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs uppercase text-on-surface-variant mb-1 font-semibold">Harga Jual (Rp) *</label>
                        <div class="flex items-stretch">
                            <span class="inline-flex items-center px-4 text-sm font-bold text-on-surface-variant bg-surface-container-low border border-muted-border rounded-l-lg border-r-0 select-none">Rp</span>
                            <input type="text" id="edit-harga-dasar" name="harga_dasar" required inputmode="numeric" data-rupiah placeholder="949.000" class="raliva-input w-full" style="border-top-left-radius:0;border-bottom-left-radius:0;" />
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs uppercase text-on-surface-variant mb-1 font-semibold">Deskripsi</label>
                    <textarea id="edit-deskripsi" name="deskripsi" rows="3" class="raliva-textarea w-full" placeholder="Deskripsi produk..."></textarea>
                </div>
            </div>
            <div class="space-y-4">
                <p class="text-xs font-medium text-gold-accent pt-2 border-t border-muted-border">Variasi &amp; Stok</p>
                <div>
                    <p class="raliva-label mb-2">Ukuran @if(!empty($tokoKategori))<span class="text-xs font-normal text-on-surface-variant">(kategori toko: {{ $tokoKategori }})</span>@endif</p>
                    <div class="flex flex-wrap gap-2" id="edit-ukuran-chips">
                        @foreach (($ukuranOptions ?? ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size']) as $size)
                            <button type="button" class="edit-ukuran-chip px-4 py-2 rounded-lg border border-muted-border text-xs font-medium text-on-surface hover:border-gold-accent transition-colors" data-size="{{ $size }}">{{ $size }}</button>
                        @endforeach
                    </div>
                    <input type="hidden" name="ukuran_terpilih" id="edit-ukuran-terpilih" />
                </div>
                <div>
                <p class="raliva-label mb-2">Warna <span class="text-xs font-normal text-on-surface-variant">(opsional; klik untuk pilih, bisa lebih dari satu)</span></p>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2" id="edit-warna-presets">
                        @foreach ([['Navy', '#22304a'], ['Camel', '#c19a6b'], ['Putih', '#f5f3f3'], ['Merah', '#c62828'], ['Biru', '#2360a8'], ['Kuning', '#e6b91e'], ['Marun', '#7d2b33'], ['Hijau', '#2e7d32'], ['Emerald', '#046e4c'], ['Coral', '#f2875c'], ['Teal', '#0f766e'], ['Cream', '#f6ecd9'], ['Violet', '#7c3aed'], ['Sage', '#9caf88']] as $color)
                            <label class="edit-warna-chip flex flex-col items-center gap-1 py-2 rounded-lg border border-muted-border cursor-pointer hover:border-gold-accent transition-colors has-[:checked]:bg-gold-accent/10 has-[:checked]:border-gold-accent" data-warna-value="{{ $color[0] }}" data-hex="{{ $color[1] }}">
                                <input type="checkbox" name="warna[]" value="{{ $color[0] }}" class="sr-only peer" />
                                <span class="w-7 h-7 rounded-full border border-outline-variant shadow-inner transition-all" style="background-color: {{ $color[1] }};"></span>
                                <span class="font-body-md text-[10px] text-on-surface-variant text-center leading-tight">{{ $color[0] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div id="edit-warna-custom-chips" class="flex flex-wrap gap-2 mt-2"></div>
                    <div class="flex items-center gap-2 mt-3 flex-wrap">
                        <input type="text" id="edit-warna-custom-name" placeholder="Warna baru (wajib bila tambah warna, cth: Tosca)" maxlength="30" class="raliva-input text-sm flex-1" style="min-width:10rem;" />
                        <div class="flex items-center gap-1.5 shrink-0">
                            <span class="text-on-surface-variant font-bold text-sm">#</span>
                            <input type="text" id="edit-warna-custom-hex" placeholder="f4f4f4" maxlength="6" autocomplete="off" spellcheck="false" class="raliva-input text-sm font-mono uppercase" style="width: 7.5rem;" title="Ketik kode warna hex, cth: f4f4f4" />
                        </div>
                        <input type="color" id="edit-warna-custom-color" value="#1c1b1b" class="w-10 h-10 rounded cursor-pointer shrink-0" title="Pilih warna" />
                        <button type="button" id="edit-warna-custom-add" class="px-4 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded shrink-0">Tambah</button>
                    </div>
                </div>
                <div>
                    <p class="raliva-label mb-1">Stok per Varian</p>
                    <p class="text-xs text-on-surface-variant mb-3">Ubah stok untuk setiap kombinasi varian. Kombinasi baru akan dibuat otomatis.</p>
                    <div id="edit-varian-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
                    <p id="edit-varian-empty" class="mt-2 p-4 border border-dashed border-outline-variant rounded-lg text-center text-xs text-on-surface-variant">Belum ada varian. Pilih ukuran di atas; warna boleh dikosongkan.</p>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border px-6 py-4 space-y-3">
            <p class="text-xs text-on-surface-variant">Perubahan tidak langsung berlaku. Pengajuan ini dikunci sampai diputuskan Super Admin.</p>
            <div class="flex gap-3">
                <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded transition-colors btn-premium">Ajukan Perubahan</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    (function () {
        const detailModal = document.getElementById('modal-detail-produk');
        const openModal = (el) => { if (window.ralivaOpenModal) window.ralivaOpenModal(el); else el?.classList.remove('hidden'); };
        const closeModal = (el) => { if (window.ralivaCloseModal) window.ralivaCloseModal(el); else el?.classList.add('hidden'); };

        const editUkuranSelected = new Set();
        let editVarianExisting = [];

        const editGetUkuran = () => Array.from(editUkuranSelected);
        const editGetWarna = () => Array.from(document.querySelectorAll('#edit-warna-presets input[name="warna[]"]:checked, #edit-warna-custom-chips input[name="warna[]"]:checked')).map(cb => cb.value);

        function editSyncUkuranHidden() {
            document.getElementById('edit-ukuran-terpilih').value = editGetUkuran().join(',');
        }

        function editRenderVarian() {
            const ukuran = editGetUkuran();
            const warna = editGetWarna();
            const barisWarna = warna.length ? warna : [null];
            const grid = document.getElementById('edit-varian-grid');
            const empty = document.getElementById('edit-varian-empty');
            if (!grid || !empty) return;
            const byKey = {};
            editVarianExisting.forEach(v => { byKey[(v.ukuran || '') + '|' + (v.warna || '')] = v; });
            const count = ukuran.length * barisWarna.length;
            empty.style.display = count > 0 ? 'none' : 'block';
            grid.innerHTML = '';
            let i = 0;
            ukuran.forEach(uk => {
                barisWarna.forEach(wr => {
                    const ex = byKey[uk + '|' + (wr || '')];
                    const row = document.createElement('div');
                    row.className = 'p-3 border border-muted-border rounded-lg bg-surface-container-low space-y-2';
                    row.innerHTML = `
                        <input type="hidden" name="varian_stok[${i}][variant_id]" value="${ex ? ex.id : ''}" />
                        <input type="hidden" name="varian_stok[${i}][ukuran]" value="${escapeHtml(uk)}" />
                        <input type="hidden" name="varian_stok[${i}][warna]" value="${wr === null ? '' : escapeHtml(wr)}" />
                        <div class="flex items-center gap-2">
                            ${wr === null ? '' : `<span class="w-4 h-4 rounded-full border border-outline-variant shrink-0 inline-block" style="background-color: ${warnaSwatch(wr)}"></span>`}
                            <span class="text-xs font-bold text-on-surface truncate">${escapeHtml(uk)}${wr === null ? '' : ` · ${escapeHtml(wr)}`}${ex ? '' : ' <span class="text-gold-accent font-normal">(baru)</span>'}</span>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Stok</label>
                            <input type="text" inputmode="numeric" data-ribuan-int placeholder="10.000" name="varian_stok[${i}][stok]" value="${ex ? ex.stok : 0}" class="raliva-input text-sm" />
                        </div>
                    `;
                    grid.appendChild(row);
                    i++;
                });
            });
        }

        function editSetUkuranChip(size, on) {
            document.querySelectorAll('#edit-ukuran-chips .edit-ukuran-chip').forEach(ch => {
                if ((ch.dataset.size || ch.textContent.trim()) !== size) return;
                ch.classList.toggle('bg-gold-accent', on);
                ch.classList.toggle('text-white', on);
                ch.classList.toggle('border-gold-accent', on);
            });
        }

        function editEnsureUkuranChip(size) {
            let found = false;
            document.querySelectorAll('#edit-ukuran-chips .edit-ukuran-chip').forEach(ch => {
                if ((ch.dataset.size || ch.textContent.trim()) === size) found = true;
            });
            if (found) return;
            const box = document.getElementById('edit-ukuran-chips');
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'edit-ukuran-chip px-4 py-2 rounded-lg border text-xs font-medium';
            chip.dataset.size = size;
            chip.textContent = size;
            chip.addEventListener('click', () => {
                const s = chip.dataset.size;
                if (editUkuranSelected.has(s)) { editUkuranSelected.delete(s); editSetUkuranChip(s, false); }
                else { editUkuranSelected.add(s); editSetUkuranChip(s, true); }
                editSyncUkuranHidden();
                editRenderVarian();
            });
            box.appendChild(chip);
        }

        function editEnsureWarnaChecked(name, storedHex = '') {
            let cb = document.querySelector(`#edit-warna-presets input[name="warna[]"][value="${CSS.escape(name)}"]`);
            if (cb) { cb.checked = true; return; }
            cb = document.querySelector(`#edit-warna-custom-chips input[name="warna[]"][value="${CSS.escape(name)}"]`);
            if (cb) { cb.checked = true; return; }
            window.__warnaCustomHex = window.__warnaCustomHex || {};
            if (storedHex && !window.__warnaCustomHex[name]) window.__warnaCustomHex[name] = storedHex;
            const wrap = document.getElementById('edit-warna-custom-chips');
            const label = document.createElement('label');
            label.className = 'flex items-center gap-2 py-1.5 pr-2 pl-2 rounded-lg border border-gold-accent bg-gold-accent/10 cursor-pointer';
            label.innerHTML = `<input type="checkbox" name="warna[]" value="${escapeHtml(name)}" class="sr-only" checked />
                <span class="w-6 h-6 rounded-full border border-outline-variant shadow-inner inline-block" style="background-color: ${warnaSwatch(name)};"></span>
                <span class="font-body-md text-xs text-on-surface">${escapeHtml(name)}</span>`;
            label.querySelector('input').addEventListener('change', editRenderVarian);
            wrap.appendChild(label);
        }

        document.querySelectorAll('#edit-ukuran-chips .edit-ukuran-chip').forEach(ch => {
            ch.addEventListener('click', () => {
                const s = ch.dataset.size || ch.textContent.trim();
                if (editUkuranSelected.has(s)) { editUkuranSelected.delete(s); editSetUkuranChip(s, false); }
                else { editUkuranSelected.add(s); editSetUkuranChip(s, true); }
                editSyncUkuranHidden();
                editRenderVarian();
            });
        });
        document.querySelectorAll('#edit-warna-presets input[name="warna[]"]').forEach(cb => {
            cb.addEventListener('change', editRenderVarian);
        });
        const editHexInput = document.getElementById('edit-warna-custom-hex');
        const editNormHex = (raw) => {
            const h = String(raw || '').trim().replace(/^#/, '').toLowerCase();
            return /^[0-9a-f]{6}$/.test(h) ? '#' + h : '';
        };
        document.getElementById('edit-warna-custom-color')?.addEventListener('input', (e) => {
            if (editHexInput) { editHexInput.value = e.target.value.replace('#', ''); editHexInput.classList.remove('border-error'); }
        });
        editHexInput?.addEventListener('input', () => {
            const valid = editNormHex(editHexInput.value);
            const picker = document.getElementById('edit-warna-custom-color');
            if (valid) {
                if (picker) picker.value = valid;
                editHexInput.classList.remove('border-error');
            } else if (editHexInput.value.trim() !== '') {
                editHexInput.classList.add('border-error');
            } else {
                editHexInput.classList.remove('border-error');
            }
        });
        document.getElementById('edit-warna-custom-add')?.addEventListener('click', () => {
            const nameInput = document.getElementById('edit-warna-custom-name');
            const colorInput = document.getElementById('edit-warna-custom-color');
            const nama = (nameInput.value || '').trim();
            const hex = editNormHex(editHexInput?.value || '');
            if (nama.length < 2 || /^warna\s*\d+$/i.test(nama)) {
                window.showRalivaToast('Nama warna custom wajib diisi minimal 2 karakter.', 'gpp_bad');
                nameInput.focus();
                return;
            }
            if (!hex) {
                window.showRalivaToast('Kode hex warna custom wajib valid. Contoh: f4f4f4.', 'gpp_bad');
                editHexInput.focus();
                return;
            }
            colorInput.value = hex;
            window.__warnaCustomHex = window.__warnaCustomHex || {};
            window.__warnaCustomHex[nama] = hex;
            editEnsureWarnaChecked(nama);
            nameInput.value = '';
            if (editHexInput) { editHexInput.value = colorInput.value.replace('#', ''); editHexInput.classList.remove('border-error'); }
            editRenderVarian();
        });


        document.querySelectorAll('[data-produk-edit]').forEach(btn => {
            btn.addEventListener('click', () => {
                const editModal = document.getElementById('modal-edit-produk');
                const form = document.getElementById('form-edit-produk');
                if (!form || !editModal) return;
                form.action = btn.getAttribute('data-action') || '';
                document.getElementById('edit-nama-produk').value = btn.getAttribute('data-nama') || '';
                window.setKategoriCombobox('edit', btn.getAttribute('data-kategori') || '', btn.getAttribute('data-kategori-nama') || '');
                document.getElementById('edit-harga-dasar').value = btn.getAttribute('data-harga') || '0';
                if (window.__fmtRpHarga) window.__fmtRpHarga(document.getElementById('edit-harga-dasar'));
                document.getElementById('edit-hpp').value = btn.getAttribute('data-hpp') || '0';
                if (window.__fmtRpHarga) window.__fmtRpHarga(document.getElementById('edit-hpp'));
                document.getElementById('edit-tipe-produk').value = btn.getAttribute('data-tipe') || 'regular';
                document.getElementById('edit-deskripsi').value = btn.getAttribute('data-deskripsi') || '';

                let fotos = [];
                let varian = [];
                try { fotos = JSON.parse(btn.getAttribute('data-fotos') || '[]'); } catch (e) { fotos = []; }
                try { varian = JSON.parse(btn.getAttribute('data-varian') || '[]'); } catch (e) { varian = []; }
                editVarianExisting = varian;

                const fotoBox = document.getElementById('edit-foto-lama');
                const fotoEmpty = document.getElementById('edit-foto-kosong');
                fotoBox.innerHTML = '';
                if (!fotos.length) { fotoEmpty.classList.remove('hidden'); }
                else {
                    fotoEmpty.classList.add('hidden');
                    fotos.forEach(f => {
                        const lab = document.createElement('label');
                        lab.className = 'relative aspect-[3/4] rounded-lg overflow-hidden border border-muted-border cursor-pointer group has-[:checked]:border-error has-[:checked]:ring-2 has-[:checked]:ring-error/40';
                        lab.title = 'Centang untuk hapus';
                        lab.innerHTML = `<img src="${f.url}" alt="" class="w-full h-full object-cover" loading="lazy" />
                            <input type="checkbox" name="hapus_foto_ids[]" value="${f.id}" class="sr-only" />
                            <span class="absolute inset-x-0 bottom-0 text-center text-[10px] font-bold uppercase py-1 bg-black/55 text-white opacity-0 group-has-[:checked]:opacity-100 transition-opacity">Hapus</span>`;
                        fotoBox.appendChild(lab);
                    });
                }

                document.getElementById('edit-foto-slot-grid').innerHTML = '';
                _editUpdateFotoCountInit();

                editUkuranSelected.clear();
                document.querySelectorAll('#edit-ukuran-chips .edit-ukuran-chip').forEach(ch => {
                    ch.classList.remove('bg-gold-accent', 'text-white', 'border-gold-accent');
                });
                document.querySelectorAll('#edit-warna-presets input[name="warna[]"]').forEach(cb => { cb.checked = false; });
                document.getElementById('edit-warna-custom-chips').innerHTML = '';
                window.__warnaCustomHex = {};
                const ukSet = [...new Set(varian.map(v => v.ukuran).filter(Boolean))];
                const wrHex = {};
                varian.forEach(v => { if (v.warna && v.hex && !wrHex[v.warna]) wrHex[v.warna] = v.hex; });
                ukSet.forEach(s => { editEnsureUkuranChip(s); editUkuranSelected.add(s); editSetUkuranChip(s, true); });
                Object.keys(wrHex).forEach(w => editEnsureWarnaChecked(w, wrHex[w]));
                [...new Set(varian.map(v => v.warna).filter(Boolean))].filter(w => !wrHex[w]).forEach(w => editEnsureWarnaChecked(w));
                editSyncUkuranHidden();
                editRenderVarian();
                openModal(editModal);
            });
        });

        const editFotoCount = () => {
            const lama = document.querySelectorAll('#edit-foto-lama label').length;
            const hapus = document.querySelectorAll('#edit-foto-lama input[name="hapus_foto_ids[]"]:checked').length;
            const baru = Array.from(document.querySelectorAll('#edit-foto-slot-grid [data-foto-input]')).filter(i => i.files && i.files.length > 0).length;
            const c1 = document.getElementById('edit-foto-count');
            const c2 = document.getElementById('edit-foto-baru-count');
            if (c1) c1.textContent = lama - hapus;
            if (c2) c2.textContent = baru;
            return { kept: lama - hapus, baru };
        };
        const renderEditFotoSlots = () => {
            const grid = document.getElementById('edit-foto-slot-grid');
            if (!grid) return;
            const { kept } = editFotoCount();
            const kapasitas = Math.max(0, 5 - kept);
            const terisi = Array.from(grid.querySelectorAll('[data-foto-input]')).filter(i => i.files && i.files.length > 0).length;
            grid.querySelectorAll('[data-foto-slot]').forEach(slot => {
                const input = slot.querySelector('[data-foto-input]');
                if (!input || !(input.files && input.files.length > 0)) {
                    const img = slot.querySelector('[data-foto-preview]');
                    if (img?.dataset.url) { URL.revokeObjectURL(img.dataset.url); }
                    slot.remove();
                }
            });
            for (let i = terisi; i < kapasitas; i++) {
                const lab = document.createElement('label');
                lab.className = 'foto-slot aspect-[3/4] rounded-lg border-2 border-dashed border-outline-variant flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-gold-accent hover:bg-surface-container-low transition-colors group relative overflow-hidden';
                lab.setAttribute('data-foto-slot', '');
                lab.innerHTML = `
                    <input type="file" name="foto_produk[]" accept="image/*" class="hidden" data-foto-input onchange="previewFotoSlot(this)" />
                    <img alt="" class="hidden absolute inset-0 w-full h-full object-cover" data-foto-preview />
                    <span class="material-symbols-outlined text-[22px] text-on-surface-variant/60 group-hover:text-gold-accent transition-colors animate-[spin_2.5s_linear_infinite] motion-reduce:animate-none" data-foto-icon>progress_activity</span>
                    <span class="text-[10px] text-on-surface-variant" data-foto-label>Foto Baru</span>
                    <span class="hidden absolute top-1 left-1 w-6 h-6 rounded-full bg-secondary text-white items-center justify-center" data-foto-check><span class="material-symbols-outlined text-[14px]">check_circle</span></span>
                    <button type="button" class="hidden absolute top-1 right-1 w-6 h-6 rounded-full bg-black/60 text-white items-center justify-center hover:bg-error transition-colors" data-foto-hapus title="Hapus foto" onclick="hapusFotoSlot(event, this)"><span class="material-symbols-outlined text-[14px]">close</span></button>
                `;
                grid.appendChild(lab);
            }
            editFotoCount();
            window.__editFotoRefresh = renderEditFotoSlots;
        };
        document.getElementById('edit-foto-lama')?.addEventListener('change', renderEditFotoSlots);
        const _editUpdateFotoCountInit = renderEditFotoSlots;

        document.getElementById('form-edit-produk')?.addEventListener('submit', function (e) {
            const { kept, baru } = editFotoCount();
            if (kept + baru > 5) {
                e.preventDefault();
                if (window.__restoreStripped) window.__restoreStripped(document.getElementById('form-edit-produk'));
                window.showRalivaToast('Maksimal total 5 foto (sekarang ' + (kept + baru) + ').', 'gpp_bad');
                return;
            }
            this.querySelectorAll('input[name="warna_hex[]"]').forEach(h => h.remove());
            const hexOf = (name) => {
                if (window.__warnaCustomHex && window.__warnaCustomHex[name]) return window.__warnaCustomHex[name];
                window.__warnaPresetHex = window.__warnaPresetHex || {};
                return (window.__warnaPresetHex && window.__warnaPresetHex[name]) || '';
            };
            this.querySelectorAll('#edit-warna-presets input[name="warna[]"]:checked, #edit-warna-custom-chips input[name="warna[]"]:checked').forEach(cb => {
                const h = document.createElement('input');
                h.type = 'hidden';
                h.name = 'warna_hex[]';
                h.value = cb.closest('label')?.getAttribute('data-hex') || hexOf(cb.value);
                this.appendChild(h);
            });
        });

        const fillGallery = (urls) => {
            const gallery = document.getElementById('detail-gallery');
            const main = document.getElementById('detail-main-img');
            const noimg = document.getElementById('detail-noimg');
            gallery.innerHTML = '';
            if (!urls.length) {
                main.classList.add('hidden');
                noimg.classList.remove('hidden');
                noimg.classList.add('flex');
                return;
            }
            main.classList.remove('hidden');
            noimg.classList.add('hidden');
            noimg.classList.remove('flex');
            main.src = urls[0];
            urls.forEach((u, i) => {
                const b = document.createElement('button');
                b.type = 'button';
                b.className = 'h-20 rounded-lg overflow-hidden border ' + (i === 0 ? 'border-gold-accent ring-2 ring-gold-accent/30' : 'border-outline-variant');
                const im = document.createElement('img');
                im.src = u;
                im.alt = 'Foto produk ' + (i + 1);
                im.className = 'w-full h-full object-cover';
                im.loading = 'lazy';
                b.appendChild(im);
                b.addEventListener('click', () => {
                    main.src = u;
                    gallery.querySelectorAll('button').forEach((x) => {
                        x.classList.remove('border-gold-accent', 'ring-2', 'ring-gold-accent/30');
                        x.classList.add('border-outline-variant');
                    });
                    b.classList.remove('border-outline-variant');
                    b.classList.add('border-gold-accent', 'ring-2', 'ring-gold-accent/30');
                });
                gallery.appendChild(b);
            });
        };

        document.querySelectorAll('[data-produk-detail]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const card = btn.closest('article');
                if (!card) return;
                const d = card.dataset;
                document.getElementById('detail-nama').textContent = d.produkNama || '-';
                document.getElementById('detail-sub').textContent = (d.produkSku || '-') + ' • ' + (d.produkCreated || '-');
                document.getElementById('detail-harga').textContent = d.produkHarga || '-';
                document.getElementById('detail-kategori').textContent = d.produkKategori || '-';
                document.getElementById('detail-tipe').textContent = d.produkTipe || '-';
                document.getElementById('detail-varian').textContent = d.produkVarian || '-';
                document.getElementById('detail-deskripsi').textContent = d.produkDeskripsi || '-';
                document.getElementById('detail-resep-ringkasan').textContent = d.resepRingkasan || '-';
                let resepRows = [];
                try { resepRows = JSON.parse(d.resepRows || '[]'); } catch (e) { resepRows = []; }
                const resepWrap = document.getElementById('detail-resep-wrap');
                const resepBody = document.getElementById('detail-resep-rows');
                resepWrap.classList.toggle('hidden', resepRows.length === 0);
                resepBody.innerHTML = '';
                resepRows.forEach((row) => {
                    const tr = document.createElement('tr');
                    tr.className = 'border-t border-muted-border';
                    [['nama', `${row.nama} • ${row.satuan}`], ['jumlah', row.jumlah], ['biaya', row.biaya], ['total', row.total]].forEach(([key, value], index) => {
                        const td = document.createElement('td');
                        td.className = 'py-1 pr-2 text-on-surface' + (index === 3 ? ' text-right font-bold' : '');
                        td.textContent = value ?? '-';
                        tr.appendChild(td);
                    });
                    resepBody.appendChild(tr);
                });
                let operasionalRows = [];
                try { operasionalRows = JSON.parse(d.operasionalRows || '[]'); } catch (e) { operasionalRows = []; }
                const operasionalWrap = document.getElementById('detail-operasional-wrap');
                const operasionalBody = document.getElementById('detail-operasional-rows');
                operasionalWrap.classList.toggle('hidden', operasionalRows.length === 0);
                operasionalBody.innerHTML = '';
                operasionalRows.forEach((row) => {
                    const tr = document.createElement('tr');
                    tr.className = 'border-t border-muted-border';
                    [[row.nama], [row.nominal]].forEach((value, index) => {
                        const td = document.createElement('td');
                        td.className = 'py-1 pr-2 text-on-surface' + (index === 1 ? ' text-right font-bold' : '');
                        td.textContent = value ?? '-';
                        tr.appendChild(td);
                    });
                    operasionalBody.appendChild(tr);
                });
                const rbox = document.getElementById('detail-reason-box');
                if (d.produkStatus === 'ditolak' && d.produkAlasan) {
                    rbox.classList.remove('hidden');
                    document.getElementById('detail-reason').textContent = d.produkAlasan;
                } else {
                    rbox.classList.add('hidden');
                }
                let urls = [];
                try { urls = JSON.parse(d.produkImages || '[]'); } catch (e) { urls = []; }
                fillGallery(urls);
                openModal(detailModal);
            });
        });

        document.querySelectorAll('[data-adm-tab]').forEach((tab) => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('[data-adm-tab]').forEach((t) => {
                    const active = t === tab;
                    t.classList.toggle('bg-deep-onyx', active);
                    t.classList.toggle('text-on-primary', active);
                    t.classList.toggle('text-on-surface-variant', !active);
                    t.classList.toggle('hover:text-on-surface', !active);
                });
                const target = tab.getAttribute('data-adm-tab');
                let visible = 0;
                document.querySelectorAll('[data-adm-row]').forEach((row) => {
                    const show = target === 'semua' || row.getAttribute('data-status') === target;
                    row.classList.toggle('hidden', !show);
                    if (show) visible++;
                });
                document.querySelector('[data-empty-state]')?.classList.toggle('hidden', visible > 0);
                document.querySelector('[data-empty-state]')?.classList.toggle('flex', visible === 0);
            });
        });

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const cardTimers = new Map();
        const stopCardCycle = (card) => {
            const t = cardTimers.get(card);
            if (t) { clearInterval(t); cardTimers.delete(card); }
        };
        window.__ralivaPauseCardGalleries = () => {
            cardTimers.forEach((t, card) => stopCardCycle(card));
        };
        const paintCardPhoto = (card, index) => {
            const mainImg = card.querySelector('[data-produk-main-img]');
            const urls = card._galleryUrls || [];
            if (!mainImg || !urls.length) return;
            const i = Math.min(Math.max(index, 0), urls.length - 1);
            if (card._galleryShown === i && mainImg.getAttribute('src') === urls[i]) return;
            card._galleryShown = i;
            mainImg.onload = () => { mainImg.style.opacity = ''; };
            mainImg.style.opacity = '0';
            mainImg.src = urls[i];
            mainImg.alt = (card.dataset.produkNama || 'Foto produk') + ' — foto ' + (i + 1);
            card.querySelectorAll('[data-produk-pin]').forEach((th) => {
                const active = parseInt(th.dataset.produkPin || '0', 10) === i;
                th.setAttribute('aria-pressed', active ? 'true' : 'false');
                th.classList.toggle('border-gold-accent', active);
                th.classList.toggle('ring-2', active);
                th.classList.toggle('ring-gold-accent/30', active);
                th.classList.toggle('border-outline-variant', !active);
            });
        };
        document.querySelectorAll('article[data-produk-id]').forEach((card) => {
            let urls = [];
            try { urls = JSON.parse(card.dataset.produkImages || '[]'); } catch (e) { urls = []; }
            if (urls.length <= 1) return;
            card._galleryUrls = urls;
            card._galleryPinned = 0;
            card._galleryShown = 0;
            card.querySelectorAll('[data-produk-pin]').forEach((th) => {
                th.addEventListener('click', () => {
                    card._galleryPinned = parseInt(th.dataset.produkPin || '0', 10);
                    stopCardCycle(card);
                    paintCardPhoto(card, card._galleryPinned);
                });
            });
            if (reduceMotion) return;
            const gallery = card.querySelector('[data-produk-gallery]');
            if (!gallery) return;
            gallery.addEventListener('mouseenter', () => {
                stopCardCycle(card);
                let i = card._galleryPinned;
                cardTimers.set(card, setInterval(() => {
                    if (card.classList.contains('hidden')) return;
                    i = (i + 1) % urls.length;
                    paintCardPhoto(card, i);
                }, 1200));
            });
            gallery.addEventListener('mouseleave', () => {
                stopCardCycle(card);
                paintCardPhoto(card, card._galleryPinned);
            });
        });
        document.querySelectorAll('[data-adm-tab]').forEach((tab) => {
            tab.addEventListener('click', () => {
                cardTimers.forEach((t, card) => stopCardCycle(card));
            });
        });
        // Escape ditangani global (ui-scripts) — tutup modal paling atas, anti double-handler.
    })();
</script>
@endpush

{{-- Modal Form Produk — tengah, pola data-modal --}}
<div id="modal-form-produk" data-modal class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form id="form-produk" method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data" class="relative mx-auto w-full max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto" style="overscroll-behavior: contain; scrollbar-gutter: stable;">
        @csrf
    <div class="sticky top-0 bg-surface-container-lowest z-10 flex items-center justify-between px-6 py-5 border-b border-muted-border shrink-0">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Produk Baru</h3>
        <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <div class="p-6 space-y-6">
        {{-- Foto --}}
        <div>
            <label class="block raliva-label mb-2">Foto Produk (maks. 5 foto) <span class="text-error">*</span></label>
            <div class="grid grid-cols-4 gap-gutter" id="foto-slot-grid">
                @for ($i = 0; $i < 5; $i++)
                    <label class="foto-slot aspect-[3/4] rounded-lg border-2 border-dashed border-outline-variant flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-gold-accent hover:bg-surface-container-low transition-colors group relative overflow-hidden" data-foto-slot>
                        <input type="file" name="foto_produk[]" accept="image/*" class="hidden" data-foto-input onchange="previewFotoSlot(this)" />
                        <img alt="" class="hidden absolute inset-0 w-full h-full object-cover" data-foto-preview />
                        <span class="material-symbols-outlined text-[22px] text-on-surface-variant/60 group-hover:text-gold-accent transition-colors animate-[spin_2.5s_linear_infinite] motion-reduce:animate-none" data-foto-icon>progress_activity</span>
                        <span class="text-[10px] text-on-surface-variant" data-foto-label>Foto {{ $i + 1 }}</span>
                        <span class="hidden absolute top-1 left-1 w-6 h-6 rounded-full bg-secondary text-white items-center justify-center" data-foto-check><span class="material-symbols-outlined text-[14px]">check_circle</span></span>
                        <button type="button" class="hidden absolute top-1 right-1 w-6 h-6 rounded-full bg-black/60 text-white items-center justify-center hover:bg-error transition-colors" data-foto-hapus title="Hapus foto" onclick="hapusFotoSlot(event, this)"><span class="material-symbols-outlined text-[14px]">close</span></button>
                    </label>
                @endfor
            </div>
            <p class="text-xs text-on-surface-variant mt-2"><span id="foto-count">0</span>/5 foto dipilih.</p>
        </div>

        {{-- Informasi Dasar --}}
        <div class="space-y-4">
            <p class="text-xs font-medium text-gold-accent pt-2 border-t border-muted-border">Informasi Dasar</p>
            <div>
                <label for="fp-nama" class="block raliva-label mb-2">Nama Produk</label>
                <input id="fp-nama" name="nama_produk" type="text" placeholder="cth. Blazer Wool Premium" required class="raliva-input" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                <div>
                        <label class="block raliva-label mb-2">Kategori <span class="text-error">*</span></label>
                    @include('partials.kategori-combobox', ['prefix' => 'fp', 'categories' => $categories, 'selectedId' => old('category_id', ''), 'selectedName' => ''])
                </div>
                <div>
                    <label for="fp-tipe" class="block raliva-label mb-2">Tipe Produk</label>
                    <select id="fp-tipe" name="tipe_produk" required class="raliva-select">
                        <option value="regular">Regular</option>
                        <option value="preorder">Preorder</option>
                        <option value="made_to_order">Made to Order</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                <div>
                    <label for="fp-hpp" class="block raliva-label mb-2">HPP / Modal (Rp) <span class="text-error">*</span></label>
                    <div class="flex items-stretch">
                        <span class="inline-flex items-center px-4 text-sm font-bold text-on-surface-variant bg-surface-container-low border border-muted-border rounded-l-lg border-r-0 select-none">Rp</span>
                        <input id="fp-hpp" name="hpp" type="text" inputmode="numeric" data-rupiah-harga placeholder="650.000" required class="raliva-input" style="border-top-left-radius:0;border-bottom-left-radius:0;" />
                    </div>
                </div>
                <div>
                    <label for="fp-harga" class="block raliva-label mb-2">Harga Jual (Rp) <span class="text-error">*</span></label>
                    <div class="flex items-stretch">
                        <span class="inline-flex items-center px-4 text-sm font-bold text-on-surface-variant bg-surface-container-low border border-muted-border rounded-l-lg border-r-0 select-none">Rp</span>
                        <input id="fp-harga" name="harga_dasar" type="text" inputmode="numeric" data-rupiah-harga placeholder="949.000" required class="raliva-input" style="border-top-left-radius:0;border-bottom-left-radius:0;" />
                    </div>
                </div>
            </div>
            <div>
                <label for="fp-deskripsi" class="block raliva-label mb-2">Deskripsi <span class="text-error">*</span></label>
                <textarea id="fp-deskripsi" name="deskripsi" rows="3" required minlength="10" maxlength="2000" placeholder="Bahan, potongan, keunggulan produk... (min. 10 karakter)" class="raliva-textarea"></textarea>
            </div>
        </div>

        {{-- Variasi --}}
        <div class="space-y-4">
            <p class="text-xs font-medium text-gold-accent pt-2 border-t border-muted-border">Variasi &amp; Stok</p>
            <div>
                <p class="raliva-label mb-2">Ukuran @if(!empty($tokoKategori))<span class="text-xs font-normal text-on-surface-variant">(kategori toko: {{ $tokoKategori }})</span>@endif</p>
                <div class="flex flex-wrap gap-2" id="ukuran-chips">
                    @foreach (($ukuranOptions ?? ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size']) as $size)
                        <button type="button" class="ukuran-chip px-4 py-2 rounded-lg border border-muted-border text-xs font-medium text-on-surface hover:border-gold-accent transition-colors" data-size="{{ $size }}">{{ $size }}</button>
                    @endforeach
                    <button type="button" onclick="document.getElementById('custom-size-fields').classList.toggle('hidden')" class="px-4 py-2 rounded-lg border border-dashed border-gold-accent/40 text-gold-accent text-xs font-medium hover:bg-gold-accent/5 transition-colors">+ Custom</button>
                </div>
                <div id="custom-size-fields" class="hidden mt-3 p-3 border border-muted-border rounded-lg bg-surface-container-low space-y-2">
                    <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Ukuran Custom (isi yang relevan)</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <input type="text" inputmode="numeric" data-ribuan-int name="custom_ld" placeholder="Lingkar Dada (cm)" class="raliva-input text-sm" />
                        <input type="text" inputmode="numeric" data-ribuan-int name="custom_pb" placeholder="Panjang Baju (cm)" class="raliva-input text-sm" />
                        <input type="text" inputmode="numeric" data-ribuan-int name="custom_lb" placeholder="Lebar Bahu (cm)" class="raliva-input text-sm" />
                        <input type="text" inputmode="numeric" data-ribuan-int name="custom_lt" placeholder="Lingkar Tangan (cm)" class="raliva-input text-sm" />
                        <input type="text" inputmode="numeric" data-ribuan-int name="custom_pl" placeholder="Panjang Lengan (cm)" class="raliva-input text-sm" />
                    </div>
                    <button type="button" onclick="addCustomSize()" class="px-3 py-1.5 bg-deep-onyx text-on-primary text-xs rounded">Tambah Ukuran Custom</button>
                </div>
                <input type="hidden" name="ukuran_terpilih" id="ukuran-terpilih" />
            </div>
            <div>
                <p class="raliva-label mb-2">Warna <span class="text-xs font-normal text-on-surface-variant">(klik untuk pilih, bisa lebih dari satu)</span></p>
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-2" id="warna-presets">
                    @foreach ([['Navy', '#22304a'], ['Camel', '#c19a6b'], ['Putih', '#f5f3f3'], ['Merah', '#c62828'], ['Biru', '#2360a8'], ['Kuning', '#e6b91e'], ['Marun', '#7d2b33'], ['Hijau', '#2e7d32'], ['Emerald', '#046e4c'], ['Coral', '#f2875c'], ['Teal', '#0f766e'], ['Cream', '#f6ecd9'], ['Violet', '#7c3aed'], ['Sage', '#9caf88']] as $color)
                        <label class="warna-chip flex flex-col items-center gap-1 py-2 rounded-lg border border-muted-border cursor-pointer hover:border-gold-accent transition-colors has-[:checked]:bg-gold-accent/10 has-[:checked]:border-gold-accent" data-warna-value="{{ $color[0] }}" data-hex="{{ $color[1] }}">
                            <input type="checkbox" name="warna[]" value="{{ $color[0] }}" class="sr-only peer" />
                            <span class="w-7 h-7 rounded-full border border-outline-variant shadow-inner peer-checked:ring-2 peer-checked:ring-gold-accent peer-checked:ring-offset-2 ring-offset-surface-container-lowest transition-all" style="background-color: {{ $color[1] }};"></span>
                            <span class="font-body-md text-[10px] text-on-surface-variant peer-checked:text-gold-accent text-center leading-tight">{{ $color[0] }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="mt-3">
                    <button type="button" id="warna-custom-toggle" class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-dashed border-gold-accent/40 text-gold-accent text-xs font-medium hover:bg-gold-accent/5 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">palette</span> + Warna Sendiri
                    </button>
                    <div id="warna-custom-fields" class="hidden mt-3 p-3 border border-muted-border rounded-lg bg-surface-container-low space-y-3">
                        <div class="flex items-center gap-3 flex-wrap">
                            <label title="Pilih warna" class="relative w-10 h-10 rounded-full border border-muted-border shadow-inner cursor-pointer overflow-hidden shrink-0" id="warna-custom-preview" style="background-color:#1c1b1b;">
                                <input type="color" id="warna-custom-color" value="#1c1b1b" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" title="Pilih warna" />
                            </label>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="text-on-surface-variant font-bold text-sm">#</span>
                                <input type="text" id="warna-custom-hex" placeholder="f4f4f4" maxlength="6" autocomplete="off" spellcheck="false" class="raliva-input text-sm font-mono uppercase" style="width: 7.5rem;" title="Ketik kode warna hex, cth: f4f4f4" />
                            </div>
                            <input type="text" id="warna-custom-name" placeholder="Nama warna (wajib bila tambah warna) — cth: Tosca" maxlength="30" class="raliva-input text-sm flex-1" style="width:auto;min-width:10rem;" />
                            <button type="button" id="warna-custom-add" class="px-4 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium shrink-0">Tambah</button>
                        </div>
                        <div id="warna-custom-chips" class="flex flex-wrap gap-2"></div>
                    </div>
                </div>
            </div>
            <div>
                <p class="raliva-label mb-1">Stok per Varian</p>
                <p class="text-xs text-on-surface-variant mb-3">Setelah pilih ukuran, isi stok untuk setiap varian. Warna boleh dikosongkan.</p>
                <div id="varian-stok-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
                <div id="varian-stok-empty" class="mt-2 p-4 border border-dashed border-outline-variant rounded-lg text-center text-xs text-on-surface-variant">Belum ada varian. Pilih ukuran di atas untuk mengatur stok per varian.</div>
                <input type="hidden" name="stok_awal" id="fp-stok-synced" value="0" />
            </div>
        </div>

        <div class="sticky bottom-0 -mx-6 px-6 py-4 bg-surface-container-lowest border-t border-muted-border flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter">
            <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
            <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">check_circle</span>Simpan Produk
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.querySelectorAll('.ukuran-chip').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        btn.classList.toggle('bg-gold-accent');
        btn.classList.toggle('text-white');
        btn.classList.toggle('border-gold-accent');
        updateUkuranTerpilih();
        renderVarianStok();
    });
});

document.querySelectorAll('[name="warna[]"]').forEach(cb=>{
    cb.addEventListener('change', ()=>{
        renderVarianStok();
    });
});

function getSelectedUkuran() {
    return Array.from(document.querySelectorAll('.ukuran-chip.bg-gold-accent')).map(b=>b.dataset.size || b.textContent.trim());
}

function getSelectedWarna() {
    return Array.from(document.querySelectorAll('[name="warna[]"]:checked')).map(cb=>cb.value);
}

function renderVarianStok() {
    const ukuran = getSelectedUkuran();
    const warna = getSelectedWarna();
    const barisWarna = warna.length ? warna : [null];
    const grid = document.getElementById('varian-stok-grid');
    const empty = document.getElementById('varian-stok-empty');
    if (!grid || !empty) return;

    const count = ukuran.length * barisWarna.length;
    empty.style.display = count > 0 ? 'none' : 'block';
    grid.innerHTML = '';

    let i = 0;
    for (const uk of ukuran) {
        for (const wr of barisWarna) {
            const row = document.createElement('div');
            row.className = 'p-3 border border-muted-border rounded-lg bg-surface-container-low space-y-2';
            row.innerHTML = `
                <input type="hidden" name="varian_stok[${i}][ukuran]" value="${escapeHtml(uk)}" />
                <input type="hidden" name="varian_stok[${i}][warna]" value="${wr === null ? '' : escapeHtml(wr)}" />
                <div class="flex items-center gap-2">
                    ${wr === null ? '' : `<span class="w-4 h-4 rounded-full border border-outline-variant shrink-0 inline-block" style="background-color: ${warnaSwatch(wr)}"></span>`}
                    <span class="text-xs font-bold text-on-surface truncate">${escapeHtml(uk)}${wr === null ? '' : ` · ${escapeHtml(wr)}`}</span>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Stok</label>
                    <input type="text" inputmode="numeric" data-ribuan-int placeholder="10.000" name="varian_stok[${i}][stok]" value="0" class="raliva-input text-sm" />
                </div>
            `;
            grid.appendChild(row);
            i++;
        }
    }

    // sync hidden total (backward compat)
    const total = Array.from(grid.querySelectorAll('[name$="[stok]"]')).reduce((s, el)=> s + (window.parseRibuanInt(el.value)), 0);
    const syncTotal = document.getElementById('fp-stok-synced');
    if (syncTotal) syncTotal.value = total;
}

function escapeHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function warnaSwatch(name) {
    name = String(name || '').trim();
    window.__warnaPresetHex = window.__warnaPresetHex || @json(\App\Support\WarnaPalet::all());
    if (!window.__warnaPresetHex) window.__warnaPresetHex = {};
    if (window.__warnaCustomHex && window.__warnaCustomHex[name]) return window.__warnaCustomHex[name];
    const key = name.toLowerCase();
    const preset = Object.keys(window.__warnaPresetHex).find((label) => label.toLowerCase() === key);
    return preset ? window.__warnaPresetHex[preset] : '#cccccc';
}

function updateUkuranTerpilih() {
    const selected = getSelectedUkuran();
    document.getElementById('ukuran-terpilih').value = selected.join(',');
}

function addCustomSize() {
    const ld = window.parseRibuanInt(document.querySelector('[name="custom_ld"]').value);
    const pb = window.parseRibuanInt(document.querySelector('[name="custom_pb"]').value);
    const lb = window.parseRibuanInt(document.querySelector('[name="custom_lb"]').value);
    const lt = window.parseRibuanInt(document.querySelector('[name="custom_lt"]').value);
    const pl = window.parseRibuanInt(document.querySelector('[name="custom_pl"]').value);
    const parts = [];
    if (ld) parts.push('LD=' + ld);
    if (pb) parts.push('PB=' + pb);
    if (lb) parts.push('LB=' + lb);
    if (lt) parts.push('LT=' + lt);
    if (pl) parts.push('PL=' + pl);
    if (parts.length === 0) { alert('Isi minimal 1 ukuran custom.'); return; }
    const label = parts.join(' / ');
    const container = document.getElementById('ukuran-chips');
    const chip = document.createElement('button');
    chip.type = 'button';
    chip.className = 'ukuran-chip px-4 py-2 rounded-lg border border-gold-accent bg-gold-accent text-white text-xs font-medium';
    chip.dataset.size = label;
    chip.textContent = label;
    chip.addEventListener('click', function() {
        this.remove();
        updateUkuranTerpilih();
        renderVarianStok();
    });
    container.insertBefore(chip, container.lastElementChild);
    updateUkuranTerpilih();
    renderVarianStok();
    // Reset inputs
    document.querySelectorAll('[name^="custom_"]').forEach(i => i.value = '');
}

// --- Combobox Kategori (searchable + inline create), dipakai tambah (fp) & edit ---
function initKategoriCombobox(prefix) {
    const box = document.getElementById(prefix + '-kategori-box');
    const btn = document.getElementById(prefix + '-kategori-btn');
    const menu = document.getElementById(prefix + '-kategori-menu');
    const label = document.getElementById(prefix + '-kategori-label');
    const hidden = document.getElementById(prefix + '-kategori-hidden');
    const search = document.getElementById(prefix + '-kategori-search');
    const list = document.getElementById(prefix + '-kategori-list');
    const inline = document.getElementById(prefix + '-kategori-inline');
    const inlineNama = document.getElementById(prefix + '-kategori-inline-nama');
    const inlineSimpan = document.getElementById(prefix + '-kategori-inline-simpan');
    const inlineBatal = document.getElementById(prefix + '-kategori-inline-batal');
    const addBtn = document.getElementById(prefix + '-kategori-add');
    if (!box || !btn || !menu || !hidden || !list || box.dataset.ktgInit) return;
    box.dataset.ktgInit = '1';

    const openMenu = () => {
        menu.classList.remove('hidden');
        btn.setAttribute('aria-expanded', 'true');
        requestAnimationFrame(() => search?.focus());
    };
    const closeMenu = () => {
        menu.classList.add('hidden');
        inline.classList.add('hidden');
        btn.setAttribute('aria-expanded', 'false');
    };
    const selectCategory = (id, name) => {
        hidden.value = id;
        label.textContent = name;
        label.classList.remove('text-on-surface-variant');
        label.classList.add('text-on-surface');
        closeMenu();
    };
    box._ktgSelect = selectCategory;

    const isOpen = () => !menu.classList.contains('hidden');
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (isOpen()) { closeMenu(); } else { openMenu(); }
    });
    document.addEventListener('click', (e) => {
        if (!box.contains(e.target) && !menu.contains(e.target)) closeMenu();
    });
    search.addEventListener('keydown', (e) => { if (e.key === 'Enter') e.preventDefault(); });
    search.addEventListener('input', () => {
        const q = search.value.trim().toLowerCase();
        let visible = 0;
        list.querySelectorAll('[data-category-id]').forEach((li) => {
            const show = li.getAttribute('data-category-name').toLowerCase().includes(q);
            li.classList.toggle('hidden', !show);
            if (show) visible++;
        });
        list.querySelector('[data-category-empty]')?.remove();
        if (!visible) {
            const empty = document.createElement('li');
            empty.setAttribute('data-category-empty', '');
            empty.className = 'px-4 py-6 text-center text-xs text-on-surface-variant';
            empty.textContent = 'Kategori "' + search.value + '" tidak ditemukan.';
            list.appendChild(empty);
        }
    });
    list.querySelectorAll('[data-category-id]').forEach((li) => {
        li.addEventListener('click', () => selectCategory(li.getAttribute('data-category-id'), li.getAttribute('data-category-name')));
    });
    addBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        inline.classList.remove('hidden');
        inlineNama.value = '';
        requestAnimationFrame(() => inlineNama.focus());
    });
    inlineBatal.addEventListener('click', () => inline.classList.add('hidden'));

    const saveCategory = () => {
        const nama = inlineNama.value.trim();
        if (!nama) { window.showRalivaToast('Nama kategori wajib diisi.', 'gpp_bad'); return; }
        const dup = Array.from(list.querySelectorAll('[data-category-id]')).some(
            (li) => li.getAttribute('data-category-name').toLowerCase() === nama.toLowerCase()
        );
        if (dup) {
            Array.from(list.querySelectorAll('[data-category-id]')).forEach((li) => {
                if (li.getAttribute('data-category-name').toLowerCase() === nama.toLowerCase()) selectCategory(li.getAttribute('data-category-id'), nama);
            });
            inline.classList.add('hidden');
            return;
        }
        inlineSimpan.disabled = true;
        const data = new FormData();
        data.append('_token', document.querySelector('input[name="_token"]')?.value || '');
        data.append('nama_kategori', nama);
        fetch('{{ route('admin.kategori.store') }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: data
        })
        .then(r => r.json().then(j => ({ ok: r.ok, j })))
        .then(({ ok, j }) => {
            if (!ok || !j.success) throw new Error(j.message || 'Gagal menyimpan kategori.');
            document.querySelectorAll('[data-ktg-list]').forEach((otherList) => {
                const li = document.createElement('li');
                li.setAttribute('role', 'option');
                li.dataset.categoryId = j.kategori.category_id;
                li.dataset.categoryName = j.kategori.nama_kategori;
                li.className = 'px-4 py-2.5 text-sm cursor-pointer hover:bg-surface-container-low transition-colors text-on-surface';
                li.textContent = j.kategori.nama_kategori;
                li.addEventListener('click', () => {
                    const otherBox = otherList.closest('[data-ktg-box]');
                    if (otherBox && otherBox._ktgSelect) otherBox._ktgSelect(j.kategori.category_id, j.kategori.nama_kategori);
                });
                otherList.appendChild(li);
            });
            selectCategory(j.kategori.category_id, j.kategori.nama_kategori);
            window.showRalivaToast('Kategori "' + j.kategori.nama_kategori + '" berhasil ditambahkan.', 'task_alt');
        })
        .catch(err => window.showRalivaToast(err.message || 'Terjadi kesalahan.', 'gpp_bad'))
        .finally(() => { inlineSimpan.disabled = false; });
    };
    inlineSimpan.addEventListener('click', saveCategory);
    inlineNama.addEventListener('keydown', (e) => { if (e.key === 'Enter') { e.preventDefault(); saveCategory(); } });
}

window.setKategoriCombobox = (prefix, id, name) => {
    const box = document.getElementById(prefix + '-kategori-box');
    if (box && box._ktgSelect && id) box._ktgSelect(id, name || id);
    else {
        const hidden = document.getElementById(prefix + '-kategori-hidden');
        const label = document.getElementById(prefix + '-kategori-label');
        if (hidden) hidden.value = id || '';
        if (label && name) {
            label.textContent = name;
            label.classList.remove('text-on-surface-variant');
            label.classList.add('text-on-surface');
        }
    }
};

initKategoriCombobox('fp');
initKategoriCombobox('edit');

// --- Warna Custom (pilih visual, nama opsional) ---
(function () {
    const toggleBtn = document.getElementById('warna-custom-toggle');
    const fields = document.getElementById('warna-custom-fields');
    const colorInput = document.getElementById('warna-custom-color');
    const hexInput = document.getElementById('warna-custom-hex');
    const preview = document.getElementById('warna-custom-preview');
    const nameInput = document.getElementById('warna-custom-name');
    const addBtn = document.getElementById('warna-custom-add');
    const chips = document.getElementById('warna-custom-chips');
    if (!toggleBtn || !fields || !colorInput || !preview || !nameInput || !addBtn || !chips) return;

    window.__warnaCustomHex = window.__warnaCustomHex || {};

    const normHex = (raw) => {
        const h = String(raw || '').trim().replace(/^#/, '').toLowerCase();
        return /^[0-9a-f]{6}$/.test(h) ? '#' + h : '';
    };

    toggleBtn.addEventListener('click', () => {
        fields.classList.toggle('hidden');
        if (!fields.classList.contains('hidden')) requestAnimationFrame(() => colorInput.click());
    });

    colorInput.addEventListener('input', () => {
        preview.style.background = colorInput.value;
        if (hexInput) hexInput.value = colorInput.value.replace('#', '');
    });

    hexInput?.addEventListener('input', () => {
        const valid = normHex(hexInput.value);
        if (valid) {
            colorInput.value = valid;
            preview.style.background = valid;
            hexInput.classList.remove('border-error');
        } else if (hexInput.value.trim() !== '') {
            hexInput.classList.add('border-error');
        } else {
            hexInput.classList.remove('border-error');
        }
    });

    if (hexInput && !hexInput.value) hexInput.value = colorInput.value.replace('#', '');

    const addCustomWarna = () => {
        const nama = (nameInput.value || '').trim();
        const hex = normHex(hexInput?.value || '');
        if (nama.length < 2 || /^warna\s*\d+$/i.test(nama)) {
            window.showRalivaToast('Nama warna custom wajib diisi minimal 2 karakter.', 'gpp_bad');
            nameInput.focus();
            return;
        }
        const exists = Array.from(document.querySelectorAll('[name="warna[]"]')).some((cb) => cb.value.toLowerCase() === nama.toLowerCase());
        if (exists) { window.showRalivaToast('Warna "' + nama + '" sudah ada.', 'gpp_bad'); return; }
        if (!hex) {
            window.showRalivaToast('Kode hex warna custom wajib valid. Contoh: f4f4f4.', 'gpp_bad');
            hexInput.focus();
            return;
        }

        colorInput.value = hex;
        window.__warnaCustomHex[nama] = hex;

        const labelEl = document.createElement('label');
        labelEl.className = 'warna-chip flex items-center gap-2 py-1.5 pr-2 pl-2 rounded-lg border border-muted-border cursor-pointer hover:border-gold-accent transition-colors has-[:checked]:bg-gold-accent/10 has-[:checked]:border-gold-accent';
        labelEl.innerHTML = `
            <input type="checkbox" name="warna[]" value="${escapeHtml(nama)}" class="sr-only peer" checked />
            <span class="w-6 h-6 rounded-full border border-outline-variant shadow-inner peer-checked:ring-2 peer-checked:ring-gold-accent peer-checked:ring-offset-1 ring-offset-surface-container-lowest transition-all" style="background-color: ${colorInput.value};"></span>
            <span class="font-body-md text-xs text-on-surface peer-checked:text-gold-accent">${escapeHtml(nama)}</span>
            <button type="button" class="text-on-surface-variant hover:text-error transition-colors" title="Hapus warna" onclick="(function(el){var cb=el.closest('label').querySelector('input[type=checkbox]'); if(cb&&window.__warnaCustomHex)delete window.__warnaCustomHex[cb.value]; el.closest('label').remove(); renderVarianStok();})(this)">
                <span class="material-symbols-outlined text-[14px]">close</span>
            </button>
        `;
        labelEl.querySelector('input').addEventListener('change', renderVarianStok);
        chips.appendChild(labelEl);
        nameInput.value = '';
        renderVarianStok();
        window.showRalivaToast('Warna custom "' + nama + '" ditambahkan.', 'task_alt');
    };

    addBtn.addEventListener('click', addCustomWarna);
    nameInput.addEventListener('keydown', (e) => { if (e.key === 'Enter') { e.preventDefault(); addCustomWarna(); } });
})();

// --- Preview foto instan + counter (maks 5) ---
function previewFotoSlot(input) {
    const slot = input.closest('[data-foto-slot]');
    if (!slot) return;
    const img = slot.querySelector('[data-foto-preview]');
    const icon = slot.querySelector('[data-foto-icon]');
    const label = slot.querySelector('[data-foto-label]');
    const hapus = slot.querySelector('[data-foto-hapus]');
    if (input.files && input.files[0]) {
        if (img.dataset.url) URL.revokeObjectURL(img.dataset.url);
        const url = URL.createObjectURL(input.files[0]);
        img.dataset.url = url;
        img.src = url;
        img.classList.remove('hidden');
        icon.classList.add('hidden');
        label.classList.add('hidden');
        hapus.classList.remove('hidden');
        hapus.classList.add('flex');
        const check = slot.querySelector('[data-foto-check]');
        check?.classList.remove('hidden');
        check?.classList.add('flex');
        slot.classList.add('border-gold-accent');
    }
    updateFotoCount();
    if (input.closest('#edit-foto-slot-grid') && typeof window.__editFotoRefresh === 'function') window.__editFotoRefresh();
}

function hapusFotoSlot(e, btn) {
    e.preventDefault();
    e.stopPropagation();
    const slot = btn.closest('[data-foto-slot]');
    if (!slot) return;
    const input = slot.querySelector('[data-foto-input]');
    const img = slot.querySelector('[data-foto-preview]');
    if (img.dataset.url) { URL.revokeObjectURL(img.dataset.url); delete img.dataset.url; }
    input.value = '';
    img.removeAttribute('src');
    img.classList.add('hidden');
    slot.querySelector('[data-foto-icon]').classList.remove('hidden');
    slot.querySelector('[data-foto-label]').classList.remove('hidden');
    btn.classList.add('hidden');
    btn.classList.remove('flex');
    const check = slot.querySelector('[data-foto-check]');
    check?.classList.add('hidden');
    check?.classList.remove('flex');
    slot.classList.remove('border-gold-accent');
    updateFotoCount();
    if (slot.closest('#edit-foto-slot-grid') && typeof window.__editFotoRefresh === 'function') window.__editFotoRefresh();
}

function updateFotoCount() {
    const n = Array.from(document.querySelectorAll('#foto-slot-grid [data-foto-input]')).filter(i => i.files && i.files.length > 0).length;
    const el = document.getElementById('foto-count');
    if (el) el.textContent = n;
}

function parseRibuanInt(raw) {
    const digits = String(raw ?? '').replace(/\D/g, '').slice(0, 12).replace(/^0+(?=\d)/, '');
    return digits === '' ? 0 : parseInt(digits, 10);
}

function parseRibuanDecimal(raw) {
    const text = String(raw ?? '').trim().replace(/\s/g, '');
    if (!text) return 0;
    const normalized = text.replace(/\./g, '').replace(',', '.');
    const value = Number(normalized);
    return Number.isFinite(value) ? value : 0;
}

// --- Format ribuan/Rp live (10000 -> 10.000) + normalisasi saat submit ---
(function () {
    const fmtRp = (el) => {
        const digits = el.value.replace(/\D/g, '').slice(0, 12).replace(/^0+(?=\d)/, '');
        el.value = digits ? new Intl.NumberFormat('id-ID').format(digits) : '';
    };
    const fmtRibuanInt = (el) => {
        const digits = el.value.replace(/\D/g, '').slice(0, 12).replace(/^0+(?=\d)/, '');
        el.value = digits ? new Intl.NumberFormat('id-ID').format(digits) : '';
    };
    const fmtRibuanDecimal = (el) => {
        const text = el.value.replace(/[^0-9.,]/g, '');
        const parts = text.split(',');
        const digits = parts[0].replace(/\D/g, '').slice(0, 12).replace(/^0+(?=\d)/, '');
        const decimals = (parts[1] || '').replace(/\D/g, '').slice(0, 3);
        el.value = digits ? new Intl.NumberFormat('id-ID').format(digits) + (parts.length > 1 ? ',' + decimals : '') : '';
    };
    document.addEventListener('input', (event) => {
        const intTarget = event.target.closest?.('[data-ribuan-int]');
        if (intTarget) { fmtRibuanInt(intTarget); return; }
        const decimalTarget = event.target.closest?.('[data-ribuan-decimal]');
        if (decimalTarget) { fmtRibuanDecimal(decimalTarget); return; }
        const rpTarget = event.target.closest?.('[data-rupiah], [data-rupiah-harga]');
        if (rpTarget) fmtRp(rpTarget);
    });
    // Strip saat submit TANPA merusak tampilan: nilai polos dikirim via hidden clone,
    // input tampil tetap berformat (disabled + tanpa name agar tidak ikut terkirim).
    const stripForSubmit = (form) => {
        form.querySelectorAll('[data-rupiah-harga], #edit-harga-dasar, [data-rupiah], [data-ribuan-int]').forEach((el) => {
            if (!el.name || el.dataset.stripped === '1') return;
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = el.name;
            hidden.value = el.value.replace(/\./g, '');
            hidden.setAttribute('data-strip-clone', '');
            el.setAttribute('data-orig-name', el.name);
            el.removeAttribute('name');
            el.disabled = true;
            el.dataset.stripped = '1';
            el.after(hidden);
        });
        form.querySelectorAll('[data-ribuan-decimal]').forEach((el) => {
            if (!el.name || el.dataset.stripped === '1') return;
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = el.name;
            hidden.value = String(el.value ?? '').replace(/\./g, '').replace(',', '.');
            hidden.setAttribute('data-strip-clone', '');
            el.setAttribute('data-orig-name', el.name);
            el.removeAttribute('name');
            el.disabled = true;
            el.dataset.stripped = '1';
            el.after(hidden);
        });
    };
    const restoreStripped = (form) => {
        form.querySelectorAll('[data-strip-clone]').forEach((h) => h.remove());
        form.querySelectorAll('[data-stripped="1"]').forEach((el) => {
            el.name = el.getAttribute('data-orig-name') || el.name;
            el.removeAttribute('data-orig-name');
            el.disabled = false;
            delete el.dataset.stripped;
        });
        if (window.__reformatRp) window.__reformatRp(form);
    };
    window.__restoreStripped = restoreStripped;
    ['form-produk', 'form-edit-produk'].forEach((id) => {
        document.getElementById(id)?.addEventListener('submit', function () {
            stripForSubmit(this);
        });
    });
    window.__fmtRpHarga = fmtRp;
    window.__reformatRp = (scope) => {
        const root = scope || document;
        root.querySelectorAll('[data-rupiah], [data-rupiah-harga]').forEach(fmtRp);
        root.querySelectorAll('[data-ribuan-int]').forEach(fmtRibuanInt);
        root.querySelectorAll('[data-ribuan-decimal]').forEach(fmtRibuanDecimal);
    };
    window.__reformatRp();
    window.parseRibuanInt = parseRibuanInt;
    window.parseRibuanDecimal = parseRibuanDecimal;
})();

// --- Validasi wajib-isi form tambah (semua harus diinput dulu) ---
(function () {
    const form = document.getElementById('form-produk');
    if (!form) return;
    form.addEventListener('submit', (e) => {
        const fail = (msg, target) => {
            e.preventDefault();
            if (window.__restoreStripped) window.__restoreStripped(form);
            window.showRalivaToast(msg, 'gpp_bad');
            target?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        };
        const fotoTerisi = Array.from(form.querySelectorAll('input[name="foto_produk[]"]')).some(i => i.files && i.files.length > 0);
        if (!fotoTerisi) return fail('Wajib: unggah minimal 1 foto produk.', form.querySelector('input[name="foto_produk[]"]'));
        if (!document.getElementById('fp-kategori-hidden')?.value) return fail('Wajib: pilih kategori produk.', document.getElementById('fp-kategori-box'));
        if (getSelectedUkuran().length === 0) return fail('Wajib: pilih minimal 1 ukuran.', document.getElementById('ukuran-chips'));
        const rows = Array.from(document.querySelectorAll('#varian-stok-grid [name$="[stok]"]'));
        if (!rows.length) return fail('Wajib: isi stok tiap varian (pilih ukuran dulu).', document.getElementById('varian-stok-empty'));
        const kosong = rows.find(i => i.value === '' || window.parseRibuanInt(i.value) < 1);
        if (kosong) return fail('Wajib: stok tiap varian minimal 1.', kosong);
    });
})();

// --- Sinkronkan hex warna custom/preset ke hidden warna_hex[] saat submit ---
(function () {
    const form = document.getElementById('form-produk');
    if (!form) return;
    form.addEventListener('submit', () => {
        form.querySelectorAll('input[name="warna_hex[]"]').forEach((h) => h.remove());
        const hexOf = (name) => {
            if (window.__warnaCustomHex && window.__warnaCustomHex[name]) return window.__warnaCustomHex[name];
            window.__warnaPresetHex = window.__warnaPresetHex || @json(\App\Support\WarnaPalet::all());
            return (window.__warnaPresetHex && window.__warnaPresetHex[name]) || '';
        };
        form.querySelectorAll('input[name="warna[]"]:checked').forEach((cb) => {
            const h = document.createElement('input');
            h.type = 'hidden';
            h.name = 'warna_hex[]';
            h.value = cb.closest('label')?.getAttribute('data-hex') || hexOf(cb.value);
            form.appendChild(h);
        });
    });
})();


// Modal open/close + scroll-lock ditangani terpusat di partials/ui-scripts (ralivaOpenModal).
</script>
@endpush
@endsection
