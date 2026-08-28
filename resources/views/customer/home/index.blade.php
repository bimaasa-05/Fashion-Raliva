<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="color-scheme" content="light dark"/>
<title>RALIVA - {{ __('The Art of Everyday Dressing') }}</title>
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
                        "secondary": "#8B1E1E",
                        "surface-dim": "#dbdad9",
                        "on-error": "#ffffff",
                        "primary": "#000000",
                        "on-secondary": "#ffffff",
                        "tertiary-container": "#1a1c1a",
                        "error-container": "#ffdad6",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed": "#8B1E1E",
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
                        "secondary-fixed-dim": "#8B1E1E",
                        "outline": "#747878",
                        "on-primary-fixed-variant": "#474646",
                        "on-secondary-fixed-variant": "#5E0F0F",
                        "on-tertiary-fixed": "#1a1c1a",
                        "on-secondary-container": "#5E0F0F",
                        "inverse-on-surface": "#f2f0f0",
                        "tertiary-fixed-dim": "#c7c6c4",
                        "tertiary-fixed": "#e3e2df",
                        "surface-container-high": "#e9e8e7",
                        "on-secondary-fixed": "#5E0F0F",
                        "background": "#fbf9f9",
                        "surface": "#fbf9f9",
                        "secondary-container": "#8B1E1E",
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
        :root {
        --chrome-bg: #ffffff;
        --chrome-bg-soft: rgba(255,255,255,.92);
        --chrome-text: #1b1c1c;
        --chrome-text-dim: rgba(0,0,0,.55);
        --chrome-text-faint: rgba(0,0,0,.45);
        --chrome-border: rgba(0,0,0,.1);
        --chrome-hover: rgba(0,0,0,.06);
        --chrome-accent: #8B1E1E;
    }
    html.theme-dark {
        --chrome-bg: #1c1b1b;
        --chrome-bg-soft: rgba(28,27,27,.9);
        --chrome-text: #ffffff;
        --chrome-text-dim: rgba(255,255,255,.6);
        --chrome-text-faint: rgba(255,255,255,.5);
        --chrome-border: rgba(255,255,255,.1);
        --chrome-hover: rgba(255,255,255,.1);
        --chrome-accent: #A32626;
    }
</style>
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
    /* ============ FULL DARK MODE TOKEN REMAP (matched to RALIVA Register) ============ */
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
    html.theme-dark .text-secondary { color: #A32626 !important; }
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
    html.theme-dark .hover\:text-secondary:hover { color: #A32626 !important; }
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
    /* ============  BUTTON + LIGHT FLASH ============ */
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
    :root           { --btn-gold-bg: #8B1E1E; --btn-gold-text: #ffffff; }
    html.theme-dark { --btn-gold-bg: #5E0F0F; --btn-gold-text: #ffffff; }

    /* ============ HERO ENTRANCE (image fade + staggered text) ============ */
    @keyframes heroFade { from { opacity: 0; } to { opacity: 1; } }
    .hero-photos { animation: heroFade 1.1s ease both; }

    @keyframes heroReveal {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: none; }
    }
    .hero-reveal {
        opacity: 0;
        animation: heroReveal .8s cubic-bezier(.22, 1, .36, 1) forwards;
    }
    .hero-content > .hero-reveal:nth-child(1) { animation-delay: .45s; }
    .hero-content > .hero-reveal:nth-child(2) { animation-delay: .60s; }
    .hero-content > .hero-reveal:nth-child(3) { animation-delay: .75s; }
    .hero-content > .hero-reveal:nth-child(4) { animation-delay: .90s; }

    /* ============ SUBTLE SCROLL REVEAL ============ */
    @keyframes sectionRise {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: none; }
    }
    .reveal-up { opacity: 0; }
    .reveal-up.in { animation: sectionRise .7s cubic-bezier(.22, 1, .36, 1) forwards; }

    /* ============ ATELIER EYEBROW MARKER ============ */
    .atl-eyebrow { display: inline-flex; align-items: center; gap: .65rem; }
    .atl-eyebrow::before {
        content: '';
        width: 30px;
        height: 1px;
        background: var(--chrome-accent);
        opacity: .7;
    }

    @media (prefers-reduced-motion: reduce) {
        .hero-photos, .hero-reveal, .reveal-up { animation: none !important; opacity: 1 !important; transform: none !important; }
    }

    /* ============ RALIVA HOME: remap drawer + bottom-nav accent to burgundy (Register language)
       Scoped so only the Home page is affected; other customer pages keep their own theme. ============ */
    #drawer-panel {
        --chrome-accent: #8B1E1E;
        --gold-wash: rgba(139, 30, 30, .10);
    }
    html.theme-dark #drawer-panel {
        --chrome-accent: #A32626;
        --gold-wash: rgba(163, 38, 38, .16);
    }
    .bn-active .material-symbols-outlined { color: #8B1E1E !important; }
    html.theme-dark .bn-active .material-symbols-outlined { color: #A32626 !important; }
</style>
</head>
<body class="bg-surface text-on-surface antialiased font-body-lg pb-[72px] md:pb-0 lg:pl-72">
<!-- TopAppBar -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)]">
<button class="hover:opacity-80 transition-opacity lg:hidden" onclick="openDrawer()" type="button">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)]">RALIVA</h1>
<div class="flex items-center gap-sm">
<a href="{{ route('customer.search') }}" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="search">search</span>
</a>
<a href="{{ route('customer.chart') }}" class="relative hover:opacity-80 transition-opacity hidden md:flex">
<span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
<span class="absolute -top-1 -right-1 bg-secondary-fixed-dim text-on-secondary-fixed text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">2</span>
</a>
</div>
</header>
<!-- Main Content -->
<main class="pt-16">
<!-- Hero Section (editorial crossfade, RALIVA Home pictures) -->
<section class="relative w-full h-[78vh] min-h-[560px] max-h-[860px] overflow-hidden">
<div class="hero-photos absolute inset-0">
<img alt="RALIVA Home Editorial 1" src="{{ asset('storage/home-pictures/1.jfif') }}" class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000" data-hero-slide/>
<img alt="RALIVA Home Editorial 2" src="{{ asset('storage/home-pictures/2.jfif') }}" class="hero-slide absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" data-hero-slide/>
<img alt="RALIVA Home Editorial 3" src="{{ asset('storage/home-pictures/3.jfif') }}" class="hero-slide absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" data-hero-slide/>
</div>
<div class="absolute inset-0 bg-black/45"></div>
<div class="hero-content relative z-10 h-full flex flex-col justify-end items-center text-center px-container-margin pb-xl">
<span class="hero-reveal font-label-caps text-label-caps uppercase tracking-[0.22em] text-white/90 mb-sm">{{ __('NEW COLLECTION') }}</span>
<h2 class="hero-reveal font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-white mb-xs max-w-xl">{{ __('The Art of Everyday Dressing') }}</h2>
<p class="hero-reveal font-body-lg text-body-lg text-white/85 mb-md max-w-md">{{ __('Timeless looks, made for you.') }}</p>
<a href="{{ route('customer.shop') }}" class="hero-reveal btn-gold font-label-caps text-label-caps px-lg py-sm uppercase tracking-widest inline-block">{{ __('SHOP COLLECTION') }}</a>
</div>
</section>
<!-- Categories -->
<section class="py-lg border-b border-outline-variant">
<div class="flex overflow-x-auto no-scrollbar px-container-margin gap-sm pb-xs">
<button type="button" data-cat="All" onclick="selectHomeCategory(null)" class="home-cat-pill shrink-0 px-md py-xs border border-secondary text-secondary font-label-sm text-label-sm rounded-full bg-secondary/5">{{ __('Semua') }}</button>
<button type="button" data-cat="Women" onclick="selectHomeCategory('Women')" class="home-cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Women') }}</button>
<button type="button" data-cat="Men" onclick="selectHomeCategory('Men')" class="home-cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Men') }}</button>
<button type="button" data-cat="Accessories" onclick="selectHomeCategory('Accessories')" class="home-cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Accessories') }}</button>
<button type="button" data-cat="Shoes" onclick="selectHomeCategory('Shoes')" class="home-cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Shoes') }}</button>
<button type="button" data-cat="Bags" onclick="selectHomeCategory('Bags')" class="home-cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Bags') }}</button>
</div>
</section>
<!-- New Arrivals -->
<section class="py-xl px-container-margin reveal-up">
<div class="atl-eyebrow mb-xs">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('New Arrivals') }}</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-md">{{ __('The Latest from Our Ateliers') }}</h3>
<div id="new-arrivals-grid" class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
<!-- Product 1 -->
<div data-category="Women" class="relative flex flex-col group cursor-pointer">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden">
<img loading="lazy" decoding="async" class="object-cover w-full h-full group-hover:scale-[1.04] transition-transform duration-500" alt="A high-quality minimalist editorial product shot of a tailored linen blazer in a soft ivory shade." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPD5-Gnh3eTuUtU4T7JNWo5RRzeJvQHK9Ga-Qyub2VAxmLGZrXcu5eAhUHzglaK2leeCgs_S1rotd_qxAlW3J4__SdbjTf72VBHQzRpit8rbEixeyo2UKLpiBeBbgQfpUO8i83JOSeojGk4-pg0MhKw305uBjXfYyPk4JPteEhhs_SytMO40NERGkVHIbKNFaDIS4tZRo7KpphEGebXYRJRggcWTAf3NNm6pvcs8WOjecDptx1ZzQ"/>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Noiré Studio</span>
<h4 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">Tailored Linen Blazer</h4>
<span class="font-body-sm text-body-sm text-on-surface mt-1">$245.00</span>
</a>
<button type="button" aria-label="{{ __('Add to wishlist') }}" data-login-url="{{ route('login', ['redirect' => url()->current()]) }}" onclick="window.location.href=this.dataset.loginUrl" class="absolute top-2 right-2 p-2 rounded-full bg-black/15 backdrop-blur-sm text-white hover:bg-black/30 hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<!-- Product 2 -->
<div data-category="Bags" class="relative flex flex-col group cursor-pointer">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden">
<img loading="lazy" decoding="async" class="object-cover w-full h-full group-hover:scale-[1.04] transition-transform duration-500" alt="A sleek, minimalist editorial photograph of a black leather structured tote bag." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDotrquQ9ru5aXlWl5XbgLhEMJq3WBfo5DDEAS3Z-F5LnAIv27Q3259la3QLZghjnF5R8udNJqY0Toq6SHw5JvN3PqANThsUOvwujXixkrq5zZBH5OW_D3QTRD3qObufW5Uz2-ahDe36xdtDHuA8SK2Ldhp4wpMReozYAnqkNj5ZG3A37LwDOS6aXDnCEg_MNh_j2C1VKegB7PNMCwMV-jwzYAwrhuqG1UCGjQoSl3A0QRKO-gFHlQ"/>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lunara Fashion</span>
<h4 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">Structured Leather Tote</h4>
<span class="font-body-sm text-body-sm text-on-surface mt-1">$380.00</span>
</a>
<button type="button" aria-label="{{ __('Add to wishlist') }}" data-login-url="{{ route('login', ['redirect' => url()->current()]) }}" onclick="window.location.href=this.dataset.loginUrl" class="absolute top-2 right-2 p-2 rounded-full bg-black/15 backdrop-blur-sm text-white hover:bg-black/30 hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<!-- Product 3 -->
<div data-category="Women" class="relative flex flex-col group cursor-pointer">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden">
<img loading="lazy" decoding="async" class="object-cover w-full h-full group-hover:scale-[1.04] transition-transform duration-500" alt="A beautiful, clean editorial product shot of a minimalist silk slip dress in a muted olive tone." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrQWexD2Xms4d7-qplQNqqTI4EebkIxaCqpOssP3jfxkcDDAjBvE4kuCEgO-j-Yd-Vfxm6sW-zOaQShx89-kFo0JwvaQ9DnVYjw0ZeHlwNYQaWtigNJNUb1P2E3VS7jVbvb2gfkn5AgK0_pHzGjUiSO2kjiDWXbTKy2tRqRQq5I2md_UYdyHQR_axy07aFn3BeoVctJgri9jLNSSEizCJoXGSF5I0rX6QAaqkzanalXeH6sTmuLnA"/>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Maëva House</span>
<h4 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">Silk Slip Dress</h4>
<span class="font-body-sm text-body-sm text-on-surface mt-1">$195.00</span>
</a>
<button type="button" aria-label="{{ __('Add to wishlist') }}" data-login-url="{{ route('login', ['redirect' => url()->current()]) }}" onclick="window.location.href=this.dataset.loginUrl" class="absolute top-2 right-2 p-2 rounded-full bg-black/15 backdrop-blur-sm text-white hover:bg-black/30 hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<!-- Product 4 -->
<div data-category="Accessories" class="relative flex flex-col group cursor-pointer">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden">
<img loading="lazy" decoding="async" class="object-cover w-full h-full group-hover:scale-[1.04] transition-transform duration-500" alt="A pristine editorial shot of minimal, modern geometric gold hoop earrings resting on a white marble surface." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXqNhNFWMr-Gm8_uwAVgBbqtzcNdb5MAfQUsG_3GJbmE0gm167f27WLQY44QclgDSw7N_b2k0qpe9HdTKZlExYsZl6FJUCnKft0foIHP3pp3uFUAxnwrYM3o7ap46wCmmnSGAbNN-gDM_Kptg0bVNG6ghZhp7r3PeQ66ZD2yhgIMKhB9sSycHTa8yXBJ3fTbNvx2tH5SUu76da_WcZ3bJW7JeJmVuEnVOdIHENcwQB0a1sOCp-u_s"/>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kayana Apparel</span>
<h4 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">Geometric Gold Hoops</h4>
<span class="font-body-sm text-body-sm text-on-surface mt-1">$85.00</span>
</a>
<button type="button" aria-label="{{ __('Add to wishlist') }}" data-login-url="{{ route('login', ['redirect' => url()->current()]) }}" onclick="window.location.href=this.dataset.loginUrl" class="absolute top-2 right-2 p-2 rounded-full bg-black/15 backdrop-blur-sm text-white hover:bg-black/30 hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
</div>
<p id="new-arrivals-empty" class="hidden text-center text-on-surface-variant font-body-lg py-md">{{ __('No products in this category.') }}</p>
<div class="mt-md flex justify-center">
<a href="{{ route('customer.shop') }}" class="border border-secondary text-secondary bg-transparent font-label-caps text-label-caps px-lg py-sm uppercase tracking-widest hover:bg-secondary/5 transition-colors inline-block">{{ __('VIEW ALL NEW ARRIVALS') }}</a>
</div>
</section>
<!-- Featured Stores -->
<section class="py-xl bg-surface-container-low border-t border-b border-outline-variant reveal-up">
<div class="px-container-margin mb-md">
<div class="atl-eyebrow mb-xs">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Featured Stores') }}</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface">{{ __('Maisions We Love') }}</h3>
</div>
<div class="flex overflow-x-auto no-scrollbar lg:grid lg:grid-cols-4 lg:overflow-visible pl-container-margin pr-container-margin gap-md pb-xs snap-x snap-mandatory">
<!-- Store 1 -->
<a href="{{ route('customer.shop.store-detail', 1) }}" class="shrink-0 w-64 lg:w-auto cursor-pointer group snap-center">
<div class="aspect-video mb-xs bg-surface-container overflow-hidden">
<img loading="lazy" decoding="async" class="object-cover w-full h-full group-hover:scale-[1.04] transition-transform duration-500" alt="Lunara Fashion boutique exterior." src="https://lh3.googleusercontent.com/aida-public/AB6AXuATiKrXBx3vVfoNsTu1_JuFvfVqHhF9A63yLIFGC0hF5MVVUZB6Nu-eyjEa5IxqGiEpPzawhFtGfTAatsc-_9Pwi9D9AsVEO7TOOEszevnRdatfxPYIK7ZAvB0-Aa3R8CSQPOhV3EN9w5_S8sCYYX8NCMlAs_gD3RYhAkt91QyBdC8bmQs-v4yGHXrAH2KKGIPWNoi7jJqivQtsOliueGluswaKxAgwLxl1rh_aTfA_gB_LTeqw8oU"/>
</div>
<h4 class="font-title-md text-title-md text-on-surface group-hover:text-secondary transition-colors">Lunara Fashion</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Modern feminine silhouettes.') }}</p>
</a>
<!-- Store 2 -->
<a href="{{ route('customer.shop.store-detail', 2) }}" class="shrink-0 w-64 lg:w-auto cursor-pointer group snap-center">
<div class="aspect-video mb-xs bg-surface-container overflow-hidden">
<img loading="lazy" decoding="async" class="object-cover w-full h-full group-hover:scale-[1.04] transition-transform duration-500" alt="Noiré Studio interior." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDGwknduyLPGxjMRvTx7tN4JeGs-9IICVVEqumRS28Y9jTBxPfkTa9uV98aPjekXCG1uLxayAYmBwFZIIf73qfeWOcTQ6jI97GOQBVdIzBaAZhTlYEO8RKF_NsqCMXssspqoctKzP8RpOHtJI_bw-qZI1QF_fn1OH80mwa6ht1vSJY8vkFSZq_OBTROdz1TubDt_Y_Ax7quip7t8HNO7TkKNnLYOEFLbmjlpYvis2wIP6LJwYtpaNo"/>
</div>
<h4 class="font-title-md text-title-md text-on-surface group-hover:text-secondary transition-colors">Noiré Studio</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Tailored, stark minimalism.') }}</p>
</a>
<!-- Store 3 -->
<a href="{{ route('customer.shop.store-detail', 3) }}" class="shrink-0 w-64 lg:w-auto cursor-pointer group snap-center">
<div class="aspect-video mb-xs bg-surface-container overflow-hidden">
<img loading="lazy" decoding="async" class="object-cover w-full h-full group-hover:scale-[1.04] transition-transform duration-500" alt="Kayana Apparel lifestyle shot." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCSCazvaWZuccedNS2ILQbkHUJlNvuZ7i1_N2EHvjuBbo7CLD3CW8iHh-xOfNuHEsio3RxsEYKR2jEnuEUUOg9R7Xza1li0VetG6_yfhRrJs3dSULL6lG6fVDPX4qijbhNAokLUQ8tn673XhAZ-l8Vx3WZDIaxtdNLAHriglRfoPt6xRPff_qYINXAgslwYqW_xSQsAbEn2mjrBLNDh6NTT4t86gs2BbXDST-ewDyDYcbA5FZIEMUM"/>
</div>
<h4 class="font-title-md text-title-md text-on-surface group-hover:text-secondary transition-colors">Kayana Apparel</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Organic textures and flow.') }}</p>
</a>
<!-- Store 4 -->
<a href="{{ route('customer.shop.store-detail', 4) }}" class="shrink-0 w-64 lg:w-auto cursor-pointer group snap-center">
<div class="aspect-video mb-xs bg-surface-container overflow-hidden">
<img loading="lazy" decoding="async" class="object-cover w-full h-full group-hover:scale-[1.04] transition-transform duration-500" alt="Maëva House still-life." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBces6Xx741Ae5cYEDlbS_pgcHXZ9vEiOAGb5jvlBttKDyIRgUl6PUSDzKjI9nXu8X8Zb-RxuuplZY4dbVXDDBRqOVusLCAlczBCFDMM9qeGCl18jyL9AKeYbo_KYUolJQ-tUyLZ6kLqZFaQ2yKWY0Gs6ucQPlMm57RTWXBipH9At2Nbp1nWNEZDCqkafxCVNpFOE3MSCOi3nOPMbtk9_6tU4iBkCexkl7qGGFlVexn74kqDcVgzRc"/>
</div>
<h4 class="font-title-md text-title-md text-on-surface group-hover:text-secondary transition-colors">Maëva House</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Essential daily luxury.') }}</p>
</a>
</div>
</section>
<!-- Newsletter -->
<section class="py-xl px-container-margin border-b border-outline-variant reveal-up">
<div class="mx-auto max-w-[1200px] flex flex-col items-center text-center">
<div class="atl-eyebrow mb-xs justify-center">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Newsletter') }}</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-xs">{{ __('Be the first to know') }}</h3>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-md max-w-md">{{ __('Subscribe to our newsletter for exclusive collections and editorial insights.') }}</p>
<form class="w-full max-w-sm lg:max-w-lg flex flex-col lg:flex-row gap-sm" onsubmit="return false;">
<input class="w-full lg:flex-1 rounded-full border border-outline-variant bg-surface px-5 py-3 font-body-lg text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-secondary transition-colors" placeholder="{{ __('Email Address') }}" type="email"/>
<button class="btn-gold font-label-caps text-label-caps py-3 lg:px-xl rounded-full uppercase tracking-widest" type="submit">{{ __('SUBSCRIBE') }}</button>
</form>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-surface-bright py-xl px-container-margin md:pb-xl pb-32 reveal-up">
<div class="mx-auto max-w-[1200px]">
<div class="grid grid-cols-1 md:grid-cols-12 gap-xl mb-xl">
<div class="md:col-span-6">
<h4 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] mb-md">RALIVA</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant max-w-xs">{{ __('Curated fashion for the modern minimalist. Discover the art of everyday dressing.') }}</p>
</div>
<div class="md:col-span-3 flex flex-col gap-sm">
<h5 class="font-label-caps text-label-caps text-on-surface uppercase mb-xs">{{ __('Shop') }}</h5>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('customer.shop') }}">Women</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('customer.shop') }}">Men</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('customer.shop') }}">New Arrivals</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('customer.shop') }}">Designers</a>
</div>
<div class="md:col-span-3 flex flex-col gap-sm">
<h5 class="font-label-caps text-label-caps text-on-surface uppercase mb-xs">{{ __('Support') }}</h5>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('FAQ') }}</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('Shipping &amp; Returns') }}</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('Contact Us') }}</a>
</div>
</div>
<div class="border-t border-outline-variant pt-md flex flex-col md:flex-row justify-between items-center gap-sm">
<span class="font-body-sm text-body-sm text-on-surface-variant">© 2024 RALIVA. All rights reserved.</span>
<div class="flex gap-md">
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('Privacy Policy') }}</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('Terms of Service') }}</a>
</div>
</div>
</div>
</footer>
<script>
    var homeCategory = null;
    function selectHomeCategory(cat) {
        homeCategory = cat;
        applyHomeFilter();
        syncHomePills();
    }
    function applyHomeFilter() {
        var grid = document.getElementById('new-arrivals-grid');
        if (!grid) return;
        var cards = Array.prototype.slice.call(grid.children);
        var shown = 0;
        cards.forEach(function (c) {
            var show = !homeCategory || c.getAttribute('data-category') === homeCategory;
            c.style.display = show ? '' : 'none';
            if (show) shown++;
        });
        var empty = document.getElementById('new-arrivals-empty');
        if (empty) empty.classList.toggle('hidden', shown > 0);
    }
    function syncHomePills() {
        document.querySelectorAll('.home-cat-pill').forEach(function (b) {
            var on = (b.dataset.cat === homeCategory) || (homeCategory === null && b.dataset.cat === 'All');
            b.classList.toggle('border-secondary', on);
            b.classList.toggle('text-secondary', on);
            b.classList.toggle('bg-secondary/5', on);
            b.classList.toggle('border-outline-variant', !on);
            b.classList.toggle('text-on-surface-variant', !on);
        });
    }
    syncHomePills();

    /* Hero crossfade — matched to RALIVA Register editorial transition */
    (function () {
        var slides = document.querySelectorAll('[data-hero-slide]');
        if (slides.length < 2) return;
        var idx = 0;
        setInterval(function () {
            slides[idx].style.opacity = '0';
            idx = (idx + 1) % slides.length;
            slides[idx].style.opacity = '1';
        }, 4000);
    })();

    /* Subtle scroll reveal */
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
@include('customer._partials.bottom-nav')
@include('customer._partials.drawer')
</body></html>
