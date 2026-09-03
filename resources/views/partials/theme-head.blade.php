<script>
    (function () {
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    })();
</script>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "on-background": "rgb(var(--c-on-surface) / <alpha-value>)",
                    "on-surface": "rgb(var(--c-on-surface) / <alpha-value>)",
                    "surface-container": "rgb(var(--c-sc) / <alpha-value>)",
                    "surface-variant": "rgb(var(--c-sc-highest) / <alpha-value>)",
                    "tertiary-fixed": "rgb(var(--c-sc-highest) / <alpha-value>)",
                    "secondary-container": "#fdd177",
                    "gold-accent": "#C9A24D",
                    "on-error": "rgb(var(--c-on-error) / <alpha-value>)",
                    "background": "rgb(var(--c-background) / <alpha-value>)",
                    "on-tertiary-container": "rgb(var(--c-on-muted) / <alpha-value>)",
                    "on-secondary-fixed-variant": "#5c4300",
                    "on-primary-fixed": "#1c1b1b",
                    "surface-dim": "rgb(var(--c-surface-dim) / <alpha-value>)",
                    "surface-container-high": "rgb(var(--c-sc-high) / <alpha-value>)",
                    "on-primary-fixed-variant": "#474646",
                    "on-secondary-fixed": "#261a00",
                    "error": "rgb(var(--c-error) / <alpha-value>)",
                    "on-primary-container": "rgb(var(--c-on-muted) / <alpha-value>)",
                    "tertiary": "rgb(var(--c-primary) / <alpha-value>)",
                    "surface-container-lowest": "rgb(var(--c-sc-lowest) / <alpha-value>)",
                    "on-tertiary-fixed-variant": "#464745",
                    "on-error-container": "rgb(var(--c-on-error-container) / <alpha-value>)",
                    "primary-fixed-dim": "rgb(var(--c-primary-fixed-dim) / <alpha-value>)",
                    "error-container": "rgb(var(--c-error-container) / <alpha-value>)",
                    "surface-container-highest": "rgb(var(--c-sc-highest) / <alpha-value>)",
                    "surface-container-low": "rgb(var(--c-sc-low) / <alpha-value>)",
                    "primary-container": "#1c1b1b",
                    "outline-variant": "rgb(var(--c-outline-variant) / <alpha-value>)",
                    "inverse-primary": "rgb(var(--c-primary-fixed-dim) / <alpha-value>)",
                    "on-tertiary": "#ffffff",
                    "secondary-fixed-dim": "#ebc168",
                    "warm-bg": "rgb(var(--c-background) / <alpha-value>)",
                    "on-primary": "rgb(var(--c-on-primary) / <alpha-value>)",
                    "primary": "rgb(var(--c-primary) / <alpha-value>)",
                    "tertiary-container": "rgb(var(--c-tertiary-container) / <alpha-value>)",
                    "secondary": "rgb(var(--c-secondary) / <alpha-value>)",
                    "surface": "rgb(var(--c-surface) / <alpha-value>)",
                    "secondary-fixed": "#ffdf9f",
                    "inverse-on-surface": "rgb(var(--c-inverse-on-surface) / <alpha-value>)",
                    "on-tertiary-fixed": "#1a1c1a",
                    "tertiary-fixed-dim": "#c7c6c4",
                    "surface-bright": "rgb(var(--c-background) / <alpha-value>)",
                    "muted-border": "rgb(var(--c-border) / <alpha-value>)",
                    "surface-tint": "rgb(var(--c-surface-tint) / <alpha-value>)",
                    "outline": "rgb(var(--c-outline) / <alpha-value>)",
                    "primary-fixed": "rgb(var(--c-primary-fixed) / <alpha-value>)",
                    "deep-onyx": "rgb(var(--c-deep-onyx) / <alpha-value>)",
                    "on-surface-variant": "rgb(var(--c-on-surface-variant) / <alpha-value>)",
                    "on-secondary": "#ffffff",
                    "inverse-surface": "rgb(var(--c-inverse-surface) / <alpha-value>)",
                    "on-secondary-container": "#775804",
                    "sidebar": "rgb(var(--c-sidebar) / <alpha-value>)",
                    "on-sidebar": "rgb(var(--c-on-sidebar) / <alpha-value>)",
                    "sidebar-border": "rgb(var(--c-sidebar-border) / <alpha-value>)",
                    "sidebar-hover": "rgb(var(--c-sidebar-hover) / <alpha-value>)",
                    "scrim": "rgb(var(--c-scrim) / <alpha-value>)"
                },
                borderRadius: {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
                },
                spacing: {
                    "element-gap": "12px",
                    "gutter": "16px",
                    "unit": "4px",
                    "section-gap": "64px",
                    "container-margin": "24px"
                },
                fontFamily: {
                    "label-sm": ["Manrope"],
                    "body-md": ["Manrope"],
                    "headline-lg": ["Playfair Display"],
                    "headline-lg-mobile": ["Playfair Display"],
                    "title-md": ["Manrope"],
                    "display-lg": ["Playfair Display"]
                },
                fontSize: {
                    "label-sm": ["12px", { lineHeight: "1.0", letterSpacing: "0.1em", fontWeight: "700" }],
                    "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
                    "headline-lg": ["32px", { lineHeight: "1.2", fontWeight: "500" }],
                    "headline-lg-mobile": ["28px", { lineHeight: "1.2", fontWeight: "500" }],
                    "title-md": ["18px", { lineHeight: "1.4", letterSpacing: "0.01em", fontWeight: "600" }],
                    "display-lg": ["48px", { lineHeight: "1.1", letterSpacing: "-0.02em", fontWeight: "600" }]
                }
            }
        }
    }
</script>
<style>
    :root {
        --c-background: 251 249 249;
        --c-surface: 251 249 249;
        --c-surface-dim: 219 218 217;
        --c-sc-lowest: 255 255 255;
        --c-sc-low: 245 243 243;
        --c-sc: 239 237 237;
        --c-sc-high: 233 232 231;
        --c-sc-highest: 227 226 226;
        --c-primary-fixed: 229 226 225;
        --c-primary-fixed-dim: 200 198 197;
        --c-inverse-on-surface: 242 240 240;
        --c-inverse-surface: 48 48 49;
        --c-on-surface: 27 28 28;
        --c-on-surface-variant: 68 71 72;
        --c-on-muted: 133 131 131;
        --c-outline: 116 120 120;
        --c-outline-variant: 196 199 199;
        --c-border: 233 232 231;
        --c-primary: 0 0 0;
        --c-deep-onyx: 17 17 17;
        --c-on-primary: 255 255 255;
        --c-secondary: 121 89 5;
        --c-error: 186 26 26;
        --c-on-error: 255 255 255;
        --c-error-container: 255 218 214;
        --c-on-error-container: 147 0 10;
        --c-tertiary-container: 26 28 26;
        --c-surface-tint: 95 94 94;
        --c-sidebar: 255 255 255;
        --c-on-sidebar: 27 28 28;
        --c-sidebar-border: 233 232 231;
        --c-sidebar-hover: 239 237 237;
        --c-scrim: 0 0 0;
    }

    .dark {
        --c-background: 14 14 14;
        --c-surface: 14 14 14;
        --c-surface-dim: 8 8 8;
        --c-sc-lowest: 20 20 20;
        --c-sc-low: 26 26 26;
        --c-sc: 33 33 33;
        --c-sc-high: 41 41 41;
        --c-sc-highest: 51 51 51;
        --c-primary-fixed: 44 44 44;
        --c-primary-fixed-dim: 64 64 64;
        --c-inverse-on-surface: 30 30 30;
        --c-inverse-surface: 238 236 236;
        --c-on-surface: 240 238 238;
        --c-on-surface-variant: 186 184 184;
        --c-on-muted: 178 176 176;
        --c-outline: 138 141 141;
        --c-outline-variant: 46 46 46;
        --c-border: 34 34 34;
        --c-primary: 240 238 238;
        --c-deep-onyx: 240 238 238;
        --c-on-primary: 17 17 17;
        --c-secondary: 235 193 104;
        --c-error: 255 179 171;
        --c-on-error: 60 14 12;
        --c-error-container: 93 26 22;
        --c-on-error-container: 255 218 214;
        --c-tertiary-container: 74 74 74;
        --c-surface-tint: 170 168 168;
        --c-sidebar: 28 28 28;
        --c-on-sidebar: 255 255 255;
        --c-sidebar-border: 46 46 46;
        --c-sidebar-hover: 41 41 41;
    }

    body { background-color: rgb(var(--c-background)); }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    @keyframes drawLine { to { stroke-dashoffset: 0; } }
    .animate-line { stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: drawLine 2s ease-out forwards; }

    .card-premium { box-shadow: 0 1px 2px rgb(17 17 17 / 0.04), 0 12px 32px -16px rgb(17 17 17 / 0.16); transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease; }
    .card-premium:hover { transform: translateY(-3px); box-shadow: 0 2px 4px rgb(17 17 17 / 0.05), 0 20px 48px -20px rgb(17 17 17 / 0.22); border-color: rgb(201 162 77 / 0.45); }
    .dark .card-premium { box-shadow: 0 1px 2px rgb(0 0 0 / 0.35), 0 12px 32px -16px rgb(0 0 0 / 0.55); }
    .dark .card-premium:hover { box-shadow: 0 2px 4px rgb(0 0 0 / 0.4), 0 20px 48px -20px rgb(0 0 0 / 0.7); }

    .premium-heading::before { content: ''; display: inline-block; width: 4px; height: 0.95em; margin-right: 0.65rem; background: #C9A24D; border-radius: 9999px; }

    .premium-table tbody tr { transition: background-color 0.2s ease; }
    .premium-table tbody tr td:first-child { position: relative; }
    .premium-table tbody tr td:first-child::before { content: ''; position: absolute; left: 0; top: 22%; bottom: 22%; width: 3px; border-radius: 9999px; background: #C9A24D; opacity: 0; transition: opacity 0.2s ease; }
    .premium-table tbody tr:hover td:first-child::before { opacity: 1; }

    .btn-premium { transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease; box-shadow: 0 8px 20px -10px rgb(17 17 17 / 0.45); }
    .btn-premium:hover { transform: translateY(-1px); box-shadow: 0 14px 30px -12px rgb(17 17 17 / 0.55); filter: brightness(1.06); }
    /* Scrollbar nav sidebar: rapi, beri jarak dari item menu + stabil gutter + muncul halus saat hover */
    .sidebar-scroll { scrollbar-width: thin; scrollbar-color: transparent transparent; scrollbar-gutter: stable; padding-right: 12px; }
    .sidebar-scroll:hover { scrollbar-color: rgb(var(--c-on-sidebar) / 0.18) transparent; }
    .sidebar-scroll::-webkit-scrollbar { width: 6px; }
    .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background: transparent; border: 2px solid transparent; background-clip: padding-box; border-radius: 9999px; }
    .sidebar-scroll:hover::-webkit-scrollbar-thumb { background: rgb(var(--c-on-sidebar) / 0.18); background-clip: padding-box; border: 2px solid transparent; }
    .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgb(var(--c-on-sidebar) / 0.28); background-clip: padding-box; }
    /* ===== Raliva Motion Polish — pop-in menu, modal & toast (berlaku semua role) ===== */
    @keyframes ralivaPopIn { from { opacity: 0; transform: scale(0.96) translateY(-4px); } to { opacity: 1; transform: none; } }
    @keyframes ralivaToastIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
    [data-profile-menu], [data-notification-menu], [data-dropdown-menu] { transform-origin: top right; animation: ralivaPopIn 0.18s cubic-bezier(0.22, 1, 0.36, 1); }
    [data-modal] > .relative { animation: ralivaPopIn 0.2s cubic-bezier(0.22, 1, 0.36, 1); }
    #raliva-toast { animation: ralivaToastIn 0.25s cubic-bezier(0.22, 1, 0.36, 1); }
    [data-profile-chevron] { transition: transform 0.3s ease; }
    [data-profile-container].menu-open [data-profile-chevron] { transform: rotate(180deg); }
    .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }
    @media (prefers-reduced-motion: reduce) {
        [data-profile-menu], [data-notification-menu], [data-dropdown-menu],
        [data-modal] > .relative, #raliva-toast { animation: none; }
    }

    /* ===== Raliva Form Components — satu style untuk semua role ===== */
</style>
<style type="text/tailwindcss">
    .raliva-label { @apply block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant; }
    .raliva-figure { @apply font-body-md font-bold tracking-tight leading-none; }
    .raliva-input { @apply w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3.5 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant/60 focus:outline-none focus:border-gold-accent focus:ring-4 focus:ring-gold-accent/10 transition-all duration-200 disabled:bg-surface-container-low disabled:text-on-surface-variant disabled:cursor-not-allowed disabled:opacity-80; }
    .raliva-textarea { @apply w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3.5 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant/60 focus:outline-none focus:border-gold-accent focus:ring-4 focus:ring-gold-accent/10 transition-all duration-200; }
    .raliva-search { @apply w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-10 pr-3.5 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant/60 focus:outline-none focus:border-gold-accent focus:ring-4 focus:ring-gold-accent/10 transition-all duration-200; }
    .raliva-select { @apply w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-3.5 pr-10 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent focus:ring-4 focus:ring-gold-accent/10 transition-all duration-200 appearance-none cursor-pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23888888' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 16px; }
    .raliva-toggle { @apply relative inline-flex items-center cursor-pointer shrink-0 align-middle; }
    .raliva-toggle-track { @apply block w-11 h-6 bg-surface-container-high border border-outline-variant rounded-full peer-checked:bg-gold-accent peer-checked:border-gold-accent transition-colors duration-200; }
    .raliva-toggle-knob { @apply absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow pointer-events-none transition-transform duration-200 peer-checked:translate-x-5; }

    .text-gradient-gold {
        background: linear-gradient(115deg, #a8823a 0%, #C9A24D 35%, #ecd398 55%, #C9A24D 80%, #a8823a 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hero-glow { position: relative; }
    .hero-glow::before {
        content: '';
        position: absolute;
        inset: -30%;
        background: radial-gradient(circle at 70% 30%, rgba(201, 162, 77, 0.14), transparent 45%),
                    radial-gradient(circle at 15% 85%, rgba(201, 162, 77, 0.08), transparent 40%);
        pointer-events: none;
    }

    .gauge-progress {
        transition: stroke-dashoffset 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        filter: drop-shadow(0 0 6px rgba(201, 162, 77, 0.45));
    }

    .raliva-donut-seg { transition: stroke-dasharray 1.1s cubic-bezier(0.22, 1, 0.36, 1); }
    .raliva-bar { transition: height 0.9s cubic-bezier(0.22, 1, 0.36, 1); }
    .raliva-lb-fill { transition: width 1s cubic-bezier(0.22, 1, 0.36, 1); }

    .card-static:hover { transform: none; }

    @keyframes pageEnter {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes riseIn {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (prefers-reduced-motion: no-preference) {
        .page-enter { animation: pageEnter 0.55s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .rise { opacity: 0; animation: riseIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards; }

        /* Container beranimasi (page-enter / .rise) tetap menangkap event pointer
           saat masih opacity:0 / ber-transform, sehingga tombol di dalamnya tidak
           bisa diklik sampai animasi selesai. Solusi: container tidak menangkap
           klik, tapi semua elemen interaktif di dalamnya di-whitelist agar
           langsung bisa diklik meski container masih beranimasi. */
        .page-enter, .rise { pointer-events: none; }
        .page-enter button, .page-enter a, .page-enter input, .page-enter select,
        .page-enter textarea, .page-enter label, .page-enter [onclick],
        .page-enter [role="button"], .page-enter .filter-chip, .page-enter .user-card,
        .rise button, .rise a, .rise input, .rise select,
        .rise textarea, .rise label, .rise [onclick],
        .rise [role="button"], .rise .filter-chip, .rise .user-card {
            pointer-events: auto;
        }
    }

    /* ===== Sidebar mini (collapse) — semua role ===== */
    #sidebar .sidebar-scroll { overflow-x: hidden; touch-action: pan-y; overscroll-behavior-x: none; }
    #sidebar [data-menu-label], #sidebar [data-group-label] { -webkit-user-select: none; user-select: none; }
    #sidebar a, #sidebar .material-symbols-outlined { -webkit-user-drag: none; }

    /* Tooltip di-render global via JS (bebas terpotong overflow nav) */
    #sidebar .sidebar-tip { display: none !important; }
    #sidebar-tip-global {
        position: fixed;
        transform: translateY(-50%) translateX(-4px);
        white-space: nowrap;
        padding: 6px 10px;
        border-radius: 8px;
        background: #1b1c1c;
        color: #ffffff;
        font-family: 'Manrope', sans-serif;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        border: 1px solid rgba(201, 162, 77, .45);
        box-shadow: 0 8px 24px rgba(0, 0, 0, .28);
        opacity: 0;
        pointer-events: none;
        transition: opacity .18s ease-out, transform .18s ease-out;
        z-index: 70;
    }
    #sidebar-tip-global.visible { opacity: 1; transform: translateY(-50%) translateX(0); }
    .dark #sidebar-tip-global { background: #F0EEEE; color: #111111; }

    #sidebar.sidebar-collapsed { width: 4.75rem; padding-left: .5rem; padding-right: .5rem; }
    #sidebar.sidebar-collapsed [data-sidebar-text],
    #sidebar.sidebar-collapsed [data-menu-label],
    #sidebar.sidebar-collapsed [data-group-label] { display: none !important; }
    #sidebar.sidebar-collapsed [data-sidebar-group-button] { display: none !important; }
    #sidebar.sidebar-collapsed .sidebar-head { flex-direction: column; gap: .75rem; }
    #sidebar.sidebar-collapsed nav a { justify-content: center; gap: 0; padding-left: 0; padding-right: 0; margin-right: 0; }
    #sidebar.sidebar-collapsed nav a > .material-symbols-outlined:first-child { margin-right: 0; }
    #sidebar.sidebar-collapsed .sidebar-collapse-btn { justify-content: center; }
    #sidebar.sidebar-collapsed .sidebar-collapse-btn .icon-chevron { transform: rotate(180deg); }
    /* Sidebar profile — collapsed: center avatar, hide card chrome */
    #sidebar.sidebar-collapsed .sidebar-profile { justify-content: center; padding: 10px 0; margin-left: 0; margin-right: 0; background: transparent !important; border-color: transparent !important; box-shadow: none !important; }
    #sidebar.sidebar-collapsed .sidebar-profile .w-11 { width: 2.75rem; height: 2.75rem; font-size: 13px; border-width: 2px; }

    /* Overlay modal tidak boleh terbawa margin flow (mis. dari parent space-y-*) */
    [data-modal] { margin: 0 !important; }
</style>
@stack('styles')
