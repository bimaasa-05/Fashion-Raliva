@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('header-title', 'Notifikasi')
@section('header-badge', 'Admin Toko')
@section('header-subtitle', 'Semua pemberitahuan penting operasional toko Anda.')

@section('content')
@php
    $iconMap = [
        'order' => 'shopping_bag',
        'pembayaran' => 'payments',
        'pengiriman' => 'local_shipping',
        'komplain' => 'support_agent',
        'wallet' => 'account_balance_wallet',
        'promo' => 'local_offer',
        'sistem' => 'notifications',
    ];
    $labelMap = [
        'order' => 'Pesanan',
        'pembayaran' => 'Pembayaran',
        'pengiriman' => 'Pengiriman',
        'komplain' => 'Komplain',
        'wallet' => 'Keuangan',
        'promo' => 'Promo',
        'sistem' => 'Sistem',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-14 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[480px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex items-center justify-between gap-4 pb-4 mb-2 border-b border-muted-border">
            <p class="font-label-sm text-xs text-on-surface-variant"><span class="font-bold text-gold-accent">{{ $notifications->whereNull('dibaca_pada')->count() }} notifikasi belum dibaca</span></p>
            <button type="button" id="mark-all-read" class="font-label-sm text-[10px] text-gold-accent uppercase tracking-widest hover:underline shrink-0">Tandai Semua Dibaca</button>
        </div>

        <ul id="notif-list" class="divide-y divide-muted-border">
            @forelse ($notifications as $item)
                @php
                    /** @var \App\Models\Notification $item */
                    $unread = is_null($item->dibaca_pada);
                    $relTime = $item->created_at?->diffForHumans() ?? '-';
                    $tone = $unread ? 'text-gold-accent bg-gold-accent/10' : 'bg-surface-container-high text-on-surface-variant';
                @endphp
                <li class="notif-item {{ $unread ? '' : 'opacity-80' }} flex items-start gap-4 px-4 py-4 hover:bg-surface-container-low transition-colors cursor-pointer rounded-lg"
                    data-notif-id="{{ $item->notification_id }}"
                    data-notif-target="{{ $item->url ?? '#' }}">
                    <div class="relative shrink-0 mt-0.5">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $tone }}">
                            <span class="material-symbols-outlined text-[20px]">{{ $iconMap[$item->tipe] ?? 'notifications' }}</span>
                        </div>
                        @if ($unread)
                            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface-container-lowest notif-dot"></span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-body-md text-sm text-on-surface {{ $unread ? 'font-semibold' : '' }} notif-text">{{ $item->judul }}</p>
                        <p class="text-on-surface-variant font-body-md text-[13px] mt-0.5">{{ $item->pesan }}</p>
                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                            <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ $relTime }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">{{ $labelMap[$item->tipe] ?? 'Sistem' }}</span>
                            @if ($item->aktor)
                                <span class="inline-flex items-center gap-1 font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant max-w-[160px] truncate">
                                    <span class="material-symbols-outlined text-[14px]">person</span>{{ $item->aktor->nama_lengkap }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant text-[20px] self-center shrink-0">chevron_right</span>
                </li>
            @empty
                <li class="py-10 text-center text-on-surface-variant">Belum ada notifikasi.</li>
            @endforelse
        </ul>

        @if ($notifications->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-4 mt-8 pt-6 border-t border-muted-border">
                <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} dari {{ $notifications->total() }} notifikasi</p>
                <div class="flex items-center gap-1">{{ $notifications->withQueryString()->links() }}</div>
            </div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const readUrlTemplate = '{{ route("notifikasi.read", ":id") }}';

        document.querySelectorAll('.notif-item').forEach((item) => {
            item.addEventListener('click', () => {
                const id = item.getAttribute('data-notif-id');
                const target = item.getAttribute('data-notif-target') || '#';
                if (id) {
                    fetch(readUrlTemplate.replace(':id', id), {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: { 'X-CSRF-TOKEN': csrf(), 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    }).then((res) => res.json()).then((data) => {
                        window.location.href = data.target || target;
                    }).catch(() => {
                        window.location.href = target;
                    });
                } else {
                    window.location.href = target;
                }
            });
        });

        document.getElementById('mark-all-read')?.addEventListener('click', () => {
            fetch('{{ route("notifikasi.mark-all-read") }}', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': csrf(), 'Content-Type': 'application/json', 'Accept': 'application/json' },
            }).then((res) => res.json()).then(() => {
                document.querySelectorAll('.notif-dot').forEach((dot) => dot.remove());
                document.querySelectorAll('.notif-item').forEach((item) => {
                    item.classList.add('opacity-80');
                    item.querySelector('.notif-text')?.classList.remove('font-semibold');
                });
                if (window.showRalivaToast) showRalivaToast('Semua notifikasi ditandai sudah dibaca.', 'done_all');
                if (window.updateNotifBadge) window.updateNotifBadge();
            });
        });
    })();
</script>
@endpush