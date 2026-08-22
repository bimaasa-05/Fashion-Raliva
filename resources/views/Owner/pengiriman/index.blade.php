@extends('layouts.owner')

@section('title', 'Pengiriman')

@section('header-title', 'Pengiriman')
@section('header-badge', '45 Berjalan')
@section('header-subtitle', 'Atur pilihan kurir toko dan pantau proses pengiriman.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-56 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Pilihan Kurir --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading mb-6">Pilihan Pengiriman Toko</h2>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border border-muted-border rounded-lg px-5 py-4 mb-gutter bg-surface-container-low">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-deep-onyx text-on-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">rocket_launch</span>
                </div>
                <div>
                    <p class="font-title-md text-sm text-on-surface">Kurir Platform — Raliva Express</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Tarif terintegrasi, asuransi otomatis, resi realtime.</p>
                </div>
            </div>
            <label class="raliva-toggle">
                <input type="checkbox" class="sr-only peer" checked />
                <span class="raliva-toggle-track"></span>
                <span class="raliva-toggle-knob"></span>
            </label>
        </div>

        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-gutter mt-gutter">
            @foreach ([['JNE Reguler & YES', '1–3 hari kerja • Rp 12rb–28rb', true], ['SiCepat REG & BEST', '1–2 hari kerja • Rp 14rb–32rb', false], ['GoSend Instant', 'Same day • Rp 18rb+', false]] as $kurir)
                <div data-reveal class="border border-muted-border rounded-lg px-4 py-3.5 flex items-center justify-between gap-3 hover:border-gold-accent/40 transition-colors">
                    <div>
                        <p class="font-title-md text-sm text-on-surface">{{ $kurir[0] }}</p>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $kurir[1] }}</p>
                    </div>
                    <label class="raliva-toggle">
                        <input type="checkbox" class="sr-only peer" {{ $kurir[2] ? 'checked' : '' }} />
                        <span class="raliva-toggle-track"></span>
                        <span class="raliva-toggle-knob"></span>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="mt-gutter border border-muted-border rounded-lg px-5 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-gold-accent">store</span>
                </div>
                <div>
                    <p class="font-title-md text-sm text-on-surface">Kurir Toko Sendiri / Instan Lain</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Aktifkan jika ingin mengatur pengantaran secara mandiri.</p>
                </div>
            </div>
            <label class="raliva-toggle">
                <input type="checkbox" class="sr-only peer" />
                <span class="raliva-toggle-track"></span>
                <span class="raliva-toggle-knob"></span>
            </label>
        </div>
    </section>

    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Dalam Pengiriman</span>
            <span class="raliva-figure text-[26px] text-on-surface">45</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Terkirim Hari Ini</span>
            <span class="raliva-figure text-[26px] text-secondary">38</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">where_to_vote</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Pickup</span>
            <span class="raliva-figure text-[26px] text-gold-accent">7</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">schedule_send</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Rata-rata Waktu Kirim</span>
            <span class="raliva-figure text-[26px] text-on-surface"><span>2</span>,3 hari</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">timer</span>
        </div>
    </section>

    {{-- Tabel Pengiriman --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Proses Pengiriman</h2>
            <div class="flex items-center gap-gutter w-full sm:w-auto">
                <select data-table-filter="status-kirim" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="pickup">Menunggu Pickup</option>
                    <option value="jalan">Dalam Pengiriman</option>
                    <option value="sampai">Terkirim</option>
                </select>
            </div>
        </div>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[880px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tujuan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kurir & Resi</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['kode' => '#RLV-2091', 'tgl' => '21 Agu', 'tujuan' => 'Jakarta Selatan', 'kurir' => 'Raliva Express', 'resi' => 'RLVX-88123', 'status' => 'Dalam Pengiriman', 'key' => 'jalan'],
                        ['kode' => '#RLV-2090', 'tgl' => '21 Agu', 'tujuan' => 'Bandung Kota', 'kurir' => 'JNE YES', 'resi' => 'JNE-Y77120', 'status' => 'Dalam Pengiriman', 'key' => 'jalan'],
                        ['kode' => '#RLV-2087', 'tgl' => '20 Agu', 'tujuan' => 'Surabaya Barat', 'kurir' => 'SiCepat BEST', 'resi' => 'SCP-90211', 'status' => 'Terkirim', 'key' => 'sampai'],
                        ['kode' => '#RLV-2086', 'tgl' => '20 Agu', 'tujuan' => 'Depok', 'kurir' => 'GoSend Instant', 'resi' => 'GO-55102', 'status' => 'Terkirim', 'key' => 'sampai'],
                        ['kode' => '#RLV-2085', 'tgl' => '22 Agu', 'tujuan' => 'Tangerang Selatan', 'kurir' => 'Raliva Express', 'resi' => '—', 'status' => 'Menunggu Pickup', 'key' => 'pickup'],
                        ['kode' => '#RLV-2084', 'tgl' => '22 Agu', 'tujuan' => 'Bekasi Timur', 'kurir' => 'JNE Reguler', 'resi' => '—', 'status' => 'Menunggu Pickup', 'key' => 'pickup'],
                    ] as $ship)
                        <tr data-table-row data-status-kirim="{{ $ship['key'] }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $ship['kode'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $ship['tgl'] }} Agu</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $ship['tujuan'] }}</td>
                            <td class="py-3.5 px-4">
                                <p class="text-on-surface">{{ $ship['kurir'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5 font-mono">{{ $ship['resi'] }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($ship['key'] === 'sampai')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="material-symbols-outlined fill text-[12px]">check_circle</span>Terkirim</span>
                                @elseif ($ship['key'] === 'jalan')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30"><span class="material-symbols-outlined fill text-[12px]">local_shipping</span>Dalam Perjalanan</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant"><span class="material-symbols-outlined fill text-[12px]">schedule</span>Menunggu Pickup</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="showRalivaToast('Lacak paket dibuka (demo).', 'location_searching')" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Lacak</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pengiriman pada status ini.</p>
        </div>
    </section>
</div>
@endsection
