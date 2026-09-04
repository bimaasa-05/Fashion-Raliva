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
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Supplier Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">14</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">check_circle</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Menunggu Verifikasi</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gold-accent">3</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium" data-reveal>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Jangkauan Kota</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">9 <span class="text-on-surface-variant font-body-md text-sm">kota</span></span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">location_on</span>
        </div>
    </div>

    <!-- Toolbar -->
    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
        <div class="p-4 md:p-6 pb-0">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 mb-6">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Supplier</h2>
                <button type="button" data-modal-open="modal-form-supplier" onclick="openAddSupplier()" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
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
                    @forelse ($suppliers as $s)
                    <tr data-table-row data-sup-id="{{ $s->supplier_id }}" data-jenis="{{ $s->jenis ?? 'lainnya' }}" data-status="{{ $s->status }}" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">SUP-{{ $s->supplier_id }}</td>
                        <input type="hidden" id="sup-nama-{{ $s->supplier_id }}" value="{{ $s->nama_supplier }}" />
                        <input type="hidden" id="sup-kota-{{ $s->supplier_id }}" value="{{ $s->kota }}" />
                        <input type="hidden" id="sup-kontak-{{ $s->supplier_id }}" value="{{ $s->kontak }}" />
                        <input type="hidden" id="sup-email-{{ $s->supplier_id }}" value="{{ $s->email }}" />
                        <input type="hidden" id="sup-jenis-{{ $s->supplier_id }}" value="{{ $s->jenis }}" />
                        <input type="hidden" id="sup-status-{{ $s->supplier_id }}" value="{{ $s->status }}" />
                        <input type="hidden" id="sup-catatan-{{ $s->supplier_id }}" value="{{ $s->catatan }}" />
                        <td class="p-4"><span class="block font-medium text-on-surface">{{ $s->nama_supplier }}</span><span class="text-xs text-on-surface-variant">Sejak {{ $s->created_at?->format('Y') }}</span></td>
                        <td class="p-4 text-on-surface-variant">{{ $s->kontak ?? '-' }}<br /><span class="text-xs">{{ $s->email ?? '' }}</span></td>
                        <td class="p-4 text-on-surface">{{ $s->kota ?? '-' }}</td>
                        <td class="p-4 text-center"><span class="px-2 py-1 rounded bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wide">{{ $s->jenis ?? '-' }}</span></td>
                        <td class="p-4 text-center">
                            @if($s->status === 'aktif')
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                            @elseif($s->status === 'verifikasi')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-warning/15 text-warning text-[10px] font-bold uppercase border border-warning/30"><span class="material-symbols-outlined text-[12px]">hourglass_top</span>Verifikasi</span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/25">Non-aktif</span>
                            @endif
                        </td>
                        <td class="p-4 text-right whitespace-nowrap">
                            <button type="button" onclick="editSupplier({{ $s->supplier_id }})" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button type="button" data-modal-open="modal-del-{{ $s->supplier_id }}" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="p-8 text-center text-on-surface-variant text-sm">Belum ada supplier.</td></tr>
                    @endforelse
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
        <form id="supplier-form" method="POST" action="{{ route('admin.supplier.store') }}" class="p-6 space-y-5">
            @csrf
            @method('POST')
            <input type="hidden" name="supplier_id" id="supplierId" value="" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="raliva-label" for="supplierNama">Nama Supplier</label>
                    <input type="text" id="supplierNama" name="nama_supplier" required placeholder="Misal: CV Tekstil Bandung" class="raliva-input" />
                </div>
                <div>
                    <label class="raliva-label" for="supplierKota">Kota</label>
                    <input type="text" id="supplierKota" name="kota" required placeholder="Misal: Bandung" class="raliva-input" />
                </div>
                <div>
                    <label class="raliva-label" for="supplierKontak">Nama Kontak</label>
                    <input type="text" id="supplierKontak" name="kontak" required placeholder="Nama PIC" class="raliva-input" />
                </div>
                <div>
                    <label class="raliva-label" for="supplierTelp">No. Telepon / Email</label>
                    <input type="text" id="supplierTelp" name="email" required placeholder="08xx / email" class="raliva-input" />
                </div>
            </div>
            <div>
                <label class="raliva-label">Jenis Barang</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach(['kain' => 'Kain', 'aksesoris' => 'Aksesoris', 'kemasan' => 'Kemasan', 'jadi' => 'Produk Jadi'] as $val => $label)
                        <label class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                            <input type="radio" class="sr-only" name="jenis" value="{{ $val }}" {{ $val === 'kain' ? 'checked' : '' }} />
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="raliva-label">Status Kerja Sama</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="status" value="aktif" checked /> Aktif
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="status" value="verifikasi" /> Verifikasi
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="status" value="nonaktif" /> Non-aktif
                    </label>
                </div>
            </div>
            <div>
                <label class="raliva-label" for="supplierCatatan">Catatan</label>
                <textarea class="raliva-textarea" id="supplierCatatan" name="catatan" rows="3" placeholder="Syarat pembayaran, minimal order, dsb."></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" id="supplier-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Supplier</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus Supplier --}}
@foreach ($suppliers as $s)
<div id="modal-del-{{ $s->supplier_id }}" data-modal class="fixed inset-0 z-[75] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.supplier.destroy', $s) }}" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
        @csrf
        @method('DELETE')
        <p class="raliva-label text-error">Hapus Supplier</p>
        <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $s->nama_supplier }}</h3>
        <p class="text-sm text-on-surface-variant mt-3">Yakin ingin menghapus supplier ini? Tindakan tidak dapat dibatalkan.</p>
        <div class="flex gap-3 mt-6">
            <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
            <button type="submit" class="flex-1 py-2.5 bg-error/10 border border-error/20 text-error font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-error/20 transition-colors">Ya, Hapus</button>
        </div>
    </form>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    const openAddSupplier = () => {
        const form = document.getElementById('supplier-form');
        form.action = '{{ route('admin.supplier.store') }}';
        form.querySelector('input[name="_method"]')?.remove();
        form.reset();
        document.getElementById('supplierId').value = '';
        document.getElementById('supplier-modal-title').textContent = 'Tambah Supplier Baru';
        document.getElementById('supplier-modal-sub').textContent = 'Data supplier digunakan untuk pengadaan bahan & produk.';
        document.getElementById('supplier-submit-btn').textContent = 'Simpan Supplier';
    };

    const editSupplier = (id) => {
        const form = document.getElementById('supplier-form');
        form.action = '{{ url('admin/supplier') }}/' + id;
        form.querySelector('input[name="_method"]')?.remove();
        const m = document.createElement('input');
        m.type = 'hidden'; m.name = '_method'; m.value = 'PUT';
        form.appendChild(m);
        document.getElementById('supplierId').value = id;
        document.getElementById('supplierNama').value = document.getElementById('sup-nama-' + id)?.value || '';
        document.getElementById('supplierKota').value = document.getElementById('sup-kota-' + id)?.value || '';
        document.getElementById('supplierKontak').value = document.getElementById('sup-kontak-' + id)?.value || '';
        document.getElementById('supplierTelp').value = document.getElementById('sup-email-' + id)?.value || '';
        document.getElementById('supplierCatatan').value = document.getElementById('sup-catatan-' + id)?.value || '';
        document.querySelector('input[name="jenis"][value="' + (document.getElementById('sup-jenis-' + id)?.value || 'kain') + '"]')?.click();
        document.querySelector('input[name="status"][value="' + (document.getElementById('sup-status-' + id)?.value || 'aktif') + '"]')?.click();
        document.getElementById('supplier-modal-title').textContent = 'Ubah Data Supplier';
        document.getElementById('supplier-modal-sub').textContent = 'Perbarui informasi kerja sama supplier.';
        document.getElementById('supplier-submit-btn').textContent = 'Simpan Perubahan';
    };
</script>
@endpush
