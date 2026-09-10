@extends('layouts.owner')

@section('title', 'Data Produk')

@section('header-title', 'Data Produk')
@section('header-badge', $counts['total'] . ' Produk')
@section('header-subtitle', 'Kelola produk, harga, variasi, dan stok toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
<p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
                </div>
            </div>
        @endif
    @if (($counts['pending'] ?? 0) > 0)
        <a href="{{ route('owner.moderasi-produk') }}" class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-center gap-3 hover:border-gold-accent/60 transition-colors">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">hourglass_top</span>
            <p class="text-sm text-on-surface"><span class="font-bold">{{ $counts['pending'] }} produk</span> menunggu verifikasi Owner. <span class="underline text-gold-accent font-semibold">Ke Moderasi Produk</span></p>
        </a>
    @endif
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produk</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $counts['total'] }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">checkroom</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Produk Aktif</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $counts['aktif'] }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">check_circle</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Produk Nonaktif</span>
            <span class="raliva-figure text-[26px] text-error">{{ $counts['nonaktif'] }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">visibility_off</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Varian</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $counts['varian'] }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">tune</span>
        </div>
    </section>

    {{-- Toolbar & Tabel --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Produk</h2>
                <p class="text-xs text-on-surface-variant mt-1">Kelola seluruh produk, variasi, dan stok toko Anda.</p>
            </div>
        </div>

        {{-- Toolbar: search kiri, filter kanan — realtime JS (debounce) + fallback server GET --}}
        <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
            <div class="relative flex-1 min-w-[220px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari nama produk atau SKU..." data-table-search value="{{ request('q') }}" class="raliva-search" />
            </div>
            <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                <select data-table-filter="kategori" class="raliva-select lg:w-44">
                    <option value="">Semua Kategori</option>
                    @foreach(($categories ?? collect()) as $catName)
                        <option value="{{ $catName }}" {{ request('kategori')===$catName ? 'selected' : '' }}>{{ $catName }}</option>
                    @endforeach
                </select>
                <select data-table-filter="status-produk" class="raliva-select lg:w-44">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status-produk')==='aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="pending" {{ request('status-produk')==='pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="ditolak" {{ request('status-produk')==='ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="nonaktif" {{ request('status-produk')==='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="draft" {{ request('status-produk')==='draft' ? 'selected' : '' }}>Draft</option>
                    <option value="arsip" {{ request('status-produk')==='arsip' ? 'selected' : '' }}>Arsip</option>
                </select>
                <button type="button" data-filter-reset class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</button>
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto min-h-[380px]">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kategori</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Harga</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Stok</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Terjual</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
<tbody>
                                    @php
                                        $statusOptions = [
                                            'aktif' => ['aktif', 'nonaktif'],
                                            'nonaktif' => ['nonaktif', 'aktif'],
                                            'draft' => ['draft', 'pending'],
                                        ];
                                    @endphp
                                    @forelse ($products as $p)
                        <tr data-table-row data-kategori="{{ $p->category?->nama_kategori }}" data-status-produk="{{ $p->status }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-14 rounded-md bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 overflow-hidden">
                                        <span class="material-symbols-outlined text-[22px] text-on-surface-variant">checkroom</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-on-surface truncate">{{ $p->nama_produk }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $p->variants->first()?->sku ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $p->category?->nama_kategori ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">{{ 'Rp ' . number_format($p->harga_dasar, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center text-on-surface">{{ $p->variants->count() }} <span class="text-xs text-on-surface-variant">varian</span></td>
                            <td class="py-3.5 px-4">
                                <p class="text-on-surface">{{ $p->terjual }} pcs</p>
                                <p class="text-xs text-on-surface-variant">{{ $p->variants->map(fn($v) => trim(($v->ukuran ?? '').' '.($v->warna ?? '')))->filter()->implode(', ') ?: '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if (in_array($p->status, ['aktif', 'nonaktif', 'draft'], true))
                                    <form method="POST" action="{{ route('owner.produk.status', $p) }}" class="inline">
                                        @csrf
                                        <select name="status" data-status-select data-current="{{ $p->status }}" title="Ubah status" class="cursor-pointer text-[10px] font-bold uppercase border rounded-full pl-2 pr-6 py-1 {{ $p->status === 'aktif' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : ($p->status === 'nonaktif' ? 'bg-surface-container-high text-on-surface-variant border-outline-variant' : 'bg-gold-accent/15 text-gold-accent border-gold-accent/30') }}">
                                            @foreach (($statusOptions[$p->status] ?? [$p->status]) as $opt)
                                                <option value="{{ $opt }}" @selected($opt === $p->status)>{{ ucfirst($opt) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                @elseif ($p->status === 'pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Menunggu</span>
                                @elseif ($p->status === 'ditolak')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Ditolak</span>
                                @elseif ($p->status === 'habis')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Habis</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ ucfirst($p->status) }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" data-modal-open="modal-produk-{{ $p->product_id }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap"><span class="material-symbols-outlined text-[16px]">visibility</span>Detail</button>
                                    @if (in_array($p->status, ['pending', 'ditolak', 'draft'], true))
                                        <button type="button" data-modal-open="modal-edit-produk-{{ $p->product_id }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap"><span class="material-symbols-outlined text-[16px]">edit</span>Edit</button>
                                    @endif
                                    <button type="button" data-modal-open="modal-hapus-produk-{{ $p->product_id }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-error/10 border border-error/20 rounded-lg text-xs font-semibold text-error hover:bg-error/20 transition-colors whitespace-nowrap"><span class="material-symbols-outlined text-[16px]">delete</span>Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-on-surface-variant">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center justify-center py-12 min-h-[260px] text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <div>
                <p class="font-title-md text-title-md text-on-surface">Produk tidak ditemukan</p>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Coba ubah kata kunci atau filter pencarian Anda.</p>
            </div>
            <button type="button" data-filter-reset class="mt-2 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <div data-pagination class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 mt-2 border-t border-muted-border">
            <p class="text-xs text-on-surface-variant">
                Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
            </p>
            <div class="flex items-center gap-1">
                @if ($products->onFirstPage())
                    <span class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant opacity-50 cursor-not-allowed"><span class="material-symbols-outlined text-[18px]">chevron_left</span></span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface hover:border-gold-accent transition-colors"><span class="material-symbols-outlined text-[18px]">chevron_left</span></a>
                @endif
                <span class="px-3 py-1.5 text-xs font-medium text-on-surface-variant whitespace-nowrap">Halaman {{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface hover:border-gold-accent transition-colors"><span class="material-symbols-outlined text-[18px]">chevron_right</span></a>
                @else
                    <span class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant opacity-50 cursor-not-allowed"><span class="material-symbols-outlined text-[18px]">chevron_right</span></span>
                @endif
            </div>
        </div>
    </section>
</div>

{{-- Modal Detail Produk per produk --}}
@foreach ($products as $p)
<div id="modal-produk-{{ $p->product_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Detail Produk</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $p->nama_produk }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ $p->category?->nama_kategori ?? '-' }} • SKU {{ $p->variants->first()?->sku ?? '-' }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-surface-container-low rounded-lg p-3">
                    <p class="text-[10px] uppercase text-on-surface-variant">Harga Dasar</p>
                    <p class="font-bold text-on-surface">Rp {{ number_format($p->harga_dasar, 0, ',', '.') }}</p>
                </div>
                <div class="bg-surface-container-low rounded-lg p-3">
                    <p class="text-[10px] uppercase text-on-surface-variant">Status</p>
                    <p class="font-bold text-on-surface capitalize">{{ $p->status }}</p>
                </div>
                <div class="bg-surface-container-low rounded-lg p-3">
                    <p class="text-[10px] uppercase text-on-surface-variant">Jumlah Varian</p>
                    <p class="font-bold text-on-surface">{{ $p->variants->count() }}</p>
                </div>
                <div class="bg-surface-container-low rounded-lg p-3">
                    <p class="text-[10px] uppercase text-on-surface-variant">Terjual</p>
                    <p class="font-bold text-on-surface">{{ $sold[$p->product_id] ?? 0 }}</p>
                </div>
            </div>
            <div>
                <p class="text-[10px] uppercase text-on-surface-variant mb-2">Varian (ukuran • warna • stok)</p>
                <ul class="space-y-1.5">
                    @foreach ($p->variants as $v)
                    <li class="flex items-center justify-between text-sm bg-surface-container-low rounded-lg px-3 py-2">
                        <span class="text-on-surface">{{ trim(($v->ukuran ?? '').' '.($v->warna ?? '')) ?: '-' }}</span>
                        <span class="text-on-surface-variant">SKU {{ $v->sku ?? '-' }} • stok {{ $v->stok ?? 0 }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>
@endforeach

{{-- Modal Edit Produk per produk --}}
@foreach ($products as $p)
<div id="modal-edit-produk-{{ $p->product_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Edit Produk</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $p->nama_produk }}</h3>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('owner.produk.update', $p) }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit-nama-{{ $p->product_id }}" class="block raliva-label mb-2">Nama Produk</label>
                <input id="edit-nama-{{ $p->product_id }}" name="nama_produk" type="text" value="{{ old('nama_produk', $p->nama_produk) }}" required class="raliva-input" />
                @error('nama_produk') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-harga-{{ $p->product_id }}" class="block raliva-label mb-2">Harga Dasar</label>
                    <input id="edit-harga-{{ $p->product_id }}" name="harga_dasar" type="number" min="0" value="{{ old('harga_dasar', $p->harga_dasar) }}" required class="raliva-input" />
                    @error('harga_dasar') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="edit-tipe-{{ $p->product_id }}" class="block raliva-label mb-2">Tipe</label>
                    <select id="edit-tipe-{{ $p->product_id }}" name="tipe_produk" class="raliva-select">
                        @foreach (['regular' => 'Regular', 'preorder' => 'Preorder', 'made_to_order' => 'Made to Order'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('tipe_produk', $p->tipe_produk) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="edit-kategori-{{ $p->product_id }}" class="block raliva-label mb-2">Kategori</label>
                <select id="edit-kategori-{{ $p->product_id }}" name="category_id" class="raliva-select">
                    <option value="">— Tanpa Kategori —</option>
                    @foreach (($categoryOptions ?? collect()) as $id => $nama)
                        <option value="{{ $id }}" @selected((string) old('category_id', $p->category_id) === (string) $id)>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="edit-deskripsi-{{ $p->product_id }}" class="block raliva-label mb-2">Deskripsi</label>
                <textarea id="edit-deskripsi-{{ $p->product_id }}" name="deskripsi" rows="3" class="raliva-textarea">{{ old('deskripsi', $p->deskripsi) }}</textarea>
            </div>
            @if ($p->images->isNotEmpty())
                <div>
                    <p class="raliva-label mb-2">Foto Saat Ini</p>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach ($p->images as $img)
                            <div class="h-20 rounded-lg overflow-hidden border border-outline-variant bg-surface-container-high">
                                <img src="{{ asset('storage/' . $img->file_gambar) }}" alt="Foto produk" class="w-full h-full object-cover" loading="lazy" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div>
                <label for="edit-foto-{{ $p->product_id }}" class="block raliva-label mb-2">Tambah Foto (Opsional)</label>
                <input id="edit-foto-{{ $p->product_id }}" type="file" name="foto_produk[]" accept=".jpg,.jpeg,.png,.webp" multiple class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:cursor-pointer" />
                <p class="text-[11px] text-on-surface-variant mt-1">JPG / PNG / WEBP, maks 2 MB per file.</p>
                @error('foto_produk.*') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" data-modal-close class="flex-1 py-3 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- Modal Hapus Produk per produk --}}
@foreach ($products as $p)
<div id="modal-hapus-produk-{{ $p->product_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6 text-center">
        <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-error text-[28px]">delete</span>
        </div>
        <h3 class="font-title-md text-title-md text-on-surface">Hapus Produk</h3>
        <p class="text-on-surface-variant text-sm mt-2">Hapus <span class="font-bold text-on-surface">{{ $p->nama_produk }}</span> beserta varian &amp; fotonya? Tindakan ini tidak dapat dibatalkan.</p>
        <form method="POST" action="{{ route('owner.produk.destroy', $p) }}" class="flex gap-3 mt-6">
            @csrf
            @method('DELETE')
            <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
            <button type="submit" class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error text-sm font-semibold rounded-lg hover:bg-error/20 transition-colors">Ya, Hapus</button>
        </form>
    </div>
</div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  if (!document.querySelector('[data-real]')) return;
  // Check if no store banner exists (means no store)
  const noStore = document.querySelector('[data-no-store-banner]');
  if (!noStore) return;
  // Disable all primary action buttons except Ajukan Toko
  document.querySelectorAll('[data-modal-open], button[type="submit"], a[href*="pengajuan-toko"]:not([href*="ajukan"])').forEach(el=>{
    // Keep Ajukan Toko enabled
    if (el.textContent.includes('Ajukan Toko') || el.getAttribute('data-modal-open')?.includes('modal-tambah')) {
      // For tambah buttons, disable if no store
      el.setAttribute('disabled','');
      el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
      el.title = 'Ajukan toko dulu';
    }
  });
  // More generic: disable all buttons in data-real except those inside pengajuan
  document.querySelectorAll('[data-real] button, [data-real] a.btn-premium').forEach(el=>{
    if (el.closest('[data-modal]')) return;
    if (el.textContent.trim().includes('Ajukan')) return;
    el.setAttribute('disabled','');
    el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
  });
});
</script>
@endpush

@endsection

@push('styles')
<style>
    .ukuran-chip.selected { background: #111; color: #fff; border-color: #111; }
    .dark .ukuran-chip.selected { background: #f0eeee; color: #111; border-color: #f0eeee; }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.ukuran-chip').forEach((chip) => {
        chip.addEventListener('click', () => chip.classList.toggle('selected'));
    });
    document.querySelectorAll('[data-status-select]').forEach((sel) => {
        sel.addEventListener('change', () => {
            const label = sel.options[sel.selectedIndex]?.text || sel.value;
            if (confirm('Ubah status menjadi ' + label + '?')) {
                sel.closest('form').submit();
            } else {
                sel.value = sel.dataset.current;
            }
        });
    });
</script>
@endpush
