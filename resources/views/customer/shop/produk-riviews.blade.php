<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
<title>{{ $product->nama_produk }} - {{ __('Reviews') }} | RALIVA</title>
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
<style>
    /* ===== Store reviews: checkbox/radio accent ===== */
    .shop-checkbox {
        border-radius: 4px;
        transition: border-color .2s ease, background-color .2s ease;
    }
    .shop-checkbox:hover:not(:checked) { border-color: #8B1E3F; }
    .shop-checkbox:checked {
        background-color: #8B1E3F !important;
        border-color: #8B1E3F !important;
    }
    .shop-checkbox:focus-visible { box-shadow: 0 0 0 3px rgba(139,30,63,.3); }
    html.theme-dark .shop-checkbox { border-color: #3a3937; background-color: #201f1e; }
  </style>
  </head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[120px] lg:pl-72">
<!-- TopAppBar -->
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
<a href="{{ route('customer.shop.produk-detail', $product->product_id) }}" aria-label="{{ __('Go back') }}" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
<span class="material-symbols-outlined text-[24px]">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('REVIEWS') }}</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-[120px] w-full">
<section class="py-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<!-- product context card -->
<a href="{{ route('customer.shop.produk-detail', $product->product_id) }}" class="block bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl px-container-margin py-md md:py-lg shadow-sm card-premium hover:border-[var(--chrome-accent)] transition-colors">
<div class="flex items-center gap-md">
@php $pImg = $product->images->first()?->file_gambar; @endphp
<div class="w-20 h-20 md:w-24 md:h-24 rounded-xl overflow-hidden border border-outline-variant shrink-0 bg-surface-container-high">
@if ($pImg)
<img alt="{{ $product->nama_produk }}" class="w-full h-full object-cover" src="{{ filter_var($pImg, FILTER_VALIDATE_URL) ? $pImg : asset($pImg) }}"/>
@else
<div class="w-full h-full flex items-center justify-center bg-surface-container-high text-on-surface-variant">
<span class="material-symbols-outlined text-[40px]">checkroom</span>
</div>
@endif
</div>
<div class="min-w-0 flex-1">
<p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-1">{{ __('PRODUCT REVIEWS') }}</p>
<h2 class="premium-heading font-headline-md text-headline-md text-on-surface truncate">{{ $product->nama_produk }}</h2>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs truncate">{{ $product->store?->nama_toko }}</p>
<div class="flex items-center gap-xs text-on-surface-variant font-label-sm text-label-sm mt-sm">
<span class="material-symbols-outlined text-secondary text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span>{{ $averageRating ? number_format($averageRating, 1) : __('No rating yet') }}</span>
<span class="px-2">•</span>
<span>{{ $reviewCount }} {{ __('Reviews') }}</span>
</div>
</div>
<span class="material-symbols-outlined text-on-surface-variant shrink-0">chevron_right</span>
</div>
</a>

<section class="pt-lg mt-lg reveal-up">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl px-container-margin md:px-[64px] py-md md:py-lg shadow-sm card-premium">
<!-- Rating summary -->
<div class="flex flex-col md:flex-row md:items-center gap-md md:gap-lg border-b border-outline-variant pb-lg mb-lg">
<div class="text-center md:text-left shrink-0">
<p class="font-display-lg text-display-lg text-on-surface leading-none">{{ $averageRating ? number_format($averageRating, 1) : '0.0' }}</p>
<div class="flex text-secondary-fixed-dim justify-center md:justify-start mt-sm">
@for ($i = 1; $i <= 5; $i++)
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">{{ $i <= ($averageRating ? round($averageRating) : 0) ? 'star' : 'star_border' }}</span>
@endfor
</div>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">{{ __('Based on :count reviews', ['count' => $reviewCount]) }}</p>
</div>
<div class="hidden md:block w-px bg-outline-variant h-16"></div>
<div class="space-y-sm flex-1 max-w-md mx-auto md:mx-0 w-full">
@php $dist = [5=>0,4=>0,3=>0,2=>0,1=>0]; foreach ($reviews as $r) { $d = (int)$r->rating; if (isset($dist[$d])) { $dist[$d]++; } } @endphp
@for ($rc = 5; $rc >= 1; $rc--)
@php $cnt = $dist[$rc] ?? 0; $pct = $reviewCount > 0 ? round($cnt / $reviewCount * 100) : 0; @endphp
<div class="flex items-center gap-sm">
<span class="font-label-sm text-label-sm text-on-surface-variant w-8 shrink-0">{{ $rc }}</span>
<div class="flex-1 h-2 rounded-full bg-surface-variant overflow-hidden"><div class="h-full bg-secondary-fixed-dim" style="width:{{ $pct }}%"></div></div>
<span class="font-label-sm text-label-sm text-on-surface-variant w-10 text-right shrink-0">{{ $cnt }}</span>
</div>
@endfor
</div>
</div>
<!-- Review list -->
<h2 class="premium-heading font-headline-md text-headline-md text-on-surface mb-md">{{ __('Buyer Reviews') }}</h2>
@forelse ($reviews as $ri => $review)
@php $u = $review->user; $initials = $u ? collect(explode(' ', trim($u->nama_lengkap ?? '')))->filter()->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('') : 'U'; $rating = (int)$review->rating; @endphp
<article class="border-b border-outline-variant py-md">
<div class="flex items-start justify-between gap-md">
<div class="flex items-center gap-sm min-w-0">
@if ($u && $u->foto_profil)
<img src="{{ filter_var($u->foto_profil, FILTER_VALIDATE_URL) ? $u->foto_profil : asset($u->foto_profil) }}" alt="{{ $u->nama_lengkap }}" class="w-9 h-9 rounded-full object-cover shrink-0"/>
@else
<div class="w-9 h-9 rounded-full bg-secondary-container text-on-secondary-fixed flex items-center justify-center font-label-caps text-label-caps shrink-0">{{ $initials }}</div>
@endif
<div class="min-w-0">
<p class="font-body-sm text-body-sm font-semibold text-on-surface truncate">{{ $u->nama_lengkap ?? __('Anonymous') }}</p>
<p class="font-label-sm text-label-sm text-on-surface-variant truncate">{{ $review->created_at?->format('M d, Y') }} @if (true)· {{ __('Verified Purchase') }}@endif</p>
</div>
</div>
<div class="relative shrink-0">
<button aria-label="{{ __('More options') }}" class="w-8 h-8 rounded-full hover:bg-surface-container-high flex items-center justify-center transition-colors text-on-surface-variant" onclick="toggleReviewMenu(event, 'rv-menu-{{ $ri }}')" type="button">
<span class="material-symbols-outlined text-[18px]">more_vert</span>
</button>
<div class="hidden absolute right-0 top-9 z-20 w-44 bg-surface-container-lowest border border-outline-variant rounded-DEFAULT shadow-xl overflow-hidden" id="rv-menu-{{ $ri }}">
<button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors text-left" onclick="translateReview('rv-text-{{ $ri }}', this)" type="button">
<span class="material-symbols-outlined text-[18px] text-on-surface-variant">translate</span><span class="rv-label" data-state="b" data-a="{{ __('See original') }}" data-b="{{ __('Translate') }}">{{ __('Translate') }}</span>
</button>
<button class="w-full flex items-center gap-sm px-md py-sm font-body-sm text-body-sm text-error hover:bg-surface-container-low transition-colors text-left" onclick="openReport()" type="button">
<span class="material-symbols-outlined text-[18px]">flag</span>{{ __('Report review') }}
</button>
</div>
</div>
</div>
<div class="flex items-center gap-xs mt-xs">
<div class="flex text-secondary-fixed-dim">
@for ($i = 1; $i <= 5; $i++)
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">{{ $i <= $rating ? 'star' : 'star_border' }}</span>
@endfor
</div>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-sm" data-state="original" data-original="{{ $review->ulasan }}" data-translated="{{ $review->ulasan }}" id="rv-text-{{ $ri }}">
{{ $review->ulasan }}
</p>
</article>
@empty
<div class="text-center py-xl">
<p class="font-body-lg text-body-lg text-on-surface-variant">{{ __('No reviews for this product yet. Be the first to review.') }}</p>
</div>
@endforelse
</div>
</section>
</div>
</section>
<div class="md:hidden h-24"></div>
</main>
<!-- Report Review Modal -->
<div class="hidden fixed inset-0 bg-black/50 z-[80] flex items-center justify-center p-container-margin" id="report-modal" onclick="closeReport()">
<div class="w-full max-w-sm bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-xl" onclick="event.stopPropagation()">
<div class="flex items-center gap-sm mb-sm">
<span class="material-symbols-outlined text-error text-[22px]">flag</span>
<h3 class="font-title-md text-title-md text-on-surface">{{ __('Report this review') }}</h3>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mb-md">{{ __('Why are you reporting this review?') }}</p>
<form id="report-form">
<label class="flex items-center gap-sm py-sm border-b border-outline-variant cursor-pointer">
<input class="w-4 h-4 shop-checkbox shrink-0" name="reason" required type="radio" value="spam"/>
<span class="font-body-sm text-body-sm text-on-surface">{{ __('Spam or promotion') }}</span>
</label>
<label class="flex items-center gap-sm py-sm border-b border-outline-variant cursor-pointer">
<input class="w-4 h-4 shop-checkbox shrink-0" name="reason" required type="radio" value="harassment"/>
<span class="font-body-sm text-body-sm text-on-surface">{{ __('Harassment or offensive content') }}</span>
</label>
<label class="flex items-center gap-sm py-sm cursor-pointer">
<input class="w-4 h-4 shop-checkbox shrink-0" name="reason" required type="radio" value="irrelevant"/>
<span class="font-body-sm text-body-sm text-on-surface">{{ __('Irrelevant to the store') }}</span>
</label>
<div class="flex gap-gutter mt-lg">
<button class="flex-1 h-12 border border-secondary text-secondary font-label-caps text-label-caps uppercase tracking-widest hover:bg-secondary/5 transition-colors" onclick="closeReport()" type="button">{{ __('Cancel') }}</button>
<button class="flex-1 h-12 btn-gold font-label-caps text-label-caps uppercase tracking-widest flex items-center justify-center" type="submit">{{ __('Send report') }}</button>
</div>
</form>
<div class="hidden text-center py-md" id="report-success">
<span class="material-symbols-outlined text-secondary text-[48px]">check_circle</span>
<p class="font-body-lg text-body-lg text-on-surface mt-sm mb-lg">{{ __('Thank you. Your report has been submitted.') }}</p>
<button class="w-full h-12 btn-gold font-label-caps text-label-caps uppercase tracking-widest flex items-center justify-center" onclick="closeReport()" type="button">{{ __('Close') }}</button>
</div>
</div>
</div>
<script>
        function toggleReviewMenu(e, id) {
            e.stopPropagation();
            document.querySelectorAll('[id^="rv-menu-"]').forEach(function (m) {
                if (m.id !== id) m.classList.add('hidden');
            });
            document.getElementById(id).classList.toggle('hidden');
        }
        document.addEventListener('click', function () {
            document.querySelectorAll('[id^="rv-menu-"]').forEach(function (m) { m.classList.add('hidden'); });
        });
        function translateReview(id, btn) {
            var p = document.getElementById(id);
            var label = btn.querySelector('.rv-label');
            if (p.dataset.state !== 'translated') {
                p.textContent = p.dataset.translated;
                p.dataset.state = 'translated';
                label.textContent = label.dataset.a;
                label.dataset.state = 'a';
            } else {
                p.textContent = p.dataset.original;
                p.dataset.state = 'original';
                label.textContent = label.dataset.b;
                label.dataset.state = 'b';
            }
        }
        function openReport() {
            document.querySelectorAll('[id^="rv-menu-"]').forEach(function (m) { m.classList.add('hidden'); });
            document.getElementById('report-modal').classList.remove('hidden');
            document.getElementById('report-form').classList.remove('hidden');
            document.getElementById('report-success').classList.add('hidden');
            var f = document.getElementById('report-form');
            f.reset();
            f.querySelectorAll('input[name="reason"]').forEach(function (r) { r.checked = false; });
        }
        function closeReport() {
            document.getElementById('report-modal').classList.add('hidden');
        }
        document.getElementById('report-form').addEventListener('submit', function (e) {
            e.preventDefault();
            this.classList.add('hidden');
            document.getElementById('report-success').classList.remove('hidden');
        });
        document.querySelectorAll('.btn-gold').forEach(function (b) {
            b.addEventListener('click', function () {
                b.classList.remove('flashing');
                void b.offsetWidth;
                b.classList.add('flashing');
                setTimeout(function () { b.classList.remove('flashing'); }, 600);
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            var els = document.querySelectorAll('.reveal-up');
            if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('is-visible'); }); return; }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } });
            }, { threshold: 0.1 });
            els.forEach(function (e) { io.observe(e); });
        });
    </script>
@include('customer._partials.drawer')
</body></html>
