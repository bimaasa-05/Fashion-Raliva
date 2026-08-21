<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Lunara Fashion - RALIVA</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;family=Playfair+Display:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
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
            -webkit-tap-highlight-color: transparent;
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="bg-background text-on-background font-body-sm min-h-screen flex flex-col antialiased">
<!-- Top App Bar -->
<header class="bg-surface dark:bg-surface flex justify-between items-center w-full px-container-margin h-16 border-b border-outline-variant sticky top-0 z-40">
<a aria-label="Back" href="{{ url()->previous() }}" class="text-on-surface dark:text-on-surface hover:opacity-80 transition-opacity flex items-center justify-center p-2 -ml-2">
<span class="material-symbols-outlined" data-icon="arrow_back" style="font-variation-settings: 'FILL' 0;">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-on-surface dark:text-on-surface">RALIVA</h1>
<a aria-label="Search" href="{{ route('customer.search') }}" class="text-on-surface dark:text-on-surface hover:opacity-80 transition-opacity flex items-center justify-center p-2 -mr-2">
<span class="material-symbols-outlined" data-icon="search" style="font-variation-settings: 'FILL' 0;">search</span>
</a>
</header>
<!-- Main Content Canvas -->
<main class="flex-grow pb-24 md:pb-lg w-full max-w-screen-xl mx-auto">
<!-- Store Header Section -->
<section class="px-container-margin py-lg flex flex-col items-center text-center border-b border-outline-variant bg-surface-bright">
<div class="w-24 h-24 rounded-full overflow-hidden border border-outline-variant mb-md shadow-sm">
<img alt="Lunara Fashion Logo" class="w-full h-full object-cover" data-alt="A refined, minimalist logo for 'Lunara Fashion'. The logo features elegant, thin-line serif typography in black against a pure white background. The style is high-end editorial, conveying luxury and sophisticated femininity. Soft, diffuse lighting highlights the crispness of the design." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCI74slTo7YmWoujdZO6hzYYQCAEkiOgSipcStQowpp7n4FDE2M7KDNQCRCchmHtdLRwdkXE_ngeYM2mVTXcdWL59a2HIZB6dtYUgIo3i5FU-CqWMfACDifUy9I4GoR0sbJf0JD6-uqF7DwJwmKxunT2RFbKH_CaEbhz9LLWYM0-9SgznAVzl4INwAta1qIaWmol1GgQv2mTSuClK5luG3I5T04rEShWfMtSHt0JO9SQTrtp7AmW8Y"/>
</div>
<h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg mb-xs">Lunara Fashion</h2>
<div class="flex items-center gap-xs text-on-surface-variant font-label-sm text-label-sm mb-md">
<span class="material-symbols-outlined text-secondary text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span>4.9 Rating</span>
<span class="px-2">•</span>
<span>Jakarta</span>
</div>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-md mx-auto mb-lg">
                Modern feminine silhouettes designed for the contemporary woman. Curated elegance and effortless style.
            </p>
<button class="bg-secondary text-on-secondary font-label-caps text-label-caps px-xl py-sm rounded-none tracking-widest hover:bg-opacity-90 transition-colors w-full md:w-auto min-w-[200px]">
                FOLLOW STORE
            </button>
</section>
<!-- Navigation Tabs -->
<div class="sticky top-16 z-30 bg-surface/95 backdrop-blur-md border-b border-outline-variant">
<nav class="flex px-container-margin w-full overflow-x-auto hide-scrollbar">
<button class="py-sm px-md border-b-2 border-primary text-primary font-label-caps text-label-caps whitespace-nowrap">
                    PRODUCTS
                </button>
<button class="py-sm px-md border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-caps text-label-caps whitespace-nowrap">
                    REVIEWS
                </button>
<button class="py-sm px-md border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-caps text-label-caps whitespace-nowrap">
                    ABOUT
                </button>
</nav>
</div>
<!-- Product Grid -->
<section class="p-container-margin">
<div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
<!-- Product Card 1 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="group flex flex-col cursor-pointer">
<div class="relative w-full aspect-[3/4] mb-sm overflow-hidden bg-surface-container-low">
<img alt="Pleated Silk Midi Dress" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out" data-alt="A high-fashion editorial shot of a woman wearing a flowing, pleated silk midi dress in a soft ivory tone. The model is posed elegantly against a minimalist, textured beige studio backdrop. The lighting is soft and directional, creating gentle shadows that highlight the garment's fluid drape and premium fabric texture." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwXTlTSVsiy5AZHhqQh1MgqLLVK4MB-SrxjlelWELFb6i8KGPNhED4FExnQ1On6jE827hjb842itYeDpn7S7tw2UI8OHLmcvzOIQjusnSbBsepHqK2R8YRQwY0nsQEWDGZdyEEUWXsSSotCfaGFX6QLGpSrhpA33f2FwnImbBAlato1v6p_5xZSRLw2ENMzoWBzF7IAHhf7z3M1e97Js-fu4ICWp1qjJCZVmeLnA9Jwy2JcFaeoTg"/>
<button aria-label="Add to wishlist" class="absolute top-sm right-sm text-on-surface bg-surface/50 backdrop-blur-sm p-1.5 rounded-full hover:bg-surface transition-colors">
<span class="material-symbols-outlined text-[20px]" data-icon="favorite" style="font-variation-settings: 'FILL' 0;">favorite</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lunara Fashion</span>
<h3 class="font-body-sm text-body-sm font-semibold truncate">Pleated Silk Midi Dress</h3>
<span class="font-body-sm text-body-sm mt-1">$245.00</span>
</div>
</a>
<!-- Product Card 2 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="group flex flex-col cursor-pointer">
<div class="relative w-full aspect-[3/4] mb-sm overflow-hidden bg-surface-container-low">
<img alt="Structured Charcoal Blazer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out" data-alt="A close-up editorial photograph focusing on a structured, tailored blazer in a muted charcoal grey. The garment is worn by a model, showing off the sharp shoulders and modern, minimalist lapel design. The background is a stark, bright white, emphasizing the clean lines and sophisticated tailoring of the piece." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAVASjrbvqMDVMUE-4VSQoQPqHIiUufFkdiq_Y8L7NFX4aWn7u7rI8WvL5zpmpwiAIbEfwzPgzdwu1hA22bUnHDJxz5wYyYHWVcAV82899ylh1j1-6PqtxlbV4RBeDtXnfSrNUDLM2tyPuKfT-KF-BQl86qisNMHkxY-wF6tuEgNd0hrwuI0m-ui_3T5OQhhJInd1dX786_WX6sN9UotFNS32L3x8MEu-7n-xDiGDqvqBZN-NWLCMc"/>
<button aria-label="Add to wishlist" class="absolute top-sm right-sm text-on-surface bg-surface/50 backdrop-blur-sm p-1.5 rounded-full hover:bg-surface transition-colors">
<span class="material-symbols-outlined text-[20px]" data-icon="favorite" style="font-variation-settings: 'FILL' 0;">favorite</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lunara Fashion</span>
<h3 class="font-body-sm text-body-sm font-semibold truncate">Structured Charcoal Blazer</h3>
<span class="font-body-sm text-body-sm mt-1">$310.00</span>
</div>
</a>
<!-- Product Card 3 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="group flex flex-col cursor-pointer">
<div class="relative w-full aspect-[3/4] mb-sm overflow-hidden bg-surface-container-low">
<img alt="Wide-Leg Linen Trouser" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out" data-alt="A stylized fashion image featuring a wide-leg linen trouser in a soft sage green. The model is standing in profile, illustrating the relaxed yet elegant fit of the pants. The setting is a minimalist interior with warm, natural sunlight casting long, artistic shadows across the floor, creating a serene, luxurious mood." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBP2Eft0s3saJCK9YLiyH7YEPu7z2LxB1cc9QeyPbQVGwet9-CEp2QoXZmACnrpKKTy2kbye2RrZWgoOW6zx54cYhjCSaPFh8FZuMqiZwZaY6IRPhA6HbRzEMQ3yRCDoomRlqROX0biMNn7k5yzo6DCTNukoS8D98anIAWz6MgD18owlWpqrZJm0rape8bp9bLnq94DDAMeopNINJ9UYjMRANGMp8SiEBcm3OhzeZh0wcFUiR5OjxQ"/>
<button aria-label="Add to wishlist" class="absolute top-sm right-sm text-on-surface bg-surface/50 backdrop-blur-sm p-1.5 rounded-full hover:bg-surface transition-colors">
<span class="material-symbols-outlined text-[20px]" data-icon="favorite" style="font-variation-settings: 'FILL' 0;">favorite</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lunara Fashion</span>
<h3 class="font-body-sm text-body-sm font-semibold truncate">Wide-Leg Linen Trouser</h3>
<span class="font-body-sm text-body-sm mt-1">$185.00</span>
</div>
</a>
<!-- Product Card 4 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="group flex flex-col cursor-pointer">
<div class="relative w-full aspect-[3/4] mb-sm overflow-hidden bg-surface-container-low">
<img alt="Ribbed Knit Top" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out" data-alt="A premium fashion shot of a minimalist, ribbed knit top in a warm terracotta hue. The texture of the fine knit is highly detailed, shown on a model with simple, modern styling. The lighting is moody and dramatic, reminiscent of a high-end fashion magazine editorial, highlighting the subtle contours of the garment." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDWRAobFrskCQKCoX4nsaGtOO--FROG9mOy30DGnRwPRLqK9_pXDrHUmr0Jt9czXcRe5zbwketdXsxccYrU9BnsvlhlRCa-nMKgNgUaEK1fNn3C_VngpO37I5tzeYTiYoX69gbO_ITL750vyHQ5WrTveFKLqD2rqh_YeWF1AjQQMsbZAXGT2XBtoMJJ4d3N9ma0fS41M5tLqEbfAgLclVKPM6f58c_KUxV5hutF8VGYcmi90_1EGD8"/>
<button aria-label="Add to wishlist" class="absolute top-sm right-sm text-on-surface bg-surface/50 backdrop-blur-sm p-1.5 rounded-full hover:bg-surface transition-colors">
<span class="material-symbols-outlined text-[20px]" data-icon="favorite" style="font-variation-settings: 'FILL' 0;">favorite</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lunara Fashion</span>
<h3 class="font-body-sm text-body-sm font-semibold truncate">Ribbed Knit Top</h3>
<span class="font-body-sm text-body-sm mt-1">$120.00</span>
</div>
</a>
</div>
<!-- Load More -->
<div class="mt-xl flex justify-center">
<button class="border border-primary text-primary bg-transparent font-label-caps text-label-caps px-xl py-sm hover:bg-surface-container-low transition-colors w-full md:w-auto">
                    LOAD MORE
                </button>
</div>
</section>
</main>
<!-- Bottom Navigation Bar (Mobile Only) -->
<nav class="flex justify-around items-center w-full h-[72px] bg-surface dark:bg-surface px-xs pb-safe border-t border-outline-variant shadow-sm fixed bottom-0 z-50 md:hidden">
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 h-full" href="{{ route('customer.home') }}">
<span class="material-symbols-outlined mb-1" data-icon="home" style="font-variation-settings: 'FILL' 0;">home</span>
<span class="font-label-sm text-[10px]">Home</span>
</a>
<a class="flex flex-col items-center justify-center text-secondary dark:text-secondary-fixed-dim scale-95 transition-transform w-16 h-full" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined mb-1" data-icon="shopping_bag" style="font-variation-settings: 'FILL' 1;">shopping_bag</span>
<span class="font-label-sm text-[10px] font-semibold">Shop</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 h-full" href="{{ route('customer.wishlist') }}">
<span class="material-symbols-outlined mb-1" data-icon="favorite" style="font-variation-settings: 'FILL' 0;">favorite</span>
<span class="font-label-sm text-[10px]">Wishlist</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 h-full" href="{{ route('customer.chart') }}">
<span class="material-symbols-outlined mb-1" data-icon="shopping_cart" style="font-variation-settings: 'FILL' 0;">shopping_cart</span>
<span class="font-label-sm text-[10px]">Cart</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 h-full" href="{{ route('customer.account') }}">
<span class="material-symbols-outlined mb-1" data-icon="person" style="font-variation-settings: 'FILL' 0;">person</span>
<span class="font-label-sm text-[10px]">Account</span>
</a>
</nav>
</body></html>