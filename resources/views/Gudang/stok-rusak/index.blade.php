@extends('layouts.gudang')

@section('title', 'Stok Rusak')

@section('header-title', 'Stok Rusak')
@section('header-badge', 'Gudang Utama Bandung')
@section('header-subtitle', 'Laporkan dan pantau produk rusak di gudang Anda.')

@section('content')
@php
    $rows = [
        ['SR-0009', 'Silk Scarf', 'SYL-004', 2, 'Kemasan rusak', '22 Agu 2026 • 10:05', 'Andi Pratama', 'Dilaporkan'],
        ['SR-0008', 'Basic T-Shirt Cotton', 'KSL-002', 3, 'Produk cacat', '21 Agu 2026 • 14:40', 'Budi Santoso', 'Diverifikasi'],
        ['SR-0007', 'Midi Dress Linen', 'DRS-008', 1, 'Rusak saat penyimpanan', '20 Agu 2026 • 09:25', 'Citra Dewi', 'Selesai'],
        ['SR-0006', 'Relaxed Blazer', 'BLZ-021', 1, 'Cacat jahitan', '19 Agu 2026 • 16:10', 'Andi Pratama', 'Diverifikasi'],
        ['SR-0005', 'Leather Belt', 'IKT-006', 2, 'Rusak saat penyimpanan', '18 Agu 2026 • 11:50', 'Budi Santoso', 'Selesai'],
    ];
    $badgeClass = [
        'Dilaporkan' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'Diverifikasi' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'Selesai' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[360px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter & Pencarian</span>
            </div>
            <div class="flex flex-col lg:flex-row lg:items-center gap-gutter">
            <div class="relative flex-1 min-w-0">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" data-table-search placeholder="Cari nomor, produk, alasan..." class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent transition-colors" />
            </div>
            <select data-table-filter="status" aria-label="Filter status" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent lg:max-w-[200px]">
                <option value="semua">Semua Status</option>
                <option value="dilaporkan">Dilaporkan</option>
                <option value="diverifikasi">Diverifikasi</option>
                <option value="selesai">Selesai</option>
            </select>
            <button type="button" data-modal-open="modal-stok-rusak" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span>
                Catat Stok Rusak
            </button>
        </div>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Nomor</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">SKU</th>
                        <th class="p-4 text-center">Jumlah</th>
                        <th class="p-4 text-left">Alasan</th>
                        <th class="p-4 text-center">Tanggal</th>
                        <th class="p-4 text-center">Petugas</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @foreach ($rows as $row)
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-table-row data-status="{{ strtolower($row[7]) }}">
                            <td class="p-4"><span class="font-bold text-on-surface">{{ $row[0] }}</span></td>
                            <td class="p-4 text-on-surface">{{ $row[1] }}</td>
                            <td class="p-4 text-center text-on-surface-variant">{{ $row[2] }}</td>
                            <td class="p-4 text-center font-bold text-error">{{ $row[3] }}</td>
                            <td class="p-4 text-on-surface">{{ $row[4] }}@if(in_array($row[0], ['SR-0008', 'SR-0006']))<span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gold-accent/10 border border-gold-accent/30 text-[10px] font-bold uppercase text-gold-accent"><span class="material-symbols-outlined text-[12px]">fact_check</span>Dari {{ ['SR-0008' => 'QC-0009', 'SR-0006' => 'QC-0007'][$row[0]] }}</span>@endif</td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $row[5] }}</td>
                            <td class="p-4 text-center text-on-surface whitespace-nowrap">{{ $row[6] }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></td>
                            <td class="p-4 text-center">
                                <button type="button" data-modal-open="sr-detail-{{ $loop->iteration }}" title="Lihat Detail" class="w-9 h-9 mx-auto rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center justify-center py-16 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-on-surface-variant">report</span>
            </div>
            <p class="font-title-md text-title-md text-on-surface">Tidak Ada Laporan Kerusakan</p>
            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Belum terdapat catatan stok rusak yang sesuai dengan pencarian atau filter pada gudang ini.</p>
            <button type="button" data-modal-open="modal-stok-rusak" class="mt-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Catat Stok Rusak</button>
        </div>

        <div data-pagination class="flex flex-wrap items-center justify-between gap-4 mt-6">
            <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ count($rows) }} laporan terakhir • Gudang Utama Bandung</p>
            <div class="flex items-center gap-1">
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-2 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">
                    <span class="material-symbols-outlined text-[18px] align-middle">chevron_left</span>
                </button>
                <button type="button" class="min-w-[36px] h-9 px-3 rounded bg-deep-onyx text-on-primary text-sm font-bold">1</button>
                <button type="button" onclick="showRalivaToast('Halaman demo: hanya 1 halaman data tersedia.', 'info')" class="min-w-[36px] h-9 px-2 rounded border border-muted-border text-sm text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">
                    <span class="material-symbols-outlined text-[18px] align-middle">chevron_right</span>
                </button>
            </div>
        </div>
    </section>

    @foreach ($rows as $row)
        <div id="sr-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">Detail {{ $row[0] }}</h3>
                        <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1">{{ $row[5] }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Produk</dt><dd class="text-on-surface text-right">{{ $row[1] }}<span class="block text-xs text-on-surface-variant mt-0.5">{{ $row[2] }}</span></dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Jumlah Rusak</dt><dd class="text-error font-bold">{{ $row[3] }} unit</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Alasan</dt><dd class="text-on-surface text-right">{{ $row[4] }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Petugas Pelapor</dt><dd class="text-on-surface">{{ $row[6] }}</dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant">Status</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></dd></div>
                </dl>
                @if ($row[7] === 'Dilaporkan')
                    <div class="mt-5 flex gap-gutter">
                        <button type="button" onclick="showRalivaToast('{{ $row[0] }} diverifikasi dan menunggu keputusan Admin Toko.', 'task_alt')" class="flex-1 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Verifikasi</button>
                    </div>
                @endif
                <button type="button" data-modal-close class="w-full mt-5 py-3 {{ $row[7] === 'Dilaporkan' ? '' : 'mt-6' }} border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
            </div>
        </div>
    @endforeach

    <div id="modal-stok-rusak" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Catat Stok Rusak</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Gudang Aktif: Gudang Utama Bandung</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form data-toast-message="Stok rusak berhasil dilaporkan." class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Produk</label>
                        <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Silk Scarf (SYL-004)</option>
                            <option>Basic T-Shirt Cotton (KSL-002)</option>
                            <option>Midi Dress Linen (DRS-008)</option>
                            <option>Relaxed Blazer (BLZ-021)</option>
                            <option>Leather Belt (IKT-006)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Jumlah</label>
                        <input type="number" min="1" placeholder="cth. 2" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent" />
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Alasan</label>
                        <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Kemasan rusak</option>
                            <option>Produk cacat</option>
                            <option>Cacat jahitan</option>
                            <option>Rusak saat penyimpanan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Tanggal</label>
                        <input type="date" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Catatan</label>
                    <textarea rows="3" placeholder="Detail kerusakan, lokasi penemuan, foto pendukung (opsional)" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent resize-none"></textarea>
                </div>
                <div class="flex items-start gap-3 p-4 border border-error/30 bg-error-container/60 rounded-lg">
                    <span class="material-symbols-outlined text-error mt-0.5 text-[20px]">info</span>
                    <p class="font-body-md text-xs text-on-error-container">Pelaporan stok rusak akan mengurangi stok tersedia setelah diverifikasi oleh Admin Toko.</p>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Laporkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
