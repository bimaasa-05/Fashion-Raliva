@extends('layouts.admin')

@section('title', 'Dashboard Operasional')

@section('header-title', 'Dashboard Operasional')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Prioritaskan pekerjaan harian tokomu hari ini.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Hari Ini</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pesanan Baru</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">12</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Verifikasi</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">5</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">fact_check</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Siap Kirim</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">8</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_shipping</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komplain Terbuka</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-error">3</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">support_agent</span>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Pekerjaan Tertunda</h2>
            <ul class="flex flex-col gap-4">
                <li class="flex items-center justify-between group cursor-pointer pb-4 border-b border-muted-border last:border-0 last:pb-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                            <span class="material-symbols-outlined">fact_check</span>
                        </div>
                        <div>
                            <span class="font-title-md text-title-md text-on-surface block">Verifikasi Pembayaran</span>
                            <span class="text-on-surface-variant font-body-md text-sm">5 bukti transfer menunggu tinjauan</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant group-hover:text-on-surface transition-colors">chevron_right</span>
                </li>
                <li class="flex items-center justify-between group cursor-pointer pb-4 border-b border-muted-border last:border-0 last:pb-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface">
                            <span class="material-symbols-outlined">local_shipping</span>
                        </div>
                        <div>
                            <span class="font-title-md text-title-md text-on-surface block">Input Resi Pengiriman</span>
                            <span class="text-on-surface-variant font-body-md text-sm">8 paket siap kirim</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant group-hover:text-on-surface transition-colors">chevron_right</span>
                </li>
                <li class="flex items-center justify-between group cursor-pointer pb-4 border-b border-muted-border last:border-0 last:pb-0">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-error-container flex items-center justify-center text-on-error-container">
                            <span class="material-symbols-outlined">support_agent</span>
                        </div>
                        <div>
                            <span class="font-title-md text-title-md text-on-surface block">Balas Komplain</span>
                            <span class="text-on-surface-variant font-body-md text-sm">3 komplain menunggu respons</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant group-hover:text-on-surface transition-colors">chevron_right</span>
                </li>
            </ul>
        </section>

        <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Pesanan Masuk Terbaru</h2>
            <ul class="flex flex-col">
                <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm text-on-surface">shopping_bag</span>
                        </div>
                        <div>
                            <p class="font-body-md text-on-surface"><span class="font-bold">#RLV-2081</span> • Sarah Jenkins • 3 produk</p>
                            <p class="text-on-surface-variant text-sm mt-0.5">Rp 890.000 • 10 menit lalu</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Baru</span>
                </li>
                <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm text-on-surface">shopping_bag</span>
                        </div>
                        <div>
                            <p class="font-body-md text-on-surface"><span class="font-bold">#RLV-2080</span> • Andi Pratama • 1 produk</p>
                            <p class="text-on-surface-variant text-sm mt-0.5">Rp 320.000 • 45 menit lalu</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Baru</span>
                </li>
                <li class="p-4 hover:bg-surface-container-low transition-colors flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center">
                            <span class="material-symbols-outlined text-sm text-on-surface">shopping_bag</span>
                        </div>
                        <div>
                            <p class="font-body-md text-on-surface"><span class="font-bold">#RLV-2079</span> • Dewi Lestari • 2 produk</p>
                            <p class="text-on-surface-variant text-sm mt-0.5">Rp 1.150.000 • 1 jam lalu</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Diproses</span>
                </li>
            </ul>
        </section>
    </div>
</div>
@endsection
