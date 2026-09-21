<!DOCTYPE html>
<html class="light" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Checkout') }} — {{ __('Review') }}</title>
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
                            "25": "6.25rem",
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
    .overflow-x-clip { overflow-x: clip; }
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
    .card-premium:hover {  box-shadow:0 2px 4px rgb(17 17 17 / .05),0 20px 48px -20px rgb(17 17 17 / .22); border-color:rgba(139,30,63,.45); }
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
    .co-scroll {
        overflow-x: auto;
        -ms-overflow-style: auto !important;
        scrollbar-width: thin !important;
        scrollbar-color: var(--chrome-accent) transparent !important;
        padding-bottom: 0.875rem;
    }
    .co-scroll::-webkit-scrollbar { display: block !important; width: 5px !important; height: 5px !important; }
    .co-scroll::-webkit-scrollbar-track { background: transparent; }
    .co-scroll::-webkit-scrollbar-thumb { background: var(--chrome-accent); border-radius: 999px; }
    .co-input {
        width: 100%;
        background: var(--surface-warm);
        border: 1px solid var(--border-soft);
        border-radius: 0.5rem;
        padding: 0.65rem 0.9rem;
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
        color: var(--on-surface);
        outline: none;
        transition: border-color .18s ease, background .18s ease;
    }
    .co-input:focus { border-color: #8B1E3F; background: #fff; box-shadow: none; outline: none; }
    html.theme-dark .co-input:focus { background:#262524; }
    .co-input.is-error { border-color: #ba1a1a; }
    input.co-input[type='email'] {
        background: var(--surface-warm);
        border: 1px solid var(--border-soft);
        border-radius: 0.5rem;
        padding: 0.65rem 0.9rem;
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
        color: var(--on-surface);
        height: auto;
        line-height: inherit;
        box-shadow: none;
        outline: none;
        -webkit-appearance: none;
        appearance: none;
    }
    input.co-input[type='email']:focus { background: #fff; border-color: #8B1E3F; }
    html.theme-dark input.co-input[type='email']:focus { background: #262524; }
    .co-input:-webkit-autofill,
    .co-input:-webkit-autofill:hover,
    .co-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px var(--surface-warm) inset !important;
        -webkit-text-fill-color: var(--on-surface) !important;
        caret-color: var(--on-surface);
        transition: background-color 999999s, -webkit-box-shadow 0s;
    }
    html.theme-dark .co-input:-webkit-autofill,
    html.theme-dark .co-input:-webkit-autofill:hover,
    html.theme-dark .co-input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px #201f1e inset !important;
        -webkit-text-fill-color: var(--on-surface) !important;
    }
    .co-textarea {
        width: 100%;
        background: var(--surface-warm);
        border: 1px solid var(--border-soft);
        border-radius: 0.5rem;
        padding: 0.65rem 0.9rem;
        font-family: 'Manrope', sans-serif;
        font-size: 14px;
        color: var(--on-surface);
        outline: none;
        resize: vertical;
        transition: border-color .18s ease, background .18s ease;
    }
    .co-textarea:focus { border-color: #8B1E3F; background: #fff; box-shadow: none; outline: none; }
    html.theme-dark .co-textarea:focus { background:#262524; }
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
    .co-bottom-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 50;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 1.25rem;
        padding-bottom: calc(0.75rem + env(safe-area-inset-bottom));
        background: var(--chrome-bg-soft);
        border-top: 1px solid var(--chrome-border);
        backdrop-filter: blur(12px) saturate(1.4);
        -webkit-backdrop-filter: blur(12px) saturate(1.4);
    }
    html.theme-dark .co-ship-option:hover { background: #262524; border-color: #8B1E3F; color: #8B1E3F; }
    html.theme-dark .co-ship-option.selected { background: rgba(139, 30, 63, .18); border-color: #8B1E3F; color: #ffc2c9; box-shadow: inset 0 0 0 1px rgba(139, 30, 63, .4); }
    html.theme-dark .co-ship-option.selected p:first-of-type { color: #ffc2c9; }
    @media (min-width: 1024px) { .co-bottom-bar { display: none !important; } }
    /* ===== co-bottom collapsible sheet (mobile) ===== */
    .co-bottom-bar { flex-direction: column; align-items: stretch; gap: 0; padding: 0; }
    .co-bb-head { display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: .625rem 1.25rem; cursor: pointer; background: transparent; border: 0; width: 100%; text-align: left; font-family: 'Manrope', sans-serif; }
    .co-bb-head span:first-child { color: var(--text-muted); }
    .co-bb-chev { display:inline-flex; align-items:center; justify-content:center;
        width:28px; height:28px; border-radius:9999px;
        background:var(--chrome-bg-soft); border:1px solid var(--border-soft);
        color:var(--text-muted); font-variation-settings:'FILL' 0,'wght' 500;
        transition:transform .3s cubic-bezier(.4,0,.2,1),background .25s,border-color .25s,color .25s;
        transform:rotate(180deg); }
    .co-bb-toggle:hover .co-bb-chev { border-color:var(--chrome-accent); color:var(--chrome-accent); }
    .co-bottom-bar.open .co-bb-chev { transform:rotate(0deg);
        background:var(--chrome-accent); border-color:var(--chrome-accent); color:#fff;
        font-variation-settings:'FILL' 1,'wght' 600; }
    .co-bb-panel { max-height: 0; overflow: hidden; transition: max-height .32s cubic-bezier(.4,0,.2,1); padding-left: 1.25rem; padding-right: 1.25rem; }
    .co-bottom-bar.open .co-bb-panel { max-height: 100vh; overflow-y: auto; padding-bottom: .5rem; }
    .co-bb-block { padding: .625rem 0; border-top: 1px solid var(--border-soft); }
    .co-bb-panel > :first-child { border-top: 1px solid var(--border-soft); }
    .co-bb-rows { display: flex; flex-direction: column; gap: .4rem; }
    .co-bb-row { display: flex; align-items: center; justify-content: space-between; font-family: 'Manrope', sans-serif; font-size: 13px; color: var(--on-surface); }
    .co-bb-row.total { padding-top: .6rem; margin-top: .2rem; border-top: 1px dashed var(--border-soft); font-weight: 700; }

    /* ---- daftar produk dalam panel ---- */
    .co-bb-store { padding: .75rem 0 1rem; }
    .co-bb-store + .co-bb-store { padding-top: 0; border-top: 1px dashed var(--border-soft); }
    .co-bb-store-name { font-family: 'Manrope', sans-serif; font-size: 12px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--chrome-accent); margin-bottom: .5rem; }
    .co-bb-item { display: flex; align-items: flex-start; justify-content: space-between; gap: .75rem; padding: .4rem 0; }
    .co-bb-item + .co-bb-item { border-top: 1px dashed var(--border-soft); }
    .co-bb-item-name { font-family: 'Manrope', sans-serif; font-size: 13px; font-weight: 600; color: var(--on-surface); line-height: 1.3; }
    .co-bb-item-note { font-size: 11px; color: var(--text-muted); }
    .co-bb-item-qty { font-size: 12px; color: var(--text-muted); margin-top: .15rem; }
    .co-bb-item-total { font-family: 'Manrope', sans-serif; font-size: 13px; font-weight: 600; color: var(--on-surface); white-space: nowrap; }
    .co-bottom-bar .co-bb-foot { display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: .75rem 1.25rem calc(.75rem + env(safe-area-inset-bottom)); }
    .co-bottom-bar .co-bb-foot .summary { flex: 1 1 0%; min-width: 0; }
    .co-bottom-bar .co-bb-foot .summary p:last-child { font-size: 15px; }
    .co-bottom-bar .summary { flex: 1 1 0%; min-width: 0; }
    .co-bottom-bar .summary p:first-child { font-family: 'Manrope', sans-serif; font-size: 11px; font-weight: 500; letter-spacing: 0.06em; text-transform: uppercase; color: var(--text-muted); }
    .co-bottom-bar .summary p:last-child { font-family: 'Manrope', sans-serif; font-size: 18px; font-weight: 600; color: var(--on-surface); }
    @media (max-width: 639px) { .co-bottom-bar .summary p:last-child { font-size: 16px; } }
    /* stepper */
    .co-stepper { display:flex; align-items:center; justify-content:center; gap:.5rem; }
    .co-step { display:flex; align-items:center; gap:.45rem; font-family:'Manrope',sans-serif; font-size:11px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; }
    .co-step .num { width:28px; height:28px; border-radius:9999px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; border:1.5px solid var(--border-soft); background: var(--surface-warm); color: var(--text-muted); }
    .co-step.active .num { background:#8B1E3F; border-color:#8B1E3F; color:#fff; }
    .co-step.done .num { background:#8B1E3F; border-color:#8B1E3F; color:#fff; }
    .co-step.active { color:#8B1E3F; }
    .co-step:not(.active):not(.done) { color: var(--text-muted); }
    .co-step-line { width:32px; height:1px; background:var(--border-soft); }
    .co-step-line.done { background:#8B1E3F; }
    /* Layar sangat sempit: tampilkan hanya nomor step, sembunyikan label + garis penguhubung */
    @media (max-width: 374px) { .co-step { font-size: 0; gap: .3rem; } .co-step-line { display: none; } }

    /* Spinner loading untuk step belum dicapai */
    .co-step .num.loading {
        border: 2px solid var(--border-soft);
        border-top-color: #8B1E3F;
        background: transparent !important;
        color: transparent !important;
        animation: co-spin 0.75s linear infinite;
    }
    .co-step .num.loading::after { content:''; display:none; }
    @keyframes co-spin { to { transform: rotate(360deg); } }

    /* Step yang sudah selesai (done) bisa diklik untuk kembali */
    .co-step.done { cursor:pointer; text-decoration:none; transition: opacity .2s ease; }
    .co-step.done:hover { opacity: .75; }
    /* rincian pesanan dropdown */
    .co-rincian-toggle {
        display:inline-flex; align-items:center; justify-content:center; gap:.4rem;
        height:34px; padding:0 .9rem; border-radius:9999px;
        border:1px solid var(--border-soft); background:var(--surface-warm);
        color:var(--chrome-text-dim); cursor:pointer;
        transition: background .18s ease, border-color .18s ease, color .18s ease;
    }
    .co-rincian-toggle:hover, .co-rincian-toggle.open { border-color:#8B1E3F; color:#8B1E3F; }
    .co-rincian-label { font-family:'Manrope',sans-serif; font-size:12px; font-weight:700; letter-spacing:.03em; text-transform:uppercase; white-space:nowrap; }
    .co-rincian-toggle .material-symbols-outlined { font-size:20px; transition: transform .35s ease; }
    .co-rincian-toggle.open .material-symbols-outlined { transform: rotate(180deg); }
    /* Animasi smooth atas -> bawah per item extra: tinggi diukur JS dalam px eksplisit
       (scrollHeight) lalu ditransisikan via max-height — tidak mengandalkan interpolasi
       unit fr sehingga berjalan di semua browser. Tertutup = max-height:0. */
    #co-rincian-grid .co-item-wrap { min-height:0; min-width:0; overflow:hidden; max-height:0; opacity:0; visibility:hidden; transform:translateY(-12px); transition:max-height .45s cubic-bezier(.4,0,.2,1), opacity .35s ease, transform .45s cubic-bezier(.4,0,.2,1), visibility 0s linear .35s; }
    #co-rincian-grid .co-item-wrap.open { opacity:1; visibility:visible; transform:none; transition:max-height .45s cubic-bezier(.4,0,.2,1), opacity .35s ease, transform .45s cubic-bezier(.4,0,.2,1), visibility 0s linear 0s; }
    #co-rincian-grid .co-item-wrap > .co-item-inner { overflow:hidden; min-height:0; }
    /* Item ke-3: selalu tampil di desktop (lg = 3 kolom = 1 baris), ikut collapse hanya di mobile */
    @media (min-width: 1024px) {
        #co-rincian-grid .co-item-wrap.co-third { max-height:none; opacity:1; visibility:visible; transform:none; }
    }
    @media (prefers-reduced-motion: reduce) {
        #co-rincian-grid .co-item-wrap { transition:max-height .15s ease, opacity .15s ease, transform .15s ease; }
    }
</style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-10 lg:pl-72">

@php
    $isGuest = ! auth()->check();
    $authUser = auth()->user();
    // Prefill dari address default / user untuk member; tamu kosong
    $prefill = [
        'nama_penerima' => old('nama_penerima', $isGuest ? '' : ($authUser->nama_lengkap ?? $address?->nama_penerima ?? '')),
        'nomor_telepon' => old('nomor_telepon', $isGuest ? '' : ($authUser->nomor_telepon ?? $address?->nomor_telepon ?? '')),
        'email_pelanggan' => old('email_pelanggan', $isGuest ? '' : ($authUser->email ?? '')),
        'alamat' => old('alamat', $isGuest ? '' : ($address?->alamat ?? '')),
        'kota' => old('kota', $isGuest ? '' : ($address?->kota ?? '')),
        'provinsi' => old('provinsi', $isGuest ? '' : ($address?->provinsi ?? '')),
        'kode_pos' => old('kode_pos', $isGuest ? '' : ($address?->kode_pos ?? '')),
        'catatan' => old('catatan', ''),
    ];
    $hasSavedAddress = ! $isGuest && $address ? true : false;
@endphp

<!-- TopAppBar -->
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
    <a href="{{ $backProductId > 0 ? route('customer.shop.produk-detail', $backProductId) : route('customer.chart') }}" aria-label="Back" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
        <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
    </a>
    <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('Review') }}</h1>
    <div class="w-10"></div>
</header>

<form id="checkout-review-form" method="POST" action="{{ route('customer.checkout.store') }}" class="flex flex-col flex-1 min-h-0">
@csrf
@if ($buyId > 0)
<input type="hidden" name="buy" value="{{ $buyId }}"/>
@endif
<input type="hidden" name="shipping" id="co-shipping-input" value="{{ $shipping }}"/>

<!-- Main Content -->
<main class="pt-6 pb-[72px] w-full overflow-x-clip">
    <div class="mx-auto max-w-[1400px] px-container-margin">

        {{-- Stepper --}}
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-sm mb-md flex justify-center reveal-up">
            <div class="co-stepper">
                <span class="co-step active"><span class="num">1</span> {{ __('Review') }}</span>
                <span class="co-step-line"></span>
                <span class="co-step"><span class="num loading"></span> {{ __('Bayar') }}</span>
                <span class="co-step-line"></span>
                <span class="co-step"><span class="num loading"></span> {{ __('Selesai') }}</span>
            </div>
        </div>

        @if ($errors->any())
        <div class="bg-error-container border border-error/20 rounded-xl p-md mb-md">
            <p class="font-body-sm text-body-sm text-on-error-container font-semibold mb-xs">{{ __('Periksa kembali isian Anda:') }}</p>
            <ul class="list-disc list-inside font-body-sm text-body-sm text-on-error-container">
                @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif
        @if (session('toast'))
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md mb-md flex items-center gap-sm">
            <span class="material-symbols-outlined text-secondary">info</span>
            <p class="font-body-sm text-body-sm text-on-surface-variant">{{ session('toast')['message'] ?? session('toast') }}</p>
        </div>
        @endif

        {{-- Page Title Card --}}
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium mb-lg reveal-up">
            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('ORDER') }}</p>
            <h2 class="premium-heading font-headline-md text-headline-md text-on-surface">{{ __('Review Pesanan') }}</h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">{{ __('Periksa data pemesan, catatan, dan rincian pesanan sebelum melanjutkan ke pembayaran. Akun dan alamat akan dibuat otomatis saat lanjut.') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.65fr)_minmax(0,.95fr)] gap-lg items-start">

            {{-- LEFT: Data Pemesan + Catatan + Rincian Pesanan --}}
            <div class="space-y-lg min-w-0">

                {{-- ========== DATA PEMESAN ========== --}}
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
                    <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('DATA PEMESAN') }}</p>
                    <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Data Pemesan') }}</h3>

                    @if($hasSavedAddress)
                    <div class="rounded-xl border border-[var(--border-soft)] bg-surface-warm p-md">
                        <div class="flex items-start justify-between gap-sm">
                            <div class="space-y-sm min-w-0">
                                <p class="font-body-md text-body-md font-semibold text-on-surface">{{ $address->nama_penerima }}</p>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $address->nomor_telepon }}</p>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $address->alamat }}<br/>{{ $address->kota }}, {{ $address->provinsi }} {{ $address->kode_pos }}</p>
                                <p class="font-label-sm text-label-sm text-on-surface-variant/70 flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">location_on</span> {{ __('Alamat diisi otomatis dari alamat tersimpan.') }}</p>
                            </div>
                            <a href="{{ route('customer.address.index') }}" class="shrink-0 flex items-center gap-xs text-secondary font-label-sm text-label-sm hover:opacity-75 transition-opacity">
                                <span class="material-symbols-outlined text-[18px]">settings</span> {{ __('Kelola Alamat') }}
                            </a>
                        </div>
                    </div>

                    <input type="hidden" name="nama_penerima" value="{{ $prefill['nama_penerima'] }}"/>
                    <input type="hidden" name="nomor_telepon" value="{{ $prefill['nomor_telepon'] }}"/>
                    <input type="hidden" name="email_pelanggan" value="{{ $prefill['email_pelanggan'] }}"/>
                    <input type="hidden" name="provinsi" value="{{ $prefill['provinsi'] }}"/>
                    <input type="hidden" name="kota" value="{{ $prefill['kota'] }}"/>
                    <input type="hidden" name="kode_pos" value="{{ $prefill['kode_pos'] }}"/>
                    <input type="hidden" name="alamat" value="{{ $prefill['alamat'] }}"/>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-md">
                        <label class="flex flex-col gap-1.5 lg:col-span-3">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Nama Lengkap') }} <span class="text-error">*</span></span>
                            <input name="nama_penerima" value="{{ $prefill['nama_penerima'] }}" required maxlength="150" class="co-input @error('nama_penerima') is-error @enderror" placeholder="{{ __('Nama penerima') }}"/>
                            @error('nama_penerima')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                        </label>
                        <label class="flex flex-col gap-1.5 lg:col-span-3">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('No. Telepon / WhatsApp') }} <span class="text-error">*</span></span>
                            <input name="nomor_telepon" value="{{ $prefill['nomor_telepon'] }}" required maxlength="30" class="co-input @error('nomor_telepon') is-error @enderror" placeholder="08xxxxxxxxxx"/>
                            @error('nomor_telepon')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                        </label>
                        <label class="flex flex-col gap-1.5 md:col-span-2 lg:col-span-6">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Email') }} <span class="text-error">*</span></span>
                            <input name="email_pelanggan" type="email" value="{{ $prefill['email_pelanggan'] }}" required maxlength="150" class="co-input @error('email_pelanggan') is-error @enderror" placeholder="nama@email.com"/>
                            @error('email_pelanggan')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                            @if($isGuest)<span class="font-label-sm text-label-sm text-on-surface-variant/70">{{ __('Dipakai sebagai username akun. Password default: Raliva123') }}</span>@endif
                        </label>
                        <label class="flex flex-col gap-1.5 lg:col-span-2">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Provinsi') }} <span class="text-error">*</span></span>
                            <input name="provinsi" value="{{ $prefill['provinsi'] }}" required maxlength="100" class="co-input @error('provinsi') is-error @enderror" placeholder="{{ __('Provinsi') }}"/>
                            @error('provinsi')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                        </label>
                        <label class="flex flex-col gap-1.5 lg:col-span-2">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Kota') }} <span class="text-error">*</span></span>
                            <input name="kota" value="{{ $prefill['kota'] }}" required maxlength="100" class="co-input @error('kota') is-error @enderror" placeholder="{{ __('Kota') }}"/>
                            @error('kota')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                        </label>
                        <label class="flex flex-col gap-1.5 md:col-span-2 lg:col-span-2">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Kode Pos') }} <span class="text-error">*</span></span>
                            <input name="kode_pos" value="{{ $prefill['kode_pos'] }}" required maxlength="20" class="co-input @error('kode_pos') is-error @enderror" placeholder="12345"/>
                            @error('kode_pos')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                        </label>
                        <label class="flex flex-col gap-1.5 md:col-span-2 lg:col-span-6">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Alamat') }} <span class="text-error">*</span></span>
                            <textarea name="alamat" required rows="2" class="co-textarea @error('alamat') is-error @enderror" placeholder="{{ __('Jl. ...') }}">{{ $prefill['alamat'] }}</textarea>
                            @error('alamat')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                        </label>
                    </div>
                    @if(!$isGuest)
                    <p class="font-label-sm text-label-sm text-on-surface-variant/70 mt-md flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">location_on</span> {{ __('Alamat akan tersimpan otomatis setelah pesanan dibuat.') }}</p>
                    @endif
                    @endif
                </div>

                {{-- ========== RINCIAN PESANAN ========== --}}
                @php
                    $coShowCount = $items->count();
                @endphp
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up min-w-0">
                    <div class="flex items-start justify-between gap-sm mb-md">
                        <div class="min-w-0">
                            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('RINCIAN PESANAN') }}</p>
                            <h3 class="premium-heading font-title-md text-title-md text-on-surface">{{ __('Rincian Pesanan') }}</h3>
                        </div>
                        @if($coShowCount > 2)
                        <button id="co-rincian-toggle" type="button" aria-expanded="false" aria-controls="co-rincian-grid" aria-label="{{ __('Tampilkan semua produk') }}" data-label-open="{{ __('Show less') }}" data-label-close="{{ __('Show more') }}" class="co-rincian-toggle shrink-0{{ $coShowCount <= 3 ? ' lg:hidden' : '' }}" onclick="coToggleRincian(this)">
                            <span class="co-rincian-label">{{ __('Show more') }}</span>
                            <span class="material-symbols-outlined">expand_more</span>
                        </button>
                        @endif
                    </div>
                    <div id="co-rincian-grid" class="grid grid-cols-2 lg:grid-cols-3 gap-md">
                    @forelse ($items as $idx => $i)
                    @php
                        $pv = $i->productVariant;
                        $pr = $pv?->product;
                        $img = $pr?->images->first()?->file_gambar ?? '';
                        $imgUrl = $img ? (filter_var($img, FILTER_VALIDATE_URL) ? $img : asset($img)) : 'https://picsum.photos/seed/checkout/600/800';
                        $isWrapped = $idx >= 2;
                        $isThird = $idx === 2;
                    @endphp
                    @if($isWrapped)
                        <div class="co-item-wrap{{ $isThird ? ' co-third' : '' }}" data-pos="{{ $idx }}" aria-hidden="true">
                        <div class="co-item-inner">
                    @endif
                        <div class="flex flex-col bg-surface-container border border-[var(--border-soft)] rounded-lg overflow-hidden h-full">
                            <div class="relative w-full aspect-[3/4] bg-surface-container-high overflow-hidden">
                                <img class="w-full h-full object-cover" loading="lazy" alt="{{ $pr?->nama_produk ?? __('Produk') }}" src="{{ $imgUrl }}"/>
                            </div>
                            <div class="flex flex-col flex-1 min-w-0 gap-1 p-sm">
                                <p class="font-body-sm text-body-sm text-on-surface font-semibold truncate">{{ $pr?->nama_produk ?? __('Produk') }}</p>
                                <p class="font-label-sm text-label-sm text-on-surface-variant truncate">{{ trim(($pv?->warna ?? '') . ' · ' . ($pv?->ukuran ?? ''), ' ·') }}</p>
                                <p class="font-body-sm text-body-sm text-on-surface font-semibold mt-auto">Rp {{ number_format((float)$i->harga_snapshot, 0, ',', '.') }}</p>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">×{{ $i->quantity }}</p>
                            </div>
                        </div>
                    @if($idx >= 2)
                        </div>
                        </div>
                    @endif
                    @empty
                        <div class="col-span-full flex items-center justify-center py-lg text-center">
                            <p class="font-body-sm text-body-sm text-on-surface-variant">{{ $isGuest ? __('Pilih produk terlebih dahulu.') : __('Keranjang masih kosong.') }}</p>
                        </div>
                    @endforelse
                    </div>
                </div>

                {{-- ========== CATATAN OPSIONAL — terpisah ========== --}}
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
                    <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('CATATAN') }}</p>
                    <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Catatan Opsional') }}</h3>
                    <label class="flex flex-col gap-1.5">
                        <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Catatan untuk produk (mis. instruksi pengiriman)') }}</span>
                        <textarea name="catatan" rows="3" maxlength="1000" class="co-textarea @error('catatan') is-error @enderror" placeholder="{{ __('cth. 1–2 produk dikirim duluan ke alamat ini') }}">{{ $prefill['catatan'] }}</textarea>
                        @error('catatan')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                    </label>
                </div>

                </div>

            {{-- RIGHT: Sticky kolom rangkuman (harga, pengiriman, bayar) --}}
            <div class="space-y-md min-w-0 lg:sticky lg:top-25">

                {{-- ========== RINCIAN HARGA ========== --}}
                <div class="hidden lg:block bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
                    <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('RINCIAN HARGA') }}</p>
                    <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Rincian Harga') }}</h3>
                    <div class="co-summary-row">
                        <span>Subtotal</span>
                        <span id="co-subtotal" data-subtotal="{{ $subtotal }}">Rp {{ number_format((float)$subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="co-summary-row">
                        <span>Shipping</span>
                        <span id="co-shipping">Rp {{ number_format((float)$shipping, 0, ',', '.') }}</span>
                    </div>
                    <div class="co-summary-row">
                        <span>Tax (PPN)</span>
                        <span id="co-tax" data-tax="{{ $tax }}">Rp {{ number_format((float)$tax, 0, ',', '.') }}</span>
                    </div>
                    @if ($biayaLayanan > 0)
                    <div class="co-summary-row">
                        <span>Biaya Layanan</span>
                        <span id="co-biaya" data-biaya="{{ $biayaLayanan }}">Rp {{ number_format((float)$biayaLayanan, 0, ',', '.') }}</span>
                    </div>
                    @else
                    <div id="co-biaya" data-biaya="{{ $biayaLayanan }}" class="hidden"></div>
                    @endif
                    <div class="co-summary-row total">
                        <span>Total Payment</span>
                        <span id="co-total">Rp {{ number_format((float)$total, 0, ',', '.') }}</span>
                    </div>
                    @if($isGuest)
                    <p class="font-label-sm text-label-sm text-on-surface-variant/70 mt-sm">{{ __('Akun dan alamat akan dibuat otomatis (password: Raliva123) saat lanjut ke pembayaran.') }}</p>
                    @endif
                </div>

                {{-- ========== METODE PENGIRIMAN (desktop) ========== --}}
                <div class="hidden lg:block bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
                    <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('SHIPPING METHOD') }}</p>
                    <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Metode Pengiriman') }}</h3>
                    @foreach ($shippingOptions as $opt)
                    @php $selectedShip = (int)$opt['ongkir'] === (int)$shipping; @endphp
                    <div class="co-ship-option{{ $selectedShip ? ' selected' : '' }}" data-shipping-ongkir="{{ $opt['ongkir'] }}">
                        <div class="flex items-center gap-sm">
                            <div class="w-4 h-4 rounded-full border-2 border-secondary flex items-center justify-center">
                                @if($selectedShip)<div class="w-2 h-2 rounded-full bg-secondary"></div>@endif
                            </div>
                            <div>
                                <p class="font-body-sm text-body-sm font-semibold">{{ __($opt['nama']) }}</p>
                                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __($opt['estimasi']) }}</p>
                            </div>
                        </div>
                        <span class="font-body-sm text-body-sm">{{ $opt['ongkir'] > 0 ? 'Rp ' . number_format((float)$opt['ongkir'], 0, ',', '.') : __('Free') }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- ========== TOTAL PAYMENT + LANJUT KE PEMBAYARAN ========== --}}
                <div class="hidden lg:block bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
                    <div class="flex items-center justify-between gap-sm flex-wrap">
                        <div class="min-w-0">
                            <p class="font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)]">{{ __('Total Payment') }}</p>
                            <p id="co-total-bottom" class="font-body-lg text-body-lg md:text-title-md font-semibold text-on-surface">Rp {{ number_format((float)$total, 0, ',', '.') }}</p>
                        </div>
                        <button type="submit" class="btn-gold w-full md:w-auto inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            {{ __('Lanjut Ke Pembayaran') }}
                        </button>
                    </div>
                </div>

                <div class="co-bottom-bar lg:hidden">
                    <button type="button" class="co-bb-toggle w-full inline-flex items-center justify-between gap-2 px-xs py-sm" id="co-bb-toggle" aria-expanded="false" aria-controls="co-bb-panel">
                        <span class="inline-flex items-center gap-2 font-label-caps text-label-caps uppercase tracking-widest text-on-surface-variant">
                            <span class="material-symbols-outlined text-[16px]">tune</span>
                            {{ __('Rincian & Pengiriman') }}
                        </span>
                        <span class="material-symbols-outlined co-bb-chev text-[18px]">expand_more</span>
                    </button>
                    <div class="co-bb-panel" id="co-bb-panel" aria-hidden="true">
                        {{-- ===== DAFTAR PRODUK ===== --}}
                        @php $coGroups = $items->groupBy(fn ($i) => $i->productVariant?->product?->store_id ?? 0); @endphp
                        @foreach($coGroups as $coGroup)
                        <div class="co-bb-store">
                            <p class="co-bb-store-name flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">storefront</span>
                                {{ $coGroup->first()->productVariant?->product?->store?->nama_toko ?? __('Toko') }}
                            </p>
                            <div>
                                @foreach($coGroup as $coItem)
                                <div class="co-bb-item">
                                    <div>
                                        <p class="co-bb-item-name">{{ $coItem->productVariant?->product?->nama_produk ?? __('Produk') }}</p>
                                        @if(trim(($coItem->productVariant?->warna ?? '') . ' · ' . ($coItem->productVariant?->ukuran ?? ''), ' ·') !== '')
                                        <p class="co-bb-item-note">{{ trim(($coItem->productVariant?->warna ?? '') . ' · ' . ($coItem->productVariant?->ukuran ?? ''), ' ·') }}</p>
                                        @endif
                                        <p class="co-bb-item-qty">{{ $coItem->quantity }} × Rp {{ number_format((float)$coItem->harga_snapshot, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="co-bb-item-total">Rp {{ number_format((float)($coItem->quantity * $coItem->harga_snapshot), 0, ',', '.') }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        {{-- ===== METODE PENGIRIMAN ===== --}}
                        <div class="co-bb-block">
                            <p class="font-label-caps text-label-caps uppercase tracking-widest text-on-surface-variant mb-xs">{{ __('Metode Pengiriman') }}</p>
                            @foreach ($shippingOptions as $opt)
                            @php $isSel = (int)$opt['ongkir'] === (int)$shipping; @endphp
                            <div class="co-ship-option{{ $isSel ? ' selected' : '' }}" data-shipping-ongkir="{{ $opt['ongkir'] }}">
                                <div class="flex items-center gap-sm">
                                    <div class="w-4 h-4 rounded-full border-2 border-primary-dim flex items-center justify-center">
                                        @if($isSel)<div class="w-2 h-2 rounded-full bg-primary"></div>@endif
                                    </div>
                                    <div>
                                        <p class="font-body-sm text-body-sm font-semibold">{{ __($opt['nama']) }}</p>
                                        <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __($opt['estimasi']) }}</p>
                                    </div>
                                </div>
                                <span class="font-body-sm text-body-sm">{{ $opt['ongkir'] > 0 ? 'Rp ' . number_format((float)$opt['ongkir'], 0, ',', '.') : __('Free') }}</span>
                            </div>
                            @endforeach
                        </div>
                        {{-- ===== RINCIAN HARGA ===== --}}
                        <div class="co-bb-block co-bb-rows">
                            <div class="co-bb-row"><span>{{ __('Subtotal') }}</span><span>Rp {{ number_format((float)$subtotal, 0, ',', '.') }}</span></div>
                            <div class="co-bb-row"><span>{{ __('Shipping') }}</span><span id="co-shipping-sticky">Rp {{ number_format((float)$shipping, 0, ',', '.') }}</span></div>
                            <div class="co-bb-row"><span>{{ __('Tax') }}</span><span>Rp {{ number_format((float)$tax, 0, ',', '.') }}</span></div>
                            <div class="co-bb-row co-bb-total"><span>{{ __('Total Payment') }}</span><span id="co-total-sticky-panel">Rp {{ number_format((float)$total, 0, ',', '.') }}</span></div>
                        </div>
                    </div>
                    <div class="co-bb-foot">
                        <div class="summary">
                            <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Total Payment') }}</p>
                            <p id="co-total-sticky" class="font-body-lg text-body-lg font-semibold text-on-surface">Rp {{ number_format((float)$total, 0, ',', '.') }}</p>
                        </div>
                        <button type="submit" class="btn-gold shrink-0 inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            {{ __('Lanjut') }}
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

</form>

{{-- Drawer --}}
@include('customer._partials.drawer')

<script>
    var coRincianMQ = window.matchMedia('(max-width: 1023.98px)');
    var coRincianOpen = false;
    var coReduceMotionMQ = window.matchMedia('(prefers-reduced-motion: reduce)');
    function coIsMobileView() { return coRincianMQ.matches; }
    function coStaggerDelay() { return (coReduceMotionMQ && coReduceMotionMQ.matches) ? 0 : 40; }
    function coAnimDuration() { return (coReduceMotionMQ && coReduceMotionMQ.matches) ? 150 : 450; }
    function coExtraWraps() {
        return Array.prototype.slice.call(document.querySelectorAll('#co-rincian-grid .co-item-wrap'));
    }
    function coOpenWrap(w, delay) {
        w.style.transitionDelay = delay + 'ms';
        w.classList.add('open');
        w.style.maxHeight = w.scrollHeight + 'px';
        w.setAttribute('aria-hidden', 'false');
        setTimeout(function () {
            if (w.classList.contains('open')) w.style.maxHeight = 'none';
            w.style.transitionDelay = '';
        }, coAnimDuration() + delay + 60);
    }
    function coCloseWrap(w, delay) {
        w.style.transitionDelay = delay + 'ms';
        if (!w.style.maxHeight || w.style.maxHeight === 'none' || w.style.maxHeight === '') {
            w.style.maxHeight = w.scrollHeight + 'px';
        }
        void w.offsetHeight;
        w.classList.remove('open');
        w.style.maxHeight = '0px';
        var done = function () { w.setAttribute('aria-hidden', 'true'); };
        var onEnd = function (e) {
            if (e && e.target !== w) return;
            w.removeEventListener('transitionend', onEnd);
            done();
        };
        w.addEventListener('transitionend', onEnd);
        setTimeout(done, coAnimDuration() + delay + 150);
        setTimeout(function () { w.style.transitionDelay = ''; }, coAnimDuration() + delay + 60);
    }
    function coEnsureThirdDesktop(w) {
        w.classList.add('open');
        w.style.maxHeight = 'none';
        w.style.transitionDelay = '';
        w.setAttribute('aria-hidden', 'false');
    }
    function coSyncThirdView() {
        if (coRincianOpen) return;
        var mobile = coIsMobileView();
        document.querySelectorAll('#co-rincian-grid .co-item-wrap.co-third').forEach(function (w) {
            if (mobile) {
                w.classList.remove('open');
                w.style.maxHeight = '0px';
                w.setAttribute('aria-hidden', 'true');
            } else {
                coEnsureThirdDesktop(w);
            }
        });
    }
    if (typeof coToggleRincian !== 'function') {
        function coToggleRincian(btn) {
            var wraps = coExtraWraps();
            if (!wraps.length) return;
            var open = btn.classList.toggle('open');
            coRincianOpen = open;
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            var step = coStaggerDelay();
            var animatables = [];
            wraps.forEach(function (w) {
                if (!open && w.classList.contains('co-third') && !coIsMobileView()) {
                    coEnsureThirdDesktop(w);
                    return;
                }
                animatables.push(w);
            });
            animatables.forEach(function (w, i) {
                var order = open ? i : (animatables.length - 1 - i);
                var delay = order * step;
                if (open) coOpenWrap(w, delay);
                else coCloseWrap(w, delay);
            });
            var label = btn.querySelector('.co-rincian-label');
            if (label) label.textContent = open ? btn.getAttribute('data-label-open') : btn.getAttribute('data-label-close');
        }
    }
    if (typeof coRincianMQ.addEventListener === 'function') {
        coRincianMQ.addEventListener('change', coSyncThirdView);
    } else if (typeof coRincianMQ.addListener === 'function') {
        coRincianMQ.addListener(coSyncThirdView);
    }
    document.addEventListener('DOMContentLoaded', coSyncThirdView);
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
    document.addEventListener('DOMContentLoaded', function () {
        function rupiah(n) {
            n = Math.round(Number(n) || 0);
            return 'Rp ' + n.toLocaleString('id-ID');
        }
        var subtotalEl = document.getElementById('co-subtotal');
        var taxEl = document.getElementById('co-tax');
        var biayaEl = document.getElementById('co-biaya');
        var subtotal = subtotalEl ? (parseFloat(subtotalEl.getAttribute('data-subtotal')) || 0) : 0;
        var tax = taxEl ? (parseFloat(taxEl.getAttribute('data-tax')) || 0) : 0;
        var biaya = biayaEl ? (parseFloat(biayaEl.getAttribute('data-biaya')) || 0) : 0;
        function refreshTotal(ongkir) {
            var total = subtotal + (parseFloat(ongkir) || 0) + tax + biaya;
            var shipEl = document.getElementById('co-shipping');
            var shipElSticky = document.getElementById('co-shipping-sticky');
            var totalEls = document.querySelectorAll('#co-total, #co-total-bottom, #co-total-sticky, #co-total-sticky-panel');
            if (shipEl) shipEl.textContent = rupiah(ongkir);
            if (shipElSticky) shipElSticky.textContent = rupiah(ongkir);
            totalEls.forEach(function (t) { t.textContent = rupiah(total); });
        }
        document.querySelectorAll('.co-ship-option').forEach(function (opt) {
            opt.addEventListener('click', function () {
                document.querySelectorAll('.co-ship-option').forEach(function (o) { o.classList.remove('selected'); });
                opt.classList.add('selected');
                refreshTotal(opt.getAttribute('data-shipping-ongkir'));
                var shipInput = document.getElementById('co-shipping-input');
                if (shipInput) shipInput.value = opt.getAttribute('data-shipping-ongkir');
            });
        });
        // btn flash
        document.querySelectorAll('.btn-gold,.btn-place').forEach(function(b){
            b.addEventListener('click', function(){ b.classList.remove('flashing'); void b.offsetWidth; b.classList.add('flashing'); setTimeout(function(){ b.classList.remove('flashing'); },600); });
        });
        // ===== toggle sticky detail sheet (mobile) =====
        var bbToggle = document.getElementById('co-bb-toggle');
        var bbPanel = document.getElementById('co-bb-panel');
        if (bbToggle && bbPanel) {
            bbToggle.addEventListener('click', function () {
                var open = bbPanel.classList.toggle('open');
                bbToggle.classList.toggle('open', open);
                var bbBar = bbToggle.closest('.co-bottom-bar');
                if (bbBar) bbBar.classList.toggle('open', open);
                bbToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                bbPanel.setAttribute('aria-hidden', open ? 'false' : 'true');
            });
        }
        // rincian pesanan dropdown (tampil >3 produk) - lihat coToggleRincian() di script bawah
    });
</script>

</body>
</html>
