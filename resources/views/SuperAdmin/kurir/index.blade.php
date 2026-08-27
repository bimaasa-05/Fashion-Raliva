@extends('layouts.superadmin')

@section('title', 'Kurir & Layanan')

@section('header-title', 'Kurir & Layanan')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola data kurir dan layanan pengiriman')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .layanan-list { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
    .layanan-list.expanded { max-height: 500px; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Toolbar -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">local_shipping</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Kurir & Layanan</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Kelola data kurir dan layanan pengiriman.</p>
            </div>
        </div>
        <button type="button" onclick="openKurirForm()" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Kurir
        </button>
    </section>

    <!-- Courier Grid -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Kurir</h2>
            <span class="text-on-surface-variant font-body-md text-sm">{{ $stats['aktif'] }} aktif • {{ $stats['layanan'] }} layanan • total {{ $stats['total'] }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            @forelse ($couriers as $kurir)
                <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5"
                    data-id="{{ $kurir->courier_id }}"
                    data-nama="{{ $kurir->nama_kurir }}"
                    data-kode="{{ $kurir->kode_kurir }}"
                    data-status="{{ $kurir->status }}">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-secondary text-[28px]">local_shipping</span>
                            </div>
                            @if ($kurir->status !== \App\Models\Courier::STATUS_AKTIF)
                                <span class="inline-flex px-1.5 py-0.5 rounded bg-error/10 text-error border border-error/20 text-[9px] font-bold uppercase">Non-aktif</span>
                            @else
                                <span class="inline-flex px-1.5 py-0.5 rounded bg-success/10 text-success border border-success/20 text-[9px] font-bold uppercase">Aktif</span>
                            @endif
                        </div>
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors mb-1">{{ $kurir->nama_kurir }}</h3>
                        <p class="text-on-surface-variant text-xs uppercase tracking-wider mb-2">Kode: {{ $kurir->kode_kurir }}</p>
                        <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                            <span class="material-symbols-outlined text-[16px]">route</span>
                            <span>{{ $kurir->services->count() }} layanan</span>
                        </div>
                    </div>

                    <!-- Layanan List (expandable) -->
                    @if ($kurir->services->count() > 0)
                        <div class="layanan-list mt-3 pt-3 border-t border-muted-border" id="layanan-{{ $kurir->courier_id }}">
                            @foreach ($kurir->services as $layanan)
                                <div class="flex items-center justify-between py-1.5 text-sm"
                                    data-layanan-id="{{ $layanan->shipping_service_id }}"
                                    data-layanan-nama="{{ $layanan->nama_layanan }}"
                                    data-layanan-estimasi="{{ $layanan->estimasi_hari }}"
                                    data-layanan-status="{{ $layanan->status }}">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[14px] text-on-surface-variant">radio_button_checked</span>
                                        <span class="text-on-surface">{{ $layanan->nama_layanan }}</span>
                                        @if ($layanan->estimasi_hari)
                                            <span class="text-on-surface-variant text-xs">({{ $layanan->estimasi_hari }})</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button type="button" onclick="openLayananForm(this.closest('[data-layanan-id]'), {{ $kurir->courier_id }})" class="p-1 rounded text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[14px]">edit</span>
                                        </button>
                                        <button type="button" onclick="openHapusLayanan(this.closest('[data-layanan-id]'))" class="p-1 rounded text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-[14px]">delete</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="layanan-list mt-3 pt-3 border-t border-muted-border" id="layanan-{{ $kurir->courier_id }}">
                            <p class="text-on-surface-variant/60 text-xs italic text-center py-2">Belum ada layanan</p>
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-4 border-t border-muted-border mt-4">
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="toggleLayanan({{ $kurir->courier_id }})" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Lihat Layanan">
                                <span class="material-symbols-outlined text-[20px]">expand_more</span>
                            </button>
                            <button type="button" onclick="openLayananForm(null, {{ $kurir->courier_id }})" class="p-2 rounded-lg text-on-surface-variant hover:text-success hover:bg-success/10 transition-colors" title="Tambah Layanan">
                                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                            </button>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="openKurirForm(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit Kurir">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            <button type="button" onclick="openHapusKurir(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus Kurir">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-12">Belum ada kurir. Tambahkan kurir pertama Anda.</p>
            @endforelse
        </div>
    </section>
</div>

<!-- Modal Form Kurir (Tambah/Edit) -->
<form method="POST" action="" id="kurir-form" onsubmit="closeKurirModal()">
    @csrf
    <div id="modal-form-kurir" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close onclick="closeKurirModal()"></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 id="kurir-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Kurir Baru</h3>
                    <p id="kurir-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Data kurir pengiriman.</p>
                </div>
                <button type="button" onclick="closeKurirModal()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="namaKurir">Nama Kurir</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="namaKurir" name="nama_kurir" type="text" maxlength="100" placeholder="JNE, J&T, SiCepat..." required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="kodeKurir">Kode Kurir</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 uppercase" id="kodeKurir" name="kode_kurir" type="text" maxlength="50" placeholder="JNE" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-3">Status</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="aktif" class="w-4 h-4 accent-gold-accent" checked />
                            <span class="text-sm text-on-surface">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="nonaktif" class="w-4 h-4 accent-gold-accent" />
                            <span class="text-sm text-on-surface">Non-aktif</span>
                        </label>
                    </div>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" onclick="closeKurirModal()" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" id="kurir-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Kurir</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Form Layanan (Tambah/Edit) -->
<form method="POST" action="" id="layanan-form" onsubmit="closeLayananModal()">
    @csrf
    <div id="modal-form-layanan" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close onclick="closeLayananModal()"></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 id="layanan-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Layanan</h3>
                    <p id="layanan-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Layanan pengiriman untuk kurir ini.</p>
                </div>
                <button type="button" onclick="closeLayananModal()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-5">
                <input type="hidden" name="courier_id" id="layananCourierId" value="" />
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="namaLayanan">Nama Layanan</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="namaLayanan" name="nama_layanan" type="text" maxlength="100" placeholder="Reg, Yes, Same Day..." required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="estimasiHari">Estimasi Pengiriman</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="estimasiHari" name="estimasi_hari" type="text" maxlength="50" placeholder="1-2 hari, 3 hari..." />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-3">Status</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="aktif" class="w-4 h-4 accent-gold-accent" checked />
                            <span class="text-sm text-on-surface">Aktif</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="nonaktif" class="w-4 h-4 accent-gold-accent" />
                            <span class="text-sm text-on-surface">Non-aktif</span>
                        </label>
                    </div>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" onclick="closeLayananModal()" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" id="layanan-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Layanan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Hapus Kurir -->
<form method="POST" action="" id="hapus-kurir-form" onsubmit="closeHapusModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="hapusKurirModal" onclick="if (event.target === this) closeHapusModal()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">delete_forever</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus Kurir</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Kurir <span id="hapus-nama" class="font-bold text-on-surface">-</span> beserta semua layanannya akan dihapus permanen.</p>
                <div id="hapus-warning" class="hidden mb-4"></div>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeHapusModal()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Hapus Layanan -->
<form method="POST" action="" id="hapus-layanan-form" onsubmit="closeHapusModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="hapusLayananModal" onclick="if (event.target === this) closeHapusModal()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">delete_forever</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus Layanan</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Layanan <span id="hapus-layanan-nama" class="font-bold text-on-surface">-</span> akan dihapus permanen.</p>
                <div id="hapus-layanan-warning" class="hidden mb-4"></div>
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
    const kurirUrls = {
        store: '{{ route('superadmin.kurir.store') }}',
        update: (id) => '{{ route('superadmin.kurir.update', ':id:') }}'.replace(':id:', id),
        hapus: (id) => '{{ route('superadmin.kurir.hapus', ':id:') }}'.replace(':id:', id),
        storeLayanan: '{{ route('superadmin.kurir.layanan.store') }}',
        updateLayanan: (id) => '{{ route('superadmin.kurir.layanan.update', ':id:') }}'.replace(':id:', id),
        hapusLayanan: (id) => '{{ route('superadmin.kurir.layanan.hapus', ':id:') }}'.replace(':id:', id)
    };

    function toggleLayanan(courierId) {
        const el = document.getElementById('layanan-' + courierId);
        if (el) el.classList.toggle('expanded');
    }

    function openKurirForm(card = null) {
        const isEdit = !!card;
        const form = document.getElementById('kurir-form');

        if (isEdit) {
            const d = card.dataset;
            document.getElementById('kurir-modal-title').textContent = 'Ubah Kurir';
            document.getElementById('kurir-modal-sub').textContent = 'Perubahan berlaku untuk semua layanan.';
            document.getElementById('namaKurir').value = d.nama;
            document.getElementById('kodeKurir').value = d.kode;
            document.querySelector('#modal-form-kurir input[name="status"][value="' + d.status + '"]').checked = true;
            form.action = kurirUrls.update(d.id);
            document.getElementById('kurir-submit-btn').textContent = 'Simpan Perubahan';
        } else {
            document.getElementById('kurir-modal-title').textContent = 'Tambah Kurir Baru';
            document.getElementById('kurir-modal-sub').textContent = 'Data kurir pengiriman.';
            document.getElementById('namaKurir').value = '';
            document.getElementById('kodeKurir').value = '';
            document.querySelector('#modal-form-kurir input[name="status"][value="aktif"]').checked = true;
            form.action = kurirUrls.store;
            document.getElementById('kurir-submit-btn').textContent = 'Tambah Kurir';
        }

        document.getElementById('modal-form-kurir').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('namaKurir').focus(), 100);
    }

    function closeKurirModal() {
        document.getElementById('modal-form-kurir').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openLayananForm(el, courierId) {
        const isEdit = !!el;
        const form = document.getElementById('layanan-form');

        document.getElementById('layananCourierId').value = courierId;

        if (isEdit) {
            const d = el.dataset;
            document.getElementById('layanan-modal-title').textContent = 'Ubah Layanan';
            document.getElementById('layanan-modal-sub').textContent = 'Perubahan berlaku untuk pengiriman mendatang.';
            document.getElementById('namaLayanan').value = d.layananNama;
            document.getElementById('estimasiHari').value = d.layananEstimasi || '';
            document.querySelector('#modal-form-layanan input[name="status"][value="' + d.layananStatus + '"]').checked = true;
            form.action = kurirUrls.updateLayanan(d.layananId);
            document.getElementById('layanan-submit-btn').textContent = 'Simpan Perubahan';
        } else {
            document.getElementById('layanan-modal-title').textContent = 'Tambah Layanan';
            document.getElementById('layanan-modal-sub').textContent = 'Layanan pengiriman untuk kurir ini.';
            document.getElementById('namaLayanan').value = '';
            document.getElementById('estimasiHari').value = '';
            document.querySelector('#modal-form-layanan input[name="status"][value="aktif"]').checked = true;
            form.action = kurirUrls.storeLayanan;
            document.getElementById('layanan-submit-btn').textContent = 'Tambah Layanan';
        }

        document.getElementById('modal-form-layanan').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('namaLayanan').focus(), 100);
    }

    function closeLayananModal() {
        document.getElementById('modal-form-layanan').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openHapusKurir(card) {
        const d = card.dataset;
        document.getElementById('hapus-nama').textContent = d.nama;
        document.getElementById('hapus-warning').className = 'hidden';

        document.getElementById('hapus-kurir-form').action = kurirUrls.hapus(d.id);
        const modal = document.getElementById('hapusKurirModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function openHapusLayanan(el) {
        const d = el.dataset;
        document.getElementById('hapus-layanan-nama').textContent = d.layananNama;
        document.getElementById('hapus-layanan-warning').className = 'hidden';

        document.getElementById('hapus-layanan-form').action = kurirUrls.hapusLayanan(d.layananId);
        const modal = document.getElementById('hapusLayananModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeHapusModal() {
        ['hapusKurirModal', 'hapusLayananModal'].forEach(id => {
            const m = document.getElementById(id);
            m.classList.add('hidden');
            m.classList.remove('flex');
        });
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeKurirModal(); closeLayananModal(); closeHapusModal(); }
    });
</script>
@endpush
