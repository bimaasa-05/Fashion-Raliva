<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - Account</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] md:pb-0">
<!-- TopAppBar -->
<header class="flex justify-between items-center w-full px-container-margin h-16 bg-primary-container text-on-primary border-b border-white/10 flat no shadows docked full-width top-0 z-40 sticky">
<button aria-label="Menu" class="hover:opacity-80 transition-opacity flex items-center justify-center p-2 -ml-2">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<h1 class="font-display-lg text-headline-md tracking-widest text-secondary-fixed-dim">RALIVA</h1>
<button aria-label="Search" class="hover:opacity-80 transition-opacity flex items-center justify-center p-2 -mr-2">
<span class="material-symbols-outlined" data-icon="search">search</span>
</button>
</header>
<!-- Main Content Canvas -->
<main class="flex-grow w-full max-w-[600px] mx-auto px-container-margin py-md">
<!-- Page Title -->
<div class="mb-lg text-center md:text-left">
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">ACCOUNT</h2>
</div>
<!-- Profile Header -->
<section class="flex flex-col items-center md:flex-row md:items-start gap-md mb-xl">
<div class="w-24 h-24 rounded-full overflow-hidden border border-outline-variant flex-shrink-0">
<img alt="Profile Picture" class="w-full h-full object-cover" data-alt="A sophisticated close-up portrait of a stylish woman. She is well-lit in a modern, airy space, embodying high-end fashion editorial minimalism. The background is a soft, out-of-focus light ivory. The overall mood is confident and elegant, matching a luxury retail brand aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC2mFOUgXwyToT210lLVyaVSmF1VogUfk85zb-0hplBwDJrLXcscOUZ6HC-QneJkQeiXtusGTnccpRNL_Qfwg6Iv1eVkAyIGJww1Oeb_iYCbIyOeCVeUW2b1Sm0yZ1Ilyxant3LPd15_T_3d5wXZ6WuDg04U46PEh96KMwKZLe0bO4ULe1L4wvC1WuBzsmGdp1FVC5JBPcCooQlUdVdE7hMrw6wp72LxJBvu2PdD4yA1caYdtRO7ss"/>
</div>
<div class="flex flex-col items-center md:items-start justify-center flex-grow">
<h3 class="font-title-md text-title-md text-on-surface mb-1">Jane Doe</h3>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-4">jane.doe@example.com</p>
<a class="px-6 py-2 border border-primary text-primary font-label-caps text-label-caps uppercase hover:bg-surface-container-low transition-colors duration-200 inline-block" href="{{ route('customer.account.edit') }}">
                    Edit Profile
                </a>
</div>
</section>
<!-- Menu List -->
<nav class="flex flex-col border-t border-outline-variant">
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.order-tracking') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="local_mall">local_mall</span>
<span class="font-body-lg text-body-lg text-on-surface">My Orders</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.wishlist') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="favorite_border">favorite_border</span>
<span class="font-body-lg text-body-lg text-on-surface">Wishlist</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.address') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="location_on">location_on</span>
<span class="font-body-lg text-body-lg text-on-surface">Addresses</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="#">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="credit_card">credit_card</span>
<span class="font-body-lg text-body-lg text-on-surface">Payment Methods</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.reviews') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="star_border">star_border</span>
<span class="font-body-lg text-body-lg text-on-surface">My Reviews</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.notifications') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="notifications_none">notifications_none</span>
<span class="font-body-lg text-body-lg text-on-surface">Notifications</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.help') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="help_outline">help_outline</span>
<span class="font-body-lg text-body-lg text-on-surface">Help Center</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group" href="{{ route('customer.settings') }}">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors" data-icon="settings">settings</span>
<span class="font-body-lg text-body-lg text-on-surface">Settings</span>
</div>
<span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors" data-icon="chevron_right">chevron_right</span>
</a>
<a class="flex items-center justify-between py-sm border-b border-outline-variant hover:bg-surface-container-low transition-colors group mt-lg" href="#">
<div class="flex items-center gap-sm">
<span class="material-symbols-outlined text-error" data-icon="logout">logout</span>
<span class="font-body-lg text-body-lg text-error">Logout</span>
</div>
</a>
</nav>
</main>
<!-- BottomNavBar (Mobile Only) -->
<nav class="md:hidden flex justify-around items-center w-full h-[72px] bg-primary-container text-on-primary px-xs pb-safe fixed bottom-0 z-50 border-t border-white/10 shadow-sm">
<a class="flex flex-col items-center justify-center text-on-primary/60 hover:text-secondary-fixed-dim transition-colors w-16 h-full" href="{{ route('customer.home') }}">
<span class="material-symbols-outlined mb-1" data-icon="home">home</span>
<span class="font-label-sm text-label-sm">Home</span>
</a>
<a class="flex flex-col items-center justify-center text-on-primary/60 hover:text-secondary-fixed-dim transition-colors w-16 h-full" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined mb-1" data-icon="shopping_bag">shopping_bag</span>
<span class="font-label-sm text-label-sm">Shop</span>
</a>
<a class="flex flex-col items-center justify-center text-on-primary/60 hover:text-secondary-fixed-dim transition-colors w-16 h-full" href="{{ route('customer.wishlist') }}">
<span class="material-symbols-outlined mb-1" data-icon="favorite">favorite</span>
<span class="font-label-sm text-label-sm">Wishlist</span>
</a>
<a class="flex flex-col items-center justify-center text-on-primary/60 hover:text-secondary-fixed-dim transition-colors w-16 h-full" href="{{ route('customer.chart') }}">
<span class="material-symbols-outlined mb-1" data-icon="shopping_cart">shopping_cart</span>
<span class="font-label-sm text-label-sm">Cart</span>
</a>
<a aria-current="page" class="flex flex-col items-center justify-center text-secondary-fixed-dim hover:text-secondary transition-colors w-16 h-full active:scale-95 transition-transform" href="{{ route('customer.account') }}">
<span class="material-symbols-outlined mb-1" data-icon="person" style="font-variation-settings: 'FILL' 1;">person</span>
<span class="font-label-sm text-label-sm">Account</span>
</a>
</nav>
</body></html>