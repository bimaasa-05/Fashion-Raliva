@extends('layouts.owner')

@section('title', 'Notifikasi')

@section('header-title', 'Notifikasi')
@section('header-subtitle', 'Semua pemberitahuan penting untuk toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-14 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[480px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex items-center justify-between gap-4 pb-4 mb-2 border-b border-muted-border">
            <p class="font-label-sm text-xs text-on-surface-variant"><span class="font-bold text-gold-accent">{{ $notifications->whereNull('dibaca_pada')->count() }} notifikasi belum dibaca</span></p>
            <button type="button" id="mark-all-read" class="font-label-sm text-[10px] text-gold-accent uppercase tracking-widest hover:underline shrink-0">Tandai Semua Dibaca</button>
        </div>

        <ul id="notif-list" class="divide-y divide-muted-border">
            @forelse ($notifications as $item)
                @include('partials.notifikasi-item', ['item' => $item, 'showActor' => true])
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
                        window.updateNotifBadge?.();
                        const dest = (data && data.target) || target;
                        if (dest && dest !== '#') window.location.href = dest;
                    }).catch(() => {
                        if (target && target !== '#') window.location.href = target;
                    });
                } else if (target && target !== '#') {
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