<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - Reset Password</title>
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
<body class="bg-surface text-on-surface antialiased font-body-lg min-h-screen flex flex-col">
<main class="flex-grow flex flex-col justify-center w-full max-w-md mx-auto px-container-margin py-xl">
<!-- Floating Chips: Home + Theme -->
<div class="fixed top-sm right-sm z-50 flex items-center gap-xs">
<a aria-label="Back to home" title="Kembali ke Home" href="{{ route('customer.home') }}" class="w-10 h-10 rounded-full border border-outline bg-surface-container-lowest shadow-sm hover:border-primary hover:text-secondary transition-colors flex items-center justify-center">
<span class="material-symbols-outlined text-[20px]">home</span>
</a>
<button aria-label="Toggle theme" title="Ganti tema" type="button" onclick="toggleTheme()" class="w-10 h-10 rounded-full border border-outline bg-surface-container-lowest shadow-sm hover:border-primary hover:text-secondary transition-colors flex items-center justify-center">
<span class="material-symbols-outlined text-[20px]" id="auth-theme-icon">dark_mode</span>
</button>
</div>
<!-- Form Section -->
<div id="reset-section">
<!-- Logo -->
<div class="text-center mb-xl">
<h1 class="font-display-lg text-headline-lg tracking-widest text-on-surface">RALIVA</h1>
<p class="font-label-sm text-label-sm text-on-surface-variant tracking-wide mt-1">The Art of Everyday Dressing</p>
</div>
<!-- Heading -->
<div class="mb-lg">
<h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mb-xs">Reset Password</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant">Create a new password for your account.</p>
</div>
<form id="reset-form" novalidate>
<!-- New Password -->
<div class="mb-md">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="password">New Password</label>
<div class="relative">
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="password" placeholder="Minimum 8 characters" type="password"/>
<button aria-label="Show password" class="absolute right-md top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors flex" id="password-toggle" type="button">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="password-error">Password must be at least 8 characters.</p>
</div>
<!-- Confirm New Password -->
<div class="mb-md">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="confirm-password">Confirm New Password</label>
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="confirm-password" placeholder="Re-enter your new password" type="password"/>
<p class="hidden font-label-sm text-label-sm text-error mt-xs" id="confirm-error">Password does not match.</p>
</div>
<!-- Submit -->
<button class="w-full h-14 btn-gold font-label-caps text-label-caps uppercase tracking-widest hover:opacity-90 transition-opacity flex items-center justify-center gap-sm disabled:opacity-60 disabled:pointer-events-none" id="reset-btn" type="submit">
<span id="reset-btn-text">RESET PASSWORD</span>
<span class="material-symbols-outlined text-[20px] animate-spin hidden" id="reset-spinner">progress_activity</span>
</button>
</form>
</div>
<!-- Success State -->
<div class="hidden text-center py-xl" id="reset-success">
<span class="material-symbols-outlined text-secondary text-[64px]">lock_reset</span>
<h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface mt-md mb-sm">Password Updated</h2>
<p class="font-body-lg text-body-lg text-on-surface-variant mb-xl max-w-xs mx-auto">Your password has been updated successfully.</p>
<a class="w-full h-14 btn-gold font-label-caps text-label-caps uppercase tracking-widest hover:opacity-90 transition-opacity flex items-center justify-center inline-flex" href="{{ route('customer.login') }}">
            GO TO LOGIN
        </a>
</div>
</main>
<script>
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

        document.getElementById('reset-form').addEventListener('submit', function (e) {
            e.preventDefault();
            var password = document.getElementById('password').value;
            var confirm = document.getElementById('confirm-password').value;
            var valid = true;

            setError('password-error', false);
            setError('confirm-error', false);

            if (password.length < 8) { setError('password-error', true); valid = false; }
            if (password !== confirm) { setError('confirm-error', true); valid = false; }
            if (!valid) return;

            var btn = document.getElementById('reset-btn');
            btn.disabled = true;
            document.getElementById('reset-spinner').classList.remove('hidden');

            setTimeout(function () {
                document.getElementById('reset-section').classList.add('hidden');
                document.getElementById('reset-success').classList.remove('hidden');
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
