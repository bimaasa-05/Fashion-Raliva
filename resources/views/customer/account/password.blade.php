<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - Change Password</title>
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
  </head>
<body class="bg-surface text-on-surface antialiased font-body-lg">
<!-- TopAppBar -->
<header class="fixed top-0 w-full z-50 bg-primary-container text-on-primary flex justify-between items-center px-container-margin h-16 border-b border-white/10">
<a href="{{ route('customer.account.edit') }}" aria-label="Back" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-secondary-fixed-dim uppercase flex-1 text-center truncate max-w-[240px]">Change Password</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-40 px-container-margin max-w-2xl mx-auto w-full">
<form>
<section class="py-lg">
<div class="flex flex-col gap-md">
<!-- Current Password -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="current-password">Current Password</label>
<div class="relative">
<input autocomplete="current-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="current-password" placeholder="Enter your current password" type="password"/>
<span aria-label="Show password" class="absolute right-md top-1/2 -translate-y-1/2 cursor-pointer text-on-surface-variant hover:text-on-surface transition-colors flex" role="button" tabindex="0">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</span>
</div>
</div>
<!-- New Password -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="new-password">New Password</label>
<div class="relative">
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="new-password" placeholder="Enter a new password" type="password"/>
<span aria-label="Show password" class="absolute right-md top-1/2 -translate-y-1/2 cursor-pointer text-on-surface-variant hover:text-on-surface transition-colors flex" role="button" tabindex="0">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</span>
</div>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">Choose a strong password you don't use elsewhere.</p>
</div>
<!-- Confirm New Password -->
<div>
<label class="font-label-sm text-label-sm text-on-surface block mb-xs" for="confirm-password">Confirm New Password</label>
<div class="relative">
<input autocomplete="new-password" class="w-full bg-surface border border-outline-variant rounded-DEFAULT pl-md pr-xl py-sm font-body-lg text-body-lg text-on-surface focus:outline-none focus:border-primary transition-colors" id="confirm-password" placeholder="Re-enter your new password" type="password"/>
<span aria-label="Show password" class="absolute right-md top-1/2 -translate-y-1/2 cursor-pointer text-on-surface-variant hover:text-on-surface transition-colors flex" role="button" tabindex="0">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</span>
</div>
</div>
</div>
</section>
<!-- Password Requirements -->
<section class="pb-lg">
<div class="bg-surface-container-low border border-outline-variant rounded-DEFAULT p-md">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-sm">Password Requirements</h2>
<ul class="flex flex-col gap-xs">
<li class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]" data-icon="check_circle" style="color: #795905;">check_circle</span>
<span class="font-body-sm text-body-sm text-on-surface">At least 8 characters long</span>
</li>
<li class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]" data-icon="check_circle" style="color: #795905;">check_circle</span>
<span class="font-body-sm text-body-sm text-on-surface">Contains at least 1 uppercase letter</span>
</li>
<li class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px]" data-icon="check_circle" style="color: #795905;">check_circle</span>
<span class="font-body-sm text-body-sm text-on-surface">Contains at least 1 number</span>
</li>
<li class="flex items-center gap-sm">
<span class="material-symbols-outlined text-[18px] text-outline-variant" data-icon="radio_button_unchecked">radio_button_unchecked</span>
<span class="font-body-sm text-body-sm text-on-surface-variant">Recommended: include 1 special character (!@#$%)</span>
</li>
</ul>
</div>
</section>
<!-- Security Tip -->
<section class="pb-lg">
<div class="flex items-start gap-sm">
<span class="material-symbols-outlined text-secondary text-[20px] mt-1" data-icon="shield">shield</span>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
After changing your password, you will stay signed in on this device but will need to sign in again on other devices.
            </p>
</div>
</section>
</form>
</main>
<!-- Fixed Bottom Action Bar -->
<div class="fixed bottom-0 left-0 w-full bg-surface border-t border-outline-variant px-container-margin py-md z-50 max-w-2xl mx-auto">
<a href="{{ route('customer.account') }}" class="w-full bg-primary text-on-primary font-label-caps text-label-caps h-14 flex items-center justify-center hover:opacity-90 transition-opacity uppercase tracking-widest">
            Update Password
        </a>
</div>
</body></html>
