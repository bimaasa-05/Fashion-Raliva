@extends('layouts.superadmin')

@section('title', 'Paket Slot Produk')

@section('header-title', 'Paket Slot Produk')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola paket kapasitas produk untuk model bisnis gratis daftar + paket slot.')

@section('content')
<div class="space-y-section-gap">
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">workspace_premium</span></div>
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Paket</h2>
        </div>
        <button type="button" data-modal-open="modal-form-paket" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Paket
        </button>
    </section>

    <section class="space-y-gutter">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">Gratis Daftar</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                </div>
                <div>
                    <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp 0</span>
                    <span class="text-on-surface-variant font-body-md text-sm"> /selamanya</span>
                </div>
                <ul class="space-y-2 font-body-md text-sm text-on-surface-variant flex-1">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check</span>5 slot produk</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check</span>Tanpa biaya bulanan</li>
                </ul>
                <div class="pt-4 border-t border-muted-border flex items-center justify-between">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">Pelanggan Aktif</span>
                    <span class="font-title-md text-on-surface">1.284</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">Starter</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                </div>
                <div>
                    <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp 49RB</span>
                    <span class="text-on-surface-variant font-body-md text-sm"> /bulan</span>
                </div>
                <ul class="space-y-2 font-body-md text-sm text-on-surface-variant flex-1">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check</span>25 slot produk</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check</span>Statistik toko dasar</li>
                </ul>
                <div class="pt-4 border-t border-muted-border flex items-center justify-between">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">Pelanggan Aktif</span>
                    <span class="font-title-md text-on-surface">342</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border-2 border-gold-accent rounded-xl p-6 flex flex-col gap-4 relative">
                <span class="absolute -top-3 left-6 px-3 py-1 bg-gold-accent text-deep-onyx font-label-sm text-[10px] uppercase tracking-wider rounded-full">Terpopuler</span>
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">Growth</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                </div>
                <div>
                    <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp 149RB</span>
                    <span class="text-on-surface-variant font-body-md text-sm"> /bulan</span>
                </div>
                <ul class="space-y-2 font-body-md text-sm text-on-surface-variant flex-1">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check</span>100 slot produk</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check</span>Prioritas moderasi</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check</span>Laporan penjualan</li>
                </ul>
                <div class="pt-4 border-t border-muted-border flex items-center justify-between">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">Pelanggan Aktif</span>
                    <span class="font-title-md text-on-surface">187</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">Pro</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Non-aktif</span>
                </div>
                <div>
                    <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp 399RB</span>
                    <span class="text-on-surface-variant font-body-md text-sm"> /bulan</span>
                </div>
                <ul class="space-y-2 font-body-md text-sm text-on-surface-variant flex-1">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check</span>Slot produk tanpa batas</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">check</span>Dukungan prioritas 24/7</li>
                </ul>
                <div class="pt-4 border-t border-muted-border flex items-center justify-between">
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">Pelanggan Aktif</span>
                    <span class="font-title-md text-on-surface">23</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Form Paket -->
    <div id="modal-form-paket" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah / Ubah Paket</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Atur kapasitas slot dan harga paket langganan.</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form class="p-6 space-y-5" id="paket-form" data-toast-message="Paket slot produk berhasil disimpan.">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="namaPaket">Nama Paket</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="namaPaket" name="namaPaket" type="text" placeholder="Misal: Starter, Growth" required />
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="hargaPaket">Harga per Bulan (Rp)</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="hargaPaket" name="hargaPaket" type="number" min="0" placeholder="0 untuk paket gratis" required />
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="slotProduk">Jumlah Slot Produk</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="slotProduk" name="slotProduk" type="number" min="1" placeholder="Misal: 25" required />
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Status Paket</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                                <input type="radio" class="sr-only" name="statusPaket" value="aktif" checked />
                                Aktif
                            </label>
                            <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                                <input type="radio" class="sr-only" name="statusPaket" value="nonaktif" />
                                Non-aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="fiturPaket">Fitur Paket (satu per baris)</label>
                    <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="fiturPaket" name="fiturPaket" rows="3" placeholder="Statistik toko dasar&#10;Prioritas moderasi"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
