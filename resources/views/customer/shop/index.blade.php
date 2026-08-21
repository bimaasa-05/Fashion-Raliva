<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - Shop</title>
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
                        "display-lg": ["40px", { "lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "600" }],
                        "label-caps": ["12px", { "lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "700" }],
                        "headline-lg-mobile": ["28px", { "lineHeight": "36px", "fontWeight": "500" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "500" }],
                        "title-md": ["18px", { "lineHeight": "24px", "letterSpacing": "0.01em", "fontWeight": "600" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "500" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "fontWeight": "500" }]
                    }
                }
            }
        }
    </script>
<style>
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
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] md:pb-0">
<!-- TopAppBar -->
<header class="bg-surface dark:bg-surface text-on-surface dark:text-on-surface sticky full-width top-0 border-b border-outline-variant flat no shadows z-40">
<div class="flex justify-between items-center w-full px-container-margin h-16">
<button aria-label="Menu" class="hover:opacity-80 transition-opacity flex items-center justify-center p-2 -ml-2" onclick="openDrawer()" type="button">
<span class="material-symbols-outlined" data-icon="menu">menu</span>
</button>
<h1 class="font-display-lg text-headline-md tracking-widest text-on-surface dark:text-on-surface">RALIVA</h1>
<div class="flex items-center gap-2">
<a aria-label="Search" href="{{ route('customer.search') }}" class="hover:opacity-80 transition-opacity flex items-center justify-center p-2">
<span class="material-symbols-outlined" data-icon="search">search</span>
</a>
<button aria-label="Filter" class="hover:opacity-80 transition-opacity flex items-center justify-center p-2 -mr-2" onclick="openFilter()" type="button">
<span class="material-symbols-outlined" data-icon="tune">tune</span>
</button>
</div>
</div>
</header>
<!-- Main Content -->
<main class="flex-grow w-full max-w-screen-xl mx-auto flex flex-col md:flex-row">
<!-- Desktop Sidebar Navigation -->
<aside class="hidden md:flex flex-col w-64 flex-shrink-0 border-r border-outline-variant min-h-[calc(100vh-64px)] p-container-margin sticky top-16">
<h2 class="font-title-md text-title-md text-on-surface mb-md">CATEGORIES</h2>
<nav class="flex flex-col gap-sm">
<a class="font-body-lg text-body-lg text-on-surface-variant hover:bg-surface-container-low px-3 py-2 rounded transition-colors flex items-center gap-3" href="#">
<span class="material-symbols-outlined" data-icon="new_releases">new_releases</span>
                    New Arrivals
                </a>
<a class="font-body-lg text-body-lg text-on-surface-variant hover:bg-surface-container-low px-3 py-2 rounded transition-colors flex items-center gap-3" href="#">
<span class="material-symbols-outlined" data-icon="auto_awesome">auto_awesome</span>
                    Designers
                </a>
<a class="font-body-lg text-body-lg text-secondary font-semibold hover:bg-surface-container-low px-3 py-2 rounded transition-colors flex items-center gap-3 bg-surface-container-low" href="#">
<span class="material-symbols-outlined" data-icon="apparel">apparel</span>
                    Clothing
                </a>
<a class="font-body-lg text-body-lg text-on-surface-variant hover:bg-surface-container-low px-3 py-2 rounded transition-colors flex items-center gap-3" href="#">
<span class="material-symbols-outlined" data-icon="watch">watch</span>
                    Accessories
                </a>
<a class="font-body-lg text-body-lg text-on-surface-variant hover:bg-surface-container-low px-3 py-2 rounded transition-colors flex items-center gap-3" href="#">
<span class="material-symbols-outlined" data-icon="menu_book">menu_book</span>
                    Editorial
                </a>
</nav>
</aside>
<!-- Canvas Area -->
<div class="flex-grow flex flex-col w-full">
<!-- Category Tabs (Mobile/Tablet) -->
<div class="w-full border-b border-outline-variant overflow-x-auto hide-scrollbar sticky top-16 bg-surface z-30 md:hidden">
<div class="flex px-container-margin gap-lg min-w-max h-12 items-center">
<button class="font-label-sm text-label-sm text-secondary border-b-2 border-secondary h-full flex items-center px-1">All</button>
<button class="font-label-sm text-label-sm text-on-surface-variant h-full flex items-center px-1 hover:text-on-surface transition-colors">Women</button>
<button class="font-label-sm text-label-sm text-on-surface-variant h-full flex items-center px-1 hover:text-on-surface transition-colors">Men</button>
<button class="font-label-sm text-label-sm text-on-surface-variant h-full flex items-center px-1 hover:text-on-surface transition-colors">Accessories</button>
</div>
</div>
<!-- Sort & Filter Bar -->
<div class="flex justify-between items-center px-container-margin py-sm border-b border-outline-variant">
<div class="font-body-sm text-body-sm text-on-surface-variant">Showing 42 items</div>
<div class="flex gap-4">
<div class="relative" id="sort-menu-container">
<button class="flex items-center gap-1 font-label-sm text-label-sm text-on-surface hover:text-secondary transition-colors" onclick="toggleSortMenu()" type="button">
<span id="sort-label">Sort</span>
<span class="material-symbols-outlined text-[16px] transition-transform duration-200" data-icon="expand_more" id="sort-chevron">expand_more</span>
</button>
<div id="sort-menu" class="absolute right-0 top-full mt-xs w-56 bg-surface rounded-lg border border-outline-variant shadow-xl z-20 py-xs origin-top-right transition-all duration-200 ease-out invisible opacity-0 scale-95 -translate-y-1">
<p class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest px-md pt-xs pb-sm">Sort By</p>
<button class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors font-semibold" data-sort="Newest" onclick="selectSort('Newest')" type="button">
<span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">schedule</span>Newest</span>
<span class="material-symbols-outlined text-[18px] text-secondary sort-check">check</span>
</button>
<button class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors" data-sort="Price: Low to High" onclick="selectSort('Price: Low to High')" type="button">
<span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">arrow_upward</span>Price: Low to High</span>
<span class="material-symbols-outlined text-[18px] text-secondary sort-check invisible">check</span>
</button>
<button class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors" data-sort="Price: High to Low" onclick="selectSort('Price: High to Low')" type="button">
<span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">arrow_downward</span>Price: High to Low</span>
<span class="material-symbols-outlined text-[18px] text-secondary sort-check invisible">check</span>
</button>
<button class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors" data-sort="Popular" onclick="selectSort('Popular')" type="button">
<span class="flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">local_fire_department</span>Popular</span>
<span class="material-symbols-outlined text-[18px] text-secondary sort-check invisible">check</span>
</button>
</div>
</div>
</div>
</div>
<!-- Product Grid -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-gutter p-container-margin">
<!-- Product 1 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative w-full aspect-[3/4] bg-surface-container mb-sm overflow-hidden rounded">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A full-length editorial shot of a model wearing high-end minimal straight fit trousers in a sophisticated ivory tone. The lighting is soft and natural, evoking a premium fashion lookbook style. The background is a stark, bright studio setting to emphasize the clean lines and texture of the fabric." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAps2M8arrWbQY6jXAaISjDMblJoS3se1hpcmHWepeH6VczwS5VPkR4AM-pXm-ncoDRs1Nvlc-uTUq0Njoh538e4U4gtMAG0OyE3mOcGJPaz0g4fpCbTiNUVrBR12VzliXLH0tih4PCW3xl2DSpKGC_xkQZAyXSyn5W9SfOUfPKBcD0MUHvDTvlix7j3UEroZX7lXoveWhsxMc0B1clCXYWJ-5Mct8SR210aTaGAxBrtYJbIinXAiU"/>
<button aria-label="Add to wishlist" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant">RALIVA ESSENTIALS</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Straight Fit Pants</h3>
<span class="font-body-sm text-body-sm text-on-surface">Rp 329.000</span>
</div>
</a>
<!-- Product 2 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative w-full aspect-[3/4] bg-surface-container mb-sm overflow-hidden rounded">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A premium fashion editorial image featuring a relaxed fit blazer in a muted earthy tone, worn by a model in a high-end minimalist setting. The lighting is diffused, highlighting the drape and structure of the garment. The overall aesthetic is clean, sophisticated, and modern." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAax1OhxvSc1htL3J-oZrJsK06nXoqeC7N_pmJWtnMwexPZmJABVpA8hgsW7QimiCrKDbAbF2QZJZqX32JY1O-0BxXFuuSE5FkP0xonQAuzISb3yAK3r-YD1svUl5LmSg6Rdn_vJ_617kZ_uA83kwaYo-0divU3t_vq5baQRi1RcPOEZ4sCHHcfr_xsvzeGRThANll5NUxqWpFBAnjwzb4kGdK_w_CN6OXuOOTYMiLbt9ADnrPJAKA"/>
<button aria-label="Add to wishlist" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant">RALIVA STUDIO</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Relaxed Blazer</h3>
<span class="font-body-sm text-body-sm text-on-surface">Rp 579.000</span>
</div>
</a>
<!-- Product 3 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative w-full aspect-[3/4] bg-surface-container mb-sm overflow-hidden rounded">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="A striking close-up shot of a pleated midi skirt in motion, capturing the elegant flow of the fabric. The color is a soft, warm neutral. The studio lighting casts subtle shadows to emphasize the pleats. The mood is refined and distinctly high-fashion." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAumPepE3uIJ6AwAkmTZ9_-YsAJidBJhhtUl1zj0Gr1TL0xi50_O8B6t0Y-QrwVzrsGu6V9Ez0WWnJAMSroGzu5A9ZFd9BMdxY9fo9n62z5gEI_137Qx8UGHVAMXBxep6FQ7LwfN5GDvsLBShloSY7SE5-bycdtXhqUHyAWcA4B36P_xx4H5ldRuNR76fo3XUMsW3b0Mh-XLL12XFCmtO-5LE3uGUVWagT2xjawnzMa4frmfrKE-SU"/>
<button aria-label="Add to wishlist" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant">RALIVA STUDIO</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Pleated Midi Skirt</h3>
<span class="font-body-sm text-body-sm text-on-surface">Rp 380.000</span>
</div>
</a>
<!-- Product 4 -->
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="flex flex-col group cursor-pointer">
<div class="relative w-full aspect-[3/4] bg-surface-container mb-sm overflow-hidden rounded">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="An editorial fashion photograph of a crisp linen blend shirt in pristine white. The shirt is styled simply on a model against a minimalist, warm grey background. Natural light illuminates the breathable texture of the linen, creating a serene, luxury aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBFDVvGhk1fRmFqr2msVELhThiwYZS_qaw5B2sFlpG_oUABmy2HUkfTdsOGgu3QoYvDiwgRG62hQu2-iz4wze0Jgt10LNVpzeMWtp5JvLJ0s1T3mW9YBzf7XWv2f73BU_Dp8smVo8FG7viGA4YrJKUSEmOB9PLKo12---_uuSNV455LZytF66bBcFn8pdC4HPxE7imenZu4rcnccn6PDK8lreskykX-dBeOyaljMak73QzcCv8e4no"/>
<button aria-label="Add to wishlist" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors">
<span class="material-symbols-outlined" data-icon="favorite_border">favorite_border</span>
</button>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant">RALIVA ESSENTIALS</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Linen Blend Shirt</h3>
<span class="font-body-sm text-body-sm text-on-surface">Rp 299.000</span>
</div>
</a>
</div>
<div class="flex justify-center py-xl border-t border-outline-variant mt-md">
<button class="font-label-caps text-label-caps bg-transparent border border-primary text-primary px-8 py-3 rounded-none hover:bg-primary hover:text-on-primary transition-colors">
                    LOAD MORE
                </button>
</div>
</div>
</main>
<!-- BottomNavBar -->
<nav class="md:hidden fixed bottom-0 w-full z-50 border-t border-outline-variant shadow-sm flex justify-around items-center h-[72px] bg-surface dark:bg-surface px-xs pb-safe">
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 h-full gap-1" href="{{ route('customer.home') }}">
<span class="material-symbols-outlined text-[24px]" data-icon="home">home</span>
<span class="font-label-sm text-label-sm text-[10px]">Home</span>
</a>
<a class="flex flex-col items-center justify-center text-secondary dark:text-secondary-fixed-dim scale-95 transition-transform w-16 h-full gap-1" href="{{ route('customer.shop') }}">
<span class="material-symbols-outlined text-[24px]" data-icon="shopping_bag" data-weight="fill" style="font-variation-settings: 'FILL' 1;">shopping_bag</span>
<span class="font-label-sm text-label-sm text-[10px] font-bold">Shop</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 h-full gap-1" href="{{ route('customer.wishlist') }}">
<span class="material-symbols-outlined text-[24px]" data-icon="favorite">favorite</span>
<span class="font-label-sm text-label-sm text-[10px]">Wishlist</span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 h-full gap-1 relative" href="{{ route('customer.chart') }}">
<span class="material-symbols-outlined text-[24px]" data-icon="shopping_cart">shopping_cart</span>
<span class="font-label-sm text-label-sm text-[10px]">Cart</span>
<span class="absolute top-2 right-4 w-2 h-2 bg-secondary rounded-full"></span>
</a>
<a class="flex flex-col items-center justify-center text-on-surface-variant hover:text-secondary transition-colors w-16 h-full gap-1" href="{{ route('customer.account') }}">
<span class="material-symbols-outlined text-[24px]" data-icon="person">person</span>
<span class="font-label-sm text-label-sm text-[10px]">Account</span>
</a>
</nav>
@include('customer._partials.drawer')
{{-- FILTER BOTTOM SHEET --}}
<div id="filter-overlay" class="fixed inset-0 bg-black/50 z-[60] hidden" onclick="closeFilter()"></div>
<div id="filter-sheet" class="fixed bottom-0 left-0 w-full z-[70] bg-surface rounded-t-2xl translate-y-full transition-transform duration-300 max-h-[85vh] flex flex-col">
<div class="flex justify-center pt-sm shrink-0">
<span class="w-10 h-1 rounded-full bg-outline-variant"></span>
</div>
<div class="flex justify-between items-center px-container-margin py-sm border-b border-outline-variant shrink-0">
<h2 class="font-title-md text-title-md uppercase tracking-widest">Filters</h2>
<button aria-label="Close filters" class="hover:opacity-80 transition-opacity flex" onclick="closeFilter()" type="button">
<span class="material-symbols-outlined" data-icon="close">close</span>
</button>
</div>
<div class="overflow-y-auto px-container-margin pb-md grow">
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest pt-lg pb-sm">Category</h3>
<div class="grid grid-cols-2 gap-sm">
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4" type="checkbox"/><span class="font-body-sm text-body-sm">Women</span></label>
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4" type="checkbox"/><span class="font-body-sm text-body-sm">Men</span></label>
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4" type="checkbox"/><span class="font-body-sm text-body-sm">Accessories</span></label>
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4" type="checkbox"/><span class="font-body-sm text-body-sm">Shoes</span></label>
<label class="flex items-center gap-sm py-xs cursor-pointer"><input class="w-4 h-4" type="checkbox"/><span class="font-body-sm text-body-sm">Bags</span></label>
</div>
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest pt-lg pb-sm">Size</h3>
<div class="flex flex-wrap gap-sm">
<button class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">XS</button>
<button class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">S</button>
<button class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">M</button>
<button class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">L</button>
<button class="f-opt px-md py-xs border border-outline-variant rounded-full font-label-sm text-label-sm text-on-surface-variant transition-shadow" onclick="toggleSel(this)" type="button">XL</button>
</div>
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest pt-lg pb-sm">Color</h3>
<div class="flex flex-wrap gap-md">
<button aria-label="Black" class="f-opt w-8 h-8 rounded-full bg-[#111111] transition-shadow" onclick="toggleSel(this)" type="button"></button>
<button aria-label="White" class="f-opt w-8 h-8 rounded-full bg-[#FFFFFF] border border-outline-variant transition-shadow" onclick="toggleSel(this)" type="button"></button>
<button aria-label="Beige" class="f-opt w-8 h-8 rounded-full bg-[#E5DCC5] transition-shadow" onclick="toggleSel(this)" type="button"></button>
<button aria-label="Brown" class="f-opt w-8 h-8 rounded-full bg-[#6B4F3A] transition-shadow" onclick="toggleSel(this)" type="button"></button>
<button aria-label="Gold" class="f-opt w-8 h-8 rounded-full bg-[#D4AF37] transition-shadow" onclick="toggleSel(this)" type="button"></button>
</div>
<h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest pt-lg pb-sm">Price Range</h3>
<div class="flex items-end gap-gutter">
<div class="flex-1">
<label class="font-label-sm text-label-sm text-on-surface-variant block mb-xs" for="price-min">Min</label>
<input class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-sm text-body-sm focus:outline-none focus:border-primary transition-colors" id="price-min" inputmode="numeric" placeholder="Rp 0"/>
</div>
<span class="text-on-surface-variant pb-sm">—</span>
<div class="flex-1">
<label class="font-label-sm text-label-sm text-on-surface-variant block mb-xs" for="price-max">Max</label>
<input class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-sm text-body-sm focus:outline-none focus:border-primary transition-colors" id="price-max" inputmode="numeric" placeholder="Rp 1.000.000"/>
</div>
</div>
</div>
<div class="flex gap-gutter px-container-margin py-md border-t border-outline-variant shrink-0">
<button class="flex-1 h-12 border border-primary text-primary font-label-caps text-label-caps tracking-widest hover:bg-surface-container-low transition-colors" onclick="resetFilters()" type="button">RESET</button>
<button class="flex-1 h-12 bg-primary text-on-primary font-label-caps text-label-caps tracking-widest hover:opacity-90 transition-opacity" onclick="applyFilters()" type="button">APPLY</button>
</div>
</div>
<script>
        function openFilter() {
            document.getElementById('filter-sheet').classList.remove('translate-y-full');
            document.getElementById('filter-overlay').classList.remove('hidden');
        }
        function closeFilter() {
            document.getElementById('filter-sheet').classList.add('translate-y-full');
            document.getElementById('filter-overlay').classList.add('hidden');
        }
        function applyFilters() {
            closeFilter();
        }
        function toggleSel(el) {
            el.classList.toggle('ring-2');
            el.classList.toggle('ring-on-surface');
        }
        function resetFilters() {
            document.querySelectorAll('#filter-sheet input[type="checkbox"]').forEach(function (cb) { cb.checked = false; });
            document.querySelectorAll('#filter-sheet .f-opt').forEach(function (el) {
                el.classList.remove('ring-2', 'ring-on-surface');
            });
            document.getElementById('price-min').value = '';
            document.getElementById('price-max').value = '';
        }
        function toggleSortMenu() {
            var menu = document.getElementById('sort-menu');
            if (menu.classList.contains('invisible')) {
                menu.classList.remove('invisible', 'opacity-0', 'scale-95', '-translate-y-1');
                document.getElementById('sort-chevron').classList.add('rotate-180');
            } else {
                closeSortMenu();
            }
        }
        function closeSortMenu() {
            document.getElementById('sort-menu').classList.add('invisible', 'opacity-0', 'scale-95', '-translate-y-1');
            document.getElementById('sort-chevron').classList.remove('rotate-180');
        }
        function selectSort(label) {
            document.getElementById('sort-label').textContent = label;
            document.querySelectorAll('#sort-menu [data-sort]').forEach(function (btn) {
                var check = btn.querySelector('.sort-check');
                if (btn.dataset.sort === label) {
                    check.classList.remove('invisible');
                    btn.classList.add('font-semibold');
                } else {
                    check.classList.add('invisible');
                    btn.classList.remove('font-semibold');
                }
            });
            closeSortMenu();
        }
        document.addEventListener('click', function (e) {
            var container = document.getElementById('sort-menu-container');
            if (container && !container.contains(e.target)) {
                closeSortMenu();
            }
        });
    </script>
</body></html>