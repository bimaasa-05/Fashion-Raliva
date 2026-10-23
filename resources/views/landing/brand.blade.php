@extends('landing.layout')

@section('title', __('landing.meta.brand_title'))
@section('meta_description', __('landing.meta.brand_description'))

@section('content')
    @php
        $features = [
            ['icon' => 'styler', 'title' => __('landing.home.feature_1_title'), 'text' => __('landing.home.feature_1_text')],
            ['icon' => 'local_shipping', 'title' => __('landing.home.feature_2_title'), 'text' => __('landing.home.feature_2_text')],
            ['icon' => 'warehouse', 'title' => __('landing.home.feature_3_title'), 'text' => __('landing.home.feature_3_text')],
            ['icon' => 'precision_manufacturing', 'title' => __('landing.home.feature_4_title'), 'text' => __('landing.home.feature_4_text')],
            ['icon' => 'account_balance_wallet', 'title' => __('landing.home.feature_5_title'), 'text' => __('landing.home.feature_5_text')],
            ['icon' => 'campaign', 'title' => __('landing.home.feature_6_title'), 'text' => __('landing.home.feature_6_text')],
            ['icon' => 'reviews', 'title' => __('landing.home.feature_7_title'), 'text' => __('landing.home.feature_7_text')],
            ['icon' => 'groups', 'title' => __('landing.home.feature_8_title'), 'text' => __('landing.home.feature_8_text')],
        ];

        $audienceItems = [
            __('landing.brand.audience_1'),
            __('landing.brand.audience_2'),
            __('landing.brand.audience_3'),
            __('landing.brand.audience_4'),
        ];
    @endphp

    <section class="relative overflow-hidden pb-16 pt-28 sm:pb-24 sm:pt-36">
        <div class="hero-orb -right-20 top-28 h-80 w-80 bg-gold-accent/10"></div>
        <div class="hero-grid pointer-events-none absolute inset-x-0 top-0 h-[30rem] opacity-50"></div>

        <div class="relative mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-[1.02fr_0.98fr] lg:gap-16">
                <div class="reveal">
                    <span class="eyebrow">{{ __('landing.brand.eyebrow') }}</span>
                    <h1 class="mt-5 max-w-3xl font-display text-[2.8rem] leading-[0.98] tracking-[-0.03em] sm:text-6xl lg:text-[4.4rem]">{{ __('landing.brand.title') }}</h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-on-surface-variant sm:text-lg">{{ __('landing.brand.description') }}</p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('landing.open-store') }}" class="btn-primary">
                            {{ __('landing.brand.cta_primary') }}
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                        <a href="{{ route('landing.open-store') }}#steps" class="btn-secondary">{{ __('landing.brand.cta_secondary') }}</a>
                    </div>

                    <div class="mt-8 grid max-w-lg grid-cols-3 gap-3 border-t border-muted-border pt-6">
                        <div>
                            <p class="font-display text-2xl">01</p>
                            <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-on-surface-variant">{{ __('landing.common.owner_dashboard') }}</p>
                        </div>
                        <div>
                            <p class="font-display text-2xl">02</p>
                            <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-on-surface-variant">{{ __('landing.home.feature_4_title') }}</p>
                        </div>
                        <div>
                            <p class="font-display text-2xl">03</p>
                            <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-on-surface-variant">{{ __('landing.home.feature_5_title') }}</p>
                        </div>
                    </div>
                </div>

                <div class="reveal" style="--reveal-delay: 120ms">
                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        <div class="image-frame aspect-[4/5]">
                            <img src="{{ asset('assets/picture/home-pictures/1.jfif') }}" alt="{{ __('landing.brand.eyebrow') }}" loading="lazy" />
                        </div>
                        <div class="space-y-3 sm:space-y-4">
                            <div class="image-frame aspect-[4/3]">
                                <img src="{{ asset('assets/picture/home-pictures/2.jfif') }}" alt="" loading="lazy" />
                            </div>
                            <div class="rounded-2xl border border-muted-border bg-surface-container-lowest p-5">
                                <span class="icon-tile !h-9 !w-9 !rounded-xl">
                                    <span class="material-symbols-outlined text-[18px]">auto_awesome</span>
                                </span>
                                <p class="mt-4 font-display text-2xl leading-tight">{{ __('landing.common.built_for_brands') }}</p>
                                <p class="mt-2 text-xs leading-5 text-on-surface-variant">{{ __('landing.home.why_1_text') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-muted-border bg-surface-container-lowest py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
                <div class="reveal">
                    <span class="eyebrow">{{ __('landing.brand.intro_eyebrow') }}</span>
                    <h2 class="mt-4 max-w-lg font-display text-4xl leading-[1.05] tracking-[-0.02em] sm:text-5xl">{{ __('landing.brand.intro_title') }}</h2>
                    <p class="mt-5 max-w-lg text-sm leading-7 text-on-surface-variant">{{ __('landing.brand.intro_text') }}</p>
                </div>

                <div class="reveal" style="--reveal-delay: 100ms">
                    <h3 class="font-display text-2xl">{{ __('landing.brand.audience_title') }}</h3>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach ($audienceItems as $item)
                            <div class="flex items-start gap-3 rounded-xl border border-muted-border bg-surface p-4">
                                <span class="material-symbols-outlined mt-0.5 text-[18px] text-gold-accent">check_circle</span>
                                <p class="text-sm leading-6 text-on-surface-variant">{{ $item }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="max-w-2xl reveal">
                <span class="eyebrow">{{ __('landing.home.features_eyebrow') }}</span>
                <h2 class="mt-4 font-display text-4xl leading-[1.05] tracking-[-0.02em] sm:text-5xl">{{ __('landing.home.features_title') }}</h2>
                <p class="mt-5 text-sm leading-7 text-on-surface-variant">{{ __('landing.home.features_description') }}</p>
            </div>

            <div class="mt-10 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($features as $index => $feature)
                    <article class="feature-card reveal" style="--reveal-delay: {{ ($index % 4) * 70 }}ms">
                        <span class="icon-tile">
                            <span class="material-symbols-outlined text-[21px]">{{ $feature['icon'] }}</span>
                        </span>
                        <h3 class="mt-4 font-display text-lg leading-tight">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-xs leading-6 text-on-surface-variant">{{ $feature['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="pb-20 sm:pb-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid items-center gap-8 lg:grid-cols-2 lg:gap-16">
                <div class="image-frame aspect-[4/3] reveal">
                    <img src="{{ asset('assets/picture/home-pictures/3.jfif') }}" alt="" loading="lazy" />
                </div>
                <div class="reveal" style="--reveal-delay: 100ms">
                    <span class="material-symbols-outlined text-5xl text-gold-accent">format_quote</span>
                    <blockquote class="mt-4 font-display text-3xl leading-[1.12] tracking-[-0.02em] sm:text-4xl">{{ __('landing.brand.quote') }}</blockquote>
                    <p class="mt-5 text-[10px] font-extrabold uppercase tracking-[0.18em] text-on-surface-variant">{{ __('landing.brand.quote_author') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-20 sm:pb-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="relative overflow-hidden rounded-[2rem] bg-[#1b1c1c] px-6 py-12 text-white sm:px-12 sm:py-16">
                <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-[#8B1E3F]/40 blur-3xl"></div>
                <div class="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-end">
                    <div>
                        <span class="eyebrow !text-[#e9c9d0]">{{ __('landing.common.built_for_brands') }}</span>
                        <h2 class="mt-4 max-w-2xl font-display text-4xl leading-[1.02] tracking-[-0.02em] sm:text-5xl">{{ __('landing.brand.cta_title') }}</h2>
                        <p class="mt-4 max-w-xl text-sm leading-7 text-white/65">{{ __('landing.brand.cta_text') }}</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                        <a href="{{ route('landing.open-store') }}" class="btn-light">{{ __('landing.common.open_store') }}</a>
                        <a href="{{ route('customer.help') }}" class="btn-outline-light">{{ __('landing.common.contact_support') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
