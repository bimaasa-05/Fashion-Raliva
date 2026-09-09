<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
<title>RALIVA - {{ __('Cart') }}</title>
<script>if (localStorage.getItem('raliva-theme') === 'dark') document.documentElement.classList.add('theme-dark');</script>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        "surface-bright": "#fbf9f9",
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
                        "secondary-fixed": "#ffdf9f",
                        "on-primary": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "error": "#ba1a1a",
                        "surface-container-highest": "#e3e2e2",
                        "inverse-surface": "#303031",
                        "surface-container": "#efeded",
                        "tertiary": "#000000",
                        "primary-fixed": "#e5e2e1",
                        "outline-variant": "#c4c7c7",
                        "surface-tint": "#5f5e5e",
                        "secondary-fixed-dim": "#8B1E3F",
                        "outline": "#747878",
                        "on-primary-fixed-variant": "#474646",
                        "on-secondary-fixed-variant": "#5c4300",
                        "on-tertiary-fixed": "#1a1c1a",
                        "on-secondary-container": "#775804",
                        "inverse-on-surface": "#f2f0f0",
                        "tertiary-fixed-dim": "#c7c6c4",
                        "tertiary-fixed": "#e3e2df",
                        "surface-container-high": "#e9e8e7",
                        "on-secondary-fixed": "#261a00",
                        "background": "#fbf9f9",
                        "surface": "#fbf9f9",
                        "secondary-container": "#fdd177",
                        "on-surface-variant": "#444748",
                        "primary-container": "#1c1b1b",
                        "inverse-primary": "#c8c6c5",
                        "surface-container-low": "#f5f3f3",
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
        body { -webkit-tap-highlight-color: transparent; }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
        .pt-safe { padding-top: env(safe-area-inset-top); }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
<style>
        :root {
        --chrome-bg: #ffffff;
        --chrome-bg-soft: rgba(255,255,255,.92);
        --chrome-text: #1b1c1c;
        --chrome-text-dim: rgba(0,0,0,.55);
        --chrome-text-faint: rgba(0,0,0,.45);
        --chrome-border: rgba(0,0,0,.1);
        --chrome-hover: rgba(0,0,0,.06);
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
        --border-soft: #3a3937;
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
    /* ============ BURGUNDY PARITY (wishlist/checkout) ============ */
    /* Scoped burgundy accent for the shared drawer + bottom-nav partials */
    #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(139,30,63,.10); }
    html.theme-dark #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(163,38,63,.16); }
    .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    html.theme-dark .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    .bn-active { color: #8B1E3F !important; }
    html.theme-dark .bn-active { color: #8B1E3F !important; }

    /* Atelier Eyebrow */
    .atl-eyebrow { display: inline-flex; align-items: center; gap: .65rem; }
    .atl-eyebrow::before { content: ''; width: 30px; height: 1px; background: var(--chrome-accent); opacity: .7; }

    /* Button (burgundy) */
    .btn-gold {
        position: relative;
        overflow: hidden;
        isolation: isolate;
        background-color: var(--btn-gold-bg) !important;
        color: var(--btn-gold-text) !important;
    }
    .btn-gold > * { position: relative; z-index: 2; }
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
        z-index: 1;
        opacity: 0;
    }
    .btn-gold:hover::after, .btn-gold.flashing::after { opacity: 1; animation: authFlash 1.4s linear infinite; }
    .btn-gold.flashing::after { animation: authFlash 1.4s cubic-bezier(.4,0,.2,1) 1; }
    @keyframes authFlash { from { left: -80%; } to { left: 135%; } }
    :root           { --btn-gold-bg: #8B1E3F; --btn-gold-text: #ffffff; }
    html.theme-dark { --btn-gold-bg: #6D1428; --btn-gold-text: #ffffff; }
    .btn-gold:hover { box-shadow: 0 0 0 1px rgba(139,30,63,.35), 0 8px 22px -8px rgba(139,30,63,.45); }

    /* Premium Card + Heading (burgundy accent) */
    .card-premium {
        background-color: var(--surface-ivory);
        border: 1px solid var(--border-soft);
        border-radius: 0.75rem;
        box-shadow: 0 1px 2px rgba(17,17,17,.04), 0 8px 24px -12px rgba(17,17,17,.12);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .card-premium:hover {  box-shadow: 0 4px 12px rgba(17,17,17,.06), 0 18px 40px -16px rgba(17,17,17,.18); border-color: rgba(139,30,63,.45); }
    html.theme-dark .card-premium { background-color: var(--surface-ivory); border-color: var(--border-soft); box-shadow: 0 1px 2px rgba(0,0,0,.3), 0 8px 24px -12px rgba(0,0,0,.5); }
</style>
  </head>
<body class="bg-background text-on-background min-h-screen flex flex-col font-body-lg lg:pl-72">
<!-- TopAppBar (Small Center Aligned) -->
<header class="bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 pt-safe border-b border-[var(--chrome-border)] sticky top-0 z-40">
<a href="{{ route('customer.shop') }}" class="w-10 h-10 flex items-center justify-center -ml-2 hover:opacity-80 transition-opacity">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">arrow_back_ios_new</span>
</a>
<div class="flex-1 flex justify-center">
<h1 id="cart-title" class="font-title-md text-title-md uppercase text-[var(--chrome-accent)]">{{ __('CART') }} ({{ $count }})</h1>
</div>
<button class="w-10 h-10 flex items-center justify-center -mr-2 text-on-surface hover:opacity-80 transition-opacity">
<!-- Empty trailing icon space to balance header -->
</button>
</header>
<!-- Main Content Canvas -->
<main class="flex-1 overflow-y-auto pb-36 lg:pb-28 w-full">
<div class="lg:max-w-screen-xl lg:mx-auto lg:px-md">

<!-- Empty state -->
<div id="cart-empty" class="{{ $items->count() ? 'hidden' : '' }} flex flex-col items-center justify-center text-center gap-md py-2xl px-container-margin min-h-[calc(100dvh-4rem)]">
<span class="material-symbols-outlined text-[72px] text-on-surface-variant/40" data-icon="shopping_bag">shopping_bag</span>
<p class="font-body-lg text-body-lg text-on-surface-variant">{{ __('Keranjang kamu masih kosong.') }}</p>
<a href="{{ route('customer.shop') }}" class="btn-gold mt-sm font-label-caps text-label-caps px-lg py-sm uppercase tracking-widest transition-colors">{{ __('START SHOPPING') }}</a>
</div>

<div id="cart-content" class="{{ $items->count() ? '' : 'hidden' }} w-full">
<!-- Cart Items (single shared div) -->
<div class="flex flex-col gap-md px-container-margin pt-md pb-xs">
@foreach ($items as $i)
@php
    $pv = $i->productVariant;
    $pr = $pv?->product;
    $img = $pr?->images->first()?->file_gambar ?? '';
    $name = $pr?->nama_produk ?? __('Produk');
    $color = $pv?->warna ?? '';
    $size = $pv?->ukuran ?? '';
    $itemTotal = $i->quantity * $i->harga_snapshot;
    $imgUrl = $img ? (filter_var($img, FILTER_VALIDATE_URL) ? $img : asset($img)) : 'https://picsum.photos/seed/cart/900/1200';
@endphp
<div class="card-premium p-md md:p-lg flex gap-md relative" data-cart-row data-item-id="{{ $i->cart_item_id }}">
<!-- Item Image -->
<div class="w-24 h-32 bg-surface-container-high rounded-lg overflow-hidden shrink-0">
<img class="w-full h-full object-cover" loading="lazy" decoding="async" alt="{{ $name }}" src="{{ $imgUrl }}"/>
</div>
<!-- Item Details -->
<div class="flex-1 flex flex-col justify-between py-xs">
<div class="flex justify-between items-start">
<div>
<h3 class="font-body-sm text-body-sm font-semibold text-[var(--chrome-accent)]">{{ $name }}</h3>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-1">{{ trim($color . ' / ' . $size, ' /') }}</p>
</div>
<button type="button" data-cart-remove data-item-id="{{ $i->cart_item_id }}" class="text-on-surface-variant hover:text-error transition-colors p-1 -mr-1 -mt-1">
<span class="material-symbols-outlined text-lg">close</span>
</button>
</div>
<div class="flex justify-between items-end mt-sm">
<p class="font-body-sm text-body-sm text-[var(--chrome-accent)]" data-item-total>{{ 'Rp ' . number_format($itemTotal, 0, ',', '.') }}</p>
<!-- Quantity Selector -->
<div class="flex items-center border border-outline-variant h-8 qty-wrap">
<button type="button" data-cart-dec data-item-id="{{ $i->cart_item_id }}" class="w-8 h-full flex items-center justify-center text-on-surface-variant hover:text-secondary transition-colors">
<span class="material-symbols-outlined text-sm">remove</span>
</button>
<span class="qty-value font-body-sm text-body-sm w-8 text-center">{{ $i->quantity }}</span>
<button type="button" data-cart-inc data-item-id="{{ $i->cart_item_id }}" class="w-8 h-full flex items-center justify-center text-on-surface-variant hover:text-secondary transition-colors">
<span class="material-symbols-outlined text-sm">add</span>
</button>
</div>
</div>
</div>
</div>
@endforeach
</div>
</div>
</div>
</main>
<!-- Sticky Bottom Toolbar: Order Summary + Checkout -->
<div id="cart-footer" class="{{ $items->count() ? '' : 'hidden' }} fixed bottom-0 inset-x-0 lg:left-72 z-50 px-container-margin py-sm pb-safe">
<div class="card-premium flex items-center gap-sm md:gap-md bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-xs md:p-sm shadow-[0_-4px_24px_-12px_rgba(0,0,0,0.18)]">
<div class="flex-1 min-w-0 leading-tight">
<p class="font-label-sm text-label-sm text-on-surface-variant truncate">{{ __('Subtotal') }}</p>
<p id="cart-total" class="font-title-md text-title-md text-[var(--chrome-accent)] truncate">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</p>
</div>
<a href="{{ route('customer.checkout') }}" class="btn-gold shrink-0 font-label-caps text-label-caps h-14 px-xl flex items-center justify-center rounded-lg uppercase tracking-widest">
{{ __('Checkout') }}
</a>
</div>
</div>
@include('customer._partials.drawer')
</body></html>