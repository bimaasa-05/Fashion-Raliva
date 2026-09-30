@extends('layouts.superadmin')

@section('title', __('Profil Saya'))

@section('header-title', __('Profil Saya'))
@section('header-badge', __('Kelola'))

@section('header-subtitle', __('Kelola informasi akun dan keamanan Anda.'))

@include('partials.profil-premium-styles')

@section('content')
@include('partials.flash-toast')

<div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

    <!-- Kolom kiri: Hero Profil -->
    <div class="lg:col-span-5">

    <!-- Profile Hero -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow profil-hero rise">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">person</span>
        <span class="hero-ornt tl" aria-hidden="true"></span>
        <span class="hero-ornt tr" aria-hidden="true"></span>
        <span class="hero-ornt bl" aria-hidden="true"></span>
        <span class="hero-ornt br" aria-hidden="true"></span>
        <div class="gold-dust" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i></div>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                <div class="flex-1 min-w-0 text-center lg:text-left">
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold uppercase tracking-wider border border-gold-accent/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-gold-accent"></span>
                            {{ __('Profil Super Admin') }}
                        </span>
                        <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant">Terakhir diperbarui {{ now()->translatedFormat('d M Y') }}</span>
                    </div>
                    <h2 class="font-display-lg name-shimmer text-4xl sm:text-5xl lg:text-5xl leading-tight tracking-tight mb-4 break-words hyphens-auto">{{ $user->nama_lengkap }}</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mx-auto lg:mx-0">{{ $user->email }}</p>
                    @if ($user->nomor_telepon)
                        <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mx-auto lg:mx-0 mt-1">{{ $user->nomor_telepon }}</p>
                    @endif
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 mt-4">
                        <span class="inline-flex px-2 py-0.5 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $user->role->nama_role ?? '-' }}</span>
                        <span class="inline-flex px-1.5 py-0.5 rounded bg-success/10 text-success border border-success/20 text-[9px] font-bold uppercase">{{ $user->status }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-3 mt-6">
                        <div class="profil-stat">
                            <span class="material-symbols-outlined">calendar_month</span>
                            <span class="lbl">{{ __('Bergabung') }}</span>
                            <span class="val">{{ optional($user->created_at)->translatedFormat('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="profil-stat">
                            <span class="material-symbols-outlined">workspace_premium</span>
                            <span class="lbl">{{ __('Role') }}</span>
                            <span class="val">{{ $user->role->nama_role ?? '-' }}</span>
                        </div>
                        <div class="profil-stat">
                            <span class="material-symbols-outlined">verified_user</span>
                            <span class="lbl">{{ __('Status') }}</span>
                            <span class="val">{{ ucfirst($user->status ?? 'aktif') }}</span>
                        </div>
                    </div>
                </div>

                @php
                    $snama = $user->nama_lengkap ?? 'Super Admin';
                    $sw = preg_split('/\s+/', trim($snama));
                    $si = '';
                    if(!empty($sw[0])) $si .= mb_substr($sw[0],0,1);
                    if(isset($sw[1])) $si .= mb_substr($sw[1],0,1);
                    elseif(mb_strlen($sw[0]??'')>1) $si .= mb_substr($sw[0],1,1);
                    $sinit = strtoupper(mb_substr($si,0,2)) ?: '?';
                @endphp
                <div class="shrink-0 mx-auto lg:mx-0">
                    <div class="photo-upload-wrapper relative inline-block">
                        <div class="avatar-ring">
                            <div id="hero-avatar" class="w-28 h-28 md:w-32 md:h-32 rounded-full bg-gold-accent text-white flex items-center justify-center shadow-xl photo-preview font-bold text-3xl overflow-hidden">
                            @if ($user->foto_profil_url)
                                <img id="hero-avatar-img" src="{{ $user->foto_profil_url }}" class="w-full h-full object-cover" alt="{{ $user->nama_lengkap }}" />
                                <span id="hero-avatar-initial" style="display:none">{{ $sinit }}</span>
                            @else
                                <span id="hero-avatar-initial">{{ $sinit }}</span>
                            @endif
                            </div>
                        </div>
                        <span class="status-dot" title="{{ __('Status akun aktif') }}" aria-hidden="true"></span>
                        <label for="foto_profil" class="photo-upload-label absolute bottom-0 right-0 w-8 h-8 rounded-full bg-gold-accent text-on-primary flex items-center justify-center cursor-pointer border-2 border-surface-container-lowest shadow-lg hover:scale-105">
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
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl card-premium profil-card p-6 md:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg icon-tile flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px]">badge</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">{{ __('Informasi Akun') }}</h3>
                        <p class="text-on-surface-variant font-body-md text-sm">{{ __('Kelola data pribadi dan kontak Anda.') }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('superadmin.profil.update') }}" id="profil-form" class="space-y-5" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="foto_profil">{{ __('Foto Profil') }}</label>
                        <div class="photo-upload-wrapper">
                            <div id="form-avatar" class="w-24 h-24 rounded-full bg-secondary-container flex items-center justify-center border-2 border-muted-border overflow-hidden photo-preview">
                                @if ($user->foto_profil_url)
                                    <img id="form-avatar-img" src="{{ $user->foto_profil_url }}" class="w-full h-full object-cover" alt="{{ $user->nama_lengkap }}" />
                                @else
                                    <span id="form-avatar-initial" class="font-title-lg text-title-lg text-white">{{ strtoupper(mb_substr($user->nama_lengkap, 0, 2)) }}</span>
                                @endif
                            </div>
                            <label for="foto_profil" class="photo-upload-label absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-gold-accent text-on-primary flex items-center justify-center cursor-pointer border-2 border-surface-container-lowest shadow hover:scale-105">
                                <span class="material-symbols-outlined text-[18px]">camera_alt</span>
                            </label>
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*" onchange="previewPhoto(this)" />
                            <p id="photo-hint" class="text-on-surface-variant/60 text-xs mt-2">{{ __('Klik avatar untuk ganti foto (max 2MB: JPG, PNG, WebP)') }}</p>
                            @error('foto_profil')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama">{{ __('Nama Lengkap') }}</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="nama" name="nama_lengkap" type="text" maxlength="150" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required />
                        @error('nama_lengkap')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="email">{{ __('Email') }}</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="email" name="email" type="email" maxlength="150" value="{{ old('email', $user->email) }}" required />
                        @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="telepon">{{ __('Nomor Telepon') }}</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="telepon" name="nomor_telepon" type="tel" maxlength="30" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" placeholder="+62..." />
                        @error('nomor_telepon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="gender-trigger">{{ __('Jenis Kelamin') }}</label>
                        @php
                            $genderVal = old('gender', $user->gender);
                            $genderLabel = $genderVal === 'male' ? 'Laki-laki' : ($genderVal === 'female' ? 'Perempuan' : '—');
                        @endphp
                        <div class="relative" data-cs>
                            <button type="button" data-cs-trigger id="gender-trigger" aria-haspopup="listbox" aria-expanded="false"
                                class="w-full flex items-center justify-between gap-2 bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md text-left text-on-surface cursor-pointer focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors profil-input">
                                <span data-cs-label class="truncate">{{ $genderLabel }}</span>
                                <span data-cs-chevron class="material-symbols-outlined text-[16px] text-on-surface-variant transition-transform duration-200">expand_more</span>
                            </button>
                            <div data-cs-menu
                                class="hidden absolute left-0 right-0 top-full mt-2 z-50 bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl overflow-hidden py-1">
                                @foreach (['' => '—', 'male' => 'Laki-laki', 'female' => 'Perempuan'] as $gKey => $gLabel)
                                    <button type="button" role="option" data-cs-option="{{ $gKey }}" data-cs-option-label="{{ $gLabel }}"
                                        class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                                        {{ $gLabel }}<span data-cs-check class="material-symbols-outlined text-[18px] text-gold-accent {{ ($genderVal ?? '') === $gKey ? '' : 'hidden' }}">check</span>
                                    </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="gender" value="{{ $genderVal }}" data-cs-input />
                        </div>
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="tanggal-lahir">{{ __('Tanggal Lahir') }}</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors profil-input" id="tanggal-lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d') ?? '') }}" />
                        @error('tanggal_lahir')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">{{ __('Peran') }}</label>
                        <input class="w-full bg-surface-container border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface-variant cursor-not-allowed" type="text" value="{{ $user->role->nama_role ?? '-' }}" disabled />
                    </div>
                </div>
                @if($user->foto_profil_url)
                    <label class="flex items-center gap-2 text-sm text-on-surface-variant cursor-pointer">
                        <input type="checkbox" name="remove_photo" value="1" class="rounded border-muted-border text-gold-accent" />
                        {{ __('Hapus foto profil saat ini') }}
                    </label>
                @endif
                <div class="flex justify-end pt-4 border-t border-muted-border">
                    <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium btn-sheen inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        {{ __('Simpan Perubahan') }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Keamanan -->
    <section class="rise rise-d2">
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl card-premium profil-card p-6 md:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg icon-tile flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px]">lock</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">{{ __('Keamanan') }}</h3>
                        <p class="text-on-surface-variant font-body-md text-sm">{{ __('Ubah password untuk menjaga keamanan akun.') }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('superadmin.profil.password') }}" id="password-form" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-lama">{{ __('Password Lama') }}</label>
                    <div class="relative">
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 pr-12 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="password-lama" name="password_lama" type="password" placeholder="{{ __('Masukkan password lama') }}" required />
                        <button type="button" data-pw-toggle="password-lama" aria-label="{{ __('Lihat password') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent transition-colors">
                            <span class="material-symbols-outlined text-[20px]">visibility</span>
                        </button>
                    </div>
                    @error('password_lama')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-baru">{{ __('Password Baru') }}</label>
                        <div class="relative">
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 pr-12 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="password-baru" name="password_baru" type="password" placeholder="{{ __('Minimal 8 karakter') }}" required />
                            <button type="button" data-pw-toggle="password-baru" aria-label="{{ __('Lihat password') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent transition-colors">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                        @error('password_baru')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-konfirmasi">{{ __('Konfirmasi Password') }}</label>
                        <div class="relative">
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 pr-12 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="password-konfirmasi" name="password_baru_confirmation" type="password" placeholder="{{ __('Ulangi password baru') }}" required />
                            <button type="button" data-pw-toggle="password-konfirmasi" aria-label="{{ __('Lihat password') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-gold-accent transition-colors">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-muted-border">
                    <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium btn-sheen inline-flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">key</span>
                        {{ __('Ubah Password') }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    </div><!-- /Kolom kanan -->

</div>

<!-- Language -->
<section class="rise rise-d3 w-full mt-8 lg:mt-10">
    <div class="bg-surface-container-lowest border border-muted-border rounded-xl card-premium profil-card p-6 md:p-8 relative overflow-hidden">
        <span class="card-watermark material-symbols-outlined fill absolute -right-5 -bottom-7 text-[120px] text-gold-accent/[0.05]" aria-hidden="true">translate</span>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg icon-tile flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[22px]">language</span>
            </div>
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">{{ __('Language') }}</h3>
                <p class="text-on-surface-variant font-body-md text-sm">{{ __('Pilih bahasa tampilan.') }}</p>
            </div>
        </div>
        <form id="staff-language-form" action="{{ route('customer.locale.switch') }}" method="POST">
            @csrf
            <input type="hidden" name="session_area" value="superadmin">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                <label class="flex items-center justify-center py-sm border-2 {{ app()->getLocale() === 'en' ? 'border-secondary' : 'border-outline-variant' }} rounded-xl bg-surface-container-low cursor-pointer hover:border-secondary transition-colors">
                    <input {{ app()->getLocale() === 'en' ? 'checked' : '' }} class="sr-only" name="locale" type="radio" value="en" onchange="document.getElementById('staff-language-form').submit()"/>
                    <span class="material-symbols-outlined text-[20px] mr-xs">language</span>
                    <span class="font-body-sm text-body-sm {{ app()->getLocale() === 'en' ? 'font-semibold' : '' }}">{{ __('English') }}</span>
                </label>
                <label class="flex items-center justify-center py-sm border-2 {{ app()->getLocale() === 'id' ? 'border-secondary' : 'border-outline-variant' }} rounded-xl bg-surface-container-low cursor-pointer hover:border-secondary transition-colors">
                    <input {{ app()->getLocale() === 'id' ? 'checked' : '' }} class="sr-only" name="locale" type="radio" value="id" onchange="document.getElementById('staff-language-form').submit()"/>
                    <span class="material-symbols-outlined text-[20px] mr-xs">translate</span>
                    <span class="font-body-sm text-body-sm {{ app()->getLocale() === 'id' ? 'font-semibold' : '' }}">{{ __('Bahasa Indonesia') }}</span>
                </label>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
@include('partials.form-submit-guard')
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