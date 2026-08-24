<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Change Password') }}</title>
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
<a href="{{ route('customer.account.edit') }}" aria-label="{{ __('Back') }}" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase flex-1 text-center truncate max-w-[240px]">{{ __('Change Password') }}</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-40 px-container-margin max-w-2xl mx-auto w-full">
<form>
<section class="py-lg">
<div class="flex flex-col gap-md">
<!-- Current Password -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="current-password">{{ __('Current Password') }}</label>
<div class="relative">
<input autocomplete="current-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="current-password" placeholder="{{ __('Enter your current password') }}" type="password"/>
<span aria-label="{{ __('Show password') }}" class="absolute right-md top-1/2 -translate-y-1/2 cursor-pointer text-on-surface-variant hover:text-on-surface transition-colors flex" role="button" tabindex="0">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</span>
</div>
</div>
<!-- New Password -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="new-password">{{ __('New Password') }}</label>
<div class="relative">
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="new-password" placeholder="{{ __('Enter a new password') }}" type="password"/>
<span aria-label="{{ __('Show password') }}" class="absolute right-md top-1/2 -translate-y-1/2 cursor-pointer text-on-surface-variant hover:text-on-surface transition-colors flex" role="button" tabindex="0">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</span>
</div>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">{{ __("Choose a strong password you don't use elsewhere.") }}</p>
</div>
<!-- Confirm New Password -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="confirm-password">{{ __('Confirm New Password') }}</label>
<div class="relative">
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="confirm-password" placeholder="{{ __('Re-enter your new password') }}" type="password"/>
<span aria-label="{{ __('Show password') }}" class="absolute right-md top-1/2 -translate-y-1/2 cursor-pointer text-on-surface-variant hover:text-on-surface transition-colors flex" role="button" tabindex="0">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</span>
</div>
</div>
</div>
</section>
<!-- Password Requirements -->
<section class="pb-lg">
<div class="bg-surface-container-low border border-outline-variant rounded-DEFAULT p-md">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-sm">{{ __('Password Requirements') }}</h2>
<ul class="flex flex-col gap-xs">
<li class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]" data-icon="check_circle" style="color: #795905;">check_circle</span>
<span class="font-body-sm text-body-sm text-on-surface">{{ __('At least 8 characters long') }}</span>
</li>
<li class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]" data-icon="check_circle" style="color: #795905;">check_circle</span>
<span class="font-body-sm text-body-sm text-on-surface">{{ __('Contains at least 1 uppercase letter') }}</span>
</li>
<li class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]" data-icon="check_circle" style="color: #795905;">check_circle</span>
<span class="font-body-sm text-body-sm text-on-surface">{{ __('Contains at least 1 number') }}</span>
</li>
<li class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px] text-outline-variant" data-icon="radio_button_unchecked">radio_button_unchecked</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Recommended: include 1 special character (!@#$%)') }}</span>
</li>
</ul>
</div>
</section>
<!-- Security Tip -->
<section class="pb-lg">
<div class="flex items-start gap-sm">
<span class="material-symbols-outlined text-secondary text-[20px] mt-1" data-icon="shield">shield</span>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
            {{ __('After changing your password, you will stay signed in on this device but will need to sign in again on other devices.') }}
            </p>
</div>
</section>
</form>
</main>
<!-- Fixed Bottom Action Bar -->
<div class="fixed bottom-0 inset-x-0 lg:left-72 bg-surface border-t border-outline-variant px-container-margin py-md z-50 max-w-2xl mx-auto">
<a href="{{ route('customer.account') }}" class="w-full bg-primary text-on-primary font-label-caps text-label-caps h-14 flex items-center justify-center hover:opacity-90 transition-opacity uppercase tracking-widest">
            {{ __('Update Password') }}
        </a>
</div>
@include('customer._partials.drawer')
</body></html>
