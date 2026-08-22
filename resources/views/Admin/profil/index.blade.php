@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('header-title', 'Profil Saya')
@section('header-subtitle', 'Kelola informasi akun Admin Toko Anda.')

@section('content')
<div class="space-y-section-gap max-w-4xl">
    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <div class="w-24 h-24 rounded-full bg-surface-container-high border border-outline-variant overflow-hidden shrink-0">
                <img alt="Foto Profil" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuUf094pPsvMlxNz9CEzztLZIPfB4q2FE_6HM73O8sFoIt42FkBx43D1cxFlylMdSolVSJZNCBDrc8ttYGcVUIYXcsS0AUGBhcZYBAFGqcAXzmuJyVyjyJY6CXvyxdr0Zwzlwi2Tw3Djm9F2wtwaOLZklTUYLsRg7NCbF9hgI1uCTcTdgGi-0zShSJMzVkR1HYp_C02xOHHVWnGLI4_rrhbWQnSlrZ2VpmUbZL0Gc18YDjNwDrrkAcPg" />
            </div>
            <div class="text-center sm:text-left flex-grow">
                <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Sinta Maharani</h2>
                <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-label-sm uppercase tracking-wider">Admin Toko</span>
                <p class="font-body-md text-on-surface-variant mt-3">Operator operasional harian toko. Berhubungan langsung dengan customer, pesanan, pembayaran, pengiriman, dan komplain.</p>
                <button type="button" onclick="showRalivaToast('Fitur ganti foto demo belum tersedia.', 'photo_camera')" class="mt-4 px-5 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-low transition-colors">Ganti Foto</button>
            </div>
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
