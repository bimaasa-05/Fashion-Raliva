@extends('layouts.gudang')

@section('title', 'Barang Masuk')

@section('header-title', 'Barang Masuk')
@section('header-badge', 'Gudang Utama Bandung')
@section('header-subtitle', 'Catat dan kelola penerimaan barang di gudang Anda.')

@section('content')
@php
    $rows = [
        ['BM-0012', 'Produksi', 'PRD-0412', 'Oversized Linen Shirt', 50, '22 Agu 2026 • 08:15', 'Andi Pratama', 'Menunggu Pemeriksaan'],
        ['BM-0011', 'Supplier', 'SUP-2201', 'Silk Scarf', 30, '22 Agu 2026 • 09:02', 'Budi Santoso', 'Selesai'],
        ['BM-0010', 'Produksi', 'PRD-0409', 'Midi Dress Linen', 40, '21 Agu 2026 • 10:44', 'Andi Pratama', 'Diterima'],
        ['BM-0009', 'Gudang Lain', 'PM-0004', 'Hoodie Fleece Premium', 25, '21 Agu 2026 • 13:20', 'Citra Dewi', 'Diterima'],
        ['BM-0008', 'Supplier', 'SUP-2187', 'Leather Belt', 60, '20 Agu 2026 • 11:05', 'Budi Santoso', 'Selesai'],
        ['BM-0007', 'Produksi', 'PRD-0401', 'Basic T-Shirt Cotton', 100, '19 Agu 2026 • 14:32', 'Andi Pratama', 'Selesai'],
        ['BM-0006', 'Supplier', 'SUP-2163', 'Cargo Shorts', 45, '18 Agu 2026 • 09:48', 'Citra Dewi', 'Selesai'],
    ];
    $badgeClass = [
        'Diterima' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'Menunggu Pemeriksaan' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'Selesai' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center gap-gutter mb-6">
            <div class="relative flex-1 min-w-0">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" data-table-search placeholder="Cari nomor transaksi, produk..." class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent transition-colors" />
            </div>
            <div class="flex flex-wrap gap-gutter items-center">
                <select data-table-filter="sumber" aria-label="Filter sumber barang" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                    <option value="semua">Semua Sumber</option>
                    <option value="produksi">Produksi</option>
                    <option value="supplier">Supplier</option>
                    <option value="gudang-lain">Gudang Lain</option>
                </select>
                <select data-table-filter="status" aria-label="Filter status" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                    <option value="semua">Semua Status</option>
                    <option value="diterima">Diterima</option>
                    <option value="menunggu-pemeriksaan">Menunggu Pemeriksaan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <button type="button" data-modal-open="modal-barang-masuk" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span>
                Catat Barang Masuk
            </button>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[980px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Nomor Transaksi</th>
                        <th class="p-4 text-center">Sumber</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Jumlah</th>
                        <th class="p-4 text-center">Tanggal</th>
                        <th class="p-4 text-center">Petugas</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @foreach ($rows as $row)
                        @php
                            $statusKey = str_replace(' ', '-', strtolower($row[7]));
                            $sumberKey = str_replace(' ', '-', strtolower($row[1]));
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-table-row data-sumber="{{ $sumberKey }}" data-status="{{ $statusKey }}">
                            <td class="p-4"><span class="font-bold text-on-surface">{{ $row[0] }}</span><span class="block text-xs text-on-surface-variant mt-0.5">Ref: {{ $row[2] }}</span></td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center gap-1.5 text-on-surface">
                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">{{ ['Produksi' => 'precision_manufacturing', 'Supplier' => 'local_shipping', 'Gudang Lain' => 'warehouse'][$row[1]] }}</span>
                                    {{ $row[1] }}
                                </span>
                            </td>
                            <td class="p-4 text-on-surface">{{ $row[3] }}</td>
                            <td class="p-4 text-center font-bold text-secondary">+{{ $row[4] }}</td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $row[5] }}</td>
                            <td class="p-4 text-center text-on-surface">{{ $row[6] }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" data-modal-open="bm-detail-{{ $loop->iteration }}" title="Lihat Detail" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                    @if ($row[7] === 'Menunggu Pemeriksaan')
                                        <button type="button" onclick="showRalivaToast('Pemeriksaan {{ $row[0] }} dimulai.', 'fact_check')" title="Periksa" class="w-9 h-9 rounded-lg bg-deep-onyx flex items-center justify-center text-on-primary hover:bg-tertiary-container transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">fact_check</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center justify-center py-16 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-on-surface-variant">archive</span>
            </div>
            <p class="font-title-md text-title-md text-on-surface">Belum Ada Barang Masuk</p>
            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Belum terdapat catatan penerimaan barang yang sesuai dengan pencarian atau filter pada gudang ini.</p>
            <button type="button" data-modal-open="modal-barang-masuk" class="mt-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Catat Barang Masuk</button>
        </div>

        <div data-pagination class="flex flex-wrap items-center justify-between gap-4 mt-6">
            <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ count($rows) }} transaksi terakhir • Gudang Utama Bandung</p>
            <div class="flex items-center gap-1">
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-2 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">
                    <span class="material-symbols-outlined text-[18px] align-middle">chevron_left</span>
                </button>
                <button type="button" class="min-w-[36px] h-9 px-3 rounded bg-deep-onyx text-on-primary text-sm font-bold">1</button>
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-3 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">2</button>
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-2 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">
                    <span class="material-symbols-outlined text-[18px] align-middle">chevron_right</span>
                </button>
            </div>
        </div>
    </section>

    @foreach ($rows as $row)
        <div id="bm-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">Detail {{ $row[0] }}</h3>
                        <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1">Sumber: {{ $row[1] }} • Ref {{ $row[2] }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Produk</dt><dd class="text-on-surface text-right">{{ $row[3] }}<span class="block text-xs text-on-surface-variant mt-0.5">Variasi: Assorted</span></dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Jumlah</dt><dd class="text-secondary font-bold">+{{ $row[4] }} unit</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Tanggal &amp; Waktu</dt><dd class="text-on-surface">{{ $row[5] }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Petugas</dt><dd class="text-on-surface">{{ $row[6] }}</dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant">Status</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></dd></div>
                </dl>
                <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
            </div>
        </div>
    @endforeach

    <div id="modal-barang-masuk" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Catat Barang Masuk</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Gudang Aktif: Gudang Utama Bandung</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form data-toast-message="Barang masuk berhasil dicatat." class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Sumber Barang</label>
                        <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Produksi</option>
                            <option>Supplier</option>
                            <option>Gudang Lain</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Nomor Referensi</label>
                        <input type="text" placeholder="cth. PRD-0415 / SUP-2210" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Produk</label>
                        <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Oversized Linen Shirt</option>
                            <option>Straight Fit Pants</option>
                            <option>Relaxed Blazer</option>
                            <option>Midi Dress Linen</option>
                            <option>Basic T-Shirt Cotton</option>
                            <option>Hoodie Fleece Premium</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Variasi</label>
                        <select class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Assorted</option>
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Jumlah</label>
                        <input type="number" min="1" placeholder="cth. 50" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent" />
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Tanggal</label>
                        <input type="date" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Catatan</label>
                    <textarea rows="3" placeholder="Catatan kondisi barang, kelengkapan dokumen, dll. (opsional)" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent resize-none"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
