@php
    /* Sparkline KPI: polyline + area tipis, lebar penuh.
       preserveAspectRatio="none" agar meregang ikut kartu, stroke tetap
       rapi lewat vector-effect="non-scaling-stroke". */
    $vals = array_values(array_map('floatval', array_values($values ?? [])));
    $count = count($vals);
    $max = $vals ? max($vals) : 0.0;
    $min = $vals ? min($vals) : 0.0;
    $span = ($max - $min) ?: 1.0;
    $w = 120;
    $h = 26;
    $pad = 3;
    $pts = [];
    foreach ($vals as $i => $v) {
        $x = $count > 1 ? ($i / ($count - 1)) * $w : $w;
        $y = $h - $pad - ((($v - $min) / $span) * ($h - $pad * 2));
        $pts[] = round($x, 2).','.round($y, 2);
    }
    $line = implode(' ', $pts ?: ['0,'.$h, $w.','.$h]);
    $area = '0,'.$h.' '.$line.' '.$w.','.$h;
    $gid = 'saSpark'.preg_replace('/[^A-Za-z0-9]/', '', $id ?? md5($line));
@endphp
<svg viewBox="0 0 {{ $w }} {{ $h }}" preserveAspectRatio="none" class="w-full h-6" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="{{ $gid }}" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#8B1E3F" stop-opacity="0.30" />
            <stop offset="100%" stop-color="#8B1E3F" stop-opacity="0" />
        </linearGradient>
    </defs>
    <polygon points="{{ $area }}" fill="url(#{{ $gid }})" />
    <polyline points="{{ $line }}" fill="none" stroke="#8B1E3F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
</svg>
