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
    <div class="flex items-center justify-center space-x-gutter mb-container-margin overflow-x-auto no-scrollbar py-2">
        <button class="font-label-sm text-label-sm px-4 py-2 border-b-2 border-primary text-primary transition-colors whitespace-nowrap">MENUNGGU (12)</button>
        <button class="font-label-sm text-label-sm px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap">DISETUJUI</button>
        <button class="font-label-sm text-label-sm px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface transition-colors whitespace-nowrap">DITOLAK</button>
    </div>
</div>
<div class="px-container-margin flex-grow">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-gutter gap-y-container-margin">
        <div class="group cursor-pointer flex flex-col transition-transform duration-300 hover:-translate-y-1" onclick="openDetailModal()">
            <div class="relative w-full aspect-[3/4] bg-surface-container-low mb-element-gap overflow-hidden rounded-lg">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAD17mbo2RMjQLUurWOk7oZNTqAPgUrUyNN2hXJFja0JUiv7N5fyAVB-RM_Gwf7YWS-zlWF4w8PVNCRoB-WIrtbXpRe0DACYYb9pkRwkRax9iKiMlmSc7DPKHzV98xsB4N4lhDoHWHn9hbwZtRVOutmfDKXPZe33TxnwiBp-2lCaek0XEV4Hh7Swi_FhekAiCYroSJd_0BhMpj4Q4LUBt4AlFR6P774tt5okifXIuW6dkaL9RD7XeNTKA" />
                <div class="absolute top-2 right-2 p-1 bg-surface/80 rounded"><span class="material-symbols-outlined text-[18px] text-on-surface">pending</span></div>
            </div>
            <div class="flex flex-col flex-grow"><span class="font-label-sm text-label-sm text-on-surface-variant mb-1">NOIRÉ STUDIO</span><h3 class="font-body-md text-body-md text-on-surface leading-tight mb-1 truncate">Oversized Linen Shirt</h3><div class="font-body-md text-body-md text-on-surface mt-auto">Rp 289.000</div></div>
        </div>
        <div class="group cursor-pointer flex flex-col transition-transform duration-300 hover:-translate-y-1" onclick="openDetailModal()">
            <div class="relative w-full aspect-[3/4] bg-surface-container-low mb-element-gap overflow-hidden rounded-lg">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCxZBoFsBSw3s9O0blHnCy0w_36DLAYcSiXSRfubszdCTT6D3UP9xbCFVJ3JfOZBEhvKauimi2-UFeA_QaKj-MRYmdkGLVyOyYYx1M6u2t35PrBhKj5dGu7b4GL9JI2Eo1wyuuqe1R4FiZ-dCt0Yw8Mwn3C1FnanQI7ctVL-Qjg_yGGFV9jbjskhYGL5XMp6H1Y-2e9n5rl8W7eOO7msph0i1bfZBzq4xBCrXAoRqPkWXCl-jwgFpGsJw" />
                <div class="absolute top-2 right-2 p-1 bg-surface/80 rounded"><span class="material-symbols-outlined text-[18px] text-on-surface">pending</span></div>
            </div>
            <div class="flex flex-col flex-grow"><span class="font-label-sm text-label-sm text-on-surface-variant mb-1">KAYANA APPAREL</span><h3 class="font-body-md text-body-md text-on-surface leading-tight mb-1 truncate">Straight Fit Pants</h3><div class="font-body-md text-body-md text-on-surface mt-auto">Rp 329.000</div></div>
        </div>
        <div class="group cursor-pointer flex flex-col transition-transform duration-300 hover:-translate-y-1" onclick="openDetailModal()">
            <div class="relative w-full aspect-[3/4] bg-surface-container-low mb-element-gap overflow-hidden rounded-lg">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5J3YOps85wsdu1QkE_7T3YAuqYPVpAvbNWjL7SxutlGXGyi9Pw4ortd7iCtouvyLSdvaEdhFQFY0wAjnT5zUVsj_UQ9XnS8UEy0hVfqXqDJZ5-A7c97y0no37fz1hwq5RfWOY6dgWsJCuGUYeopfsDE0NwB7FYINkx8FnNTSRD0zkx501NfnRk4DVy1OKPw2-vBcYOhsD_3l45HUA1E2NvPQztPw3T87yUR9DMq-kOF9ENheX8k9t6w" />
                <div class="absolute top-2 right-2 p-1 bg-surface/80 rounded"><span class="material-symbols-outlined text-[18px] text-on-surface">pending</span></div>
            </div>
            <div class="flex flex-col flex-grow"><span class="font-label-sm text-label-sm text-on-surface-variant mb-1">LUNARA FASHION</span><h3 class="font-body-md text-body-md text-on-surface leading-tight mb-1 truncate">Relaxed Blazer</h3><div class="font-body-md text-body-md text-on-surface mt-auto">Rp 579.000</div></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.remove('modal-enter');
        modal.classList.add('modal-enter-active');
        document.body.style.overflow = 'hidden';
    }
    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.remove('modal-enter-active');
        modal.classList.add('modal-enter');
        document.body.style.overflow = '';
    }
    function openRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.remove('modal-enter'); modal.classList.add('modal-enter-active'); }, 10);
    }
    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('modal-enter-active');
        modal.classList.add('modal-enter');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }
</script>
@endpush

@push('modals')
<!-- Detail Modal -->
<div class="fixed inset-0 z-[60] flex items-center justify-center p-4 modal-enter bg-scrim/50 backdrop-blur-sm" id="detailModal">
    <div class="bg-surface w-full max-w-2xl max-h-[795px] overflow-y-auto flex flex-col md:flex-row relative rounded-xl shadow-2xl">
        <button class="absolute top-4 right-4 z-10 p-2 bg-surface/80 rounded-full hover:bg-surface transition-colors" onclick="closeDetailModal()"><span class="material-symbols-outlined text-on-surface">close</span></button>
        <div class="w-full md:w-1/2 aspect-[3/4] md:aspect-auto bg-surface-container-low"><img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDyCcrmTRfGfsxs_N3ci9i5HchBHGyO7-NS4KaH08rxg5KEVTQHbujUhwRjP9Cx_oAJKlFkLeJNg41S_nDTbkkzwgSEQfWZ9NXhtb_992MQQ44WF2XQZesWGCO4i0dPy_0JlpR_71A7X9_EyfbAghnliaoPC73GgYX51dz9qI45IIUycAoqM8RVEla78vBAU5SiPyPHbzn330s4nqHZ6svj6g_3f2NDhPd_5gqbC1NfSCUDBM-qSUzc-w" /></div>
        <div class="w-full md:w-1/2 p-container-margin flex flex-col">
            <div class="mb-auto">
                <div class="flex items-center justify-between mb-2"><span class="font-label-sm text-label-sm text-on-surface-variant uppercase">NOIRÉ STUDIO</span><span class="px-2 py-1 bg-surface-container-high text-on-surface font-label-sm text-[10px] uppercase">Menunggu</span></div>
                <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-2">Oversized Linen Shirt</h2>
                <div class="font-title-md text-title-md text-on-surface mb-container-margin">Rp 289.000</div>
                <div class="space-y-4 mb-section-gap">
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Kategori</span><span class="font-body-md text-body-md text-on-surface">Pakaian Wanita > Atasan</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Warna</span><div class="flex space-x-2"><div class="w-6 h-6 rounded-full bg-white border border-outline-variant"></div><div class="w-6 h-6 rounded-full bg-black"></div><div class="w-6 h-6 rounded-full bg-[#d2b48c]"></div></div></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Ukuran</span><span class="font-body-md text-body-md text-on-surface">S, M, L, XL</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Deskripsi</span><p class="font-body-md text-body-md text-on-surface-variant leading-relaxed text-sm">Kemeja linen oversized serbaguna dengan kerah rapi, kancing depan, dan bahu jatuh untuk potongan santai. Terbuat dari linen Eropa 100% yang bernapas.</p></div>
                </div>
            </div>
            <div class="flex flex-col space-y-3 mt-container-margin pt-container-margin border-t border-outline-variant">
                <button class="w-full bg-deep-onyx text-on-primary font-label-sm text-label-sm py-4 uppercase tracking-widest hover:bg-black transition-colors btn-premium" onclick="alert('Produk disetujui')">Setujui Produk</button>
                <button class="w-full bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-4 uppercase tracking-widest hover:bg-surface-container-low transition-colors" onclick="openRejectModal()">Tolak</button>
            </div>
        </div>
    </div>
</div>
<!-- Reject Reason Modal -->
<div class="fixed inset-0 z-[70] flex items-center justify-center p-4 modal-enter bg-scrim/50 backdrop-blur-sm hidden" id="rejectModal">
    <div class="bg-surface w-full max-w-md p-container-margin relative rounded-xl shadow-2xl">
        <h3 class="font-title-md text-title-md text-on-surface mb-4">Alasan Penolakan</h3>
        <textarea class="w-full border border-outline-variant bg-transparent p-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-primary mb-4" placeholder="Tulis alasan di sini..." rows="4"></textarea>
        <div class="flex space-x-4">
            <button class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors" onclick="closeRejectModal()">Batal</button>
            <button class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity" onclick="alert('Produk ditolak'); closeRejectModal(); closeDetailModal();">Konfirmasi Penolakan</button>
        </div>
    </div>
</div>
@endpush