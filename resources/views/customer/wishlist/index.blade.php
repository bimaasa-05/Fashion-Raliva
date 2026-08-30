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
    .card-premium:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(17,17,17,.06), 0 18px 40px -16px rgba(17,17,17,.18); border-color: rgba(139,30,63,.45); }
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
<!-- TopAppBar -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)]">
<a href="{{ url()->previous() }}" aria-label="Back" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)]">RALIVA</h1>
<div class="flex items-center gap-sm">
<a href="{{ route('customer.chart') }}" aria-label="Cart" class="relative hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
<span class="absolute -top-1 -right-1 bg-secondary-fixed-dim text-on-secondary-fixed text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">2</span>
</a>
</div>
</header>
<!-- Main Content -->
<main class="flex-grow pt-16 pb-8 lg:pb-12 w-full overflow-x-hidden">
<!-- Wishlist Header (Super-Admin style premium card, aksen Burgundy) -->
<section class="py-xl px-container-margin reveal-up">
<div class="mx-auto max-w-7xl">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg shadow-sm card-premium">
<div class="flex items-center justify-between gap-md">
<div>
<div class="atl-eyebrow mb-xs">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Saved Items') }}</span>
</div>
<h2 class="premium-heading font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">{{ __('My Wishlist') }}</h2>
<p class="mt-2 inline-flex items-center gap-xs font-label-sm text-label-sm text-secondary rounded-full bg-secondary/5 px-sm py-xs">
<span class="material-symbols-outlined text-[16px]" data-icon="checkroom">checkroom</span>
4 {{ __('items saved') }}
</p>
</div>
<span class="material-symbols-outlined text-secondary text-[30px] shrink-0" data-icon="favorite" data-weight="fill">favorite</span>
</div>
</div>
</div>
</section>
<!-- Wishlist Grid (wrapped in Super-Admin style premium card, aksen Burgundy) -->
<section class="py-xl px-container-margin reveal-up">
<div class="mx-auto max-w-7xl">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg shadow-sm card-premium">
<div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
@foreach ([
                    ['brand' => 'Noiré Studio', 'name' => 'Tailored Linen Blazer', 'price' => '$245.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBPD5-Gnh3eTuUtU4T7JNWo5RRzeJvQHK9Ga-Qyub2VAxmLGZrXcu5eAhUHzglaK2leeCgs_S1rotd_qxAlW3J4__SdbjTf72VBHQzRpit8rbEixeyo2UKLpiBeBbgQfpUO8i83JOSeojGk4-pg0MhKw305uBjXfYyPk4JPteEhhs_SytMO40NERGkVHIbKNFaDIS4tZRo7KpphEGebXYRJRggcWTAf3NNm6pvcs8WOjecDptx1ZzQ'],
                    ['brand' => 'Lunara Fashion', 'name' => 'Structured Leather Tote', 'price' => '$380.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDotrquQ9ru5aXlWl5XbgLhEMJq3WBfo5DDEAS3Z-F5LnAIv27Q3259la3QLZghjnF5R8udNJqY0Toq6SHw5JvN3PqANThsUOvwujXixkrq5zZBH5OW_D3QTRD3qObufW5Uz2-ahDe36xdtDHuA8SK2Ldhp4wpMReozYAnqkNj5ZG3A37LwDOS6aXDnCEg_MNh_j2C1VKegB7PNMCwMV-jwzYAwrhuqG1UCGjQoSl3A0QRKO-gFHlQ'],
                    ['brand' => 'Maëva House', 'name' => 'Silk Slip Dress', 'price' => '$195.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBrQWexD2Xms4d7-qplQNqqTI4EebkIxaCqpOssP3jfxkcDDAjBvE4kuCEgO-j-Yd-Vfxm6sW-zOaQShx89-kFo0JwvaQ9DnVYjw0ZeHlwNYQaWtigNJNUb1P2E3VS7jVbvb2gfkn5AgK0_pHzGjUiSO2kjiDWXbTKy2tRqRQq5I2md_UYdyHQR_axy07aFn3BeoVctJgri9jLNSSEizCJoXGSF5I0rX6QAaqkzanalXeH6sTmuLnA'],
                    ['brand' => 'Kayana Apparel', 'name' => 'Geometric Gold Hoops', 'price' => '$85.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAXqNhNFWMr-Gm8_uwAVgBbqtzcNdb5MAfQUsG_3GJbmE0gm167f27WLQY44QclgDSw7N_b2k0qpe9HdTKZlExYsZl6FJUCnKft0foIHP3pp3uFUAxnwrYM3o7ap46wCmmnSGAbNN-gDM_Kptg0bVNG6ghZhp7r3PeQ66ZD2yhgIMKhB9sSycHTa8yXBJ3fTbNvx2tH5SUu76da_WcZ3bJW7JeJmVuEnVOdIHENcwQB0a1sOCp-u_s'],
                ] as $product)
<div class="flex flex-col group cursor-pointer">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden rounded-lg">
<img loading="lazy" decoding="async" alt="{{ $product['name'] }}" class="object-cover w-full h-full group-hover:scale-[1.04] transition-transform duration-500" src="{{ $product['img'] }}"/>
<span role="button" tabindex="0" aria-label="{{ __('Remove from wishlist') }}" class="absolute top-2 right-2 p-2 rounded-full bg-black/15 backdrop-blur-sm text-white hover:bg-black/30 hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite" data-weight="fill">favorite</span>
</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ $product['brand'] }}</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">{{ $product['name'] }}</h3>
<span class="font-body-sm text-body-sm text-on-surface mt-1">{{ $product['price'] }}</span>
</a>
<a href="{{ route('customer.chart') }}" class="btn-gold mt-sm font-label-caps text-label-caps px-xs py-xs lg:px-md uppercase tracking-widest transition-colors flex items-center justify-center gap-xs">
<span class="material-symbols-outlined text-[16px]" data-icon="add_shopping_cart">add_shopping_cart</span>
{{ __('ADD TO CART') }}
</a>
</div>
@endforeach
</div>
</div>
</div>
</section>
</main>
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
<!-- BottomNavBar -->
@include('customer._partials.bottom-nav')
@include('customer._partials.drawer')
</body></html>
