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
        if ($hari > 0) $part[] = $hari . __('hari_u');
        if ($jam > 0)  $part[] = $jam . __('jam_u');
        if ($mnt > 0)  $part[] = $mnt . __('menit_u');
        if ($dtk > 0)  $part[] = $dtk . __('detik_u');
        return $part ? implode(' ', $part) : '0' . __('detik_u');
    }
}
@endphp