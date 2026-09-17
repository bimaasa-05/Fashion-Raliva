<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Akun') }}</title>
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
                        "on-primary-fixed": "#1c1b1b","on-error-container": "#93000a","on-tertiary-container": "#848482","surface-bright": "#fbf9f9","on-primary-container": "#858383","primary-fixed-dim": "#c8c6c5","surface-variant": "#e3e2e2","on-surface": "#1b1c1c","secondary": "#8B1E3F","surface-dim": "#dbdad9","on-error": "#ffffff","primary": "#000000","on-secondary": "#ffffff","tertiary-container": "#1a1c1a","error-container": "#ffdad6","on-tertiary": "#ffffff","secondary-fixed": "#8B1E3F","on-primary": "#ffffff","surface-container-lowest": "#ffffff","error": "#ba1a1a","surface-container-highest": "#e3e2e2","inverse-surface": "#303031","surface-container": "#efeded","tertiary": "#000000","primary-fixed": "#e5e2e1","outline-variant": "#c4c7c7","surface-tint": "#5f5e5e","secondary-fixed-dim": "#8B1E3F","outline": "#747878","on-primary-fixed-variant": "#474646","on-secondary-fixed-variant": "#6D1428","on-tertiary-fixed": "#1a1c1a","on-secondary-container": "#6D1428","inverse-on-surface": "#f2f0f0","tertiary-fixed-dim": "#c7c6c4","tertiary-fixed": "#e3e2df","surface-container-high": "#e9e8e7","on-secondary-fixed": "#6D1428","background": "#fbf9f9","surface": "#fbf9f9","secondary-container": "#8B1E3F","on-surface-variant": "#444748","primary-container": "#1c1b1b","inverse-primary": "#c8c6c5","surface-container-low": "#f5f3f3","on-tertiary-fixed-variant": "#464745","on-background": "#1b1c1c"
                    },
                    "borderRadius": {"DEFAULT": "0.25rem","lg": "0.5rem","xl": "0.75rem","full": "9999px"},
                    "spacing": {"gutter": "12px","base": "4px","xl": "48px","lg": "32px","container-margin": "20px","sm": "16px","md": "24px","xs": "8px"},
                    "fontFamily": {"display-lg": ["Playfair Display"],"label-caps": ["Manrope"],"headline-lg-mobile": ["Playfair Display"],"headline-lg": ["Playfair Display"],"title-md": ["Manrope"],"headline-md": ["Playfair Display"],"body-lg": ["Manrope"],"body-sm": ["Manrope"],"label-sm": ["Manrope"]},
                    "fontSize": {"display-lg": ["40px", {"lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "600"}],"label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "700"}],"headline-lg-mobile": ["28px", {"lineHeight": "36px", "fontWeight": "500"}],"headline-lg": ["32px", {"lineHeight": "40px", "fontWeight": "500"}],"title-md": ["18px", {"lineHeight": "24px", "letterSpacing": "0.01em", "fontWeight": "600"}],"headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "500"}],"body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],"body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],"label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}]}
                }
            }
        }
    </script>
<style> body{min-height:max(884px,100dvh);} .pb-safe{padding-bottom:env(safe-area-inset-bottom);} </style>
<style> :root{--chrome-bg:#fff;--chrome-bg-soft:rgba(255,255,255,.92);--chrome-text:#1b1c1c;--chrome-text-dim:rgba(0,0,0,.55);--chrome-text-faint:rgba(0,0,0,.45);--chrome-border:rgba(0,0,0,.1);--chrome-hover:rgba(0,0,0,.06);--chrome-accent:#8B1E3F;--surface-ivory:#F8F6F2;--surface-warm:#F3F0EA;--border-soft:#E5E1DA;--text-muted:#777;} html.theme-dark{--chrome-bg:#1c1b1b;--chrome-bg-soft:rgba(28,27,27,.9);--chrome-text:#fff;--chrome-text-dim:rgba(255,255,255,.6);--chrome-text-faint:rgba(255,255,255,.5);--chrome-border:rgba(255,255,255,.1);--chrome-hover:rgba(255,255,255,.1);--chrome-accent:#8B1E3F;--surface-ivory:#1e1d1c;--surface-warm:#201f1e;--border-soft:rgba(255,255,255,.1);--text-muted:#b9b6b1;} </style>
<style> html.theme-dark .bg-background,html.theme-dark .bg-surface,html.theme-dark .bg-surface-bright{background:#161514!important;} html.theme-dark .bg-surface-container-lowest{background:#1e1d1c!important;} html.theme-dark .bg-surface-container-low{background:#201f1e!important;} html.theme-dark .text-on-surface,html.theme-dark .text-on-background{color:#e6e4e1!important;} html.theme-dark .text-on-surface-variant{color:#b9b6b1!important;} html.theme-dark .border-outline-variant{border-color:#3a3937!important;} </style>
<style> .btn-gold{position:relative;overflow:hidden;background:var(--btn-gold-bg)!important;color:var(--btn-gold-text)!important;} .btn-gold::after{content:'';position:absolute;top:-10%;bottom:-10%;left:-80%;width:45%;background:rgba(255,255,255,.55);transform:skewX(-24deg);pointer-events:none;} .btn-gold:hover::after{animation:authFlash 1.4s linear infinite;} @keyframes authFlash{from{left:-80%}to{left:135%}} :root{--btn-gold-bg:#8B1E3F;--btn-gold-text:#fff;} html.theme-dark{--btn-gold-bg:#6D1428;--btn-gold-text:#fff;} :root{--surface-ivory:#F8F6F2;--surface-warm:#F3F0EA;--border-soft:#E5E1DA;--text-muted:#777;} html.theme-dark{--surface-ivory:#1e1d1c;--surface-warm:#201f1e;--border-soft:rgba(255,255,255,.1);--text-muted:#b9b6b1;} .atl-eyebrow{display:inline-flex;align-items:center;gap:.65rem;} .atl-eyebrow::before{content:'';width:30px;height:1px;background:var(--chrome-accent);opacity:.7;} .card-premium{box-shadow:0 1px 2px rgb(17 17 17 / .04),0 12px 32px -16px rgb(17 17 17 / .16);transition:transform .25s,box-shadow .25s,border-color .25s;} .card-premium:hover{box-shadow:0 2px 4px rgb(17 17 17 / .05),0 20px 48px -20px rgb(17 17 17 / .22);border-color:rgba(139,30,63,.45);} html.theme-dark .card-premium{background:var(--surface-ivory);border-color:var(--border-soft);} .premium-heading::before{content:'';display:inline-block;width:4px;height:.95em;margin-right:.65rem;background:#8B1E3F;border-radius:9999px;vertical-align:-.05em;} .reveal-up{opacity:0;transform:translateY(12px);transition:opacity .5s,transform .5s;} .reveal-up.is-visible{opacity:1;transform:none;} #drawer-panel{--chrome-accent:#8B1E3F;--gold-wash:rgba(139,30,30,.10);} html.theme-dark #drawer-panel{--chrome-accent:#8B1E3F;--gold-wash:rgba(163,38,38,.16);} </style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] md:pb-0 lg:pl-72">
<header class="flex justify-between items-center w-full px-container-margin h-16 bg-[var(--chrome-bg)] text-[var(--chrome-text)] border-b border-[var(--chrome-border)] sticky top-0 z-40">
<button aria-label="{{ __('Menu') }}" class="hover:opacity-80 lg:hidden flex items-center justify-center" onclick="openDrawer()" type="button"><span class="material-symbols-outlined">menu</span></button>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)]">RALIVA</h1>
<div class="w-10" aria-hidden="true"></div>
</header>
<main class="flex-grow pt-10 pb-8 lg:pb-12 w-full overflow-x-hidden">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up text-center overflow-hidden relative">
<div class="absolute inset-0 opacity-[0.04]" style="background: radial-gradient(600px 220px at 50% -40px, #8B1E3F 0%, transparent 70%);"></div>
<div class="relative">
<div class="w-20 h-20 rounded-full bg-secondary/10 border border-secondary/15 flex items-center justify-center mx-auto mb-md">
<span class="material-symbols-outlined text-[38px] text-secondary">person</span>
</div>
<p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-secondary justify-center mb-xs">{{ __('AKUN RALIVA') }}</p>
<h2 class="font-headline-md text-headline-md text-on-surface">{{ __('Akun Raliva Anda') }}</h2>
<p class="font-body-sm text-body-sm text-on-surface-variant mt-sm max-w-xl mx-auto">{{ __('Masuk untuk mengakses pesanan, alamat, wishlist, ulasan, notifikasi, dan pengaturan akun Anda. Belum punya akun? Daftar sekarang.') }}</p>
<div class="flex flex-col sm:flex-row gap-sm justify-center mt-lg max-w-md mx-auto">
<a href="{{ route('login', ['redirect' => route('customer.account')]) }}" class="btn-gold flex-1 inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
<span class="material-symbols-outlined text-[18px]">login</span> {{ __('Masuk') }}
</a>
<a href="{{ route('register') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full border border-secondary text-secondary font-label-caps text-label-caps uppercase tracking-widest hover:bg-secondary hover:text-white transition-colors">
<span class="material-symbols-outlined text-[18px]">person_add</span> {{ __('Daftar') }}
</a>
</div>
</div>
</div>

<section class="py-lg md:py-xl reveal-up">
<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md card-premium flex gap-sm">
<span class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0 border border-outline-variant/30"><span class="material-symbols-outlined text-secondary text-[20px]">local_mall</span></span>
<div class="min-w-0"><h3 class="font-title-md text-title-md text-on-surface">{{ __('Pesanan & Lacak Pengiriman') }}</h3><p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ __('Lihat semua pesanan dan status pengiriman setelah masuk.') }}</p></div>
</div>
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md card-premium flex gap-sm">
<span class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0 border border-outline-variant/30"><span class="material-symbols-outlined text-secondary text-[20px]">location_on</span></span>
<div class="min-w-0"><h3 class="font-title-md text-title-md text-on-surface">{{ __('Alamat Tersimpan') }}</h3><p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ __('Kelola alamat pengiriman untuk checkout lebih cepat.') }}</p></div>
</div>
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md card-premium flex gap-sm">
<span class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0 border border-outline-variant/30"><span class="material-symbols-outlined text-secondary text-[20px]">favorite</span></span>
<div class="min-w-0"><h3 class="font-title-md text-title-md text-on-surface">{{ __('Wishlist') }}</h3><p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ __('Simpan produk favorit dan cek kembali kapan saja.') }}</p></div>
</div>
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md card-premium flex gap-sm">
<span class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0 border border-outline-variant/30"><span class="material-symbols-outlined text-secondary text-[20px]">star</span></span>
<div class="min-w-0"><h3 class="font-title-md text-title-md text-on-surface">{{ __('Ulasan') }}</h3><p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ __('Tulis ulasan setelah pesanan selesai untuk membantu pembeli lain.') }}</p></div>
</div>
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md card-premium flex gap-sm">
<span class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0 border border-outline-variant/30"><span class="material-symbols-outlined text-secondary text-[20px]">notifications</span></span>
<div class="min-w-0"><h3 class="font-title-md text-title-md text-on-surface">{{ __('Notifikasi') }}</h3><p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ __('Dapatkan update pesanan, promo, dan info penting lainnya.') }}</p></div>
</div>
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md card-premium flex gap-sm">
<span class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center shrink-0 border border-outline-variant/30"><span class="material-symbols-outlined text-secondary text-[20px]">settings</span></span>
<div class="min-w-0"><h3 class="font-title-md text-title-md text-on-surface">{{ __('Pengaturan') }}</h3><p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ __('Atur preferensi akun, bahasa, dan keamanan.') }}</p></div>
</div>
</div>
<div class="flex flex-col sm:flex-row gap-sm justify-center mt-lg">
<a href="{{ route('customer.shop') }}" class="inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full border border-outline-variant font-label-caps text-label-caps uppercase tracking-widest hover:border-secondary hover:text-secondary transition-colors">
<span class="material-symbols-outlined text-[18px]">storefront</span> {{ __('Lanjut Belanja') }}
</a>
</div>
</section>
</div>
</main>
@include('customer._partials.bottom-nav')
@include('customer._partials.drawer')
<script>document.querySelectorAll('.btn-gold').forEach(function(b){b.addEventListener('click',function(){b.classList.remove('flashing');void b.offsetWidth;b.classList.add('flashing');setTimeout(function(){b.classList.remove('flashing');},600);});});document.addEventListener('DOMContentLoaded',function(){var els=document.querySelectorAll('.reveal-up');if(!('IntersectionObserver'in window)){els.forEach(function(e){e.classList.add('is-visible');});return;}var io=new IntersectionObserver(function(entries){entries.forEach(function(en){if(en.isIntersecting){en.target.classList.add('is-visible');io.unobserve(en.target);}});},{threshold:0.08});els.forEach(function(e){io.observe(e);});});</script>
@if (session('toast'))
    <script>
        (function () {
            var msg = @js(session('toast.message'));
            var icon = @js(session('toast.icon', 'lock'));
            var toast = document.createElement('div');
            toast.innerHTML = '<span class="material-symbols-outlined text-[18px]">' + icon + '</span><span>' + msg + '</span>';
            toast.style.cssText = 'position:fixed;left:50%;bottom:96px;transform:translateX(-50%);display:flex;align-items:center;gap:8px;background:#1c1b1b;color:#fff;padding:10px 18px;border-radius:999px;font-size:13px;font-family:Manrope,sans-serif;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,.25);opacity:0;transition:opacity .3s ease;';
            document.body.appendChild(toast);
            requestAnimationFrame(function () { toast.style.opacity = '1'; });
            setTimeout(function () { toast.style.opacity = '0'; setTimeout(function () { toast.remove(); }, 350); }, 2200);
        })();
    </script>
@endif
</body></html>
