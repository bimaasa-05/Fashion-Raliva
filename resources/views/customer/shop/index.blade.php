<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Shop') }}</title>
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
                        "secondary-fixed": "#8B1E3F",
                        "on-primary": "#ffffff",
                        "surface-container-lowest": "#F8F6F2",
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
                        "background": "#F3F0EA",
                        "surface": "#F3F0EA",
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
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
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
    :root           { --btn-gold-bg: #8B1E3F; --btn-gold-text: #ffffff; }
    html.theme-dark { --btn-gold-bg: #6D1428; --btn-gold-text: #ffffff; }
    /* ============ SHOP FILTER CHECKBOX (same system as Register .terms-checkbox) ============ */
    .shop-checkbox {
        border-radius: 4px;
        transition: border-color .2s ease, background-color .2s ease;
    }
    .shop-checkbox:hover:not(:checked) { border-color: #8B1E3F; }
    .shop-checkbox:checked {
        background-color: #8B1E3F !important;
        border-color: #8B1E3F !important;
    }
    .shop-checkbox:focus-visible { box-shadow: 0 0 0 3px rgba(139,30,63,.3); }
    html.theme-dark .shop-checkbox {
        border-color: #3a3937;
        background-color: #201f1e;
    }
</style>
<style>
    /* ============ Shop: remap drawer + bottom-nav accent to burgundy (Register language) ============ */
    #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(139,30,30,.10); }
    html.theme-dark #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(163,38,38,.16); }
    .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    html.theme-dark .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    .bn-active { color: #8B1E3F !important; }
    html.theme-dark .bn-active { color: #8B1E3F !important; }
</style>
<style>
    /* ============ PREMIUM CARD + HEADING (style tiruan Super-Admin, aksen Burgundy) ============ */
    .card-premium {
        box-shadow: 0 1px 2px rgb(17 17 17 / 0.04), 0 12px 32px -16px rgb(17 17 17 / 0.16);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }
    .card-premium:hover {
        transform: translateY(-3px);
        box-shadow: 0 2px 4px rgb(17 17 17 / 0.05), 0 20px 48px -20px rgb(17 17 17 / 0.22);
        border-color: rgba(139, 30, 63, .45);   /* Burgundy, bukan gold */
    }
    html.theme-dark .card-premium {
        box-shadow: 0 1px 2px rgb(0 0 0 / 0.35), 0 12px 32px -16px rgb(0 0 0 / 0.55);
    }
    html.theme-dark .card-premium:hover {
        box-shadow: 0 2px 4px rgb(0 0 0 / 0.4), 0 20px 48px -20px rgb(0 0 0 / 0.7);
        border-color: rgba(139, 30, 63, .55);
    }

    .premium-heading::before {
        content: '';
        display: inline-block;
        width: 4px;
        height: 0.95em;
        margin-right: 0.65rem;
        background: #8B1E3F;                      /* Burgundy accent bar */
        border-radius: 9999px;
        vertical-align: -0.05em;
    }

    /* ============ ATELIER EYEBROW MARKER ============ */
    .atl-eyebrow { display: inline-flex; align-items: center; gap: .65rem; }
    .atl-eyebrow::before {
        content: '';
        width: 30px;
        height: 1px;
        background: var(--chrome-accent);
        opacity: .7;
    }
    /* ============ SHOP TOOLBAR (scoped: category nav + actions) ============ */
    .shop-action-btn {
        min-height: 40px;
        min-width: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9999px;
        transition: color .2s ease, border-color .2s ease, background-color .2s ease;
    }
    .shop-action-btn:hover { background-color: var(--chrome-hover); }
    .shop-sort-trigger { min-height: 40px; }
    /* ============ SHOP CONTENT CONTAINER (single product-area container, mirrors Wishlist card feel) ============ */
    .shop-content-container {
        background-color: #ffffff;
        border: 1px solid var(--border-soft);
        border-radius: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .shop-content-container:hover { border-color: rgba(139, 30, 63, .45); }
    /* Customer premium-heading: vertical burgundy accent bar (mirrors super-admin card-premium heading, NO gold) */
    .shop-content-heading {
        position: relative;
        padding-left: 0.85rem;
    }
    .shop-content-heading::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 1.1em;
        background: #8B1E3F;
        border-radius: 9999px;
    }
    html.theme-dark .shop-content-container {
        background-color: #1e1d1c;
        border-color: var(--border-soft);
    }
    html.theme-dark .shop-content-container:hover { border-color: rgba(139, 30, 63, .55); }
</style>
  </head>
<body class="bg-surface text-on-surface antialiased font-body-lg pb-[72px] md:pb-0 lg:pl-72">
<!-- TopAppBar -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)]">
<button aria-label="{{ __('Menu') }}" class="hover:opacity-80 transition-opacity lg:hidden flex items-center justify-center" onclick="openDrawer()" type="button">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)]">RALIVA</h1>
<div class="flex items-center gap-sm">
<a aria-label="Search" href="{{ route('customer.search') }}" class="hover:opacity-80 transition-opacity flex items-center justify-center">
<span class="material-symbols-outlined" data-icon="search">search</span>
</a>
</div>
</header>
<!-- Main Content -->
<main class="flex-grow w-full flex flex-col pt-16 pb-8 lg:pb-12 overflow-x-clip">
<!-- Canvas Area -->
<div class="flex-grow flex flex-col w-full">
<!-- Shop Toolbar (parent container: category navigation left, actions right) -->
<div class="shop-toolbar flex flex-row items-center gap-sm md:gap-md px-container-margin py-sm border-b border-outline-variant sticky top-16 lg:top-16 z-30" style="background-color: var(--surface-ivory);">
    <!-- Category Navigation (scrolls horizontally on small screens) -->
    <div class="shop-category-nav flex-1 min-w-0 flex items-center gap-sm overflow-x-auto hide-scrollbar -mx-container-margin px-container-margin md:mx-0 md:px-0">
        <button type="button" data-cat="All" onclick="selectCategory(null)" class="cat-pill shrink-0 px-md py-xs border border-secondary text-secondary font-label-sm text-label-sm rounded-full bg-secondary/5">{{ __('All') }}</button>
        <button type="button" data-cat="Women" onclick="selectCategory('Women')" class="cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Women') }}</button>
        <button type="button" data-cat="Men" onclick="selectCategory('Men')" class="cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Men') }}</button>
        <button type="button" data-cat="Accessories" onclick="selectCategory('Accessories')" class="cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Accessories') }}</button>
        <button type="button" data-cat="Shoes" onclick="selectCategory('Shoes')" class="cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Shoes') }}</button>
        <button type="button" data-cat="Bags" onclick="selectCategory('Bags')" class="cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-secondary hover:text-secondary transition-colors">{{ __('Bags') }}</button>
    </div>
    <!-- Shop Actions (Filter · Cart · Sort) -->
    <div class="shop-actions ml-auto flex items-center gap-xs md:gap-sm shrink-0">
        <button aria-label="{{ __('Filter') }}" class="shop-action-btn order-3 border border-outline-variant hover:text-secondary hover:border-secondary transition-colors relative" onclick="openFilter()" type="button">
            <span class="material-symbols-outlined text-[18px]" data-icon="tune">tune</span>
            <span id="filter-badge" class="absolute -top-1 -right-1.5 bg-secondary text-on-secondary text-[10px] min-w-4 h-4 px-1 rounded-full flex items-center justify-center font-bold hidden">0</span>
        </button>
        <a aria-label="{{ __('Cart') }}" href="{{ route('customer.chart') }}" class="shop-action-btn relative order-2 border border-outline-variant hover:text-secondary hover:border-secondary transition-colors">
            <span class="material-symbols-outlined text-[22px]" data-icon="shopping_cart">shopping_cart</span>
            <span class="absolute -top-1 -right-1.5 bg-secondary-fixed-dim text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">2</span>
        </a>
        <div class="relative shop-sort-trigger order-1" id="sort-menu-container">
            <button class="shop-action-btn px-md gap-1 border border-outline-variant font-label-sm text-label-sm text-on-surface hover:text-secondary hover:border-secondary transition-colors" onclick="toggleSortMenu()" type="button">
                <span id="sort-label">{{ __('Sort') }}</span>
                <span class="material-symbols-outlined text-[16px] transition-transform duration-200" data-icon="expand_more" id="sort-chevron">expand_more</span>
            </button>
            <div id="sort-menu" class="absolute right-0 top-full mt-xs w-56 bg-surface rounded-lg border border-outline-variant shadow-xl z-20 py-xs origin-top-right transition-all duration-200 ease-out invisible opacity-0 scale-95 -translate-y-1">
                <p class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest px-md pt-xs pb-sm">{{ __('Sort By') }}</p>
                <button class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors" data-sort="Newest" onclick="selectSort('Newest')" type="button">
                    <span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">schedule</span>{{ __('Newest') }}</span>
                    <span class="material-symbols-outlined text-[18px] text-secondary sort-check">check</span>
                </button>
                <button class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors" data-sort="Price: Low to High" onclick="selectSort('Price: Low to High')" type="button">
                    <span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">arrow_upward</span>{{ __('Price: Low to High') }}</span>
                    <span class="material-symbols-outlined text-[18px] text-secondary sort-check invisible">check</span>
                </button>
                <button class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors" data-sort="Price: High to Low" onclick="selectSort('Price: High to Low')" type="button">
                    <span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">arrow_downward</span>{{ __('Price: High to Low') }}</span>
                    <span class="material-symbols-outlined text-[18px] text-secondary sort-check invisible">check</span>
                </button>
                <button class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors" data-sort="Popular" onclick="selectSort('Popular')" type="button">
                    <span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">local_fire_department</span>{{ __('Popular') }}</span>
                    <span class="material-symbols-outlined text-[18px] text-secondary sort-check invisible">check</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Active Filter Chips -->
<div id="active-chips" class="px-container-margin py-sm border-b border-outline-variant flex flex-wrap gap-sm items-center min-h-[2.75rem] opacity-0 pointer-events-none border-transparent transition-opacity duration-200">
<div id="chips-list" class="flex flex-wrap gap-sm items-center grow"></div>
<button id="clear-all" class="font-label-sm text-label-sm text-secondary underline hover:opacity-80 transition-opacity shrink-0" onclick="clearAll()" type="button">{{ __('Clear all') }}</button>
</div>
<!-- Shop Content Container -->
<div class="mx-auto max-w-[1400px] px-container-margin py-xl">
<div class="shop-content-container bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
<!-- Shop Header -->
<div class="flex items-center justify-between gap-md mb-md flex-wrap">
<div class="atl-eyebrow">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary shop-content-heading">{{ __('Shop') }}</span>
</div>
<div class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Showing') }} <span id="result-count">0</span> {{ __('items') }}</div>
</div>
<div id="product-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-gutter" style="background-color: transparent;">
<!-- Product 1 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative w-full aspect-[3/4] bg-surface-container mb-sm overflow-hidden rounded">
<img class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" data-alt="A full-length editorial shot of a model wearing high-end minimal straight fit trousers in a sophisticated ivory tone. The lighting is soft and natural, evoking a premium fashion lookbook style. The background is a stark, bright studio setting to emphasize the clean lines and texture of the fabric." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAps2M8arrWbQY6jXAaISjDMblJoS3se1hpcmHWepeH6VczwS5VPkR4AM-pXm-ncoDRs1Nvlc-uTUq0Njoh538e4U4gtMAG0OyE3mOcGJPaz0g4fpCbTiNUVrBR12VzliXLH0tih4PCW3xl2DSpKGC_xkQZAyXSyn5W9SfOUfPKBcD0MUHvDTvlix7j3UEroZX7lXoveWhsxMc0B1clCXYWJ-5Mct8SR210aTaGAxBrtYJbIinXAiU"/>
<button aria-label="{{ __('Add to wishlist') }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant">RALIVA ESSENTIALS</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Straight Fit Pants</h3>
<span class="font-body-sm text-body-sm text-on-surface">Rp 329.000</span>
</div>
</a>
<!-- Product 2 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative w-full aspect-[3/4] bg-surface-container mb-sm overflow-hidden rounded">
<img class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" data-alt="A premium fashion editorial image featuring a relaxed fit blazer in a muted earthy tone, worn by a model in a high-end minimalist setting. The lighting is diffused, highlighting the drape and structure of the garment. The overall aesthetic is clean, sophisticated, and modern." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAax1OhxvSc1htL3J-oZrJsK06nXoqeC7N_pmJWtnMwexPZmJABVpA8hgsW7QimiCrKDbAbF2QZJZqX32JY1O-0BxXFuuSE5FkP0xonQAuzISb3yAK3r-YD1svUl5LmSg6Rdn_vJ_617kZ_uA83kwaYo-0divU3t_vq5baQRi1RcPOEZ4sCHHcfr_xsvzeGRThANll5NUxqWpFBAnjwzb4kGdK_w_CN6OXuOOTYMiLbt9ADnrPJAKA"/>
<button aria-label="{{ __('Add to wishlist') }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant">RALIVA STUDIO</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Relaxed Blazer</h3>
<span class="font-body-sm text-body-sm text-on-surface">Rp 579.000</span>
</div>
</a>
<!-- Product 3 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative w-full aspect-[3/4] bg-surface-container mb-sm overflow-hidden rounded">
<img class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" data-alt="A striking close-up shot of a pleated midi skirt in motion, capturing the elegant flow of the fabric. The color is a soft, warm neutral. The studio lighting casts subtle shadows to emphasize the pleats. The mood is refined and distinctly high-fashion." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAumPepE3uIJ6AwAkmTZ9_-YsAJidBJhhtUl1zj0Gr1TL0xi50_O8B6t0Y-QrwVzrsGu6V9Ez0WWnJAMSroGzu5A9ZFd9BMdxY9fo9n62z5gEI_137Qx8UGHVAMXBxep6FQ7LwfN5GDvsLBShloSY7SE5-bycdtXhqUHyAWcA4B36P_xx4H5ldRuNR76fo3XUMsW3b0Mh-XLL12XFCmtO-5LE3uGUVWagT2xjawnzMa4frmfrKE-SU"/>
<button aria-label="{{ __('Add to wishlist') }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant">RALIVA STUDIO</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Pleated Midi Skirt</h3>
<span class="font-body-sm text-body-sm text-on-surface">Rp 380.000</span>
</div>
</a>
<!-- Product 4 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative w-full aspect-[3/4] bg-surface-container mb-sm overflow-hidden rounded">
<img class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-500" data-alt="An editorial fashion photograph of a crisp linen blend shirt in pristine white. The shirt is styled simply on a model against a minimalist, warm grey background. Natural light illuminates the breathable texture of the linen, creating a serene, luxury aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBFDVvGhk1fRmFqr2msVELhThiwYZS_qaw5B2sFlpG_oUABmy2HUkfTdsOGgu3QoYvDiwgRG62hQu2-iz4wze0Jgt10LNVpzeMWtp5JvLJ0s1T3mW9YBzf7XWv2f73BU_Dp8smVo8FG7viGA4YrJKUSEmOB9PLKo12---_uuSNV455LZytF66bBcFn8pdC4HPxE7imenZu4rcnccn6PDK8lreskykX-dBeOyaljMak73QzcCv8e4no"/>
<button aria-label="{{ __('Add to wishlist') }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant">RALIVA ESSENTIALS</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Linen Blend Shirt</h3>
<span class="font-body-sm text-body-sm text-on-surface">Rp 299.000</span>
</div>
</a>
</div>
<!-- Empty State (shown when no products match the selected filters) -->
<div id="product-empty" class="hidden w-full flex-col items-center justify-center text-center gap-md py-2xl">
    <span class="material-symbols-outlined text-[72px] text-on-surface-variant/40" data-icon="inventory_2">inventory_2</span>
    <p class="font-body-lg text-body-lg text-on-surface-variant">{{ __('No products found for this selection.') }}</p>
    <button class="btn-gold font-label-caps text-label-caps px-lg py-3 lg:px-xl rounded-full uppercase tracking-widest mt-xs" type="button" onclick="selectCategory(null)">{{ __('Reset filters') }}</button>
</div>
<div id="load-more-wrap" class="flex justify-center py-xl mt-md">
<button class="btn-gold font-label-caps text-label-caps px-lg py-3 lg:px-xl rounded-full uppercase tracking-widest" type="button">
                    {{ __('LOAD MORE') }}
                </button>
</div>
</div>
</div>
</div>
</main>
<!-- BottomNavBar -->
@include('customer._partials.bottom-nav')
@include('customer._partials.drawer')
{{-- FILTER BOTTOM SHEET --}}
<div id="filter-overlay" class="fixed inset-0 lg:left-72 bg-black/50 z-[60] hidden" onclick="closeFilter()"></div>
<div id="filter-sheet" class="fixed bottom-0 inset-x-0 lg:left-72 z-[70] bg-surface rounded-t-2xl translate-y-full transition-transform duration-300 max-h-[85vh] flex flex-col">
<div class="flex justify-center pt-sm shrink-0">
<span class="w-10 h-1 rounded-full bg-outline-variant"></span>
</div>
<div class="flex justify-between items-center px-container-margin py-sm border-b border-outline-variant shrink-0">
<h2 class="font-headline-md text-headline-md uppercase tracking-widest">{{ __('Filters') }} <span id="applied-count" class="normal-case tracking-normal text-body-sm font-body-sm text-on-surface-variant"></span></h2>
<button aria-label="{{ __('Close filters') }}" class="hover:opacity-80 transition-opacity flex" onclick="closeFilter()" type="button">
<span class="material-symbols-outlined" data-icon="close">close</span>
</button>
</div>
<div class="overflow-y-auto px-container-margin pb-md grow">
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest pt-lg pb-sm">{{ __('Category') }}</h3>
<div class="grid grid-cols-2 gap-sm">
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4 shop-checkbox" type="checkbox"/><span class="font-body-sm text-body-sm">Women</span></label>
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4 shop-checkbox" type="checkbox"/><span class="font-body-sm text-body-sm">Men</span></label>
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4 shop-checkbox" type="checkbox"/><span class="font-body-sm text-body-sm">Accessories</span></label>
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4 shop-checkbox" type="checkbox"/><span class="font-body-sm text-body-sm">Shoes</span></label>
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4 shop-checkbox" type="checkbox"/><span class="font-body-sm text-body-sm">Bags</span></label>
</div>
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest pt-lg pb-sm">{{ __('Size') }}</h3>
<div class="flex flex-wrap gap-sm">
<button data-type="size" class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">XS</button>
<button data-type="size" class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">S</button>
<button data-type="size" class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">M</button>
<button data-type="size" class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">L</button>
<button data-type="size" class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">XL</button>
</div>
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest pt-lg pb-sm">{{ __('Color') }}</h3>
<div class="flex flex-wrap gap-md">
<button data-type="color" aria-label="Black" class="f-opt w-8 h-8 rounded-full bg-[#111111] border border-outline-variant transition-shadow" onclick="toggleSel(this)" type="button"></button>
<button data-type="color" aria-label="White" class="f-opt w-8 h-8 rounded-full bg-[#FFFFFF] border border-outline-variant transition-shadow" onclick="toggleSel(this)" type="button"></button>
<button data-type="color" aria-label="Beige" class="f-opt w-8 h-8 rounded-full bg-[#E5DCC5] border border-outline-variant transition-shadow" onclick="toggleSel(this)" type="button"></button>
<button data-type="color" aria-label="Brown" class="f-opt w-8 h-8 rounded-full bg-[#6B4F3A] border border-outline-variant transition-shadow" onclick="toggleSel(this)" type="button"></button>
<button data-type="color" aria-label="Gold" class="f-opt w-8 h-8 rounded-full bg-[#D4AF37] border border-outline-variant transition-shadow" onclick="toggleSel(this)" type="button"></button>
</div>
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest pt-lg pb-sm">{{ __('Price Range') }}</h3>
<div class="flex items-end gap-gutter">
<div class="flex-1">
<label class="font-label-sm text-label-sm text-on-surface-variant block mb-xs" for="price-min">{{ __('Min') }}</label>
<input class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-sm text-body-sm focus:outline-none focus:ring-0 focus:border-secondary transition-colors" id="price-min" inputmode="numeric" placeholder="Rp 0"/>
</div>
<span class="text-on-surface-variant pb-sm">—</span>
<div class="flex-1">
<label class="font-label-sm text-label-sm text-on-surface-variant block mb-xs" for="price-max">{{ __('Max') }}</label>
<input class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-sm text-body-sm focus:outline-none focus:ring-0 focus:border-secondary transition-colors" id="price-max" inputmode="numeric" placeholder="Rp 1.000.000"/>
</div>
</div>
</div>
<div class="flex gap-gutter px-container-margin py-md border-t border-outline-variant shrink-0">
<button class="flex-1 h-12 border border-secondary text-secondary font-label-caps text-label-caps tracking-widest hover:bg-secondary/5 transition-colors" onclick="resetFilters()" type="button">{{ __('RESET') }}</button>
<button class="btn-gold flex-1 h-12 font-label-caps text-label-caps tracking-widest" onclick="applyFilters()" type="button">{{ __('APPLY') }}</button>
</div>
</div>
    <script>
        var activeFilters = { category: [], size: [], color: [], price: { min: null, max: null } };
        var currentSort = 'Newest';

        function openFilter() {
            document.getElementById('filter-sheet').classList.remove('translate-y-full');
            document.getElementById('filter-overlay').classList.remove('hidden');
        }
        function closeFilter() {
            document.getElementById('filter-sheet').classList.add('translate-y-full');
            document.getElementById('filter-overlay').classList.add('hidden');
        }
        function gatherFilters() {
            activeFilters.category = [];
            document.querySelectorAll('#filter-sheet input[type="checkbox"]').forEach(function (cb) {
                if (cb.checked) activeFilters.category.push(cb.parentElement.textContent.trim());
            });
            activeFilters.size = [];
            document.querySelectorAll('#filter-sheet .f-opt[data-type="size"]').forEach(function (b) {
                if (b.classList.contains('bg-secondary')) activeFilters.size.push(b.textContent.trim());
            });
            activeFilters.color = [];
            document.querySelectorAll('#filter-sheet .f-opt[data-type="color"]').forEach(function (b) {
                if (b.classList.contains('ring-2')) activeFilters.color.push(b.getAttribute('aria-label'));
            });
            var pmin = document.getElementById('price-min').value.replace(/\D/g, '');
            var pmax = document.getElementById('price-max').value.replace(/\D/g, '');
            activeFilters.price.min = pmin ? parseInt(pmin, 10) : null;
            activeFilters.price.max = pmax ? parseInt(pmax, 10) : null;
        }
        function countActive() {
            var n = activeFilters.category.length + activeFilters.size.length + activeFilters.color.length;
            if (activeFilters.price.min !== null || activeFilters.price.max !== null) n++;
            return n;
        }
        function applyFilters() {
            gatherFilters();
            renderChips();
            updateBadge();
            updateAppliedLabel();
            applyGridFilter();
            syncCategoryBar();
            closeFilter();
        }
        function selectCategory(cat) {
            if (cat === null) {
                activeFilters.category = [];
            } else {
                activeFilters.category = [cat];
            }
            document.querySelectorAll('#filter-sheet input[type="checkbox"]').forEach(function (cb) {
                cb.checked = cb.parentElement.textContent.trim() === cat;
            });
            syncCategoryBar();
            renderChips();
            updateBadge();
            updateAppliedLabel();
            applyGridFilter();
        }
        function syncCategoryBar() {
            var selected = activeFilters.category.length === 1 ? activeFilters.category[0] : null;
            document.querySelectorAll('.cat-pill').forEach(function (b) {
                var on = b.dataset.cat === selected || (selected === null && b.dataset.cat === 'All');
                b.classList.toggle('border-secondary', on);
                b.classList.toggle('text-secondary', on);
                b.classList.toggle('bg-secondary/5', on);
                b.classList.toggle('border-outline-variant', !on);
                b.classList.toggle('text-on-surface-variant', !on);
            });
            document.querySelectorAll('.cat-tab').forEach(function (b) {
                var on = b.dataset.cat === selected || (selected === null && b.dataset.cat === 'All');
                b.classList.toggle('text-secondary', on);
                b.classList.toggle('border-secondary', on);
                b.classList.toggle('border-b-2', on);
                b.classList.toggle('text-on-surface-variant', !on);
            });
        }
        function resetFilters() {
            document.querySelectorAll('#filter-sheet input[type="checkbox"]').forEach(function (cb) { cb.checked = false; });
            document.querySelectorAll('#filter-sheet .f-opt').forEach(function (el) {
                el.classList.remove('ring-2', 'ring-secondary', 'ring-offset-2', 'bg-secondary', 'text-on-secondary', 'border-secondary');
            });
            document.getElementById('price-min').value = '';
            document.getElementById('price-max').value = '';
        }
        function renderChips() {
            var list = document.getElementById('chips-list');
            list.innerHTML = '';
            function addChip(label, type, val) {
                var chip = document.createElement('span');
                chip.className = 'inline-flex items-center gap-1 pl-md pr-2 py-1 bg-surface-container-low border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface';
                var txt = document.createElement('span');
                txt.textContent = label;
                chip.appendChild(txt);
                var x = document.createElement('button');
                x.type = 'button';
                x.setAttribute('aria-label', 'Remove ' + label);
                x.className = 'hover:text-secondary transition-colors flex items-center';
                x.innerHTML = '<span class="material-symbols-outlined text-[16px]">close</span>';
                x.onclick = function () { removeFilter(type, val); };
                chip.appendChild(x);
                list.appendChild(chip);
            }
            activeFilters.category.forEach(function (v) { addChip(v, 'category', v); });
            activeFilters.size.forEach(function (v) { addChip(v, 'size', v); });
            activeFilters.color.forEach(function (v) { addChip(v, 'color', v); });
            if (activeFilters.price.min !== null || activeFilters.price.max !== null) {
                var pmin = activeFilters.price.min !== null ? 'Rp ' + activeFilters.price.min.toLocaleString('id-ID') : 'Rp 0';
                var pmax = activeFilters.price.max !== null ? 'Rp ' + activeFilters.price.max.toLocaleString('id-ID') : '∞';
                addChip(pmin + ' – ' + pmax, 'price', 'price');
            }
            var ac = document.getElementById('active-chips');
            var hasChips = countActive() > 0;
            ac.classList.toggle('opacity-0', !hasChips);
            ac.classList.toggle('pointer-events-none', !hasChips);
            ac.classList.toggle('border-transparent', !hasChips);
        }
        function removeFilter(type, val) {
            if (type === 'category') {
                document.querySelectorAll('#filter-sheet input[type="checkbox"]').forEach(function (cb) {
                    if (cb.parentElement.textContent.trim() === val) cb.checked = false;
                });
            } else if (type === 'size') {
                document.querySelectorAll('#filter-sheet .f-opt[data-type="size"]').forEach(function (b) {
                    if (b.textContent.trim() === val) b.classList.remove('bg-secondary', 'text-on-secondary', 'border-secondary', 'ring-2', 'ring-secondary');
                });
            } else if (type === 'color') {
                document.querySelectorAll('#filter-sheet .f-opt[data-type="color"]').forEach(function (b) {
                    if (b.getAttribute('aria-label') === val) b.classList.remove('ring-2', 'ring-secondary', 'ring-offset-2');
                });
            } else if (type === 'price') {
                document.getElementById('price-min').value = '';
                document.getElementById('price-max').value = '';
            }
            gatherFilters();
            renderChips();
            updateBadge();
            updateAppliedLabel();
            applyGridFilter();
            syncCategoryBar();
        }
        function clearAll() {
            resetFilters();
            gatherFilters();
            renderChips();
            updateBadge();
            updateAppliedLabel();
            applyGridFilter();
            syncCategoryBar();
        }
        function updateBadge() {
            var n = countActive();
            var badge = document.getElementById('filter-badge');
            badge.textContent = n;
            badge.classList.toggle('hidden', n === 0);
        }
        function updateAppliedLabel() {
            var n = countActive();
            document.getElementById('applied-count').textContent = n > 0 ? '· ' + n + ' Applied' : '';
        }
        function applyGridFilter() {
            var cards = document.querySelectorAll('#product-grid > a');
            var shown = 0;
            cards.forEach(function (card) {
                var cat = (card.getAttribute('data-category') || '').split(' ');
                var sizes = (card.getAttribute('data-size') || '').split(' ');
                var colors = (card.getAttribute('data-color') || '').split(' ');
                var price = parseInt(card.getAttribute('data-price'), 10) || 0;
                var ok = true;
                if (activeFilters.category.length && !activeFilters.category.some(function (c) { return cat.indexOf(c) >= 0; })) ok = false;
                if (activeFilters.size.length && !activeFilters.size.some(function (s) { return sizes.indexOf(s) >= 0; })) ok = false;
                if (activeFilters.color.length && !activeFilters.color.some(function (c) { return colors.indexOf(c) >= 0; })) ok = false;
                if (activeFilters.price.min !== null && price < activeFilters.price.min) ok = false;
                if (activeFilters.price.max !== null && price > activeFilters.price.max) ok = false;
                card.style.display = ok ? '' : 'none';
                if (ok) shown++;
            });
            var countEl = document.getElementById('result-count');
            if (countEl) countEl.textContent = shown;
            var emptyEl = document.getElementById('product-empty');
            if (emptyEl) {
                emptyEl.classList.toggle('hidden', shown > 0);
                emptyEl.classList.toggle('flex', shown === 0);
            }
            var loadMoreEl = document.getElementById('load-more-wrap');
            if (loadMoreEl) loadMoreEl.classList.toggle('hidden', shown === 0);
        }
        function applySort() {
            var grid = document.getElementById('product-grid');
            if (!grid) return;
            var cards = Array.prototype.slice.call(grid.children);
            cards.sort(function (a, b) {
                if (currentSort === 'Price: Low to High') return (parseInt(a.dataset.price, 10) || 0) - (parseInt(b.dataset.price, 10) || 0);
                if (currentSort === 'Price: High to Low') return (parseInt(b.dataset.price, 10) || 0) - (parseInt(a.dataset.price, 10) || 0);
                if (currentSort === 'Popular') return (parseInt(b.dataset.popular, 10) || 0) - (parseInt(a.dataset.popular, 10) || 0);
                return (parseInt(b.dataset.created, 10) || 0) - (parseInt(a.dataset.created, 10) || 0);
            });
            cards.forEach(function (c) { grid.appendChild(c); });
            applyGridFilter();
        }
        function toggleSel(el) {
            if (el.dataset.type === 'size') {
                el.classList.toggle('bg-secondary');
                el.classList.toggle('text-on-secondary');
                el.classList.toggle('border-secondary');
                el.classList.toggle('ring-2');
                el.classList.toggle('ring-secondary');
            } else if (el.dataset.type === 'color') {
                el.classList.toggle('ring-2');
                el.classList.toggle('ring-secondary');
                el.classList.toggle('ring-offset-2');
            }
        }
        function toggleSortMenu() {
            var menu = document.getElementById('sort-menu');
            if (menu.classList.contains('invisible')) {
                menu.classList.remove('invisible', 'opacity-0', 'scale-95', '-translate-y-1');
                document.getElementById('sort-chevron').classList.add('rotate-180');
            } else {
                closeSortMenu();
            }
        }
        function closeSortMenu() {
            document.getElementById('sort-menu').classList.add('invisible', 'opacity-0', 'scale-95', '-translate-y-1');
            document.getElementById('sort-chevron').classList.remove('rotate-180');
        }
        function selectSort(label) {
            currentSort = label;
            document.getElementById('sort-label').textContent = label;
            document.querySelectorAll('#sort-menu [data-sort]').forEach(function (btn) {
                var check = btn.querySelector('.sort-check');
                if (btn.dataset.sort === label) {
                    check.classList.remove('invisible');
                    btn.classList.add('font-semibold');
                } else {
                    check.classList.add('invisible');
                    btn.classList.remove('font-semibold');
                }
            });
            applySort();
            closeSortMenu();
        }
        document.addEventListener('click', function (e) {
            var container = document.getElementById('sort-menu-container');
            if (container && !container.contains(e.target)) {
                closeSortMenu();
            }
        });
        (function initShop() {
            var productData = [
                { category: 'Women', size: 'S M L', color: 'Beige Brown', price: 329000, created: 4, popular: 2 },
                { category: 'Women', size: 'S M', color: 'Brown', price: 579000, created: 3, popular: 4 },
                { category: 'Women', size: 'XS S M', color: 'Beige', price: 380000, created: 2, popular: 3 },
                { category: 'Men', size: 'M L XL', color: 'White', price: 299000, created: 1, popular: 1 }
            ];
            var cards = document.querySelectorAll('#product-grid > a');
            cards.forEach(function (card, i) {
                var d = productData[i];
                if (!d) return;
                card.setAttribute('data-category', d.category);
                card.setAttribute('data-size', d.size);
                card.setAttribute('data-color', d.color);
                card.setAttribute('data-price', d.price);
                card.setAttribute('data-created', d.created);
                card.setAttribute('data-popular', d.popular);
            });
            applySort();
            renderChips();
            updateBadge();
            updateAppliedLabel();
            syncCategoryBar();
        })();
        document.querySelectorAll('.btn-gold').forEach(function (b) {
            b.addEventListener('click', function () {
                b.classList.remove('flashing');
                void b.offsetWidth;
                b.classList.add('flashing');
                setTimeout(function () { b.classList.remove('flashing'); }, 600);
            });
        });
    </script>
</body></html>