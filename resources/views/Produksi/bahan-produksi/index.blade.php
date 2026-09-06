@extends('layouts.produksi')

@section('title', 'Bahan Produksi')

@section('header-title', 'Bahan Produksi')
@section('header-badge', 'Opsional')
@section('header-subtitle', 'Kelola stok bahan baku untuk model bisnis produksi mandiri.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    <div data-reveal class="border border-gold-accent/25 bg-gold-accent/5 rounded-lg px-4 py-3 flex items-start gap-3">
        <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">info</span>
        <p class="text-on-surface-variant font-body-md text-sm">Fitur ini bersifat <span class="font-bold text-on-surface">opsional</span>. Aktifkan hanya jika toko Anda memproduksi sendiri dan perlu melacak stok bahan baku. Dapat dipangkas pada V1.</p>
    </div>

    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Jenis Bahan</span>
            <span class="raliva-figure text-[26px] text-on-surface">18</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">bahan tercatat</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">inventory</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Menipis</span>
            <span class="raliva-figure text-[26px] text-error">2</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">perlu isi ulang</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">warning</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-3 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pemakaian Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-on-surface">320<span class="text-[14px] font-normal"> meter</span></span>
            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="64"></div>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">straighten</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Nilai Stok Bahan</span>
            <span class="raliva-figure text-[26px] text-gold-accent">Rp 18,4<span class="text-[14px] font-normal">jt</span></span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">estimasi persediaan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">savings</span>
        </div>
    </section>

    {{-- Daftar Bahan --}}
    <section data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        @foreach ([
            ['nama' => 'Kain Katun Premium', 'stok' => 42, 'sat' => 'meter', 'min' => 20, 'status' => 'aman', 'pakai' => 38],
            ['nama' => 'Wool Charcoal', 'stok' => 18, 'sat' => 'meter', 'min' => 20, 'status' => 'menipis', 'pakai' => 85],
            ['nama' => 'Sutra Grade A', 'stok' => 65, 'sat' => 'meter', 'min' => 15, 'status' => 'aman', 'pakai' => 30],
            ['nama' => 'Benang Wol Beige', 'stok' => 12, 'sat' => 'roll', 'min' => 10, 'status' => 'menipis', 'pakai' => 70],
            ['nama' => 'Kancing Tanduk', 'stok' => 240, 'sat' => 'pcs', 'min' => 50, 'status' => 'aman', 'pakai' => 45],
            ['nama' => 'Lining Satin', 'stok' => 88, 'sat' => 'meter', 'min' => 20, 'status' => 'aman', 'pakai' => 25],
        ] as $bahan)
            <article data-reveal class="bg-surface-container-lowest border {{ $bahan['status'] === 'menipis' ? 'border-error/25' : 'border-muted-border' }} rounded-lg p-5 card-premium flex flex-col gap-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gold-accent">inventory_2</span>
                    </div>
                    @if ($bahan['status'] === 'menipis')
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Menipis</span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aman</span>
                    @endif
                </div>
                <div>
                    <p class="font-title-md text-sm text-on-surface">{{ $bahan['nama'] }}</p>
                    <p class="text-xs text-on-surface-variant mt-1">Stok {{ $bahan['stok'] }} {{ $bahan['sat'] }} • Min. {{ $bahan['min'] }} {{ $bahan['sat'] }}</p>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Terpakai Bulan Ini</span>
                        <span class="font-label-sm text-[11px] font-bold {{ $bahan['pakai'] >= 70 ? 'text-gold-accent' : 'text-on-surface' }}">{{ $bahan['pakai'] }}%</span>
                    </div>
                    <div class="h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                        <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="{{ $bahan['pakai'] }}"></div>
                    </div>
                </div>
                <div class="flex gap-gutter mt-auto">
                    <button type="button" onclick="showRalivaToast('Stok {{ $bahan['nama'] }} ditambah (demo).', 'add_circle')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tambah</button>
                    <button type="button" data-modal-open="modal-bahan-detail" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Riwayat</button>
                </div>
            </article>
        @endforeach
    </section>

    {{-- Tabel Detail Bahan (ringkas) --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Mutasi Bahan</h2>
            <div class="relative w-full sm:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari bahan..." data-table-search class="raliva-search" />
            </div>
        </div>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[760px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Waktu</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bahan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Jenis</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['waktu' => '22 Agu 09:15', 'bahan' => 'Wool Charcoal', 'jenis' => 'Pemakaian', 'jml' => '- 12 meter', 'ket' => 'PRD-0017 Blazer'],
                        ['waktu' => '20 Agu 14:00', 'bahan' => 'Kain Katun Premium', 'jenis' => 'Masuk', 'jml' => '+ 50 meter', 'ket' => 'Pembelian supplier'],
                        ['waktu' => '18 Agu 08:30', 'bahan' => 'Benang Wol Beige', 'jenis' => 'Pemakaian', 'jml' => '- 3 roll', 'ket' => 'PRD-0014 Cardigan'],
                        ['waktu' => '15 Agu 11:20', 'bahan' => 'Kancing Tanduk', 'jenis' => 'Pemakaian', 'jml' => '- 86 pcs', 'ket' => 'PRD-0017 Blazer'],
                    ] as $row)
                        <tr data-table-row class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['waktu'] }}</td>
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $row['bahan'] }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full {{ $row['jenis'] === 'Masuk' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' }} text-[10px] font-bold uppercase border">{{ $row['jenis'] }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold {{ str_starts_with($row['jml'], '+') ? 'text-secondary' : 'text-error' }} whitespace-nowrap">{{ $row['jml'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $row['ket'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

{{-- Modal Detail Bahan --}}
<div id="modal-bahan-detail" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-24 md:mt-40 w-[calc(100%-2rem)] max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-[28px] text-gold-accent">history</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface">Riwayat Bahan</h3>
            <p class="text-on-surface-variant font-body-md text-sm leading-relaxed">Detail pemakaian dan sisa stok diperbarui otomatis setiap produksi mencatat bahan.</p>
            <button type="button" data-modal-close class="w-full py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>
@endsection
