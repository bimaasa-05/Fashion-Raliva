@extends('layouts.gudang')

@section('title', 'Notifikasi')

@section('header-title', 'Notifikasi')
@section('header-badge', 'Gudang Utama Bandung')
@section('header-subtitle', 'Pemberitahuan pekerjaan dan kondisi stok gudang Anda.')

@section('content')
@php
    $items = [
        ['tipe' => 'stok-menipis', 'label' => 'Stok Menipis', 'icon' => 'warning', 'tone' => 'gold', 'html' => 'Stok <span class="font-bold">Silk Scarf</span> tinggal <span class="font-bold">5 unit</span>.', 'time' => '10 menit lalu', 'unread' => true],
        ['tipe' => 'barang-masuk', 'label' => 'Barang Masuk', 'icon' => 'archive', 'tone' => 'neutral', 'html' => 'Barang masuk <span class="font-bold">BM-0012</span> menunggu pemeriksaan.', 'time' => '30 menit lalu', 'unread' => true],
        ['tipe' => 'stok-habis', 'label' => 'Stok Habis', 'icon' => 'priority_high', 'tone' => 'error', 'html' => 'Stok <span class="font-bold">Denim Jacket Classic</span> habis. Segera ajukan restock.', 'time' => '1 jam lalu', 'unread' => true],
        ['tipe' => 'pemenuhan', 'label' => 'Pemenuhan Pesanan', 'icon' => 'shopping_bag', 'tone' => 'neutral', 'html' => 'Permintaan pemenuhan pesanan <span class="font-bold">#RLV-2085</span> menunggu pengambilan barang.', 'time' => '2 jam lalu', 'unread' => true],
        ['tipe' => 'barang-keluar', 'label' => 'Barang Keluar', 'icon' => 'unarchive', 'tone' => 'neutral', 'html' => '<span class="font-bold">BK-0008</span> telah diserahkan ke kurir untuk pesanan <span class="font-bold">#RLV-2085</span>.', 'time' => '2 jam lalu', 'unread' => false],
        ['tipe' => 'pemeriksaan', 'label' => 'Pemeriksaan Stok', 'icon' => 'fact_check', 'tone' => 'error', 'html' => 'Pemeriksaan <span class="font-bold">PS-0012</span> menemukan selisih <span class="font-bold">2 unit</span> pada Silk Scarf.', 'time' => '3 jam lalu', 'unread' => false],
        ['tipe' => 'pemindahan', 'label' => 'Pemindahan Stok', 'icon' => 'swap_horiz', 'tone' => 'gold', 'html' => 'Pemindahan stok <span class="font-bold">PM-0004</span> telah diterima oleh Gudang Cabang Cimahi.', 'time' => '4 jam lalu', 'unread' => false],
        ['tipe' => 'stok-menipis', 'label' => 'Stok Menipis', 'icon' => 'warning', 'tone' => 'gold', 'html' => 'Stok <span class="font-bold">Wide Leg Trousers</span> tinggal <span class="font-bold">4 unit</span> — status kritis.', 'time' => 'Kemarin • 16:20', 'unread' => false],
        ['tipe' => 'pemindahan', 'label' => 'Pemindahan Stok', 'icon' => 'swap_horiz', 'tone' => 'gold', 'html' => 'Permintaan pemindahan <span class="font-bold">PM-0006</span> dibuat sebagai draft.', 'time' => 'Kemarin • 09:15', 'unread' => false],
        ['tipe' => 'pemeriksaan', 'label' => 'Pemeriksaan Stok', 'icon' => 'fact_check', 'tone' => 'neutral', 'html' => 'Jadwalkan pemeriksaan stok mingguan untuk rak A dan B.', 'time' => 'Kemarin • 08:00', 'unread' => false],
    ];
    $chips = [
        ['semua', 'Semua'],
        ['stok-menipis', 'Stok Menipis'],
        ['stok-habis', 'Stok Habis'],
        ['barang-masuk', 'Barang Masuk'],
        ['barang-keluar', 'Barang Keluar'],
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
            <p class="font-label-sm text-xs text-on-surface-variant"><span id="notif-unread-count" class="font-bold text-gold-accent">4 notifikasi belum dibaca</span></p>
            <button type="button" id="mark-all-read" class="font-label-sm text-[10px] text-gold-accent uppercase tracking-widest hover:underline shrink-0">Tandai Semua Dibaca</button>
        </div>

        <ul id="notif-list" class="divide-y divide-muted-border">
            @foreach ($items as $item)
                <li class="notif-item {{ $item['unread'] ? '' : 'opacity-80' }} flex items-start gap-4 px-4 py-4 hover:bg-surface-container-low transition-colors cursor-pointer rounded-lg" data-tipe-item="{{ $item['tipe'] }}">
                    <div class="relative shrink-0 mt-0.5">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ ['gold' => 'bg-gold-accent/10 border border-gold-accent/30 text-gold-accent', 'error' => 'bg-error/10 border border-error/20 text-error', 'neutral' => 'bg-surface-container-high text-on-surface'][$item['tone']] }}">
                            <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                        </div>
                        @if ($item['unread'])
                            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface-container-lowest notif-dot"></span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-body-md text-sm text-on-surface {{ $item['unread'] ? 'font-semibold' : '' }} notif-text">{!! $item['html'] !!}</p>
                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                            <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">{{ $item['time'] }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant text-[9px] font-bold uppercase border border-outline-variant">{{ $item['label'] }}</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline-variant text-[20px] self-center shrink-0">chevron_right</span>
                </li>
            @endforeach
        </ul>

        <div id="notif-empty" class="hidden flex-col items-center justify-center py-16 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-on-surface-variant">notifications_off</span>
            </div>
            <p class="font-title-md text-title-md text-on-surface">Tidak Ada Notifikasi</p>
            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Tidak terdapat notifikasi pada kategori ini. Semua pekerjaan gudang sudah terpantau.</p>
        </div>

        <div class="flex justify-center mt-8 pt-6 border-t border-muted-border">
            <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="px-5 py-2.5 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Muat Notifikasi Sebelumnya</button>
        </div>
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
            const dot = item.querySelector('.notif-dot');
            if (dot) {
                dot.remove();
                item.classList.add('opacity-80');
                const text = item.querySelector('.notif-text');
                text?.classList.remove('font-semibold');
                updateUnreadCount();
            }
        });
    });

    document.getElementById('mark-all-read')?.addEventListener('click', () => {
        notifList?.querySelectorAll('.notif-dot').forEach((dot) => dot.remove());
        notifList?.querySelectorAll('.notif-item').forEach((item) => {
            item.classList.add('opacity-80');
            item.querySelector('.notif-text')?.classList.remove('font-semibold');
        });
        updateUnreadCount();
        showRalivaToast('Semua notifikasi ditandai sudah dibaca.');
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
