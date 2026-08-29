@extends('layouts.admin')

@section('title', 'Data Supplier')

@section('header-title', 'Data Supplier')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola daftar supplier bahan & produk untuk kebutuhan produksi.')

@section('content')
<div class="space-y-6">
    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Total Supplier</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">18</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Supplier Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">14</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">check_circle</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Menunggu Verifikasi</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">3</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Jangkauan Kota</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">9 <span class="text-on-surface-variant font-body-md text-sm">kota</span></span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">location_on</span>
        </div>
    </div>

    <!-- Toolbar -->
    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
        <div class="p-4 md:p-6 pb-0">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-6">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Supplier</h2>
                <button type="button" data-modal-open="modal-form-supplier" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                    <span class="material-symbols-outlined text-[18px]">add</span> Tambah Supplier
                </button>
            </div>

            <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
                    <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter & Pencarian</span>
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center gap-gutter">
                    <div class="relative flex-1 min-w-0">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                        <input type="text" data-table-search placeholder="Cari nama, kontak, atau kota..." class="w-full bg-surface-container-lowest border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-gold-accent transition-colors" />
                    </div>
                    <select data-table-filter="jenis" aria-label="Filter jenis barang" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                        <option value="semua">Semua Jenis Barang</option>
                        <option value="kain">Kain / Bahan</option>
                        <option value="aksesoris">Aksesoris Jahit</option>
                        <option value="kemasan">Kemasan</option>
                        <option value="jadi">Produk Jadi</option>
                    </select>
                    <select data-table-filter="status" aria-label="Filter status" class="bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                        <option value="semua">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="verifikasi">Verifikasi</option>
                        <option value="nonaktif">Non-aktif</option>
                    </select>
                    <button type="button" data-filter-reset class="px-3 py-2.5 border border-muted-border rounded-lg font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant hover:text-on-surface hover:border-gold-accent transition-colors">Reset</button>
                </div>
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Kode</th>
                        <th class="p-4 text-left">Nama Supplier</th>
                        <th class="p-4 text-left">Kontak</th>
                        <th class="p-4 text-left">Kota</th>
                        <th class="p-4 text-center">Jenis Barang</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr data-table-row data-jenis="kain" data-status="aktif" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">SPL-001</td>
                        <td class="p-4"><span class="block font-medium text-on-surface">CV Tekstil Bandung</span><span class="text-xs text-on-surface-variant">Sejak 2023</span></td>
                        <td class="p-4 text-on-surface-variant">Budi Santoso<br /><span class="text-xs">0812-3456-7890</span></td>
                        <td class="p-4 text-on-surface">Bandung</td>
                        <td class="p-4 text-center"><span class="px-2 py-1 rounded bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wide">Kain</span></td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button type="button" onclick="editSupplier(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button type="button" onclick="showRalivaToast('Supplier SPL-001 dinonaktifkan (demo).', 'block')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Non-aktifkan"><span class="material-symbols-outlined text-[18px]">block</span></button>
                        </td>
                    </tr>
                    <tr data-table-row data-jenis="aksesoris" data-status="aktif" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">SPL-002</td>
                        <td class="p-4"><span class="block font-medium text-on-surface">UD Aksesoris Mas</span><span class="text-xs text-on-surface-variant">Sejak 2024</span></td>
                        <td class="p-4 text-on-surface-variant">Siti Aminah<br /><span class="text-xs">0813-9876-5432</span></td>
                        <td class="p-4 text-on-surface">Solo</td>
                        <td class="p-4 text-center"><span class="px-2 py-1 rounded bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wide">Aksesoris</span></td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button type="button" onclick="editSupplier(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button type="button" onclick="showRalivaToast('Supplier SPL-002 dinonaktifkan (demo).', 'block')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Non-aktifkan"><span class="material-symbols-outlined text-[18px]">block</span></button>
                        </td>
                    </tr>
                    <tr data-table-row data-jenis="kemasan" data-status="verifikasi" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">SPL-003</td>
                        <td class="p-4"><span class="block font-medium text-on-surface">PT Kemasan Amanah</span><span class="text-xs text-on-surface-variant">Baru bergabung</span></td>
                        <td class="p-4 text-on-surface-variant">Rizky Pratama<br /><span class="text-xs">0857-1122-3344</span></td>
                        <td class="p-4 text-on-surface">Jakarta Timur</td>
                        <td class="p-4 text-center"><span class="px-2 py-1 rounded bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wide">Kemasan</span></td>
                        <td class="p-4 text-center"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-warning/15 text-warning text-[10px] font-bold uppercase border border-warning/30"><span class="material-symbols-outlined text-[12px]">hourglass_top</span>Verifikasi</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button type="button" onclick="editSupplier(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button type="button" onclick="showRalivaToast('Dokumen verifikasi SPL-003 sedang ditinjau.', 'fact_check')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Non-aktifkan"><span class="material-symbols-outlined text-[18px]">block</span></button>
                        </td>
                    </tr>
                    <tr data-table-row data-jenis="jadi" data-status="nonaktif" class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">SPL-004</td>
                        <td class="p-4"><span class="block font-medium text-on-surface">Gudang Konveksi Makmur</span><span class="text-xs text-on-surface-variant">Kontrak selesai</span></td>
                        <td class="p-4 text-on-surface-variant">Andi Wijaya<br /><span class="text-xs">0821-5566-7788</span></td>
                        <td class="p-4 text-on-surface">Semarang</td>
                        <td class="p-4 text-center"><span class="px-2 py-1 rounded bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wide">Produk Jadi</span></td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/25">Non-aktif</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button type="button" onclick="editSupplier(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button type="button" onclick="showRalivaToast('Aktivasi ulang supplier dikirim (demo).', 'restart_alt')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Aktifkan ulang"><span class="material-symbols-outlined text-[18px]">restart_alt</span></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p data-empty-state class="hidden text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada supplier yang sesuai dengan pencarian atau filter.</p>
    </section>
</div>

<!-- Modal Form Supplier -->
<div id="modal-form-supplier" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 id="supplier-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Supplier Baru</h3>
                <p id="supplier-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Data supplier digunakan untuk pengadaan bahan & produk.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form data-toast-message="Data supplier berhasil disimpan." class="p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="raliva-label" for="supplierNama">Nama Supplier</label>
                    <input type="text" id="supplierNama" required placeholder="Misal: CV Tekstil Bandung" class="raliva-input" />
                </div>
                <div>
                    <label class="raliva-label" for="supplierKota">Kota</label>
                    <input type="text" id="supplierKota" required placeholder="Misal: Bandung" class="raliva-input" />
                </div>
                <div>
                    <label class="raliva-label" for="supplierKontak">Nama Kontak</label>
                    <input type="text" id="supplierKontak" required placeholder="Nama PIC" class="raliva-input" />
                </div>
                <div>
                    <label class="raliva-label" for="supplierTelp">No. Telepon</label>
                    <input type="tel" id="supplierTelp" required placeholder="08xx-xxxx-xxxx" class="raliva-input" />
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Jenis Barang</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach(['kain' => 'Kain', 'aksesoris' => 'Aksesoris', 'kemasan' => 'Kemasan', 'jadi' => 'Produk Jadi'] as $val => $label)
                        <label class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                            <input type="radio" class="sr-only" name="supplierJenis" value="{{ $val }}" {{ $val === 'kain' ? 'checked' : '' }} />
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Status Kerja Sama</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="supplierStatus" value="aktif" checked /> Aktif
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="supplierStatus" value="verifikasi" /> Verifikasi
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="supplierStatus" value="nonaktif" /> Non-aktif
                    </label>
                </div>
            </div>
            <div>
                <label class="raliva-label" for="supplierCatatan">Catatan</label>
                <textarea class="raliva-textarea" id="supplierCatatan" rows="3" placeholder="Syarat pembayaran, minimal order, dsb."></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" id="supplier-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Supplier</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const editSupplier = (btn) => {
        const row = btn.closest('tr');
        const tds = row.querySelectorAll('td');
        document.getElementById('supplierNama').value = tds[1]?.querySelector('span')?.textContent.trim() || '';
        document.getElementById('supplierKota').value = tds[3]?.textContent.trim() || '';
        document.getElementById('supplierKontak').value = tds[2]?.childNodes[0]?.textContent.trim() || '';
        document.getElementById('supplierTelp').value = tds[2]?.querySelector('.text-xs')?.textContent.trim().replace(/\s/g, '') || '';
        document.getElementById('supplierCatatan').value = '';
        document.getElementById('supplier-modal-title').textContent = 'Ubah Data Supplier';
        document.getElementById('supplier-modal-sub').textContent = 'Perbarui informasi kerja sama supplier.';
        document.getElementById('supplier-submit-btn').textContent = 'Simpan Perubahan';
        const form = document.querySelector('#modal-form-supplier form');
        form.setAttribute('data-toast-message', 'Perubahan data supplier berhasil disimpan.');
        document.getElementById('modal-form-supplier')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    document.querySelector('[data-modal-open="modal-form-supplier"]')?.addEventListener('click', () => {
        document.getElementById('supplier-modal-title').textContent = 'Tambah Supplier Baru';
        document.getElementById('supplier-modal-sub').textContent = 'Data supplier digunakan untuk pengadaan bahan & produk.';
        document.getElementById('supplier-submit-btn').textContent = 'Simpan Supplier';
        document.querySelector('#modal-form-supplier form')?.setAttribute('data-toast-message', 'Supplier baru berhasil ditambahkan.');
    });
</script>
@endpush
