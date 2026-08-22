@extends('layouts.admin')

@section('title', 'Dashboard Operasional')

@section('header-title', 'Dashboard Operasional')
@section('header-badge', 'Lihat')
@section('header-subtitle', 'Prioritaskan pekerjaan harian tokomu hari ini.')

@section('content')
<div class="rise flex flex-wrap items-center gap-3 -mt-2 mb-2">
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent">
        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
        Sabtu, 22 Agustus 2026
    </span>
    <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
        Operasional toko berjalan normal
    </span>
</div>

<section class="rise">
    <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Hari Ini</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pesanan Baru</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="12">12</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>+3 dari kemarin</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Verifikasi</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent"><span data-count="5">5</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant">perlu ditinjau hari ini</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">fact_check</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Siap Kirim</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface"><span data-count="8">8</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant">paket menunggu resi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-error/25 rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Komplain Terbuka</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-error"><span data-count="3">3</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant">respons maks 24 jam</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-error/10 pointer-events-none select-none" aria-hidden="true">support_agent</span>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
    <section class="rise bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Pekerjaan Tertunda</h2>
        <ul class="flex flex-col gap-5">
            <li class="group cursor-pointer">
                <div class="flex items-center justify-between pb-2">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center text-gold-accent shrink-0 shadow-sm">
                            <span class="material-symbols-outlined">fact_check</span>
                        </div>
                        <div>
                            <span class="font-title-md text-title-md text-on-surface block">Verifikasi Pembayaran</span>
                            <span class="text-on-surface-variant font-body-md text-sm">2 dari 5 bukti transfer sudah ditinjau</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant group-hover:text-gold-accent group-hover:translate-x-0.5 transition-all">chevron_right</span>
                </div>
                <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
                    <div class="h-full w-[40%] bg-gradient-to-r from-gold-accent/70 to-gold-accent rounded-full"></div>
                </div>
            </li>
            <li class="group cursor-pointer">
                <div class="flex items-center justify-between pb-2">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface shrink-0 shadow-sm">
                            <span class="material-symbols-outlined">local_shipping</span>
                        </div>
                        <div>
                            <span class="font-title-md text-title-md text-on-surface block">Input Resi Pengiriman</span>
                            <span class="text-on-surface-variant font-body-md text-sm">3 dari 8 paket sudah dikirim</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant group-hover:text-gold-accent group-hover:translate-x-0.5 transition-all">chevron_right</span>
                </div>
                <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
                    <div class="h-full w-[38%] bg-gradient-to-r from-gold-accent/70 to-gold-accent rounded-full"></div>
                </div>
            </li>
            <li class="group cursor-pointer">
                <div class="flex items-center justify-between pb-2">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-full bg-error-container flex items-center justify-center text-on-error-container shrink-0 shadow-sm">
                            <span class="material-symbols-outlined">support_agent</span>
                        </div>
                        <div>
                            <span class="font-title-md text-title-md text-on-surface block">Balas Komplain</span>
                            <span class="text-on-surface-variant font-body-md text-sm">0 dari 3 komplain terjawab</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant group-hover:text-gold-accent group-hover:translate-x-0.5 transition-all">chevron_right</span>
                </div>
                <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
                    <div class="h-full w-[8%] bg-gradient-to-r from-error/60 to-error rounded-full"></div>
                </div>
            </li>
        </ul>
    </section>

    <section class="rise bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Pesanan Masuk Terbaru</h2>
            <a href="{{ route('admin.pesanan') }}" class="font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:underline">Lihat Semua</a>
        </div>
        <ul class="flex flex-col">
            <li class="p-4 border-b border-muted-border hover:bg-surface-container-low transition-colors flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-9 h-9 rounded-full bg-secondary-container/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-secondary">shopping_bag</span>
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
                    <div class="w-9 h-9 rounded-full bg-secondary-container/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm text-secondary">shopping_bag</span>
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
                    <div class="w-9 h-9 rounded-full bg-surface-container-high flex items-center justify-center">
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
@endsection
