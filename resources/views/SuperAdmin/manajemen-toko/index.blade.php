@extends('layouts.superadmin')

@section('title', 'Manajemen Toko')

@section('header-title', 'Data Toko')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Verifikasi, tolak, tangguhkan, dan aktifkan kembali toko penjual.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.filled { font-variation-settings: 'FILL' 1; }

    .toko-card { transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease; }
    .toko-card:hover .toko-detail-hint { opacity: 1; transform: translateX(0); }
    .toko-detail-hint { opacity: 0; transform: translateX(-6px); transition: all 0.25s ease; }
</style>
@endpush

@section('content')
<div id="toko-tabs" class="flex flex-wrap gap-3 border-b border-muted-border pb-4">
    <button type="button" data-toko-tab="semua" class="px-4 py-2 border-b-2 border-primary text-primary font-label-sm uppercase tracking-wider transition-colors">Semua</button>
    <button type="button" data-toko-tab="menunggu" class="px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-sm uppercase tracking-wider">Menunggu</button>
    <button type="button" data-toko-tab="aktif" class="px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-sm uppercase tracking-wider">Aktif</button>
    <button type="button" data-toko-tab="ditangguhkan" class="px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-sm uppercase tracking-wider">Ditangguhkan</button>
</div>

<section class="px-gutter md:px-container-margin py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">

        <article data-status="aktif" data-name="LUNARA Fashion" data-initial="LF" data-owner="Elara Vance" data-joined="12 Mar 2025" data-location="Bandung, ID" data-products="124" data-orders="892" data-rating="4.9" data-desc="Fashion feminin modern dengan bahan lokal premium. Andalan: blouse linen dan dress kasual untuk pemakaian harian." onclick="openStoreModal(this)" class="toko-card group bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden cursor-pointer card-premium">
            <div class="h-1 w-full bg-gradient-to-r from-gold-accent via-gold-accent/40 to-transparent"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden ring-2 ring-gold-accent/25 flex-shrink-0">
                            <img class="w-full h-full object-cover" alt="Logo LUNARA Fashion" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAi0PISoKVztJcbKTXivcpR48A-jBk4Fdi5zQVd41-FvdVMMoRQNOdJBsy0RvmapGdtun9Dx0EshdJ7gfZ0jnDQ_tCy70tuZHtgbuHX03CulLr9e2k7WMtl850qaeiBdwVigzBqcjhBiK1gnxiC398qn9tmbTb4OD_SjW5JeMA7NWv0eWaLgyhH04lzQHQncjvxTbi462sBsLxjhrSdlirfPIudzf7JTh8CwiQlzbiYDcxHDmxzfBqhGg" />
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-title-md text-title-md text-on-surface truncate">LUNARA Fashion</h3>
                            <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mt-1 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Elara Vance</p>
                        </div>
                    </div>
                    <span data-badge class="inline-flex items-center px-2.5 py-1 bg-secondary-container/20 text-secondary text-[10px] font-bold tracking-widest uppercase border border-secondary/20 rounded-full shrink-0">Aktif</span>
                </div>
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md text-on-surface">124</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Produk</span>
                    </div>
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md text-on-surface">892</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Pesanan</span>
                    </div>
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md text-on-surface flex items-center justify-center gap-1">4.9 <span class="material-symbols-outlined text-[14px] filled text-secondary">star</span></span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Rating</span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="toko-detail-hint font-label-sm text-[11px] uppercase tracking-widest text-gold-accent inline-flex items-center gap-1">Lihat Detail <span class="material-symbols-outlined text-[14px]">arrow_forward</span></span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">place</span>Bandung</span>
                </div>
            </div>
        </article>

        <article data-status="menunggu" data-name="NOIRÉ Studio" data-initial="NS" data-owner="Julian Thorne" data-joined="24 Okt 2023" data-location="Jakarta, ID" data-products="12" data-orders="--" data-rating="--" data-desc="Koleksi esensial minimalis sehari-hari dengan bahan berkelanjutan. Fokus pada siluet timeless yang mudah dipadukan." onclick="openStoreModal(this)" class="toko-card group bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden cursor-pointer card-premium">
            <div class="h-1 w-full bg-gradient-to-r from-gold-accent via-gold-accent/40 to-transparent"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-14 h-14 rounded-2xl bg-surface-container-high ring-2 ring-gold-accent/25 flex items-center justify-center flex-shrink-0">
                            <span class="font-title-md text-on-surface-variant">NS</span>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-title-md text-title-md text-on-surface truncate">NOIRÉ Studio</h3>
                            <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mt-1 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-gold-accent animate-pulse"></span>Julian Thorne</p>
                        </div>
                    </div>
                    <span data-badge class="inline-flex items-center px-2.5 py-1 bg-gold-accent/10 text-gold-accent text-[10px] font-bold tracking-widest uppercase border border-gold-accent/30 rounded-full shrink-0">Menunggu</span>
                </div>
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md text-on-surface">12</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Produk</span>
                    </div>
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md text-on-surface-variant">--</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Pesanan</span>
                    </div>
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md text-on-surface-variant">--</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Rating</span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="toko-detail-hint font-label-sm text-[11px] uppercase tracking-widest text-gold-accent inline-flex items-center gap-1">Lihat Detail <span class="material-symbols-outlined text-[14px]">arrow_forward</span></span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">place</span>Jakarta</span>
                </div>
            </div>
        </article>

        <article data-status="ditangguhkan" data-name="KAYANA Apparel" data-initial="KA" data-owner="Maya Rossi" data-joined="08 Feb 2024" data-location="Surabaya, ID" data-products="45" data-orders="112" data-rating="3.2" data-desc="Streetwear eklektik produksi terbatas dengan sablon manual dan potongan oversized." onclick="openStoreModal(this)" class="toko-card group bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden cursor-pointer card-premium">
            <div class="h-1 w-full bg-gradient-to-r from-error/50 via-error/20 to-transparent"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden ring-2 ring-error/25 grayscale flex-shrink-0">
                            <img class="w-full h-full object-cover" alt="Logo KAYANA Apparel" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBUgNmup5rkRgv3EapVhf-b012EHAn4p6eSWo3kuXhP8w84jNkEOJaXF1xilHpASOQfk1znipKa92wFdpahG8ATrWvOWnMR0Gsv-0HuEe0YdLuN7gE2y5hqEJLurSYwy5qW4Yx1wMaSVouoOJyCFc7Jmtyy9y10mY-jv8jNgNrP0GfjD2utgH9u8lzHGo0GWMjR-x7p16V3mEjioYs_Cwlvrt0V2LLKt8uL1rUPswjftzoCFzKegipEzA" />
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-title-md text-title-md text-on-surface line-through decoration-on-surface-variant truncate">KAYANA Apparel</h3>
                            <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mt-1 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-error"></span>Maya Rossi</p>
                        </div>
                    </div>
                    <span data-badge class="inline-flex items-center px-2.5 py-1 bg-error/10 text-error text-[10px] font-bold tracking-widest uppercase border border-error/20 rounded-full shrink-0">Ditangguhkan</span>
                </div>
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md text-on-surface-variant">45</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Produk</span>
                    </div>
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md text-on-surface-variant">112</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Pesanan</span>
                    </div>
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md text-on-surface-variant flex items-center justify-center gap-1">3.2 <span class="material-symbols-outlined text-[14px] filled">star</span></span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Rating</span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="toko-detail-hint font-label-sm text-[11px] uppercase tracking-widest text-gold-accent inline-flex items-center gap-1">Lihat Detail <span class="material-symbols-outlined text-[14px]">arrow_forward</span></span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">place</span>Surabaya</span>
                </div>
            </div>
        </article>
    </div>
    <p id="toko-kosong" class="hidden text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada toko pada status ini.</p>
</section>
@endsection

@push('scripts')
<script>
    const statusMeta = {
        aktif: {
            badgeLabel: 'Aktif',
            badgeClass: 'inline-flex items-center px-2.5 py-1 bg-secondary-container/20 text-secondary text-[10px] font-bold tracking-widest uppercase border border-secondary/20 rounded-full shrink-0',
            chipLabel: 'Aktif',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-secondary-container/20 text-secondary border-secondary/20',
            actionLabel: 'Tangguhkan Toko',
            verification: 'Terverifikasi'
        },
        menunggu: {
            badgeLabel: 'Menunggu',
            badgeClass: 'inline-flex items-center px-2.5 py-1 bg-gold-accent/10 text-gold-accent text-[10px] font-bold tracking-widest uppercase border border-gold-accent/30 rounded-full shrink-0',
            chipLabel: 'Menunggu Tinjauan',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-gold-accent/10 text-gold-accent border-gold-accent/30',
            actionLabel: 'Setujui Toko',
            verification: 'Dokumen lengkap • Menunggu review'
        },
        ditangguhkan: {
            badgeLabel: 'Ditangguhkan',
            badgeClass: 'inline-flex items-center px-2.5 py-1 bg-error/10 text-error text-[10px] font-bold tracking-widest uppercase border border-error/20 rounded-full shrink-0',
            chipLabel: 'Ditangguhkan',
            chipClass: 'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-error/10 text-error border-error/20',
            actionLabel: 'Aktifkan Kembali',
            verification: ' Ditangguhkan oleh Admin'
        }
    };

    let activeCard = null;
    let currentTokoTab = 'semua';

    function openStoreModal(card) {
        activeCard = card;
        const d = card.dataset;
        const meta = statusMeta[d.status];

        document.getElementById('store-avatar').textContent = d.initial;
        document.getElementById('modal-title').textContent = d.name;
        const chip = document.getElementById('store-status-chip');
        chip.textContent = meta.chipLabel;
        chip.className = meta.chipClass;
        document.getElementById('store-meta').textContent = 'Bergabung ' + d.joined + ' \u2022 ' + d.location;
        document.getElementById('stat-products').textContent = d.products;
        document.getElementById('stat-orders').textContent = d.orders;
        document.getElementById('stat-rating').textContent = d.rating;
        document.getElementById('store-desc').textContent = d.desc;
        document.getElementById('info-owner').textContent = d.owner;
        document.getElementById('info-location').textContent = d.location;
        document.getElementById('info-joined').textContent = d.joined;
        document.getElementById('info-verification').textContent = meta.verification;

        document.getElementById('store-action-main').textContent = meta.actionLabel;
        document.getElementById('store-action-reject').classList.toggle('hidden', d.status !== 'menunggu');

        document.getElementById('store-modal-scroll').scrollTop = 0;
        const modal = document.getElementById('store-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeStoreModal() {
        const modal = document.getElementById('store-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function setCardStatus(card, status) {
        card.dataset.status = status;
        const meta = statusMeta[status];
        const badge = card.querySelector('[data-badge]');
        badge.textContent = meta.badgeLabel;
        badge.className = meta.badgeClass;
        applyTokoFilter();
    }

    function applyStoreAction() {
        if (!activeCard) return;
        const status = activeCard.dataset.status;
        const name = activeCard.dataset.name;
        if (status === 'menunggu') {
            setCardStatus(activeCard, 'aktif');
            showRalivaToast('Toko ' + name + ' disetujui dan kini aktif.', 'task_alt');
        } else if (status === 'aktif') {
            setCardStatus(activeCard, 'ditangguhkan');
            showRalivaToast('Toko ' + name + ' ditangguhkan.', 'block');
        } else {
            setCardStatus(activeCard, 'aktif');
            showRalivaToast('Toko ' + name + ' diaktifkan kembali.', 'task_alt');
        }
        closeStoreModal();
    }

    function openRejectModal() {
        if (!activeCard) return;
        document.getElementById('reject-store-name').textContent = activeCard.dataset.name;
        const modal = document.getElementById('reject-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmRejectStore() {
        if (!activeCard) return;
        const name = activeCard.dataset.name;
        setCardStatus(activeCard, 'ditangguhkan');
        closeRejectModal();
        closeStoreModal();
        showRalivaToast('Toko ' + name + ' ditolak. Alasan dikirim ke pemilik toko.', 'block');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeStoreModal(); closeRejectModal(); }
    });

    const tokoTabs = document.querySelectorAll('[data-toko-tab]');
    const tokoCards = document.querySelectorAll('.toko-card');

    function applyTokoFilter() {
        let visible = 0;
        tokoCards.forEach((card) => {
            const show = currentTokoTab === 'semua' || card.getAttribute('data-status') === currentTokoTab;
            card.classList.toggle('hidden', !show);
            if (show) visible++;
        });
        document.getElementById('toko-kosong')?.classList.toggle('hidden', visible > 0);
    }

    tokoTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            tokoTabs.forEach((t) => {
                t.classList.add('border-transparent', 'text-on-surface-variant');
                t.classList.remove('border-primary', 'text-primary');
            });
            tab.classList.remove('border-transparent', 'text-on-surface-variant');
            tab.classList.add('border-primary', 'text-primary');
            currentTokoTab = tab.getAttribute('data-toko-tab');
            applyTokoFilter();
        });
    });
</script>
@endpush

@push('modals')
<div aria-labelledby="modal-title" aria-modal="true" role="dialog" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 md:p-6 bg-black/50 backdrop-blur-sm" id="store-modal" onclick="if (event.target === this) closeStoreModal()">
    <div class="relative z-10 w-full max-w-3xl h-[min(795px,92vh)] bg-surface-container-lowest rounded-xl border border-muted-border shadow-2xl flex flex-col overflow-hidden">

        <div class="shrink-0 relative border-b border-muted-border">
            <div class="absolute inset-0 bg-gradient-to-r from-gold-accent/15 via-gold-accent/5 to-transparent pointer-events-none"></div>
            <div class="relative flex items-start justify-between gap-4 p-6 md:p-7">
                <div class="flex items-center gap-5 min-w-0">
                    <div id="store-avatar" class="w-16 h-16 rounded-2xl bg-surface-container-high border-2 border-gold-accent/40 shadow-lg flex items-center justify-center font-title-md text-lg text-on-surface shrink-0">NS</div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h2 class="font-display-lg text-headline-lg-mobile md:text-headline-lg truncate" id="modal-title">NOIRÉ Studio</h2>
                            <span class="material-symbols-outlined filled text-secondary text-[20px]" title="Bisnis Terverifikasi">verified</span>
                        </div>
                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                            <span id="store-status-chip" class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border bg-gold-accent/10 text-gold-accent border-gold-accent/30">Menunggu Tinjauan</span>
                            <span id="store-meta" class="text-xs text-on-surface-variant">Bergabung 24 Okt 2023 • Jakarta, ID</span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="closeStoreModal()" class="text-on-surface-variant hover:text-on-surface transition-colors p-2 -mr-2 shrink-0"><span class="material-symbols-outlined">close</span></button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-8" id="store-modal-scroll">
            <div class="grid grid-cols-3 gap-gutter">
                <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                    <span class="block font-headline-lg-mobile text-headline-lg-mobile text-on-surface" id="stat-products">12</span>
                    <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Produk Aktif</span>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/10 pointer-events-none select-none">checkroom</span>
                </div>
                <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                    <span class="block font-headline-lg-mobile text-headline-lg-mobile text-on-surface" id="stat-orders">--</span>
                    <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Total Pesanan</span>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/10 pointer-events-none select-none">shopping_bag</span>
                </div>
                <div class="relative overflow-hidden bg-surface-container-low border border-muted-border rounded-lg p-4 text-center">
                    <span class="block font-headline-lg-mobile text-headline-lg-mobile text-on-surface flex items-center justify-center gap-1"><span id="stat-rating">--</span><span class="material-symbols-outlined text-[18px] filled text-secondary">star</span></span>
                    <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-1">Rating Toko</span>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[44px] text-gold-accent/10 pointer-events-none select-none">reviews</span>
                </div>
            </div>

            <section>
                <h4 class="font-title-md text-title-md mb-3 uppercase tracking-wider text-on-surface premium-heading">Deskripsi Toko</h4>
                <p id="store-desc" class="font-body-md text-body-md text-on-surface-variant leading-relaxed max-w-2xl">Koleksi esensial minimalis sehari-hari dengan bahan berkelanjutan.</p>
            </section>

            <section>
                <h4 class="font-title-md text-title-md mb-4 uppercase tracking-wider text-on-surface premium-heading">Informasi Toko</h4>
                <div class="grid sm:grid-cols-2 gap-gutter">
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">person</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Pemilik</span><span id="info-owner" class="font-title-md text-title-md text-on-surface block truncate">Julian Thorne</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">calendar_month</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Bergabung</span><span id="info-joined" class="font-title-md text-title-md text-on-surface block truncate">24 Okt 2023</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">place</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Lokasi</span><span id="info-location" class="font-title-md text-title-md text-on-surface block truncate">Jakarta, ID</span></div>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">fact_check</span></div>
                        <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Verifikasi</span><span id="info-verification" class="font-title-md text-title-md text-on-surface block truncate">Menunggu review</span></div>
                    </div>
                </div>
            </section>

            <section>
                <h4 class="font-title-md text-title-md mb-4 uppercase tracking-wider text-on-surface premium-heading">Dokumen Verifikasi</h4>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between gap-4 p-4 border border-muted-border bg-surface-container-lowest rounded-lg hover:border-gold-accent/40 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-on-surface-variant text-[20px]">description</span></div>
                            <div class="min-w-0"><p class="font-body-md text-sm text-on-surface truncate">Izin_Usaha.pdf</p><p class="text-xs text-on-surface-variant">PDF • 1,2 MB • Diunggah 20 Okt 2023</p></div>
                        </div>
                        <button type="button" onclick="showRalivaToast('Pratinjau dokumen demo tidak tersedia.', 'description')" class="text-gold-accent font-label-sm text-[10px] uppercase tracking-wider hover:underline shrink-0">Lihat</button>
                    </li>
                    <li class="flex items-center justify-between gap-4 p-4 border border-muted-border bg-surface-container-lowest rounded-lg hover:border-gold-accent/40 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-on-surface-variant text-[20px]">badge</span></div>
                            <div class="min-w-0"><p class="font-body-md text-sm text-on-surface truncate">KTP_Pemilik.jpg</p><p class="text-xs text-on-surface-variant">JPG • 840 KB • Diunggah 20 Okt 2023</p></div>
                        </div>
                        <button type="button" onclick="showRalivaToast('Pratinjau dokumen demo tidak tersedia.', 'badge')" class="text-gold-accent font-label-sm text-[10px] uppercase tracking-wider hover:underline shrink-0">Lihat</button>
                    </li>
                </ul>
            </section>
        </div>

        <div class="shrink-0 border-t border-muted-border bg-surface/95 backdrop-blur px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px] text-gold-accent">history</span>
                Keputusan tercatat di riwayat aktivitas
            </p>
            <div class="flex gap-3 w-full sm:w-auto">
                <button id="store-action-reject" type="button" onclick="openRejectModal()" class="flex-1 sm:flex-none px-6 py-3 border border-error/40 text-error font-label-sm text-label-sm uppercase tracking-wider rounded-lg hover:bg-error/10 transition-colors">Tolak</button>
                <button id="store-action-main" type="button" onclick="applyStoreAction()" class="flex-1 sm:flex-none px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-wider rounded-lg btn-premium">Setujui Toko</button>
            </div>
        </div>
    </div>
</div>

<div aria-labelledby="reject-title" aria-modal="true" role="dialog" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="reject-modal" onclick="if (event.target === this) closeRejectModal()">
    <div class="relative z-10 bg-surface-container-lowest w-full max-w-md border border-muted-border rounded-xl shadow-2xl overflow-hidden">
        <div class="p-8">
            <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-error text-[28px]">gpp_bad</span>
            </div>
            <h3 class="font-display-lg text-headline-lg-mobile text-center mb-2" id="reject-title">Tolak Toko</h3>
            <p class="text-on-surface-variant text-sm text-center mb-6">Berikan alasan penolakan untuk <span id="reject-store-name" class="font-bold text-on-surface">NOIRÉ Studio</span>. Pesan ini akan dikirim ke pemilik toko.</p>
            <textarea class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-sm focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-6 min-h-[120px] resize-none" placeholder="Misal: Dokumen izin usaha belum lengkap..."></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" class="px-4 py-2.5 text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider hover:text-on-surface transition-colors" onclick="closeRejectModal()">Batal</button>
                <button type="button" onclick="confirmRejectStore()" class="px-6 py-2.5 bg-error text-on-error font-label-sm text-[11px] uppercase tracking-wider rounded-lg hover:opacity-90 transition-opacity btn-premium">Konfirmasi Penolakan</button>
            </div>
        </div>
    </div>
</div>
@endpush
