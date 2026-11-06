{{--=== Penawaran Landing ===--}}
<section id="kartu-landing-penawaran" data-scroll-section="landing-penawaran" class="snap-anchor bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-gold-accent text-[20px]">campaign</span>
                </div>
                <h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">{{ __('Penawaran Landing') }}</h2>
            </div>
            <p class="font-body-md text-sm text-on-surface-variant mt-2">{{ __('Konten section Daftar / Buka Toko di halaman :path.', ['path' => '/join#daftar']) }}</p>
        </div>
        <a href="{{ route('landing') }}#daftar" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-[11px] font-bold uppercase tracking-wider text-on-surface-variant hover:border-gold-accent hover:text-gold-accent transition-colors">
            <span class="material-symbols-outlined text-[16px]">open_in_new</span>
            {{ __('Lihat Halaman') }}
        </a>
    </div>

    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.landing-penawaran.update') }}" class="space-y-gutter" data-save-form>
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="p-4 border border-error/30 rounded-lg bg-error/5">
                <p class="font-title-md text-sm text-error">{{ __('Periksa kembali isian penawaran landing.') }}</p>
                <ul class="mt-2 list-disc list-inside space-y-1 text-xs text-error">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="p-4 border border-gold-accent/25 rounded-lg bg-gold-accent/5">
            <p class="font-body-md text-xs leading-relaxed text-on-surface-variant">
                {{ __('Field ID wajib untuk Judul dan Deskripsi. Field lain boleh kosong dan akan memakai teks bawaan. Field EN opsional; jika kosong, website memakai versi ID.') }}
            </p>
        </div>

        @php
            $offerGroups = [
                'Teks Utama' => ['eyebrow', 'title', 'description'],
                'Benefit' => ['benefit_1', 'benefit_2', 'benefit_3'],
                'Kebutuhan' => ['requirements_title', 'requirement_1', 'requirement_2', 'requirement_3', 'requirement_note'],
                'CTA & Komisi' => ['cta_primary', 'cta_secondary', 'commission_note'],
            ];
        @endphp

        @foreach ($offerGroups as $groupLabel => $fields)
            <div class="space-y-gutter border border-muted-border rounded-lg p-4">
                <p class="font-title-md text-sm text-on-surface">{{ __($groupLabel) }}</p>

                @foreach ($fields as $field)
                    @php($data = $landingOffer[$field])
                    <div class="grid grid-cols-1 md:grid-cols-[minmax(0,0.3fr)_minmax(0,1fr)_minmax(0,1fr)] gap-3 items-start border-t border-muted-border pt-3">
                        <div>
                            <label class="block font-label-sm text-[11px] text-on-surface-variant uppercase tracking-wider">
                                {{ __($data['label']) }}
                                @if ($data['required'])
                                    <span class="text-error">*</span>
                                @endif
                            </label>
                            <p class="font-body-md text-[11px] text-on-surface-variant mt-1">
                                {{ $data['required'] ? __('Wajib versi ID') : __('Opsional') }}
                            </p>
                        </div>

                        <div>
                            <label class="block font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider mb-1.5" for="landing_offer_{{ $field }}_id">{{ __('Versi ID') }}</label>
                            @if ($data['type'] === 'textarea')
                                <textarea id="landing_offer_{{ $field }}_id" name="landing_offer_{{ $field }}_id" rows="{{ $data['rows'] }}" maxlength="{{ $data['max'] }}" @if ($data['required']) required @endif
                                    class="w-full bg-surface-container-low border border-muted-border rounded-lg p-3 font-body-md text-sm leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"
                                    placeholder="{{ $data['fallback_id'] }}">{{ old("landing_offer_{$field}_id", $data['id']) }}</textarea>
                            @else
                                <input id="landing_offer_{{ $field }}_id" name="landing_offer_{{ $field }}_id" type="text" maxlength="{{ $data['max'] }}" @if ($data['required']) required @endif
                                    value="{{ old("landing_offer_{$field}_id", $data['id']) }}"
                                    class="w-full bg-surface-container-low border border-muted-border rounded-lg p-3 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"
                                    placeholder="{{ $data['fallback_id'] }}" />
                            @endif
                            @error("landing_offer_{$field}_id")<p class="font-body-md text-xs text-error mt-1.5">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider mb-1.5" for="landing_offer_{{ $field }}_en">{{ __('Versi EN') }}</label>
                            @if ($data['type'] === 'textarea')
                                <textarea id="landing_offer_{{ $field }}_en" name="landing_offer_{{ $field }}_en" rows="{{ $data['rows'] }}" maxlength="{{ $data['max'] }}"
                                    class="w-full bg-surface-container-low border border-muted-border rounded-lg p-3 font-body-md text-sm leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"
                                    placeholder="{{ $data['fallback_en'] }}">{{ old("landing_offer_{$field}_en", $data['en']) }}</textarea>
                            @else
                                <input id="landing_offer_{{ $field }}_en" name="landing_offer_{{ $field }}_en" type="text" maxlength="{{ $data['max'] }}"
                                    value="{{ old("landing_offer_{$field}_en", $data['en']) }}"
                                    class="w-full bg-surface-container-low border border-muted-border rounded-lg p-3 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"
                                    placeholder="{{ $data['fallback_en'] }}" />
                            @endif
                            @error("landing_offer_{$field}_en")<p class="font-body-md text-xs text-error mt-1.5">{{ $message }}</p>@enderror
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="flex items-center justify-between gap-4 pt-gutter border-t border-muted-border flex-wrap">
            <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[14px] text-gold-accent">history</span>
                {{ __('Perubahan tercatat di riwayat aktivitas') }}
            </p>
            <button type="submit" data-save-button class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">{{ __('Simpan Penawaran Landing') }}</button>
        </div>
    </form>
</section>
