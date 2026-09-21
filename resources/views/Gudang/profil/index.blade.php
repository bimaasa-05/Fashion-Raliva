@extends('layouts.gudang')

@section('title', 'Profil')

@section('header-title', 'Profil')
@section('header-subtitle', 'Informasi akun dan penugasan gudang Anda.')

@include('partials.profil-premium-styles')

@section('content')
@include('partials.flash-toast')

<div data-skeleton class="space-y-section-gap">
    <div class="h-48 bg-surface-container-high rounded-xl animate-pulse"></div>
    <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
        <div class="lg:col-span-5 h-72 bg-surface-container-high rounded-xl animate-pulse"></div>
        <div class="lg:col-span-7 h-96 bg-surface-container-high rounded-xl animate-pulse"></div>
    </div>
</div>

@php
    $gnama = $user->nama_lengkap ?? 'Gudang';
    $gw = preg_split('/\s+/', trim($gnama));
    $gi = '';
    if(!empty($gw[0])) $gi .= mb_substr($gw[0],0,1);
    if(isset($gw[1])) $gi .= mb_substr($gw[1],0,1);
    elseif(mb_strlen($gw[0]??'')>1) $gi .= mb_substr($gw[0],1,1);
    $ginit = strtoupper(mb_substr($gi,0,2)) ?: '?';
@endphp

<div data-real class="hidden space-y-section-gap">
    <div class="w-full max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

        <!-- Kolom kiri: Hero Profil & Penugasan -->
        <div class="lg:col-span-5 space-y-8">

        <!-- Profile Hero -->
        <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow profil-hero rise">
            <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">warehouse</span>
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
                                Profil Gudang
                            </span>
                            <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant">Terakhir diperbarui {{ now()->translatedFormat('d M Y') }}</span>
                        </div>
                        <h2 class="font-display-lg name-shimmer text-4xl sm:text-5xl lg:text-5xl leading-tight tracking-tight mb-4 break-words hyphens-auto">{{ $user->nama_lengkap }}</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mx-auto lg:mx-0">{{ $user->email }}</p>
                        @if ($user->nomor_telepon)
                            <p class="font-body-md text-body-md text-on-surface-variant max-w-lg mx-auto lg:mx-0 mt-1">{{ $user->nomor_telepon }}</p>
                        @endif
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 mt-4">
                            <span class="inline-flex px-2 py-0.5 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $user->role->nama_role ?? 'Gudang' }}</span>
                            <span class="inline-flex px-1.5 py-0.5 rounded bg-success/10 text-success border border-success/20 text-[9px] font-bold uppercase">{{ ucfirst($user->status ?? 'aktif') }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mt-6">
                            <div class="profil-stat">
                                <span class="material-symbols-outlined">calendar_month</span>
                                <span class="lbl">Bergabung</span>
                                <span class="val">{{ optional($user->created_at)->translatedFormat('d M Y') ?? '-' }}</span>
                            </div>
                            <div class="profil-stat">
                                <span class="material-symbols-outlined">workspace_premium</span>
                                <span class="lbl">Role</span>
                                <span class="val">{{ $user->role->nama_role ?? 'Gudang' }}</span>
                            </div>
                            <div class="profil-stat">
                                <span class="material-symbols-outlined">verified_user</span>
                                <span class="lbl">Status</span>
                                <span class="val">{{ ucfirst($user->status ?? 'aktif') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0 mx-auto lg:mx-0">
                        <div class="photo-upload-wrapper relative inline-block">
<div class="avatar-ring">
                            <div id="hero-avatar" class="w-28 h-28 md:w-32 md:h-32 rounded-full bg-gold-accent text-white flex items-center justify-center shadow-xl photo-preview font-bold text-3xl overflow-hidden">
                            @if ($user->foto_profil_url)
                                <img id="hero-avatar-img" src="{{ $user->foto_profil_url }}" class="w-full h-full object-cover" alt="{{ $user->nama_lengkap }}" />
                                <span id="hero-avatar-initial" style="display:none">{{ $ginit }}</span>
                            @else
                                <span id="hero-avatar-initial">{{ $ginit }}</span>
                            @endif
                            </div>
                        </div>
                        <span class="status-dot" title="Status akun aktif" aria-hidden="true"></span>
                        <label for="foto_profil" class="photo-upload-label absolute bottom-0 right-0 w-8 h-8 rounded-full bg-gold-accent text-on-primary flex items-center justify-center cursor-pointer border-2 border-surface-container-lowest shadow-lg hover:scale-105">
                                <span class="material-symbols-outlined text-[18px]">camera_alt</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Penugasan & Akses -->
        <section class="rise rise-d3">
            <div class="bg-surface-container-lowest border border-muted-border rounded-xl card-premium profil-card p-6 md:p-8 relative overflow-hidden">
                <span class="card-watermark material-symbols-outlined fill absolute -right-5 -bottom-7 text-[120px] text-gold-accent/[0.05]" aria-hidden="true">warehouse</span>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg icon-tile flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px]">assignment_ind</span>
                    </div>
                    <div>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Penugasan &amp; Akses</h3>
                        <p class="text-on-surface-variant font-body-md text-sm">Toko, gudang, dan hak akses Anda.</p>
                    </div>
                </div>
                <dl class="space-y-5 font-body-md text-sm">
                    <div class="profil-row flex justify-between gap-4 pb-4"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface font-bold text-right">{{ $warehouse?->store?->nama_toko ?? '-' }}</dd></div>
                    <div class="profil-row flex justify-between gap-4 pb-4 items-start">
                        <dt class="text-on-surface-variant shrink-0">Gudang Ditugaskan</dt>
                        <dd>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-high text-on-surface text-xs font-bold border border-outline-variant whitespace-nowrap">
                                <span class="material-symbols-outlined text-[16px] text-gold-accent">warehouse</span>
                                {{ $warehouse?->nama_gudang ?? 'Belum ada' }}
                            </span>
                            <p class="text-xs text-on-surface-variant mt-2 text-right">Anda hanya dapat mengakses data gudang ini.</p>
                        </dd>
                    </div>
                    <div class="pb-1"><dt class="text-on-surface-variant text-sm mb-3 block">Hak Akses Gudang</dt></div>
                </dl>
                <div class="flex flex-wrap gap-2">
                    @foreach ([['inventory_2', 'Melihat Stok'], ['archive', 'Catat Barang Masuk'], ['unarchive', 'Catat Barang Keluar'], ['swap_horiz', 'Pindah Stok'], ['fact_check', 'Periksa Stok'], ['report', 'Lapor Rusak'], ['history', 'Lihat Riwayat']] as $perm)
                        <span class="chip-premium">
                            <span class="material-symbols-outlined text-[14px]">{{ $perm[0] }}</span>
                            {{ $perm[1] }}
                        </span>
                    @endforeach
                </div>
                <p class="profil-footnote text-xs text-on-surface-variant mt-5 flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">lock</span>
                    Role, toko, dan penugasan gudang hanya dapat diubah oleh Super Admin atau Owner.
                </p>
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
                            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Informasi Akun</h3>
                            <p class="text-on-surface-variant font-body-md text-sm">Kelola data pribadi dan kontak Anda.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('gudang.profil.update') }}" id="profil-form" class="space-y-5" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="foto_profil">Foto Profil</label>
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
                                <p id="photo-hint" class="text-on-surface-variant/60 text-xs mt-2">Klik avatar untuk ganti foto (max 2MB: JPG, PNG, WebP)</p>
                                @error('foto_profil')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama">Nama Lengkap</label>
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="nama" name="nama_lengkap" type="text" maxlength="150" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required />
                            @error('nama_lengkap')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="email">Email</label>
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="email" name="email" type="email" maxlength="150" value="{{ old('email', $user->email) }}" required />
                            @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="telepon">Nomor Telepon</label>
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="telepon" name="nomor_telepon" type="tel" maxlength="30" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" placeholder="+62..." />
                            @error('nomor_telepon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="gender-trigger">Jenis Kelamin</label>
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
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="tanggal-lahir">Tanggal Lahir</label>
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors profil-input" id="tanggal-lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d') ?? '') }}" />
                            @error('tanggal_lahir')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Peran</label>
                            <input class="w-full bg-surface-container border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface-variant cursor-not-allowed" type="text" value="{{ $user->role->nama_role ?? 'Gudang' }}" disabled />
                        </div>
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Gudang</label>
                            <input class="w-full bg-surface-container border border-muted-border rounded-lg p-4 font-body-md text-body-md text-on-surface-variant cursor-not-allowed" type="text" value="{{ $warehouse?->nama_gudang ?? 'Belum ada' }}" disabled />
                        </div>
                    </div>
                    @if($user->foto_profil_url)
                        <label class="flex items-center gap-2 text-sm text-on-surface-variant cursor-pointer">
                            <input type="checkbox" name="remove_photo" value="1" class="rounded border-muted-border text-gold-accent" />
                            Hapus foto profil saat ini
                        </label>
                    @endif
                    <div class="flex justify-end pt-4 border-t border-muted-border">
                        <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium btn-sheen inline-flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">save</span>
                            Simpan Perubahan
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
                            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Keamanan</h3>
                            <p class="text-on-surface-variant font-body-md text-sm">Ubah password untuk menjaga keamanan akun.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('gudang.profil.password') }}" id="password-form" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-lama">Password Lama</label>
                        <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="password-lama" name="password_lama" type="password" placeholder="Masukkan password lama" required />
                        @error('password_lama')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-baru">Password Baru</label>
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="password-baru" name="password_baru" type="password" placeholder="Minimal 8 karakter" required />
                            @error('password_baru')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="password-konfirmasi">Konfirmasi Password</label>
                            <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors placeholder-on-surface-variant/50 profil-input" id="password-konfirmasi" name="password_baru_confirmation" type="password" placeholder="Ulangi password baru" required />
                        </div>
                    </div>
                    <div class="flex justify-end pt-4 border-t border-muted-border">
                        <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium btn-sheen inline-flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">key</span>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </section>

        </div><!-- /Kolom kanan -->

    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
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