@extends('layouts.owner')

@section('title', 'Profil')

@section('header-title', 'Profil')
@section('header-subtitle', 'Kelola informasi akun Owner Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-surface-container-high border border-outline-variant overflow-hidden shrink-0 mx-auto sm:mx-0">
                <img alt="Foto Profil Bima Prasetya" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuUf094pPsvMlxNz9CEzztLZIPfB4q2FE_6HM73O8sFoIt42FkBx43D1cxFlylMdSolVSJZNCBDrc8ttYGcVUIYXcsS0AUGBhcZYBAFGqcAXzmuJyVyjyJY6CXvyxdr0Zwzlwi2Tw3Djm9F2wtwaOLZklTUYLsRg7NCbF9hgI1uCTcTdgGi-0zShSJMzVkR1HYp_C02xOHHVWnGLI4_rrhbWQnSlrZ2VpmUbZL0Gc18YDjNwDrrkAcPg" />
            </div>
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center sm:justify-start">
                    <h2 class="raliva-figure text-[26px] text-on-surface">Bima Prasetya</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider w-fit mx-auto sm:mx-0">Owner</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">bima.prasetya@raliva.id • +62 811-2299-0055</p>
            </div>
            <button type="button" data-modal-open="modal-edit-profil" class="px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium shrink-0">Edit Profil</button>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <section data-reveal-group class="space-y-section-gap">
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Informasi Akun</h2>
                <dl class="space-y-5 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nama Lengkap</dt><dd class="text-on-surface font-bold text-right">Bima Prasetya</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Email</dt><dd class="text-on-surface text-right break-all">bima.prasetya@raliva.id</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nomor HP</dt><dd class="text-on-surface text-right">+62 811-2299-0055</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border items-start"><dt class="text-on-surface-variant shrink-0">Role</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Owner</span></dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant shrink-0">Status Akun</dt><dd><span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Aktif sejak Mar 2024</span></dd></div>
                </dl>
            </section>

            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Keamanan Akun</h2>
                <form data-toast-message="Kata sandi berhasil diperbarui." class="space-y-5">
                    <div>
                        <label for="pw-lama" class="block raliva-label mb-2">Kata Sandi Saat Ini</label>
                        <input id="pw-lama" type="password" required class="raliva-input" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                        <div>
                            <label for="pw-baru" class="block raliva-label mb-2">Kata Sandi Baru</label>
                            <input id="pw-baru" type="password" required minlength="8" class="raliva-input" />
                        </div>
                        <div>
                            <label for="pw-konfirmasi" class="block raliva-label mb-2">Konfirmasi Kata Sandi</label>
                            <input id="pw-konfirmasi" type="password" required minlength="8" class="raliva-input" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Perbarui Kata Sandi</button>
                    </div>
                </form>
            </section>
        </section>

        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium self-start">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Toko yang Dimiliki</h2>
            @foreach ([['Raliva Atelier Jakarta', 'Jakarta Selatan', true, '142 produk'], ['Raliva Store Bandung', 'Bandung', true, '86 produk']] as $toko)
                <div class="border border-muted-border rounded-lg px-4 py-4 flex items-center justify-between gap-3 {{ !$loop->last ? 'mb-gutter' : '' }} hover:border-gold-accent/40 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-deep-onyx text-on-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">storefront</span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-title-md text-sm text-on-surface truncate">{{ $toko[0] }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $toko[1] }} • {{ $toko[3] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('owner.data-toko') }}" class="shrink-0 text-xs font-semibold text-gold-accent hover:underline">Kelola</a>
                </div>
            @endforeach

            <p class="raliva-label mt-7 mb-4">Hak Akses Owner</p>
            <div class="flex flex-wrap gap-2">
                @foreach ([['storefront', 'Kelola Data Toko'], ['fact_check', 'Pengajuan & Verifikasi'], ['checkroom', 'Kelola Produk'], ['shopping_bag', 'Pantau Pesanan'], ['groups', 'Kelola Karyawan'], ['local_offer', 'Promo Toko'], ['warehouse', 'Gudang'], ['account_balance_wallet', 'Saldo & Pencairan'], ['monitoring', 'Laporan Toko'], ['tune', 'Pengaturan Toko']] as $perm)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant font-label-sm text-[11px]">
                        <span class="material-symbols-outlined text-[14px] text-secondary">{{ $perm[0] }}</span>
                        {{ $perm[1] }}
                    </span>
                @endforeach
            </div>
            <p class="text-xs text-on-surface-variant mt-6 flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">lock</span>
                Konfigurasi global platform hanya dapat diubah oleh Super Admin.
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
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="w-20 h-20 rounded-full overflow-hidden border border-outline-variant">
                            <img alt="Foto Profil" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuUf094pPsvMlxNz9CEzztLZIPfB4q2FE_6HM73O8sFoIt42FkBx43D1cxFlylMdSolVSJZNCBDrc8ttYGcVUIYXcsS0AUGBhcZYBAFGqcAXzmuJyVyjyJY6CXvyxdr0Zwzlwi2Tw3Djm9F2wtwaOLZklTUYLsRg7NCbF9hgI1uCTcTdgGi-0zShSJMzVkR1HYp_C02xOHHVWnGLI4_rrhbWQnSlrZ2VpmUbZL0Gc18YDjNwDrrkAcPg" />
                        </div>
                        <button type="button" onclick="showRalivaToast('Silakan pilih foto baru (demo).', 'image')" class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center btn-premium shadow-md" aria-label="Ubah Foto">
                            <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                        </button>
                    </div>
                </div>
                <div>
                    <label for="ep-nama" class="block raliva-label mb-2">Nama Lengkap</label>
                    <input id="ep-nama" type="text" value="Bima Prasetya" required class="raliva-input" />
                </div>
                <div>
                    <label for="ep-email" class="block raliva-label mb-2">Email</label>
                    <input id="ep-email" type="email" value="bima.prasetya@raliva.id" required class="raliva-input" />
                </div>
                <div>
                    <label for="ep-hp" class="block raliva-label mb-2">Nomor HP</label>
                    <input id="ep-hp" type="text" value="+62 811-2299-0055" required class="raliva-input" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Role</label>
                    <input type="text" value="Owner — Raliva Atelier Jakarta & Raliva Store Bandung" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
