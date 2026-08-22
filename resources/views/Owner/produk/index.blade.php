@extends('layouts.owner')

@section('title', 'Data Produk')

@section('header-title', 'Data Produk')
@section('header-badge', '142 / 200 Slot')
@section('header-subtitle', 'Kelola produk, harga, variasi, dan stok toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Produk</span>
            <span class="raliva-figure text-[26px] text-on-surface">148</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">checkroom</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-3 relative overflow-hidden card-premium col-span-2">
            <div class="flex items-center justify-between gap-3">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Slot Paket Growth</span>
                <a href="{{ route('owner.paket-slot') }}" class="text-xs font-semibold text-gold-accent hover:underline">Upgrade Paket</a>
            </div>
            <span class="raliva-figure text-[26px] text-secondary"><span>142</span> / 200</span>
            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="71"></div>
            </div>
            <span class="font-label-sm text-[11px] text-on-surface-variant">58 slot tersedia — aktif s.d. 12 Feb 2027</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Produk Nonaktif</span>
            <span class="raliva-figure text-[26px] text-error">6</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">visibility_off</span>
        </div>
    </section>

    {{-- Toolbar & Tabel --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari nama produk atau SKU..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="kategori" class="raliva-select">
                    <option value="">Semua Kategori</option>
                    <option value="outerwear">Outerwear</option>
                    <option value="atasan">Atasan</option>
                    <option value="bawahan">Bawahan</option>
                    <option value="aksesoris">Aksesoris</option>
                </select>
                <select data-table-filter="status-produk" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                    <option value="habis">Stok Habis</option>
                </select>
            </div>
            <button type="button" data-drawer-open="drawer-form-produk" class="shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium w-full lg:w-auto">
                <span class="material-symbols-outlined text-[18px]">add</span>Tambah Produk
            </button>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kategori</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Harga</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Stok</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Terjual</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['nama' => 'Trench Coat Signature', 'sku' => 'RLV-TCS-001', 'kat' => 'outerwear', 'katLabel' => 'Outerwear', 'harga' => 'Rp 1.290.000', 'stok' => 42, 'variasi' => 'S, M, L, XL • Hitam, Krem, Navy', 'terjual' => '128', 'status' => 'aktif'],
                        ['nama' => 'Blazer Wool Premium', 'sku' => 'RLV-BWP-014', 'kat' => 'outerwear', 'katLabel' => 'Outerwear', 'harga' => 'Rp 949.000', 'stok' => 8, 'variasi' => 'M, L, XL • Charcoal, Camel', 'terjual' => '96', 'status' => 'aktif'],
                        ['nama' => 'Kemeja Linen Oversized', 'sku' => 'RLV-KLO-032', 'kat' => 'atasan', 'katLabel' => 'Atasan', 'harga' => 'Rp 389.000', 'stok' => 64, 'variasi' => 'All Size • Putih, Sage, Terracotta', 'terjual' => '212', 'status' => 'aktif'],
                        ['nama' => 'Wide Leg Trousers', 'sku' => 'RLV-WLT-008', 'kat' => 'bawahan', 'katLabel' => 'Bawahan', 'harga' => 'Rp 459.000', 'stok' => 27, 'variasi' => '28–34 • Hitam, Cokelat', 'terjual' => '87', 'status' => 'aktif'],
                        ['nama' => 'Silk Scarf Monogram', 'sku' => 'RLV-SSM-002', 'kat' => 'aksesoris', 'katLabel' => 'Aksesoris', 'harga' => 'Rp 259.000', 'stok' => 0, 'variasi' => 'One Size • Gold, Ivory', 'terjual' => '154', 'status' => 'habis'],
                        ['nama' => 'Knit Cardigan Rajut', 'sku' => 'RLV-KCR-021', 'kat' => 'atasan', 'katLabel' => 'Atasan', 'harga' => 'Rp 529.000', 'stok' => 19, 'variasi' => 'S, M, L • Beige, Mocha', 'terjual' => '73', 'status' => 'aktif'],
                        ['nama' => 'Dress Midi Satin', 'sku' => 'RLV-DMS-011', 'kat' => 'atasan', 'katLabel' => 'Atasan', 'harga' => 'Rp 789.000', 'stok' => 31, 'variasi' => 'S, M, L • Dusty Rose, Emerald', 'terjual' => '65', 'status' => 'aktif'],
                        ['nama' => 'Jaket Denim Vintage Wash', 'sku' => 'RLV-JDV-005', 'kat' => 'outerwear', 'katLabel' => 'Outerwear', 'harga' => 'Rp 699.000', 'stok' => 12, 'variasi' => 'M, L, XL • Light Wash', 'terjual' => '48', 'status' => 'nonaktif'],
                    ] as $p)
                        <tr data-table-row data-kategori="{{ $p['kat'] }}" data-status-produk="{{ $p['status'] }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-14 rounded-md bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 overflow-hidden">
                                        <span class="material-symbols-outlined text-[22px] text-on-surface-variant">checkroom</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-on-surface truncate">{{ $p['nama'] }}</p>
                                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $p['sku'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $p['katLabel'] }}</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">{{ $p['harga'] }}</td>
                            <td class="py-3.5 px-4 text-center {{ $p['stok'] === 0 ? 'text-error font-bold' : ($p['stok'] <= 10 ? 'text-gold-accent font-bold' : 'text-on-surface') }}">{{ number_format($p['stok'], 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4">
                                <p class="text-on-surface">{{ $p['terjual'] }} pcs</p>
                                <p class="text-xs text-on-surface-variant">{{ $p['variasi'] }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($p['status'] === 'aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                                @elseif ($p['status'] === 'habis')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Habis</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="relative inline-block" data-dropdown-wrap>
                                    <button type="button" data-dropdown-toggle class="p-1.5 rounded-md hover:bg-surface-container-high transition-colors text-on-surface-variant hover:text-on-surface" aria-label="Aksi produk">
                                        <span class="material-symbols-outlined text-[20px]">more_vert</span>
                                    </button>
                                    <div data-dropdown-menu class="hidden absolute right-0 top-full mt-1 w-44 bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl z-50 py-1.5 text-left">
                                        <button type="button" onclick="showRalivaToast('Form edit dibuka di panel kanan (demo).', 'edit')" class="w-full flex items-center gap-3 px-4 py-2.5 text-on-surface hover:bg-surface-container-low transition-colors text-sm">
                                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">edit</span>Edit Produk
                                        </button>
                                        <button type="button" data-drawer-open="drawer-form-produk" class="w-full flex items-center gap-3 px-4 py-2.5 text-on-surface hover:bg-surface-container-low transition-colors text-sm">
                                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">content_copy</span>Duplikat
                                        </button>
                                        <button type="button" onclick="showRalivaToast('Produk dinonaktifkan (demo).', 'block')" class="w-full flex items-center gap-3 px-4 py-2.5 text-on-surface hover:bg-surface-container-low transition-colors text-sm">
                                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">visibility_off</span>Nonaktifkan
                                        </button>
                                        <div class="my-1.5 border-t border-muted-border"></div>
                                        <button type="button" onclick="showRalivaToast('Produk dihapus permanen (demo).', 'delete')" class="w-full flex items-center gap-3 px-4 py-2.5 text-error hover:bg-error/10 transition-colors text-sm">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>Hapus
                                        </button>
                                    </div>
                                </div>
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
            <div>
                <p class="font-title-md text-title-md text-on-surface">Produk tidak ditemukan</p>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Coba ubah kata kunci atau filter pencarian Anda.</p>
            </div>
            <button type="button" data-filter-reset class="mt-2 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <div data-pagination class="flex items-center justify-between pt-6 mt-2 border-t border-muted-border">
            <p class="text-xs text-on-surface-variant">Menampilkan 8 dari 148 produk</p>
            <div class="flex items-center gap-1">
                <button type="button" disabled class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface-variant opacity-50 cursor-not-allowed"><span class="material-symbols-outlined text-[18px]">chevron_left</span></button>
                <button type="button" class="w-9 h-9 rounded-lg bg-deep-onyx text-on-primary font-label-sm text-[12px]">1</button>
                <button type="button" onclick="showRalivaToast('Halaman berikutnya (demo).')" class="w-9 h-9 rounded-lg border border-muted-border font-label-sm text-[12px] text-on-surface hover:border-gold-accent transition-colors">2</button>
                <button type="button" onclick="showRalivaToast('Halaman berikutnya (demo).')" class="w-9 h-9 rounded-lg border border-muted-border flex items-center justify-center text-on-surface hover:border-gold-accent transition-colors"><span class="material-symbols-outlined text-[18px]">chevron_right</span></button>
            </div>
        </div>
    </section>
</div>

{{-- Drawer Form Produk --}}
<div id="drawer-overlay" class="fixed inset-0 bg-black/50 z-[70] hidden opacity-0 transition-opacity duration-300"></div>
<div id="drawer-form-produk" data-drawer-panel class="fixed inset-y-0 right-0 z-[80] w-full max-w-xl bg-surface-container-lowest border-l border-muted-border shadow-xl translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-center justify-between px-6 py-5 border-b border-muted-border shrink-0">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Produk Baru</h3>
        <button type="button" data-drawer-close class="text-on-surface-variant hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <form data-toast-message="Produk baru berhasil ditambahkan." class="flex-1 overflow-y-auto p-6 space-y-6">
        {{-- Foto --}}
        <div>
            <label class="block raliva-label mb-2">Foto Produk (maks. 8 foto)</label>
            <div class="grid grid-cols-4 gap-gutter">
                @for ($i = 0; $i < 4; $i++)
                    <div class="aspect-[3/4] rounded-lg border-2 border-dashed border-outline-variant flex flex-col items-center justify-center gap-1 cursor-pointer hover:border-gold-accent hover:bg-surface-container-low transition-colors group">
                        <span class="material-symbols-outlined text-[22px] text-on-surface-variant group-hover:text-gold-accent transition-colors">add_photo_alternate</span>
                        <span class="text-[10px] text-on-surface-variant">Foto {{ $i + 1 }}</span>
                    </div>
                @endfor
            </div>
        </div>

        {{-- Informasi Dasar --}}
        <div class="space-y-4">
            <p class="text-xs font-mediumr text-gold-accent pt-2 border-t border-muted-border">Informasi Dasar</p>
            <div>
                <label for="fp-nama" class="block raliva-label mb-2">Nama Produk</label>
                <input id="fp-nama" type="text" placeholder="cth. Blazer Wool Premium" required class="raliva-input" />
            </div>
            <div>
                <label for="fp-deskripsi" class="block raliva-label mb-2">Deskripsi</label>
                <textarea id="fp-deskripsi" rows="3" placeholder="Bahan, potongan, keunggulan produk..." class="raliva-textarea"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="fp-kategori" class="block raliva-label mb-2">Kategori</label>
                    <select id="fp-kategori" class="raliva-select">
                        <option>Outerwear</option><option>Atasan</option><option>Bawahan</option><option>Aksesoris</option>
                    </select>
                </div>
                <div>
                    <label for="fp-harga" class="block raliva-label mb-2">Harga (Rp)</label>
                    <input id="fp-harga" type="number" placeholder="949000" required class="raliva-input" />
                </div>
            </div>
        </div>

        {{-- Variasi --}}
        <div class="space-y-4">
            <p class="text-xs font-mediumr text-gold-accent pt-2 border-t border-muted-border">Variasi &amp; Stok</p>
            <div>
                <p class="raliva-label mb-2">Ukuran</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size'] as $size)
                        <button type="button" class="ukuran-chip px-4 py-2 rounded-lg border border-muted-border text-xs font-medium text-on-surface hover:border-gold-accent transition-colors">{{ $size }}</button>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="raliva-label mb-2">Warna</p>
                <div class="flex flex-wrap gap-3">
                    @foreach ([['Hitam', '#1c1b1b'], ['Krem', '#e8dcc8'], ['Navy', '#22304a'], ['Camel', '#c19a6b'], ['Putih', '#f5f3f3']] as $color)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="sr-only peer" />
                            <span class="w-7 h-7 rounded-full border border-outline-variant shadow-inner peer-checked:ring-2 peer-checked:ring-gold-accent peer-checked:ring-offset-2 ring-offset-surface-container-lowest transition-all" style="background-color: {{ $color[1] }};"></span>
                            <span class="font-body-md text-xs text-on-surface peer-checked:text-gold-accent">{{ $color[0] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="fp-stok" class="block raliva-label mb-2">Total Stok Awal</label>
                    <input id="fp-stok" type="number" value="50" min="0" class="raliva-input" />
                </div>
                <div>
                    <label for="fp-min-restock" class="block raliva-label mb-2">Ambang Stok Menipis</label>
                    <input id="fp-min-restock" type="number" value="10" min="0" class="raliva-input" />
                </div>
            </div>
        </div>

        <div class="sticky bottom-0 -mx-6 px-6 py-4 bg-surface-container-lowest border-t border-muted-border flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter">
            <button type="button" data-drawer-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
            <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">check_circle</span>Simpan Produk
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .ukuran-chip.selected { background: #111; color: #fff; border-color: #111; }
    .dark .ukuran-chip.selected { background: #f0eeee; color: #111; border-color: #f0eeee; }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.ukuran-chip').forEach((chip) => {
        chip.addEventListener('click', () => chip.classList.toggle('selected'));
    });
</script>
@endpush
