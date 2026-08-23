@extends('layouts.produksi')

@section('title', 'Data Produksi')

@section('header-title', 'Data Produksi')
@section('header-badge', '6 Berjalan')
@section('header-subtitle', 'Mencatat detail produk, jumlah target, bahan dan status produksi.')

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
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Catatan</span>
            <span class="raliva-figure text-[26px] text-on-surface">48</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">sejak Jan 2026</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">description</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Berjalan</span>
            <span class="raliva-figure text-[26px] text-on-surface">6</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">aktif di workshop</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">precision_manufacturing</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai</span>
            <span class="raliva-figure text-[26px] text-secondary">32</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">telah diserahkan ke gudang</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Tertunda</span>
            <span class="raliva-figure text-[26px] text-error">2</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">butuh tindak lanjut</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">pending</span>
        </div>
    </section>

    {{-- Tabel Data Produksi --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari produk atau kode..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="status-produksi" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="berjalan">Berjalan</option>
                    <option value="selesai">Selesai</option>
                    <option value="tertunda">Tertunda</option>
                </select>
            </div>
            <button type="button" data-modal-open="modal-tambah-produksi" class="shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium w-full lg:w-auto">
                <span class="material-symbols-outlined text-[18px]">add</span>Catat Produksi
            </button>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[960px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Target</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bahan Utama</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Jadwal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['kode' => 'PRD-0018', 'produk' => 'Trench Coat Signature', 'qty' => 25, 'bahan' => 'Wool Premium', 'jadwal' => '22 Agu — 30 Agu', 'status' => 'Berjalan', 'key' => 'berjalan'],
                        ['kode' => 'PRD-0017', 'produk' => 'Blazer Wool Premium', 'qty' => 40, 'bahan' => 'Wool Charcoal', 'jadwal' => '18 Agu — 28 Agu', 'status' => 'Berjalan', 'key' => 'berjalan'],
                        ['kode' => 'PRD-0016', 'produk' => 'Silk Scarf Monogram', 'qty' => 80, 'bahan' => 'Sutra Grade A', 'jadwal' => '15 Agu — 05 Sep', 'status' => 'Berjalan', 'key' => 'berjalan'],
                        ['kode' => 'PRD-0015', 'produk' => 'Wide Leg Trousers', 'qty' => 60, 'bahan' => 'Katun Drill', 'jadwal' => '10 Agu — 20 Agu', 'status' => 'Selesai', 'key' => 'selesai'],
                        ['kode' => 'PRD-0014', 'produk' => 'Knit Cardigan Rajut', 'qty' => 45, 'bahan' => 'Benang Wol Beige', 'jadwal' => '05 Agu — 15 Agu', 'status' => 'Selesai', 'key' => 'selesai'],
                        ['kode' => 'PRD-0013', 'produk' => 'Kemeja Linen Oversized', 'qty' => 120, 'bahan' => 'Linen Natural', 'jadwal' => '02 Agu — 12 Agu', 'status' => 'Tertunda', 'key' => 'tertunda'],
                    ] as $row)
                        <tr data-table-row data-status-produksi="{{ $row['key'] }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $row['kode'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface font-bold">{{ $row['produk'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface">{{ number_format($row['qty'], 0, ',', '.') }} unit</td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $row['bahan'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['jadwal'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row['key'] === 'selesai')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-deep-onyx text-on-primary text-[10px] font-bold uppercase">Selesai</span>
                                @elseif ($row['key'] === 'tertunda')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Tertunda</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Berjalan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" data-modal-open="modal-ubah-produksi" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Kelola</button>
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
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada data produksi yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>
    </section>
</div>

{{-- Modal Tambah Produksi --}}
<div id="modal-tambah-produksi" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Catat Data Produksi</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Jejak proses produksi yang terstruktur dan mudah ditelusuri.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Data produksi berhasil dicatat." class="p-6 space-y-5">
            <div>
                <label class="block raliva-label mb-2">Produk</label>
                <select class="raliva-select">
                    <option>Trench Coat Signature</option>
                    <option>Blazer Wool Premium</option>
                    <option selected>Kemeja Linen Oversized</option>
                    <option>Silk Scarf Monogram</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="dp-qty" class="block raliva-label mb-2">Jumlah Target</label>
                    <input id="dp-qty" type="number" value="80" min="1" required class="raliva-input" />
                </div>
                <div>
                    <label for="dp-bahan" class="block raliva-label mb-2">Bahan Utama</label>
                    <input id="dp-bahan" type="text" placeholder="cth. Sutra Grade A" class="raliva-input" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="dp-mulai" class="block raliva-label mb-2">Tanggal Mulai</label>
                    <input id="dp-mulai" type="date" value="2026-08-22" required class="raliva-input" />
                </div>
                <div>
                    <label for="dp-selesai" class="block raliva-label mb-2">Target Selesai</label>
                    <input id="dp-selesai" type="date" value="2026-08-30" required class="raliva-input" />
                </div>
            </div>
            <div>
                <label for="dp-status" class="block raliva-label mb-2">Status Awal</label>
                <select id="dp-status" class="raliva-select">
                    <option selected>Berjalan</option>
                    <option>Selesai</option>
                    <option>Tertunda</option>
                </select>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span>Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Ubah (dummy kelola) --}}
<div id="modal-ubah-produksi" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-24 md:mt-40 w-[calc(100%-2rem)] max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl">
        <div class="p-6 text-center space-y-4">
            <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-[28px] text-gold-accent">edit</span>
            </div>
            <h3 class="font-title-md text-title-md text-on-surface">Kelola Produksi</h3>
            <p class="text-on-surface-variant font-body-md text-sm leading-relaxed">Perbarui jumlah, bahan atau status produksi untuk jejak yang akurat.</p>
            <div class="flex flex-col-reverse gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
                <button type="button" data-modal-open="modal-tambah-produksi" onclick="showRalivaToast('Form edit dibuka (demo).', 'edit')" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Buka Form Edit</button>
            </div>
        </div>
    </div>
</div>
@endsection
