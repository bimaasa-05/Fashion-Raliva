@extends('layouts.gudang')

@section('title', 'Notifikasi')

@section('header-title', 'Notifikasi')
@section('header-badge', $warehouse->nama_gudang ?? 'Gudang')
@section('header-subtitle', 'Pemberitahuan pekerjaan dan kondisi stok gudang Anda.')

@section('content')
@php
    $chips = [
        ['semua', 'Semua'],
        ['stok_menipis', 'Stok Menipis'],
        ['stok_habis', 'Stok Habis'],
        ['barang_masuk', 'Barang Masuk'],
        ['barang_keluar', 'Barang Keluar'],
        ['pemenuhan', 'Pemenuhan'],
        ['pemeriksaan', 'Pemeriksaan'],
        ['pemindahan', 'Pemindahan'],
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-14 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[480px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6" id="notif-chips">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Notifikasi</span>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($chips as $chip)
                    <button type="button" data-chip="{{ $chip[0] }}" class="{{ $loop->first ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:text-on-surface hover:border-gold-accent' }} px-4 py-2 rounded-full border font-label-sm text-[11px] uppercase tracking-wide transition-colors">{{ $chip[1] }}</button>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-between gap-4 pb-4 mb-2 border-b border-muted-border">
            <p class="font-label-sm text-xs text-on-surface-variant"><span id="notif-unread-count" class="font-bold text-gold-accent">{{ $notifications->whereNull('dibaca_pada')->count() }} notifikasi belum dibaca</span></p>
            <button type="button" id="mark-all-read" class="font-label-sm text-[10px] text-gold-accent uppercase tracking-widest hover:underline shrink-0">Tandai Semua Dibaca</button>
        </div>

        <ul id="notif-list" class="divide-y divide-muted-border">
            @forelse ($notifications as $item)
                @include('partials.notifikasi-item', ['item' => $item, 'showActor' => true])
            @empty
                <li class="py-10 text-center text-on-surface-variant">Belum ada notifikasi.</li>
            @endforelse
        </ul>

        <div id="notif-empty" class="hidden flex-col items-center justify-center py-16 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-on-surface-variant">notifications_off</span>
            </div>
            <p class="font-title-md text-title-md text-on-surface">Tidak Ada Notifikasi</p>
            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Tidak terdapat notifikasi pada kategori ini. Semua pekerjaan gudang sudah terpantau.</p>
        </div>

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
    const notifList = document.getElementById('notif-list');
    const notifEmpty = document.getElementById('notif-empty');
    const unreadCount = document.getElementById('notif-unread-count');

    const updateUnreadCount = () => {
        const count = notifList.querySelectorAll('.notif-dot').length;
        if (!unreadCount) return;
        unreadCount.textContent = count > 0 ? `${count} notifikasi belum dibaca` : 'Semua notifikasi telah dibaca';
        unreadCount.classList.toggle('text-gold-accent', count > 0);
        unreadCount.classList.toggle('font-bold', count > 0);
    };

    notifList?.querySelectorAll('.notif-item').forEach((item) => {
        item.addEventListener('click', () => {
            const id = item.getAttribute('data-notif-id');
            const target = item.getAttribute('data-notif-target') || '#';
            if (!id) return;
            fetch('{{ route("notifikasi.read", ":id") }}'.replace(':id', id), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            }).then((res) => {
                if (!res.ok) throw new Error('Gagal menandai dibaca.');
                return res.json();
            }).then((data) => {
                if (window.updateNotifBadge) window.updateNotifBadge();
                const dest = (data && data.target) || target;
                if (dest && dest !== '#') window.location.href = dest;
            }).catch(() => {
                if (target && target !== '#') window.location.href = target;
            });
            updateUnreadCount();
        });
    });

    document.getElementById('mark-all-read')?.addEventListener('click', () => {
        const btn = document.getElementById('mark-all-read');
        const original = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Memproses…';
        fetch('{{ route('gudang.notifikasi.tandai-dibaca') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then((res) => {
            if (!res.ok) throw new Error('Gagal menandai semua dibaca.');
            return res.json();
        }).then(() => {
            notifList?.querySelectorAll('.notif-dot').forEach((dot) => dot.remove());
            notifList?.querySelectorAll('.notif-item').forEach((item) => {
                item.classList.add('opacity-80');
                item.querySelector('.notif-text')?.classList.remove('font-semibold');
            });
            updateUnreadCount();
            showRalivaToast('Semua notifikasi ditandai sudah dibaca.');
        }).catch((err) => {
            showRalivaToast(err.message || 'Gagal menandai semua dibaca.', 'error');
        }).finally(() => {
            btn.disabled = false;
            btn.textContent = original;
        });
    });

    document.querySelectorAll('[data-chip]').forEach((chip) => {
        chip.addEventListener('click', () => {
            const activeClasses = ['bg-deep-onyx', 'text-on-primary', 'border-deep-onyx'];
            const idleClasses = ['border-muted-border', 'text-on-surface-variant'];
            document.querySelectorAll('[data-chip]').forEach((c) => {
                c.classList.remove(...activeClasses);
                c.classList.add(...idleClasses, 'hover:text-on-surface', 'hover:border-gold-accent');
            });
            chip.classList.remove(...idleClasses, 'hover:text-on-surface', 'hover:border-gold-accent');
            chip.classList.add(...activeClasses);

            const tipe = chip.getAttribute('data-chip');
            let visible = 0;
            notifList?.querySelectorAll('[data-tipe-item]').forEach((item) => {
                const show = tipe === 'semua' || item.getAttribute('data-tipe-item') === tipe;
                item.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            notifEmpty?.classList.toggle('hidden', visible > 0);
            notifEmpty?.classList.toggle('flex', visible === 0);
            notifList?.classList.toggle('hidden', visible === 0);
        });
    });
</script>
@endpush
