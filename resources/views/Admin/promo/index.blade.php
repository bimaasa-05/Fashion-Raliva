@extends('layouts.admin')

@section('title', 'Promo Toko')

@section('header-title', 'Promo Toko')
@section('header-badge', 'Terbatas')
@section('header-subtitle', 'Kelola promo toko jika diberi izin oleh Owner.')

@section('content')
<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Pembuatan promo baru memerlukan persetujuan Owner. Kamu dapat mengaktifkan/menonaktifkan promo yang sudah dibuat Owner.</p>
    </div>

    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Daftar Promo Toko</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">Diskon Gajian</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                </div>
                <p class="font-body-md text-sm text-on-surface-variant flex-1">Diskon 20% semua atasan, min. belanja Rp 300.000. Berlaku sampai 31 Agu 2026.</p>
                <div class="pt-4 border-t border-muted-border flex justify-between items-center">
                    <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">Dibuat oleh Owner</span>
                    <button type="button" onclick="showRalivaToast('Promo dinonaktifkan.', 'block')" class="px-4 py-2 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/10 transition-colors">Nonaktifkan</button>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">Flash Sale Weekend</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Non-aktif</span>
                </div>
                <p class="font-body-md text-sm text-on-surface-variant flex-1">Potongan Rp 50.000 untuk outerwear, weekend saja. Kuota 50 pembelian.</p>
                <div class="pt-4 border-t border-muted-border flex justify-between items-center">
                    <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">Dibuat oleh Owner</span>
                    <button type="button" onclick="showRalivaToast('Promo diaktifkan kembali.', 'task_alt')" class="px-4 py-2 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-tertiary-container transition-colors btn-premium">Aktifkan</button>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col gap-4 relative overflow-hidden card-premium">
                <div class="flex items-center justify-between">
                    <span class="font-title-md text-title-md text-on-surface">Gratis Ongkir Se-Indonesia</span>
                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Aktif</span>
                </div>
                <p class="font-body-md text-sm text-on-surface-variant flex-1">Bebas ongkir tanpa minimum belanja melalui kurir mitra platform.</p>
                <div class="pt-4 border-t border-muted-border flex justify-between items-center">
                    <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">Dibuat oleh Owner</span>
                    <button type="button" onclick="showRalivaToast('Promo dinonaktifkan.', 'block')" class="px-4 py-2 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/10 transition-colors">Nonaktifkan</button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
