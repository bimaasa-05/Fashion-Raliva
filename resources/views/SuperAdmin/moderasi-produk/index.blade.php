@extends('layouts.superadmin')

@section('title', 'Moderasi Produk')

@section('header-title', 'Moderasi Produk')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Tinjau produk pending dan beri keputusan setujui atau tolak beserta alasan.')

@php
    $tabs = [
        \App\Models\Product::STATUS_PENDING => ['label' => 'Menunggu', 'icon' => 'pending'],
        \App\Models\Product::STATUS_AKTIF => ['label' => 'Disetujui', 'icon' => 'task_alt'],
        \App\Models\Product::STATUS_DITOLAK => ['label' => 'Ditolak', 'icon' => 'block'],
    ];

    $statusIconMap = [
        \App\Models\Product::STATUS_PENDING => 'pending',
        \App\Models\Product::STATUS_AKTIF => 'task_alt',
        \App\Models\Product::STATUS_DITOLAK => 'gpp_bad',
    ];
@endphp

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
    .filled-icon { font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="px-container-margin pb-element-gap">
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
        <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Moderasi</span>
        </div>
        <div class="flex items-center justify-center space-x-gutter overflow-x-auto no-scrollbar py-2">
            @foreach ($tabs as $key => $tab)
                <a href="{{ route('superadmin.moderasi-produk', ['status' => $key]) }}"
                    class="font-label-sm text-label-sm px-4 py-2 border-b-2 transition-colors whitespace-nowrap {{ $activeStatus === $key
                        ? 'border-primary text-primary'
                        : 'border-transparent text-on-surface-variant hover:text-on-surface' }}">
                    {{ strtoupper($tab['label']) }} ({{ $stats[$key] ?? 0 }})
                </a>
            @endforeach
        </div>
    </div>
</div>

<div class="px-container-margin flex-grow">
    <div id="moderasi-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-gutter gap-y-container-margin">
        @forelse ($products as $product)
            <div class="group cursor-pointer flex flex-col transition-transform duration-300 hover:-translate-y-1"
                onclick="openDetailModal(this)"
                data-id="{{ $product->product_id }}"
                data-name="{{ $product->nama_produk }}"
                data-store="{{ $product->store->nama_toko ?? '-' }}"
                data-price="Rp {{ number_format($product->harga_dasar, 0, ',', '.') }}"
                data-category="{{ ($product->category->nama_kategori ?? '-') }}"
                data-desc="{{ $product->deskripsi }}"
                data-status="{{ $product->status }}"
                data-reason="{{ $product->alasan_penolakan }}"
                data-variants="{{ $product->variants->map(fn ($v) => trim(($v->warna ?? '') . ' ' . ($v->ukuran ?? '')))->filter()->implode(', ') }}">
                <div class="relative w-full aspect-[3/4] bg-surface-container-low mb-element-gap overflow-hidden rounded-lg">
                    @if ($product->images->first())
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset($product->images->first()->file_gambar) }}" alt="{{ $product->nama_produk }}" />
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-surface-container-high">
                            <span class="material-symbols-outlined text-[42px] text-on-surface-variant/40">checkroom</span>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2 p-1 bg-surface/80 rounded"><span class="material-symbols-outlined text-[18px] text-on-surface">{{ $statusIconMap[$product->status] ?? 'pending' }}</span></div>
                    @if ($product->status === \App\Models\Product::STATUS_DITOLAK)
                        <div class="absolute bottom-2 left-2 right-2 px-2 py-1 bg-error/90 text-on-error text-[9px] font-bold uppercase tracking-widest rounded text-center">Ditolak • Lihat Alasan</div>
                    @endif
                </div>
                <div class="flex flex-col flex-grow"><span class="font-label-sm text-label-sm text-on-surface-variant mb-1">{{ strtoupper($product->store->nama_toko ?? '-') }}</span><h3 class="font-body-md text-body-md text-on-surface leading-tight mb-1 truncate">{{ $product->nama_produk }}</h3><div class="font-body-md text-body-md text-on-surface mt-auto">Rp {{ number_format($product->harga_dasar, 0, ',', '.') }}</div></div>
            </div>
        @empty
            <p id="moderasi-kosong" class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-16">Belum ada produk pada status ini.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeProductCard = null;

    const productActionUrls = {
        setujui: (id) => '{{ route('superadmin.moderasi-produk.setujui', ':id:') }}'.replace(':id:', id),
        tolak: (id) => '{{ route('superadmin.moderasi-produk.tolak', ':id:') }}'.replace(':id:', id)
    };

    function openDetailModal(card) {
        activeProductCard = card;
        const d = card.dataset;
        document.getElementById('mod-name').textContent = d.name;
        document.getElementById('mod-store').textContent = d.store.toUpperCase();
        document.getElementById('mod-price').textContent = d.price;
        document.getElementById('mod-category').textContent = d.category;
        document.getElementById('mod-variants').textContent = d.variants || '-';
        document.getElementById('mod-desc').textContent = d.desc;

        const img = card.querySelector('img');
        const imgEl = document.getElementById('mod-img');
        if (img) {
            imgEl.src = img.src;
            imgEl.classList.remove('hidden');
        } else {
            imgEl.classList.add('hidden');
        }

        const reasonBox = document.getElementById('mod-reason-box');
        if (d.status === 'ditolak' && d.reason) {
            reasonBox.classList.remove('hidden');
            document.getElementById('mod-reason-text').textContent = d.reason;
        } else {
            reasonBox.classList.add('hidden');
        }

        const canDecide = d.status === 'pending';
        document.getElementById('mod-action-reject').classList.toggle('hidden', !canDecide);
        document.getElementById('mod-action-approve').classList.toggle('hidden', !canDecide);
        document.getElementById('mod-action-note').classList.toggle('hidden', canDecide);
        document.getElementById('approve-product-form').action = productActionUrls.setujui(d.id);

        document.getElementById('mod-reject-store').textContent = d.store;
        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function openRejectModal() {
        if (!activeProductCard) return;
        document.getElementById('mod-reject-store').textContent = activeProductCard.dataset.store;
        document.getElementById('reject-product-form').action = productActionUrls.tolak(activeProductCard.dataset.id);
        document.getElementById('reject-alasan-input').value = '';
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') { closeDetailModal(); closeRejectModal(); }
    });
</script>
@endpush

@push('modals')
<!-- Detail Modal -->
<div class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="detailModal">
    <div class="relative w-full max-w-2xl h-[min(795px,92vh)] bg-surface-container-lowest rounded-xl border border-muted-border shadow-2xl flex flex-col overflow-hidden">
        <div class="shrink-0 relative border-b border-muted-border">
            <div class="absolute inset-0 bg-gradient-to-r from-gold-accent/15 via-gold-accent/5 to-transparent pointer-events-none"></div>
            <div class="relative p-6 pr-14">
                <div class="flex items-center gap-2 flex-wrap mb-1.5">
                    <span id="mod-store" class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">-</span>
                </div>
                <h2 id="mod-name" class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface leading-tight">-</h2>
                <div id="mod-price" class="font-title-md text-title-md text-gold-accent mt-1">-</div>
            </div>
            <button type="button" class="absolute top-4 right-4 z-10 p-2 rounded-full hover:bg-surface-container-high transition-colors" onclick="closeDetailModal()"><span class="material-symbols-outlined text-on-surface">close</span></button>
        </div>

        <div class="flex-1 overflow-y-auto">
            <div id="mod-reason-box" class="hidden m-6 mb-0 bg-error/5 border border-error/25 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-error text-[18px] mt-0.5">gpp_bad</span>
                    <div>
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-error mb-1">Alasan Penolakan Sebelumnya</p>
                        <p id="mod-reason-text" class="text-sm text-on-surface"></p>
                    </div>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-0">
                <div class="bg-surface-container-low min-h-[220px]"><img id="mod-img" class="w-full h-full object-cover" src="" alt="Foto produk" /></div>
                <div class="p-6 space-y-4">
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Kategori</span><span id="mod-category" class="font-body-md text-body-md text-on-surface">-</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Varian (Warna & Ukuran)</span><span id="mod-variants" class="font-body-md text-body-md text-on-surface">-</span></div>
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Deskripsi</span><p id="mod-desc" class="font-body-md text-body-md text-on-surface-variant leading-relaxed text-sm">-</p></div>
                </div>
            </div>
        </div>

        <div class="shrink-0 border-t border-muted-border px-6 py-4 bg-surface/95 backdrop-blur flex gap-3">
            <p id="mod-action-note" class="hidden flex-1 self-center text-xs text-on-surface-variant italic">Keputusan sudah diambil untuk produk ini.</p>
            <button id="mod-action-reject" type="button" onclick="openRejectModal()" class="hidden flex-1 py-3 bg-transparent border border-error/40 text-error font-label-sm text-label-sm uppercase tracking-widest hover:bg-error/10 transition-colors rounded-lg">Tolak</button>
            <form id="approve-product-form" method="POST" action="" onsubmit="closeDetailModal()">
                @csrf
                <button id="mod-action-approve" type="submit" class="hidden w-full py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">Setujui Produk</button>
            </form>
        </div>
    </div>
</div>

<!-- Reject Reason Modal -->
<form id="reject-product-form" method="POST" action="" onsubmit="closeRejectModal(); closeDetailModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="rejectModal" onclick="if (event.target === this) closeRejectModal()">
        <div class="relative bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">block</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Alasan Penolakan</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Alasan dikirim ke <span id="mod-reject-store" class="font-bold text-on-surface">-</span>.</p>
                <textarea required minlength="10" maxlength="1000" name="alasan" id="reject-alasan-input" class="w-full border border-muted-border bg-surface-container-low rounded-lg p-3 font-body-md text-body-md text-on-surface focus:outline-none focus:border-error focus:ring-1 focus:ring-error mb-4" placeholder="Tulis alasan di sini... (minimal 10 karakter)" rows="4"></textarea>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeRejectModal()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endpush
