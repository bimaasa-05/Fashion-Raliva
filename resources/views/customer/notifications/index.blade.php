<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - Notifications</title>
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
<h1 class="font-display-lg text-headline-md tracking-widest text-secondary-fixed-dim uppercase flex-1 text-center truncate max-w-[240px]">Notifications</h1>
<div class="w-10"></div> <!-- Spacer for centering -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-xl max-w-2xl mx-auto w-full">
<!-- Mark All As Read Bar -->
<div class="flex justify-end items-center px-container-margin py-sm border-b border-outline-variant">
<button class="font-label-caps text-label-caps text-secondary uppercase tracking-widest hover:opacity-80 transition-opacity" onclick="markAllRead()" type="button">
            Mark all as read
        </button>
</div>
<!-- Notification List -->
<section>
<!-- Notification 1 (Unread) -->
<article class="notification-item flex gap-md px-container-margin py-md border-b border-outline-variant cursor-pointer hover:bg-surface-container-low transition-colors" onclick="markRead(this)">
<div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">local_mall</span>
</div>
<div class="flex-grow min-w-0">
<div class="flex justify-between items-start gap-sm">
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface">Order Shipped</h3>
<span class="font-label-sm text-[10px] text-on-surface-variant whitespace-nowrap mt-1">2h ago</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Your order #RLV-240520-5678 has been shipped and is on its way.</p>
</div>
<span class="unread-dot w-2 h-2 rounded-full bg-secondary shrink-0 self-center"></span>
</article>
<!-- Notification 2 (Unread) -->
<article class="notification-item flex gap-md px-container-margin py-md border-b border-outline-variant cursor-pointer hover:bg-surface-container-low transition-colors" onclick="markRead(this)">
<div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">sell</span>
</div>
<div class="flex-grow min-w-0">
<div class="flex justify-between items-start gap-sm">
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface">Mid-Year Sale</h3>
<span class="font-label-sm text-[10px] text-on-surface-variant whitespace-nowrap mt-1">5h ago</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Enjoy up to 50% off on selected items. Use code RALIVA50 at checkout.</p>
</div>
<span class="unread-dot w-2 h-2 rounded-full bg-secondary shrink-0 self-center"></span>
</article>
<!-- Notification 3 -->
<article class="notification-item flex gap-md px-container-margin py-md border-b border-outline-variant cursor-pointer hover:bg-surface-container-low transition-colors" onclick="markRead(this)">
<div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">favorite</span>
</div>
<div class="flex-grow min-w-0">
<div class="flex justify-between items-start gap-sm">
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface">Wishlist Price Drop</h3>
<span class="font-label-sm text-[10px] text-on-surface-variant whitespace-nowrap mt-1">1d ago</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Silk Slip Dress from your wishlist is now $175.00 — down from $195.00.</p>
</div>
</article>
<!-- Notification 4 -->
<article class="notification-item flex gap-md px-container-margin py-md border-b border-outline-variant cursor-pointer hover:bg-surface-container-low transition-colors" onclick="markRead(this)">
<div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">local_shipping</span>
</div>
<div class="flex-grow min-w-0">
<div class="flex justify-between items-start gap-sm">
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface">Order Delivered</h3>
<span class="font-label-sm text-[10px] text-on-surface-variant whitespace-nowrap mt-1">3d ago</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">Order #RLV-240501-1234 was delivered. We hope you love it!</p>
</div>
</article>
<!-- Notification 5 -->
<article class="notification-item flex gap-md px-container-margin py-md border-b border-outline-variant cursor-pointer hover:bg-surface-container-low transition-colors" onclick="markRead(this)">
<div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">rate_review</span>
</div>
<div class="flex-grow min-w-0">
<div class="flex justify-between items-start gap-sm">
<h3 class="font-body-sm text-body-sm font-semibold text-on-surface">Review Reminder</h3>
<span class="font-label-sm text-[10px] text-on-surface-variant whitespace-nowrap mt-1">4d ago</span>
</div>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">How were your Geometric Gold Hoops? Share a review to help other shoppers.</p>
</div>
</article>
</section>
</main>
<script>
        function markRead(el) {
            var dot = el.querySelector('.unread-dot');
            if (dot) dot.remove();
        }
        function markAllRead() {
            document.querySelectorAll('.unread-dot').forEach(function (dot) { dot.remove(); });
        }
    </script>
</body></html>
