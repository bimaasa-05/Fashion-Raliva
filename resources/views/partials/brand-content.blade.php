@php($logo = brand_logo_url())
@if ($logo !== '')
<span class="inline-flex items-center gap-2 align-middle">
    <img alt="" class="{{ $logoClass ?? 'h-7' }} w-auto" src="{{ $logo }}" />
    <span>{{ brand() }}</span>
</span>
@else
{{ brand() }}
@endif
