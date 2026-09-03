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
</style>
<style>
    /* ===== Edit Account field rows (scoped) ===== */
    .ea-field {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        min-height: 3.6rem;
    }
    .ea-field + .ea-field {
        border-top: 1px solid var(--border-soft);
    }
    .ea-field:hover {
        background: var(--surface-warm);
        border-radius: 0.375rem;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .ea-icon {
        width: 2.625rem;
        height: 2.625rem;
        border-radius: 0.5rem;
        background: var(--surface-warm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--text-muted);
        border: 1px solid var(--border-soft);
        transition: background .18s ease, color .18s ease, border-color .18s ease, transform .15s ease;
    }
    .ea-field:hover .ea-icon {
        background: #F3F0EA;
        color: #8B1E3F;
        border-color: #8B1E3F;
    }
    .ea-field .ea-icon:active {
        transform: scale(0.92);
    }
    html.theme-dark .ea-icon {
        background: var(--surface-warm);
        color: var(--text-muted);
        border-color: var(--border-soft);
    }
    html.theme-dark .ea-field:hover .ea-icon {
        background: #201f1e;
        color: #8B1E3F;
        border-color: #8B1E3F;
    }
    .ea-label {
        font-family: 'Manrope', sans-serif;
        font-size: 15px;
        font-weight: 500;
        color: var(--on-surface);
        flex: 0 0 auto;
        min-width: 0;
    }
    .ea-input-wrap {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    .ea-input {
        width: 100%;
        background: transparent;
        border: none;
        border-bottom: 1.5px solid var(--border-soft);
        padding: 0.2rem 0;
        font-family: 'Manrope', sans-serif;
        font-size: 15px;
        font-weight: 400;
        color: var(--on-surface);
        outline: none;
        transition: border-color .18s ease, color .18s ease;
    }
    .ea-input::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }
    .ea-input:focus {
        border-bottom-color: #8B1E3F;
        color: #8B1E3F;
    }
    html.theme-dark .ea-input:focus {
        border-bottom-color: #8B1E3F;
        color: #8B1E3F;
    }
    .ea-helper {
        font-family: 'Manrope', sans-serif;
        font-size: 12px;
        font-weight: 400;
        color: var(--text-muted);
        padding-left: 3.625rem;
        line-height: 1.4;
    }
    /* Gender compact segmented control */
    .ea-gender {
        display: inline-flex;
        border: 1px solid var(--border-soft);
        border-radius: 0.5rem;
        overflow: hidden;
        background: var(--surface-warm);
    }
    .ea-gender label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.125rem;
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-muted);
        cursor: pointer;
        border-right: 1px solid var(--border-soft);
        transition: background .18s ease, color .18s ease;
        user-select: none;
    }
    .ea-gender label:last-child {
        border-right: none;
    }
    .ea-gender label.selected {
        background: #8B1E3F;
        color: #ffffff;
    }
    .ea-gender label:not(.selected):hover {
        background: #F3F0EA;
        color: var(--on-surface);
    }
    html.theme-dark .ea-gender label:not(.selected):hover {
        background: var(--surface-warm);
        color: var(--on-surface);
    }
    /* Change password action row */
    .ea-action {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        min-height: 3.6rem;
        cursor: pointer;
        transition: background .15s ease;
    }
    .ea-action:hover {
        background: var(--surface-warm);
        border-radius: 0.375rem;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    /* Profile photo compact row */
    .ea-photo {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.4rem 0 0.6rem;
    }
    .ea-photo-avatar {
        width: 3rem;
        height: 3rem;
        border-radius: 9999px;
        overflow: hidden;
        border: 1px solid var(--border-soft);
        background: var(--surface-warm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .ea-photo-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .ea-photo-avatar .material-symbols-outlined {
        font-size: 1.5rem;
        color: var(--text-muted);
    }
    .ea-photo-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 0.05rem;
    }
    .ea-photo-label {
        font-family: 'Manrope', sans-serif;
        font-size: 15px;
        font-weight: 500;
        color: var(--on-surface);
    }
    .ea-photo-filename {
        font-family: 'Manrope', sans-serif;
        font-size: 12px;
        font-weight: 400;
        color: var(--text-muted);
    }
    .ea-photo-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
    }
    /* Action buttons row */
    .ea-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-top: 1.5rem;
        margin-top: 0.25rem;
        border-top: 1px solid var(--border-soft);
    }
    .ea-btn-cancel {
        flex: 0 0 auto;
        padding: 0.625rem 1.5rem;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--text-muted);
        background: transparent;
        border: 1px solid var(--border-soft);
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background .18s ease, color .18s ease, border-color .18s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .ea-btn-cancel:hover {
        background: var(--surface-warm);
        color: var(--on-surface);
        border-color: #8B1E3F;
    }
    .ea-btn-save {
        flex: 1;
        padding: 0.625rem 1.5rem;
        font-family: 'Manrope', sans-serif;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #ffffff;
        background: #8B1E3F;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background .18s ease, transform .12s ease;
    }
    .ea-btn-save:hover {
        background: #6D1428;
    }
    .ea-btn-save:active {
        transform: scale(0.985);
    }
    /* Responsive: stack on mobile */
    @media (max-width: 639px) {
        .ea-field {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.4rem;
            padding: 0.85rem 0;
        }
        .ea-field + .ea-field {
            border-top: 1px solid var(--border-soft);
        }
        .ea-icon {
            width: 2rem;
            height: 2rem;
        }
        .ea-label {
            font-size: 14px;
        }
        .ea-helper {
            padding-left: 0;
        }
        .ea-input {
            width: 100%;
            border-bottom: 1.5px solid var(--border-soft);
        }
        .ea-gender label {
            padding: 0.5rem 0.875rem;
            font-size: 13px;
        }
        .ea-photo {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.4rem 0 0.6rem;
        }
        .ea-photo-actions {
            width: 100%;
        }
        .ea-photo-actions label,
        .ea-photo-actions button {
            width: 100%;
            justify-content: center;
        }
        .ea-actions {
            flex-direction: column;
            gap: 0.625rem;
            padding-top: 1.1rem;
        }
        .ea-btn-cancel,
        .ea-btn-save {
            width: 100%;
            text-align: center;
            justify-content: center;
        }
    }
</style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] lg:pl-72">

<!-- TopAppBar -->
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky z-40 border-b border-[var(--chrome-border)]">
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

                <div class="ea-photo">
                    <div class="ea-photo-avatar">
                        @if(Auth::user()->foto_profil_url)
                            <img id="photo-preview-small" alt="Profile Picture" src="{{ Auth::user()->foto_profil_url }}"/>
                        @else
                            <span class="material-symbols-outlined">person</span>
                        @endif
                    </div>
                    <div class="ea-photo-info">
                        <span class="ea-photo-label">{{ __('Profile Photo') }}</span>
                        <span id="no-file-chosen" class="ea-photo-filename">{{ __('No file chosen') }}</span>
                    </div>
                    <div class="ea-photo-actions">
                        <label for="foto_profil" class="font-label-caps text-label-caps text-sm uppercase tracking-widest text-[var(--chrome-accent)] cursor-pointer hover:opacity-80 transition-opacity flex items-center gap-1.5 bg-[var(--surface-warm)] border border-[var(--border-soft)] rounded-lg px-3 py-2">
                            <span class="material-symbols-outlined text-base">upload</span>
                            {{ __('Change Photo') }}
                        </label>
                        @if(Auth::user()->foto_profil_url)
                            <button type="button" id="remove-photo" aria-label="{{ __('Remove photo') }}" class="w-8 h-8 rounded-full bg-error/10 text-error flex items-center justify-center cursor-pointer hover:bg-error/20 hover:scale-105 transition-all">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        @endif
                    </div>
                </div>

                <input id="foto_profil" name="foto_profil" type="file" accept="image/*" class="sr-only"/>
                <p id="photo-error" class="text-error text-label-sm mt-1 hidden text-center"></p>
                <input type="hidden" name="remove_photo" id="remove-photo-flag" value="0"/>

                {{-- ========== PERSONAL INFORMATION ========== --}}
                <p class="font-label-caps text-label-caps text-[var(--chrome-accent)] uppercase tracking-widest mb-xs mt-3">{{ __('PERSONAL INFORMATION') }}</p>

                {{-- Full Name --}}
                <div class="ea-field">
                    <div class="ea-icon">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <span class="ea-label">{{ __('Full Name') }}</span>
                    <div class="ea-input-wrap">
                        <input class="ea-input" id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap', Auth::user()->nama_lengkap) }}" autocomplete="name"/>
                    </div>
                </div>

                {{-- Email --}}
                <div class="ea-field">
                    <div class="ea-icon">
                        <span class="material-symbols-outlined">mail</span>
                    </div>
                    <span class="ea-label">{{ __('Email Address') }}</span>
                    <div class="ea-input-wrap">
                        <input class="ea-input" id="email" name="email" type="email" value="{{ old('email', Auth::user()->email) }}" autocomplete="email"/>
                        <span class="ea-helper">{{ __('Used for order updates and receipts.') }}</span>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="ea-field">
                    <div class="ea-icon">
                        <span class="material-symbols-outlined">phone</span>
                    </div>
                    <span class="ea-label">{{ __('Phone Number') }}</span>
                    <div class="ea-input-wrap">
                        <input class="ea-input" id="nomor_telepon" name="nomor_telepon" type="tel" value="{{ old('nomor_telepon', Auth::user()->nomor_telepon) }}" autocomplete="tel"/>
                        <span class="ea-helper">{{ __('The courier will contact this number upon delivery.') }}</span>
                    </div>
                </div>

                {{-- ========== ADDITIONAL INFORMATION ========== --}}
                <p class="font-label-caps text-label-caps text-[var(--chrome-accent)] uppercase tracking-widest mb-xs mt-3">{{ __('ADDITIONAL INFORMATION') }}</p>

                {{-- Gender --}}
                <div class="ea-field">
                    <div class="ea-icon">
                        <span class="material-symbols-outlined">transgender</span>
                    </div>
                    <span class="ea-label">{{ __('Gender') }}</span>
                    <div class="ea-input-wrap">
                        <div class="ea-gender" id="gender-segment">
                            <label class="{{ old('gender', Auth::user()->gender) === 'female' ? 'selected' : '' }}">
                                <input type="radio" name="gender" value="female" class="sr-only" {{ old('gender', Auth::user()->gender) === 'female' ? 'checked' : '' }}/>
                                <span class="material-symbols-outlined text-base">female</span>
                                {{ __('Female') }}
                            </label>
                            <label class="{{ old('gender', Auth::user()->gender) === 'male' ? 'selected' : '' }}">
                                <input type="radio" name="gender" value="male" class="sr-only" {{ old('gender', Auth::user()->gender) === 'male' ? 'checked' : '' }}/>
                                <span class="material-symbols-outlined text-base">male</span>
                                {{ __('Male') }}
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Date of Birth --}}
                <div class="ea-field">
                    <div class="ea-icon">
                        <span class="material-symbols-outlined">calendar_month</span>
                    </div>
                    <span class="ea-label">{{ __('Date of Birth') }}</span>
                    <div class="ea-input-wrap">
                        <input class="ea-input" id="dob" type="date" value="1998-05-17"/>
                        <span class="ea-helper">{{ __('Get a special surprise on your birthday.') }}</span>
                    </div>
                </div>

                {{-- ========== SECURITY ========== --}}
                <p class="font-label-caps text-label-caps text-[var(--chrome-accent)] uppercase tracking-widest mb-xs mt-3">{{ __('SECURITY') }}</p>

                <div class="ea-action">
                    <div class="ea-icon">
                        <span class="material-symbols-outlined">lock</span>
                    </div>
                    <span class="ea-label">{{ __('Change Password') }}</span>
                    <div class="ea-input-wrap">
                        <a href="{{ route('customer.account.password') }}" class="inline-flex items-center gap-1.5 font-body-sm text-[var(--chrome-accent)] hover:underline underline-offset-2">
                            {{ __('Change Password') }}
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>

                {{-- ========== ACTION BUTTONS ========== --}}
                <div class="ea-actions">
                    <a href="{{ route('customer.account') }}" class="ea-btn-cancel">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" form="profile-form" class="ea-btn-save">
                        {{ __('Save Changes') }}
                    </button>
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
    // Gender segmented control visual state
    (function () {
        var segment = document.getElementById('gender-segment');
        if (!segment) return;
        var options = segment.querySelectorAll('label');
        options.forEach(function (opt) {
            var radio = opt.querySelector('input[type="radio"]');
            if (!radio) return;
            radio.addEventListener('change', function () {
                options.forEach(function (o) { o.classList.remove('selected'); });
                opt.classList.add('selected');
            });
            if (radio.checked) opt.classList.add('selected');
        });
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
    document.querySelectorAll('.ea-btn-save').forEach(function (b) {
        b.addEventListener('click', function () {
            b.style.transition = 'none';
            b.style.transform = 'scale(0.97)';
            setTimeout(function () {
                b.style.transition = '';
                b.style.transform = '';
            }, 120);
        });
    });
</script>

</body>
</html>
