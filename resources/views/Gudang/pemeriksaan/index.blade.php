@extends('layouts.gudang')

@section('title', 'Pemeriksaan Stok')

@section('header-title', 'Pemeriksaan Stok')
@section('header-badge', 'Gudang Utama Bandung')
@section('header-subtitle', 'Bandingkan stok fisik dengan catatan sistem secara berkala.')

@section('content')
@php
    $rows = [
        ['PS-0012', '22 Agu 2026 • 13:40', 'Silk Scarf', 'SYL-004', 7, 5, 'Andi Pratama', 'Selisih'],
        ['PS-0011', '22 Agu 2026 • 11:05', 'Basic T-Shirt Cotton', 'KSL-002', 142, 142, 'Citra Dewi', 'Sesuai'],
        ['PS-0010', '21 Agu 2026 • 15:20', 'Wide Leg Trousers', 'CEL-022', 4, 4, 'Budi Santoso', 'Selesai'],
        ['PS-0009', '20 Agu 2026 • 09:55', 'Leather Belt', 'IKT-006', 90, 88, 'Andi Pratama', 'Selisih'],
        ['PS-0008', '19 Agu 2026 • 14:10', 'Midi Dress Linen', 'DRS-008', 58, 58, 'Budi Santoso', 'Sesuai'],
        ['PS-0007', '18 Agu 2026 • 10:30', 'Hoodie Fleece Premium', 'HDD-011', 65, 63, 'Citra Dewi', 'Selesai'],
    ];
    $badgeClass = [
        'Sesuai' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'Selisih' => 'bg-error/10 text-error border-error/20',
        'Selesai' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-20 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[400px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="flex items-start gap-3 p-4 border border-error/30 bg-error-container/60 rounded-lg">
        <span class="material-symbols-outlined text-error mt-0.5">warning</span>
        <div>
            <p class="font-body-md text-sm font-bold text-on-error-container">Terdapat selisih stok yang perlu ditindaklanjuti.</p>
            <p class="text-on-error-container/90 text-sm mt-0.5">2 pemeriksaan menemukan selisih stok. Segera verifikasi ulang stok fisik atau laporkan ke Admin Toko.</p>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center gap-gutter mb-6">
            <div class="relative flex-1 min-w-0">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" data-table-search placeholder="Cari nomor atau produk..." class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent transition-colors" />
            </div>
            <select data-table-filter="status" aria-label="Filter status" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent lg:max-w-[200px]">
                <option value="semua">Semua Status</option>
                <option value="sesuai">Sesuai</option>
                <option value="selisih">Selisih</option>
                <option value="selesai">Selesai</option>
            </select>
            <button type="button" data-modal-open="modal-pemeriksaan" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span>
                Pemeriksaan Baru
            </button>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[980px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Nomor Pemeriksaan</th>
                        <th class="p-4 text-center">Tanggal</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-center">Stok Sistem</th>
                        <th class="p-4 text-center">Stok Fisik</th>
                        <th class="p-4 text-center">Selisih</th>
                        <th class="p-4 text-center">Petugas</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @foreach ($rows as $row)
                        @php
                            $selisih = $row[5] - $row[4];
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-table-row data-status="{{ strtolower($row[7]) }}">
                            <td class="p-4"><span class="font-bold text-on-surface">{{ $row[0] }}</span></td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $row[1] }}</td>
                            <td class="p-4"><span class="text-on-surface">{{ $row[2] }}</span><span class="block text-xs text-on-surface-variant mt-0.5">{{ $row[3] }}</span></td>
                            <td class="p-4 text-center text-on-surface">{{ $row[4] }}</td>
                            <td class="p-4 text-center font-bold text-on-surface">{{ $row[5] }}</td>
                            <td class="p-4 text-center font-bold {{ $selisih !== 0 ? 'text-error' : 'text-on-surface-variant' }}">{{ $selisih > 0 ? '+' . $selisih : $selisih }}</td>
                            <td class="p-4 text-center text-on-surface whitespace-nowrap">{{ $row[6] }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" data-modal-open="ps-detail-{{ $loop->iteration }}" title="Lihat Detail" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                    @if ($row[7] === 'Selisih')
                                        <button type="button" onclick="showRalivaToast('Laporan selisih {{ $row[0] }} diteruskan ke Admin Toko.', 'send')" title="Tindak Lanjuti" class="w-9 h-9 rounded-lg bg-deep-onyx flex items-center justify-center text-on-primary hover:bg-tertiary-container transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">send</span>
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
                <span class="material-symbols-outlined text-on-surface-variant">fact_check</span>
            </div>
            <p class="font-title-md text-title-md text-on-surface">Belum Ada Pemeriksaan</p>
            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Belum terdapat catatan pemeriksaan stok yang sesuai dengan pencarian atau filter pada gudang ini.</p>
            <button type="button" data-modal-open="modal-pemeriksaan" class="mt-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Mulai Pemeriksaan</button>
        </div>

        <div data-pagination class="flex flex-wrap items-center justify-between gap-4 mt-6">
            <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ count($rows) }} pemeriksaan terakhir • Gudang Utama Bandung</p>
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
        @php
            $selisih = $row[5] - $row[4];
        @endphp
        <div id="ps-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface">Detail {{ $row[0] }}</h3>
                        <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1">{{ $row[1] }} • {{ $row[6] }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-gutter mb-6">
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-3 text-center">
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Sistem</p>
                        <p class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $row[4] }}</p>
                    </div>
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-3 text-center">
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Fisik</p>
                        <p class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $row[5] }}</p>
                    </div>
                    <div class="rounded-lg p-3 text-center border {{ $selisih !== 0 ? 'bg-error/10 border-error/20' : 'bg-surface-container-low border-muted-border' }}">
                        <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Selisih</p>
                        <p class="font-headline-lg-mobile text-headline-lg-mobile {{ $selisih !== 0 ? 'text-error' : 'text-on-surface' }}">{{ $selisih > 0 ? '+' . $selisih : $selisih }}</p>
                    </div>
                </div>
                @if ($selisih !== 0)
                    <div class="flex items-start gap-3 p-4 mb-6 border border-error/30 bg-error-container/60 rounded-lg">
                        <span class="material-symbols-outlined text-error mt-0.5 text-[20px]">report_problem</span>
                        <p class="font-body-md text-sm text-on-error-container">Terdapat selisih stok yang perlu ditindaklanjuti. Periksa kembali rak penyimpanan dan laporan kerusakan terkait.</p>
                    </div>
                @endif
                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Produk</dt><dd class="text-on-surface text-right">{{ $row[2] }}<span class="block text-xs text-on-surface-variant mt-0.5">{{ $row[3] }}</span></dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Metode</dt><dd class="text-on-surface">Hitung Fisik Manual</dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant">Status</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></dd></div>
                </dl>
                <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
            </div>
        </div>
    @endforeach

    <div id="modal-pemeriksaan" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Pemeriksaan Baru</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Gudang Aktif: Gudang Utama Bandung</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form data-toast-message="Pemeriksaan baru berhasil dibuat." class="p-6 space-y-5">
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Produk</label>
                    <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                        <option>Oversized Linen Shirt (KEM-001)</option>
                        <option>Relaxed Blazer (BLZ-021)</option>
                        <option>Midi Dress Linen (DRS-008)</option>
                        <option>Pleated Skirt (RKT-005)</option>
                        <option>Silk Scarf (SYL-004)</option>
                    </select>
                    <p class="text-xs text-on-surface-variant mt-1.5">Stok sistem akan dicocokkan otomatis saat pemeriksaan disimpan.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Tanggal Pemeriksaan</label>
                        <input type="date" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Jumlah Stok Fisik</label>
                        <input type="number" min="0" placeholder="cth. 24" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent" />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Catatan</label>
                    <textarea rows="3" placeholder="Kondisi barang, lokasi rak, temuan lain (opsional)" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent resize-none"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Pemeriksaan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
