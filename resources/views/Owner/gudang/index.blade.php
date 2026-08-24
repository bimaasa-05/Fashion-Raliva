@extends('layouts.owner')

@section('title', 'Gudang')

@section('header-title', 'Gudang')
@section('header-badge', '2 Gudang Aktif')
@section('header-subtitle', 'Kelola data gudang dan pantau stok di setiap lokasi.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Gudang</span>
            <span class="raliva-figure text-[26px] text-on-surface">2</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">warehouse</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Unit Tersimpan</span>
            <span class="raliva-figure text-[26px] text-on-surface">8.426</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">inventory_2</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-3 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kapasitas Terpakai</span>
            <span class="raliva-figure text-[26px] text-secondary"><span>63</span>%</span>
            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="63"></div>
            </div>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">equalizer</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Menipis</span>
            <span class="raliva-figure text-[26px] text-error">9</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">warning</span>
        </div>
    </section>

    {{-- Daftar Gudang --}}
    <section>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 data-reveal class="font-title-md text-title-md text-on-surface premium-heading">Daftar Gudang</h2>
            <button data-reveal type="button" data-modal-open="modal-tambah-gudang" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium w-full sm:w-auto shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span>Tambah Gudang
            </button>
        </div>

        <div data-reveal-group class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
            @foreach ([
                ['nama' => 'Gudang Utama Bandung', 'alamat' => 'Jl. Soekarno Hatta No. 450, Cimahi, Jawa Barat', 'status' => 'Aktif', 'kapasitas' => 72, 'unit' => 6120, 'produk' => 148, 'menipis' => 9, 'petugas' => ['Andi Pratama', 'Rudi Hartono']],
                ['nama' => 'Gudang Cabang Jakarta', 'alamat' => 'Kawasan Pergudangan PIK, Penjaringan, Jakarta Utara', 'status' => 'Aktif', 'kapasitas' => 48, 'unit' => 2306, 'produk' => 96, 'menipis' => 0, 'petugas' => ['Sinta Dewi']],
            ] as $g)
                <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col gap-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-deep-onyx text-on-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[24px]">warehouse</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-title-md text-title-md text-on-surface leading-tight">{{ $g['nama'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">{{ $g['alamat'] }}</p>
                            </div>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[9px] font-bold uppercase border border-secondary/20">{{ $g['status'] }}</span>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-label-sm text-[10px] uppercase tracking-wider text-on-surface-variant">Kapasitas Terpakai</span>
                            <span class="font-label-sm text-[11px] font-bold text-on-surface">{{ $g['kapasitas'] }}%</span>
                        </div>
                        <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                            <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="{{ $g['kapasitas'] }}"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-gutter pt-4 border-t border-muted-border text-center">
                        <div>
                            <p class="font-title-md text-base text-on-surface">{{ number_format($g['unit'], 0, ',', '.') }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Unit Stok</p>
                        </div>
                        <div class="border-x border-muted-border">
                            <p class="font-title-md text-base text-on-surface">{{ $g['produk'] }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Varian Produk</p>
                        </div>
                        <div>
                            <p class="font-title-md text-base {{ $g['menipis'] > 0 ? 'text-error' : 'text-secondary' }}">{{ $g['menipis'] }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Stok Menipis</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        @foreach ($g['petugas'] as $petugas)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant font-label-sm text-[11px]">
                                <span class="material-symbols-outlined text-[14px] text-secondary">person</span>{{ $petugas }}
                            </span>
                        @endforeach
                    </div>

                    <div class="flex gap-gutter mt-auto">
                        <button type="button" onclick="showRalivaToast('Detail gudang dibuka (demo).', 'visibility')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Lihat Detail</button>
                        <button type="button" onclick="showRalivaToast('Form edit gudang dibuka (demo).', 'edit')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Edit Data</button>
                    </div>
                </article>
            @endforeach

            {{-- Kartu Tambah --}}
            <article data-reveal class="border-2 border-dashed border-outline-variant rounded-lg flex flex-col items-center justify-center gap-4 min-h-[280px] cursor-pointer hover:border-gold-accent hover:bg-surface-container-low transition-colors group" onclick="document.querySelector('[data-modal-open=&quot;modal-tambah-gudang&quot;]')?.click()">
                <div class="w-14 h-14 rounded-full bg-surface-container-high group-hover:bg-gold-accent/10 flex items-center justify-center transition-colors">
                    <span class="material-symbols-outlined text-[28px] text-on-surface-variant group-hover:text-gold-accent transition-colors">add_business</span>
                </div>
                <div class="text-center px-6">
                    <p class="font-title-md text-title-md text-on-surface">Tambah Lokasi Baru</p>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Satu toko dapat memiliki lebih dari satu gudang.</p>
                </div>
            </article>
        </div>
    </section>

    {{-- Ringkasan Stok Antar Gudang --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Ringkasan Stok Kritis</h2>
            <div class="relative w-full sm:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari produk..." data-table-search class="raliva-search" />
            </div>
        </div>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[760px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Gudang</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Sisa Stok</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Terjual / Minggu</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['produk' => 'Silk Scarf Monogram', 'sku' => 'RLV-SSM-002', 'gudang' => 'Utama Bandung', 'sisa' => 0, 'terjual' => '38 pcs'],
                        ['produk' => 'Blazer Wool Premium', 'sku' => 'RLV-BWP-014', 'gudang' => 'Utama Bandung', 'sisa' => 8, 'terjual' => '21 pcs'],
                        ['produk' => 'Jaket Denim Vintage Wash', 'sku' => 'RLV-JDV-005', 'gudang' => 'Cabang Jakarta', 'sisa' => 12, 'terjual' => '17 pcs'],
                        ['produk' => 'Knit Cardigan Rajut', 'sku' => 'RLV-KCR-021', 'gudang' => 'Utama Bandung', 'sisa' => 19, 'terjual' => '14 pcs'],
                    ] as $row)
                        <tr data-table-row class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $row['produk'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $row['sku'] }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $row['gudang'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold {{ $row['sisa'] === 0 ? 'text-error' : 'text-gold-accent' }}">{{ number_format($row['sisa'], 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-on-surface">{{ $row['terjual'] }}</td>
                            <td class="py-3.5 px-4 text-right"><button type="button" data-modal-open="modal-restock" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Ajukan Restock</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

{{-- Modal Tambah Gudang --}}
<div id="modal-tambah-gudang" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Gudang</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Daftarkan lokasi penyimpanan baru untuk toko ini.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Gudang baru berhasil ditambahkan." class="p-6 space-y-5">
            <div>
                <label for="gd-nama" class="block raliva-label mb-2">Nama Gudang</label>
                <input id="gd-nama" type="text" placeholder="cth. Gudang Cabang Surabaya" required class="raliva-input" />
            </div>
            <div>
                <label for="gd-alamat" class="block raliva-label mb-2">Alamat Lengkap</label>
                <textarea id="gd-alamat" rows="3" required placeholder="Alamat fisik lengkap gudang..." class="raliva-textarea"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="gd-kapasitas" class="block raliva-label mb-2">Kapasitas Rak (unit)</label>
                    <input id="gd-kapasitas" type="number" value="5000" min="1" required class="raliva-input" />
                </div>
                <div>
                    <label for="gd-pic" class="block raliva-label mb-2">Petugas Bertugas</label>
                    <select id="gd-pic" class="raliva-select">
                        <option>Andi Pratama</option>
                        <option>Rudi Hartono</option>
                        <option>Sinta Dewi</option>
                        <option>— Belum ditugaskan —</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Simpan Gudang</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Ajukan Restock --}}
<div id="modal-restock" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Ajukan Produksi Restock</h3>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Permintaan restock berhasil dibuat di modul Produksi." class="p-6 space-y-5">
            <div>
                <label for="rs-jumlah" class="block raliva-label mb-2">Jumlah Restock</label>
                <input id="rs-jumlah" type="number" value="60" min="1" required class="raliva-input" />
            </div>
            <div>
                <label for="rs-target" class="block raliva-label mb-2">Target Selesai</label>
                <input id="rs-target" type="date" value="2026-09-05" required class="raliva-input" />
            </div>
            <p class="text-xs text-on-surface-variant flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
                Permintaan akan otomatis muncul sebagai PRQ baru pada halaman Produksi.
            </p>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Buat Permintaan</button>
            </div>
        </form>
    </div>
</div>
@endsection
