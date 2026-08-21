@extends('layouts.superadmin')

@section('title', 'Manajemen Toko')

@section('header-title', 'Data Toko')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Verifikasi, tolak, tangguhkan, dan aktifkan kembali toko penjual.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.filled { font-variation-settings: 'FILL' 1; }
    .modal-enter { opacity: 0; transform: scale(0.95); }
    .modal-enter-active { opacity: 1; transform: scale(1); transition: opacity 300ms, transform 300ms; }
    .modal-exit { opacity: 1; transform: scale(1); }
    .modal-exit-active { opacity: 0; transform: scale(0.95); transition: opacity 200ms, transform 200ms; }
</style>
@endpush

@section('content')
<div class="flex flex-wrap gap-3 border-b border-muted-border pb-4">
    <button class="px-4 py-2 border-b-2 border-primary text-primary font-label-sm uppercase tracking-wider">Semua</button>
    <button class="px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-sm uppercase tracking-wider">Menunggu</button>
    <button class="px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-sm uppercase tracking-wider">Aktif</button>
    <button class="px-4 py-2 border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-sm uppercase tracking-wider">Ditangguhkan</button>
</div>

<!-- Store Grid -->
<section class="px-gutter md:px-container-margin py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        <article class="border border-muted-border p-6 hover:bg-surface-container-low transition-colors group cursor-pointer card-premium" onclick="openStoreModal('store-1')">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-surface-variant overflow-hidden border border-muted-border flex items-center justify-center">
                        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAi0PISoKVztJcbKTXivcpR48A-jBk4Fdi5zQVd41-FvdVMMoRQNOdJBsy0RvmapGdtun9Dx0EshdJ7gfZ0jnDQ_tCy70tuZHtgbuHX03CulLr9e2k7WMtl850qaeiBdwVigzBqcjhBiK1gnxiC398qn9tmbTb4OD_SjW5JeMA7NWv0eWaLgyhH04lzQHQncjvxTbi462sBsLxjhrSdlirfPIudzf7JTh8CwiQlzbiYDcxHDmxzfBqhGg" />
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-primary transition-colors">LUNARA Fashion</h3>
                        <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Elara Vance</p>
                    </div>
                </div>
                <span class="px-2 py-1 bg-surface-container-high text-on-surface text-[10px] font-bold tracking-widest uppercase border border-muted-border">Aktif</span>
            </div>
            <div class="grid grid-cols-3 gap-4 border-t border-muted-border pt-6">
                <div class="text-center">
                    <span class="block font-title-md text-on-surface">124</span>
                    <span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Produk</span>
                </div>
                <div class="text-center border-l border-muted-border pl-4">
                    <span class="block font-title-md text-on-surface">892</span>
                    <span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Pesanan</span>
                </div>
                <div class="text-center border-l border-muted-border pl-4">
                    <span class="block font-title-md text-on-surface flex items-center justify-center gap-1">4.9 <span class="material-symbols-outlined text-[16px] filled text-secondary">star</span></span>
                    <span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Rating</span>
                </div>
            </div>
        </article>
        <article class="border border-muted-border p-6 hover:bg-surface-container-low transition-colors group cursor-pointer card-premium" onclick="openStoreModal('store-2')">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-surface-variant overflow-hidden border border-muted-border flex items-center justify-center">
                        <span class="font-title-md text-on-surface-variant">NS</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-primary transition-colors">NOIRÉ Studio</h3>
                        <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Julian Thorne</p>
                    </div>
                </div>
                <span class="px-2 py-1 bg-secondary-container text-on-secondary-container text-[10px] font-bold tracking-widest uppercase border border-secondary/20">Menunggu</span>
            </div>
            <div class="grid grid-cols-3 gap-4 border-t border-muted-border pt-6">
                <div class="text-center"><span class="block font-title-md text-on-surface">12</span><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Produk</span></div>
                <div class="text-center border-l border-muted-border pl-4"><span class="block font-title-md text-on-surface-variant">--</span><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Pesanan</span></div>
                <div class="text-center border-l border-muted-border pl-4"><span class="block font-title-md text-on-surface-variant">--</span><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Rating</span></div>
            </div>
        </article>
        <article class="border border-muted-border p-6 bg-surface-container-low opacity-75 hover:opacity-100 transition-opacity cursor-pointer group" onclick="openStoreModal('store-3')">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-surface-variant overflow-hidden border border-muted-border flex items-center justify-center grayscale">
                        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBUgNmup5rkRgv3EapVhf-b012EHAn4p6eSWo3kuXhP8w84jNkEOJaXF1xilHpASOQfk1znipKa92wFdpahG8ATrWvOWnMR0Gsv-0HuEe0YdLuN7gE2y5hqEJLurSYwy5qW4Yx1wMaSVouoOJyCFc7Jmtyy9y10mY-jv8jNgNrP0GfjD2utgH9u8lzHGo0GWMjR-x7p16V3mEjioYs_Cwlvrt0V2LLKt8uL1rUPswjftzoCFzKegipEzA" />
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface line-through decoration-on-surface-variant">KAYANA Apparel</h3>
                        <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Maya Rossi</p>
                    </div>
                </div>
                <span class="px-2 py-1 bg-error-container text-on-error-container text-[10px] font-bold tracking-widest uppercase border border-error/20">Ditangguhkan</span>
            </div>
            <div class="grid grid-cols-3 gap-4 border-t border-muted-border pt-6">
                <div class="text-center"><span class="block font-title-md text-on-surface-variant">45</span><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Produk</span></div>
                <div class="text-center border-l border-muted-border pl-4"><span class="block font-title-md text-on-surface-variant">112</span><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Pesanan</span></div>
                <div class="text-center border-l border-muted-border pl-4"><span class="block font-title-md text-on-surface-variant flex items-center justify-center gap-1">3.2 <span class="material-symbols-outlined text-[16px] filled">star</span></span><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mt-1">Rating</span></div>
            </div>
        </article>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function openStoreModal(storeId) {
        const modal = document.getElementById('store-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closeStoreModal() {
        const modal = document.getElementById('store-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }
    function openRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeStoreModal(); closeRejectModal(); }
    });
</script>
@endpush

@push('modals')
<!-- Detailed Store Modal -->
<div aria-labelledby="modal-title" aria-modal="true" class="fixed inset-0 z-[100] hidden items-center justify-center px-gutter pt-12 pb-safe bg-scrim/40 backdrop-blur-sm" id="store-modal" role="dialog">
    <div class="fixed inset-0 bg-scrim/20 backdrop-blur-md transition-opacity" onclick="closeStoreModal()"></div>
    <div class="bg-surface w-full max-w-3xl max-h-[795px] overflow-y-auto border border-muted-border shadow-2xl relative z-10 flex flex-col">
        <div class="sticky top-0 bg-surface/95 backdrop-blur-sm border-b border-muted-border p-6 flex justify-between items-center z-20">
            <h2 class="font-display-lg text-headline-lg-mobile md:text-headline-lg" id="modal-title">Detail Toko</h2>
            <button class="text-on-surface-variant hover:text-on-surface transition-colors p-2 -mr-2" onclick="closeStoreModal()"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 md:p-8 flex-grow">
            <div class="flex flex-col md:flex-row gap-8 mb-12">
                <div class="w-32 h-32 rounded-full bg-surface-variant overflow-hidden border border-muted-border flex-shrink-0 self-start"><span class="font-title-md text-on-surface-variant flex items-center justify-center w-full h-full text-2xl">NS</span></div>
                <div class="flex-grow">
                    <div class="flex items-center gap-3 mb-2"><h3 class="font-display-lg text-headline-lg">NOIRÉ Studio</h3><span class="material-symbols-outlined text-secondary text-[20px]" title="Verified Business">verified</span></div>
                    <p class="text-on-surface-variant mb-6 font-body-md max-w-lg">Koleksi esensial minimalis sehari-hari dengan bahan berkelanjutan. Fokus pada siluet timeless.</p>
                    <div class="grid grid-cols-2 gap-y-4 gap-x-8 border-t border-b border-muted-border py-4 mb-6">
                        <div><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Pemilik</span><span class="font-title-md text-on-surface">Julian Thorne</span></div>
                        <div><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Status</span><span class="text-secondary font-semibold">Menunggu Tinjauan</span></div>
                        <div><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Bergabung</span><span class="font-body-md text-on-surface">24 Okt 2023</span></div>
                        <div><span class="block text-label-sm font-label-sm text-on-surface-variant uppercase mb-1">Lokasi</span><span class="font-body-md text-on-surface">Jakarta, ID</span></div>
                    </div>
                    <div class="mb-8">
                        <h4 class="font-title-md text-title-md mb-4 uppercase tracking-wider">Dokumen Verifikasi</h4>
                        <ul class="space-y-3">
                            <li class="flex items-center justify-between p-3 border border-muted-border bg-surface-container-lowest"><div class="flex items-center gap-3"><span class="material-symbols-outlined text-on-surface-variant">description</span><span class="font-body-md">Izin_Usaha.pdf</span></div><button class="text-primary font-label-sm uppercase hover:underline">Lihat</button></li>
                            <li class="flex items-center justify-between p-3 border border-muted-border bg-surface-container-lowest"><div class="flex items-center gap-3"><span class="material-symbols-outlined text-on-surface-variant">badge</span><span class="font-body-md">KTP_Pemilik.jpg</span></div><button class="text-primary font-label-sm uppercase hover:underline">Lihat</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface border-t border-muted-border p-6 flex justify-end gap-4 z-20">
            <button class="px-6 py-3 border border-muted-border text-on-surface font-label-sm uppercase tracking-wider hover:bg-surface-container-low transition-colors" onclick="openRejectModal()">Tolak</button>
            <button class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm uppercase tracking-wider hover:bg-primary-fixed-dim transition-colors">Setujui Toko</button>
        </div>
    </div>
</div>
<!-- Reject Reason Modal -->
<div aria-labelledby="reject-title" class="fixed inset-0 z-[110] hidden items-center justify-center px-gutter bg-scrim/50 backdrop-blur-sm" id="reject-modal" role="dialog">
    <div class="fixed inset-0 bg-scrim/20" onclick="closeRejectModal()"></div>
    <div class="bg-surface w-full max-w-md border border-muted-border p-8 relative z-10 shadow-2xl">
        <h3 class="font-display-lg text-title-md mb-4" id="reject-title">Berikan Alasan Penolakan</h3>
        <p class="text-on-surface-variant text-sm mb-6">Pesan ini akan dikirim ke pemilik toko.</p>
        <textarea class="w-full border border-muted-border bg-surface-container-lowest p-3 font-body-md focus:border-primary focus:ring-0 mb-6 min-h-[120px] resize-none" placeholder="Misal: Dokumen izin usaha belum lengkap..."></textarea>
        <div class="flex justify-end gap-3">
            <button class="px-4 py-2 text-on-surface-variant font-label-sm uppercase tracking-wider hover:text-on-surface transition-colors" onclick="closeRejectModal()">Batal</button>
            <button class="px-6 py-2 border border-error text-error font-label-sm uppercase tracking-wider hover:bg-error-container transition-colors">Konfirmasi Penolakan</button>
        </div>
    </div>
</div>
@endpush