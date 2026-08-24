@extends('layouts.superadmin')

@section('title', 'Pajak & Biaya')

@section('header-title', 'Pajak & Biaya Layanan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola biaya global dan pajak yang ditampilkan saat checkout')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
<div class="space-y-section-gap">
    <!-- Dua Kartu Terpisah: Komisi Raliva vs Pajak -->
    <section class="space-y-gutter">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">request_quote</span></div>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Komisi vs Pajak</h2>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Dua potongan berbeda — jangan tertukar: komisi adalah pendapatan platform, pajak adalah hak negara.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
            <!-- Komisi Raliva (emas) -->
            <div class="relative overflow-hidden bg-gradient-to-br from-gold-accent/10 to-gold-accent/5 border border-gold-accent/30 rounded-xl p-6 md:p-8 transition-all duration-300 hover:border-gold-accent hover:shadow-xl">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-gold-accent/15 to-transparent rounded-full -translate-y-12 translate-x-12" style="filter: blur(25px); opacity: 0.6;"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/15 border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-widest text-gold-accent">
                            <span class="material-symbols-outlined text-[14px]">percent</span> Pendapatan Platform
                        </span>
                        <span class="material-symbols-outlined text-gold-accent fill text-[24px]" aria-hidden="true">currency_exchange</span>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Komisi Raliva</span>
                    <div class="flex items-baseline gap-2">
                        <span class="font-display-lg text-5xl md:text-6xl tracking-tight text-gradient-gold">5</span>
                        <span class="font-display-lg text-3xl md:text-4xl text-gold-accent/70 self-end mb-1">%</span>
                    </div>
                    <p class="font-body-md text-sm text-on-surface-variant">Potongan dari setiap transaksi toko yang menjadi pendapatan bersih Raliva. Dikelola di halaman Komisi Global.</p>
                    <a href="{{ route('superadmin.komisi-global') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium self-start mt-1">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span> Kelola Komisi
                    </a>
                </div>
            </div>

            <!-- Pajak Penjualan (secondary/netral) -->
            <div class="relative overflow-hidden bg-gradient-to-br from-secondary-container/20 to-transparent border border-secondary/30 rounded-xl p-6 md:p-8 transition-all duration-300 hover:border-secondary hover:shadow-xl">
                <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-secondary-container/30 to-transparent rounded-full -translate-y-12 translate-x-12" style="filter: blur(25px); opacity: 0.5;"></div>
                <div class="relative flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/25 border border-secondary/30 font-label-sm text-[10px] uppercase tracking-widest text-secondary">
                            <span class="material-symbols-outlined text-[14px]">account_balance</span> Hak Negara — Bukan Pendapatan Raliva
                        </span>
                        <span class="material-symbols-outlined text-secondary fill text-[24px]" aria-hidden="true">receipt_long</span>
                    </div>
                    <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">Pajak Penjualan (PPN)</span>
                    <div class="flex items-baseline gap-2">
                        <span class="font-display-lg text-5xl md:text-6xl tracking-tight text-on-surface">11</span>
                        <span class="font-display-lg text-3xl md:text-4xl text-secondary self-end mb-1">%</span>
                    </div>
                    <p class="font-body-md text-sm text-on-surface-variant">PPN sesuai tarif yang berlaku, dipungut atas nama pemerintah lalu disetor — tidak masuk kas Raliva maupun toko.</p>
                    <button type="button" data-modal-open="modal-edit-biaya" class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-secondary text-secondary font-label-sm text-[11px] uppercase tracking-widest rounded hover:bg-secondary/10 transition-colors self-start mt-1">
                        <span class="material-symbols-outlined text-[18px]">edit</span> Ubah Pajak
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Simulasi Dua Jalur -->
    <section class="space-y-gutter" data-reveal>
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">calculate</span></div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Simulasi Transaksi Rp 100.000</h2>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter">
            <!-- Jalur Pelanggan -->
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
                <p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant mb-4 inline-flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span> Jalur Pelanggan — yang dibayar pembeli
                </p>
                <ul class="divide-y divide-muted-border">
                    <li class="flex items-center justify-between py-3"><span class="font-body-md text-sm text-on-surface">Subtotal barang</span><span class="font-title-md text-title-md text-on-surface">Rp 100.000</span></li>
                    <li class="flex items-center justify-between py-3"><span class="font-body-md text-sm text-secondary inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">account_balance</span>PPN 11% <em class="not-italic text-on-surface-variant text-xs">(pajak)</em></span><span class="font-title-md text-title-md text-secondary">+ Rp 11.000</span></li>
                    <li class="flex items-center justify-between pt-3 border-t border-muted-border"><span class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Total Dibayar Pelanggan</span><span class="font-headline-lg-mobile text-headline-lg-mobile text-deep-onyx">Rp 111.000</span></li>
                </ul>
            </div>
            <!-- Jalur Platform -->
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-8 translate-x-8" style="filter: blur(20px); opacity: 0.5;"></div>
                <p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant mb-4 inline-flex items-center gap-2 relative">
                    <span class="w-2 h-2 rounded-full bg-gold-accent"></span> Jalur Platform — pembagian hasil transaksi
                </p>
                <ul class="divide-y divide-muted-border relative">
                    <li class="flex items-center justify-between py-3"><span class="font-body-md text-sm text-on-surface">Nilai transaksi (subtotal)</span><span class="font-title-md text-title-md text-on-surface">Rp 100.000</span></li>
                    <li class="flex items-center justify-between py-3"><span class="font-body-md text-sm text-gold-accent inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">percent</span>Komisi Raliva 5% <em class="not-italic text-on-surface-variant text-xs">(pendapatan platform)</em></span><span class="font-title-md text-title-md text-gold-accent">Rp 5.000</span></li>
                    <li class="flex items-center justify-between py-3"><span class="font-body-md text-sm text-on-surface-variant">Penerima bersih (toko)</span><span class="font-title-md text-title-md text-on-surface">Rp 95.000</span></li>
                    <li class="flex items-center justify-between pt-3 border-t border-muted-border"><span class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Catatan</span><span class="text-right text-xs text-on-surface-variant max-w-[55%]">Pajak disetor ke negara; komisi masuk kas Raliva.</span></li>
                </ul>
            </div>
        </div>
        <div class="flex items-start gap-3 p-4 border border-error/30 bg-error-container/60 rounded-lg">
            <span class="material-symbols-outlined text-error mt-0.5 text-[20px]">warning</span>
            <p class="font-body-md text-xs text-on-error-container">Jangan mencampur komisi dengan pajak pada laporan keuangan — komisi ialah pendapatan kotor platform, sedangkan PPN adalah dana titipan yang wajib disetorkan.</p>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    function closeEditFeeModal() {
        document.getElementById('modal-edit-biaya').classList.add('hidden');
        document.getElementById('edit-fee-form').reset();
        updateFeePreview(11);
        document.body.style.overflow = '';
    }

    function cancelEdit() {
        document.getElementById('confirm-dialog').classList.add('hidden');
        closeEditFeeModal();
    }

    function updateFeePreview(val) {
        const num = parseFloat(val);
        if (isNaN(num)) return;
        const subtotal = 100000;
        const fee = subtotal * (num / 100);
        document.getElementById('fee-preview-amount').innerText = '+ Rp ' + fee.toLocaleString('id-ID');
    }

    document.getElementById('feePercentage').addEventListener('input', function() {
        updateFeePreview(this.value);
    });

    document.getElementById('edit-fee-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const rate = document.getElementById('feePercentage').value || '11';
        document.getElementById('confirm-fee-rate').textContent = rate + '%';
        document.getElementById('confirm-dialog').classList.remove('hidden');
    });
</script>
@endpush

@push('modals')
<!-- Modal Edit Biaya -->
<div id="modal-edit-biaya" data-modal class="fixed inset-0 z-[80] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Ubah Pajak Penjualan (PPN)</h3>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Sesuaikan dengan tarif PPN yang berlaku.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form class="p-6 space-y-5" id="edit-fee-form">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="feePercentage">Tarif Pajak (%)</label>
                    <div class="relative">
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors placeholder-on-surface-variant/50" id="feePercentage" name="feePercentage" max="50" min="0" step="0.1" type="number" value="11" required />
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">%</div>
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="feeName">Label Pajak</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors placeholder-on-surface-variant/50" id="feeName" name="feeName" type="text" value="PPN" required />
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="feeDescription">Deskripsi</label>
                <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors resize-none placeholder-on-surface-variant/50" id="feeDescription" name="feeDescription" rows="3">Pajak Penjualan (PPN) dipungut atas nama pemerintah sesuai tarif berlaku dan tidak menjadi pendapatan Raliva.</textarea>
            </div>
            <div class="bg-surface-container border border-muted-border rounded-lg p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-gutter">
                <div>
                    <span class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-1">Preview Perhitungan</span>
                    <span class="font-body-md text-body-md">Pada subtotal Rp 100.000</span>
                </div>
                <div class="text-right">
                    <span class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-1">Pajak Terkumpul</span>
                    <span class="font-headline-lg text-headline-lg text-secondary" id="fee-preview-amount">Rp 11.000</span>
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Review Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Dialog Overlay -->
<div class="fixed inset-0 z-[95] hidden bg-surface-container/80 backdrop-blur-sm flex items-center justify-center p-gutter" id="confirm-dialog">
    <div class="bg-surface border border-muted-border p-section-gap max-w-md w-full shadow-2xl relative rounded-xl">
        <button class="absolute top-4 right-4 text-on-surface-variant hover:text-deep-onyx transition-colors" onclick="document.getElementById('confirm-dialog').classList.add('hidden')"><span class="material-symbols-outlined">close</span></button>
        <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-gutter">
            <span class="material-symbols-outlined text-gold-accent text-[28px]">published_with_changes</span>
        </div>
        <h3 class="font-display-lg text-headline-lg-mobile md:text-headline-lg mb-gutter text-center">Konfirmasi Perubahan</h3>
        <p class="font-body-md text-body-md text-on-surface-variant mb-container-margin text-center">Anda akan mengubah tarif <strong class="text-deep-onyx">Pajak Penjualan (PPN)</strong> menjadi <strong class="text-deep-onyx" id="confirm-fee-rate">11%</strong>. Pajak dipungut atas nama pemerintah dan wajib disetor — bukan pendapatan Raliva.</p>
        <div class="flex flex-col gap-gutter">
            <button class="w-full bg-secondary text-on-secondary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-secondary/85 transition-colors" onclick="showRalivaToast('Tarif pajak penjualan berhasil diperbarui.', 'task_alt'); cancelEdit();">Konfirmasi & Terapkan</button>
            <button class="w-full border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors" onclick="document.getElementById('confirm-dialog').classList.add('hidden')">Batal</button>
        </div>
    </div>
</div>
@endpush