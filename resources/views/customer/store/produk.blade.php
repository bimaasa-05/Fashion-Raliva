<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
<title>{{ $store->nama_toko }} - {{ __('Store') }} | RALIVA</title>
<script>if (localStorage.getItem('raliva-theme') === 'dark') document.documentElement.classList.add('theme-dark');</script>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;family=Playfair+Display:wght@500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined[data-weight="fill"] {
            font-variation-settings: 'FILL' 1;
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
                      "secondary": "#8B1E3F",
                      "surface-dim": "#dbdad9",
                      "on-error": "#ffffff",
                      "primary": "#000000",
                      "on-secondary": "#ffffff",
                      "tertiary-container": "#1a1c1a",
                      "error-container": "#ffdad6",
                      "on-tertiary": "#ffffff",
                      "secondary-fixed": "#8B1E3F",
                      "on-primary": "#ffffff",
                      "surface-container-lowest": "#ffffff",
                      "error": "#ba1a1a",
                      "surface-container-highest": "#e3e2e2",
                      "inverse-surface": "#303031",
                      "surface-container": "#efeded",
                      "tertiary": "#1b1c1c",
                      "primary-fixed": "#e5e2e1",
                      "outline-variant": "#c4c7c7",
                      "surface-tint": "#5f5e5e",
                      "secondary-fixed-dim": "#8B1E3F",
                      "outline": "#747878",
                      "on-primary-fixed-variant": "#474646",
                      "on-secondary-fixed-variant": "#6D1428",
                      "on-tertiary-fixed": "#1a1c1a",
                      "on-secondary-container": "#6D1428",
                      "inverse-on-surface": "#f2f0f0",
                      "tertiary-fixed-dim": "#c7c6c4",
                      "tertiary-fixed": "#e3e2df",
                      "surface-container-high": "#e9e8e7",
                      "on-secondary-fixed": "#6D1428",
                      "background": "#fbf9f9",
                      "surface": "#fbf9f9",
                      "secondary-container": "#8B1E3F",
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
<style>
        :root {
        --chrome-bg: #ffffff;
        --chrome-bg-soft: rgba(255,255,255,.92);
        --chrome-text: #1b1c1c;
        --chrome-text-dim: rgba(0,0,0,.55);
        --chrome-text-faint: rgba(0,0,0,.45);
        --chrome-border: rgba(0,0,0,.1);
        --chrome-hover: rgba(0,0,0,.06);
        --chrome-accent: #8B1E3F;
        --surface-ivory: #F8F6F2;
        --surface-warm: #F3F0EA;
        --border-soft: #E5E1DA;
        --text-muted: #777777;
    }
    html.theme-dark {
        --chrome-bg: #1c1b1b;
        --chrome-bg-soft: rgba(28,27,27,.92);
        --chrome-text: #ffffff;
        --chrome-text-dim: rgba(255,255,255,.6);
        --chrome-text-faint: rgba(255,255,255,.5);
        --chrome-border: rgba(255,255,255,.1);
        --chrome-hover: rgba(255,255,255,.1);
        --chrome-accent: #8B1E3F;
        --surface-ivory: #1e1d1c;
        --surface-warm: #201f1e;
        --border-soft: rgba(255,255,255,.1);
        --text-muted: #b9b6b1;
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
    html.theme-dark .text-secondary { color: #8B1E3F !important; }
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
    html.theme-dark .hover\:text-secondary:hover { color: #8B1E3F !important; }
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
<style>
    /* ===== Premium cards + burgundy accents (parity with account/address) ===== */
    .card-premium { box-shadow:0 1px 2px rgb(17 17 17 / .04),0 12px 32px -16px rgb(17 17 17 / .16); transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease; }
    .card-premium:hover { transform:translateY(-3px); box-shadow:0 2px 4px rgb(17 17 17 / .05),0 20px 48px -20px rgb(17 17 17 / .22); border-color:rgba(139,30,63,.45); }
    html.theme-dark .card-premium { background-color:var(--surface-ivory); border-color:var(--border-soft); box-shadow:0 1px 2px rgb(0 0 0 / .3),0 8px 24px -12px rgb(0 0 0 / .5); }
    html.theme-dark .card-premium:hover { box-shadow:0 2px 4px rgb(0 0 0 / .4),0 20px 48px -20px rgb(0 0 0 / .7); border-color:rgba(139,30,63,.55); }
    .premium-heading { display:block; }
    .premium-heading::before { content:''; display:inline-block; width:4px; height:.95em; margin-right:.65rem; background:#8B1E3F; border-radius:9999px; vertical-align:-.05em; }
    .atl-eyebrow { display:inline-flex; align-items:center; gap:.65rem; }
    .atl-eyebrow::before { content:''; width:30px; height:1px; background:var(--chrome-accent); opacity:.7; }
    html.theme-dark .premium-heading::before { background:#8B1E3F; }
    .reveal-up { opacity:0; transform:translateY(12px); transition:opacity .5s ease,transform .5s ease; }
    .reveal-up.is-visible { opacity:1; transform:none; }
    @media (prefers-reduced-motion: reduce) { .reveal-up { opacity:1; transform:none; transition:none; } }
    /* ===== Primary solid button (burgundy + shimmer flash) ===== */
    .btn-gold { position: relative; overflow: hidden; background-color: var(--btn-gold-bg) !important; color: var(--btn-gold-text) !important; }
    .btn-gold::after { content:''; position:absolute; top:-10%; bottom:-10%; left:-80%; width:45%; background: rgba(255,255,255,.55); transform:skewX(-24deg); pointer-events:none; }
    .btn-gold:hover::after { animation: authFlash 1.4s linear infinite; }
    .btn-gold.flashing::after { animation: authFlash 1.4s cubic-bezier(.4,0,.2,1) 1; }
    @keyframes authFlash { from { left:-80%; } to { left:135%; } }
    :root           { --btn-gold-bg:#8B1E3F; --btn-gold-text:#ffffff; }
    html.theme-dark { --btn-gold-bg:#6D1428; --btn-gold-text:#ffffff; }
    /* ===== Drawer burgundy parity ===== */
    #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(139,30,63,.10); }
    html.theme-dark #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(163,38,63,.16); }
  </style>
  </head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[120px] lg:pl-72">
<!-- TopAppBar -->
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
<a href="{{ route('customer.shop') }}" data-go-back aria-label="{{ __('Go back') }}" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
<span class="material-symbols-outlined text-[24px]">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('STORE') }}</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-[120px] w-full">
<section class="py-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<!-- Store Header Section - centered -->
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium text-center">
<p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs mx-auto max-w-xs">{{ __('STORE') }}</p>
<h2 class="premium-heading font-headline-md text-headline-md text-on-surface mb-md">{{ $store->nama_toko }}</h2>
<div class="w-24 h-24 rounded-full overflow-hidden border border-outline-variant mx-auto mb-md shadow-sm">
@if ($store->logo)
<img alt="{{ $store->nama_toko }} Logo" class="w-full h-full object-cover" src="{{ filter_var($store->logo, FILTER_VALIDATE_URL) ? $store->logo : asset($store->logo) }}"/>
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container-high text-on-surface font-headline-xl">
<span class="material-symbols-outlined text-[48px]">storefront</span>
</div>
@endif
</div>
<div class="flex items-center gap-xs text-on-surface-variant font-label-sm text-label-sm mb-md justify-center">
<span class="material-symbols-outlined text-secondary text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span>{{ $averageRating ? number_format($averageRating, 1) : __('No rating yet') }}</span>
<span class="px-2">•</span>
<span>{{ $reviewCount }} {{ __('Reviews') }}</span>
</div>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-md mx-auto mb-lg">
                {{ $store->deskripsi }}
            </p>
<button class="btn-gold font-label-caps text-label-caps px-xl py-sm rounded-none tracking-widest font-label-caps text-label-caps uppercase tracking-widest w-full md:w-auto min-w-[200px] mx-auto">
                {{ __('FOLLOW STORE') }}
            </button>
</div>
<!-- Navigation Tabs (Produk active) -->
<div class="shop-toolbar flex flex-row items-center gap-sm md:gap-md px-container-margin py-md sticky top-16 lg:top-16 z-30 card-premium bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl shadow-sm flex items-center gap-sm md:gap-md min-w-0 overflow-x-auto hide-scrollbar">
<a href="{{ route('customer.shop.store', $store->store_id) }}" class="cat-pill shrink-0 px-md py-xs border border-[var(--chrome-accent)] text-[var(--chrome-accent)] font-label-sm text-label-sm rounded-full bg-secondary/5">{{ __('PRODUCTS') }}</a>
<a href="{{ route('customer.shop.store.riviews', $store->store_id) }}" class="cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-[var(--chrome-accent)] hover:text-[var(--chrome-accent)] transition-colors">{{ __('REVIEWS') }}</a>
<a href="{{ route('customer.shop.store.about', $store->store_id) }}" class="cat-pill shrink-0 px-md py-xs border border-outline-variant text-on-surface-variant font-label-sm text-label-sm rounded-full hover:border-[var(--chrome-accent)] hover:text-[var(--chrome-accent)] transition-colors">{{ __('ABOUT') }}</a>
</div>
<style>
    .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(139,30,63,.35);
        border-top-color: #8B1E3F;
        border-radius: 50%;
        animation: spin .7s linear infinite;
        display: inline-block;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    #load-more-btn:disabled { cursor: not-allowed; opacity: .7; }
</style>
<!-- Product Grid -->
<section class="pt-lg mt-lg reveal-up">
<div id="product-grid" class="grid grid-cols-2 md:grid-cols-4 gap-gutter" data-total="{{ $totalProducts ?? $products->count() }}">
@forelse ($products as $p)
@php $pImg = $p->images->first()?->file_gambar; $pMin = $p->variants->min('harga') ?? $p->harga_dasar; @endphp
<div class="group relative flex flex-col cursor-pointer">
<a href="{{ route('customer.shop.produk-detail', $p->product_id) }}" class="flex flex-col w-full">
<div class="relative w-full aspect-[3/4] mb-sm overflow-hidden bg-surface-container-low">
<img alt="{{ $p->nama_produk }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out" src="{{ $pImg ? (filter_var($pImg, FILTER_VALIDATE_URL) ? $pImg : asset($pImg)) : 'https://picsum.photos/seed/store'.$p->product_id.'/900/1200' }}"/>
</div>
<div class="flex flex-col gap-1">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ $store->nama_toko }}</span>
<h3 class="font-body-sm text-body-sm font-semibold truncate">{{ $p->nama_produk }}</h3>
<span class="font-body-sm text-body-sm text-secondary mt-1">Rp {{ number_format($pMin, 0, ',', '.') }}</span>
</div>
</a>
@php $isWl = in_array($p->product_id, $wishlistedIds, true); @endphp
<button type="button" data-wishlist-toggle data-product-id="{{ $p->product_id }}" aria-label="Add to wishlist" class="absolute top-2 right-2 p-2 text-on-surface hover:text-secondary transition-colors flex items-center z-10{{ $isWl ? ' wishlisted-active' : '' }}">
<span class="material-symbols-outlined" data-icon="favorite{{ $isWl ? '' : '_border' }}"@if($isWl) data-weight="fill"@endif>favorite{{ $isWl ? '' : '_border' }}</span>
</button>
</div>
@empty
<div class="col-span-full text-center py-xl">
<p class="font-body-lg text-body-lg text-on-surface-variant">{{ __('No products in this store yet.') }}</p>
</div>
@endforelse
</div><!-- Load More -->
<div class="mt-xl flex justify-center">
<button id="load-more-btn" class="border border-[var(--chrome-accent)] text-[var(--chrome-accent)] bg-transparent font-label-caps text-label-caps px-xl py-sm hover:bg-surface-container-low transition-colors w-full md:w-auto rounded-lg flex items-center justify-center gap-2 uppercase tracking-widest" type="button" onclick="loadMoreProducts()" style="display:none;">
<span class="spinner" style="display:none;"></span>
<span id="load-more-txt">{{ __('LOAD MORE') }}</span>
</button>
</div>
</section>
</div>
</section>
<div class="md:hidden h-24"></div>
</main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var els = document.querySelectorAll('.reveal-up');
            if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('is-visible'); }); return; }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } });
            }, { threshold: 0.1 });
            els.forEach(function (e) { io.observe(e); });
        });
        (function () {
            var PAGE = 6;
            var revealedCount = PAGE;
            var grid = document.getElementById('product-grid');
            var btn = document.getElementById('load-more-btn');
            if (!grid || !btn) return;
            var total = parseInt(grid.getAttribute('data-total') || '0', 10);
            var cards = Array.prototype.slice.call(grid.children);
            function refresh() {
                cards.forEach(function (c, idx) {
                    c.style.display = idx < revealedCount ? '' : 'none';
                });
                btn.style.display = (total > PAGE && revealedCount < cards.length) ? 'inline-flex' : 'none';
            }
            refresh();
            window.loadMoreProducts = function () {
                if (btn.hasAttribute('disabled')) return;
                btn.setAttribute('disabled', 'disabled');
                var spinner = btn.querySelector('.spinner');
                var txt = document.getElementById('load-more-txt');
                if (spinner) spinner.style.display = 'inline-block';
                if (txt) txt.textContent = 'Loading';
                setTimeout(function () {
                    revealedCount += PAGE;
                    if (spinner) spinner.style.display = 'none';
                    if (txt) txt.textContent = 'LOAD MORE';
                    btn.removeAttribute('disabled');
                    refresh();
                }, 650);
            };
        })();
    </script>
@include('customer._partials.drawer')
<script>
    /* Arrow back = kembali ke halaman customer sebelumnya */
    document.addEventListener('click', function (e) {
        var back = e.target.closest('[data-go-back]');
        if (!back) return;
        e.preventDefault();
        var ref = document.referrer;
        if (ref && ref.indexOf(window.location.origin) === 0) {
            window.history.back();
        } else {
            window.location.href = back.getAttribute('href');
        }
    });
</script>
</body></html>
