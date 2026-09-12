@push('modals')
<!-- Gallery Modal (read-only) -->
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
            <button type="button" class="absolute top-4 right-4 z-10 p-2 rounded-full hover:bg-surface-container-low transition-colors" onclick="closeGalleryModal()"><span class="material-symbols-outlined text-on-surface">close</span></button>
        </div>

        <div class="flex-1 overflow-y-auto">
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
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Status</span><span id="mod-status" class="font-body-md text-body-md text-on-surface">-</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Deskripsi</span><p id="mod-desc" class="font-body-md text-body-md text-on-surface-variant leading-relaxed text-sm">-</p></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Foto -->
<div class="fixed inset-0 z-[80] hidden items-center justify-center p-4 bg-black/80" id="lightbox" onclick="if (event.target === this) closeLightbox()">
    <img id="lightbox-img" class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg" src="" alt="Foto produk" />
    <span id="lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-xs font-bold bg-black/50 px-2.5 py-1 rounded-full">-</span>
    <button type="button" class="absolute top-4 right-4 p-2 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors z-10" onclick="closeLightbox()"><span class="material-symbols-outlined">close</span></button>
</div>
@endpush

@push('scripts')
<script>
    let activeGallery = [];

    function resolveSrc(src) {
        if (!src) return '';
        if (src.startsWith('http')) return src;
        if (src.startsWith('/')) return src;
        if (src.startsWith('storage/')) return '/' + src;
        return '/storage/' + src.replace(/^\/+/, '');
    }

    function openProdukGallery(card) {
        const d = card.dataset;
        document.getElementById('mod-name').textContent = d.name || '-';
        document.getElementById('mod-store').textContent = (d.store || '-').toUpperCase();
        document.getElementById('mod-price').textContent = d.price || '-';
        document.getElementById('mod-category').textContent = d.category || '-';
        document.getElementById('mod-tipe').textContent = d.tipe || '-';
        document.getElementById('mod-variants').textContent = d.variants || '-';
        document.getElementById('mod-desc').textContent = d.desc || '-';
        document.getElementById('mod-status').textContent = d.statusLabel || '-';

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

        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeGalleryModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        closeLightbox();
        document.body.style.overflow = '';
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
        if (event.key === 'Escape') { closeLightbox(); closeGalleryModal(); }
    });
</script>
@endpush