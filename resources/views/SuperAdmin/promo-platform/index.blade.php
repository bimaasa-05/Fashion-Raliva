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
    <!-- Toolbar -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">local_fire_department</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Promo Platform</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Buat dan kelola promo lintas toko.</p>
            </div>
        </div>
        <button type="button" data-modal-open="modal-buat-promo" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Buat Promo
        </button>
    </section>

    <!-- Active Promos -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3"><h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Promo Aktif</h2></div>
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

    <!-- Modal Buat Promo -->
    <div id="modal-buat-promo" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Buat Promo Baru</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Promo berlaku lintas toko di seluruh platform.</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form id="create-promo-form" data-toast-message="Promo platform berhasil dibuat." class="p-6 space-y-5">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="promoName">Nama Promo</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="promoName" name="promoName" type="text" placeholder="Misal: Lebaran Sale" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Jenis Promo</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                            <input type="radio" class="w-4 h-4 accent-gold-accent" name="promoType" value="discount" checked />
                            <span class="font-body-md text-sm text-on-surface">Diskon (%)</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                            <input type="radio" class="w-4 h-4 accent-gold-accent" name="promoType" value="free-shipping" />
                            <span class="font-body-md text-sm text-on-surface">Ongkir Gratis</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                            <input type="radio" class="w-4 h-4 accent-gold-accent" name="promoType" value="buy-one-get-one" />
                            <span class="font-body-md text-sm text-on-surface">Beli 1 Gratis 1</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low hover:border-gold-accent cursor-pointer transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                            <input type="radio" class="w-4 h-4 accent-gold-accent" name="promoType" value="percentage-off" />
                            <span class="font-body-md text-sm text-on-surface">Potongan Harga</span>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="discountValue">Persentase/Besar Diskon</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="discountValue" name="discountValue" type="number" min="0" max="100" step="0.5" value="15" required />
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
                    <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="promoRules" name="promoRules" rows="3" placeholder="Misal: Berlaku untuk toko aktif, minimum order Rp 50.000"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Buat Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection