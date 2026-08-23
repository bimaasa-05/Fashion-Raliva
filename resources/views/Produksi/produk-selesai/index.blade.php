@extends('layouts.produksi')

@section('title', 'Produk Selesai')

@section('header-title', 'Produk Selesai')
@section('header-badge', '3 Siap Serah')
@section('header-subtitle', 'Menandai hasil produksi yang siap diserahkan ke Gudang.')

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
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Selesai (Agu)</span>
            <span class="raliva-figure text-[26px] text-on-surface">236</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">unit layak</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Siap Serah ke Gudang</span>
            <span class="raliva-figure text-[26px] text-gold-accent">3</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu penyerahan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Sudah Diserahkan</span>
            <span class="raliva-figure text-[26px] text-secondary">28</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">batch bulan ini</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">warehouse</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Rata Waktu Produksi</span>
            <span class="raliva-figure text-[26px] text-on-surface">6,2<span class="text-[16px] font-normal"> hari</span></span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">per batch</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">timer</span>
        </div>
    </section>

    {{-- Tabel Produk Selesai --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari produk selesai..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="status-selesai" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="siap">Siap Serah</option>
                    <option value="serah">Sudah Diserahkan</option>
                </select>
            </div>
            <span class="font-label-sm text-[11px] text-on-surface-variant">Menghubungkan produksi dengan persediaan gudang.</span>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[920px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kode Selesai</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Qty Layak</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal Selesai</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Gudang Tujuan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['kode' => 'FIN-0012', 'produk' => 'Wide Leg Trousers', 'qty' => 58, 'tgl' => '20 Agu 2026', 'gudang' => 'Gudang Utama Bandung', 'status' => 'Siap Serah', 'key' => 'siap'],
                        ['kode' => 'FIN-0011', 'produk' => 'Knit Cardigan Rajut', 'qty' => 43, 'tgl' => '15 Agu 2026', 'gudang' => 'Gudang Utama Bandung', 'status' => 'Siap Serah', 'key' => 'siap'],
                        ['kode' => 'FIN-0010', 'produk' => 'Blazer Wool Premium', 'qty' => 38, 'tgl' => '18 Agu 2026', 'gudang' => 'Gudang Cabang Jakarta', 'status' => 'Siap Serah', 'key' => 'siap'],
                        ['kode' => 'FIN-0009', 'produk' => 'Kemeja Linen Oversized', 'qty' => 115, 'tgl' => '12 Agu 2026', 'gudang' => 'Gudang Utama Bandung', 'status' => 'Sudah Diserahkan', 'key' => 'serah'],
                        ['kode' => 'FIN-0008', 'produk' => 'Silk Scarf Monogram', 'qty' => 78, 'tgl' => '10 Agu 2026', 'gudang' => 'Gudang Utama Bandung', 'status' => 'Sudah Diserahkan', 'key' => 'serah'],
                    ] as $row)
                        <tr data-table-row data-status-selesai="{{ $row['key'] }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $row['kode'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface font-bold">{{ $row['produk'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-secondary">{{ number_format($row['qty'], 0, ',', '.') }} unit</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['tgl'] }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant text-[11px] whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[14px] text-gold-accent">warehouse</span>{{ $row['gudang'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row['key'] === 'siap')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Siap Serah</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Diserahkan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if ($row['key'] === 'siap')
                                    <button type="button" data-modal-open="modal-serah" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Serahkan</button>
                                @else
                                    <span class="text-xs text-on-surface-variant">—</span>
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
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada produk selesai yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>
    </section>
</div>

{{-- Modal Serah ke Gudang --}}
<div id="modal-serah" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Serah ke Gudang</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">FIN-0012 — Wide Leg Trousers • 58 unit layak</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Produk berhasil diserahkan ke Gudang." class="p-6 space-y-5">
            <div>
                <label class="block raliva-label mb-2">Gudang Tujuan</label>
                <select class="raliva-select">
                    <option selected>Gudang Utama Bandung — Kapasitas 72% terpakai</option>
                    <option>Gudang Cabang Jakarta — Kapasitas 48% terpakai</option>
                </select>
            </div>
            <div>
                <label for="serah-catatan" class="block raliva-label mb-2">Catatan Serah Terima</label>
                <textarea id="serah-catatan" rows="2" placeholder="Kondisi kemasan, kelengkapan label..." class="raliva-textarea"></textarea>
            </div>
            <div class="border border-gold-accent/20 bg-gold-accent/5 rounded-lg px-4 py-3 flex items-start gap-3">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">info</span>
                <p class="text-on-surface-variant font-body-md text-sm">Stok layak akan otomatis tercatat di Data Stok Gudang dan siap dijual.</p>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">local_shipping</span>Serahkan Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
