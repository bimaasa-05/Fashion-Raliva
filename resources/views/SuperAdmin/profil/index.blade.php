@extends('layouts.superadmin')

@section('title', 'Profil Saya')

@section('header-title', 'Profil Saya')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola informasi akun dan keamanan Anda.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap max-w-4xl">
    <!-- Profile Hero -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gold-accent/10 via-surface-container-lowest to-secondary-container/10 border border-muted-border rounded-xl p-8 md:p-12 card-premium">
        <div class="absolute top-0 right-0 w-48 h-48 bg-gradient-to-br from-gold-accent/15 to-transparent rounded-full -translate-y-16 translate-x-16" style="filter: blur(30px); opacity: 0.5;"></div>
        <div class="relative flex flex-col sm:flex-row items-center gap-6">
            <div class="w-24 h-24 rounded-full bg-secondary-container flex items-center justify-center flex-shrink-0 border-4 border-surface-container-lowest shadow-lg">
                @if ($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-24 h-24 rounded-full object-cover" alt="{{ $user->nama_lengkap }}" />
                @else
                    <span class="font-headline-lg text-headline-lg text-secondary">{{ strtoupper(mb_substr($user->nama_lengkap, 0, 2)) }}</span>
                @endif
            </div>
            <div class="text-center sm:text-left">
                <h2 class="font-headline-lg text-headline-lg text-on-surface premium-heading">{{ $user->nama_lengkap }}</h2>
                <div class="flex items-center gap-2 justify-center sm:justify-start mt-1">
                    <span class="inline-flex px-2 py-0.5 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $user->role->nama_role ?? '-' }}</span>
                    <span class="inline-flex px-1.5 py-0.5 rounded bg-success/10 text-success border border-success/20 text-[9px] font-bold uppercase">{{ $user->status }}</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-2">{{ $user->email }}</p>
                @if ($user->nomor_telepon)
                    <p class="text-on-surface-variant font-body-md text-sm">{{ $user->nomor_telepon }}</p>
                @endif
            </div>
        </div>
    </section>

    <!-- Form Informasi Akun -->
    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 md:p-8 card-premium">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading mb-6">Informasi Akun</h3>
        <form method="POST" action="{{ route('superadmin.profil.update') }}" id="profil-form" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama">Nama Lengkap</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="nama" name="nama_lengkap" type="text" maxlength="150" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required />
                @error('nama_lengkap')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="email">Email</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="email" name="email" type="email" maxlength="150" value="{{ old('email', $user->email) }}" required />
                @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="telepon">Nomor Telepon</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="telepon" name="nomor_telepon" type="tel" maxlength="30" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" placeholder="+62..." />
                @error('nomor_telepon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Peran</label>
                <input class="w-full bg-surface-container border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface-variant cursor-not-allowed" type="text" value="{{ $user->role->nama_role ?? '-' }}" disabled />
            </div>
            <div class="flex justify-end">
                <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Perubahan</button>
            </div>
        </form>
    </section>

    <!-- Form Keamanan -->
    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 md:p-8 card-premium">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading mb-6">Keamanan</h3>
        <form method="POST" action="{{ route('superadmin.profil.password') }}" id="password-form" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-lama">Password Lama</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="password-lama" name="password_lama" type="password" placeholder="Masukkan password lama" required />
                @error('password_lama')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-baru">Password Baru</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="password-baru" name="password_baru" type="password" placeholder="Minimal 8 karakter" required />
                    @error('password_baru')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-konfirmasi">Konfirmasi Password</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50" id="password-konfirmasi" name="password_confirmation" type="password" placeholder="Ulangi password baru" required />
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Ubah Password</button>
            </div>
        </form>
    </section>
</div>
@endsection
