@php($logo = brand_logo_url())

<header id="site-header" class="site-header fixed inset-x-0 top-0 z-50">
    <div class="mx-auto max-w-8xl px-5 sm:px-8">
        <div class="flex h-20 items-center justify-between gap-4">
            <a href="{{ route('landing') }}" class="group inline-flex min-w-0 items-center gap-3" aria-label="{{ brand() }}">
                @if ($logo !== '')
                    <img alt="{{ brand() }}" class="h-7 w-auto max-w-[9rem] object-contain" src="{{ $logo }}" />
                @else
                    <span class="brand-wordmark truncate text-[1.2rem] text-on-surface sm:text-[1.45rem]">{{ brand() }}</span>
                @endif
                <span class="hidden border-l border-muted-border pl-3 text-[9px] font-bold uppercase leading-4 tracking-[0.18em] text-on-surface-variant md:inline">{{ __('landing.nav.tagline') }}</span>
            </a>

            <nav class="hidden items-center gap-7 lg:flex" aria-label="{{ __('landing.nav.menu') }}">
                <a class="nav-link" href="{{ route('landing.brand') }}" @if (request()->routeIs('landing.brand')) aria-current="page" @endif>{{ __('landing.nav.for_brand') }}</a>
                <a class="nav-link" href="{{ route('landing.open-store') }}" @if (request()->routeIs('landing.open-store')) aria-current="page" @endif>{{ __('landing.nav.open_store') }}</a>
                <a class="nav-link" href="{{ route('landing.brand') }}#fitur">{{ __('landing.nav.features') }}</a>
                <a class="nav-link" href="{{ route('landing.open-store') }}#faq">{{ __('landing.nav.faq') }}</a>
            </nav>

            <div class="hidden items-center gap-3 lg:flex">
                <a href="{{ route('customer.home') }}" class="text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface-variant transition-colors hover:text-gold-accent">{{ __('landing.nav.buyer') }}</a>
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
                <a href="{{ route('landing.open-store') }}" class="btn-primary !px-4 !py-2.5 !text-[10px]">{{ __('landing.nav.open_store') }}</a>
            </div>

            <div class="flex items-center gap-2 lg:hidden">
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

    <div id="mobile-menu" class="mobile-menu lg:hidden">
        <div class="mx-auto max-w-8xl space-y-4 px-5 py-5 sm:px-8">
            <nav class="grid gap-1" aria-label="{{ __('landing.nav.menu') }}">
                <a class="rounded-xl px-3 py-3 text-sm font-bold text-on-surface transition-colors hover:bg-surface-container-low" href="{{ route('landing.brand') }}">{{ __('landing.nav.for_brand') }}</a>
                <a class="rounded-xl px-3 py-3 text-sm font-bold text-on-surface transition-colors hover:bg-surface-container-low" href="{{ route('landing.open-store') }}">{{ __('landing.nav.open_store') }}</a>
                <a class="rounded-xl px-3 py-3 text-sm font-bold text-on-surface transition-colors hover:bg-surface-container-low" href="{{ route('landing.brand') }}#fitur">{{ __('landing.nav.features') }}</a>
                <a class="rounded-xl px-3 py-3 text-sm font-bold text-on-surface transition-colors hover:bg-surface-container-low" href="{{ route('landing.open-store') }}#faq">{{ __('landing.nav.faq') }}</a>
            </nav>

            <div class="flex items-center justify-between gap-3 border-t border-muted-border pt-4">
                @include('partials.locale-switcher')
                <a href="{{ route('customer.home') }}" class="text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface-variant">{{ __('landing.nav.buyer') }}</a>
            </div>

            <div class="flex items-center gap-3">
                @if ($dashboardUrl)
                    <a href="{{ $dashboardUrl }}" class="btn-secondary flex-1 !py-3">{{ __('landing.nav.dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary flex-1 !py-3">{{ __('landing.nav.login') }}</a>
                @endif
                <a href="{{ route('landing.open-store') }}" class="btn-primary flex-1 !py-3">{{ __('landing.nav.open_store') }}</a>
            </div>
        </div>
    </div>
</header>
