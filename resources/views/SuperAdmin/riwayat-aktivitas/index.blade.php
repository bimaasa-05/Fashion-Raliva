@extends('layouts.superadmin')

@section('title', 'Riwayat Aktivitas')

@section('header-title', 'Riwayat Aktivitas')
@section('header-badge', 'Lihat')

@section('header-subtitle', 'Catatan audit tindakan penting pengguna dan admin sistem.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .icon-fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filter-scroll::-webkit-scrollbar { height: 4px; }
    .filter-scroll::-webkit-scrollbar-track { background: transparent; }
    .filter-scroll::-webkit-scrollbar-thumb { background-color: #e3e2e2; border-radius: 4px; }
    .timeline-line::before { content: ''; position: absolute; left: 20px; top: 48px; bottom: -24px; width: 1px; background: linear-gradient(to bottom, rgba(201,162,77,0.55), rgba(201,162,77,0.06)); z-index: 0; }
    .timeline-item:last-child .timeline-line::before { display: none; }
</style>
@endpush

@section('content')

<!-- Filters -->
<div class="mb-8">
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Kategori</span>
        </div>
        <div class="flex overflow-x-auto filter-scroll pb-2 -mx-gutter px-gutter md:mx-0 md:px-0 space-x-4">
            <button type="button" data-aktifitas-tab="semua" class="whitespace-nowrap px-4 py-2 border-b-2 border-primary text-on-surface font-label-sm text-label-sm uppercase transition-colors">Semua Aktivitas</button>
            <button type="button" data-aktifitas-tab="pengguna" class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">Pengguna</button>
            <button type="button" data-aktifitas-tab="toko" class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">Toko</button>
            <button type="button" data-aktifitas-tab="produk" class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">Produk</button>
            <button type="button" data-aktifitas-tab="keuangan" class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">Keuangan</button>
            <button type="button" data-aktifitas-tab="sistem" class="whitespace-nowrap px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface font-label-sm text-label-sm uppercase transition-colors">Sistem</button>
        </div>
        <div class="h-[1px] w-full bg-muted-border -mt-[1px]"></div>
    </div>
</div>

<!-- Timeline -->
<div class="space-y-6">
    <div data-kategori="toko" class="timeline-item relative timeline-line">
        <div class="flex items-start">
            <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(201,162,77,0.08)] mt-1"><span class="material-symbols-outlined text-gold-accent text-sm">storefront</span></div>
            <div class="ml-element-gap flex-grow">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Toko Disetujui</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">Hari ini, 10.45</span></div>
                <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2 card-premium">
                    <div class="text-sm"><span class="font-bold text-on-surface">Admin (Sistem)</span> menyetujui pendaftaran <span class="font-bold text-on-surface">Lunara Fashion</span> untuk bergabung ke marketplace.</div>
                    <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Toko</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Persetujuan</span></div>
                </div>
            </div>
        </div>
    </div>
    <div data-kategori="keuangan" class="timeline-item relative timeline-line">
        <div class="flex items-start">
            <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(201,162,77,0.08)] mt-1"><span class="material-symbols-outlined text-gold-accent text-sm">payments</span></div>
            <div class="ml-element-gap flex-grow">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Pencairan Diajukan</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">Kemarin, 16.20</span></div>
                <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2 card-premium">
                    <div class="text-sm"><span class="font-bold text-on-surface">Noir Studio</span> mengajukan pencairan <span class="font-bold text-on-surface">Rp 4.500.000</span> ke rekening bank terdaftar.</div>
                    <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Keuangan</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Menunggu</span></div>
                </div>
            </div>
        </div>
    </div>
    <div data-kategori="produk" class="timeline-item relative timeline-line">
        <div class="flex items-start">
            <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(201,162,77,0.08)] mt-1"><span class="material-symbols-outlined text-gold-accent text-sm">block</span></div>
            <div class="ml-element-gap flex-grow">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Produk Ditolak</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">24 Okt, 14.15</span></div>
                <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2 card-premium">
                    <div class="text-sm"><span class="font-bold text-on-surface">Moderasi Otomatis</span> menolak produk <span class="font-bold text-on-surface">"Vintage Leather Jacket"</span> dari <span class="font-bold text-on-surface">Kayana Apparel</span>.</div>
                    <div class="mt-2 text-sm text-on-surface-variant bg-surface p-2 border border-muted-border rounded-sm">Alasan: Informasi komposisi material wajib tidak lengkap.</div>
                    <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Produk</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Sistem</span></div>
                </div>
            </div>
        </div>
    </div>
    <div data-kategori="sistem" class="timeline-item relative timeline-line">
        <div class="flex items-start">
            <div class="relative z-10 w-10 h-10 rounded-full bg-surface-container-lowest flex items-center justify-center shrink-0 border border-gold-accent/40 shadow-[0_0_0_3px_rgba(201,162,77,0.08)] mt-1"><span class="material-symbols-outlined text-gold-accent text-sm">percent</span></div>
            <div class="ml-element-gap flex-grow">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline mb-1"><span class="font-title-md text-title-md text-on-surface">Tarif Komisi Diperbarui</span><span class="text-xs text-on-surface-variant mt-1 sm:mt-0 font-label-sm uppercase tracking-wider">20 Okt, 09.00</span></div>
                <div class="p-4 bg-surface-container-low border border-muted-border rounded-DEFAULT mt-2 card-premium">
                    <div class="text-sm"><span class="font-bold text-on-surface">Super Admin</span> mengubah tarif komisi global marketplace.</div>
                    <div class="mt-2 text-sm"><span class="line-through text-on-surface-variant">12.5%</span> <span class="material-symbols-outlined text-[12px] align-middle mx-1">arrow_forward</span> <span class="font-bold text-on-surface">15.0%</span></div>
                    <div class="mt-3 flex gap-2"><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Sistem</span><span class="inline-block px-2 py-1 bg-surface-container-high text-on-surface-variant text-[10px] uppercase font-bold tracking-wider rounded-DEFAULT">Keuangan</span></div>
                </div>
            </div>
        </div>
    </div>
    <p id="aktivitas-kosong" class="hidden text-center text-on-surface-variant font-body-md text-sm py-8">Tidak ada aktivitas pada kategori ini.</p>
    <div class="pt-8 text-center">
        <button type="button" onclick="showRalivaToast('Halaman demo: aktivitas lebih lama belum tersedia.', 'info')" class="px-6 py-3 border border-on-surface text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-high transition-colors">Muat Aktivitas Lebih Lama</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const aktivitasTabs = document.querySelectorAll('[data-aktifitas-tab]');
    const aktivitasItems = document.querySelectorAll('[data-kategori]');
    const aktivitasKosong = document.getElementById('aktivitas-kosong');

    aktivitasTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            aktivitasTabs.forEach((t) => {
                t.classList.add('border-transparent', 'text-on-surface-variant', 'hover:text-on-surface');
                t.classList.remove('border-primary', 'text-on-surface');
            });
            tab.classList.remove('border-transparent', 'text-on-surface-variant', 'hover:text-on-surface');
            tab.classList.add('border-primary', 'text-on-surface');

            const kategori = tab.getAttribute('data-aktifitas-tab');
            let visible = 0;
            aktivitasItems.forEach((item) => {
                const show = kategori === 'semua' || item.getAttribute('data-kategori') === kategori;
                item.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            aktivitasKosong?.classList.toggle('hidden', visible > 0);
        });
    });
</script>
@endpush