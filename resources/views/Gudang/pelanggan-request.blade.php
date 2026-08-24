@extends('layouts.gudang')

@section('title', 'Permintaan Kustom')

@section('header-title', 'Permintaan Kustom Pelanggan')
@section('header-badge', 'Layanan')

@section('header-subtitle', 'Request khusus dari pelanggan — misal tambah nama bordir/sablon di produk yang sudah di-checkout.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-14 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-3 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest">Permintaan Masuk</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">4</span>
            <span class="text-xs text-on-surface-variant">menunggu ditindaklanjuti</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest">Sedang Dikerjakan</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">2</span>
            <span class="text-xs text-on-surface-variant">proses sablon & bordir</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest">Selesai Bulan Ini</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">11</span>
            <span class="text-xs text-on-surface-variant">semua tepat waktu</span>
        </div>
    </div>

    <!-- Filter -->
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Permintaan</span>
        </div>
        <div class="flex flex-col lg:flex-row lg:items-center gap-gutter">
            <div class="relative flex-1 min-w-0">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" data-table-search placeholder="Cari nomor pesanan, pelanggan, produk..." class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent transition-colors" />
            </div>
            <select data-table-filter="jenis" aria-label="Filter jenis request" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                <option value="semua">Semua Jenis</option>
                <option value="sablon-nama">Sablon Nama</option>
                <option value="bordir">Bordir Nama</option>
                <option value="ukuran">Ganti Ukuran/Warna</option>
                <option value="lainnya">Lainnya</option>
            </select>
            <select data-table-filter="status" aria-label="Filter status" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                <option value="semua">Semua Status</option>
                <option value="masuk">Baru Masuk</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
            </select>
            <button type="button" data-filter-reset class="px-3 py-2.5 border border-muted-border rounded-lg font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Reset</button>
        </div>
    </section>

    <!-- Daftar Request -->
    <div class="space-y-gutter">
        <!-- Request 1 -->
        <article data-table-row data-jenis="sablon-nama" data-status="masuk" class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 card-premium hover:border-gold-accent/40 transition-colors">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="flex items-start gap-4 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gold-accent text-[24px]">stylus_note</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-mono text-sm text-on-surface">#RLV-2091</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Sablon Nama</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/25"><span class="w-1.5 h-1.5 rounded-full bg-error mr-1"></span>Baru Masuk</span>
                        </div>
                        <p class="font-title-md text-title-md text-on-surface leading-snug">Basic T-Shirt Cotton — Hitam, Ukuran L</p>
                        <p class="text-on-surface-variant text-sm mt-1">Pelanggan: <span class="text-on-surface">Dewi Lestari</span> • Ingin ditambahkan nama <strong class="text-gold-accent">"DEWI"</strong> di bagian belakang baju (sablon putih, huruf kapital, tengah punggung).</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row items-stretch sm:items-center gap-2 shrink-0">
                    <button type="button" onclick="showRalivaToast('Request #RLV-2091 mulai dikerjakan.', 'play_arrow')" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium whitespace-nowrap">Proses</button>
                    <button type="button" onclick="showRalivaToast('Detail lengkap #RLV-2091 (demo).', 'visibility')" class="px-4 py-2.5 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Detail</button>
                </div>
            </div>
            <div class="flex items-center gap-4 mt-4 pt-3 border-t border-muted-border text-xs text-on-surface-variant">
                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">event</span>Tenggat: 27 Agu 2026</span>
                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">payments</span>Sudah dibayar: Rp 15.000 (biaya kustom)</span>
            </div>
        </article>

        <!-- Request 2 -->
        <article data-table-row data-jenis="bordir" data-status="diproses" class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 card-premium hover:border-gold-accent/40 transition-colors">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="flex items-start gap-4 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-secondary-container/30 border border-secondary/25 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-secondary text-[24px]">gesture</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-mono text-sm text-on-surface">#RLV-2088</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Bordir Nama</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="w-1.5 h-1.5 rounded-full bg-secondary mr-1 animate-pulse"></span>Diproses</span>
                        </div>
                        <p class="font-title-md text-title-md text-on-surface leading-snug">Hoodie Fleece Premium — Abu-abu, Ukuran XL</p>
                        <p class="text-on-surface-variant text-sm mt-1">Pelanggan: <span class="text-on-surface">Andi Pratama</span> • Bordir nama <strong class="text-gold-accent">"A. PRATAMA"</strong> di dada kiri, benang warna emas.</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row items-stretch sm:items-center gap-2 shrink-0">
                    <button type="button" onclick="showRalivaToast('Request #RLV-2088 ditandai selesai.', 'task_alt')" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium whitespace-nowrap">Tandai Selesai</button>
                    <button type="button" onclick="showRalivaToast('Detail lengkap #RLV-2088 (demo).', 'visibility')" class="px-4 py-2.5 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Detail</button>
                </div>
            </div>
            <div class="flex items-center gap-4 mt-4 pt-3 border-t border-muted-border text-xs text-on-surface-variant">
                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">event</span>Tenggat: 29 Agu 2026</span>
                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">progress_activity</span>Dikerjakan sejak: 22 Agu 2026</span>
            </div>
        </article>

        <!-- Request 3 -->
        <article data-table-row data-jenis="ukuran" data-status="diproses" class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 card-premium hover:border-gold-accent/40 transition-colors">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="flex items-start gap-4 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-surface-container-high border border-muted-border flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-on-surface-variant text-[24px]">swap_horiz</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-mono text-sm text-on-surface">#RLV-2085</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-muted-border">Ganti Ukuran</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="w-1.5 h-1.5 rounded-full bg-secondary mr-1 animate-pulse"></span>Diproses</span>
                        </div>
                        <p class="font-title-md text-title-md text-on-surface leading-snug">Pleated Skirt — Mau tukar ukuran M ke L</p>
                        <p class="text-on-surface-variant text-sm mt-1">Pelanggan: <span class="text-on-surface">Maya Rossi</span> • Stok ukuran L tersedia 6 pcs — tunggu barang kembali lalu kirim ulang.</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row md:flex-col lg:flex-row items-stretch sm:items-center gap-2 shrink-0">
                    <button type="button" onclick="showRalivaToast('Request #RLV-2085 ditandai selesai.', 'task_alt')" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium whitespace-nowrap">Tandai Selesai</button>
                    <button type="button" onclick="showRalivaToast('Detail lengkap #RLV-2085 (demo).', 'visibility')" class="px-4 py-2.5 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Detail</button>
                </div>
            </div>
            <div class="flex items-center gap-4 mt-4 pt-3 border-t border-muted-border text-xs text-on-surface-variant">
                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">event</span>Tenggat: 26 Agu 2026</span>
            </div>
        </article>

        <!-- Request 4 -->
        <article data-table-row data-jenis="sablon-nama" data-status="selesai" class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 card-premium opacity-80">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="flex items-start gap-4 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-secondary-container/30 border border-secondary/25 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-secondary text-[24px]">check_circle</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-mono text-sm text-on-surface">#RLV-2079</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Sablon Nama</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Selesai</span>
                        </div>
                        <p class="font-title-md text-title-md text-on-surface leading-snug">Oversized Linen Shirt — Putih, Ukuran M</p>
                        <p class="text-on-surface-variant text-sm mt-1">Pelanggan: <span class="text-on-surface">Sarah Jenkins</span> • Sablon nama "SARAH" di kantong dada. Sudah dikirim dengan resi JNE-0088.</p>
                    </div>
                </div>
                <div class="shrink-0">
                    <button type="button" onclick="showRalivaToast('Detail lengkap #RLV-2079 (demo).', 'visibility')" class="px-4 py-2.5 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Detail</button>
                </div>
            </div>
        </article>
    </div>

    <p data-empty-state class="hidden text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada permintaan yang sesuai dengan pencarian atau filter.</p>
</div>
@endsection
