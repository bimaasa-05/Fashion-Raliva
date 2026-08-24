<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Help Center') }}</title>
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
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined[data-weight="fill"] {
            font-variation-settings: 'FILL' 1;
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
<body class="bg-surface text-on-surface antialiased font-body-lg lg:pl-72">
<!-- TopAppBar -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)]">
<a href="{{ route('customer.account') }}" aria-label="Back" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase flex-1 text-center truncate max-w-[240px]">Help Center</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-xl max-w-2xl mx-auto w-full">
<!-- Search -->
<section class="py-lg px-container-margin border-b border-outline-variant">
<div class="relative">
<span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input class="w-full bg-surface-container-low border border-outline-variant rounded-full pl-xl pr-md py-sm font-body-lg text-body-lg text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary transition-colors" placeholder="Search help topics..." type="search"/>
</div>
</section>
<!-- FAQ -->
<section class="border-b border-outline-variant">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest px-container-margin pt-lg pb-sm">{{ __('Frequently Asked Questions') }}</h2>
<details class="group border-b border-outline-variant">
<summary class="flex justify-between items-center px-container-margin py-sm cursor-pointer list-none">
<span class="font-title-md text-title-md text-on-surface">{{ __('How do I track my order?') }}</span>
<span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
</summary>
<div class="px-container-margin pb-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Go to Account → My Orders and select the order you want to follow. You will see the latest delivery status there, from preparing to delivered.
                </p>
</div>
</details>
<details class="group border-b border-outline-variant">
<summary class="flex justify-between items-center px-container-margin py-sm cursor-pointer list-none">
<span class="font-title-md text-title-md text-on-surface">{{ __('What is your return policy?') }}</span>
<span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
</summary>
<div class="px-container-margin pb-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Returns are accepted within 14 days of delivery. Items must be unworn with original tags attached. Start a return by contacting our support team with your order number.
                </p>
</div>
</details>
<details class="group border-b border-outline-variant">
<summary class="flex justify-between items-center px-container-margin py-sm cursor-pointer list-none">
<span class="font-title-md text-title-md text-on-surface">{{ __('Which payment methods do you accept?') }}</span>
<span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
</summary>
<div class="px-container-margin pb-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    We accept bank transfer, major credit cards, and popular e-wallets. All payments are processed securely at checkout.
                </p>
</div>
</details>
<details class="group border-b border-outline-variant">
<summary class="flex justify-between items-center px-container-margin py-sm cursor-pointer list-none">
<span class="font-title-md text-title-md text-on-surface">{{ __('Can I change my delivery address?') }}</span>
<span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
</summary>
<div class="px-container-margin pb-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Yes, as long as the order has not been shipped. Update your saved addresses in Account → Addresses, then contact support so we can apply it to your open order.
                </p>
</div>
</details>
<details class="group border-b border-outline-variant">
<summary class="flex justify-between items-center px-container-margin py-sm cursor-pointer list-none">
<span class="font-title-md text-title-md text-on-surface">{{ __('How do I contact a store?') }}</span>
<span class="material-symbols-outlined text-on-surface group-open:rotate-180 transition-transform">expand_more</span>
</summary>
<div class="px-container-margin pb-sm">
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Open the store page from any product or from Featured Stores on the home page. Store contact options are available on their profile.
                </p>
</div>
</details>
</section>
<!-- Contact Support -->
<section class="py-lg px-container-margin">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-md">{{ __('Still Need Help?') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
<button class="flex items-center gap-sm p-md border border-outline-variant rounded-DEFAULT bg-surface hover:border-primary transition-colors text-left" type="button">
<span class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">chat</span>
</span>
<span>
<span class="font-body-sm text-body-sm font-semibold block">{{ __('WhatsApp') }}</span>
<span class="font-label-sm text-label-sm text-on-surface-variant block">Mon–Fri, 09.00–17.00 WIB</span>
</span>
</button>
<button class="flex items-center gap-sm p-md border border-outline-variant rounded-DEFAULT bg-surface hover:border-primary transition-colors text-left" type="button">
<span class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">mail</span>
</span>
<span>
<span class="font-body-sm text-body-sm font-semibold block">{{ __('Email Us') }}</span>
<span class="font-label-sm text-label-sm text-on-surface-variant block">support@raliva.com</span>
</span>
</button>
</div>
</section>
</main>
@include('customer._partials.drawer')
</body></html>
