@extends('layouts.admin')

@section('title', 'Data Produk')
@section('header-title', 'Data Produk')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Kelola produk sesuai permission yang diberikan Owner.')

@section('content')
<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Akses terbatas: kamu hanya dapat melihat detail produk. Menambah produk akan diajukan dan menunggu persetujuan Owner (status <b>pending</b>).</p>
    </div>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Katalog Produk Toko</h2>
            <div class="flex items-center gap-3">
                <form method="GET" class="relative w-full md:w-56">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="raliva-search" />
                </form>
                <button type="button" data-modal-open="modal-tambah-produk" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                    <span class="material-symbols-outlined text-[18px]">add</span> Tambah
                </button>
            </div>
        </div>

        @if ($products->isEmpty())
            <p class="text-on-surface-variant text-sm py-10 text-center">Tidak ada produk ditemukan.</p>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            @foreach ($products as $p)
            <div class="border border-muted-border rounded-lg overflow-hidden card-premium">
                <div class="aspect-[4/3] bg-surface-container-low overflow-hidden">
                    @if ($p->gambar_utama)
                        <img class="w-full h-full object-cover" alt="{{ $p->nama_produk }}" src="{{ asset('storage/'.$p->gambar_utama) }}" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined text-[40px]">inventory_2</span></div>
                    @endif
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-title-md text-title-md text-on-surface leading-tight">{{ $p->nama_produk }}</h3>
                        <span class="px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase rounded-full border border-outline-variant shrink-0">{{ $p->status }}</span>
                    </div>
                    <p class="font-body-md text-body-md text-gold-accent mt-2">Rp {{ number_format($p->harga_dasar, 0, ',', '.') }}</p>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-muted-border">
                        <span class="text-on-surface-variant text-xs">{{ $p->category?->nama_kategori ?? '-' }}</span>
                        <button type="button" data-modal-open="modal-produk-{{ $p->product_id }}" class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Detail</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $products->links() }}</div>
        @endif
    </section>
</div>

{{-- Modal detail tiap produk (dipisah dari grid agar id unik & tidak bentrok) --}}
@foreach ($products as $p)
<div id="modal-produk-{{ $p->product_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Detail Produk</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $p->nama_produk }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ $p->category?->nama_kategori ?? '-' }} • {{ $p->store?->nama_toko ?? '-' }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-surface-container-low rounded-lg p-4">
                <p class="text-[10px] uppercase text-on-surface-variant mb-1">Deskripsi</p>
                <p class="font-body-md text-sm text-on-surface">{{ $p->deskripsi ?: '—' }}</p>
            </div>
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
                    <p class="text-[10px] uppercase text-on-surface-variant">Varian</p>
                    <p class="font-bold text-on-surface">{{ $p->variants->count() }}</p>
                </div>
                <div class="bg-surface-container-low rounded-lg p-3">
                    <p class="text-[10px] uppercase text-on-surface-variant">Tipe</p>
                    <p class="font-bold text-on-surface capitalize">{{ $p->tipe_produk ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>
@endforeach

{{-- Modal Tambah Produk --}}
<div id="modal-tambah-produk" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.produk.store') }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        @csrf
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Tambah Produk</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">Ajukan Produk Baru</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">Akan menunggu persetujuan Owner.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="raliva-label" for="np-nama">Nama Produk <span class="text-error">*</span></label>
                <input id="np-nama" name="nama_produk" required class="raliva-input" placeholder="Misal: Trench Coat Signature" />
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="raliva-label" for="np-harga">Harga Dasar <span class="text-error">*</span></label>
                    <input id="np-harga" name="harga_dasar" required type="number" min="0" class="raliva-input" placeholder="0" />
                </div>
                <div>
                    <label class="raliva-label" for="np-kategori">Kategori</label>
                    <select id="np-kategori" name="category_id" class="raliva-select">
                        <option value="">— Pilih —</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->category_id }}">{{ $c->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="raliva-label" for="np-tipe">Tipe Produk</label>
                <input id="np-tipe" name="tipe_produk" class="raliva-input" placeholder="Misal: Atasan, Bawahan, Outer" />
            </div>
            <div>
                <label class="raliva-label" for="np-deskripsi">Deskripsi</label>
                <textarea id="np-deskripsi" name="deskripsi" rows="3" class="raliva-textarea" placeholder="Deskripsi singkat produk..."></textarea>
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end gap-3">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
            <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Ajukan</button>
        </div>
    </form>
</div>
@endsection
