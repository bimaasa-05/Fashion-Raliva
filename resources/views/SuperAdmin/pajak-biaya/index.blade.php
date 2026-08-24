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
    <!-- Current Fee Display -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3"><div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">percent</span></div><h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Biaya Layanan Platform</h2></div>
        </div>
        <div class="group relative overflow-hidden bg-gradient-to-br from-gold-accent/5 to-gold-accent/10 border border-gold-accent/20 rounded-xl p-8 transition-all duration-300 hover:border-gold-accent hover:shadow-xl">
            <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-br from-gold-accent/10 to-transparent rounded-full -translate-y-16 translate-x-16" style="filter: blur(30px); opacity: 0.5;"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="text-center md:text-left">
                    <span class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">Biaya Layanan Platform</span>
                    <div class="flex items-baseline justify-center md:justify-start gap-2">
                        <span class="font-display-lg text-6xl md:text-7xl tracking-tight text-deep-onyx">5</span>
                        <span class="font-display-lg text-4xl md:text-5xl text-deep-onyx/70 self-end mb-1">%</span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-3 max-w-md mx-auto md:mx-0">Ditampilkan otomatis saat checkout untuk semua transaksi</p>
                </div>
                <button class="flex-1 md:w-auto bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase px-8 py-4 tracking-widest hover:bg-tertiary-container transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                    Ubah Biaya
                </button>
            </div>
        </div>
    </section>

    <!-- Edit Form Section -->
    <section class="space-y-gutter" id="edit-form-section" style="display: none;">
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 animate-fade-in card-premium">
            <div class="flex justify-between items-center mb-gutter">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface">Update Biaya Layanan</h2>
                <button class="p-2 rounded-lg text-on-surface-variant hover:text-gold-accent hover:bg-gold-accent/10 transition-colors" onclick="document.getElementById('edit-form-section').style.display='none'; document.querySelector('[onclick=\"showEditForm()\"]').style.display='flex'">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>
            <form class="space-y-gutter" id="edit-fee-form" onsubmit="event.preventDefault(); document.getElementById('confirm-dialog').classList.remove('hidden');">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="feePercentage">Persentase Biaya (%)</label>
                        <div class="relative">
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="feePercentage" name="feePercentage" max="50" min="0" step="0.1" type="number" value="5" required />
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">%</div>
                        </div>
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="feeName">Nama Biaya</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="feeName" name="feeName" type="text" value="Biaya Layanan" required />
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="feeDescription">Deskripsi</label>
                    <textarea class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors resize-none placeholder-on-surface-variant/50" id="feeDescription" name="feeDescription" placeholder="Deskripsi biaya yang akan ditampilkan pelanggan..." rows="3"></textarea>
                </div>
                <div class="bg-surface-container border border-muted-border rounded-lg p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-gutter">
                    <div>
                        <span class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-1">Preview Perhitungan</span>
                        <span class="font-body-md text-body-md">Pada subtotal Rp 100.000</span>
                    </div>
                    <div class="text-right">
                        <span class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-1">Pendapatan Platform</span>
                        <span class="font-headline-lg text-headline-lg text-deep-onyx" id="fee-preview-amount">Rp 5.000</span>
                    </div>
                </div>
                <div class="flex gap-gutter pt-gutter border-t border-muted-border">
                    <button type="button" class="flex-1 border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors" onclick="cancelEdit()">Batal</button>
                    <button type="submit" class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-tertiary-container transition-colors">Review Perubahan</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Fee Impact Preview -->
    <section class="space-y-gutter">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3"><div class="w-11 h-11 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">calculate</span></div><h2 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Rincian Biaya (Simulasi)</h2></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
                <p class="text-on-surface-variant font-label-sm uppercase tracking-widest mb-1">Subtotal</p>
                <p class="font-display-lg text-headline-lg text-on-surface">Rp 100.000</p>
            </div>
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
                <p class="text-on-surface-variant font-label-sm uppercase tracking-widest mb-1">Biaya Layanan (5%)</p>
                <p class="font-display-lg text-headline-lg text-gold-accent">Rp 5.000</p>
            </div>
            <div class="bg-gradient-to-br from-gold-accent/10 to-gold-accent/5 border border-gold-accent/20 rounded-xl p-6">
                <p class="text-on-surface-variant font-label-sm uppercase tracking-widest mb-1">Total Dibayar Pelanggan</p>
                <p class="font-display-lg text-headline-lg text-deep-onyx">Rp 105.000</p>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    function showEditForm() {
        document.getElementById('edit-form-section').style.display = 'block';
        document.querySelector('[onclick="showEditForm()"]').style.display = 'none';
        document.getElementById('feePercentage').focus();
    }

    function cancelEdit() {
        document.getElementById('edit-form-section').style.display = 'none';
        document.querySelector('[onclick="showEditForm()"]').style.display = 'flex';
        document.getElementById('edit-fee-form').reset();
        updateFeePreview(5);
    }

    function updateFeePreview(val) {
        const num = parseFloat(val);
        if (isNaN(num)) return;
        const subtotal = 100000;
        const fee = subtotal * (num / 100);
        document.getElementById('fee-preview-amount').innerText = 'Rp ' + fee.toLocaleString('id-ID');
    }

    document.getElementById('feePercentage').addEventListener('input', function() {
        updateFeePreview(this.value);
    });

    document.getElementById('edit-fee-form').addEventListener('submit', function() {
        document.getElementById('confirm-dialog').classList.remove('hidden');
    });
</script>
@endpush

@push('modals')
<!-- Confirmation Dialog Overlay -->
<div class="fixed inset-0 z-50 hidden bg-surface-container/80 backdrop-blur-sm flex items-center justify-center p-gutter" id="confirm-dialog">
    <div class="bg-surface border border-muted-border p-section-gap max-w-md w-full shadow-2xl relative rounded-xl">
        <button class="absolute top-4 right-4 text-on-surface-variant hover:text-deep-onyx transition-colors" onclick="document.getElementById('confirm-dialog').classList.add('hidden')"><span class="material-symbols-outlined">close</span></button>
        <div class="w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-gutter">
            <span class="material-symbols-outlined text-gold-accent text-[28px]">published_with_changes</span>
        </div>
        <h3 class="font-display-lg text-headline-lg-mobile md:text-headline-lg mb-gutter text-center">Konfirmasi Perubahan</h3>
        <p class="font-body-md text-body-md text-on-surface-variant mb-container-margin text-center">Anda akan mengubah biaya layanan platform menjadi <strong class="text-deep-onyx" id="confirm-fee-rate">5%</strong>. Perubahan ini akan berlaku untuk semua transaksi baru.</p>
        <div class="flex flex-col gap-gutter">
            <button class="w-full bg-gold-accent text-on-secondary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-secondary-container transition-colors" onclick="showRalivaToast('Biaya layanan berhasil diperbarui.', 'task_alt'); document.getElementById('confirm-dialog').classList.add('hidden'); cancelEdit();">Konfirmasi & Terapkan</button>
            <button class="w-full border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors" onclick="document.getElementById('confirm-dialog').classList.add('hidden')">Batal</button>
        </div>
    </div>
</div>
@endpush