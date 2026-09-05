@extends('layouts.produksi')

@section('title', 'Pemeriksaan Kualitas')

@section('header-title', 'Pemeriksaan Kualitas')
@section('header-badge', '3 Menunggu')
@section('header-subtitle', 'Mencatat jumlah barang layak dan defect agar stok gudang akurat.')

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
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Diperiksa Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-on-surface">248</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">unit produksi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">fact_check</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Layak Jual</span>
            <span class="raliva-figure text-[26px] text-secondary">236</span>
            <span class="font-label-sm text-[11px] text-secondary">95,2% lolos QC</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">verified</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-3 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Tingkat Kelayakan</span>
            <span class="raliva-figure text-[26px] text-secondary">95%</span>
            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full" data-progress="95"></div>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">equalizer</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Defect</span>
            <span class="raliva-figure text-[26px] text-error">12</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">perlu penanganan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">report</span>
        </div>
    </section>

    {{-- Tabel Pemeriksaan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari kode produksi..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="status-qc" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="lolos">Lolos</option>
                    <option value="tinjau">Perlu Tinjau</option>
                    <option value="menunggu">Menunggu QC</option>
                </select>
            </div>
            <button type="button" data-modal-open="modal-qc" class="shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium w-full lg:w-auto">
                <span class="material-symbols-outlined text-[18px]">add_task</span>Catat QC
            </button>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[980px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kode QC</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produksi / Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Diperiksa</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Layak</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Defect</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Catatan Defect</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['qc' => 'QC-0012', 'prd' => 'PRD-0017 • Blazer Wool Premium', 'periksa' => 40, 'layak' => 38, 'rusak' => 2, 'catatan' => 'Jahitan kancing lepas (1), noda kain (1)', 'status' => 'Lolos', 'key' => 'lolos'],
                        ['qc' => 'QC-0011', 'prd' => 'PRD-0018 • Trench Coat Signature', 'periksa' => 25, 'layak' => 25, 'rusak' => 0, 'catatan' => '—', 'status' => 'Lolos', 'key' => 'lolos'],
                        ['qc' => 'QC-0010', 'prd' => 'PRD-0016 • Silk Scarf Monogram', 'periksa' => 0, 'layak' => 0, 'rusak' => 0, 'catatan' => 'Menunggu produksi selesai', 'status' => 'Menunggu QC', 'key' => 'menunggu'],
                        ['qc' => 'QC-0009', 'prd' => 'PRD-0015 • Wide Leg Trousers', 'periksa' => 60, 'layak' => 58, 'rusak' => 2, 'catatan' => 'Ukuran tidak konsisten (2)', 'status' => 'Perlu Tinjau', 'key' => 'tinjau'],
                        ['qc' => 'QC-0008', 'prd' => 'PRD-0014 • Knit Cardigan Rajut', 'periksa' => 45, 'layak' => 43, 'rusak' => 2, 'catatan' => 'Benang lepas di ujung lengan', 'status' => 'Lolos', 'key' => 'lolos'],
                    ] as $row)
                        <tr data-table-row data-status-qc="{{ $row['key'] }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $row['qc'] }}</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ explode(' • ', $row['prd'])[0] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ explode(' • ', $row['prd'])[1] }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center text-on-surface">{{ $row['periksa'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-secondary">{{ $row['layak'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold {{ $row['rusak'] > 0 ? 'text-error' : 'text-on-surface-variant' }}">{{ $row['rusak'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant max-w-[260px]">{{ $row['catatan'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row['key'] === 'lolos')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $row['status'] }}</span>
                                @elseif ($row['key'] === 'tinjau')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $row['status'] }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $row['status'] }}</span>
                                @endif
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
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pemeriksaan yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>
    </section>
</div>

{{-- Modal QC --}}
<div id="modal-qc" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Catat Pemeriksaan QC</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Hasil QC menentukan stok layak masuk gudang.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Pemeriksaan QC berhasil dicatat." class="p-6 space-y-5">
            <div>
                <label class="block raliva-label mb-2">Kode Produksi</label>
                <select class="raliva-select">
                    <option>PRD-0017 • Blazer Wool Premium (40 unit)</option>
                    <option>PRD-0018 • Trench Coat Signature (25 unit)</option>
                    <option selected>PRD-0016 • Silk Scarf Monogram (80 unit)</option>
                </select>
            </div>
            <div class="grid grid-cols-3 gap-gutter">
                <div>
                    <label for="qc-diperiksa" class="block raliva-label mb-2">Diperiksa</label>
                    <input id="qc-diperiksa" type="number" value="80" min="0" required class="raliva-input" />
                </div>
                <div>
                    <label for="qc-layak" class="block raliva-label mb-2">Layak</label>
                    <input id="qc-layak" type="number" value="78" min="0" required class="raliva-input" />
                </div>
                <div>
                    <label for="qc-rusak" class="block raliva-label mb-2">Defect</label>
                    <input id="qc-rusak" type="number" value="2" min="0" required class="raliva-input" />
                </div>
            </div>
            <div>
                <label for="qc-catatan" class="block raliva-label mb-2">Alasan Defect</label>
                <textarea id="qc-catatan" rows="2" placeholder="cth. Jahitan kancing kurang kuat pada 2 unit..." class="raliva-textarea"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">fact_check</span>Simpan QC
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
