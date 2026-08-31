<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Account') }}</title>
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
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
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
</style>
<style>
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
    :root           { --btn-gold-bg: #e8c25a; --btn-gold-text: #261a00; }
    html.theme-dark { --btn-gold-bg: #d9ab4f; --btn-gold-text: #261a00; }
</style>
  </head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] md:pb-0 lg:pl-72">
<!-- TopAppBar -->
<header class="flex justify-between items-center w-full px-container-margin h-16 bg-[var(--chrome-bg)] text-[var(--chrome-text)] border-b border-[var(--chrome-border)] flat no shadows docked full-width top-0 z-40 sticky">
<button aria-label="Menu" class="hover:opacity-80 transition-opacity flex items-center justify-center p-2 -ml-2">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)]">RALIVA</h1>
<button aria-label="{{ __('Search') }}" class="hover:opacity-80 transition-opacity flex items-center justify-center p-2 -mr-2">
<span class="material-symbols-outlined" data-icon="search">search</span>
</button>
</header>
<!-- Main Content Canvas -->
<main class="flex-grow w-full max-w-[600px] lg:max-w-3xl mx-auto px-container-margin py-md">
<!-- Page Title -->
<div class="mb-lg text-center md:text-left">
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">{{ __('ACCOUNT') }}</h2>
</div>
<!-- Profile Header -->
@auth
<section class="flex flex-col items-center md:flex-row md:items-start gap-md mb-xl bg-surface-container-low rounded-lg p-md">
<div class="w-24 h-24 rounded-full overflow-hidden border border-outline-variant flex-shrink-0 bg-surface-container-high flex items-center justify-center">
@if(Auth::user()->foto_profil_url)
<img src="{{ Auth::user()->foto_profil_url }}" alt="{{ Auth::user()->nama_lengkap }}" class="w-full h-full object-cover"/>
@else
<span class="material-symbols-outlined text-[44px] text-on-surface-variant">person</span>
@endif
</div>
<div class="flex flex-col items-center md:items-start justify-center flex-grow">
<h3 class="font-title-md text-title-md text-on-surface mb-1">{{ Auth::user()->nama_lengkap }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-4">{{ Auth::user()->email }}</p>
<div class="flex flex-wrap items-center gap-3">
<a class="px-6 py-2 border border-primary text-primary font-label-caps text-label-caps uppercase hover:bg-surface-container-low transition-colors duration-200 inline-block" href="{{ route('customer.account.edit') }}">
                    {{ __('Edit Profile') }}
                </a>
</div>
</div>
</section>
@else
<section class="flex flex-col items-center md:flex-row md:items-start gap-md mb-xl">
<div class="w-24 h-24 rounded-full border border-outline-variant flex-shrink-0 bg-surface-container-high flex items-center justify-center">
<span class="material-symbols-outlined text-[44px] text-on-surface-variant">person</span>
</div>
<div class="flex flex-col items-center md:items-start justify-center flex-grow text-center md:text-left">
<h3 class="font-title-md text-title-md text-on-surface mb-1">{{ __('Welcome') }}</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-md max-w-xs">{{ __('Register to track your orders and save your wishlist.') }}</p>
<a class="btn-gold w-full md:w-auto h-14 px-xl font-label-caps text-label-caps uppercase tracking-widest inline-flex items-center justify-center" href="{{ route('register') }}">
<span>{{ __('Register Now') }}</span>
</a>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-md">
                    {{ __('Already have an account?') }}
<a class="text-secondary font-semibold hover:opacity-80 transition-opacity ml-1" href="{{ route('login', ['redirect' => '/customer/account']) }}">{{ __('LOGIN') }}</a>
</p>
</div>
</section>
@endif
<!-- Menu List -->
<nav class="flex flex-col border-t border-outline-variant">
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.order-tracking') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="local_mall">local_mall</span>
<span class="font-body-lg text-body-lg text-on-surface">{{ __('My Orders') }}</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
        <a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.wishlist') }}">
            <div class="flex items-center gap-sm">
                <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="favorite_border">favorite_border</span>
                <span class="font-body-lg text-body-lg text-on-surface">{{ __('Wishlist') }}</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.address') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="location_on">location_on</span>
<span class="font-body-lg text-body-lg text-on-surface">{{ __('Addresses') }}</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.reviews') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="star_border">star_border</span>
<span class="font-body-lg text-body-lg text-on-surface">{{ __('My Reviews') }}</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.notifications') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="notifications_none">notifications_none</span>
<span class="font-body-lg text-body-lg text-on-surface">{{ __('Notifications') }}</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.help') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="help_outline">help_outline</span>
<span class="font-body-lg text-body-lg text-on-surface">{{ __('Help Center') }}</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.settings') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="settings">settings</span>
<span class="font-body-lg text-body-lg text-on-surface">Settings</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<form method="POST" action="{{ route('logout') }}" class="mt-lg">
    @csrf
    <button type="submit" class="w-full flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group">
        <div class="flex items-center gap-sm">
            <span class="material-symbols-outlined text-error" data-icon="logout">logout</span>
            <span class="font-body-lg text-body-lg text-error">{{ __('Logout') }}</span>
        </div>
    </button>
</form>
</nav>
</main>
<!-- BottomNavBar (Mobile Only) -->
@include('customer._partials.bottom-nav')
<script>
        document.querySelectorAll('.btn-gold').forEach(function (b) {
            b.addEventListener('click', function () {
                b.classList.remove('flashing');
                void b.offsetWidth;
                b.classList.add('flashing');
                setTimeout(function () { b.classList.remove('flashing'); }, 600);
            });
        });
    </script>
@include('customer._partials.drawer')
</body></html>