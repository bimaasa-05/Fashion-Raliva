@extends('layouts.superadmin')

@section('title', 'Promo Platform')

@section('header-title', 'Promo Platform')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Buat dan kelola promo lintas toko untuk meningkatkan penjualan')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
<div class="space-y-section-gap">
    <!-- Active Promos -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Promo Aktif</h2>
            <span class="text-on-surface-variant font-body-md text-sm">3 promo berjalan</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            <!-- Promo Card 1 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-gold-accent/30 rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-12 translate-x-12" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[24px]">celebration</span>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent/20 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">
                        <span class="material-symbols-outlined text-[10px]">local_fire_department</span>
                        Active
                    </span>
                </div>
                <p class="text-on-surface-variant text-sm mb-2">Pengiriman gratis untuk semua toko tanpa minimum order</p>
                <p class="font-headline-lg text-headline-lg text-gold-accent mb-1">Gratis Ongkir</p>
                <p class="text-on-surface-variant text-xs">Tersisa: 30 hari</p>
            </div>

            <!-- Promo Card 2 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-gold-accent/30 rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-12 translate-x-12" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[24px]">gift</span>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent/20 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">
                        <span class="material-symbols-outlined text-[10px]">local_fire_department</span>
                        Active
                    </span>
                </div>
                <p class="text-on-surface-variant text-sm mb-2">Diskon 15% maksimal pada seluruh produk marketplace</p>
                <p class="font-headline-lg text-headline-lg text-gold-accent mb-1">Diskon 15%</p>
                <p class="text-on-surface-variant text-xs">Tersisa: 14 hari</p>
            </div>

            <!-- Promo Card 3 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-gold-accent/30 rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-12 translate-x-12" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[24px]">trending_up</span>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent/20 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">
                        <span class="material-symbols-outlined text-[10px]">local_fire_department</span>
                        Active
                    </span>
                </div>
                <p class="text-on-surface-variant text-sm mb-2">Beli 1 produk, dapatkan 1 produk gratis (nilai sama/lebih rendah)</p>
                <p class="font-headline-lg text-headline-lg text-gold-accent mb-1">Beli 1 Gratis 1</p>
                <p class="text-on-surface-variant text-xs">Tersisa: 21 hari</p>
            </div>
        </div>
    </section>

    <!-- Create New Promo Form -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Buat Promo Baru</h2>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
            <form class="space-y-gutter" id="create-promo-form" data-toast-message="Promo platform berhasil dibuat.">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="promoName">Nama Promo</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="promoName" name="promoName" type="text" placeholder="Misal: Lebaran Sale" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Jenis Promo</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all">
                            <input type="radio" class="w-4 h-4 border border-on-surface rounded accent-gold-accent" name="promoType" value="discount" checked />
                            <span class="font-body-md text-on-surface">Diskon (%)</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all">
                            <input type="radio" class="w-4 h-4 border border-on-surface rounded accent-gold-accent" name="promoType" value="free-shipping" />
                            <span class="font-body-md text-on-surface">Pengiriman Gratis</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all">
                            <input type="radio" class="w-4 h-4 border border-on-surface rounded accent-gold-accent" name="promoType" value="buy-one-get-one" />
                            <span class="font-body-md text-on-surface">Beli 1 Gratis 1</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all">
                            <input type="radio" class="w-4 h-4 border border-on-surface rounded accent-gold-accent" name="promoType" value="percentage-off" />
                            <span class="font-body-md text-on-surface">Potongan Harga</span>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="discountValue">Persentase/Besar Diskon</label>
                        <div class="relative">
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="discountValue" name="discountValue" type="number" min="0" max="100" step="0.5" value="15" required />
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">%</div>
                        </div>
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="promoCode">Kode Promo</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="promoCode" name="promoCode" type="text" placeholder="PROMO-LEBARAN24" required />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="startDate">Mulai</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="startDate" name="startDate" type="date" required />
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="endDate">Berakhir</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="endDate" name="endDate" type="date" required />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="promoRules">Aturan & Syarat</label>
                    <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="promoRules" name="promoRules" rows="3" placeholder="Misal: Berlaku untuk toko aktif, minimum order Rp 50.000, maksimal 1 kali penggunaan per user"></textarea>
                </div>
                <div class="flex gap-gutter pt-gutter border-t border-muted-border">
                    <button type="button" class="flex-1 border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors" onclick="document.getElementById('create-promo-form').reset()">Batal</button>
                    <button type="submit" class="flex-1 bg-gold-accent text-on-secondary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-secondary-container transition-colors">Buat Promo</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection