@extends('layouts.superadmin')

@section('title', 'Data Bank')

@section('header-title', 'Data Bank')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola data rekening bank platform untuk pencairan dana')

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
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">account_balance</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Data Bank</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Kelola rekening bank platform.</p>
            </div>
        </div>
        <button type="button" onclick="openBankForm()" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Bank
        </button>
    </section>

    <!-- Bank Grid -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Bank</h2>
            <span class="text-on-surface-variant font-body-md text-sm">{{ $stats['aktif'] }} aktif • total {{ $stats['total'] }} bank</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            @forelse ($banks as $bank)
                @php $rekening = $bank->platformBankAccounts->first(); @endphp
                <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5"
                    data-id="{{ $bank->bank_id }}"
                    data-nama="{{ $bank->nama_bank }}"
                    data-kode="{{ $bank->kode_bank }}"
                    data-rekening="{{ $rekening?->nomor_rekening ?? '' }}"
                    data-pemilik="{{ $rekening?->nama_pemilik ?? '' }}"
                    data-status="{{ $bank->status }}">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-secondary-container/20 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                    <div class="relative">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-14 h-14 rounded-full bg-secondary-container flex items-center justify-center group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-secondary text-[28px]">account_balance</span>
                            </div>
                            @if ($bank->status !== \App\Models\Bank::STATUS_AKTIF)
                                <span class="inline-flex px-1.5 py-0.5 rounded bg-error/10 text-error border border-error/20 text-[9px] font-bold uppercase">Non-aktif</span>
                            @else
                                <span class="inline-flex px-1.5 py-0.5 rounded bg-success/10 text-success border border-success/20 text-[9px] font-bold uppercase">Aktif</span>
                            @endif
                        </div>
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors mb-1">{{ $bank->nama_bank }}</h3>
                        @if ($rekening)
                            <div class="space-y-1.5 mt-3">
                                <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px]">credit_card</span>
                                    <span class="font-mono">{{ $rekening->nomor_rekening }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px]">person</span>
                                    <span>{{ $rekening->nama_pemilik }}</span>
                                </div>
                            </div>
                        @else
                            <p class="text-on-surface-variant/60 text-sm mt-2 italic">Belum ada rekening platform</p>
                        @endif
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-muted-border mt-4">
                        <button type="button" onclick="openBankForm(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>
                        <button type="button" onclick="openHapusBank(this.closest('[data-id]'))" class="p-2 rounded-lg text-on-surface-variant hover:text-error hover:bg-error/10 transition-colors" title="Hapus">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-12">Belum ada data bank. Tambahkan bank pertama Anda.</p>
            @endforelse
        </div>
    </section>
</div>

<!-- Modal Form Bank (Tambah/Edit) -->
<form method="POST" action="" id="bank-form" onsubmit="closeBankModal()">
    @csrf
    <div id="modal-form-bank" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close onclick="closeBankModal()"></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 id="bank-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Bank Baru</h3>
                    <p id="bank-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Data rekening bank platform.</p>
                </div>
                <button type="button" onclick="closeBankModal()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="namaBank">Nama Bank</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="namaBank" name="nama_bank" type="text" maxlength="100" placeholder="BCA, Mandiri, BRI..." required />
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="kodeBank">Kode Bank</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 uppercase" id="kodeBank" name="kode_bank" type="text" maxlength="20" placeholder="BCA" required />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nomorRekening">Nomor Rekening</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 font-mono" id="nomorRekening" name="nomor_rekening" type="text" maxlength="50" placeholder="1234567890" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="namaPemilik">Nama Pemilik Rekening</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="namaPemilik" name="nama_pemilik" type="text" maxlength="150" placeholder="PT Raliva Fashion" required />
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
                    <button type="button" onclick="closeBankModal()" class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" id="bank-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tambah Bank</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Hapus Bank -->
<form method="POST" action="" id="hapus-bank-form" onsubmit="closeHapusModal()">
    @csrf
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="hapusBankModal" onclick="if (event.target === this) closeHapusModal()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">delete_forever</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus Bank</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Bank <span id="hapus-nama" class="font-bold text-on-surface">-</span> akan dihapus permanen beserta rekening platform-nya.</p>
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
    const bankUrls = {
        store: '{{ route('superadmin.data-bank.store') }}',
        update: (id) => '{{ route('superadmin.data-bank.update', ':id:') }}'.replace(':id:', id),
        hapus: (id) => '{{ route('superadmin.data-bank.hapus', ':id:') }}'.replace(':id:', id)
    };

    function openBankForm(card = null) {
        const isEdit = !!card;
        const form = document.getElementById('bank-form');

        if (isEdit) {
            const d = card.dataset;
            document.getElementById('bank-modal-title').textContent = 'Ubah Bank';
            document.getElementById('bank-modal-sub').textContent = 'Perubahan berlaku pada seluruh transaksi.';
            document.getElementById('namaBank').value = d.nama;
            document.getElementById('kodeBank').value = d.kode;
            document.getElementById('nomorRekening').value = d.rekening;
            document.getElementById('namaPemilik').value = d.pemilik;
            document.querySelector('input[name="status"][value="' + d.status + '"]').checked = true;
            form.action = bankUrls.update(d.id);
            document.getElementById('bank-submit-btn').textContent = 'Simpan Perubahan';
        } else {
            document.getElementById('bank-modal-title').textContent = 'Tambah Bank Baru';
            document.getElementById('bank-modal-sub').textContent = 'Data rekening bank platform.';
            document.getElementById('namaBank').value = '';
            document.getElementById('kodeBank').value = '';
            document.getElementById('nomorRekening').value = '';
            document.getElementById('namaPemilik').value = '';
            document.querySelector('input[name="status"][value="aktif"]').checked = true;
            form.action = bankUrls.store;
            document.getElementById('bank-submit-btn').textContent = 'Tambah Bank';
        }

        document.getElementById('modal-form-bank').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('namaBank').focus(), 100);
    }

    function closeBankModal() {
        document.getElementById('modal-form-bank').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openHapusBank(card) {
        const d = card.dataset;
        document.getElementById('hapus-nama').textContent = d.nama;
        document.getElementById('hapus-warning').className = 'hidden';

        document.getElementById('hapus-bank-form').action = bankUrls.hapus(d.id);
        const modal = document.getElementById('hapusBankModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeHapusModal() {
        const modal = document.getElementById('hapusBankModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { closeBankModal(); closeHapusModal(); }
    });
</script>
@endpush
