@extends('layouts.produksi')

@section('title', 'Profil')

@section('header-title', 'Profil')
@section('header-subtitle', 'Informasi akun dan penugasan produksi Anda.')

@section('content')
@include('partials.flash-toast')
<div data-skeleton class="space-y-section-gap">
    <div class="h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
</div>

@php
    $gnama = $user->nama_lengkap ?? 'Produksi';
    $gw = preg_split('/\s+/', trim($gnama));
    $gi = '';
    if(!empty($gw[0])) $gi .= mb_substr($gw[0],0,1);
    if(isset($gw[1])) $gi .= mb_substr($gw[1],0,1);
    elseif(mb_strlen($gw[0]??'')>1) $gi .= mb_substr($gw[0],1,1);
    $ginit = strtoupper(mb_substr($gi,0,2)) ?: '?';
@endphp
<div data-real class="hidden space-y-section-gap">
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-gold-accent text-white flex items-center justify-center font-bold text-xl shrink-0 mx-auto sm:mx-0 border border-gold-accent/30 overflow-hidden">
                @if ($user->foto_profil_url)
                    <img src="{{ $user->foto_profil_url }}" alt="{{ $gnama }}" class="w-full h-full object-cover" />
                @else
                    {{ $ginit }}
                @endif
            </div>
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center sm:justify-start">
                    <h2 class="raliva-figure text-[26px] text-on-surface">{{ $user->nama_lengkap ?? '-' }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 raliva-label text-[10px] w-fit mx-auto sm:mx-0">{{ $roleName }}</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">{{ $user->email ?? '-' }} • {{ $user->nomor_telepon ?? '-' }}</p>
            </div>
            <button type="button" data-modal-open="modal-edit-profil" class="px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium shrink-0">Edit Profil</button>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Informasi Akun</h2>
            <dl class="space-y-5 font-body-md text-sm">
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nama Lengkap</dt><dd class="text-on-surface font-bold text-right">{{ $user->nama_lengkap ?? '-' }}</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Email</dt><dd class="text-on-surface text-right break-all">{{ $user->email ?? '-' }}</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nomor HP</dt><dd class="text-on-surface text-right">{{ $user->nomor_telepon ?? '-' }}</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Jenis Kelamin</dt><dd class="text-on-surface text-right">{{ $user->gender === 'male' ? 'Laki-laki' : ($user->gender === 'female' ? 'Perempuan' : '-') }}</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Tanggal Lahir</dt><dd class="text-on-surface text-right">{{ $user->tanggal_lahir?->translatedFormat('d F Y') ?? '-' }}</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Role</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $roleName }}</span></dd></div>
                <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant shrink-0">Status Akun</dt><dd><span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>{{ ucfirst($user->status ?? 'aktif') }}</span></dd></div>
            </dl>
        </section>

        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Keamanan Akun</h2>
            <form method="POST" action="{{ route('produksi.profil.password') }}" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="pw-lama" class="block raliva-label mb-2">Kata Sandi Saat Ini</label>
                    <input id="pw-lama" name="password_lama" type="password" required autocomplete="current-password" class="raliva-input" />
                    @error('password_lama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                    <div>
                        <label for="pw-baru" class="block raliva-label mb-2">Kata Sandi Baru</label>
                        <input id="pw-baru" name="password_baru" type="password" required minlength="8" autocomplete="new-password" class="raliva-input" placeholder="Minimal 8 karakter, 1 huruf kapital & 1 angka" />
                        @error('password_baru') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="pw-konfirmasi" class="block raliva-label mb-2">Konfirmasi Kata Sandi</label>
                        <input id="pw-konfirmasi" name="password_baru_confirmation" type="password" required minlength="8" autocomplete="new-password" class="raliva-input" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Perbarui Kata Sandi</button>
                </div>
            </form>
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
            <form method="POST" action="{{ route('produksi.profil.update') }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="block raliva-label mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap ?? '') }}" required maxlength="150" class="raliva-input" />
                    @error('nama_lengkap') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block raliva-label mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required maxlength="150" class="raliva-input" />
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block raliva-label mb-2">Nomor HP</label>
                    <input type="text" name="nomor_telepon" maxlength="30" value="{{ old('nomor_telepon', $user->nomor_telepon ?? '') }}" class="raliva-input" placeholder="08..." />
                    @error('nomor_telepon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block raliva-label mb-2">Jenis Kelamin</label>
                        <select name="gender" class="raliva-input">
                            <option value="" {{ old('gender', $user->gender) === null ? 'selected' : '' }}>—</option>
                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d') ?? '') }}" class="raliva-input" />
                        @error('tanggal_lahir') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Foto Profil <span class="text-on-surface-variant font-normal">(opsional)</span></label>
                    <img id="produksi-foto-preview" alt="Pratinjau Foto Profil" src="{{ $user->foto_profil_url ?? '' }}" class="w-20 h-20 rounded-full object-cover border-2 border-gold-accent/40 mb-2 {{ $user->foto_profil_url ? '' : 'hidden' }}" />
                    <input type="file" name="foto_profil" accept="image/*" data-photo-preview="produksi-foto-preview" class="w-full text-sm text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border file:border-muted-border file:bg-surface-container-low file:text-sm" />
                    <p class="text-xs text-on-surface-variant mt-1">JPG/PNG/WebP, maks 2MB. Kosongkan jika tidak ingin mengubah.</p>
                    @error('foto_profil') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                @if($user->foto_profil_url)
                    <label class="flex items-center gap-2 text-sm text-on-surface-variant cursor-pointer">
                        <input type="checkbox" name="remove_photo" value="1" class="rounded border-muted-border text-gold-accent" />
                        Hapus foto profil saat ini
                    </label>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block raliva-label mb-2">Role</label>
                        <input type="text" value="{{ $roleName }}" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Workshop</label>
                        <input type="text" value="Atelier Produksi Raliva" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
                    Perubahan role dan penugasan tidak diizinkan pada akun Anda.
                </p>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@include('partials.profile-photo-preview')