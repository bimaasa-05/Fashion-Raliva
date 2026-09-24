<!DOCTYPE html>
<html class="light" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Pesanan Berhasil') }}</title>
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
<style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-outlined[data-weight="fill"] { font-variation-settings: 'FILL' 1; }
    </style>
<style> body { min-height: max(884px, 100dvh); } </style>
<style>
        :root { --chrome-bg: #ffffff; --chrome-bg-soft: rgba(255,255,255,.92); --chrome-text: #1b1c1c; --chrome-text-dim: rgba(0,0,0,.55); --chrome-text-faint: rgba(0,0,0,.45); --chrome-border: rgba(0,0,0,.1); --chrome-hover: rgba(0,0,0,.06); --chrome-accent: #8B1E3F; --surface-ivory: #F8F6F2; --surface-warm: #F3F0EA; --border-soft: #E5E1DA; --text-muted: #777777; }
    html.theme-dark { --chrome-bg: #1c1b1b; --chrome-bg-soft: rgba(28,27,27,.9); --chrome-text: #ffffff; --chrome-text-dim: rgba(255,255,255,.6); --chrome-text-faint: rgba(255,255,255,.5); --chrome-border: rgba(255,255,255,.1); --chrome-hover: rgba(255,255,255,.1); --chrome-accent: #8B1E3F; --surface-ivory: #1e1d1c; --surface-warm: #201f1e; --border-soft: rgba(255,255,255,.1); --text-muted: #b9b6b1; }
</style>
<style>
    html.theme-dark .bg-background, html.theme-dark .bg-surface, html.theme-dark .bg-surface-bright { background-color: #161514 !important; }
    html.theme-dark .bg-surface-container-lowest { background-color: #1e1d1c !important; }
    html.theme-dark .bg-surface-container-low { background-color: #201f1e !important; }
    html.theme-dark .text-on-surface, html.theme-dark .text-on-background { color: #e6e4e1 !important; }
    html.theme-dark .text-on-surface-variant { color: #b9b6b1 !important; }
    html.theme-dark .border-outline-variant { border-color: #3a3937 !important; }
</style>
<style>
    .card-premium { box-shadow:0 1px 2px rgb(17 17 17 / .04),0 12px 32px -16px rgb(17 17 17 / .16); transition:transform .25s ease,box-shadow .25s ease,border-color .25s ease; }
    .card-premium:hover { box-shadow:0 2px 4px rgb(17 17 17 / .05),0 20px 48px -20px rgb(17 17 17 / .22); border-color:rgba(139,30,63,.45); }
    html.theme-dark .card-premium { background-color:var(--surface-ivory); border-color:var(--border-soft); }
    .premium-heading::before { content:''; display:inline-block; width:4px; height:.95em; margin-right:.65rem; background:#8B1E3F; border-radius:9999px; vertical-align:-.05em; }
    .atl-eyebrow { display:inline-flex; align-items:center; gap:.65rem; }
    .atl-eyebrow::before { content:''; width:30px; height:1px; background:var(--chrome-accent); opacity:.7; }
    .reveal-up { opacity:0; transform:translateY(12px); transition:opacity .5s ease,transform .5s ease; }
    .reveal-up.is-visible { opacity:1; transform:none; }
    .btn-gold { position: relative; overflow: hidden; background-color: var(--btn-gold-bg) !important; color: var(--btn-gold-text) !important; }
    .btn-gold::after { content:''; position:absolute; top:-10%; bottom:-10%; left:-80%; width:45%; background: rgba(255,255,255,.55); transform:skewX(-24deg); pointer-events:none; }
    .btn-gold:hover::after { animation: authFlash 1.4s linear infinite; }
    @keyframes authFlash { from { left:-80%; } to { left:135%; } }
    :root { --btn-gold-bg:#8B1E3F; --btn-gold-text:#ffffff; } html.theme-dark { --btn-gold-bg:#6D1428; --btn-gold-text:#ffffff; }
    #drawer-panel { --chrome-accent:#8B1E3F; --gold-wash:rgba(139,30,63,.10); }
    .co-stepper { display:flex; align-items:center; justify-content:center; gap:.5rem; }
    .co-step { display:flex; align-items:center; gap:.45rem; font-family:'Manrope',sans-serif; font-size:11px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; }
    .co-step .num { width:28px; height:28px; border-radius:9999px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; border:1.5px solid var(--border-soft); background: var(--surface-warm); color: var(--text-muted); }
    .co-step.done .num, .co-step.active .num { background:#8B1E3F; border-color:#8B1E3F; color:#fff; }
    .co-step.active { color:#8B1E3F; } .co-step:not(.active):not(.done) { color: var(--text-muted); }
    .co-step-line { width:32px; height:1px; background:var(--border-soft); } .co-step-line.done { background:#8B1E3F; }

    /* Spinner loading untuk step belum dicapai */
    .co-step .num.loading {
        border: 2px solid var(--border-soft);
        border-top-color: #8B1E3F;
        background: transparent !important;
        color: transparent !important;
        animation: co-spin 0.75s linear infinite;
    }
    .co-step .num.loading::after { content:''; display:none; }
    @keyframes co-spin { to { transform: rotate(360deg); } }

    /* ---- spinner menunggu verifikasi ---- */
    .co-wait-spin { width: 2.5rem; height: 2.5rem; border-radius: 9999px; border: 3.5px solid rgba(16,185,129,.25); border-top-color: #10B981; animation: co-spin .8s linear infinite; }

    /* ---- success check icon: filled circle pop + stroke draw ---- */
    .co-success-wrap { animation: co-wrap-pop .5s cubic-bezier(.34,1.3,.5,1) both; }
    .co-success-svg { display:block; width:72px; height:72px; }
    .co-success-ring-circle { transform: rotate(-90deg); transform-origin: center; }
    .co-success-ring-circle .co-ring-path { stroke-dasharray: 226; stroke-dashoffset: 226; animation: co-draw-ring .5s ease-in-out .2s forwards; }
    .co-success-check { stroke-dasharray: 48; stroke-dashoffset: 48; animation: co-draw-check .45s cubic-bezier(.45,.05,.4,.95) .68s forwards; }
    @keyframes co-wrap-pop {
        0%   { transform: scale(.4); opacity: 0; }
        60%  { transform: scale(1.06); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes co-draw-ring { to { stroke-dashoffset: 0; } }
    @keyframes co-draw-check { to { stroke-dashoffset: 0; } }

    /* Step yang sudah selesai (done) bisa diklik untuk kembali */
    .co-step.done { cursor:pointer; text-decoration:none; transition: opacity .2s ease; }
    .co-step.done:hover { opacity: .75; }

    /* ===== mobile bottom-sheet collapsible ===== */
    .co-bottom-bar { position:fixed; bottom:0; left:0; right:0; z-index:50;
        display:flex; flex-direction:column; align-items:stretch; gap:0; padding:0;
        background:var(--chrome-bg-soft); border-top:1px solid var(--chrome-border);
        backdrop-filter:blur(12px) saturate(1.4); -webkit-backdrop-filter:blur(12px) saturate(1.4); }
    @media(min-width:1024px){ .co-bottom-bar{ display:none!important; } }
    .co-bb-toggle { display:flex; align-items:center; justify-content:space-between;
        gap:.75rem; padding:.625rem 1.25rem; cursor:pointer; background:transparent;
        border:0; width:100%; text-align:left; font-family:'Manrope',sans-serif; }
    .co-bb-toggle span:first-child { color:var(--text-muted); }
    .co-bb-chev { display:inline-flex; align-items:center; justify-content:center;
        width:28px; height:28px; border-radius:9999px;
        background:var(--chrome-bg-soft); border:1px solid var(--border-soft);
        color:var(--text-muted); font-variation-settings:'FILL' 0,'wght' 500;
        transition:transform .3s cubic-bezier(.4,0,.2,1),background .25s,border-color .25s,color .25s;
        transform:rotate(180deg); }
    .co-bb-toggle:hover .co-bb-chev { border-color:var(--chrome-accent); color:var(--chrome-accent); }
    .co-bottom-bar.open .co-bb-chev { transform:rotate(0deg);
        background:var(--chrome-accent); border-color:var(--chrome-accent); color:#fff;
        font-variation-settings:'FILL' 1,'wght' 600; }
    .co-bb-panel { max-height:0; overflow:hidden;
        transition:max-height .32s cubic-bezier(.4,0,.2,1);
        padding-left:1.25rem; padding-right:1.25rem; }
    .co-bottom-bar.open .co-bb-panel { max-height:100vh; overflow-y:auto; padding-bottom:.5rem; }
    .co-bb-rows { display:flex; flex-direction:column; gap:.4rem; padding:.625rem 0; }
    .co-bb-store + .co-bb-rows { border-top:1px dashed var(--border-soft); }

    /* ---- daftar produk dalam panel ---- */
    .co-bb-store { padding:.75rem 0 1rem; }
    .co-bb-store + .co-bb-store { padding-top:0; border-top:1px dashed var(--border-soft); }
    .co-bb-store-name { font-family:'Manrope',sans-serif; font-size:12px; font-weight:700;
        letter-spacing:.06em; text-transform:uppercase; color:var(--chrome-accent); margin-bottom:.5rem; }
    .co-bb-item { display:flex; align-items:flex-start; justify-content:space-between;
        gap:.75rem; padding:.4rem 0; }
    .co-bb-item + .co-bb-item { border-top:1px dashed var(--border-soft); }
    .co-bb-item-name { font-family:'Manrope',sans-serif; font-size:13px; font-weight:600; color:var(--on-surface); line-height:1.3; }
    .co-bb-item-note { font-size:11px; color:var(--text-muted); }
    .co-bb-item-qty { font-size:12px; color:var(--text-muted); margin-top:.15rem; }
    .co-bb-item-total { font-family:'Manrope',sans-serif; font-size:13px; font-weight:600; color:var(--on-surface); white-space:nowrap; }
    .co-bb-row { display:flex; align-items:center; justify-content:space-between;
        font-family:'Manrope',sans-serif; font-size:13px; color:var(--on-surface); }
    .co-bb-row.total { padding-top:.6rem; margin-top:.2rem; border-top:1px dashed var(--border-soft); font-weight:700; }
    .co-bottom-bar .co-bb-foot { display:flex; align-items:center; justify-content:space-between;
        gap:.75rem; padding:.75rem 1.25rem calc(.75rem + env(safe-area-inset-bottom)); }
    .co-bottom-bar .co-bb-foot .summary { flex:1 1 0%; min-width:0; }
    .co-bottom-bar .co-bb-foot .summary p:last-child { font-size:15px; }
</style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col lg:pl-72">
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
    <a href="{{ route('customer.home') }}" aria-label="Home" class="p-2 -ml-2 hover:opacity-70 flex"><span class="material-symbols-outlined">home</span></a>
    <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[220px] text-center">{{ __('Selesai') }}</h1>
    <div class="w-10"></div>
</header>
<main class="pt-6 pb-[128px] w-full overflow-x-hidden">
    <div class="mx-auto max-w-[1400px] px-container-margin">
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-sm mb-md flex justify-center reveal-up">
<div class="co-stepper">
            <a href="{{ route('customer.checkout') }}" class="co-step done"><span class="num"><span class="material-symbols-outlined text-[14px]">check</span></span> {{ __('Review') }}</a>
            <span class="co-step-line done"></span>
            <a href="{{ route('customer.checkout.payment', $checkout->checkout_id) }}" class="co-step done"><span class="num"><span class="material-symbols-outlined text-[14px]">check</span></span> {{ __('Bayar') }}</a>
            <span class="co-step-line done"></span>
            <span class="co-step active"><span class="num">3</span> {{ __('Selesai') }}</span>
        </div>
        </div>

        @php
            $akunBaru = session('akun_baru');
            $payStatus = $payment->status ?? null;
            $isVerified = $payStatus === \App\Models\Payment::STATUS_TERVERIFIKASI;
            $isRejected = in_array($payStatus, [\App\Models\Payment::STATUS_DITOLAK, \App\Models\Payment::STATUS_KADALUARSA], true);
            $statusLabels = [
                \App\Models\Payment::STATUS_PENDING => ['Menunggu Pembayaran', 'bg-amber-100 text-amber-800'],
                \App\Models\Payment::STATUS_MENUNGGU_VERIFIKASI => ['Menunggu Verifikasi', 'bg-blue-100 text-blue-800'],
                \App\Models\Payment::STATUS_TERVERIFIKASI => ['Terverifikasi', 'bg-emerald-100 text-emerald-800'],
                \App\Models\Payment::STATUS_DITOLAK => ['Ditolak', 'bg-red-100 text-red-800'],
                \App\Models\Payment::STATUS_KADALUARSA => ['Kadaluarsa', 'bg-surface-container text-on-surface-variant'],
            ];
            $statusLabel = $statusLabels[$payStatus][0] ?? ucfirst((string) $payStatus);
            $statusClass = $statusLabels[$payStatus][1] ?? 'bg-surface-container text-on-surface-variant';
        @endphp

        {{-- === SUKSES HEADER === --}}
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-2xl p-md md:p-xl card-premium text-center reveal-up overflow-hidden">
            <div id="pay-icon-wrap" class="co-success-wrap relative mx-auto mb-md w-20 h-20 rounded-full
                @if($isVerified) bg-emerald-100 items-center justify-center
                @elseif($isRejected) bg-error/10 items-center justify-center
                @else bg-emerald-100 items-center justify-center
                @endif flex">
                @if($isVerified)
                    <svg class="co-success-svg" viewBox="0 0 80 80" fill="none" aria-hidden="true">
                        <g class="co-success-ring-circle">
                            <circle class="co-ring-path" cx="40" cy="40" r="36" stroke="#10B981" stroke-width="3.5" stroke-linecap="round"/>
                        </g>
                        <path class="co-success-check" d="M28 41 L36.5 49.5 L53 32" stroke="#10B981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                @elseif($isRejected)
                    <div class="w-10 h-10 rounded-full bg-error/15 inline-flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[26px] text-error">gpp_bad</span>
                    </div>
                @else
                    <span class="co-wait-spin" role="status" aria-label="{{ __('Menunggu verifikasi') }}"></span>
                @endif
            </div>
            <h2 id="pay-title" class="font-headline-md text-headline-md text-on-surface">
                @if($isVerified)
                    {{ __('Pesanan Berhasil!') }}
                @elseif($isRejected)
                    {{ __('Pembayaran belum berhasil') }}
                @else
                    {{ __('Sedang diverifikasi…') }}
                @endif
            </h2>
            <p id="pay-desc" class="font-body-sm text-body-sm text-on-surface-variant mt-sm max-w-xl mx-auto">
                @if($isVerified)
                    {{ __('Pembayaran telah diverifikasi. Pesananmu segera diproses.') }}
                @elseif($isRejected)
                    {{ __('Pembayaranmu ditolak atau melewati batas waktu. Silakan lakukan pembayaran ulang sebelum pesanan dibatalkan.') }}
                @else
                    {{ __('Tunggu sebentar, pesanan Anda sedang diverifikasi.') }}
                @endif
            </p>
            @if($isRejected)
            <div class="mt-md flex justify-center">
                <a href="{{ route('customer.checkout.payment', $checkout->checkout_id) }}" class="btn-gold inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                    <span class="material-symbols-outlined text-[18px]">payments</span> {{ __('Bayar Ulang') }}
                </a>
            </div>
            @endif
        </div>

        {{-- === DETAIL PESANAN LENGKAP === --}}
        <div class="mt-lg max-w-3xl mx-auto space-y-md">

            {{-- Header: Nomor Order & Tanggal --}}
            <div class="bg-surface-container-low border border-outline-variant rounded-xl p-md reveal-up">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-xs">
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Nomor Pesanan') }}</p>
                        @foreach($checkout->orders as $o)
                            <p class="font-body-lg text-body-lg font-semibold text-on-surface">{{ $o->nomor_order }}</p>
                        @endforeach
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Tanggal') }}</p>
                        <p class="font-body-lg text-body-lg font-semibold text-on-surface">{{ $checkout->created_at ? $checkout->created_at->format('d M Y, H:i') : '—' }}</p>
                    </div>
                </div>
                @if($checkout->email_pelanggan)
                <div class="mt-sm pt-sm border-t border-[var(--border-soft)]">
                    <p class="font-label-sm text-label-sm text-on-surface-variant/70">{{ __('Email pemesan') }}: {{ $checkout->email_pelanggan }}</p>
                </div>
                @endif
            </div>

            {{-- Grid: Metode Pembayaran + Alamat Pengiriman --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                {{-- Metode Pembayaran --}}
                <div class="border border-outline-variant rounded-lg p-md reveal-up">
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider flex items-center gap-1.5 mb-sm">
                        <span class="material-symbols-outlined text-[16px]">payments</span> {{ __('Metode Pembayaran') }}
                    </p>
                    <p class="font-body-lg text-body-lg font-semibold text-on-surface">{{ $payment->paymentMethod?->nama_metode ?? '—' }}</p>
                    @if($payment->account)
                    <div class="mt-sm space-y-1">
                        <p class="text-sm text-on-surface-variant"><span class="font-medium text-on-surface">{{ $payment->account->nama_pemilik ?? $payment->account->nama }}</span></p>
                        @if($payment->account->nomor_rekening)
                        <p class="text-sm text-on-surface-variant font-mono">{{ $payment->account->nomor_rekening }}</p>
                        @endif
                    </div>
                    @endif
                    <div class="mt-sm pt-sm border-t border-[var(--border-soft)]">
                        <p class="text-xs text-on-surface-variant/70 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                            {{ __('Status') }}:
                            <span
                                id="pay-status-badge"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold ml-1 {{ $statusClass }}">{{ $statusLabel }}</span>
                        </p>
                    </div>
                </div>

                {{-- Alamat Pengiriman --}}
                <div class="border border-outline-variant rounded-lg p-md reveal-up">
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider flex items-center gap-1.5 mb-sm">
                        <span class="material-symbols-outlined text-[16px]">local_shipping</span> {{ __('Alamat Pengiriman') }}
                    </p>
                    <p class="font-body-lg text-body-lg font-semibold text-on-surface">{{ $checkout->nama_penerima }}</p>
                    <div class="mt-sm space-y-0.5 text-sm text-on-surface-variant">
                        <p>{{ $checkout->nomor_telepon }}</p>
                        <p>{{ $checkout->alamat }}</p>
                        <p>{{ $checkout->kota }}, {{ $checkout->provinsi }}{{ $checkout->kode_pos ? ', ' . $checkout->kode_pos : '' }}</p>
                    </div>
                </div>
            </div>

            {{-- Daftar Produk per Toko (desktop; di mobile ada di sticky footer) --}}
            @foreach($checkout->orders as $order)
            <div class="hidden lg:block border border-outline-variant rounded-lg p-md reveal-up">
                <p class="font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-sm flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">storefront</span>
                    {{ $order->store?->nama_toko ?? __('Toko') }}
                </p>
                @if($order->items && $order->items->count() > 0)
                <div class="divide-y divide-outline-variant/50">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-start py-3 gap-sm">
                        <div class="min-w-0">
                            <p class="font-medium text-on-surface text-sm leading-snug">{{ $item->nama_produk_snapshot }}</p>
                            @if($item->catatan_custom)
                            <p class="text-xs text-on-surface-variant mt-0.5 italic">{{ $item->catatan_custom }}</p>
                            @endif
                            <p class="text-sm text-on-surface-variant mt-1">{{ $item->quantity }} × Rp {{ number_format((float)$item->harga_snapshot, 0,',','.') }}</p>
                        </div>
                        <p class="font-semibold text-on-surface text-sm whitespace-nowrap">Rp {{ number_format((float)$item->total, 0,',','.') }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-on-surface-variant italic py-3">{{ __('Tidak ada item.') }}</p>
                @endif
            </div>
            @endforeach

            {{-- Rincian Biaya (desktop; di mobile ada di sticky footer) --}}
            <div class="hidden lg:block bg-surface-container-low border border-outline-variant rounded-xl p-md reveal-up">
                <p class="font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-sm">{{ __('Rincian Biaya') }}</p>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">{{ __('Subtotal Produk') }}</span>
                        <span class="text-on-surface">Rp {{ number_format((float)$checkout->subtotal, 0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">{{ __('Ongkos Kirim') }}</span>
                        <span class="text-on-surface">Rp {{ number_format((float)$checkout->total_ongkir, 0,',','.') }}</span>
                    </div>
                    @if($checkout->total_diskon > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">{{ __('Diskon') }}</span>
                        <span class="text-emerald-600 font-medium">− Rp {{ number_format((float)$checkout->total_diskon, 0,',','.') }}</span>
                    </div>
                    @endif
                    @if($checkout->total_pajak > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">{{ __('Pajak') }}</span>
                        <span class="text-on-surface">Rp {{ number_format((float)$checkout->total_pajak, 0,',','.') }}</span>
                    </div>
                    @endif
                    @if($checkout->biaya_layanan > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">{{ __('Biaya Layanan') }}</span>
                        <span class="text-on-surface">Rp {{ number_format((float)$checkout->biaya_layanan, 0,',','.') }}</span>
                    </div>
                    @endif
                    @if ((float) $payment->jumlah_saldo > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">{{ __('Dibayar Saldo') }}</span>
                        <span class="text-emerald-600 font-medium">Rp {{ number_format((float)$payment->jumlah_saldo, 0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-on-surface-variant">{{ __('Transfer (') . ($payment->paymentMethod?->nama_metode ?? __('Metode Kedua')) . ')' }}</span>
                        <span class="text-on-surface">Rp {{ number_format((float)$payment->sisa_transfer, 0,',','.') }}</span>
                    </div>
                    @endif
                    <div class="h-px bg-[var(--border-soft)] my-2"></div>
                    <div class="flex justify-between">
                        <span class="font-semibold text-on-surface">{{ __('Total Dibayar') }}</span>
                        <span class="font-title-md text-title-md font-bold text-[var(--chrome-accent)]">Rp {{ number_format((float)$payment->jumlah, 0,',','.') }}</span>
                    </div>
                </div>
                <p class="font-label-sm text-label-sm text-on-surface-variant/70 mt-sm">{{ __('Nomor order disimpan. Gunakan untuk lacak resi.') }}</p>
            </div>

        </div> {{-- end max-w-3xl space-y-md --}}

        {{-- === BANNER AKUN BARU === --}}
        @if($akunBaru)
        <div class="mt-lg max-w-3xl mx-auto bg-emerald-50 border border-emerald-200 rounded-xl p-md text-left flex gap-sm reveal-up">
            <span class="material-symbols-outlined text-emerald-600 shrink-0">key</span>
            <div>
                <p class="font-body-sm text-body-sm font-semibold text-emerald-800">{{ __('Akun berhasil dibuat') }}</p>
                <p class="font-body-sm text-body-sm text-emerald-700 mt-xs">{{ __('Email') }}: <strong>{{ $akunBaru }}</strong> • {{ __('Password') }}: <strong>Raliva123</strong></p>
                <p class="font-label-sm text-label-sm text-emerald-700/80 mt-xs">{{ __('Segera ganti password untuk keamanan.') }} <a href="{{ route('customer.account.password') }}" class="underline font-semibold">{{ __('Ganti Password') }}</a></p>
            </div>
        </div>
        @endif

        {{-- === TOMBOL AKSI (desktop; di mobile ada di sticky footer) === --}}
        <div class="hidden lg:flex mt-lg max-w-3xl mx-auto flex flex-col sm:flex-row gap-sm justify-center reveal-up">
            <a href="{{ route('customer.shop') }}" class="btn-gold inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                <span class="material-symbols-outlined text-[18px]">storefront</span> {{ __('Lanjut Belanja') }}
            </a>
            <a href="{{ route('customer.order-tracking') }}" class="inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full border border-outline-variant font-label-caps text-label-caps uppercase tracking-widest hover:border-[var(--chrome-accent)] hover:text-[var(--chrome-accent)] transition-colors">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span> {{ __('Lacak Pesanan') }}
            </a>
        </div>
    </div>

    {{-- ===== STICKY FOOTER MOBILE: Rincian Pesanan ===== --}}
    <div class="co-bottom-bar lg:hidden" id="co-bottom-bar">
        <button type="button" class="co-bb-toggle" id="co-bb-toggle" aria-expanded="false" aria-controls="co-bb-panel">
            <span class="inline-flex items-center gap-2 font-label-caps text-label-caps uppercase tracking-widest text-on-surface-variant">
                <span class="material-symbols-outlined text-[16px]">tune</span>
                {{ __('Rincian Pesanan') }}
            </span>
            <span class="material-symbols-outlined co-bb-chev text-[18px]">expand_more</span>
        </button>

        <div class="co-bb-panel" id="co-bb-panel" aria-hidden="true">
            {{-- Daftar Produk --}}
            @foreach($checkout->orders as $order)
            <div class="co-bb-store">
                <p class="co-bb-store-name flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[14px]">storefront</span>
                    {{ $order->store?->nama_toko ?? __('Toko') }}
                </p>
                @if($order->items && $order->items->count() > 0)
                <div>
                    @foreach($order->items as $item)
                    <div class="co-bb-item">
                        <div>
                            <p class="co-bb-item-name">{{ $item->nama_produk_snapshot }}</p>
                            @if($item->catatan_custom)
                            <p class="co-bb-item-note italic">{{ $item->catatan_custom }}</p>
                            @endif
                            <p class="co-bb-item-qty">{{ $item->quantity }} × Rp {{ number_format((float)$item->harga_snapshot, 0, ',', '.') }}</p>
                        </div>
                        <p class="co-bb-item-total">Rp {{ number_format((float)$item->total, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach

            <div class="co-bb-rows">
                <div class="co-bb-row"><span>{{ __('Subtotal') }}</span><span>Rp {{ number_format((float)$checkout->subtotal, 0, ',', '.') }}</span></div>
                <div class="co-bb-row"><span>{{ __('Ongkos Kirim') }}</span><span>Rp {{ number_format((float)$checkout->total_ongkir, 0, ',', '.') }}</span></div>
                @if($checkout->total_diskon > 0)
                <div class="co-bb-row"><span>{{ __('Diskon') }}</span><span class="text-emerald-600">− Rp {{ number_format((float)$checkout->total_diskon, 0, ',', '.') }}</span></div>
                @endif
                @if($checkout->total_pajak > 0)
                <div class="co-bb-row"><span>{{ __('Pajak') }}</span><span>Rp {{ number_format((float)$checkout->total_pajak, 0, ',', '.') }}</span></div>
                @endif
                @if($checkout->biaya_layanan > 0)
                <div class="co-bb-row"><span>{{ __('Biaya Layanan') }}</span><span>Rp {{ number_format((float)$checkout->biaya_layanan, 0, ',', '.') }}</span></div>
                @endif
                @if ((float) $payment->jumlah_saldo > 0)
                <div class="co-bb-row"><span>{{ __('Dibayar Saldo') }}</span><span class="text-emerald-600">Rp {{ number_format((float)$payment->jumlah_saldo, 0, ',', '.') }}</span></div>
                <div class="co-bb-row"><span>{{ __('Transfer (') . ($payment->paymentMethod?->nama_metode ?? __('Metode Kedua')) . ')' }}</span><span>Rp {{ number_format((float)$payment->sisa_transfer, 0, ',', '.') }}</span></div>
                @endif
                <div class="co-bb-row total"><span>{{ __('Total Dibayar') }}</span><span>Rp {{ number_format((float)$payment->jumlah, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <div class="co-bb-foot">
            <div class="summary">
                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Total Dibayar') }}</p>
                <p class="font-body-lg text-body-lg font-semibold text-on-surface">Rp {{ number_format((float)$payment->jumlah, 0, ',', '.') }}</p>
            </div>
            <div class="shrink-0 flex items-center gap-sm">
                <a href="{{ route('customer.order-tracking') }}" class="inline-flex items-center justify-center gap-1.5 px-sm py-3 rounded-full border border-outline-variant font-label-caps text-label-caps uppercase tracking-widest hover:border-[var(--chrome-accent)] hover:text-[var(--chrome-accent)] transition-colors">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                </a>
                <a href="{{ route('customer.shop') }}" class="btn-gold shrink-0 inline-flex items-center justify-center gap-1 px-sm py-2.5 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                    <span class="material-symbols-outlined text-[16px]">storefront</span>
                    {{ __('Lanjut Belanja') }}
                </a>
            </div>
        </div>
    </div>

</main>
@include('customer._partials.drawer')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var els = document.querySelectorAll('.reveal-up');
        if (!('IntersectionObserver' in window)) { els.forEach(function (e) { e.classList.add('is-visible'); }); return; }
        var io = new IntersectionObserver(function (entries) { entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } }); }, { threshold: 0.08 });
        els.forEach(function (e) { io.observe(e); });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var bbToggle = document.getElementById('co-bb-toggle');
        var bbPanel  = document.getElementById('co-bb-panel');
        if (bbToggle && bbPanel) {
            bbToggle.addEventListener('click', function () {
                var open = bbPanel.classList.toggle('open');
                bbToggle.classList.toggle('open', open);
                var bbBar = bbToggle.closest('.co-bottom-bar');
                if (bbBar) bbBar.classList.toggle('open', open);
                bbToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                bbPanel.setAttribute('aria-hidden', open ? 'false' : 'true');
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var isVerified = @json($isVerified);
        var isRejected = @json($isRejected);
        if (isVerified) return;

        var endpoint = @json(route('customer.checkout.payment.status', $checkout->checkout_id));
        var payAgainUrl = @json(route('customer.checkout.payment', $checkout->checkout_id));
        var iconWrap = document.getElementById('pay-icon-wrap');
        var titleEl = document.getElementById('pay-title');
        var descEl = document.getElementById('pay-desc');
        var statusEl = document.getElementById('pay-status-badge');
        var interval = null;
        var transitioning = false;

        var checkSvg = '<svg class="co-success-svg" viewBox="0 0 80 80" fill="none" aria-hidden="true">' +
            '<g class="co-success-ring-circle"><circle class="co-ring-path" cx="40" cy="40" r="36" stroke="#10B981" stroke-width="3.5" stroke-linecap="round"/></g>' +
            '<path class="co-success-check" d="M28 41 L36.5 49.5 L53 32" stroke="#10B981" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

        var rejectedSvg = '<div class="w-10 h-10 rounded-full bg-error/15 inline-flex items-center justify-center shrink-0">' +
            '<span class="material-symbols-outlined text-[26px] text-error">gpp_bad</span></div>';

        var badgeMap = {
            'pending': ['Menunggu Pembayaran', 'bg-amber-100 text-amber-800'],
            'menunggu_verifikasi': ['Menunggu Verifikasi', 'bg-blue-100 text-blue-800'],
            'terverifikasi': ['Terverifikasi', 'bg-emerald-100 text-emerald-800'],
            'ditolak': ['Ditolak', 'bg-red-100 text-red-800'],
            'kadaluarsa': ['Kadaluarsa', 'bg-surface-container text-on-surface-variant']
        };

        function setUi(mode, statusKey) {
            if (!iconWrap || transitioning) return;
            if (mode === 'verified') {
                iconWrap.className = 'co-success-wrap relative mx-auto mb-md w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center';
                iconWrap.innerHTML = checkSvg;
                if (titleEl) titleEl.textContent = '{{ __('Pesanan Berhasil!') }}';
                if (descEl) descEl.textContent = '{{ __('Pembayaran telah diverifikasi. Pesananmu segera diproses.') }}';
            } else if (mode === 'rejected') {
                iconWrap.className = 'co-success-wrap relative mx-auto mb-md w-20 h-20 rounded-full bg-error/10 flex items-center justify-center';
                iconWrap.innerHTML = rejectedSvg;
                if (titleEl) titleEl.textContent = '{{ __('Pembayaran belum berhasil') }}';
                if (descEl) descEl.textContent = '{{ __('Pembayaranmu ditolak atau melewati batas waktu. Silakan lakukan pembayaran ulang sebelum pesanan dibatalkan.') }}';
                if (descEl && !document.getElementById('pay-again-btn')) {
                    var btn = document.createElement('a');
                    btn.id = 'pay-again-btn';
                    btn.href = payAgainUrl;
                    btn.className = 'btn-gold inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest mt-md';
                    btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">payments</span> {{ __('Bayar Ulang') }}';
                    var wrap = document.createElement('div');
                    wrap.className = 'mt-md flex justify-center';
                    wrap.appendChild(btn);
                    descEl.parentNode.appendChild(wrap);
                }
            }
            if (statusEl && badgeMap[statusKey]) {
                statusEl.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold ml-1 ' + badgeMap[statusKey][1];
                statusEl.textContent = badgeMap[statusKey][0];
            }
        }

        function poll() {
            fetch(endpoint, { headers: { 'Accept': 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    if (!d || typeof d.verified === 'undefined') return;
                    if (d.verified) {
                        transitioning = true;
                        if (interval) { clearInterval(interval); interval = null; }
                        setUi('verified', 'terverifikasi');
                        setTimeout(function () { window.location.reload(); }, 2400);
                    } else if ((d.status === 'ditolak' || d.status === 'kadaluarsa') && !isRejected) {
                        isRejected = true;
                        setUi('rejected', d.status);
                    }
                })
                .catch(function () {});
        }

        var onVisibility = function () {
            if (document.hidden) {
                if (interval) { clearInterval(interval); interval = null; }
            } else if (!transitioning && !interval) {
                interval = setInterval(poll, 6000);
            }
        };

        document.addEventListener('visibilitychange', onVisibility);
        interval = setInterval(poll, 6000);
        poll();
    });
</script>
</body>
</html>
