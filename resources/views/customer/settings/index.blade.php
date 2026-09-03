<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Settings') }}</title>
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
                        "secondary-fixed": "#fbe2e9",
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
                        "secondary-fixed-dim": "#f1c2cf",
                        "outline": "#747878",
                        "on-primary-fixed-variant": "#474646",
                        "on-secondary-fixed-variant": "#6D1428",
                        "on-tertiary-fixed": "#1a1c1a",
                        "on-secondary-container": "#8B1E3F",
                        "inverse-on-surface": "#f2f0f0",
                        "tertiary-fixed-dim": "#c7c6c4",
                        "tertiary-fixed": "#e3e2df",
                        "surface-container-high": "#e9e8e7",
                        "on-secondary-fixed": "#5e0f23",
                        "background": "#fbf9f9",
                        "surface": "#fbf9f9",
                        "secondary-container": "#fbe2e9",
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
        --chrome-accent: #8B1E3F;
        --surface-ivory: #F8F6F2;
        --border-soft: #E5E1DA;
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
        --border-soft: rgba(255,255,255,.1);
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
    html.theme-dark .border-secondary { border-color: #f2efec !important; }
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
    html.theme-dark .hover\:border-secondary:hover { border-color: #f2efec !important; }
    html.theme-dark .hover\:border-on-surface:hover { border-color: #e6e4e1 !important; }
    html.theme-dark .hover\:border-outline:hover { border-color: #4a4844 !important; }
    html.theme-dark .focus\:border-secondary:focus { border-color: #f2efec !important; }
    html.theme-dark .focus\:border-on-surface:focus { border-color: #e6e4e1 !important; }
    html.theme-dark .focus\:border-outline:focus { border-color: #4a4844 !important; }
    html.theme-dark .group:hover .group-hover\:text-primary { color: #f2efec !important; }
    html.theme-dark .group:hover .group-hover\:border-outline { border-color: #4a4844 !important; }
    html.theme-dark .peer:checked ~ .peer-checked\:bg-primary { background-color: #f2efec !important; }
    html.theme-dark .peer:checked ~ .after\:bg-surface::after { background-color: #161514 !important; }

  </style>
<style>
  /* ===== Premium cards + burgundy accents (parity with home/account) ===== */
  .card-premium { box-shadow:0 1px 2px rgb(17 17 17 / .04),0 12px 32px -16px rgb(17 17 17 / .16); transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease; }
  .card-premium:hover { transform:translateY(-3px); box-shadow:0 2px 4px rgb(17 17 17 / .05),0 20px 48px -20px rgb(17 17 17 / .22); border-color:rgba(139,30,63,.45); }
  html.theme-dark .card-premium { background-color:var(--surface-ivory); border-color:var(--border-soft); box-shadow:0 1px 2px rgb(0 0 0 / .3),0 8px 24px -12px rgb(0 0 0 / .5); }
  html.theme-dark .card-premium:hover { box-shadow:0 2px 4px rgb(0 0 0 / .4),0 20px 48px -20px rgb(0 0 0 / .7); border-color:rgba(139,30,63,.55); }
  .premium-heading { display:block; }
  .premium-heading::before { content:''; display:inline-block; width:4px; height:.95em; margin-right:.65rem; background:#8B1E3F; border-radius:9999px; vertical-align:-.05em; }
  .atl-eyebrow { display:inline-flex; align-items:center; gap:.65rem; }
  .atl-eyebrow::before { content:''; width:30px; height:1px; background:var(--chrome-accent); opacity:.7; }
  html.theme-dark .premium-heading::before { background:#8B1E3F; }
  /* ===== Primary solid button (parity with Home SUBSCRIBE) ===== */
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
  /* ===== Danger solid button (same component shape as .btn-gold, red) ===== */
  .btn-danger {
    position: relative;
    overflow: hidden;
    background-color: var(--btn-danger-bg) !important;
    color: var(--btn-danger-text) !important;
  }
  .btn-danger::after {
    content: '';
    position: absolute;
    top: -10%;
    bottom: -10%;
    left: -80%;
    width: 45%;
    background: rgba(255,255,255,.5);
    transform: skewX(-24deg);
    pointer-events: none;
  }
  .btn-danger:hover::after { animation: authFlash 1.4s linear infinite; }
  .btn-danger.flashing::after { animation: authFlash 1.4s cubic-bezier(.4,0,.2,1) 1; }
  :root           { --btn-danger-bg: #8B1E3F; --btn-danger-text: #ffffff; }
  html.theme-dark { --btn-danger-bg: #6D1428; --btn-danger-text: #ffffff; }
  /* ===== Drawer / sidebar burgundy — match other customer pages (rgba(139,30,30)) ===== */
  #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(139,30,30,.10); }
  html.theme-dark #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(163,38,38,.16); }
  /* ===== Drawer / chrome burgundy remap ===== */
  html.theme-dark .bg-chrome-burgundy { background-color:#8B1E3F !important; }
  html.theme-dark .text-chrome-burgundy { color:#8B1E3F !important; }
  .border-burgundy { border-color:#8B1E3F !important; }
  /* ===== Scroll reveal ===== */
  .reveal-up { opacity:0; transform:translateY(12px); transition:opacity .5s ease,transform .5s ease; }
  .reveal-up.is-visible { opacity:1; transform:none; }
  @media (prefers-reduced-motion: reduce) { .reveal-up { opacity:1; transform:none; transition:none; } }
</style>
  </head>
<body class="bg-surface text-on-surface antialiased font-body-lg lg:pl-72">
<!-- TopAppBar -->
<header class="fixed top-0 inset-x-0 lg:left-72 z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)]">
<a href="{{ route('customer.account') }}" aria-label="Back" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase flex-1 text-center truncate max-w-[240px]">{{ __('Settings') }}</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-xl w-full">
<section class="py-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
<p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">PREFERENCES</p>
<h2 class="premium-heading font-headline-md text-headline-md text-on-surface mb-md">Settings</h2>
<div class="flex flex-col gap-sm">
<!-- Notifications Preferences -->
<section class="px-container-margin py-lg border-b border-outline-variant">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-sm">{{ __('Notifications') }}</h2>
<label class="flex items-center justify-between py-sm border-b border-outline-variant cursor-pointer">
<span>
<span class="font-body-lg text-body-lg text-on-surface block">{{ __('Promo Emails') }}</span>
<span class="font-label-sm text-label-sm text-on-surface-variant block">{{ __('Sales, discounts and exclusive offers') }}</span>
</span>
<input checked="" class="sr-only peer" type="checkbox"/>
<span class="relative shrink-0 w-10 h-6 rounded-full bg-outline-variant peer-checked:bg-secondary transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-surface after:shadow-sm after:transition-transform peer-checked:after:translate-x-4"></span>
</label>
<label class="flex items-center justify-between py-sm border-b border-outline-variant cursor-pointer">
<span>
<span class="font-body-lg text-body-lg text-on-surface block">{{ __('Push Notifications') }}</span>
<span class="font-label-sm text-label-sm text-on-surface-variant block">{{ __('Order updates and delivery status') }}</span>
</span>
<input checked="" class="sr-only peer" type="checkbox"/>
<span class="relative shrink-0 w-10 h-6 rounded-full bg-outline-variant peer-checked:bg-secondary transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-surface after:shadow-sm after:transition-transform peer-checked:after:translate-x-4"></span>
</label>
<label class="flex items-center justify-between py-sm cursor-pointer">
<span>
<span class="font-body-lg text-body-lg text-on-surface block">{{ __('Newsletter') }}</span>
<span class="font-label-sm text-label-sm text-on-surface-variant block">{{ __('Editorial insights, once a month') }}</span>
</span>
<input class="sr-only peer" type="checkbox"/>
<span class="relative shrink-0 w-10 h-6 rounded-full bg-outline-variant peer-checked:bg-secondary transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-surface after:shadow-sm after:transition-transform peer-checked:after:translate-x-4"></span>
</label>
</section>
<!-- Language -->
<section class="px-container-margin py-lg border-b border-outline-variant">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-md">{{ __('Language') }}</h2>
<form id="language-form" action="{{ route('customer.locale.switch') }}" method="POST">
@csrf
<div class="grid grid-cols-2 gap-gutter">
<label class="flex items-center justify-center py-sm border-2 {{ app()->getLocale() === 'en' ? 'border-secondary' : 'border-outline-variant' }} rounded-DEFAULT bg-surface-container-low cursor-pointer hover:border-secondary transition-colors">
<input {{ app()->getLocale() === 'en' ? 'checked' : '' }} class="sr-only" name="locale" type="radio" value="en" onchange="document.getElementById('language-form').submit()"/>
<span class="material-symbols-outlined text-[20px] mr-xs">language</span>
<span class="font-body-sm text-body-sm {{ app()->getLocale() === 'en' ? 'font-semibold' : '' }}">English</span>
</label>
<label class="flex items-center justify-center py-sm border-2 {{ app()->getLocale() === 'id' ? 'border-secondary' : 'border-outline-variant' }} rounded-DEFAULT bg-surface-container-low cursor-pointer hover:border-secondary transition-colors">
<input {{ app()->getLocale() === 'id' ? 'checked' : '' }} class="sr-only" name="locale" type="radio" value="id" onchange="document.getElementById('language-form').submit()"/>
<span class="material-symbols-outlined text-[20px] mr-xs">translate</span>
<span class="font-body-sm text-body-sm {{ app()->getLocale() === 'id' ? 'font-semibold' : '' }}">Bahasa Indonesia</span>
</label>
</div>
</form>
</section>
<!-- Appearance -->
<section class="px-container-margin py-lg border-b border-outline-variant">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-sm">{{ __('Appearance') }}</h2>
<label class="flex items-center justify-between py-sm cursor-pointer">
<span>
<span class="font-body-lg text-body-lg text-on-surface block">{{ __('Dark Mode') }}</span>
<span class="font-label-sm text-label-sm text-on-surface-variant block">{{ __('Header, menu dan navigasi berwarna gelap') }}</span>
</span>
<input checked="" class="sr-only peer" id="dark-mode-toggle" onchange="onThemeToggle(this)" type="checkbox"/>
<span class="relative shrink-0 w-10 h-6 rounded-full bg-outline-variant peer-checked:bg-secondary transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-surface after:shadow-sm after:transition-transform peer-checked:after:translate-x-4"></span>
</label>
</section>
<!-- Danger Zone -->
<section class="px-container-margin py-lg">
<h2 class="font-label-caps text-label-caps text-error uppercase tracking-widest mb-md">{{ __('Danger Zone') }}</h2>
<button class="btn-danger w-full font-label-caps text-label-caps py-3 lg:px-xl rounded-full uppercase tracking-widest flex items-center justify-center gap-2" type="button">
<span class="material-symbols-outlined text-[20px]">delete_forever</span>
<span>{{ __('Delete Account') }}</span>
</button>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-sm text-center">{{ __('This action is permanent and cannot be undone.') }}</p>
</section>
</div>
</div>
</div>
</section>
</main>
<script>
        function onThemeToggle(cb) {
            document.documentElement.classList.toggle('theme-dark', cb.checked);
            localStorage.setItem('raliva-theme', cb.checked ? 'dark' : 'light');
        }
        (function () {
            var cb = document.getElementById('dark-mode-toggle');
            if (cb) cb.checked = document.documentElement.classList.contains('theme-dark');
        })();
    </script>
@include('customer._partials.drawer')
</body></html>
