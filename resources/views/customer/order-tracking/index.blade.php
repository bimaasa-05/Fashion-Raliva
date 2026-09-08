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
    $shipment = $selected->shipments->first();
    $estDeliv = $shipment?->estimasi_tiba;
    $itemsCount = $selected->items->count();
    $progressWidth = $isCancelled ? 0 : (($step - 1) / 3 * 100);
    $details = [
        'pending_payment' => [__('Menunggu pembayaran'), __('Silakan selesaikan pembayaran Anda agar pesanan segera diproses.')],
        'dibayar' => [__('Pembayaran diterima'), __('Pembayaran Anda telah kami terima. Pesanan sedang menunggu diproses.')],
        'diproses' => [__('Sedang disiapkan'), __('Pesanan sedang diproses di gudang dan akan segera dikirim.')],
        'dikirim' => [__('Sedang dalam perjalanan'), __('Pesanan sudah dikirim dan sedang dalam perjalanan menuju alamat Anda.')],
        'selesai' => [__('Pesanan selesai'), __('Pesanan telah sampai dan selesai. Terima kasih sudah berbelanja di RALIVA.')],
        'dibatalkan' => [__('Pesanan dibatalkan'), __('Pesanan ini telah dibatalkan. Hubungi layanan pelanggan jika ada pertanyaan.')],
        'refund' => [__('Refund sedang diproses'), __('Pengembalian dana untuk pesanan ini sedang diproses.')],
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
<p class="font-title-md text-title-md md:text-headline-md font-semibold text-on-surface tracking-tight">#{{ $selected->nomor_order }}</p>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $selected->created_at->format('M j, Y') }} • {{ $itemsCount }} {{ __('items') }}</p>
</div>
<div class="text-left md:text-right">
<div class="inline-flex items-center gap-xs px-sm py-1 rounded-full border {{ $isCancelled ? 'bg-error/10 border-error/15' : 'bg-secondary/10 border-secondary/15' }}">
<span class="w-2 h-2 rounded-full {{ $isCancelled ? 'bg-error' : 'bg-secondary' }} animate-pulse"></span>
<span class="font-label-sm text-label-sm {{ $isCancelled ? 'text-error' : 'text-secondary' }} uppercase tracking-wider font-semibold">{{ $statusLabel }}</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 md:text-right">{{ $estDeliv ? __('Est. delivery:').' '.$estDeliv->format('M j, Y') : __('Menunggu konfirmasi pengiriman') }}</p>
</div>
</div>
</div>
</div>
</section>
<!-- Visual Tracking Timeline -->
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
@if ($isCancelled)
<div class="text-center py-1">
<span class="material-symbols-outlined text-[40px] text-error mb-xs block">cancel</span>
<h3 class="font-title-md text-title-md text-on-surface mb-xs">{{ $detail[0] }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant max-w-md mx-auto">{{ $detail[1] }}</p>
</div>
@else
<div class="relative max-w-[480px] mx-auto">
<div class="timeline-line"></div>
<div class="timeline-progress" style="width: {{ $progressWidth }}%;"></div>
<div class="flex justify-between gap-2 relative z-10">
@foreach ([1 => __('Preparing'), 2 => __('Packed'), 3 => __('Shipped'), 4 => __('Delivered')] as $stepIndex => $stepLabel)
@php
$passed = ! $isCancelled && $step && $stepIndex < $step;
$active = ! $isCancelled && $step && $stepIndex === $step;
@endphp
<div class="flex flex-col items-center gap-1 group cursor-pointer flex-1">
@if ($passed)
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full flex items-center justify-center bg-secondary border border-secondary shrink-0">
<span class="material-symbols-outlined text-[14px] text-white">check</span>
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface-variant text-center leading-tight">{{ $stepLabel }}</span>
@elseif ($active)
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full flex items-center justify-center bg-surface transition-colors timeline-active-circle shrink-0" style="border: 2px solid var(--chrome-accent);">
<div class="w-2.5 h-2.5 md:w-2 md:h-2 rounded-full timeline-active-dot" style="background-color: var(--chrome-accent);"></div>
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface text-center leading-tight timeline-active-label" style="color: var(--chrome-accent);">{{ $stepLabel }}</span>
@else
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full bg-surface border border-outline-variant flex items-center justify-center shrink-0 transition-colors group-hover:border-outline">
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface-variant text-center leading-tight">{{ $stepLabel }}</span>
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
$imgUrl = $img ? (filter_var($img, FILTER_VALIDATE_URL) ? $img : asset($img)) : 'https://picsum.photos/seed/order-'.$item->order_item_id.'/900/1200';
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
</script>
</body></html>