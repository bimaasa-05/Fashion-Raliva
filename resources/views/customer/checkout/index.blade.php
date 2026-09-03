<!DOCTYPE html>
<html class="light" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Checkout') }}</title>
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
    /* ===== Premium cards + burgundy accents (same as account/index, address/edit) ===== */
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
    /* ===== Checkout scoped styles ===== */
    .co-section-label {
        font-family: 'Manrope', sans-serif;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.10em;
        text-transform: uppercase;
        color: var(--chrome-accent);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1rem;
    }
    .co-section-label::before {
        content: '';
        width: 28px;
        height: 1px;
        background: var(--chrome-accent);
        opacity: .65;
        flex-shrink: 0;
    }
    .co-divider {
        height: 1px;
        background: var(--border-soft);
        border: none;
        margin: 1.25rem 0;
    }
    /* Item scroller utility */
    .co-scroll::-webkit-scrollbar { display: none; }
    .co-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    .co-field {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        padding: 0.875rem 0;
        min-height: 3.5rem;
    }
    .co-field + .co-field {
        border-top: 1px solid var(--border-soft);
    }
    .co-field:hover {
        background: var(--surface-warm);
        border-radius: 0.375rem;
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    .co-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        background: var(--surface-warm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--text-muted);
        border: 1px solid var(--border-soft);
        transition: background .18s ease, color .18s ease, border-color .18s ease;
    }
    .co-field:hover .co-icon {
        background: #F3F0EA;
        color: #8B1E3F;
        border-color: #8B1E3F;
    }
    html.theme-dark .co-icon {
        background: var(--surface-warm);
        color: var(--text-muted);
        border-color: var(--border-soft);
    }
    html.theme-dark .co-field:hover .co-icon {
        background: #201f1e;
        color: #8B1E3F;
        border-color: #8B1E3F;
    }
    .co-label {
        font-family: 'Manrope', sans-serif;
        font-size: 15px;
        font-weight: 500;
        color: var(--on-surface);
        flex: 0 0 auto;
        min-width: 0;
    }
    .co-input-wrap {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
    }
    .co-input {
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
    .co-input::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }
    .co-input:focus {
        border-bottom-color: #8B1E3F;
        color: #8B1E3F;
    }
    html.theme-dark .co-input:focus {
        border-bottom-color: #8B1E3F;
        color: #8B1E3F;
    }
    .co-helper {
        font-family: 'Manrope', sans-serif;
        font-size: 12px;
        font-weight: 400;
        color: var(--text-muted);
        padding-left: 3.375rem;
        line-height: 1.4;
    }
    /* Shipping option */
    .co-ship-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-soft);
        border-radius: 0.5rem;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease, color .18s ease;
        background: var(--surface-warm);
    }
    .co-ship-option:hover {
        background: #ECE7DF;
        border-color: #8B1E3F;
    }
    .co-ship-option.selected {
        border-color: #8B1E3F;
        background: rgba(139, 30, 63, .07);
        box-shadow: inset 0 0 0 1px rgba(139, 30, 63, .15);
    }
    .co-ship-option.selected p:first-of-type {
        color: #8B1E3F;
    }
    .co-ship-option + .co-ship-option {
        margin-top: 0.5rem;
    }
    /* Payment method grid */
    .co-payment-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.75rem;
    }
    .co-payment-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 1rem;
        border: 1px solid var(--border-soft);
        border-radius: 0.5rem;
        background: var(--surface-warm);
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease, color .18s ease;
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-muted);
    }
    .co-payment-btn:hover {
        background: #ECE7DF;
        border-color: #8B1E3F;
        color: #8B1E3F;
    }
    .co-payment-btn.selected {
        border-color: #8B1E3F;
        background: rgba(139, 30, 63, .07);
        color: #8B1E3F;
        font-weight: 600;
        box-shadow: inset 0 0 0 1px rgba(139, 30, 63, .15);
    }
    .co-payment-btn.selected .material-symbols-outlined {
        color: #8B1E3F;
    }
    .co-payment-btn .material-symbols-outlined {
        font-size: 1.5rem;
    }
    /* Order summary */
    .co-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
        color: var(--text-muted);
    }
    .co-summary-row.total {
        font-size: 16px;
        font-weight: 600;
        color: var(--on-surface);
        border-top: 1px solid var(--border-soft);
        padding-top: 0.75rem;
        margin-top: 0.5rem;
    }
    /* Bottom action bar wrapper (positioning only; visual card = .co-bottom-bar-card below) */
    .co-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 50;
    }
    html.theme-dark .co-ship-option:hover,
    html.theme-dark .co-payment-btn:hover {
        background: #262524;
        border-color: #8B1E3F;
        color: #8B1E3F;
    }
    html.theme-dark .co-ship-option.selected,
    html.theme-dark .co-payment-btn.selected {
        background: rgba(139, 30, 63, .18);
        border-color: #8B1E3F;
        color: #ffc2c9;
        box-shadow: inset 0 0 0 1px rgba(139, 30, 63, .4);
    }
    html.theme-dark .co-ship-option.selected p:first-of-type,
    html.theme-dark .co-payment-btn.selected .material-symbols-outlined {
        color: #ffc2c9;
    }
    /* Desktop: keep the fixed bar clear of the fixed sidebar (lg:left-72 = 288px) */
    @media (min-width: 1024px) {
        .co-bottom-bar {
            left: 288px;
            right: 0;
        }
    }
    .co-bottom-bar .summary {
        flex: 1 1 0%;
        min-width: 0;
    }
    .co-bottom-bar .summary p:first-child {
        font-family: 'Manrope', sans-serif;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--text-muted);
    }
    .co-bottom-bar .summary p:last-child {
        font-family: 'Manrope', sans-serif;
        font-size: 18px;
        font-weight: 600;
        color: var(--on-surface);
    }
    .co-bottom-bar .btn-place {
        padding: 0.75rem 1.5rem;
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .co-bottom-bar .btn-place:hover {
        background: #6D1428;
    }
    .co-bottom-bar .btn-place:active {
        transform: scale(0.985);
    }

    /* Responsive */
    @media (max-width: 639px) {
        .co-field {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
            padding: 0.8rem 0;
        }
        .co-field + .co-field {
            border-top: 1px solid var(--border-soft);
        }
        .co-icon {
            width: 2rem;
            height: 2rem;
        }
        .co-label {
            font-size: 14px;
        }
        .co-helper {
            padding-left: 0;
        }
        .co-input {
            width: 100%;
            border-bottom: 1.5px solid var(--border-soft);
        }
        .co-payment-grid {
            grid-template-columns: 1fr;
        }
        .co-bottom-bar {
            bottom: 72px;
        }
        .co-bottom-bar .summary p:last-child {
            font-size: 16px;
        }
    }
</style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[168px] md:pb-[104px] lg:pl-72">

<!-- TopAppBar -->
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
    <a href="{{ route('customer.chart') }}" aria-label="Back" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
        <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
    </a>
    <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('CHECKOUT') }}</h1>
    <div class="w-10"></div>
</header>

<!-- Main Content -->
<main class="pt-16 pb-[72px] w-full overflow-x-hidden">

    {{-- Outer wrapper: same as account/index, address/edit --}}
    <div class="mx-auto max-w-[1400px]">

        {{-- Page Title Card --}}
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl px-xl md:px-[64px] py-md md:py-lg shadow-sm card-premium mb-lg md:mb-xl text-center md:text-left reveal-up">
            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('ORDER') }}</p>
            <h2 class="premium-heading font-headline-md text-headline-md text-on-surface">{{ __('Checkout') }}</h2>
        </div>

        {{-- Form Card: ONE card contains all sections --}}
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl px-container-margin md:px-[64px] py-md md:py-lg shadow-sm card-premium reveal-up">

            {{-- ========== DELIVERY ADDRESS ========== --}}
            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('DELIVERY ADDRESS') }}</p>

            <div class="flex items-start gap-md py-md">
                <div class="co-icon mt-0.5">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <div class="min-w-0 flex-1 flex flex-col gap-1">
                    <p class="font-body-sm text-body-sm text-on-surface font-semibold">Jane Doe</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">123 Fashion Avenue, Suite 4B, Jakarta Selatan, DKI Jakarta 12190</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">call</span>
                        +62 812 3456 7890
                    </p>
                </div>
                <a href="{{ route('customer.address.index') }}" class="font-label-caps text-label-caps text-[var(--chrome-accent)] hover:underline underline-offset-2 shrink-0">{{ __('Edit') }}</a>
            </div>

            <hr class="co-divider"/>

            {{-- ========== ORDER ITEMS ========== --}}
            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('ORDER ITEMS') }}</p>

            <div class="flex gap-sm overflow-x-auto pb-sm co-scroll">
                <div class="flex-shrink-0 w-64 flex items-center gap-sm bg-surface-container border border-[var(--border-soft)] rounded-xl p-sm">
                    <div class="flex-shrink-0 w-16 h-20 bg-surface-container-high rounded-lg overflow-hidden">
                        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4G81Gtat5BKzpijwKFnCEhnYyz8ZuXSv5z9_-yaV3L6pPWN-ZLMlKk9D8CfOxlwW2h6_XSvZwA7lQs70AN-q2tlMInMu3xk9wRY7JFzyBFLosxtY9SDjAPSFJ29WFtFv3L3jRAaaKHH53OR30tGk1y6zfjcPJGmZSls-Dzh_ZeiqfLEGhkyh5MobBab8pFvyHdKJ2z2pMdKjElHU1812vN10nL0Bqqb04HqRY_Xvz-PZ9w_qEJOg"/>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-body-sm text-body-sm text-on-surface font-semibold truncate">Oversized Linen Shirt</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant truncate">Ivory · M</p>
                        <p class="font-body-sm text-body-sm text-on-surface mt-xs">Rp 289.000</p>
                    </div>
                </div>
                <div class="flex-shrink-0 w-64 flex items-center gap-sm bg-surface-container border border-[var(--border-soft)] rounded-xl p-sm">
                    <div class="flex-shrink-0 w-16 h-20 bg-surface-container-high rounded-lg overflow-hidden">
                        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDYD6gQ9MzXtJCmq7ifPSDGi9tdMZoHxGUeIA_ZxbwzAb0BrMgldkXslIC5mq53UYgJAVDJnKm1WXR5OdHGr00iFE0HHrfb45MKIEXmGtafzdl5VzVYKkZ4jt2emMmDEANdFL1lx_1DlPkkbxfZpN42dtgm72-WjHKu59ktKb2LkwQ_Hd-Rba4IfJJjqZHJUOBA6rB6RebtR36JFk-HARhgfgNjIsxqk0PW3Xrdij1s5VE02H9JBhI"/>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-body-sm text-body-sm text-on-surface font-semibold truncate">Ribbed Knit Tank</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant truncate">Terracotta · S</p>
                        <p class="font-body-sm text-body-sm text-on-surface mt-xs">Rp 312.000</p>
                    </div>
                </div>
            </div>

            <hr class="co-divider"/>

            {{-- ========== SHIPPING METHOD ========== --}}
            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('SHIPPING METHOD') }}</p>

            <div class="co-ship-option">
                <div class="flex items-center gap-sm">
                    <div class="w-4 h-4 rounded-full border border-secondary flex items-center justify-center"></div>
                    <div>
                        <p class="font-body-sm text-body-sm font-semibold">{{ __('Regular Delivery') }}</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant">3-5 Business Days</p>
                    </div>
                </div>
                <span class="font-body-sm text-body-sm">{{ __('Free') }}</span>
            </div>
            <div class="co-ship-option selected">
                <div class="flex items-center gap-sm">
                    <div class="w-4 h-4 rounded-full border-2 border-secondary flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-secondary"></div>
                    </div>
                    <div>
                        <p class="font-body-sm text-body-sm font-semibold">{{ __('Express Delivery') }}</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __('1-2 Business Days') }}</p>
                    </div>
                </div>
                <span class="font-body-sm text-body-sm">Rp 35.000</span>
            </div>

            <hr class="co-divider"/>

            {{-- ========== PAYMENT METHOD ========== --}}
            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('PAYMENT METHOD') }}</p>

            <div class="co-payment-grid">
                <button class="co-payment-btn" type="button">
                    <span class="material-symbols-outlined">account_balance</span>
                    {{ __('Bank Transfer') }}
                </button>
                <button class="co-payment-btn selected" type="button">
                    <span class="material-symbols-outlined">credit_card</span>
                    {{ __('Credit Card') }}
                </button>
                <button class="co-payment-btn" type="button">
                    <span class="material-symbols-outlined">account_balance_wallet</span>
                    {{ __('E-Wallet') }}
                </button>
            </div>

            <div class="mt-sm p-md border border-[var(--border-soft)] rounded-xl bg-surface">
                <div class="flex justify-between items-center mb-sm">
                    <div class="flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[20px] text-[var(--text-muted)]">credit_card</span>
                        <span class="font-body-sm text-body-sm">**** **** **** 4242</span>
                    </div>
                    <span class="material-symbols-outlined text-[20px] text-[var(--chrome-accent)]">check_circle</span>
                </div>
                <button class="font-label-caps text-label-caps text-[var(--text-muted)] hover:text-[var(--chrome-accent)] underline" type="button">{{ __('Change Card') }}</button>
            </div>

            <hr class="co-divider"/>

            {{-- ========== ORDER SUMMARY ========== --}}
            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('ORDER SUMMARY') }}</p>

            <div class="co-summary-row">
                <span>Subtotal</span>
                <span>Rp 601.000</span>
            </div>
            <div class="co-summary-row">
                <span>Shipping</span>
                <span>Rp 35.000</span>
            </div>
            <div class="co-summary-row">
                <span>Tax</span>
                <span>Rp 0</span>
            </div>
            <div class="co-summary-row total">
                <span>Total Payment</span>
                <span>Rp 636.000</span>
            </div>

        </div>

    </div>
</main>

{{-- Bottom Action Bar (mirrors customer/address toolbar card) --}}
<div class="co-bottom-bar fixed bottom-0 left-0 right-0 lg:left-72 z-50 px-container-margin py-sm pb-safe">
    <div class="co-bottom-bar-card card-premium flex items-center gap-sm md:gap-md bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-xs md:p-sm shadow-[0_-4px_24px_-12px_rgba(0,0,0,0.18)]">
        <div class="summary flex-1 min-w-0">
            <p>{{ __('Total Payment') }}</p>
            <p>Rp 636.000</p>
        </div>
        <a href="{{ route('customer.order-tracking') }}" class="btn-place shrink-0">
            <span class="material-symbols-outlined">check</span>
            <span class="truncate">{{ __('PLACE ORDER') }}</span>
        </a>
    </div>
</div>

{{-- Bottom Nav (mobile) --}}
@include('customer._partials.bottom-nav')

{{-- Drawer --}}
@include('customer._partials.drawer')

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

</body>
</html>
