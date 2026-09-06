@extends('layouts.superadmin')

@section('title', 'Notifikasi')
@section('header-title', 'Notifikasi')
@section('header-badge', 'Pantau')
@section('header-subtitle', 'Semua notifikasi dari seluruh aktivitas platform Raliva.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filter-chip { transition: all 0.2s ease; }
    .filter-chip:hover { border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; transform: translateY(-1px); }
    .filter-chip.active { background-color: rgba(201, 162, 77, 0.15); border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">notifications</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            {{ $notifications->total() }} Total
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase tracking-wider border border-error/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
                            {{ $unread }} Belum Dibaca
                        </span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg">Lihat semua notifikasi dari seluruh aktivitas platform. Filter berdasarkan tipe notifikasi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Toolbar -->
    <section class="rise rise-d1">
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
            <!-- Filters -->
            <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="text-gold-accent material-symbols-outlined text-[16px]">filter_list</span>
                    <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-widest self-center mr-1">Tipe:</span>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide active" data-filter="tipe" data-value="">Semua</button>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="tipe" data-value="order">Pesanan</button>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="tipe" data-value="pembayaran">Pembayaran</button>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="tipe" data-value="pengiriman">Pengiriman</button>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="tipe" data-value="komplain">Komplain</button>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="tipe" data-value="wallet">Wallet</button>
                    <button type="button" class="filter-chip px-4 py-2 border border-muted-border rounded-full font-label-sm text-[11px] uppercase tracking-wide text-on-surface-variant" data-filter="tipe" data-value="sistem">Sistem</button>
                </div>
                <div class="lg:ml-auto flex items-center gap-3">
                    <p class="text-on-surface-variant font-body-md text-xs">
                        <span id="notif-count">{{ $notifications->total() }}</span> notifikasi
                    </p>
                    <button type="button" id="mark-all-read" class="py-2 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">
                        <span class="material-symbols-outlined text-[14px] align-middle mr-1">done_all</span>Tandai Semua Dibaca
                    </button>
                </div>
            </div>

            <!-- Notification List -->
            <ul id="notif-list" class="divide-y divide-muted-border">
                @forelse ($notifications as $item)
                    @php
                        $m = $meta[$item->tipe] ?? ['icon' => 'info', 'tone' => 'info', 'label' => 'Sistem'];
                        $unread = is_null($item->dibaca_pada);
                        $relTime = $item->created_at?->diffForHumans() ?? '-';
                        $toneClass = match($m['tone']) {
                            'success' => 'bg-secondary-container/20 text-secondary',
                            'warning' => 'bg-tertiary-container/20 text-tertiary',
                            'error' => 'bg-error/10 text-error',
                            default => 'bg-surface-container-high text-on-surface-variant',
                        };
                    @endphp
                    <li class="notif-item {{ $unread ? '' : 'opacity-80' }} flex items-start gap-4 px-4 py-4 hover:bg-surface-container-low transition-colors cursor-pointer rounded-lg" data-tipe-item="{{ $item->tipe }}">
                        <div class="relative shrink-0 mt-0.5">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $toneClass }}">
                                <span class="material-symbols-outlined text-[20px]">{{ $m['icon'] }}</span>
                            </div>
                            @if ($unread)
                                <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface-container-lowest notif-dot"></span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-body-md text-sm text-on-surface {{ $unread ? 'font-semibold' : '' }} notif-text">{!! $item->pesan !!}</p>
                            <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ $relTime }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">{{ $m['label'] }}</span>
                                @if ($item->user)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent text-[9px] font-bold border border-gold-accent/20">{{ $item->user->nama_lengkap ?? '-' }}</span>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-16 text-center text-on-surface-variant">
                        <div class="flex flex-col items-center gap-3">
                            <span class="material-symbols-outlined text-[48px] text-on-surface-variant/30">notifications_off</span>
                            <p class="font-title-md text-title-md text-on-surface">Tidak Ada Notifikasi</p>
                            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Belum ada notifikasi dari aktivitas platform.</p>
                        </div>
                    </li>
                @endforelse
            </ul>

            <!-- Empty Filter State -->
            <div id="notif-empty" class="hidden flex-col items-center py-12 text-center gap-3">
                <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px] text-on-surface-variant">filter_list_off</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada notifikasi pada kategori ini.</p>
                <button type="button" onclick="resetFilter()" class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
            </div>

            <!-- Pagination -->
            @if ($notifications->hasPages())
                <div class="flex justify-center mt-8 pt-6 border-t border-muted-border">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    // === FILTER CHIPS ===
    document.querySelectorAll('[data-filter="tipe"]').forEach((chip) => {
        chip.addEventListener('click', () => {
            const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
            const idleClasses = ['border-muted-border', 'text-on-surface-variant'];

            document.querySelectorAll('[data-filter="tipe"]').forEach((c) => {
                c.classList.remove(...activeClasses);
                c.classList.add(...idleClasses);
            });
            chip.classList.remove(...idleClasses);
            chip.classList.add(...activeClasses);

            const tipe = chip.getAttribute('data-value');
            const items = document.querySelectorAll('[data-tipe-item]');
            let visible = 0;

            items.forEach((item) => {
                const show = tipe === '' || item.getAttribute('data-tipe-item') === tipe;
                item.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            document.getElementById('notif-empty').style.display = visible === 0 ? 'flex' : 'none';
            document.getElementById('notif-list').style.display = visible === 0 ? 'none' : '';
            document.getElementById('notif-count').textContent = visible;
        });
    });

    function resetFilter() {
        document.querySelectorAll('[data-filter="tipe"]').forEach((c, i) => {
            const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
            const idleClasses = ['border-muted-border', 'text-on-surface-variant'];
            if (i === 0) {
                c.classList.remove(...idleClasses);
                c.classList.add(...activeClasses);
            } else {
                c.classList.remove(...activeClasses);
                c.classList.add(...idleClasses);
            }
        });
        document.querySelectorAll('[data-tipe-item]').forEach((item) => item.classList.remove('hidden'));
        document.getElementById('notif-empty').style.display = 'none';
        document.getElementById('notif-list').style.display = '';
        document.getElementById('notif-count').textContent = '{{ $notifications->total() }}';
    }

    // === MARK AS READ ===
    document.querySelectorAll('.notif-item').forEach((item) => {
        item.addEventListener('click', () => {
            const dot = item.querySelector('.notif-dot');
            if (dot) {
                dot.remove();
                item.classList.add('opacity-80');
                item.querySelector('.notif-text')?.classList.remove('font-semibold');
            }
        });
    });

    document.getElementById('mark-all-read')?.addEventListener('click', () => {
        fetch('{{ route('superadmin.notifikasi.tandai-dibaca') }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then((res) => {
            if (!res.ok) return;
            document.querySelectorAll('.notif-dot').forEach((dot) => dot.remove());
            document.querySelectorAll('.notif-item').forEach((item) => {
                item.classList.add('opacity-80');
                item.querySelector('.notif-text')?.classList.remove('font-semibold');
            });
            showRalivaToast('Semua notifikasi ditandai sudah dibaca.', 'done_all');
        });
    });
</script>
@endpush
