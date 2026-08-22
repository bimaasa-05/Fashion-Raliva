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
        --c-sidebar: 17 17 17;
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
    .sidebar-scroll { scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.18) transparent; }
    .sidebar-scroll::-webkit-scrollbar { width: 4px; }
    .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
    .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.18); border-radius: 9999px; }
    .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }

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
    }
</style>
@stack('styles')
