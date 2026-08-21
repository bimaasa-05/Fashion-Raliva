@extends('layouts.superadmin')

@section('title', 'Kurir')

@section('header-title', 'Manajemen Kurir')
@section('header-subtitle', 'Kelola kurir yang tersedia di platform untuk pengiriman')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
<div class="space-y-section-gap">
    <!-- Add Courier Form Card -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Tambah Kurir Baru</h2>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6">
            <form class="space-y-gutter" id="add-courier-form">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="courierName">Nama Kurir</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="courierName" name="courierName" type="text" placeholder="Misal: J&T, POS Indonesia, GoSend" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Jasa Kurir</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all">
                            <input type="radio" class="w-4 h-4 border border-on-surface rounded accent-gold-accent" name="courierType" value="jne" checked />
                            <span class="font-body-md text-on-surface">JNE</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all">
                            <input type="radio" class="w-4 h-4 border border-on-surface rounded accent-gold-accent" name="courierType" value="pos" />
                            <span class="font-body-md text-on-surface">POS Indonesia</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all">
                            <input type="radio" class="w-4 h-4 border border-on-surface rounded accent-gold-accent" name="courierType" value="gosend" />
                            <span class="font-body-md text-on-surface">GoSend</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all">
                            <input type="radio" class="w-4 h-4 border border-on-surface rounded accent-gold-accent" name="courierType" value="sicepat" />
                            <span class="font-body-md text-on-surface">SiCepat</span>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="baseCost">Biaya Dasar</label>
                        <div class="relative">
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="baseCost" name="baseCost" type="number" min="0" step="500" value="5000" required />
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">Rp</div>
                        </div>
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="estimatedTime">Waktu Estimasi</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="estimatedTime" name="estimatedTime" type="text" placeholder="Misal: 1-2 hari Jawa, 2-3 hari luar Jawa" required />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Status</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all">
                            <input type="radio" class="sr-only" name="courierStatus" value="active" checked />
                            Aktif
                        </label>
                        <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all">
                            <input type="radio" class="sr-only" name="courierStatus" value="inactive" />
                            Non-aktif
                        </label>
                        <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all">
                            <input type="radio" class="sr-only" name="courierStatus" value="verification" />
                            Verifikasi
                        </label>
                    </div>
                </div>
                <div class="flex gap-gutter pt-gutter border-t border-muted-border">
                    <button type="button" class="flex-1 border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors" onclick="document.getElementById('add-courier-form').reset()">Batal</button>
                    <button type="submit" class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-tertiary-container transition-colors">Tambah Kurir</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Couriers List -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Daftar Kurir</h2>
            <span class="text-on-surface-variant font-body-md text-sm">4 kurir terdaftar</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
            <!-- Courier Card 1 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[28px]">local_shipping</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">J&T Express</h3>
                        <p class="text-on-surface-variant text-xs mt-1">Biaya: Rp 5.000 - Rp 15.000</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="text-on-surface-variant text-xs">Waktu: 1-2 hari (Jawa)</span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined text-[10px]">check_circle</span>
                        Aktif
                    </span>
                </div>
            </div>

            <!-- Courier Card 2 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[28px]">local_shipping</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">POS Indonesia</h3>
                        <p class="text-on-surface-variant text-xs mt-1">Biaya: Rp 3.000 - Rp 10.000</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="text-on-surface-variant text-xs">Waktu: 2-3 hari (Wilayah)</span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined text-[10px]">check_circle</span>
                        Aktif
                    </span>
                </div>
            </div>

            <!-- Courier Card 3 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[28px]">local_shipping</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">GoSend</h3>
                        <p class="text-on-surface-variant text-xs mt-1">Biaya: Rp 7.000 - Rp 20.000</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="text-on-surface-variant text-xs">Waktu: 1 hari (Kota)</span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined text-[10px]">check_circle</span>
                        Aktif
                    </span>
                </div>
            </div>

            <!-- Courier Card 4 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[28px]">local_shipping</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">SiCepat</h3>
                        <p class="text-on-surface-variant text-xs mt-1">Biaya: Rp 6.000 - Rp 18.000</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="text-on-surface-variant text-xs">Waktu: 1-2 hari (Wilayah)</span>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined text-[10px]">check_circle</span>
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection