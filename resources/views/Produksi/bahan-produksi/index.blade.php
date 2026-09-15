@extends('layouts.produksi')

@section('title', 'Bahan Produksi')

@section('header-title', 'Bahan Produksi')
@section('header-badge', 'Stok Gudang')
@section('header-subtitle', 'Kelola stok bahan baku di penyimpanan gudang — pemasukan dan pengeluaran tercatat otomatis.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-64 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Jenis Bahan</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ number_format($stats['jenis'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">bahan tercatat</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">inventory</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Stok Menipis</span>
            <span class="raliva-figure text-[26px] {{ $stats['menipis'] > 0 ? 'text-error' : 'text-secondary' }}">{{ number_format($stats['menipis'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">pada / di bawah minimum</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">warning</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pengeluaran Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ number_format($stats['pemakaian'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">dipakai produksi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">outbound</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pemasukan Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ number_format($stats['pemasukan'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">stok masuk gudang</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">inbound</span>
        </div>
    </section>

    {{-- Tombol aksi --}}
    <section data-reveal class="flex flex-wrap items-center gap-gutter">
        <button type="button" data-modal-open="modal-tambah-bahan" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">
            <span class="material-symbols-outlined text-[18px]">add</span>Tambah Bahan
        </button>
        <button type="button" data-modal-open="modal-stok-masuk" class="flex items-center justify-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">
            <span class="material-symbols-outlined text-[18px] text-secondary">move_to_inbox</span>Pemasukan Stok
        </button>
        <button type="button" data-modal-open="modal-stok-keluar" class="flex items-center justify-center gap-2 px-5 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">outbox</span>Pengeluaran Stok
        </button>
    </section>

    {{-- Daftar Bahan --}}
    <section data-reveal-group class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-section-gap">
        @forelse ($materials as $material)
            @php $menipis = $material->stok_sekarang <= $material->minimal_stok; @endphp
            <article data-reveal class="bg-surface-container-lowest border {{ $menipis ? 'border-error/25' : 'border-muted-border' }} rounded-lg p-5 card-premium flex flex-col gap-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gold-accent">inventory_2</span>
                    </div>
                    @if ($menipis)
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Menipis</span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aman</span>
                    @endif
                </div>
                <div>
                    <p class="font-title-md text-sm text-on-surface">{{ $material->nama_bahan }}</p>
                    <p class="text-xs text-on-surface-variant mt-1">Stok <span class="font-bold {{ $menipis ? 'text-error' : 'text-on-surface' }}">{{ number_format($material->stok_sekarang, 0, ',', '.') }}</span> {{ $material->satuan }} • Min. {{ number_format($material->minimal_stok, 0, ',', '.') }} {{ $material->satuan }}</p>
                    @if ($material->keterangan)
                        <p class="text-xs text-on-surface-variant/70 mt-1">{{ $material->keterangan }}</p>
                    @endif
                </div>
                <div class="flex gap-gutter mt-auto">
                    <button type="button" data-open-stok="masuk" data-material="masuk-{{ $material->production_material_id }}" data-nama="{{ $material->nama_bahan }}" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-secondary hover:text-secondary transition-colors">Pemasukan</button>
                    <button type="button" data-open-stok="keluar" data-material="keluar-{{ $material->production_material_id }}" data-nama="{{ $material->nama_bahan }}" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">Pengeluaran</button>
                </div>
            </article>
        @empty
            <div data-reveal class="md:col-span-2 xl:col-span-3 text-center py-12">
                <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-[28px] text-on-surface-variant">inventory_2</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-3">Belum ada bahan. Klik <span class="font-bold text-on-surface">Tambah Bahan</span> untuk merekam stok bahan baku.</p>
            </div>
        @endforelse
    </section>

    @if ($materials->hasPages())
        <div class="flex justify-center">{{ $materials->links() }}</div>
    @endif

    {{-- Transaksi Pengeluaran & Pemasukan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Transaksi Pengeluaran & Pemasukan</h2>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Seluruh mutasi stok bahan — pengeluaran (dipakai produksi) dan pemasukan (stok diterima).</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="premium-table w-full min-w-[760px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Waktu</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bahan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Jenis</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Sumber / Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $mv)
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $mv->created_at ? $mv->created_at->format('d M H:i') : '-' }}</td>
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $mv->material?->nama_bahan ?? '-' }}</td>
                            <td class="py-3.5 px-4">
                                @if ($mv->tipe === 'masuk')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Pemasukan</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold {{ $mv->tipe === 'masuk' ? 'text-secondary' : 'text-error' }} whitespace-nowrap">
                                {{ $mv->tipe === 'masuk' ? '+' : '-' }}{{ number_format($mv->jumlah, 0, ',', '.') }} {{ $mv->material?->satuan }}
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant max-w-[280px]">
                                {{ $mv->productionOrder?->nomor_produksi ? $mv->productionOrder->nomor_produksi.' — ' : '' }}{{ $mv->alasan ?: '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <p class="text-on-surface-variant font-body-md text-sm">Belum ada transaksi bahan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

{{-- Modal Tambah Bahan --}}
<div id="modal-tambah-bahan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-12 w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border z-10">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Bahan</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Rekam bahan baku baru untuk penyimpanan gudang.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('produksi.bahan-produksi.store') }}" class="p-6 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-gutter">
                <div class="col-span-2">
                    <label class="block raliva-label mb-2">Nama Bahan</label>
                    <input name="nama_bahan" type="text" required placeholder="cth. Kain Katun Premium" class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Satuan</label>
                    <input name="satuan" type="text" required placeholder="meter / roll / pcs" class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Stok Awal <span class="text-on-surface-variant/60">(opsional)</span></label>
                    <input name="stok_awal" type="number" min="0" step="any" placeholder="0" class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Minimal Stok <span class="text-on-surface-variant/60">(opsional)</span></label>
                    <input name="minimal_stok" type="number" min="0" step="any" placeholder="0" class="raliva-input" />
                </div>
                <div class="col-span-2">
                    <label class="block raliva-label mb-2">Keterangan <span class="text-on-surface-variant/60">(opsional)</span></label>
                    <textarea name="keterangan" rows="2" placeholder="cth. Kain utama untuk blazer..." class="raliva-textarea"></textarea>
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">save</span>Simpan Bahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Pemasukan Stok --}}
<div id="modal-stok-masuk" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-12 w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border z-10">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Pemasukan Stok</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Tambah stok bahan <span id="masuk-nama" class="font-bold text-on-surface"></span>.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('produksi.bahan-produksi.tambah-stok') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Bahan</label>
                <select id="masuk-select" name="production_material_id" class="raliva-select" required></select>
            </div>
            <div>
                <label class="block raliva-label mb-2">Jumlah</label>
                <input name="jumlah" type="number" min="0.01" step="any" required placeholder="0" class="raliva-input" />
            </div>
            <div>
                <label class="block raliva-label mb-2">Keterangan <span class="text-on-surface-variant/60">(opsional)</span></label>
                <textarea name="alasan" rows="2" placeholder="cth. Pembelian dari supplier..." class="raliva-textarea"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">move_to_inbox</span>Catat Pemasukan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Pengeluaran Stok --}}
<div id="modal-stok-keluar" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-12 w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border z-10">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Pengeluaran Stok</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Kurangi stok bahan <span id="keluar-nama" class="font-bold text-on-surface"></span>.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('produksi.bahan-produksi.pakai') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Bahan</label>
                <select id="keluar-select" name="production_material_id" class="raliva-select" required></select>
            </div>
            <div>
                <label class="block raliva-label mb-2">Jumlah</label>
                <input name="jumlah" type="number" min="0.01" step="any" required placeholder="0" class="raliva-input" />
            </div>
            <div>
                <label class="block raliva-label mb-2">Keterangan <span class="text-on-surface-variant/60">(opsional)</span></label>
                <textarea name="alasan" rows="2" placeholder="cth. Bahan terbuang / penyesuaian..." class="raliva-textarea"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">outbox</span>Catat Pengeluaran
                </button>
            </div>
        </form>
    </div>
</div>
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