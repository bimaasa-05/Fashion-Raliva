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

        <div class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 mb-6 overflow-x-auto max-w-full">
            <button type="button" data-adm-tab="semua" class="adm-tab px-4 py-2 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Semua</button>
            <button type="button" data-adm-tab="pending" class="adm-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Menunggu</button>
            <button type="button" data-adm-tab="disetujui" class="adm-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Disetujui</button>
            <button type="button" data-adm-tab="ditolak" class="adm-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Ditolak</button>
        </div>

        @if ($products->isEmpty())
            <p class="text-on-surface-variant text-sm py-10 text-center">Tidak ada produk ditemukan.</p>
        @else
        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            @forelse ($products as $p)
                @php
                    $skuA = $p->variants->first()?->sku ?? '-';
                    $statusA = $p->status;
                    $normFotoA = function ($raw) {
                        if (filter_var($raw, FILTER_VALIDATE_URL)) return $raw;
                        $raw = ltrim($raw, '/');
                        if (str_starts_with($raw, 'assets/')) return asset($raw);
                        return asset('storage/' . $raw);
                    };
                    $fotosA = $p->images->map(fn ($img) => $normFotoA($img->file_gambar))->values()->all();
                @endphp
                <article data-reveal data-adm-row data-status="{{ $statusA === 'aktif' ? 'disetujui' : $statusA }}" data-produk-id="{{ $p->product_id }}" data-produk-nama="{{ $p->nama_produk }}" data-produk-sku="{{ $skuA }}" data-produk-created="{{ $p->created_at?->translatedFormat('d M Y') }}" data-produk-harga="Rp {{ number_format((float) $p->harga_dasar, 0, ',', '.') }}" data-produk-kategori="{{ $p->category?->nama_kategori ?? '-' }}" data-produk-tipe="{{ ucfirst($p->tipe_produk ?? 'regular') }}" data-produk-varian="{{ $p->variants->map(fn ($v) => trim(($v->warna ?? '') . ' ' . ($v->ukuran ?? '')))->filter()->implode(', ') }}" data-produk-deskripsi="{{ $p->deskripsi }}" data-produk-status="{{ $statusA }}" data-produk-alasan="{{ $statusA === 'ditolak' ? ($p->alasan_penolakan ?? '') : '' }}" data-produk-images='@json($fotosA)' class="group bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium flex flex-col">
                    <div class="relative aspect-[3/4] bg-surface-container-low overflow-hidden" data-produk-gallery>
                        @if (count($fotosA))
                            <div class="block w-full h-full" data-produk-main>
                                <img src="{{ $fotosA[0] }}" alt="{{ $p->nama_produk }}" data-produk-main-img class="w-full h-full object-cover transition-opacity duration-300" loading="lazy" />
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined text-[40px]">inventory_2</span></div>
                        @endif
                        <div class="absolute top-2 right-2">
                            @if ($statusA === 'aktif')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">Disetujui</span>
                            @elseif ($statusA === 'ditolak')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20">Ditolak</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface/80 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30">Menunggu</span>
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
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-muted-border gap-2 flex-wrap">
                            <span class="text-xs text-on-surface-variant truncate">{{ $p->category?->nama_kategori ?? '-' }}</span>
                            <button type="button" data-produk-detail class="inline-flex items-center gap-1 px-3 py-1.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap"><span class="material-symbols-outlined text-[16px]">visibility</span>Detail</button>
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
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-2xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
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
            <div id="detail-gallery" class="grid grid-cols-4 gap-2"></div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <div><dt class="raliva-label">Harga Dasar</dt><dd id="detail-harga" class="font-bold text-on-surface mt-1">-</dd></div>
                <div><dt class="raliva-label">Kategori</dt><dd id="detail-kategori" class="text-on-surface mt-1">-</dd></div>
                <div><dt class="raliva-label">Tipe</dt><dd id="detail-tipe" class="text-on-surface mt-1">-</dd></div>
                <div><dt class="raliva-label">Varian</dt><dd id="detail-varian" class="text-on-surface mt-1">-</dd></div>
                <div class="sm:col-span-2"><dt class="raliva-label">Deskripsi</dt><dd id="detail-deskripsi" class="text-on-surface mt-1 whitespace-pre-line">-</dd></div>
            </dl>
            <div id="detail-reason-box" class="hidden rounded-lg border border-error/20 bg-error/5 px-4 py-3">
                <p class="font-label-sm text-[10px] uppercase tracking-wider text-error">Alasan Penolakan</p>
                <p id="detail-reason" class="text-sm text-on-surface mt-1">-</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const detailModal = document.getElementById('modal-detail-produk');
        const openModal = (el) => el?.classList.remove('hidden');
        const closeModal = (el) => el?.classList.add('hidden');

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
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') { closeModal(detailModal); }
        });
    })();
</script>
@endpush

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
