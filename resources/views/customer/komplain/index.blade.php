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
    <article data-complaint-card data-open-id="{{ $c->complaint_id }}" data-open-subjek="{{ $c->subjek }}" data-open-kode="#{{ $c->complaint_id }}" data-open-statuslabel="{{ $statusLabel }}" data-open-done="{{ $done ? '1' : '0' }}" onclick="openChatFromCard(this)" class="group flex items-start gap-sm md:gap-md p-md border border-outline-variant rounded-xl cursor-pointer transition-colors hover:border-secondary">
        <div class="w-11 h-11 rounded-full bg-surface-container flex items-center justify-center shrink-0 {{ $done ? '' : 'text-[var(--chrome-accent)]' }}">
            <span class="material-symbols-outlined text-[22px]">{{ $done ? 'task_alt' : 'support_agent' }}</span>
        </div>
        <div class="flex-grow min-w-0">
            <div class="flex flex-wrap items-center justify-between gap-sm">
                <p class="font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">#{{ $c->complaint_id }} • {{ $c->order_id ? '#'.$c->order_id : '-' }}</p>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $done ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }}">{{ $statusLabel }}</span>
            </div>
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
        <span class="shrink-0 self-center inline-flex items-center gap-1 px-3 py-2 rounded-full border border-outline-variant text-on-surface-variant group-hover:border-secondary group-hover:text-secondary transition-colors font-label-caps text-label-caps uppercase tracking-widest">
            <span class="material-symbols-outlined text-[16px]">chat</span>{{ __('Buka') }}
        </span>
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
<!-- Chat Komplain Modal (ala Super Admin; warna RALIVA) -->
<div class="hidden fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm" id="chat-container" onclick="if(event.target===this) closeChatModal()">
    <div class="p-4 lg:p-8 flex items-end justify-end">
        <button type="button" onclick="closeChatModal()" class="p-3 rounded-full bg-surface-container-high/80 text-on-surface hover:bg-surface-container-high transition-colors lg:mt-4 print:hidden" title="{{ __('Tutup') }}">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>
    <div class="flex flex-col bg-surface-container-low border-l border-[var(--border-soft)] lg:h-full overflow-hidden" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between gap-3 px-6 py-4 border-b border-[var(--border-soft)] shrink-0">
            <div class="min-w-0">
                <h3 class="font-title-md text-title-md text-on-surface truncate" id="chat-subject">-</h3>
                <p class="font-mono text-on-surface-variant text-xs mt-0.5" id="chat-kode">-</p>
            </div>
            <span id="chat-status" class="shrink-0 inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border bg-surface-container-high text-on-surface-variant border-outline-variant"></span>
        </div>
        <div class="flex-1 overflow-y-auto px-6 py-6 space-y-4 min-h-0" id="chat-messages">
            <div class="flex justify-center items-center py-8">
                <div class="w-10 h-10 border-4 border-secondary border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-[var(--border-soft)] bg-surface-container-lowest/60 shrink-0" id="chat-input-area">
            <div id="chat-composer" class="flex items-end gap-3">
                <textarea id="chat-input" rows="1" maxlength="2000" placeholder="{{ __('Tulis pesan...') }}"
                    class="flex-1 bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-sm text-body-sm text-on-surface placeholder-on-surface-variant resize-none focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors"
                    onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}"></textarea>
                <button type="button" onclick="sendMessage()" id="chat-send"
                    class="w-12 h-12 flex items-center justify-center bg-secondary text-white shrink-0 hover:opacity-80 transition-opacity disabled:opacity-40 rounded-full">
                    <span class="material-symbols-outlined text-[20px]">send</span>
                </button>
            </div>
            <p id="chat-closed-note" class="hidden text-center font-body-sm text-body-sm text-on-surface-variant pt-4">{{ __('Komplain telah selesai dan tidak dapat dibalas lagi.') }}</p>
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
    let currentChat = { id: null, polling: null, done: false };
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
        document.getElementById('chat-subject').textContent = subjek;
        document.getElementById('chat-kode').textContent = kode;
        const statusEl = document.getElementById('chat-status');
        statusEl.textContent = statusLabel || '';
        statusEl.className = 'shrink-0 inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border ' + (done ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant');
        document.getElementById('chat-composer').classList.toggle('hidden', done);
        document.getElementById('chat-closed-note').classList.toggle('hidden', !done);
        document.getElementById('chat-messages').innerHTML = '<div class="flex justify-center items-center py-8"><div class="w-10 h-10 border-4 border-secondary border-t-transparent rounded-full animate-spin"></div></div>';

        const container = document.getElementById('chat-container');
        container.classList.remove('hidden');
        if (window.innerWidth >= 1024) container.style.display = 'grid';
        container.style.gridTemplateColumns = '1fr 560px';
        document.body.style.overflow = 'hidden';

        loadMessages();
        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.polling = setInterval(loadMessages, 5000);
    }

    function closeChatModal() {
        const container = document.getElementById('chat-container');
        container.classList.add('hidden');
        container.style.display = '';
        container.style.gridTemplateColumns = '';
        document.body.style.overflow = '';
        if (currentChat.polling) clearInterval(currentChat.polling);
        currentChat.id = null;
    }

    async function loadMessages() {
        if (!currentChat.id) return;
        try {
            const url = '{{ route('customer.komplain.messages', ':id:') }}'.replace(':id:', currentChat.id);
            const resp = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!resp.ok) throw new Error('Gagal memuat pesan');
            const messages = await resp.json();
            renderMessages(messages);
        } catch (err) {
            showChatError(err.message);
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

    function renderMessages(messages) {
        const el = document.getElementById('chat-messages');
        el.innerHTML = messages.map(m => {
            const mine = m.sender_id === myId;
            const bubble = mine ? 'bg-secondary text-white' : 'bg-surface-container-low';
            const meta = mine ? 'text-white/60' : 'text-on-surface-variant';
            const time = mine ? 'text-white/40' : 'text-on-surface-variant/50';
            const sender = mine ? 'Anda' : (m.sender ? m.sender.nama_lengkap : 'Toko');
            return '<div class="flex ' + (mine ? 'justify-end' : 'justify-start') + '">' +
                '<div class="max-w-[85%] md:max-w-[70%] rounded-xl px-4 py-3 ' + bubble + '">' +
                '<p class="text-xs mb-1 ' + meta + ' uppercase tracking-wider">' + escapeHtml(sender) + '</p>' +
                '<p class="font-body-sm text-body-sm whitespace-pre-wrap break-words">' + escapeHtml(m.pesan) + '</p>' +
                '<p class="text-[10px] mt-2 ' + time + '">' + formatTime(m.created_at) + '</p>' +
                '</div></div>';
        }).join('');
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

    async function sendMessage() {
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

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeChatModal(); });

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