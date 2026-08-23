<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Checkout') }}</title>
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
        body { background-color: #fbf9f9; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0; }
        /* Safe area padding for mobile bottom nav */
        .pb-safe { padding-bottom: env(safe-area-inset-bottom, 20px); }
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
<body class="text-on-background bg-background font-body-sm overflow-x-hidden pb-32">
<!-- Top App Bar -->
<header class="flex justify-between items-center w-full px-container-margin h-16 bg-[var(--chrome-bg)] text-[var(--chrome-text)] border-b border-[var(--chrome-border)] fixed top-0 z-40">
<a aria-label="{{ __('Back') }}" href="{{ route('customer.chart') }}" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)]">{{ __('CHECKOUT') }}</h1>
<div class="w-6"></div> <!-- Spacer for centering -->
</header>
<main class="mt-16 px-container-margin pt-lg max-w-2xl mx-auto">
<!-- Delivery Address -->
<section class="mb-lg">
<div class="flex justify-between items-center mb-sm">
<h2 class="font-title-md text-title-md">{{ __('Delivery Address') }}</h2>
<a href="{{ route('customer.address') }}" class="font-label-caps text-label-caps text-on-surface-variant hover:text-primary transition-colors underline">{{ __('Edit') }}</a>
</div>
<div class="bg-surface-container-low p-md rounded-DEFAULT border border-outline-variant">
<div class="flex items-start gap-sm">
<span class="material-symbols-outlined text-outline mt-1" data-icon="location_on">location_on</span>
<div>
<p class="font-body-lg text-body-lg font-semibold mb-1">Jane Doe</p>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                            123 Fashion Avenue, Suite 4B<br/>
                            Jakarta Selatan, DKI Jakarta 12190<br/>
                            +62 812 3456 7890
                        </p>
</div>
</div>
</div>
</section>
<!-- Order Items Summary (Brief) -->
<section class="mb-lg">
<h2 class="font-title-md text-title-md mb-sm">{{ __('Order Items (2)') }}</h2>
<div class="flex gap-sm overflow-x-auto pb-sm no-scrollbar">
<div class="flex-shrink-0 w-24 h-32 bg-surface-container border border-outline-variant rounded-DEFAULT overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A minimalist, high-end editorial product shot of a sleek black silk slip dress hanging on a slender metallic hanger against a soft ivory studio background. The lighting is bright and diffuse, casting very soft shadows to emphasize the fabric's fluid texture." src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4G81Gtat5BKzpijwKFnCEhnYyz8ZuXSv5z9_-yaV3L6pPWN-ZLMlKk9D8CfOxlwW2h6_XSvZwA7lQs70AN-q2tlMInMu3xk9wRY7JFzyBFLosxtY9SDjAPSFJ29WFtFv3L3jRAaaKHH53OR30tGk1y6zfjcPJGmZSls-Dzh_ZeiqfLEGhkyh5MobBab8pFvyHdKJ2z2pMdKjElHU1812vN10nL0Bqqb04HqRY_Xvz-PZ9w_qEJOg"/>
</div>
<div class="flex-shrink-0 w-24 h-32 bg-surface-container border border-outline-variant rounded-DEFAULT overflow-hidden">
<img class="w-full h-full object-cover" data-alt="A luxurious, minimalist product photograph featuring a structured cream-colored leather tote bag placed on a clean white plinth. Soft, directional light highlights the subtle grain of the leather and the minimal gold hardware, set against a pristine, light-mode background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDYD6gQ9MzXtJCmq7ifPSDGi9tdMZoHxGUeIA_ZxbwzAb0BrMgldkXslIC5mq53UYgJAVDJnKm1WXR5OdHGr00iFE0HHrfb45MKIEXmGtafzdl5VzVYKkZ4jt2emMmDEANdFL1lx_1DlPkkbxfZpN42dtgm72-WjHKu59ktKb2LkwQ_Hd-Rba4IfJJjqZHJUOBA6rB6RebtR36JFk-HARhgfgNjIsxqk0PW3Xrdij1s5VE02H9JBhI"/>
</div>
</div>
</section>
<!-- Shipping Method -->
<section class="mb-lg">
<h2 class="font-title-md text-title-md mb-sm">{{ __('Shipping Method') }}</h2>
<div class="space-y-gutter">
<!-- Option 1 -->
<label class="flex items-center justify-between p-sm border border-outline-variant rounded-DEFAULT cursor-pointer hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-sm">
<div class="w-4 h-4 rounded-full border border-primary flex items-center justify-center"></div>
<div>
<p class="font-body-sm text-body-sm font-semibold">{{ __('Regular Delivery') }}</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">3-5 Business Days</p>
</div>
</div>
<span class="font-body-sm text-body-sm">{{ __('Free') }}</span>
</label>
<!-- Option 2 -->
<label class="flex items-center justify-between p-sm border-2 border-primary rounded-DEFAULT cursor-pointer bg-surface-container-low">
<div class="flex items-center gap-sm">
<div class="w-4 h-4 rounded-full border-2 border-primary flex items-center justify-center">
<div class="w-2 h-2 rounded-full bg-primary"></div>
</div>
<div>
<p class="font-body-sm text-body-sm font-semibold">{{ __('Express Delivery') }}</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">{{ __('1-2 Business Days') }}</p>
</div>
</div>
<span class="font-body-sm text-body-sm">Rp 35.000</span>
</label>
</div>
</section>
<!-- Payment Method -->
<section class="mb-xl">
<h2 class="font-title-md text-title-md mb-sm">{{ __('Payment Method') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
<button class="p-md border border-outline-variant rounded-DEFAULT flex flex-col items-center justify-center gap-xs hover:border-primary transition-colors bg-surface">
<span class="material-symbols-outlined text-outline" data-icon="account_balance">account_balance</span>
<span class="font-body-sm text-body-sm">{{ __('Bank Transfer') }}</span>
</button>
<button class="p-md border-2 border-primary rounded-DEFAULT flex flex-col items-center justify-center gap-xs bg-surface-container-low">
<span class="material-symbols-outlined text-primary" data-icon="credit_card">credit_card</span>
<span class="font-body-sm text-body-sm font-semibold">{{ __('Credit Card') }}</span>
</button>
<button class="p-md border border-outline-variant rounded-DEFAULT flex flex-col items-center justify-center gap-xs hover:border-primary transition-colors bg-surface">
<span class="material-symbols-outlined text-outline" data-icon="account_balance_wallet">account_balance_wallet</span>
<span class="font-body-sm text-body-sm">{{ __('E-Wallet') }}</span>
</button>
</div>
<!-- Card Details (Mock) -->
<div class="mt-sm p-md border border-outline-variant rounded-DEFAULT bg-surface">
<div class="flex justify-between items-center mb-sm">
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined text-outline text-[20px]" data-icon="credit_card">credit_card</span>
<span class="font-body-sm text-body-sm">**** **** **** 4242</span>
</div>
<span class="material-symbols-outlined text-outline text-[20px]" data-icon="check_circle">check_circle</span>
</div>
<button class="font-label-caps text-label-caps text-on-surface-variant hover:text-primary underline">{{ __('Change Card') }}</button>
</div>
</section>
</main>
<!-- Fixed Bottom Action Area (Order Summary & CTA) -->
<div class="fixed bottom-0 left-0 w-full bg-surface border-t border-outline-variant px-container-margin py-md pb-safe z-50 flex flex-col md:flex-row justify-between items-center gap-md shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
<div class="w-full md:w-auto">
<p class="font-label-sm text-label-sm text-on-surface-variant mb-1">{{ __('Total Payment') }}</p>
<p class="font-title-md text-title-md">Rp 636.000</p>
</div>
<a href="{{ route('customer.order-tracking') }}" class="w-full md:w-auto px-xl py-sm bg-primary text-on-primary font-label-caps text-label-caps rounded-DEFAULT hover:opacity-90 transition-opacity flex justify-center items-center">
            {{ __('PLACE ORDER') }}
        </a>
</div>
</body></html>