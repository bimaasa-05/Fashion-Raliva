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
        <button type="button" data-modal-open="modal-form-kategori" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Kategori
        </button>
    </section>
    <!-- Categories Grid -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Kategori</h2>
            <span class="text-on-surface-variant font-body-md text-sm">7 kategori aktif</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            <!-- Category Card 1 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-[28px]">folder</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">Pakaian</h3>
                        <p class="text-on-surface-variant text-sm mt-1">24 produk</p>
                    </div>
                </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <button type="button" onclick="editCategory(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button type="button" onclick="showRalivaToast('Hapus kategori dinonaktifkan pada demo — kategori masih memiliki produk.', 'delete')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>

            <!-- Category Card 2 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-[28px]">storefront</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">Aksesoris</h3>
                        <p class="text-on-surface-variant text-sm mt-1">12 produk</p>
                    </div>
                </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <button type="button" onclick="editCategory(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button type="button" onclick="showRalivaToast('Hapus kategori dinonaktifkan pada demo — kategori masih memiliki produk.', 'delete')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>

            <!-- Category Card 3 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-[28px]">category</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">Sepatu</h3>
                        <p class="text-on-surface-variant text-sm mt-1">18 produk</p>
                    </div>
                </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <button type="button" onclick="editCategory(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button type="button" onclick="showRalivaToast('Hapus kategori dinonaktifkan pada demo — kategori masih memiliki produk.', 'delete')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>

            <!-- Category Card 4 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-[28px]">diamond</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">Tas & Dompet</h3>
                        <p class="text-on-surface-variant text-sm mt-1">8 produk</p>
                    </div>
                </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <button type="button" onclick="editCategory(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button type="button" onclick="showRalivaToast('Hapus kategori dinonaktifkan pada demo — kategori masih memiliki produk.', 'delete')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>

            <!-- Category Card 5 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-[28px]">watch</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">Jam & Aksesoris</h3>
                        <p class="text-on-surface-variant text-sm mt-1">6 produk</p>
                    </div>
                </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <button type="button" onclick="editCategory(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button type="button" onclick="showRalivaToast('Hapus kategori dinonaktifkan pada demo — kategori masih memiliki produk.', 'delete')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>

            <!-- Category Card 6 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-[28px]">checkroom</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">Perawatan Diri</h3>
                        <p class="text-on-surface-variant text-sm mt-1">15 produk</p>
                    </div>
                </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <button type="button" onclick="editCategory(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button type="button" onclick="showRalivaToast('Hapus kategori dinonaktifkan pada demo — kategori masih memiliki produk.', 'delete')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>

            <!-- Category Card 7 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-secondary text-[28px]">kitchen</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">Rumah Tangga</h3>
                        <p class="text-on-surface-variant text-sm mt-1">11 produk</p>
                    </div>
                </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <button type="button" onclick="editCategory(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                    <button type="button" onclick="showRalivaToast('Hapus kategori dinonaktifkan pada demo — kategori masih memiliki produk.', 'delete')" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Form Kategori -->
<div id="modal-form-kategori" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 id="kategori-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Kategori Baru</h3>
                <p id="kategori-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Kategori berlaku untuk seluruh toko di platform.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="add-category-form" data-toast-message="Kategori baru berhasil ditambahkan." class="p-6 space-y-5">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="categoryName">Nama Kategori</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="categoryName" name="categoryName" type="text" placeholder="Misal: Pakaian, Aksesoris" required />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="categoryDesc">Deskripsi</label>
                <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="categoryDesc" name="categoryDesc" rows="3" placeholder="Deskripsi kategori..."></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" id="kategori-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Kategori</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const openCategoryForm = (mode, name = '') => {
        const isEdit = mode === 'edit';
        document.getElementById('kategori-modal-title').textContent = isEdit ? 'Ubah Kategori' : 'Tambah Kategori Baru';
        document.getElementById('kategori-modal-sub').textContent = isEdit
            ? 'Perubahan berlaku pada seluruh produk dalam kategori ini.'
            : 'Kategori berlaku untuk seluruh toko di platform.';
        document.getElementById('categoryName').value = isEdit ? name : '';
        document.getElementById('categoryDesc').value = '';
        document.getElementById('kategori-submit-btn').textContent = isEdit ? 'Simpan Perubahan' : 'Tambah Kategori';
        document.getElementById('add-category-form').setAttribute('data-toast-message', isEdit ? 'Perubahan kategori berhasil disimpan.' : 'Kategori baru berhasil ditambahkan.');
        document.getElementById('modal-form-kategori')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    const editCategory = (btn) => {
        const card = btn.closest('.group');
        const name = card?.querySelector('h3')?.textContent.trim() || '';
        openCategoryForm('edit', name);
    };
</script>
@endpush