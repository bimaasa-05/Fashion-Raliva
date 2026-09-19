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
                        "on-tertiary-fixed-variant": "#464745", "on-background": "#1b1c1c", "gold-accent": "#C9A24D"
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
        .tp-pending { background: #FFFBEB; border-color: #FCD34D; }
        .tp-verif { background: #EFF6FF; border-color: #93C5FD; }
        .tp-ditolak { background: #FEF2F2; border-color: #FCA5A5; }
        html.theme-dark .tp-pending { background: rgba(251,191,36,.12); border-color: rgba(251,191,36,.4); }
        html.theme-dark .tp-verif { background: rgba(59,130,246,.12); border-color: rgba(59,130,246,.4); }
        html.theme-dark .tp-ditolak { background: rgba(239,68,68,.12); border-color: rgba(239,68,68,.4); }
        .tp-icon { display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 9999px; }
        .tp-icon.ic-pending { background: rgba(252,211,77,.28); color: #B45309; }
        .tp-icon.ic-verif { background: rgba(147,197,253,.32); color: #2563EB; }
        .tp-icon.ic-ditolak { background: rgba(252,165,165,.32); color: #DC2626; }
        html.theme-dark .tp-icon.ic-pending { color: #FCD34D; }
        html.theme-dark .tp-icon.ic-verif { color: #93C5FD; }
        html.theme-dark .tp-icon.ic-ditolak { color: #FCA5A5; }
        .tp-pulse { animation: tp-pulse 2s ease-in-out infinite; }
        @keyframes tp-pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(59,130,246,.35); } 50% { box-shadow: 0 0 0 10px rgba(59,130,246,0); } }
        .tp-shake { animation: tp-shake 1.6s ease-in-out infinite; }
        @keyframes tp-shake { 0%,100% { transform: translateX(0); } 20% { transform: translateX(-2px); } 40% { transform: translateX(2px); } 60% { transform: translateX(-1px); } 80% { transform: translateX(1px); } }
        .tp-breathe { animation: tp-breathe 2.4s ease-in-out infinite; }
        @keyframes tp-breathe { 0%,100% { opacity: 1; transform: scale(1); } 50% { opacity: .5; transform: scale(.9); } }
        @media (prefers-reduced-motion: reduce) { .tp-pulse, .tp-shake, .tp-breathe { animation: none; } }
        .raliva-bar { transition: height 0.9s cubic-bezier(0.22, 1, 0.36, 1); }
        @media (prefers-reduced-motion: reduce) { .raliva-bar { transition: none; } }
        [data-bars] { opacity: 1; transform: translateY(0); transition: opacity .55s cubic-bezier(0.22, 1, 0.36, 1), transform .55s cubic-bezier(0.22, 1, 0.36, 1); }
        [data-bars].chart-hide { opacity: 0; transform: translateY(12px); }
        .col-enter { animation: colFadeUp .6s cubic-bezier(0.22, 1, 0.36, 1) both; }
        @keyframes colFadeUp { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
        @media (prefers-reduced-motion: reduce) { .col-enter { animation: none; } [data-bars] { transition: none; } }
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
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg items-center">
                    <div class="min-w-0">
                        <div class="flex items-center justify-between gap-sm">
                            <p class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)]">{{ __('SALDO TERSEDIA') }}</p>
                            <a href="{{ route('customer.saldo.isi') }}"
                                class="inline-flex items-center gap-1 px-3.5 py-2 rounded-full border border-[var(--chrome-accent)]/25 bg-[var(--chrome-accent)]/5 text-[var(--chrome-accent)] font-label-sm text-label-sm uppercase tracking-wider hover:bg-[var(--chrome-accent)]/10 transition-colors whitespace-nowrap">
                                <span class="material-symbols-outlined text-[16px]">add</span>
                                <span>{{ __('Isi Saldo') }}</span>
                            </a>
                        </div>
                        <p class="font-display-lg text-display-lg text-on-surface mt-sm">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                        <p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">{{ __('Top up saldo untuk berbelanja lebih mudah.') }}</p>
                    </div>
                    <div class="border-t lg:border-t-0 lg:border-l border-[var(--border-soft)] pt-lg lg:pt-0 lg:pl-lg">
                        <div class="grid grid-cols-2 gap-gutter mb-md">
                            <button type="button" data-chart-mode="in"
                                class="flex items-center justify-center gap-xs py-sm border-2 rounded-DEFAULT bg-surface-container-low cursor-pointer hover:border-emerald-500 transition-colors font-body-sm text-body-sm">
                                <span class="material-symbols-outlined text-[20px]">trending_up</span>
                                <span>{{ __('Pemasukan') }}</span>
                            </button>
                            <button type="button" data-chart-mode="out"
                                class="flex items-center justify-center gap-xs py-sm border-2 rounded-DEFAULT bg-surface-container-low cursor-pointer hover:border-secondary transition-colors font-body-sm text-body-sm">
                                <span class="material-symbols-outlined text-[20px]">trending_down</span>
                                <span>{{ __('Pengeluaran') }}</span>
                            </button>
                        </div>
                        <h3 class="atl-eyebrow font-label-caps text-label-caps uppercase tracking-widest text-[var(--chrome-accent)]">{{ __('Aktivitas Saldo') }}</h3>
                        <p id="chart-subline" class="font-label-sm text-label-sm text-on-surface-variant mt-1 mb-sm">{{ __('Pemasukan saldo per bulan — 6 bulan terakhir') }}</p>
                        <div class="h-48" data-bars></div>
                    </div>
                </div>
            </div>

            @if ($activeTopups->isNotEmpty())
                <div class="bg-surface-container-lowest border border-[var(--border-soft)] rounded-xl md:rounded-2xl p-md md:p-lg card-premium">
                    <h3 class="premium-heading font-title-md text-title-md text-on-surface mb-md">{{ __('Top Up Berjalan') }}</h3>
                    <div class="space-y-sm">
                        @foreach ($activeTopups as $tp)
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-sm rounded-xl p-md border-l-4
                                    @if($tp->status === \App\Models\CustomerTopup::STATUS_MENUNGGU_VERIFIKASI) tp-verif border-blue-500
                                    @elseif($tp->status === \App\Models\CustomerTopup::STATUS_DITOLAK) tp-ditolak border-red-500
                                    @else tp-pending border-amber-500 @endif">
                                <div class="flex items-center gap-md">
                                    <span class="tp-icon
                                        @if($tp->status === \App\Models\CustomerTopup::STATUS_MENUNGGU_VERIFIKASI) ic-verif tp-pulse
                                        @elseif($tp->status === \App\Models\CustomerTopup::STATUS_DITOLAK) ic-ditolak tp-shake
                                        @else ic-pending tp-breathe @endif">
                                        <span class="material-symbols-outlined text-[26px]">
                                            {{ $tp->status === \App\Models\CustomerTopup::STATUS_MENUNGGU_VERIFIKASI ? 'hourglass_top' : ($tp->status === \App\Models\CustomerTopup::STATUS_DITOLAK ? 'error' : 'schedule') }}
                                        </span>
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
                                        @if($tp->status === \App\Models\CustomerTopup::STATUS_MENUNGGU_VERIFIKASI) {{ __('Menunggu Verifikasi') }}
                                        @elseif($tp->status === \App\Models\CustomerTopup::STATUS_DITOLAK) {{ __('Ditolak') }}
                                        @else {{ __('Menunggu Pembayaran') }} @endif
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.querySelector('[data-bars]');
            if (!el) return;
            var para = @json($chart);
            var keluar = @json($chartKeluar);
            var mode = 'in';
            var subline = document.getElementById('chart-subline');

            function shortNum(v) {
                v = Math.round(v || 0);
                if (v >= 1000000) return (v / 1000000).toLocaleString('id-ID', { maximumFractionDigits: 2 }) + 'jt';
                if (v >= 1000) return (v / 1000).toLocaleString('id-ID', { maximumFractionDigits: 2 }) + 'k';
                return String(v);
            }

            function renderBars() {
                var data = mode === 'in' ? para : keluar;
                el.classList.remove('flex', 'items-center', 'justify-center', 'items-end', 'gap-2', 'md:gap-3');
                el.innerHTML = '';
                var hasData = data.some(function(s) { return (s.value || 0) > 0; });
                if (!hasData) {
                    el.classList.add('flex', 'items-center', 'justify-center');
                    el.innerHTML = '<div class="w-full flex flex-col items-center justify-center py-6 text-center gap-2 text-on-surface-variant">'
                        + '<span class="material-symbols-outlined text-[28px] opacity-50">bar_chart</span>'
                        + '<p class="font-body-sm text-body-sm">' + '{{ __('Belum ada aktivitas saldo.') }}' + '</p></div>';
                    return;
                }
                el.classList.add('flex', 'items-end', 'gap-2', 'md:gap-3');
                var barCls = mode === 'in'
                    ? 'w-full max-w-[36px] rounded-t-md raliva-bar bg-gradient-to-t from-emerald-500/45 to-emerald-500 hover:from-emerald-500/70 hover:shadow-[0_0_12px_rgba(16,185,129,0.35)] transition-shadow'
                    : 'w-full max-w-[36px] rounded-t-md raliva-bar bg-gradient-to-t from-[#BA1A1A]/45 to-[#BA1A1A] hover:from-[#BA1A1A]/70 hover:shadow-[0_0_12px_rgba(186,26,26,0.35)] transition-shadow';
                var max = Math.max.apply(null, data.map(function(s) { return s.value || 0; })) || 1;

                data.forEach(function(s, i) {
                    var pct = Math.round(((s.value || 0) / max) * 100);
                    var col = document.createElement('div');
                    col.className = 'flex-1 min-w-0 flex flex-col items-center justify-end gap-2 h-full col-enter';
                    col.style.animationDelay = (i * 80) + 'ms';

                    var val = document.createElement('span');
                    val.className = 'text-[10px] font-bold text-on-surface leading-none';
                    val.textContent = shortNum(s.value || 0);

                    var barZone = document.createElement('div');
                    barZone.className = 'w-full h-full flex items-end justify-center';
                    var bar = document.createElement('div');
                    bar.className = barCls;
                    bar.style.height = '0%';
                    bar.title = (s.label || '') + ': ' + shortNum(s.value || 0);
                    barZone.appendChild(bar);

                    var lab = document.createElement('span');
                    lab.className = 'font-label-sm text-[10px] uppercase tracking-wide text-on-surface-variant truncate max-w-full';
                    lab.textContent = s.label || '';

                    col.appendChild(val); col.appendChild(barZone); col.appendChild(lab);
                    el.appendChild(col);
                    setTimeout(function() { bar.style.height = Math.max(pct, 4) + '%'; }, 140 + i * 90);
                });
            }

            var renderTimer = null;

            function switchMode(m) {
                if (m === mode) return;
                clearTimeout(renderTimer);
                el.classList.add('chart-hide');
                renderTimer = setTimeout(function() {
                    mode = m;
                    setActiveMode(m);
                    subline.textContent = mode === 'in'
                        ? 'Pemasukan saldo per bulan - 6 bulan terakhir'
                        : 'Pengeluaran saldo per bulan - 6 bulan terakhir';
                    renderBars();
                    void el.offsetWidth;
                    el.classList.remove('chart-hide');
                }, 260);
            }

            function setActiveMode(m) {
                document.querySelectorAll('[data-chart-mode]').forEach(function(b) {
                    var on = b.getAttribute('data-chart-mode') === m;
                    b.classList.toggle('font-semibold', on);
                    if (b.getAttribute('data-chart-mode') === 'in') {
                        b.classList.toggle('border-emerald-500', on);
                        b.classList.toggle('text-emerald-600', on);
                        b.classList.toggle('border-outline-variant', !on);
                    } else {
                        b.classList.toggle('border-secondary', on);
                        b.classList.toggle('border-outline-variant', !on);
                    }
                });
            }

            document.querySelectorAll('[data-chart-mode]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    switchMode(btn.getAttribute('data-chart-mode'));
                });
            });

            setActiveMode('in');
            el.classList.add('chart-hide');
            renderBars();
            void el.offsetWidth;
            requestAnimationFrame(function() { el.classList.remove('chart-hide'); });
        });
    </script>
</body>
</html>