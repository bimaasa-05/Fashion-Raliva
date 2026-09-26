<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - {{ __('Isi Saldo') }}</title>
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
        .chip-quick { border: 1px solid var(--border-soft); background: var(--surface-warm); }
        .chip-quick.active { border-color: #8B1E3F; background: rgba(139,30,63,.08); color: #8B1E3F; font-weight: 600; box-shadow: inset 0 0 0 1px rgba(139,30,63,.15); }
        html.theme-dark .chip-quick.active { background: rgba(139,30,63,.18); color: #FFC2C9; }
        .co-stepper { display: flex; align-items: center; justify-content: center; gap: .5rem; }
        .co-step { display: flex; align-items: center; gap: .45rem; font-family: 'Manrope', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .co-step .num { width: 28px; height: 28px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; border: 1.5px solid var(--border-soft); background: var(--surface-warm); color: var(--text-muted); }
        .co-step.active .num, .co-step.done .num { background: #8B1E3F; border-color: #8B1E3F; color: #fff; }
        .co-step.active { color: #8B1E3F; }
        .co-step:not(.active):not(.done) { color: var(--text-muted); }
        .co-step-line { width: 32px; height: 1px; background: var(--border-soft); }
        .co-step-line.done { background: #8B1E3F; }
        @media (max-width: 374px) { .co-step { font-size: 0; gap: .3rem; } .co-step-line { display: none; } }
        .co-step .num.loading { border: 2px solid var(--border-soft); border-top-color: #8B1E3F; background: transparent !important; color: transparent !important; animation: co-spin 0.75s linear infinite; }
        .co-step .num.loading::after { content: ''; display: none; }
        @keyframes co-spin { to { transform: rotate(360deg); } }
        .co-step.done { cursor: pointer; text-decoration: none; transition: opacity .2s ease; }
        .co-step.done:hover { opacity: .75; }
    </style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] lg:pl-72">

    <header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
        <a href="{{ route('customer.saldo') }}" aria-label="Back" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
            <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
        </a>
        <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('Isi Saldo') }}</h1>
        <div class="w-10"></div>
    </header>

    <main class="pt-6 pb-10 w-full overflow-x-hidden">
        <div class="mx-auto max-w-[900px] px-container-margin space-y-lg">

            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-sm mb-md flex justify-center">
                <div class="co-stepper">
                    <span class="co-step active"><span class="num">1</span> {{ __('Isi') }}</span>
                    <span class="co-step-line"></span>
                    <span class="co-step"><span class="num loading"></span> {{ __('Bayar') }}</span>
                    <span class="co-step-line"></span>
                    <span class="co-step"><span class="num loading"></span> {{ __('Selesai') }}</span>
                </div>
            </div>

            @if (session('toast'))
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md flex items-center gap-sm">
                    <span class="material-symbols-outlined text-secondary">task_alt</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">{{ session('toast')['message'] ?? session('toast') }}</p>
                </div>
            @endif

            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
                <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('TOP UP SALDO') }}</p>
                <h2 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Masukkan Nominal') }}</h2>

                <form method="POST" action="{{ route('customer.saldo.topup') }}" class="space-y-sm" id="form-isi-saldo">
                    @csrf
                    <div class="flex flex-wrap gap-sm">
                        @foreach ($nominalCepat as $nominal)
                            <button type="button" data-nominal="{{ $nominal }}"
                                class="chip-quick px-4 py-2 rounded-full font-label-caps text-label-caps uppercase tracking-widest transition-colors">
                                Rp {{ number_format($nominal, 0, ',', '.') }}
                            </button>
                        @endforeach
                    </div>
                    <div class="flex flex-col sm:flex-row gap-sm">
                        <div class="relative flex-1">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-body-sm text-body-sm font-semibold text-secondary pointer-events-none">Rp</span>
                            <input type="text" inputmode="numeric" autocomplete="off" name="nominal" id="input-nominal" min="{{ $minNominal }}" max="{{ $maxNominal }}"
                                placeholder="{{ __('Nominal topup (min :min)', ['min' => number_format($minNominal, 0, ',', '.')]) }}"
                                class="w-full border border-outline-variant rounded-xl pl-11 pr-md py-3 bg-surface-container-low text-on-surface outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/30 transition-colors"
                                value="{{ old('nominal') ? number_format((int) preg_replace('/\D/', '', (string) old('nominal')), 0, ',', '.') : '' }}" />
                        </div>
                        <button type="submit"
                            class="btn-gold inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                            <span class="material-symbols-outlined text-[20px]">add_card</span>
                            <span>{{ __('Isi Saldo') }}</span>
                        </button>
                    </div>
                    @error('nominal')
                        <p class="font-label-sm text-label-sm text-error">{{ $message }}</p>
                    @enderror
                    <button type="button" id="btn-batal-nominal" class="hidden font-label-sm text-label-sm text-on-surface-variant hover:underline">{{ __('Bersihkan nominal') }}</button>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed pt-sm">{{ __('Transfer sesuai nominal di atas, lalu unggah bukti untuk diverifikasi Super Admin.') }}</p>
                </form>
            </div>

        </div>
    </main>

    @include('customer._partials.bottom-nav')
    @include('customer._partials.drawer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var chips = document.querySelectorAll('.chip-quick');
            var input = document.getElementById('input-nominal');
            var form = document.getElementById('form-isi-saldo');
            var btnBatal = document.getElementById('btn-batal-nominal');

            var digitsOnly = function(v) { return (v || '').replace(/\D/g, ''); };
            var formatRibuan = function(v) {
                var d = digitsOnly(v).replace(/^0+(?=\d)/, '');
                if (!d) return '';
                return d.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            };
            var syncChips = function() {
                var raw = digitsOnly(input ? input.value : '');
                chips.forEach(function(x) {
                    x.classList.toggle('active', x.getAttribute('data-nominal') === raw);
                });
                if (btnBatal) btnBatal.classList.toggle('hidden', raw === '');
            };

            chips.forEach(function(c) {
                c.addEventListener('click', function() {
                    chips.forEach(function(x) { x.classList.remove('active'); });
                    c.classList.add('active');
                    if (input) {
                        input.value = formatRibuan(c.getAttribute('data-nominal'));
                        if (btnBatal) btnBatal.classList.remove('hidden');
                    }
                });
            });
            if (input) {
                if (input.value) input.value = formatRibuan(input.value);
                input.addEventListener('input', function() {
                    input.value = formatRibuan(input.value);
                    syncChips();
                });
                if (btnBatal) {
                    btnBatal.addEventListener('click', function() {
                        input.value = '';
                        chips.forEach(function(x) { x.classList.remove('active'); });
                        btnBatal.classList.add('hidden');
                    });
                }
                syncChips();
            }
            if (form && input) {
                form.addEventListener('submit', function() {
                    input.value = digitsOnly(input.value);
                });
            }
        });
    </script>
</body>
</html>