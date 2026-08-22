@extends('layouts.superadmin')

@section('title', 'Komisi Raliva')

@section('header-title', 'Komisi Raliva')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Atur persentase komisi platform yang berlaku untuk semua transaksi berhasil.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 300, 'GRAD' 0, 'opsz' 24; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="w-full max-w-3xl mx-auto">
    <!-- Current Commission Display -->
    <div class="w-full bg-surface-container-low border border-muted-border p-section-gap text-center mb-section-gap relative overflow-hidden group card-premium">
        <div class="absolute inset-0 bg-gradient-to-br from-surface-container to-surface-container-lowest opacity-50 z-0"></div>
        <div class="relative z-10">
            <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest block mb-gutter">Tarif Saat Ini</span>
            <div class="font-display-lg text-6xl md:text-8xl tracking-tighter text-deep-onyx mb-gutter" id="current-rate">5%</div>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-md mx-auto mb-section-gap">Diterapkan otomatis ke semua penjualan toko. Terakhir diperbarui 12 Oktober 2023.</p>
            <button class="bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase px-8 py-4 tracking-widest hover:bg-tertiary-container transition-colors" onclick="document.getElementById('edit-form').classList.remove('hidden'); this.classList.add('hidden')">Ubah Komisi</button>
        </div>
    </div>

    <!-- Edit Form Section -->
    <div class="w-full hidden transition-all duration-500 ease-in-out opacity-0" id="edit-form" style="animation: fadeIn 0.5s forwards;">
        <style>@keyframes fadeIn { to { opacity: 1; } }</style>
        <div class="border-t border-muted-border pt-section-gap">
            <h2 class="font-title-md text-title-md mb-container-margin">Perbarui Tarif Komisi</h2>
            <form class="space-y-container-margin" onsubmit="event.preventDefault(); document.getElementById('confirm-dialog').classList.remove('hidden');">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="commissionRate">Tarif Baru (%)</label>
                    <div class="relative">
                        <input class="w-full bg-transparent border border-muted-border p-4 font-body-md text-body-md focus:outline-none focus:border-deep-onyx transition-colors placeholder-on-surface-variant/50" id="commissionRate" max="100" min="0" name="commissionRate" oninput="updatePreview(this.value)" placeholder="misal 5.5" step="0.1" type="number" value="5" />
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">%</div>
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="updateNotes">Catatan Perubahan (Internal)</label>
                    <textarea class="w-full bg-transparent border border-muted-border p-4 font-body-md text-body-md focus:outline-none focus:border-deep-onyx transition-colors resize-none placeholder-on-surface-variant/50" id="updateNotes" name="updateNotes" placeholder="Alasan perubahan ini..." rows="3"></textarea>
                </div>
                <div class="bg-surface-container border border-muted-border p-container-margin flex flex-col sm:flex-row justify-between items-start sm:items-center gap-gutter">
                    <div><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Pratinjau</span><span class="font-body-md text-body-md">Dari penjualan Rp 1.000.000</span></div>
                    <div class="text-right"><span class="font-label-sm text-label-sm text-on-surface-variant uppercase block mb-1">Pendapatan Platform</span><span class="font-title-md text-title-md text-deep-onyx" id="preview-amount">Rp 50.000</span></div>
                </div>
                <div class="flex gap-gutter pt-container-margin">
                    <button class="flex-1 border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors" onclick="document.getElementById('edit-form').classList.add('hidden'); document.querySelector('.bg-deep-onyx.px-8.py-4').classList.remove('hidden');" type="button">Batal</button>
                    <button class="flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-tertiary-container transition-colors" type="submit">Tinjau Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updatePreview(val) {
        const num = parseFloat(val);
        if (isNaN(num)) return;
        const sale = 1000000;
        const rev = sale * (num / 100);
        document.getElementById('preview-amount').innerText = 'Rp ' + rev.toLocaleString('id-ID');
        document.getElementById('confirm-rate').innerText = num + '%';
    }
</script>
@endpush

@push('modals')
<!-- Confirmation Dialog Overlay -->
<div class="fixed inset-0 z-50 hidden bg-surface-container/80 backdrop-blur-sm flex items-center justify-center p-gutter" id="confirm-dialog">
    <div class="bg-surface border border-muted-border p-section-gap max-w-md w-full shadow-2xl relative">
        <button class="absolute top-4 right-4 text-on-surface-variant hover:text-deep-onyx transition-colors" onclick="document.getElementById('confirm-dialog').classList.add('hidden')"><span class="material-symbols-outlined">close</span></button>
        <h3 class="font-display-lg text-headline-lg-mobile md:text-headline-lg mb-gutter text-center">Konfirmasi Perubahan</h3>
        <p class="font-body-md text-body-md text-on-surface-variant mb-container-margin text-center">Anda akan mengubah tarif komisi global platform menjadi <strong class="text-deep-onyx" id="confirm-rate">5%</strong>. Perubahan ini berlaku untuk semua transaksi selanjutnya.</p>
        <div class="flex flex-col gap-gutter">
            <button class="w-full bg-error text-on-error font-label-sm text-label-sm uppercase py-4 tracking-widest hover:opacity-90 transition-opacity" onclick="document.getElementById('current-rate').innerText = document.getElementById('confirm-rate').innerText; showRalivaToast('Tarif komisi global berhasil diperbarui.', 'task_alt'); document.getElementById('confirm-dialog').classList.add('hidden'); document.getElementById('edit-form').classList.add('hidden'); document.querySelector('.bg-deep-onyx.px-8.py-4').classList.remove('hidden');">Konfirmasi & Terapkan</button>
            <button class="w-full border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-lowest transition-colors" onclick="document.getElementById('confirm-dialog').classList.add('hidden')">Batal</button>
        </div>
    </div>
</div>
@endpush