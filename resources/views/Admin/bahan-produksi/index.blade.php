@extends('layouts.admin')

@section('title', 'Bahan Produksi')
@section('header-title', 'Bahan Produksi')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola stok bahan baku untuk produksi.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <section data-reveal-group class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">inventory_2</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Total Bahan</span>
            <span class="raliva-figure text-[26px] text-on-surface relative">{{ $stats['total'] }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">check_circle</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Aktif</span>
            <span class="raliva-figure text-[26px] text-secondary relative">{{ $stats['aktif'] }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">warning</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Stok Menipis</span>
            <span class="raliva-figure text-[26px] text-error relative">{{ $stats['menipis'] }}</span>
        </div>
    </section>

    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Bahan Produksi</h2>
            <button type="button" data-modal-open="modal-form-bahan" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[18px]">add</span> Tambah Bahan
            </button>
        </div>

        <div class="relative w-full md:w-72 mb-6">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
            <input type="text" data-table-search placeholder="Cari nama bahan..." class="raliva-search" />
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Nama Bahan</th>
                        <th class="p-4 text-left">Kategori</th>
                        <th class="p-4 text-right">Stok</th>
                        <th class="p-4 text-right">Min.</th>
                        <th class="p-4 text-left">Supplier</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($bahans as $b)
                        @php
                            $stokTipis = (int) $b->stok <= (int) $b->stok_minimum && $b->status === \App\Models\BahanProduksi::STATUS_AKTIF;
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors" data-table-row data-status="{{ $b->status }}">
                            <td class="p-4 font-medium text-on-surface">{{ $b->nama_bahan }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border border-muted-border bg-surface-container-low text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[13px]">category</span>{{ $b->kategori }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold whitespace-nowrap {{ $stokTipis ? 'text-error' : 'text-on-surface' }}">
                                {{ $b->stok }} {{ $b->satuan }}
                                @if ($stokTipis)
                                    <span class="ml-1 align-middle text-error" title="Stok menipis"><span class="material-symbols-outlined text-[16px]">warning</span></span>
                                @endif
                            </td>
                            <td class="p-4 text-right text-on-surface-variant whitespace-nowrap">{{ $b->stok_minimum }} {{ $b->satuan }}</td>
                            <td class="p-4 text-on-surface">{{ $b->supplier?->nama_supplier ?? '-' }}</td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $b->status === 'aktif' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-error/10 text-error border-error/20' }}">{{ $b->status }}</span>
                            </td>
                            <td class="p-4 text-right whitespace-nowrap">
                                <button type="button" data-modal-open="modal-edit-{{ $b->bahan_id }}" class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:border-gold-accent transition-colors inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px]">edit</span> Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant">Belum ada bahan produksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p data-empty-state class="hidden flex-col items-center justify-center text-on-surface-variant text-sm py-6 text-center">Tidak ada bahan yang cocok dengan pencarian.</p>
        <div class="mt-4">{{ $bahans->withQueryString()->links() }}</div>
    </section>
</div>

{{-- Modal Tambah Bahan --}}
<div id="modal-form-bahan" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl flex flex-col max-h-[90vh] overflow-y-auto" style="overscroll-behavior: contain;">
        <div class="sticky top-0 bg-surface-container-lowest z-10 flex items-center justify-between px-6 py-5 border-b border-muted-border shrink-0">
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Bahan Produksi</h3>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.bahan-produksi.store') }}" class="flex-1 p-6 space-y-4">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Nama Bahan *</label>
                <input type="text" name="nama_bahan" required maxlength="150" placeholder="cth. Kain Katun Premium" class="raliva-input" />
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Kategori *</label>
                    <select name="kategori" required class="raliva-select">
                        <option value="kain">Kain</option>
                        <option value="aksesoris">Aksesoris</option>
                        <option value="kemasan">Kemasan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Satuan *</label>
                    <input type="text" name="satuan" required maxlength="20" placeholder="meter, pcs, roll" class="raliva-input" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Stok</label>
                    <input type="number" name="stok" min="0" value="0" class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Stok Minimum</label>
                    <input type="number" name="stok_minimum" min="0" value="0" class="raliva-input" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Supplier</label>
                    <select name="supplier_id" class="raliva-select">
                        <option value="">— Pilih Supplier —</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->supplier_id }}">{{ $s->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Status</label>
                    <select name="status" class="raliva-select">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border pt-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span> Simpan Bahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit per bahan --}}
@foreach ($bahans as $b)
<div id="modal-edit-{{ $b->bahan_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl flex flex-col max-h-[90vh] overflow-y-auto" style="overscroll-behavior: contain;">
        <div class="sticky top-0 bg-surface-container-lowest z-10 flex items-center justify-between px-6 py-5 border-b border-muted-border shrink-0">
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Edit Bahan Produksi</h3>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.bahan-produksi.update', ['bahan' => $b->bahan_id]) }}" class="flex-1 p-6 space-y-4">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Nama Bahan *</label>
                <input type="text" name="nama_bahan" required maxlength="150" value="{{ $b->nama_bahan }}" class="raliva-input" />
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Kategori *</label>
                    <select name="kategori" required class="raliva-select">
                        @foreach (['kain', 'aksesoris', 'kemasan', 'lainnya'] as $ktg)
                            <option value="{{ $ktg }}" @selected($b->kategori === $ktg)>{{ ucfirst($ktg) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Satuan *</label>
                    <input type="text" name="satuan" required maxlength="20" value="{{ $b->satuan }}" class="raliva-input" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Stok</label>
                    <input type="number" name="stok" min="0" value="{{ $b->stok }}" class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Stok Minimum</label>
                    <input type="number" name="stok_minimum" min="0" value="{{ $b->stok_minimum }}" class="raliva-input" />
                </div>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label class="block raliva-label mb-2">Supplier</label>
                    <select name="supplier_id" class="raliva-select">
                        <option value="">— Pilih Supplier —</option>
                        @foreach ($suppliers as $s)
                            <option value="{{ $s->supplier_id }}" @selected((int) $b->supplier_id === (int) $s->supplier_id)>{{ $s->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Status</label>
                    <select name="status" class="raliva-select">
                        <option value="aktif" @selected($b->status === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($b->status === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border pt-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span> Perbarui Bahan
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
    const lockScroll = () => {
        const w = window.innerWidth - document.documentElement.clientWidth;
        if (w > 0) { document.body.style.paddingRight = w + 'px'; document.documentElement.style.paddingRight = w + 'px'; }
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    };
    const unlockScroll = () => {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        document.documentElement.style.overflow = '';
        document.documentElement.style.paddingRight = '';
    };
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => setTimeout(lockScroll, 0));
    });
    document.querySelectorAll('[data-modal-close]').forEach(el => {
        el.addEventListener('click', () => {
            setTimeout(() => {
                if (!document.querySelector('[data-modal]:not(.hidden)')) unlockScroll();
            }, 50);
        });
    });
    document.addEventListener('click', (e) => {
        if (e.target.matches('[data-modal]')) setTimeout(() => {
            if (!document.querySelector('[data-modal]:not(.hidden)')) unlockScroll();
        }, 50);
    });
</script>
@endpush