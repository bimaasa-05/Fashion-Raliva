<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Wishlist') }}</title>
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
                        "secondary-fixed": "#8B1E3F",
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
                        "on-secondary-fixed-variant": "#6D1428",
                        "on-tertiary-fixed": "#1a1c1a",
                        "on-secondary-container": "#6D1428",
                        "inverse-on-surface": "#f2f0f0",
                        "tertiary-fixed-dim": "#c7c6c4",
                        "tertiary-fixed": "#e3e2df",
                        "surface-container-high": "#e9e8e7",
                        "on-secondary-fixed": "#6D1428",
                        "background": "#fbf9f9",
                        "surface": "#fbf9f9",
                        "secondary-container": "#8B1E3F",
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
                        "display-lg": ["40px", {"lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "600"}],
                        "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "700"}],
                        "headline-lg-mobile": ["28px", {"lineHeight": "36px", "fontWeight": "500"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "fontWeight": "500"}],
                        "title-md": ["18px", {"lineHeight": "24px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "500"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}]
                    }
                }
            }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined[data-weight="fill"] {
            font-variation-settings: 'FILL' 1;
        }
        /* Hide scrollbar for horizontal scroll areas */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
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
        --chrome-bg-soft: rgba(255,255,255,.92);
        --chrome-text: #1b1c1c;
        --chrome-text-dim: rgba(0,0,0,.55);
        --chrome-text-faint: rgba(0,0,0,.45);
        --chrome-border: rgba(0,0,0,.1);
        --chrome-hover: rgba(0,0,0,.06);
        --chrome-accent: #8B1E3F;       /* Burgundy */
        --surface-ivory: #F8F6F2;       /* Soft Ivory */
        --surface-warm: #F3F0EA;       /* Warm Ivory */
        --border-soft: #E5E1DA;         /* Soft border */
        --text-muted: #777777;          /* Secondary text */
    }
    html.theme-dark {
        --chrome-bg: #1c1b1b;
        --chrome-bg-soft: rgba(28,27,27,.9);
        --chrome-text: #ffffff;
        --chrome-text-dim: rgba(255,255,255,.6);
        --chrome-text-faint: rgba(255,255,255,.5);
        --chrome-border: rgba(255,255,255,.1);
        --chrome-hover: rgba(255,255,255,.1);
        --chrome-accent: #8B1E3F;       /* Burgundy (same identity) */
        --surface-ivory: #1e1d1c;       /* dark secondary surface */
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
    /* Scoped burgundy accent for the shared drawer + bottom-nav partials on this page */
    #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(139,30,30,.10); }
    html.theme-dark #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(163,38,38,.16); }
    .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    html.theme-dark .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    .bn-active { color: #8B1E3F !important; }
    html.theme-dark .bn-active { color: #8B1E3F !important; }
</style>
<style>
    /* ===== Wishlist header search (scoped) ===== */
    .wl-header-item { transition: opacity .3s ease, transform .3s ease; }
    .wl-header-hidden { opacity: 0; transform: translateY(-6px); pointer-events: none; }
    #wl-search-panel { opacity: 0; transform: translateX(28px); pointer-events: none; transition: opacity .3s cubic-bezier(.22,1,.36,1), transform .3s cubic-bezier(.22,1,.36,1); }
    #wl-search-panel.wl-search-open { opacity: 1; transform: translateX(0); pointer-events: auto; }
    #wl-search-input,
    #wl-search-input:focus,
    #wl-search-input:focus-visible,
    #wl-search-input:active {
        outline: none !important;
        box-shadow: none !important;
        -webkit-appearance: none;
        appearance: none;
    }
    #wl-search-input { caret-color: #8B1E3F; }
    #wl-search-input::selection { background: rgba(139,30,63,.55); color: #ffffff; }
    #wl-search-input:-webkit-autofill,
    #wl-search-input:-webkit-autofill:hover,
    #wl-search-input:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--chrome-text);
        -webkit-box-shadow: 0 0 0 1000px var(--chrome-bg) inset;
        box-shadow: 0 0 0 1000px var(--chrome-bg) inset;
        transition: background-color 9999s ease-in-out 0s;
        caret-color: #8B1E3F;
    }
    #wl-hamburger:focus,
    #wl-search-toggle:focus,
    #wl-search-close:focus,
    #wl-search-clear:focus,
    #wl-search-input:focus { outline: none !important; }
    #wl-hamburger:active,
    #wl-search-toggle:active,
    #wl-search-close:active,
    #wl-search-clear:active { outline: none !important; box-shadow: none !important; }
    #wl-hamburger:focus-visible,
    #wl-search-toggle:focus-visible,
    #wl-search-close:focus-visible,
    #wl-search-clear:focus-visible { outline: none !important; box-shadow: 0 0 0 2px rgba(139,30,63,.5); border-radius: 9999px; }
</style>
<style>
    /* ============ ATELIER EYEBROW (parity home/order-tracking) ============ */
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

    /* ============ BUTTON (burgundy, parity home/shop/order-tracking) ============ */
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

    @media (prefers-reduced-motion: reduce) {
        .reveal-up { animation: none !important; opacity: 1 !important; transform: none !important; }
    }
</style>
  </head>
<body class="antialiased font-body-lg pb-[72px] md:pb-0 lg:pl-72" style="background-color: var(--surface-warm);">
@php $cartCount = auth()->check() ? \App\Http\Controllers\Customer\CartController::countForUser(auth()->id()) : 0; @endphp
<!-- TopAppBar -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)]">
<button id="wl-hamburger" aria-label="{{ __('Menu') }}" class="wl-header-item hover:opacity-80 transition-opacity lg:hidden flex items-center justify-center" onclick="openDrawer()" type="button">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<h1 id="wl-title" class="wl-header-item font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)]">RALIVA</h1>
<div class="flex items-center gap-sm">
<button id="wl-search-toggle" aria-label="{{ __('Search wishlist') }}" class="wl-header-item hover:opacity-80 transition-opacity flex items-center justify-center relative" onclick="toggleWishlistSearch()" type="button">
<span class="material-symbols-outlined text-[22px]" data-icon="search">search</span>
</button>
</div>
</header>
<div id="wl-search-panel" class="fixed top-0 inset-x-0 lg:left-72 z-[55] h-16 bg-[var(--chrome-bg)] text-[var(--chrome-text)] border-b border-[var(--chrome-border)] flex items-center gap-sm px-container-margin">
<button id="wl-search-close" aria-label="{{ __('Close search') }}" class="hover:opacity-80 transition-opacity flex items-center justify-center shrink-0" onclick="toggleWishlistSearch()" type="button">
<span class="material-symbols-outlined text-[22px]" data-icon="search">search</span>
</button>
<input id="wl-search-input" type="text" inputmode="search" autocomplete="off" placeholder="{{ __('Cari wishlist Anda...') }}" class="flex-1 min-w-0 bg-transparent font-body-lg text-body-lg text-on-surface placeholder:text-on-surface-variant/70 border-b border-[var(--chrome-border)] focus:border-secondary py-2"/>
<button id="wl-search-clear" aria-label="{{ __('Clear search') }}" class="hidden hover:opacity-80 transition-opacity flex items-center justify-center shrink-0" onclick="clearWishlistSearch()" type="button">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant" data-icon="close">close</span>
</button>
<span id="wl-search-count" class="font-label-sm text-label-sm text-on-surface-variant shrink-0 hidden"></span>
</div>
<!-- Main Content -->
<main class="flex-grow pt-16 pb-8 lg:pb-12 w-full overflow-x-hidden">
<!-- Wishlist Header (Super-Admin style premium card, aksen Burgundy) -->
<section class="py-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
<div class="flex items-center justify-between gap-md">
<div>
<div class="atl-eyebrow mb-xs">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Saved Items') }}</span>
</div>
<h2 class="premium-heading font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">{{ __('My Wishlist') }}</h2>
 <p class="mt-2 inline-flex items-center gap-xs font-label-sm text-label-sm text-secondary rounded-full bg-secondary/5 px-sm py-xs">
<span class="material-symbols-outlined text-[16px]" data-icon="checkroom">checkroom</span>
<span id="wishlist-count">{{ $items->count() }}</span> {{ __('items saved') }}
</p>
</div>
<span class="material-symbols-outlined text-secondary text-[30px] shrink-0" data-icon="favorite" data-weight="fill">favorite</span>
</div>
</div>
</div>
</section>
<!-- Wishlist Grid (wrapped in Super-Admin style premium card, aksen Burgundy) -->
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
<div class="grid grid-cols-2 md:grid-cols-4 gap-gutter" id="wishlist-grid">
@forelse ($items as $item)
@php
    $p = $item->product;
    $img = $p?->images->first()?->file_gambar ?? '';
    $minPrice = $p?->variants->min('harga') ?? $p?->harga_dasar ?? 0;
    $brand = $p?->store?->nama_toko ?? __('RALIVA');
@endphp
<div class="flex flex-col group" data-wishlist-item data-product-id="{{ $p?->product_id }}">
<a href="{{ $p ? route('customer.shop.produk-detail', $p->product_id) : '#' }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden rounded-lg">
<img loading="lazy" decoding="async" alt="{{ $p?->nama_produk ?? '' }}" class="object-cover w-full h-full " src="{{ $img ? (filter_var($img, FILTER_VALIDATE_URL) ? $img : asset($img)) : 'https://picsum.photos/seed/product/900/1200' }}"/>
<button type="button" aria-label="{{ __('Remove from wishlist') }}" data-wishlist-remove data-product-id="{{ $p?->product_id }}" class="absolute top-2 right-2 p-2 rounded-full bg-black/15 backdrop-blur-sm text-white hover:bg-black/30 hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite" data-weight="fill">favorite</span>
</button>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ $brand }}</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">{{ $p?->nama_produk ?? '' }}</h3>
<span class="font-body-sm text-body-sm text-on-surface mt-1">{{ $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : '' }}</span>
</a>
@php
    $activeVariants = $p?->variants ?? collect();
    $defaultVariant = $activeVariants->sortBy('harga')->first();
@endphp
@if ($defaultVariant)
<button type="button" data-cart-add data-variant-id="{{ $defaultVariant->product_variant_id }}" class="btn-gold mt-sm font-label-caps text-label-caps px-xs py-xs lg:px-md uppercase tracking-widest transition-colors flex items-center justify-center gap-xs w-full">
<span class="material-symbols-outlined text-[16px]" data-icon="add_shopping_cart">add_shopping_cart</span>
{{ __('ADD TO CART') }}
</button>
@else
<a href="{{ $p ? route('customer.shop.produk-detail', $p->product_id) : '#' }}" class="btn-gold mt-sm font-label-caps text-label-caps px-xs py-xs lg:px-md uppercase tracking-widest transition-colors flex items-center justify-center gap-xs w-full">
<span class="material-symbols-outlined text-[16px]" data-icon="add_shopping_cart">add_shopping_cart</span>
{{ __('ADD TO CART') }}
</a>
@endif
</div>
@empty
<div class="col-span-full flex flex-col items-center justify-center text-center py-2xl gap-md">
<span class="material-symbols-outlined text-[72px] text-on-surface-variant/40" data-icon="favorite_border">favorite_border</span>
<p class="font-body-lg text-body-lg text-on-surface-variant">{{ __('Wishlist Anda masih kosong.') }}</p>
<a href="{{ route('customer.shop') }}" class="btn-gold mt-sm font-label-caps text-label-caps px-lg py-sm uppercase tracking-widest transition-colors">{{ __('EXPLORE PRODUCTS') }}</a>
</div>
@endforelse
</div>
</div>
</div>
<p id="wl-no-results" class="hidden mt-md text-center font-body-sm text-body-sm text-on-surface-variant mx-auto max-w-[1400px] px-container-margin">{{ __('Tidak ada item yang cocok.') }}</p>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var grid = document.getElementById('wishlist-grid');
        if (!grid) return;

        grid.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-wishlist-remove]');
            if (!btn) return;

            var productId = btn.getAttribute('data-product-id');
            var csrf = '{{ csrf_token() }}';
            var url = '{{ route("customer.wishlist.destroy", 0) }}'.replace('/0', '/' + productId);

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.status === 'removed') {
                    var card = btn.closest('[data-wishlist-item]');
                    if (card) card.remove();
                    var countEl = document.getElementById('wishlist-count');
                    if (countEl) countEl.textContent = data.count;

                    if (grid.querySelectorAll('[data-wishlist-item]').length === 0) {
                        grid.innerHTML = '<div class="col-span-full flex flex-col items-center justify-center text-center py-2xl gap-md">' +
                            '<span class="material-symbols-outlined text-[72px] text-on-surface-variant/40" data-icon="favorite_border">favorite_border</span>' +
                            '<p class="font-body-lg text-body-lg text-on-surface-variant">{{ __("Wishlist Anda masih kosong.") }}</p>' +
                            '<a href="{{ route("customer.shop") }}" class="btn-gold mt-sm font-label-caps text-label-caps px-lg py-sm uppercase tracking-widest transition-colors">{{ __("EXPLORE PRODUCTS") }}</a>' +
                            '</div>';
                    }
                }
            })
            .catch(function () {});
        });
    });
</script>
<script>
    /* Subtle scroll reveal (parity home) */
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
<script>
    /* Wishlist header search: slide-to-left overlay + live filter */
    (function () {
        var panel = document.getElementById('wl-search-panel');
        var input = document.getElementById('wl-search-input');
        var countEl = document.getElementById('wl-search-count');
        var clearBtn = document.getElementById('wl-search-clear');
        var noResults = document.getElementById('wl-no-results');
        var headers = document.querySelectorAll('.wl-header-item');
        if (!panel || !input) return;

        var open = false;
        function setOpen(v) {
            open = v;
            panel.classList.toggle('wl-search-open', v);
            headers.forEach(function (h) { h.classList.toggle('wl-header-hidden', v); });
            if (v) {
                input.focus();
            } else {
                input.value = '';
                filter('');
            }
        }
        window.toggleWishlistSearch = function () { setOpen(!open); };
        window.clearWishlistSearch = function () {
            input.value = '';
            filter('');
            input.focus();
        };

        function filter(q) {
            q = (q || '').trim().toLowerCase();
            var cards = document.querySelectorAll('[data-wishlist-item]');
            var shown = 0;
            cards.forEach(function (card) {
                var nameEl = card.querySelector('h3');
                var name = (nameEl ? nameEl.textContent : '').toLowerCase();
                var ok = !q || name.indexOf(q) >= 0;
                card.style.display = ok ? '' : 'none';
                if (ok) shown++;
            });
            if (clearBtn) clearBtn.classList.toggle('hidden', !q);
            if (countEl) {
                countEl.textContent = shown + ' / ' + cards.length;
                countEl.classList.toggle('hidden', !q);
            }
            if (noResults) noResults.classList.toggle('hidden', shown > 0 || cards.length === 0);
        }

        input.addEventListener('input', function () { filter(input.value); });
        input.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
    })();
</script>
<!-- BottomNavBar -->
@include('customer._partials.bottom-nav')
@include('customer._partials.drawer')
</body></html>
