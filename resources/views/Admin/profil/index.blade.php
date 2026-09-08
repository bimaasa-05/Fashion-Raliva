@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('header-title', 'Profil Saya')
@section('header-subtitle', 'Kelola informasi akun Admin Toko Anda.')

@section('content')
@include('partials.flash-toast')
<div class="space-y-section-gap max-w-4xl">
    <section data-reveal class="relative bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
        <div class="relative h-28 md:h-32 bg-gradient-to-r from-gold-accent/25 via-gold-accent/10 to-transparent">
            <span class="material-symbols-outlined absolute right-8 -bottom-6 text-[110px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">storefront</span>
        </div>
        @php
            $anama = $user->nama_lengkap ?? 'Admin';
            $aw = preg_split('/\s+/', trim($anama));
            $ai = '';
            if(!empty($aw[0])) $ai .= mb_substr($aw[0],0,1);
            if(isset($aw[1])) $ai .= mb_substr($aw[1],0,1);
            elseif(mb_strlen($aw[0]??'')>1) $ai .= mb_substr($aw[0],1,1);
            $ainit = strtoupper(mb_substr($ai,0,2)) ?: '?';
        @endphp
        <div class="px-6 md:px-8 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-12">
                <button type="button" data-modal-open="modal-foto" class="relative w-24 h-24 rounded-xl ring-4 ring-surface-container-lowest bg-gold-accent text-white flex items-center justify-center font-bold text-2xl shrink-0 mx-auto sm:mx-0 border border-gold-accent/30 overflow-hidden group shrink-0 hover:ring-gold-accent/40 transition-all">
                    @if($user->foto_profil_url)
                        <img src="{{ $user->foto_profil_url }}" alt="{{ $user->nama_lengkap }}" class="w-full h-full object-cover" />
                    @else
                        {{ $ainit }}
                    @endif
                    <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">photo_camera</span>
                    </span>
                </button>
                <div class="text-center sm:text-left flex-grow pb-1 min-w-0">
                    <div class="flex items-center gap-3 justify-center sm:justify-start flex-wrap">
                        <h2 class="raliva-figure text-[26px] text-on-surface">{{ $user->nama_lengkap }}</h2>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[14px]">badge</span>{{ $roleName }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary border border-secondary/20 font-label-sm text-[10px] uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Aktif</span>
                    </div>
                    <p class="text-on-surface-variant font-body-md text-sm mt-2 flex items-center justify-center sm:justify-start gap-4 flex-wrap">
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">mail</span>{{ $user->email }}</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">call</span>{{ $user->nomor_telepon ?? '-' }}</span>
                    </p>
                    @if($user->created_at)
                        <p class="text-xs text-on-surface-variant flex items-center justify-center sm:justify-start gap-1.5 mt-2">
                            <span class="material-symbols-outlined text-[14px]">event_available</span>
                            Bergabung sejak {{ $user->created_at->translatedFormat('d F Y') }}
                        </p>
                    @endif
                </div>
                <button type="button" data-modal-open="modal-foto" class="px-5 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest hover:border-gold-accent hover:text-gold-accent transition-colors shrink-0">Ganti Foto</button>
            </div>
            <p class="font-body-md text-on-surface-variant text-sm mt-4 max-w-2xl">Operator operasional harian toko — berhubungan langsung dengan customer, pesanan, pembayaran, pengiriman, dan komplain.</p>
        </div>
    </section>

    <section data-reveal-group class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">storefront</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Toko Ditugaskan</span>
            <span class="raliva-figure text-[26px] text-on-surface relative">{{ $assignedStores->count() }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">badge</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Role</span>
            <span class="raliva-figure text-[26px] text-gold-accent relative">{{ $roleName }}</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">verified_user</span>
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Status Akun</span>
            <span class="raliva-figure text-[26px] text-secondary relative">{{ $user->status ?? 'aktif' }}</span>
        </div>
    </section>

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading mb-6">Toko yang Ditugaskan</h3>
        @if($assignedStores->isEmpty())
            <div class="border border-dashed border-muted-border rounded-lg px-4 py-8 text-center">
                <span class="material-symbols-outlined text-[32px] text-on-surface-variant">storefront</span>
                <p class="text-on-surface-variant text-sm mt-2">Belum ada toko yang ditugaskan untuk Anda.</p>
            </div>
        @else
            @php
                $storeBadgeMap = [
                    \App\Models\Store::STATUS_AKTIF => ['label' => 'Aktif', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
                    \App\Models\Store::STATUS_PENDING => ['label' => 'Pending', 'class' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30'],
                    \App\Models\Store::STATUS_NONAKTIF => ['label' => 'Nonaktif', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                    \App\Models\Store::STATUS_DITOLAK => ['label' => 'Ditolak', 'class' => 'bg-error/10 text-error border-error/20'],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                @foreach ($assignedStores as $assignment)
                    @php
                        $store = $assignment->store;
                        $badge = $storeBadgeMap[$store?->status] ?? ['label' => ucfirst($store?->status ?? '-'), 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
                    @endphp
                    <div class="flex items-start justify-between gap-3 p-4 border border-muted-border rounded-lg bg-surface-container-low">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center font-label-sm text-label-sm shrink-0">{{ \Illuminate\Support\Str::upper($store?->nama_toko ? \Illuminate\Support\Str::substr($store->nama_toko, 0, 2) : '?') }}</div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-title-md text-title-md text-on-surface truncate">{{ $store?->nama_toko ?? '-' }}</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full border font-label-sm text-[10px] uppercase tracking-wider {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                </div>
                                <p class="text-on-surface-variant text-xs mt-0.5">Ditugaskan sejak {{ $assignment->tanggal_penugasan?->translatedFormat('M Y') ?? '-' }}</p>
                                @if($store?->alamat)
                                    <p class="text-on-surface-variant text-xs mt-0.5 flex items-start gap-1 min-w-0">
                                        <span class="material-symbols-outlined text-[13px] mt-px shrink-0">location_on</span>
                                        <span class="truncate">{{ $store->alamat }}</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-gold-accent text-[20px] shrink-0">check_circle</span>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 space-y-gutter card-premium">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Informasi Akun</h3>
        <form class="space-y-gutter" method="POST" action="{{ route('admin.profil.update') }}">
            @csrf
            @method('PUT')
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
                    <input class="raliva-input bg-surface-container-low text-on-surface-variant cursor-not-allowed" id="role" type="text" value="{{ $roleName }}" disabled />
                </div>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Perubahan</button>
            </div>
        </form>
    </section>

    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 space-y-gutter card-premium">
        <h3 class="font-title-md text-title-md text-on-surface premium-heading">Keamanan</h3>
        <form class="space-y-gutter" method="POST" action="{{ route('admin.profil.password') }}">
            @csrf
            @method('PUT')
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
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl overflow-hidden">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Ganti Foto Profil</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">Unggah Foto Baru</h3>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.profil.foto') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="flex flex-col items-center gap-4">
                <div class="w-28 h-28 rounded-full overflow-hidden border border-outline-variant bg-surface-container-low">
                    <img id="af-preview" alt="Preview Foto" class="w-full h-full object-cover" src="{{ $user->foto_profil_url ?? 'https://ui-avatars.com/api/?name='.urlencode($anama ?? 'Admin').'&background=FF4F87&color=fff&size=112' }}" />
                </div>
                <div class="w-full">
                    <label for="af-foto" class="block raliva-label mb-2">Pilih Foto Baru</label>
                    <input id="af-foto" name="foto_profil" type="file" accept="image/*" required class="w-full text-sm text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border file:border-muted-border file:bg-surface-container-low file:text-sm" onchange="if(this.files[0]){const r=new FileReader();r.onload=e=>document.getElementById('af-preview').src=e.target.result;r.readAsDataURL(this.files[0]);}" />
                    <p class="text-xs text-on-surface-variant mt-1">JPG/PNG/WebP, maks 2MB.</p>
                    @error('foto_profil') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border text-on-surface font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-tertiary-container transition-colors btn-premium">Unggah</button>
            </div>
        </form>
    </div>
</div>
@endsection