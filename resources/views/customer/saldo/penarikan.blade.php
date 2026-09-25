<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - {{ __('Penarikan Saldo') }}</title>
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
        .detail-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .65rem 0; border-bottom: 1px solid var(--border-soft); font-size: 14px; }
        .detail-row:last-child { border-bottom: 0; }
    </style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] lg:pl-72">

@php
    $st = $penarikan->status;
    $isDone = in_array($st, ['dibayar', 'ditolak', 'dibatalkan'], true);
    $isStep2 = in_array($st, ['pending', 'disetujui'], true);
    $tujuanLabel = $penarikan->tipe_tujuan === 'bank'
        ? (($penarikan->bank?->nama_bank ?? 'Bank') . ' • ' . $penarikan->nomor_tujuan)
        : (($penarikan->penyedia ?? 'E-Wallet') . ' • ' . $penarikan->nomor_tujuan);
@endphp

    <header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
        <a href="{{ route('customer.saldo') }}" aria-label="Back" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
            <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
        </a>
        <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('Penarikan Saldo') }}</h1>
        <div class="w-10"></div>
    </header>

    <main class="pt-6 pb-10 w-full overflow-x-hidden">
        <div class="mx-auto max-w-[900px] px-container-margin space-y-lg">

            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-sm mb-md flex justify-center">
                <div class="co-stepper">
                    <a href="{{ route('customer.saldo') }}" class="co-step done"><span class="num"><span class="material-symbols-outlined text-[14px]">check</span></span> {{ __('Pengajuan') }}</a>
                    <span class="co-step-line {{ $isDone ? 'done' : '' }}"></span>
                    @if ($isStep2)
                        <span class="co-step active"><span class="num">2</span> {{ __('Verifikasi') }}</span>
                    @else
                        <span class="co-step done"><span class="num"><span class="material-symbols-outlined text-[14px]">check</span></span> {{ __('Verifikasi') }}</span>
                    @endif
                    <span class="co-step-line {{ $isDone ? 'done' : '' }}"></span>
                    @if ($isDone)
                        <span class="co-step active"><span class="num">3</span> {{ __('Selesai') }}</span>
                    @else
                        <span class="co-step"><span class="num loading"></span> {{ __('Selesai') }}</span>
                    @endif
                </div>
            </div>

            @if (session('toast'))
                @php
                    $toastIcon2 = is_array(session('toast')) ? (session('toast')['icon'] ?? '') : '';
                    $isErr2 = in_array($toastIcon2, ['gpp_maybe', 'block', 'error'], true);
                    $isOk2 = in_array($toastIcon2, ['task_alt', 'check', 'check_circle'], true);
                @endphp
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md flex items-center gap-sm">
                    @if ($isErr2)
                        <span class="material-symbols-outlined text-error">close</span>
                    @elseif ($isOk2)
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    @else
                        <span class="material-symbols-outlined text-secondary">task_alt</span>
                    @endif
                    <p class="font-body-sm text-body-sm text-on-surface-variant">{{ is_array(session('toast')) ? (session('toast')['message'] ?? '') : session('toast') }}</p>
                </div>
            @endif

            @if ($st === 'pending' || $st === 'disetujui')
                <div class="bg-surface-container-low border border-outline-variant rounded-xl p-md mb-lg flex items-center gap-sm">
                    <span class="material-symbols-outlined text-secondary">hourglass_top</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">
                        {{ $st === 'pending' ? __('Pengajuan penarikan sedang diverifikasi oleh Super Admin.') : __('Penarikan disetujui dan segera ditransfer.') }}
                    </p>
                </div>
            @elseif ($st === 'ditolak')
                <div class="bg-surface-container-low border border-error rounded-xl p-md mb-lg flex items-center gap-sm">
                    <span class="material-symbols-outlined text-error">error</span>
                    <p class="font-body-sm text-body-sm text-error">
                        {{ __('Penarikan ditolak. Saldo Anda telah dikembalikan.') }}{{ $penarikan->catatan_admin ? ' ' . __('Catatan:') . ' ' . $penarikan->catatan_admin : '' }}
                    </p>
                </div>
            @elseif ($st === 'dibatalkan')
                <div class="bg-surface-container-low border border-outline-variant rounded-xl p-md mb-lg flex items-center gap-sm">
                    <span class="material-symbols-outlined text-on-surface-variant">cancel</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Penarikan dibatalkan dan saldo telah dikembalikan.') }}</p>
                </div>
            @elseif ($st === 'dibayar')
                <div class="bg-surface-container-low border border-outline-variant rounded-xl p-md mb-lg flex items-center gap-sm">
                    <span class="material-symbols-outlined text-secondary">task_alt</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Dana telah ditransfer ke tujuan Anda.') }}</p>
                </div>
            @endif

            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
                <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('RINCIAN PENARIKAN') }}</p>
                <h2 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Penarikan') }} #{{ $penarikan->customer_withdrawal_id }}</h2>
                <div class="detail-row"><span class="text-on-surface-variant">{{ __('Nominal') }}</span><strong>Rp {{ number_format((float) $penarikan->jumlah, 0, ',', '.') }}</strong></div>
                <div class="detail-row"><span class="text-on-surface-variant">{{ __('Biaya platform') }}</span><strong>Rp {{ number_format((float) $penarikan->fee, 0, ',', '.') }}</strong></div>
                <div class="detail-row"><span class="text-on-surface-variant">{{ __('Diterima bersih') }}</span><strong class="text-[var(--chrome-accent)]">Rp {{ number_format((float) $penarikan->jumlah_bersih, 0, ',', '.') }}</strong></div>
                <div class="detail-row"><span class="text-on-surface-variant">{{ __('Tujuan') }}</span><strong class="text-right">{{ $tujuanLabel }}</strong></div>
                @if ($penarikan->nama_pemilik)
                    <div class="detail-row"><span class="text-on-surface-variant">{{ __('Nama pemilik') }}</span><strong>{{ $penarikan->nama_pemilik }}</strong></div>
                @endif
                <div class="detail-row"><span class="text-on-surface-variant">{{ __('Diajukan') }}</span><strong>{{ $penarikan->diajukan_pada?->format('d M Y, H:i') ?? '-' }}</strong></div>
                <div class="detail-row"><span class="text-on-surface-variant">{{ __('Status') }}</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        @if ($st === 'pending') bg-amber-100 text-amber-800
                        @elseif ($st === 'disetujui') bg-blue-100 text-blue-800
                        @elseif ($st === 'dibayar') bg-emerald-100 text-emerald-800
                        @elseif ($st === 'ditolak') bg-red-100 text-red-800
                        @else bg-surface-container text-on-surface-variant @endif
                    ">{{ $st }}</span>
                </div>

                @if ($st === 'pending')
                    <form method="POST" action="{{ route('customer.saldo.tarik.batal', $penarikan->customer_withdrawal_id) }}" onsubmit="return confirm('{{ __('Batalkan penarikan ini? Saldo akan dikembalikan.') }}')" class="mt-lg">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest border border-outline text-on-surface hover:bg-surface-container-high transition-colors">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                            <span>{{ __('Batalkan Penarikan') }}</span>
                        </button>
                    </form>
                @endif
            </div>

            @if ($st === 'dibayar' && $penarikan->file_bukti)
                @php
                    $buktiSAUrl = asset('storage/' . ltrim($penarikan->file_bukti, '/'));
                    $buktiSAExt = strtolower(pathinfo($penarikan->file_bukti, PATHINFO_EXTENSION));
                    $buktiSANama = \Illuminate\Support\Str::afterLast($penarikan->file_bukti, '/');
                @endphp
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
                    <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('BUKTI TRANSFER SUPER ADMIN') }}</p>
                    <div class="rounded-xl border border-emerald-300/40 bg-emerald-50 p-2">
                        @if (in_array($buktiSAExt, ['jpg', 'jpeg', 'png'], true))
                            <a href="{{ $buktiSAUrl }}" target="_blank" rel="noopener" class="block hover:opacity-90 transition-opacity">
                                <img src="{{ $buktiSAUrl }}" alt="{{ $buktiSANama }}" class="w-full max-h-64 h-auto object-contain rounded-lg" loading="lazy" />
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-2 flex items-center justify-center gap-1"><span class="material-symbols-outlined text-[16px]">visibility</span>{{ __('Perbesar foto') }}</p>
                            </a>
                        @else
                            <a href="{{ $buktiSAUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-3 py-3">
                                <span class="material-symbols-outlined text-[18px]">description</span>
                                <span class="font-body-sm text-body-sm text-on-surface truncate">{{ $buktiSANama }}</span>
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        @endif
                        @if ($penarikan->deskripsi_bukti)
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">{{ $penarikan->deskripsi_bukti }}</p>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </main>

    @include('customer._partials.bottom-nav')
    @include('customer._partials.drawer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var url = '{{ route('customer.saldo.tarik.status', $penarikan->customer_withdrawal_id) }}';
            var finished = {{ $isDone ? 'true' : 'false' }};
            if (finished) return;
            setInterval(function() {
                fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data && data.done) window.location.reload();
                    })
                    .catch(function () {});
            }, 6000);
        });
    </script>
</body>
</html>
