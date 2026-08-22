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
    html.theme-dark .text-secondary { color: #ebc168 !important; }
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
    :root { --gold-inner: rgba(121, 89, 5, .30); }
    html.theme-dark { --gold-inner: rgba(235, 193, 104, .25); }
    /* ============ QUILTED DIAMOND PATTERN ============ */
    .auth-monogram {
        background-image: var(--monogram);
        background-size: 80px 80px;
        background-repeat: repeat;
    }
    :root {
        --monogram: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M0 0L80 80M80 0L0 80' stroke='rgba(121,89,5,0.14)' stroke-width='1' fill='none'/%3E%3Ccircle cx='40' cy='40' r='2' fill='rgba(121,89,5,0.20)'/%3E%3C/svg%3E");
    }
    html.theme-dark {
        --monogram: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M0 0L80 80M80 0L0 80' stroke='rgba(235,193,104,0.10)' stroke-width='1' fill='none'/%3E%3Ccircle cx='40' cy='40' r='2' fill='rgba(235,193,104,0.16)'/%3E%3C/svg%3E");
    }
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
    :root           { --btn-gold-bg: #e8c25a; --btn-gold-text: #261a00; }
    html.theme-dark { --btn-gold-bg: #d9ab4f; --btn-gold-text: #261a00; }
</style>
</head>
<body class="bg-surface text-on-surface antialiased font-body-lg min-h-screen flex flex-col lg:flex-row">
<!-- Editorial Panel -->
<aside class="relative overflow-hidden shrink-0 h-44 lg:h-auto lg:w-[44%] flex">
<img alt="RALIVA Editorial" class="absolute inset-0 w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDotrquQ9ru5aXlWl5XbgLhEMJq3WBfo5DDEAS3Z-F5LnAIv27Q3259la3QLZghjnF5R8udNJqY0Toq6SHw5JvN3PqANThsUOvwujXixkrq5zZBH5OW_D3QTRD3qObufW5Uz2-ahDe36xdtDHuA8SK2Ldhp4wpMReozYAnqkNj5ZG3A37LwDOS6aXDnCEg_MNh_j2C1VKegB7PNMCwMV-jwzYAwrhuqG1UCGjQoSl3A0QRKO-gFHlQ"/>
<div class="absolute inset-0 bg-black/50"></div>
<div class="relative z-10 flex flex-col items-center justify-center text-center h-full w-full p-lg lg:items-start lg:justify-between lg:text-left lg:p-xl">
<div>
<span class="font-display-lg text-headline-lg tracking-widest text-white">RALIVA</span>
<p class="font-label-sm text-label-sm text-white/80 tracking-wide mt-1 lg:hidden">{{ __('The Art of Everyday Dressing') }}</p>
<div class="w-10 h-px bg-secondary-fixed-dim mt-sm lg:hidden"></div>
</div>
<div class="hidden lg:block">
<p class="font-label-caps text-label-caps uppercase tracking-widest text-secondary-fixed-dim mb-sm">{{ __('Join Raliva') }}</p>
<p class="font-headline-lg-mobile text-headline-lg-mobile text-white max-w-sm">{{ __('Discover your style, curated for you.') }}</p>
<div class="w-10 h-px bg-secondary-fixed-dim mt-md"></div>
</div>
</div>
</aside>
<!-- Form Side -->
<main class="auth-monogram flex-grow flex flex-col relative overflow-hidden bg-surface-container-low border-outline-variant lg:border-l">
<!-- Floating Chips: Home + Theme -->
<div class="fixed top-sm right-sm z-50 flex items-center gap-xs">
<a aria-label="{{ __('Back to home') }}" title="Kembali ke Home" href="{{ route('customer.home') }}" class="w-10 h-10 rounded-full border border-outline bg-surface-container-lowest shadow-sm hover:border-primary hover:text-secondary transition-colors flex items-center justify-center">
<span class="material-symbols-outlined text-[20px]">home</span>
</a>
<button aria-label="Toggle theme" title="Ganti tema" type="button" onclick="toggleTheme()" class="w-10 h-10 rounded-full border border-outline bg-surface-container-lowest shadow-sm hover:border-primary hover:text-secondary transition-colors flex items-center justify-center">
<span class="material-symbols-outlined text-[20px]" id="auth-theme-icon">dark_mode</span>
</button>
</div>
<div class="flex-grow flex flex-col justify-center w-full max-w-md mx-auto py-xl relative z-10">
<div id="register-section" class="border-2 border-secondary rounded-lg p-lg lg:p-xl frame-gold">
<!-- Heading -->
<div class="mb-lg">
<p class="font-label-caps text-label-caps uppercase tracking-widest text-secondary mb-xs">{{ __('Raliva Account') }}</p>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mb-xs">{{ __('Create Your Account') }}</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">{{ __('Join RALIVA and discover your style.') }}</p>
</div>
<form id="register-form" novalidate>
<!-- Full Name -->
<div class="mb-md">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="full-name">{{ __('Full Name') }}</label>
<input autocomplete="name" class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary transition-colors" id="full-name" placeholder="{{ __('Your full name') }}" type="text"/>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="name-error">{{ __('Full name is required.') }}</p>
</div>
<!-- Email -->
<div class="mb-md">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="email">{{ __('Email') }}</label>
<input autocomplete="email" class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary transition-colors" id="email" placeholder="you@example.com" type="email"/>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="email-error">{{ __('Invalid email address.') }}</p>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="email-taken-error">{{ __('Email is already registered.') }}</p>
</div>
<!-- Password -->
<div class="mb-md">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="password">{{ __('Password') }}</label>
<div class="relative">
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="password" placeholder="{{ __('Minimum 8 characters') }}" type="password"/>
<button aria-label="{{ __('Show password') }}" class="absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors flex" id="password-toggle" type="button">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="password-error">{{ __('Password must be at least 8 characters.') }}</p>
</div>
<!-- Confirm Password -->
<div class="mb-md">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="confirm-password">{{ __('Confirm Password') }}</label>
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="confirm-password" placeholder="{{ __('Re-enter your password') }}" type="password"/>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="confirm-error">{{ __('Password does not match.') }}</p>
</div>
<!-- Terms Checkbox -->
<label class="flex items-start gap-sm cursor-pointer mb-md">
<input class="mt-1 w-4 h-4 shrink-0" id="terms" type="checkbox"/>
<span class="font-body-sm text-body-sm text-on-surface-variant">
            {{ __("I agree to the") }} <span class="text-secondary underline underline-offset-4">{{ __('Terms &amp; Privacy Policy') }}</span>
        </span>
</label>
<p class="hidden font-label-sm text-label-sm text-error -mt-sm mb-md" id="terms-error">{{ __('Please agree to the Terms &amp; Privacy Policy.') }}</p>
<!-- Submit -->
<button class="w-full h-14 btn-gold font-label-caps text-label-caps uppercase tracking-widest hover:opacity-90 transition-opacity flex items-center justify-center gap-sm disabled:opacity-60 disabled:pointer-events-none" id="register-btn" type="submit">
<span id="register-btn-text">{{ __('REGISTER') }}</span>
<span class="material-symbols-outlined text-[20px] animate-spin hidden" id="register-spinner">progress_activity</span>
</button>
</form>
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
        function applyAuthThemeIcon() {
            var icon = document.getElementById('auth-theme-icon');
            if (!icon) return;
            var dark = document.documentElement.classList.contains('theme-dark');
            icon.textContent = dark ? 'light_mode' : 'dark_mode';
            icon.setAttribute('data-icon', dark ? 'light_mode' : 'dark_mode');
        }
        function toggleTheme() {
            var dark = document.documentElement.classList.toggle('theme-dark');
            localStorage.setItem('raliva-theme', dark ? 'dark' : 'light');
            applyAuthThemeIcon();
        }
        applyAuthThemeIcon();
    </script>
</body></html>
