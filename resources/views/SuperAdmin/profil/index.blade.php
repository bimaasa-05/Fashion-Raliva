@extends('layouts.superadmin')

@section('title', 'Profil Saya')

@section('header-title', 'Profil Saya')
@section('header-subtitle', 'Kelola informasi akun Super Admin Anda.')

@section('content')
<div class="space-y-section-gap max-w-4xl">
    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
            <div class="w-24 h-24 rounded-full bg-surface-container-high border border-outline-variant overflow-hidden shrink-0">
                <img alt="Foto Profil Admin" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAuUf094pPsvMlxNz9CEzztLZIPfB4q2FE_6HM73O8sFoIt42FkBx43D1cxFlylMdSolVSJZNCBDrc8ttYGcVUIYXcsS0AUGBhcZYBAFGqcAXzmuJyVyjyJY6CXvyxdr0Zwzlwi2Tw3Djm9F2wtwaOLZklTUYLsRg7NCbF9hgI1uCTcTdgGi-0zShSJMzVkR1HYp_C02xOHHVWnGLI4_rrhbWQnSlrZ2VpmUbZL0Gc18YDjNwDrrkAcPg" />
            </div>
            <div class="text-center sm:text-left flex-grow">
                <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rizky Pratama</h2>
                <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-label-sm uppercase tracking-wider">Super Admin</span>
                <p class="font-body-md text-on-surface-variant mt-3">Pengelola platform Raliva secara keseluruhan. Mengawasi pengguna, toko, transaksi, aturan bisnis, moderasi, komisi, pencairan dana, dan konfigurasi global.</p>
                <button type="button" class="mt-4 px-5 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:bg-surface-container-low transition-colors">Ganti Foto</button>
            </div>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 space-y-gutter card-premium">
        <h3 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Informasi Akun</h3>
        <form class="space-y-gutter" id="profil-form">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama">Nama Lengkap</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="nama" type="text" value="Rizky Pratama" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="email">Email</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="email" type="email" value="rizky.pratama@raliva.com" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="telepon">No. Telepon</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="telepon" type="tel" value="+62 812-3456-7890" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="role">Role</label>
                    <input class="w-full bg-surface-container-low border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface-variant cursor-not-allowed" id="role" type="text" value="Super Admin" disabled />
                </div>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Perubahan</button>
            </div>
        </form>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 space-y-gutter card-premium">
        <h3 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Keamanan</h3>
        <form class="space-y-gutter" id="password-form">
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
                <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors">Ubah Password</button>
            </div>
        </form>
    </section>
</div>
@endsection
