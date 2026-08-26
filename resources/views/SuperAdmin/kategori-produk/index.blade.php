@extends('layouts.superadmin')

@section('title', 'Kategori Produk')

@section('header-title', 'Kategori Produk')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola kategori global yang digunakan oleh semua toko')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Toolbar -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">category</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Kategori Produk</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Kelola kategori global yang digunakan semua toko.</p>
            </div>
        </div>
        <button type="button" onclick="openKategoriForm()" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Kategori
        </button>
    </section>

    <!-- Categories Grid -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Kategori</h2>
            <span class="text-on-surface-variant font-body-md text-sm">{{ $stats['aktif'] }} kategori aktif • {{ $stats['induk'] }} induk • total {{ $stats['total'] }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            @forelse ($categories as $kategori)
                <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5"
                    data-id="{{ $kategori->category_id }}"
                    data-nama="{{ $kategori->nama_kategori }}"
                    data-deskripsi="{{ $kategori->deskripsi }}"
                    data-parent="{{ $kategori->parent_id ?? '' }}"
                    data-produk="{{ $kategori->products_count }}"
                    data-sub="{{ $kategori->children_count }}">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                    <div class="relative flex items-start gap-4">
                        <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-secondary text-[28px]">{{ $kategori->parent_id ? 'subdirectory_arrow_right' : 'folder' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">{{ $kategori->nama_kategori }}</h3>
                                @if ($kategori->status !== \App\Models\Category::STATUS_AKTIF)
                                    <span class="inline-flex px-1.5 py-0.5 rounded bg-error/10 text-error border border-error/20 text-[9px] font-bold uppercase">Nonaktif</span>
                                @endif
                            </div>
                            <p class="text-on-surface-variant text-sm mt-1">
                                {{ $kategori->products_count }} produk
                                @if ($kategori->children_count > 0)• {{ $kategori->children_count }} sub-kategori @endif
                            </p>
                            @if ($kategori->deskripsi)
                                <p class="text-on-surface-variant/80 text-xs mt-1 line-clamp-2" title="{{ $kategori->deskripsi }}">{{ \Illuminate\Support\Str::limit($kategori->deskripsi, 70) }}</p>
                            @endif
                            @if ($kategori->parent)
                                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant mt-1.5 inline-flex items-center gap-1"><span class="material-symbols-outlined text-[12px]">account_tree</span>Sub dari {{ $kategori->parent->nama_kategori }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border mt-4">
                        <button type="button" onclick="openKategoriForm(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button type="button" onclick="openHapusKategori(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-12">Belum ada kategori. Tambahkan kategori pertama Anda.</p>
            @endforelse
        </div>
    </section>
</div>

<!-- Modal Form Kategori (Tambah/Edit) -->
<form method="POST" action="" id="kategori-form" onsubmit="closeKategoriModal()">
    @csrf
    <div id="modal-form-kategori" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close onclick="closeKategoriModal()"></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 id="kategori-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Kategori Baru</h3>
                    <p id="kategori-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Kategori berlaku untuk seluruh toko di platform.</p>
                </div>
                <button type="button" onclick="closeKategoriModal()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="namaKategori">Nama Kategori</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="namaKategori" name="nama_kategori" type="text" maxlength="100" placeholder="Misal: Pakaian, Aksesoris" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="parentId">Kategori Induk (opsional)</label>
                    <select name="parent_id" id="parentId" class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors">
                        <option value="">— Tanpa induk (kategori utama) —</option>
                        @foreach ($parents as $induk)
                            <option value="{{ $induk->category_id }}">{{ $induk->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="deskripsiKategori">Deskripsi</label>
                    <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="deskripsiKategori" name="deskripsi" rows="3" maxlength="500" placeholder="Deskripsi kategori..."></textarea>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" onclick="closeKategoriModal()" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" id="kategori-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Kategori</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Hapus Kategori -->
<form method="POST" action="" id="hapus-kategori-form" onsubmit="closeHapusModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="hapusKategoriModal" onclick="if (event.target === this) closeHapusModal()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">delete_forever</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus Kategori</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Kategori <span id="hapus-nama" class="font-bold text-on-surface">-</span> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
                <div id="hapus-warning" class="hidden mb-4"></div>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeHapusModal()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const kategoriUrls = {
        store: '{{ route('superadmin.kategori-produk.store') }}',
        update: (id) => '{{ route('superadmin.kategori-produk.update', ':id:') }}'.replace(':id:', id),
        hapus: (id) => '{{ route('superadmin.kategori-produk.hapus', ':id:') }}'.replace(':id:', id)
    };

    function openKategoriForm(card = null) {
        const isEdit = !!card;
        const form = document.getElementById('kategori-form');

        if (isEdit) {
            const d = card.dataset;
            document.getElementById('kategori-modal-title').textContent = 'Ubah Kategori';
            document.getElementById('kategori-modal-sub').textContent = 'Perubahan berlaku pada seluruh produk dalam kategori ini.';
            document.getElementById('namaKategori').value = d.nama;
            document.getElementById('deskripsiKategori').value = d.deskripsi || '';
            document.getElementById('parentId').value = d.parent || '';
            form.action = kategoriUrls.update(d.id);
            document.getElementById('kategori-submit-btn').textContent = 'Simpan Perubahan';
        } else {
            document.getElementById('kategori-modal-title').textContent = 'Tambah Kategori Baru';
            document.getElementById('kategori-modal-sub').textContent = 'Kategori berlaku untuk seluruh toko di platform.';
            document.getElementById('namaKategori').value = '';
            document.getElementById('deskripsiKategori').value = '';
            document.getElementById('parentId').value = '';
            form.action = kategoriUrls.store;
            document.getElementById('kategori-submit-btn').textContent = 'Tambah Kategori';
        }

        document.getElementById('modal-form-kategori').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('namaKategori').focus(), 100);
    }

    function closeKategoriModal() {
        document.getElementById('modal-form-kategori').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openHapusKategori(card) {
        const d = card.dataset;
        const warningBox = document.getElementById('hapus-warning');
        document.getElementById('hapus-nama').textContent = d.nama;

        if (parseInt(d.produk) > 0 || parseInt(d.sub) > 0) {
            const alasan = [];
            if (parseInt(d.produk) > 0) alasan.push(d.produk + ' produk');
            if (parseInt(d.sub) > 0) alasan.push(d.sub + ' sub-kategori');
            warningBox.className = 'mb-4 bg-error/5 border border-error/25 rounded-lg p-3 text-xs text-on-surface';
            warningBox.textContent = '⚠️ Kategori ini masih ' + alasan.join(' dan ') + '. Penghapusan akan ditolak sistem.';
        } else {
            warningBox.className = 'hidden';
            warningBox.textContent = '';
        }

        document.getElementById('hapus-kategori-form').action = kategoriUrls.hapus(d.id);
        const modal = document.getElementById('hapusKategoriModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeHapusModal() {
        const modal = document.getElementById('hapusKategoriModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeKategoriModal(); closeHapusModal(); }
    });
</script>
@endpush
