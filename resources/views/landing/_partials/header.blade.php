@php
    $logo = brand_logo_url();
    $navItems = [
        ['href' => route('landing').'#beranda', 'label' => __('landing.nav.home')],
        ['href' => route('landing').'#tentang', 'label' => __('landing.nav.about')],
        ['href' => route('landing').'#fitur', 'label' => __('landing.nav.features')],
        ['href' => route('landing').'#cara-kerja', 'label' => __('landing.nav.how_it_works')],
        ['href' => route('landing').'#untuk-siapa', 'label' => __('landing.nav.for_who')],
        ['href' => route('landing').'#faq', 'label' => __('landing.nav.faq')],
    ];
@endphp

<header id="site-header" class="site-header fixed inset-x-0 top-0 z-50">
    <div class="mx-auto max-w-8xl px-5 sm:px-8">
        <div class="flex h-20 items-center justify-between gap-4">
            <a href="{{ route('landing') }}#beranda" class="group inline-flex min-w-0 items-center gap-3" aria-label="{{ brand() }}">
                @if ($logo !== '')
                    <img alt="{{ brand() }}" class="h-7 w-auto max-w-[9rem] object-contain" src="{{ $logo }}" />
                @else
                    <span class="brand-wordmark truncate text-[1.2rem] text-on-surface sm:text-[1.45rem]">{{ brand() }}</span>
                @endif
                <span class="hidden border-l border-muted-border pl-3 text-[9px] font-bold uppercase leading-4 tracking-[0.18em] text-on-surface-variant md:inline">{{ __('landing.nav.tagline') }}</span>
            </a>

            <nav class="hidden items-center gap-6 xl:flex" aria-label="{{ __('landing.nav.menu') }}">
                @foreach ($navItems as $item)
                    <a class="nav-link" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="hidden items-center gap-3 xl:flex">
                @include('partials.locale-switcher')
                <button type="button" data-theme-toggle aria-pressed="false" aria-label="{{ __('landing.nav.switch_theme') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-muted-border bg-surface-container-lowest text-on-surface-variant transition-colors hover:border-gold-accent/40 hover:text-gold-accent">
                    <span class="material-symbols-outlined theme-icon-light text-[18px]">dark_mode</span>
                    <span class="material-symbols-outlined theme-icon-dark text-[18px]">light_mode</span>
                </button>
                @if ($dashboardUrl)
                    <a href="{{ $dashboardUrl }}" class="text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface transition-colors hover:text-gold-accent">{{ __('landing.nav.dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface transition-colors hover:text-gold-accent">{{ __('landing.nav.login') }}</a>
                @endif
                <a href="{{ route('register') }}" class="btn-primary !px-4 !py-2.5 !text-[10px]">{{ __('landing.nav.register_owner') }}</a>
            </div>

            <div class="flex items-center gap-2 xl:hidden">
                <button type="button" data-theme-toggle aria-pressed="false" aria-label="{{ __('landing.nav.switch_theme') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-muted-border bg-surface-container-lowest text-on-surface-variant">
                    <span class="material-symbols-outlined theme-icon-light text-[18px]">dark_mode</span>
                    <span class="material-symbols-outlined theme-icon-dark text-[18px]">light_mode</span>
                </button>
                <button type="button" data-mobile-menu-toggle aria-expanded="false" aria-controls="mobile-menu" aria-label="{{ __('landing.nav.menu') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-muted-border bg-surface-container-lowest text-on-surface">
                    <span class="material-symbols-outlined text-[20px]">menu</span>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="mobile-menu xl:hidden">
        <div class="mx-auto max-w-8xl space-y-4 px-5 py-5 sm:px-8">
            <nav class="grid gap-1" aria-label="{{ __('landing.nav.menu') }}">
                @foreach ($navItems as $item)
                    <a class="rounded-xl px-3 py-3 text-sm font-bold text-on-surface transition-colors hover:bg-surface-container-low" href="{{ $item['href'] }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="flex items-center justify-between gap-3 border-t border-muted-border pt-4">
                @include('partials.locale-switcher')
                <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface-variant">{{ __('landing.nav.tagline') }}</span>
            </div>

            <div class="flex items-center gap-3">
                @if ($dashboardUrl)
                    <a href="{{ $dashboardUrl }}" class="btn-secondary flex-1 !py-3">{{ __('landing.nav.dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary flex-1 !py-3">{{ __('landing.nav.login') }}</a>
                @endif
                <a href="{{ route('register') }}" class="btn-primary flex-1 !py-3">{{ __('landing.nav.register_owner') }}</a>
            </div>
        </div>
    </div>
</header>
