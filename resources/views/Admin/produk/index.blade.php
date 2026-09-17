@extends('layouts.admin')

@section('title', 'Data Produk')
@section('header-title', 'Data Produk')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Kelola produk sesuai permission yang diberikan Owner.')

@section('content')
<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Akses terbatas: produk yang kamu tambahkan akan diajukan dan menunggu persetujuan Super Admin (status <b>pending</b>).</p>
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
        <div data-reveal-group class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-gutter">
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
                @for ($i = 0; $i < 8; $i++)
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
                    <div class="flex gap-2">
                        <div class="flex-1 space-y-2">
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant pointer-events-none">search</span>
                                <input type="text" id="kategori-search" placeholder="Cari kategori..." autocomplete="off" class="raliva-input text-sm pl-9" />
                            </div>
                            <select id="fp-kategori" name="category_id" class="raliva-select w-full">
                                <option value="">— Pilih —</option>
                                @foreach ($categories as $c)
                                    <option value="{{ $c->category_id }}">{{ $c->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" data-modal-open="modal-tambah-kategori" class="px-3 py-2 border border-gold-accent/40 text-gold-accent rounded-lg text-xs whitespace-nowrap h-fit">+ Kategori</button>
                    </div>
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
                <div class="flex flex-wrap gap-2" id="ukuran-chips">
                    @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size'] as $size)
                        <button type="button" class="ukuran-chip px-4 py-2 rounded-lg border border-muted-border text-xs font-medium text-on-surface hover:border-gold-accent transition-colors" data-size="{{ $size }}">{{ $size }}</button>
                    @endforeach
                    <button type="button" onclick="document.getElementById('custom-size-fields').classList.toggle('hidden')" class="px-4 py-2 rounded-lg border border-dashed border-gold-accent/40 text-gold-accent text-xs font-medium hover:bg-gold-accent/5 transition-colors">+ Custom</button>
                </div>
                <div id="custom-size-fields" class="hidden mt-3 p-3 border border-muted-border rounded-lg bg-surface-container-low space-y-2">
                    <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Ukuran Custom (isi yang relevan)</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <input type="number" name="custom_ld" placeholder="Lingkar Dada (cm)" class="raliva-input text-sm" />
                        <input type="number" name="custom_pb" placeholder="Panjang Baju (cm)" class="raliva-input text-sm" />
                        <input type="number" name="custom_lb" placeholder="Lebar Bahu (cm)" class="raliva-input text-sm" />
                        <input type="number" name="custom_lt" placeholder="Lingkar Tangan (cm)" class="raliva-input text-sm" />
                        <input type="number" name="custom_pl" placeholder="Panjang Lengan (cm)" class="raliva-input text-sm" />
                    </div>
                    <button type="button" onclick="addCustomSize()" class="px-3 py-1.5 bg-deep-onyx text-on-primary text-xs rounded">Tambah Ukuran Custom</button>
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
            <div>
                <p class="raliva-label mb-1">Stok per Varian</p>
                <p class="text-xs text-on-surface-variant mb-3">Setelah pilih ukuran &amp; warna, isi stok untuk setiap kombinasi varian.</p>
                <div id="varian-stok-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
                <div id="varian-stok-empty" class="mt-2 p-4 border border-dashed border-outline-variant rounded-lg text-center text-xs text-on-surface-variant">Belum ada varian. Pilih ukuran &amp; warna di atas untuk mengatur stok per varian.</div>
                <input type="hidden" name="stok_awal" id="fp-stok-synced" value="0" />
                <input type="hidden" name="stok_minimum" id="fp-min-restock-synced" value="0" />
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

{{-- Modal Tambah Kategori (terpisah dari form produk) --}}
<div id="modal-tambah-kategori" data-modal class="fixed inset-0 z-[80] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl flex flex-col max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest z-10 flex items-center justify-between px-6 py-5 border-b border-muted-border shrink-0">
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Kategori Baru</h3>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="form-tambah-kategori" class="flex-1 overflow-y-auto p-6 space-y-4">
            @csrf
            <div>
                <label for="ktg-nama" class="block raliva-label mb-2">Nama Kategori</label>
                <input id="ktg-nama" name="nama_kategori" type="text" placeholder="cth. Outerwear" required maxlength="100" class="raliva-input" />
            </div>
            <div>
                <label for="ktg-deskripsi" class="block raliva-label mb-2">Deskripsi</label>
                <textarea id="ktg-deskripsi" name="deskripsi" rows="3" maxlength="500" placeholder="Opsional, penjelasan singkat kategori..." class="raliva-textarea"></textarea>
            </div>
        </form>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter">
            <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
            <button type="button" id="ktg-simpan" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">check_circle</span>Simpan Kategori
            </button>
        </div>
    </div>
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
    const grid = document.getElementById('varian-stok-grid');
    const empty = document.getElementById('varian-stok-empty');
    if (!grid || !empty) return;

    const count = ukuran.length * warna.length;
    empty.style.display = count > 0 ? 'none' : 'block';
    grid.innerHTML = '';

    let i = 0;
    for (const uk of ukuran) {
        for (const wr of warna) {
            const row = document.createElement('div');
            row.className = 'p-3 border border-muted-border rounded-lg bg-surface-container-low space-y-2';
            row.innerHTML = `
                <input type="hidden" name="varian_stok[${i}][ukuran]" value="${escapeHtml(uk)}" />
                <input type="hidden" name="varian_stok[${i}][warna]" value="${escapeHtml(wr)}" />
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full border border-outline-variant shrink-0 inline-block" style="background-color: ${warnaSwatch(wr)}"></span>
                    <span class="text-xs font-bold text-on-surface truncate">${escapeHtml(uk)} · ${escapeHtml(wr)}</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Stok</label>
                        <input type="number" name="varian_stok[${i}][stok]" value="0" min="0" class="raliva-input text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Ambang Menipis</label>
                        <input type="number" name="varian_stok[${i}][stok_minimum]" value="0" min="0" class="raliva-input text-sm" />
                    </div>
                </div>
            `;
            grid.appendChild(row);
            i++;
        }
    }

    // sync hidden total (backward compat)
    const total = Array.from(grid.querySelectorAll('[name$="[stok]"]')).reduce((s, el)=> s + (parseInt(el.value,10) || 0), 0);
    const syncTotal = document.getElementById('fp-stok-synced');
    const syncMin = document.getElementById('fp-min-restock-synced');
    if (syncTotal) syncTotal.value = total;
    if (syncMin) {
        const mins = Array.from(grid.querySelectorAll('[name$="[stok_minimum]"]')).map(el=>parseInt(el.value,10)||0);
        syncMin.value = mins.length ? Math.min(...mins) : 0;
    }
}

function escapeHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function warnaSwatch(name) {
    const map = {'Hitam':'#1c1b1b','Krem':'#e8dcc8','Navy':'#22304a','Camel':'#c19a6b','Putih':'#f5f3f3'};
    return map[name] || '#cccccc';
}

function updateUkuranTerpilih() {
    const selected = getSelectedUkuran();
    document.getElementById('ukuran-terpilih').value = selected.join(',');
}

function addCustomSize() {
    const ld = document.querySelector('[name="custom_ld"]').value;
    const pb = document.querySelector('[name="custom_pb"]').value;
    const lb = document.querySelector('[name="custom_lb"]').value;
    const lt = document.querySelector('[name="custom_lt"]').value;
    const pl = document.querySelector('[name="custom_pl"]').value;
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

// --- Searchable kategori ---
(function() {
    const search = document.getElementById('kategori-search');
    const select = document.getElementById('fp-kategori');
    if (!search || !select) return;
    search.addEventListener('input', () => {
        const q = search.value.trim().toLowerCase();
        Array.from(select.options).forEach(opt => {
            if (!opt.value) return;
            opt.style.display = opt.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
})();

// --- Tambah Kategori via AJAX ---
(function() {
    const btn = document.getElementById('ktg-simpan');
    const modal = document.getElementById('modal-tambah-kategori');
    const form = document.getElementById('form-tambah-kategori');
    if (!btn || !modal || !form) return;
    btn.addEventListener('click', () => {
        const nama = document.getElementById('ktg-nama').value.trim();
        const deskripsi = document.getElementById('ktg-deskripsi').value.trim();
        if (!nama) { alert('Nama kategori wajib diisi.'); return; }
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span>Menyimpan...';
        const data = new FormData();
        data.append('_token', document.querySelector('input[name="_token"]').value);
        data.append('nama_kategori', nama);
        data.append('deskripsi', deskripsi);
        fetch('{{ route('admin.kategori.store') }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: data
        })
        .then(r => r.json().then(j => ({ ok: r.ok, j })))
        .then(({ ok, j }) => {
            if (!ok || !j.success) throw new Error(j.message || 'Gagal menyimpan kategori.');
            const opt = document.createElement('option');
            opt.value = j.kategori.category_id;
            opt.textContent = j.kategori.nama_kategori;
            const select = document.getElementById('fp-kategori');
            select.appendChild(opt);
            select.value = opt.value;
            document.getElementById('ktg-nama').value = '';
            document.getElementById('ktg-deskripsi').value = '';
            modal.classList.add('hidden');
            const search = document.getElementById('kategori-search');
            if (search) search.value = '';
            alert('Kategori "' + opt.textContent + '" berhasil ditambahkan.');
        })
        .catch(err => { alert(err.message || 'Terjadi kesalahan.'); })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">check_circle</span>Simpan Kategori';
        });
    });
})();
</script>
@endpush
@endsection
