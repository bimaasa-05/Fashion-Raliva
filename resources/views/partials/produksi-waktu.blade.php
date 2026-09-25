@php
    $cStart = $produksiOrder->tgl_mulai_produksi;
    $cEnd = $produksiOrder->tgl_berakhir_produksi;
    $cStartTs = $cStart ? $cStart->timestamp : null;
    $cEndTs = $cEnd ? $cEnd->timestamp : null;
    $cNowTs = time();
    $cBelum = ($cStartTs !== null) ? ($cNowTs < $cStartTs) : false;
    $cLambat = ($cEndTs !== null) ? ($cNowTs > $cEndTs) : false;
    $cTotal = ($cStartTs !== null && $cEndTs !== null) ? max(1, $cEndTs - $cStartTs) : 1;
    $cPct = ($cStartTs !== null && $cEndTs !== null) ? ($cBelum ? 0 : min(100, round((($cNowTs - $cStartTs) / $cTotal) * 100))) : 0;
@endphp
@if ($cStart && $cEnd)
    <div class="min-w-[150px]">
        <p class="text-xs text-on-surface-variant whitespace-nowrap">{{ $cStart->translatedFormat('d M H:i') }} &rarr; {{ $cEnd->translatedFormat('d M H:i') }}</p>
        <div class="mt-1.5 h-1 w-full bg-surface-container-high rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-[width] duration-300 {{ $cLambat ? 'bg-error' : ($cBelum ? 'bg-surface-container-high' : 'bg-gold-accent') }}" style="width: {{ $cPct }}%"></div>
        </div>
        <p class="[font-variant-numeric:tabular-nums] mt-1.5 text-xs {{ $cBelum ? 'text-secondary' : ($cLambat ? 'text-error font-bold' : 'text-on-surface-variant') }}" data-countdown-start="{{ $cStartTs }}" data-countdown-end="{{ $cEndTs }}" data-countdown-progress="{{ $cPct }}">{{ $cBelum ? 'Mulai dalam...' : ($cLambat ? 'Terlambat...' : 'Memuat...') }}</p>
    </div>
@else
    <span class="text-on-surface-variant text-xs">&mdash;</span>
@endif