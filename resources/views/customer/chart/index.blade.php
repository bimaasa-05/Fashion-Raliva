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
        /* Custom Checkbox */
        .checkbox-custom {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 1px solid #111111;
            border-radius: 0;
            background-color: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .checkbox-custom:checked {
            background-color: #111111;
        }
        .checkbox-custom:checked::after {
            content: '';
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 1.5px 1.5px 0;
            transform: rotate(45deg);
            margin-bottom: 2px;
        }
        html.theme-dark .checkbox-custom { border-color: #e6e4e1; }
        html.theme-dark .checkbox-custom:checked { background-color: #f2efec; }
        html.theme-dark .checkbox-custom:checked::after { border-color: #1b1a19; }
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
<body class="bg-background text-on-background min-h-screen flex flex-col font-body-lg">
<!-- TopAppBar (Small Center Aligned) -->
<header class="bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 pt-safe border-b border-[var(--chrome-border)] sticky top-0 z-40">
<a href="{{ url()->previous() }}" class="w-10 h-10 flex items-center justify-center -ml-2 hover:opacity-80 transition-opacity">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">arrow_back_ios_new</span>
</a>
<div class="flex-1 flex justify-center">
<h1 class="font-title-md text-title-md uppercase text-[var(--chrome-accent)]">{{ __('CART (2)') }}</h1>
</div>
<button class="w-10 h-10 flex items-center justify-center -mr-2 text-on-surface hover:opacity-80 transition-opacity">
<!-- Empty trailing icon space to balance header -->
</button>
</header>
<!-- Main Content Canvas -->
<main class="flex-1 overflow-y-auto pb-32">
<!-- Multi-store grouping -->
<section class="mt-md mb-lg">
<!-- Store Header -->
<div class="px-container-margin py-sm border-b border-surface-variant flex items-center gap-sm bg-surface-bright">
<input checked="" class="checkbox-custom" type="checkbox"/>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-on-surface-variant text-lg">storefront</span>
<h2 class="font-label-caps text-label-caps text-primary tracking-widest uppercase">LUNARA FASHION</h2>
</div>
</div>
<!-- Cart Items -->
<div class="flex flex-col">
<!-- Item 1 -->
<div class="px-container-margin py-md flex gap-md border-b border-surface-variant bg-surface relative">
<!-- Item Checkbox -->
<div class="flex items-center absolute left-container-margin top-1/2 -translate-y-1/2 z-10 h-full">
<input checked="" class="checkbox-custom" type="checkbox"/>
</div>
<!-- Item Image -->
<div class="w-24 h-32 ml-8 bg-surface-container-high shrink-0">
<img class="w-full h-full object-cover grayscale opacity-90" data-alt="A detailed, high-end editorial product shot of an oversized linen shirt in pristine white. The shirt is displayed flat against a minimalist light ivory background. The lighting is soft and natural, emphasizing the texture and drape of the high-quality linen fabric. Clean aesthetic, luxury fashion, light mode, highly curated." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDWiUZJp3vyEgKEFlkXYr8uYhmrhd7nc1O76xK1pykK3gv9yZPwpTziMompgTYrsm4pqLGic8maeveknCfmWhiXg6t-TYd4oUUS88SA88hvpuO1oqPwrtzdSK5uxE2dGRAk7ddwVMhlRVWnGVY69afdnijuhxIyk42pjZO5l_6OlXgzNMT7LSAhnhSw3Fv_dyryq875ZFnw76t_UZNNfMrZxV1bzNbmZ7tZuZbvZp7ZkLIOYbtdfEg"/>
</div>
<!-- Item Details -->
<div class="flex-1 flex flex-col justify-between py-xs">
<div class="flex justify-between items-start">
<div>
<h3 class="font-body-sm text-body-sm font-semibold text-primary">Oversized Linen Shirt</h3>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-1">White / M</p>
</div>
<button class="text-on-surface-variant hover:text-error transition-colors p-1 -mr-1 -mt-1">
<span class="material-symbols-outlined text-lg">close</span>
</button>
</div>
<div class="flex justify-between items-end mt-sm">
<p class="font-body-sm text-body-sm text-primary">Rp 289.000</p>
<!-- Quantity Selector -->
<div class="flex items-center border border-outline-variant h-8">
<button class="w-8 h-full flex items-center justify-center text-on-surface-variant hover:bg-surface-variant transition-colors">
<span class="material-symbols-outlined text-sm">remove</span>
</button>
<span class="font-body-sm text-body-sm w-8 text-center">1</span>
<button class="w-8 h-full flex items-center justify-center text-on-surface-variant hover:bg-surface-variant transition-colors">
<span class="material-symbols-outlined text-sm">add</span>
</button>
</div>
</div>
</div>
</div>
<!-- Item 2 -->
<div class="px-container-margin py-md flex gap-md border-b border-surface-variant bg-surface relative">
<!-- Item Checkbox -->
<div class="flex items-center absolute left-container-margin top-1/2 -translate-y-1/2 z-10 h-full">
<input checked="" class="checkbox-custom" type="checkbox"/>
</div>
<!-- Item Image -->
<div class="w-24 h-32 ml-8 bg-surface-container-high shrink-0">
<img class="w-full h-full object-cover grayscale opacity-90" data-alt="A premium fashion photography detail shot of straight fit tailored pants in deep black. The pants are meticulously styled and folded to show the clean lines and premium fabric finish against a stark, bright minimalist backdrop. The aesthetic is modern, editorial, and monochromatic, fitting a luxury light-mode UI." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAifLjZGvt9ItjMEmycs9r7iWxS1B4ilgBtNEE8z5ED0-tdWVGttqEFxXDC860crhxjF5PTSWvJN8rygjldElc_sw1wJQKGFMlcFGvYZ514clTvV3a4ofWHJWF7jhOOqw2d0VpqzT14vr8WZmtCSUC7NsgEDjNARgZ3R8I49iXCd4J0Rkq3r8nOvV5o_Qe4hff0IQJ0bo2dZOCZbVOm8ixnqmW339MD28IxcYUo5rNFsfuGF2aW0Jw"/>
</div>
<!-- Item Details -->
<div class="flex-1 flex flex-col justify-between py-xs">
<div class="flex justify-between items-start">
<div>
<h3 class="font-body-sm text-body-sm font-semibold text-primary">Straight Fit Pants</h3>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-1">Black / M</p>
</div>
<button class="text-on-surface-variant hover:text-error transition-colors p-1 -mr-1 -mt-1">
<span class="material-symbols-outlined text-lg">close</span>
</button>
</div>
<div class="flex justify-between items-end mt-sm">
<p class="font-body-sm text-body-sm text-primary">Rp 329.000</p>
<!-- Quantity Selector -->
<div class="flex items-center border border-outline-variant h-8">
<button class="w-8 h-full flex items-center justify-center text-on-surface-variant hover:bg-surface-variant transition-colors">
<span class="material-symbols-outlined text-sm">remove</span>
</button>
<span class="font-body-sm text-body-sm w-8 text-center">1</span>
<button class="w-8 h-full flex items-center justify-center text-on-surface-variant hover:bg-surface-variant transition-colors">
<span class="material-symbols-outlined text-sm">add</span>
</button>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Order Summary & Checkout Section -->
<section class="bg-surface-container-low px-container-margin py-lg mt-xl">
<h2 class="font-label-caps text-label-caps text-primary tracking-widest uppercase mb-md">{{ __('Order Summary') }}</h2>
<div class="space-y-sm mb-lg">
<div class="flex justify-between font-body-sm text-body-sm text-on-surface-variant">
<span>{{ __('Subtotal (2 items)') }}</span>
<span class="text-primary">Rp 618.000</span>
</div>
<div class="flex justify-between font-body-sm text-body-sm text-on-surface-variant">
<span>{{ __('Estimated Shipping') }}</span>
<span class="text-primary">Rp 18.000</span>
</div>
<div class="w-full h-px bg-outline-variant my-sm"></div>
<div class="flex justify-between font-title-md text-title-md text-primary">
<span>Total</span>
<span>Rp 636.000</span>
</div>
</div>
<a href="{{ route('customer.checkout') }}" class="w-full bg-primary text-on-primary font-label-caps text-label-caps h-14 flex items-center justify-center hover:opacity-90 transition-opacity uppercase tracking-widest">
                {{ __('Checkout') }}
            </a>
</section>
</main>
<!-- BottomNavBar (Label Icon) -->
<nav class="md:hidden bg-[var(--chrome-bg)] text-[var(--chrome-text)] fixed bottom-0 w-full z-50 border-t border-[var(--chrome-border)] shadow-sm flex justify-around items-center h-[72px] px-xs pb-safe">
<!-- Home (Inactive) -->
<a class="flex flex-col items-center justify-center text-[var(--chrome-text-dim)] hover:text-[var(--chrome-accent)] transition-colors w-16" href="{{ route('customer.home') }}">
<span class="material-symbols-outlined mb-1">home</span>
<span class="font-label-sm text-label-sm">{{ __('Home') }}</span>
</a>
<!-- Shop (Inactive) -->
<a class="flex flex-col items-center justify-center text-[var(--chrome-text-dim)] hover:text-[var(--chrome-accent)] transition-colors w-16" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined mb-1">shopping_bag</span>
<span class="font-label-sm text-label-sm">{{ __('Shop') }}</span>
</a>
<!-- Wishlist (Inactive) -->
<a class="flex flex-col items-center justify-center text-[var(--chrome-text-dim)] hover:text-[var(--chrome-accent)] transition-colors w-16" href="{{ route('customer.login', ['redirect' => route('customer.wishlist')]) }}">
<span class="material-symbols-outlined mb-1">favorite</span>
<span class="font-label-sm text-label-sm">{{ __('Wishlist') }}</span>
</a>
<!-- Cart (Active) -->
<a class="flex flex-col items-center justify-center text-[var(--chrome-accent)] hover:text-secondary transition-colors w-16 scale-95 transition-transform" href="{{ route('customer.chart') }}">
<span class="material-symbols-outlined mb-1" style="font-variation-settings: 'FILL' 1;">shopping_cart</span>
<span class="font-label-sm text-label-sm">{{ __('Cart') }}</span>
</a>
<!-- Account (Inactive) -->
<a class="flex flex-col items-center justify-center text-[var(--chrome-text-dim)] hover:text-[var(--chrome-accent)] transition-colors w-16" href="{{ route('customer.login', ['redirect' => route('customer.account')]) }}">
<span class="material-symbols-outlined mb-1">person</span>
<span class="font-label-sm text-label-sm">{{ __('Account') }}</span>
</a>
</nav>
</body></html>