@extends('layouts.gudang')

@section('title', 'Profil')

@section('header-title', 'Profil')
@section('header-subtitle', 'Informasi akun dan penugasan gudang Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    <section class="rise relative bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden card-premium">
        <div class="relative h-28 md:h-32 bg-gradient-to-r from-gold-accent/25 via-gold-accent/10 to-transparent">
            <span class="material-symbols-outlined absolute right-8 -bottom-6 text-[110px] text-gold-accent/15 pointer-events-none select-none" aria-hidden="true">warehouse</span>
        </div>
        <div class="px-6 md:px-8 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-end gap-4 -mt-12">
                <div class="w-24 h-24 rounded-2xl ring-4 ring-surface-container-lowest overflow-hidden bg-surface-container-high shadow-xl shrink-0 mx-auto sm:mx-0">
                    @if ($user->foto_profil_url)
                        <img alt="Foto Profil {{ $user->nama_lengkap }}" class="w-full h-full object-cover" src="{{ $user->foto_profil_url }}" />
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gold-accent/10 text-gold-accent">
                            <span class="material-symbols-outlined text-[40px]">person</span>
                        </div>
                    @endif
                </div>
                <div class="flex-grow text-center sm:text-left pb-1 min-w-0">
                    <div class="flex items-center gap-3 justify-center sm:justify-start flex-wrap">
                        <h2 class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">{{ $user->nama_lengkap ?? '-' }}</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider">{{ $user->role->nama_role ?? 'Gudang' }}</span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary border border-secondary/20 font-label-sm text-[10px] uppercase tracking-wider"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>{{ ucfirst($user->status ?? 'aktif') }}</span>
                    </div>
                    <p class="text-on-surface-variant font-body-md text-sm mt-2 flex items-center justify-center sm:justify-start gap-4 flex-wrap">
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">mail</span>{{ $user->email ?? '-' }}</span>
                        <span class="inline-flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">call</span>{{ $user->nomor_telepon ?? '-' }}</span>
                    </p>
                </div>
                <button type="button" data-modal-open="modal-edit-profil" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">Edit Profil</button>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Informasi Akun</h2>
            <div class="grid sm:grid-cols-2 gap-gutter">
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">person</span></div>
                    <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Nama Lengkap</span><span class="font-title-md text-title-md text-on-surface block truncate">{{ $user->nama_lengkap ?? '-' }}</span></div>
                </div>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">mail</span></div>
                    <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Email</span><span class="font-title-md text-sm text-on-surface block truncate">{{ $user->email ?? '-' }}</span></div>
                </div>
                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[18px]">call</span></div>
                    <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Nomor HP</span><span class="font-title-md text-title-md text-on-surface block truncate">{{ $user->nomor_telepon ?? '-' }}</span></div>
                </div>
                <div class="bg-surface-container-low border border-secondary/20 rounded-lg p-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-secondary-container/30 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-secondary text-[18px]">verified_user</span></div>
                    <div class="min-w-0"><span class="block text-[10px] font-label-sm text-on-surface-variant uppercase tracking-widest">Status Akun</span><span class="font-title-md text-title-md text-secondary block">{{ ucfirst($user->status ?? 'aktif') }}</span></div>
                </div>
            </div>
        </section>

        <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Penugasan &amp; Akses</h2>
            <dl class="space-y-5 font-body-md text-sm">
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface font-bold text-right">{{ $warehouse?->store?->nama_toko ?? '-' }}</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border items-start">
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
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant font-label-sm text-[11px]">
                        <span class="material-symbols-outlined text-[14px] text-secondary">{{ $perm[0] }}</span>
                        {{ $perm[1] }}
                    </span>
                @endforeach
            </div>
            <p class="text-xs text-on-surface-variant mt-5 flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">lock</span>
                Role, toko, dan penugasan gudang hanya dapat diubah oleh Super Admin atau Owner.
            </p>
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
            <form action="{{ route('gudang.profil.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap ?? '' }}" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Email</label>
                    <input type="email" name="email" value="{{ $user->email ?? '' }}" required class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Nomor HP</label>
                    <input type="text" name="nomor_telepon" value="{{ $user->nomor_telepon ?? '' }}" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                </div>
                <div>
                    <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Foto Profil</label>
                    <input type="file" name="foto_profil" accept="image/*" class="w-full bg-surface-container-lowest border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-gold-accent/10 file:text-gold-accent hover:file:bg-gold-accent/20" />
                    <p class="text-xs text-on-surface-variant mt-1">Maks 2MB. Kosongkan jika tidak ingin mengubah.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Role</label>
                        <input type="text" value="{{ $user->role->nama_role ?? 'Gudang' }}" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-2">Gudang</label>
                        <input type="text" value="{{ $warehouse?->nama_gudang ?? 'Belum ada' }}" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
                    Perubahan role dan penugasan gudang tidak diizinkan pada akun Anda.
                </p>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
