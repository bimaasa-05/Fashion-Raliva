@php
    $footerLogo = brand_logo_url();
    $instagramLabel = $instagramSupport ?? '';
    if (str_starts_with($instagramLabel, 'http')) {
        $instagramLabel = trim(parse_url($instagramLabel, PHP_URL_PATH) ?? '', '/');
    }
    $instagramLabel = '@'.ltrim($instagramLabel, '@');
@endphp

<footer class="site-footer">
    <div class="mx-auto max-w-8xl px-5 py-14 sm:px-8 sm:py-16">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-8">
            <div class="lg:col-span-5">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-3" aria-label="{{ brand() }}">
                    @if ($footerLogo !== '')
                        <img alt="{{ brand() }}" class="h-8 w-auto max-w-[11rem] object-contain brightness-0 invert" src="{{ $footerLogo }}" />
                    @else
                        <span class="brand-wordmark text-[1.35rem] text-white">{{ brand() }}</span>
                    @endif
                </a>
                <p class="mt-5 max-w-sm text-sm leading-7 text-[rgb(var(--c-footer-muted))]">{{ __('landing.footer.tagline') }}</p>
                <a href="{{ route('landing.open-store') }}" class="btn-light mt-6 !px-4 !py-2.5 !text-[10px]">{{ __('landing.footer.open_store') }}</a>
            </div>

            <div class="lg:col-span-2">
                <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-white">{{ __('landing.footer.platform') }}</h2>
                <div class="mt-4 grid gap-3 text-sm">
                    <a href="{{ route('landing.brand') }}">{{ __('landing.footer.for_brand') }}</a>
                    <a href="{{ route('landing.open-store') }}">{{ __('landing.footer.open_store') }}</a>
                    <a href="{{ route('landing.brand') }}#fitur">{{ __('landing.footer.features') }}</a>
                    <a href="{{ route('landing.open-store') }}#faq">{{ __('landing.footer.faq') }}</a>
                </div>
            </div>

            <div class="lg:col-span-2">
                <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-white">{{ __('landing.footer.buyers') }}</h2>
                <div class="mt-4 grid gap-3 text-sm">
                    <a href="{{ route('customer.home') }}">{{ __('landing.footer.shop_home') }}</a>
                    <a href="{{ route('customer.shop') }}">{{ __('landing.footer.catalog') }}</a>
                    <a href="{{ route('customer.help') }}">{{ __('landing.footer.help') }}</a>
                </div>
            </div>

            <div class="lg:col-span-3">
                <h2 class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-white">{{ __('landing.footer.contact') }}</h2>
                <div class="mt-4 grid gap-4 text-sm">
                    @if ($emailSupport !== '')
                        <a class="footer-contact-link items-start" href="mailto:{{ $emailSupport }}">
                            <span class="material-symbols-outlined mt-0.5 text-[18px]">mail</span>
                            <span>
                                <span class="block text-[10px] uppercase tracking-[0.12em] text-white/60">{{ __('landing.footer.email') }}</span>
                                <span class="mt-0.5 block break-all">{{ $emailSupport }}</span>
                            </span>
                        </a>
                    @endif

                    @if ($whatsappDigits !== '')
                        <a class="footer-contact-link items-start" href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noopener noreferrer">
                            <span class="material-symbols-outlined mt-0.5 text-[18px]">chat</span>
                            <span>
                                <span class="block text-[10px] uppercase tracking-[0.12em] text-white/60">{{ __('landing.footer.whatsapp') }}</span>
                                <span class="mt-0.5 block">+{{ $whatsappDigits }}</span>
                            </span>
                        </a>
                    @endif

                    @if ($instagramUrl !== '')
                        <a class="footer-contact-link items-start" href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer">
                            <span class="material-symbols-outlined mt-0.5 text-[18px]">photo_camera</span>
                            <span>
                                <span class="block text-[10px] uppercase tracking-[0.12em] text-white/60">{{ __('landing.footer.instagram') }}</span>
                                <span class="mt-0.5 block break-all">{{ $instagramLabel }}</span>
                            </span>
                        </a>
                    @endif

                    @if ($emailSupport === '' && $whatsappDigits === '' && $instagramUrl === '')
                        <a class="footer-contact-link items-start" href="{{ route('customer.help') }}">
                            <span class="material-symbols-outlined mt-0.5 text-[18px]">help</span>
                            <span>{{ __('landing.footer.no_contact') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-12 flex flex-col gap-3 border-t border-white/10 pt-6 text-[11px] text-[rgb(var(--c-footer-muted))] sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ brand() }}. {{ __('landing.footer.rights') }}</p>
            <p>{{ __('landing.footer.built_for') }}</p>
        </div>
    </div>
</footer>
