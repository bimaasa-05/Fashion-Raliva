@extends('layouts.admin')

@section('title', 'Data Pesanan')
@section('header-title', 'Data Pesanan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Lihat detail dan proses pesanan sesuai alur status.')

@php
    $badgeMap = [
        \App\Models\Order::STATUS_PENDING_PAYMENT => [
            'label' => 'Menunggu Bayar',
            'class' => \App\Support\StatusStyle::CLASS_ACCENT,
        ],
        \App\Models\Order::STATUS_MENUNGGU_PRODUKSI => [
            'label' => 'Menunggu Produksi',
            'class' => \App\Support\StatusStyle::CLASS_AMBER,
        ],
        \App\Models\Order::STATUS_DIBAYAR => ['label' => 'Baru', 'class' => \App\Support\StatusStyle::CLASS_AMBER],
        \App\Models\Order::STATUS_DIPROSES => ['label' => 'Diproses', 'class' => \App\Support\StatusStyle::CLASS_AMBER],
        \App\Models\Order::STATUS_DIKIRIM => ['label' => 'Dikirim', 'class' => \App\Support\StatusStyle::CLASS_SKY],
        \App\Models\Order::STATUS_SELESAI => ['label' => 'Selesai', 'class' => \App\Support\StatusStyle::CLASS_SUCCESS],
        \App\Models\Order::STATUS_DIBATALKAN => [
            'label' => 'Dibatalkan',
            'class' => 'bg-error/10 text-error border-error/20',
        ],
        \App\Models\Order::STATUS_REFUND => ['label' => 'Refund', 'class' => 'bg-error/10 text-error border-error/20'],
    ];
@endphp

@section('content')
    @include('partials.flash-toast')

    <section data-reveal-group class="grid grid-cols-2 lg:grid-cols-4 gap-gutter mb-6">
        <div data-reveal
            class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span
                class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none"
                aria-hidden="true">shopping_bag</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Total Pesanan</span>
            <span class="raliva-figure text-[26px] text-on-surface relative">{{ $orders->count() }}</span>
        </div>
        <div data-reveal
            class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span
                class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none"
                aria-hidden="true">payments</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Menunggu / Baru</span>
            <span
                class="raliva-figure text-[26px] text-gold-accent relative">{{ $orders->whereIn('status', ['pending_payment', 'dibayar'])->count() }}</span>
        </div>
        <div data-reveal
            class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span
                class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none"
                aria-hidden="true">local_shipping</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Diproses / Dikirim</span>
            <span
                class="raliva-figure text-[26px] text-secondary relative">{{ $orders->whereIn('status', ['diproses', 'dikirim'])->count() }}</span>
        </div>
        <div data-reveal
            class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span
                class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none"
                aria-hidden="true">task_alt</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Selesai</span>
            <span
                class="raliva-figure text-[26px] text-secondary relative">{{ $orders->where('status', 'selesai')->count() }}</span>
        </div>
    </section>

    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Pesanan Toko</h2>
            <button type="button" data-modal-open="modal-tambah-pesanan"
                class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span> Tambah Pesanan
            </button>
        </div>

        <div
            class="mb-6 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter
                    Status</span>
            </div>
            <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.pesanan') }}"
                    class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $activeStatus === 'semua' ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">Semua</a>
                @foreach ($statuses as $key => $label)
                    <a href="{{ route('admin.pesanan', ['status' => $key]) }}"
                        class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $activeStatus === $key ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr
                        class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Pesanan</th>
                        <th class="p-4 text-left">Pelanggan</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-left">Produksi</th>
                        <th class="p-4 text-right">Total</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($orders as $pesanan)
                        @php
                            $badge = $badgeMap[$pesanan->status] ?? [
                                'label' => ucfirst($pesanan->status),
                                'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                            ];
                            $custName =
                                $pesanan->checkout?->nama_penerima ?? ($pesanan->checkout?->user?->nama_lengkap ?? '-');
                            $custId = $pesanan->checkout?->user_id;
                            $waRaw =
                                $pesanan->checkout?->nomor_telepon ?? ($pesanan->checkout?->user?->nomor_telepon ?? '');
                            $waNum = preg_replace('/\D+/', '', (string) $waRaw);
                            if ($waNum !== '' && str_starts_with($waNum, '0')) {
                                $waNum = '62' . substr($waNum, 1);
                            }
                            $waTexts = [
                                \App\Models\Order::STATUS_PENDING_PAYMENT =>
                                    'Halo {nama}, pesanan {nomor} Anda menunggu pembayaran. Segera selesaikan ya!',
                                \App\Models\Order::STATUS_DIBAYAR =>
                                    'Halo {nama}, pembayaran pesanan {nomor} sudah kami terima. Pesanan segera diproses!',
                                \App\Models\Order::STATUS_MENUNGGU_PRODUKSI =>
                                    'Halo {nama}, pesanan {nomor} masuk antrean produksi.',
                                \App\Models\Order::STATUS_DIPROSES => 'Halo {nama}, pesanan {nomor} sedang diproduksi.',
                                \App\Models\Order::STATUS_SIAP_KIRIM =>
                                    'Halo {nama}, pesanan {nomor} siap kirim/diambil!',
                                \App\Models\Order::STATUS_DIKIRIM =>
                                    'Halo {nama}, pesanan {nomor} sudah dikirim. Mohon konfirmasi saat barang diterima ya!',
                                \App\Models\Order::STATUS_SELESAI =>
                                    'Halo {nama}, terima kasih! Pesanan {nomor} selesai. Jangan lupa beri ulasan ya!',
                                \App\Models\Order::STATUS_DIBATALKAN =>
                                    'Halo {nama}, pesanan {nomor} dibatalkan. Hubungi kami untuk info lebih lanjut.',
                                \App\Models\Order::STATUS_REFUND =>
                                    'Halo {nama}, pengembalian dana pesanan {nomor} sedang diproses.',
                            ];
                            $waMsg = str_replace(
                                ['{nama}', '{nomor}'],
                                [$custName, $pesanan->nomor_order ?? '#' . $pesanan->order_id],
                                $waTexts[$pesanan->status] ?? 'Halo {nama}, ada info mengenai pesanan {nomor} Anda.',
                            );
                            $waLink =
                                $waNum !== '' ? 'https://wa.me/' . $waNum . '?text=' . rawurlencode($waMsg) : null;
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors"
                            data-id="{{ $pesanan->order_id }}"
                            data-nomor="{{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}"
                            data-cust="{{ $custName }}" data-custid="{{ $custId }}">
                            <td class="p-4 font-mono text-on-surface">
                                {{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}</td>
                            <td class="p-4">
                                <p class="text-on-surface">{{ $custName }}</p>
                                <p class="text-on-surface-variant text-xs">{{ $pesanan->store?->nama_toko }}</p>
                            </td>
                            <td class="p-4 text-on-surface"
                                title="{{ $pesanan->items->pluck('nama_produk_snapshot')->implode(', ') }}">
                                {{ $pesanan->items->count() }} produk &#8226;
                                {{ \Illuminate\Support\Str::limit($pesanan->items->pluck('nama_produk_snapshot')->first(), 28) }}
                            </td>
                            <td class="p-4">@include('partials.produksi-waktu', ['produksiOrder' => $pesanan])</td>
                            <td class="p-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp
                                {{ number_format((float) ($pesanan->grand_total ?? 0), 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                @if ($pesanan->isOffline())
                                    <span
                                        class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-gold-accent/10 border-gold-accent/25 text-gold-accent">Offline</span>
                                @endif
                                @if ($pesanan->isAmbil())
                                    <span
                                        class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-secondary/10 border-secondary/25 text-secondary">Ambil
                                        di Toko</span>
                                @endif
                                @if ($pesanan->checkout?->payment?->status === \App\Models\Payment::STATUS_DITOLAK)
                                    <span
                                        class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-error/10 border-error/20 text-error">Bayar
                                        Ditolak</span>
                                @endif
                                @if ($pesanan->qc_perlu_admin_pada)
                                    <span title="Keterangan Produksi: {{ $pesanan->qc_perlu_admin_catatan }}"
                                        class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-error/10 border-error/20 text-error">QC
                                        Gagal</span>
                                @endif
                            </td>
                            <td class="p-4 text-right whitespace-nowrap">
                                @if (in_array($pesanan->status, [\App\Models\Order::STATUS_MENUNGGU_PRODUKSI, \App\Models\Order::STATUS_DIBAYAR], true))
                                    <button type="button" data-modal-open="modal-proses-{{ $pesanan->order_id }}"
                                        class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Proses</button>
                                @endif
                                @if (in_array(
                                        $pesanan->status,
                                        [
                                            \App\Models\Order::STATUS_DIBAYAR,
                                            \App\Models\Order::STATUS_MENUNGGU_PRODUKSI,
                                            \App\Models\Order::STATUS_DIPROSES,
                                        ],
                                        true) && !$pesanan->isPaymentVerified())
                                    <button type="button" data-modal-open="modal-batalkan-{{ $pesanan->order_id }}"
                                        class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Batalkan</button>
                                @endif
                                @if ($pesanan->status === \App\Models\Order::STATUS_SIAP_KIRIM && $pesanan->isAmbil())
                                    <button type="button" data-modal-open="modal-selesai-{{ $pesanan->order_id }}"
                                        class="px-3 py-1.5 ml-1 bg-secondary-container/20 border border-secondary/20 text-secondary font-label-sm text-[10px] uppercase rounded hover:bg-secondary-container/30 transition-colors">Selesai</button>
                                @endif

                                @if (
                                    !in_array(
                                        $pesanan->status,
                                        [
                                            \App\Models\Order::STATUS_DIKIRIM,
                                            \App\Models\Order::STATUS_SELESAI,
                                            \App\Models\Order::STATUS_DIBATALKAN,
                                            \App\Models\Order::STATUS_REFUND,
                                        ],
                                        true) && $pesanan->shipments->where('status', '!=', \App\Models\Shipment::STATUS_GAGAL)->isEmpty())
                                    <button type="button" data-modal-open="modal-alihkan-{{ $pesanan->order_id }}"
                                        class="px-3 py-1.5 ml-1 border border-gold-accent/40 text-gold-accent font-label-sm text-[10px] uppercase rounded hover:bg-gold-accent/10 transition-colors"
                                        title="{{ $pesanan->isAmbil() ? 'Alihkan ke diantar kurir' : 'Alihkan ke ambil di toko' }}">Alihkan</button>
                                @endif
                                @if ($pesanan->qc_perlu_admin_pada && $pesanan->status === \App\Models\Order::STATUS_MENUNGGU_QC)
                                    <button type="button" data-modal-open="modal-qctanggapan-{{ $pesanan->order_id }}"
                                        class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Tanggapi
                                        QC</button>
                                @endif
                                @if ($waLink)
                                    <a href="{{ $waLink }}" target="_blank" title="Chat WhatsApp customer"
                                        class="inline-flex items-center justify-center w-8 h-8 ml-1 rounded-lg bg-[#25D366]/10 border border-[#25D366]/30 text-[#1da851] hover:bg-[#25D366]/20 transition-colors align-middle">
                                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path
                                                d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2c-1.5 0-3-.4-4.3-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.6-6.1c-.3-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4 0-.5.1-.7l.4-.5c.1-.2.1-.4 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.2-.7.5-.9 1-.4 2.7 1.4 4.5 1.7 1.7 3.5 2.4 4.9 2.1.6-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2 0-.1-.2-.1-.4-.2z" />
                                        </svg>
                                    </a>
                                @endif
                                <button type="button" data-modal-open="modal-detail-{{ $pesanan->order_id }}"
                                    class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                                <button type="button" onclick="openDetailProduksi('{{ $pesanan->order_id }}')"
                                    class="px-3 py-1.5 ml-1 border border-gold-accent/40 text-gold-accent font-label-sm text-[10px] uppercase rounded hover:bg-gold-accent/10 transition-colors">Produksi</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant">Tidak ada pesanan pada
                                filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($orders as $pesanan)
                @php
                    $badge = $badgeMap[$pesanan->status] ?? [
                        'label' => ucfirst($pesanan->status),
                        'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                    ];
                    $custName = $pesanan->checkout?->nama_penerima ?? ($pesanan->checkout?->user?->nama_lengkap ?? '-');
                    $custId = $pesanan->checkout?->user_id;
                    $waRaw = $pesanan->checkout?->nomor_telepon ?? ($pesanan->checkout?->user?->nomor_telepon ?? '');
                    $waNum = preg_replace('/\D+/', '', (string) $waRaw);
                    if ($waNum !== '' && str_starts_with($waNum, '0')) {
                        $waNum = '62' . substr($waNum, 1);
                    }
                    $waTexts = [
                        \App\Models\Order::STATUS_PENDING_PAYMENT =>
                            'Halo {nama}, pesanan {nomor} Anda menunggu pembayaran. Segera selesaikan ya!',
                        \App\Models\Order::STATUS_DIBAYAR =>
                            'Halo {nama}, pembayaran pesanan {nomor} sudah kami terima. Pesanan segera diproses!',
                        \App\Models\Order::STATUS_MENUNGGU_PRODUKSI =>
                            'Halo {nama}, pesanan {nomor} masuk antrean produksi.',
                        \App\Models\Order::STATUS_DIPROSES => 'Halo {nama}, pesanan {nomor} sedang diproduksi.',
                        \App\Models\Order::STATUS_SIAP_KIRIM => 'Halo {nama}, pesanan {nomor} siap kirim/diambil!',
                        \App\Models\Order::STATUS_DIKIRIM =>
                            'Halo {nama}, pesanan {nomor} sudah dikirim. Mohon konfirmasi saat barang diterima ya!',
                        \App\Models\Order::STATUS_SELESAI =>
                            'Halo {nama}, terima kasih! Pesanan {nomor} selesai. Jangan lupa beri ulasan ya!',
                        \App\Models\Order::STATUS_DIBATALKAN =>
                            'Halo {nama}, pesanan {nomor} dibatalkan. Hubungi kami untuk info lebih lanjut.',
                        \App\Models\Order::STATUS_REFUND =>
                            'Halo {nama}, pengembalian dana pesanan {nomor} sedang diproses.',
                    ];
                    $waMsg = str_replace(
                        ['{nama}', '{nomor}'],
                        [$custName, $pesanan->nomor_order ?? '#' . $pesanan->order_id],
                        $waTexts[$pesanan->status] ?? 'Halo {nama}, ada info mengenai pesanan {nomor} Anda.',
                    );
                    $waLink = $waNum !== '' ? 'https://wa.me/' . $waNum . '?text=' . rawurlencode($waMsg) : null;
                @endphp
                <article data-table-row data-id="{{ $pesanan->order_id }}"
                    data-nomor="{{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}" data-cust="{{ $custName }}"
                    data-custid="{{ $custId }}"
                    class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-mono font-bold text-on-surface">
                                {{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $custName }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $pesanan->store?->nama_toko }}</p>
                        </div>
                        <div class="shrink-0">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            @if ($pesanan->isOffline())
                                <span
                                    class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-gold-accent/10 border-gold-accent/25 text-gold-accent">Offline</span>
                            @endif
                            @if ($pesanan->isAmbil())
                                <span
                                    class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-secondary/10 border-secondary/25 text-secondary">Ambil
                                    di Toko</span>
                            @endif
                            @if ($pesanan->checkout?->payment?->status === \App\Models\Payment::STATUS_DITOLAK)
                                <span
                                    class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-error/10 border-error/20 text-error">Bayar
                                    Ditolak</span>
                            @endif
                            @if ($pesanan->qc_perlu_admin_pada)
                                <span title="Keterangan Produksi: {{ $pesanan->qc_perlu_admin_catatan }}"
                                    class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-error/10 border-error/20 text-error">QC
                                    Gagal</span>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-3 pt-3 border-t border-muted-border">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Produk</p>
                            <p class="text-sm text-on-surface mt-0.5">{{ $pesanan->items->count() }} produk &#8226;
                                {{ \Illuminate\Support\Str::limit($pesanan->items->pluck('nama_produk_snapshot')->first(), 24) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Total</p>
                            <p class="font-bold text-gold-accent mt-0.5">Rp
                                {{ number_format((float) ($pesanan->grand_total ?? 0), 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-muted-border flex justify-end gap-1.5 flex-wrap">
                        @if (in_array($pesanan->status, [\App\Models\Order::STATUS_MENUNGGU_PRODUKSI, \App\Models\Order::STATUS_DIBAYAR], true))
                            <button type="button" data-modal-open="modal-proses-{{ $pesanan->order_id }}"
                                class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Proses</button>
                        @endif
                        @if (in_array(
                                $pesanan->status,
                                [
                                    \App\Models\Order::STATUS_DIBAYAR,
                                    \App\Models\Order::STATUS_MENUNGGU_PRODUKSI,
                                    \App\Models\Order::STATUS_DIPROSES,
                                ],
                                true) && !$pesanan->isPaymentVerified())
                            <button type="button" data-modal-open="modal-batalkan-{{ $pesanan->order_id }}"
                                class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Batalkan</button>
                        @endif
                        @if ($pesanan->status === \App\Models\Order::STATUS_SIAP_KIRIM && $pesanan->isAmbil())
                            <button type="button" data-modal-open="modal-selesai-{{ $pesanan->order_id }}"
                                class="px-3 py-1.5 ml-1 bg-secondary-container/20 border border-secondary/20 text-secondary font-label-sm text-[10px] uppercase rounded hover:bg-secondary-container/30 transition-colors">Selesai</button>
                        @endif

                        @if (
                            !in_array(
                                $pesanan->status,
                                [
                                    \App\Models\Order::STATUS_DIKIRIM,
                                    \App\Models\Order::STATUS_SELESAI,
                                    \App\Models\Order::STATUS_DIBATALKAN,
                                    \App\Models\Order::STATUS_REFUND,
                                ],
                                true) && $pesanan->shipments->where('status', '!=', \App\Models\Shipment::STATUS_GAGAL)->isEmpty())
                            <button type="button" data-modal-open="modal-alihkan-{{ $pesanan->order_id }}"
                                class="px-3 py-1.5 ml-1 border border-gold-accent/40 text-gold-accent font-label-sm text-[10px] uppercase rounded hover:bg-gold-accent/10 transition-colors"
                                title="{{ $pesanan->isAmbil() ? 'Alihkan ke diantar kurir' : 'Alihkan ke ambil di toko' }}">Alihkan</button>
                        @endif
                        @if ($pesanan->qc_perlu_admin_pada && $pesanan->status === \App\Models\Order::STATUS_MENUNGGU_QC)
                            <button type="button" data-modal-open="modal-qctanggapan-{{ $pesanan->order_id }}"
                                class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Tanggapi
                                QC</button>
                        @endif
                        @if ($waLink)
                            <a href="{{ $waLink }}" target="_blank" title="Chat WhatsApp customer"
                                class="inline-flex items-center justify-center w-8 h-8 ml-1 rounded-lg bg-[#25D366]/10 border border-[#25D366]/30 text-[#1da851] hover:bg-[#25D366]/20 transition-colors align-middle">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path
                                        d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2c-1.5 0-3-.4-4.3-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.6-6.1c-.3-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4 0-.5.1-.7l.4-.5c.1-.2.1-.4 0-.5l-.8-1.9c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.2-.7.5-.9 1-.4 2.7 1.4 4.5 1.7 1.7 3.5 2.4 4.9 2.1.6-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2 0-.1-.2-.1-.4-.2z" />
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('admin.pesanan.invoice', $pesanan->order_id) }}" target="_blank"
                            class="inline-block px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Invoice</a>
                        <button type="button" data-modal-open="modal-detail-{{ $pesanan->order_id }}"
                            class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        <button type="button" onclick="openDetailProduksi('{{ $pesanan->order_id }}')"
                            class="px-3 py-1.5 ml-1 border border-gold-accent/40 text-gold-accent font-label-sm text-[10px] uppercase rounded hover:bg-gold-accent/10 transition-colors">Produksi</button>
                    </div>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-6 text-center">Tidak ada pesanan pada filter ini.</p>
            @endforelse
        </div>
    </section>

    {{-- Modal detail per pesanan --}}
    @foreach ($orders as $pesanan)
        <div id="modal-detail-{{ $pesanan->order_id }}" data-modal
            class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div
                class="relative mx-auto w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
                <div
                    class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Pesanan</h3>
                        <p class="text-on-surface-variant font-mono text-xs uppercase tracking-wider mt-1">
                            {{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}</p>
                    </div>
                    <button type="button" data-modal-close
                        class="text-on-surface-variant hover:text-on-surface transition-colors"><span
                            class="material-symbols-outlined">close</span></button>
                </div>
                <div class="p-6 space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-3 border-b border-muted-border">
                        <dt class="text-on-surface-variant shrink-0">Pelanggan</dt>
                        <dd class="text-on-surface text-right">
                            {{ $pesanan->checkout?->nama_penerima ?? ($pesanan->checkout?->user?->nama_lengkap ?? '-') }}
                            @if ($pesanan->checkout?->nomor_telepon)
                                <br><span
                                    class="text-xs text-on-surface-variant">{{ $pesanan->checkout->nomor_telepon }}</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between gap-4 pb-3 border-b border-muted-border">
                        <dt class="text-on-surface-variant shrink-0">Toko</dt>
                        <dd class="text-on-surface text-right">{{ $pesanan->store?->nama_toko ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 pb-3 border-b border-muted-border">
                        <dt class="text-on-surface-variant shrink-0">Tanggal Pesanan</dt>
                        <dd class="text-on-surface text-right">
                            {{ $pesanan->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</dd>
                    </div>
                    @if ($pesanan->tgl_mulai_produksi || $pesanan->tgl_berakhir_produksi)
                        <div class="flex justify-between gap-4 pb-3 border-b border-muted-border">
                            <dt class="text-on-surface-variant shrink-0">Jadwal Produksi</dt>
                            <dd class="text-on-surface text-right">
                                {{ $pesanan->tgl_mulai_produksi?->translatedFormat('d M Y H:i') ?? '-' }} &rarr;
                                {{ $pesanan->tgl_berakhir_produksi?->translatedFormat('d M Y H:i') ?? '-' }}</dd>
                        </div>
                    @endif
                    @if ($pesanan->catatan)
                        <div class="pb-3 border-b border-muted-border">
                            <dt class="text-on-surface-variant mb-1">Catatan Pelanggan</dt>
                            <dd class="text-on-surface">{{ $pesanan->catatan }}</dd>
                        </div>
                    @endif
                    <div>
                        <p class="text-[10px] uppercase text-on-surface-variant mb-2">Item Pesanan</p>
                        <ul class="space-y-2">
                            @foreach ($pesanan->items as $it)
                                <li class="flex justify-between gap-3 bg-surface-container-low rounded-lg p-3">
                                    <span class="text-on-surface">{{ $it->nama_produk_snapshot }}</span>
                                    <span class="text-on-surface-variant shrink-0">{{ $it->quantity }} × Rp
                                        {{ number_format((float) ($it->harga_snapshot ?? 0), 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="flex justify-between gap-4 pt-3 border-t border-muted-border">
                        <dt class="text-on-surface-variant shrink-0">Total</dt>
                        <dd class="text-gold-accent font-bold text-right">Rp
                            {{ number_format((float) ($pesanan->grand_total ?? 0), 0, ',', '.') }}</dd>
                    </div>
                    @if ($pesanan->checkout?->payment?->proofs && $pesanan->checkout->payment->proofs->isNotEmpty())
                        <div class="pt-3 border-t border-muted-border">
                            <p class="text-[10px] uppercase text-on-surface-variant mb-2">Bukti Bayar</p>
                            @foreach ($pesanan->checkout->payment->proofs as $proof)
                                <a href="{{ asset('storage/' . $proof->file_bukti) }}" target="_blank"
                                    class="flex items-center gap-3 p-3 bg-surface-container-low rounded-lg border border-muted-border hover:border-gold-accent transition-colors">
                                    <span class="material-symbols-outlined text-gold-accent">receipt_long</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-on-surface truncate">
                                            {{ \Illuminate\Support\Str::afterLast($proof->file_bukti, '/') }}</p>
                                        <p class="text-xs text-on-surface-variant">
                                            {{ $proof->uploaded_at?->translatedFormat('d M Y H:i') ?? '-' }} •
                                            {{ $pesanan->checkout->payment->paymentMethod->nama_metode ?? 'Transfer' }} •
                                            Rp
                                            {{ number_format((float) ($pesanan->checkout->payment->jumlah ?? 0), 0, ',', '.') }}
                                            @if ((float) ($pesanan->checkout->payment->jumlah_saldo ?? 0) > 0)
                                                <span class="text-emerald-600">(saldo Rp
                                                    {{ number_format((float) $pesanan->checkout->payment->jumlah_saldo, 0, ',', '.') }}
                                                    + transfer Rp
                                                    {{ number_format((float) $pesanan->checkout->payment->sisa_transfer, 0, ',', '.') }})</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="material-symbols-outlined text-on-surface-variant">open_in_new</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex justify-between gap-4">
                        <dt class="text-on-surface-variant shrink-0">Status</dt>
                        <dd class="text-on-surface text-right">
                            {{ $badgeMap[$pesanan->status]['label'] ?? ucfirst($pesanan->status) }}</dd>
                    </div>
                </div>
                <div
                    class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end gap-3">
                    <a href="{{ route('admin.pesanan.invoice', $pesanan->order_id) }}" target="_blank"
                        class="px-5 py-2.5 bg-deep-onyx text-on-primary rounded-lg text-xs font-semibold btn-premium">Lihat
                        Invoice</a>
                    <button type="button" data-modal-close
                        class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
                </div>
            </div>
        </div>
        @if (in_array($pesanan->status, [\App\Models\Order::STATUS_MENUNGGU_PRODUKSI, \App\Models\Order::STATUS_DIBAYAR], true))
            <div id="modal-proses-{{ $pesanan->order_id }}" data-modal
                class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                <form method="POST" action="{{ route('admin.pesanan.proses', $pesanan->order_id) }}"
                    class="relative mx-auto w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
                    @csrf
                    <div
                        class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                        <div>
                            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Proses Pesanan</h3>
                            <p class="text-on-surface-variant font-mono text-xs uppercase tracking-wider mt-1">
                                {{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}</p>
                        </div>
                        <button type="button" data-modal-close
                            class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0"
                            aria-label="Tutup">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="text-xs text-on-surface-variant">Atur jadwal produksi, lalu teruskan ke tim Produksi.
                            Input kebutuhan bahan dilakukan oleh Produksi.</p>
                        <div>
                            <p class="text-xs font-medium text-gold-accent mb-2">Jadwal Produksi</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Tgl
                                        Mulai Produksi *</label>
                                    <input type="datetime-local" name="tgl_mulai_produksi" required
                                        class="raliva-input w-full" />
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Tgl
                                        Berakhir Produksi *</label>
                                    <input type="datetime-local" name="tgl_berakhir_produksi" required
                                        class="raliva-input w-full" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex gap-3">
                        <button type="button" data-modal-close
                            class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                        <button type="submit"
                            class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Proses
                            Pesanan</button>
                    </div>
                </form>
            </div>
        @endif
        @if (in_array(
                $pesanan->status,
                [
                    \App\Models\Order::STATUS_DIBAYAR,
                    \App\Models\Order::STATUS_MENUNGGU_PRODUKSI,
                    \App\Models\Order::STATUS_DIPROSES,
                ],
                true) && !$pesanan->isPaymentVerified())
            <div id="modal-batalkan-{{ $pesanan->order_id }}" data-modal
                class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                <form method="POST" action="{{ route('admin.pesanan.batalkan', $pesanan->order_id) }}"
                    class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-8">
                    @csrf
                    <div
                        class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                        <span class="material-symbols-outlined text-error text-[28px]">cancel</span>
                    </div>
                    <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Batalkan Pesanan</h3>
                    <p class="text-on-surface-variant text-sm text-center mb-4">Pesanan <span
                            class="font-mono font-bold text-on-surface">{{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}</span>
                        akan dibatalkan dan Customer dinotifikasi.</p>
                    <textarea name="alasan" required minlength="10" maxlength="1000" rows="3" class="raliva-textarea"
                        placeholder="Alasan pembatalan... (minimal 10 karakter)"></textarea>
                    <div class="flex space-x-3 mt-4">
                        <button type="button" data-modal-close
                            class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
                        <button type="submit"
                            class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
                    </div>
                </form>
            </div>
        @endif
        @if ($pesanan->status === \App\Models\Order::STATUS_SIAP_KIRIM && $pesanan->isAmbil())
            <div id="modal-selesai-{{ $pesanan->order_id }}" data-modal
                class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                <form method="POST" action="{{ route('admin.pesanan.selesai', $pesanan->order_id) }}"
                    class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-8">
                    @csrf
                    <div
                        class="w-14 h-14 rounded-full bg-secondary-container/20 border border-secondary/25 flex items-center justify-center mx-auto mb-5">
                        <span class="material-symbols-outlined text-secondary text-[28px]">storefront</span>
                    </div>
                    <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Selesai — Sudah Diambil</h3>
                    <p class="text-on-surface-variant text-sm text-center mb-4">Pesanan
                        {{ $pesanan->isOffline() ? 'offline' : 'online' }} <span
                            class="font-mono font-bold text-on-surface">{{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}</span>
                        akan ditandai <b>Selesai</b> dan dana penjualan masuk ke saldo toko. Konfirmasi bahwa customer sudah
                        mengambil barangnya.</p>
                    <input type="text" name="catatan" maxlength="500" class="raliva-input"
                        placeholder="Catatan (opsional)" />
                    <div class="flex space-x-3 mt-4">
                        <button type="button" data-modal-close
                            class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
                        <button type="submit"
                            class="flex-1 bg-secondary-container/20 text-secondary border border-secondary/20 font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-secondary-container/30 transition-colors rounded-lg btn-premium">Konfirmasi
                            Selesai</button>
                    </div>
                </form>
            </div>
        @endif
        @if (
            !in_array(
                $pesanan->status,
                [
                    \App\Models\Order::STATUS_DIKIRIM,
                    \App\Models\Order::STATUS_SELESAI,
                    \App\Models\Order::STATUS_DIBATALKAN,
                    \App\Models\Order::STATUS_REFUND,
                ],
                true) && $pesanan->shipments->where('status', '!=', \App\Models\Shipment::STATUS_GAGAL)->isEmpty())
            @php
                $ongkirLama = (float) ($pesanan->total_ongkir ?? 0);
                $grandBaru = max(0, (float) $pesanan->grand_total - $ongkirLama);
            @endphp
            <div id="modal-alihkan-{{ $pesanan->order_id }}" data-modal
                class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                <form method="POST" action="{{ route('admin.pesanan.alihFulfillment', $pesanan->order_id) }}"
                    class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-8">
                    @csrf
                    <div
                        class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-5">
                        <span class="material-symbols-outlined text-gold-accent text-[28px]">swap_horiz</span>
                    </div>
                    <h3 class="font-title-md text-title-md text-on-surface mb-1 text-center">Ubah Tipe Pengiriman</h3>
                    <p class="text-on-surface-variant text-sm text-center font-mono mb-3">
                        {{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}</p>
                    @if (!$pesanan->isAmbil())
                        <p class="text-on-surface-variant text-sm text-center mb-4">Pilih <b>Ambil di Toko</b> — ongkir Rp
                            {{ number_format($ongkirLama, 0, ',', '.') }} akan <b>dibatalkan</b> dan total jadi <b>Rp
                                {{ number_format($grandBaru, 0, ',', '.') }}</b>. Customer dinotifikasi.</p>
                    @else
                        <p class="text-on-surface-variant text-sm text-center mb-4">Pilih <b>Diantar</b> — ongkir tidak
                            dipulihkan otomatis (saat ini Rp {{ number_format($ongkirLama, 0, ',', '.') }}). Customer
                            dinotifikasi.</p>
                    @endif
                    <div class="flex flex-wrap gap-3 mt-4">
                        <button type="submit" name="fulfillment" value="diantar"
                            @disabled(!$pesanan->isAmbil())
                            class="flex-1 min-w-[7.5rem] {{ !$pesanan->isAmbil() ? 'bg-surface-container-low border border-muted-border text-on-surface-variant cursor-not-allowed opacity-60' : 'bg-gold-accent/90 text-deep-onyx btn-premium' }} font-label-sm text-label-sm py-3 uppercase tracking-widest rounded-lg transition-opacity">Diantar
                            (Kurir)</button>
                        <button type="submit" name="fulfillment" value="ambil"
                            @disabled($pesanan->isAmbil())
                            class="flex-1 min-w-[7.5rem] {{ $pesanan->isAmbil() ? 'bg-surface-container-low border border-muted-border text-on-surface-variant cursor-not-allowed opacity-60' : 'bg-gold-accent/90 text-deep-onyx btn-premium' }} font-label-sm text-label-sm py-3 uppercase tracking-widest rounded-lg transition-opacity">Ambil
                            di Toko</button>
                        <button type="button" data-modal-close
                            class="flex-1 min-w-[7.5rem] bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
                    </div>
                </form>
            </div>
        @endif
        @if ($pesanan->qc_perlu_admin_pada && $pesanan->status === \App\Models\Order::STATUS_MENUNGGU_QC)
            <div id="modal-qctanggapan-{{ $pesanan->order_id }}" data-modal
                class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/50" data-modal-close></div>
                <form method="POST" action="{{ route('admin.pesanan.qcTanggapan', $pesanan->order_id) }}"
                    class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-8">
                    @csrf
                    <div
                        class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                        <span class="material-symbols-outlined text-error text-[28px]">report</span>
                    </div>
                    <h3 class="font-title-md text-title-md text-on-surface mb-1 text-center">Tanggapan QC Gagal</h3>
                    <p class="text-on-surface-variant text-sm text-center font-mono mb-3">
                        {{ $pesanan->nomor_order ?? '#' . $pesanan->order_id }}</p>
                    <div
                        class="bg-error/5 border border-error/20 rounded-lg p-3 mb-4 text-xs text-on-surface space-y-1">
                        <p><span class="font-bold uppercase text-error">Keterangan Produksi:</span>
                            {{ $pesanan->qc_perlu_admin_catatan }}</p>
                        <p class="text-on-surface-variant">Sejak
                            {{ $pesanan->qc_perlu_admin_pada?->translatedFormat('d M Y H:i') }}</p>
                    </div>
                    <textarea name="catatan" rows="2" maxlength="500" class="raliva-textarea mb-4"
                        placeholder="Catatan untuk tim Produksi (opsional)"></textarea>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" name="aksi" value="rework"
                            class="flex-1 min-w-[7.5rem] bg-error/10 border border-error/20 text-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-error/20 transition-colors rounded-lg">Rework
                            Produksi</button>
                        <button type="submit" name="aksi" value="lanjut"
                            class="flex-1 min-w-[7.5rem] bg-gold-accent/90 text-deep-onyx font-label-sm text-label-sm py-3 uppercase tracking-widest btn-premium rounded-lg">Lanjut
                            QC</button>
                        <button type="button" data-modal-close
                            class="flex-1 min-w-[7.5rem] bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
                    </div>
                </form>
            </div>
        @endif
    @endforeach

    {{-- Modal Tambah Pesanan --}}
    <div id="modal-tambah-pesanan" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <form method="POST" action="{{ route('admin.pesanan.store') }}" enctype="multipart/form-data"
            class="relative mx-auto w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
            @csrf
            <div
                class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Pesanan</h3>
                    <p class="text-on-surface-variant text-sm mt-1">Pilih status customer (<b>Online</b> = user terdaftar,
                        <b>Offline</b> = tamu). Cara terima barang ditentukan belakangan di Pengiriman.</p>
                </div>
                <button type="button" data-modal-close
                    class="text-on-surface-variant hover:text-on-surface transition-colors"><span
                        class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-5">
                {{-- Status customer: Online / Offline --}}
                <div>
                    <span class="raliva-label">Status Customer</span>
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <label
                            class="flex flex-col items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                            <input type="radio" class="sr-only" name="tipe_pesanan" value="online" checked
                                onchange="toggleTipePesanan()" /> Online <span
                                class="text-[9px] normal-case font-normal opacity-70 mt-0.5">user terdaftar</span>
                        </label>
                        <label
                            class="flex flex-col items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                            <input type="radio" class="sr-only" name="tipe_pesanan" value="offline"
                                onchange="toggleTipePesanan()" /> Offline <span
                                class="text-[9px] normal-case font-normal opacity-70 mt-0.5">tamu / walk-in</span>
                        </label>
                    </div>
                </div>

                {{-- Online: pilih customer --}}
                <div id="online-fields" class="space-y-4">
                    <div>
                        <label class="raliva-label" for="tp-cust">Customer <span class="text-error">*</span></label>
                        <select id="tp-cust" name="user_id" class="raliva-select" required>
                            <option value="">— Pilih Customer —</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->user_id }}">{{ $c->nama_lengkap }} ({{ $c->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Offline: data penerima --}}
                <div id="offline-fields" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="raliva-label" for="tp-nama">Nama Penerima <span
                                    class="text-error">*</span></label>
                            <input id="tp-nama" name="nama_penerima" type="text" class="raliva-input"
                                placeholder="Nama lengkap penerima" />
                        </div>
                        <div>
                            <label class="raliva-label" for="tp-telp">Nomor Telepon <span
                                    class="text-error">*</span></label>
                            <input id="tp-telp" name="nomor_telepon" type="text" class="raliva-input"
                                placeholder="08xxxxxxxxxx" />
                        </div>
                    </div>
                    <div>
                        <label class="raliva-label" for="tp-email">Email Pelanggan</label>
                        <input id="tp-email" name="email_pelanggan" type="email" class="raliva-input"
                            placeholder="email@contoh.com (opsional)" />
                    </div>
                    <div>
                        <label class="raliva-label" for="tp-alamat">Alamat <span class="text-error">*</span></label>
                        <textarea id="tp-alamat" name="alamat" rows="2" class="raliva-textarea"
                            placeholder="Alamat lengkap penerima"></textarea>
                    </div>

                    {{-- Pembayaran offline --}}
                    <div class="pt-3 border-t border-muted-border">
                        <span class="raliva-label">Metode Pembayaran</span>
                        <div class="grid grid-cols-2 gap-3 mt-2">
                            <label
                                class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                                <input type="radio" class="sr-only" name="metode_bayar" value="tunai" checked
                                    onchange="toggleMetodeBayar()" /> Tunai
                            </label>
                            <label
                                class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                                <input type="radio" class="sr-only" name="metode_bayar" value="transfer"
                                    onchange="toggleMetodeBayar()" /> Transfer
                            </label>
                        </div>
                    </div>
                    <div id="transfer-fields" class="space-y-3 hidden">
                        <div>
                            <label class="raliva-label" for="tp-acc">Rekening Tujuan <span
                                    class="text-error">*</span></label>
                            <select id="tp-acc" name="payment_account_id" class="raliva-select">
                                <option value="">— Pilih Rekening —</option>
                                @foreach ($paymentAccounts as $acc)
                                    <option value="{{ $acc->platform_bank_account_id }}">{{ $acc->nama }} •
                                        {{ $acc->nomor_rekening }} ({{ $acc->nama_pemilik }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="raliva-label" for="tp-bukti">Bukti Transfer <span
                                    class="text-error">*</span></label>
                            <input id="tp-bukti" name="bukti" type="file" accept="image/jpeg,image/png,image/jpg"
                                class="raliva-input" />
                            <p class="text-xs text-on-surface-variant mt-1">Format jpg/png, maks 4MB.</p>
                        </div>
                    </div>
                </div>

                {{-- Item produk --}}
                <div class="pt-3 border-t border-muted-border">
                    <p class="text-xs font-medium text-gold-accent">Item Produk (minimal 1 baris)</p>
                    <div id="item-container" class="space-y-3 mt-3"></div>
                    <button type="button" onclick="addItemRow()"
                        class="mt-2 w-full py-2.5 border border-dashed border-outline-variant rounded-lg text-xs font-semibold text-on-surface-variant hover:border-gold-accent hover:text-gold-accent transition-colors flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">add</span> Tambah Produk
                    </button>
                </div>

                <div>
                    <label class="raliva-label" for="tp-catatan">Catatan untuk Pelanggan</label>
                    <textarea id="tp-catatan" name="catatan" rows="2" maxlength="1000" class="raliva-textarea"
                        placeholder="Catatan pesanan, mis. request khusus customer (opsional)"></textarea>
                </div>

                <div class="pt-3 border-t border-muted-border flex justify-between gap-4">
                    <dt class="text-on-surface-variant shrink-0">Total</dt>
                    <dd class="text-gold-accent font-bold text-right">Rp <span id="grand-total">0</span></dd>
                </div>
                <p class="text-xs text-on-surface-variant">Subtotal, ongkir (Rp 0), dan grand total dihitung ulang
                    otomatis. Status awal Menunggu Pembayaran.</p>
            </div>
            <div
                class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end gap-3">
                <button type="button" data-modal-close
                    class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit"
                    class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Buat
                    Pesanan</button>
            </div>
        </form>
    </div>

    {{-- Modal Detail Produksi (timeline) per pesanan --}}
    @foreach ($orders as $pesanan)
        @include('partials.modal-produksi-detail', ['o' => $pesanan])
    @endforeach
@endsection

@push('scripts')
    <script>
        const lockScroll = () => {
            const w = window.innerWidth - document.documentElement.clientWidth;
            if (w > 0) {
                document.body.style.paddingRight = w + 'px';
                document.documentElement.style.paddingRight = w + 'px';
            }
            document.body.style.overflow = 'hidden';
            document.documentElement.style.overflow = 'hidden';
        };
        const unlockScroll = () => {
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            document.documentElement.style.overflow = '';
            document.documentElement.style.paddingRight = '';
        };
        // Patch all data-modal on this page to use lockScroll with padding (anti geser)
        document.querySelectorAll('[data-modal-open]').forEach(btn => {
            btn.addEventListener('click', () => setTimeout(lockScroll, 0));
        });
        document.querySelectorAll('[data-modal-close]').forEach(el => {
            el.addEventListener('click', () => {
                setTimeout(() => {
                    if (!document.querySelector('[data-modal]:not(.hidden)')) unlockScroll();
                }, 50);
            });
        });
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-modal]')) setTimeout(() => {
                if (!document.querySelector('[data-modal]:not(.hidden)')) unlockScroll();
            }, 50);
        });
    </script>
@endpush

@push('scripts')
    <script>
        /* ====================================================================
           MODAL TAMBAH PESANAN — Online/Offline + dynamic product rows
           ==================================================================== */
        @php
            $itemVariantsJson = $variants
                ->map(function ($v) {
                    return [
                        'product_variant_id' => $v->product_variant_id,
                        'nama_produk' => $v->product?->nama_produk ?? 'Produk',
                        'warna' => $v->warna,
                        'ukuran' => $v->ukuran,
                        'stok' => $v->warehouseStocks->sum('jumlah_stok'),
                        'harga' => (float) ($v->harga ?? 0),
                    ];
                })
                ->toJson();
        @endphp
        const itemVariants = {!! $itemVariantsJson !!};

        function variantLabel(v) {
            return [v.nama_produk, v.ukuran, v.warna].filter((item) => typeof item === 'string' && item !== '').join(' — ');
        }

        let itemIdx = 0;

        function toggleTipePesanan() {
            const online = document.querySelector('#modal-tambah-pesanan input[name="tipe_pesanan"]:checked')?.value ===
                'online';
            document.getElementById('online-fields').classList.toggle('hidden', !online);
            document.getElementById('offline-fields').classList.toggle('hidden', online);
            // field required toggling
            document.querySelector('#tp-cust')?.toggleAttribute('required', online);
            ['nama_penerima', 'nomor_telepon', 'alamat'].forEach(n => {
                const el = document.querySelector(`[name="${n}"]`);
                if (el) el.toggleAttribute('required', !online);
            });
            // field non-aktif tidak ikut terkirim (disabled)
            document.querySelector('#tp-cust')?.toggleAttribute('disabled', !online);
            ['nama_penerima', 'nomor_telepon', 'alamat', 'email_pelanggan'].forEach(n => {
                const el = document.querySelector(`[name="${n}"]`);
                if (el) el.toggleAttribute('disabled', online);
            });
            recalculateTotal();
        }

        function toggleMetodeBayar() {
            const transfer = document.querySelector('#modal-tambah-pesanan input[name="metode_bayar"]:checked')?.value ===
                'transfer';
            document.getElementById('transfer-fields').classList.toggle('hidden', !transfer);
            const acc = document.querySelector('#tp-acc');
            const bukti = document.querySelector('#tp-bukti');
            if (acc) acc.toggleAttribute('required', transfer);
            if (bukti) bukti.toggleAttribute('required', transfer);
            if (acc) acc.toggleAttribute('disabled', !transfer);
            if (bukti) bukti.toggleAttribute('disabled', !transfer);
        }

        function addItemRow() {
            const container = document.getElementById('item-container');
            if (!container) return;
            const i = itemIdx++;
            const row = document.createElement('div');
            row.setAttribute('data-item-row', '');
            row.className = 'grid grid-cols-[1fr_110px_140px] gap-3 items-end';
            const dlId = 'variant-datalist-' + i;
            row.innerHTML = `
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Produk</label>
                <input type="text" list="${dlId}" autocomplete="off" placeholder="Ketik nama produk / pilih varian..." oninput="onVariantInput(this)" class="raliva-input w-full" />
                <input type="hidden" name="items[${i}][product_variant_id]" value="" />
                <datalist id="${dlId}">
                    ${itemVariants.map(v => {
                        const nama = variantLabel(v);
                        const detail = ` (stok ${v.stok}) — Rp ${v.harga.toLocaleString('id-ID')}`;
                        return `<option value="${nama}" data-id="${v.product_variant_id}" label="${nama}${detail}">${nama}${detail}</option>`;
                    }).join('')}
                </datalist>
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Qty</label>
                <input type="number" name="items[${i}][quantity]" min="1" max="100" value="1" oninput="onQtyChange(this)" class="raliva-input w-full py-2 text-center" />
            </div>
            <div class="flex items-end justify-between gap-2">
                <div class="text-right flex-1 min-w-0">
                    <span class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Subtotal</span>
                    <span class="item-subtotal text-sm font-bold text-gold-accent whitespace-nowrap">Rp 0</span>
                </div>
                <button type="button" onclick="removeItemRow(this)" class="shrink-0 mb-0.5 px-2 py-2 rounded-lg border border-error/20 text-error hover:bg-error/10 transition-colors" title="Hapus baris">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
        `;
            container.appendChild(row);
            recalculateTotal();
        }

        function removeItemRow(btn) {
            const row = btn.closest('[data-item-row]');
            if (row) row.remove();
            recalculateTotal();
        }

        function onVariantInput(textInput) {
            const row = textInput.closest('[data-item-row]');
            if (!row) return;
            const hiddenInput = row.querySelector('input[name$="[product_variant_id]"]');
            if (!hiddenInput) return;
            const v = itemVariants.find(x => x.product_variant_id == textInput.value || x.nama_produk == textInput.value ||
                variantLabel(x) == textInput.value);
            if (v) {
                hiddenInput.value = v.product_variant_id;
                textInput.value = variantLabel(v);
                recalculateRow(row);
                recalculateTotal();
            } else {
                hiddenInput.value = '';
                recalculateRow(row);
                recalculateTotal();
            }
        }

        function onQtyChange(input) {
            const row = input.closest('[data-item-row]');
            if (!row) return;
            recalculateRow(row);
            recalculateTotal();
        }

        function recalculateRow(row) {
            const hiddenInput = row.querySelector('input[name$="[product_variant_id]"]');
            const qtyInput = row.querySelector('input[name$="[quantity]"]');
            const subtotalEl = row.querySelector('.item-subtotal');
            if (!hiddenInput || !qtyInput || !subtotalEl) return;
            const v = itemVariants.find(x => x.product_variant_id == hiddenInput.value);
            const harga = v ? parseFloat(v.harga) : 0;
            const qty = Math.max(1, parseInt(qtyInput.value || 1, 10));
            const sub = harga * qty;
            subtotalEl.textContent = 'Rp ' + sub.toLocaleString('id-ID');
        }

        function recalculateTotal() {
            let total = 0;
            document.querySelectorAll('#item-container [data-item-row]').forEach(row => {
                const hiddenInput = row.querySelector('input[name$="[product_variant_id]"]');
                const qtyInput = row.querySelector('input[name$="[quantity]"]');
                const v = itemVariants.find(x => x.product_variant_id == hiddenInput?.value);
                const harga = v ? parseFloat(v.harga) : 0;
                const qty = Math.max(0, parseInt(qtyInput?.value || 0, 10));
                total += harga * qty;
                const subtotalEl = row.querySelector('.item-subtotal');
                if (subtotalEl) subtotalEl.textContent = 'Rp ' + (harga * qty).toLocaleString('id-ID');
            });
            const grand = document.getElementById('grand-total');
            if (grand) grand.textContent = total.toLocaleString('id-ID');
        }

        // Seed first item row when tambah modal opens
        document.querySelectorAll('[data-modal-open]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-modal-open');
                if (id !== 'modal-tambah-pesanan') return;
                const container = document.getElementById('item-container');
                if (container && container.querySelectorAll('[data-item-row]').length === 0) {
                    addItemRow();
                }
                toggleTipePesanan();
                toggleMetodeBayar();
            });
        });
    </script>
@endpush
