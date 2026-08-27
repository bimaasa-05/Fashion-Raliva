<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ __('Oversized Linen Shirt') }} - RALIVA</title>
<script>if (localStorage.getItem('raliva-theme') === 'dark') document.documentElement.classList.add('theme-dark');</script>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&amp;display=swap" rel="stylesheet"/>
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
                      "display-lg": [
                              "Playfair Display"
                      ],
                      "label-caps": [
                              "Manrope"
                      ],
                      "headline-lg-mobile": [
                              "Playfair Display"
                      ],
                      "headline-lg": [
                              "Playfair Display"
                      ],
                      "title-md": [
                              "Manrope"
                      ],
                      "headline-md": [
                              "Playfair Display"
                      ],
                      "body-lg": [
                              "Manrope"
                      ],
                      "body-sm": [
                              "Manrope"
                      ],
                      "label-sm": [
                              "Manrope"
                      ]
              },
              "fontSize": {
                      "display-lg": [
                              "40px",
                              {
                                      "lineHeight": "48px",
                                      "letterSpacing": "-0.02em",
                                      "fontWeight": "600"
                              }
                      ],
                      "label-caps": [
                              "12px",
                              {
                                      "lineHeight": "16px",
                                      "letterSpacing": "0.08em",
                                      "fontWeight": "700"
                              }
                      ],
                      "headline-lg-mobile": [
                              "28px",
                              {
                                      "lineHeight": "36px",
                                      "fontWeight": "500"
                              }
                      ],
                      "headline-lg": [
                              "32px",
                              {
                                      "lineHeight": "40px",
                                      "fontWeight": "500"
                              }
                      ],
                      "title-md": [
                              "18px",
                              {
                                      "lineHeight": "24px",
                                      "letterSpacing": "0.01em",
                                      "fontWeight": "600"
                              }
                      ],
                      "headline-md": [
                              "24px",
                              {
                                      "lineHeight": "32px",
                                      "fontWeight": "500"
                              }
                      ],
                      "body-lg": [
                              "16px",
                              {
                                      "lineHeight": "24px",
                                      "fontWeight": "400"
                              }
                      ],
                      "body-sm": [
                              "14px",
                              {
                                      "lineHeight": "20px",
                                      "fontWeight": "400"
                              }
                      ],
                      "label-sm": [
                              "12px",
                              {
                                      "lineHeight": "16px",
                                      "fontWeight": "500"
                              }
                      ]
              }
      },
          },
        }
      </script>
<style>
        .material-symbols-outlined {
          font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
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
<body class="bg-surface text-on-surface font-body-lg antialiased pb-32 lg:pl-72 lg:pb-0">
<!-- Header (Custom TopAppBar for Product Details) -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] grid grid-cols-3 items-center px-container-margin h-16">
<a aria-label="Go back" href="{{ url()->previous() }}" class="w-10 h-10 justify-self-start flex items-center justify-center rounded-full bg-surface-container-lowest/50 hover:bg-surface-variant transition-colors">
<span class="material-symbols-outlined text-[var(--chrome-text)]">arrow_back</span>
</a>
<h1 class="font-display-lg text-title-md md:text-headline-md tracking-widest text-[var(--chrome-accent)] text-center truncate">RALIVA</h1>
<div class="flex gap-xs justify-self-end">
<a aria-label="Wishlist" href="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-lowest/50 hover:bg-surface-variant transition-colors">
<span class="material-symbols-outlined text-[var(--chrome-text)]">favorite_border</span>
</a>
<a aria-label="Cart" href="{{ route('customer.chart') }}" class="relative w-10 h-10 flex items-center justify-center rounded-full bg-surface-container-lowest/50 hover:bg-surface-variant transition-colors">
<span class="material-symbols-outlined text-[var(--chrome-text)]">shopping_cart</span>
<span class="absolute top-0 right-0 bg-secondary-fixed-dim text-on-secondary-fixed text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">2</span>
</a>
</div>
</header>
<main class="pt-0 lg:pt-16">
<div class="lg:flex lg:items-start lg:gap-xl">
<!-- Product Gallery -->
<section class="relative w-full aspect-[3/4] md:aspect-[4/5] lg:w-[44%] lg:shrink-0 lg:aspect-auto lg:h-[calc(100vh-9rem)] bg-surface-variant overflow-hidden snap-x snap-mandatory flex overflow-x-auto hide-scrollbar lg:sticky lg:top-24 lg:self-start">
<div class="min-w-full snap-start relative">
<img class="w-full h-full object-cover" data-alt="A high-fashion editorial shot of a crisp white oversized linen shirt worn by a model in a minimalist, sunlit studio. The lighting is soft and natural, casting delicate shadows that emphasize the texture and drape of the breathable linen fabric. The aesthetic is clean, luxurious, and modern, with a neutral soft ivory background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB60zlPtMN4DNcfKmNtNvMLaGtiKfbUV1jwb0QK8OA5iaHO4UH7XENx8hMwhaE5yT5LrfK8UMcwLCPExcTdn6andqBGPBlLUnx50TloBkou9GTUxw3G_AUUXNsW0tW-nac6qgCMlCN5DNx-MS9SaBZyxtqnHsrZoA5wWOTWt35qMllqNd3QRmPFzPPUS0OYj9jK2fpGsyo_-h8LA0wGcj5Ox9jJzKViCJecEiEYn63RRtnGwLNRA44"/>
</div>
<div class="min-w-full snap-start relative">
<img class="w-full h-full object-cover" data-alt="Close-up detail shot of the oversized white linen shirt, focusing on the collar, buttons, and fabric weave. The image highlights the premium quality and effortless elegance of the garment. Natural lighting creates a sophisticated, minimalist mood, consistent with a high-end luxury fashion catalog." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC5hkBUk8qjajx4Fs6RcYyo91ZEngPiYmbd4X1xjBR-1fRqEFsTUr3wJO9ILTg25nmWSo1ZwRtuMoHAH8BFNB_kPlvgOmAGOKVLwSO5vgy0InA79cDfZAFfH6-i_efcu1--rpyxDrlBXOfbgFC0qZSHx-tEMf7gLErcRFXJ5jIb527IzIUXgYjwJoOcwqGXsFxx4Rmttv2pWKce_alMda2737sk-d7imVOjnHulwFk6JxBuryI-ssU"/>
</div>
<div class="absolute bottom-md left-1/2 -translate-x-1/2 flex gap-2 z-10">
<div class="w-2 h-2 rounded-full bg-on-surface"></div>
<div class="w-2 h-2 rounded-full bg-outline-variant"></div>
</div>
</section>
<div class="lg:flex-1 lg:min-w-0">
<!-- Product Info -->
<section class="px-container-margin py-md">
<div class="flex justify-between items-start mb-2">
<h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">Oversized Linen Shirt</h1>
</div>
<div class="flex items-center gap-xs mb-sm">
<div class="flex text-secondary-fixed-dim">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('4.8 (124 reviews)') }}</span>
</div>
<p class="font-title-md text-title-md text-on-surface mb-lg">Rp 289.000</p>
<!-- Color Selection -->
<div class="mb-lg">
<p class="font-label-caps text-label-caps text-on-surface mb-sm">{{ __('COLOR: WHITE') }}</p>
<div class="flex gap-sm">
<button aria-label="{{ __('Select White') }}" class="w-8 h-8 rounded-full border border-on-surface p-[2px]">
<span class="block w-full h-full rounded-full bg-[#FFFFFF] border border-outline-variant"></span>
</button>
<button aria-label="{{ __('Select Black') }}" class="w-8 h-8 rounded-full border border-transparent p-[2px]">
<span class="block w-full h-full rounded-full bg-[#111111]"></span>
</button>
<button aria-label="{{ __('Select Beige') }}" class="w-8 h-8 rounded-full border border-transparent p-[2px]">
<span class="block w-full h-full rounded-full bg-[#E5DCC5]"></span>
</button>
</div>
</div>
<!-- Size Selection -->
<div class="mb-lg">
<div class="flex justify-between items-center mb-sm">
<p class="font-label-caps text-label-caps text-on-surface">{{ __('SIZE') }}</p>
<button class="font-label-sm text-label-sm text-on-surface-variant underline decoration-1 underline-offset-4">{{ __('Size Guide') }}</button>
</div>
<div class="grid grid-cols-4 gap-gutter">
<button class="h-12 border border-outline-variant flex items-center justify-center font-body-sm text-body-sm text-on-surface hover:border-on-surface transition-colors">S</button>
<button class="h-12 border border-on-surface bg-surface-container-low flex items-center justify-center font-body-sm text-body-sm text-on-surface font-semibold">M</button>
<button class="h-12 border border-outline-variant flex items-center justify-center font-body-sm text-body-sm text-on-surface hover:border-on-surface transition-colors">L</button>
<button class="h-12 border border-outline-variant flex items-center justify-center font-body-sm text-body-sm text-on-surface hover:border-on-surface transition-colors">XL</button>
</div>
</div>
<!-- Desktop Actions -->
<div class="hidden lg:flex gap-sm mt-xl">
<a href="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" class="flex-1 h-12 border border-on-surface bg-transparent text-on-surface font-label-caps text-label-caps tracking-widest hover:bg-surface-variant transition-colors flex items-center justify-center">
            {{ __('ADD TO CART') }}
        </a>
<a href="{{ route('customer.checkout') }}" class="flex-1 h-12 bg-primary text-on-primary font-label-caps text-label-caps tracking-widest hover:opacity-90 transition-opacity flex items-center justify-center">
            {{ __('BUY NOW') }}
        </a>
</div>
</section>
<!-- Accordions -->
<section class="border-t border-outline-variant">
<!-- Description -->
<details class="group border-b border-outline-variant">
<summary class="flex justify-between items-center px-container-margin py-sm cursor-pointer list-none">
<span class="font-title-md text-title-md text-on-surface">{{ __('Description') }}</span>
<span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
</summary>
<div class="px-container-margin pb-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                        An effortlessly chic oversized shirt crafted from premium, breathable linen. Featuring a classic collar, button-down front, and dropped shoulders for a relaxed silhouette. Perfect for layering over swimwear or tucking into tailored trousers for a sophisticated summer look.
                    </p>
</div>
</details>
<!-- Material & Care -->
<details class="group border-b border-outline-variant">
<summary class="flex justify-between items-center px-container-margin py-sm cursor-pointer list-none">
<span class="font-title-md text-title-md text-on-surface">{{ __('Material &amp; Care') }}</span>
<span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
</summary>
<div class="px-container-margin pb-sm">
<ul class="font-body-sm text-body-sm text-on-surface-variant list-disc list-inside space-y-1">
<li>100% Linen</li>
<li>{{ __('Machine wash cold with like colors') }}</li>
<li>{{ __('Do not bleach') }}</li>
<li>{{ __('Tumble dry low or hang to dry') }}</li>
<li>{{ __('Warm iron if needed') }}</li>
</ul>
</div>
</details>
<!-- Shipping & Returns -->
<details class="group border-b border-outline-variant">
<summary class="flex justify-between items-center px-container-margin py-sm cursor-pointer list-none">
<span class="font-title-md text-title-md text-on-surface">{{ __('Shipping &amp; Returns') }}</span>
<span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
</summary>
<div class="px-container-margin pb-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                        {{ __('Free standard shipping on orders over Rp 500.000. Returns accepted within 14 days of delivery. Items must be unworn with original tags attached.') }}
                    </p>
</div>
</details>
</section>
<!-- Buyer Reviews -->
<section class="border-t border-outline-variant">
<div class="flex items-center justify-between px-container-margin pt-md pb-sm">
<h2 class="font-title-md text-title-md text-on-surface">{{ __('Buyer Reviews') }}</h2>
<span class="font-label-sm text-label-sm text-on-surface-variant inline-flex items-center gap-xs">4.8 <span class="material-symbols-outlined text-[14px] text-secondary-fixed-dim" style="font-variation-settings: 'FILL' 1;">star</span> {{ __('(124)') }}</span>
</div>
<!-- Review 1 -->
<article class="px-container-margin py-md border-b border-outline-variant">
<div class="flex items-start justify-between gap-md">
<div class="flex items-center gap-sm min-w-0">
<div class="w-9 h-9 rounded-full bg-secondary-container text-on-secondary-fixed flex items-center justify-center font-label-caps text-label-caps shrink-0">SM</div>
<div class="min-w-0">
<p class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Sarah M.</p>
<p class="font-label-sm text-label-sm text-on-surface-variant truncate">May 30, 2026 · {{ __('Verified Purchase') }}</p>
</div>
</div>
<div class="relative shrink-0">
<button aria-label="{{ __('More options') }}" class="w-8 h-8 rounded-full hover:bg-surface-container-high flex items-center justify-center transition-colors text-on-surface-variant" onclick="toggleReviewMenu(event, 'rv-menu-1')" type="button">
<span class="material-symbols-outlined text-[18px]">more_vert</span>
</button>
<div class="hidden absolute right-0 top-9 z-20 w-44 bg-surface-container-lowest border border-outline-variant rounded-DEFAULT shadow-xl overflow-hidden" id="rv-menu-1">
<button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors text-left" onclick="translateReview('rv-text-1', this)" type="button">
<span class="material-symbols-outlined text-[18px] text-on-surface-variant">translate</span><span class="rv-label" data-state="b" data-a="{{ __('See original') }}" data-b="{{ __('Translate') }}">{{ __('Translate') }}</span>
</button>
<button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-error hover:bg-surface-container-low transition-colors text-left" onclick="openReport()" type="button">
<span class="material-symbols-outlined text-[18px]">flag</span>{{ __('Report review') }}
</button>
</div>
</div>
</div>
<div class="flex items-center gap-xs mt-xs">
<div class="flex text-secondary-fixed-dim">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-sm" data-state="original" data-original="The linen quality is exceptional - breathable yet structured. The oversized cut drapes beautifully and the white stays crisp after washing. Worth every rupiah." data-translated="Kualitas linen-nya luar biasa - adem tapi tetap terstruktur. Potongan oversized-nya jatuhnya bagus banget dan warna putihnya tetap cerah setelah dicuci. Sepadunya!" id="rv-text-1">
                    The linen quality is exceptional - breathable yet structured. The oversized cut drapes beautifully and the white stays crisp after washing. Worth every rupiah.
                </p>
</article>
<!-- Review 2 -->
<article class="px-container-margin py-md border-b border-outline-variant">
<div class="flex items-start justify-between gap-md">
<div class="flex items-center gap-sm min-w-0">
<div class="w-9 h-9 rounded-full bg-secondary-container text-on-secondary-fixed flex items-center justify-center font-label-caps text-label-caps shrink-0">DK</div>
<div class="min-w-0">
<p class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Dewi K.</p>
<p class="font-label-sm text-label-sm text-on-surface-variant truncate">May 21, 2026 · {{ __('Verified Purchase') }}</p>
</div>
</div>
<div class="relative shrink-0">
<button aria-label="{{ __('More options') }}" class="w-8 h-8 rounded-full hover:bg-surface-container-high flex items-center justify-center transition-colors text-on-surface-variant" onclick="toggleReviewMenu(event, 'rv-menu-2')" type="button">
<span class="material-symbols-outlined text-[18px]">more_vert</span>
</button>
<div class="hidden absolute right-0 top-9 z-20 w-44 bg-surface-container-lowest border border-outline-variant rounded-DEFAULT shadow-xl overflow-hidden" id="rv-menu-2">
<button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors text-left" onclick="translateReview('rv-text-2', this)" type="button">
<span class="material-symbols-outlined text-[18px] text-on-surface-variant">translate</span><span class="rv-label" data-state="b" data-a="{{ __('See original') }}" data-b="{{ __('Translate') }}">{{ __('Translate') }}</span>
</button>
<button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-error hover:bg-surface-container-low transition-colors text-left" onclick="openReport()" type="button">
<span class="material-symbols-outlined text-[18px]">flag</span>{{ __('Report review') }}
</button>
</div>
</div>
</div>
<div class="flex items-center gap-xs mt-xs">
<div class="flex text-secondary-fixed-dim">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]">star_border</span>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-sm" data-state="original" data-original="Bahannya adem dan tidak nerawang, cocok untuk cuaca Jakarta. Ukurannya sesuai dengan panduan ukuran dan pengirimannya cepat. Recommended!" data-translated="The fabric is cool and not see-through, perfect for Jakarta weather. The size matches the size guide and delivery was fast. Recommended!" id="rv-text-2">
                    Bahannya adem dan tidak nerawang, cocok untuk cuaca Jakarta. Ukurannya sesuai dengan panduan ukuran dan pengirimannya cepat. Recommended!
                </p>
</article>
<!-- Review 3 -->
<article class="px-container-margin py-md border-b border-outline-variant">
<div class="flex items-start justify-between gap-md">
<div class="flex items-center gap-sm min-w-0">
<div class="w-9 h-9 rounded-full bg-secondary-container text-on-secondary-fixed flex items-center justify-center font-label-caps text-label-caps shrink-0">AR</div>
<div class="min-w-0">
<p class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Amanda R.</p>
<p class="font-label-sm text-label-sm text-on-surface-variant truncate">May 12, 2026 · {{ __('Verified Purchase') }}</p>
</div>
</div>
<div class="relative shrink-0">
<button aria-label="{{ __('More options') }}" class="w-8 h-8 rounded-full hover:bg-surface-container-high flex items-center justify-center transition-colors text-on-surface-variant" onclick="toggleReviewMenu(event, 'rv-menu-3')" type="button">
<span class="material-symbols-outlined text-[18px]">more_vert</span>
</button>
<div class="hidden absolute right-0 top-9 z-20 w-44 bg-surface-container-lowest border border-outline-variant rounded-DEFAULT shadow-xl overflow-hidden" id="rv-menu-3">
<button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors text-left" onclick="translateReview('rv-text-3', this)" type="button">
<span class="material-symbols-outlined text-[18px] text-on-surface-variant">translate</span><span class="rv-label" data-state="b" data-a="{{ __('See original') }}" data-b="{{ __('Translate') }}">{{ __('Translate') }}</span>
</button>
<button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-error hover:bg-surface-container-low transition-colors text-left" onclick="openReport()" type="button">
<span class="material-symbols-outlined text-[18px]">flag</span>{{ __('Report review') }}
</button>
</div>
</div>
</div>
<div class="flex items-center gap-xs mt-xs">
<div class="flex text-secondary-fixed-dim">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-sm" data-state="original" data-original="An elegant minimalist shirt that pairs with everything in my closet. The sleeves run slightly long but that is the intended oversized look." data-translated="Kemeja minimalis yang elegan dan mudah dipadukan dengan semua baju di lemari saya. Lengannya agak panjang tapi memang itu gaya oversized yang dimaksudkan." id="rv-text-3">
                    An elegant minimalist shirt that pairs with everything in my closet. The sleeves run slightly long but that is the intended oversized look.
                </p>
</article>
</section>
</div>
</div>
<!-- You May Also Like -->
<section class="py-xl">
<h2 class="font-headline-md text-headline-md text-center text-on-surface mb-lg">{{ __('You May Also Like') }}</h2>
<div class="flex overflow-x-auto lg:grid lg:grid-cols-3 lg:overflow-visible gap-gutter px-container-margin hide-scrollbar pb-sm">
<!-- Card 1 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="min-w-[160px] md:min-w-[200px] lg:min-w-0 block">
<div class="relative w-full aspect-[3/4] mb-sm bg-surface-variant">
<img class="w-full h-full object-cover" data-alt="A minimalist fashion editorial image of tailored wide-leg trousers in a soft beige tone, styled cleanly against a light, neutral background, reflecting premium quality and modern elegance." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD4IYXT8zx75qRHmyTLk9xkyFXQ2aqGro0exB1cwe11WPpyS4_FUyQA7qq8cxx_mFM3PPrNwyPQcZ-_wI0J8kAwOnv5OPd8VFDvqKFUJDKt9UwsYXTBwCIVxPjpYG2Tc4O-GOg6_Sx5cjoPIKUP4Xa4HwLQKlAk6lcw-xaN7sm1Ad78waPAEVlkv60tLR_o8Ap-HQ14icz3DI2UgdsVBjVQiozmTytcTh9-KT1npkU4xDEEJGQTJro"/>
<a aria-label="{{ __('Add to wishlist') }}" href="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</a>
</div>
<div class="space-y-1">
<p class="font-label-sm text-label-sm text-on-surface-variant">RALIVA</p>
<p class="font-body-sm text-body-sm text-on-surface font-medium truncate">Tailored Wide Leg Trousers</p>
<p class="font-body-sm text-body-sm text-on-surface">Rp 349.000</p>
</div>
</a>
<!-- Card 2 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="min-w-[160px] md:min-w-[200px] lg:min-w-0 block">
<div class="relative w-full aspect-[3/4] mb-sm bg-surface-variant">
<img class="w-full h-full object-cover" data-alt="A chic, minimalist editorial shot of a ribbed knit tank top in black, draped elegantly on a hanger against a pristine white background. The focus is on the texture and modern silhouette." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDBSCYHpJJ10PR1rv62xsiSUHcgECc8Yl7gxPOJqlAhXqjJGHnlXSe3G3OT0zZOpoO6zdOywN_zGJ312gSUWGyrERx3QJH1sib9jdTkpcPR1UGz6uHG3aBCzTk7nRRLeHq2PxVj1WHkGQGh3Vuk2k_lfNftY_XKOXombF0_TGRpWMQudl33iPubHVACr4ZiMJFHeHt5rU1xGcveNoDt2q3Et_j-G22OqOzW2MDW8EobpXOmXTWjw3M"/>
<a aria-label="{{ __('Add to wishlist') }}" href="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</a>
</div>
<div class="space-y-1">
<p class="font-label-sm text-label-sm text-on-surface-variant">RALIVA</p>
<p class="font-body-sm text-body-sm text-on-surface font-medium truncate">Ribbed Knit Tank</p>
<p class="font-body-sm text-body-sm text-on-surface">Rp 149.000</p>
</div>
</a>
<!-- Card 3 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="min-w-[160px] md:min-w-[200px] lg:min-w-0 block">
<div class="relative w-full aspect-[3/4] mb-sm bg-surface-variant">
<img class="w-full h-full object-cover" data-alt="A sophisticated editorial photograph of a minimalist leather tote bag in deep brown, placed thoughtfully on a smooth stone surface with soft, diffused lighting typical of luxury fashion campaigns." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB1cthY8K2JPKLNpkQV3JBRI6w4KRyg6mQeqgXctAcetZp_v6EdYIOJjePq8SWVSrQa2JsuHIHIjEMmjJ5PJF-s2QDQm4sbvggtYfBOhWZFYXxH9UkXED66ErqitL29o75HKKd40LGYNnkEMndKxfJ4L-7z-rbdPVecIV7fdOrMA_mrvmKu5Y8cgTTHi3JY3AyfNe_NyppH-jBZSnRZdg5g_HhxOs5QixseLjNAx7O8kEcjJiOq07Q"/>
<a aria-label="{{ __('Add to wishlist') }}" href="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors flex items-center">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</a>
</div>
<div class="space-y-1">
<p class="font-label-sm text-label-sm text-on-surface-variant">RALIVA</p>
<p class="font-body-sm text-body-sm text-on-surface font-medium truncate">Minimalist Leather Tote</p>
<p class="font-body-sm text-body-sm text-on-surface">Rp 599.000</p>
</div>
</a>
</div>
</section>
</main>
<!-- Sticky Bottom Action Bar -->
<div class="fixed bottom-0 left-0 w-full lg:hidden bg-surface/95 backdrop-blur-sm px-container-margin py-md flex gap-sm z-40" style="padding-bottom: max(16px, env(safe-area-inset-bottom));">
<a href="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" class="flex-1 h-12 border border-on-surface bg-transparent text-on-surface font-label-caps text-label-caps tracking-widest hover:bg-surface-variant transition-colors flex items-center justify-center">
            {{ __('ADD TO CART') }}
        </a>
<a href="{{ route('customer.checkout') }}" class="flex-1 h-12 bg-primary text-on-primary font-label-caps text-label-caps tracking-widest hover:opacity-90 transition-opacity flex items-center justify-center">
            {{ __('BUY NOW') }}
        </a>
</div>
<!-- Report Review Modal -->
<div class="hidden fixed inset-0 bg-black/50 z-[80] flex items-center justify-center p-container-margin" id="report-modal" onclick="closeReport()">
<div class="w-full max-w-sm bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-xl" onclick="event.stopPropagation()">
<div class="flex items-center gap-sm mb-sm">
<span class="material-symbols-outlined text-error text-[22px]">flag</span>
<h3 class="font-title-md text-title-md text-on-surface">{{ __('Report this review') }}</h3>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-md">{{ __('Why are you reporting this review?') }}</p>
<form id="report-form">
<label class="flex items-center gap-sm py-sm border-b border-outline-variant cursor-pointer">
<input class="w-4 h-4 shrink-0" name="reason" required type="radio" value="spam"/>
<span class="font-body-sm text-body-sm text-on-surface">{{ __('Spam or promotion') }}</span>
</label>
<label class="flex items-center gap-sm py-sm border-b border-outline-variant cursor-pointer">
<input class="w-4 h-4 shrink-0" name="reason" type="radio" value="harassment"/>
<span class="font-body-sm text-body-sm text-on-surface">{{ __('Harassment or offensive content') }}</span>
</label>
<label class="flex items-center gap-sm py-sm cursor-pointer">
<input class="w-4 h-4 shrink-0" name="reason" type="radio" value="irrelevant"/>
<span class="font-body-sm text-body-sm text-on-surface">{{ __('Irrelevant to the product') }}</span>
</label>
<div class="flex gap-gutter mt-lg">
<button class="flex-1 h-12 border border-outline text-on-surface font-label-caps text-label-caps uppercase tracking-widest hover:bg-surface-container-low transition-colors" onclick="closeReport()" type="button">{{ __('Cancel') }}</button>
<button class="flex-1 h-12 bg-primary text-on-primary font-label-caps text-label-caps uppercase tracking-widest hover:opacity-90 transition-opacity" type="submit">{{ __('Send report') }}</button>
</div>
</form>
<div class="hidden text-center py-md" id="report-success">
<span class="material-symbols-outlined text-secondary text-[48px]">check_circle</span>
<p class="font-body-lg text-body-lg text-on-surface mt-sm mb-lg">{{ __('Thank you. Your report has been submitted.') }}</p>
<button class="w-full h-12 bg-primary text-on-primary font-label-caps text-label-caps uppercase tracking-widest hover:opacity-90 transition-opacity" onclick="closeReport()" type="button">{{ __('Close') }}</button>
</div>
</div>
</div>
<script>
        function toggleReviewMenu(e, id) {
            e.stopPropagation();
            document.querySelectorAll('[id^="rv-menu-"]').forEach(function (m) {
                if (m.id !== id) m.classList.add('hidden');
            });
            document.getElementById(id).classList.toggle('hidden');
        }
        document.addEventListener('click', function () {
            document.querySelectorAll('[id^="rv-menu-"]').forEach(function (m) { m.classList.add('hidden'); });
        });
        function translateReview(id, btn) {
            var p = document.getElementById(id);
            var label = btn.querySelector('.rv-label');
            if (p.dataset.state !== 'translated') {
                p.textContent = p.dataset.translated;
                p.dataset.state = 'translated';
                label.textContent = label.dataset.a;
                label.dataset.state = 'a';
            } else {
                p.textContent = p.dataset.original;
                p.dataset.state = 'original';
                label.textContent = label.dataset.b;
                label.dataset.state = 'b';
            }
        }
        function openReport() {
            document.querySelectorAll('[id^="rv-menu-"]').forEach(function (m) { m.classList.add('hidden'); });
            document.getElementById('report-modal').classList.remove('hidden');
            document.getElementById('report-form').classList.remove('hidden');
            document.getElementById('report-success').classList.add('hidden');
            var f = document.getElementById('report-form');
            f.reset();
            f.querySelectorAll('input[name="reason"]').forEach(function (r) { r.checked = false; });
        }
        function closeReport() {
            document.getElementById('report-modal').classList.add('hidden');
        }
        document.getElementById('report-form').addEventListener('submit', function (e) {
            e.preventDefault();
            this.classList.add('hidden');
            document.getElementById('report-success').classList.remove('hidden');
        });
    </script>
@include('customer._partials.drawer')
</body></html>