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

    /* Step yang sudah selesai (done) bisa diklik untuk kembali */
    .co-step.done { cursor:pointer; text-decoration:none; transition: opacity .2s ease; }
    .co-step.done:hover { opacity: .75; }
</style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col lg:pl-72">
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
    <a href="{{ route('customer.home') }}" aria-label="Home" class="p-2 -ml-2 hover:opacity-70 flex"><span class="material-symbols-outlined">home</span></a>
    <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[220px] text-center">{{ __('Selesai') }}</h1>
    <div class="w-10"></div>
</header>
<main class="pt-6 pb-10 w-full overflow-x-hidden">
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

        @php $akunBaru = session('akun_baru'); @endphp

        {{-- === SUKSES HEADER === --}}
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-2xl p-md md:p-xl card-premium text-center reveal-up">
            <div class="mx-auto w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mb-md">
                <span class="material-symbols-outlined text-[40px] text-emerald-600">task_alt</span>
            </div>
            <h2 class="font-headline-md text-headline-md text-on-surface">{{ __('Pesanan Berhasil!') }}</h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm max-w-xl mx-auto">{{ __('Terima kasih. Pesananmu telah kami terima dan bukti pembayaran sedang diverifikasi admin.') }}</p>
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
                    <div class="text-right">
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
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold ml-1
                                @if($payment->status === 'pending') bg-amber-100 text-amber-800
                                @elseif($payment->status === 'menunggu_verifikasi') bg-blue-100 text-blue-800
                                @elseif($payment->status === 'terverifikasi') bg-emerald-100 text-emerald-800
                                @elseif($payment->status === 'ditolak') bg-red-100 text-red-800
                                @else bg-surface-container text-on-surface-variant @endif
                            ">{{ $payment->status }}</span>
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

            {{-- Daftar Produk per Toko --}}
            @foreach($checkout->orders as $order)
            <div class="border border-outline-variant rounded-lg p-md reveal-up">
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

            {{-- Rincian Biaya --}}
            <div class="bg-surface-container-low border border-outline-variant rounded-xl p-md reveal-up">
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

        {{-- === TOMBOL AKSI === --}}
        <div class="mt-lg max-w-3xl mx-auto flex flex-col sm:flex-row gap-sm justify-center reveal-up">
            <a href="{{ route('customer.shop') }}" class="btn-gold inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                <span class="material-symbols-outlined text-[18px]">storefront</span> {{ __('Lanjut Belanja') }}
            </a>
            <a href="{{ route('customer.order-tracking') }}" class="inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full border border-outline-variant font-label-caps text-label-caps uppercase tracking-widest hover:border-[var(--chrome-accent)] hover:text-[var(--chrome-accent)] transition-colors">
                <span class="material-symbols-outlined text-[18px]">receipt_long</span> {{ __('Lacak Pesanan') }}
            </a>
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
</body>
</html>
