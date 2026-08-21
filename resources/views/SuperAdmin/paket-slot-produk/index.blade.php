@extends('layouts.superadmin')

@section('title', 'Paket Slot Produk')

@section('header-title', 'Paket Slot Produk')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola paket kapasitas produk untuk model bisnis gratis daftar + paket slot.')

@section('content')
<div class="space-y-section-gap">
    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface">Daftar Paket</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 hover:border-gold-accent transition-colors">
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

            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 hover:border-gold-accent transition-colors">
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

            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 flex flex-col gap-4 hover:border-gold-accent transition-colors">
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

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface">Tambah / Ubah Paket</h2>
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6">
            <form class="space-y-gutter" id="paket-form">
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
                            <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all">
                                <input type="radio" class="sr-only" name="statusPaket" value="aktif" checked />
                                Aktif
                            </label>
                            <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all">
                                <input type="radio" class="sr-only" name="statusPaket" value="nonaktif" />
                                Non-aktif
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="fiturPaket">Fitur Paket (satu per baris)</label>
                    <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="fiturPaket" name="fiturPaket" rows="3" placeholder="Statistik toko dasar&#10;Prioritas moderasi"></textarea>
                </div>
                <div class="flex gap-gutter pt-gutter border-t border-muted-border">
                    <button type="button" class="flex-1 border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors" onclick="document.getElementById('paket-form').reset()">Batal</button>
                    <button type="submit" class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-tertiary-container transition-colors">Simpan Paket</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
