@extends('layouts.admin')

@section('title', 'Data Produk')

@section('header-title', 'Data Produk')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Kelola produk sesuai permission yang diberikan Owner.')

@section('content')
<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Akses terbatas: kamu hanya dapat mengelola produk yang diizinkan Owner. Perubahan harga dan penghapusan produk hanya bisa dilakukan Owner.</p>
    </div>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Katalog Produk Toko</h2>
            <button type="button" onclick="showRalivaToast('Tambah produk baru memerlukan persetujuan Owner (demo).', 'add')" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Tambah Produk</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            <div class="border border-muted-border rounded-lg overflow-hidden card-premium">
                <div class="aspect-[4/3] bg-surface-container-low overflow-hidden">
                    <img class="w-full h-full object-cover" alt="Oversized Linen Shirt" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAD17mbo2RMjQLUurWOk7oZNTqAPgUrUyNN2hXJFja0JUiv7N5fyAVB-RM_Gwf7YWS-zlWF4w8PVNCRoB-WIrtbXpRe0DACYYb9pkRwkRax9iKiMlmSc7DPKHzV98xsB4N4lhDoHWHn9hbwZtRVOutmfDKXPZe33TxnwiBp-2lCaek0XEV4Hh7Swi_FhekAiCYroSJd_0BhMpj4Q4LUBt4AlFR6P774tt5okifXIuW6dkaL9RD7XeNTKA" />
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-title-md text-title-md text-on-surface leading-tight">Oversized Linen Shirt</h3>
                        <span class="px-2 py-1 bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase rounded-full border border-secondary/20 shrink-0">Bisa Edit</span>
                    </div>
                    <p class="font-body-md text-body-md text-gold-accent mt-2">Rp 289.000</p>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-muted-border">
                        <span class="text-on-surface-variant text-xs">Stok: 24</span>
                        <button type="button" onclick="showRalivaToast('Editor produk demo belum tersedia.', 'edit')" class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Kelola</button>
                    </div>
                </div>
            </div>

            <div class="border border-muted-border rounded-lg overflow-hidden card-premium">
                <div class="aspect-[4/3] bg-surface-container-low overflow-hidden">
                    <img class="w-full h-full object-cover" alt="Straight Fit Pants" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCxZBoFsBSw3s9O0blHnCy0w_36DLAYcSiXSRfubszdCTT6D3UP9xbCFVJ3JfOZBEhvKauimi2-UFeA_QaKj-MRYmdkGLVyOyYYx1M6u2t35PrBhKj5dGu7b4GL9JI2Eo1wyuuqe1R4FiZ-dCt0Yw8Mwn3C1FnanQI7ctVL-Qjg_yGGFV9jbjskhYGL5XMp6H1Y-2e9n5rl8W7eOO7msph0i1bfZBzq4xBCrXAoRqPkWXCl-jwgFpGsJw" />
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-title-md text-title-md text-on-surface leading-tight">Straight Fit Pants</h3>
                        <span class="px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase rounded-full border border-outline-variant shrink-0">Lihat Saja</span>
                    </div>
                    <p class="font-body-md text-body-md text-gold-accent mt-2">Rp 329.000</p>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-muted-border">
                        <span class="text-on-surface-variant text-xs">Stok: 12</span>
                        <button type="button" onclick="showRalivaToast('Detail produk Straight Fit Pants (demo).', 'visibility')" class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Detail</button>
                    </div>
                </div>
            </div>

            <div class="border border-muted-border rounded-lg overflow-hidden card-premium">
                <div class="aspect-[4/3] bg-surface-container-low overflow-hidden">
                    <img class="w-full h-full object-cover" alt="Relaxed Blazer" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5J3YOps85wsdu1QkE_7T3YAuqYPVpAvbNWjL7SxutlGXGyi9Pw4ortd7iCtouvyLSdvaEdhFQFY0wAjnT5zUVsj_UQ9XnS8UEy0hVfqXqDJZ5-A7c97y0no37fz1hwq5RfWOY6dgWsJCuGUYeopfsDE0NwB7FYINkx8FnNTSRD0zkx501NfnRk4DVy1OKPw2-vBcYOhsD_3l45HUA1E2NvPQztPw3T87yUR9DMq-kOF9ENheX8k9t6w" />
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-title-md text-title-md text-on-surface leading-tight">Relaxed Blazer</h3>
                        <span class="px-2 py-1 bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase rounded-full border border-secondary/20 shrink-0">Bisa Edit</span>
                    </div>
                    <p class="font-body-md text-body-md text-gold-accent mt-2">Rp 579.000</p>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-muted-border">
                        <span class="text-on-surface-variant text-xs">Stok: 8</span>
                        <button type="button" onclick="showRalivaToast('Editor produk demo belum tersedia.', 'edit')" class="text-gold-accent font-label-sm text-[10px] uppercase hover:underline">Kelola</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
