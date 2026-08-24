@extends('layouts.superadmin')

@section('title', 'Moderasi Produk')

@section('header-title', 'Moderasi Produk')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Tinjau produk pending dan beri keputusan setujui atau tolak beserta alasan.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
    .filled-icon { font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
    .modal-enter { opacity: 0; pointer-events: none; }
    .modal-enter-active { opacity: 1; pointer-events: auto; transition: opacity 0.3s ease; }
    .modal-exit { opacity: 1; pointer-events: auto; }
    .modal-exit-active { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
</style>
@endpush

@section('content')
<div class="px-container-margin pb-element-gap">
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Moderasi</span>
        </div>
        <div class="flex items-center justify-center space-x-gutter overflow-x-auto no-scrollbar py-2">
            <button type="button" data-moderasi-tab="menunggu" class="font-label-sm text-label-sm px-4 py-2 border-b-2 border-primary text-primary transition-colors whitespace-nowrap">MENUNGGU (3)</button>
            <button type="button" data-moderasi-tab="disetujui" class="font-label-sm text-label-sm px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap">DISETUJUI</button>
            <button type="button" data-moderasi-tab="ditolak" class="font-label-sm text-label-sm px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap">DITOLAK</button>
        </div>
    </div>
</div>
<div class="px-container-margin flex-grow">
    <div id="moderasi-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-gutter gap-y-container-margin">
        <div class="group cursor-pointer flex flex-col transition-transform duration-300 hover:-translate-y-1" onclick="openDetailModal(this)" data-name="Oversized Linen Shirt" data-store="NOIRÉ STUDIO" data-price="Rp 289.000">
            <div class="relative w-full aspect-[3/4] bg-surface-container-low mb-element-gap overflow-hidden rounded-lg">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAD17mbo2RMjQLUurWOk7oZNTqAPgUrUyNN2hXJFja0JUiv7N5fyAVB-RM_Gwf7YWS-zlWF4w8PVNCRoB-WIrtbXpRe0DACYYb9pkRwkRax9iKiMlmSc7DPKHzV98xsB4N4lhDoHWHn9hbwZtRVOutmfDKXPZe33TxnwiBp-2lCaek0XEV4Hh7Swi_FhekAiCYroSJd_0BhMpj4Q4LUBt4AlFR6P774tt5okifXIuW6dkaL9RD7XeNTKA" />
                <div class="absolute top-2 right-2 p-1 bg-surface/80 rounded"><span class="material-symbols-outlined text-[18px] text-on-surface">pending</span></div>
            </div>
            <div class="flex flex-col flex-grow"><span class="font-label-sm text-label-sm text-on-surface-variant mb-1">NOIRÉ STUDIO</span><h3 class="font-body-md text-body-md text-on-surface leading-tight mb-1 truncate">Oversized Linen Shirt</h3><div class="font-body-md text-body-md text-on-surface mt-auto">Rp 289.000</div></div>
        </div>
        <div class="group cursor-pointer flex flex-col transition-transform duration-300 hover:-translate-y-1" onclick="openDetailModal(this)" data-name="Straight Fit Pants" data-store="KAYANA APPAREL" data-price="Rp 329.000">
            <div class="relative w-full aspect-[3/4] bg-surface-container-low mb-element-gap overflow-hidden rounded-lg">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCxZBoFsBSw3s9O0blHnCy0w_36DLAYcSiXSRfubszdCTT6D3UP9xbCFVJ3JfOZBEhvKauimi2-UFeA_QaKj-MRYmdkGLVyOyYYx1M6u2t35PrBhKj5dGu7b4GL9JI2Eo1wyuuqe1R4FiZ-dCt0Yw8Mwn3C1FnanQI7ctVL-Qjg_yGGFV9jbjskhYGL5XMp6H1Y-2e9n5rl8W7eOO7msph0i1bfZBzq4xBCrXAoRqPkWXCl-jwgFpGsJw" />
                <div class="absolute top-2 right-2 p-1 bg-surface/80 rounded"><span class="material-symbols-outlined text-[18px] text-on-surface">pending</span></div>
            </div>
            <div class="flex flex-col flex-grow"><span class="font-label-sm text-label-sm text-on-surface-variant mb-1">KAYANA APPAREL</span><h3 class="font-body-md text-body-md text-on-surface leading-tight mb-1 truncate">Straight Fit Pants</h3><div class="font-body-md text-body-md text-on-surface mt-auto">Rp 329.000</div></div>
        </div>
        <div class="group cursor-pointer flex flex-col transition-transform duration-300 hover:-translate-y-1" onclick="openDetailModal(this)" data-name="Relaxed Blazer" data-store="LUNARA FASHION" data-price="Rp 579.000">
            <div class="relative w-full aspect-[3/4] bg-surface-container-low mb-element-gap overflow-hidden rounded-lg">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5J3YOps85wsdu1QkE_7T3YAuqYPVpAvbNWjL7SxutlGXGyi9Pw4ortd7iCtouvyLSdvaEdhFQFY0wAjnT5zUVsj_UQ9XnS8UEy0hVfqXqDJZ5-A7c97y0no37fz1hwq5RfWOY6dgWsJCuGUYeopfsDE0NwB7FYINkx8FnNTSRD0zkx501NfnRk4DVy1OKPw2-vBcYOhsD_3l45HUA1E2NvPQztPw3T87yUR9DMq-kOF9ENheX8k9t6w" />
                <div class="absolute top-2 right-2 p-1 bg-surface/80 rounded"><span class="material-symbols-outlined text-[18px] text-on-surface">pending</span></div>
            </div>
            <div class="flex flex-col flex-grow"><span class="font-label-sm text-label-sm text-on-surface-variant mb-1">LUNARA FASHION</span><h3 class="font-body-md text-body-md text-on-surface leading-tight mb-1 truncate">Relaxed Blazer</h3><div class="font-body-md text-body-md text-on-surface mt-auto">Rp 579.000</div></div>
        </div>
    </div>
    <p id="moderasi-kosong" class="hidden text-center text-on-surface-variant font-body-md text-sm py-16">Belum ada produk pada status ini.</p>
</div>
@endsection

@push('scripts')
<script>
        let activeProductCard = null;

    function openDetailModal(card) {
        activeProductCard = card;
        document.getElementById('mod-name').textContent = card.dataset.name;
        document.getElementById('mod-store').textContent = card.dataset.store;
        document.getElementById('mod-price').textContent = card.dataset.price;
        const img = card.querySelector('img');
        document.getElementById('mod-img').src = img ? img.src : '';
        document.getElementById('mod-reject-store').textContent = card.dataset.store;
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function openRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function approveActiveProduct() {
        if (!activeProductCard) return;
        closeDetailModal();
        showRalivaToast('Produk ' + activeProductCard.dataset.name + ' disetujui dan tayang di marketplace.', 'task_alt');
    }

    function confirmRejectProduct() {
        if (!activeProductCard) return;
        closeRejectModal();
        closeDetailModal();
        showRalivaToast('Produk ' + activeProductCard.dataset.name + ' ditolak. Alasan dikirim ke toko.', 'block');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeDetailModal(); closeRejectModal(); }
    });

    const moderasiTabs = document.querySelectorAll('[data-moderasi-tab]');
    const moderasiGrid = document.getElementById('moderasi-grid');
    const moderasiKosong = document.getElementById('moderasi-kosong');

    moderasiTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            moderasiTabs.forEach((t) => {
                t.classList.add('border-transparent', 'text-on-surface-variant', 'hover:text-on-surface');
                t.classList.remove('border-primary', 'text-primary');
            });
            tab.classList.remove('border-transparent', 'text-on-surface-variant', 'hover:text-on-surface');
            tab.classList.add('border-primary', 'text-primary');

            const status = tab.getAttribute('data-moderasi-tab');
            const hasContent = status === 'menunggu';
            moderasiGrid?.classList.toggle('hidden', !hasContent);
            moderasiKosong?.classList.toggle('hidden', hasContent);
            moderasiKosong?.classList.toggle('flex', !hasContent);
        });
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
                    <span id="mod-store" class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">NOIRÉ STUDIO</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30">Menunggu</span>
                </div>
                <h2 id="mod-name" class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface leading-tight">Oversized Linen Shirt</h2>
                <div id="mod-price" class="font-title-md text-title-md text-gold-accent mt-1">Rp 289.000</div>
            </div>
            <button type="button" class="absolute top-4 right-4 z-10 p-2 rounded-full hover:bg-surface-container-high transition-colors" onclick="closeDetailModal()"><span class="material-symbols-outlined text-on-surface">close</span></button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div class="grid md:grid-cols-2 gap-0">
                <div class="bg-surface-container-low min-h-[220px]"><img id="mod-img" class="w-full h-full object-cover" src="" alt="Foto produk" /></div>
                <div class="p-6 space-y-4">
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Kategori</span><span class="font-body-md text-body-md text-on-surface">Pakaian Wanita > Atasan</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Warna</span><div class="flex space-x-2"><div class="w-6 h-6 rounded-full bg-white border border-outline-variant"></div><div class="w-6 h-6 rounded-full bg-black"></div><div class="w-6 h-6 rounded-full bg-[#d2b48c]"></div></div></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Ukuran</span><span class="font-body-md text-body-md text-on-surface">S, M, L, XL</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Deskripsi</span><p class="font-body-md text-body-md text-on-surface-variant leading-relaxed text-sm">Kemeja linen oversized serbaguna dengan kerah rapi, kancing depan, dan bahu jatuh untuk potongan santai. Terbuat dari linen Eropa 100% yang bernapas.</p></div>
                </div>
            </div>
        </div>

        <div class="shrink-0 border-t border-muted-border px-6 py-4 bg-surface/95 backdrop-blur flex gap-3">
            <button type="button" onclick="openRejectModal()" class="flex-1 py-3 bg-transparent border border-error/40 text-error font-label-sm text-label-sm uppercase tracking-widest hover:bg-error/10 transition-colors rounded-lg">Tolak</button>
            <button type="button" onclick="approveActiveProduct()" class="flex-1 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">Setujui Produk</button>
        </div>
    </div>
</div>
<!-- Reject Reason Modal -->
<div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="rejectModal">
    <div class="relative bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
        <div class="p-8">
            <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-error text-[28px]">block</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Alasan Penolakan</h3>
            <p class="text-on-surface-variant text-sm text-center mb-4">Alasan dikirim ke <span id="mod-reject-store" class="font-bold text-on-surface">NOIRÉ STUDIO</span>.</p>
            <textarea class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-4" placeholder="Tulis alasan di sini..." rows="4"></textarea>
            <div class="flex space-x-3">
                <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeRejectModal()">Batal</button>
                <button type="button" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium" onclick="confirmRejectProduct()">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>
@endpush