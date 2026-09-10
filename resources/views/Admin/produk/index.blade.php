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

    <section data-reveal-group class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
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

{{-- Modal Form Produk — tengah, pola data-modal --}}
<div id="modal-form-produk" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl flex flex-col max-h-[90vh] overflow-y-auto">
    <div class="sticky top-0 bg-surface-container-lowest z-10 flex items-center justify-between px-6 py-5 border-b border-muted-border shrink-0">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Produk Baru</h3>
        <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <form method="POST" action="{{ route('admin.produk.store') }}" enctype="multipart/form-data" class="flex-1 overflow-y-auto p-6 space-y-6">
        @csrf
        {{-- Foto --}}
        <div>
            <label class="block raliva-label mb-2">Foto Produk (maks. 8 foto)</label>
            <div class="grid grid-cols-4 gap-gutter">
                @for ($i = 0; $i < 4; $i++)
                    <label class="aspect-[3/4] rounded-lg border-2 border-dashed border-outline-variant flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-gold-accent hover:bg-surface-container-low transition-colors group">
                        <input type="file" name="foto_produk[]" accept="image/*" class="hidden" onchange="if(this.files[0]){this.parentElement.querySelector('span').textContent='✓';}" />
                        <span class="material-symbols-outlined text-[22px] text-on-surface-variant group-hover:text-gold-accent transition-colors">add_photo_alternate</span>
                        <span class="text-[10px] text-on-surface-variant">Foto {{ $i + 1 }}</span>
                    </label>
                @endfor
            </div>
        </div>

        {{-- Informasi Dasar --}}
        <div class="space-y-4">
            <p class="text-xs font-medium text-gold-accent pt-2 border-t border-muted-border">Informasi Dasar</p>
            <div>
                <label for="fp-nama" class="block raliva-label mb-2">Nama Produk</label>
                <input id="fp-nama" name="nama_produk" type="text" placeholder="cth. Blazer Wool Premium" required class="raliva-input" />
            </div>
            <div>
                <label for="fp-deskripsi" class="block raliva-label mb-2">Deskripsi</label>
                <textarea id="fp-deskripsi" name="deskripsi" rows="3" placeholder="Bahan, potongan, keunggulan produk..." class="raliva-textarea"></textarea>
            </div>
            <div>
                <label for="fp-tipe" class="block raliva-label mb-2">Tipe Produk</label>
                <select id="fp-tipe" name="tipe_produk" required class="raliva-select">
                    <option value="regular">Regular</option>
                    <option value="preorder">Preorder</option>
                    <option value="made_to_order">Made to Order</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="fp-kategori" class="block raliva-label mb-2">Kategori</label>
                    <select id="fp-kategori" name="category_id" class="raliva-select">
                        <option value="">— Pilih —</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->category_id }}">{{ $c->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="fp-harga" class="block raliva-label mb-2">Harga (Rp)</label>
                    <input id="fp-harga" name="harga_dasar" type="number" placeholder="949000" required class="raliva-input" />
                </div>
            </div>
        </div>

        {{-- Variasi --}}
        <div class="space-y-4">
            <p class="text-xs font-medium text-gold-accent pt-2 border-t border-muted-border">Variasi &amp; Stok</p>
            <div>
                <p class="raliva-label mb-2">Ukuran</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size'] as $size)
                        <button type="button" class="ukuran-chip px-4 py-2 rounded-lg border border-muted-border text-xs font-medium text-on-surface hover:border-gold-accent transition-colors" data-size="{{ $size }}">{{ $size }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="ukuran_terpilih" id="ukuran-terpilih" />
            </div>
            <div>
                <p class="raliva-label mb-2">Warna</p>
                <div class="flex flex-wrap gap-3">
                    @foreach ([['Hitam', '#1c1b1b'], ['Krem', '#e8dcc8'], ['Navy', '#22304a'], ['Camel', '#c19a6b'], ['Putih', '#f5f3f3']] as $color)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="warna[]" value="{{ $color[0] }}" class="sr-only peer" />
                            <span class="w-7 h-7 rounded-full border border-outline-variant shadow-inner peer-checked:ring-2 peer-checked:ring-gold-accent peer-checked:ring-offset-2 ring-offset-surface-container-lowest transition-all" style="background-color: {{ $color[1] }};"></span>
                            <span class="font-body-md text-xs text-on-surface peer-checked:text-gold-accent">{{ $color[0] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="fp-stok" class="block raliva-label mb-2">Total Stok Awal</label>
                    <input id="fp-stok" name="stok_awal" type="number" value="50" min="0" class="raliva-input" />
                </div>
                <div>
                    <label for="fp-min-restock" class="block raliva-label mb-2">Ambang Stok Menipis</label>
                    <input id="fp-min-restock" name="stok_minimum" type="number" value="10" min="0" class="raliva-input" />
                </div>
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
</div>
@push('scripts')
<script>
document.querySelectorAll('.ukuran-chip').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        btn.classList.toggle('bg-gold-accent');
        btn.classList.toggle('text-white');
        btn.classList.toggle('border-gold-accent');
        const selected = Array.from(document.querySelectorAll('.ukuran-chip.bg-gold-accent')).map(b=>b.dataset.size || b.textContent.trim());
        document.getElementById('ukuran-terpilih').value = selected.join(',');
    });
});
</script>
@endpush
@endsection
