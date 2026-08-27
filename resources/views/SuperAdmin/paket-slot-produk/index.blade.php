@extends('layouts.superadmin')

@section('title', 'Paket Slot Produk')

@section('header-title', 'Paket Slot Produk')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola paket langganan slot produk untuk seluruh toko')

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
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">inventory_2</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Paket Slot Produk</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Kelola paket langganan slot produk.</p>
            </div>
        </div>
        <button type="button" onclick="openPaketForm()" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Paket
        </button>
    </section>

    <!-- Packages Grid -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Paket</h2>
            <span class="text-on-surface-variant font-body-md text-sm">{{ $stats['aktif'] }} aktif • {{ $stats['nonaktif'] }} nonaktif • total {{ $stats['total'] }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-gutter">
            @forelse ($packages as $paket)
                <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5"
                    data-id="{{ $paket->slot_package_id }}"
                    data-nama="{{ $paket->nama_paket }}"
                    data-harga="{{ $paket->harga }}"
                    data-slot="{{ $paket->jumlah_slot }}"
                    data-durasi="{{ $paket->durasi_hari }}"
                    data-status="{{ $paket->status }}">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-secondary text-[28px]">{{ $paket->jumlah_slot >= 999 ? 'all_inclusive' : 'inventory_2' }}</span>
                            </div>
                            @if ($paket->status !== \App\Models\ProductSlotPackage::STATUS_AKTIF)
                                <span class="inline-flex px-1.5 py-0.5 rounded bg-error/10 text-error border border-error/20 text-[9px] font-bold uppercase">Non-aktif</span>
                            @else
                                <span class="inline-flex px-1.5 py-0.5 rounded bg-success/10 text-success border border-success/20 text-[9px] font-bold uppercase">Aktif</span>
                            @endif
                        </div>
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors mb-1">{{ $paket->nama_paket }}</h3>
                        <div class="flex items-baseline gap-1 mb-3">
                            <span class="font-headline-lg text-headline-lg text-on-surface">Rp {{ number_format($paket->harga, 0, ',', '.') }}</span>
                            <span class="text-on-surface-variant text-xs">/{{ $paket->durasi_hari >= 365 ? 'selamanya' : $paket->durasi_hari . ' hari' }}</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-[16px]">package_2</span>
                                <span>{{ $paket->jumlah_slot >= 999 ? 'Slot tanpa batas' : $paket->jumlah_slot . ' slot produk' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-[16px]">storefront</span>
                                <span>{{ $paket->subscriptions_count }} subscriber aktif</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border mt-4">
                        <button type="button" onclick="openPaketForm(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button type="button" onclick="openHapusPaket(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-12">Belum ada paket slot. Tambahkan paket pertama Anda.</p>
            @endforelse
        </div>
    </section>
</div>

<!-- Modal Form Paket (Tambah/Edit) -->
<form method="POST" action="" id="paket-form" onsubmit="closePaketModal()">
    @csrf
    <div id="modal-form-paket" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close onclick="closePaketModal()"></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 id="paket-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Paket Baru</h3>
                    <p id="paket-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Paket slot untuk langganan toko.</p>
                </div>
                <button type="button" onclick="closePaketModal()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="namaPaket">Nama Paket</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="namaPaket" name="nama_paket" type="text" maxlength="100" placeholder="Misal: Starter, Growth, Pro" required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="hargaPaket">Harga/Bulan (Rp)</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="hargaPaket" name="harga" type="number" min="0" max="999999999" placeholder="0" required />
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="jumlahSlot">Jumlah Slot</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="jumlahSlot" name="jumlah_slot" type="number" min="1" max="99999" placeholder="25" required />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="durasiHari">Durasi (hari)</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="durasiHari" name="durasi_hari" type="number" min="1" max="3650" placeholder="30" required />
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
                    <button type="button" onclick="closePaketModal()" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" id="paket-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Paket</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Hapus Paket -->
<form method="POST" action="" id="hapus-paket-form" onsubmit="closeHapusModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="hapusPaketModal" onclick="if (event.target === this) closeHapusModal()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">delete_forever</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus Paket</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Paket <span id="hapus-nama" class="font-bold text-on-surface">-</span> akan dihapus permanen.</p>
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
    const paketUrls = {
        store: '{{ route('superadmin.paket-slot-produk.store') }}',
        update: (id) => '{{ route('superadmin.paket-slot-produk.update', ':id:') }}'.replace(':id:', id),
        hapus: (id) => '{{ route('superadmin.paket-slot-produk.hapus', ':id:') }}'.replace(':id:', id)
    };

    function openPaketForm(card = null) {
        const isEdit = !!card;
        const form = document.getElementById('paket-form');

        if (isEdit) {
            const d = card.dataset;
            document.getElementById('paket-modal-title').textContent = 'Ubah Paket';
            document.getElementById('paket-modal-sub').textContent = 'Perubahan berlaku untuk semua subscriber.';
            document.getElementById('namaPaket').value = d.nama;
            document.getElementById('hargaPaket').value = d.harga;
            document.getElementById('jumlahSlot').value = d.slot;
            document.getElementById('durasiHari').value = d.durasi;
            document.querySelector('input[name="status"][value="' + d.status + '"]').checked = true;
            form.action = paketUrls.update(d.id);
            document.getElementById('paket-submit-btn').textContent = 'Simpan Perubahan';
        } else {
            document.getElementById('paket-modal-title').textContent = 'Tambah Paket Baru';
            document.getElementById('paket-modal-sub').textContent = 'Paket slot untuk langganan toko.';
            document.getElementById('namaPaket').value = '';
            document.getElementById('hargaPaket').value = '';
            document.getElementById('jumlahSlot').value = '';
            document.getElementById('durasiHari').value = '';
            document.querySelector('input[name="status"][value="aktif"]').checked = true;
            form.action = paketUrls.store;
            document.getElementById('paket-submit-btn').textContent = 'Tambah Paket';
        }

        document.getElementById('modal-form-paket').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('namaPaket').focus(), 100);
    }

    function closePaketModal() {
        document.getElementById('modal-form-paket').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openHapusPaket(card) {
        const d = card.dataset;
        const warningBox = document.getElementById('hapus-warning');
        document.getElementById('hapus-nama').textContent = d.nama;

        const subscriberCount = parseInt(d.id) ? 0 : 0;

        if (subscriberCount > 0) {
            warningBox.className = 'mb-4 bg-error/5 border border-error/25 rounded-lg p-3 text-xs text-on-surface';
            warningBox.textContent = 'Paket ini masih memiliki ' + subscriberCount + ' subscriber aktif. Penghapusan akan ditolak sistem.';
        } else {
            warningBox.className = 'hidden';
            warningBox.textContent = '';
        }

        document.getElementById('hapus-paket-form').action = paketUrls.hapus(d.id);
        const modal = document.getElementById('hapusPaketModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeHapusModal() {
        const modal = document.getElementById('hapusPaketModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closePaketModal(); closeHapusModal(); }
    });
</script>
@endpush
