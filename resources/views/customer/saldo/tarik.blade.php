<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - {{ __('Tarik Saldo') }}</title>
    <script>if (localStorage.getItem('raliva-theme') === 'dark') document.documentElement.classList.add('theme-dark');</script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;family=Playfair+Display:wght@500;600&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-fixed": "#1c1b1b", "on-error-container": "#93000a", "on-tertiary-container": "#848482",
                        "surface-bright": "#fbf9f9", "on-primary-container": "#858383", "primary-fixed-dim": "#c8c6c5",
                        "surface-variant": "#e3e2e2", "on-surface": "#1b1c1c", "secondary": "#8B1E3F", "surface-dim": "#dbdad9",
                        "on-error": "#ffffff", "primary": "#000000", "on-secondary": "#ffffff", "tertiary-container": "#1a1c1a",
                        "error-container": "#ffdad6", "on-tertiary": "#ffffff", "secondary-fixed": "#8B1E3F", "on-primary": "#ffffff",
                        "surface-container-lowest": "#ffffff", "error": "#ba1a1a", "surface-container-highest": "#e3e2e2",
                        "inverse-surface": "#303031", "surface-container": "#efeded", "tertiary": "#000000", "primary-fixed": "#e5e2e1",
                        "outline-variant": "#c4c7c7", "surface-tint": "#5f5e5e", "secondary-fixed-dim": "#8B1E3F", "outline": "#747878",
                        "on-primary-fixed-variant": "#474646", "on-secondary-fixed-variant": "#6D1428", "on-tertiary-fixed": "#1a1c1a",
                        "on-secondary-container": "#6D1428", "inverse-on-surface": "#f2f0f0", "tertiary-fixed-dim": "#c7c6c4",
                        "tertiary-fixed": "#e3e2df", "surface-container-high": "#e9e8e7", "on-secondary-fixed": "#6D1428",
                        "background": "#fbf9f9", "surface": "#fbf9f9", "secondary-container": "#8B1E3F", "on-surface-variant": "#444748",
                        "primary-container": "#1c1b1b", "inverse-primary": "#c8c6c5", "surface-container-low": "#f5f3f3",
                        "on-tertiary-fixed-variant": "#464745", "on-background": "#1b1c1c"
                    },
                    "borderRadius": { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                    "spacing": { "gutter": "12px", "base": "4px", "xl": "48px", "lg": "32px", "container-margin": "20px", "sm": "16px", "md": "24px", "xs": "8px" },
                    "fontFamily": { "display-lg": ["Playfair Display"], "label-caps": ["Manrope"], "title-md": ["Manrope"], "headline-lg": ["Playfair Display"], "body-lg": ["Manrope"], "body-sm": ["Manrope"], "label-sm": ["Manrope"] },
                    "fontSize": {
                        "display-lg": ["40px", {"lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "600"}],
                        "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.08em", "fontWeight": "700"}],
                        "title-md": ["18px", {"lineHeight": "24px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "500"}],
                        "body-lg": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}]
                    }
                }
            }
        };
    </script>
    <style>
        :root {
            --chrome-bg: #ffffff; --chrome-bg-soft: rgba(255,255,255,.92); --chrome-text: #1b1c1c;
            --chrome-text-dim: rgba(0,0,0,.55); --chrome-text-faint: rgba(0,0,0,.45);
            --chrome-border: rgba(0,0,0,.1); --chrome-hover: rgba(0,0,0,.06); --chrome-accent: #8B1E3F;
            --surface-ivory: #F8F6F2; --surface-warm: #F3F0EA; --border-soft: #E5E1DA; --text-muted: #777777;
        }
        html.theme-dark {
            --chrome-bg: #1c1b1b; --chrome-bg-soft: rgba(28,27,27,.9); --chrome-text: #ffffff;
            --chrome-text-dim: rgba(255,255,255,.6); --chrome-text-faint: rgba(255,255,255,.5);
            --chrome-border: rgba(255,255,255,.1); --chrome-hover: rgba(255,255,255,.1); --chrome-accent: #8B1E3F;
            --surface-ivory: #1e1d1c; --surface-warm: #201f1e; --border-soft: rgba(255,255,255,.1); --text-muted: #b9b6b1;
        }
        html.theme-dark .bg-background, html.theme-dark .bg-surface, html.theme-dark .bg-surface-bright { background-color: #161514 !important; }
        html.theme-dark .bg-surface-container-lowest { background-color: #1e1d1c !important; }
        html.theme-dark .bg-surface-container-low { background-color: #201f1e !important; }
        html.theme-dark .text-on-surface, html.theme-dark .text-on-background { color: #e6e4e1 !important; }
        html.theme-dark .text-on-surface-variant { color: #b9b6b1 !important; }
        html.theme-dark .border-outline-variant { border-color: #3a3937 !important; }
        html.theme-dark .border-outline { border-color: #4a4844 !important; }
        html.theme-dark .text-error { color: #ffb4ab !important; }
        html.theme-dark .border-error { border-color: #ffb4ab !important; }
        .card-premium { box-shadow: 0 1px 2px rgb(17 17 17 / .04), 0 12px 32px -16px rgb(17 17 17 / .16); transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease; }
        html.theme-dark .card-premium { background-color: var(--surface-ivory); border-color: var(--border-soft); box-shadow: 0 1px 2px rgb(0 0 0 / .3), 0 8px 24px -12px rgb(0 0 0 / .5); }
        .premium-heading { display: block; }
        .premium-heading::before { content: ''; display: inline-block; width: 4px; height: .95em; margin-right: .65rem; background: #8B1E3F; border-radius: 9999px; vertical-align: -.05em; }
        .atl-eyebrow { display: inline-flex; align-items: center; gap: .65rem; }
        .atl-eyebrow::before { content: ''; width: 30px; height: 1px; background: var(--chrome-accent); opacity: .7; }
        .btn-gold { position: relative; overflow: hidden; background-color: #8B1E3F !important; color: #ffffff !important; }
        html.theme-dark .btn-gold { background-color: #6D1428 !important; }
        .co-stepper { display: flex; align-items: center; justify-content: center; gap: .5rem; }
        .co-step { display: flex; align-items: center; gap: .45rem; font-family: 'Manrope', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .co-step .num { width: 28px; height: 28px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; border: 1.5px solid var(--border-soft); background: var(--surface-warm); color: var(--text-muted); }
        .co-step.active .num, .co-step.done .num { background: #8B1E3F; border-color: #8B1E3F; color: #fff; }
        .co-step.active { color: #8B1E3F; }
        .co-step:not(.active):not(.done) { color: var(--text-muted); }
        .co-step-line { width: 32px; height: 1px; background: var(--border-soft); }
        .co-step-line.done { background: #8B1E3F; }
        @media (max-width: 480px) {
            .co-stepper { gap: .35rem; }
            .co-step { font-size: 10px; gap: .3rem; }
            .co-step .num { width: 24px; height: 24px; font-size: 11px; }
            .co-step-line { width: 16px; }
        }
        @media (max-width: 374px) { .co-step { font-size: 0; gap: .3rem; } .co-step-line { display: none; } }
        .co-step .num.loading { border: 2px solid var(--border-soft); border-top-color: #8B1E3F; background: transparent !important; color: transparent !important; animation: co-spin 0.75s linear infinite; }
        .co-step .num.loading::after { content: ''; display: none; }
        @keyframes co-spin { to { transform: rotate(360deg); } }
        .co-step.done { cursor: pointer; text-decoration: none; transition: opacity .2s ease; }
        .co-step.done:hover { opacity: .75; }
        .tujuan-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: .75rem; }
        .pay-method { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .4rem; padding: .75rem; border: 1px solid var(--border-soft); border-radius: .75rem; background: var(--surface-warm); cursor: pointer; transition: background .18s, border-color .18s, color .18s; }
        .pay-method:hover { background: #ECE7DF; border-color: #8B1E3F; color: #8B1E3F; }
        .pay-method.selected { border-color: #8B1E3F; background: rgba(139, 30, 63, .08); color: #8B1E3F; font-weight: 600; box-shadow: inset 0 0 0 1px rgba(139, 30, 63, .15); }
        html.theme-dark .pay-method:hover { background: #262524; }
        html.theme-dark .pay-method.selected { background: rgba(139, 30, 63, .18); color: #FFC2C9; }
        .tujuan-detail { margin-top: .75rem; animation: tujuanIn .25s ease both; }
        @keyframes tujuanIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
        .acc-ew-brand.dana { --brand: #008EDA; --brand-rgb: 0, 142, 218; }
        .acc-ew-brand.gopay { --brand: #00B7C4; --brand-rgb: 0, 183, 196; }
        .acc-ew-brand.ovo { --brand: #4C2E8D; --brand-rgb: 76, 46, 141; }
        .acc-ew-brand.shopeepay { --brand: #EE4D2D; --brand-rgb: 238, 77, 45; }
        .acc-bank-brand.bca { --brand: #0060AE; --brand-rgb: 0, 96, 174; }
        .acc-bank-brand.bri { --brand: #00529C; --brand-rgb: 0, 82, 156; }
        .acc-bank-brand.bni { --brand: #F7941E; --brand-rgb: 247, 148, 30; }
        .acc-bank-brand.mandiri { --brand: #FFC400; --brand-rgb: 255, 196, 0; }
        .ew-accounts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; position: relative; width: 100%; }
        .ew-card-wrap { width: 100%; transition: transform .3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity .25s ease; }
        .ew-accounts-grid.ew-expanded .ew-card-wrap:not(.ew-active) { display: none !important; }
        .ew-card-wrap.ew-active { grid-column: 1 / -1; width: 100%; }
        .account-opt-ew { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 1.2rem; border: 1px solid var(--border-soft); border-radius: .6rem; background: var(--surface-warm); cursor: pointer; width: 100%; box-sizing: border-box; transition: transform .25s cubic-bezier(0.34, 1.56, 0.64, 1), border-color .2s ease, background .2s ease; }
        .account-opt-ew:hover { border-color: var(--brand); transform: scale(1.02); }
        .account-opt-ew.selected { border-color: var(--brand); background: rgba(var(--brand-rgb), .10); box-shadow: inset 0 0 0 1px rgba(var(--brand-rgb), .35); align-items: center; text-align: center; animation: bubblePopOpen .4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        .account-opt-ew.selected .ew-brand-name { color: var(--brand); font-weight: 600; }
        .account-opt-ew .ew-card-detail { display: none; width: 100%; }
        .account-opt-ew.selected .ew-card-detail { display: block; animation: bubbleDetailFade .35s ease-out forwards; }
        .account-opt-ew .ew-card-detail-inner { margin-top: .8rem; padding-top: .8rem; border-top: 1px solid rgba(var(--brand-rgb), .30); text-align: left; width: 100%; }
        .ew-detail-nama { font-family: 'Manrope', sans-serif; font-size: 13px; font-weight: 700; line-height: 1.3; color: var(--brand); margin: 0 0 .4rem; }
        .ew-detail-line { font-family: 'Manrope', sans-serif; font-size: 12px; line-height: 1.5; color: var(--text-muted); margin: 0; }
        .ew-detail-line strong { color: var(--chrome-text); font-weight: 600; }
        html.theme-dark .account-opt-ew { background: #201f1e; border-color: rgba(255,255,255,.08); }
        html.theme-dark .account-opt-ew.selected { background: rgba(var(--brand-rgb), .14); }
        html.theme-dark .ew-detail-line { color: #b9b6b1; }
        html.theme-dark .ew-detail-line strong { color: #e6e4e1; }
        @keyframes bubblePopOpen { 0% { transform: scale(0.88); opacity: 0.7; } 60% { transform: scale(1.03); opacity: 1; } 100% { transform: scale(1); opacity: 1; } }
        @keyframes bubblePopClose { 0% { transform: scale(1); opacity: 1; } 40% { transform: scale(1.04); } 100% { transform: scale(0.92); opacity: 0.8; } }
        @keyframes bubbleDetailFade { 0% { opacity: 0; transform: translateY(10px) scale(0.96); } 100% { opacity: 1; transform: translateY(0) scale(1); } }
        .account-opt-ew.closing { animation: bubblePopClose .25s cubic-bezier(0.4, 0, 0.2, 1) forwards !important; }
        .tujuan-brand-name { font-size: 12px; line-height: 1.25; text-align: center; }
    </style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] lg:pl-72">

    <header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
        <a href="{{ route('customer.saldo') }}" aria-label="Back" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
            <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
        </a>
        <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('Tarik Saldo') }}</h1>
        <div class="w-10"></div>
    </header>

    <main class="pt-6 pb-10 w-full overflow-x-hidden">
        <div class="mx-auto max-w-[900px] px-container-margin space-y-lg">

            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-sm mb-md flex justify-center">
                <div class="co-stepper">
                    <span class="co-step active"><span class="num">1</span> {{ __('Pengajuan') }}</span>
                    <span class="co-step-line"></span>
                    <span class="co-step"><span class="num loading"></span> {{ __('Verifikasi') }}</span>
                    <span class="co-step-line"></span>
                    <span class="co-step"><span class="num loading"></span> {{ __('Selesai') }}</span>
                </div>
            </div>

            @if (session('toast'))
                @php
                    $toastIcon = is_array(session('toast')) ? (session('toast')['icon'] ?? '') : '';
                    $isErr = in_array($toastIcon, ['gpp_maybe', 'block', 'error'], true);
                    $isOk = in_array($toastIcon, ['task_alt', 'check', 'check_circle'], true);
                @endphp
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md flex items-center gap-sm">
                    @if ($isErr)
                        <span class="material-symbols-outlined text-error">close</span>
                    @elseif ($isOk)
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    @else
                        <span class="material-symbols-outlined text-secondary">task_alt</span>
                    @endif
                    <p class="font-body-sm text-body-sm text-on-surface-variant">{{ is_array(session('toast')) ? (session('toast')['message'] ?? '') : session('toast') }}</p>
                </div>
            @endif

            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
                <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('PENARIKAN SALDO') }}</p>
                <h2 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Tarik ke Rekening') }}</h2>

                <div class="flex items-center justify-between gap-sm rounded-xl border border-outline-variant bg-surface-container-low px-md py-sm mb-md">
                    <span class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Saldo tersedia') }}</span>
                    <strong class="font-title-md text-title-md text-on-surface">Rp {{ number_format((float) $saldo, 0, ',', '.') }}</strong>
                </div>

                <form method="POST" action="{{ route('customer.saldo.tarik.store') }}" class="space-y-md" id="form-tarik-saldo">
                    @csrf
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-sm">{{ __('Nominal Penarikan') }}</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-body-sm text-body-sm font-semibold text-secondary pointer-events-none">Rp</span>
                            <input type="text" inputmode="numeric" autocomplete="off" name="nominal" id="input-nominal"
                                placeholder="{{ __('Min :min', ['min' => number_format($minTarik, 0, ',', '.')]) }}"
                                class="w-full border border-outline-variant rounded-xl pl-11 pr-md py-3 bg-surface-container-low text-on-surface outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/30 transition-colors"
                                value="{{ old('nominal') ? number_format((int) preg_replace('/\D/', '', (string) old('nominal')), 0, ',', '.') : '' }}" />
                        </div>
                        @error('nominal')
                            <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-sm">{{ __('Tujuan Pencairan') }}</label>
                        <input type="hidden" name="tipe_tujuan" id="tujuan-tipe" value="{{ old('tipe_tujuan', 'bank') }}" />
                        <input type="hidden" name="bank_id" id="input-bank-id" value="{{ old('bank_id', '') }}" />
                        <input type="hidden" name="penyedia" id="input-penyedia" value="{{ old('penyedia', '') }}" />
                        <div class="tujuan-grid" id="tujuan-grid">
                            <div class="pay-method" data-tujuan="bank" role="button" tabindex="0">
                                <span class="material-symbols-outlined text-[28px]">account_balance</span>
                                <span class="text-center leading-tight text-sm">{{ __('Bank') }}</span>
                            </div>
                            <div class="pay-method" data-tujuan="e-wallet" role="button" tabindex="0">
                                <span class="material-symbols-outlined text-[28px]">account_balance_wallet</span>
                                <span class="text-center leading-tight text-sm">{{ __('E-Wallet') }}</span>
                            </div>
                        </div>
                        <div id="detail-bank" class="tujuan-detail hidden">
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-sm">{{ __('Pilih bank tujuan') }}:</p>
                            <div class="ew-accounts-grid" id="grid-bank">
                                @foreach ($banks as $b)
                                    @php $bkode = strtolower($b->kode_bank ?? ''); $blogo = in_array($bkode, ['bca', 'bni', 'bri', 'mandiri'], true) ? asset('images/Bank/' . $bkode . '.png') : null; @endphp
                                    <div class="ew-card-wrap" data-kode="{{ $bkode }}">
                                        <div class="account-opt account-opt-ew acc-bank-brand {{ $bkode }}" data-bank-id="{{ $b->bank_id }}" data-nama="{{ $b->nama_bank }}" role="button" tabindex="0">
                                            @if ($blogo)
                                                <img src="{{ $blogo }}" alt="{{ $b->nama_bank }}" class="h-7 object-contain" />
                                            @else
                                                <span class="w-9 h-9 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center font-bold text-sm text-on-surface-variant">{{ strtoupper(substr($b->nama_bank ?? '?', 0, 1)) }}</span>
                                            @endif
                                            <span class="ew-brand-name text-xs leading-tight mt-1">{{ $b->nama_bank }}</span>
                                            <div class="ew-card-detail">
                                                <div class="ew-card-detail-inner">
                                                    <p class="ew-detail-nama">{{ $b->nama_bank }}</p>
                                                    <p class="ew-detail-line">
                                                        <span>{{ __('Kode bank') }}:</span>
                                                        <strong>{{ strtoupper($bkode) ?: '-' }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('bank_id')
                                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="detail-ewallet" class="tujuan-detail hidden">
                            <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-sm">{{ __('Pilih e-wallet') }}:</p>
                            <div class="ew-accounts-grid" id="grid-ewallet">
                                @php $ewList = [['dana', 'DANA', 'images/E-Wallet/dana.png'], ['gopay', 'GoPay', 'images/E-Wallet/gopay.jpg'], ['ovo', 'OVO', 'images/E-Wallet/ovo.png'], ['shopeepay', 'ShopeePay', 'images/E-Wallet/shoopepay.jfif']]; @endphp
                                @foreach ($ewList as [$ekode, $enama, $eimg])
                                    <div class="ew-card-wrap" data-kode="{{ $ekode }}">
                                        <div class="account-opt account-opt-ew acc-ew-brand {{ $ekode }}" data-penyedia="{{ $ekode }}" data-nama="{{ $enama }}" role="button" tabindex="0">
                                            <img src="{{ asset($eimg) }}" alt="{{ $enama }}" class="h-7 object-contain" />
                                            <span class="ew-brand-name text-xs leading-tight mt-1">{{ $enama }}</span>
                                            <div class="ew-card-detail">
                                                <div class="ew-card-detail-inner">
                                                    <p class="ew-detail-nama">{{ $enama }}</p>
                                                    <p class="ew-detail-line">
                                                        <span>{{ __('E-wallet') }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('penyedia')
                                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        @error('tipe_tujuan')
                            <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-sm">
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-sm">{{ __('Nomor Tujuan') }}</label>
                            <input name="nomor_tujuan" type="text" inputmode="tel" maxlength="50" value="{{ old('nomor_tujuan') }}" placeholder="{{ __('cth. 1234567890') }}"
                                class="w-full border border-outline-variant rounded-xl px-md py-3 bg-surface-container-low text-on-surface outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/30 transition-colors" />
                            @error('nomor_tujuan')
                                <p class="font-label-sm text-label-sm text-error mt-xs">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-sm">{{ __('Nama Pemilik (opsional)') }}</label>
                            <input name="nama_pemilik" type="text" maxlength="150" value="{{ old('nama_pemilik') }}"
                                class="w-full border border-outline-variant rounded-xl px-md py-3 bg-surface-container-low text-on-surface outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/30 transition-colors" />
                        </div>
                    </div>

                    <div class="rounded-xl border border-outline-variant bg-surface-container-low px-md py-sm space-y-xs">
                        <div class="flex items-center justify-between gap-sm font-body-sm text-body-sm text-on-surface-variant">
                            <span>{{ __('Biaya platform') }} ({{ rtrim(rtrim(number_format((float) $feePersen, 2, ',', '.'), '0'), ',') }}%)</span>
                            <strong class="text-on-surface" id="ringkas-fee">Rp 0</strong>
                        </div>
                        <div class="flex items-center justify-between gap-sm font-body-sm text-body-sm">
                            <span class="text-on-surface-variant">{{ __('Diterima bersih') }}</span>
                            <strong class="text-[var(--chrome-accent)]" id="ringkas-bersih">Rp 0</strong>
                        </div>
                    </div>

                    <button type="submit"
                        class="btn-gold w-full inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                        <span>{{ __('Ajukan Penarikan') }}</span>
                    </button>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ __('Saldo dipotong di muka sebagai hold. Dana ditransfer setelah Super Admin menyetujui.') }}</p>
                </form>
            </div>

        </div>
    </main>

    @include('customer._partials.bottom-nav')
    @include('customer._partials.drawer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById('input-nominal');
            var form = document.getElementById('form-tarik-saldo');
            var feePersen = parseFloat('{{ (float) $feePersen }}') || 0;
            var feeEl = document.getElementById('ringkas-fee');
            var bersihEl = document.getElementById('ringkas-bersih');
            var tipeInput = document.getElementById('tujuan-tipe');
            var bankIdInput = document.getElementById('input-bank-id');
            var penyediaInput = document.getElementById('input-penyedia');
            var methods = document.querySelectorAll('#tujuan-grid .pay-method');
            var detailBank = document.getElementById('detail-bank');
            var detailEwallet = document.getElementById('detail-ewallet');

            var digitsOnly = function(v) { return (v || '').replace(/\D/g, ''); };
            var formatRibuan = function(v) {
                var d = digitsOnly(v).replace(/^0+(?=\d)/, '');
                if (!d) return '';
                return d.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            };
            var syncBersih = function() {
                if (!bersihEl) return;
                var nominal = parseFloat(digitsOnly(input ? input.value : '') || '0');
                var fee = Math.round(nominal * feePersen / 100);
                var bersih = Math.max(0, nominal - fee);
                if (feeEl) feeEl.textContent = 'Rp ' + fee.toLocaleString('id-ID');
                bersihEl.textContent = 'Rp ' + bersih.toLocaleString('id-ID');
            };
            var syncTujuan = function() {
                var v = tipeInput ? tipeInput.value : 'bank';
                methods.forEach(function(m) { m.classList.toggle('selected', m.getAttribute('data-tujuan') === v); });
                if (detailBank) {
                    detailBank.classList.toggle('hidden', v !== 'bank');
                    if (v === 'bank') { detailBank.style.animation = 'none'; void detailBank.offsetWidth; detailBank.style.animation = ''; }
                }
                if (detailEwallet) {
                    detailEwallet.classList.toggle('hidden', v !== 'e-wallet');
                    if (v === 'e-wallet') { detailEwallet.style.animation = 'none'; void detailEwallet.offsetWidth; detailEwallet.style.animation = ''; }
                }
                syncOpts();
            };
            var syncOpts = function() {
                var bid = bankIdInput ? String(bankIdInput.value || '') : '';
                var bankGrid = document.getElementById('grid-bank');
                var bankHas = false;
                document.querySelectorAll('#detail-bank .account-opt-ew').forEach(function(o) {
                    var on = bid !== '' && o.getAttribute('data-bank-id') === bid;
                    o.classList.toggle('selected', on);
                    var w = o.closest('.ew-card-wrap');
                    if (w) w.classList.toggle('ew-active', on);
                    if (on) bankHas = true;
                });
                if (bankGrid) bankGrid.classList.toggle('ew-expanded', bankHas);
                var pv = penyediaInput ? String(penyediaInput.value || '') : '';
                var ewGrid = document.getElementById('grid-ewallet');
                var ewHas = false;
                document.querySelectorAll('#detail-ewallet .account-opt-ew').forEach(function(o) {
                    var on = pv !== '' && o.getAttribute('data-penyedia') === pv;
                    o.classList.toggle('selected', on);
                    var w2 = o.closest('.ew-card-wrap');
                    if (w2) w2.classList.toggle('ew-active', on);
                    if (on) ewHas = true;
                });
                if (ewGrid) ewGrid.classList.toggle('ew-expanded', ewHas);
            };
            var selectOpt = function(opt, kind) {
                var grid = opt.closest('.ew-accounts-grid');
                var wrap = opt.closest('.ew-card-wrap');
                if (opt.classList.contains('selected')) {
                    opt.classList.add('closing');
                    setTimeout(function() {
                        opt.classList.remove('selected', 'closing');
                        if (wrap) wrap.classList.remove('ew-active');
                        if (grid) grid.classList.remove('ew-expanded');
                        if (kind === 'bank' && bankIdInput) bankIdInput.value = '';
                        if (kind === 'ewallet' && penyediaInput) penyediaInput.value = '';
                    }, 200);
                    return;
                }
                if (grid) {
                    grid.querySelectorAll('.ew-card-wrap').forEach(function(w) { w.classList.remove('ew-active'); });
                    grid.querySelectorAll('.account-opt-ew').forEach(function(o) { o.classList.remove('selected', 'closing'); });
                }
                if (wrap) {
                    wrap.classList.add('ew-active');
                    setTimeout(function() { opt.classList.add('selected'); }, 120);
                } else {
                    opt.classList.add('selected');
                }
                if (grid) grid.classList.add('ew-expanded');
                if (kind === 'bank' && bankIdInput) bankIdInput.value = opt.getAttribute('data-bank-id');
                if (kind === 'ewallet' && penyediaInput) penyediaInput.value = opt.getAttribute('data-penyedia');
            };
            var bindOptKeys = function(el, fn) {
                el.addEventListener('click', fn);
                el.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fn(); }
                });
            };

            methods.forEach(function(m) {
                bindOptKeys(m, function() {
                    if (tipeInput) tipeInput.value = m.getAttribute('data-tujuan');
                    syncTujuan();
                });
            });
            document.querySelectorAll('#detail-bank .account-opt-ew').forEach(function(o) {
                bindOptKeys(o, function() { selectOpt(o, 'bank'); });
            });
            document.querySelectorAll('#detail-ewallet .account-opt-ew').forEach(function(o) {
                bindOptKeys(o, function() { selectOpt(o, 'ewallet'); });
            });
            if (input) {
                if (input.value) input.value = formatRibuan(input.value);
                input.addEventListener('input', function() {
                    input.value = formatRibuan(input.value);
                    syncBersih();
                });
            }
            if (form && input) {
                form.addEventListener('submit', function() {
                    input.value = digitsOnly(input.value);
                });
            }
            syncTujuan();
            syncBersih();
        });
    </script>
</body>
</html>
