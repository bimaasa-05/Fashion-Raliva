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
        html.theme-dark .timeline-active-circle { box-shadow: 0 0 0 4px rgba(163,38,38,.16); }
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
    :root           { --btn-gold-bg: #8B1E1E; --btn-gold-text: #ffffff; }
    html.theme-dark { --btn-gold-bg: #5E0F0F; --btn-gold-text: #ffffff; }
</style>
<style>
    /* ============ Order-Tracking: remap drawer + bottom-nav accent to burgundy (parity home/shop) ============ */
    #drawer-panel { --chrome-accent: #8B1E1E; --gold-wash: rgba(139,30,30,.10); }
    html.theme-dark #drawer-panel { --chrome-accent: #A32626; --gold-wash: rgba(163,38,38,.16); }
    .bn-active .material-symbols-outlined { color: #8B1E1E !important; }
    html.theme-dark .bn-active .material-symbols-outlined { color: #A32626 !important; }
    .bn-active { color: #8B1E1E !important; }
    html.theme-dark .bn-active { color: #A32626 !important; }

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
</style>
  </head>
<body class="bg-surface text-on-surface antialiased font-body-lg flex flex-col min-h-screen pb-[72px] md:pb-0 lg:pl-72 overflow-x-hidden">
<!-- TopAppBar (parity home/shop - fixed with burgundy) -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)]">
<a href="{{ auth()->check() ? route('customer.account') : route('login', ['redirect' => route('customer.account')]) }}" class="w-10 h-10 flex items-center justify-start hover:opacity-80 transition-opacity">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase text-center flex-1">ORDER TRACKING</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content Canvas -->
<main class="flex-grow pt-16 pb-8 lg:pb-12 w-full overflow-x-hidden">
<!-- Order Header -->
<section class="px-container-margin py-lg md:py-xl border-b border-outline-variant reveal-up">
<div class="max-w-[960px] mx-auto">
<div class="atl-eyebrow mb-sm">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Order Tracking') }}</span>
</div>
<div class="flex flex-wrap justify-between items-end gap-sm">
<div>
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-1">{{ __('Order ID') }}</p>
<p class="font-title-md text-title-md md:text-headline-md font-semibold text-on-surface tracking-tight">#RLV-240501-1234</p>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">May 1, 2024 • {{ __('2 items') }}</p>
</div>
<div class="text-left md:text-right">
<div class="inline-flex items-center gap-xs px-sm py-1 rounded-full bg-secondary/10 border border-secondary/15">
<span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
<span class="font-label-sm text-label-sm text-secondary uppercase tracking-wider font-semibold">{{ __('Preparing') }}</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 md:text-right">{{ __('Est. delivery: May 5–7, 2024') }}</p>
</div>
</div>
</div>
</section>
<!-- Visual Tracking Timeline -->
<section class="px-container-margin py-lg md:py-xl bg-surface reveal-up">
<div class="max-w-[960px] mx-auto">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl md:rounded-2xl p-md md:p-lg shadow-sm">
<div class="relative max-w-[480px] mx-auto">
<div class="timeline-line"></div>
<div class="timeline-progress" style="width: 0%;"></div>
<div class="flex justify-between gap-2 relative z-10">
<!-- Step 1: Preparing (Active) -->
<div class="flex flex-col items-center gap-1 group cursor-pointer flex-1">
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full flex items-center justify-center bg-surface transition-colors timeline-active-circle shrink-0" style="border: 2px solid var(--chrome-accent);">
<div class="w-2.5 h-2.5 md:w-2 md:h-2 rounded-full timeline-active-dot" style="background-color: var(--chrome-accent);"></div>
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface text-center leading-tight timeline-active-label" style="color: var(--chrome-accent);">{{ __('Preparing') }}</span>
</div>
<!-- Step 2: Packed -->
<div class="flex flex-col items-center gap-1 group cursor-pointer flex-1">
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full bg-surface border border-outline-variant flex items-center justify-center shrink-0 transition-colors group-hover:border-outline">
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface-variant text-center leading-tight">{{ __('Packed') }}</span>
</div>
<!-- Step 3: Shipped -->
<div class="flex flex-col items-center gap-1 group cursor-pointer flex-1">
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full bg-surface border border-outline-variant flex items-center justify-center shrink-0 transition-colors group-hover:border-outline">
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface-variant text-center leading-tight">{{ __('Shipped') }}</span>
</div>
<!-- Step 4: Delivered -->
<div class="flex flex-col items-center gap-1 group cursor-pointer flex-1">
<div class="w-7 h-7 md:w-6 md:h-6 rounded-full bg-surface border border-outline-variant flex items-center justify-center shrink-0 transition-colors group-hover:border-outline">
</div>
<span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface-variant text-center leading-tight">{{ __('Delivered') }}</span>
</div>
</div>
</div>
<!-- Current Status Detail -->
<div class="mt-lg text-center bg-surface-container-low p-md border border-outline-variant rounded-xl shadow-sm">
<h3 class="font-title-md text-title-md text-on-surface mb-xs">{{ __("We're getting your order ready") }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Your items are currently being processed in our warehouse and will be packed shortly.') }}</p>
</div>
</div>
</div>
</section>
<!-- Order Items List -->
<section class="px-container-margin py-lg md:py-xl border-t border-outline-variant reveal-up">
<div class="max-w-[960px] mx-auto">
<div class="flex items-center justify-between mb-md">
<div>
<div class="atl-eyebrow mb-1">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Order Items') }}</span>
</div>
<h2 class="font-title-md md:font-headline-md text-title-md md:text-headline-md text-on-surface">{{ __('Items in Order') }} <span class="font-body-sm text-body-sm text-on-surface-variant font-normal">• 2 items</span></h2>
</div>
<a href="{{ route('customer.shop') }}" class="hidden md:inline-flex items-center gap-1 font-label-sm text-label-sm text-secondary hover:text-secondary/80 transition-colors">{{ __('Browse Shop') }} <span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
</div>
<div class="flex flex-col gap-sm md:gap-md">
<!-- Item 1 -->
<div class="group flex gap-sm md:gap-md bg-surface-container-lowest border border-outline-variant rounded-xl p-sm md:p-md hover:shadow-sm hover:border-outline/50 transition-all">
<div class="w-24 h-32 md:w-28 md:h-36 bg-surface-container rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/30">
<img class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500" loading="lazy" data-alt="A luxurious, minimalist product shot of an oversized linen shirt in a pristine white color, styled neatly folded against a stark white background with soft, diffused high-key studio lighting. The texture of the premium linen is highlighted, evoking a sophisticated, modern, and editorial fashion aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJlZAPwP-_xQgZVO14xE3Iu5npa30PNtPh-mhwqhHFy8WGyDIB88pqpUCiHtphw4m9zlFgrt_ycF_HlVLz0pUDshR1F3eqGVZYdqp4_qIihhITt0Blr3kgQOXjSp8iPBaotQEQIy3fGR8WUWbYK9JzLS_tYGjRSwZ8AMmViSKwigSNj1QeNqICwi6gOfkES04iTCN8Q-PxuntGupL9_rET-Zmjx_exlyS_3ai4_QXi8XxmMbFdQq4"/>
</div>
<div class="flex flex-col justify-between py-1 flex-grow min-w-0">
<div class="min-w-0">
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">RALIVA</p>
<h3 class="font-body-sm md:font-title-md text-body-sm md:text-title-md font-semibold text-on-surface truncate">Oversized Linen Shirt</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 flex flex-wrap gap-2"><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container-low border border-outline-variant text-xs">{{ __('White') }} • M</span></p>
</div>
<div class="flex justify-between items-center mt-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Qty: 1') }}</p>
<p class="font-body-sm md:font-title-md text-body-sm md:text-title-md font-semibold text-on-surface">$145.00</p>
</div>
</div>
</div>
<!-- Item 2 -->
<div class="group flex gap-sm md:gap-md bg-surface-container-lowest border border-outline-variant rounded-xl p-sm md:p-md hover:shadow-sm hover:border-outline/50 transition-all">
<div class="w-24 h-32 md:w-28 md:h-36 bg-surface-container rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/30">
<img class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500" loading="lazy" data-alt="A high-end editorial product photograph of tailored straight fit pants in deep black, sharply folded and presented on a minimalist gray textured surface. The lighting is crisp and dramatic, highlighting the structural integrity and premium fabric of the garment, perfectly suited for a luxury fashion marketplace." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBjIMzWrbFLT6KKutzy6PhQHHWEnYzrTgY-dKZrde1_cde8Vn6WRo8ZqI5d6eCMRbNv0V1GLwD501aHhMDfqY2xyaMilKOQFanRXb_E2Lgr0zm2E07fnIj01Ek7udmiq8489lpf4z38jLzFHJTW_XAKLUZO5a5fvIj1yMCqo1OlmFisVMzLkqVYJ0_nUJurX6Us8b3nT34YQMqNISCpv1fVNHyXNZkUn8-wgCUk-2yx6KcGEJn3Ncs"/>
</div>
<div class="flex flex-col justify-between py-1 flex-grow min-w-0">
<div class="min-w-0">
<p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">RALIVA</p>
<h3 class="font-body-sm md:font-title-md text-body-sm md:text-title-md font-semibold text-on-surface truncate">Straight Fit Pants</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-1 flex flex-wrap gap-2"><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container-low border border-outline-variant text-xs">{{ __('Black') }} • 32</span></p>
</div>
<div class="flex justify-between items-center mt-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Qty: 1') }}</p>
<p class="font-body-sm md:font-title-md text-body-sm md:text-title-md font-semibold text-on-surface">$210.00</p>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Order Summary (Tonal Background) -->
<section class="px-container-margin py-lg md:py-xl bg-surface reveal-up">
<div class="max-w-[960px] mx-auto">
<div class="bg-surface-container-low border border-outline-variant rounded-xl md:rounded-2xl p-md md:p-lg shadow-sm">
<div class="flex items-center gap-sm mb-md">
<div class="w-8 h-8 rounded-full bg-secondary/10 border border-secondary/15 flex items-center justify-center">
<span class="material-symbols-outlined text-secondary text-[18px]">receipt_long</span>
</div>
<h2 class="font-title-md md:font-headline-md text-title-md md:text-headline-md text-on-surface">{{ __('Order Summary') }}</h2>
</div>
<div class="flex flex-col gap-sm">
<div class="flex justify-between py-1">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Subtotal') }}</p>
<p class="font-body-sm text-body-sm text-on-surface font-medium">$355.00</p>
</div>
<div class="flex justify-between py-1">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Shipping') }}</p>
<span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#e8f5e9] text-[#2e7d32] dark:bg-[#1b3a1f] dark:text-[#a5d6a7] font-label-sm text-label-sm font-semibold">{{ __('Free') }}</span>
</div>
<div class="flex justify-between py-1">
<p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Tax') }}</p>
<p class="font-body-sm text-body-sm text-on-surface font-medium">$31.95</p>
</div>
<div class="w-full h-px bg-outline-variant my-md"></div>
<div class="flex justify-between items-center py-1">
<p class="font-title-md text-title-md text-on-surface font-semibold">{{ __('Total') }}</p>
<p class="font-headline-md text-headline-md text-on-surface">$386.95</p>
</div>
<p class="font-label-sm text-label-sm text-on-surface-variant/70 text-right mt-1">{{ __('Shipping & taxes calculated at checkout') }}</p>
</div>
</div>
</div>
</section>
<!-- Need Help Action -->
<section class="px-container-margin py-lg md:py-xl text-center reveal-up">
<div class="max-w-[960px] mx-auto">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md md:p-lg">
<div class="atl-eyebrow mb-sm justify-center">
<span class="font-label-caps text-label-caps uppercase tracking-widest text-secondary">{{ __('Need Help?') }}</span>
</div>
<h3 class="font-title-md text-title-md text-on-surface mb-xs">{{ __('We’re here for you') }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-md max-w-md mx-auto">{{ __('Questions about your order? Our team typically replies within 2 hours.') }}</p>
<div class="flex flex-col sm:flex-row gap-sm justify-center">
<button class="inline-flex items-center justify-center gap-2 border border-secondary text-secondary font-label-caps text-label-caps px-lg py-sm rounded-full hover:bg-secondary/5 transition-colors" type="button">
<span class="material-symbols-outlined text-[18px]">support_agent</span>
                    {{ __('Contact Customer Service') }}
                </button>
<a href="{{ route('customer.shop') }}" class="btn-gold inline-flex items-center justify-center gap-2 font-label-caps text-label-caps px-lg py-sm rounded-full uppercase tracking-widest">
<span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                    {{ __('Continue Shopping') }}
                </a>
</div>
</div>
</div>
</section>
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