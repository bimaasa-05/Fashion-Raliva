<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
<title>RALIVA - My Addresses</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;family=Playfair+Display:wght@500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
    </style>
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
                      "display-lg": [
                              "Playfair Display"
                      ],
                      "label-caps": [
                              "Manrope"
                      ],
                      "headline-lg-mobile": [
                              "Playfair Display"
                      ],
                      "headline-lg": [
                              "Playfair Display"
                      ],
                      "title-md": [
                              "Manrope"
                      ],
                      "headline-md": [
                              "Playfair Display"
                      ],
                      "body-lg": [
                              "Manrope"
                      ],
                      "body-sm": [
                              "Manrope"
                      ],
                      "label-sm": [
                              "Manrope"
                      ]
              },
              "fontSize": {
                      "display-lg": [
                              "40px",
                              {
                                      "lineHeight": "48px",
                                      "letterSpacing": "-0.02em",
                                      "fontWeight": "600"
                              }
                      ],
                      "label-caps": [
                              "12px",
                              {
                                      "lineHeight": "16px",
                                      "letterSpacing": "0.08em",
                                      "fontWeight": "700"
                              }
                      ],
                      "headline-lg-mobile": [
                              "28px",
                              {
                                      "lineHeight": "36px",
                                      "fontWeight": "500"
                              }
                      ],
                      "headline-lg": [
                              "32px",
                              {
                                      "lineHeight": "40px",
                                      "fontWeight": "500"
                              }
                      ],
                      "title-md": [
                              "18px",
                              {
                                      "lineHeight": "24px",
                                      "letterSpacing": "0.01em",
                                      "fontWeight": "600"
                              }
                      ],
                      "headline-md": [
                              "24px",
                              {
                                      "lineHeight": "32px",
                                      "fontWeight": "500"
                              }
                      ],
                      "body-lg": [
                              "16px",
                              {
                                      "lineHeight": "24px",
                                      "fontWeight": "400"
                              }
                      ],
                      "body-sm": [
                              "14px",
                              {
                                      "lineHeight": "20px",
                                      "fontWeight": "400"
                              }
                      ],
                      "label-sm": [
                              "12px",
                              {
                                      "lineHeight": "16px",
                                      "fontWeight": "500"
                              }
                      ]
              }
      },
          },
        }
    </script>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="bg-background text-on-background font-body-sm min-h-screen flex flex-col antialiased selection:bg-secondary-container selection:text-on-secondary-container pb-[calc(72px+env(safe-area-inset-bottom))]">
<!-- Top App Bar -->
<header class="bg-primary-container/90 backdrop-blur-md text-on-primary flex justify-between items-center w-full px-container-margin h-16 sticky z-40 border-b border-white/10">
<a aria-label="Go back" href="{{ route('customer.account') }}" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
<span class="material-symbols-outlined text-[24px]">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-secondary-fixed-dim uppercase truncate max-w-[200px] text-center">MY ADDRESSES</h1>
<div class="w-10"></div> <!-- Spacer for center alignment -->
</header>
<!-- Main Content -->
<main class="flex-grow flex flex-col px-container-margin py-lg gap-sm max-w-2xl mx-auto w-full">
<!-- Default Address Card (Home) -->
<article class="bg-surface border border-outline-variant rounded-DEFAULT p-sm relative group overflow-hidden transition-all duration-300 hover:shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:border-outline">
<div class="flex justify-between items-start mb-base">
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined text-secondary text-[20px]" style="font-variation-settings: 'FILL' 1;">home</span>
<h2 class="font-title-md text-title-md text-on-surface">Home</h2>
<span class="bg-secondary-container text-on-secondary-container font-label-sm text-label-sm px-2 py-0.5 rounded-sm ml-2">Default</span>
</div>
</div>
<div class="font-body-sm text-body-sm text-on-surface-variant space-y-1 mb-md">
<p class="font-medium text-on-surface">Jane Doe</p>
<p>+1 (555) 123-4567</p>
<p>123 Fashion Avenue, Suite 4B<br/>New York, NY 10001<br/>United States</p>
</div>
<div class="flex gap-sm border-t border-outline-variant pt-sm mt-auto">
<button class="font-label-caps text-label-caps text-on-surface hover:text-secondary transition-colors uppercase tracking-wider flex-1 text-center py-2">Edit</button>
<div class="w-px bg-outline-variant"></div>
<button class="font-label-caps text-label-caps text-error hover:opacity-80 transition-opacity uppercase tracking-wider flex-1 text-center py-2">Delete</button>
</div>
</article>
<!-- Address Card (Office) -->
<article class="bg-surface border border-outline-variant rounded-DEFAULT p-sm relative group overflow-hidden transition-all duration-300 hover:shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:border-outline">
<div class="flex justify-between items-start mb-base">
<div class="flex items-center gap-xs">
<span class="material-symbols-outlined text-on-surface-variant text-[20px]">business</span>
<h2 class="font-title-md text-title-md text-on-surface">Office</h2>
</div>
</div>
<div class="font-body-sm text-body-sm text-on-surface-variant space-y-1 mb-md">
<p class="font-medium text-on-surface">Jane Doe</p>
<p>+1 (555) 987-6543</p>
<p>456 Corporate Blvd, Floor 12<br/>Los Angeles, CA 90001<br/>United States</p>
</div>
<div class="flex gap-sm border-t border-outline-variant pt-sm mt-auto">
<button class="font-label-caps text-label-caps text-on-surface hover:text-secondary transition-colors uppercase tracking-wider flex-1 text-center py-2">Edit</button>
<div class="w-px bg-outline-variant"></div>
<button class="font-label-caps text-label-caps text-error hover:opacity-80 transition-opacity uppercase tracking-wider flex-1 text-center py-2">Delete</button>
</div>
<!-- Set as default action -->
<button class="absolute top-sm right-sm font-label-sm text-label-sm text-on-surface-variant hover:text-secondary underline underline-offset-4 opacity-0 group-hover:opacity-100 transition-opacity">Set Default</button>
</article>
<!-- Add New Address Button (Stickyish context within main) -->
<div class="mt-lg sticky bottom-[env(safe-area-inset-bottom)] pb-md bg-background/90 backdrop-blur-sm pt-4 z-10">
<button class="w-full bg-primary text-on-primary font-label-caps text-label-caps py-4 rounded-DEFAULT hover:opacity-90 transition-opacity flex items-center justify-center gap-2 uppercase tracking-wider">
<span class="material-symbols-outlined text-[20px]">add</span>
                Add New Address
            </button>
</div>
</main>
<!-- Bottom Navigation Bar -->
<nav class="md:hidden bg-primary-container text-on-primary font-label-sm text-label-sm fixed bottom-0 w-full z-50 border-t border-white/10 shadow-sm flex justify-around items-center h-[72px] px-xs pb-safe">
<a class="flex flex-col items-center justify-center text-on-primary/60 hover:text-secondary-fixed-dim transition-colors group w-16" href="{{ route('customer.home') }}">
<span class="material-symbols-outlined mb-1 group-hover:scale-110 transition-transform text-[24px]">home</span>
<span class="truncate w-full text-center">Home</span>
</a>
<a class="flex flex-col items-center justify-center text-on-primary/60 hover:text-secondary-fixed-dim transition-colors group w-16" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined mb-1 group-hover:scale-110 transition-transform text-[24px]">shopping_bag</span>
<span class="truncate w-full text-center">Shop</span>
</a>
<a class="flex flex-col items-center justify-center text-on-primary/60 hover:text-secondary-fixed-dim transition-colors group w-16" href="{{ route('customer.wishlist') }}">
<span class="material-symbols-outlined mb-1 group-hover:scale-110 transition-transform text-[24px]">favorite</span>
<span class="truncate w-full text-center">Wishlist</span>
</a>
<a class="flex flex-col items-center justify-center text-on-primary/60 hover:text-secondary-fixed-dim transition-colors group w-16 relative" href="{{ route('customer.chart') }}">
<span class="material-symbols-outlined mb-1 group-hover:scale-110 transition-transform text-[24px]">shopping_cart</span>
<span class="truncate w-full text-center">Cart</span>
<span class="absolute top-0 right-2 w-2 h-2 bg-secondary-fixed-dim rounded-full"></span>
</a>
<a class="flex flex-col items-center justify-center text-secondary-fixed-dim hover:text-secondary transition-colors scale-95 transition-transform group w-16" href="{{ route('customer.account') }}">
<span class="material-symbols-outlined mb-1 text-[24px]" style="font-variation-settings: 'FILL' 1;">person</span>
<span class="truncate w-full text-center font-medium">Account</span>
</a>
</nav>
</body></html>