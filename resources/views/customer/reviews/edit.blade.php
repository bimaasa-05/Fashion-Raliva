<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Edit Review') }}</title>
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
<a href="{{ route('customer.reviews') }}" aria-label="{{ __('Back') }}" class="hover:opacity-80 transition-opacity flex">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase flex-1 text-center truncate max-w-[240px]">{{ __('Edit Review') }}</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-40 px-container-margin max-w-2xl mx-auto w-full">
<form>
<!-- Product Summary -->
<section class="py-lg flex items-center gap-md border-b border-outline-variant">
<a href="{{ route('customer.shop.produk-detail', 1) }}" class="w-20 h-24 bg-surface-container shrink-0 overflow-hidden block">
<img alt="Tailored Linen Blazer" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPD5-Gnh3eTuUtU4T7JNWo5RRzeJvQHK9Ga-Qyub2VAxmLGZrXcu5eAhUHzglaK2leeCgs_S1rotd_qxAlW3J4__SdbjTf72VBHQzRpit8rbEixeyo2UKLpiBeBbgQfpUO8i83JOSeojGk4-pg0MhKw305uBjXfYyPk4JPteEhhs_SytMO40NERGkVHIbKNFaDIS4tZRo7KpphEGebXYRJRggcWTAf3NNm6pvcs8WOjecDptx1ZzQ"/>
</a>
<div class="min-w-0">
<span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Noiré Studio</span>
<h2 class="font-title-md text-title-md text-on-surface truncate">Tailored Linen Blazer</h2>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">Purchased May 2, 2026</p>
</div>
</section>
<!-- Rating -->
<section class="py-lg border-b border-outline-variant">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-md">{{ __('Your Rating') }}</h2>
<div class="flex items-center gap-xs" id="rating-stars">
<button aria-label="Rate 1 star" class="star-btn p-1" data-value="1" type="button"><span class="material-symbols-outlined text-[32px] text-secondary-fixed-dim" style="font-variation-settings: 'FILL' 1;">star</span></button>
<button aria-label="Rate 2 stars" class="star-btn p-1" data-value="2" type="button"><span class="material-symbols-outlined text-[32px] text-secondary-fixed-dim" style="font-variation-settings: 'FILL' 1;">star</span></button>
<button aria-label="Rate 3 stars" class="star-btn p-1" data-value="3" type="button"><span class="material-symbols-outlined text-[32px] text-secondary-fixed-dim" style="font-variation-settings: 'FILL' 1;">star</span></button>
<button aria-label="Rate 4 stars" class="star-btn p-1" data-value="4" type="button"><span class="material-symbols-outlined text-[32px] text-secondary-fixed-dim" style="font-variation-settings: 'FILL' 1;">star</span></button>
<button aria-label="Rate 5 stars" class="star-btn p-1" data-value="5" type="button"><span class="material-symbols-outlined text-[32px] text-secondary-fixed-dim" style="font-variation-settings: 'FILL' 1;">star</span></button>
<span class="font-body-sm text-body-sm text-on-surface-variant ml-sm" id="rating-label">5 / 5</span>
<input id="rating-value" type="hidden" value="5"/>
</div>
</section>
<!-- Review Text -->
<section class="py-lg border-b border-outline-variant">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-md">{{ __('Your Review') }}</h2>
<textarea class="w-full bg-surface border border-outline-variant rounded-DEFAULT px-md py-sm font-body-lg text-body-lg text-on-surface placeholder-on-surface-variant focus:outline-none focus:border-primary transition-colors resize-none" id="review-text" placeholder="Share your experience with this product..." rows="5">Beautifully tailored and the linen feels premium. The fit is exactly as described and it has become my go-to blazer for both work and weekends.</textarea>
<p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">{{ __('Minimum 20 characters. Be honest and helpful for other shoppers.') }}</p>
</section>
<!-- Photos -->
<section class="py-lg">
<h2 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-md">{{ __('Add Photos') }} <span class="normal-case tracking-normal text-on-surface-variant/70">{{ __('(optional)') }}</span></h2>
<div class="flex gap-gutter">
<span role="button" tabindex="0" class="w-20 h-20 border border-dashed border-outline rounded-DEFAULT flex flex-col items-center justify-center gap-xs cursor-pointer hover:border-primary hover:text-primary transition-colors text-on-surface-variant">
<span class="material-symbols-outlined text-[24px]">add_a_photo</span>
<span class="font-label-sm text-[10px]">{{ __('Add Photo') }}</span>
</span>
</div>
</section>
</form>
</main>
<!-- Fixed Bottom Action Bar -->
<div class="fixed bottom-0 left-0 w-full bg-surface border-t border-outline-variant px-container-margin py-md z-50 max-w-2xl mx-auto">
<a href="{{ route('customer.reviews') }}" class="w-full bg-primary text-on-primary font-label-caps text-label-caps h-14 flex items-center justify-center hover:opacity-90 transition-opacity uppercase tracking-widest">{{ __('Save Changes') }}        </a>
</div>
<script>
        function setRating(value) {
            document.querySelectorAll('#rating-stars .star-btn').forEach(function (btn) {
                var star = parseInt(btn.dataset.value);
                var icon = btn.querySelector('.material-symbols-outlined');
                if (star <= value) {
                    icon.textContent = 'star';
                    icon.setAttribute('data-icon', 'star');
                    icon.style.fontVariationSettings = "'FILL' 1";
                    icon.classList.add('text-secondary-fixed-dim');
                    icon.classList.remove('text-outline-variant');
                } else {
                    icon.textContent = 'star_border';
                    icon.setAttribute('data-icon', 'star_border');
                    icon.style.fontVariationSettings = "'FILL' 0";
                    icon.classList.remove('text-secondary-fixed-dim');
                    icon.classList.add('text-outline-variant');
                }
            });
            document.getElementById('rating-value').value = value;
            document.getElementById('rating-label').textContent = value + ' / 5';
        }
        document.querySelectorAll('#rating-stars .star-btn').forEach(function (btn) {
            btn.addEventListener('click', function () { setRating(parseInt(btn.dataset.value)); });
        });
    </script>
</body></html>
