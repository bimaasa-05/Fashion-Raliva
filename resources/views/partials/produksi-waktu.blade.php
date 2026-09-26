@include('partials.produksi-durasi')
@php
    $cMulai = $produksiOrder->tgl_mulai_produksi;
    $cSelesai = $produksiOrder->tgl_berakhir_produksi;
    $cSelesaiAktual = $produksiOrder->produksi_selesai_pada;
    $cAktualMulai = $produksiOrder->produksi_dimulai_pada;
    $cAktualSelesai = $produksiOrder->produksi_selesai_pada ?: $produksiOrder->tanggal_qc;
    $cStartTs = $cMulai ? $cMulai->timestamp : null;
    $cEndTs = $cSelesai ? $cSelesai->timestamp : null;
    $cNowTs = time();
    $cBelum = ($cStartTs !== null) ? ($cNowTs < $cStartTs) : false;
    $cLambat = ($cEndTs !== null) ? ($cNowTs > $cEndTs) : false;
    $cTotal = ($cStartTs !== null && $cEndTs !== null) ? max(1, $cEndTs - $cStartTs) : 1;
    $cPct = ($cStartTs !== null && $cEndTs !== null) ? ($cBelum ? 0 : min(100, round((($cNowTs - $cStartTs) / $cTotal) * 100))) : 0;
    $cSelesaiTepat = (bool) ($cSelesaiAktual && $cSelesai && $cSelesaiAktual->lessThanOrEqualTo($cSelesai));
@endphp
@if ($cMulai && $cSelesai)
    <div class="min-w-[150px]">
        <p class="text-xs text-on-surface-variant whitespace-nowrap">{{ $cMulai->translatedFormat('d M H:i') }} &rarr; {{ $cSelesai->translatedFormat('d M H:i') }}</p>
        <div class="mt-1.5 h-1 w-full bg-surface-container-high rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-[width] duration-300 {{ ($cSelesaiAktual && $cSelesaiTepat) ? 'bg-secondary' : ($cLambat ? 'bg-error' : ($cBelum ? 'bg-surface-container-high' : 'bg-gold-accent')) }}" style="width: {{ $cPct }}%"></div>
        </div>
        @if ($cSelesaiAktual)
            @if ($cSelesaiTepat)
                <p class="[font-variant-numeric:tabular-nums] mt-1.5 text-xs text-on-surface-variant">Selesai tepat waktu</p>
            @else
                <p class="[font-variant-numeric:tabular-nums] mt-1.5 text-xs text-error font-bold">Terlambat {{ produksiFmtDetik((int) $cSelesaiAktual->timestamp - (int) $cSelesai->timestamp) }}</p>
            @endif
        @else
            <p class="[font-variant-numeric:tabular-nums] mt-1.5 text-xs {{ $cBelum ? 'text-secondary' : ($cLambat ? 'text-error font-bold' : 'text-on-surface-variant') }}" data-countdown-start="{{ $cStartTs }}" data-countdown-end="{{ $cEndTs }}" data-countdown-progress="{{ $cPct }}">{{ $cBelum ? 'Mulai dalam...' : ($cLambat ? 'Terlambat...' : 'Memuat...') }}</p>
        @endif
        @if ($cAktualMulai || $cAktualSelesai)
            <div class="[font-variant-numeric:tabular-nums] mt-1.5 pt-1.5 border-t border-muted-border/60 text-[10px] leading-relaxed">
                @if ($cAktualMulai)
                    <p class="whitespace-nowrap"><span class="text-on-surface-variant">Mulai</span> <span class="font-bold text-on-surface">{{ $cAktualMulai->translatedFormat('d M H:i') }}</span></p>
                @endif
                @if ($cAktualSelesai)
                    <p class="whitespace-nowrap"><span class="text-on-surface-variant">Selesai</span> <span class="font-bold text-on-surface">{{ $cAktualSelesai->translatedFormat('d M H:i') }}</span></p>
                @endif
            </div>
        @endif
    </div>
@else
    <span class="text-on-surface-variant text-xs">&mdash;</span>
@endif