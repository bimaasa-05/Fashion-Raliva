<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
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
                        "secondary": "#795905",
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
                        "secondary-fixed-dim": "#ebc168",
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
        --chrome-accent: #795905;
    }
    html.theme-dark {
        --chrome-bg: #1c1b1b;
        --chrome-bg-soft: rgba(28,27,27,.9);
        --chrome-text: #ffffff;
        --chrome-text-dim: rgba(255,255,255,.6);
        --chrome-text-faint: rgba(255,255,255,.5);
        --chrome-border: rgba(255,255,255,.1);
        --chrome-hover: rgba(255,255,255,.1);
        --chrome-accent: #ebc168;
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
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
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
    html.theme-dark .text-secondary { color: #ebc168 !important; }
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
    html.theme-dark .hover\:text-secondary:hover { color: #ebc168 !important; }
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
  </head>
<body class="bg-surface text-on-surface antialiased font-body-lg pb-[72px] md:pb-0 lg:pl-72">
<!-- TopAppBar -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)] flat no shadows">
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
<!-- Hero Section -->
<section class="relative w-full h-[618px] min-h-[500px]">
<div class="absolute inset-0 bg-cover bg-center w-full h-full" data-alt="A striking editorial fashion photograph of a model in a minimalist, light-filled studio. She wears a sophisticated, structured beige trench coat over a crisp white shirt, exuding luxury and high-end styling. The lighting is soft and natural, casting delicate shadows that emphasize the texture of the garments. The overall aesthetic is clean, timeless, and perfectly aligned with a premium, minimalist light-mode fashion brand identity." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCa_tLb2w-HbYA0FJXTWvi2fi1lP1XFac_SANUcdrAFd8IjyMz_faith_rIbXRnr1dyQMR5_gXgVVPxqBFzroKfkGz_YDcPs2cYBNaXcLY55MwzONcYSSyveSZlXlNs-DGhvmpt61mzXC3O0bkPiiGQ7Sg0LYjKOE7BZZuFPRLqdSBQvDkctF8UJvD8XgAX8ASSRyez4WoYiqzzdAJPtsF0BtCngo-sAh-5HSrwBznKVACNyk2BVG8')"></div>
<div class="absolute inset-0 bg-gradient-to-t from-surface/80 to-transparent"></div>
<div class="absolute bottom-0 left-0 w-full p-container-margin flex flex-col items-center text-center pb-xl">
<span class="font-label-caps text-label-caps text-on-surface mb-sm tracking-widest uppercase">{{ __('NEW COLLECTION') }}</span>
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-xs">{{ __('The Art of Everyday Dressing') }}</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-md max-w-md">{{ __('Timeless looks, made for you.') }}</p>
<a href="{{ route('customer.shop') }}" class="bg-primary text-on-primary font-label-caps text-label-caps px-lg py-sm uppercase tracking-widest hover:opacity-90 transition-opacity inline-block">SHOP COLLECTION</a>
</div>
</section>
<!-- Categories -->
<section class="py-lg border-b border-outline-variant">
<div class="flex overflow-x-auto no-scrollbar px-container-margin gap-sm pb-xs">
<a href="{{ route('customer.shop') }}" class="shrink-0 px-md py-xs border border-primary text-primary font-label-sm text-label-sm rounded-full bg-primary/5">Women</a>
<a href="{{ route('customer.shop') }}" class="shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-primary hover:text-primary transition-colors">Men</a>
<a href="{{ route('customer.shop') }}" class="shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-primary hover:text-primary transition-colors">Accessories</a>
<a href="{{ route('customer.shop') }}" class="shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-primary hover:text-primary transition-colors">Shoes</a>
<a href="{{ route('customer.shop') }}" class="shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-primary hover:text-primary transition-colors">Bags</a>
</div>
</section>
<!-- New Arrivals -->
<section class="py-xl px-container-margin">
<h3 class="font-title-md text-title-md text-on-surface mb-md">{{ __('New Arrivals') }}</h3>
<div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
<!-- Product 1 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden">
<img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="A high-quality minimalist editorial product shot of a tailored linen blazer in a soft ivory shade. The garment is photographed flat or on an invisible mannequin against a pristine white background. Lighting is sharp and professional, highlighting the woven texture and precise stitching. The aesthetic is clean, luxury, and perfectly suited for a light-mode premium fashion marketplace." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPD5-Gnh3eTuUtU4T7JNWo5RRzeJvQHK9Ga-Qyub2VAxmLGZrXcu5eAhUHzglaK2leeCgs_S1rotd_qxAlW3J4__SdbjTf72VBHQzRpit8rbEixeyo2UKLpiBeBbgQfpUO8i83JOSeojGk4-pg0MhKw305uBjXfYyPk4JPteEhhs_SytMO40NERGkVHIbKNFaDIS4tZRo7KpphEGebXYRJRggcWTAf3NNm6pvcs8WOjecDptx1ZzQ"/>
<a aria-label="Add to wishlist" href="{{ route('customer.login', ['redirect' => url()->current()]) }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</a>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Noiré Studio</span>
<h4 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">Tailored Linen Blazer</h4>
<span class="font-body-sm text-body-sm text-on-surface mt-1">$245.00</span>
</a>
<!-- Product 2 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden">
<img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="A sleek, minimalist editorial photograph of a black leather structured tote bag. Photographed under bright, soft studio lighting against an off-white background, emphasizing the smooth, high-grade leather texture and fine silver hardware details. The presentation is premium, austere, and perfectly matches a high-end luxury fashion boutique's light-mode aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDotrquQ9ru5aXlWl5XbgLhEMJq3WBfo5DDEAS3Z-F5LnAIv27Q3259la3QLZghjnF5R8udNJqY0Toq6SHw5JvN3PqANThsUOvwujXixkrq5zZBH5OW_D3QTRD3qObufW5Uz2-ahDe36xdtDHuA8SK2Ldhp4wpMReozYAnqkNj5ZG3A37LwDOS6aXDnCEg_MNh_j2C1VKegB7PNMCwMV-jwzYAwrhuqG1UCGjQoSl3A0QRKO-gFHlQ"/>
<a aria-label="Add to wishlist" href="{{ route('customer.login', ['redirect' => url()->current()]) }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</a>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lunara Fashion</span>
<h4 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">Structured Leather Tote</h4>
<span class="font-body-sm text-body-sm text-on-surface mt-1">$380.00</span>
</a>
<!-- Product 3 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden">
<img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="A beautiful, clean editorial product shot of a minimalist silk slip dress in a muted olive tone. The dress is draped elegantly on a subtle light-gray stone pedestal against a bright white backdrop. The soft, high-key lighting creates gentle folds and shadows, highlighting the garment's luxurious silk drape. Ideal for a premium light-mode fashion platform." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrQWexD2Xms4d7-qplQNqqTI4EebkIxaCqpOssP3jfxkcDDAjBvE4kuCEgO-j-Yd-Vfxm6sW-zOaQShx89-kFo0JwvaQ9DnVYjw0ZeHlwNYQaWtigNJNUb1P2E3VS7jVbvb2gfkn5AgK0_pHzGjUiSO2kjiDWXbTKy2tRqRQq5I2md_UYdyHQR_axy07aFn3BeoVctJgri9jLNSSEizCJoXGSF5I0rX6QAaqkzanalXeH6sTmuLnA"/>
<a aria-label="Add to wishlist" href="{{ route('customer.login', ['redirect' => url()->current()]) }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</a>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Maëva House</span>
<h4 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">Silk Slip Dress</h4>
<span class="font-body-sm text-body-sm text-on-surface mt-1">$195.00</span>
</a>
<!-- Product 4 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden">
<img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="A pristine editorial shot of minimal, modern geometric gold hoop earrings resting on a white marble surface. The lighting is incredibly crisp and bright, creating subtle reflections and deep, sharp shadows that elevate the luxury feel. The overall visual tone is sophisticated, austere, and perfectly tailored for a high-end minimalist jewelry collection in a light-mode UI." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXqNhNFWMr-Gm8_uwAVgBbqtzcNdb5MAfQUsG_3GJbmE0gm167f27WLQY44QclgDSw7N_b2k0qpe9HdTKZlExYsZl6FJUCnKft0foIHP3pp3uFUAxnwrYM3o7ap46wCmmnSGAbNN-gDM_Kptg0bVNG6ghZhp7r3PeQ66ZD2yhgIMKhB9sSycHTa8yXBJ3fTbNvx2tH5SUu76da_WcZ3bJW7JeJmVuEnVOdIHENcwQB0a1sOCp-u_s"/>
<a aria-label="Add to wishlist" href="{{ route('customer.login', ['redirect' => url()->current()]) }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</a>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kayana Apparel</span>
<h4 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">Geometric Gold Hoops</h4>
<span class="font-body-sm text-body-sm text-on-surface mt-1">$85.00</span>
</a>
</div>
<div class="mt-md flex justify-center">
<a href="{{ route('customer.shop') }}" class="border border-primary text-primary bg-transparent font-label-caps text-label-caps px-lg py-sm uppercase tracking-widest hover:bg-surface-container-low transition-colors inline-block">{{ __('VIEW ALL NEW ARRIVALS') }}</a>
</div>
</section>
<!-- Featured Stores (Horizontal Scroll) -->
<section class="py-xl bg-surface-container-low border-t border-b border-outline-variant">
<div class="px-container-margin mb-md">
<h3 class="font-title-md text-title-md text-on-surface">{{ __('Featured Stores') }}</h3>
</div>
<div class="flex overflow-x-auto no-scrollbar lg:grid lg:grid-cols-4 lg:overflow-visible pl-container-margin pr-container-margin gap-md pb-xs">
<!-- Store 1 -->
<a href="{{ route('customer.shop.store-detail', 1) }}" class="shrink-0 w-64 lg:w-auto cursor-pointer group">
<div class="aspect-video mb-xs bg-surface-container overflow-hidden">
<img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="A minimalist architectural photograph of a high-end fashion boutique exterior named Lunara Fashion. The storefront features expansive glass windows, stark white walls, and a beautifully curated display of single garments bathed in warm, soft sunlight. The composition is clean, airy, and extremely premium, perfectly fitting a light-mode luxury marketplace theme." src="https://lh3.googleusercontent.com/aida-public/AB6AXuATiKrXBx3vVfoNsTu1_JuFvfVqHhF9A63yLIFGC0hF5MVVUZB6Nu-eyjEa5IxqGiEpPzawhFtGfTAatsc-_9Pwi9D9AsVEO7TOOEszevnRdatfxPYIK7ZAvB0-Aa3R8CSQPOhV3EN9w5_S8sCYYX8NCMlAs_gD3RYhAkt91QyBdC8bmQs-v4yGHXrAH2KKGIPWNoi7jJqivQtsOliueGluswaKxAgwLxl1rh_aTfA_gB_LTeqw8oU"/>
</div>
<h4 class="font-title-md text-title-md text-on-surface">Lunara Fashion</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Modern feminine silhouettes.') }}</p>
</a>
<!-- Store 2 -->
<a href="{{ route('customer.shop.store-detail', 2) }}" class="shrink-0 w-64 lg:w-auto cursor-pointer group">
<div class="aspect-video mb-xs bg-surface-container overflow-hidden">
<img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="A sleek, monochromatic photograph of a minimalist fashion studio interior for Noiré Studio. The space features polished concrete floors, austere black metal clothing racks holding sharply tailored suits, and bright, diffuse natural light streaming from large skylights. The aesthetic is extremely sophisticated, stark, and appropriate for a high-end luxury editorial context." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDGwknduyLPGxjMRvTx7tN4JeGs-9IICVVEqumRS28Y9jTBxPfkTa9uV98aPjekXCG1uLxayAYmBwFZIIf73qfeWOcTQ6jI97GOQBVdIzBaAZhTlYEO8RKF_NsqCMXssspqoctKzP8RpOHtJI_bw-qZI1QF_fn1OH80mwa6ht1vSJY8vkFSZq_OBTROdz1TubDt_Y_Ax7quip7t8HNO7TkKNnLYOEFLbmjlpYvis2wIP6LJwYtpaNo"/>
</div>
<h4 class="font-title-md text-title-md text-on-surface">Noiré Studio</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Tailored, stark minimalism.') }}</p>
</a>
<!-- Store 3 -->
<a href="{{ route('customer.shop.store-detail', 3) }}" class="shrink-0 w-64 lg:w-auto cursor-pointer group">
<div class="aspect-video mb-xs bg-surface-container overflow-hidden">
<img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="A bright, airy lifestyle shot representing Kayana Apparel. A minimalist wooden hanger displays a flowy, natural-fiber linen shirt against a plain, textured plaster wall. Sunbeams cut across the composition, creating harsh, dramatic shadows that add depth and a modern, organic, luxury feel to the light-mode UI design." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCSCazvaWZuccedNS2ILQbkHUJlNvuZ7i1_N2EHvjuBbo7CLD3CW8iHh-xOfNuHEsio3RxsEYKR2jEnuEUUOg9R7Xza1li0VetG6_yfhRrJs3dSULL6lG6fVDPX4qijbhNAokLUQ8tn673XhAZ-l8Vx3WZDIaxtdNLAHriglRfoPt6xRPff_qYINXAgslwYqW_xSQsAbEn2mjrBLNDh6NTT4t86gs2BbXDST-ewDyDYcbA5FZIEMUM"/>
</div>
<h4 class="font-title-md text-title-md text-on-surface">Kayana Apparel</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Organic textures and flow.') }}</p>
</a>
<!-- Store 4 -->
<a href="{{ route('customer.shop.store-detail', 4) }}" class="shrink-0 w-64 lg:w-auto cursor-pointer group">
<div class="aspect-video mb-xs bg-surface-container overflow-hidden">
<img class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" data-alt="A highly curated, still-life editorial photograph of accessories for Maëva House. A selection of minimalist silver jewelry and small leather goods rests on varying levels of pristine white ceramic blocks. The lighting is incredibly soft and high-key, ensuring the focus remains on the high quality and craftsmanship of the items in a luxury, light-mode setting." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBces6Xx741Ae5cYEDlbS_pgcHXZ9vEiOAGb5jvlBttKDyIRgUl6PUSDzKjI9nXu8X8Zb-RxuuplZY4dbVXDDBRqOVusLCAlczBCFDMM9qeGCl18jyL9AKeYbo_KYUolJQ-tUyLZ6kLqZFaQ2yKWY0Gs6ucQPlMm57RTWXBipH9At2Nbp1nWNEZDCqkafxCVNpFOE3MSCOi3nOPMbtk9_6tU4iBkCexkl7qGGFlVexn74kqDcVgzRc"/>
</div>
<h4 class="font-title-md text-title-md text-on-surface">Maëva House</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Essential daily luxury.') }}</p>
</a>
</div>
</section>
<!-- Newsletter -->
<section class="py-xl px-container-margin flex flex-col items-center text-center border-b border-outline-variant">
<h3 class="font-headline-md text-headline-md text-on-surface mb-xs">{{ __('Be the first to know') }}</h3>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-md max-w-md">{{ __('Subscribe to our newsletter for exclusive collections and editorial insights.') }}</p>
<form class="w-full max-w-sm lg:max-w-lg flex flex-col lg:flex-row gap-sm">
<div class="relative w-full border-b border-on-surface pb-1 lg:flex-1">
<input class="w-full bg-transparent border-none p-0 focus:ring-0 font-body-lg text-body-lg text-on-surface placeholder-on-surface-variant" placeholder="{{ __('Email Address') }}" type="email"/>
</div>
<button class="w-full lg:w-auto bg-primary text-on-primary font-label-caps text-label-caps py-sm lg:px-xl uppercase tracking-widest hover:opacity-90 transition-opacity mt-sm lg:mt-0" type="button">{{ __('SUBSCRIBE') }}</button>
</form>
</section>
</main>
<!-- Footer -->
<footer class="bg-surface-bright py-xl px-container-margin md:pb-xl pb-32">
<div class="flex flex-col md:flex-row justify-between gap-lg mb-xl">
<div>
<h4 class="font-display-lg text-title-md tracking-widest text-on-surface mb-md">RALIVA</h4>
<p class="font-body-sm text-body-sm text-on-surface-variant max-w-xs">{{ __('Curated fashion for the modern minimalist. Discover the art of everyday dressing.') }}</p>
</div>
<div class="grid grid-cols-2 md:flex gap-xl">
<div class="flex flex-col gap-sm">
<h5 class="font-label-caps text-label-caps text-on-surface uppercase">{{ __('Shop') }}</h5>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('customer.shop') }}">Women</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('customer.shop') }}">Men</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('customer.shop') }}">New Arrivals</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('customer.shop') }}">Designers</a>
</div>
<div class="flex flex-col gap-sm">
<h5 class="font-label-caps text-label-caps text-on-surface uppercase">{{ __('Support') }}</h5>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('FAQ') }}</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('Shipping &amp; Returns') }}</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('Contact Us') }}</a>
</div>
</div>
</div>
<div class="border-t border-outline-variant pt-md flex flex-col md:flex-row justify-between items-center gap-sm">
<span class="font-body-sm text-body-sm text-on-surface-variant">© 2024 RALIVA. All rights reserved.</span>
<div class="flex gap-md">
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('Privacy Policy') }}</a>
<a class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors" href="#">{{ __('Terms of Service') }}</a>
</div>
</div>
</footer>
@include('customer._partials.bottom-nav')
@include('customer._partials.drawer')
</body></html>