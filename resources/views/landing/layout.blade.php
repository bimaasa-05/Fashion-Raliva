<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light dark" />
    <title>@yield('title', __('landing.meta.home_title'))</title>
    <meta name="description" content="@yield('meta_description', __('landing.meta.home_description'))" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="icon" href="{{ brand_favicon_url() }}" />

    <script>
        if (localStorage.getItem('raliva-theme') === 'dark') {
            document.documentElement.classList.add('theme-dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        surface: 'rgb(var(--c-surface) / <alpha-value>)',
                        'surface-container-lowest': 'rgb(var(--c-sc-lowest) / <alpha-value>)',
                        'surface-container-low': 'rgb(var(--c-sc-low) / <alpha-value>)',
                        'surface-container': 'rgb(var(--c-sc) / <alpha-value>)',
                        'surface-container-high': 'rgb(var(--c-sc-high) / <alpha-value>)',
                        'on-surface': 'rgb(var(--c-on-surface) / <alpha-value>)',
                        'on-surface-variant': 'rgb(var(--c-on-surface-variant) / <alpha-value>)',
                        'outline-variant': 'rgb(var(--c-outline-variant) / <alpha-value>)',
                        'muted-border': 'rgb(var(--c-border) / <alpha-value>)',
                        'gold-accent': 'rgb(var(--c-accent) / <alpha-value>)',
                        secondary: 'rgb(var(--c-accent) / <alpha-value>)',
                        primary: 'rgb(var(--c-ink) / <alpha-value>)',
                        'deep-onyx': 'rgb(var(--c-ink) / <alpha-value>)',
                        'on-primary': 'rgb(var(--c-on-accent) / <alpha-value>)',
                    },
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        body: ['Manrope', 'sans-serif'],
                    },
                    maxWidth: {
                        '8xl': '90rem',
                    },
                },
            },
        };
    </script>

    <style>
        :root {
            --c-surface: 243 240 234;
            --c-sc-lowest: 255 253 249;
            --c-sc-low: 248 246 242;
            --c-sc: 239 237 237;
            --c-sc-high: 233 232 231;
            --c-on-surface: 27 28 28;
            --c-on-surface-variant: 86 81 76;
            --c-outline-variant: 222 216 207;
            --c-border: 229 225 218;
            --c-accent: 139 30 63;
            --c-accent-strong: 109 20 40;
            --c-ink: 17 17 17;
            --c-on-accent: 255 255 255;
            --c-footer: 23 22 21;
            --c-footer-text: 245 241 235;
            --c-footer-muted: 188 180 171;
            --c-glow: 139 30 63;
        }

        html.theme-dark {
            --c-surface: 22 21 20;
            --c-sc-lowest: 30 29 28;
            --c-sc-low: 34 33 31;
            --c-sc: 42 40 38;
            --c-sc-high: 50 48 46;
            --c-on-surface: 230 228 225;
            --c-on-surface-variant: 185 182 177;
            --c-outline-variant: 58 57 55;
            --c-border: 58 57 55;
            --c-accent: 201 75 95;
            --c-accent-strong: 139 30 63;
            --c-ink: 242 239 236;
            --c-on-accent: 255 255 255;
            --c-footer: 15 14 13;
            --c-footer-text: 245 241 235;
            --c-footer-muted: 166 159 151;
            --c-glow: 201 75 95;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-padding-top: 6rem;
        }

        body {
            margin: 0;
            background: rgb(var(--c-surface));
            color: rgb(var(--c-on-surface));
            font-family: 'Manrope', sans-serif;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        ::selection {
            background: rgb(var(--c-accent) / 0.2);
        }

        a,
        button {
            -webkit-tap-highlight-color: transparent;
        }

        :focus-visible {
            outline: 2px solid rgb(var(--c-accent));
            outline-offset: 3px;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            line-height: 1;
        }

        .skip-link {
            position: fixed;
            top: 0.75rem;
            left: 0.75rem;
            z-index: 100;
            transform: translateY(-180%);
            border-radius: 9999px;
            background: rgb(var(--c-accent));
            color: #fff;
            padding: 0.7rem 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            transition: transform 0.2s ease;
        }

        .skip-link:focus {
            transform: translateY(0);
        }

        .site-header {
            background: rgb(var(--c-surface) / 0.88);
            border-bottom: 1px solid rgb(var(--c-border) / 0.8);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            transition: box-shadow 0.25s ease;
        }

        .site-header.is-scrolled {
            box-shadow: 0 16px 40px -28px rgba(17, 17, 17, 0.45);
        }

        .brand-wordmark {
            font-family: 'Playfair Display', serif;
            letter-spacing: 0.16em;
            line-height: 1;
        }

        .nav-link {
            position: relative;
            color: rgb(var(--c-on-surface-variant));
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            transition: color 0.2s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            right: 0;
            bottom: -0.45rem;
            left: 0;
            height: 1px;
            transform: scaleX(0);
            transform-origin: right;
            background: rgb(var(--c-accent));
            transition: transform 0.25s ease;
        }

        .nav-link:hover,
        .nav-link[aria-current='page'] {
            color: rgb(var(--c-accent));
        }

        .nav-link:hover::after,
        .nav-link[aria-current='page']::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            border-top: 1px solid transparent;
            transition: max-height 0.3s ease, border-color 0.3s ease;
        }

        .mobile-menu.is-open {
            max-height: 44rem;
            border-top-color: rgb(var(--c-border));
        }

        .btn-primary,
        .btn-secondary,
        .btn-light,
        .btn-outline-light {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            border-radius: 9999px;
            padding: 0.9rem 1.35rem;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.11em;
            line-height: 1;
            text-transform: uppercase;
            transition: transform 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .btn-primary {
            background: rgb(var(--c-accent));
            color: #fff;
            border: 1px solid rgb(var(--c-accent));
            box-shadow: 0 12px 24px -16px rgb(var(--c-accent) / 0.8);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            background: rgb(var(--c-accent-strong));
            border-color: rgb(var(--c-accent-strong));
        }

        .btn-secondary {
            background: transparent;
            color: rgb(var(--c-on-surface));
            border: 1px solid rgb(var(--c-border));
        }

        .btn-secondary:hover {
            transform: translateY(-1px);
            border-color: rgb(var(--c-accent));
            color: rgb(var(--c-accent));
        }

        .btn-light {
            background: #fff;
            color: rgb(var(--c-accent-strong));
            border: 1px solid #fff;
        }

        .btn-light:hover {
            transform: translateY(-1px);
            background: #f7f1e9;
        }

        .btn-outline-light {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }

        .btn-outline-light:hover {
            transform: translateY(-1px);
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.08);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            color: rgb(var(--c-accent));
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: '';
            width: 1.9rem;
            height: 1px;
            background: currentColor;
            opacity: 0.7;
        }

        .hero-orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(1px);
            pointer-events: none;
        }

        .hero-grid {
            background-image:
                linear-gradient(rgb(var(--c-outline-variant) / 0.26) 1px, transparent 1px),
                linear-gradient(90deg, rgb(var(--c-outline-variant) / 0.26) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), transparent 85%);
            -webkit-mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), transparent 85%);
        }

        .dashboard-shell {
            position: relative;
            isolation: isolate;
        }

        .dashboard-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgb(var(--c-border));
            border-radius: 1.5rem;
            background: rgb(var(--c-sc-lowest));
            box-shadow: 0 30px 80px -38px rgba(17, 17, 17, 0.45);
        }

        html.theme-dark .dashboard-card {
            box-shadow: 0 30px 80px -38px rgba(0, 0, 0, 0.8);
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgb(var(--c-accent) / 0.06), transparent 42%);
            pointer-events: none;
        }

        .dashboard-card > * {
            position: relative;
            z-index: 1;
        }

        .stat-card {
            border: 1px solid rgb(var(--c-border));
            border-radius: 1rem;
            background: rgb(var(--c-sc-low));
            padding: 0.85rem;
        }

        .chart-bars {
            display: flex;
            align-items: flex-end;
            gap: 0.45rem;
            height: 5.8rem;
            border-bottom: 1px solid rgb(var(--c-border));
            padding: 0 0.2rem;
        }

        .chart-bar {
            flex: 1;
            min-width: 0.35rem;
            border-radius: 0.35rem 0.35rem 0 0;
            background: linear-gradient(to top, rgb(var(--c-accent) / 0.3), rgb(var(--c-accent)));
            opacity: 0.85;
        }

        .chart-bar:nth-child(3n) {
            opacity: 1;
        }

        .order-row {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            border-top: 1px solid rgb(var(--c-border));
            padding: 0.65rem 0;
        }

        .order-row:first-child {
            border-top: 0;
        }

        .order-thumb {
            width: 2.1rem;
            height: 2.1rem;
            flex: 0 0 auto;
            border-radius: 0.65rem;
            object-fit: cover;
            border: 1px solid rgb(var(--c-border));
        }

        .floating-card {
            position: absolute;
            right: 1rem;
            bottom: -1.4rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            max-width: 15rem;
            border: 1px solid rgb(var(--c-border));
            border-radius: 1rem;
            background: rgb(var(--c-sc-lowest) / 0.96);
            padding: 0.8rem 0.9rem;
            box-shadow: 0 18px 44px -26px rgba(17, 17, 17, 0.5);
            backdrop-filter: blur(12px);
        }

        html.theme-dark .floating-card {
            box-shadow: 0 18px 44px -26px rgba(0, 0, 0, 0.8);
        }

        .feature-card {
            border: 1px solid rgb(var(--c-border));
            border-radius: 1.15rem;
            background: rgb(var(--c-sc-lowest));
            padding: 1.35rem;
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .feature-card:hover {
            transform: translateY(-3px);
            border-color: rgb(var(--c-accent) / 0.4);
            box-shadow: 0 22px 44px -32px rgba(17, 17, 17, 0.45);
        }

        html.theme-dark .feature-card:hover {
            box-shadow: 0 22px 44px -32px rgba(0, 0, 0, 0.8);
        }

        .icon-tile {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 0.85rem;
            border: 1px solid rgb(var(--c-accent) / 0.22);
            background: rgb(var(--c-accent) / 0.1);
            color: rgb(var(--c-accent));
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            background: rgb(var(--c-accent) / 0.1);
            color: rgb(var(--c-accent));
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.08em;
        }

        .image-frame {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgb(var(--c-border));
            background: rgb(var(--c-sc));
        }

        .image-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .image-frame:hover img {
            transform: scale(1.04);
        }

        .site-footer {
            background: rgb(var(--c-footer));
            color: rgb(var(--c-footer-text));
        }

        .site-footer a {
            color: rgb(var(--c-footer-muted));
            transition: color 0.2s ease;
        }

        .site-footer a:hover {
            color: #fff;
        }

        .footer-contact-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .faq-item {
            border-bottom: 1px solid rgb(var(--c-border));
        }

        .faq-item summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            cursor: pointer;
            list-style: none;
            padding: 1.15rem 0;
            color: rgb(var(--c-on-surface));
            font-size: 0.95rem;
            font-weight: 700;
        }

        .faq-item summary::-webkit-details-marker {
            display: none;
        }

        .faq-item summary .faq-plus {
            flex: 0 0 auto;
            color: rgb(var(--c-accent));
            transition: transform 0.2s ease;
        }

        .faq-item[open] summary .faq-plus {
            transform: rotate(45deg);
        }

        .faq-item p {
            margin: 0;
            padding: 0 0 1.2rem;
            color: rgb(var(--c-on-surface-variant));
            font-size: 0.9rem;
            line-height: 1.75;
        }

        .reveal {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1), transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
            transition-delay: var(--reveal-delay, 0ms);
        }

        .reveal.is-visible {
            opacity: 1;
            transform: none;
        }

        .theme-icon-dark {
            display: none;
        }

        html.theme-dark .theme-icon-dark {
            display: inline-flex;
        }

        html.theme-dark .theme-icon-light {
            display: none;
        }

        @media (max-width: 639px) {
            .floating-card {
                right: 0.7rem;
                bottom: 0.7rem;
                max-width: 12.5rem;
                padding: 0.65rem 0.7rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }

            .reveal {
                opacity: 1;
                transform: none;
            }
        }
    </style>

    @stack('head')
</head>
<body class="font-body">
    <a class="skip-link" href="#main">{{ __('landing.common.skip_to_content') }}</a>

    @include('landing._partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('landing._partials.footer')

    <script>
        (function () {
            const root = document.documentElement;
            const themeButtons = document.querySelectorAll('[data-theme-toggle]');

            function syncThemeButtons() {
                const isDark = root.classList.contains('theme-dark');
                themeButtons.forEach(function (button) {
                    button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
                });
            }

            themeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const isDark = root.classList.toggle('theme-dark');
                    localStorage.setItem('raliva-theme', isDark ? 'dark' : 'light');
                    syncThemeButtons();
                });
            });

            syncThemeButtons();

            const menu = document.getElementById('mobile-menu');
            const menuToggle = document.querySelector('[data-mobile-menu-toggle]');

            if (menu && menuToggle) {
                menuToggle.addEventListener('click', function () {
                    const isOpen = menu.classList.toggle('is-open');
                    menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                menu.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        menu.classList.remove('is-open');
                        menuToggle.setAttribute('aria-expanded', 'false');
                    });
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && menu.classList.contains('is-open')) {
                        menu.classList.remove('is-open');
                        menuToggle.setAttribute('aria-expanded', 'false');
                        menuToggle.focus();
                    }
                });
            }

            const header = document.getElementById('site-header');
            function syncHeader() {
                if (!header) return;
                header.classList.toggle('is-scrolled', window.scrollY > 8);
            }
            syncHeader();
            window.addEventListener('scroll', syncHeader, { passive: true });

            const revealItems = document.querySelectorAll('.reveal');
            if (!('IntersectionObserver' in window)) {
                revealItems.forEach(function (item) {
                    item.classList.add('is-visible');
                });
            } else {
                const observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.12 });

                revealItems.forEach(function (item) {
                    observer.observe(item);
                });
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
