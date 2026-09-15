<!DOCTYPE html>
<html class="light" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - {{ __('Checkout') }} — {{ __('Bayar') }}</title>
    <script>
        if (localStorage.getItem('raliva-theme') === 'dark') document.documentElement.classList.add('theme-dark');
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;family=Playfair+Display:wght@500;600&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
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
                        "display-lg": ["40px", {
                            "lineHeight": "48px",
                            "letterSpacing": "-0.02em",
                            "fontWeight": "600"
                        }],
                        "label-caps": ["12px", {
                            "lineHeight": "16px",
                            "letterSpacing": "0.08em",
                            "fontWeight": "700"
                        }],
                        "headline-lg-mobile": ["28px", {
                            "lineHeight": "36px",
                            "fontWeight": "500"
                        }],
                        "headline-lg": ["32px", {
                            "lineHeight": "40px",
                            "fontWeight": "500"
                        }],
                        "title-md": ["18px", {
                            "lineHeight": "24px",
                            "letterSpacing": "0.01em",
                            "fontWeight": "600"
                        }],
                        "headline-md": ["24px", {
                            "lineHeight": "32px",
                            "fontWeight": "500"
                        }],
                        "body-lg": ["16px", {
                            "lineHeight": "24px",
                            "fontWeight": "400"
                        }],
                        "body-sm": ["14px", {
                            "lineHeight": "20px",
                            "fontWeight": "400"
                        }],
                        "label-sm": ["12px", {
                            "lineHeight": "16px",
                            "fontWeight": "500"
                        }]
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
            --chrome-bg-soft: rgba(255, 255, 255, .92);
            --chrome-text: #1b1c1c;
            --chrome-text-dim: rgba(0, 0, 0, .55);
            --chrome-text-faint: rgba(0, 0, 0, .45);
            --chrome-border: rgba(0, 0, 0, .1);
            --chrome-hover: rgba(0, 0, 0, .06);
            --chrome-accent: #8B1E3F;
            --surface-ivory: #F8F6F2;
            --surface-warm: #F3F0EA;
            --border-soft: #E5E1DA;
            --text-muted: #777777;
        }

        html.theme-dark {
            --chrome-bg: #1c1b1b;
            --chrome-bg-soft: rgba(28, 27, 27, .9);
            --chrome-text: #ffffff;
            --chrome-text-dim: rgba(255, 255, 255, .6);
            --chrome-text-faint: rgba(255, 255, 255, .5);
            --chrome-border: rgba(255, 255, 255, .1);
            --chrome-hover: rgba(255, 255, 255, .1);
            --chrome-accent: #8B1E3F;
            --surface-ivory: #1e1d1c;
            --surface-warm: #201f1e;
            --border-soft: rgba(255, 255, 255, .1);
            --text-muted: #b9b6b1;
        }
    </style>
    <style>
        .acc-ew-brand.dana { --brand: #008EDA; --brand-rgb: 0, 142, 218; }
.acc-ew-brand.gopay { --brand: #00B7C4; --brand-rgb: 0, 183, 196; }
.acc-ew-brand.ovo { --brand: #4C2E8D; --brand-rgb: 76, 46, 141; }
.acc-ew-brand.shopeepay { --brand: #EE4D2D; --brand-rgb: 238, 77, 45; }

.acc-bank-brand.bca { --brand: #0060AE; --brand-rgb: 0, 96, 174; }
.acc-bank-brand.bri { --brand: #00529C; --brand-rgb: 0, 82, 156; }
.acc-bank-brand.bni { --brand: #F7941E; --brand-rgb: 247, 148, 30; }
.acc-bank-brand.mandiri { --brand: #FFC400; --brand-rgb: 255, 196, 0; }

.ew-accounts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
    position: relative;
    width: 100%;
}

.ew-card-wrap {
    width: 100%;
    transition: transform .3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity .25s ease;
}

/* Sembunyikan kartu non-aktif */
.ew-accounts-grid.ew-expanded .ew-card-wrap:not(.ew-active) {
    display: none !important;
}

/* Posisi penuh saat aktif */
.ew-card-wrap.ew-active {
    grid-column: 1 / -1;
    width: 100%;
}

/* Container utama E-Wallet */
.account-opt-ew {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 1.2rem;
    border: 1px solid var(--border-soft);
    border-radius: .6rem;
    background: var(--surface-warm);
    cursor: pointer;
    width: 100%;
    box-sizing: border-box;
    transition: transform .25s cubic-bezier(0.34, 1.56, 0.64, 1), border-color .2s ease, background .2s ease;
}

.account-opt-ew:hover { 
    border-color: var(--brand);
    transform: scale(1.02); /* Efek membal kecil saat di-hover */
}

/* State terpilih / Aktif */
.account-opt-ew.selected {
    border-color: var(--brand);
    background: rgba(var(--brand-rgb), .10);
    box-shadow: inset 0 0 0 1px rgba(var(--brand-rgb), .35);
    align-items: center;
    text-align: center;
    /* Animasi bubble mekar */
    animation: bubblePopOpen .4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

.account-opt-ew.selected .ew-brand-name {
    color: var(--brand);
    font-weight: 600;
}

/* Container Detail (Konten Rekening) */
.account-opt-ew .ew-card-detail {
    display: none;
    width: 100%;
}

.account-opt-ew.selected .ew-card-detail {
    display: block;
    /* Animasi kemunculan detail teks dari bawah */
    animation: bubbleDetailFade .35s ease-out forwards;
}

.account-opt-ew .ew-card-detail-inner {
    margin-top: .8rem;
    padding-top: .8rem;
    border-top: 1px solid rgba(var(--brand-rgb), .30);
    text-align: left;
    width: 100%;
}

.ew-detail-nama {
    font-family: 'Manrope', sans-serif;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.3;
    color: var(--brand);
    margin: 0 0 .4rem;
}

.ew-detail-line {
    font-family: 'Manrope', sans-serif;
    font-size: 12px;
    line-height: 1.5;
    color: var(--text-muted);
    margin: 0;
}

.ew-detail-line strong {
    color: var(--chrome-text);
    font-weight: 600;
}

/* Dark Mode adjustment */
html.theme-dark .account-opt-ew { background: #201f1e; border-color: rgba(255,255,255,.08); }
html.theme-dark .account-opt-ew.selected { background: rgba(var(--brand-rgb), .14); }
html.theme-dark .ew-detail-line { color: #b9b6b1; }
html.theme-dark .ew-detail-line strong { color: #e6e4e1; }

/* ========================================================= */
/* ANIMASI KEYFRAMES BUBBLE POP (MEKAR & MEMBAL)            */
/* ========================================================= */
@keyframes bubblePopOpen {
    0% {
        transform: scale(0.88);
        opacity: 0.7;
    }
    60% {
        transform: scale(1.03);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes bubblePopClose {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    40% {
        transform: scale(1.04);
    }
    100% {
        transform: scale(0.92);
        opacity: 0.8;
    }
}

@keyframes bubbleDetailFade {
    0% {
        opacity: 0;
        transform: translateY(10px) scale(0.96);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Class pembantu untuk animasi tertutup via JS */
.account-opt-ew.closing {
    animation: bubblePopClose .25s cubic-bezier(0.4, 0, 0.2, 1) forwards !important;
}
    </style>
    <style>
        html.theme-dark .bg-background,
        html.theme-dark .bg-surface,
        html.theme-dark .bg-surface-bright {
            background-color: #161514 !important;
        }

        html.theme-dark .bg-surface-container-lowest {
            background-color: #1e1d1c !important;
        }

        html.theme-dark .bg-surface-container-low {
            background-color: #201f1e !important;
        }

        html.theme-dark .bg-surface-container {
            background-color: #262524 !important;
        }

        html.theme-dark .bg-surface-container-high {
            background-color: #2c2b2a !important;
        }

        html.theme-dark .bg-surface-container-highest,
        html.theme-dark .bg-surface-variant {
            background-color: #323130 !important;
        }

        html.theme-dark .bg-surface\/50 {
            background-color: rgba(38, 37, 36, .5) !important;
        }

        html.theme-dark .bg-surface\/95 {
            background-color: rgba(22, 21, 20, .95) !important;
        }

        html.theme-dark .bg-background\/90 {
            background-color: rgba(22, 21, 20, .9) !important;
        }

        html.theme-dark .bg-surface-container-lowest\/50 {
            background-color: rgba(30, 29, 28, .5) !important;
        }

        html.theme-dark .from-surface\/80 {
            --tw-gradient-from: rgba(22, 21, 20, .85) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-via, transparent), var(--tw-gradient-to, transparent) !important;
        }

        html.theme-dark .bg-primary {
            background-color: #f2efec !important;
        }

        html.theme-dark .bg-primary\/5 {
            background-color: rgba(242, 239, 236, .08) !important;
        }

        html.theme-dark .text-primary {
            color: #f2efec !important;
        }

        html.theme-dark .text-on-primary {
            color: #1b1a19 !important;
        }

        html.theme-dark .border-primary {
            border-color: #f2efec !important;
        }

        html.theme-dark .text-on-surface,
        html.theme-dark .text-on-background {
            color: #e6e4e1 !important;
        }

        html.theme-dark .text-on-surface-variant {
            color: #b9b6b1 !important;
        }

        html.theme-dark .text-on-surface-variant\/70 {
            color: rgba(185, 182, 177, .7) !important;
        }

        html.theme-dark .text-outline {
            color: #8a8781 !important;
        }

        html.theme-dark .text-outline-variant {
            color: #6f6d68 !important;
        }

        html.theme-dark .text-error {
            color: #ffb4ab !important;
        }

        html.theme-dark .text-secondary {
            color: #8B1E3F !important;
        }

        html.theme-dark .placeholder-on-surface-variant::placeholder {
            color: #b9b6b1 !important;
        }

        html.theme-dark .border-outline-variant {
            border-color: #3a3937 !important;
        }

        html.theme-dark .border-outline {
            border-color: #4a4844 !important;
        }

        html.theme-dark .border-surface-variant {
            border-color: #2c2b2a !important;
        }

        html.theme-dark .border-on-surface {
            border-color: #e6e4e1 !important;
        }

        html.theme-dark .border-error {
            border-color: #ffb4ab !important;
        }

        html.theme-dark .bg-outline-variant {
            background-color: #3a3937 !important;
        }

        html.theme-dark .bg-on-surface {
            background-color: #e6e4e1 !important;
        }

        html.theme-dark .hover\:bg-surface-container-low:hover {
            background-color: #201f1e !important;
        }

        html.theme-dark .hover\:bg-surface-container-high:hover {
            background-color: #2c2b2a !important;
        }

        html.theme-dark .hover\:bg-surface-variant:hover {
            background-color: #323130 !important;
        }

        html.theme-dark .hover\:bg-surface:hover {
            background-color: #262524 !important;
        }

        html.theme-dark .hover\:bg-primary:hover {
            background-color: #ffffff !important;
        }

        html.theme-dark .hover\:text-secondary:hover {
            color: #8B1E3F !important;
        }

        html.theme-dark .hover\:text-primary:hover {
            color: #f2efec !important;
        }

        html.theme-dark .hover\:text-on-surface:hover {
            color: #e6e4e1 !important;
        }

        html.theme-dark .hover\:text-error:hover {
            color: #ffb4ab !important;
        }

        html.theme-dark .hover\:border-primary:hover {
            border-color: #f2efec !important;
        }

        html.theme-dark .hover\:border-on-surface:hover {
            border-color: #e6e4e1 !important;
        }

        html.theme-dark .hover\:border-outline:hover {
            border-color: #4a4844 !important;
        }

        html.theme-dark .focus\:border-primary:focus {
            border-color: #f2efec !important;
        }

        html.theme-dark .focus\:border-on-surface:focus {
            border-color: #e6e4e1 !important;
        }

        html.theme-dark .focus\:border-outline:focus {
            border-color: #4a4844 !important;
        }

        html.theme-dark .group:hover .group-hover\:text-primary {
            color: #f2efec !important;
        }

        html.theme-dark .group:hover .group-hover\:border-outline {
            border-color: #4a4844 !important;
        }

        html.theme-dark .peer:checked~.peer-checked\:bg-primary {
            background-color: #f2efec !important;
        }
    </style>
    <style>
        .card-premium {
            box-shadow: 0 1px 2px rgb(17 17 17 / .04), 0 12px 32px -16px rgb(17 17 17 / .16);
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }

        .card-premium:hover {
            box-shadow: 0 2px 4px rgb(17 17 17 / .05), 0 20px 48px -20px rgb(17 17 17 / .22);
            border-color: rgba(139, 30, 63, .45);
        }

        html.theme-dark .card-premium {
            background-color: var(--surface-ivory);
            border-color: var(--border-soft);
            box-shadow: 0 1px 2px rgb(0 0 0 / .3), 0 8px 24px -12px rgb(0 0 0 / .5);
        }

        html.theme-dark .card-premium:hover {
            box-shadow: 0 2px 4px rgb(0 0 0 / .4), 0 20px 48px -20px rgb(0 0 0 / .7);
            border-color: rgba(139, 30, 63, .55);
        }

        .premium-heading {
            display: block;
        }

        .premium-heading::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: .95em;
            margin-right: .65rem;
            background: #8B1E3F;
            border-radius: 9999px;
            vertical-align: -.05em;
        }

        .atl-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .65rem;
        }

        .atl-eyebrow::before {
            content: '';
            width: 30px;
            height: 1px;
            background: var(--chrome-accent);
            opacity: .7;
        }

        html.theme-dark .premium-heading::before {
            background: #8B1E3F;
        }

        .reveal-up {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity .5s ease, transform .5s ease;
        }

        .reveal-up.is-visible {
            opacity: 1;
            transform: none;
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal-up {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }

        .carpet-down {
            animation: carpetDown .45s cubic-bezier(.4, 0, .2, 1) both;
        }

        @keyframes carpetDown {
            from {
                clip-path: inset(0 0 100% 0);
                opacity: 0;
            }
            to {
                clip-path: inset(0 0 0 0);
                opacity: 1;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .carpet-down {
                animation: none;
            }
        }

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
            background: rgba(255, 255, 255, .55);
            transform: skewX(-24deg);
            pointer-events: none;
        }

        .btn-gold:hover::after {
            animation: authFlash 1.4s linear infinite;
        }

        .btn-gold.flashing::after {
            animation: authFlash 1.4s cubic-bezier(.4, 0, .2, 1) 1;
        }

        @keyframes authFlash {
            from {
                left: -80%;
            }

            to {
                left: 135%;
            }
        }

        :root {
            --btn-gold-bg: #8B1E3F;
            --btn-gold-text: #ffffff;
        }

        html.theme-dark {
            --btn-gold-bg: #6D1428;
            --btn-gold-text: #ffffff;
        }

        #drawer-panel {
            --chrome-accent: #8B1E3F;
            --gold-wash: rgba(139, 30, 63, .10);
        }

        html.theme-dark #drawer-panel {
            --chrome-accent: #8B1E3F;
            --gold-wash: rgba(163, 38, 63, .16);
        }
    </style>
    <style>
        .co-stepper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
        }

        .co-step {
            display: flex;
            align-items: center;
            gap: .45rem;
            font-family: 'Manrope', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .co-step .num {
            width: 28px;
            height: 28px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            border: 1.5px solid var(--border-soft);
            background: var(--surface-warm);
            color: var(--text-muted);
        }

        .co-step.active .num {
            background: #8B1E3F;
            border-color: #8B1E3F;
            color: #fff;
        }

        .co-step.done .num {
            background: #8B1E3F;
            border-color: #8B1E3F;
            color: #fff;
        }

        .co-step.active {
            color: #8B1E3F;
        }

        .co-step:not(.active):not(.done) {
            color: var(--text-muted);
        }

        .co-step-line {
            width: 32px;
            height: 1px;
            background: var(--border-soft);
        }

        .co-step-line.done {
            background: #8B1E3F;
        }

        /* Spinner loading untuk step belum dicapai */
        .co-step .num.loading {
            border: 2px solid var(--border-soft);
            border-top-color: #8B1E3F;
            background: transparent !important;
            color: transparent !important;
            animation: co-spin 0.75s linear infinite;
        }
        .co-step .num.loading::after {
            content: '';
            display: none;
        }
        @keyframes co-spin {
            to { transform: rotate(360deg); }
        }

        /* Step yang sudah selesai (done) bisa diklik untuk kembali */
        .co-step.done {
            cursor: pointer;
            text-decoration: none;
            transition: opacity .2s ease;
        }
        .co-step.done:hover {
            opacity: .75;
        }

        .pay-method {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            padding: .75rem;
            border: 1px solid var(--border-soft);
            border-radius: .75rem;
            background: var(--surface-warm);
            cursor: pointer;
            transition: background .18s, border-color .18s, color .18s;
        }

        .pay-method:hover {
            background: #ECE7DF;
            border-color: #8B1E3F;
            color: #8B1E3F;
        }

        .pay-method.selected {
            border-color: #8B1E3F;
            background: rgba(139, 30, 63, .08);
            color: #8B1E3F;
            font-weight: 600;
            box-shadow: inset 0 0 0 1px rgba(139, 30, 63, .15);
        }

        html.theme-dark .pay-method:hover {
            background: #262524;
        }

        html.theme-dark .pay-method.selected {
            background: rgba(139, 30, 63, .18);
            color: #FFC2C9;
        }

        .pay-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .5rem;
        }

        @media(min-width:768px) {
            .pay-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: .75rem;
            }
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
            padding: .55rem 0;
            font-family: 'Manrope', sans-serif;
            font-size: 13px;
            color: var(--text-muted);
        }

        .detail-row strong {
            color: var(--on-surface);
            font-weight: 600;
        }

        .detail-row.total {
            border-top: 1px solid var(--border-soft);
            margin-top: .5rem;
            padding-top: .75rem;
            font-size: 15px;
            font-weight: 600;
            color: var(--on-surface);
        }

        .banner-akun {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }

        html.theme-dark .banner-akun {
            background: rgba(16, 185, 129, .12);
            border-color: rgba(16, 185, 129, .35);
            color: #a7f3d0;
        }
    </style>
</head>

<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] lg:pl-72">

    @php
        $akunBaruEmail = session('akun_baru');
    @endphp

    <header
        class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
        <a href="{{ route('customer.checkout', request()->query('buy') ? ['buy' => request()->query('buy')] : []) }}"
            aria-label="Back" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
            <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
        </a>
        <h1
            class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">
            {{ __('Bayar') }}</h1>
        <div class="w-10"></div>
    </header>

    <main class="pt-6 pb-10 w-full overflow-x-hidden">
        <div class="mx-auto max-w-[1400px] px-container-margin">

            <div
                class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-sm mb-md flex justify-center reveal-up">
                <div class="co-stepper">
                    <a href="{{ route('customer.checkout', request()->query('buy') ? ['buy' => request()->query('buy')] : []) }}"
                        class="co-step done"><span class="num"><span
                                class="material-symbols-outlined text-[14px]">check</span></span>
                        {{ __('Review') }}</a>
                    <span class="co-step-line done"></span>
                    <span class="co-step active"><span class="num">2</span> {{ __('Bayar') }}</span>
                    <span class="co-step-line"></span>
                    <span class="co-step"><span class="num loading"></span> {{ __('Selesai') }}</span>
                </div>
            </div>

            @if ($akunBaruEmail)
                <div class="banner-akun rounded-xl p-md mb-lg flex items-start gap-sm reveal-up">
                    <span class="material-symbols-outlined text-[22px] shrink-0 mt-0.5">celebration</span>
                    <div class="min-w-0">
                        <p class="font-body-sm text-body-sm font-semibold">{{ __('Akun berhasil dibuat!') }}</p>
                        <p class="font-body-sm text-body-sm mt-xs">{{ __('Email') }}:
                            <strong>{{ $akunBaruEmail }}</strong> &nbsp;•&nbsp; {{ __('Password') }}:
                            <strong>Raliva123</strong></p>
                        <p class="font-label-sm text-label-sm mt-xs opacity-80">
                            {{ __('Simpan kredensial ini. Ubah password di') }} <a
                                href="{{ route('customer.account.password') }}"
                                class="underline underline-offset-2 font-semibold">{{ __('My Account → Ganti Password') }}</a>.
                        </p>
                    </div>
                </div>
            @endif

            @if ($payment->status === \App\Models\Payment::STATUS_MENUNGGU_VERIFIKASI)
                <div
                    class="bg-surface-container-low border border-outline-variant rounded-xl p-md mb-lg flex items-center gap-sm reveal-up">
                    <span class="material-symbols-outlined text-secondary">hourglass_top</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        {{ __('Bukti pembayaran sedang diverifikasi oleh admin. Anda akan mendapat notifikasi setelah diverifikasi.') }}
                    </p>
                </div>
            @elseif ($payment->status === \App\Models\Payment::STATUS_DITOLAK)
                <div
                    class="bg-surface-container-low border border-error rounded-xl p-md mb-lg flex items-center gap-sm reveal-up">
                    <span class="material-symbols-outlined text-error">error</span>
                    <p class="font-body-sm text-body-sm text-error">
                        {{ __('Bukti pembayaran Anda ditolak. Silakan pilih metode & unggah ulang bukti yang benar.') }}
                    </p>
                </div>
            @elseif ($payment->status === \App\Models\Payment::STATUS_TERVERIFIKASI)
                <div
                    class="bg-surface-container-low border border-outline-variant rounded-xl p-md mb-lg flex items-center gap-sm reveal-up">
                    <span class="material-symbols-outlined text-secondary">task_alt</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        {{ __('Pembayaran telah diverifikasi. Pesanan Anda sedang diproses.') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-error-container border border-error/20 rounded-xl p-md mb-md">
                    <p class="font-body-sm text-body-sm text-on-error-container font-semibold mb-xs">
                        {{ __('Periksa isian:') }}</p>
                    <ul class="list-disc list-inside font-body-sm text-body-sm text-on-error-container">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('toast'))
                <div
                    class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md mb-md flex items-center gap-sm">
                    <span class="material-symbols-outlined text-secondary">info</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        {{ session('toast')['message'] ?? session('toast') }}</p>
                </div>
            @endif

            @if (in_array($payment->status, [\App\Models\Payment::STATUS_PENDING, \App\Models\Payment::STATUS_DITOLAK], true))
                <div class="grid grid-cols-1 lg:grid-cols-[1.7fr_1fr] gap-lg items-start">

                    <div class="space-y-lg">
                        <div
                            class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
                            <p
                                class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">
                                {{ __('METODE PEMBAYARAN') }}</p>
                            <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">
                                {{ __('Pilih Metode Pembayaran') }}</h3>

                            <form id="form-bayar" method="POST"
                                action="{{ route('customer.checkout.payment.upload', $checkout->checkout_id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="payment_method_id" id="input-payment-method"
                                    value="{{ old('payment_method_id', $payment->payment_method_id) }}" />
                                <input type="hidden" name="payment_method_account_id" id="input-account-id"
                                    value="{{ old('payment_method_account_id', $payment->payment_method_account_id) }}" />

                                @if ($paymentMethods->isEmpty())
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                                        {{ __('Belum ada metode pembayaran aktif. Hubungi admin.') }}</p>
                                @else
                                    <div class="pay-grid" id="pay-grid">
                                        @foreach ($paymentMethods as $pm)
                                            @php
                                                $isSelected =
                                                    (string) old('payment_method_id', $payment->payment_method_id) ===
                                                    (string) $pm->payment_method_id;
                                                $icon = match ($pm->kode_metode) {
                                                    'qris' => 'qr_code_2',
                                                    'ewallet' => 'account_balance_wallet',
                                                    'bank_transfer' => 'account_balance',
                                                    default => 'payments',
                                                };
                                                $qrAccount = $pm->kode_metode === 'qris' ? $pm->accounts->first() : null;
                                            @endphp
                                            <div class="pay-method{{ $isSelected ? ' selected' : '' }}"
                                                data-id="{{ $pm->payment_method_id }}"
                                                data-nama="{{ $pm->nama_metode }}"
                                                data-kode="{{ $pm->kode_metode }}"
                                                data-account-id="{{ $qrAccount?->payment_method_account_id ?? '' }}">
                                                <span
                                                    class="material-symbols-outlined text-[28px]">{{ $icon }}</span>
                                                <span
                                                    class="text-center leading-tight text-sm">{{ $pm->nama_metode }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div id="pay-detail" class="hidden mt-lg space-y-lg">
                                        @foreach ($paymentMethods as $pm)
                                            @php
                                                $kode = $pm->kode_metode;
                                                $accts = $pm->accounts;
                                            @endphp
                                            <div id="detail-{{ $kode }}" class="method-detail hidden"
                                                data-kode="{{ $kode }}">
                                                @if ($kode === 'qris')
                                                    @php $qr = $accts->first(); @endphp
                                                    @if ($qr)
                                                        <div
                                                            class="border border-outline-variant rounded-xl p-md md:p-lg">
                                                            <div
                                                                class="flex flex-col sm:flex-row sm:items-center gap-md">
                                                                @if ($qr->file_gambar)
                                                                    <img src="{{ asset('storage/' . ltrim($qr->file_gambar, '/')) }}"
                                                                        alt="{{ $qr->nama }}"
                                                                        class="w-44 h-44 object-contain rounded-lg border border-outline-variant bg-white mx-auto sm:mx-0" />
                                                                @endif
                                                                <div class="min-w-0 text-center sm:text-left">
                                                                    <p
                                                                        class="font-title-md text-title-md text-on-surface">
                                                                        {{ $qr->nama }}</p>
                                                                    <p
                                                                        class="font-body-sm text-body-sm text-on-surface-variant mt-xs">
                                                                        {{ $qr->deskripsi }}</p>
                                                                    <p
                                                                        class="font-body-sm text-body-sm text-on-surface mt-sm">
                                                                        {{ __('Nama') }}:
                                                                        <strong>{{ $qr->nama_pemilik }}</strong></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @elseif ($kode === 'ewallet')
                                                    @php
                                                        $ewIcons = [
                                                            'dana' => 'images/E-Wallet/dana.png',
                                                            'gopay' => 'images/E-Wallet/gopay.jpg',
                                                            'ovo' => 'images/E-Wallet/ovo.png',
                                                            'shopeepay' => 'images/E-Wallet/shoopepay.jfif',
                                                        ];
                                                        $accts = $accts
                                                            ->filter(fn ($a) => array_key_exists($a->kode, $ewIcons))
                                                            ->values();
                                                    @endphp
                                                    <div class="space-y-sm">
                                                        <p
                                                            class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">
                                                            {{ __('Pilih salah satu') }}
                                                            {{ $pm->nama_metode }}:</p>
                                                        <div class="ew-accounts-grid" id="grid-ewallet">
                                                            @foreach ($accts as $a)
                                                                @php $sel = (string) ($payment->payment_method_account_id ?? '') === (string) $a->payment_method_account_id; @endphp
                                                                <div class="ew-card-wrap{{ $sel ? ' ew-active' : '' }}" data-kode="{{ $a->kode }}">
                                                                    <div class="account-opt account-opt-ew acc-ew-brand {{ $a->kode }}{{ $sel ? ' selected' : '' }}"
                                                                        data-panel="{{ $kode }}"
                                                                        data-account-id="{{ $a->payment_method_account_id }}"
                                                                        data-nama="{{ $a->nama }}"
                                                                        data-rekening="{{ $a->nomor_rekening ?? '-' }}"
                                                                        data-pemilik="{{ $a->nama_pemilik ?? '-' }}">
                                                                        <img src="{{ asset($ewIcons[$a->kode]) }}"
                                                                            alt="{{ $a->nama }}" class="h-7 object-contain" />
                                                                        <span
                                                                            class="ew-brand-name text-xs leading-tight mt-1">{{ $a->nama }}</span>
                                                                        <div class="ew-card-detail">
                                                                            <div class="ew-card-detail-inner">
                                                                                <p class="ew-detail-nama">{{ $a->nama }}</p>
                                                                                <p class="ew-detail-line">
                                                                                    <span>{{ __('Rekening/Nomor') }}:</span>
                                                                                    <strong>{{ $a->nomor_rekening ?? '-' }}</strong>
                                                                                </p>
                                                                                <p class="ew-detail-line">
                                                                                    <span>{{ __('Atas nama') }}:</span>
                                                                                    <strong>{{ $a->nama_pemilik ?? '-' }}</strong>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    @php
                                                        $brandIcons = [
                                                            'bca' => 'images/Bank/bca.png',
                                                            'bri' => 'images/Bank/bri.png',
                                                            'bni' => 'images/Bank/bni.png',
                                                            'mandiri' => 'images/Bank/mandiri.png',
                                                        ];
                                                        $accts = $accts
                                                            ->filter(fn ($a) => array_key_exists($a->kode, $brandIcons))
                                                            ->values();
                                                    @endphp
                                                    <div class="space-y-sm">
                                                        <p
                                                            class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">
                                                            {{ __('Pilih salah satu') }}
                                                            {{ $pm->nama_metode }}:</p>
                                                        <div class="ew-accounts-grid" id="grid-{{ $kode }}">
                                                            @foreach ($accts as $a)
                                                                @php $sel = (string) ($payment->payment_method_account_id ?? '') === (string) $a->payment_method_account_id; @endphp
                                                                <div class="ew-card-wrap{{ $sel ? ' ew-active' : '' }}" data-kode="{{ $a->kode }}">
                                                                    <div class="account-opt account-opt-ew acc-bank-brand {{ $a->kode }}{{ $sel ? ' selected' : '' }}"
                                                                        data-panel="{{ $kode }}"
                                                                        data-account-id="{{ $a->payment_method_account_id }}"
                                                                        data-nama="{{ $a->nama }}"
                                                                        data-rekening="{{ $a->nomor_rekening ?? '-' }}"
                                                                        data-pemilik="{{ $a->nama_pemilik ?? '-' }}">
                                                                        <img src="{{ asset($brandIcons[$a->kode]) }}"
                                                                            alt="{{ $a->nama }}" class="h-7 object-contain" />
                                                                        <span
                                                                            class="ew-brand-name text-xs leading-tight mt-1">{{ $a->nama }}</span>
                                                                        <div class="ew-card-detail">
                                                                            <div class="ew-card-detail-inner">
                                                                                <p class="ew-detail-nama">{{ $a->nama }}</p>
                                                                                <p class="ew-detail-line">
                                                                                    <span>{{ __('Nomor Rekening') }}:</span>
                                                                                    <strong>{{ $a->nomor_rekening ?? '-' }}</strong>
                                                                                </p>
                                                                                <p class="ew-detail-line">
                                                                                    <span>{{ __('Atas nama') }}:</span>
                                                                                    <strong>{{ $a->nama_pemilik ?? '-' }}</strong>
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @error('payment_method_id')
                                    <p class="font-label-sm text-label-sm text-error mt-sm">{{ $message }}</p>
                                @enderror
                                <p class="font-label-sm text-label-sm text-on-surface-variant/70 mt-sm"
                                    id="pay-selected-hint">
                                    @if (old('payment_method_id', $payment->payment_method_id))
                                        {{ __('Metode terpilih akan ditampilkan di rincian.') }}
                                    @else
                                        {{ __('Pilih salah satu metode di atas.') }}
                                    @endif
                                </p>

<div id="panel-bukti" class="hidden">
                                    <div class="mt-lg">
                                        <p
                                            class="font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-sm flex items-center gap-2">
                                            <span class="w-7 h-px bg-[var(--chrome-accent)] opacity-60"></span>
                                            {{ __('UPLOAD BUKTI') }}</p>
                                    @php $buktiTerakhir = $payment->proofs->last(); @endphp
                                    @if ($buktiTerakhir)
                                        <div class="flex items-center gap-md border border-outline-variant rounded-xl p-md mb-sm bg-surface-container-low/50">
                                            <span class="material-symbols-outlined text-on-surface-variant shrink-0">receipt_long</span>
                                            <span class="font-body-sm text-body-sm text-on-surface-variant min-w-0 flex-1 truncate">{{ \Illuminate\Support\Str::afterLast($buktiTerakhir->file_bukti, '/') }}</span>
                                            <a href="{{ asset('storage/' . ltrim($buktiTerakhir->file_bukti, '/')) }}" target="_blank" rel="noopener"
                                                class="font-label-sm text-label-sm text-secondary uppercase tracking-wider hover:underline shrink-0">{{ __('Lihat Bukti') }}</a>
                                        </div>
                                    @endif
                                    <label id="dropzone"
                                        class="flex flex-col items-center justify-center gap-sm border-2 border-dashed border-outline rounded-xl py-xl bg-surface-container-low cursor-pointer hover:border-secondary transition-colors text-center px-md">
                                        <span id="dropzone-icon"
                                            class="material-symbols-outlined text-[40px] text-on-surface-variant">upload_file</span>
                                        <span id="upload-hint"
                                            class="font-body-sm text-body-sm text-on-surface-variant text-center">{{ __('Klik untuk memilih gambar bukti transfer (JPG/PNG, maks 4MB)') }}</span>
                                        <img id="preview-bukti" alt="{{ __('Pratinjau bukti') }}"
                                            class="hidden max-h-60 w-auto max-w-full object-contain rounded-lg border border-outline-variant bg-surface-container-lowest" />
                                        <input type="file" name="bukti" id="input-bukti"
                                            accept="image/jpeg,image/png,image/jpg" class="sr-only" required />
                                    </label>
                                    @error('bukti')
                                        <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div id="btn-actions" class="mt-lg" data-prev-proof="@json((bool) $buktiTerakhir)">
    <button type="submit" id="btn-unggah"{{ $buktiTerakhir ? ' class="btn-gold w-full inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest hidden"' : ' class="btn-gold w-full inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest"' }}>
        <span class="material-symbols-outlined text-[20px]" id="btn-unggah-icon">task_alt</span>
        <span id="btn-unggah-text">{{ __('Unggah Bukti Pembayaran') }}</span>
    </button>

    <div id="btn-pair" class="{{ $buktiTerakhir ? 'grid grid-cols-2 gap-sm' : 'grid grid-cols-2 gap-sm hidden' }}">
        <button type="button" id="btn-ganti"
            class="w-full inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest border border-outline text-on-surface hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-[20px]">photo_camera_back</span>
            <span>{{ __('Ganti') }}</span>
        </button>

        <button type="submit" id="btn-selesai"
            class="btn-gold w-full inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <span>{{ __('Selesai') }}</span>
        </button>
    </div>
</div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div
                        class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up lg:sticky lg:top-20">
                        <p
                            class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">
                            {{ __('RINCIAN') }}</p>
                        <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">
                            {{ __('Rincian Pembayaran') }}</h3>
                        <div class="detail-row">
                            <span>{{ __('Nomor Pesanan') }}</span>
                            <span class="text-right">
                                @foreach ($checkout->orders as $o)
                                    <strong class="block">{{ $o->nomor_order }}</strong>
                                @endforeach
                            </span>
                        </div>
                        <div class="detail-row">
                            <span>{{ __('Metode') }}</span>
                            <div class="text-right">
                                <strong id="rincian-metode"
                                    class="block">{{ $payment->paymentMethod?->nama_metode ?? '—' }}</strong>
                                <span id="rincian-akun"
                                    class="block text-xs text-on-surface-variant font-normal mt-0.5">{{ $payment->account?->nama ?? '' }}</span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <span>{{ __('Total Dibayar') }}</span>
                            <strong class="text-[var(--chrome-accent)]">Rp
                                {{ number_format((float) $payment->jumlah, 0, ',', '.') }}</strong>
                        </div>
                        <div class="detail-row">
                            <span>{{ __('Batas Waktu') }}</span>
                            <strong>{{ $payment->batas_waktu?->format('d M Y, H:i') ?? '—' }}</strong>
                        </div>
                        <div class="detail-row">
                            <span>{{ __('Status') }}</span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        @if ($payment->status === 'pending') bg-amber-100 text-amber-800
                        @elseif($payment->status === 'menunggu_verifikasi') bg-blue-100 text-blue-800
                        @elseif($payment->status === 'terverifikasi') bg-emerald-100 text-emerald-800
                        @elseif($payment->status === 'ditolak') bg-red-100 text-red-800
                        @else bg-surface-container text-on-surface-variant @endif
                    ">{{ $payment->status }}</span>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-md">
                            {{ __('Transfer sesuai total di atas, lalu unggah bukti pembayaran untuk diverifikasi admin.') }}
                        </p>
                        @if ($checkout->email_pelanggan)
                            <p class="font-label-sm text-label-sm text-on-surface-variant/70 mt-sm">
                                {{ __('Email pemesan') }}: {{ $checkout->email_pelanggan }}</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-[1.7fr_1fr] gap-lg">
                    <div
                        class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
                        <p
                            class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">
                            {{ __('DETAIL') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mt-sm">
                            <div class="border border-outline-variant rounded-lg p-md">
                                <p
                                    class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">
                                    {{ __('Nomor Pesanan') }}</p>
                                <p class="font-body-lg text-body-lg font-semibold text-on-surface mt-xs">
                                    @foreach ($checkout->orders as $o)
                                        <span class="inline-block">{{ $o->nomor_order }}</span><br />
                                    @endforeach
                                </p>
                            </div>
                            <div class="border border-outline-variant rounded-lg p-md">
                                <p
                                    class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">
                                    {{ __('Metode Pembayaran') }}</p>
                                <p class="font-body-lg text-body-lg font-semibold text-on-surface mt-xs">
                                    {{ $payment->paymentMethod?->nama_metode ?? '—' }}</p>
                            </div>
                            <div class="border border-outline-variant rounded-lg p-md">
                                <p
                                    class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">
                                    {{ __('Total Dibayar') }}</p>
                                <p class="font-title-md text-title-md font-semibold text-[var(--chrome-accent)] mt-xs">
                                    Rp {{ number_format((float) $payment->jumlah, 0, ',', '.') }}</p>
                            </div>
                            <div class="border border-outline-variant rounded-lg p-md">
                                <p
                                    class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">
                                    {{ __('Batas Waktu') }}</p>
                                <p class="font-body-lg text-body-lg font-semibold text-on-surface mt-xs">
                                    {{ $payment->batas_waktu?->format('d M Y, H:i') ?? '—' }}</p>
                            </div>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-lg">
                            {{ __('Menunggu verifikasi admin. Kamu akan mendapat notifikasi bila disetujui.') }}</p>
                    </div>
                    <div
                        class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up lg:sticky lg:top-20">
                        <p
                            class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">
                            {{ __('RINCIAN') }}</p>
                        <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">
                            {{ __('Rincian Pembayaran') }}</h3>
                        <div class="detail-row"><span>{{ __('Nomor Pesanan') }}</span><span class="text-right">
                                @foreach ($checkout->orders as $o)
                                    <strong class="block">{{ $o->nomor_order }}</strong>
                                @endforeach
                            </span></div>
                        <div class="detail-row"><span>{{ __('Metode') }}</span><strong
                                class="text-right">{{ $payment->paymentMethod?->nama_metode ?? '—' }}</strong></div>
                        <div class="detail-row"><span>{{ __('Total') }}</span><strong
                                class="text-[var(--chrome-accent)]">Rp
                                {{ number_format((float) $payment->jumlah, 0, ',', '.') }}</strong></div>
                        <div class="detail-row"><span>{{ __('Status') }}</span><span
                                class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ $payment->status }}</span>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </main>

    @include('customer._partials.drawer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var els = document.querySelectorAll('.reveal-up');
            if (!('IntersectionObserver' in window)) {
                els.forEach(function(e) { e.classList.add('is-visible'); });
                return;
            }
            var io = new IntersectionObserver(function(entries) {
                entries.forEach(function(en) {
                    if (en.isIntersecting) {
                        en.target.classList.add('is-visible');
                        io.unobserve(en.target);
                    }
                });
            }, { threshold: 0.08 });
            els.forEach(function(e) { io.observe(e); });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var grid = document.getElementById('pay-grid');
            var input = document.getElementById('input-payment-method');
            var accountInput = document.getElementById('input-account-id');
            var rincian = document.getElementById('rincian-metode');
            var rincianAkun = document.getElementById('rincian-akun');
            var hint = document.getElementById('pay-selected-hint');
            var panelBukti = document.getElementById('panel-bukti');

            var showPanel = function(kode) {
                document.querySelectorAll('.method-detail').forEach(function(p) {
                    p.classList.add('hidden');
                });
                var panel = document.getElementById('detail-' + kode);
                if (panel) panel.classList.remove('hidden');
                var wrap = document.getElementById('pay-detail');
                if (wrap) wrap.classList.remove('hidden');
            };

            var proofs = {};
            var currentKode = null;
            var toastShown = {};

            var showBukti = function() {
                if (!panelBukti) return;
                panelBukti.classList.remove('hidden');
                panelBukti.classList.remove('carpet-down');
                void panelBukti.offsetWidth;
                panelBukti.classList.add('carpet-down');
            };

            var hideBukti = function() {
                if (!panelBukti) return;
                panelBukti.classList.add('hidden');
                panelBukti.classList.remove('carpet-down');
            };

            var assignFile = function(file) {
                if (!fileInput) return;
                if (!file) {
                    fileInput.value = '';
                    return;
                }
                var dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
            };

            var applyProofUi = function(kode) {
                if (!fileInput || !previewImg || !dropzone || !dropzoneIcon || !uploadHint) return;
                currentKode = kode;
                var store = proofs[kode] || null;
                if (store && store.file) {
                    assignFile(store.file);
                    previewImg.src = store.url;
                    previewImg.classList.remove('hidden');
                    dropzone.classList.add('border-secondary');
                    dropzoneIcon.classList.add('hidden');
                    uploadHint.classList.add('hidden');
                    showPair();
                } else {
                    assignFile(null);
                    previewImg.removeAttribute('src');
                    previewImg.classList.add('hidden');
                    dropzone.classList.remove('border-secondary');
                    dropzoneIcon.classList.remove('hidden');
                    uploadHint.classList.remove('hidden');
                    if (hasPrevProof) showPair(); else showSingle();
                }
            };

            var syncBukti = function() {
                var sel = grid ? grid.querySelector('.pay-method.selected') : null;
                if (!sel) { hideBukti(); return; }
                var kode = sel.getAttribute('data-kode');
                currentKode = kode;
                if (kode === 'qris') { applyProofUi(kode); showBukti(); return; }
                if (kode === 'ewallet' || kode === 'bank_transfer') {
                    var gridEl = document.getElementById('grid-' + kode);
                    var selAcc = gridEl ? gridEl.querySelector('.account-opt-ew.selected') : null;
                    if (selAcc) { applyProofUi(kode); showBukti(); return; }
                    hideBukti();
                    return;
                }
                applyProofUi(kode);
                showBukti();
            };

            var showToast = function(msg, dur) {
                var existing = document.getElementById('pay-toast');
                if (existing) existing.remove();
                var toast = document.createElement('div');
                toast.id = 'pay-toast';
                toast.textContent = msg;
                toast.style.cssText = 'position:fixed;left:50%;bottom:96px;transform:translateX(-50%);background:#1c1b1b;color:#fff;padding:10px 18px;border-radius:999px;font-size:13px;font-family:Manrope,sans-serif;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,.25);opacity:0;transition:opacity .3s ease;';
                document.body.appendChild(toast);
                requestAnimationFrame(function() { toast.style.opacity = '1'; });
                setTimeout(function() { toast.style.opacity = '0'; setTimeout(function() { toast.remove(); }, 350); }, dur || 1800);
            };

            var updateAccountDetail = function(opt) {
                var panelKode = opt.getAttribute('data-panel');
                var namaEl = document.getElementById('account-detail-nama-' + panelKode);
                var rekEl = document.getElementById('account-detail-rekening-' + panelKode);
                var pemEl = document.getElementById('account-detail-pemilik-' + panelKode);
                var wrap = document.getElementById('account-detail-' + panelKode);
                if (namaEl) namaEl.textContent = opt.getAttribute('data-nama') || '';
                if (rekEl) rekEl.textContent = 'Rekening/Nomor: ' + (opt.getAttribute('data-rekening') || '-');
                if (pemEl) pemEl.textContent = 'Atas nama: ' + (opt.getAttribute('data-pemilik') || '-');
                if (wrap) wrap.classList.remove('hidden');
            };

            var selectAccount = function(opt) {
    if (opt.classList.contains('account-opt-ew')) {
        var wrap = opt.closest('.ew-card-wrap');
        var ewGrid = opt.closest('.ew-accounts-grid');
        var isAlreadySelected = opt.classList.contains('selected');

        // Jika diklik kembali -> Jalankan animasi bubble tertutup
        if (isAlreadySelected) {
            opt.classList.add('closing');
            
            setTimeout(function() {
                opt.classList.remove('selected', 'closing');
                if (wrap) wrap.classList.remove('ew-active');
                if (ewGrid) ewGrid.classList.remove('ew-expanded');
                if (accountInput) accountInput.value = '';
                if (rincianAkun) rincianAkun.textContent = '';
                syncBukti();
            }, 200); // Penundaan sejenak mengikuti durasi animasi closing
            return;
        }

        // Reset semua kartu lain dalam grid yang sama
        if (ewGrid) {
            ewGrid.querySelectorAll('.ew-card-wrap').forEach(function(w) {
                w.classList.remove('ew-active');
            });
            ewGrid.querySelectorAll('.account-opt-ew').forEach(function(o) {
                o.classList.remove('selected', 'closing');
            });
        }

        var kodeMet = opt.getAttribute('data-panel');

        // Tampilkan kartu yang diklik dengan animasi mekar
        if (wrap) wrap.classList.add('ew-active');
        opt.classList.add('selected');
        if (ewGrid) ewGrid.classList.add('ew-expanded');

        if (accountInput) accountInput.value = opt.getAttribute('data-account-id');
        if (rincianAkun) rincianAkun.textContent = opt.getAttribute('data-nama') || '';
        syncBukti();
        if (kodeMet && !toastShown[kodeMet]) {
            toastShown[kodeMet] = true;
            showToast('Klik lagi untuk mengganti metode pembayaran.', 5000);
        }
        return;
                } else {
                    document.querySelectorAll('.account-opt').forEach(function(o) {
                        o.classList.remove('border-secondary', 'bg-secondary/5', 'ring-1', 'ring-secondary/20');
                    });
                    opt.classList.add('border-secondary', 'bg-secondary/5', 'ring-1', 'ring-secondary/20');
                }
                if (accountInput) accountInput.value = opt.getAttribute('data-account-id');
                if (rincianAkun) rincianAkun.textContent = opt.getAttribute('data-nama') || '';
                updateAccountDetail(opt);
                syncBukti();
            };

            var selectMethod = function(el) {
                if (grid) {
                    grid.querySelectorAll('.pay-method').forEach(function(o) {
                        o.classList.remove('selected');
                    });
                    el.classList.add('selected');
                }
                if (input) input.value = el.getAttribute('data-id');
                if (rincian) rincian.textContent = el.getAttribute('data-nama') || '—';
                if (hint) hint.textContent = 'Metode terpilih: ' + (el.getAttribute('data-nama') || '');
                var kode = el.getAttribute('data-kode');
                if (kode) {
                    showPanel(kode);
                    var autoAcc = el.getAttribute('data-account-id');
                    if (autoAcc && accountInput) accountInput.value = autoAcc;
                    syncBukti();
                }
            };

            if (grid && input) {
                grid.querySelectorAll('.pay-method').forEach(function(el) {
                    el.addEventListener('click', function() { selectMethod(el); });
                });
            }

            document.querySelectorAll('.account-opt').forEach(function(opt) {
                opt.addEventListener('click', function() { selectAccount(opt); });
            });

            (function() {
                var sel = grid ? grid.querySelector('.pay-method.selected') : null;
                if (!sel) return;
                var kode = sel.getAttribute('data-kode');
                if (kode) {
                    showPanel(kode);
                    if (kode === 'qris') {
                        var autoAcc = sel.getAttribute('data-account-id');
                        if (autoAcc && accountInput && !accountInput.value) accountInput.value = autoAcc;
                    } else if (kode === 'ewallet' || kode === 'bank_transfer') {
                        var gridEl = document.getElementById('grid-' + kode);
                        var selEw = gridEl ? gridEl.querySelector('.account-opt-ew.selected') : null;
                        if (selEw) {
                            var parentWrap = selEw.closest('.ew-card-wrap');
                            if (parentWrap) parentWrap.classList.add('ew-active');
                            if (gridEl) gridEl.classList.add('ew-expanded');
                            if (accountInput && !accountInput.value) accountInput.value = selEw.getAttribute('data-account-id');
                        }
                    } else {
                        var selOpt = document.querySelector('#grid-' + kode + ' .account-opt.ring-1');
                        if (selOpt) updateAccountDetail(selOpt);
                    }
                    syncBukti();
                }
            })();

            var fileInput = document.getElementById('input-bukti');
            var dropzone = document.getElementById('dropzone');
            var previewImg = document.getElementById('preview-bukti');
            var dropzoneIcon = document.getElementById('dropzone-icon');
            var uploadHint = document.getElementById('upload-hint');
            var btnUnggah = document.getElementById('btn-unggah');
            var btnPair = document.getElementById('btn-pair');
            var btnGanti = document.getElementById('btn-ganti');
var btnActions = document.getElementById('btn-actions');
            var hasPrevProof = btnActions ? btnActions.getAttribute('data-prev-proof') === 'true' : false;

            var showSingle = function() {
                if (btnUnggah) btnUnggah.classList.remove('hidden');
                if (btnPair) btnPair.classList.add('hidden');
            };

            var showPair = function() {
                if (btnUnggah) btnUnggah.classList.add('hidden');
                if (btnPair) btnPair.classList.remove('hidden');
            };

            if (btnGanti) {
                btnGanti.addEventListener('click', function() {
                    if (fileInput) fileInput.click();
                });
            }

            if (hasPrevProof) showPair(); else showSingle();

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    var kode = currentKode;
                    if (fileInput.files && fileInput.files[0]) {
                        var f = fileInput.files[0];
                        if (proofs[kode] && proofs[kode].url) URL.revokeObjectURL(proofs[kode].url);
                        proofs[kode] = { file: f, url: URL.createObjectURL(f) };
                        if (dropzone) dropzone.classList.add('border-secondary');
                        if (previewImg) {
                            previewImg.src = proofs[kode].url;
                            previewImg.classList.remove('hidden');
                        }
                        if (dropzoneIcon) dropzoneIcon.classList.add('hidden');
                        if (uploadHint) uploadHint.classList.add('hidden');
                        showPair();
                    } else {
                        applyProofUi(kode);
                    }
                });
                if (dropzone) {
                    dropzone.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        dropzone.classList.add('border-secondary', 'bg-surface-container');
                    });
                    dropzone.addEventListener('dragleave', function() {
                        dropzone.classList.remove('border-secondary', 'bg-surface-container');
                    });
                    dropzone.addEventListener('drop', function(e) {
                        e.preventDefault();
                        dropzone.classList.remove('bg-surface-container');
                        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                            fileInput.files = e.dataTransfer.files;
                            fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                }
            }
            document.querySelectorAll('.btn-gold').forEach(function(b) {
                b.addEventListener('click', function() {
                    b.classList.remove('flashing');
                    void b.offsetWidth;
                    b.classList.add('flashing');
                    setTimeout(function() { b.classList.remove('flashing'); }, 600);
                });
            });

            var form = document.getElementById('form-bayar');
            if (form) {
                form.addEventListener('submit', function(e) {
                    var v = input ? input.value : '';
                    if (!v) {
                        e.preventDefault();
                        alert('Pilih metode pembayaran terlebih dahulu.');
                        if (grid) grid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }
                    var selM = grid ? grid.querySelector('.pay-method.selected') : null;
                    if (selM) {
                        var kodeM = selM.getAttribute('data-kode');
                        if ((kodeM === 'ewallet' || kodeM === 'bank_transfer') && accountInput && !accountInput.value) {
                            e.preventDefault();
                            alert('Pilih akun/tujuan pembayaran terlebih dahulu.');
                        }
                    }
                });
            }
        });
    </script>

</body>

</html>