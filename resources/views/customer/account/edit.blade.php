<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - Edit Profile</title>
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
        --chrome-accent: #795905;
    }
    html.theme-dark {
        --chrome-bg: #1c1b1b;
        --chrome-bg-soft: rgba(28,27,27,.9);
        --chrome-text: #ffffff;
        --chrome-text-dim: rgba(255,255,255,.6);
        --chrome-text-faint: rgba(255,255,255,.5);
        --chrome-border: rgba(255,255,255,.1);
        --chrome-hover: rgba(255,255,255,.1);
        --chrome-accent: #ebc168;
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
    html.theme-dark .text-secondary { color: #ebc168 !important; }
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
    html.theme-dark .hover\:text-secondary:hover { color: #ebc168 !important; }
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
  </head>
<body class="bg-surface text-on-surface antialiased font-body-lg">
<!-- TopAppBar -->
<header class="fixed top-0 w-full z-50 bg-[var(--chrome-bg)] text-[var(--chrome-text)] flex justify-between items-center px-container-margin h-16 border-b border-[var(--chrome-border)]">
<a href="{{ route('customer.account') }}" aria-label="Back" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase flex-1 text-center truncate max-w-[240px]">Edit Profile</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-40 px-container-margin max-w-2xl mx-auto w-full">
<form>
<!-- Profile Photo Section -->
<section class="py-xl flex flex-col items-center border-b border-outline-variant">
<div class="relative mb-sm">
<div class="w-24 h-24 rounded-full overflow-hidden border border-outline-variant">
<img alt="Profile Picture" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC2mFOUgXwyToT210lLVyaVSmF1VogUfk85zb-0hplBwDJrLXcscOUZ6HC-QneJkQeiXtusGTnccpRNL_Qfwg6Iv1eVkAyIGJww1Oeb_iYCbIyOeCVeUW2b1Sm0yZ1Ilyxant3LPd15_T_3d5wXZ6WuDg04U46PEh96KMwKZLe0bO4ULe1L4wvC1WuBzsmGdp1FVC5JBPcCooQlUdVdE7hMrw6wp72LxJBvu2PdD4yA1caYdtRO7ss"/>
</div>
<span role="button" tabindex="0" aria-label="Change profile photo" class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center cursor-pointer hover:opacity-90 transition-opacity">
<span class="material-symbols-outlined text-[18px]">photo_camera</span>
</span>
</div>
<span role="button" tabindex="0" class="font-label-caps text-label-caps text-secondary uppercase tracking-widest underline underline-offset-4 cursor-pointer hover:opacity-80 transition-opacity">Change Photo</span>
</section>
<!-- Personal Information -->
<section class="py-lg">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-md">Personal Information</h2>
<div class="flex flex-col gap-md">
<!-- Full Name -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="full-name">Full Name</label>
<input class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="full-name" type="text" value="Jane Doe"/>
</div>
<!-- Email -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="email">Email Address</label>
<input class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="email" type="email" value="jane.doe@example.com"/>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">Used for order updates and receipts.</p>
</div>
<!-- Phone Number -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="phone">Phone Number</label>
<input class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="phone" type="tel" value="+62 812 3456 7890"/>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">The courier will contact this number upon delivery.</p>
</div>
</div>
</section>
<!-- Additional Information -->
<section class="py-lg border-t border-outline-variant">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-md">Additional Information</h2>
<!-- Gender -->
<div class="mb-md">
<label class="font-label-sm text-label-sm text-on-surface block mb-xs">Gender</label>
<div class="grid grid-cols-2 gap-gutter">
<label class="flex items-center justify-center gap-xs py-sm border-2 border-primary rounded-DEFAULT bg-surface-container-low cursor-pointer">
<input checked="" class="sr-only" name="gender" type="radio" value="female"/>
<span class="material-symbols-outlined text-[20px]" data-icon="female">female</span>
<span class="font-body-sm text-body-sm font-semibold">Female</span>
</label>
<label class="flex items-center justify-center gap-xs py-sm border border-outline-variant rounded-DEFAULT cursor-pointer hover:border-primary transition-colors">
<input class="sr-only" name="gender" type="radio" value="male"/>
<span class="material-symbols-outlined text-[20px]" data-icon="male">male</span>
<span class="font-body-sm text-body-sm">Male</span>
</label>
</div>
</div>
<!-- Date of Birth -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="dob">Date of Birth</label>
<input class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="dob" type="date" value="1998-05-17"/>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">Get a special surprise on your birthday.</p>
</div>
</section>
<!-- Security -->
<section class="py-lg border-t border-outline-variant">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-md">Security</h2>
<a class="flex items-center justify-between py-sm group" href="{{ route('customer.account.password') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="lock">lock</span>
<span class="font-body-lg text-body-lg text-on-surface">Change Password</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
</section>
</form>
</main>
<!-- Fixed Bottom Action Bar -->
<div class="fixed bottom-0 left-0 w-full bg-surface border-t border-outline-variant px-container-margin py-md z-50 max-w-2xl mx-auto">
<a href="{{ route('customer.account') }}" class="w-full bg-primary text-on-primary font-label-caps text-label-caps h-14 flex items-center justify-center hover:opacity-90 transition-opacity uppercase tracking-widest">
            Save Changes
        </a>
</div>
</body></html>
