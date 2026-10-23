@extends('landing.layout')

@section('title', __('landing.meta.open_store_title'))
@section('meta_description', __('landing.meta.open_store_description'))

@section('content')
    @php
        $steps = [
            ['title' => __('landing.open_store.step_1_title'), 'text' => __('landing.open_store.step_1_text')],
            ['title' => __('landing.open_store.step_2_title'), 'text' => __('landing.open_store.step_2_text')],
            ['title' => __('landing.open_store.step_3_title'), 'text' => __('landing.open_store.step_3_text')],
            ['title' => __('landing.open_store.step_4_title'), 'text' => __('landing.open_store.step_4_text')],
        ];

        $requirements = [
            __('landing.open_store.requirement_1'),
            __('landing.open_store.requirement_2'),
            __('landing.open_store.requirement_3'),
        ];

        $afterSteps = [
            __('landing.open_store.after_1'),
            __('landing.open_store.after_2'),
            __('landing.open_store.after_3'),
            __('landing.open_store.after_4'),
        ];

        $faqs = [
            ['q' => __('landing.open_store.faq_1_q'), 'a' => __('landing.open_store.faq_1_a')],
            ['q' => __('landing.open_store.faq_2_q'), 'a' => __('landing.open_store.faq_2_a', ['percent' => $commissionPercent])],
            ['q' => __('landing.open_store.faq_3_q'), 'a' => __('landing.open_store.faq_3_a')],
            ['q' => __('landing.open_store.faq_4_q'), 'a' => __('landing.open_store.faq_4_a')],
            ['q' => __('landing.open_store.faq_5_q'), 'a' => __('landing.open_store.faq_5_a')],
            ['q' => __('landing.open_store.faq_6_q'), 'a' => __('landing.open_store.faq_6_a')],
        ];
    @endphp

    <section class="relative overflow-hidden pb-16 pt-28 sm:pb-24 sm:pt-36">
        <div class="hero-orb -left-20 top-28 h-80 w-80 bg-gold-accent/10"></div>
        <div class="hero-grid pointer-events-none absolute inset-x-0 top-0 h-[30rem] opacity-50"></div>

        <div class="relative mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-[1.02fr_0.98fr] lg:gap-16">
                <div class="reveal">
                    <span class="eyebrow">{{ __('landing.open_store.eyebrow') }}</span>
                    <h1 class="mt-5 max-w-3xl font-display text-[2.8rem] leading-[0.98] tracking-[-0.03em] sm:text-6xl lg:text-[4.4rem]">{{ __('landing.open_store.title') }}</h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-on-surface-variant sm:text-lg">{{ __('landing.open_store.description') }}</p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="btn-primary">
                            {{ __('landing.open_store.cta_primary') }}
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                        <a href="{{ route('login') }}" class="btn-secondary">{{ __('landing.open_store.cta_secondary') }}</a>
                    </div>

                    <div class="mt-7 flex flex-wrap gap-x-5 gap-y-2 text-[11px] font-semibold text-on-surface-variant">
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-gold-accent">check_circle</span>{{ __('landing.common.free_registration') }}</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-gold-accent">check_circle</span>{{ __('landing.common.verified_store') }}</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px] text-gold-accent">check_circle</span>{{ __('landing.common.owner_dashboard') }}</span>
                    </div>
                </div>

                <div class="reveal" style="--reveal-delay: 120ms">
                    <div class="mx-auto max-w-md rounded-[1.5rem] border border-muted-border bg-surface-container-lowest p-5 shadow-[0_30px_80px_-38px_rgba(17,17,17,0.45)] sm:p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-[9px] font-extrabold uppercase tracking-[0.16em] text-gold-accent">{{ __('landing.open_store.preview_title') }}</p>
                                <h2 class="mt-1 font-display text-2xl">{{ __('landing.open_store.preview_step') }}</h2>
                            </div>
                            <span class="inline-flex items-center gap-1 rounded-full border border-amber-500/20 bg-amber-500/10 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider text-amber-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                {{ __('landing.open_store.preview_status') }}
                            </span>
                        </div>

                        <div class="mt-5 grid gap-3">
                            <div class="rounded-xl border border-muted-border bg-surface-container-low p-3">
                                <p class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">{{ __('landing.open_store.preview_store_name') }}</p>
                                <p class="mt-1 text-sm font-semibold">{{ __('landing.open_store.preview_store_value') }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-xl border border-muted-border bg-surface-container-low p-3">
                                    <p class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">{{ __('landing.open_store.preview_category') }}</p>
                                    <p class="mt-1 text-sm font-semibold">{{ __('landing.open_store.preview_category_value') }}</p>
                                </div>
                                <div class="rounded-xl border border-muted-border bg-surface-container-low p-3">
                                    <p class="text-[9px] font-bold uppercase tracking-wider text-on-surface-variant">{{ __('landing.open_store.preview_city') }}</p>
                                    <p class="mt-1 text-sm font-semibold">{{ __('landing.open_store.preview_city_value') }}</p>
                                </div>
                            </div>
                            <div class="rounded-xl border border-dashed border-gold-accent/40 bg-gold-accent/5 p-3">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gold-accent/10 text-gold-accent">
                                        <span class="material-symbols-outlined text-[18px]">upload_file</span>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold">{{ __('landing.open_store.preview_ktp') }}</p>
                                        <p class="mt-0.5 text-[10px] text-on-surface-variant">{{ __('landing.open_store.preview_ktp_note') }}</p>
                                    </div>
                                    <span class="ml-auto text-[10px] font-bold text-emerald-600">{{ __('landing.open_store.preview_ktp_status') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex items-start gap-2 rounded-xl border border-muted-border bg-surface-container-low p-3">
                            <span class="material-symbols-outlined text-[18px] text-gold-accent">info</span>
                            <p class="text-[11px] leading-5 text-on-surface-variant">{{ __('landing.open_store.preview_status_note') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="steps" class="border-y border-muted-border bg-surface-container-lowest py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="max-w-2xl reveal">
                <span class="eyebrow">{{ __('landing.open_store.eyebrow') }}</span>
                <h2 class="mt-4 font-display text-4xl leading-[1.05] tracking-[-0.02em] sm:text-5xl">{{ __('landing.open_store.steps_title') }}</h2>
            </div>

            <div class="mt-10 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($steps as $index => $step)
                    <article class="feature-card reveal" style="--reveal-delay: {{ $index * 80 }}ms">
                        <span class="step-number">0{{ $index + 1 }}</span>
                        <h3 class="mt-4 font-display text-xl leading-tight">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-on-surface-variant">{{ $step['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid gap-6 lg:grid-cols-2">
                <div class="reveal rounded-2xl border border-muted-border bg-surface-container-lowest p-6 sm:p-8">
                    <span class="eyebrow">{{ __('landing.open_store.requirements_title') }}</span>
                    <div class="mt-6 grid gap-4">
                        @foreach ($requirements as $requirement)
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined mt-0.5 text-[19px] text-gold-accent">check_circle</span>
                                <p class="text-sm leading-6 text-on-surface-variant">{{ $requirement }}</p>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-6 border-t border-muted-border pt-4 text-[11px] leading-5 text-on-surface-variant">{{ __('landing.open_store.requirement_note') }}</p>
                </div>

                <div class="reveal rounded-2xl border border-muted-border bg-[#1b1c1c] p-6 text-white sm:p-8" style="--reveal-delay: 100ms">
                    <span class="eyebrow !text-[#e9c9d0]">{{ __('landing.open_store.after_title') }}</span>
                    <div class="mt-6 grid gap-4">
                        @foreach ($afterSteps as $index => $after)
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-white/20 bg-white/10 text-[10px] font-bold">{{ $index + 1 }}</span>
                                <p class="text-sm leading-6 text-white/75">{{ $after }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="border-y border-muted-border bg-surface-container-lowest py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.7fr_1.3fr] lg:gap-16">
                <div class="reveal">
                    <span class="eyebrow">{{ __('landing.nav.faq') }}</span>
                    <h2 class="mt-4 max-w-sm font-display text-4xl leading-[1.05] tracking-[-0.02em] sm:text-5xl">{{ __('landing.open_store.faq_title') }}</h2>
                    <p class="mt-5 max-w-sm text-sm leading-7 text-on-surface-variant">{{ __('landing.open_store.support_text') }}</p>
                    <a href="#support" class="btn-secondary mt-6">{{ __('landing.open_store.support_cta') }}</a>
                </div>

                <div class="reveal" style="--reveal-delay: 100ms">
                    @foreach ($faqs as $faq)
                        <details class="faq-item">
                            <summary>
                                <span>{{ $faq['q'] }}</span>
                                <span class="material-symbols-outlined faq-plus text-[20px]">add</span>
                            </summary>
                            <p>{{ $faq['a'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="support" class="py-20 sm:py-28">
        <div class="mx-auto max-w-8xl px-5 sm:px-8">
            <div class="rounded-[2rem] border border-muted-border bg-surface-container-lowest p-6 sm:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <span class="eyebrow">{{ __('landing.open_store.support_cta') }}</span>
                        <h2 class="mt-4 max-w-xl font-display text-3xl leading-tight sm:text-4xl">{{ __('landing.open_store.support_title') }}</h2>
                        <p class="mt-4 max-w-xl text-sm leading-7 text-on-surface-variant">{{ __('landing.open_store.support_text') }}</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                        @if ($emailSupport !== '')
                            <a href="mailto:{{ $emailSupport }}" class="btn-secondary">
                                <span class="material-symbols-outlined text-[16px]">mail</span>
                                {{ __('landing.footer.email') }}
                            </a>
                        @endif
                        @if ($whatsappDigits !== '')
                            <a href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">
                                <span class="material-symbols-outlined text-[16px]">chat</span>
                                {{ __('landing.footer.whatsapp') }}
                            </a>
                        @endif
                        @if ($instagramUrl !== '')
                            <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="btn-secondary">
                                <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                                {{ __('landing.footer.instagram') }}
                            </a>
                        @endif
                        @if ($emailSupport === '' && $whatsappDigits === '' && $instagramUrl === '')
                            <a href="{{ route('customer.help') }}" class="btn-secondary">
                                <span class="material-symbols-outlined text-[16px]">help</span>
                                {{ __('landing.footer.help') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
