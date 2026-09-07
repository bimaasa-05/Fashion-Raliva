<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
<title>RALIVA - {{ __('Add Address') }}</title>
<script>if (localStorage.getItem('raliva-theme') === 'dark') document.documentElement.classList.add('theme-dark');</script>
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
                      "secondary": "#8B1E3F",
                      "surface-dim": "#dbdad9",
                      "on-error": "#ffffff",
                      "primary": "#000000",
                      "on-secondary": "#ffffff",
                      "tertiary-container": "#1a1c1a",
                      "error-container": "#ffdad6",
                      "on-tertiary": "#ffffff",
                      "secondary-fixed": "#fbe2e9",
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
                      "secondary-fixed-dim": "#f1c2cf",
                      "outline": "#747878",
                      "on-primary-fixed-variant": "#474646",
                      "on-secondary-fixed-variant": "#6D1428",
                      "on-tertiary-fixed": "#1a1c1a",
                      "on-secondary-container": "#8B1E3F",
                      "inverse-on-surface": "#f2f0f0",
                      "tertiary-fixed-dim": "#c7c6c4",
                      "tertiary-fixed": "#e3e2df",
                      "surface-container-high": "#e9e8e7",
                      "on-secondary-fixed": "#5e0f23",
                      "background": "#fbf9f9",
                      "surface": "#fbf9f9",
                      "secondary-container": "#fbe2e9",
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
        --border-soft: #E5E1DA;
    }
    html.theme-dark {
        --chrome-bg: #1c1b1c;
        --chrome-bg-soft: rgba(28,27,27,.9);
        --chrome-text: #ffffff;
        --chrome-text-dim: rgba(255,255,255,.6);
        --chrome-text-faint: rgba(255,255,255,.5);
        --chrome-border: rgba(255,255,255,.1);
        --chrome-hover: rgba(255,255,255,.1);
        --chrome-accent: #8B1E3F;
        --surface-ivory: #1e1d1c;
        --border-soft: rgba(255,255,255,.1);
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
  /* ===== Premium cards + burgundy accents (parity with home/account) ===== */
  .card-premium { box-shadow:0 1px 2px rgb(17 17 17 / .04),0 12px 32px -16px rgb(17 17 17 / .16); transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease; }
  .card-premium:hover { transform:translateY(-3px); box-shadow:0 2px 4px rgb(17 17 17 / .05),0 20px 48px -20px rgb(17 17 17 / .22); border-color:rgba(139,30,63,.45); }
  html.theme-dark .card-premium { background-color:var(--surface-ivory); border-color:var(--border-soft); box-shadow:0 1px 2px rgb(0 0 0 / .3),0 8px 24px -12px rgb(0 0 0 / .5); }
  html.theme-dark .card-premium:hover { box-shadow:0 2px 4px rgb(0 0 0 / .4),0 20px 48px -20px rgb(0 0 0 / .7); border-color:rgba(139,30,63,.55); }
  .premium-heading { display:block; }
  .premium-heading::before { content:''; display:inline-block; width:4px; height:.95em; margin-right:.65rem; background:#8B1E3F; border-radius:9999px; vertical-align:-.05em; }
  .atl-eyebrow { display:inline-flex; align-items:center; gap:.65rem; }
  .atl-eyebrow::before { content:''; width:30px; height:1px; background:var(--chrome-accent); opacity:.7; }
  html.theme-dark .premium-heading::before { background:#8B1E3F; }
  /* ===== Drawer / sidebar burgundy — match other customer pages (rgba(139,30,30)) ===== */
  #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(139,30,30,.10); }
  html.theme-dark #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(163,38,38,.16); }
  /* ===== Drawer / chrome burgundy remap ===== */
  html.theme-dark .bg-chrome-burgundy { background-color:#8B1E3F !important; }
  html.theme-dark .text-chrome-burgundy { color:#8B1E3F !important; }
  .border-burgundy { border-color:#8B1E3F !important; }
  /* ===== Primary solid button (parity with Home SUBSCRIBE / SHOP COLLECTION) ===== */
  .btn-gold {
    position: relative;
    overflow: hidden;
    background-color: var(--btn-gold-bg) !important;
    color: var(--btn-gold-text) !important;
  }
  .btn-gold::after {
    content: '';
    position: absolute;
    top: -10%;
    bottom: -10%;
    left: -80%;
    width: 45%;
    background: rgba(255,255,255,.55);
    transform: skewX(-24deg);
    pointer-events: none;
  }
  .btn-gold:hover::after { animation: authFlash 1.4s linear infinite; }
  .btn-gold.flashing::after { animation: authFlash 1.4s cubic-bezier(.4,0,.2,1) 1; }
  @keyframes authFlash { from { left: -80%; } to { left: 135%; } }
  :root           { --btn-gold-bg: #8B1E3F; --btn-gold-text: #ffffff; }
  html.theme-dark { --btn-gold-bg: #6D1428; --btn-gold-text: #ffffff; }
  /* ===== Scroll reveal ===== */
  .reveal-up { opacity:0; transform:translateY(12px); transition:opacity .5s ease,transform .5s ease; }
  .reveal-up.is-visible { opacity:1; transform:none; }
  @media (prefers-reduced-motion: reduce) { .reveal-up { opacity:1; transform:none; transition:none; } }
</style>
  </head>
<body class="bg-background text-on-background font-body-sm min-h-screen flex flex-col antialiased selection:bg-secondary-container selection:text-on-secondary-container pb-[calc(72px+env(safe-area-inset-bottom))] lg:pl-72">
<!-- Top App Bar -->
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
<a aria-label="{{ __('Go back') }}" href="{{ route('customer.address.index') }}" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
<span class="material-symbols-outlined text-[24px]">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('ADD ADDRESS') }}</h1>
<div class="w-10"></div> <!-- Spacer for center alignment -->
</header>
<!-- Main Content -->
<main class="pt-16 pb-[120px] w-full">
<section class="py-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
<p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">ADDRESS BOOK</p>
<h2 class="premium-heading font-headline-md text-headline-md text-on-surface mb-md">{{ __('Add New Address') }}</h2>

<form method="POST" action="{{ route('customer.address.store') }}" class="space-y-md">
@csrf
<div class="space-y-md">
<div>
<label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Label') }}</label>
<div class="relative" id="label-dropdown-container">
<input type="hidden" name="label" id="address_label" value="Home"/>
<button type="button" id="label-trigger" onclick="toggleLabelMenu()" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none flex items-center justify-between gap-sm cursor-pointer hover:border-secondary transition-colors">
<span id="label-trigger-label" class="flex items-center gap-2">
<span class="material-symbols-outlined text-[20px] text-on-surface-variant">home</span>
<span>{{ __('Home') }}</span>
</span>
<span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform duration-200" id="label-chevron">expand_more</span>
</button>
<div id="label-menu" class="absolute left-0 right-0 top-full mt-xs w-full bg-surface rounded-lg border border-outline-variant shadow-xl z-20 py-xs origin-top transition-all duration-200 ease-out invisible opacity-0 scale-95 -translate-y-1">
<button type="button" data-label="Home" onclick="selectLabel(this)" class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors font-semibold">
<span class="label-body flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">home</span>{{ __('Home') }}</span>
<span class="material-symbols-outlined text-[18px] text-secondary label-check">check</span>
</button>
<button type="button" data-label="Office" onclick="selectLabel(this)" class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors">
<span class="label-body flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">business</span>{{ __('Office') }}</span>
<span class="material-symbols-outlined text-[18px] text-secondary label-check invisible">check</span>
</button>
<button type="button" data-label="Other" onclick="selectLabel(this)" class="w-full flex items-center justify-between gap-sm text-left px-md py-sm font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors">
<span class="label-body flex items-center gap-sm"><span class="material-symbols-outlined text-[18px] text-on-surface-variant">location_on</span>{{ __('Other') }}</span>
<span class="material-symbols-outlined text-[18px] text-secondary label-check invisible">check</span>
</button>
</div>
</div>
</div>
<div>
<label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Full Name') }}</label>
<input type="text" name="nama_penerima" id="address_nama_penerima" required maxlength="150" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none" placeholder="{{ __('John Doe') }}"/>
@error('nama_penerima')
<p class="text-error text-label-sm mt-xs">{{ $message }}</p>
@enderror
</div>
<div>
<label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Phone Number') }}</label>
<input type="tel" name="nomor_telepon" id="address_nomor_telepon" required maxlength="30" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none" placeholder="+62 812-3456-7890"/>
@error('nomor_telepon')
<p class="text-error text-label-sm mt-xs">{{ $message }}</p>
@enderror
</div>
<div>
<label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Address') }}</label>
<textarea name="alamat" id="address_alamat" rows="3" required class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none resize-none" placeholder="{{ __('Jl. Contoh No. 123') }}"></textarea>
@error('alamat')
<p class="text-error text-label-sm mt-xs">{{ $message }}</p>
@enderror
</div>
<div class="grid grid-cols-2 gap-sm">
<div>
<label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('City') }}</label>
<input type="text" name="kota" id="address_kota" required maxlength="100" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none" placeholder="{{ __('Jakarta') }}"/>
@error('kota')
<p class="text-error text-label-sm mt-xs">{{ $message }}</p>
@enderror
</div>
<div>
<label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Province') }}</label>
<input type="text" name="provinsi" id="address_provinsi" maxlength="100" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none" placeholder="{{ __('DKI Jakarta') }}"/>
@error('provinsi')
<p class="text-error text-label-sm mt-xs">{{ $message }}</p>
@enderror
</div>
</div>
<div class="grid grid-cols-2 gap-sm">
<div>
<label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Postal Code') }}</label>
<input type="text" name="kode_pos" id="address_kode_pos" maxlength="20" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none" placeholder="12345"/>
@error('kode_pos')
<p class="text-error text-label-sm mt-xs">{{ $message }}</p>
@enderror
</div>
<div>
<label class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block mb-xs">{{ __('Country') }}</label>
<input type="text" name="negara" id="address_negara" maxlength="100" class="w-full bg-surface border border-outline-variant rounded-lg px-sm py-2.5 text-on-surface font-body-sm focus:border-secondary focus:outline-none" value="Indonesia"/>
</div>
</div>
<div class="flex items-center gap-sm">
<input type="checkbox" name="is_default" id="address_is_default" value="1" class="w-4 h-4 rounded border-outline-variant text-secondary focus:ring-secondary"/>
<label for="address_is_default" class="font-body-sm text-on-surface-variant">{{ __('Set as default address') }}</label>
</div>
</div>
<div class="flex flex-col sm:flex-row gap-sm pt-sm">
<a href="{{ route('customer.address.index') }}" class="flex-1 flex items-center justify-center gap-2 py-3 rounded-full border border-outline-variant text-on-surface-variant font-label-caps text-label-caps uppercase tracking-widest hover:bg-surface-container-low hover:border-secondary hover:text-secondary transition-all duration-200">
<span class="material-symbols-outlined text-[18px]">close</span>{{ __('Cancel') }}</a>
<button type="submit" class="btn-gold flex-1 flex items-center justify-center gap-2 py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest shadow-lg">
<span class="material-symbols-outlined text-[18px]">check</span>{{ __('Save Address') }}</button>
</div>
</form>
</div>
</div>
</div>
</section>
</main>

@include('customer._partials.drawer')

<script>
function toggleLabelMenu() {
    var menu = document.getElementById('label-menu');
    if (menu.classList.contains('invisible')) {
        menu.classList.remove('invisible', 'opacity-0', 'scale-95', '-translate-y-1');
        document.getElementById('label-chevron').classList.add('rotate-180');
    } else {
        closeLabelMenu();
    }
}
function closeLabelMenu() {
    document.getElementById('label-menu').classList.add('invisible', 'opacity-0', 'scale-95', '-translate-y-1');
    document.getElementById('label-chevron').classList.remove('rotate-180');
}
function selectLabel(btn) {
    document.getElementById('address_label').value = btn.getAttribute('data-label');
    var body = btn.querySelector('.label-body');
    if (body) {
        document.getElementById('label-trigger-label').innerHTML = body.innerHTML;
    }
    document.querySelectorAll('#label-menu [data-label]').forEach(function (b) {
        var check = b.querySelector('.label-check');
        if (b === btn) {
            check.classList.remove('invisible');
            b.classList.add('font-semibold');
        } else {
            check.classList.add('invisible');
            b.classList.remove('font-semibold');
        }
    });
    closeLabelMenu();
}
document.addEventListener('click', function (e) {
    var container = document.getElementById('label-dropdown-container');
    if (container && !container.contains(e.target)) closeLabelMenu();
});
</script>
</body></html>
