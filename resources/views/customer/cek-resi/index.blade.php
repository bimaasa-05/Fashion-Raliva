<!DOCTYPE html>
<html class="light" lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>RALIVA - {{ __('Cek Resi') }}</title>
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
<style> .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; } .material-symbols-outlined[data-weight="fill"]{font-variation-settings:'FILL'1;} body{min-height:max(884px,100dvh);} </style>
<style> :root{--chrome-bg:#fff;--chrome-bg-soft:rgba(255,255,255,.92);--chrome-text:#1b1c1c;--chrome-text-dim:rgba(0,0,0,.55);--chrome-text-faint:rgba(0,0,0,.45);--chrome-border:rgba(0,0,0,.1);--chrome-hover:rgba(0,0,0,.06);--chrome-accent:#8B1E3F;--surface-ivory:#F8F6F2;--surface-warm:#F3F0EA;--border-soft:#E5E1DA;--text-muted:#777;} html.theme-dark{--chrome-bg:#1c1b1b;--chrome-bg-soft:rgba(28,27,27,.9);--chrome-text:#fff;--chrome-text-dim:rgba(255,255,255,.6);--chrome-text-faint:rgba(255,255,255,.5);--chrome-border:rgba(255,255,255,.1);--chrome-hover:rgba(255,255,255,.1);--chrome-accent:#8B1E3F;--surface-ivory:#1e1d1c;--surface-warm:#201f1e;--border-soft:rgba(255,255,255,.1);--text-muted:#b9b6b1;} </style>
<style> html.theme-dark .bg-background,html.theme-dark .bg-surface,html.theme-dark .bg-surface-bright{background:#161514!important;} html.theme-dark .bg-surface-container-lowest{background:#1e1d1c!important;} html.theme-dark .bg-surface-container-low{background:#201f1e!important;} html.theme-dark .text-on-surface,html.theme-dark .text-on-background{color:#e6e4e1!important;} html.theme-dark .text-on-surface-variant{color:#b9b6b1!important;} html.theme-dark .border-outline-variant{border-color:#3a3937!important;} </style>
<style> .card-premium{box-shadow:0 1px 2px rgb(17 17 17 / .04),0 12px 32px -16px rgb(17 17 17 / .16);transition:transform .25s,box-shadow .25s,border-color .25s;} .card-premium:hover{box-shadow:0 2px 4px rgb(17 17 17 / .05),0 20px 48px -20px rgb(17 17 17 / .22);border-color:rgba(139,30,63,.45);} html.theme-dark .card-premium{background:var(--surface-ivory);border-color:var(--border-soft);} .premium-heading::before{content:'';display:inline-block;width:4px;height:.95em;margin-right:.65rem;background:#8B1E3F;border-radius:9999px;vertical-align:-.05em;} .atl-eyebrow{display:inline-flex;align-items:center;gap:.65rem;} .atl-eyebrow::before{content:'';width:30px;height:1px;background:var(--chrome-accent);opacity:.7;} .reveal-up{opacity:0;transform:translateY(12px);transition:opacity .5s,transform .5s;} .reveal-up.is-visible{opacity:1;transform:none;} .btn-gold{position:relative;overflow:hidden;background:var(--btn-gold-bg)!important;color:var(--btn-gold-text)!important;} .btn-gold::after{content:'';position:absolute;top:-10%;bottom:-10%;left:-80%;width:45%;background:rgba(255,255,255,.55);transform:skewX(-24deg);pointer-events:none;} .btn-gold:hover::after{animation:authFlash 1.4s linear infinite;} @keyframes authFlash{from{left:-80%}to{left:135%}} :root{--btn-gold-bg:#8B1E3F;--btn-gold-text:#fff;} html.theme-dark{--btn-gold-bg:#6D1428;--btn-gold-text:#fff;} #drawer-panel{--chrome-accent:#8B1E3F;--gold-wash:rgba(139,30,63,.10);} html.theme-dark #drawer-panel{--chrome-accent:#8B1E3F;--gold-wash:rgba(163,38,63,.16);} </style>
<style> .co-input{width:100%;background:var(--surface-warm);border:1px solid var(--border-soft);border-radius:.5rem;padding:.65rem .9rem;font-family:Manrope,sans-serif;font-size:14px;color:var(--on-surface);outline:none;transition:border-color .18s,background .18s;} .co-input:focus{border-color:#8B1E3F;background:#fff;} html.theme-dark .co-input:focus{background:#262524;} .co-textarea{width:100%;background:var(--surface-warm);border:1px solid var(--border-soft);border-radius:.5rem;padding:.65rem .9rem;font-family:Manrope,sans-serif;font-size:14px;color:var(--on-surface);outline:none;resize:vertical;} .co-textarea:focus{border-color:#8B1E3F;background:#fff;} .timeline-line{position:absolute;top:12px;left:16px;right:16px;height:2px;background:var(--chrome-border);border-radius:9999px;z-index:0;} .timeline-progress{position:absolute;top:12px;left:16px;height:2px;background:var(--chrome-accent);border-radius:9999px;z-index:1;transition:width .6s cubic-bezier(.22,1,.36,1);} .timeline-active-circle{border-color:var(--chrome-accent)!important;box-shadow:0 0 0 4px rgba(139,30,30,.10);} html.theme-dark .timeline-active-circle{box-shadow:0 0 0 4px rgba(109,20,40,.16);} .timeline-active-dot{background:var(--chrome-accent)!important;} .timeline-active-label{color:var(--chrome-accent)!important;} .tab-active{border-color:#8B1E3F!important;background:rgba(139,30,63,.08)!important;color:#8B1E3F!important;} </style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] lg:pl-72">
<header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
    <a href="{{ route('customer.home') }}" aria-label="Home" class="p-2 -ml-2 hover:opacity-70 flex"><span class="material-symbols-outlined">arrow_back</span></a>
    <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[220px] text-center">{{ __('Cek Resi') }}</h1>
    <div class="w-10"></div>
</header>

<main class="pt-6 pb-10 w-full overflow-x-hidden">
    <div class="mx-auto max-w-[1400px] px-container-margin">
        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium mb-lg reveal-up text-center md:text-left">
            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('TRACKING') }}</p>
            <h2 class="premium-heading font-headline-md text-headline-md text-on-surface">{{ __('Lacak Pesanan Anda') }}</h2>
            <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">{{ __('Cek status pengiriman via nomor resi atau nomor order + telepon.') }}</p>
        </div>

        <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
            <div class="flex gap-sm mb-md">
                <button type="button" id="tab-resi" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-full border border-[var(--border-soft)] font-label-caps text-label-caps uppercase tracking-widest transition-colors tab-active" onclick="switchTab('resi')">
                    <span class="material-symbols-outlined text-[18px]">local_shipping</span> {{ __('Nomor Resi') }}
                </button>
                <button type="button" id="tab-order" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-full border border-[var(--border-soft)] font-label-caps text-label-caps uppercase tracking-widest transition-colors" onclick="switchTab('order')">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span> {{ __('Nomor Order') }}
                </button>
            </div>

            <form id="form-resi" method="POST" action="{{ route('customer.cek-resi.search') }}" class="space-y-md">
                @csrf
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Nomor Resi') }}</span>
                    <input name="nomor_resi" value="{{ old('nomor_resi', $query['nomor_resi'] ?? '') }}" maxlength="100" placeholder="cth. JNE1234567890" class="co-input @error('nomor_resi') border-error @enderror"/>
                    @error('nomor_resi')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                </label>
                <button type="submit" class="btn-gold w-full inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                    <span class="material-symbols-outlined text-[18px]">search</span> {{ __('Lacak Resi') }}
                </button>
            </form>

            <form id="form-order" method="POST" action="{{ route('customer.cek-resi.search') }}" class="space-y-md hidden">
                @csrf
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Nomor Order') }}</span>
                    <input name="nomor_order" value="{{ old('nomor_order', $query['nomor_order'] ?? '') }}" maxlength="100" placeholder="cth. RLV-3-ABCDEF" class="co-input @error('nomor_order') border-error @enderror"/>
                    @error('nomor_order')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                </label>
                <label class="flex flex-col gap-1.5">
                    <span class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Nomor Telepon / Email') }}</span>
                    <input name="nomor_telepon" value="{{ old('nomor_telepon', $query['nomor_telepon'] ?? '') }}" maxlength="30" placeholder="08xxxxxxxxxx / email" class="co-input @error('nomor_telepon') border-error @enderror"/>
                    @error('nomor_telepon')<span class="font-label-sm text-label-sm text-error">{{ $message }}</span>@enderror
                </label>
                <p class="font-label-sm text-label-sm text-on-surface-variant/70">{{ __('Nomor telepon harus sama dengan saat checkout. Bisa juga pakai email.') }}</p>
                <button type="submit" class="btn-gold w-full inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                    <span class="material-symbols-outlined text-[18px]">search</span> {{ __('Cek Pesanan') }}
                </button>
            </form>

            @if($error)
            <div class="mt-md bg-error-container border border-error/20 rounded-xl p-md flex items-center gap-sm">
                <span class="material-symbols-outlined text-error">error</span>
                <p class="font-body-sm text-body-sm text-on-error-container">{{ $error }}</p>
            </div>
            @endif
        </div>

        @if($result)
        @php
            $order = $result['order'];
            $shipment = $result['shipment'];
            $timeline = $result['timeline'];
            $steps = $timeline['steps'];
            $active = $timeline['active'];
            $isGagal = $timeline['isGagal'];
            $progressWidth = $isGagal ? 0 : (($active-1)/3*100);
            $progressWidth = max(0,min(100,$progressWidth));
        @endphp
        <div class="mt-lg space-y-lg">
            {{-- Timeline --}}
            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
                <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('STATUS') }}</p>
                <div class="flex flex-wrap justify-between items-end gap-sm mb-md">
                    <div>
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('Order ID') }}</p>
                        <p class="font-title-md text-title-md font-semibold text-on-surface tracking-tight">#{{ $order->nomor_order }}</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $order->created_at->format('M j, Y') }} • {{ $order->items->count() }} {{ __('items') }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <span class="inline-flex items-center gap-xs px-sm py-1 rounded-full border {{ $isGagal ? 'bg-error/10 border-error/15' : 'bg-secondary/10 border-secondary/15' }}">
                            <span class="w-2 h-2 rounded-full {{ $isGagal ? 'bg-error' : 'bg-secondary' }} animate-pulse"></span>
                            <span class="font-label-sm text-label-sm {{ $isGagal ? 'text-error' : 'text-secondary' }} uppercase tracking-wider font-semibold">{{ $order->status }}</span>
                        </span>
                        @if($shipment)
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ __('Resi') }}: <strong class="text-on-surface">{{ $shipment->nomor_resi }}</strong> • {{ $shipment->courier?->nama_kurir ?? '' }} {{ $shipment->shippingService?->nama_layanan ?? '' }}</p>
                        @else
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ __('Belum ada resi') }}</p>
                        @endif
                    </div>
                </div>

                @if($isGagal)
                <div class="text-center py-1">
                    <span class="material-symbols-outlined text-[40px] text-error mb-xs block">cancel</span>
                    <h3 class="font-title-md text-title-md text-on-surface mb-xs">{{ __('Pengiriman gagal / pesanan dibatalkan') }}</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Hubungi layanan pelanggan untuk bantuan.') }}</p>
                </div>
                @else
                <div class="relative max-w-[480px] mx-auto">
                    <div class="timeline-line"></div>
                    <div class="timeline-progress" style="width: {{ $progressWidth }}%;"></div>
                    <div class="flex justify-between gap-2 relative z-10">
                        @foreach($steps as $idx => $s)
                        @php $stepNum = $idx+1; $passed = $active > $stepNum; $cur = $active === $stepNum; @endphp
                        <div class="flex flex-col items-center gap-1 flex-1">
                            @if($passed)
                            <div class="w-7 h-7 md:w-6 md:h-6 rounded-full flex items-center justify-center bg-secondary border border-secondary shrink-0"><span class="material-symbols-outlined text-[14px] text-white">check</span></div>
                            <span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface-variant text-center leading-tight">{{ __($s['label']) }}</span>
                            @elseif($cur)
                            <div class="w-7 h-7 md:w-6 md:h-6 rounded-full flex items-center justify-center bg-surface transition-colors timeline-active-circle shrink-0" style="border:2px solid var(--chrome-accent);"><div class="w-2.5 h-2.5 md:w-2 md:h-2 rounded-full timeline-active-dot"></div></div>
                            <span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface text-center leading-tight timeline-active-label">{{ __($s['label']) }}</span>
                            @else
                            <div class="w-7 h-7 md:w-6 md:h-6 rounded-full bg-surface border border-outline-variant flex items-center justify-center shrink-0"></div>
                            <span class="font-label-sm text-[10px] md:text-label-sm uppercase tracking-wider text-on-surface-variant text-center leading-tight">{{ __($s['label']) }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($shipment)
                <div class="mt-lg grid grid-cols-1 md:grid-cols-3 gap-md">
                    <div class="border border-outline-variant rounded-lg p-md">
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Ekspedisi') }}</p>
                        <p class="font-body-lg text-body-lg font-semibold text-on-surface mt-xs">{{ $shipment->courier?->nama_kurir ?? '-' }} {{ $shipment->shippingService?->nama_layanan ? '('.$shipment->shippingService->nama_layanan.')' : '' }}</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">{{ __('Layanan') }}: {{ $shipment->shippingService?->estimasi_hari ? $shipment->shippingService->estimasi_hari.' hari' : '-' }}</p>
                    </div>
                    <div class="border border-outline-variant rounded-lg p-md">
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Nomor Resi') }}</p>
                        <p class="font-title-md text-title-md font-semibold text-on-surface mt-xs break-all">{{ $shipment->nomor_resi }}</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">{{ __('Estimasi tiba') }}: {{ $shipment->estimasi_tiba?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="border border-outline-variant rounded-lg p-md">
                        <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">{{ __('Waktu') }}</p>
                        <p class="font-body-sm text-body-sm text-on-surface mt-xs">{{ __('Dikirim') }}: {{ $shipment->dikirim_pada?->format('d M Y, H:i') ?? '-' }}</p>
                        <p class="font-body-sm text-body-sm text-on-surface">{{ __('Diterima') }}: {{ $shipment->diterima_pada?->format('d M Y, H:i') ?? '-' }}</p>
                        <p class="font-label-sm text-label-sm text-on-surface-variant mt-xs">Status: {{ $shipment->status }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Order info --}}
            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium reveal-up">
                <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('PESANAN') }}</p>
                <div class="flex flex-wrap justify-between items-center gap-sm mb-md">
                    <h3 class="premium-heading font-title-md text-title-md text-on-surface">{{ $order->store?->nama_toko ?? 'RALIVA' }}</h3>
                    <span class="font-body-sm text-body-sm text-on-surface font-medium">Rp {{ number_format((float)$order->grand_total,0,',','.') }}</span>
                </div>
                <div class="divide-y divide-outline-variant/40">
                    @foreach($order->items as $item)
                    @php $v=$item->productVariant; $img=$v?->product?->images->first()?->file_gambar ?? ''; $imgUrl=$img ? (filter_var($img, FILTER_VALIDATE_URL) ? $img : asset($img)) : 'https://picsum.photos/seed/order-'.$item->order_item_id.'/900/1200'; @endphp
                    <div class="flex gap-sm md:gap-md py-sm">
                        <div class="w-20 h-24 bg-surface-container-low rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/30"><img class="w-full h-full object-cover" loading="lazy" src="{{ $imgUrl }}" alt="{{ $item->nama_produk_snapshot }}"/></div>
                        <div class="flex flex-col justify-between py-1 flex-grow min-w-0">
                            <div class="min-w-0">
                                <h4 class="font-body-sm text-body-sm font-semibold text-on-surface truncate">{{ $item->nama_produk_snapshot }}</h4>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1"><span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-surface-container-low border border-outline-variant text-xs">{{ $v?->warna ?? __('Default') }}@if($v?->ukuran) • {{ $v->ukuran }}@endif </span></p>
                            </div>
                            <div class="flex justify-between items-center mt-sm"><p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Qty:') }} {{ $item->quantity }}</p><p class="font-body-sm text-body-sm font-semibold text-on-surface">Rp {{ number_format($item->harga_snapshot,0,',','.') }}</p></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @php $c=$order->checkout; $displayName=$c?->nama_penerima ?? $c?->user?->nama_lengkap ?? '-'; $displayPhone=$c?->nomor_telepon ?? $c?->user?->nomor_telepon ?? '-'; @endphp
                <div class="mt-md border-t border-outline-variant pt-md">
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-xs">{{ __('Penerima') }}</p>
                    <p class="font-body-sm text-body-sm text-on-surface">{{ $displayName }} • {{ $displayPhone }}</p>
                    @if($c?->alamat)<p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $c->alamat }}, {{ $c->kota }} {{ $c->kode_pos }} ({{ $c->provinsi }})</p>@endif
                </div>
            </div>
        </div>
        @endif
    </div>
</main>

@include('customer._partials.drawer')
@include('customer._partials.bottom-nav')

<script>
    function switchTab(which){
        var tResi=document.getElementById('tab-resi'),tOrder=document.getElementById('tab-order'),fResi=document.getElementById('form-resi'),fOrder=document.getElementById('form-order');
        if(which==='order'){ tOrder.classList.add('tab-active'); tResi.classList.remove('tab-active'); fOrder.classList.remove('hidden'); fResi.classList.add('hidden'); }
        else { tResi.classList.add('tab-active'); tOrder.classList.remove('tab-active'); fResi.classList.remove('hidden'); fOrder.classList.add('hidden'); }
    }
    (function(){
        @if(!empty($query['nomor_order'])) switchTab('order'); @endif
        var els=document.querySelectorAll('.reveal-up');
        if(!('IntersectionObserver' in window)){els.forEach(function(e){e.classList.add('is-visible');});return;}
        var io=new IntersectionObserver(function(entries){entries.forEach(function(en){if(en.isIntersecting){en.target.classList.add('is-visible');io.unobserve(en.target);}});},{threshold:0.08});
        els.forEach(function(e){io.observe(e);});
    })();
</script>
</body>
</html>
