@extends('layouts.produksi')

@section('title', 'Permintaan Produksi')

@section('header-title', 'Permintaan Produksi')
@section('header-badge', ($stats['requested'] ?? 0).' Baru')
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
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['requested'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">perlu persetujuan produksi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">pending_actions</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Sedang Diproduksi</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $stats['diproses'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">tahap jahit & finishing</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">precision_manufacturing</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Siap QC</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['menunggu_qc'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu pemeriksaan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">fact_check</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $stats['selesai'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">unit layak jual</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">task_alt</span>
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
                    <option value="requested">Menunggu Konfirmasi</option>
                    <option value="diproses">Diproses</option>
                    <option value="menunggu_qc">Siap QC</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
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
                    @forelse ($orders as $po)
                        @php
                            $statusKey = $po->status;
                            $statusLabel = match ($statusKey) {
                                'requested' => 'Menunggu Konfirmasi',
                                'diproses' => 'Diproses',
                                'menunggu_qc' => 'Siap QC',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default => ucfirst($statusKey),
                            };
                            $nextStatuses = match ($statusKey) {
                                'requested' => ['diproses' => 'Diproses', 'dibatalkan' => 'Dibatalkan'],
                                'diproses' => ['menunggu_qc' => 'Siap QC', 'dibatalkan' => 'Dibatalkan'],
                                'menunggu_qc' => ['selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'],
                                default => [],
                            };
                            $unitTotal = $po->items->sum('jumlah_diminta');
                            $firstItem = $po->items->first();
                        @endphp
                        <tr data-table-row data-status-permintaan="{{ $statusKey }}" class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $po->nomor_produksi }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $po->dimulai_pada?->translatedFormat('d M Y') ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="text-on-surface">{{ $po->requester?->nama_lengkap ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">Prioritas {{ ucfirst($po->prioritas ?? '-') }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $firstItem?->productVariant?->product?->nama_produk ?? '-' }}@if ($po->items->count() > 1) <span class="font-normal text-on-surface-variant">+{{ $po->items->count() - 1 }} lainnya</span>@endif</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">Target {{ number_format($unitTotal, 0, ',', '.') }} unit</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant max-w-[220px]">{{ $po->targetWarehouse?->nama_gudang ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($statusKey === 'selesai')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-deep-onyx text-on-primary text-[10px] font-bold uppercase">Selesai</span>
                                @elseif ($statusKey === 'diproses')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Diproses</span>
                                @elseif ($statusKey === 'menunggu_qc')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Siap QC</span>
                                @elseif ($statusKey === 'dibatalkan')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Dibatalkan</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if (count($nextStatuses))
                                    <button type="button" data-modal-open="modal-kelola-{{ $po->production_order_id }}" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Kelola</button>
                                @else
                                    <span class="text-xs text-on-surface-variant">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-12 text-center text-on-surface-variant font-body-md text-sm">Belum ada permintaan produksi.</td></tr>
                    @endforelse
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

{{-- Modal Kelola Permintaan per baris --}}
@foreach ($orders as $po)
    @php
        $poNext = match ($po->status) {
            'requested' => ['diproses' => 'Diproses', 'dibatalkan' => 'Dibatalkan'],
            'diproses' => ['menunggu_qc' => 'Siap QC', 'dibatalkan' => 'Dibatalkan'],
            'menunggu_qc' => ['selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'],
            default => [],
        };
    @endphp
    @if (count($poNext))
        <div id="modal-kelola-{{ $po->production_order_id }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
                <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Kelola Permintaan</h3>
                        <p class="text-on-surface-variant font-body-md text-xs mt-1">{{ $po->nomor_produksi }} — {{ $po->items->first()?->productVariant?->product?->nama_produk ?? '-' }} • {{ number_format($po->items->sum('jumlah_diminta'), 0, ',', '.') }} unit</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('produksi.permintaan-produksi.status', $po) }}" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block raliva-label mb-2">Ubah Status</label>
                        <select name="status" required class="raliva-select">
                            @foreach ($poNext as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Catatan Produksi</label>
                        <textarea name="catatan" rows="3" placeholder="Konfirmasi bahan tersedia, estimasi selesai..." class="raliva-textarea">{{ old('catatan', $po->catatan) }}</textarea>
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
    @endif
@endforeach
@endsection
