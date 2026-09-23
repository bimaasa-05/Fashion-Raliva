@extends('layouts.produksi')

@section('title', 'Notifikasi')

@section('header-title', 'Notifikasi')
@section('header-subtitle', 'Semua pemberitahuan penting untuk tim produksi Anda.')

@section('content')
<div data-skeleton class="space-y-gutter">
    @for ($i = 0; $i < 5; $i++)
        <div class="h-24 bg-surface-container-high rounded-lg animate-pulse"></div>
    @endfor
</div>

<div data-real class="hidden space-y-section-gap">
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg px-6 py-4 flex items-center justify-between gap-4 card-premium">
        <div class="flex items-center gap-3">
            <span class="relative flex w-2.5 h-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-gold-accent opacity-60"></span>
                <span class="relative inline-flex rounded-full w-2.5 h-2.5 bg-gold-accent"></span>
            </span>
            <p class="font-title-md text-sm text-on-surface"><span id="notif-unread-count" class="font-bold text-gold-accent">{{ $notifications->whereNull('dibaca_pada')->count() }} notifikasi belum dibaca</span></p>
        </div>
        <button type="button" id="mark-all-read" class="font-label-sm text-[10px] text-gold-accent uppercase tracking-widest hover:underline shrink-0">Tandai Semua Dibaca</button>
    </section>

    <section>
        <ul id="notif-list" class="divide-y divide-muted-border bg-surface-container-lowest border border-muted-border rounded-lg card-premium overflow-hidden">
            @forelse ($notifications as $item)
                @include('partials.notifikasi-item', ['item' => $item, 'showActor' => true])
            @empty
                <li class="py-10 text-center flex flex-col items-center gap-3">
                    <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-surface-variant">notifications_off</span>
                    </div>
                    <p class="font-title-md text-title-md text-on-surface">Tidak Ada Notifikasi</p>
                </li>
            @endforelse
        </ul>

        @if ($notifications->hasPages())
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6">
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

        const updateUnreadCount = () => {
            const count = document.querySelectorAll('.notif-dot').length;
            const el = document.getElementById('notif-unread-count');
            if (!el) return;
            el.textContent = count > 0 ? `${count} notifikasi belum dibaca` : 'Semua notifikasi telah dibaca';
        };

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
                updateUnreadCount();
                if (window.showRalivaToast) showRalivaToast('Semua notifikasi ditandai sudah dibaca.', 'done_all');
                if (window.updateNotifBadge) window.updateNotifBadge();
            });
        });
    })();
</script>
@endpush