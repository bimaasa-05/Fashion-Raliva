@extends('layouts.superadmin')

@section('title', 'Moderasi Produk')

@section('header-title', 'Moderasi Produk')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Tinjau produk pending dan beri keputusan setujui atau tolak beserta alasan.')

@php
    $tabs = [
        \App\Models\Product::STATUS_PENDING => 'Menunggu',
        \App\Models\Product::STATUS_DITOLAK => 'Ditolak',
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
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Moderasi</span>
        </div>
        <div id="moderasi-tabs" class="flex flex-wrap items-center gap-2.5 justify-center py-2">
            @foreach ($tabs as $key => $label)
                <button type="button" data-status="{{ $key }}" class="moderasi-filter-btn px-4 py-2 rounded-lg border font-label-sm uppercase tracking-wider transition-colors {{ $activeStatus === $key
                    ? 'bg-deep-onyx text-on-primary border-deep-onyx hover:bg-deep-onyx/90'
                    : 'bg-surface-container-low text-on-surface-variant border-muted-border hover:bg-surface-container-high hover:text-on-surface hover:border-gold-accent' }}">
                    {{ strtoupper($label) }} ({{ $stats[$key] ?? 0 }})
                </button>
            @endforeach
        </div>
        <div class="flex justify-center pt-2">
            <a href="{{ route('superadmin.produk') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-gold-accent/40 bg-gold-accent/5 text-gold-accent font-label-sm text-label-sm uppercase tracking-wider hover:bg-gold-accent/15"><span class="material-symbols-outlined text-[16px]">visibility</span> Lihat produk disetujui di Data Produk</a>
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
    <div id="moderasi-list-holder">
        @include('SuperAdmin.moderasi-produk.partials.produk-list')
    </div>
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

    function switchModTab(tab) {
        document.querySelectorAll('[data-mod-tab]').forEach((b) => {
            const active = b.getAttribute('data-mod-tab') === tab;
            b.classList.toggle('bg-deep-onyx', active);
            b.classList.toggle('text-on-primary', active);
            b.classList.toggle('border', !active);
            b.classList.toggle('border-muted-border', !active);
            b.classList.toggle('text-on-surface-variant', !active);
        });
        document.querySelector('[data-mod-panel="produk"]').classList.toggle('hidden', tab !== 'produk');
        document.querySelector('[data-mod-panel="bahan"]').classList.toggle('hidden', tab !== 'bahan');
    }

    function openDetailModal(card) {        activeProductCard = card;
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
                : d.slotUsed + '/' + d.slotTotal + ' slot terpakai — sisa ' + d.slotAvailable + ' slot.';
            el.className = 'font-body-md text-body-md ' + (slotFull ? 'text-error' : 'text-success');
        } else {
            slotBox.classList.add('hidden');
        }

        const canDecide = d.status === 'pending';
        document.getElementById('mod-action-reject').classList.toggle('hidden', !canDecide);
        document.getElementById('mod-action-approve').classList.toggle('hidden', !canDecide || slotFull);
        document.getElementById('mod-action-note').classList.toggle('hidden', canDecide && !slotFull);
        document.getElementById('mod-action-slotfull').classList.toggle('hidden', !(canDecide && slotFull));

        let bahanRows = [];
        try { bahanRows = JSON.parse(card.getAttribute('data-bahan') || '[]'); } catch(e) { bahanRows = []; }
        const bahanBody = document.getElementById('mod-bahan-rows');
        const bahanEmpty = document.getElementById('mod-bahan-empty');
        bahanBody.innerHTML = '';
        bahanRows.forEach((row) => {
            const tr = document.createElement('tr');
            tr.className = 'border-t border-muted-border';
            const tdNama = document.createElement('td');
            tdNama.className = 'py-1 pr-2 text-on-surface font-semibold';
            tdNama.textContent = row.nama || '-';
            const tdJumlah = document.createElement('td');
            tdJumlah.className = 'py-1 pr-2 text-right text-on-surface';
            tdJumlah.textContent = row.jumlah ?? '-';
            const tdSatuan = document.createElement('td');
            tdSatuan.className = 'py-1 pr-2 text-on-surface-variant';
            tdSatuan.textContent = row.satuan || '-';
            tr.appendChild(tdNama);
            tr.appendChild(tdJumlah);
            tr.appendChild(tdSatuan);
            bahanBody.appendChild(tr);
        });
        bahanEmpty.classList.toggle('hidden', bahanRows.length > 0);

        document.querySelectorAll('[data-mod-tab]').forEach((btn) => {
            const tab = btn.getAttribute('data-mod-tab');
            if (tab === 'produk') {
                btn.onclick = () => switchModTab('produk');
            } else {
                btn.onclick = () => switchModTab('bahan');
            }
        });
        switchModTab('produk');
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

    const holder = document.getElementById('moderasi-list-holder');
    const searchInput = document.getElementById('moderasi-search');
    const clearBtn = document.getElementById('moderasi-clear-search');
    const countEl = document.getElementById('moderasi-result-count');
    const modBaseUrl = '{{ route('superadmin.moderasi-produk') }}';

    function rowsNow() {
        return Array.from(scope.querySelectorAll('[data-table-row]'));
    }

    // === Galeri kartu: pilih foto via thumbnail & cycle saat hover (seperti Owner) ===
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const cardTimers = new Map();
    const stopCardCycle = (card) => {
        const t = cardTimers.get(card);
        if (t) { clearInterval(t); cardTimers.delete(card); }
    };
    const stopAllCardCycles = () => cardTimers.forEach((t, card) => stopCardCycle(card));
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
        mainImg.alt = (card.dataset.name || 'Foto produk') + ' — foto ' + (i + 1);
        card.querySelectorAll('[data-produk-pin]').forEach((th) => {
            const active = parseInt(th.dataset.produkPin || '0', 10) === i;
            th.setAttribute('aria-pressed', active ? 'true' : 'false');
            th.classList.toggle('border-gold-accent', active);
            th.classList.toggle('ring-2', active);
            th.classList.toggle('ring-gold-accent/30', active);
            th.classList.toggle('border-outline-variant', !active);
        });
    };

    function initCardGalleries() {
        rowsNow().forEach((card) => {
            let urls = [];
            try { urls = JSON.parse(card.dataset.produkImages || '[]'); } catch (e) { urls = []; }
            if (urls.length <= 1) return;
            card._galleryUrls = urls;
            card._galleryPinned = 0;
            card._galleryShown = 0;
            card.querySelectorAll('[data-produk-pin]').forEach((th) => {
                th.addEventListener('click', (event) => {
                    event.stopPropagation();
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
                    if (!card.isConnected || card.classList.contains('hidden')) return;
                    i = (i + 1) % urls.length;
                    paintCardPhoto(card, i);
                }, 1200));
            });
            gallery.addEventListener('mouseleave', () => {
                stopCardCycle(card);
                paintCardPhoto(card, card._galleryPinned);
            });
        });
    }

    function applyModerasiFilter() {
        const term = searchInput.value.trim().toLowerCase();
        const rows = rowsNow();
        const totalEl = holder.querySelector('[data-moderasi-total]');
        const total = Number(totalEl ? totalEl.getAttribute('data-moderasi-total') : rows.length);
        const emptySearch = holder.querySelector('#moderasi-empty-search');
        let visible = 0;

        rows.forEach((row) => {
            const show = !term || (row.getAttribute('data-search') || '').includes(term);
            row.classList.toggle('hidden', !show);
            if (show) visible++;
        });

        countEl.textContent = term ? visible : total;
        if (emptySearch) emptySearch.classList.toggle('hidden', visible > 0 || rows.length === 0);
        stopAllCardCycles();
    }

    let debounce;
    searchInput.addEventListener('input', () => {
        clearBtn.classList.toggle('opacity-0', !searchInput.value);
        clearTimeout(debounce);
        debounce = setTimeout(applyModerasiFilter, 200);
    });

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearBtn.classList.add('opacity-0');
        applyModerasiFilter();
    });

    // === Filter status via AJAX (tanpa refresh) ===
    let currentModerasiStatus = '{{ $activeStatus }}';

    function setModerasiFilterButtonState() {
        document.querySelectorAll('.moderasi-filter-btn').forEach((b) => {
            const active = b.getAttribute('data-status') === currentModerasiStatus;
            b.classList.toggle('bg-deep-onyx', active);
            b.classList.toggle('text-on-primary', active);
            b.classList.toggle('border-deep-onyx', active);
            b.classList.toggle('bg-surface-container-low', !active);
            b.classList.toggle('text-on-surface-variant', !active);
            b.classList.toggle('border-muted-border', !active);
        });
    }

    async function loadModerasiList(url) {
        stopAllCardCycles();
        const u = new URL(url, window.location.origin);
        u.searchParams.set('partial', '1');
        try {
            const res = await fetch(u.toString(), { headers: { 'Accept': 'text/html' } });
            if (!res.ok) throw new Error(res.status);
            holder.innerHTML = await res.text();
            initCardGalleries();
            applyModerasiFilter();
        } catch (err) {
            if (window.showRalivaToast) showRalivaToast('Gagal memuat daftar produk. Silakan coba lagi.', 'error');
        }
    }

    document.querySelectorAll('.moderasi-filter-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            currentModerasiStatus = btn.getAttribute('data-status');
            setModerasiFilterButtonState();
            loadModerasiList(modBaseUrl + '?status=' + encodeURIComponent(currentModerasiStatus));
        });
    });

    setModerasiFilterButtonState();
    initCardGalleries();
    applyModerasiFilter();
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
            <div class="flex gap-2 px-6 pt-4">
                <button type="button" data-mod-tab="produk" class="mod-tab-btn px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-colors bg-deep-onyx text-on-primary">Informasi Produk</button>
                <button type="button" data-mod-tab="bahan" class="mod-tab-btn px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-colors border border-muted-border text-on-surface-variant hover:text-on-surface">Informasi Bahan</button>
            </div>
            <div data-mod-panel="produk">
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
            <div data-mod-panel="bahan" class="hidden p-6">
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant mb-3">Bahan produksi (diinput Gudang)</p>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[420px] text-sm">
                        <thead>
                            <tr class="text-left text-[10px] uppercase tracking-widest text-on-surface-variant">
                                <th class="py-1 pr-2">Bahan</th>
                                <th class="py-1 pr-2 text-right">Jumlah / unit</th>
                                <th class="py-1 pr-2">Satuan</th>
                            </tr>
                        </thead>
                        <tbody id="mod-bahan-rows"></tbody>
                    </table>
                    <p id="mod-bahan-empty" class="hidden text-sm text-on-surface-variant py-4 text-center">Belum ada bahan untuk produk ini.</p>
                </div>
            </div>
        </div>

        <div class="shrink-0 border-t border-muted-border px-6 py-4 bg-surface/95 backdrop-blur flex gap-3">
            <p id="mod-action-note" class="hidden flex-1 self-center text-xs text-on-surface-variant italic">Keputusan sudah diambil untuk produk ini.</p>
            <p id="mod-action-slotfull" class="hidden flex-1 self-center text-xs text-error font-semibold">Kuota slot toko penuh — setujui hanya setelah slot ditambah.</p>
            <button id="mod-action-reject" type="button" onclick="openRejectModal()" class="hidden flex-1 py-3 bg-transparent border border-error/40 text-error font-label-sm text-label-sm uppercase tracking-widest hover:bg-error/10 transition-colors rounded-lg inline-flex items-center justify-center gap-1.5"><span class="material-symbols-outlined text-[18px]">block</span>Tolak</button>
            <form id="approve-product-form" class="flex-1" method="POST" action="" onsubmit="closeDetailModal()">
                @csrf
                <button id="mod-action-approve" type="submit" class="hidden w-full py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium inline-flex items-center justify-center gap-1.5"><span class="material-symbols-outlined text-[18px]">verified</span>Setujui Produk</button>
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
    @component('SuperAdmin.partials.premium-confirm', [
        'id' => 'rejectModal',
        'icon' => 'block',
        'close' => 'closeRejectModal',
        'dataModal' => true,
    ])
        <div class="p-6">
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Alasan Penolakan</h3>
            <p class="text-on-surface-variant text-sm text-center mb-4">Alasan dikirim ke <span id="mod-reject-store" class="font-bold text-on-surface">-</span>.</p>
            <textarea required minlength="10" maxlength="1000" name="alasan" id="reject-alasan-input" class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-4" placeholder="Tulis alasan di sini... (minimal 10 karakter)" rows="4"></textarea>
        </div>
        @slot('footer')
            <div class="flex space-x-3">
                <button type="button" class="flex-1 btn-modal btn-modal-ghost" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="flex-1 btn-modal btn-modal-danger">Konfirmasi</button>
            </div>
        @endslot
    @endcomponent
</form>
@endpush
