@extends('layouts.gudang')

@section('title', 'Pemindahan Stok')

@section('header-title', 'Pemindahan Stok')
@section('header-badge', 'Gudang Utama Bandung')
@section('header-subtitle', 'Pindahkan stok antar gudang dalam toko yang sama.')

@section('content')
@php
    $rows = [
        ['PM-0006', 'Gudang Utama Bandung', 'Gudang Cabang Cimahi', 'Maxi Dress Floral', 24, '22 Agu 2026 • 09:10', 'Andi Pratama', 'Draft'],
        ['PM-0005', 'Gudang Utama Bandung', 'Gudang Cabang Cimahi', 'Cargo Shorts', 18, '21 Agu 2026 • 14:25', 'Budi Santoso', 'Diproses'],
        ['PM-0004', 'Gudang Utama Bandung', 'Gudang Cabang Cimahi', 'Pleated Skirt', 30, '21 Agu 2026 • 11:20', 'Andi Pratama', 'Dikirim'],
        ['PM-0003', 'Gudang Utama Bandung', 'Gudang Cabang Cimahi', 'Leather Belt', 20, '19 Agu 2026 • 16:55', 'Citra Dewi', 'Diterima'],
        ['PM-0002', 'Gudang Utama Bandung', 'Gudang Cabang Cimahi', 'Midi Dress Linen', 12, '18 Agu 2026 • 10:40', 'Andi Pratama', 'Diterima'],
        ['PM-0001', 'Gudang Utama Bandung', 'Gudang Cabang Cimahi', 'Relaxed Blazer', 8, '17 Agu 2026 • 13:15', 'Budi Santoso', 'Dibatalkan'],
    ];
    $badgeClass = [
        'Draft' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
        'Diproses' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
        'Dikirim' => 'bg-secondary-container/20 text-secondary border-secondary/20',
        'Diterima' => 'bg-secondary text-on-secondary border-secondary',
        'Dibatalkan' => 'bg-error/10 text-error border-error/20',
    ];
@endphp

<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-[400px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap" data-table-scope>
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center gap-gutter mb-6">
            <div class="relative flex-1 min-w-0">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" data-table-search placeholder="Cari nomor atau produk..." class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent transition-colors" />
            </div>
            <select data-table-filter="status" aria-label="Filter status" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent lg:max-w-[220px]">
                <option value="semua">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="diproses">Diproses</option>
                <option value="dikirim">Dikirim</option>
                <option value="diterima">Diterima</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
            <button type="button" data-modal-open="modal-pemindahan" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span>
                Buat Pemindahan
            </button>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[980px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Nomor</th>
                        <th class="p-4 text-left">Rute Gudang</th>
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
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-table-row data-status="{{ strtolower($row[7]) }}">
                            <td class="p-4"><span class="font-bold text-on-surface">{{ $row[0] }}</span></td>
                            <td class="p-4">
                                <div class="flex items-center gap-2 text-sm whitespace-nowrap">
                                    <span class="text-on-surface">{{ $row[1] }}</span>
                                    <span class="material-symbols-outlined text-[16px] text-gold-accent">east</span>
                                    <span class="text-on-surface">{{ $row[2] }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-on-surface">{{ $row[3] }}</td>
                            <td class="p-4 text-center font-bold text-gold-accent">{{ $row[4] }}</td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $row[5] }}</td>
                            <td class="p-4 text-center text-on-surface whitespace-nowrap">{{ $row[6] }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" data-modal-open="pm-detail-{{ $loop->iteration }}" title="Lihat Detail" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant hover:text-gold-accent hover:border-gold-accent transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                    @if ($row[7] === 'Draft')
                                        <button type="button" onclick="showRalivaToast('{{ $row[0] }} diproses dan disiapkan untuk dikirim.', 'local_shipping')" title="Proses" class="w-9 h-9 rounded-lg bg-deep-onyx flex items-center justify-center text-on-primary hover:bg-tertiary-container transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">play_arrow</span>
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
                <span class="material-symbols-outlined text-on-surface-variant">swap_horiz</span>
            </div>
            <p class="font-title-md text-title-md text-on-surface">Belum Ada Pemindahan</p>
            <p class="text-on-surface-variant font-body-md text-sm max-w-sm">Belum terdapat histori pemindahan stok yang sesuai dengan pencarian atau filter pada gudang ini.</p>
            <button type="button" data-modal-open="modal-pemindahan" class="mt-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Buat Pemindahan</button>
        </div>

        <div data-pagination class="flex flex-wrap items-center justify-between gap-4 mt-6">
            <p class="font-label-sm text-xs text-on-surface-variant">Menampilkan {{ count($rows) }} pemindahan terakhir • Gudang Utama Bandung</p>
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
        <div id="pm-detail-{{ $loop->iteration }}" data-modal class="fixed inset-0 z-[70] hidden">
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
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between gap-3 text-sm">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="material-symbols-outlined text-[18px] text-secondary shrink-0">warehouse</span>
                            <div class="min-w-0">
                                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Asal</p>
                                <p class="text-on-surface truncate">{{ $row[1] }}</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-gold-accent shrink-0">east</span>
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="material-symbols-outlined text-[18px] text-gold-accent shrink-0">warehouse</span>
                            <div class="min-w-0">
                                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Tujuan</p>
                                <p class="text-on-surface truncate">{{ $row[2] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <dl class="space-y-4 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Produk</dt><dd class="text-on-surface text-right">{{ $row[3] }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Jumlah</dt><dd class="text-gold-accent font-bold">{{ $row[4] }} unit</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Petugas</dt><dd class="text-on-surface">{{ $row[6] }}</dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant">Status</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full {{ $badgeClass[$row[7]] }} text-[10px] font-bold uppercase border">{{ $row[7] }}</span></dd></div>
                </dl>
                <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
            </div>
        </div>
    @endforeach

    <div id="modal-pemindahan" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Buat Pemindahan</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Pindahkan stok ke gudang lain dalam toko yang sama.</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form data-toast-message="Permintaan pemindahan berhasil dibuat." class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Gudang Asal</label>
                        <input type="text" value="Gudang Utama Bandung" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                        <p class="text-xs text-on-surface-variant mt-1.5">Sesuai penugasan akun Anda.</p>
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Gudang Tujuan</label>
                        <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Gudang Cabang Cimahi</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Produk</label>
                        <select required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Maxi Dress Floral</option>
                            <option>Cargo Shorts</option>
                            <option>Pleated Skirt</option>
                            <option>Midi Dress Linen</option>
                            <option>Basic T-Shirt Cotton</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Variasi</label>
                        <select class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                            <option>Assorted</option>
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Jumlah</label>
                    <input type="number" min="1" placeholder="cth. 24" required class="w-full md:w-56 bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent" />
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Catatan</label>
                    <textarea rows="3" placeholder="Alasan pemindahan, kondisi barang, dll. (opsional)" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent resize-none"></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="submit" name="action" value="draft" onclick="this.form.setAttribute('data-toast-message','Pemindahan disimpan sebagai draft.')" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Simpan Draft</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Ajukan Pemindahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
