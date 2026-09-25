{{-- Formatter durasi terpadu (PHP). Unit: h=hari, j=jam, m=menit, d=detik. --}}
@php
if (! function_exists('produksiFmtDetik')) {
    function produksiFmtDetik(int $detik): string
    {
        $abs = abs($detik);
        $hari = intdiv($abs, 86400);
        $jam  = intdiv($abs % 86400, 3600);
        $mnt  = intdiv($abs % 3600, 60);
        $dtk  = $abs % 60;
        $part = [];
        if ($hari > 0) $part[] = $hari . 'h';
        if ($jam > 0)  $part[] = $jam . 'j';
        if ($mnt > 0)  $part[] = $mnt . 'm';
        if ($dtk > 0)  $part[] = $dtk . 'd';
        return $part ? implode(' ', $part) : '0d';
    }
}
@endphp