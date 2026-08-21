<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - Search</title>
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
        /* Hide scrollbar for horizontal scroll areas */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
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
<body class="bg-surface text-on-surface antialiased font-body-lg pb-[72px] md:pb-0">
<!-- TopAppBar -->
<header class="fixed top-0 w-full z-50 bg-surface dark:bg-surface text-on-surface dark:text-on-surface flex justify-between items-center px-container-margin h-16 border-b border-outline-variant">
<a href="{{ url()->previous() }}" aria-label="Back" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-on-surface dark:text-on-surface">RALIVA</h1>
<div class="flex items-center gap-sm">
<a href="{{ route('customer.chart') }}" aria-label="Cart" class="relative hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
<span class="absolute -top-1 -right-1 bg-secondary text-on-secondary text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">2</span>
</a>
</div>
</header>
<!-- Main Content -->
<main class="pt-16">
<!-- Search Bar Section -->
<section class="py-lg px-container-margin border-b border-outline-variant">
<form class="flex items-center gap-sm w-full max-w-2xl mx-auto">
<div class="relative flex-grow">
<span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
<input autofocus class="w-full bg-surface-container-low border border-outline-variant rounded-full pl-xl pr-md py-sm font-body-lg text-body-lg text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary transition-colors" placeholder="Search products, stores, categories..." type="search"/>
</div>
<button class="bg-primary text-on-primary font-label-caps text-label-caps px-lg py-sm uppercase tracking-widest hover:opacity-90 transition-opacity rounded-full" type="submit">SEARCH</button>
</form>
</section>
<!-- Popular Searches -->
<section class="py-lg px-container-margin border-b border-outline-variant">
<h2 class="font-title-md text-title-md text-on-surface mb-md">Popular Searches</h2>
<div class="flex flex-wrap gap-sm">
@foreach (['Linen Shirt', 'Silk Dress', 'Blazer', 'Tote Bag', 'Trousers', 'Knit Top'] as $tag)
<a href="{{ route('customer.shop') }}" class="shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-primary hover:text-primary transition-colors">{{ $tag }}</a>
@endforeach
</div>
</section>
<!-- Trending Now -->
<section class="py-xl px-container-margin">
<div class="flex justify-between items-center mb-md">
<h2 class="font-title-md text-title-md text-on-surface">Trending Now</h2>
<a href="{{ route('customer.shop') }}" class="font-label-caps text-label-caps text-secondary uppercase tracking-widest hover:opacity-80 transition-opacity">View All</a>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
@foreach ([
                    ['brand' => 'Noiré Studio', 'name' => 'Tailored Linen Blazer', 'price' => '$245.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBPD5-Gnh3eTuUtU4T7JNWo5RRzeJvQHK9Ga-Qyub2VAxmLGZrXcu5eAhUHzglaK2leeCgs_S1rotd_qxAlW3J4__SdbjTf72VBHQzRpit8rbEixeyo2UKLpiBeBbgQfpUO8i83JOSeojGk4-pg0MhKw305uBjXfYyPk4JPteEhhs_SytMO40NERGkVHIbKNFaDIS4tZRo7KpphEGebXYRJRggcWTAf3NNm6pvcs8WOjecDptx1ZzQ'],
                    ['brand' => 'Lunara Fashion', 'name' => 'Structured Leather Tote', 'price' => '$380.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDotrquQ9ru5aXlWl5XbgLhEMJq3WBfo5DDEAS3Z-F5LnAIv27Q3259la3QLZghjnF5R8udNJqY0Toq6SHw5JvN3PqANThsUOvwujXixkrq5zZBH5OW_D3QTRD3qObufW5Uz2-ahDe36xdtDHuA8SK2Ldhp4wpMReozYAnqkNj5ZG3A37LwDOS6aXDnCEg_MNh_j2C1VKegB7PNMCwMV-jwzYAwrhuqG1UCGjQoSl3A0QRKO-gFHlQ'],
                    ['brand' => 'Maëva House', 'name' => 'Silk Slip Dress', 'price' => '$195.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBrQWexD2Xms4d7-qplQNqqTI4EebkIxaCqpOssP3jfxkcDDAjBvE4kuCEgO-j-Yd-Vfxm6sW-zOaQShx89-kFo0JwvaQ9DnVYjw0ZeHlwNYQaWtigNJNUb1P2E3VS7jVbvb2gfkn5AgK0_pHzGjUiSO2kjiDWXbTKy2tRqRQq5I2md_UYdyHQR_axy07aFn3BeoVctJgri9jLNSSEizCJoXGSF5I0rX6QAaqkzanalXeH6sTmuLnA'],
                    ['brand' => 'Kayana Apparel', 'name' => 'Geometric Gold Hoops', 'price' => '$85.00', 'img' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAXqNhNFWMr-Gm8_uwAVgBbqtzcNdb5MAfQUsG_3GJbmE0gm167f27WLQY44QclgDSw7N_b2k0qpe9HdTKZlExYsZl6FJUCnKft0foIHP3pp3uFUAxnwrYM3o7ap46wCmmnSGAbNN-gDM_Kptg0bVNG6ghZhp7r3PeQ66ZD2yhgIMKhB9sSycHTa8yXBJ3fTbNvx2tH5SUu76da_WcZ3bJW7JeJmVuEnVOdIHENcwQB0a1sOCp-u_s'],
                ] as $product)
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative aspect-[3/4] mb-xs bg-surface-container overflow-hidden">
<img alt="{{ $product['name'] }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" src="{{ $product['img'] }}"/>
<span class="absolute top-xs right-xs text-on-surface bg-surface/50 rounded-full p-1 backdrop-blur-sm">
<span class="material-symbols-outlined text-[20px]" data-icon="favorite">favorite</span>
</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ $product['brand'] }}</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface mt-1 truncate">{{ $product['name'] }}</h3>
<span class="font-body-sm text-body-sm text-on-surface mt-1">{{ $product['price'] }}</span>
</a>
@endforeach
</div>
</section>
</main>
<!-- BottomNavBar -->
<nav class="md:hidden fixed bottom-0 w-full z-50 flex justify-around items-center h-[72px] bg-surface dark:bg-surface px-xs pb-safe border-t border-outline-variant shadow-sm">
<!-- Home -->
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16" href="{{ route('customer.home') }}">
<span class="material-symbols-outlined mb-1" data-icon="home">home</span>
<span class="font-label-sm text-[10px]">Home</span>
</a>
<!-- Shop -->
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined mb-1" data-icon="shopping_bag">shopping_bag</span>
<span class="font-label-sm text-[10px]">Shop</span>
</a>
<!-- Wishlist -->
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16" href="{{ route('customer.wishlist') }}">
<span class="material-symbols-outlined mb-1" data-icon="favorite">favorite</span>
<span class="font-label-sm text-[10px]">Wishlist</span>
</a>
<!-- Cart -->
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 relative" href="{{ route('customer.chart') }}">
<span class="material-symbols-outlined mb-1" data-icon="shopping_cart">shopping_cart</span>
<span class="font-label-sm text-[10px]">Cart</span>
<span class="absolute top-0 right-2 bg-primary text-on-primary text-[8px] w-3 h-3 rounded-full flex items-center justify-center font-bold">2</span>
</a>
<!-- Account -->
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16" href="{{ route('customer.account') }}">
<span class="material-symbols-outlined mb-1" data-icon="person">person</span>
<span class="font-label-sm text-[10px]">Account</span>
</a>
</nav>
</body></html>
