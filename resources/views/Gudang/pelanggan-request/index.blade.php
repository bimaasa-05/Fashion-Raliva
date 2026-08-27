@extends('layouts.gudang')

@section('title', 'Pelanggan Request')

@section('header-title', 'Pelanggan Request')
@section('header-badge', '5 Menunggu Cek')
@section('header-subtitle', 'Kelola permintaan pelanggan — custom (Pilih bahan & hitung modal) dan produk tetap.')

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
    {{-- Alur Info --}}
    <div data-reveal class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
        <div class="border border-gold-accent/25 bg-gold-accent/5 rounded-lg p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">styler</span>
            <div>
                <p class="font-title-md text-sm text-on-surface">Custom: Pelanggan → Admin → Produksi → Gudang</p>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Pelanggan pilih bahan, Produksi hitung modal (HPP) & total, Gudang cek ketersediaan bahan & konfirmasi.</p>
            </div>
        </div>
        <div class="border border-muted-border bg-surface-container-low rounded-lg p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-[20px] text-on-surface-variant mt-0.5">inventory_2</span>
            <div>
                <p class="font-title-md text-sm text-on-surface">Produk Tetap: Customer → Admin → Gudang</p>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Gudang cek stok fisik produk jadi dan konfirmasi siap kirim.</p>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Cek</span>
            <span class="raliva-figure text-[26px] text-gold-accent">5</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">perlu pengecekan stok/bahan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">pending_actions</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Tersedia</span>
            <span class="raliva-figure text-[26px] text-secondary">8</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">siap konfirmasi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">check_circle</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Diteruskan Produksi</span>
            <span class="raliva-figure text-[26px] text-on-surface">3</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">custom perlu produksi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">precision_manufacturing</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Tidak Tersedia</span>
            <span class="raliva-figure text-[26px] text-error">2</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">bahan/stok kosong</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">block</span>
        </div>
    </section>

    {{-- Tabel Request --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari pelanggan atau produk..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="jenis" class="raliva-select">
                    <option value="">Semua Jenis</option>
                    <option value="custom">Custom</option>
                    <option value="tetap">Produk Tetap</option>
                </select>
                <select data-table-filter="status-request" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="cek">Menunggu Cek</option>
                    <option value="tersedia">Tersedia</option>
                    <option value="produksi">Diteruskan Produksi</option>
                    <option value="siap">Siap Ambil/Kirim</option>
                    <option value="selesai">Selesai</option>
                    <option value="kosong">Tidak Tersedia</option>
                </select>
            </div>
            <span class="font-label-sm text-[11px] text-on-surface-variant">Gudang cek ketersediaan, bukan kelola pelanggan langsung.</span>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[1100px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Request</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pelanggan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Jenis / Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bahan Pilihan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Modal (HPP)</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Total</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['kode' => 'REQ-0081', 'tgl' => '22 Agu 2026', 'cust' => 'Sinta Maharani', 'jenis' => 'Custom', 'jkey' => 'custom', 'produk' => 'Blazer Custom', 'bahan' => 'Wool Charcoal + Lining Satin', 'hpp' => 'Rp 420.000', 'total' => 'Rp 750.000', 'status' => 'Menunggu Cek', 'skey' => 'cek'],
                        ['kode' => 'REQ-0080', 'tgl' => '21 Agu 2026', 'cust' => 'Dian Pratiwi', 'jenis' => 'Custom', 'jkey' => 'custom', 'produk' => 'Dress Pesta Custom', 'bahan' => 'Sutra Grade A + Payet', 'hpp' => 'Rp 680.000', 'total' => 'Rp 1.200.000', 'status' => 'Diteruskan Produksi', 'skey' => 'produksi'],
                        ['kode' => 'REQ-0079', 'tgl' => '21 Agu 2026', 'cust' => 'Budi Santoso', 'jenis' => 'Produk Tetap', 'jkey' => 'tetap', 'produk' => 'Kemeja Linen Oversized', 'bahan' => '—', 'hpp' => 'Rp 180.000', 'total' => 'Rp 289.000', 'status' => 'Tersedia', 'skey' => 'tersedia'],
                        ['kode' => 'REQ-0078', 'tgl' => '20 Agu 2026', 'cust' => 'Rina Wulandari', 'jenis' => 'Produk Tetap', 'jkey' => 'tetap', 'produk' => 'Silk Scarf Monogram', 'bahan' => '—', 'hpp' => 'Rp 95.000', 'total' => 'Rp 259.000', 'status' => 'Tidak Tersedia', 'skey' => 'kosong'],
                        ['kode' => 'REQ-0077', 'tgl' => '20 Agu 2026', 'cust' => 'Andi Gunawan', 'jenis' => 'Custom', 'jkey' => 'custom', 'produk' => 'Celana Custom Slim', 'bahan' => 'Katun Drill + Kancing Tanduk', 'hpp' => 'Rp 210.000', 'total' => 'Rp 385.000', 'status' => 'Menunggu Cek', 'skey' => 'cek'],
                        ['kode' => 'REQ-0076', 'tgl' => '19 Agu 2026', 'cust' => 'Maya Sari', 'jenis' => 'Produk Tetap', 'jkey' => 'tetap', 'produk' => 'Wide Leg Trousers', 'bahan' => '—', 'hpp' => 'Rp 185.000', 'total' => 'Rp 320.000', 'status' => 'Tersedia', 'skey' => 'tersedia'],
                        ['kode' => 'REQ-0075', 'tgl' => '14 Agu 2026', 'cust' => 'Dian Pratiwi', 'jenis' => 'Custom', 'jkey' => 'custom', 'produk' => 'Dress Pesta Custom', 'bahan' => 'Sutra Grade A + Payet', 'hpp' => 'Rp 680.000', 'total' => 'Rp 1.200.000', 'status' => 'Siap Ambil', 'skey' => 'siap'],
                        ['kode' => 'REQ-0074', 'tgl' => '10 Agu 2026', 'cust' => 'Budi Santoso', 'jenis' => 'Produk Tetap', 'jkey' => 'tetap', 'produk' => 'Kemeja Linen Oversized', 'bahan' => '—', 'hpp' => 'Rp 180.000', 'total' => 'Rp 289.000', 'status' => 'Selesai', 'skey' => 'selesai'],
                    ] as $row)
                        <tr data-table-row data-jenis="{{ $row['jkey'] }}" data-status-request="{{ $row['skey'] }}" class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $row['kode'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $row['tgl'] }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $row['cust'] }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $row['jkey'] === 'custom' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }} text-[10px] font-bold uppercase border mb-1">{{ $row['jenis'] }}</span>
                                <p class="font-bold text-on-surface leading-tight">{{ $row['produk'] }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant max-w-[220px]">{{ $row['bahan'] }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-on-surface-variant whitespace-nowrap">{{ $row['hpp'] }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">{{ $row['total'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row['skey'] === 'tersedia')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Tersedia</span>
                                @elseif ($row['skey'] === 'produksi')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Ke Produksi</span>
                                @elseif ($row['skey'] === 'siap')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-deep-onyx text-on-primary text-[10px] font-bold uppercase"><span class="material-symbols-outlined fill text-[12px]">local_shipping</span>Siap Ambil</span>
                                @elseif ($row['skey'] === 'selesai')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="material-symbols-outlined fill text-[12px]">task_alt</span>Selesai</span>
                                @elseif ($row['skey'] === 'kosong')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Kosong</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if ($row['skey'] === 'cek')
                                    <button type="button" data-modal-open="modal-cek-request" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Cek Stok</button>
                                @else
                                    <button type="button" onclick="showRalivaToast('Detail request dibuka (demo).', 'visibility')" class="text-xs font-semibold text-on-surface-variant hover:text-gold-accent transition-colors whitespace-nowrap">Detail</button>
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
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada request yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            Alur resmi: Pelanggan → Admin (terima & teruskan) → Gudang cek stok / Produksi hitung modal. Gudang tidak berinteraksi langsung dengan pelanggan.
        </p>
    </section>
</div>

{{-- Modal Cek Stok --}}
<div id="modal-cek-request" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Konfirmasi Ketersediaan</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">REQ-0081 — Blazer Custom • Wool Charcoal (25 unit)</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Status request berhasil diperbarui." class="p-6 space-y-5">
            <div>
                <label class="block raliva-label mb-2">Hasil Pengecekan</label>
                <select class="raliva-select">
                    <option selected>Tersedia — Siap diproses</option>
                    <option>Diteruskan ke Produksi (bahan perlu produksi)</option>
                    <option>Tidak Tersedia — Bahan kosong</option>
                </select>
            </div>
            <div>
                <label for="cek-catatan" class="block raliva-label mb-2">Catatan untuk Admin</label>
                <textarea id="cek-catatan" rows="2" placeholder="Stok bahan cukup untuk 25 unit, estimasi..." class="raliva-textarea"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-3 text-center">
                    <p class="raliva-label">Bahan Dicek</p>
                    <p class="font-title-md text-sm text-on-surface mt-1">Wool Charcoal</p>
                    <p class="text-xs text-secondary font-bold mt-1">42m tersedia</p>
                </div>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-3 text-center">
                    <p class="raliva-label">Modal (HPP)</p>
                    <p class="font-title-md text-sm text-on-surface mt-1">Rp 420.000</p>
                    <p class="text-xs text-gold-accent font-bold mt-1">Total Rp 750.000</p>
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
