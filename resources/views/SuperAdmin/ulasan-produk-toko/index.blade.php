@extends('layouts.superadmin')

@section('title', 'Ulasan Produk Toko')
@section('header-title', 'Ulasan Produk Toko')
@section('header-badge', 'Pantau')
@section('header-subtitle', 'Pantau semua ulasan produk dari seluruh toko di platform')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filter-chip { transition: all 0.2s ease; }
    .filter-chip:hover { border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; transform: translateY(-1px); }
    .filter-chip.active { background-color: rgba(201, 162, 77, 0.15); border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; }
    .review-card { transition: all 0.3s ease; }
    .review-card:hover { border-color: rgba(201, 162, 77, 0.5); box-shadow: 0 8px 25px -5px rgba(201, 162, 77, 0.15); transform: translateY(-2px); }
    .star { color: #C9A24D; }
    .star-empty { color: rgba(201, 162, 77, 0.2); }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">star</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            {{ $stats['total'] }} Ulasan
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase tracking-wider border border-success/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                            {{ $stats['aktif'] }} Aktif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase tracking-wider border border-error/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                            {{ $stats['nonaktif'] }} Nonaktif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-container/30 text-on-tertiary-container text-[10px] font-bold uppercase tracking-wider border border-tertiary-container/50">
                            <span class="material-symbols-outlined text-[12px]">star</span>
                            {{ $stats['rata_rating'] }} Rata-rata
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wider border border-outline-variant">
                            {{ $stats['total_toko'] }} Toko
                        </span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg">Lihat semua ulasan dari seluruh toko. Tinjau kualitas produk dan layanan toko di platform Raliva.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <section class="rise rise-d1">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <!-- Status chips -->
            <div class="flex flex-wrap gap-2 items-center" data-filter-group="status">
                <span class="text-gold-accent material-symbols-outlined text-[16px]">filter_list</span>
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest self-center mr-1">Status:</span>
                <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide active" data-filter="status" data-value="">Semua</button>
                <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="status" data-value="aktif">Aktif</button>
                <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="status" data-value="nonaktif">Nonaktif</button>
            </div>
            <!-- Rating chips -->
            <div class="flex flex-wrap gap-2 items-center" data-filter-group="rating">
                <span class="text-gold-accent material-symbols-outlined text-[16px]">star</span>
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest self-center mr-1">Rating:</span>
                <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide active" data-filter="rating" data-value="">Semua</button>
                <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="rating" data-value="5">5★</button>
                <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="rating" data-value="4">4★</button>
                <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="rating" data-value="3">3★</button>
                <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="rating" data-value="2">2★</button>
                <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="rating" data-value="1">1★</button>
            </div>
            <!-- Search -->
            <div class="relative flex-1 min-w-[200px] lg:ml-auto">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant/50 text-[20px]">search</span>
                <input type="text" id="searchInput" placeholder="Cari produk, toko, atau reviewer..." class="w-full bg-transparent border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" oninput="applyFilter()" />
            </div>
        </div>
    </section>

    <!-- Review Grid -->
    <section class="rise rise-d2">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-4">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Ulasan</h2>
            <span id="review-count" class="text-on-surface-variant font-body-md text-sm">{{ $reviews->count() }} ulasan</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter" id="review-grid">
            @forelse ($reviews as $review)
                <div class="review-card group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 cursor-pointer card-premium"
                    data-id="{{ $review->review_id }}"
                    data-status="{{ $review->status }}"
                    data-rating="{{ $review->rating }}"
                    data-search="{{ strtolower($review->product->nama_produk ?? '' . ' ' . $review->store->nama_toko ?? '' . ' ' . $review->user->nama_lengkap ?? '') }}"
                    onclick="openDetailModal(this)">
                    <!-- Decorative blob -->
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/15 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                    <div class="relative">
                        <!-- Header: reviewer + status -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-secondary-container/30 flex items-center justify-center shrink-0">
                                    <span class="font-title-md text-title-sm text-secondary">{{ substr($review->user->nama_lengkap ?? '?', 0, 1) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-title-md text-title-sm text-on-surface truncate">{{ $review->user->nama_lengkap ?? '-' }}</h4>
                                    <p class="text-on-surface-variant text-xs truncate">{{ $review->store->nama_toko ?? '-' }}</p>
                                </div>
                            </div>
                            @if($review->status === 'aktif')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-success/10 text-success text-[9px] font-bold uppercase border border-success/20 shrink-0">
                                    <span class="w-1 h-1 rounded-full bg-success"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-error/10 text-error text-[9px] font-bold uppercase border border-error/20 shrink-0">
                                    <span class="w-1 h-1 rounded-full bg-error"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                        <!-- Product name -->
                        <p class="text-on-surface-variant text-xs mb-2 truncate">
                            <span class="material-symbols-outlined text-[12px] align-middle">checkroom</span>
                            {{ $review->product->nama_produk ?? '-' }}
                        </p>
                        <!-- Rating -->
                        <div class="flex items-center gap-1 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-[16px] {{ $i <= $review->rating ? 'star' : 'star-empty' }}">star</span>
                            @endfor
                            <span class="text-on-surface-variant text-xs ml-1">{{ $review->rating }}/5</span>
                        </div>
                        <!-- Ulasan preview -->
                        <p class="text-on-surface text-sm line-clamp-2 mb-3">{{ $review->ulasan ?? 'Tidak ada ulasan teks' }}</p>
                        <!-- Date -->
                        <p class="text-on-surface-variant/60 text-[11px]">{{ $review->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16" id="empty-static">
                    <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">reviews</span>
                    </div>
                    <p class="text-on-surface-variant font-body-md text-sm">Belum ada ulasan.</p>
                </div>
            @endforelse
            <!-- Filter empty state -->
            <div class="col-span-full text-center py-16 hidden" id="empty-filter">
                <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-on-surface-variant/50 text-[32px]">search_off</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada ulasan yang cocok dengan filter.</p>
                <button onclick="resetFilters()" class="mt-3 px-4 py-2 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant hover:border-gold-accent hover:text-gold-accent transition-colors">Reset Filter</button>
            </div>
        </div>
    </section>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" onclick="if (event.target === this) closeDetailModal()">
    <div class="bg-surface-container-lowest w-full max-w-lg rounded-xl border border-muted-border shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Ulasan</h3>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Informasi lengkap ulasan produk</p>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <!-- Body -->
        <div class="p-6 space-y-5">
            <!-- Reviewer info -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-secondary-container/30 flex items-center justify-center shrink-0">
                    <span id="modal-initial" class="font-title-md text-title-md text-secondary">-</span>
                </div>
                <div class="min-w-0">
                    <h4 id="modal-reviewer" class="font-title-md text-title-md text-on-surface truncate">-</h4>
                    <p id="modal-email" class="text-on-surface-variant text-sm truncate">-</p>
                </div>
                <div class="ml-auto shrink-0">
                    <span id="modal-status-badge" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase"></span>
                </div>
            </div>
            <!-- Product & Store -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 border border-muted-border rounded-lg">
                    <p class="text-on-surface-variant text-[10px] uppercase tracking-widest font-label-sm mb-1">Toko</p>
                    <p id="modal-store" class="text-on-surface font-body-md text-sm truncate">-</p>
                </div>
                <div class="p-3 border border-muted-border rounded-lg">
                    <p class="text-on-surface-variant text-[10px] uppercase tracking-widest font-label-sm mb-1">Produk</p>
                    <p id="modal-product" class="text-on-surface font-body-md text-sm truncate">-</p>
                </div>
            </div>
            <!-- Rating -->
            <div class="text-center py-4 border-y border-muted-border">
                <div id="modal-stars" class="flex items-center justify-center gap-1 mb-2"></div>
                <p id="modal-rating-text" class="text-on-surface-variant text-sm"></p>
            </div>
            <!-- Ulasan -->
            <div>
                <p class="text-on-surface-variant text-[10px] uppercase tracking-widest font-label-sm mb-2">Ulasan</p>
                <p id="modal-ulasan" class="text-on-surface font-body-md text-sm leading-relaxed">-</p>
            </div>
            <!-- Date -->
            <div class="flex items-center gap-2 text-on-surface-variant text-xs">
                <span class="material-symbols-outlined text-[14px]">schedule</span>
                <span id="modal-date">-</span>
            </div>
            <!-- Action buttons -->
            <div id="modal-actions" class="flex gap-3 pt-2">
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div id="toggleModal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" onclick="if (event.target === this) closeToggleModal()">
    <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
        <div class="p-8">
            <div class="w-14 h-14 rounded-full bg-tertiary-container/30 border border-tertiary-container/50 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-tertiary-container text-[28px]">warning</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center" id="toggle-title">Ubah Status Ulasan</h3>
            <p class="text-on-surface-variant text-sm text-center mb-4" id="toggle-message">-</p>
            <form method="POST" action="" id="toggle-form">
                @csrf
                @method('PUT')
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeToggleModal()">Batal</button>
                    <button type="submit" class="flex-1 bg-tertiary-container text-on-tertiary-container font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Ya, Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // === FILTER SYSTEM ===
    let activeFilters = { status: '', rating: '' };

    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            const group = chip.dataset.filter;
            const value = chip.dataset.value;
            activeFilters[group] = value;

            chip.closest('[data-filter-group]').querySelectorAll('.filter-chip').forEach(c => {
                c.classList.remove('active');
                c.classList.add('text-on-surface-variant');
            });
            chip.classList.add('active');
            chip.classList.remove('text-on-surface-variant');

            applyFilter();
        });
    });

    function applyFilter() {
        const search = (document.getElementById('searchInput').value || '').toLowerCase();
        const cards = document.querySelectorAll('#review-grid [data-id]');
        let visible = 0;

        cards.forEach(card => {
            const statusMatch = !activeFilters.status || card.dataset.status === activeFilters.status;
            const ratingMatch = !activeFilters.rating || card.dataset.rating === activeFilters.rating;
            const searchMatch = !search || card.dataset.search.includes(search);

            if (statusMatch && ratingMatch && searchMatch) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('review-count').textContent = visible + ' ulasan';
        document.getElementById('empty-static').classList.toggle('hidden', visible > 0 || cards.length > 0);
        document.getElementById('empty-filter').classList.toggle('hidden', visible > 0 || cards.length === 0);
    }

    function resetFilters() {
        activeFilters = { status: '', rating: '' };
        document.getElementById('searchInput').value = '';
        document.querySelectorAll('.filter-chip').forEach(c => {
            c.classList.remove('active');
            c.classList.add('text-on-surface-variant');
        });
        document.querySelectorAll('.filter-chip[data-value=""]').forEach(c => {
            c.classList.add('active');
            c.classList.remove('text-on-surface-variant');
        });
        applyFilter();
    }

    // === DETAIL MODAL ===
    const reviews = @json($reviewsJson);

    function openDetailModal(card) {
        const id = parseInt(card.dataset.id);
        const review = reviews.find(r => r.id === id);
        if (!review) return;

        document.getElementById('modal-initial').textContent = review.initial;
        document.getElementById('modal-reviewer').textContent = review.reviewer;
        document.getElementById('modal-email').textContent = review.email;
        document.getElementById('modal-store').textContent = review.store;
        document.getElementById('modal-product').textContent = review.product;
        document.getElementById('modal-ulasan').textContent = review.ulasan;
        document.getElementById('modal-date').textContent = review.date;

        // Status badge
        const badge = document.getElementById('modal-status-badge');
        if (review.status === 'aktif') {
            badge.className = 'inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase border border-success/20';
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-success"></span> Aktif';
        } else {
            badge.className = 'inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20';
            badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-error"></span> Nonaktif';
        }

        // Stars
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            starsHtml += '<span class="material-symbols-outlined text-[28px] ' + (i <= review.rating ? 'star' : 'star-empty') + '">star</span>';
        }
        document.getElementById('modal-stars').innerHTML = starsHtml;
        document.getElementById('modal-rating-text').textContent = review.rating + ' dari 5 bintang';

        // Actions
        const actionsDiv = document.getElementById('modal-actions');
        actionsDiv.innerHTML = '';
        if (review.status === 'aktif') {
            actionsDiv.innerHTML = '<button onclick="openToggleModal(' + review.id + ', \'nonaktifkan\', \'' + review.reviewer.replace(/'/g, "\\'") + '\')" class="flex-1 py-3 px-6 border border-error/50 text-error font-label-sm text-[11px] uppercase tracking-widest rounded-lg hover:bg-error/10 transition-colors inline-flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[16px]">block</span> Nonaktifkan</button>';
        } else {
            actionsDiv.innerHTML = '<button onclick="openToggleModal(' + review.id + ', \'aktifkan\', \'' + review.reviewer.replace(/'/g, "\\'") + '\')" class="flex-1 py-3 px-6 border border-success/50 text-success font-label-sm text-[11px] uppercase tracking-widest rounded-lg hover:bg-success/10 transition-colors inline-flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[16px]">check_circle</span> Aktifkan</button>';
        }

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

    // === TOGGLE STATUS MODAL ===
    function openToggleModal(id, action, reviewer) {
        const isActive = action === 'aktifkan';
        document.getElementById('toggle-title').textContent = isActive ? 'Aktifkan Ulasan' : 'Nonaktifkan Ulasan';
        document.getElementById('toggle-message').textContent = 'Ulasan dari ' + reviewer + ' akan di' + (isActive ? 'aktifkan kembali' : 'nonaktifkan') + '.';
        document.getElementById('toggle-form').action = '{{ url("superadmin/ulasan-produk-toko") }}/' + id + '/' + action;
        const modal = document.getElementById('toggleModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeToggleModal() {
        const modal = document.getElementById('toggleModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // === ESCAPE KEY ===
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeToggleModal();
        }
    });
</script>
@endpush
