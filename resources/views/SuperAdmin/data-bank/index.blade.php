@extends('layouts.superadmin')

@section('title', 'Data Bank')
@section('header-title', 'Data Bank')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Daftar bank yang didukung platform untuk pencairan dana')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .modal-enter { opacity: 0; transform: scale(0.95); }
    .modal-enter-active { opacity: 1; transform: scale(1); transition: opacity 300ms, transform 300ms; }
    .modal-exit { opacity: 1; transform: scale(1); }
    .modal-exit-active { opacity: 0; transform: scale(0.95); transition: opacity 200ms, transform 200ms; }
</style>
@endpush

@section('content')
<div class="space-y-section-gap">
    <!-- Toolbar -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">account_balance</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Data Bank</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Bank yang didukung untuk pencairan dana.</p>
            </div>
        </div>
        <button type="button" data-modal-open="modal-tambah-bank" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Bank
        </button>
    </section>

    <!-- Banks List -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight premium-heading">Daftar Bank</h2>
            <span class="text-on-surface-variant font-body-md text-sm">3 bank terdaftar</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            <!-- Bank Card 1 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[28px]">account_balance</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">BCA</h3>
                        <p class="text-on-surface-variant text-sm mt-1">Rekening: 1234567890</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined text-[10px]">check_circle</span>
                        Aktif
                    </span>
                    <button type="button" onclick="editBank(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                </div>
            </div>

            <!-- Bank Card 2 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[28px]">account_balance</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">Mandiri</h3>
                        <p class="text-on-surface-variant text-sm mt-1">Rekening: 9876543210</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">
                        <span class="material-symbols-outlined text-[10px]">check_circle</span>
                        Aktif
                    </span>
                    <button type="button" onclick="editBank(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                </div>
            </div>

            <!-- Bank Card 3 -->
            <div class="group relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl p-6 transition-all duration-300 hover:border-gold-accent hover:shadow-lg hover:-translate-y-0.5 cursor-pointer">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <div class="relative flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-gold-accent/10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-gold-accent text-[28px]">account_balance</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-title-md text-title-md text-on-surface group-hover:text-gold-accent transition-colors truncate">BRI</h3>
                        <p class="text-on-surface-variant text-sm mt-1">Rekening: 5555444433</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-warning/20 text-warning text-[10px] font-bold uppercase border border-warning/20">
                        <span class="material-symbols-outlined text-[10px]">hourglass_top</span>
                        Verifikasi
                    </span>
                    <button type="button" onclick="editBank(this)" class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" title="Edit">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Form Bank -->
<div id="modal-tambah-bank" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 id="bank-modal-title" class="font-title-md text-title-md text-on-surface premium-heading">Tambah Bank Baru</h3>
                <p id="bank-modal-sub" class="text-on-surface-variant font-body-md text-sm mt-1">Bank didukung untuk pencairan dana toko.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="add-bank-form" data-toast-message="Rekening bank berhasil disimpan." class="p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="bankName">Nama Bank</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="bankName" name="bankName" type="text" placeholder="Misal: BCA, Mandiri, BRI" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="accountNumber">No. Rekening</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="accountNumber" name="accountNumber" type="text" placeholder="No. rekening" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="accountName">Nama Pemilik</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="accountName" name="accountName" type="text" placeholder="Nama pemilik rekening" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Jenis Rekening</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low cursor-pointer transition-colors has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                            <input type="radio" class="w-4 h-4 accent-gold-accent" name="accountType" value="checking" checked />
                            <span class="font-body-md text-sm text-on-surface">Cek</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-muted-border rounded-lg hover:bg-surface-container-low cursor-pointer transition-colors has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10">
                            <input type="radio" class="w-4 h-4 accent-gold-accent" name="accountType" value="savings" />
                            <span class="font-body-md text-sm text-on-surface">Tabungan</span>
                        </label>
                    </div>
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Status</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="status" value="active" checked />
                        Aktif
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="status" value="inactive" />
                        Non-aktif
                    </label>
                    <label class="flex items-center justify-center px-4 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="status" value="verification" />
                        Verifikasi
                    </label>
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" id="bank-submit-btn" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Bank</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const editBank = (btn) => {
        const card = btn.closest('.group');
        document.getElementById('bankName').value = card?.querySelector('h3')?.textContent.trim() || '';
        document.getElementById('accountNumber').value = (card?.querySelector('p.text-sm, p.text-on-surface-variant.text-sm')?.textContent || '').replace('Rekening:', '').trim();
        document.getElementById('bank-modal-title').textContent = 'Ubah Data Bank';
        document.getElementById('bank-modal-sub').textContent = 'Perbarui informasi rekening bank.';
        document.getElementById('bank-submit-btn').textContent = 'Simpan Perubahan';
        document.getElementById('add-bank-form').setAttribute('data-toast-message', 'Perubahan rekening bank berhasil disimpan.');
        document.getElementById('modal-tambah-bank')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };
</script>
@endpush