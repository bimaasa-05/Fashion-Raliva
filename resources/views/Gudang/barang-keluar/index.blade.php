@extends('layouts.gudang')

@section('title', 'Barang Keluar')

@section('header-title', 'Barang Keluar')
@section('header-badge', 'Gudang Utama Bandung')
@section('header-subtitle', 'Catat pengeluaran barang untuk pesanan dan kebutuhan internal.')

@section('content')
@php
    $rows = [
        ['BK-0008', 'Pemenuhan Pesanan', '#RLV-2085', 'Knit Cardigan Rajut', 20, 'Andi Pratama', '22 Agu 2026 • 10:15', 'Diproses'],
        ['BK-0007', 'Gudang Cabang', 'PM-0004', 'Pleated Skirt', 30, 'Budi Santoso', '22 Agu 2026 • 11:20', 'Dikirim'],
        ['BK-0006', 'Pemenuhan Pesanan', '#RLV-2081', 'Midi Dress Linen', 8, 'Citra Dewi', '21 Agu 2026 • 15:42', 'Selesai'],
        ['BK-0005', 'Produksi', 'PRD-0410', 'Relaxed Blazer', 12, 'Andi Pratama', '21 Agu 2026 • 09:30', 'Selesai'],
        ['BK-0004', 'Pemenuhan Pesanan', '#RLV-2078', 'Hoodie Fleece Premium', 15, 'Budi Santoso', '20 Agu 2026 • 13:08', 'Selesai'],
        ['BK-0003', 'Gudang Cabang', 'PM-0003', 'Leather Belt', 20, 'Citra Dewi', '19 Agu 2026 • 16:55', 'Dikirim'],
    ];
    $badgeClass = [
        'Diproses' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'Dikirim' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'Selesai' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
    ];
    $tujuanIcon = ['Pemenuhan Pesanan' => 'shopping_bag', 'Produksi' => 'precision_manufacturing', 'Gudang Cabang' => 'warehouse'];
@endphp

<div id="drawer-overlay" class="fixed inset-0 bg-black/50 z-[70] hidden opacity-0 transition-opacity duration-300" data-drawer-close></div>

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center gap-gutter mb-6">
            <div class="relative flex-1 min-w-0">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" data-table-search placeholder="Cari nomor, tujuan, pesanan..." class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent transition-colors" />
            </div>
            <div class="flex flex-wrap gap-gutter items-center">
                <select data-table-filter="tujuan" aria-label="Filter tujuan" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                    <option value="semua">Semua Tujuan</option>
                    <option value="pemenuhan-pesanan">Pemenuhan Pesanan</option>
                    <option value="produksi">Produksi</option>
                    <option value="gudang-cabang">Gudang Cabang</option>
                </select>
                <select data-table-filter="status" aria-label="Filter status" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                    <option value="semua">Semua Status</option>
                    <option value="diproses">Diproses</option>
                    <option value="dikirim">Dikirim</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <button type="button" data-modal-open="modal-barang-keluar" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span>
                Catat Barang Keluar
            </button>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[980px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Nomor</th>
                        <th class="p-4 text-center">Tujuan</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Jumlah</th>
                        <th class="p-4 text-center">Referensi Pesanan</th>
                        <th class="p-4 text-center">Petugas</th>
                        <th class="p-4 text-center">Tanggal</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @foreach ($rows as $row)
                        @php
                            $tujuanKey = str_replace(' ', '-', strtolower($row[1]));
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-table-row data-tujuan="{{ $tujuanKey }}" data-status="{{ strtolower($row[7]) }}">
                            <td class="p-4"><span class="font-bold text-on-surface">{{ $row[0] }}</span><span class="block text-xs text-on-surface-variant mt-0.5">{{ $row[6] }}</span></td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center gap-1.5 text-on-surface whitespace-nowrap">
                                    <span class="material-symbols-outlined text-[16px] text-on-surface-variant">{{ $tujuanIcon[$row[1]] }}</span>
                                    {{ $row[1] }}
                                </span>
                            </td>
                            <td class="p-4 text-on-surface">{{ $row[3] }}</td>
                            <td class="p-4 text-center font-bold text-error">-{{ $row[4] }}</td>
                            <td class="p-4 text-center"><span class="{{ str_starts_with($row[2], '#') ? 'font-bold text-gold-accent' : 'text-on-surface-variant' }}">{{ $row[2] }}</span></td>
                            <td class="p-4 text-center text-on-surface whitespace-nowrap">{{ $row[5] }}</td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap hidden xl:table-cell">{{ explode(' • ', $row[6])[0] }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></td>
                            <td class="p-4 text-center">
                                <button type="button" data-drawer-open="bk-drawer-{{ $loop->iteration }}" title="Lihat Detail" class="w-9 h-9 mx-auto rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center justify-center py-16 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-on-surface-variant">unarchive</span>
            </div>
            <p class="font-title-md text-title-md text-on-surface">Belum Ada Barang Keluar</p>
            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Belum terdapat catatan pengeluaran barang yang sesuai dengan pencarian atau filter pada gudang ini.</p>
            <button type="button" data-modal-open="modal-barang-keluar" class="mt-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Catat Barang Keluar</button>
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
        <div id="bk-drawer-{{ $loop->iteration }}" data-drawer-panel class="fixed inset-y-0 right-0 z-[80] w-full max-w-md bg-surface-container-lowest border-l border-muted-border shadow-xl translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border z-10">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface">Detail {{ $row[0] }}</h3>
                    <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1">{{ $row[6] }}</p>
                </div>
                <button type="button" data-drawer-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="px-6 py-6 space-y-6">
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gold-accent text-[20px]">{{ $tujuanIcon[$row[1]] }}</span>
                    </div>
                    <div>
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Tujuan Pengeluaran</p>
                        <p class="font-title-md text-base text-on-surface leading-snug">{{ $row[1] }}</p>
                        <p class="text-on-surface-variant text-xs mt-0.5">Referensi: <span class="{{ str_starts_with($row[2], '#') ? 'font-bold text-gold-accent' : 'font-bold text-on-surface' }}">{{ $row[2] }}</span></p>
                    </div>
                </div>

                <div>
                    <p class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-3">Rincian Barang</p>
                    <div class="border border-muted-border rounded-lg divide-y divide-muted-border">
                        <div class="flex items-center justify-between gap-4 p-4">
                            <div>
                                <p class="text-on-surface font-bold">{{ $row[3] }}</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">Variasi: Assorted</p>
                            </div>
                            <span class="font-bold text-error">-{{ $row[4] }} unit</span>
                        </div>
                    </div>
                </div>

                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Petugas</dt><dd class="text-on-surface">{{ $row[5] }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Waktu Pencatatan</dt><dd class="text-on-surface">{{ $row[6] }}</dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant">Status</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></dd></div>
                </dl>

                <div>
                    <p class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-4">Alur Pengeluaran</p>
                    <ol class="relative border-l border-muted-border ml-3 space-y-6">
                        <li class="pl-6 relative">
                            <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-secondary border-2 border-surface-container-lowest"></span>
                            <p class="font-body-md text-sm text-on-surface font-bold">Dicatat di gudang</p>
                            <p class="text-on-surface-variant text-xs mt-0.5">{{ $row[5] }} • {{ $row[6] }}</p>
                        </li>
                        <li class="pl-6 relative">
                            <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ in_array($row[7], ['Dikirim', 'Selesai']) ? 'bg-secondary' : 'bg-outline-variant' }} border-2 border-surface-container-lowest"></span>
                            <p class="font-body-md text-sm {{ in_array($row[7], ['Dikirim', 'Selesai']) ? 'text-on-surface font-bold' : 'text-on-surface-variant' }}">Diserahkan ke kurir/gudang tujuan</p>
                        </li>
                        <li class="pl-6 relative">
                            <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $row[7] === 'Selesai' ? 'bg-secondary' : 'bg-outline-variant' }} border-2 border-surface-container-lowest"></span>
                            <p class="font-body-md text-sm {{ $row[7] === 'Selesai' ? 'text-on-surface font-bold' : 'text-on-surface-variant' }}">Diterima tujuan</p>
                        </li>
                    </ol>
                </div>

                <div class="flex gap-gutter pt-2">
                    <button type="button" onclick="showRalivaToast('Detail {{ $row[0] }} siap dicetak.', 'print')" class="flex-1 text-center py-3 border border-muted-border rounded-lg font-label-sm text-[11px] text-gold-accent uppercase tracking-widest hover:bg-gold-accent/10 hover:border-gold-accent/40 transition-colors">Cetak</button>
                    <button type="button" data-drawer-close class="flex-1 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
                </div>
            </div>
        </div>
    @endforeach

    <div id="modal-barang-keluar" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Catat Barang Keluar</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Gudang Aktif: Gudang Utama Bandung</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form data-toast-message="Barang keluar berhasil dicatat." class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Tujuan</label>
                        <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Pemenuhan Pesanan</option>
                            <option>Produksi</option>
                            <option>Gudang Cabang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Referensi Pesanan</label>
                        <input type="text" placeholder="cth. #RLV-2086 / PRD-0412" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Produk</label>
                        <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Knit Cardigan Rajut</option>
                            <option>Midi Dress Linen</option>
                            <option>Basic T-Shirt Cotton</option>
                            <option>Hoodie Fleece Premium</option>
                            <option>Leather Belt</option>
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
                        <input type="number" min="1" placeholder="cth. 20" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent" />
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Tanggal</label>
                        <input type="date" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Catatan</label>
                    <textarea rows="3" placeholder="Catatan tambahan (opsional)" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent resize-none"></textarea>
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
