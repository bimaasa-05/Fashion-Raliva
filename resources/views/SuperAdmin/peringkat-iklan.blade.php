@extends('layouts.superadmin')

@section('title', 'Peringkat Produk Iklan')

@section('header-title', 'Peringkat Produk Iklan')
@section('header-badge', 'Lahan Cuan')
@section('header-subtitle', 'Slot iklan berbayar — owner membayar agar produknya tampil paling atas di katalog pelanggan.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Penjelasan Cara Kerja -->
    <div data-reveal class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gradient-to-r from-gold-accent/10 via-gold-accent/5 to-transparent rounded-lg">
        <span class="material-symbols-outlined text-gold-accent mt-0.5">campaign</span>
        <div>
            <p class="font-body-md text-sm font-bold text-on-surface">Cara kerja slot iklan</p>
            <p class="text-on-surface-variant text-sm mt-0.5">Owner menghubungi admin &amp; membayar agar produknya tampil di posisi teratas katalog pelanggan. <strong class="text-on-surface">Semakin besar pembayaran, semakin tinggi peringkatnya.</strong></p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">payments</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Pendapatan Iklan Bulan Ini</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold leading-tight">Rp 18,4JT</span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>+22,8% vs bulan lalu</span>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">ads_click</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Slot Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">12 <span class="text-on-surface-variant font-body-md text-sm">/ 20 slot</span></span>
            <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant"><span class="material-symbols-outlined text-[14px] text-gold-accent">grid_view</span>8 slot tersisa bulan ini</span>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">trending_up</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Rata-rata Bid Tertinggi</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">Rp 2,5JT</span>
            <span class="inline-flex items-center gap-1 text-xs text-secondary"><span class="material-symbols-outlined text-[14px]">trending_up</span>+9,4% vs bulan lalu</span>
        </div>
    </div>

    <!-- Podium Top 3 -->
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Podium Peringkat Saat Ini</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter items-end">
            <!-- #1 -->
            <div class="md:order-2 border-2 border-gold-accent rounded-xl p-6 flex flex-col items-center text-center gap-3 relative overflow-hidden bg-gradient-to-b from-gold-accent/10 to-transparent hero-glow">
                <span class="absolute top-3 right-3 material-symbols-outlined text-gold-accent fill text-[28px]">workspace_premium</span>
                <span class="w-12 h-12 rounded-full bg-gold-accent text-deep-onyx flex items-center justify-center font-title-md text-title-md font-bold shadow-lg">1</span>
                <div>
                    <p class="font-title-md text-title-md text-on-surface leading-snug">Kemeja Linen Premium</p>
                    <p class="text-on-surface-variant text-xs mt-0.5">LUNARA Fashion • Kemeja</p>
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold leading-none">Rp 2,5JT</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30"><span class="material-symbols-outlined text-[12px]">check_circle</span>Posisi Teratas • s/d 31 Agu</span>
            </div>
            <!-- #2 -->
            <div class="md:order-1 bg-surface-container-low border border-muted-border rounded-xl p-6 flex flex-col items-center text-center gap-3">
                <span class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant text-on-surface flex items-center justify-center font-title-md font-bold">2</span>
                <div>
                    <p class="font-title-md text-sm text-on-surface leading-snug">Dress Midi Linen</p>
                    <p class="text-on-surface-variant text-xs mt-0.5">NOIRÉ Studio • Dress</p>
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-none">Rp 1,8JT</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-muted-border">Aktif • s/d 28 Agu</span>
            </div>
            <!-- #3 -->
            <div class="md:order-3 bg-surface-container-low border border-muted-border rounded-xl p-6 flex flex-col items-center text-center gap-3">
                <span class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant text-on-surface flex items-center justify-center font-title-md font-bold">3</span>
                <div>
                    <p class="font-title-md text-sm text-on-surface leading-snug">Blazer Wool Relaxed</p>
                    <p class="text-on-surface-variant text-xs mt-0.5">KAYANA Apparel • Blazer</p>
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-none">Rp 1,5JT</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-warning/15 text-warning text-[10px] font-bold uppercase border border-warning/30"><span class="material-symbols-outlined text-[12px]">hourglass_top</span>Segera Berakhir</span>
            </div>
        </div>
    </section>

    <!-- Filter -->
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Peringkat</span>
        </div>
        <div data-chip-group data-chip-key="kategori" class="flex flex-wrap gap-2">
            <button type="button" data-chip="semua" class="px-4 py-2 rounded-lg bg-deep-onyx text-on-primary border border-deep-onyx font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Semua Kategori</button>
            <button type="button" data-chip="kemeja" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Kemeja</button>
            <button type="button" data-chip="dress" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Dress</button>
            <button type="button" data-chip="blazer" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Blazer</button>
            <button type="button" data-chip="aksesoris" class="px-4 py-2 rounded-lg border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200">Aksesoris</button>
        </div>
    </div>

    <!-- Tabel Peringkat -->
    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 flex-wrap gap-3">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Peringkat Lengkap</h2>
            <button type="button" data-modal-open="modal-slot-baru" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span> Daftarkan Slot Iklan
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Posisi</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-left">Toko / Owner</th>
                        <th class="p-4 text-right">Bayaran (Bid)</th>
                        <th class="p-4 text-center">Periode Aktif</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr data-table-row data-kategori="kemeja" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4"><span class="inline-flex w-8 h-8 rounded-full bg-gold-accent text-deep-onyx items-center justify-center font-bold">1</span></td>
                        <td class="p-4 font-medium text-on-surface">Kemeja Linen Premium</td>
                        <td class="p-4 text-on-surface-variant">LUNARA Fashion</td>
                        <td class="p-4 text-right font-title-md text-sm text-gold-accent font-bold">Rp 2.500.000</td>
                        <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">01 – 31 Agu 2026</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span></td>
                        <td class="p-4 text-right"><button type="button" onclick="showRalivaToast('Detail slot iklan #1 dibuka (demo).', 'manage_search')" class="px-3 py-1.5 border border-muted-border rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Kelola</button></td>
                    </tr>
                    <tr data-table-row data-kategori="dress" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4"><span class="inline-flex w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface items-center justify-center font-bold">2</span></td>
                        <td class="p-4 font-medium text-on-surface">Dress Midi Linen</td>
                        <td class="p-4 text-on-surface-variant">NOIRÉ Studio</td>
                        <td class="p-4 text-right font-title-md text-sm text-on-surface font-bold">Rp 1.800.000</td>
                        <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">05 – 28 Agu 2026</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span></td>
                        <td class="p-4 text-right"><button type="button" onclick="showRalivaToast('Detail slot iklan #2 dibuka (demo).', 'manage_search')" class="px-3 py-1.5 border border-muted-border rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Kelola</button></td>
                    </tr>
                    <tr data-table-row data-kategori="blazer" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4"><span class="inline-flex w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface items-center justify-center font-bold">3</span></td>
                        <td class="p-4 font-medium text-on-surface">Blazer Wool Relaxed</td>
                        <td class="p-4 text-on-surface-variant">KAYANA Apparel</td>
                        <td class="p-4 text-right font-title-md text-sm text-on-surface font-bold">Rp 1.500.000</td>
                        <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">10 – 24 Agu 2026</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-warning/15 text-warning text-[10px] font-bold uppercase border border-warning/30"><span class="material-symbols-outlined text-[12px]">hourglass_top</span>Segera Berakhir</span></td>
                        <td class="p-4 text-right"><button type="button" onclick="showRalivaToast('Perpanjang slot iklan #3 dikirim ke owner (demo).', 'schedule')" class="px-3 py-1.5 border border-muted-border rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Kelola</button></td>
                    </tr>
                    <tr data-table-row data-kategori="aksesoris" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4"><span class="inline-flex w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface items-center justify-center font-bold">4</span></td>
                        <td class="p-4 font-medium text-on-surface">Tas Kulit Minimalis</td>
                        <td class="p-4 text-on-surface-variant">MAÉVA House</td>
                        <td class="p-4 text-right font-title-md text-sm text-on-surface font-bold">Rp 1.200.000</td>
                        <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">12 – 31 Agu 2026</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span></td>
                        <td class="p-4 text-right"><button type="button" onclick="showRalivaToast('Detail slot iklan #4 dibuka (demo).', 'manage_search')" class="px-3 py-1.5 border border-muted-border rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Kelola</button></td>
                    </tr>
                    <tr data-table-row data-kategori="dress" class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4"><span class="inline-flex w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface items-center justify-center font-bold">5</span></td>
                        <td class="p-4 font-medium text-on-surface">Rok Plisket Satin</td>
                        <td class="p-4 text-on-surface-variant">Velvet Closet</td>
                        <td class="p-4 text-right font-title-md text-sm text-on-surface font-bold">Rp 900.000</td>
                        <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">15 – 30 Agu 2026</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/25">Ditunda</span></td>
                        <td class="p-4 text-right"><button type="button" onclick="showRalivaToast('Pembayaran slot #5 belum terkonfirmasi.', 'info')" class="px-3 py-1.5 border border-muted-border rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Kelola</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p data-empty-state class="hidden text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada slot iklan pada kategori ini.</p>
    </section>
</div>

<!-- Modal Daftarkan Slot Iklan -->
<div id="modal-slot-baru" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Daftarkan Slot Iklan Baru</h3>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Peringkat otomatis mengikuti besaran bayaran tertinggi.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form data-toast-message="Slot iklan baru berhasil didaftarkan." class="p-6 space-y-5">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="iklanProduk">Produk</label>
                <select required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                    <option>Pleated Skirt (Velvet Closet)</option>
                    <option>Oversized Hoodie (NOIRÉ Studio)</option>
                    <option>Leather Belt Classic (MAÉVA House)</option>
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="iklanNominal">Nominal Bayaran (Rp)</label>
                    <input type="number" min="100000" step="50000" value="1000000" id="iklanNominal" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                    <p class="text-xs text-on-surface-variant mt-1.5">Minimal Rp 100.000 — nominal tertinggi menduduki peringkat 1.</p>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="iklanMulai">Mulai Berlaku</label>
                    <input type="date" id="iklanMulai" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="iklanSelesai">Berakhir</label>
                <input type="date" id="iklanSelesai" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
            </div>
            <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/5 rounded-lg">
                <span class="material-symbols-outlined text-gold-accent mt-0.5 text-[20px]">info</span>
                <p class="font-body-md text-xs text-on-surface-variant">Setelah disimpan, produk langsung naik ke peringkat sesuai urutan nominal dan tampil teratas di katalog pelanggan.</p>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Slot</button>
            </div>
        </form>
    </div>
</div>
@endsection
