<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="color-scheme" content="light dark"/>
<title>RALIVA - {{ __('Order Tracking') }}</title>
<script>if (localStorage.getItem('raliva-theme') === 'dark') document.documentElement.classList.add('theme-dark');</script>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;family=Playfair+Display:wght@500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-fixed": "#1c1b1b",
                        "on-error-container": "#93000a",
                        "on-tertiary-container": "#848482",
                        "surface-bright": "#f2f0ee",
                        "on-primary-container": "#858383",
                        "primary-fixed-dim": "#c8c6c5",
                        "surface-variant": "#e3e2e2",
                        "on-surface": "#1b1c1c",
                        "secondary": "#8B1E3F",
                        "surface-dim": "#dbdad9",
                        "on-error": "#ffffff",
                        "primary": "#000000",
                        "on-secondary": "#ffffff",
                        "tertiary-container": "#1a1c1a",
                        "error-container": "#ffdad6",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed": "#8B1E3F",
                        "on-primary": "#ffffff",
                        "surface-container-lowest": "#F8F6F2",
                        "error": "#ba1a1a",
                        "surface-container-highest": "#e3e2e2",
                        "inverse-surface": "#303031",
                        "surface-container": "#e8e6e4",
                        "tertiary": "#000000",
                        "primary-fixed": "#e5e2e1",
                        "outline-variant": "#c8c6c4",
                        "surface-tint": "#5f5e5e",
                        "secondary-fixed-dim": "#8B1E3F",
                        "outline": "#797775",
                        "on-primary-fixed-variant": "#474646",
                        "on-secondary-fixed-variant": "#6D1428",
                        "on-tertiary-fixed": "#1a1c1a",
                        "on-secondary-container": "#6D1428",
                        "inverse-on-surface": "#f2f0f0",
                        "tertiary-fixed-dim": "#c7c6c4",
                        "tertiary-fixed": "#e3e2df",
                        "surface-container-high": "#e9e8e7",
                        "on-secondary-fixed": "#6D1428",
                        "background": "#F3F0EA",
                        "surface": "#F3F0EA",
                        "secondary-container": "#8B1E3F",
                        "on-surface-variant": "#444748",
                        "primary-container": "#1c1b1b",
                        "inverse-primary": "#c8c6c5",
                        "surface-container-low": "#ecebe9",
                        "on-tertiary-fixed-variant": "#464745",
                        "on-background": "#1b1c1c"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "gutter": "12px",
                        "base": "4px",
                        "xl": "48px",
                        "lg": "32px",
                        "container-margin": "20px",
                        "sm": "16px",
                        "md": "24px",
                        "xs": "8px"
                    },
                    "fontFamily": {
                        "display-lg": ["Playfair Display"],
                        "label-caps": ["Manrope"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "headline-lg": ["Playfair Display"],
                        "title-md": ["Manrope"],
                        "headline-md": ["Playfair Display"],
                        "body-lg": ["Manrope"],
                        "body-sm": ["Manrope"],
                        "label-sm": ["Manrope"]
                    },
                    "fontSize": {
                        "display-lg": ["40px", { "lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "600" }],
                        "label-caps": ["12px", { "lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "700" }],
                        "headline-lg-mobile": ["28px", { "lineHeight": "36px", "fontWeight": "500" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "500" }],
                        "title-md": ["18px", { "lineHeight": "24px", "letterSpacing": "0.01em", "fontWeight": "600" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "500" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "fontWeight": "500" }]
                    }
                }
            }
        }
    </script>
  <style>
        .timeline-line {
            position: absolute;
            top: 12px; /* Half of circle height (24px) */
            left: 16px;
            right: 16px;
            height: 2px;
            background-color: var(--chrome-border);
            border-radius: 9999px;
            z-index: 0;
        }
        .timeline-progress {
            position: absolute;
            top: 12px;
            left: 16px;
            height: 2px;
            background-color: var(--chrome-accent);
            border-radius: 9999px;
            z-index: 1;
            width: 0%; /* Dynamic based on active step */
            transition: width .6s cubic-bezier(.22,1,.36,1);
        }
        .timeline-active-circle {
            border-color: var(--chrome-accent) !important;
            box-shadow: 0 0 0 4px rgba(139,30,30,.10);
        }
        html.theme-dark .timeline-active-circle { box-shadow: 0 0 0 4px rgba(109,20,40,.16); }
        .timeline-active-dot {
            background-color: var(--chrome-accent) !important;
        }
        .timeline-active-label {
            color: var(--chrome-accent) !important;
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
 <style>
        :root {
        --chrome-bg: #ffffff;
        --chrome-bg-soft: rgba(255,255,255,.96);
        --chrome-text: #111111;
        --chrome-text-dim: rgba(17,17,17,.55);
        --chrome-text-faint: rgba(17,17,17,.45);
        --chrome-border: #E5E1DA;
        --chrome-hover: rgba(17,17,17,.05);
        --chrome-accent: #8B1E3F;
        --surface-ivory: #F8F6F2;
        --surface-warm: #F3F0EA;
        --border-soft: #E5E1DA;
        --text-muted: #777777;
    }
    html.theme-dark {
        --chrome-bg: #1c1b1b;
        --chrome-bg-soft: rgba(28,27,27,.9);
        --chrome-text: #ffffff;
        --chrome-text-dim: rgba(255,255,255,.6);
        --chrome-text-faint: rgba(255,255,255,.5);
        --chrome-border: rgba(255,255,255,.1);
        --chrome-hover: rgba(255,255,255,.1);
        --chrome-accent: #8B1E3F;
        --surface-ivory: #1e1d1c;
        --surface-warm: #201f1e;
        --border-soft: rgba(255,255,255,.1);
        --text-muted: #b9b6b1;
    }
</style>
<style>
    /* ============ FULL DARK MODE TOKEN REMAP ============ */
    html.theme-dark .bg-background, html.theme-dark .bg-surface, html.theme-dark .bg-surface-bright { background-color: #161514 !important; }
    html.theme-dark .bg-surface-container-lowest { background-color: #1e1d1c !important; }
    html.theme-dark .bg-surface-container-low { background-color: #201f1e !important; }
    html.theme-dark .bg-surface-container { background-color: #262524 !important; }
    html.theme-dark .bg-surface-container-high { background-color: #2c2b2a !important; }
    html.theme-dark .bg-surface-container-highest, html.theme-dark .bg-surface-variant { background-color: #323130 !important; }
    html.theme-dark .bg-surface\/50 { background-color: rgba(38,37,36,.5) !important; }
    html.theme-dark .bg-surface\/95 { background-color: rgba(22,21,20,.95) !important; }
    html.theme-dark .bg-background\/90 { background-color: rgba(22,21,20,.9) !important; }
    html.theme-dark .bg-surface-container-lowest\/50 { background-color: rgba(30,29,28,.5) !important; }
    html.theme-dark .from-surface\/80 { --tw-gradient-from: rgba(22,21,20,.85) !important; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-via, transparent), var(--tw-gradient-to, transparent) !important; }
    html.theme-dark .bg-primary { background-color: #f2efec !important; }
    html.theme-dark .bg-primary\/5 { background-color: rgba(242,239,236,.08) !important; }
    html.theme-dark .text-primary { color: #f2efec !important; }
    html.theme-dark .text-on-primary { color: #1b1a19 !important; }
    html.theme-dark .border-primary { border-color: #f2efec !important; }
    html.theme-dark .text-on-surface, html.theme-dark .text-on-background { color: #e6e4e1 !important; }
    html.theme-dark .text-on-surface-variant { color: #b9b6b1 !important; }
    html.theme-dark .text-on-surface-variant\/70 { color: rgba(185,182,177,.7) !important; }
    html.theme-dark .text-outline { color: #8a8781 !important; }
    html.theme-dark .text-outline-variant { color: #6f6d68 !important; }
    html.theme-dark .text-error { color: #ffb4ab !important; }
    html.theme-dark .text-secondary { color: #8B1E3F !important; }
    html.theme-dark .placeholder-on-surface-variant::placeholder { color: #b9b6b1 !important; }
    html.theme-dark .border-outline-variant { border-color: #3a3937 !important; }
    html.theme-dark .border-outline { border-color: #4a4844 !important; }
    html.theme-dark .border-surface-variant { border-color: #2c2b2a !important; }
    html.theme-dark .border-on-surface { border-color: #e6e4e1 !important; }
    html.theme-dark .border-error { border-color: #ffb4ab !important; }
    html.theme-dark .bg-outline-variant { background-color: #3a3937 !important; }
    html.theme-dark .bg-on-surface { background-color: #e6e4e1 !important; }
    html.theme-dark .hover\:bg-surface-container-low:hover { background-color: #201f1e !important; }
    html.theme-dark .hover\:bg-surface-container-high:hover { background-color: #2c2b2a !important; }
    html.theme-dark .hover\:bg-surface-variant:hover { background-color: #323130 !important; }
    html.theme-dark .hover\:bg-surface:hover { background-color: #262524 !important; }
    html.theme-dark .hover\:bg-primary:hover { background-color: #ffffff !important; }
    html.theme-dark .hover\:text-secondary:hover { color: #8B1E3F !important; }
    html.theme-dark .hover\:text-primary:hover { color: #f2efec !important; }
    html.theme-dark .hover\:text-on-surface:hover { color: #e6e4e1 !important; }
    html.theme-dark .hover\:text-error:hover { color: #ffb4ab !important; }
    html.theme-dark .hover\:border-primary:hover { border-color: #f2efec !important; }
    html.theme-dark .hover\:border-on-surface:hover { border-color: #e6e4e1 !important; }
    html.theme-dark .hover\:border-outline:hover { border-color: #4a4844 !important; }
    html.theme-dark .focus\:border-primary:focus { border-color: #f2efec !important; }
    html.theme-dark .focus\:border-on-surface:focus { border-color: #e6e4e1 !important; }
    html.theme-dark .focus\:border-outline:focus { border-color: #4a4844 !important; }
    html.theme-dark .group:hover .group-hover\:text-primary { color: #f2efec !important; }
    html.theme-dark .group:hover .group-hover\:border-outline { border-color: #4a4844 !important; }
    html.theme-dark .peer:checked ~ .peer-checked\:bg-primary { background-color: #f2efec !important; }
</style>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined[data-weight="fill"] {
            font-variation-settings: 'FILL' 1;
        }
        .no-scrollbar::-webkit-scrollbar, .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar, .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
</style>
<style>
    /* ============  BUTTON + LIGHT FLASH (burgundy - parity home/shop) ============ */
    .btn-gold {
        position: relative;
        overflow: hidden;
        background-color: var(--btn-gold-bg) !important;
        color: var(--btn-gold-text) !important;
    }
    .btn-gold::after {
        content: '';
        position: absolute;
        top: -10%;
        bottom: -10%;
        left: -80%;
        width: 45%;
        background: rgba(255,255,255,.55);
        transform: skewX(-24deg);
        pointer-events: none;
    }
    .btn-gold:hover::after { animation: authFlash 1.4s linear infinite; }
    .btn-gold.flashing::after { animation: authFlash 1.4s cubic-bezier(.4,0,.2,1) 1; }
    @keyframes authFlash { from { left: -80%; } to { left: 135%; } }
    :root           { --btn-gold-bg: #8B1E3F; --btn-gold-text: #ffffff; }
    html.theme-dark { --btn-gold-bg: #6D1428; --btn-gold-text: #ffffff; }
</style>
<style>
    /* ============ Order-Tracking: remap drawer + bottom-nav accent to burgundy (parity home/shop) ============ */
    #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(139,30,30,.10); }
    html.theme-dark #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(163,38,38,.16); }
    .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    html.theme-dark .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    .bn-active { color: #8B1E3F !important; }
    html.theme-dark .bn-active { color: #8B1E3F !important; }

    /* ============ ATELIER EYEBROW (parity home) ============ */
    .atl-eyebrow { display: inline-flex; align-items: center; gap: .65rem; }
    .atl-eyebrow::before {
        content: '';
        width: 30px;
        height: 1px;
        background: var(--chrome-accent);
        opacity: .7;
    }

    /* ============ SUBTLE SCROLL REVEAL (parity home) ============ */
    @keyframes sectionRise {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: none; }
    }
    .reveal-up { opacity: 0; }
    .reveal-up.in { animation: sectionRise .7s cubic-bezier(.22, 1, .36, 1) forwards; }
    @media (prefers-reduced-motion: reduce) {
        .reveal-up { animation: none !important; opacity: 1 !important; transform: none !important; }
    }
    /* safe area for bottom nav */
    .pb-safe { padding-bottom: env(safe-area-inset-bottom); }

    /* ============ PREMIUM CARD + HEADING (tiruan Super-Admin, aksen Burgundy) ============ */
    .card-premium {
        background-color: var(--surface-ivory);
        border: 1px solid var(--border-soft);
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgba(17,17,17,.04), 0 8px 24px -12px rgba(17,17,17,.12);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .card-premium:hover {  box-shadow: 0 4px 12px rgba(17,17,17,.06), 0 18px 40px -16px rgba(17,17,17,.18); border-color: rgba(139,30,63,.45); }
    html.theme-dark .card-premium { background-color: var(--surface-ivory); border-color: var(--border-soft); box-shadow: 0 1px 2px rgba(0,0,0,.3), 0 8px 24px -12px rgba(0,0,0,.5); }
    html.theme-dark .card-premium:hover { border-color: rgba(139,30,63,.5); box-shadow: 0 4px 12px rgba(0,0,0,.35), 0 20px 44px -16px rgba(0,0,0,.6); }
    .premium-heading { position: relative; padding-left: 0.9rem; }
    .premium-heading::before { content: ''; position: absolute; left: 0; top: 0.1em; bottom: 0.1em; width: 4px; border-radius: 9999px; background: var(--chrome-accent); }

    /* ============ ORDER ITEMS: nested Product List (internal scroll only) ============ */
    .ot-product-list {
        background-color: #ffffff;                 /* White — slightly different from card (Soft Ivory) */
        border: 1px solid var(--border-soft);
        border-radius: 0.75rem;
        padding: 0.5rem 0.75rem;
        max-height: 60vh;                          /* responsive; 1-2 items show normally, more scrolls */
        overflow-y: auto;
        scrollbar-width: thin;                     /* subtle scrollbar (Firefox) */
        scrollbar-color: var(--border-soft) transparent;
    }
    .ot-product-list::-webkit-scrollbar { width: 6px; }   /* subtle scrollbar (WebKit) */
    .ot-product-list::-webkit-scrollbar-track { background: transparent; }
    .ot-product-list::-webkit-scrollbar-thumb { background: var(--border-soft); border-radius: 9999px; }
    html.theme-dark .ot-product-list { background-color: #262524; border-color: var(--border-soft); }  /* slightly lighter than main dark surface */
    @media (max-width: 768px) { .ot-product-list { max-height: 55vh; padding: 0.5rem; } }
    .countdown-badge { font-variant-numeric: tabular-nums; }
</style>
  </head>
<body class="bg-surface text-on-surface antialiased font-body-lg flex flex-col min-h-screen pb-[72px] md:pb-0 lg:pl-72 overflow-x-hidden">
<!-- TopAppBar (parity home/shop - fixed with burgundy) -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)]">
<button aria-label="{{ __('Menu') }}" class="hover:opacity-80 transition-opacity lg:hidden flex items-center justify-center" onclick="openDrawer()" type="button">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)]">RALIVA</h1>
<div class="w-10"></div>
</header>
<!-- Main Content Canvas -->
<main class="flex-grow pt-16 pb-8 lg:pb-12 w-full overflow-x-clip">
@if (! $selected)
<section class="py-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium text-center">
<div class="atl-eyebrow mb-sm justify-center">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Order Tracking') }}</span>
</div>
<span class="material-symbols-outlined text-[40px] text-outline-variant block mx-auto mb-sm">local_shipping</span>
<h2 class="premium-heading font-headline-md text-headline-md text-on-surface mb-xs">{{ __('Belum ada pesanan') }}</h2>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-md max-w-md mx-auto">{{ __('Anda belum memiliki pesanan. Mulai belanja dan pesanan Anda akan muncul di sini.') }}</p>
<a href="{{ route('customer.shop') }}" class="btn-gold inline-flex items-center justify-center gap-2 font-label-caps text-label-caps px-lg py-sm rounded-full uppercase tracking-widest">
<span class="material-symbols-outlined text-[18px]">shopping_bag</span>{{ __('Mulai Belanja') }}</a>
</div>
</div>
</section>
@else
@php
    $step = $selectedStep;
    $statusLabel = \App\Http\Controllers\Customer\OrderTrackingController::STATUS_LABELS[$selected->status] ?? ucfirst(str_replace('_', ' ', $selected->status));
    $isCancelled = $step === null;
    $isRefund = $selected->status === \App\Models\Order::STATUS_REFUND;
    $shipment = $selected->shipments->first();
    $estDeliv = $shipment?->estimasi_tiba;
    $itemsCount = $selected->items->count();
    $progressWidth = 0;
    $tlList = $timeline ?? [];
    $doneCount = collect($tlList)->where('done', true)->count();
    // Langkah aktif = langkah belum-done pertama (khusus aksi role, bukan status mentah).
    $activeIndex = ($step > 0 && $doneCount < count($tlList)) ? $doneCount : null;
    $progressWidth = $isCancelled ? 0 : ($doneCount / max(1, count($tlList)) * 100);
    $latestRefund = optional($selected->refunds)->sortByDesc('diajukan_pada')->first();
    $refundStatus = $latestRefund?->status;
    $refundPernahAda = $selected->refunds->isNotEmpty();
    $refundAktif = $selected->refunds->contains(fn ($r) => in_array($r->status, [\App\Models\Refund::STATUS_REQUESTED, \App\Models\Refund::STATUS_ESKALASI, \App\Models\Refund::STATUS_DISETUJUI], true));
    $refundMeta = match ($refundStatus) {
        \App\Models\Refund::STATUS_SELESAI => __('Pengembalian dana selesai'),
        \App\Models\Refund::STATUS_DITOLAK => __('Refund ditolak'),
        default => $refundStatus ? __('Pengajuan refund sedang diproses') : null,
    };
    $details = [
        'pending_payment' => match ($selected->checkout?->payment?->status) {
            \App\Models\Payment::STATUS_PENDING => [__('Menunggu pembayaran'), __('Selesaikan pembayaran sebelum batas waktu. Klik Lanjutkan Pembayaran untuk memilih metode dan mengunggah bukti.')],
            \App\Models\Payment::STATUS_DITOLAK => [__('Bukti ditolak'), __('Bukti pembayaran Anda ditolak. Klik Unggah Ulang Bukti untuk mengunggah bukti yang benar.')],
            default => [__('Menunggu verifikasi'), __('Bukti pembayaran Anda sedang diverifikasi admin. Pesanan akan diproses setelah terverifikasi.')],
        },
        'dibayar' => [__('Pembayaran diterima'), __('Pembayaran Anda telah kami terima. Pesanan menunggu disiapkan oleh tim produksi.')],
        'menunggu_produksi' => [__('Sedang disiapkan'), __('Pesanan menunggu diproses oleh tim produksi.')],
        'diproses' => [__('Sedang disiapkan'), __('Pesanan sedang disiapkan oleh tim produksi.')],
        'menunggu_qc' => [__('Pemeriksaan kualitas'), __('Pesanan sedang dalam pemeriksaan kualitas oleh tim produksi.')],
        'siap_kirim' => ! empty($hasResi ?? false)
            ? [__('Resi diterbitkan'), __('Nomor resi sudah diterbitkan admin. Menunggu kurir mengambil paket Anda.')]
            : [__('Sudah dikemas'), __('Pesanan sudah dikemas dan siap dikirim.')],
        'dikirim' => [__('Sedang dalam perjalanan'), __('Pesanan sudah dikirim dan sedang dalam perjalanan menuju alamat Anda. Klik Konfirmasi Pesanan Diterima setelah paket sampai.')],
        'selesai' => [__('Pesanan diterima'), __('Pesanan telah sampai dan dikonfirmasi. Terima kasih sudah berbelanja di RALIVA.')],
        'dibatalkan' => [__('Pesanan dibatalkan'), __('Pesanan ini telah dibatalkan. Hubungi layanan pelanggan jika ada pertanyaan.')],
        'refund' => match ($refundStatus) {
            \App\Models\Refund::STATUS_SELESAI => [__('Refund selesai'), __('Pengembalian dana untuk pesanan ini telah diselesaikan oleh toko.')],
            \App\Models\Refund::STATUS_DITOLAK => [__('Refund ditolak'), ($latestRefund?->alasan_penolakan ?: __('Pengajuan refund Anda ditolak oleh toko.'))],
            default => [__('Refund sedang diproses'), __('Pengembalian dana untuk pesanan ini sedang diproses.')],
        },
    ];
    $detail = $details[$selected->status] ?? [__('Pesanan diterima'), __('Pesanan Anda telah tercatat.')];
@endphp
@if ($orders->count() > 1)
<div class="pt-lg sticky top-16 z-30 bg-surface">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="ot-product-list px-sm py-xs flex gap-sm overflow-x-auto hide-scrollbar" style="max-height:none">
@foreach ($orders as $o)
<a href="{{ route('customer.order-tracking', ['order' => $o->order_id]) }}" class="shrink-0 inline-flex items-center gap-2 px-4 py-2 rounded-full border transition-all duration-200 {{ $o->order_id === $selected->order_id ? 'bg-secondary text-white border-secondary' : 'border-outline-variant text-on-surface-variant hover:border-secondary hover:text-secondary' }}">
<span class="font-label-sm text-label-sm font-semibold">#{{ $o->nomor_order }}</span>
</a>
@endforeach
</div>
</div>
</div>
@endif
<!-- Order Header (Super-Admin style premium card, aksen Burgundy) -->
<section class="py-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
<div class="atl-eyebrow mb-sm">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Order Tracking') }}</span>
</div>
<div class="flex flex-wrap justify-between items-end gap-sm">
<div>
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">{{ __('Order ID') }}</p>
<p class="font-title-md text-title-md md:text-headline-md font-semibold text-on-surface tracking-tight break-all">#{{ $selected->nomor_order }}</p>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $selected->created_at->format('M j, Y') }} • {{ $itemsCount }} {{ __('items') }}</p>
</div>
<div class="text-left md:text-right">
<div class="inline-flex items-center gap-xs px-sm py-1 rounded-full border {{ $isCancelled ? 'bg-error/10 border-error/15' : 'bg-secondary/10 border-secondary/15' }}">
<span class="w-2 h-2 rounded-full {{ $isCancelled ? 'bg-error' : 'bg-secondary' }} animate-pulse"></span>
<span class="font-label-sm text-label-sm {{ $isCancelled ? 'text-error' : 'text-secondary' }} uppercase tracking-wider font-semibold">{{ $statusLabel }}</span>
</div>
@if ($selected->status === \App\Models\Order::STATUS_REFUND && $refundMeta)
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 md:text-right">{{ $refundMeta }}</p>
@else
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 md:text-right">{{ $estDeliv ? __('Est. delivery:').' '.$estDeliv->format('M j, Y') : __('Menunggu konfirmasi pengiriman') }}</p>
@endif
@if($shipment && $shipment->nomor_resi)
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 md:text-right break-all">{{ __('Resi') }}: <strong class="text-on-surface">{{ $shipment->nomor_resi }}</strong> @if($shipment->courier) • {{ $shipment->courier->nama_kurir }}@endif @if($shipment->shippingService) • {{ $shipment->shippingService->nama_layanan }}@endif</p>
@elseif($shipment)
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 md:text-right break-all">{{ __('Ekspedisi') }}: {{ $shipment->courier?->nama_kurir ?? '-' }} @if($shipment->shippingService) • {{ $shipment->shippingService->nama_layanan }}@endif • {{ __('Status pengiriman') }}: {{ $shipment->status }}</p>
@endif
</div>
</div>
</div>
</div>
</section>
@php
    $payInfo = $selected->checkout?->payment;
    $payStatusLabel = $payInfo ? match ($payInfo->status) {
        \App\Models\Payment::STATUS_PENDING => __('Menunggu Pembayaran'),
        \App\Models\Payment::STATUS_MENUNGGU_VERIFIKASI => __('Menunggu Verifikasi'),
        \App\Models\Payment::STATUS_TERVERIFIKASI => __('Pembayaran Diterima'),
        \App\Models\Payment::STATUS_DITOLAK => __('Bukti Ditolak'),
        \App\Models\Payment::STATUS_KADALUARSA => __('Kedaluwarsa'),
        default => ucfirst(str_replace('_', ' ', $payInfo->status)),
    } : null;
@endphp
@if ($payInfo)
<section class="pb-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
    <div class="flex items-center justify-between gap-3 mb-md">
        <p class="font-label-caps text-label-caps uppercase tracking-widest text-secondary flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">payments</span>
            {{ __('Rincian Pembayaran') }}
        </p>
        <span class="inline-flex items-center gap-xs px-sm py-1 rounded-full border {{ $payInfo->status === App\Models\Payment::STATUS_TERVERIFIKASI ? 'bg-secondary/10 border-secondary/15 text-secondary' : ($payInfo->status === App\Models\Payment::STATUS_KADALUARSA || $payInfo->status === App\Models\Payment::STATUS_DITOLAK ? 'bg-error/10 border-error/15 text-error' : 'bg-surface-container-high border-outline-variant text-on-surface-variant') }}">
            <span class="font-label-sm text-label-sm uppercase tracking-wider font-semibold">{{ $payStatusLabel }}</span>
        </span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">
        <div class="border border-outline-variant rounded-xl p-md">
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">{{ __('Metode') }}</p>
            <p class="font-body-md text-body-md text-on-surface font-semibold">{{ $payInfo->paymentMethod?->nama_metode ?? __('Belum dipilih') }}</p>
            @if ($payInfo->account)
                @php
                    $otBrandIcons = [
                        'dana' => 'images/E-Wallet/dana.png',
                        'gopay' => 'images/E-Wallet/gopay.jpg',
                        'ovo' => 'images/E-Wallet/ovo.png',
                        'shopeepay' => 'images/E-Wallet/shoopepay.jfif',
                        'bca' => 'images/Bank/bca.png',
                        'bri' => 'images/Bank/bri.png',
                        'bni' => 'images/Bank/bni.png',
                        'mandiri' => 'images/Bank/mandiri.png',
                    ];
                    $otFgPath = $payInfo->account->file_gambar ? ltrim($payInfo->account->file_gambar, '/') : null;
                    $otAccountImg = ($otFgPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($otFgPath))
                        ? asset('storage/' . $otFgPath)
                        : (isset($otBrandIcons[$payInfo->account->kode ?? '']) ? asset($otBrandIcons[$payInfo->account->kode]) : null);
                @endphp
                <div class="flex items-center gap-sm mt-sm">
                    @if ($otAccountImg)
                        <img src="{{ $otAccountImg }}" alt="{{ $payInfo->account->nama }}" class="h-8 object-contain rounded border border-outline-variant bg-white" />
                    @endif
                    <div class="min-w-0">
                        <p class="font-body-sm text-body-sm text-on-surface">{{ $payInfo->account->nama }}</p>
                        @if ($payInfo->account->nomor_rekening)
                            <p class="font-label-sm text-label-sm text-on-surface-variant truncate">{{ $payInfo->account->nomor_rekening }} @if($payInfo->account->nama_pemilik) &#8226; {{ $payInfo->account->nama_pemilik }}@endif</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="border border-outline-variant rounded-xl p-md">
            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">{{ __('Total') }}</p>
            <p class="font-title-md text-title-md text-on-surface font-semibold">Rp {{ number_format((float) $payInfo->jumlah, 0, ',', '.') }}</p>
            @if ($payInfo->status === \App\Models\Payment::STATUS_PENDING && $payInfo->batas_waktu)
                <p class="font-label-sm text-label-sm text-on-surface-variant mt-sm">Batas pembayaran: {{ $payInfo->batas_waktu->translatedFormat('d M Y, H:i') }}</p>
            @endif
        </div>
    </div>
    @php
        $canResumePay = $selected->checkout
            && $selected->checkout->status === \App\Models\Checkout::STATUS_PENDING
            && in_array($payInfo->status, [\App\Models\Payment::STATUS_PENDING, \App\Models\Payment::STATUS_DITOLAK], true);
    @endphp
    @if ($canResumePay)
        <a href="{{ route('customer.checkout.payment', $selected->checkout->checkout_id) }}" class="btn-gold mt-md w-full inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
            <span class="material-symbols-outlined text-[20px]">{{ $payInfo->status === \App\Models\Payment::STATUS_DITOLAK ? 'upload_file' : 'payments' }}</span>
            <span>{{ $payInfo->status === \App\Models\Payment::STATUS_DITOLAK ? __('Unggah Ulang Bukti') : __('Lanjutkan Pembayaran') }}</span>
        </a>
    @endif
</div>
</div>
</section>
@endif
<!-- Visual Tracking Timeline -->
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
@if ($isRefund)
<div class="text-center py-1">
<span class="material-symbols-outlined text-[40px] text-secondary mb-xs block">assignment_return</span>
<h3 class="font-title-md text-title-md text-on-surface mb-xs">{{ $detail[0] }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant max-w-md mx-auto">{{ $detail[1] }}</p>
@if ($latestRefund)
<p class="font-body-sm text-body-sm text-on-surface-variant max-w-md mx-auto mt-2">{{ $latestRefund->kode }} • Rp {{ number_format((float) $latestRefund->jumlah, 0, ',', '.') }} @if($latestRefund->selesai_pada) • {{ $latestRefund->selesai_pada->translatedFormat('d M Y, H:i') }}@endif</p>
@if ($latestRefund->file_bukti_request || ($latestRefund->status === \App\Models\Refund::STATUS_SELESAI && $latestRefund->file_bukti))
@php
$buktiReqUrl = $latestRefund->file_bukti_request ? asset('storage/' . ltrim($latestRefund->file_bukti_request, '/')) : null;
$buktiReqExt = $latestRefund->file_bukti_request ? strtolower(pathinfo($latestRefund->file_bukti_request, PATHINFO_EXTENSION)) : '';
$buktiReqNama = $latestRefund->file_bukti_request ? \Illuminate\Support\Str::afterLast($latestRefund->file_bukti_request, '/') : '';
$buktiTokoUrl = $latestRefund->file_bukti ? asset('storage/' . ltrim($latestRefund->file_bukti, '/')) : null;
$buktiTokoExt = $latestRefund->file_bukti ? strtolower(pathinfo($latestRefund->file_bukti, PATHINFO_EXTENSION)) : '';
$buktiTokoNama = $latestRefund->file_bukti ? \Illuminate\Support\Str::afterLast($latestRefund->file_bukti, '/') : '';
@endphp
<div class="max-w-2xl mx-auto mt-4 flex flex-wrap justify-center items-start gap-3">
@if ($buktiReqUrl)
<div class="w-full sm:max-w-xs rounded-xl border border-outline-variant bg-surface-container-low p-2">
<p class="font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant mb-2 flex items-center justify-center gap-1"><span class="material-symbols-outlined text-[16px]">inventory_2</span>{{ __('Foto bukti dari customer') }}</p>
@if (in_array($buktiReqExt, ['jpg', 'jpeg', 'png'], true))
<a href="{{ $buktiReqUrl }}" target="_blank" rel="noopener" class="block hover:opacity-90 transition-opacity">
<img src="{{ $buktiReqUrl }}" alt="{{ $buktiReqNama }}" class="w-full max-h-48 h-auto object-contain rounded-lg" loading="lazy" />
<p class="font-body-sm text-body-sm text-on-surface-variant mt-2 flex items-center justify-center gap-1"><span class="material-symbols-outlined text-[16px]">visibility</span>{{ __('Perbesar foto') }}</p>
</a>
@else
<a href="{{ $buktiReqUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-3 py-3">
<span class="material-symbols-outlined text-[18px]">description</span>
<span class="font-body-sm text-body-sm text-on-surface truncate">{{ $buktiReqNama }}</span>
<span class="material-symbols-outlined text-[16px]">open_in_new</span>
</a>
@endif
@if ($latestRefund->deskripsi_bukti_request)
<p class="font-body-sm text-body-sm text-on-surface-variant mt-2">{{ $latestRefund->deskripsi_bukti_request }}</p>
@endif
</div>
@endif
@if ($buktiTokoUrl && $latestRefund->status === \App\Models\Refund::STATUS_SELESAI)
<div class="w-full sm:max-w-xs rounded-xl border border-gold-accent/30 bg-gold-accent/5 p-2">
<p class="font-label-caps text-label-caps uppercase tracking-wider text-secondary mb-2 flex items-center justify-center gap-1"><span class="material-symbols-outlined text-[16px]">verified</span>{{ __('Bukti transfer penyelesaian') }}</p>
@if (in_array($buktiTokoExt, ['jpg', 'jpeg', 'png'], true))
<a href="{{ $buktiTokoUrl }}" target="_blank" rel="noopener" class="block hover:opacity-90 transition-opacity">
<img src="{{ $buktiTokoUrl }}" alt="{{ $buktiTokoNama }}" class="w-full max-h-48 h-auto object-contain rounded-lg" loading="lazy" />
<p class="font-body-sm text-body-sm text-on-surface-variant mt-2 flex items-center justify-center gap-1"><span class="material-symbols-outlined text-[16px]">visibility</span>{{ __('Perbesar foto') }}</p>
</a>
@else
<a href="{{ $buktiTokoUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-3 py-3">
<span class="material-symbols-outlined text-[18px]">description</span>
<span class="font-body-sm text-body-sm text-on-surface truncate">{{ $buktiTokoNama }}</span>
<span class="material-symbols-outlined text-[16px]">open_in_new</span>
</a>
@endif
@if ($latestRefund->deskripsi_bukti)
<p class="font-body-sm text-body-sm text-on-surface-variant mt-2">{{ $latestRefund->deskripsi_bukti }}</p>
@endif
</div>
@endif
</div>
@endif
@endif
</div>
@elseif ($isCancelled)
<div class="text-center py-1">
<span class="material-symbols-outlined text-[40px] text-error mb-xs block">cancel</span>
<h3 class="font-title-md text-title-md text-on-surface mb-xs">{{ $detail[0] }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant max-w-md mx-auto">{{ $detail[1] }}</p>
@if (! empty($alasanPembatalan ?? null))
<div class="mt-md max-w-md mx-auto text-left bg-error/5 border border-error/15 rounded-xl p-md">
<p class="font-label-sm text-label-sm text-error uppercase tracking-wider font-semibold mb-1">{{ __('Alasan pembatalan') }}</p>
<p class="font-body-sm text-body-sm text-on-surface">{{ $alasanPembatalan }}</p>
</div>
@endif
</div>
@else
<div class="relative max-w-[480px] mx-auto">
<div class="timeline-line"></div>
<div class="timeline-progress" style="width: calc({{ $progressWidth }}% - {{ $progressWidth * 0.32 }}px);"></div>
<div class="flex justify-between gap-1 sm:gap-2 relative z-10">
@foreach (($timeline ?? [1 => [__('Disiapkan'), 'Produksi'], 2 => [__('Dikemas'), 'Produksi'], 3 => [__('Dikirim'), 'Admin'], 4 => [__('Diterima'), 'Customer']]) as $idx => $tl)
@php
if (is_array($tl) && array_key_exists('done', $tl)) {
    $stepLabel = $tl['label'];
    $stepRole = $tl['role'];
    $passed = ! $isCancelled && ! empty($tl['done']);
    $active = ! $isCancelled && $idx === ($activeIndex ?? -1);
} else {
    $stepIndex = is_int($idx) ? $idx : 0;
    $stepLabel = is_array($tl) ? $tl[0] : $tl;
    $stepRole = is_array($tl) ? ($tl[1] ?? '') : '';
    $passed = ! $isCancelled && $step && ($stepIndex < $step || ($stepIndex === $step && $step === 3));
    $active = ! $isCancelled && $step && (($stepIndex === $step && $step !== 3) || ($stepIndex === 4 && $step === 3));
}
@endphp
<div class="flex flex-col items-center gap-1 group cursor-pointer flex-1">
@if ($passed)
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full flex items-center justify-center bg-secondary border border-secondary shrink-0">
<span class="material-symbols-outlined text-[14px] text-white">check</span>
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface-variant text-center leading-tight">{{ $stepLabel }}</span>
<span class="text-[9px] md:text-[10px] uppercase tracking-wider text-on-surface-variant/70 text-center leading-tight">{{ $stepRole }}</span>
@elseif ($active)
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full flex items-center justify-center bg-surface transition-colors timeline-active-circle shrink-0" style="border: 2px solid var(--chrome-accent);">
<div class="w-2.5 h-2.5 md:w-2 md:h-2 rounded-full timeline-active-dot" style="background-color: var(--chrome-accent);"></div>
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface text-center leading-tight timeline-active-label" style="color: var(--chrome-accent);">{{ $stepLabel }}</span>
<span class="text-[9px] md:text-[10px] uppercase tracking-wider text-center leading-tight timeline-active-label" style="color: var(--chrome-accent);">{{ $stepRole }}</span>
@else
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full bg-surface border border-outline-variant flex items-center justify-center shrink-0 transition-colors group-hover:border-outline">
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface-variant text-center leading-tight">{{ $stepLabel }}</span>
<span class="text-[9px] md:text-[10px] uppercase tracking-wider text-on-surface-variant/70 text-center leading-tight">{{ $stepRole }}</span>
@endif
</div>
@endforeach
</div>
</div>
<!-- Current Status Detail -->
<div class="mt-lg text-center bg-surface-container-low p-md border border-outline-variant rounded-xl shadow-sm shadow-[0_2px_10px_rgba(0,0,0,.05)]">
<h3 class="font-title-md text-title-md text-on-surface mb-xs">{{ $detail[0] }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ $detail[1] }}</p>
</div>
@if (in_array($selected->status, [\App\Models\Order::STATUS_DIKIRIM, \App\Models\Order::STATUS_SELESAI]))
<div class="mt-lg flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-sm">
@if ($selected->status === \App\Models\Order::STATUS_DIKIRIM)
<form method="POST" action="{{ route('customer.order-tracking.confirm', $selected->order_id) }}" class="flex-1 sm:flex-none">
@csrf
<button type="submit" class="btn-gold w-full sm:w-auto flex items-center justify-center gap-2 font-label-caps text-label-caps px-lg py-3 rounded-full uppercase tracking-widest">
<span class="material-symbols-outlined text-[18px]">local_shipping</span>{{ __('Konfirmasi Pesanan Diterima') }}
</button>
</form>
@endif
@if ($selected->status === \App\Models\Order::STATUS_SELESAI)
@if (! empty($existingComplaint ?? null))
<a href="{{ route('customer.komplain', ['order' => $selected->order_id]) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 font-label-caps text-label-caps px-lg py-3 rounded-full uppercase tracking-widest border border-secondary/40 text-secondary hover:bg-secondary/5 transition-colors">
<span class="material-symbols-outlined text-[18px]">forum</span>{{ __('Lihat Komplain') }}
</a>
@else
<a href="{{ route('customer.komplain.create', ['order' => $selected->order_id]) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 font-label-caps text-label-caps px-lg py-3 rounded-full uppercase tracking-widest border border-outline-variant text-on-surface-variant hover:border-secondary hover:text-secondary transition-colors">
<span class="material-symbols-outlined text-[18px]">report</span>{{ __('Ajukan Komplain') }}
</a>
@endif
@endif
@if ($refundPernahAda)
<div class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 font-body-sm text-body-sm text-on-surface-variant bg-surface-container-low border border-outline-variant rounded-full px-lg py-3 text-center">
<span class="material-symbols-outlined text-[18px] text-secondary">hourglass_top</span>
@if ($refundAktif)
{{ __('Refund Anda sedang diproses oleh toko.') }}
@elseif ($refundStatus === \App\Models\Refund::STATUS_SELESAI)
{{ __('Pengembalian dana telah selesai.') }}
@elseif ($refundStatus === \App\Models\Refund::STATUS_DITOLAK)
{{ __('Pengajuan refund telah ditolak (maksimal 1x per pesanan).') }}
@else
{{ __('Pengajuan refund telah dilakukan.') }}
@endif
</div>
@endif
</div>
@if ($refundStatus === \App\Models\Refund::STATUS_DITOLAK)
<div class="mt-lg text-center bg-error/10 border border-error/15 rounded-xl p-md">
<p class="font-body-sm text-body-sm text-error">{{ __('Refund Anda ditolak') }}: {{ $latestRefund->alasan_penolakan ?: __('Tidak ada keterangan tambahan.') }}</p>
</div>
@endif
@if($shipment)
<div class="mt-lg grid grid-cols-1 md:grid-cols-3 gap-md text-left">
<div class="border border-outline-variant rounded-lg p-md bg-surface-container-low">
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Ekspedisi') }}</p>
<p class="font-body-lg text-body-lg font-semibold text-on-surface mt-xs">{{ $shipment->courier?->nama_kurir ?? '-' }} @if($shipment->shippingService) • {{ $shipment->shippingService->nama_layanan }}@endif</p>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">{{ __('Estimasi') }}: {{ $shipment->estimasi_tiba?->format('d M Y') ?? '-' }} • {{ __('Ongkir') }} Rp {{ number_format((float)$shipment->ongkir,0,',','.') }}</p>
</div>
<div class="border border-outline-variant rounded-lg p-md bg-surface-container-low">
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Nomor Resi') }}</p>
<p class="font-title-md text-title-md font-semibold text-on-surface mt-xs break-all">{{ $shipment->nomor_resi ?: '-' }}</p>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">Status: {{ $shipment->status }}</p>
</div>
<div class="border border-outline-variant rounded-lg p-md bg-surface-container-low">
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Waktu Pengiriman') }}</p>
<p class="font-body-sm text-body-sm text-on-surface mt-xs">{{ __('Dikirim') }}: {{ $shipment->dikirim_pada?->format('d M Y, H:i') ?? '-' }}</p>
<p class="font-body-sm text-body-sm text-on-surface">{{ __('Diterima') }}: {{ $shipment->diterima_pada?->format('d M Y, H:i') ?? '-' }}</p>
</div>
</div>
@endif
@endif
@endif
</div>
</div>
<!-- Order Items List -->
<section class="py-lg md:py-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
<div class="mb-md md:mb-lg">
<div class="atl-eyebrow mb-1">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Order Items') }}</span>
</div>
<h2 class="premium-heading font-title-md md:font-headline-md text-title-md md:text-headline-md text-on-surface">{{ __('Items in Order') }} <span class="font-body-sm text-body-sm text-on-surface-variant font-normal">• {{ $itemsCount }} {{ __('items') }}</span></h2>
</div>
<!-- Product List (nested scroll container) -->
<div class="ot-product-list">
@foreach ($selected->items as $item)
@php
$v = $item->productVariant;
$img = $v?->product?->images->first()?->file_gambar ?? '';
$imgUrl = $img ? (photo_url($img)) : 'https://picsum.photos/seed/order-'.$item->order_item_id.'/900/1200';
$warna = $v?->warna;
$ukuran = $v?->ukuran;
@endphp
<div class="group flex gap-sm md:gap-md">
<div class="w-24 h-32 md:w-28 md:h-36 bg-surface-container-lowest rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/30">
<img class="w-full h-full object-cover " loading="lazy" src="{{ $imgUrl }}" alt="{{ $item->nama_produk_snapshot }}"/>
</div>
<div class="flex flex-col justify-between py-1 flex-grow min-w-0">
<div class="min-w-0">
<div class="inline-flex items-center self-start px-2 py-0.5 mb-1 rounded-full bg-secondary/5 text-secondary border border-secondary/20 font-label-sm text-label-sm uppercase tracking-widest">{{ $selected->store?->nama_toko ?? 'RALIVA' }}</div>
<h3 class="font-body-sm md:font-title-md text-body-sm md:text-title-md font-semibold text-on-surface truncate">{{ $item->nama_produk_snapshot }}</h3>
@if ($warna || $ukuran)
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 flex flex-wrap gap-2"><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container-low border border-outline-variant text-xs">{{ $warna ?: __('Default') }}@if ($ukuran) • {{ $ukuran }}@endif</span></p>
@endif
</div>
<div class="flex justify-between items-center mt-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Qty:') }} {{ $item->quantity }}</p>
<p class="font-body-sm md:font-title-md text-body-sm md:text-title-md font-semibold text-on-surface">Rp {{ number_format($item->harga_snapshot, 0, ',', '.') }}</p>
</div>
</div>
</div>
@if (! $loop->last)
<div class="h-px bg-outline-variant/40 mx-1 my-1"></div>
@endif
@endforeach
</div>
</div>
</div>
</section>
<!-- Order Summary (Tonal Background) -->
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
<div class="flex items-center gap-sm mb-md">
<div class="w-8 h-8 rounded-full bg-secondary/10 border border-secondary/15 flex items-center justify-center">
<span class="material-symbols-outlined text-secondary text-[18px]">receipt_long</span>
</div>
<h2 class="premium-heading font-title-md md:font-headline-md text-title-md md:text-headline-md text-on-surface">{{ __('Order Summary') }}</h2>
</div>
<div class="flex flex-col gap-sm">
<div class="flex justify-between py-1">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Subtotal') }}</p>
<p class="font-body-sm text-body-sm text-on-surface font-medium">Rp {{ number_format($selected->subtotal, 0, ',', '.') }}</p>
</div>
<div class="flex justify-between py-1">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Shipping') }}</p>
@if ((float) $selected->total_ongkir > 0)
<p class="font-body-sm text-body-sm text-on-surface font-medium">Rp {{ number_format($selected->total_ongkir, 0, ',', '.') }}</p>
@else
<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#e8f5e9] text-[#2e7d32] dark:bg-[#1b3a1f] dark:text-[#a5d6a7] font-label-sm text-label-sm font-semibold">{{ __('Free') }}</span>
@endif
</div>
<div class="flex justify-between py-1">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Tax') }}</p>
<p class="font-body-sm text-body-sm text-on-surface font-medium">Rp {{ number_format($selected->total_pajak, 0, ',', '.') }}</p>
</div>
@if ((float) $selected->biaya_layanan > 0)
<div class="flex justify-between py-1">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Biaya Layanan') }}</p>
<p class="font-body-sm text-body-sm text-on-surface font-medium">Rp {{ number_format($selected->biaya_layanan, 0, ',', '.') }}</p>
</div>
@endif
<div class="w-full h-px bg-outline-variant my-md"></div>
<div class="flex justify-between items-center py-1">
<p class="font-title-md text-title-md text-on-surface font-semibold">{{ __('Total') }}</p>
<p class="font-headline-md text-headline-md text-on-surface">Rp {{ number_format($selected->grand_total, 0, ',', '.') }}</p>
</div>
<p class="font-label-sm text-label-sm text-on-surface-variant/70 text-right mt-1">{{ __('Shipping & taxes calculated at checkout') }}</p>
</div>
</div>
</div>  
<!-- Need Help Action -->
<section class="py-lg md:py-xl text-center reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="rounded-xl p-md md:p-lg card-premium">
<div class="atl-eyebrow mb-sm justify-center">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Need Help?') }}</span>
</div>
<h3 class="font-title-md text-title-md text-on-surface mb-xs">{{ __('We’re here for you') }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-md max-w-md mx-auto">{{ __('Questions about your order? Our team typically replies within 2 hours.') }}</p>
<div class="flex justify-center">
<button class="btn-gold inline-flex items-center justify-center gap-2 font-label-caps text-label-caps px-lg py-sm rounded-full uppercase tracking-widest" type="button">
<span class="material-symbols-outlined text-[18px]">support_agent</span>
                    {{ __('Contact Customer Service') }}
                </button>
</div>
</div>
</div>
</section>
@endif
</main>
<!-- BottomNavBar -->
@include('customer._partials.bottom-nav')
@include('customer._partials.drawer')
<script>
    // btn-gold flash
    document.querySelectorAll('.btn-gold').forEach(function (b) {
        b.addEventListener('click', function () {
            b.classList.remove('flashing');
            void b.offsetWidth;
            b.classList.add('flashing');
            setTimeout(function () { b.classList.remove('flashing'); }, 600);
        });
    });
    // reveal-up on scroll (parity home)
    (function () {
        var els = document.querySelectorAll('.reveal-up');
        if (!('IntersectionObserver' in window)) {
            els.forEach(function (e) { e.classList.add('in'); });
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) {
                    en.target.classList.add('in');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.12 });
        els.forEach(function (e) { io.observe(e); });
    })();
    // === LIVE PRODUCTION TIMERS ===
    function customerDurFmt(totalSec) {
        if (totalSec === null || isNaN(totalSec) || totalSec < 0) totalSec = 0;
        const h = Math.floor(totalSec / 86400);
        const j = Math.floor((totalSec % 86400) / 3600);
        const m = Math.floor((totalSec % 3600) / 60);
        const s = Math.floor(totalSec % 60);
        const parts = [];
        if (h > 0) parts.push(h + 'h');
        if (j > 0) parts.push(j + 'j');
        if (m > 0) parts.push(m + 'm');
        if (s > 0) parts.push(s + 'd');
        return parts.length ? parts.join(' ') : '< 1 menit';
    }

    function tickCustomerTimers() {
        const now = Date.now();
        document.querySelectorAll('[data-customer-countdown-end]').forEach(function (el) {
            const end = parseInt(el.dataset.customerCountdownEnd, 10) * 1000;
            const diff = Math.floor((end - now) / 1000);
            if (diff < 0) {
                el.innerHTML = '<span class="text-error font-semibold">Terlambat ' + customerDurFmt(Math.abs(diff)) + '</span>';
            } else {
                el.innerHTML = '<span class="text-on-surface-variant">Sisa ' + customerDurFmt(diff) + '</span>';
            }
        });
        document.querySelectorAll('[data-customer-elapsed-start]').forEach(function (el) {
            const start = parseInt(el.dataset.customerElapsedStart, 10) * 1000;
            const endRaw = el.dataset.customerElapsedEnd;
            const end = endRaw ? parseInt(endRaw, 10) * 1000 : null;
            const base = end ? end : now;
            el.textContent = customerDurFmt(Math.floor((base - start) / 1000));
        });
    }
    setInterval(tickCustomerTimers, 1000);
    document.addEventListener('DOMContentLoaded', tickCustomerTimers);
</script>
</body></html>