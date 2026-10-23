@php
    $currentLocale = app()->getLocale();
    $nextLocale = $currentLocale === 'en' ? 'id' : 'en';
    $compact = $compact ?? false;
@endphp

@if ($compact)
    <form method="POST" action="{{ route('customer.locale.switch') }}" class="inline-flex">
        @csrf
        <input type="hidden" name="locale" value="{{ $nextLocale }}" />
        <button
            type="submit"
            class="inline-flex items-center justify-center min-w-9 h-9 px-2 rounded-full border border-muted-border bg-surface-container-lowest text-[10px] font-bold uppercase tracking-wider text-on-surface-variant hover:text-gold-accent hover:border-gold-accent/40 transition-colors"
            title="{{ $currentLocale === 'id' ? __('Switch to English') : __('Ganti ke Bahasa Indonesia') }}"
            aria-label="{{ $currentLocale === 'id' ? __('Switch to English') : __('Ganti ke Bahasa Indonesia') }}"
        >
            {{ strtoupper($currentLocale) }}
        </button>
    </form>
@else
    <form
        method="POST"
        action="{{ route('customer.locale.switch') }}"
        class="inline-flex items-center gap-0.5 rounded-full border border-muted-border bg-surface-container-lowest p-0.5"
        aria-label="{{ __('Pilih bahasa') }}"
    >
        @csrf
        <button
            type="submit"
            name="locale"
            value="id"
            class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors {{ $currentLocale === 'id' ? 'bg-gold-accent text-white' : 'text-on-surface-variant hover:text-gold-accent' }}"
            aria-pressed="{{ $currentLocale === 'id' ? 'true' : 'false' }}"
        >ID</button>
        <button
            type="submit"
            name="locale"
            value="en"
            class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors {{ $currentLocale === 'en' ? 'bg-gold-accent text-white' : 'text-on-surface-variant hover:text-gold-accent' }}"
            aria-pressed="{{ $currentLocale === 'en' ? 'true' : 'false' }}"
        >EN</button>
    </form>
@endif
