@extends('landing.layout')

@section('title', __('landing.meta.home_title'))
@section('meta_description', __('landing.meta.home_description'))

@section('content')
    @php
        $trustItems = [
            ['icon' => 'verified_user', 'title' => __('landing.home.trust_1_title'), 'text' => __('landing.home.trust_1_text')],
            ['icon' => 'dashboard', 'title' => __('landing.home.trust_2_title'), 'text' => __('landing.home.trust_2_text')],
            ['icon' => 'precision_manufacturing', 'title' => __('landing.home.trust_3_title'), 'text' => __('landing.home.trust_3_text')],
            ['icon' => 'monitoring', 'title' => __('landing.home.trust_4_title'), 'text' => __('landing.home.trust_4_text')],
        ];

        $whyItems = [
            ['icon' => 'storefront', 'title' => __('landing.home.why_1_title'), 'text' => __('landing.home.why_1_text')],
            ['icon' => 'verified_user', 'title' => __('landing.home.why_2_title'), 'text' => __('landing.home.why_2_text')],
            ['icon' => 'monitoring', 'title' => __('landing.home.why_3_title'), 'text' => __('landing.home.why_3_text')],
        ];

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

        $steps = [
            ['title' => __('landing.home.step_1_title'), 'text' => __('landing.home.step_1_text')],
            ['title' => __('landing.home.step_2_title'), 'text' => __('landing.home.step_2_text')],
            ['title' => __('landing.home.step_3_title'), 'text' => __('landing.home.step_3_text')],
            ['title' => __('landing.home.step_4_title'), 'text' => __('landing.home.step_4_text')],
        ];

        $requirements = [
            __('landing.open_store.requirement_1'),
            __('landing.open_store.requirement_2'),
            __('landing.open_store.requirement_3'),
        ];

        $audienceItems = [
            ['icon' => 'storefront', 'text' => __('landing.brand.audience_1')],
            ['icon' => 'precision_manufacturing', 'text' => __('landing.brand.audience_2')],
            ['icon' => 'inventory_2', 'text' => __('landing.brand.audience_3')],
            ['icon' => 'shopping_bag', 'text' => __('landing.brand.audience_4')],
        ];

        $faqs = [
            ['q' => __('landing.open_store.faq_1_q'), 'a' => __('landing.open_store.faq_1_a')],
            ['q' => __('landing.open_store.faq_2_q'), 'a' => __('landing.open_store.faq_2_a', ['percent' => $commissionPercent])],
            ['q' => __('landing.open_store.faq_3_q'), 'a' => __('landing.open_store.faq_3_a')],
            ['q' => __('landing.open_store.faq_4_q'), 'a' => __('landing.open_store.faq_4_a')],
            ['q' => __('landing.open_store.faq_6_q'), 'a' => __('landing.open_store.faq_6_a')],
        ];
    @endphp

    <section id="beranda" class="relative overflow-hidden pb-16 pt-28 sm:pb-24 sm:pt-36">
        <div class="hero-orb -left-24 top-24 h-72 w-72 bg-gold-accent/10"></div>
        <div class="hero-orb -right-24 top-40 h-96 w-96 bg-gold-accent/5"></div>
        <div class="hero-grid pointer-events-none absolute inset-x-0 top-0 h-[34rem] opacity-60"></div>

        <div class="relative mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-[1.02fr_0.98fr] lg:gap-16">
                <div class="reveal">
                    <span class="eyebrow">{{ __('landing.home.eyebrow') }}</span>
                    <h1 class="mt-5 max-w-3xl font-heading font-extrabold text-[2.7rem] leading-[1.04] tracking-[-0.025em] sm:text-6xl sm:leading-[1.04] lg:text-[4.3rem]">
                        {{ __('landing.home.title_before') }}
                        <span class="text-gold-accent">{{ __('landing.home.title_accent') }}</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-on-surface-variant sm:text-lg">{{ __('landing.home.description') }}</p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="btn-primary">
                            {{ __('landing.common.register_owner') }}
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                        <a href="#fitur" class="btn-secondary">{{ __('landing.home.cta_secondary') }}</a>
                    </div>

                    <a href="{{ route('customer.home') }}" class="mt-5 inline-flex items-center gap-2 text-xs font-bold text-gold-accent underline decoration-gold-accent/30 underline-offset-4 transition-colors hover:decoration-gold-accent">
                        <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                        {{ __('landing.home.buyer_cta') }}
                        <span class="material-symbols-outlined text-[15px]">arrow_outward</span>
                    </a>

                    <div class="mt-7 flex flex-wrap gap-x-5 gap-y-2 text-[11px] font-semibold text-on-surface-variant">
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-gold-accent">check_circle</span>{{ __('landing.home.hero_note') }}</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-gold-accent">check_circle</span>{{ __('landing.home.hero_note_2') }}</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-gold-accent">check_circle</span>{{ __('landing.home.hero_note_3') }}</span>
                    </div>
                </div>

                <div class="reveal" style="--reveal-delay: 120ms">
                    <div class="dashboard-shell mx-auto max-w-[36rem] lg:ml-auto">
                        <div class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-gold-accent/10 blur-2xl"></div>

                        <div class="dashboard-card p-4 sm:p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-[9px] font-extrabold uppercase tracking-[0.16em] text-gold-accent">{{ __('landing.home.dashboard_title') }}</p>
                                    <h2 class="mt-1 font-heading font-bold text-2xl">{{ __('landing.home.dashboard_greeting') }}</h2>
                                    <p class="mt-1 text-[11px] leading-5 text-on-surface-variant">{{ __('landing.home.dashboard_subtitle') }}</p>
                                </div>
                                <span class="inline-flex shrink-0 items-center gap-1 rounded-full border border-emerald-500/20 bg-emerald-500/10 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    {{ __('landing.home.dashboard_status') }}
                                </span>
                            </div>

                            <div class="mt-5 grid grid-cols-3 gap-2">
                                <div class="stat-card">
                                    <p class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">{{ __('landing.home.stat_orders') }}</p>
                                    <p class="mt-1 font-heading font-extrabold text-[15px] tabular-nums whitespace-nowrap sm:text-xl">128</p>
                                    <p class="mt-0.5 text-[9px] font-semibold text-emerald-600">+12%</p>
                                </div>
                                <div class="stat-card">
                                    <p class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">{{ __('landing.home.stat_revenue') }}</p>
                                    <p class="mt-1 font-heading font-extrabold text-[15px] tabular-nums whitespace-nowrap sm:text-xl">Rp 24,8 jt</p>
                                    <p class="mt-0.5 text-[9px] font-semibold text-emerald-600">+8,4%</p>
                                </div>
                                <div class="stat-card">
                                    <p class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">{{ __('landing.home.stat_products') }}</p>
                                    <p class="mt-1 font-heading font-extrabold text-[15px] tabular-nums whitespace-nowrap sm:text-xl">64</p>
                                    <p class="mt-0.5 text-[9px] font-semibold text-on-surface-variant">{{ __('landing.home.variant_count') }}</p>
                                </div>
                            </div>

                            <div class="mt-4 rounded-2xl border border-muted-border bg-surface-container-lowest p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-bold">{{ __('landing.home.chart_title') }}</p>
                                    <span class="text-[9px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ __('landing.home.chart_period') }}</span>
                                </div>
                                <div class="chart-bars mt-4" aria-hidden="true">
                                    <span class="chart-bar" style="height: 34%"></span>
                                    <span class="chart-bar" style="height: 52%"></span>
                                    <span class="chart-bar" style="height: 44%"></span>
                                    <span class="chart-bar" style="height: 68%"></span>
                                    <span class="chart-bar" style="height: 58%"></span>
                                    <span class="chart-bar" style="height: 82%"></span>
                                    <span class="chart-bar" style="height: 72%"></span>
                                    <span class="chart-bar" style="height: 94%"></span>
                                </div>
                                <div class="mt-2 flex justify-between text-[8px] font-semibold uppercase tracking-wider text-on-surface-variant">
                                    @foreach (__('landing.home.days') as $day)
                                        <span>{{ $day }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 rounded-2xl border border-muted-border bg-surface-container-lowest p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-bold">{{ __('landing.home.recent_orders') }}</p>
                                    <span class="text-[9px] font-semibold uppercase tracking-wider text-gold-accent">{{ __('landing.home.view_all') }}</span>
                                </div>
                                <div class="mt-2">
                                    <div class="order-row">
                                        <img class="order-thumb" src="{{ asset('assets/picture/home-pictures/1.jfif') }}" alt="" />
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-[11px] font-semibold">{{ __('landing.home.order_one') }}</p>
                                            <p class="text-[9px] text-on-surface-variant">#NX-1048</p>
                                        </div>
                                        <span class="text-[10px] font-bold tabular-nums">Rp 1,2 jt</span>
                                    </div>
                                    <div class="order-row">
                                        <img class="order-thumb" src="{{ asset('assets/picture/home-pictures/2.jfif') }}" alt="" />
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-[11px] font-semibold">{{ __('landing.home.order_two') }}</p>
                                            <p class="text-[9px] text-on-surface-variant">#NX-1047</p>
                                        </div>
                                        <span class="text-[10px] font-bold tabular-nums">Rp 689 rb</span>
                                    </div>
                                    <div class="order-row">
                                        <img class="order-thumb" src="{{ asset('assets/picture/home-pictures/3.jfif') }}" alt="" />
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-[11px] font-semibold">{{ __('landing.home.order_three') }}</p>
                                            <p class="text-[9px] text-on-surface-variant">#NX-1046</p>
                                        </div>
                                        <span class="text-[10px] font-bold tabular-nums">Rp 425 rb</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="floating-card hidden sm:flex">
                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-600">
                                <span class="material-symbols-outlined text-[18px]">task_alt</span>
                            </span>
                            <span>
                                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-on-surface">{{ __('landing.home.floating_title') }}</span>
                                <span class="mt-0.5 block text-[9px] leading-4 text-on-surface-variant">{{ __('landing.home.floating_text') }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-muted-border bg-surface-container-lowest/70">
        <div class="mx-auto max-w-8xl px-5 py-6 sm:px-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($trustItems as $item)
                    <div class="flex items-start gap-3">
                        <span class="icon-tile shrink-0 !h-9 !w-9 !rounded-xl">
                            <span class="material-symbols-outlined text-[18px]">{{ $item['icon'] }}</span>
                        </span>
                        <div>
                            <p class="text-xs font-extrabold">{{ $item['title'] }}</p>
                            <p class="mt-1 text-[11px] leading-5 text-on-surface-variant">{{ $item['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="tentang" class="border-b border-muted-border bg-surface-container-lowest/70 py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.82fr_1.18fr] lg:items-start lg:gap-16">
                <div class="reveal lg:sticky lg:top-28">
                    <span class="eyebrow">{{ __('landing.home.why_eyebrow') }}</span>
                    <h2 class="mt-4 max-w-md font-heading font-bold text-4xl leading-[1.08] tracking-[-0.02em] sm:text-5xl sm:leading-[1.08]">{{ __('landing.home.why_title') }}</h2>
                    <p class="mt-5 max-w-md text-sm leading-7 text-on-surface-variant">{{ __('landing.home.why_description') }}</p>
                    <a href="#fitur" class="btn-secondary mt-7">
                        {{ __('landing.common.see_features') }}
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>

                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach ($whyItems as $index => $item)
                        <article class="feature-card reveal" style="--reveal-delay: {{ $index * 70 }}ms">
                            <span class="icon-tile">
                                <span class="material-symbols-outlined text-[21px]">{{ $item['icon'] }}</span>
                            </span>
                            <h3 class="mt-4 font-heading font-semibold text-lg leading-tight">{{ $item['title'] }}</h3>
                            <p class="mt-2 text-xs leading-6 text-on-surface-variant">{{ $item['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="max-w-2xl reveal">
                <span class="eyebrow">{{ __('landing.home.features_eyebrow') }}</span>
                <h2 class="mt-4 font-heading font-bold text-4xl leading-[1.08] tracking-[-0.02em] sm:text-5xl sm:leading-[1.08]">{{ __('landing.home.features_title') }}</h2>
                <p class="mt-5 text-sm leading-7 text-on-surface-variant">{{ __('landing.home.features_description') }}</p>
            </div>

            <div class="mt-10 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($features as $index => $feature)
                    <article class="feature-card reveal" style="--reveal-delay: {{ ($index % 4) * 70 }}ms">
                        <span class="icon-tile">
                            <span class="material-symbols-outlined text-[21px]">{{ $feature['icon'] }}</span>
                        </span>
                        <h3 class="mt-4 font-heading font-semibold text-lg leading-tight">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-xs leading-6 text-on-surface-variant">{{ $feature['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="border-y border-muted-border bg-surface-container-lowest py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="max-w-2xl reveal">
                <span class="eyebrow">{{ __('landing.home.steps_eyebrow') }}</span>
                <h2 class="mt-4 font-heading font-bold text-4xl leading-[1.08] tracking-[-0.02em] sm:text-5xl sm:leading-[1.08]">{{ __('landing.home.steps_title') }}</h2>
                <p class="mt-5 text-sm leading-7 text-on-surface-variant">{{ __('landing.home.steps_description') }}</p>
            </div>

            <div class="mt-10 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($steps as $index => $step)
                    <article class="feature-card reveal" style="--reveal-delay: {{ $index * 80 }}ms">
                        <span class="step-number">0{{ $index + 1 }}</span>
                        <h3 class="mt-4 font-heading font-semibold text-xl leading-tight">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-on-surface-variant">{{ $step['text'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="{{ route('register') }}" class="btn-primary">
                    {{ __('landing.common.register_owner') }}
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
                <a href="#faq" class="btn-secondary">{{ __('landing.nav.faq') }}</a>
            </div>
        </div>
    </section>

    <section id="untuk-siapa" class="py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-start lg:gap-16">
                <div class="reveal lg:sticky lg:top-28">
                    <span class="eyebrow">{{ __('landing.brand.audience_eyebrow') }}</span>
                    <h2 class="mt-4 max-w-md font-heading font-bold text-4xl leading-[1.08] tracking-[-0.02em] sm:text-5xl sm:leading-[1.08]">{{ __('landing.brand.audience_title') }}</h2>
                    <p class="mt-5 max-w-md text-sm leading-7 text-on-surface-variant">{{ __('landing.brand.audience_description') }}</p>
                    <a href="#daftar" class="btn-secondary mt-7">
                        {{ __('landing.common.register_owner') }}
                        <span class="material-symbols-outlined text-[16px]">arrow_outward</span>
                    </a>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ($audienceItems as $index => $item)
                        <article class="feature-card reveal" style="--reveal-delay: {{ $index * 80 }}ms">
                            <span class="icon-tile">
                                <span class="material-symbols-outlined text-[22px]">{{ $item['icon'] }}</span>
                            </span>
                            <p class="mt-4 text-sm leading-6 text-on-surface-variant">{{ $item['text'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="daftar" class="border-y border-muted-border bg-surface-container-lowest py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="reveal rounded-[2rem] border border-muted-border bg-surface p-6 sm:p-10">
                <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-start lg:gap-12">
                    <div>
                        <span class="eyebrow">{{ __('landing.open_store.eyebrow') }}</span>
                        <h2 class="mt-4 max-w-xl font-heading font-bold text-4xl leading-[1.08] tracking-[-0.02em] sm:text-5xl sm:leading-[1.08]">{{ __('landing.open_store.title') }}</h2>
                        <p class="mt-5 max-w-xl text-sm leading-7 text-on-surface-variant">{{ __('landing.open_store.description') }}</p>

                        <div class="mt-7 grid gap-3 sm:grid-cols-3">
                            @foreach ([
                                ['icon' => 'rocket_launch', 'text' => __('landing.common.free_registration')],
                                ['icon' => 'verified_user', 'text' => __('landing.common.verified_store')],
                                ['icon' => 'dashboard', 'text' => __('landing.common.owner_dashboard')],
                            ] as $item)
                                <div class="flex items-center gap-2 rounded-xl border border-muted-border bg-surface-container-lowest p-3">
                                    <span class="material-symbols-outlined text-[18px] text-gold-accent">{{ $item['icon'] }}</span>
                                    <span class="text-xs font-semibold">{{ $item['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gold-accent/25 bg-surface-container-lowest p-5 sm:p-6">
                        <span class="eyebrow">{{ __('landing.open_store.requirements_title') }}</span>
                        <div class="mt-5 grid gap-3">
                            @foreach ($requirements as $requirement)
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined mt-0.5 text-[18px] text-gold-accent">check_circle</span>
                                    <p class="text-sm leading-6 text-on-surface-variant">{{ $requirement }}</p>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-4 border-t border-muted-border pt-4 text-[11px] leading-5 text-on-surface-variant">{{ __('landing.open_store.requirement_note') }}</p>

                        <div class="mt-5 flex flex-col gap-3">
                            <a href="{{ route('register') }}" class="btn-primary">
                                {{ __('landing.open_store.cta_primary') }}
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                            <a href="{{ route('login') }}" class="btn-secondary">{{ __('landing.open_store.cta_secondary') }}</a>
                        </div>

                        <p class="mt-4 text-[11px] leading-5 text-on-surface-variant">{{ __('landing.open_store.commission_note', ['percent' => $commissionPercent]) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.7fr_1.3fr] lg:gap-16">
                <div class="reveal">
                    <span class="eyebrow">{{ __('landing.nav.faq') }}</span>
                    <h2 class="mt-4 max-w-sm font-heading font-bold text-4xl leading-[1.08] tracking-[-0.02em] sm:text-5xl sm:leading-[1.08]">{{ __('landing.open_store.faq_title') }}</h2>
                    <p class="mt-5 max-w-sm text-sm leading-7 text-on-surface-variant">{{ __('landing.open_store.support_text') }}</p>
                    <a href="{{ route('customer.help') }}" class="btn-secondary mt-6">{{ __('landing.open_store.support_cta') }}</a>
                </div>

                <div class="reveal" style="--reveal-delay: 100ms">
                    @foreach ($faqs as $faq)
                        <details class="faq-item">
                            <summary aria-expanded="false">
                                <span>{{ $faq['q'] }}</span>
                                <span class="material-symbols-outlined faq-plus text-[20px]">add</span>
                            </summary>
                            <div class="faq-answer">
                                <div class="faq-answer-inner">
                                    <p>{{ $faq['a'] }}</p>
                                </div>
                            </div>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
