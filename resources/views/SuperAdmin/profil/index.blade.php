@extends('layouts.superadmin')

@section('title', 'Profil Saya')

@section('header-title', 'Profil Saya')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola informasi akun dan keamanan Anda.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }

    .text-gradient-gold {
        background: linear-gradient(115deg, #a8823a 0%, #C9A24D 35%, #ecd398 55%, #C9A24D 80%, #a8823a 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .hero-glow::before {
        content: '';
        position: absolute;
        inset: -30%;
        background: radial-gradient(circle at 70% 30%, rgba(201, 162, 77, 0.14), transparent 45%),
                    radial-gradient(circle at 15% 85%, rgba(201, 162, 77, 0.08), transparent 40%);
        pointer-events: none;
    }

    @keyframes riseIn {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .rise { opacity: 0; animation: riseIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
    .rise-d1 { animation-delay: 0.1s; }
    .rise-d2 { animation-delay: 0.2s; }
    .rise-d3 { animation-delay: 0.3s; }

    .photo-upload-wrapper { position: relative; }
    .photo-upload-wrapper input[type="file"] { display: none; }
    .photo-preview { transition: all 0.2s ease; }
    .photo-preview:hover { transform: scale(1.02); }
    .photo-upload-label { transition: all 0.2s ease; }
    .photo-upload-label:hover { background-color: rgba(201, 162, 77, 0.1); border-color: #C9A24D; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

    <!-- Kolom kiri: Hero Profil -->
    <div class="lg:col-span-5">

    <!-- Profile Hero -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow rise">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">person</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                <div class="flex-1 min-w-0 text-center lg:text-left">
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold uppercase tracking-wider border border-gold-accent/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-gold-accent"></span>
                            Profil Super Admin
                        </span>
                        <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant">Terakhir diperbarui {{ now()->translatedFormat('d M Y') }}</span>
                    </div>
                    <h2 class="font-display-lg text-gradient-gold text-4xl sm:text-5xl lg:text-5xl leading-tight tracking-tight mb-4 break-words hyphens-auto">{{ $user->nama_lengkap }}</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mx-auto lg:mx-0">{{ $user->email }}</p>
                    @if ($user->nomor_telepon)
                        <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mx-auto lg:mx-0 mt-1">{{ $user->nomor_telepon }}</p>
                    @endif
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 mt-4">
                        <span class="inline-flex px-2 py-0.5 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $user->role->nama_role ?? '-' }}</span>
                        <span class="inline-flex px-1.5 py-0.5 rounded bg-success/10 text-success border border-success/20 text-[9px] font-bold uppercase">{{ $user->status }}</span>
                    </div>
                </div>

                <div class="shrink-0 mx-auto lg:mx-0">
                    <div class="photo-upload-wrapper relative inline-block">
                        <div id="hero-avatar" class="w-28 h-28 md:w-32 md:h-32 rounded-full bg-secondary-container flex items-center justify-center border-4 border-surface-container-lowest shadow-xl overflow-hidden photo-preview">
                            @if ($user->foto_profil_url)
                                <img id="hero-avatar-img" src="{{ $user->foto_profil_url }}" class="w-full h-full object-cover" alt="{{ $user->nama_lengkap }}" />
                            @else
                                <span id="hero-avatar-initial" class="font-display-lg text-display-lg text-secondary">{{ strtoupper(mb_substr($user->nama_lengkap, 0, 2)) }}</span>
                            @endif
                        </div>
                        <label for="foto_profil" class="photo-upload-label absolute bottom-0 right-0 w-8 h-8 rounded-full bg-gold-accent text-on-primary flex items-center justify-center cursor-pointer border-3 border-surface-container-lowest shadow-lg hover:scale-105">
                            <span class="material-symbols-outlined text-[18px]">camera_alt</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </section>

    </div><!-- /Kolom kiri -->

    <!-- Kolom kanan: Informasi Akun & Keamanan -->
    <div class="lg:col-span-7 space-y-8">

    <!-- Informasi Akun -->
    <section class="rise rise-d1">
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl card-premium p-6 md:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-secondary-container/20 border border-secondary/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-secondary text-[22px]">badge</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Informasi Akun</h3>
                        <p class="text-on-surface-variant font-body-md text-sm">Kelola data pribadi dan kontak Anda.</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('superadmin.profil.update') }}" id="profil-form" class="space-y-5" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="foto_profil">Foto Profil</label>
                        <div class="photo-upload-wrapper">
                            <div id="form-avatar" class="w-24 h-24 rounded-full bg-secondary-container flex items-center justify-center border-2 border-muted-border overflow-hidden photo-preview">
                                @if ($user->foto_profil_url)
                                    <img id="form-avatar-img" src="{{ $user->foto_profil_url }}" class="w-full h-full object-cover" alt="{{ $user->nama_lengkap }}" />
                                @else
                                    <span id="form-avatar-initial" class="font-title-lg text-title-lg text-secondary">{{ strtoupper(mb_substr($user->nama_lengkap, 0, 2)) }}</span>
                                @endif
                            </div>
                            <label for="foto_profil" class="photo-upload-label absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-gold-accent text-on-primary flex items-center justify-center cursor-pointer border-2 border-surface-container-lowest shadow hover:scale-105">
                                <span class="material-symbols-outlined text-[18px]">camera_alt</span>
                            </label>
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*" onchange="previewPhoto(this)" />
                            <p id="photo-hint" class="text-on-surface-variant/60 text-xs mt-2">Klik avatar untuk ganti foto (max 2MB: JPG, PNG, WebP)</p>
                            @error('foto_profil')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="md:col-span-2">
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
                </div>
                <div class="flex justify-end pt-4 border-t border-muted-border">
                    <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Keamanan -->
    <section class="rise rise-d2">
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl card-premium p-6 md:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-error/15 border border-error/20 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-error text-[22px]">lock</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Keamanan</h3>
                        <p class="text-on-surface-variant font-body-md text-sm">Ubah password untuk menjaga keamanan akun.</p>
                    </div>
                </div>
            </div>

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
                <div class="flex justify-end pt-4 border-t border-muted-border">
                    <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">key</span>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </section>

    </div><!-- /Kolom kanan -->

</div>
@endsection

@push('scripts')
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update hero avatar
                const heroImg = document.getElementById('hero-avatar-img');
                const heroInitial = document.getElementById('hero-avatar-initial');
                if (heroImg) {
                    heroImg.src = e.target.result;
                    heroImg.style.display = 'block';
                    if (heroInitial) heroInitial.style.display = 'none';
                } else if (heroInitial) {
                    heroInitial.style.display = 'none';
                    const newImg = document.createElement('img');
                    newImg.id = 'hero-avatar-img';
                    newImg.src = e.target.result;
                    newImg.className = 'w-full h-full object-cover';
                    newImg.alt = '{{ $user->nama_lengkap }}';
                    document.getElementById('hero-avatar').appendChild(newImg);
                }

                // Update form avatar
                const formImg = document.getElementById('form-avatar-img');
                const formInitial = document.getElementById('form-avatar-initial');
                if (formImg) {
                    formImg.src = e.target.result;
                    formImg.style.display = 'block';
                    if (formInitial) formInitial.style.display = 'none';
                } else if (formInitial) {
                    formInitial.style.display = 'none';
                    const newImg = document.createElement('img');
                    newImg.id = 'form-avatar-img';
                    newImg.src = e.target.result;
                    newImg.className = 'w-full h-full object-cover';
                    newImg.alt = '{{ $user->nama_lengkap }}';
                    document.getElementById('form-avatar').appendChild(newImg);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush