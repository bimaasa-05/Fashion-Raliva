<!DOCTYPE html>

<html class="light" lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
<title>RALIVA - {{ __('My Complaints') }}</title>
<script>if (localStorage.getItem('raliva-theme') === 'dark') document.documentElement.classList.add('theme-dark');</script>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
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
                        "tertiary": "#000000",
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
                        "on-background": "#1b1c1b"
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
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
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
        --chrome-accent: #8B1E3F;
        --surface-ivory: #F8F6F2;
        --surface-warm: #F3F0EA;
        --border-soft: #E5E1DA;
        --text-muted: #777777;
    }
    html.theme-dark {
        --chrome-bg: #1c1b1b;
        --chrome-bg-soft: rgba(28,27,27,.9);
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
    html.theme-dark .text-on-surface-variant\/50 { color: rgba(185,182,177,.5) !important; }
    html.theme-dark .text-on-surface-variant\/60 { color: rgba(185,182,177,.6) !important; }
    html.theme-dark .bg-surface-container-high\/80 { background-color: rgba(44,43,42,.8) !important; }
    html.theme-dark .bg-surface-container-lowest\/60 { background-color: rgba(30,29,28,.6) !important; }
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
    .card-premium { box-shadow:0 1px 2px rgb(17 17 17 / .04),0 12px 32px -16px rgb(17 17 17 / .16); transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease; }
    .card-premium:hover {  box-shadow:0 2px 4px rgb(17 17 17 / .05),0 20px 48px -20px rgb(17 17 17 / .22); border-color:rgba(139,30,63,.45); }
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
    .btn-gold { position: relative; overflow: hidden; background-color: var(--btn-gold-bg) !important; color: var(--btn-gold-text) !important; }
    .btn-gold::after { content:''; position:absolute; top:-10%; bottom:-10%; left:-80%; width:45%; background: rgba(255,255,255,.55); transform:skewX(-24deg); pointer-events:none; }
    .btn-gold:hover::after { animation: authFlash 1.4s linear infinite; }
    .btn-gold.flashing::after { animation: authFlash 1.4s cubic-bezier(.4,0,.2,1) 1; }
    @keyframes authFlash { from { left:-80%; } to { left:135%; } }
    :root           { --btn-gold-bg:#8B1E3F; --btn-gold-text:#ffffff; }
    html.theme-dark { --btn-gold-bg:#6D1428; --btn-gold-text:#ffffff; }
    #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(139,30,63,.10); }
    html.theme-dark #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(163,38,63,.16); }
  </style>
  </head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[120px] lg:pl-72">
<!-- TopAppBar -->
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
<a href="{{ route('customer.account') }}" aria-label="{{ __('Go back') }}" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
<span class="material-symbols-outlined text-[24px]">arrow_back</span>
</a>
<h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('MY COMPLAINTS') }}</h1>
<div class="w-10"></div>
</header>
<!-- Main Content -->
<main class="pt-16 pb-[120px] w-full">
<section class="py-xl reveal-up">
<div class="mx-auto max-w-[1400px] px-container-margin">
<div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
<p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('COMPLAINTS') }}</p>
<div class="flex flex-wrap items-center justify-between gap-sm mb-md">
<h2 class="font-title-md text-title-md text-on-surface">{{ __('Komplain Saya') }}</h2>
<a href="{{ route('customer.komplain.create') }}" class="btn-gold inline-flex items-center justify-center gap-2 font-label-caps text-label-caps px-lg py-3 rounded-full uppercase tracking-widest">
<span class="material-symbols-outlined text-[18px]">add</span>{{ __('Ajukan Komplain') }}
</a>
</div>
<div class="space-y-glue">
@forelse ($complaints as $c)
    @php
        $statusLabel = match ($c->status) {
            'open' => __('Baru'),
            'diproses' => __('Dalam Penanganan'),
            'selesai' => __('Selesai'),
            'escalated' => __('Eskalasi'),
            'ditutup' => __('Ditutup'),
            default => ucfirst($c->status),
        };
        $done = in_array($c->status, ['selesai', 'ditutup'], true);
    @endphp
    <article data-complaint-card data-open-id="{{ $c->complaint_id }}" data-open-subjek="{{ $c->subjek }}" data-open-kode="{{ $c->complaint_id }}" data-open-statuslabel="{{ $statusLabel }}" data-open-done="{{ $done ? '1' : '0' }}" onclick="openChatFromCard(this)" class="group flex items-start gap-sm md:gap-md p-md border border-outline-variant rounded-xl cursor-pointer transition-colors hover:border-secondary">
        <div class="w-11 h-11 rounded-full bg-surface-container flex items-center justify-center shrink-0 {{ $done ? '' : 'text-[var(--chrome-accent)]' }}">
            <span class="material-symbols-outlined text-[22px]">{{ $done ? 'task_alt' : 'support_agent' }}</span>
        </div>
        <div class="flex-grow min-w-0">
            <p class="font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">{{ $c->complaint_id }} • {{ $c->order_id ? $c->order_id : '-' }}</p>
            <p class="font-title-md text-title-md text-on-surface mt-1 truncate">{{ $c->subjek }}</p>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 line-clamp-2">{{ $c->deskripsi }}</p>
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2 text-xs text-on-surface-variant">
                <span class="capitalize">{{ $c->kategori }}</span>
                <span>•</span>
                <span>{{ $c->store?->nama_toko ?? 'RALIVA' }}</span>
                <span>•</span>
                <span>{{ optional($c->dibuat_pada)->translatedFormat('d M Y, H:i') }}</span>
            </div>
        </div>
        <div class="shrink-0 self-stretch flex flex-col items-end gap-md">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $done ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }}">{{ $statusLabel }}</span>
            <span class="mt-auto mb-auto inline-flex items-center gap-1 px-3 py-2 rounded-full border border-outline-variant text-on-surface-variant group-hover:border-secondary group-hover:text-secondary transition-colors font-label-caps text-label-caps uppercase tracking-widest">
                <span class="material-symbols-outlined text-[16px]">chat</span>{{ __('Buka') }}
            </span>
        </div>
    </article>
@empty
    <div class="text-center py-10">
        <span class="material-symbols-outlined text-[48px] text-outline-variant block mx-auto mb-sm">support_agent</span>
        <p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Belum ada komplain.') }}</p>
        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ __('Ajukan komplain jika ada masalah dengan pesanan Anda.') }}</p>
    </div>
@endforelse
</div>
@if ($complaints instanceof \Illuminate\Pagination\AbstractPaginator && $complaints->hasPages())
    <div class="mt-md">{{ $complaints->links() }}</div>
@endif
</div>
</div>
</section>
</main>
<style>
    @keyframes raliva-chat-backdrop-in { from { opacity: 0; } to { opacity: 1; } }
    @keyframes raliva-chat-backdrop-out { from { opacity: 1; } to { opacity: 0; } }
    @keyframes raliva-chat-sheet-in-mobile { from { transform: translateY(100%); } to { transform: translateY(0); } }
    @keyframes raliva-chat-sheet-out-mobile { from { transform: translateY(0); } to { transform: translateY(100%); } }
    @keyframes raliva-chat-sheet-in-desktop { from { transform: translateX(100%); } to { transform: translateX(0); } }
    @keyframes raliva-chat-sheet-out-desktop { from { transform: translateX(0); } to { transform: translateX(100%); } }
    .raliva-chat-in { animation: raliva-chat-backdrop-in .2s ease-out both; }
    .raliva-chat-out { animation: raliva-chat-backdrop-out .2s ease-in both; }
    .raliva-chat-in-sheet { animation: raliva-chat-sheet-in-mobile .28s cubic-bezier(.22,.68,.34,1) both; }
    .raliva-chat-out-sheet { animation: raliva-chat-sheet-out-mobile .28s cubic-bezier(.22,1,.36,1) both; }
    @media (min-width: 1024px) {
        .raliva-chat-in-sheet { animation-name: raliva-chat-sheet-in-desktop; }
        .raliva-chat-out-sheet { animation-name: raliva-chat-sheet-out-desktop; }
    }
    @media (prefers-reduced-motion: reduce) {
        .raliva-chat-in, .raliva-chat-out, .raliva-chat-in-sheet, .raliva-chat-out-sheet { animation: none; }
    }
    #chat-messages { scrollbar-width: none; -ms-overflow-style: none; }
    #chat-messages::-webkit-scrollbar { display: none; }
    #chat-emoji-panel { scrollbar-width: none; -ms-overflow-style: none; }
    #chat-emoji-panel::-webkit-scrollbar { display: none; }
    #chat-edit-emoji-panel { scrollbar-width: none; -ms-overflow-style: none; }
    #chat-edit-emoji-panel::-webkit-scrollbar { display: none; }
    .raliva-doodle {
        background-color: var(--surface-container-low);
        background-image: radial-gradient(circle at 1.5px 1.5px, rgba(120, 80, 0, .10) 1.5px, transparent 0);
        background-size: 22px 22px;
    }
    html.theme-dark .raliva-doodle {
        background-image: radial-gradient(circle at 1.5px 1.5px, rgba(255, 255, 255, .07) 1.5px, transparent 0);
    }
    #chat-wp-layer { background-size: cover; background-position: center; }
    #chat-messages.chat-selecting .chat-sel-box { display: inline-flex; }
    .chat-selecting .chat-msg { cursor: pointer; user-select: none; -webkit-user-select: none; }
    .chat-sel-box {
        display: none;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        flex-shrink: 0;
        color: var(--on-surface-variant);
        cursor: pointer;
        transition: transform .2s ease, opacity .2s ease, background-color .2s ease, color .2s ease;
    }
    .chat-sel-box:hover { transform: scale(1.1); }
    .chat-sel-box:active { transform: scale(.92); }
    .chat-sel-box .material-symbols-outlined { font-size: 20px; }
    .chat-msg.sel-selected { background-color: rgba(139, 30, 63, .06); border-radius: 10px; }
    .chat-msg.sel-selected .chat-sel-box { background-color: var(--chrome-accent); color: #fff; }
    #chat-input-area.chat-selecting #chat-composer { display: none; }
    #chat-input-area.chat-selecting #chat-closed-note { display: none; }
    #chat-select-bar { display: none; }
    #chat-input-area.chat-selecting #chat-select-bar { display: flex; }
    #chat-select-bar::-webkit-scrollbar { display: none; }
    #chat-select-bar { scrollbar-width: none; }
    #chat-select-bar.animate-in { animation: chatSelIn .25s ease both; }
    #chat-select-bar.animate-out { animation: chatSelOut .2s ease both; }
    @keyframes chatSelIn { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    @keyframes chatSelOut { from { transform: translateY(0); opacity: 1; } to { transform: translateY(100%); opacity: 0; } }
</style>
<!-- Chat Komplain Modal (ala Super Admin; warna RALIVA) -->
<div class="hidden fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm" id="chat-container" onclick="if(event.target===this) closeChatModal()">
    <div class="min-h-full lg:h-full flex flex-col justify-end lg:flex-row lg:justify-end" onclick="if(event.target===this) closeChatModal()">
        <div id="chat-panel" class="flex flex-col bg-surface-container-low border-t lg:border-t-0 lg:border-l border-[var(--border-soft)] rounded-t-3xl lg:rounded-none max-h-[85dvh] lg:max-h-full lg:h-full lg:w-[560px] lg:max-w-full overflow-hidden" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between gap-2 px-6 py-4 border-b border-[var(--border-soft)] shrink-0">
                <div class="min-w-0">
                    <h3 class="font-title-md text-title-md text-on-surface truncate" id="chat-subject">-</h3>
                    <p class="font-mono text-on-surface-variant text-xs mt-0.5" id="chat-kode">-</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span id="chat-status" class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border bg-surface-container-high text-on-surface-variant border-outline-variant"></span>
                    <div class="relative shrink-0" id="chat-more-wrap">
                        <button type="button" onclick="toggleChatMoreMenu()" id="chat-more-btn" class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer" title="{{ __('Menu') }}">
                            <span class="material-symbols-outlined text-[20px]">more_horiz</span>
                        </button>
                        <div id="chat-more-menu" class="hidden absolute right-0 top-full mt-2 min-w-[220px] rounded-xl border border-outline-variant bg-surface-container-high shadow-xl z-40 py-1.5">
                            <button type="button" onclick="openWallpaperPicker()" id="chat-more-item-wallpaper" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer flex items-center gap-2">
                                <span class="material-symbols-outlined text-[19px]">wallpaper</span>{{ __('Ganti Wallpaper') }}
                            </button>
                            <button type="button" onclick="resetWallpaper()" id="chat-more-item-wp-reset" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer flex items-center gap-2">
                                <span class="material-symbols-outlined text-[19px]">restart_alt</span>{{ __('Reset Wallpaper') }}
                            </button>
                            <button type="button" onclick="selectMessagesMode()" id="chat-more-item-select" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer flex items-center gap-2">
                                <span class="material-symbols-outlined text-[19px]">check_box</span>{{ __('Select Messages') }}
                            </button>
                            <button type="button" onclick="openExportChat()" id="chat-more-item-export" class="w-full text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer flex items-center gap-2">
                                <span class="material-symbols-outlined text-[19px]">ios_share</span>{{ __('Ekspor Chat') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-4 min-h-0" id="chat-messages">
                <div class="flex justify-center items-center py-8">
                    <div class="w-10 h-10 border-4 border-secondary border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
            <div class="relative px-6 pt-4 pb-[max(1rem,env(safe-area-inset-bottom))] border-t border-[var(--border-soft)] bg-surface-container-lowest/60 shrink-0" id="chat-input-area">
                <div id="chat-emoji-panel" class="hidden absolute bottom-full mb-3 left-6 z-10 w-[264px] max-w-[calc(100vw-4rem)] lg:w-[320px] max-h-[220px] overflow-y-auto rounded-xl border border-outline-variant bg-surface-container-high p-3 shadow-xl"></div>
                <div id="chat-composer" class="flex items-end gap-2 lg:gap-3">
                    <button type="button" onclick="toggleEmojiPanel()" id="chat-emoji-toggle" aria-label="{{ __('Emoji') }}" title="{{ __('Emoji') }}" class="w-10 h-10 lg:w-12 lg:h-12 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0">
                        <span class="material-symbols-outlined text-[20px]">mood</span>
                    </button>
                    <textarea id="chat-input" rows="1" maxlength="2000" placeholder="{{ __('Tulis pesan...') }}"
                        class="flex-1 bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant resize-none focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors"
                        onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}"></textarea>
                    <button type="button" onclick="sendMessage()" id="chat-send"
                        class="w-12 h-12 flex items-center justify-center bg-secondary text-white shrink-0 hover:opacity-80 transition-opacity disabled:opacity-40 rounded-full">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                    </button>
                </div>
                <div id="chat-select-bar" class="items-center gap-2 lg:gap-3 py-1 overflow-x-auto" aria-label="{{ __('Select messages') }}">
                    <button type="button" onclick="exitSelectMessages()" id="chat-sel-close" class="w-10 h-10 lg:w-11 lg:h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="{{ __('Keluar seleksi') }}">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                    <span id="chat-sel-count" class="font-body-md text-body-md text-on-surface-variant shrink-0 whitespace-nowrap">0 selected</span>
                    <div class="flex-1 min-w-0"></div>
                    <button type="button" onclick="copySelectedMessages()" id="chat-sel-copy" class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="{{ __('Salin') }}" aria-label="{{ __('Salin') }}">
                        <span class="material-symbols-outlined text-[20px]">content_copy</span>
                    </button>
                    <button type="button" onclick="confirmDeleteSelected()" id="chat-sel-delete" class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="{{ __('Hapus') }}" aria-label="{{ __('Hapus') }}">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                    <button type="button" onclick="downloadSelectedMessages()" id="chat-sel-download" class="w-10 h-10 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="{{ __('Unduh') }}" aria-label="{{ __('Unduh') }}">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                    </button>
                </div>
                <p id="chat-closed-note" class="hidden text-center font-body-sm text-body-sm text-on-surface-variant pt-4">{{ __('Komplain telah selesai dan tidak dapat dibalas lagi.') }}</p>
            </div>
        </div>
    </div>
    <input type="file" id="chat-wallpaper-input" accept="image/*" class="hidden">
    <div id="chat-delete-dialog" class="hidden fixed inset-0 z-[70] flex items-end sm:items-center justify-center bg-black/50" onclick="if(event.target===this){event.stopPropagation();closeDeleteDialog();}">
        <div class="w-full sm:max-w-sm bg-surface-container-low rounded-t-3xl sm:rounded-2xl p-2 sm:p-4 border border-outline-variant shadow-2xl" onclick="event.stopPropagation()">
            <p class="font-title-sm text-title-sm text-on-surface px-4 pt-3 pb-2">{{ __('Hapus pesan ini?') }}</p>
            <button type="button" id="chat-del-opt-all" data-del-per="all" onclick="deleteMessage(deleteDialogMsgId,'all')" class="w-full text-left px-4 py-3 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="block font-body-sm text-body-sm text-on-surface">{{ __('Hapus untuk semua orang') }}</span>
                <span class="block font-body-sm text-body-sm text-on-surface-variant/80">{{ __('Pesan akan dihapus untuk semua peserta chat ini') }}</span>
            </button>
            <button type="button" data-del-per="me" onclick="deleteMessage(deleteDialogMsgId,'me')" class="w-full text-left px-4 py-3 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="block font-body-sm text-body-sm text-on-surface">{{ __('Hapus untuk diri sendiri') }}</span>
                <span class="block font-body-sm text-body-sm text-on-surface-variant/80">{{ __('Pesan hanya dihapus dari perangkat Anda') }}</span>
            </button>
            <button type="button" onclick="closeDeleteDialog()" class="w-full text-left px-4 py-3 mt-1 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="font-body-sm text-body-sm text-secondary">{{ __('Batal') }}</span>
            </button>
        </div>
    </div>
    <div id="chat-sel-delete-dialog" class="hidden fixed inset-0 z-[70] flex items-end sm:items-center justify-center bg-black/50" onclick="if(event.target===this){event.stopPropagation();closeSelDeleteDialog();}">
        <div class="w-full sm:max-w-sm bg-surface-container-low rounded-t-3xl sm:rounded-2xl p-2 sm:p-4 border border-outline-variant shadow-2xl" onclick="event.stopPropagation()">
            <p class="font-title-sm text-title-sm text-on-surface px-4 pt-3 pb-2">Hapus <span id="chat-sel-del-count" class="text-error">-</span>?</p>
            <p class="font-body-sm text-body-sm text-on-surface-variant px-4 pb-2">{{ __('Pesan hanya dihapus dari akun Anda.') }}</p>
            <button type="button" data-sel-del-ok onclick="deleteSelectedMessages()" class="w-full text-left px-4 py-3 mt-1 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="font-body-sm text-body-sm text-error">{{ __('Hapus untuk diri sendiri') }}</span>
            </button>
            <button type="button" onclick="closeSelDeleteDialog()" class="w-full text-left px-4 py-3 mt-1 hover:bg-surface-container-high transition-colors cursor-pointer rounded-xl">
                <span class="font-body-sm text-body-sm text-secondary">{{ __('Batal') }}</span>
            </button>
        </div>
    </div>
    <div id="chat-edit-dialog" class="hidden fixed inset-0 z-[80] flex items-end sm:items-center justify-center bg-black/50" onclick="if(event.target===this){event.stopPropagation();closeEditDialog();}">
        <div class="w-full sm:max-w-lg bg-surface-container-low rounded-t-3xl sm:rounded-2xl border border-outline-variant shadow-2xl overflow-hidden flex flex-col max-h-[90dvh]" onclick="event.stopPropagation()">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-[var(--border-soft)] shrink-0">
                <button type="button" onclick="closeEditDialog()" class="p-2 -ml-2 rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer" title="{{ __('Tutup') }}">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                <h3 class="font-title-md text-title-md text-on-surface">{{ __('Edit pesan') }}</h3>
            </div>
            <div class="raliva-doodle flex-1 min-h-[150px] sm:min-h-[220px] flex items-center justify-end px-6 py-8">
                <div class="max-w-[90%] rounded-xl px-4 py-2.5 bg-secondary text-white">
                    <p class="text-xs mb-1 text-white/60 uppercase tracking-wider">{{ __('Anda') }}</p>
                    <p id="chat-edit-preview" class="font-body-sm text-body-sm whitespace-pre-wrap break-words">-</p>
                </div>
            </div>
            <div class="relative border-t border-[var(--border-soft)] bg-surface-container-lowest/60 px-5 pt-4 pb-[max(1rem,env(safe-area-inset-bottom))] shrink-0">
                <div id="chat-edit-emoji-panel" class="hidden absolute bottom-full mb-3 left-5 z-10 w-[264px] max-w-[calc(100vw-4rem)] lg:w-[320px] max-h-[220px] overflow-y-auto rounded-xl border border-outline-variant bg-surface-container-high p-3 shadow-xl"></div>
                <div class="flex items-end gap-2 lg:gap-3">
                    <button type="button" onclick="toggleEditEmojiPanel()" id="chat-edit-emoji-toggle" class="w-10 h-10 lg:w-11 lg:h-11 flex items-center justify-center rounded-full text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface transition-colors cursor-pointer shrink-0" title="{{ __('Emoji') }}">
                        <span class="material-symbols-outlined text-[20px]">mood</span>
                    </button>
                    <textarea id="chat-edit-input" rows="1" maxlength="2000" class="flex-1 bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant resize-none focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors" onkeydown="if(event.key==='Enter'&&(event.ctrlKey||event.metaKey)){event.preventDefault();saveEditMessage();}"></textarea>
                    <button type="button" onclick="saveEditMessage()" id="chat-edit-save" class="w-11 h-11 lg:w-12 lg:h-12 flex items-center justify-center bg-secondary text-white shrink-0 hover:opacity-80 transition-opacity disabled:opacity-40 rounded-full" title="{{ __('Simpan') }}">
                        <span class="material-symbols-outlined text-[20px]">check</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var els = document.querySelectorAll('.reveal-up');
        if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('is-visible'); }); return; }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } });
        }, { threshold: 0.1 });
        els.forEach(function (e) { io.observe(e); });
    });
</script>
<script>
    let currentChat = { id: null, polling: null, done: false, closing: false };
    const myId = {{ Auth::id() }};

    function openChatFromCard(el) {
        const card = el.closest('[data-complaint-card]');
        if (!card) return;
        openChatModal(
            card.getAttribute('data-open-id'),
            card.getAttribute('data-open-subjek'),
            card.getAttribute('data-open-kode'),
            card.getAttribute('data-open-statuslabel'),
            card.getAttribute('data-open-done') === '1'
        );
    }

    function openChatModal(id, subjek, kode, statusLabel, done) {
        currentChat.id = id;
        currentChat.done = done;
        currentChat.closing = false;
        chatEditMsgId = null;
        chatSelMode = false;
        chatSelIds.clear();
        document.getElementById('chat-messages').classList.remove('chat-selecting');
        document.getElementById('chat-input-area').classList.remove('chat-selecting');
        closeSelDeleteDialog();
        closeChatMoreMenu();
        closeChatMenu();
        closeDeleteDialog();
        closeEditDialog();
        closeEmojiPanel();
        document.getElementById('chat-subject').textContent = subjek;
        document.getElementById('chat-kode').textContent = kode;
        const statusEl = document.getElementById('chat-status');
        statusEl.textContent = statusLabel || '';
        statusEl.className = 'shrink-0 inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border ' + (done ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant');
        document.getElementById('chat-composer').classList.toggle('hidden', done);
        document.getElementById('chat-closed-note').classList.toggle('hidden', !done);
        document.getElementById('chat-messages').innerHTML = '<div class="flex justify-center items-center py-8"><div class="w-10 h-10 border-4 border-secondary border-t-transparent rounded-full animate-spin"></div></div>';

        const container = document.getElementById('chat-container');
        const panel = document.getElementById('chat-panel');
        container.classList.remove('hidden', 'raliva-chat-out');
        panel.classList.remove('raliva-chat-out-sheet');
        void container.offsetWidth;
        container.classList.add('raliva-chat-in');
        panel.classList.add('raliva-chat-in-sheet');
        document.body.style.overflow = 'hidden';

        loadMessages();
        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.polling = setInterval(loadMessages, 5000);
    }

    function closeChatModal() {
        const container = document.getElementById('chat-container');
        if (currentChat.closing || container.classList.contains('hidden')) return;
        currentChat.closing = true;
        exitSelectMessages();
        closeSelDeleteDialog();
        closeChatMoreMenu();
        closeChatMenu();
        closeDeleteDialog();
        closeEditDialog();
        closeEmojiPanel();
        document.body.style.overflow = '';
        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.id = null;

        const panel = document.getElementById('chat-panel');
        container.classList.remove('raliva-chat-in');
        panel.classList.remove('raliva-chat-in-sheet');
        void container.offsetWidth;
        container.classList.add('raliva-chat-out');
        panel.classList.add('raliva-chat-out-sheet');
        setTimeout(function () {
            if (!currentChat.closing) return;
            container.classList.add('hidden');
            container.classList.remove('raliva-chat-out');
            panel.classList.remove('raliva-chat-out-sheet');
            currentChat.closing = false;
        }, 320);
    }

    async function loadMessages() {
        if (!currentChat.id) return;
        const controller = new AbortController();
        const timer = setTimeout(() => controller.abort(), 10000);
        try {
            const url = '{{ route('customer.komplain.messages', ':id:') }}'.replace(':id:', currentChat.id);
            const resp = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                signal: controller.signal
            });
            if (!resp.ok) throw new Error('Gagal memuat pesan');
            const messages = await resp.json();
            renderMessages(messages);
        } catch (err) {
            if (currentChat.id !== null) showChatError(err.name === 'AbortError' ? 'Waktu memuat pesan habis. Coba lagi.' : err.message);
        } finally {
            clearTimeout(timer);
        }
    }

    function showChatError(message) {
        const el = document.getElementById('chat-messages');
        el.innerHTML = '<div class="text-center py-8">' +
            '<p class="text-on-surface-variant text-sm">' + escapeHtml(message) + '</p>' +
            '<p class="text-xs text-on-surface-variant/60 mt-1">Coba muat ulang halaman.</p>' +
            '</div>';
        el.scrollTop = el.scrollHeight;
    }

    function chatMenuMarkup(id, btnColor) {
        return '<span class="relative shrink-0 chat-menu-wrap">' +
            '<button type="button" data-menu-btn="' + id + '" onclick="toggleChatMenu(' + id + ')" class="chat-menu-btn ' + btnColor + ' lg:opacity-0 lg:group-hover:opacity-100 transition-opacity cursor-pointer rounded-full w-7 h-7 flex items-center justify-center" title="…"><span class="material-symbols-outlined text-[17px]">more_horiz</span></button>' +
            '<span data-menu="' + id + '" class="chat-menu hidden absolute right-0 top-full mt-1 min-w-[170px] z-30 rounded-xl border border-outline-variant bg-surface-container-high py-1 shadow-xl">';
    }

    function renderMessages(messages) {
        const el = document.getElementById('chat-messages');
        chatMessages = messages || [];
        if (!messages || messages.length === 0) {
            el.innerHTML = '<div class="text-center py-10">' +
                '<span class="material-symbols-outlined text-[38px] text-outline-variant inline-block mb-2">chat_bubble_outline</span>' +
                '<p class="font-body-sm text-body-sm text-on-surface-variant">' + escapeHtml('Belum ada pesan. Mulai percakapan dengan toko.') + '</p>' +
                '</div>';
            el.scrollTop = el.scrollHeight;
            return;
        }

        el.innerHTML = messages.map(function (m) {
            const mine = m.sender_id === myId;
            const sender = mine ? 'Anda' : (m.sender ? m.sender.nama_lengkap : 'Toko');
            const time = mine ? 'text-white/40' : 'text-on-surface-variant/50';
            const bubble = mine ? 'bg-secondary text-white' : 'bg-surface-container-low';
            const meta = mine ? 'text-white/60' : 'text-on-surface-variant';
            const edited = m.edited_at ? ' <span class="italic">(' + escapeHtml('diedit') + ')</span>' : '';
            const actionsOn = !currentChat.done;
            const selBox = '<span class="chat-sel-box" data-sel-box="' + m.complaint_message_id + '" onclick="event.stopPropagation();toggleSelectMessage(' + m.complaint_message_id + ')" aria-hidden="true"><span class="material-symbols-outlined">check_box_outline_blank</span></span>';
            const rowClass = 'flex items-center gap-2 ' + (mine ? (chatSelMode ? 'justify-between' : 'justify-end') : 'justify-start') + ' group chat-msg';
            const selFirst = selBox;
            const selLast = '';

            if (m.deleted) {
                const delBubble = mine ? 'bg-secondary/20 border-white/25' : 'bg-transparent border-outline-variant';
                const delText = mine ? 'text-white/60' : 'text-on-surface-variant/70';
                const delBtn = mine ? 'text-white/50 hover:text-white' : 'text-on-surface-variant hover:text-on-surface';
                let delMenu = '';
                if (actionsOn) {
                    delMenu = chatMenuMarkup(m.complaint_message_id, delBtn) +
                        '<button type="button" onclick="openDeleteDialog(' + m.complaint_message_id + ',true)" class="w-full text-left px-4 py-2.5 font-body-sm text-body-sm text-error hover:bg-error/10 transition-colors cursor-pointer flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">delete</span>' + escapeHtml('Hapus pesan') + '</button>' +
                        '</span></span>';
                }
                return '<div class="' + rowClass + '" data-mid="' + m.complaint_message_id + '">' +
                    selFirst +
                    '<div class="max-w-[85%] md:max-w-[70%] rounded-xl px-4 py-2 border border-dashed ' + delBubble + '" data-bubble>' +
                    '<div class="flex items-center justify-between gap-2">' +
                    '<p class="font-body-sm text-body-sm italic ' + delText + '">' + escapeHtml('Pesan ini telah dihapus') + '</p>' +
                    delMenu +
                    '</div>' +
                    '<p class="text-[10px] mt-1 ' + time + '">' + formatTime(m.created_at) + '</p>' +
                    '</div>' + selLast + '</div>';
            }

            let menu = '';
            if (mine && actionsOn) {
                const menuItems = chatEditAllowed(m.created_at)
                    ? '<button type="button" onclick="openEditDialog(' + m.complaint_message_id + ')" class="w-full text-left px-4 py-2.5 font-body-sm text-body-sm text-on-surface hover:bg-surface-container-low transition-colors cursor-pointer flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">edit</span>' + escapeHtml('Edit pesan') + '</button>' +
                      '<button type="button" onclick="openDeleteDialog(' + m.complaint_message_id + ',false)" class="w-full text-left px-4 py-2.5 font-body-sm text-body-sm text-error hover:bg-error/10 transition-colors cursor-pointer flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">delete</span>' + escapeHtml('Hapus pesan') + '</button>'
                    : '<button type="button" onclick="openDeleteDialog(' + m.complaint_message_id + ',false)" class="w-full text-left px-4 py-2.5 font-body-sm text-body-sm text-error hover:bg-error/10 transition-colors cursor-pointer flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">delete</span>' + escapeHtml('Hapus pesan') + '</button>';
                menu = chatMenuMarkup(m.complaint_message_id, 'text-white/60 hover:text-white') + menuItems + '</span></span>';
            }

            return '<div class="' + rowClass + '" data-mid="' + m.complaint_message_id + '">' +
                selFirst +
                '<div class="max-w-[85%] md:max-w-[70%] rounded-xl px-4 py-3 ' + bubble + '" data-bubble>' +
                '<div class="flex items-start justify-between gap-2 mb-1">' +
                '<p class="text-xs ' + meta + ' uppercase tracking-wider">' + escapeHtml(sender) + '</p>' +
                menu +
                '</div>' +
                '<p class="font-body-sm text-body-sm whitespace-pre-wrap break-words" data-pesan>' + escapeHtml(m.pesan) + '</p>' +
                '<p class="text-[10px] mt-2 ' + time + '">' + formatTime(m.created_at) + edited + '</p>' +
                '</div>' + selLast + '</div>';
        }).join('');
        applySelectionUI();
        el.scrollTop = el.scrollHeight;
    }

    function formatTime(value) {
        const d = new Date(value);
        if (isNaN(d.getTime())) return '';
        return d.toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    let chatEditMsgId = null;
    let chatMenuId = null;
    let chatMoreOpen = false;
    let deleteDialogMsgId = null;
    let chatMessages = [];

    function chatEditAllowed(createdAt) {
        const t = new Date(createdAt).getTime();
        if (isNaN(t)) return false;
        return (Date.now() - t) <= 15 * 60 * 1000;
    }

    function toggleChatMenu(id) {
        const menu = document.querySelector('[data-menu="' + id + '"]');
        if (!menu) return;
        const opening = menu.classList.contains('hidden');
        closeChatMenu();
        if (opening) {
            closeChatMoreMenu();
            menu.classList.remove('hidden');
            chatMenuId = id;
        }
    }

    function closeChatMenu() {
        if (chatMenuId === null) return;
        const menu = document.querySelector('[data-menu="' + chatMenuId + '"]');
        if (menu) menu.classList.add('hidden');
        chatMenuId = null;
    }

    function toggleChatMoreMenu() {
        const menu = document.getElementById('chat-more-menu');
        if (!menu) return;
        const opening = menu.classList.contains('hidden');
        closeChatMoreMenu();
        if (opening) {
            closeChatMenu();
            menu.classList.remove('hidden');
            chatMoreOpen = true;
        }
    }

    function closeChatMoreMenu() {
        if (!chatMoreOpen) return;
        const menu = document.getElementById('chat-more-menu');
        if (menu) menu.classList.add('hidden');
        chatMoreOpen = false;
    }

    function showChatToast(message) {
        var existing = document.getElementById('chat-toast');
        if (existing) existing.remove();
        var toast = document.createElement('div');
        toast.id = 'chat-toast';
        toast.textContent = message;
        toast.style.cssText = 'position:fixed;left:50%;bottom:96px;transform:translateX(-50%);background:#1c1b1b;color:#fff;padding:10px 18px;border-radius:999px;font-size:13px;font-family:Manrope,sans-serif;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,.25);opacity:0;transition:opacity .3s ease;';
        document.body.appendChild(toast);
        requestAnimationFrame(function () { toast.style.opacity = '1'; });
        setTimeout(function () { toast.style.opacity = '0'; setTimeout(function () { toast.remove(); }, 350); }, 2200);
    }

    let chatSelMode = false;
    let chatSelIds = new Set();

    function openWallpaperPicker() {
        closeChatMoreMenu();
        const input = document.getElementById('chat-wallpaper-input');
        if (input) input.click();
    }

    function applyWallpaper(url, persist) {
        const layer = document.getElementById('chat-messages');
        if (!layer) return;
        layer.style.backgroundImage = url ? "url('" + url.replace(/'/g, "\\'") + "')" : '';
        layer.style.backgroundSize = 'cover';
        layer.style.backgroundPosition = 'center';
        if (persist) {
            try {
                localStorage.setItem('raliva_chat_wallpaper', url || '');
            } catch (_) {}
        }
    }

    function resetWallpaper() {
        applyWallpaper('', true);
        showChatToast('Wallpaper direset ke default.');
    }

    function initWallpaper() {
        let saved = '';
        try { saved = localStorage.getItem('raliva_chat_wallpaper') || ''; } catch (_) {}
        if (saved) applyWallpaper(saved, false);
    }

    document.addEventListener('change', function (ev) {
        if (ev.target && ev.target.id !== 'chat-wallpaper-input') return;
        const file = ev.target.files && ev.target.files[0];
        ev.target.value = '';
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function () {
            applyWallpaper(reader.result, true);
            showChatToast('Wallpaper diperbarui.');
        };
        reader.readAsDataURL(file);
    });

    function selectMessagesMode() {
        closeChatMoreMenu();
        closeChatMenu();
        if (currentChat.done) {
            showChatToast('Pesan komplain yang sudah selesai tidak dapat dipilih.');
            return;
        }
        chatSelMode = true;
        chatSelIds.clear();
        document.getElementById('chat-messages').classList.add('chat-selecting');
        const bar = document.getElementById('chat-select-bar');
        const area = document.getElementById('chat-input-area');
        area.classList.add('chat-selecting');
        bar.classList.remove('animate-out');
        bar.classList.add('animate-in');
        renderMessages(chatMessages);
        applySelectionUI();
    }

    function exitSelectMessages() {
        if (!chatSelMode) return;
        chatSelMode = false;
        chatSelIds.clear();
        const area = document.getElementById('chat-input-area');
        const bar = document.getElementById('chat-select-bar');
        bar.classList.remove('animate-in');
        bar.classList.add('animate-out');
        setTimeout(function () {
            area.classList.remove('chat-selecting');
            bar.classList.remove('animate-out');
        }, 200);
        document.getElementById('chat-messages').classList.remove('chat-selecting');
        renderMessages(chatMessages);
        applySelectionUI();
    }

    function toggleSelectMessage(id) {
        if (!chatSelMode) return;
        id = parseInt(id, 10);
        if (chatSelIds.has(id)) chatSelIds.delete(id); else chatSelIds.add(id);
        applySelectionUI();
    }

    function applySelectionUI() {
        const el = document.getElementById('chat-messages');
        const count = chatSelIds.size;
        const countEl = document.getElementById('chat-sel-count');
        if (countEl) countEl.textContent = count + ' selected';
        if (!el) return;
        Array.prototype.forEach.call(el.querySelectorAll('[data-mid]'), function (row) {
            const id = parseInt(row.getAttribute('data-mid'), 10);
            const selected = chatSelMode && chatSelIds.has(id);
            row.classList.toggle('sel-selected', selected);
            const box = row.querySelector('[data-sel-box]');
            if (box) {
                const icon = box.querySelector('.material-symbols-outlined');
                if (icon) icon.textContent = selected ? 'check_box' : 'check_box_outline_blank';
            }
        });
    }

    function copySelectedMessages() {
        if (chatSelIds.size === 0) { showChatToast('Pilih minimal satu pesan.'); return; }
        const rows = chatMessages.filter(function (m) {
            return chatSelIds.has(parseInt(m.complaint_message_id, 10)) && m.pesan;
        });
        const text = rows.map(function (m) {
            const sender = (m.sender_id === myId) ? 'Anda' : (m.sender ? m.sender.nama_lengkap : 'Toko');
            return '[' + sender + '] ' + formatTime(m.created_at) + '\n' + m.pesan;
        }).join('\n\n');
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function () {
                showChatToast('Pesan tersalin ke clipboard.');
            });
        } else {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            try { document.execCommand('copy'); showChatToast('Pesan tersalin ke clipboard.'); } catch (_) {}
            ta.remove();
        }
    }

    function confirmDeleteSelected() {
        if (chatSelIds.size === 0) { showChatToast('Pilih minimal satu pesan.'); return; }
        const el = document.getElementById('chat-sel-delete-dialog');
        document.getElementById('chat-sel-del-count').textContent = chatSelIds.size + ' pesan';
        el.classList.remove('hidden');
    }

    function closeSelDeleteDialog() {
        document.getElementById('chat-sel-delete-dialog').classList.add('hidden');
    }

    async function deleteSelectedMessages() {
        const ids = Array.from(chatSelIds);
        if (ids.length === 0) return;
        const btn = document.querySelector('#chat-sel-delete-dialog [data-sel-del-ok]');
        if (btn) btn.disabled = true;
        let failed = 0;
        for (const id of ids) {
            try {
                const url = '{{ route('customer.komplain.messages.destroy', [':cid:', ':mid:']) }}'.replace(':cid:', currentChat.id).replace(':mid:', id);
                const resp = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ per: 'me' })
                });
                if (!resp.ok) failed++;
            } catch (_) { failed++; }
        }
        closeSelDeleteDialog();
        if (btn) btn.disabled = false;
        exitSelectMessages();
        loadMessages();
        if (failed === 0) showChatToast(ids.length + ' pesan dihapus untuk diri sendiri.');
        else showChatToast(failed + ' pesan gagal dihapus.');
    }

    function downloadSelectedMessages() {
        showChatToast('Download disiapkan.');
    }

    function openExportChat() {
        closeChatMoreMenu();
        showChatToast('Ekspor Chat disiapkan.');
    }

    function openEditDialog(id) {
        closeChatMenu();
        const msg = chatMessages.find(function (m) {
            return parseInt(m.complaint_message_id, 10) === parseInt(id, 10);
        });
        if (!msg) return;
        chatEditMsgId = id;
        const input = document.getElementById('chat-edit-input');
        const preview = document.getElementById('chat-edit-preview');
        const saveBtn = document.getElementById('chat-edit-save');
        input.value = msg.pesan || '';
        if (preview) preview.textContent = msg.pesan || '';
        input.disabled = false;
        if (saveBtn) saveBtn.disabled = false;
        document.getElementById('chat-edit-dialog').classList.remove('hidden');
        setTimeout(function () {
            input.focus();
            input.setSelectionRange(input.value.length, input.value.length);
        }, 30);
    }

    function closeEditDialog() {
        closeEditEmojiPanel();
        chatEditMsgId = null;
        document.getElementById('chat-edit-dialog').classList.add('hidden');
    }

    async function saveEditMessage() {
        const id = chatEditMsgId;
        if (!id) return;
        const input = document.getElementById('chat-edit-input');
        const pesan = input.value.trim();
        if (pesan.length < 3) { input.focus(); return; }
        input.disabled = true;
        document.getElementById('chat-edit-save').disabled = true;
        try {
            const url = '{{ route('customer.komplain.messages.update', [':cid:', ':mid:']) }}'.replace(':cid:', currentChat.id).replace(':mid:', id);
            const resp = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ pesan })
            });
            if (resp.ok) {
                closeEditDialog();
                await loadMessages();
            } else {
                let msg = 'Gagal menyimpan perubahan';
                try {
                    const data = await resp.json();
                    if (data && data.message) msg = data.message;
                    else if (data && data.errors) msg = Object.values(data.errors).flat().join('\n');
                } catch (_) {}
                alert(msg);
                input.disabled = false;
                document.getElementById('chat-edit-save').disabled = false;
                input.focus();
            }
        } catch (_) {
            input.disabled = false;
            document.getElementById('chat-edit-save').disabled = false;
            input.focus();
        }
    }

    function openDeleteDialog(id, onlyMe) {
        closeChatMenu();
        deleteDialogMsgId = id;
        const optAll = document.getElementById('chat-del-opt-all');
        if (optAll) optAll.classList.toggle('hidden', !!onlyMe);
        document.getElementById('chat-delete-dialog').classList.remove('hidden');
    }

    function closeDeleteDialog() {
        deleteDialogMsgId = null;
        document.getElementById('chat-delete-dialog').classList.add('hidden');
    }

    async function deleteMessage(id, per) {
        if (!id) return;
        const btnEl = document.querySelector('#chat-delete-dialog [data-del-per="' + per + '"]');
        if (btnEl) btnEl.disabled = true;
        try {
            const url = '{{ route('customer.komplain.messages.destroy', [':cid:', ':mid:']) }}'.replace(':cid:', currentChat.id).replace(':mid:', id);
            const resp = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ per })
            });
            closeDeleteDialog();
            if (resp.ok) {
                await loadMessages();
            } else {
                let msg = 'Gagal menghapus pesan';
                try {
                    const data = await resp.json();
                    if (data && data.message) msg = data.message;
                } catch (_) {}
                alert(msg);
            }
        } catch (_) {
            closeDeleteDialog();
        }
    }

    const CHAT_EMOJI = ['😀','😁','😂','🤣','😊','😍','🥰','😘','😚','😜','🤪','😎','🥸','🤗','🤭','🫢','😇','🥺','🤔','🤨','😐','😑','😶','🙄','😏','😮','😯','😪','😴','🤤','😌','😢','😭','😅','😆','😉','🙃','😬','👍','👎','👌','✌️','🤞','🤝','🙏','👏','🙌','💪','🤙','👋','❤️','🧡','💛','💚','💙','💜','🖤','🤍','💖','💘','💯','🔥','✨','⭐','🎉','🎁','🎊','👀'];

    function renderEmojiPanelOf(panelId, inputId) {
        const panel = document.getElementById(panelId);
        if (!panel || panel.dataset.rendered) return;
        panel.dataset.inputId = inputId;
        panel.innerHTML = '<div class="grid grid-cols-8 gap-1">' + CHAT_EMOJI.map(function (e) {
            return '<button type="button" data-emoji="' + e + '" onclick="insertEmojiTo(this)" class="w-9 h-9 flex items-center justify-center text-[20px] leading-none rounded-lg hover:bg-surface-container-low transition-colors cursor-pointer">' + e + '</button>';
        }).join('') + '</div>';
        panel.dataset.rendered = '1';
    }

    function renderEmojiPanel() { renderEmojiPanelOf('chat-emoji-panel', 'chat-input'); }

    function toggleEmojiPanel() {
        renderEmojiPanel();
        const panel = document.getElementById('chat-emoji-panel');
        const btn = document.getElementById('chat-emoji-toggle');
        if (!panel) return;
        const open = panel.classList.toggle('hidden') === false;
        if (btn) {
            btn.classList.toggle('text-secondary', open);
            btn.classList.toggle('bg-surface-container-high', open);
        }
    }

    function closeEmojiPanel() {
        const panel = document.getElementById('chat-emoji-panel');
        if (!panel || panel.classList.contains('hidden')) return;
        panel.classList.add('hidden');
        const btn = document.getElementById('chat-emoji-toggle');
        if (btn) btn.classList.remove('text-secondary', 'bg-surface-container-high');
    }

    function toggleEditEmojiPanel() {
        renderEmojiPanelOf('chat-edit-emoji-panel', 'chat-edit-input');
        const panel = document.getElementById('chat-edit-emoji-panel');
        const btn = document.getElementById('chat-edit-emoji-toggle');
        if (!panel) return;
        const open = panel.classList.toggle('hidden') === false;
        if (btn) {
            btn.classList.toggle('text-secondary', open);
            btn.classList.toggle('bg-surface-container-high', open);
        }
    }

    function closeEditEmojiPanel() {
        const panel = document.getElementById('chat-edit-emoji-panel');
        if (!panel || panel.classList.contains('hidden')) return;
        panel.classList.add('hidden');
        const btn = document.getElementById('chat-edit-emoji-toggle');
        if (btn) btn.classList.remove('text-secondary', 'bg-surface-container-high');
    }

    function insertEmojiTo(btn) {
        const panel = btn.closest('[data-input-id]');
        const input = panel ? document.getElementById(panel.dataset.inputId) : null;
        const emoji = btn.getAttribute('data-emoji');
        if (!input || !emoji) return;
        const start = input.selectionStart != null ? input.selectionStart : input.value.length;
        const end = input.selectionEnd != null ? input.selectionEnd : start;
        const next = input.value.slice(0, start) + emoji + input.value.slice(end);
        input.value = next.slice(0, 2000);
        if (typeof input.dispatchEvent === 'function') {
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }
        const pos = start + emoji.length;
        input.focus();
        input.setSelectionRange(pos, pos);
    }

    document.addEventListener('click', function (ev) {
        if (ev.target.closest) {
            if (chatMoreOpen && !ev.target.closest('#chat-more-wrap')) closeChatMoreMenu();
            if (chatSelMode) {
                const row = ev.target.closest('.chat-msg');
                if (row && !ev.target.closest('[data-sel-box]') && !ev.target.closest('button') && !ev.target.closest('.chat-menu') && !ev.target.closest('#chat-more-wrap')) {
                    toggleSelectMessage(row.getAttribute('data-mid'));
                    return;
                }
            }
            if (chatMenuId !== null) {
                const inMenu = ev.target.closest('[data-menu="' + chatMenuId + '"]') ||
                    ev.target.closest('[data-menu-btn="' + chatMenuId + '"]') ||
                    ev.target.closest('[data-mid="' + chatMenuId + '"]');
                if (!inMenu) closeChatMenu();
            }
            const ep = document.getElementById('chat-edit-emoji-panel');
            if (ep && !ep.classList.contains('hidden') &&
                !ev.target.closest('#chat-edit-emoji-panel') &&
                !ev.target.closest('#chat-edit-emoji-toggle')) {
                closeEditEmojiPanel();
            }
        }
        const panel = document.getElementById('chat-emoji-panel');
        if (!panel || panel.classList.contains('hidden')) return;
        if (ev.target.closest && (ev.target.closest('#chat-emoji-panel') || ev.target.closest('#chat-emoji-toggle'))) return;
        closeEmojiPanel();
    });

    const chatEditInputEl = document.getElementById('chat-edit-input');
    if (chatEditInputEl) {
        chatEditInputEl.addEventListener('input', function () {
            const preview = document.getElementById('chat-edit-preview');
            if (preview) preview.textContent = this.value;
        });
    }

    initWallpaper();

    async function sendMessage() {
        const composer = document.getElementById('chat-composer');
        if (composer.classList.contains('hidden')) return;
        const input = document.getElementById('chat-input');
        const pesan = input.value.trim();
        if (!pesan || !currentChat.id) return;

        document.getElementById('chat-send').disabled = true;
        input.value = '';

        try {
            const url = '{{ route('customer.komplain.messages.store', ':id:') }}'.replace(':id:', currentChat.id);
            const resp = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ pesan })
            });
            if (resp.ok) {
                await loadMessages();
                if (currentChat.done) {
                    currentChat.done = false;
                    document.getElementById('chat-composer').classList.remove('hidden');
                    document.getElementById('chat-closed-note').classList.add('hidden');
                }
            } else {
                input.value = pesan;
                let msg = 'Gagal mengirim pesan';
                try {
                    const data = await resp.json();
                    if (data && data.errors) msg = Object.values(data.errors).flat().join('\n');
                    else if (data && data.message) msg = data.message;
                } catch (_) {}
                alert(msg);
            }
        } catch (_) {
            input.value = pesan;
        } finally {
            document.getElementById('chat-send').disabled = false;
        }
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (chatSelMode) { exitSelectMessages(); return; }
            if (chatMoreOpen) { closeChatMoreMenu(); return; }
            const ep = document.getElementById('chat-edit-emoji-panel');
            if (ep && !ep.classList.contains('hidden')) { closeEditEmojiPanel(); return; }
            const ed = document.getElementById('chat-edit-dialog');
            if (ed && !ed.classList.contains('hidden')) { closeEditDialog(); return; }
            if (chatMenuId !== null) { closeChatMenu(); return; }
            const dialog = document.getElementById('chat-delete-dialog');
            if (dialog && !dialog.classList.contains('hidden')) { closeDeleteDialog(); return; }
            const panel = document.getElementById('chat-emoji-panel');
            if (panel && !panel.classList.contains('hidden')) { closeEmojiPanel(); return; }
            closeChatModal();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        var params = new URLSearchParams(window.location.search);
        var openId = params.get('open');
        if (openId) {
            var card = document.querySelector('[data-open-id="' + openId + '"]');
            if (card) openChatFromCard(card);
        }
    });
</script>
@include('customer._partials.drawer')
</body></html>