@extends('layouts.superadmin')

@section('title', 'Moderasi Produk')

@section('header-title', 'Moderasi Produk')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Tinjau produk pending dan beri keputusan setujui atau tolak beserta alasan.')

@php
    $tabs = [
        \App\Models\Product::STATUS_PENDING => ['label' => 'Menunggu', 'icon' => 'pending'],
        \App\Models\Product::STATUS_DITOLAK => ['label' => 'Ditolak', 'icon' => 'block'],
    ];

    $statusIconMap = [
        \App\Models\Product::STATUS_PENDING => 'pending',
        \App\Models\Product::STATUS_DITOLAK => 'gpp_bad',
    ];
@endphp

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
    .filled-icon { font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="px-container-margin pb-element-gap">
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Moderasi</span>
        </div>
        <div class="flex items-center justify-center space-x-gutter overflow-x-auto no-scrollbar py-2">
            @foreach ($tabs as $key => $tab)
                <a href="{{ route('superadmin.moderasi-produk', ['status' => $key]) }}"
                    class="font-label-sm text-label-sm px-4 py-2 border-b-2 transition-colors whitespace-nowrap {{ $activeStatus === $key
                        ? 'border-primary text-primary'
                        : 'border-transparent text-on-surface-variant hover:text-on-surface' }}">
                    {{ strtoupper($tab['label']) }} ({{ $stats[$key] ?? 0 }})
                </a>
            @endforeach
        </div>
        <div class="flex justify-center pt-1">
            <a href="{{ route('superadmin.produk') }}" class="text-xs text-gold-accent hover:underline inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">visibility</span> Lihat produk disetujui di Data Produk</a>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-4">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input id="moderasi-search" class="w-full bg-surface-container-low border border-muted-border rounded-lg pl-11 pr-10 py-3 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" type="text" placeholder="Cari nama produk, toko, kategori, atau tipe..." />
                <button type="button" id="moderasi-clear-search" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent opacity-0 transition-opacity">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <p class="text-on-surface-variant font-body-md text-xs shrink-0">
                <span id="moderasi-result-count">{{ $products->count() }}</span> produk
            </p>
        </div>
    </div>
</div>

<div data-table-scope class="px-container-margin flex-grow">
    <div id="moderasi-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-gutter gap-y-container-margin">
        @forelse ($products as $product)
            <div class="group cursor-pointer flex flex-col transition-transform duration-300 hover:-translate-y-1"
                onclick="openDetailModal(this)"
                data-table-row
                data-search="{{ strtolower($product->nama_produk.' '.($product->store->nama_toko ?? '').' '.($product->category->nama_kategori ?? '').' '.$product->tipe_produk.' '.$product->deskripsi) }}"
                data-id="{{ $product->product_id }}"
                data-name="{{ $product->nama_produk }}"
                data-store="{{ $product->store->nama_toko ?? '-' }}"
                data-price="Rp {{ number_format($product->harga_dasar, 0, ',', '.') }}"
                data-category="{{ ($product->category->nama_kategori ?? '-') }}"
                data-desc="{{ $product->deskripsi }}"
                data-status="{{ $product->status }}"
                data-reason="{{ $product->alasan_penolakan }}"
                data-tipe="{{ ucfirst($product->tipe_produk) }}"
                data-variants="{{ $product->variants->map(fn ($v) => trim(($v->warna ?? '') . ' ' . ($v->ukuran ?? '')))->filter()->implode(', ') }}"
                data-images='{{ json_encode($product->images->pluck('file_gambar')->values(), JSON_UNESCAPED_SLASHES) }}'
                data-slot-total="{{ $product->slot_total }}"
                data-slot-used="{{ $product->slot_used }}"
                data-slot-available="{{ $product->slot_available }}"
                data-slot-full="{{ $product->slot_full ? '1' : '0' }}">
                <div class="relative w-full aspect-[3/4] bg-surface-container-low mb-element-gap overflow-hidden rounded-lg isolate">
                    @php $imgs = $product->images; $imgCount = $imgs->count(); @endphp
                    @forelse ($imgs as $i => $img)
                        <img data-card-slide class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}" src="{{ filter_var($img->file_gambar, FILTER_VALIDATE_URL) ? $img->file_gambar : asset('storage/' . ltrim($img->file_gambar, '/')) }}" alt="{{ $product->nama_produk }} — foto {{ $i + 1 }}" loading="lazy" />
                    @empty
                        <div class="w-full h-full flex items-center justify-center bg-surface-container-high">
                            <span class="material-symbols-outlined text-[42px] text-on-surface-variant/40">checkroom</span>
                        </div>
                    @endforelse
                    @if($imgCount > 1)
                        <span class="absolute top-2 left-2 bg-deep-onyx text-on-primary text-[10px] font-bold px-1.5 py-0.5 rounded">+{{ $imgCount - 1 }}</span>
                    @endif
                    <div class="absolute top-2 right-2 p-1 bg-surface/80 rounded"><span class="material-symbols-outlined text-[18px] text-on-surface">{{ $statusIconMap[$product->status] ?? 'pending' }}</span></div>
                    @if ($product->owner_verified_at)
                        <div class="absolute top-2 left-2 px-2 py-1 bg-gold-accent/90 text-white text-[9px] font-bold uppercase tracking-widest rounded">✓ Owner</div>
                    @endif
                    @if ($product->status === \App\Models\Product::STATUS_DITOLAK)
                        <div class="absolute bottom-2 left-2 right-2 px-2 py-1 bg-error/90 text-on-error text-[9px] font-bold uppercase tracking-widest rounded text-center">Ditolak • Lihat Alasan</div>
                    @endif
                </div>
                <div class="flex flex-col flex-grow"><span class="font-label-sm text-label-sm text-on-surface-variant mb-1">{{ strtoupper($product->store->nama_toko ?? '-') }}</span><h3 class="font-body-md text-body-md text-on-surface leading-tight mb-1 truncate">{{ $product->nama_produk }}</h3><div class="font-body-md text-body-md text-on-surface mt-auto">Rp {{ number_format($product->harga_dasar, 0, ',', '.') }}</div><div class="flex items-center gap-1.5 flex-wrap mt-2"><span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">{{ ucfirst($product->tipe_produk) }}</span>@if ($product->status === \App\Models\Product::STATUS_PENDING)<span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold uppercase border {{ $product->slot_full ? 'bg-error/10 text-error border-error/30' : 'bg-success/10 text-success border-success/20' }}">{{ $product->slot_full ? 'Kuota Penuh' : 'Slot ' . $product->slot_available . '/' . $product->slot_total }}</span>@endif</div></div>
            </div>
        @empty
            <p id="moderasi-kosong" class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-16">Belum ada produk pada status ini.</p>
        @endforelse
    </div>
    <p id="moderasi-empty-search" class="hidden text-center text-on-surface-variant font-body-md text-sm py-16">Tidak ada produk yang cocok.</p>
</div>
@endsection

@push('scripts')
<script>
    let activeProductCard = null;
    let activeGallery = [];

    function resolveSrc(src) {
        if (!src) return '';
        if (src.startsWith('http')) return src;
        if (src.startsWith('/')) return src;
        if (src.startsWith('storage/')) return '/' + src;
        return '/storage/' + src.replace(/^\/+/, '');
    }

    const productActionUrls = {
        setujui: (id) => '{{ route('superadmin.moderasi-produk.setujui', ':id:') }}'.replace(':id:', id),
        tolak: (id) => '{{ route('superadmin.moderasi-produk.tolak', ':id:') }}'.replace(':id:', id)
    };

    function openDetailModal(card) {
        activeProductCard = card;
        const d = card.dataset;
        document.getElementById('mod-name').textContent = d.name;
        document.getElementById('mod-store').textContent = d.store.toUpperCase();
        document.getElementById('mod-price').textContent = d.price;
        document.getElementById('mod-category').textContent = d.category;
        document.getElementById('mod-variants').textContent = d.variants || '-';
        document.getElementById('mod-desc').textContent = d.desc;
        document.getElementById('mod-tipe').textContent = d.tipe;

        const img = card.querySelector('img');
        const imgEl = document.getElementById('mod-img');
        const thumbsEl = document.getElementById('mod-thumbs');
        let gallery = [];
        try { gallery = JSON.parse(card.getAttribute('data-images') || '[]'); } catch(e) { gallery = []; }
        activeGallery = gallery;
        const photoCountEl = document.getElementById('mod-photo-count');
        const photoCounterEl = document.getElementById('mod-photo-counter');
        if (photoCountEl) photoCountEl.textContent = gallery.length;

        if (gallery.length > 0) {
            imgEl.src = resolveSrc(gallery[0]);
            imgEl.classList.remove('hidden');
            if (photoCounterEl) photoCounterEl.textContent = '1/' + gallery.length;
        } else if (img) {
            imgEl.src = img.src;
            imgEl.classList.remove('hidden');
            if (photoCounterEl) photoCounterEl.textContent = '0';
        } else {
            imgEl.classList.add('hidden');
            if (photoCounterEl) photoCounterEl.textContent = '0';
        }

        if (thumbsEl) {
            thumbsEl.innerHTML = '';
            gallery.forEach((src, idx) => {
                const resolved = resolveSrc(src);
                const thumb = document.createElement('img');
                thumb.src = resolved;
                thumb.alt = 'Foto ' + (idx + 1);
                thumb.loading = 'lazy';
                thumb.className = 'aspect-square w-full object-cover rounded-lg border cursor-pointer hover:border-gold-accent transition-colors ' + (idx === 0 ? 'border-gold-accent ring-2 ring-gold-accent' : 'border-muted-border');
                thumb.onclick = () => {
                    imgEl.src = resolved;
                    if (photoCounterEl) photoCounterEl.textContent = (idx + 1) + '/' + gallery.length;
                    openLightbox(resolved);
                    Array.from(thumbsEl.children).forEach((c, i) => c.className = 'aspect-square w-full object-cover rounded-lg border cursor-pointer hover:border-gold-accent transition-colors ' + (i === idx ? 'border-gold-accent ring-2 ring-gold-accent' : 'border-muted-border'));
                };
                thumbsEl.appendChild(thumb);
            });
        }

        const reasonBox = document.getElementById('mod-reason-box');
        if (d.status === 'ditolak' && d.reason) {
            reasonBox.classList.remove('hidden');
            document.getElementById('mod-reason-text').textContent = d.reason;
        } else {
            reasonBox.classList.add('hidden');
        }

        const slotBox = document.getElementById('mod-slot-box');
        const slotFull = String(d.slotFull) === '1';
        if (d.status === 'pending' && d.slotTotal !== undefined) {
            slotBox.classList.remove('hidden');
            const el = document.getElementById('mod-slot');
            el.textContent = slotFull
                ? 'Kuota penuh (' + d.slotUsed + '/' + d.slotTotal + ') — pemilik toko harus menambah slot.'
                : d.slotAvailable + '/' + d.slotTotal + ' slot tersisa.';
            el.className = 'font-body-md text-body-md ' + (slotFull ? 'text-error' : 'text-success');
        } else {
            slotBox.classList.add('hidden');
        }

        const canDecide = d.status === 'pending';
        document.getElementById('mod-action-reject').classList.toggle('hidden', !canDecide);
        document.getElementById('mod-action-approve').classList.toggle('hidden', !canDecide || slotFull);
        document.getElementById('mod-action-note').classList.toggle('hidden', canDecide && !slotFull);
        document.getElementById('mod-action-slotfull').classList.toggle('hidden', !(canDecide && slotFull));
        document.getElementById('approve-product-form').action = productActionUrls.setujui(d.id);

        document.getElementById('mod-reject-store').textContent = d.store;
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        closeLightbox();
        document.body.style.overflow = '';
    }

    function openRejectModal() {
        if (!activeProductCard) return;
        document.getElementById('mod-reject-store').textContent = activeProductCard.dataset.store;
        document.getElementById('reject-product-form').action = productActionUrls.tolak(activeProductCard.dataset.id);
        document.getElementById('reject-alasan-input').value = '';
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openLightbox(src) {
        if (!src) return;
        const lb = document.getElementById('lightbox');
        document.getElementById('lightbox-img').src = src;
        const lbCounter = document.getElementById('lightbox-counter');
        if (lbCounter) {
            const found = activeGallery.findIndex((s) => resolveSrc(s) === src);
            const num = found >= 0 ? found + 1 : 1;
            lbCounter.textContent = num + '/' + (activeGallery.length || 1);
        }
        lb.classList.remove('hidden');
        lb.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lb = document.getElementById('lightbox');
        if (!lb || lb.classList.contains('hidden')) return;
        lb.classList.add('hidden');
        lb.classList.remove('flex');
        const detailOpen = !document.getElementById('detailModal').classList.contains('hidden');
        document.body.style.overflow = detailOpen ? 'hidden' : '';
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeLightbox(); closeDetailModal(); closeRejectModal(); }
    });
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const scope = document.querySelector('[data-table-scope]');
    if (!scope) return;

    const rows = Array.from(scope.querySelectorAll('[data-table-row]'));
    const searchInput = document.getElementById('moderasi-search');
    const clearBtn = document.getElementById('moderasi-clear-search');
    const countEl = document.getElementById('moderasi-result-count');
    const emptySearch = document.getElementById('moderasi-empty-search');

    function applyFilter() {
        const term = searchInput.value.trim().toLowerCase();
        let visible = 0;

        rows.forEach((row) => {
            const matchSearch = !term || (row.getAttribute('data-search') || '').includes(term);
            const show = matchSearch;
            row.classList.toggle('hidden', !show);
            if (show) visible++;
        });

        countEl.textContent = visible;
        emptySearch.classList.toggle('hidden', visible > 0 || rows.length === 0);
    }

    let debounce;
    searchInput.addEventListener('input', () => {
        clearBtn.classList.toggle('opacity-0', !searchInput.value);
        clearTimeout(debounce);
        debounce = setTimeout(applyFilter, 200);
    });

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearBtn.classList.add('opacity-0');
        applyFilter();
    });

    applyFilter();

    // === Auto-rotate foto kartu (crossfade 3s seperti hero) + pause saat hover ===
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        rows.forEach((row) => {
            const slides = row.querySelectorAll('[data-card-slide]');
            if (slides.length < 2) return;
            let idx = 0;
            let timer = setInterval(tick, 3000);
            function tick() {
                slides[idx].style.opacity = '0';
                idx = (idx + 1) % slides.length;
                slides[idx].style.opacity = '1';
            }
            row.addEventListener('mouseenter', () => clearInterval(timer));
            row.addEventListener('mouseleave', () => {
                clearInterval(timer);
                timer = setInterval(tick, 3000);
            });
        });
    }
});
</script>
@endpush

@push('modals')
<!-- Detail Modal -->
<div class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="detailModal">
    <div class="relative w-full max-w-2xl h-[min(795px,92vh)] bg-surface-container-lowest rounded-xl border border-muted-border shadow-2xl flex flex-col overflow-hidden">
        <div class="shrink-0 relative border-b border-muted-border">
            <div class="absolute inset-0 bg-gradient-to-r from-gold-accent/15 via-gold-accent/5 to-transparent pointer-events-none"></div>
            <div class="relative p-6 pr-14">
                <div class="flex items-center gap-2 flex-wrap mb-1.5">
                    <span id="mod-store" class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">-</span>
                </div>
                <h2 id="mod-name" class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface leading-tight">-</h2>
                <div id="mod-price" class="font-title-md text-title-md text-gold-accent mt-1">-</div>
            </div>
            <button type="button" class="absolute top-4 right-4 z-10 p-2 rounded-full hover:bg-surface-container-high transition-colors" onclick="closeDetailModal()"><span class="material-symbols-outlined text-on-surface">close</span></button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div id="mod-reason-box" class="hidden m-6 mb-0 bg-error/5 border border-error/25 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-error text-[18px] mt-0.5">gpp_bad</span>
                    <div>
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-error mb-1">Alasan Penolakan Sebelumnya</p>
                        <p id="mod-reason-text" class="text-sm text-on-surface"></p>
                    </div>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-0">
                <div class="bg-surface-container-low min-h-[220px] p-3 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Foto Produk (<span id="mod-photo-count">0</span>)</span>
                        <span id="mod-photo-counter" class="font-body-md text-body-md font-bold text-gold-accent">-</span>
                    </div>
                    <img id="mod-img" class="w-full h-[280px] object-cover rounded-lg cursor-zoom-in border border-muted-border" src="" alt="Foto produk" onclick="openLightbox(this.src)" />
                    <div id="mod-thumbs" class="grid grid-cols-3 gap-2"></div>
                </div>
                <div class="p-6 space-y-4">
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Tipe Produk</span><span id="mod-tipe" class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">-</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Kategori</span><span id="mod-category" class="font-body-md text-body-md text-on-surface">-</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Varian (Warna & Ukuran)</span><span id="mod-variants" class="font-body-md text-body-md text-on-surface">-</span></div>
                    <div id="mod-slot-box"><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Kuota Slot Toko</span><span id="mod-slot" class="font-body-md text-body-md text-on-surface">-</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Deskripsi</span><p id="mod-desc" class="font-body-md text-body-md text-on-surface-variant leading-relaxed text-sm">-</p></div>
                </div>
            </div>
        </div>

        <div class="shrink-0 border-t border-muted-border px-6 py-4 bg-surface/95 backdrop-blur flex gap-3">
            <p id="mod-action-note" class="hidden flex-1 self-center text-xs text-on-surface-variant italic">Keputusan sudah diambil untuk produk ini.</p>
            <p id="mod-action-slotfull" class="hidden flex-1 self-center text-xs text-error font-semibold">Kuota slot toko penuh — setujui hanya setelah slot ditambah.</p>
            <button id="mod-action-reject" type="button" onclick="openRejectModal()" class="hidden flex-1 py-3 bg-transparent border border-error/40 text-error font-label-sm text-label-sm uppercase tracking-widest hover:bg-error/10 transition-colors rounded-lg">Tolak</button>
            <form id="approve-product-form" method="POST" action="" onsubmit="closeDetailModal()">
                @csrf
                <button id="mod-action-approve" type="submit" class="hidden w-full py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">Setujui Produk</button>
            </form>
        </div>
    </div>
</div>

<!-- Lightbox Foto -->
<div class="fixed inset-0 z-[80] hidden items-center justify-center p-4 bg-black/80" id="lightbox" onclick="if (event.target === this) closeLightbox()">
    <img id="lightbox-img" class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg" src="" alt="Foto produk" />
    <span id="lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-xs font-bold bg-black/50 px-2.5 py-1 rounded-full">-</span>
    <button type="button" class="absolute top-4 right-4 p-2 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors z-10" onclick="closeLightbox()"><span class="material-symbols-outlined">close</span></button>
</div>

<!-- Reject Reason Modal -->
<form id="reject-product-form" method="POST" action="" onsubmit="closeRejectModal(); closeDetailModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="rejectModal" onclick="if (event.target === this) closeRejectModal()">
        <div class="relative bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">block</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Alasan Penolakan</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Alasan dikirim ke <span id="mod-reject-store" class="font-bold text-on-surface">-</span>.</p>
                <textarea required minlength="10" maxlength="1000" name="alasan" id="reject-alasan-input" class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-4" placeholder="Tulis alasan di sini... (minimal 10 karakter)" rows="4"></textarea>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeRejectModal()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endpush
