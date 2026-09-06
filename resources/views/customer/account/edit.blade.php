<!DOCTYPE html>
<html class="light" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Edit Profile') }}</title>
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
    /* ===== Premium cards + burgundy accents (same as account/index, address/edit, reviews/edit) ===== */
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
    /* ===== Primary solid button (burgundy + shimmer flash) ===== */
    .btn-gold { position: relative; overflow: hidden; background-color: var(--btn-gold-bg) !important; color: var(--btn-gold-text) !important; }
    .btn-gold::after { content:''; position:absolute; top:-10%; bottom:-10%; left:-80%; width:45%; background: rgba(255,255,255,.55); transform:skewX(-24deg); pointer-events:none; }
    .btn-gold:hover::after { animation: authFlash 1.4s linear infinite; }
    .btn-gold.flashing::after { animation: authFlash 1.4s cubic-bezier(.4,0,.2,1) 1; }
    @keyframes authFlash { from { left:-80%; } to { left:135%; } }
    :root           { --btn-gold-bg:#8B1E3F; --btn-gold-text:#ffffff; }
    html.theme-dark { --btn-gold-bg:#6D1428; --btn-gold-text:#ffffff; }
    /* ===== Drawer burgundy parity ===== */
    #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(139,30,63,.10); }
    html.theme-dark #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(163,38,63,.16); }
    /* ===== Gender custom dropdown (boxed, matches standard inputs) ===== */
    .shop-sort-trigger { min-height: 40px; }
    .shop-action-btn-style {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }
</style>
</head>
<body class="bg-surface text-on-surface antialiased font-body-lg min-h-screen flex flex-col pb-[72px] lg:pl-72">

<!-- TopAppBar -->
<header class="bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
    <a href="{{ route('customer.account') }}" aria-label="Back" class="hover:opacity-80 transition-opacity flex">
        <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
    </a>
    <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase flex-1 text-center truncate max-w-[240px]">{{ __('Edit Profile') }}</h1>
    <div class="w-10"></div>
</header>

<!-- Main Content -->
<main class="pt-16 pb-[72px] w-full overflow-x-hidden">

    {{-- Outer wrapper: same as account/index, address/edit, reviews/edit --}}
    <div class="mx-auto max-w-[1400px] px-container-margin">

        {{-- ONE card: bg-surface-container-lowest (matches reviews/edit, address/edit) --}}
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">

            {{-- Section label + heading (compact, same style as reviews/edit section headers) --}}
            <p class="font-label-caps text-label-caps text-[var(--chrome-accent)] uppercase tracking-widest mb-xs">{{ __('MY PROFILE') }}</p>
            <h2 class="premium-heading font-headline-md text-headline-md text-on-surface mb-md md:mb-lg">{{ __('Edit Profile') }}</h2>

            <form id="profile-form" method="POST" action="{{ route('customer.account.edit') }}" enctype="multipart/form-data">
                @csrf

                {{-- ========== PROFILE PHOTO ========== --}}
                <p class="font-label-caps text-label-caps text-[var(--chrome-accent)] uppercase tracking-widest mb-xs mt-1">{{ __('PROFILE PHOTO') }}</p>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-md rounded-xl border border-[var(--border-soft)] bg-[var(--surface-warm)] p-md">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden border border-outline-variant bg-surface-container-high flex items-center justify-center shrink-0">
                        @if(Auth::user()->foto_profil_url)
                            <img id="photo-preview-small" alt="Profile Picture" src="{{ Auth::user()->foto_profil_url }}" class="w-full h-full object-cover"/>
                        @else
                            <span class="material-symbols-outlined text-[30px] text-on-surface-variant">person</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-body-sm text-body-sm font-semibold text-on-surface">{{ __('Profile Photo') }}</p>
                        <p id="no-file-chosen" class="font-body-sm text-body-sm text-on-surface-variant">{{ __('No file chosen') }}</p>
                    </div>
                    <div class="flex items-center gap-sm shrink-0">
                        <label for="foto_profil" class="inline-flex items-center gap-1.5 cursor-pointer px-4 py-2 rounded-full border border-outline-variant text-on-surface-variant font-label-caps text-label-caps uppercase tracking-widest hover:bg-surface-container-low hover:border-secondary hover:text-secondary transition-all duration-200">
                            <span class="material-symbols-outlined text-[16px]">upload</span>
                            {{ __('Change Photo') }}
                        </label>
                        @if(Auth::user()->foto_profil_url)
                            <button type="button" id="remove-photo" aria-label="{{ __('Remove photo') }}" class="w-9 h-9 rounded-full bg-error/10 text-error flex items-center justify-center cursor-pointer hover:bg-error/20 transition-all">
                                <span class="material-symbols-outlined text-base">delete</span>
                            </button>
                        @endif
                    </div>
                </div>

                <input id="foto_profil" name="foto_profil" type="file" accept="image/*" class="sr-only"/>
                <p id="photo-error" class="text-error text-label-sm mt-1 hidden text-center"></p>
                <input type="hidden" name="remove_photo" id="remove-photo-flag" value="0"/>

                {{-- ========== PERSONAL INFORMATION ========== --}}
                <p class="font-label-caps text-label-caps text-[var(--chrome-accent)] uppercase tracking-widest mb-xs mt-3">{{ __('PERSONAL INFORMATION') }}</p>

                <div class="space-y-md">
                    {{-- Full Name --}}
                    <div>
                        <label for="nama_lengkap" class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Full Name') }}</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->nama_lengkap) }}" autocomplete="name" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none"/>
                        @error('nama_lengkap')
                        <p class="text-error text-label-sm mt-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Email Address') }}</label>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" autocomplete="email" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none"/>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">{{ __('Used for order updates and receipts.') }}</p>
                        @error('email')
                        <p class="text-error text-label-sm mt-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="nomor_telepon" class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Phone Number') }}</label>
                        <input type="tel" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', Auth::user()->nomor_telepon) }}" autocomplete="tel" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none"/>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">{{ __('The courier will contact this number upon delivery.') }}</p>
                        @error('nomor_telepon')
                        <p class="text-error text-label-sm mt-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ========== ADDITIONAL INFORMATION ========== --}}
                <p class="font-label-caps text-label-caps text-[var(--chrome-accent)] uppercase tracking-widest mb-xs mt-3">{{ __('ADDITIONAL INFORMATION') }}</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">
                    {{-- Gender --}}
                    <div>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Gender') }}</span>
                        <input type="hidden" name="gender" id="gender-value" value="{{ old('gender', Auth::user()->gender) ?? 'female' }}"/>
                        <div class="relative shop-sort-trigger" id="gender-menu-container">
                            <button type="button" class="shop-action-btn-style w-full justify-between bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none hover:text-secondary" onclick="toggleGenderMenu()" aria-haspopup="listbox" aria-expanded="false" id="gender-trigger">
                                <span class="flex items-center gap-sm">
                                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant" id="gender-icon">{{ old('gender', Auth::user()->gender) === 'male' ? 'male' : 'female' }}</span>
                                    <span id="gender-label" class="font-body-lg text-body-lg">{{ old('gender', Auth::user()->gender) === 'male' ? __('Male') : __('Female') }}</span>
                                </span>
                                <span class="material-symbols-outlined text-[18px] transition-transform duration-200" data-icon="expand_more" id="gender-chevron">expand_more</span>
                            </button>
                            <div id="gender-menu" class="absolute left-0 top-full mt-xs w-full bg-surface rounded-lg border border-outline-variant shadow-xl z-20 py-xs origin-top-left transition-all duration-200 ease-out invisible opacity-0 scale-95 -translate-y-1" role="listbox">
                                <p class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest px-md pt-xs pb-sm">{{ __('Gender') }}</p>
                                <button type="button" class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors" data-gender="female" onclick="selectGender('female')" role="option">
                                    <span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">female</span>{{ __('Female') }}</span>
                                    <span class="material-symbols-outlined text-[18px] text-secondary gender-check {{ old('gender', Auth::user()->gender) === 'male' ? 'invisible' : '' }}">check</span>
                                </button>
                                <button type="button" class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors" data-gender="male" onclick="selectGender('male')" role="option">
                                    <span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">male</span>{{ __('Male') }}</span>
                                    <span class="material-symbols-outlined text-[18px] text-secondary gender-check {{ old('gender', Auth::user()->gender) === 'male' ? '' : 'invisible' }}">check</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Date of Birth --}}
                    <div>
                        <label for="dob" class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Date of Birth') }}</label>
                        <input type="date" id="dob" value="{{ old('tanggal_lahir', '1998-05-17') }}" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none"/>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">{{ __('Get a special surprise on your birthday.') }}</p>
                    </div>
                </div>

                {{-- ========== SECURITY ========== --}}
                <p class="font-label-caps text-label-caps text-[var(--chrome-accent)] uppercase tracking-widest mb-xs mt-3">{{ __('SECURITY') }}</p>

                <a href="{{ route('customer.account.password') }}" class="inline-flex items-center gap-1.5 font-body-sm text-[var(--chrome-accent)] hover:underline underline-offset-2">
                    {{ __('Change Password') }}
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>

                {{-- ========== ACTION BUTTONS ========== --}}
                <div class="mt-lg flex flex-col sm:flex-row gap-sm pt-sm border-t border-[var(--border-soft)]">
                    <a href="{{ route('customer.account') }}" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-full border border-outline-variant text-on-surface-variant font-label-caps text-label-caps uppercase tracking-widest hover:bg-surface-container-low hover:border-secondary hover:text-secondary transition-all duration-200">
                        <span class="material-symbols-outlined text-[18px]">close</span>{{ __('Cancel') }}</a>
                    <button type="submit" form="profile-form" class="btn-gold flex-1 flex items-center justify-center gap-2 py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest shadow-lg">
                        <span class="material-symbols-outlined text-[18px]">check</span>{{ __('Save Changes') }}</button>
                </div>
            </form>
        </div>

    </div>
</main>

{{-- Bottom Nav (mobile) --}}
@include('customer._partials.bottom-nav')

{{-- Drawer --}}
@include('customer._partials.drawer')

<script>
        (function () {
            var input = document.getElementById('foto_profil');
            var previewSmall = document.getElementById('photo-preview-small');
            var placeholder = document.getElementById('no-file-chosen');
            var errorEl = document.getElementById('photo-error');
            var removeFlag = document.getElementById('remove-photo-flag');
            var removeBtn = document.getElementById('remove-photo');
            if (!input) return;

            function showPreview(src) {
                if (previewSmall) {
                    previewSmall.src = src;
                    previewSmall.classList.remove('hidden');
                }
                if (placeholder) placeholder.textContent = '';
            }
            function showPlaceholder() {
                if (previewSmall) {
                    previewSmall.src = '';
                    previewSmall.classList.add('hidden');
                }
                if (placeholder) placeholder.textContent = '{{ __("No file chosen") }}';
            }
            function clearError() {
                if (errorEl) { errorEl.classList.add('hidden'); errorEl.textContent = ''; }
            }

            input.addEventListener('change', function () {
                clearError();
                var file = input.files && input.files[0];
                if (!file) return;
                if (!file.type.startsWith('image/')) {
                    errorEl.textContent = '{{ __("File harus berupa gambar.") }}';
                    errorEl.classList.remove('hidden');
                    input.value = '';
                    showPlaceholder();
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    errorEl.textContent = '{{ __("Ukuran foto maksimal 2MB.") }}';
                    errorEl.classList.remove('hidden');
                    input.value = '';
                    showPlaceholder();
                    return;
                }
                if (removeFlag) removeFlag.value = '0';
                var reader = new FileReader();
                reader.onload = function (e) {
                    showPreview(e.target.result);
                    if (placeholder) placeholder.textContent = file.name;
                };
                reader.readAsDataURL(file);
            });

            if (removeBtn) {
                removeBtn.addEventListener('click', function () {
                    if (input) input.value = '';
                    if (removeFlag) removeFlag.value = '1';
                    clearError();
                    showPlaceholder();
                });
            }
        })();
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var els = document.querySelectorAll('.reveal-up');
        if (!('IntersectionObserver' in window)) {
            els.forEach(function (e) { e.classList.add('is-visible'); });
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) {
                    en.target.classList.add('is-visible');
                    io.unobserve(en.target);
                }
            });
        }, { threshold: 0.08 });
        els.forEach(function (e) { io.observe(e); });
    });
</script>

<script>
    var genderLabels = { female: '{{ __("Female") }}', male: '{{ __("Male") }}' };
    var genderIcons = { female: 'female', male: 'male' };

    function toggleGenderMenu() {
        var menu = document.getElementById('gender-menu');
        var chevron = document.getElementById('gender-chevron');
        var trigger = document.getElementById('gender-trigger');
        var open = !menu.classList.contains('invisible');
        if (open) {
            closeGenderMenu();
        } else {
            menu.classList.remove('invisible', 'opacity-0', 'scale-95', '-translate-y-1');
            chevron.classList.add('rotate-180');
            if (trigger) trigger.setAttribute('aria-expanded', 'true');
        }
    }
    function closeGenderMenu() {
        var menu = document.getElementById('gender-menu');
        menu.classList.add('invisible', 'opacity-0', 'scale-95', '-translate-y-1');
        document.getElementById('gender-chevron').classList.remove('rotate-180');
        var trigger = document.getElementById('gender-trigger');
        if (trigger) trigger.setAttribute('aria-expanded', 'false');
    }
    function selectGender(value) {
        document.getElementById('gender-value').value = value;
        document.getElementById('gender-label').textContent = genderLabels[value];
        document.getElementById('gender-icon').textContent = genderIcons[value];
        document.querySelectorAll('#gender-menu [data-gender]').forEach(function (btn) {
            var check = btn.querySelector('.gender-check');
            if (btn.dataset.gender === value) check.classList.remove('invisible');
            else check.classList.add('invisible');
        });
        closeGenderMenu();
    }
    document.addEventListener('DOMContentLoaded', function () {
        var container = document.getElementById('gender-menu-container');
        document.addEventListener('click', function (e) {
            if (container && !container.contains(e.target)) closeGenderMenu();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeGenderMenu();
            if ((e.key === 'ArrowDown' || e.key === 'Enter') && e.target === document.getElementById('gender-trigger')) {
                toggleGenderMenu();
            }
        });
    });
</script>

</body>
</html>
