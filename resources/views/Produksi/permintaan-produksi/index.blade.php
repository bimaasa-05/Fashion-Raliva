@extends('layouts.produksi')

@section('title', 'Permintaan Produksi')

@section('header-title', 'Permintaan Produksi')
@section('header-badge', '4 Baru')
@section('header-subtitle', 'Lihat permintaan dari Owner/Admin dan kelola status proses produksinya.')

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
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Konfirmasi</span>
            <span class="raliva-figure text-[26px] text-gold-accent">4</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">perlu persetujuan produksi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">pending_actions</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Sedang Diproduksi</span>
            <span class="raliva-figure text-[26px] text-on-surface">6</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">tahap jahit & finishing</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">precision_manufacturing</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Siap QC</span>
            <span class="raliva-figure text-[26px] text-gold-accent">3</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu pemeriksaan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">fact_check</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai Minggu Ini</span>
            <span class="raliva-figure text-[26px] text-secondary">42</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">unit layak jual</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
        </div>
    </section>

    {{-- Tabel Permintaan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari kode atau produk..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="status-permintaan" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="menunggu">Menunggu Konfirmasi</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <span class="font-label-sm text-[11px] text-on-surface-variant">Terstruktur berdasarkan prioritas Owner/Admin</span>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[980px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Permintaan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Diajukan Oleh</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk & Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bahan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['kode' => 'PRQ-0043', 'tgl' => '22 Agu 2026', 'pengaju' => 'Bima Prasetya (Owner)', 'produk' => 'Trench Coat Signature', 'qty' => 25, 'bahan' => 'Wool Premium, Lining Satin', 'status' => 'Menunggu Konfirmasi', 'key' => 'menunggu'],
                        ['kode' => 'PRQ-0042', 'tgl' => '20 Agu 2026', 'pengaju' => 'Sinta Dewi (Admin)', 'produk' => 'Silk Scarf Monogram', 'qty' => 80, 'bahan' => 'Sutra Grade A', 'status' => 'Disetujui', 'key' => 'disetujui'],
                        ['kode' => 'PRQ-0041', 'tgl' => '18 Agu 2026', 'pengaju' => 'Bima Prasetya (Owner)', 'produk' => 'Blazer Wool Premium', 'qty' => 40, 'bahan' => 'Wool Charcoal, Kancing Tanduk', 'status' => 'Diproses', 'key' => 'diproses'],
                        ['kode' => 'PRQ-0040', 'tgl' => '16 Agu 2026', 'pengaju' => 'Sinta Dewi (Admin)', 'produk' => 'Kemeja Linen Oversized', 'qty' => 120, 'bahan' => 'Linen Natural', 'status' => 'Diproses', 'key' => 'diproses'],
                        ['kode' => 'PRQ-0039', 'tgl' => '12 Agu 2026', 'pengaju' => 'Bima Prasetya (Owner)', 'produk' => 'Wide Leg Trousers', 'qty' => 60, 'bahan' => 'Katun Drill', 'status' => 'Selesai', 'key' => 'selesai'],
                        ['kode' => 'PRQ-0038', 'tgl' => '08 Agu 2026', 'pengaju' => 'Sinta Dewi (Admin)', 'produk' => 'Knit Cardigan Rajut', 'qty' => 45, 'bahan' => 'Benang Wol Beige', 'status' => 'Selesai', 'key' => 'selesai'],
                    ] as $row)
                        <tr data-table-row data-status-permintaan="{{ $row['key'] }}" class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $row['kode'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $row['tgl'] }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="text-on-surface">{{ $row['pengaju'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">Raliva Atelier Jakarta</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $row['produk'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">Target {{ number_format($row['qty'], 0, ',', '.') }} unit</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant max-w-[220px]">{{ $row['bahan'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row['key'] === 'selesai')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-deep-onyx text-on-primary text-[10px] font-bold uppercase">Selesai</span>
                                @elseif ($row['key'] === 'diproses')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Diproses</span>
                                @elseif ($row['key'] === 'disetujui')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Disetujui</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" data-modal-open="modal-kelola-permintaan" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Kelola</button>
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
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada permintaan yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>
    </section>
</div>

{{-- Modal Kelola Permintaan --}}
<div id="modal-kelola-permintaan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Kelola Permintaan</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">PRQ-0043 — Trench Coat Signature • 25 unit</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Status permintaan berhasil diperbarui." class="p-6 space-y-5">
            <div>
                <label class="block raliva-label mb-2">Ubah Status</label>
                <select class="raliva-select">
                    <option>Menunggu Konfirmasi</option>
                    <option selected>Disetujui — Siap Diproduksi</option>
                    <option>Diproses</option>
                    <option>Selesai</option>
                    <option>Ditolak</option>
                </select>
            </div>
            <div>
                <label for="kelola-catatan" class="block raliva-label mb-2">Catatan Produksi</label>
                <textarea id="kelola-catatan" rows="3" placeholder="Konfirmasi bahan tersedia, estimasi selesai..." class="raliva-textarea"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>Perbarui Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
