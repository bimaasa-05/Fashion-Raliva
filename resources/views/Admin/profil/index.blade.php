@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('header-title', 'Profil Saya')
@section('header-subtitle', 'Kelola informasi akun Admin Toko Anda.')

@section('content')
<div class="space-y-section-gap max-w-4xl">
    <section class="relative bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
        <div class="relative h-28 md:h-32 bg-gradient-to-r from-gold-accent/25 via-gold-accent/10 to-transparent">
            <span class="material-symbols-outlined absolute right-8 -bottom-6 text-[110px] text-gold-accent/15 pointer-events-none select-none" aria-hidden="true">storefront</span>
        </div>
        <div class="px-6 md:px-8 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-12">
                <div class="w-24 h-24 rounded-xl ring-4 ring-surface-container-lowest overflow-hidden bg-surface-container-high shadow-xl shrink-0 mx-auto sm:mx-0">
                    <img alt="Foto Profil" class="w-full h-full object-cover" src="{{ $user->foto_profil ? (str_starts_with($user->foto_profil, 'http') ? $user->foto_profil : asset('storage/'.$user->foto_profil)) : asset('images/avatar.svg') }}" />
                </div>
                <div class="text-center sm:text-left flex-grow pb-1 min-w-0">
                    <div class="flex items-center gap-3 justify-center sm:justify-start flex-wrap">
                        <h2 class="raliva-figure text-[26px] text-on-surface">{{ $user->nama_lengkap }}</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">Admin Toko</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary border border-secondary/20 font-label-sm text-[10px] uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Aktif</span>
                    </div>
                    <p class="text-on-surface-variant font-body-md text-sm mt-2 flex items-center justify-center sm:justify-start gap-4 flex-wrap">
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">mail</span>{{ $user->email }}</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">call</span>{{ $user->nomor_telepon ?? '-' }}</span>
                    </p>
                </div>
                <button type="button" data-modal-open="modal-foto" class="px-5 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:border-gold-accent hover:text-gold-accent transition-colors shrink-0">Ganti Foto</button>
            </div>
            <p class="font-body-md text-on-surface-variant text-sm mt-4 max-w-2xl">Operator operasional harian toko — berhubungan langsung dengan customer, pesanan, pembayaran, pengiriman, dan komplain.</p>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading mb-6">Toko yang Ditugaskan</h3>
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

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 space-y-gutter card-premium">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Informasi Akun</h3>
        <form class="space-y-gutter" method="POST" action="{{ route('admin.profil.update') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="raliva-label" for="nama">Nama Lengkap</label>
                    <input class="raliva-input" id="nama" name="nama_lengkap" type="text" value="{{ $user->nama_lengkap }}" />
                </div>
                <div>
                    <label class="raliva-label" for="email">Email</label>
                    <input class="raliva-input" id="email" name="email" type="email" value="{{ $user->email }}" />
                </div>
                <div>
                    <label class="raliva-label" for="telepon">No. Telepon</label>
                    <input class="raliva-input" id="telepon" name="nomor_telepon" type="tel" value="{{ $user->nomor_telepon ?? '' }}" />
                </div>
                <div>
                    <label class="raliva-label" for="role">Role</label>
                    <input class="raliva-input bg-surface-container-low text-on-surface-variant cursor-not-allowed" id="role" type="text" value="Admin Toko" disabled />
                </div>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Perubahan</button>
            </div>
        </form>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 space-y-gutter card-premium">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Keamanan</h3>
        <form class="space-y-gutter" method="POST" action="{{ route('admin.profil.password') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <div>
                    <label class="raliva-label" for="password-lama">Password Lama</label>
                    <input class="raliva-input" id="password-lama" name="password_lama" type="password" placeholder="Masukkan password lama" />
                </div>
                <div>
                    <label class="raliva-label" for="password-baru">Password Baru</label>
                    <input class="raliva-input" id="password-baru" name="password" type="password" placeholder="Minimal 8 karakter" />
                </div>
                <div>
                    <label class="raliva-label" for="password-konfirmasi">Konfirmasi Password</label>
                    <input class="raliva-input" id="password-konfirmasi" name="password_confirmation" type="password" placeholder="Ulangi password baru" />
                </div>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Ubah Password</button>
            </div>
        </form>
    </section>
</div>

{{-- Modal Ganti Foto --}}
<div id="modal-foto" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.profil.foto') }}" enctype="multipart/form-data" class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
        @csrf
        <p class="raliva-label text-gold-accent">Ganti Foto Profil</p>
        <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">Unggah Foto Baru</h3>
        <input type="file" name="foto_profil" accept="image/*" class="raliva-input mt-4" />
        <div class="flex gap-3 mt-6">
            <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
            <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Unggah</button>
        </div>
    </form>
</div>
@endsection
