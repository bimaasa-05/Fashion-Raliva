@extends('layouts.produksi')

@section('title', 'Riwayat Produksi')

@section('header-title', 'Riwayat Produksi')
@section('header-subtitle', 'Histori produksi untuk audit dan evaluasi kinerja workshop.')

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
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produksi (Agu)</span>
            <span class="raliva-figure text-[26px] text-on-surface">32</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">batch selesai</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">history</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Waktu Rata-rata</span>
            <span class="raliva-figure text-[26px] text-on-surface">6,2<span class="text-[16px] font-normal"> hari</span></span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">per batch</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">timer</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kelayakan Rata-rata</span>
            <span class="raliva-figure text-[26px] text-secondary">96%</span>
            <span class="font-label-sm text-[11px] text-secondary">layak jual</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">verified</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Defect Rate</span>
            <span class="raliva-figure text-[26px] text-error">3,8%</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">dari total output</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">percent</span>
        </div>
    </section>

    {{-- Tabel Riwayat --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari produksi..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="hasil" class="raliva-select">
                    <option value="">Semua Hasil</option>
                    <option value="lolos">Lolos Baik</option>
                    <option value="catat">Ada Defect</option>
                </select>
            </div>
            <button type="button" onclick="showRalivaToast('Riwayat diekspor sebagai CSV (demo).', 'download')" class="shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors w-full lg:w-auto">
                <span class="material-symbols-outlined text-[18px]">download</span>Ekspor CSV
            </button>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[960px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Batch</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Periode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Output</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Defect</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Hasil</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['batch' => 'PRD-0015', 'produk' => 'Wide Leg Trousers', 'periode' => '10 — 20 Agu', 'output' => 60, 'layak' => 58, 'defect' => 2, 'key' => 'catat'],
                        ['batch' => 'PRD-0014', 'produk' => 'Knit Cardigan Rajut', 'periode' => '05 — 15 Agu', 'output' => 45, 'layak' => 43, 'defect' => 2, 'key' => 'catat'],
                        ['batch' => 'PRD-0013', 'produk' => 'Kemeja Linen Oversized', 'periode' => '02 — 12 Agu', 'output' => 120, 'layak' => 118, 'defect' => 2, 'key' => 'catat'],
                        ['batch' => 'PRD-0012', 'produk' => 'Silk Scarf Monogram', 'periode' => '28 Jul — 08 Agu', 'output' => 80, 'layak' => 80, 'defect' => 0, 'key' => 'lolos'],
                        ['batch' => 'PRD-0011', 'produk' => 'Trench Coat Signature', 'periode' => '22 Jul — 02 Agu', 'output' => 30, 'layak' => 30, 'defect' => 0, 'key' => 'lolos'],
                        ['batch' => 'PRD-0010', 'produk' => 'Blazer Wool Premium', 'periode' => '18 Jul — 28 Jul', 'output' => 40, 'layak' => 39, 'defect' => 1, 'key' => 'catat'],
                    ] as $row)
                        <tr data-table-row data-hasil="{{ $row['key'] }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $row['batch'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface font-bold">{{ $row['produk'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['periode'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-bold text-on-surface">{{ $row['output'] }}</span>
                                <span class="text-on-surface-variant text-xs"> ({{ $row['layak'] }} layak)</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold {{ $row['defect'] > 0 ? 'text-error' : 'text-on-surface-variant' }}">{{ $row['defect'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row['key'] === 'lolos')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Lolos Baik</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Ada Defect</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="showRalivaToast('Detail histori dibuka (demo).', 'visibility')" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Detail</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada riwayat yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>
    </section>
</div>
@endsection
