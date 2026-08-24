@extends('layouts.superadmin')

@section('title', 'Kurir')

@section('header-title', 'Manajemen Kurir')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola kurir yang tersedia di platform untuk pengiriman')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
<div class="space-y-section-gap">
    <!-- Toolbar -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">local_shipping</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Manajemen Kurir</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Kelola kurir yang tersedia untuk pengiriman.</p>
            </div>
        </div>
        <button type="button" data-modal-open="modal-tambah-kurir" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Kurir
        </button>
    </section>

    <!-- Couriers List -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Kurir</h2>
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

<!-- Modal Tambah Kurir -->
<div id="modal-tambah-kurir" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Kurir Baru</h3>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Kurir tersedia untuk seluruh toko di platform.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="add-courier-form" data-toast-message="Kurir baru berhasil ditambahkan." class="p-6 space-y-5">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="courierName">Nama Kurir</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="courierName" name="courierName" type="text" placeholder="Misal: J&T, POS Indonesia, GoSend" required />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Jasa Kurir</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                        <input type="radio" class="w-4 h-4 accent-gold-accent" name="courierType" value="jne" checked />
                        <span class="font-body-md text-on-surface">JNE</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                        <input type="radio" class="w-4 h-4 accent-gold-accent" name="courierType" value="pos" />
                        <span class="font-body-md text-on-surface">POS Indonesia</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                        <input type="radio" class="w-4 h-4 accent-gold-accent" name="courierType" value="gosend" />
                        <span class="font-body-md text-on-surface">GoSend</span>
                    </label>
                    <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                        <input type="radio" class="w-4 h-4 accent-gold-accent" name="courierType" value="sicepat" />
                        <span class="font-body-md text-on-surface">SiCepat</span>
                    </label>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="baseCost">Biaya Dasar</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="baseCost" name="baseCost" type="number" min="0" step="500" value="5000" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="estimatedTime">Waktu Estimasi</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="estimatedTime" name="estimatedTime" type="text" placeholder="Misal: 1-2 hari" required />
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Status</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="courierStatus" value="active" checked />
                        Aktif
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="courierStatus" value="inactive" />
                        Non-aktif
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="courierStatus" value="verification" />
                        Verifikasi
                    </label>
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Kurir</button>
            </div>
        </form>
    </div>
</div>
@endsection