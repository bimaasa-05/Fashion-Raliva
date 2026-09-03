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
    html.theme-dark .text-secondary-fixed-dim { color: #8B1E3F !important; }
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
    /* ============ RALIVA GOLD BUTTON (same system as Home / Shop / Register) ============ */
    .btn-gold {
        position: relative;
        overflow: hidden;
        background-color: var(--btn-gold-bg) !important;
        color: var(--btn-gold-text) !important;
        isolation: isolate;
    }
    .btn-gold > * { position: relative; z-index: 2; }
    .btn-gold::after {
        content: '';
        position: absolute;
        inset: 0;
        left: -80%;
        width: 55%;
        background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,.6) 50%, transparent 100%);
        transform: skewX(-24deg);
        pointer-events: none;
        z-index: 1;
        opacity: 0;
    }
    .btn-gold:hover::after,
    .btn-gold.flashing::after { opacity: 1; animation: authFlash 1.4s linear infinite; }
    .btn-gold.flashing::after { animation: authFlash 1.4s cubic-bezier(.4,0,.2,1) 1; }
    @keyframes authFlash { from { left: -80%; } to { left: 135%; } }
    .btn-gold:hover { box-shadow: 0 0 0 1px rgba(139,30,63,.35), 0 8px 22px -8px rgba(139,30,63,.45); }
    html.theme-dark .btn-gold:hover { box-shadow: 0 0 0 1px rgba(139,30,63,.4), 0 8px 22px -8px rgba(139,30,63,.5); }
    :root           { --btn-gold-bg: #8B1E3F; --btn-gold-text: #ffffff; }
    html.theme-dark { --btn-gold-bg: #6D1428; --btn-gold-text: #ffffff; }
    /* ============ SHOP/DETAIL CHECKBOX + RADIO (same system as Register .terms-checkbox) ============ */
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
    /* ============ Product Detail: remap drawer + bottom-nav accent to burgundy (Register language) ============ */
    #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(139,30,63,.10); }
    html.theme-dark #drawer-panel { --chrome-accent: #8B1E3F; --gold-wash: rgba(163,38,63,.16); }
    .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    html.theme-dark .bn-active .material-symbols-outlined { color: #8B1E3F !important; }
    .bn-active { color: #8B1E3F !important; }
    html.theme-dark .bn-active { color: #8B1E3F !important; }
  </style>
<style>
    /* ===== Premium cards + burgundy accents (parity with account/address) ===== */
    .card-premium { box-shadow:0 1px 2px rgb(17 17 17 / .04),0 12px 32px -16px rgb(17 17 17 / .16); transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease; }
    .card-premium:hover { transform:translateY(-3px); box-shadow:0 2px 4px rgb(17 17 17 / .05),0 20px 48px -20px rgb(17 17 17 / .22); border-color:rgba(139,30,63,.45); }
    html.theme-dark .card-premium { background-color:var(--surface-ivory); border-color:var(--border-soft); box-shadow:0 1px 2px rgb(0 0 0 / .3),0 8px 24px -12px rgb(0 0 0 / .5); }
    html.theme-dark .card-premium:hover { box-shadow:0 2px 4px rgb(0 0 0 / .4),0 20px 48px -20px rgb(0 0 0 / .7); border-color:rgba(139,30,63,.55); }
    .premium-heading { display:block; }
    .premium-heading::before { content:''; display:inline-block; width:4px; height:.95em; margin-right:.65rem; background:#8B1E3F; border-radius:9999px; vertical-align:-.05em; }
    .atl-eyebrow { display:inline-flex; align-items:center; gap:.65rem; }
    .atl-eyebrow::before { content:''; width:30px; height:1px; background:var(--chrome-accent); opacity:.7; }
    html.theme-dark .premium-heading::before { background:#8B1E3F; }
    .reveal-up { opacity:0; transform:translateY(12px); transition:opacity .5s ease,transform .5s ease; }
    .reveal-up.is-visible { opacity:1; transform:none; }
    @media (prefers-reduced-motion: reduce) { .reveal-up { opacity:1; transform:none; transition:none; } }
  </style>
  </head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[120px] lg:pl-72">
    <!-- Header (Custom TopAppBar for Product Details) -->
    <header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 fixed z-40 border-b border-[var(--chrome-border)]">
        <a aria-label="Go back" href="{{ url()->previous() }}" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
            <span class="material-symbols-outlined text-[24px]">arrow_back</span>
            </a>
        <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">RALIVA</h1>
        <div class="w-10"></div> <!-- Spacer for centering -->
        <div class="flex gap-xs">
            <a aria-label="Wishlist" href="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" class="p-2 hover:opacity-70 transition-all duration-200 flex">
                <span class="material-symbols-outlined text-[24px]">favorite_border</span>
                </a>
            <a aria-label="Cart" href="{{ route('customer.chart') }}" class="relative p-2 hover:opacity-70 transition-all duration-200 flex">
                <span class="material-symbols-outlined text-[24px]">shopping_cart</span>
                <span class="absolute -top-1 -right-1.5 bg-secondary-fixed-dim text-on-secondary-fixed text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">2</span>
                </a>
            </div>
        </header>
    <main class="pt-16 lg:pt-16">
        <div class="mx-auto max-w-[1400px] px-container-margin">
            <div class="lg:flex lg:items-start lg:gap-xl">
                <!-- Product Gallery (left) -->
                <section class="relative w-full aspect-[3/4] md:aspect-[4/5] lg:w-[42%] lg:shrink-0 lg:aspect-auto lg:h-[calc(100vh-8rem)] lg:sticky lg:top-24 lg:self-start bg-surface-variant overflow-hidden snap-x snap-mandatory flex overflow-x-auto hide-scrollbar">
@forelse ($product->images as $img)
                    <div class="min-w-full snap-start relative">
                        <img class="w-full h-full object-cover" alt="{{ $product->nama_produk }}" src="{{ filter_var($img->file_gambar, FILTER_VALIDATE_URL) ? $img->file_gambar : asset($img->file_gambar) }}"/>
                        </div>
@empty
                    <div class="min-w-full snap-start relative">
                        <img class="w-full h-full object-cover" alt="{{ $product->nama_produk }}" src="https://picsum.photos/seed/product/900/1200"/>
                        </div>
@endforelse
                    <div class="absolute bottom-md left-1/2 -translate-x-1/2 flex gap-2 z-10">
                        <div class="w-2 h-2 rounded-full bg-on-surface"></div>
                        <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                        </div>
                    </section>
                <div class="lg:flex-1 lg:min-w-0">
                    <section class="py-xl reveal-up">
                        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl px-container-margin md:px-[64px] py-md md:py-lg shadow-sm card-premium">
                            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('PRODUCT DETAILS') }}</p>
                            <h2 class="premium-heading font-headline-md text-headline-md text-on-surface mb-md">{{ $product->nama_produk }}</h2>
                            <div class="flex items-center gap-xs mb-sm">
                                <div class="flex text-secondary-fixed-dim">
                                    <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    </div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">{{ number_format($averageRating ?: 0, 1) }} ({{ $reviewCount }} {{ __('reviews') }})</span>
                                </div>
                            <p class="font-title-md text-title-md text-on-surface mb-lg">Rp {{ number_format($product->variants->min('harga') ?? $product->harga_dasar, 0, ',', '.') }}</p>
                            <!-- Color Selection -->
                            <div class="mb-lg">
                                <p class="font-label-caps text-label-caps text-on-surface mb-sm">{{ __('COLOR') }}: {{ strtoupper($product->variants->first()->warna ?? __('N/A')) }}</p>
                                <div class="flex gap-sm">
@foreach ($product->variants->pluck('warna')->unique() as $color)
<span class="font-label-sm text-label-sm text-on-surface-variant mr-sm">{{ $color }}</span>
@endforeach
                                    </div>
                                </div>
                            <!-- Size Selection -->
                            <div class="mb-lg">
                                <div class="flex justify-between items-center mb-sm">
                                    <p class="font-label-caps text-label-caps text-on-surface">{{ __('SIZE') }}</p>
                                    <button class="font-label-sm text-label-sm text-on-surface-variant underline decoration-1 underline-offset-4">{{ __('Size Guide') }}</button>
                                    </div>
                                <div class="grid grid-cols-4 gap-gutter">
@foreach ($product->variants->pluck('ukuran')->unique() as $size)
                                    <button class="h-12 border border-outline-variant flex items-center justify-center font-body-sm text-body-sm text-on-surface hover:border-on-surface transition-colors">{{ $size }}</button>
@endforeach
                                    </div>
                                </div>
                            <!-- Desktop Actions -->
                            <div class="hidden lg:flex gap-sm mt-xl">
                                <a href="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" class="flex-1 h-12 border border-secondary text-secondary bg-transparent font-label-caps text-label-caps tracking-widest hover:bg-secondary/5 transition-colors flex items-center justify-center">
                                    {{ __('ADD TO CART') }}
                                    </a>
                                <a href="{{ route('customer.checkout') }}" class="btn-gold flex-1 h-12 font-label-caps text-label-caps tracking-widest flex items-center justify-center">
                                    {{ __('BUY NOW') }}
                                    </a>
                                </div>
                            <!-- Accordions -->
                            <section class="border-t border-outline-variant">
                                <!-- Description -->
                                <details class="group border-b border-outline-variant">
                                    <summary class="flex justify-between items-center px-container-margin md:px-[64px] py-sm cursor-pointer list-none">
                                        <span class="font-title-md text-title-md text-on-surface">{{ __('Description') }}</span>
                                        <span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
                                        </summary>
                                    <div class="px-container-margin md:px-[64px] pb-sm">
                                        <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                            {{ $product->deskripsi }}
                                            </p>
                                        </div>
                                    </details>
                                <!-- Material & Care -->
                                <details class="group border-b border-outline-variant">
                                    <summary class="flex justify-between items-center px-container-margin md:px-[64px] py-sm cursor-pointer list-none">
                                        <span class="font-title-md text-title-md text-on-surface">{{ __('Material &amp; Care') }}</span>
                                        <span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
                                        </summary>
                                    <div class="px-container-margin md:px-[64px] pb-sm">
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
                                    <summary class="flex justify-between items-center px-container-margin md:px-[64px] py-sm cursor-pointer list-none">
                                        <span class="font-title-md text-title-md text-on-surface">{{ __('Shipping &amp; Returns') }}</span>
                                        <span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
                                        </summary>
                                    <div class="px-container-margin md:px-[64px] pb-sm">
                                        <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                            {{ __('Free standard shipping on orders over Rp 500.000. Returns accepted within 14 days of delivery. Items must be unworn with original tags attached.') }}
                                            </p>
                                        </div>
                                    </details>
                                </section>
                            <!-- Buyer Reviews -->
                            <section class="border-t border-outline-variant">
                                <div class="flex items-center justify-between px-container-margin md:px-[64px] pt-md pb-sm">
                                    <h2 class="font-title-md text-title-md text-on-surface">{{ __('Buyer Reviews') }}</h2>
                                    <span class="font-label-sm text-label-sm text-on-surface-variant inline-flex items-center gap-xs">{{ number_format($averageRating ?: 0, 1) }} <span class="material-symbols-outlined text-[14px] text-secondary-fixed-dim" style="font-variation-settings: 'FILL' 1;">star</span> ({{ $reviewCount }})</span>
                                    </div>
@forelse ($reviews as $ri => $review)
                                <!-- Review -->
                                <article class="px-container-margin md:px-[64px] py-md border-b border-outline-variant">
                                    <div class="flex items-start justify-between gap-md">
                                        <div class="flex items-center gap-sm min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-secondary-container text-on-secondary-fixed flex items-center justify-center font-label-caps text-label-caps shrink-0">{{ mb_substr($review->user?->nama_lengkap ?? 'U', 0, 2) }}</div>
                                            <div class="min-w-0">
                                                <p class="font-body-sm text-body-sm font-semibold text-on-surface truncate">{{ $review->user?->nama_lengkap ?? __('Customer') }}</p>
                                                <p class="font-label-sm text-label-sm text-on-surface-variant truncate">{{ $review->created_at?->format('M d, Y') }} · {{ __('Verified Purchase') }}</p>
                                                </div>
                                            </div>
                                        <div class="relative shrink-0">
                                            <button aria-label="{{ __('More options') }}" class="w-8 h-8 rounded-full hover:bg-surface-container-high flex items-center justify-center transition-colors text-on-surface-variant" onclick="toggleReviewMenu(event, 'rv-menu-{{ $ri }}')" type="button">
                                                <span class="material-symbols-outlined text-[18px]">more_vert</span>
                                                </button>
                                            <div class="hidden absolute right-0 top-9 z-20 w-44 bg-surface-container-lowest border border-outline-variant rounded-DEFAULT shadow-xl overflow-hidden" id="rv-menu-{{ $ri }}">
                                                <button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors text-left" onclick="toggleReviewMenu(event, 'rv-menu-{{ $ri }}')" type="button">
                                                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant">translate</span>{{ __('Translate') }}
                                                    </button>
                                                <button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-error hover:bg-surface-container-low transition-colors text-left" onclick="openReport()" type="button">
                                                    <span class="material-symbols-outlined text-[18px]">flag</span>{{ __('Report review') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="flex items-center gap-xs mt-xs">
                                        <div class="flex text-secondary-fixed-dim">
@for ($s = 1; $s <= 5; $s++)
@php $filled = $review->rating >= $s; @endphp
                                            <span class="material-symbols-outlined text-[16px]" style="{{ $filled ? "font-variation-settings: 'FILL' 1;" : '' }}">{{ $filled ? 'star' : 'star_border' }}</span>
@endfor
                                            </div>
                                        </div>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-sm">{{ $review->ulasan }}</p>
                                    </article>
@empty
                                <article class="px-container-margin md:px-[64px] py-md text-center">
                                    <p class="font-body-lg text-body-lg text-on-surface-variant">{{ __('No reviews yet. Be the first to review this product.') }}</p>
                                    </article>
@endforelse
                                @if ($reviewCount > 3)
                                <div class="px-container-margin md:px-[64px] py-md flex justify-center">
                                    <a href="{{ route('customer.shop.produk-riviews', $product->product_id) }}" class="inline-flex items-center gap-sm font-label-caps text-label-caps uppercase tracking-widest text-secondary hover:text-primary transition-colors">
                                        <span>{{ __('Lihat selengkapnya') }}</span>
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </a>
                                </div>
                                @endif
                                </section>
                            </div>
                        </section>
                    </div>
                </div>
                <!-- You May Also Like -->
                <section class="py-xl border-t border-outline-variant">
                    <h2 class="font-headline-md text-headline-md text-center text-on-surface mb-lg">{{ __('You May Also Like') }}</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-gutter px-container-margin pb-sm">
@forelse ($relatedProducts as $rp)
@php $rpImage = $rp->images->first()?->file_gambar; $rpMin = $rp->variants->min('harga') ?? $rp->harga_dasar; @endphp
                        <!-- Related -->
                        <a href="{{ route('customer.shop.produk-detail', $rp->product_id) }}" class="block group cursor-pointer">
                            <div class="relative w-full aspect-[3/4] mb-sm bg-surface-variant overflow-hidden">
                                <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-[1.04]" alt="{{ $rp->nama_produk }}" src="{{ $rpImage ? (filter_var($rpImage, FILTER_VALIDATE_URL) ? $rpImage : asset($rpImage)) : 'https://picsum.photos/seed/related/900/1200' }}"/>
                                <button type="button" aria-label="{{ __('Add to wishlist') }}" data-url="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" onclick="goWishlist(this)" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors flex items-center">
                                    <span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
                                    </button>
                                </div>
                            <div class="space-y-1">
                                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ $rp->store?->nama_toko ?? __('RALIVA') }}</p>
                                <p class="font-body-sm text-body-sm text-on-surface font-medium truncate">{{ $rp->nama_produk }}</p>
                                <p class="font-body-sm text-body-sm text-on-surface">Rp {{ number_format($rpMin, 0, ',', '.') }}</p>
                            </div>
                        </a>
@empty
                        <div class="col-span-full text-center py-xl">
                            <p class="font-body-lg text-body-lg text-on-surface-variant">{{ __('No related products yet.') }}</p>
                        </div>
@endforelse
                    </div>
                </section>
            </div>
        </main>
    <!-- Mobile Sticky Bottom Action Bar -->
    <div class="fixed bottom-0 left-0 right-0 lg:left-72 z-50 px-container-margin py-sm pb-safe">
        <div class="flex items-center gap-sm md:gap-md card-premium bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-xs md:p-sm shadow-[0_-4px_24px_-12px_rgba(0,0,0,0.18)]">
            <a href="{{ auth()->check() ? route('customer.wishlist') : route('login', ['redirect' => url()->current()]) }}" class="flex-1 min-w-0 flex items-center justify-center gap-2 px-xl py-3 rounded-full border border-secondary text-secondary font-label-caps text-label-caps uppercase tracking-widest transition-colors hover:bg-secondary/5">
                <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                <span class="truncate">{{ __('CART') }}</span>
                </a>
            <a href="{{ route('customer.checkout') }}" class="btn-gold flex-1 min-w-0 flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                <span class="material-symbols-outlined text-[20px]">attach_money</span>
                <span class="truncate">{{ __('BUY') }}</span>
                </a>
            </div>
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
                    <input class="w-4 h-4 shop-checkbox shrink-0" name="reason" required type="radio" value="spam"/>
                    <span class="font-body-sm text-body-sm text-on-surface">{{ __('Spam or promotion') }}</span>
                    </label>
                <label class="flex items-center gap-sm py-sm border-b border-outline-variant cursor-pointer">
                    <input class="w-4 h-4 shop-checkbox shrink-0" name="reason" required type="radio" value="harassment"/>
                    <span class="font-body-sm text-body-sm text-on-surface">{{ __('Harassment or offensive content') }}</span>
                    </label>
                <label class="flex items-center gap-sm py-sm cursor-pointer">
                    <input class="w-4 h-4 shop-checkbox shrink-0" name="reason" required type="radio" value="irrelevant"/>
                    <span class="font-body-sm text-body-sm text-on-surface">{{ __('Irrelevant to the product') }}</span>
                    </label>
                <div class="flex gap-gutter mt-lg">
                    <button class="flex-1 h-12 border border-secondary text-secondary font-label-caps text-label-caps uppercase tracking-widest hover:bg-secondary/5 transition-colors" onclick="closeReport()" type="button">{{ __('Cancel') }}</button>
                    <button class="flex-1 h-12 btn-gold font-label-caps text-label-caps uppercase tracking-widest flex items-center justify-center" type="submit">{{ __('Send report') }}</button>
                    </div>
                </form>
            <div class="hidden text-center py-md" id="report-success">
                <span class="material-symbols-outlined text-secondary text-[48px]">check_circle</span>
                <p class="font-body-lg text-body-lg text-on-surface mt-sm mb-lg">{{ __('Thank you. Your report has been submitted.') }}</p>
                <button class="w-full h-12 btn-gold font-label-caps text-label-caps uppercase tracking-widest flex items-center justify-center" onclick="closeReport()" type="button">{{ __('Close') }}</button>
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
        document.querySelectorAll('.btn-gold').forEach(function (b) {
            b.addEventListener('click', function () {
                b.classList.remove('flashing');
                void b.offsetWidth;
                b.classList.add('flashing');
                setTimeout(function () { b.classList.remove('flashing'); }, 600);
            });
        });
        window.goWishlist = function (btn) {
            event.stopPropagation();
            window.location.href = btn.dataset.url;
        };
        document.addEventListener('DOMContentLoaded', function () {
            var els = document.querySelectorAll('.reveal-up');
            if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('is-visible'); }); return; }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } });
            }, { threshold: 0.1 });
            els.forEach(function (e) { io.observe(e); });
        });
    </script>
    @include('customer._partials.drawer')
    </body></html>
