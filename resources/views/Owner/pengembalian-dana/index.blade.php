@extends('layouts.owner')

@section('title', 'Pengembalian Dana')

@section('header-title', 'Pengembalian Dana')
@section('header-badge', '2 Aktif')
@section('header-subtitle', 'Pantau kasus refund yang terkait dengan toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kasus Berjalan</span>
            <span class="raliva-figure text-[26px] text-gold-accent">2</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">assignment_return</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-on-surface">5</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Nilai Refund (Agu)</span>
            <span class="raliva-figure text-[26px] text-error">Rp 1.890.000</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">money_off</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Rasio Refund</span>
            <span class="raliva-figure text-[26px] text-secondary"><span>1</span>%</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">dari 389 pesanan bulan ini</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">percent</span>
        </div>
    </section>

    {{-- Tabel Kasus Refund --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Daftar Kasus Pengembalian Dana</h2>
            <select data-table-filter="status-refund" class="raliva-select">
                <option value="">Semua Status</option>
                <option value="diminta">Diminta</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Refund Selesai</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[940px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pesanan / Tanggal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Customer</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Alasan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Nominal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['kode' => '#RLV-2085', 'tgl' => '22 Agu 2026', 'customer' => 'Tania Kusuma', 'alasan' => 'Barang tidak sesuai deskripsi — warna berbeda', 'nominal' => 'Rp 459.000', 'status' => 'Diminta', 'key' => 'diminta'],
                        ['kode' => '#RLV-2079', 'tgl' => '21 Agu 2026', 'customer' => 'Hendra Wijaya', 'alasan' => 'Paket hilang dalam pengiriman', 'nominal' => 'Rp 789.000', 'status' => 'Diproses', 'key' => 'diproses'],
                        ['kode' => '#RLV-2076', 'tgl' => '18 Agu 2026', 'customer' => 'Maya Sari', 'alasan' => 'Ukuran tidak pas', 'nominal' => 'Rp 320.000', 'status' => 'Refund Selesai', 'key' => 'selesai'],
                        ['kode' => '#RLV-2071', 'tgl' => '15 Agu 2026', 'customer' => 'Yoga Pratama', 'alasan' => 'Batal sebelum dikirim', 'nominal' => 'Rp 529.000', 'status' => 'Refund Selesai', 'key' => 'selesai'],
                        ['kode' => '#RLV-2068', 'tgl' => '12 Agu 2026', 'customer' => 'Lina Marlina', 'alasan' => 'Menyesal membeli (tanpa cacat produk)', 'nominal' => 'Rp 259.000', 'status' => 'Ditolak', 'key' => 'ditolak'],
                    ] as $row)
                        <tr data-table-row data-status-refund="{{ $row['key'] }}" class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface whitespace-nowrap">{{ $row['kode'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $row['tgl'] }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $row['customer'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant max-w-[260px]">{{ $row['alasan'] }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">{{ $row['nominal'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row['key'] === 'selesai')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $row['status'] }}</span>
                                @elseif ($row['key'] === 'ditolak')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">{{ $row['status'] }}</span>
                                @elseif ($row['key'] === 'diproses')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $row['status'] }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $row['status'] }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if ($row['key'] !== 'ditolak' && $row['key'] !== 'selesai')
                                    <button type="button" onclick="showRalivaToast('Detail kasus refund dibuka (demo).', 'visibility')" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Tinjau</button>
                                @else
                                    <button type="button" onclick="showRalivaToast('Detail kasus refund dibuka (demo).', 'visibility')" class="text-xs font-medium text-on-surface-variant hover:text-gold-accent transition-colors whitespace-nowrap">Detail</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada kasus refund pada status ini.</p>
        </div>

        <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            Keputusan akhir refund ditentukan Super Admin sesuai kebijakan platform. Nilai refund yang disetujui akan otomatis dipotong dari saldo toko.
        </p>
    </section>
</div>
@endsection
