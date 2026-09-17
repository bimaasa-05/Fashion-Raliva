@extends('layouts.superadmin')

@section('title', 'Pajak & Biaya')

@section('header-title', 'Pajak & Biaya Layanan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Kelola biaya global dan pajak yang diterapkan otomatis saat checkout')

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
                        <span class="font-display-lg text-5xl md:text-6xl tracking-tight text-gradient-gold">{{ number_format($komisi, 0, ',', '.') }}</span>
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
                        <span class="font-display-lg text-5xl md:text-6xl tracking-tight text-on-surface">{{ number_format($pajak, 0, ',', '.') }}</span>
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

    <!-- Agregat Terkumpul -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-gutter" data-reveal>
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-secondary/10 fill pointer-events-none select-none" aria-hidden="true">account_balance</span>
            <p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Pajak Terkumpul</p>
            <p class="font-headline-lg text-headline-lg md:text-display-lg text-secondary mt-2">Rp {{ number_format($pajakTerkumpul, 0, ',', '.') }}</p>
            <p class="font-body-md text-sm text-on-surface-variant mt-1">Total PPN tersimpan dari seluruh transaksi (dibayar/disetor ke negara).</p>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 fill pointer-events-none select-none" aria-hidden="true">receipt_long</span>
            <p class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Biaya Layanan Terkumpul</p>
            <p class="font-headline-lg text-headline-lg md:text-display-lg text-on-surface mt-2">Rp {{ number_format($biayaLayananTerkumpul, 0, ',', '.') }}</p>
            <p class="font-body-md text-sm text-on-surface-variant mt-1">Total biaya layanan dari seluruh transaksi (default {{ $biayaLayanan > 0 ? 'Rp '.number_format($biayaLayanan, 0, ',', '.') : 'tidak berlaku' }} per transaksi).</p>
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
                    <li class="flex items-center justify-between py-3"><span class="font-body-md text-sm text-secondary inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">account_balance</span>PPN {{ $pajak }}% <em class="not-italic text-on-surface-variant text-xs">(pajak)</em></span><span class="font-title-md text-title-md text-secondary">+ Rp {{ number_format(100000 * $pajak / 100, 0, ',', '.') }}</span></li>
                    <li class="flex items-center justify-between pt-3 border-t border-muted-border"><span class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Total Dibayar Pelanggan</span><span class="font-headline-lg-mobile text-headline-lg-mobile text-deep-onyx">Rp {{ number_format(100000 + (100000 * $pajak / 100), 0, ',', '.') }}</span></li>
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
                    <li class="flex items-center justify-between py-3"><span class="font-body-md text-sm text-gold-accent inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">percent</span>Komisi Raliva {{ $komisi }}% <em class="not-italic text-on-surface-variant text-xs">(pendapatan platform)</em></span><span class="font-title-md text-title-md text-gold-accent">Rp {{ number_format(100000 * $komisi / 100, 0, ',', '.') }}</span></li>
                    <li class="flex items-center justify-between py-3"><span class="font-body-md text-sm text-on-surface-variant">Penerima bersih (toko)</span><span class="font-title-md text-title-md text-on-surface">Rp {{ number_format(100000 - (100000 * $komisi / 100), 0, ',', '.') }}</span></li>
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
        updateFeePreview({{ $pajak }});
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
        document.getElementById('confirm-dialog').classList.remove('hidden');
    });
</script>
@endpush

@push('modals')
<!-- Modal Edit Biaya -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-edit-biaya',
    'dataModal' => true,
    'icon' => 'percent',
    'title' => 'Ubah Pajak Penjualan (PPN)',
    'subtitle' => 'Sesuaikan dengan tarif PPN yang berlaku.',
    'size' => 'lg',
    'zIndex' => 80,
])
    <form method="POST" action="{{ route('superadmin.pajak-biaya.update-pajak') }}" id="edit-fee-form">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="feePercentage">Tarif Pajak (%)</label>
                <div class="relative">
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors placeholder-on-surface-variant/50" id="feePercentage" name="pajak_persen" max="50" min="0" step="0.1" type="number" value="{{ $pajak }}" required />
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">%</div>
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="feeName">Label Pajak</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-colors placeholder-on-surface-variant/50" id="feeName" type="text" value="PPN" disabled />
            </div>
        </div>
        <div class="bg-surface-container border border-muted-border rounded-lg p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-1">Preview Perhitungan</span>
                <span class="font-body-md text-body-md">Pada subtotal Rp 100.000</span>
            </div>
            <div class="text-right">
                <span class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-1">Pajak Terkumpul</span>
                <span class="font-headline-lg text-headline-lg text-secondary" id="fee-preview-amount">Rp {{ number_format(100000 * $pajak / 100, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-4 pt-2">
            <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
            <button type="submit" class="btn-modal btn-modal-primary">Review Perubahan</button>
        </div>
    </form>
@endcomponent

<!-- Confirmation Dialog Overlay -->
@component('SuperAdmin.partials.premium-confirm', [
    'id' => 'confirm-dialog',
    'icon' => 'published_with_changes',
    'iconBox' => 'bg-gold-accent/10 border-gold-accent/25',
    'iconColor' => 'text-gold-accent',
    'zIndex' => 95,
])
    <div class="p-6 space-y-4">
        <div class="text-center">
            <h3 class="font-display-lg text-headline-lg-mobile md:text-headline-lg">Konfirmasi Perubahan</h3>
            <p class="font-body-md text-body-md text-on-surface-variant mt-2">Anda akan mengubah tarif <strong class="text-deep-onyx">Pajak Penjualan (PPN)</strong>. Pajak dipungut atas nama pemerintah dan wajib disetor — bukan pendapatan Raliva.</p>
        </div>
        <div class="flex flex-col gap-4 pt-2">
            <button class="btn-modal btn-modal-primary w-full" onclick="document.getElementById('confirm-dialog').classList.add('hidden'); document.getElementById('edit-fee-form').submit();">Konfirmasi &amp; Terapkan</button>
            <button class="btn-modal btn-modal-ghost w-full" onclick="document.getElementById('confirm-dialog').classList.add('hidden')">Batal</button>
        </div>
    </div>
@endcomponent
</div>
@endpush