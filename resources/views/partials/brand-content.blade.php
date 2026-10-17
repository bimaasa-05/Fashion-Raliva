@php($logo = brand_logo_url())
@if ($logo !== '')
<img src="{{ $logo }}" alt="{{ brand() }}" class="{{ $logoClass ?? 'h-7' }} w-auto" />
@else
{{ brand() }}
@endif
