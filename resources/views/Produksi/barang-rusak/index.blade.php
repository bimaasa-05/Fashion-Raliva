@extends('layouts.produksi')

@section('title', 'Barang Rusak')

@section('header-title', 'Barang Rusak')
@section('header-badge', '12 Defect')
@section('header-subtitle', 'Mencatat hasil defect agar tidak dihitung sebagai stok siap jual.')

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
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Defect (Agu)</span>
            <span class="raliva-figure text-[26px] text-error">12</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">unit produksi cacat</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">report</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Rasio Defect</span>
            <span class="raliva-figure text-[26px] text-error">4,8<span class="text-[16px] font-normal">%</span></span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">dari 248 unit diperiksa</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">percent</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-3 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Tingkat Kelayakan</span>
            <span class="raliva-figure text-[26px] text-secondary">95,2%</span>
            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full" data-progress="95"></div>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">verified</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Perlu Tindakan</span>
            <span class="raliva-figure text-[26px] text-gold-accent">5</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu keputusan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">pending_actions</span>
        </div>
    </section>

    {{-- Tabel Barang Rusak --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari kode atau produk..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="tindakan" class="raliva-select">
                    <option value="">Semua Tindakan</option>
                    <option value="musnah">Dimusnahkan</option>
                    <option value="rework">Perbaikan Ulang</option>
                    <option value="menunggu">Menunggu Keputusan</option>
                </select>
            </div>
            <button type="button" data-modal-open="modal-rusak" class="shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium w-full lg:w-auto">
                <span class="material-symbols-outlined text-[18px]">add</span>Catat Defect
            </button>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[960px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Alasan Defect</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Tindakan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['kode' => 'DEF-0012', 'produk' => 'Blazer Wool Premium', 'qty' => 2, 'alasan' => 'Jahitan kancing lepas & noda kain', 'tgl' => '22 Agu 2026', 'tindak' => 'Menunggu Keputusan', 'key' => 'menunggu'],
                        ['kode' => 'DEF-0011', 'produk' => 'Wide Leg Trousers', 'qty' => 2, 'alasan' => 'Ukuran tidak konsisten (selisih 2cm)', 'tgl' => '20 Agu 2026', 'tindak' => 'Perbaikan Ulang', 'key' => 'rework'],
                        ['kode' => 'DEF-0010', 'produk' => 'Silk Scarf Monogram', 'qty' => 3, 'alasan' => 'Cacat printing motif (3 unit)', 'tgl' => '18 Agu 2026', 'tindak' => 'Dimusnahkan', 'key' => 'musnah'],
                        ['kode' => 'DEF-0009', 'produk' => 'Knit Cardigan Rajut', 'qty' => 2, 'alasan' => 'Benang lepas di ujung lengan', 'tgl' => '15 Agu 2026', 'tindak' => 'Perbaikan Ulang', 'key' => 'rework'],
                        ['kode' => 'DEF-0008', 'produk' => 'Trench Coat Signature', 'qty' => 1, 'alasan' => 'Kancing hilang saat QC', 'tgl' => '10 Agu 2026', 'tindak' => 'Menunggu Keputusan', 'key' => 'menunggu'],
                        ['kode' => 'DEF-0007', 'produk' => 'Kemeja Linen Oversized', 'qty' => 2, 'alasan' => 'Warna pudar tidak sesuai swatch', 'tgl' => '05 Agu 2026', 'tindak' => 'Dimusnahkan', 'key' => 'musnah'],
                    ] as $row)
                        <tr data-table-row data-tindakan="{{ $row['key'] }}" class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $row['kode'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface font-bold">{{ $row['produk'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-error">{{ $row['qty'] }} unit</td>
                            <td class="py-3.5 px-4 text-on-surface-variant max-w-[280px]">{{ $row['alasan'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['tgl'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row['key'] === 'musnah')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Dimusnahkan</span>
                                @elseif ($row['key'] === 'rework')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Rework</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" data-modal-open="modal-detail-rusak" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Detail</button>
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
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada barang rusak yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            Barang defect tidak akan pernah masuk sebagai stok siap jual di Gudang. Pilih tindakan yang tepat untuk setiap kasus.
        </p>
    </section>
</div>

{{-- Modal Catat Defect --}}
<div id="modal-rusak" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Catat Barang Rusak</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Defect dicatat agar stok jual tetap akurat.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Barang rusak berhasil dicatat." class="p-6 space-y-5">
            <div>
                <label class="block raliva-label mb-2">Kode Produksi</label>
                <select class="raliva-select">
                    <option>PRD-0017 • Blazer Wool Premium</option>
                    <option selected>PRD-0018 • Trench Coat Signature</option>
                    <option>PRD-0016 • Silk Scarf Monogram</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="rs-qty" class="block raliva-label mb-2">Jumlah Defect</label>
                    <input id="rs-qty" type="number" value="2" min="1" required class="raliva-input" />
                </div>
                <div>
                    <label for="rs-tindak" class="block raliva-label mb-2">Tindakan</label>
                    <select id="rs-tindak" class="raliva-select">
                        <option selected>Menunggu Keputusan</option>
                        <option>Perbaikan Ulang</option>
                        <option>Dimusnahkan</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="rs-alasan" class="block raliva-label mb-2">Alasan & Catatan</label>
                <textarea id="rs-alasan" rows="3" placeholder="cth. Jahitan robek di bagian kerah..." required class="raliva-textarea"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">report</span>Simpan Defect
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Detail --}}
<div id="modal-detail-rusak" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-24 md:mt-40 w-[calc(100%-2rem)] max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 rounded-full bg-error/10 border border-error/20 flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-[28px] text-error">report</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface">Detail Defect</h3>
            <p class="text-on-surface-variant font-body-md text-sm leading-relaxed">DEF-0011 — Wide Leg Trousers • 2 unit selisih ukuran 2cm, dikirim ke tim rework estimasi 2 hari.</p>
            <button type="button" data-modal-close class="w-full py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>
@endsection
