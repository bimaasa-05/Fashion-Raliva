@extends('layouts.gudang')

@section('title', 'Profil')

@section('header-title', 'Profil')
@section('header-subtitle', 'Informasi akun dan penugasan gudang Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-surface-container-high border border-outline-variant overflow-hidden shrink-0 mx-auto sm:mx-0">
                <img alt="Foto Profil Andi Pratama" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuUf094pPsvMlxNz9CEzztLZIPfB4q2FE_6HM73O8sFoIt42FkBx43D1cxFlylMdSolVSJZNCBDrc8ttYGcVUIYXcsS0AUGBhcZYBAFGqcAXzmuJyVyjyJY6CXvyxdr0Zwzlwi2Tw3Djm9F2wtwaOLZklTUYLsRg7NCbF9hgI1uCTcTdgGi-0zShSJMzVkR1HYp_C02xOHHVWnGLI4_rrhbWQnSlrZ2VpmUbZL0Gc18YDjNwDrrkAcPg" />
            </div>
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center sm:justify-start">
                    <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Andi Pratama</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider w-fit mx-auto sm:mx-0">Gudang</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">andi.pratama@raliva.id • +62 812-3456-7890</p>
            </div>
            <button type="button" data-modal-open="modal-edit-profil" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">Edit Profil</button>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Informasi Akun</h2>
            <dl class="space-y-5 font-body-md text-sm">
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nama Lengkap</dt><dd class="text-on-surface font-bold text-right">Andi Pratama</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Email</dt><dd class="text-on-surface text-right break-all">andi.pratama@raliva.id</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nomor HP</dt><dd class="text-on-surface text-right">+62 812-3456-7890</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Role</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Gudang</span></dd></div>
                <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant shrink-0">Status Akun</dt><dd><span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Aktif</span></dd></div>
            </dl>
        </section>

        <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Penugasan &amp; Akses</h2>
            <dl class="space-y-5 font-body-md text-sm">
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface font-bold text-right">Raliva Store Bandung</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border items-start">
                    <dt class="text-on-surface-variant shrink-0">Gudang Ditugaskan</dt>
                    <dd>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-high text-on-surface text-xs font-bold border border-outline-variant whitespace-nowrap">
                            <span class="material-symbols-outlined text-[16px] text-gold-accent">warehouse</span>
                            Gudang Utama Bandung
                        </span>
                        <p class="text-xs text-on-surface-variant mt-2 text-right">Anda hanya dapat mengakses data gudang ini.</p>
                    </dd>
                </div>
                <div class="pb-1"><dt class="text-on-surface-variant text-sm mb-3 block">Hak Akses Gudang</dt></div>
            </dl>
            <div class="flex flex-wrap gap-2">
                @foreach ([['inventory_2', 'Melihat Stok'], ['archive', 'Catat Barang Masuk'], ['unarchive', 'Catat Barang Keluar'], ['swap_horiz', 'Pindah Stok'], ['fact_check', 'Periksa Stok'], ['report', 'Lapor Rusak'], ['history', 'Lihat Riwayat']] as $perm)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant font-label-sm text-[11px]">
                        <span class="material-symbols-outlined text-[14px] text-secondary">{{ $perm[0] }}</span>
                        {{ $perm[1] }}
                    </span>
                @endforeach
            </div>
            <p class="text-xs text-on-surface-variant mt-5 flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">lock</span>
                Role, toko, dan penugasan gudang hanya dapat diubah oleh Super Admin atau Owner.
            </p>
        </section>
    </div>

    <div id="modal-edit-profil" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Edit Profil</h3>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form data-toast-message="Profil berhasil diperbarui." class="p-6 space-y-5">
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Nama Lengkap</label>
                    <input type="text" value="Andi Pratama" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Email</label>
                    <input type="email" value="andi.pratama@raliva.id" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Nomor HP</label>
                    <input type="text" value="+62 812-3456-7890" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Role</label>
                        <input type="text" value="Gudang" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Gudang</label>
                        <input type="text" value="Gudang Utama Bandung" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
                    Perubahan role dan penugasan gudang tidak diizinkan pada akun Anda.
                </p>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
