<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>RALIVA - {{ __('Saldo Akun') }}</title>
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
        html.theme-dark .bg-surface-container { background-color: #262524 !important; }
        html.theme-dark .bg-surface-container-high { background-color: #2c2b2a !important; }
        html.theme-dark .bg-surface-container-highest, html.theme-dark .bg-surface-variant { background-color: #323130 !important; }
        html.theme-dark .text-on-surface, html.theme-dark .text-on-background { color: #e6e4e1 !important; }
        html.theme-dark .text-on-surface-variant { color: #b9b6b1 !important; }
        html.theme-dark .text-outline { color: #8a8781 !important; }
        html.theme-dark .text-secondary { color: #8B1E3F !important; }
        html.theme-dark .border-outline-variant { border-color: #3a3937 !important; }
        html.theme-dark .border-outline { border-color: #4a4844 !important; }
        html.theme-dark .bg-primary { background-color: #f2efec !important; }
        html.theme-dark .text-on-primary { color: #1b1a19 !important; }
        html.theme-dark .hover\:bg-surface-container-low:hover { background-color: #201f1e !important; }
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
    </style>
</head>
<body class="bg-surface text-on-surface antialiased min-h-screen flex flex-col pb-[72px] lg:pl-72">

    <header class="bg-[var(--chrome-bg-soft)] backdrop-blur-md text-[var(--chrome-text)] flex justify-between items-center w-full px-container-margin h-16 sticky top-0 z-40 border-b border-[var(--chrome-border)]">
        <a href="{{ route('customer.account') }}" aria-label="Back" class="p-2 -ml-2 hover:opacity-70 transition-all duration-200 flex">
            <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
        </a>
        <h1 class="font-display-lg text-headline-md tracking-widest text-[var(--chrome-accent)] uppercase truncate max-w-[200px] text-center">{{ __('Saldo Akun') }}</h1>
        <div class="w-10"></div>
    </header>

    <main class="pt-6 pb-10 w-full overflow-x-hidden">
        <div class="mx-auto max-w-[1400px] px-container-margin space-y-lg">

            @if (session('toast'))
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl p-md flex items-center gap-sm">
                    <span class="material-symbols-outlined text-secondary">task_alt</span>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">{{ session('toast')['message'] ?? session('toast') }}</p>
                </div>
            @endif

            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-lg items-end">
                    <div>
                        <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)] mb-xs">{{ __('SALDO TERSEDIA') }}</p>
                        <p class="font-display-lg text-display-lg text-on-surface">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                        <div class="grid grid-cols-2 gap-sm mt-md max-w-md">
                            <div class="bg-surface-container-low/60 border border-[var(--border-soft)] rounded-xl p-md">
                                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Total Top Up') }}</p>
                                <p class="font-body-lg text-body-lg font-semibold text-on-surface">Rp {{ number_format($totalTopup, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-surface-container-low/60 border border-[var(--border-soft)] rounded-xl p-md">
                                <p class="font-label-sm text-label-sm text-on-surface-variant">{{ __('Total Terpakai') }}</p>
                                <p class="font-body-lg text-body-lg font-semibold text-on-surface">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:text-right">
                        <a href="{{ route('customer.saldo.isi') }}"
                            class="btn-gold inline-flex items-center justify-center gap-2 px-xl py-3 rounded-full font-label-caps text-label-caps uppercase tracking-widest">
                            <span class="material-symbols-outlined text-[20px]">add_card</span>
                            <span>{{ __('Isi Saldo') }}</span>
                        </a>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-sm">{{ __('Top up saldo untuk berbelanja lebih mudah.') }}</p>
                    </div>
                </div>
            </div>

            @if ($activeTopups->isNotEmpty())
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
                    <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Top Up Berjalan') }}</h3>
                    <div class="space-y-sm">
                        @foreach ($activeTopups as $tp)
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-sm border border-outline-variant rounded-xl p-md bg-surface-container-low/40">
                                <div class="flex items-center gap-md">
                                    <span class="material-symbols-outlined text-[28px] text-on-surface-variant">
                                        {{ $tp->status === \App\Models\CustomerTopup::STATUS_MENUNGGU_VERIFIKASI ? 'hourglass_top' : ($tp->status === \App\Models\CustomerTopup::STATUS_DITOLAK ? 'error' : 'schedule') }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-body-lg text-body-lg font-semibold text-on-surface">Rp {{ number_format((float) $tp->jumlah, 0, ',', '.') }}</p>
                                        <p class="font-label-sm text-label-sm text-on-surface-variant">#{{ $tp->customer_topup_id }} • {{ $tp->payment?->paymentMethod?->nama_metode ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-sm">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($tp->status === \App\Models\CustomerTopup::STATUS_MENUNGGU_VERIFIKASI) bg-blue-100 text-blue-800
                                        @elseif($tp->status === \App\Models\CustomerTopup::STATUS_DITOLAK) bg-red-100 text-red-800
                                        @else bg-amber-100 text-amber-800 @endif">
                                        {{ $tp->status }}
                                    </span>
                                    @if (in_array($tp->status, [\App\Models\CustomerTopup::STATUS_PENDING, \App\Models\CustomerTopup::STATUS_DITOLAK], true))
                                        <a href="{{ route('customer.saldo.topup.payment', $tp->customer_topup_id) }}"
                                            class="font-label-caps text-label-caps uppercase tracking-widest text-secondary hover:underline whitespace-nowrap">{{ __('Bayar / Upload') }}</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
                <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Riwayat Transaksi') }}</h3>
                @if ($transactions->isEmpty())
                    <p class="font-body-sm text-body-sm text-on-surface-variant">{{ __('Belum ada transaksi saldo.') }}</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="font-label-caps text-label-caps uppercase tracking-widest text-on-surface-variant border-b border-outline-variant">
                                    <th class="py-2 pr-md">{{ __('Tanggal') }}</th>
                                    <th class="py-2 pr-md">{{ __('Jenis') }}</th>
                                    <th class="py-2 pr-md">{{ __('Jumlah') }}</th>
                                    <th class="py-2">{{ __('Saldo Akhir') }}</th>
                                </tr>
                            </thead>
                            <tbody class="font-body-sm text-body-sm">
                                @foreach ($transactions as $trx)
                                    <tr class="border-b border-outline-variant/60">
                                        <td class="py-3 pr-md text-on-surface-variant">{{ $trx->created_at->format('d M Y, H:i') }}</td>
                                        <td class="py-3 pr-md">
                                            <span class="inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[16px] text-on-surface-variant">
                                                    {{ $trx->jenis_transaksi === \App\Models\CustomerWalletTransaction::JENIS_TOPUP ? 'add_circle' : ($trx->jenis_transaksi === \App\Models\CustomerWalletTransaction::JENIS_REFUND_MASUK ? 'undo' : 'remove_circle') }}
                                                </span>
                                                <span>{{ $trx->jenis_transaksi }}</span>
                                            </span>
                                        </td>
                                        <td class="py-3 pr-md font-semibold
                                            @if (in_array($trx->jenis_transaksi, [\App\Models\CustomerWalletTransaction::JENIS_TOPUP, \App\Models\CustomerWalletTransaction::JENIS_REFUND_MASUK], true)) text-emerald-700 @else text-red-700 @endif">
                                            @if (in_array($trx->jenis_transaksi, [\App\Models\CustomerWalletTransaction::JENIS_TOPUP, \App\Models\CustomerWalletTransaction::JENIS_REFUND_MASUK], true)) + @else - @endif
                                            Rp {{ number_format((float) $trx->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 text-on-surface">Rp {{ number_format((float) $trx->saldo_sesudah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-sm">{{ $transactions->links() }}</div>
                @endif
            </div>

        </div>
    </main>

    @include('customer._partials.bottom-nav')
    @include('customer._partials.drawer')

</body>
</html>