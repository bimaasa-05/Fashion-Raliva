@extends('layouts.owner')

@section('title', 'Profil')

@section('header-title', 'Profil')
@section('header-subtitle', 'Kelola informasi akun Owner Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-72 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    @php
        $onama = $user->nama_lengkap ?? 'Owner';
        $ow = preg_split('/\s+/', trim($onama));
        $oi = '';
        if(!empty($ow[0])) $oi .= mb_substr($ow[0],0,1);
        if(isset($ow[1])) $oi .= mb_substr($ow[1],0,1);
        elseif(mb_strlen($ow[0]??'')>1) $oi .= mb_substr($ow[0],1,1);
        $oinit = strtoupper(mb_substr($oi,0,2)) ?: '?';
    @endphp
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-gold-accent text-white flex items-center justify-center font-bold text-xl shrink-0 mx-auto sm:mx-0 border border-gold-accent/30">
                {{ $oinit }}
            </div>
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center sm:justify-start">
                    <h2 class="raliva-figure text-[26px] text-on-surface">{{ $user->nama_lengkap ?? '-' }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 font-label-sm text-[10px] uppercase tracking-wider w-fit mx-auto sm:mx-0">{{ $roleName }}</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">{{ $user->email }} • {{ $user->nomor_telepon ?? '-' }}</p>
            </div>
            <button type="button" data-modal-open="modal-edit-profil" class="px-5 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium shrink-0">Edit Profil</button>
            <button type="button" data-modal-open="modal-foto" class="px-5 py-2.5 border border-muted-border text-on-primary text-sm font-semibold rounded btn-premium shrink-0">Ganti Foto</button>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <section data-reveal-group class="space-y-section-gap">
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Informasi Akun</h2>
                <dl class="space-y-5 font-body-md text-sm">
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nama Lengkap</dt><dd class="text-on-surface font-bold text-right">{{ $user->nama_lengkap ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Email</dt><dd class="text-on-surface text-right break-all">{{ $user->email }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Nomor HP</dt><dd class="text-on-surface text-right">{{ $user->nomor_telepon ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 pb-4 border-b border-muted-border items-start"><dt class="text-on-surface-variant shrink-0">Role</dt><dd><span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $roleName }}</span></dd></div>
                    <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant shrink-0">Status Akun</dt><dd><span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>Aktif sejak {{ $user->created_at?->translatedFormat('M Y') ?? '-' }}</span></dd></div>
                </dl>
            </section>

            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Keamanan Akun</h2>
                <form method="POST" action="{{ route('owner.profil.password') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="pw-lama" class="block raliva-label mb-2">Kata Sandi Saat Ini</label>
                        <input id="pw-lama" name="password_lama" type="password" required class="raliva-input" />
                        @error('password_lama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                        <div>
                            <label for="pw-baru" class="block raliva-label mb-2">Kata Sandi Baru</label>
                            <input id="pw-baru" name="password" type="password" required minlength="8" class="raliva-input" />
                            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pw-konfirmasi" class="block raliva-label mb-2">Konfirmasi Kata Sandi</label>
                            <input id="pw-konfirmasi" name="password_confirmation" type="password" required minlength="8" class="raliva-input" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Perbarui Kata Sandi</button>
                    </div>
                </form>
            </section>
        </section>

        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium self-start">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Toko yang Dimiliki</h2>
            @if($ownedStores->isEmpty())
                <div class="border border-dashed border-muted-border rounded-lg px-4 py-8 text-center">
                    <span class="material-symbols-outlined text-[32px] text-on-surface-variant">store</span>
                    <p class="text-on-surface-variant text-sm mt-2">Belum memiliki toko.</p>
                    <a href="{{ route('owner.pengajuan-toko') }}" class="inline-flex mt-3 px-4 py-2 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium">Ajukan Toko Sekarang</a>
                </div>
            @else
                @foreach ($ownedStores as $toko)
                    <div class="border border-muted-border rounded-lg px-4 py-4 flex items-center justify-between gap-3 {{ !$loop->last ? 'mb-gutter' : '' }} hover:border-gold-accent/40 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-deep-onyx text-on-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">storefront</span>
                            </div>
                            <div class="min-w-0">
                                <p class="font-title-md text-sm text-on-surface truncate">{{ $toko->nama_toko }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ \Illuminate\Support\Str::limit($toko->alamat ?? '-', 32) }} • {{ $toko->status === 'aktif' ? 'Aktif' : ucfirst($toko->status) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('owner.data-toko') }}" class="shrink-0 text-xs font-semibold text-gold-accent hover:underline">Kelola</a>
                    </div>
                @endforeach
            @endif

            <p class="raliva-label mt-7 mb-4">Hak Akses Owner</p>
            <div class="flex flex-wrap gap-2">
                @foreach ([['storefront', 'Kelola Data Toko'], ['fact_check', 'Pengajuan & Verifikasi'], ['storage', 'Kelola Slot'], ['shopping_bag', 'Pantau Pesanan'], ['groups', 'Data Pelanggan'], ['local_offer', 'Promo Toko'], ['account_balance_wallet', 'Saldo & Pencairan'], ['monitoring', 'Laporan Toko'], ['tune', 'Pengaturan Toko']] as $perm)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-low border border-muted-border text-on-surface-variant font-label-sm text-[11px]">
                        <span class="material-symbols-outlined text-[14px] text-secondary">{{ $perm[0] }}</span>
                        {{ $perm[1] }}
                    </span>
                @endforeach
            </div>
            <p class="text-xs text-on-surface-variant mt-6 flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">lock</span>
                Konfigurasi global platform hanya dapat diubah oleh Super Admin.
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
            <form method="POST" action="{{ route('owner.profil.update') }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="w-20 h-20 rounded-full overflow-hidden border border-outline-variant">
                            <img id="ep-foto-preview" alt="Foto Profil" class="w-full h-full object-cover" src="{{ $user->foto_profil_url ?? ($user->foto_profil ? asset('storage/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama_lengkap ?? 'Owner').'&background=FF4F87&color=fff&size=80') }}" />
                        </div>
                        <label for="ep-foto" class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center btn-premium shadow-md cursor-pointer" aria-label="Ubah Foto">
                            <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="ep-foto" class="block raliva-label mb-2">Foto Profil <span class="text-on-surface-variant font-normal">(opsional)</span></label>
                    <input id="ep-foto" name="foto_profil" type="file" accept="image/*" class="w-full text-sm text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border file:border-muted-border file:bg-surface-container-low file:text-sm" onchange="if(this.files[0]){const r=new FileReader();r.onload=e=>document.getElementById('ep-foto-preview').src=e.target.result;r.readAsDataURL(this.files[0]);}" />
                    <p class="text-xs text-on-surface-variant mt-1">JPG/PNG/WebP, maks 2MB. Kosongkan jika tidak ingin ganti.</p>
                    @error('foto_profil') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="ep-nama" class="block raliva-label mb-2">Nama Lengkap</label>
                    <input id="ep-nama" name="nama_lengkap" type="text" value="{{ old('nama_lengkap', $user->nama_lengkap ?? '') }}" required class="raliva-input" />
                    @error('nama_lengkap') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="ep-email" class="block raliva-label mb-2">Email</label>
                    <input id="ep-email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required class="raliva-input" />
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="ep-hp" class="block raliva-label mb-2">Nomor HP <span class="text-on-surface-variant font-normal">(opsional)</span></label>
                    <input id="ep-hp" name="nomor_telepon" type="text" value="{{ old('nomor_telepon', $user->nomor_telepon ?? '') }}" class="raliva-input" placeholder="08..." />
                    @error('nomor_telepon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block raliva-label mb-2">Role</label>
                    <input type="text" value="{{ $roleName }} — {{ $ownedStores->isEmpty() ? 'Belum punya toko' : $ownedStores->pluck('nama_toko')->implode(' & ') }}" readonly disabled class="w-full bg-surface-container-low border border-muted-border rounded-lg px-3 py-2.5 font-body-md text-sm text-on-surface-variant opacity-80 cursor-not-allowed" />
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  if (!document.querySelector('[data-real]')) return;
  // Check if no store banner exists (means no store)
  const noStore = document.body.innerHTML.includes('Belum punya toko');
  if (!noStore) return;
  // Disable all primary action buttons except Ajukan Toko
  document.querySelectorAll('[data-modal-open], button[type="submit"], a[href*="pengajuan-toko"]:not([href*="ajukan"])').forEach(el=>{
    // Keep Ajukan Toko enabled
    if (el.textContent.includes('Ajukan Toko') || el.getAttribute('data-modal-open')?.includes('modal-tambah')) {
      // For tambah buttons, disable if no store
      el.setAttribute('disabled','');
      el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
      el.title = 'Ajukan toko dulu';
    }
  });
  // More generic: disable all buttons in data-real except those inside pengajuan
  document.querySelectorAll('[data-real] button, [data-real] a.btn-premium').forEach(el=>{
    if (el.closest('[data-modal]')) return;
    if (el.textContent.trim().includes('Ajukan')) return;
    el.setAttribute('disabled','');
    el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
  });
});
</script>
@endpush

@endsection
