<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Create Account') }}</title>
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
    </style>
<style>
    /* ============ FULL DARK MODE TOKEN REMAP ============ */
    html.theme-dark .bg-background, html.theme-dark .bg-surface, html.theme-dark .bg-surface-bright { background-color: #161514 !important; }
    html.theme-dark .bg-surface-container-lowest { background-color: #1e1d1c !important; }
    html.theme-dark .bg-surface-container-low { background-color: #201f1e !important; }
    html.theme-dark .bg-surface-container { background-color: #262524 !important; }
    html.theme-dark .bg-surface-container-high { background-color: #2c2b2a !important; }
    html.theme-dark .bg-surface-container-highest, html.theme-dark .bg-surface-variant { background-color: #323130 !important; }
    html.theme-dark .bg-primary { background-color: #f2efec !important; }
    html.theme-dark .text-primary { color: #f2efec !important; }
    html.theme-dark .text-on-primary { color: #1b1a19 !important; }
    html.theme-dark .border-primary { border-color: #f2efec !important; }
    html.theme-dark .text-on-surface, html.theme-dark .text-on-background { color: #e6e4e1 !important; }
    html.theme-dark .text-on-surface-variant { color: #b9b6b1 !important; }
    html.theme-dark .text-outline { color: #8a8781 !important; }
    html.theme-dark .text-outline-variant { color: #6f6d68 !important; }
    html.theme-dark .text-error { color: #ffb4ab !important; }
    html.theme-dark .text-secondary { color: #A32626 !important; }
    html.theme-dark .placeholder-on-surface-variant::placeholder { color: #b9b6b1 !important; }
    html.theme-dark .border-outline-variant { border-color: #3a3937 !important; }
    html.theme-dark .border-outline { border-color: #4a4844 !important; }
    html.theme-dark .border-error { border-color: #ffb4ab !important; }
    html.theme-dark .bg-error-container { background-color: #3a1210 !important; }
    html.theme-dark .focus\:border-primary:focus { border-color: #f2efec !important; }
    /* ============ GOLD INNER GLOW FRAME ============ */
    .frame-gold {
        box-shadow:
            inset 0 0 26px var(--gold-inner),
            0 12px 24px -8px rgba(0, 0, 0, .18);
    }
    :root { --gold-inner: rgba(139, 30, 30, .30); }
    html.theme-dark { --gold-inner: rgba(139, 30, 30, .25); }
    /* ============ RALIVA FASHION ATELIER BACKGROUND ============ */
    .auth-monogram {
        background-image:
            var(--atl-gold-a),
            var(--atl-gold-b),
            var(--atl-ruler),
            var(--atl-stitch-h),
            var(--atl-stitch-v),
            var(--atl-corner-tl),
            var(--atl-corner-br),
            var(--atl-frame-h),
            var(--atl-frame-v);
        background-repeat: no-repeat, no-repeat, no-repeat, no-repeat, no-repeat, no-repeat, no-repeat, no-repeat, repeat-y;
        background-position:
            288px 74px,
            right 150px bottom 150px,
            right 36px top 330px,
            left 56px bottom 88px,
            right 72px top 96px,
            0 0,
            100% 100%,
            20px 20px,
            20px 0;
        background-size: 16px 16px, 16px 16px, 8px 150px, 240px 3px, 3px 200px, 480px 480px, 480px 480px, 34% 1px, 1px 100%;
    }
    @media (max-width: 640px) {
        .auth-monogram {
            background-position:
                216px 56px,
                right 96px bottom 112px,
                right 14px top 320px,
                left 32px bottom 48px,
                right 40px top 128px,
                0 0,
                100% 100%,
                14px 14px,
                14px 0;
            background-size: 14px 14px, 14px 14px, 6px 120px, 180px 3px, 3px 150px, 300px 300px, 300px 300px, 30% 1px, 1px 100%;
        }
    }
    :root {
        --atl-corner-tl: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='480' height='480' viewBox='0 0 480 480'%3E%3Cg fill='none' stroke='%23111111' stroke-width='1'%3E%3Cpath stroke-opacity='.21' stroke-dasharray='1 7' stroke-linecap='round' d='M36 444V96'/%3E%3Cpath stroke-opacity='.26' d='M36 444C36 250 140 96 452 84'/%3E%3Cpath stroke-opacity='.15' d='M36 180H452'/%3E%3Cpath stroke-opacity='.18' d='M36 96H132V180'/%3E%3Cpath stroke-opacity='.23' d='M148 164l16 16m0-16l-16 16'/%3E%3Cg stroke-opacity='.08'%3E%3Cpath d='M60 420l40-40M78 430l40-40M96 440l40-40'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        --atl-corner-br: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='480' height='480' viewBox='0 0 480 480'%3E%3Cg transform='rotate(180 240 240)'%3E%3Cg fill='none' stroke='%23111111' stroke-width='1'%3E%3Cpath stroke-opacity='.21' stroke-dasharray='1 7' stroke-linecap='round' d='M36 444V96'/%3E%3Cpath stroke-opacity='.26' d='M36 444C36 250 140 96 452 84'/%3E%3Cpath stroke-opacity='.15' d='M36 180H452'/%3E%3Cpath stroke-opacity='.18' d='M36 96H132V180'/%3E%3Cpath stroke-opacity='.23' d='M148 164l16 16m0-16l-16 16'/%3E%3Cg stroke-opacity='.08'%3E%3Cpath d='M60 420l40-40M78 430l40-40M96 440l40-40'/%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        --atl-stitch-h: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='240' height='3'%3E%3Cpath d='M1 1.5h238' stroke='%23111111' stroke-opacity='.26' stroke-width='1.5' stroke-dasharray='7 6' stroke-linecap='round'/%3E%3C/svg%3E");
        --atl-stitch-v: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='3' height='200'%3E%3Cpath d='M1.5 1v198' stroke='%23111111' stroke-opacity='.26' stroke-width='1.5' stroke-dasharray='7 6' stroke-linecap='round'/%3E%3C/svg%3E");
        --atl-gold-a: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Cpath d='M8 2v12M2 8h12' stroke='%238B1E1E' stroke-width='1.4' stroke-linecap='round' stroke-opacity='.7'/%3E%3C/svg%3E");
        --atl-gold-b: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Ccircle cx='8' cy='8' r='3' fill='%238B1E1E' fill-opacity='.65'/%3E%3C/svg%3E");
        --atl-frame-h: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='1'%3E%3Crect width='4' height='1' fill='%23111111' fill-opacity='.13'/%3E%3C/svg%3E");
        --atl-frame-v: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1' height='4'%3E%3Crect width='1' height='4' fill='%23111111' fill-opacity='.13'/%3E%3C/svg%3E");
        --atl-ruler: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='150'%3E%3Cg fill='none' stroke='%23111111' stroke-width='1' stroke-opacity='.23'%3E%3Cpath d='M1 0v150'/%3E%3Cpath d='M1 50h5M1 100h5'/%3E%3Cpath d='M1 10h2.5M1 20h2.5M1 30h2.5M1 40h2.5M1 60h2.5M1 70h2.5M1 80h2.5M1 90h2.5M1 110h2.5M1 120h2.5M1 130h2.5M1 140h2.5'/%3E%3C/g%3E%3Cpath d='M1 0h5' stroke='%238B1E1E' stroke-width='1.2' stroke-opacity='.7'/%3E%3C/svg%3E");
    }
    html.theme-dark {
        --atl-corner-tl: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='480' height='480' viewBox='0 0 480 480'%3E%3Cg fill='none' stroke='%23ffffff' stroke-width='1'%3E%3Cpath stroke-opacity='.12' stroke-dasharray='1 7' stroke-linecap='round' d='M36 444V96'/%3E%3Cpath stroke-opacity='.15' d='M36 444C36 250 140 96 452 84'/%3E%3Cpath stroke-opacity='.08' d='M36 180H452'/%3E%3Cpath stroke-opacity='.10' d='M36 96H132V180'/%3E%3Cpath stroke-opacity='.13' d='M148 164l16 16m0-16l-16 16'/%3E%3Cg stroke-opacity='.05'%3E%3Cpath d='M60 420l40-40M78 430l40-40M96 440l40-40'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        --atl-corner-br: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='480' height='480' viewBox='0 0 480 480'%3E%3Cg transform='rotate(180 240 240)'%3E%3Cg fill='none' stroke='%23ffffff' stroke-width='1'%3E%3Cpath stroke-opacity='.12' stroke-dasharray='1 7' stroke-linecap='round' d='M36 444V96'/%3E%3Cpath stroke-opacity='.15' d='M36 444C36 250 140 96 452 84'/%3E%3Cpath stroke-opacity='.08' d='M36 180H452'/%3E%3Cpath stroke-opacity='.10' d='M36 96H132V180'/%3E%3Cpath stroke-opacity='.13' d='M148 164l16 16m0-16l-16 16'/%3E%3Cg stroke-opacity='.05'%3E%3Cpath d='M60 420l40-40M78 430l40-40M96 440l40-40'/%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        --atl-stitch-h: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='240' height='3'%3E%3Cpath d='M1 1.5h238' stroke='%23ffffff' stroke-opacity='.14' stroke-width='1.5' stroke-dasharray='7 6' stroke-linecap='round'/%3E%3C/svg%3E");
        --atl-stitch-v: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='3' height='200'%3E%3Cpath d='M1.5 1v198' stroke='%23ffffff' stroke-opacity='.14' stroke-width='1.5' stroke-dasharray='7 6' stroke-linecap='round'/%3E%3C/svg%3E");
        --atl-gold-a: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Cpath d='M8 2v12M2 8h12' stroke='%23A32626' stroke-width='1.4' stroke-linecap='round' stroke-opacity='.85'/%3E%3C/svg%3E");
        --atl-gold-b: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'%3E%3Ccircle cx='8' cy='8' r='3' fill='%23A32626' fill-opacity='.85'/%3E%3C/svg%3E");
        --atl-frame-h: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='1'%3E%3Crect width='4' height='1' fill='%23ffffff' fill-opacity='.07'/%3E%3C/svg%3E");
        --atl-frame-v: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1' height='4'%3E%3Crect width='1' height='4' fill='%23ffffff' fill-opacity='.07'/%3E%3C/svg%3E");
        --atl-ruler: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='150'%3E%3Cg fill='none' stroke='%23ffffff' stroke-width='1' stroke-opacity='.13'%3E%3Cpath d='M1 0v150'/%3E%3Cpath d='M1 50h5M1 100h5'/%3E%3Cpath d='M1 10h2.5M1 20h2.5M1 30h2.5M1 40h2.5M1 60h2.5M1 70h2.5M1 80h2.5M1 90h2.5M1 110h2.5M1 120h2.5M1 130h2.5M1 140h2.5'/%3E%3C/g%3E%3Cpath d='M1 0h5' stroke='%23A32626' stroke-width='1.2' stroke-opacity='.85'/%3E%3C/svg%3E");
    }
    /* ============ ATELIER CHIPS ============ */
    .atl-chip { position: relative; }
    .atl-chip:hover { box-shadow: 0 6px 16px -4px rgba(139,30,30,.28); }
    .atl-ic-sun { opacity: 0; }
    html.theme-dark .atl-ic-sun { opacity: 1; }
    .atl-ic-moon { opacity: 1; }
    html.theme-dark .atl-ic-moon { opacity: 0; }
    /* ============ ATELIER ICONS (STATIC) ============ */
    .atl-float { position: absolute; z-index: 0; pointer-events: none; }
    .atl-scissor { left: 304px; bottom: 78px; width: 32px; height: 20px; }
    .atl-btn { left: 84px; bottom: 170px; width: 20px; height: 20px; }
    .atl-needle { right: 88px; top: 306px; width: 44px; height: 44px; }
    @media (max-width: 640px) {
        .atl-scissor { left: 220px; bottom: 44px; width: 26px; height: 16px; }
        .atl-btn { left: 48px; bottom: 130px; width: 16px; height: 16px; }
        .atl-needle { right: 96px; top: 330px; width: 34px; height: 34px; }
    }
    .atl-blade-a, .atl-blade-b {
        fill: none;
        stroke: #111111;
        stroke-width: 1.2;
        stroke-linecap: round;
        stroke-opacity: .26;
    }
    .atl-pivot { fill: #111111; fill-opacity: .26; stroke: none; }
    .atl-ring { fill: none; stroke: #111111; stroke-width: 1.2; stroke-opacity: .26; }
    .atl-rim { fill: none; stroke: #111111; stroke-width: .8; stroke-dasharray: 2 2; stroke-opacity: .18; }
    .atl-dots circle { fill: #111111; fill-opacity: .3; }
    .atl-shaft { fill: none; stroke: #111111; stroke-width: 1.6; stroke-linecap: round; stroke-opacity: .26; }
    .atl-eye { fill: none; stroke: #111111; stroke-width: 1; stroke-opacity: .24; }
    .atl-thread { fill: none; stroke: #111111; stroke-width: .9; stroke-linecap: round; stroke-opacity: .24; }
    .atl-threadflow {
        fill: none;
        stroke: #8B1E1E;
        stroke-width: 1;
        stroke-linecap: round;
        stroke-dasharray: 3 12;
        stroke-opacity: .5;
    }
    html.theme-dark .atl-blade-a, html.theme-dark .atl-blade-b { stroke: #ffffff; stroke-opacity: .15; }
    html.theme-dark .atl-pivot { fill: #ffffff; fill-opacity: .15; }
    html.theme-dark .atl-ring { stroke: #ffffff; stroke-opacity: .15; }
    html.theme-dark .atl-rim { stroke: #ffffff; stroke-opacity: .12; }
    html.theme-dark .atl-dots circle { fill: #ffffff; fill-opacity: .18; }
    html.theme-dark .atl-shaft { stroke: #ffffff; stroke-opacity: .15; }
    html.theme-dark .atl-eye { stroke: #ffffff; stroke-opacity: .14; }
    html.theme-dark .atl-thread { stroke: #ffffff; stroke-opacity: .14; }
    html.theme-dark .atl-threadflow { stroke: #A32626; stroke-opacity: .45; }
</style>
<style>
    /* ============ GOLD BUTTON + LIGHT FLASH ============ */
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
</head>
<body class="bg-surface text-on-surface antialiased font-body-lg min-h-screen flex flex-col lg:flex-row">
<!-- Editorial Panel -->
<aside class="relative overflow-hidden shrink-0 h-44 lg:h-auto lg:w-[44%] flex">
<img alt="RALIVA Editorial 1" src="{{ asset('storage/register-pictures/editorial-1.jfif') }}" class="atl-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"/>
<img alt="RALIVA Editorial 2" src="{{ asset('storage/register-pictures/editorial-2.jfif') }}" class="atl-slide absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000"/>
<img alt="RALIVA Editorial 3" src="{{ asset('storage/register-pictures/editorial-3.jfif') }}" class="atl-slide absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000"/>
<div class="absolute inset-0 bg-black/50"></div>
<div class="relative z-10 flex flex-col items-center justify-center text-center h-full w-full p-lg lg:items-start lg:justify-between lg:text-left lg:p-xl">
<div>
<span class="font-display-lg text-headline-lg tracking-widest text-white">RALIVA</span>
<p class="font-label-sm text-label-sm text-white/80 tracking-wide mt-1 lg:hidden">{{ __('The Art of Everyday Dressing') }}</p>
<div class="w-10 h-px bg-secondary-fixed-dim mt-sm lg:hidden"></div>
</div>
<div class="hidden lg:block">
<p class="font-headline-lg-mobile text-headline-lg-mobile max-w-sm"><span class="text-white">Discover your style,</span> <span style="color:#A32626">curated for you.</span></p>
<div class="w-10 h-px bg-secondary-fixed-dim mt-md"></div>
</div>
</div>
</aside>
<!-- Form Side -->
<main class="auth-monogram flex-grow flex flex-col relative overflow-hidden bg-surface-container-low border-outline-variant lg:border-l">
<!-- Atelier Icons: animated -->
<svg class="atl-float atl-scissor" viewBox="0 0 32 20" aria-hidden="true">
<g class="atl-blade-a"><circle cx="6" cy="4" r="3"/><path d="M8.6 5.2 29 13.4"/></g>
<g class="atl-blade-b"><circle cx="6" cy="16" r="3"/><path d="M8.6 14.8 29 6.6"/></g>
<circle class="atl-pivot" cx="18.8" cy="10" r="1.2"/>
</svg>
<svg class="atl-float atl-btn" viewBox="0 0 20 20" aria-hidden="true">
<circle class="atl-ring" cx="10" cy="10" r="8"/>
<circle class="atl-rim" cx="10" cy="10" r="6"/>
<g class="atl-dots"><circle cx="7.2" cy="7.2" r="1.1"/><circle cx="12.8" cy="7.2" r="1.1"/><circle cx="7.2" cy="12.8" r="1.1"/><circle cx="12.8" cy="12.8" r="1.1"/></g>
</svg>
<svg class="atl-float atl-needle" viewBox="0 0 44 44" aria-hidden="true">
<path class="atl-shaft" d="M31 7L14 26"/>
<ellipse class="atl-eye" cx="12" cy="28.2" rx="3.2" ry="1.9" transform="rotate(42 12 28.2)"/>
<path class="atl-thread" d="M12 30c3 7 10 6 14 9s8-1 12 2"/>
<path class="atl-threadflow" d="M12 30c3 7 10 6 14 9s8-1 12 2"/>
</svg>
<!-- Floating Chips: Home + Theme -->
<div class="fixed top-sm right-sm z-50 flex items-center gap-xs">
<a aria-label="{{ __('Back to home') }}" title="Kembali ke Home" href="{{ route('customer.home') }}" class="atl-chip w-10 h-10 rounded-full border border-outline bg-surface-container-lowest shadow-sm hover:border-secondary hover:text-secondary flex items-center justify-center">
<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11.5 12 4.5l8 7"/><path d="M6.5 10.2V19h11v-8.8"/><path d="M10.7 19v-4.4h2.6V19"/></svg>
</a>
<button id="theme-toggle-btn" aria-label="Toggle theme" title="Mode gelap" type="button" onclick="toggleTheme()" class="atl-chip w-10 h-10 rounded-full border border-outline bg-surface-container-lowest shadow-sm hover:border-secondary hover:text-secondary flex items-center justify-center">
<span class="relative block h-5 w-5">
<svg class="atl-ic-sun absolute inset-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 3v1.8M12 19.2V21M3 12h1.8M19.2 12H21M5.64 5.64l1.27 1.27M17.09 17.09l1.27 1.27M18.36 5.64l-1.27 1.27M6.91 17.09l-1.27 1.27"/></svg>
<svg class="atl-ic-moon absolute inset-0 h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z"/><circle cx="17.5" cy="5.5" r=".75" fill="currentColor" stroke="none"/><circle cx="20.5" cy="9.5" r=".55" fill="currentColor" stroke="none"/></svg>
</span>
</button>
</div>
<div class="flex-grow flex flex-col justify-center w-full max-w-md mx-auto py-xl relative z-10">
<div id="register-section" class="border-2 border-secondary rounded-lg p-lg lg:p-xl frame-gold">
<!-- Heading -->
<div class="mb-sm">
<p class="font-label-caps text-label-caps uppercase tracking-widest text-secondary mb-xs">{{ __('Raliva Account') }}</p>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mb-xs">{{ __('Create Your Account') }}</h2>
</div>
<form id="register-form" novalidate>
<!-- Full Name -->
<div class="mb-sm">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="full-name">{{ __('Full Name') }}</label>
<input autocomplete="name" class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary transition-colors" id="full-name" placeholder="{{ __('Your full name') }}" type="text"/>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="name-error">{{ __('Full name is required.') }}</p>
</div>
<!-- Email -->
<div class="mb-sm">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="email">{{ __('Email') }}</label>
<input autocomplete="email" class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary transition-colors" id="email" placeholder="you@example.com" type="email"/>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="email-error">{{ __('Invalid email address.') }}</p>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="email-taken-error">{{ __('Email is already registered.') }}</p>
</div>
<!-- Password -->
<div class="mb-sm">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="password">{{ __('Password') }}</label>
<div class="relative">
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="password" placeholder="{{ __('Minimum 8 characters') }}" type="password"/>
<button aria-label="{{ __('Show password') }}" class="absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors flex" id="password-toggle" type="button">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
<!-- Password Strength -->
<div class="hidden mt-xs" id="pw-strength">
<div class="flex gap-xs">
<span class="h-1 flex-grow rounded-full bg-outline-variant transition-colors duration-300" id="pw-seg-1"></span>
<span class="h-1 flex-grow rounded-full bg-outline-variant transition-colors duration-300" id="pw-seg-2"></span>
<span class="h-1 flex-grow rounded-full bg-outline-variant transition-colors duration-300" id="pw-seg-3"></span>
<span class="h-1 flex-grow rounded-full bg-outline-variant transition-colors duration-300" id="pw-seg-4"></span>
</div>
<p class="font-label-sm text-label-sm mt-xs" id="pw-strength-label">&nbsp;</p>
</div>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="password-error">{{ __('Password must be at least 8 characters.') }}</p>
</div>
<!-- Confirm Password -->
<div class="mb-sm">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="confirm-password">{{ __('Confirm Password') }}</label>
<div class="relative">
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="confirm-password" placeholder="{{ __('Re-enter your password') }}" type="password"/>
<button aria-label="{{ __('Show password') }}" class="absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors flex" id="confirm-toggle" type="button">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="confirm-error">{{ __('Password does not match.') }}</p>
</div>
<!-- Terms Checkbox -->
<label class="flex items-start gap-sm cursor-pointer mb-sm">
<input class="mt-1 w-4 h-4 shrink-0" id="terms" type="checkbox"/>
<span class="font-body-sm text-body-sm text-on-surface-variant">
            {{ __("I agree to the") }} <span class="text-secondary underline underline-offset-4">{{ __('Terms &amp; Privacy Policy') }}</span>
        </span>
</label>
<p class="hidden font-label-sm text-label-sm text-error -mt-sm mb-sm" id="terms-error">{{ __('Please agree to the Terms &amp; Privacy Policy.') }}</p>
<!-- Submit -->
<button class="w-full h-14 btn-gold font-label-caps text-label-caps uppercase tracking-widest hover:opacity-90 transition-opacity flex items-center justify-center gap-sm disabled:opacity-60 disabled:pointer-events-none" id="register-btn" type="submit">
<span id="register-btn-text">{{ __('REGISTER') }}</span>
<span class="material-symbols-outlined text-[20px] animate-spin hidden" id="register-spinner">progress_activity</span>
</button>
</form>
<!-- Google Divider -->
<div class="flex items-center gap-sm my-sm">
<span class="h-px flex-grow bg-outline-variant"></span>
<span class="font-label-caps text-label-caps uppercase tracking-widest text-outline">{{ __('or') }}</span>
<span class="h-px flex-grow bg-outline-variant"></span>
</div>
<!-- Sign Up with Google -->
<a href="#" aria-label="{{ __('Sign up with Google') }}" class="w-full h-14 border border-outline-variant bg-surface-container-lowest shadow-sm hover:border-secondary font-label-caps text-label-caps uppercase tracking-widest text-on-surface transition-colors flex items-center justify-center gap-sm">
<svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" aria-hidden="true">
<path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82z"/>
<path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.16-4.07 1.16-3.13 0-5.78-2.11-6.73-4.96H1.29v3.09C3.26 21.3 7.31 24 12 24z"/>
<path fill="#FBBC05" d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.38l3.98-3.09z"/>
<path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.98 3.09c.95-2.85 3.6-4.96 6.73-4.96z"/>
</svg>
        {{ __('SIGN UP WITH GOOGLE') }}
    </a>
<!-- Login Link -->
<p class="text-center font-body-sm text-body-sm text-on-surface-variant mt-lg">
        {{ __('Already have an account?') }}
<a class="text-secondary font-semibold hover:opacity-80 transition-opacity ml-1" href="{{ route('customer.login') }}{{ request('redirect') ? '?redirect=' . urlencode(request('redirect')) : '' }}">{{ __('LOGIN') }}</a>
</p>
</div>
</div>
<!-- Success State -->
<div class="hidden text-center py-xl relative z-10 border-2 border-secondary rounded-lg p-lg lg:p-xl frame-gold" id="register-success">
<span class="material-symbols-outlined text-secondary text-[64px]">check_circle</span>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mt-md mb-sm">{{ __('Account Created') }}</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-xl max-w-xs mx-auto">{{ __('Your RALIVA account has been created successfully. Please sign in to continue.') }}</p>
<a class="w-full h-14 btn-gold font-label-caps text-label-caps uppercase tracking-widest hover:opacity-90 transition-opacity flex items-center justify-center inline-flex" href="{{ route('customer.login') }}" id="success-login-link">
            {{ __('CONTINUE TO LOGIN') }}
        </a>
</div>
</main>
<script>
        var redirectParam = new URLSearchParams(window.location.search).get('redirect');

        var successLink = document.getElementById('success-login-link');
        if (redirectParam && redirectParam.indexOf('/customer') !== -1) {
            successLink.href = successLink.href + '?redirect=' + encodeURIComponent(redirectParam);
        }

        document.getElementById('password-toggle').addEventListener('click', function () {
            var input = document.getElementById('password');
            var icon = this.querySelector('.material-symbols-outlined');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.textContent = show ? 'visibility_off' : 'visibility';
        });

        document.getElementById('confirm-toggle').addEventListener('click', function () {
            var input = document.getElementById('confirm-password');
            var icon = this.querySelector('.material-symbols-outlined');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.textContent = show ? 'visibility_off' : 'visibility';
        });

        var pwInput = document.getElementById('password');
        var pwStrengthWrap = document.getElementById('pw-strength');
        var pwSegs = [document.getElementById('pw-seg-1'), document.getElementById('pw-seg-2'), document.getElementById('pw-seg-3'), document.getElementById('pw-seg-4')];
        var pwLabel = document.getElementById('pw-strength-label');

        function pwUpdate() {
            var v = pwInput.value;
            if (!v) {
                pwStrengthWrap.classList.add('hidden');
                return;
            }
            pwStrengthWrap.classList.remove('hidden');
            var s = 0;
            if (v.length >= 8) s++;
            if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
            if (/\d/.test(v)) s++;
            if (/[^a-zA-Z0-9]/.test(v)) s++;
            if (v.length >= 12) s++;
            var filled, text, color;
            if (v.length < 8 || s <= 1) {
                filled = 1; text = 'Lemah'; color = '#ba1a1a';
            } else if (s <= 3) {
                filled = Math.min(s, 3); text = 'Bagus';
                color = document.documentElement.classList.contains('theme-dark') ? '#f59e0b' : '#d97706';
            } else {
                filled = 4; text = 'Aman'; color = '#2e7d32';
            }
            pwSegs.forEach(function (seg, i) {
                seg.style.background = i < filled ? color : '';
            });
            pwLabel.textContent = 'Password: ' + text;
            pwLabel.style.color = color;
        }

        pwInput.addEventListener('input', pwUpdate);

        function setError(id, show) {
            document.getElementById(id).classList.toggle('hidden', !show);
        }

        document.getElementById('register-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var name = document.getElementById('full-name').value.trim();
            var email = document.getElementById('email').value.trim();
            var password = document.getElementById('password').value;
            var confirm = document.getElementById('confirm-password').value;
            var terms = document.getElementById('terms').checked;
            var valid = true;

            ['name-error', 'email-error', 'email-taken-error', 'password-error', 'confirm-error', 'terms-error'].forEach(function (id) { setError(id, false); });

            if (!name) { setError('name-error', true); valid = false; }
            if (!email || !/^\S+@\S+\.\S+$/.test(email)) { setError('email-error', true); valid = false; }
            if (password.length < 8) { setError('password-error', true); valid = false; }
            if (password !== confirm) { setError('confirm-error', true); valid = false; }
            if (!terms) { setError('terms-error', true); valid = false; }
            if (!valid) return;

            var btn = document.getElementById('register-btn');
            btn.disabled = true;
            document.getElementById('register-spinner').classList.remove('hidden');

            setTimeout(function () {
                document.getElementById('register-section').classList.add('hidden');
                document.getElementById('register-success').classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 800);
        });
    </script>
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
<script>
        function toggleTheme() {
            var dark = document.documentElement.classList.toggle('theme-dark');
            localStorage.setItem('raliva-theme', dark ? 'dark' : 'light');
            updateThemeChipTitle();
        }
        function updateThemeChipTitle() {
            var b = document.getElementById('theme-toggle-btn');
            if (!b) return;
            b.title = document.documentElement.classList.contains('theme-dark') ? 'Mode terang' : 'Mode gelap';
        }
        updateThemeChipTitle();
    </script>
<script>
        (function () {
            var slides = document.querySelectorAll('.atl-slide');
            if (slides.length < 2) return;
            var idx = 0;
            setInterval(function () {
                slides[idx].style.opacity = '0';
                idx = (idx + 1) % slides.length;
                slides[idx].style.opacity = '1';
            }, 4000);
        })();
    </script>
</body></html>
