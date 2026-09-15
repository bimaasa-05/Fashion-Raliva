@extends('layouts.produksi')

@section('title', 'Bahan Produksi')

@section('header-title', 'Bahan Produksi')
@section('header-badge', $stats['total'] . ' Bahan')
@section('header-subtitle', 'Kelola stok bahan baku untuk produksi. Tambah bahan sesuai kebutuhan toko.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    {{-- Skeleton --}}
    <div data-skeleton class="space-y-section-gap">
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
            @for ($i = 0; $i < 4; $i++)
                <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
            @endfor
        </div>
        <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>

    <div data-real class="hidden space-y-section-gap">
        {{-- Stats --}}
        <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Bahan</span>
                <span class="raliva-figure text-[26px] text-on-surface">{{ $stats['total'] }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">terdaftar di toko</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">science</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Aktif</span>
                <span class="raliva-figure text-[26px] text-secondary">{{ $stats['aktif'] }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">siap dipakai</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">check_circle</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Menipis</span>
                <span class="raliva-figure text-[26px] text-error">{{ $stats['menipis'] }}</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">perlu restock</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-error/15 fill pointer-events-none select-none" aria-hidden="true">warning</span>
            </div>
        </section>

        {{-- Form Tambah Bahan --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Bahan Produksi</h3>
                <button type="button" onclick="document.getElementById('form-tambah-bahan').classList.toggle('hidden')" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded btn-premium">+ Tambah</button>
            </div>
            <form id="form-tambah-bahan" method="POST" action="{{ route('produksi.bahan-produksi.store') }}" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @csrf
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Nama Bahan *</label>
                    <input type="text" name="nama_bahan" required class="raliva-input w-full" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Kategori *</label>
                    <select name="kategori" required class="raliva-select w-full">
                        <option value="kain">Kain</option>
                        <option value="aksesoris">Aksesoris</option>
                        <option value="kemasan">Kemasan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Satuan *</label>
                    <input type="text" name="satuan" required placeholder="meter, pcs, roll" class="raliva-input w-full" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Stok Awal</label>
                    <input type="number" name="stok" min="0" value="0" class="raliva-input w-full" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Stok Minimum</label>
                    <input type="number" name="stok_minimum" min="0" value="0" class="raliva-input w-full" />
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase tracking-widest rounded btn-premium">Simpan</button>
                </div>
            </form>
        </section>

        {{-- Tabel Bahan --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                    <div class="relative flex-1 min-w-[220px]">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                        <input type="text" placeholder="Cari bahan..." data-table-search class="raliva-search" />
                    </div>
                    <select data-table-filter="kategori-bahan" class="raliva-select">
                        <option value="">Semua Kategori</option>
                        <option value="kain">Kain</option>
                        <option value="aksesoris">Aksesoris</option>
                        <option value="kemasan">Kemasan</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <div data-table-wrap class="overflow-x-auto">
                <table class="premium-table w-full min-w-[800px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Nama Bahan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kategori</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Stok</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Min.</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Supplier</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bahans as $b)
                            @php
                                $low = $b->stok <= $b->stok_minimum;
                            @endphp
                            <tr data-table-row data-kategori-bahan="{{ $b->kategori }}" class="border-b border-muted-border last:border-0 hover:bg-surface-container-low transition-colors">
                                <td class="py-3.5 px-4 font-bold text-on-surface">{{ $b->nama_bahan }}</td>
                                <td class="py-3.5 px-4 capitalize text-on-surface-variant">{{ $b->kategori }}</td>
                                <td class="py-3.5 px-4 text-center font-bold {{ $low ? 'text-error' : 'text-on-surface' }}">{{ $b->stok }} {{ $b->satuan }}</td>
                                <td class="py-3.5 px-4 text-center text-on-surface-variant">{{ $b->stok_minimum }} {{ $b->satuan }}</td>
                                <td class="py-3.5 px-4 text-on-surface-variant">{{ $b->supplier?->nama_supplier ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if ($low)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Menipis</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-12 text-center text-on-surface-variant">Belum ada bahan produksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $bahans->withQueryString()->links() }}</div>
        </section>
    </div>
</div>

@push('scripts')
<script>
    // Table search + filter
    document.querySelectorAll('[data-table-search]').forEach(input => {
        input.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            const filterVal = document.querySelector('[data-table-filter]')?.value || '';
            document.querySelectorAll('[data-table-row]').forEach(row => {
                const text = row.textContent.toLowerCase();
                const kategori = row.dataset.kategoriBahan || '';
                const matchSearch = text.includes(term);
                const matchFilter = !filterVal || kategori === filterVal;
                row.style.display = (matchSearch && matchFilter) ? '' : 'none';
            });
        });
    });
    document.querySelectorAll('[data-table-filter]').forEach(select => {
        select.addEventListener('change', function() {
            const searchInput = document.querySelector('[data-table-search]');
            if (searchInput) searchInput.dispatchEvent(new Event('input'));
        });
    });
</script>
@endpush
@endsection

@push('scripts')
<script>
    window.ralivaMaterials = [
        @foreach ($materials as $material)
            { id: {{ $material->production_material_id }}, nama: @json($material->nama_bahan), satuan: @json($material->satuan) },
        @endforeach
    ];

    const fillMaterialOptions = (select) => {
        select.innerHTML = '';
        window.ralivaMaterials.forEach((m) => {
            const opt = document.createElement('option');
            opt.value = m.id;
            opt.textContent = m.nama + ' (' + m.satuan + ')';
            select.appendChild(opt);
        });
    };

    const setStokModal = (jenis, materialId) => {
        const select = document.getElementById(jenis + '-select');
        fillMaterialOptions(select);
        const nama = document.getElementById(jenis + '-nama');
        if (materialId) {
            select.value = materialId;
            const mat = window.ralivaMaterials.find(m => m.id === materialId);
            nama.textContent = mat ? '— ' + mat.nama : '';
        } else {
            nama.textContent = '';
        }
    };

    document.querySelectorAll('[data-open-stok]').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.getElementById('modal-stok-' + btn.getAttribute('data-open-stok')).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setStokModal(btn.getAttribute('data-open-stok'), btn.getAttribute('data-material'));
        });
    });

    document.querySelectorAll('[data-modal-open="modal-stok-masuk"]').forEach((btn) => btn.addEventListener('click', () => setStokModal('masuk')));
    document.querySelectorAll('[data-modal-open="modal-stok-keluar"]').forEach((btn) => btn.addEventListener('click', () => setStokModal('keluar')));

    document.querySelectorAll('#masuk-select, #keluar-select').forEach((select) => {
        select.addEventListener('change', () => {
            const jenis = select.id.replace('-select', '');
            const mat = window.ralivaMaterials.find(m => m.id === Number(select.value));
            document.getElementById(jenis + '-nama').textContent = mat ? '— ' + mat.nama : '';
        });
    });
</script>
@endpush