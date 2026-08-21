<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - My Reviews</title>
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
<a href="{{ route('customer.account') }}" aria-label="Back" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-secondary-fixed-dim uppercase flex-1 text-center truncate max-w-[240px]">My Reviews</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Tabs -->
<div class="sticky top-16 z-30 bg-surface border-b border-outline-variant mt-16">
<nav class="flex px-container-margin max-w-2xl mx-auto">
<button id="tab-written" onclick="switchTab('written')" class="py-sm px-md border-b-2 border-primary text-primary font-label-caps text-label-caps whitespace-nowrap transition-colors">
                    WRITTEN (3)
                </button>
<button id="tab-to-review" onclick="switchTab('to-review')" class="py-sm px-md border-b-2 border-transparent text-on-surface-variant hover:text-primary transition-colors font-label-caps text-label-caps whitespace-nowrap">
                    TO REVIEW (2)
                </button>
</nav>
</div>
<!-- Main Content -->
<main class="pt-md pb-xl max-w-2xl mx-auto w-full">
<!-- Panel: Written Reviews -->
<section id="panel-written">
<!-- Review 1 -->
<article class="px-container-margin py-md border-b border-outline-variant">
<div class="flex gap-md">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="w-20 h-24 bg-surface-container shrink-0 overflow-hidden block">
<img alt="Tailored Linen Blazer" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPD5-Gnh3eTuUtU4T7JNWo5RRzeJvQHK9Ga-Qyub2VAxmLGZrXcu5eAhUHzglaK2leeCgs_S1rotd_qxAlW3J4__SdbjTf72VBHQzRpit8rbEixeyo2UKLpiBeBbgQfpUO8i83JOSeojGk4-pg0MhKw305uBjXfYyPk4JPteEhhs_SytMO40NERGkVHIbKNFaDIS4tZRo7KpphEGebXYRJRggcWTAf3NNm6pvcs8WOjecDptx1ZzQ"/>
</a>
<div class="flex-grow min-w-0">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Noiré Studio</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Tailored Linen Blazer</h3>
<div class="flex items-center gap-xs mt-xs">
<div class="flex text-secondary-fixed-dim">
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant ml-1">May 12, 2026</span>
</div>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-sm">
                Beautifully tailored and the linen feels premium. The fit is exactly as described and it has become my go-to blazer for both work and weekends.
            </p>
<div class="flex justify-end gap-md mt-sm pt-sm border-t border-outline-variant">
<a href="{{ route('customer.reviews.edit') }}" class="font-label-caps text-label-caps text-on-surface hover:text-secondary transition-colors uppercase tracking-wider py-1 flex items-center">Edit</a>
<button class="font-label-caps text-label-caps text-error hover:opacity-80 transition-opacity uppercase tracking-wider py-1">Delete</button>
</div>
</article>
<!-- Review 2 -->
<article class="px-container-margin py-md border-b border-outline-variant">
<div class="flex gap-md">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="w-20 h-24 bg-surface-container shrink-0 overflow-hidden block">
<img alt="Structured Leather Tote" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDotrquQ9ru5aXlWl5XbgLhEMJq3WBfo5DDEAS3Z-F5LnAIv27Q3259la3QLZghjnF5R8udNJqY0Toq6SHw5JvN3PqANThsUOvwujXixkrq5zZBH5OW_D3QTRD3qObufW5Uz2-ahDe36xdtDHuA8SK2Ldhp4wpMReozYAnqkNj5ZG3A37LwDOS6aXDnCEg_MNh_j2C1VKegB7PNMCwMV-jwzYAwrhuqG1UCGjQoSl3A0QRKO-gFHlQ"/>
</a>
<div class="flex-grow min-w-0">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Lunara Fashion</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Structured Leather Tote</h3>
<div class="flex items-center gap-xs mt-xs">
<div class="flex text-secondary-fixed-dim">
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star_border">star_border</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant ml-1">Apr 28, 2026</span>
</div>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-sm">
                Gorgeous bag with a very sturdy structure. It fits my laptop perfectly. One star off because the strap felt slightly stiff during the first week.
            </p>
<div class="flex justify-end gap-md mt-sm pt-sm border-t border-outline-variant">
<a href="{{ route('customer.reviews.edit') }}" class="font-label-caps text-label-caps text-on-surface hover:text-secondary transition-colors uppercase tracking-wider py-1 flex items-center">Edit</a>
<button class="font-label-caps text-label-caps text-error hover:opacity-80 transition-opacity uppercase tracking-wider py-1">Delete</button>
</div>
</article>
<!-- Review 3 -->
<article class="px-container-margin py-md border-b border-outline-variant">
<div class="flex gap-md">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="w-20 h-24 bg-surface-container shrink-0 overflow-hidden block">
<img alt="Silk Slip Dress" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrQWexD2Xms4d7-qplQNqqTI4EebkIxaCqpOssP3jfxkcDDAjBvE4kuCEgO-j-Yd-Vfxm6sW-zOaQShx89-kFo0JwvaQ9DnVYjw0ZeHlwNYQaWtigNJNUb1P2E3VS7jVbvb2gfkn5AgK0_pHzGjUiSO2kjiDWXbTKy2tRqRQq5I2md_UYdyHQR_axy07aFn3BeoVctJgri9jLNSSEizCJoXGSF5I0rX6QAaqkzanalXeH6sTmuLnA"/>
</a>
<div class="flex-grow min-w-0">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Maëva House</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Silk Slip Dress</h3>
<div class="flex items-center gap-xs mt-xs">
<div class="flex text-secondary-fixed-dim">
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star_border">star_border</span>
<span class="material-symbols-outlined text-[16px]" data-icon="star_border">star_border</span>
</div>
<span class="font-label-sm text-label-sm text-on-surface-variant ml-1">Apr 10, 2026</span>
</div>
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-sm">
                The silk drapes beautifully, but the color is slightly lighter than the photos. Still an elegant piece for special occasions.
            </p>
<div class="flex justify-end gap-md mt-sm pt-sm border-t border-outline-variant">
<a href="{{ route('customer.reviews.edit') }}" class="font-label-caps text-label-caps text-on-surface hover:text-secondary transition-colors uppercase tracking-wider py-1 flex items-center">Edit</a>
<button class="font-label-caps text-label-caps text-error hover:opacity-80 transition-opacity uppercase tracking-wider py-1">Delete</button>
</div>
</article>
</section>
<!-- Panel: To Review -->
<section id="panel-to-review" class="hidden">
<!-- Item 1 -->
<article class="px-container-margin py-md border-b border-outline-variant flex items-center gap-md">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="w-20 h-24 bg-surface-container shrink-0 overflow-hidden block">
<img alt="Geometric Gold Hoops" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAXqNhNFWMr-Gm8_uwAVgBbqtzcNdb5MAfQUsG_3GJbmE0gm167f27WLQY44QclgDSw7N_b2k0qpe9HdTKZlExYsZl6FJUCnKft0foIHP3pp3uFUAxnwrYM3o7ap46wCmmnSGAbNN-gDM_Kptg0bVNG6ghZhp7r3PeQ66ZD2yhgIMKhB9sSycHTa8yXBJ3fTbNvx2tH5SUu76da_WcZ3bJW7JeJmVuEnVOdIHENcwQB0a1sOCp-u_s"/>
</a>
<div class="flex-grow min-w-0">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Kayana Apparel</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Geometric Gold Hoops</h3>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">Delivered May 20, 2026</p>
</div>
<a href="{{ route('customer.reviews.create') }}" class="shrink-0 px-md py-xs border border-primary text-primary font-label-caps text-label-caps uppercase tracking-widest hover:bg-surface-container-low transition-colors">
                Write Review
            </a>
</article>
<!-- Item 2 -->
<article class="px-container-margin py-md border-b border-outline-variant flex items-center gap-md">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="w-20 h-24 bg-surface-container shrink-0 overflow-hidden block">
<img alt="Ribbed Knit Tank" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDBSCYHpJJ10PR1rv62xsiSUHcgECc8Yl7gxPOJqlAhXqjJGHnlXSe3G3OT0zZOpoO6zdOywN_zGJ312gSUWGyrERx3QJH1sib9jdTkpcPR1UGz6mjrBLNDh6NTT4t86gs2BbXDST-ewDyDYcbA5FZIEMUM"/>
</a>
<div class="flex-grow min-w-0">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">RALIVA</span>
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">Ribbed Knit Tank</h3>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">Delivered May 15, 2026</p>
</div>
<a href="{{ route('customer.reviews.create') }}" class="shrink-0 px-md py-xs border border-primary text-primary font-label-caps text-label-caps uppercase tracking-widest hover:bg-surface-container-low transition-colors">
                Write Review
            </a>
</article>
</section>
</main>
<script>
        function switchTab(name) {
            ['written', 'to-review'].forEach(function (tab) {
                var isActive = tab === name;
                var tabBtn = document.getElementById('tab-' + tab);
                var panel = document.getElementById('panel-' + tab);
                tabBtn.classList.toggle('border-primary', isActive);
                tabBtn.classList.toggle('text-primary', isActive);
                tabBtn.classList.toggle('border-transparent', !isActive);
                tabBtn.classList.toggle('text-on-surface-variant', !isActive);
                panel.classList.toggle('hidden', !isActive);
            });
        }
    </script>
</body></html>
