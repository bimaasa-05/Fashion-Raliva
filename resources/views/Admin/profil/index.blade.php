@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('header-title', 'Profil Saya')
@section('header-subtitle', 'Kelola informasi akun Admin Toko Anda.')

@section('content')
<div class="space-y-section-gap max-w-4xl">
    <section class="rise relative bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden card-premium">
        <div class="relative h-28 md:h-32 bg-gradient-to-r from-gold-accent/25 via-gold-accent/10 to-transparent">
            <span class="material-symbols-outlined absolute right-8 -bottom-6 text-[110px] text-gold-accent/15 pointer-events-none select-none" aria-hidden="true">storefront</span>
        </div>
        <div class="px-6 md:px-8 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-12">
                <div class="w-24 h-24 rounded-2xl ring-4 ring-surface-container-lowest overflow-hidden bg-surface-container-high shadow-xl shrink-0 mx-auto sm:mx-0">
                    <img alt="Foto Profil" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuUf094pPsvMlxNz9CEzztLZIPfB4q2FE_6HM73O8sFoIt42FkBx43D1cxFlylMdSolVSJZNCBDrc8ttYGcVUIYXcsS0AUGBhcZYBAFGqcAXzmuJyVyjyJY6CXvyxdr0Zwzlwi2Tw3Djm9F2wtwaOLZklTUYLsRg7NCbF9hgI1uCTcTdgGi-0zShSJMzVkR1HYp_C02xOHHVWnGLI4_rrhbWQnSlrZ2VpmUbZL0Gc18YDjNwDrrkAcPg" />
                </div>
                <div class="text-center sm:text-left flex-grow pb-1 min-w-0">
                    <div class="flex items-center gap-3 justify-center sm:justify-start flex-wrap">
                        <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Sinta Maharani</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">Admin Toko</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary border border-secondary/20 font-label-sm text-[10px] uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Aktif</span>
                    </div>
                    <p class="text-on-surface-variant font-body-md text-sm mt-2 flex items-center justify-center sm:justify-start gap-4 flex-wrap">
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">mail</span>sinta.maharani@lunara.com</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">call</span>+62 813-9876-5432</span>
                    </p>
                </div>
                <button type="button" onclick="showRalivaToast('Fitur ganti foto demo belum tersedia.', 'photo_camera')" class="px-5 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:border-gold-accent hover:text-gold-accent transition-colors shrink-0">Ganti Foto</button>
            </div>
            <p class="font-body-md text-on-surface-variant text-sm mt-4 max-w-2xl">Operator operasional harian toko — berhubungan langsung dengan customer, pesanan, pembayaran, pengiriman, dan komplain.</p>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <h3 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading mb-6">Toko yang Ditugaskan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
            <div class="flex items-center justify-between p-4 border border-muted-border rounded-lg bg-surface-container-low">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm text-label-sm shrink-0">LF</div>
                    <div>
                        <p class="font-title-md text-title-md text-on-surface">LUNARA Fashion</p>
                        <p class="text-on-surface-variant text-xs">Ditugaskan sejak Mar 2025</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-gold-accent text-[20px]">check_circle</span>
            </div>
            <div class="flex items-center justify-between p-4 border border-muted-border rounded-lg bg-surface-container-low">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-surface-variant text-on-surface flex items-center justify-center font-label-sm text-label-sm shrink-0">VC</div>
                    <div>
                        <p class="font-title-md text-title-md text-on-surface">Velvet Closet</p>
                        <p class="text-on-surface-variant text-xs">Ditugaskan sejak Agu 2026</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-gold-accent text-[20px]">check_circle</span>
            </div>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 space-y-gutter card-premium">
        <h3 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Informasi Akun</h3>
        <form class="space-y-gutter" id="profil-form" data-toast-message="Profil berhasil diperbarui.">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama">Nama Lengkap</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="nama" type="text" value="Sinta Maharani" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="email">Email</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="email" type="email" value="sinta.maharani@lunara.com" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="telepon">No. Telepon</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="telepon" type="tel" value="+62 813-9876-5432" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="role">Role</label>
                    <input class="w-full bg-surface-container-low border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface-variant cursor-not-allowed" id="role" type="text" value="Admin Toko" disabled />
                </div>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Perubahan</button>
            </div>
        </form>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 space-y-gutter card-premium">
        <h3 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Keamanan</h3>
        <form class="space-y-gutter" id="password-form" data-toast-message="Password berhasil diubah.">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-lama">Password Lama</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="password-lama" type="password" placeholder="Masukkan password lama" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-baru">Password Baru</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="password-baru" type="password" placeholder="Minimal 8 karakter" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-konfirmasi">Konfirmasi Password</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="password-konfirmasi" type="password" placeholder="Ulangi password baru" />
                </div>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Ubah Password</button>
            </div>
        </form>
    </section>
</div>
@endsection
