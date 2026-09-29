@extends('layouts.owner')

@section('title', 'Data Toko')

@section('header-title', 'Data Toko')
@section('header-badge', 'Terverifikasi')
@section('header-subtitle', 'Kelola identitas resmi toko Anda di Raliva.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-40 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    {{-- Hero Identitas --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="relative shrink-0">
                <div class="w-24 h-24 rounded-xl overflow-hidden border border-outline-variant bg-surface-container-high flex items-center justify-center">
                    @php $logoUrl = $store?->logo ? photo_url($store->logo) : asset('images/logo.svg'); @endphp
                    <img src="{{ $logoUrl }}" alt="Logo Toko" class="w-full h-full object-cover" id="store-logo-preview" />
                </div>
                <input type="file" name="logo" id="store-logo-input" accept=".jpg,.jpeg,.png,.webp" class="hidden" form="form-data-toko" @if(!empty($updatePending)) disabled @endif />
                    <label for="store-logo-input" @if(!empty($updatePending)) aria-disabled="true" title="Terkunci — menunggu verifikasi" @else title="Ubah Logo (JPG/PNG/WebP, maks 2 MB). Berlaku setelah diverifikasi Super Admin." @endif class="absolute -bottom-2 -right-2 w-9 h-9 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center btn-premium shadow-md @if(empty($updatePending)) cursor-pointer @else opacity-60 cursor-not-allowed @endif" aria-label="Ubah Logo">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                    </label>
                    @if(!empty($updatePending) && !empty($updatePending->logo))
                        <span class="absolute -top-2 -left-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gold-accent/15 text-gold-accent text-[9px] font-bold uppercase border border-gold-accent/30 whitespace-nowrap" title="Logo baru menunggu verifikasi Super Admin">Logo pending</span>
                    @endif
            </div>
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center sm:justify-start">
                    <h2 class="raliva-figure text-[26px] text-on-surface">{{ $store?->nama_toko ?? 'Toko' }}</h2>
                    @php $heroDitolak = in_array($store?->status, ['ditolak', 'nonaktif'], true); @endphp
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full {{ $store?->status === 'aktif' ? 'bg-success/10 text-success border-success/20' : ($heroDitolak ? 'bg-error/10 text-error border-error/25' : 'bg-gold-accent/10 text-gold-accent border-gold-accent/30') }} text-[10px] font-bold uppercase border w-fit mx-auto sm:mx-0">
                        <span class="material-symbols-outlined fill text-[12px]">{{ $store?->status === 'aktif' ? 'verified' : ($heroDitolak ? 'cancel' : 'schedule') }}</span>{{ $store?->status === 'aktif' ? 'Terverifikasi' : ucfirst($store?->status ?? 'Menunggu') }}
                    </span>
                    @if(! $store)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/30 text-[10px] font-bold uppercase w-fit mx-auto sm:mx-0" title="Terkunci — ajukan toko untuk membuka">
                            <span class="material-symbols-outlined text-[12px]">lock</span>Terkunci
                        </span>
                    @endif
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">{{ $store?->kategori ?? 'Fashion & Lifestyle' }} &mdash; ID Toko: RLV-TOKO-{{ str_pad($store?->store_id ?? 0, 4, '0', STR_PAD_LEFT) }} &bull; Bergabung {{ $store?->created_at?->translatedFormat('M Y') ?? '-' }}</p>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Rating toko <span class="font-bold text-gold-accent">{{ number_format($rating, 1, ',', '.') }}/5,0</span> &bull; {{ $reviewCount }} ulasan</p>
            </div>
        </div>
    </section>

    <form method="POST" action="{{ route('owner.data-toko.update') }}" id="form-data-toko" enctype="multipart/form-data" class="space-y-section-gap" @if(!empty($updatePending)) data-locked @endif>
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
            {{-- Informasi Umum --}}
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Informasi Umum</h2>
                <div class="space-y-5">
                    <div>
                        <label for="nama-toko" class="block raliva-label mb-2">Nama Toko</label>
                        <input id="nama-toko" name="nama_toko" type="text" value="{{ old('nama_toko', $store?->nama_toko ?? '') }}" required class="raliva-input" />
                    </div>
                    <div>
                        <label for="kategori-toko" class="block raliva-label mb-2">Kategori</label>
                        <select id="kategori-toko" name="kategori" class="raliva-select">
                            @php $kategoriToko = old('kategori', $store?->kategori ?? ($storeCategories->first() ?? '')); @endphp
                            @foreach (($storeCategories ?? collect()) as $opt)
                                <option value="{{ $opt }}" @selected($kategoriToko === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="deskripsi-toko" class="block raliva-label mb-2">Deskripsi Toko</label>
                        <textarea id="deskripsi-toko" name="deskripsi" rows="4" class="raliva-textarea">{{ old('deskripsi', $store?->deskripsi ?? '') }}</textarea>
                    </div>
                </div>
            </section>

            {{-- Kontak & Alamat --}}
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Kontak & Alamat</h2>
                <div class="space-y-5">
                    <div>
                        <label for="telepon-toko" class="block raliva-label mb-2">Nomor Telepon</label>
                        <input id="telepon-toko" name="nomor_telepon" type="text" value="{{ old('nomor_telepon', $store?->nomor_telepon ?? '') }}" required class="raliva-input" />
                    </div>
                    <div>
                        <label for="email-toko" class="block raliva-label mb-2">Email Toko</label>
                        <input id="email-toko" name="email" type="email" value="{{ old('email', Auth::user()->email ?? '') }}" required class="raliva-input" />
                    </div>
                    <div>
                        <label for="alamat-toko" class="block raliva-label mb-2">Alamat Lengkap</label>
                        <textarea id="alamat-toko" name="alamat" rows="3" required class="raliva-textarea">{{ old('alamat', $store?->alamat ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Kota</label>
                        <div class="flex items-center gap-2">
                            <div class="flex-grow min-w-0">
                                @include('partials.kota-combobox', ['prefix' => 'toko', 'cities' => $cities ?? [], 'selectedName' => old('kota', $store?->kota ?? ''), 'fieldName' => 'kota', 'placeholder' => 'Cari kota toko...'])
                            </div>
                            @if(!empty($updatePending) && !empty($updatePending->kota) && $updatePending->kota !== ($store?->kota ?? null))
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-bold uppercase border border-amber-500/30 whitespace-nowrap shrink-0" title="Perubahan kota menunggu persetujuan Super Admin"><span class="material-symbols-outlined text-[12px]">schedule</span>Menunggu persetujuan</span>
                            @endif
                        </div>
                        @error('kota') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>
        </div>

        {{-- Media Sosial --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex items-center gap-3 mb-2">
                <span class="material-symbols-outlined text-gold-accent">share</span>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Media Sosial</h2>
            </div>
            <p class="text-sm text-on-surface-variant mb-6">Tautan yang tampil di halaman Tentang toko Anda. Pilih platform atau isi nama custom bila belum tersedia.</p>

            @if(($storeSocials ?? collect())->isNotEmpty())
                <div class="space-y-3 mb-6">
                    @foreach($storeSocials as $social)
                        <div class="flex items-center justify-between gap-3 border border-muted-border rounded-lg px-4 py-3">
                            <div class="min-w-0">
                                <p class="font-semibold text-sm text-on-surface">{{ $social->label() }}</p>
                                <a href="{{ $social->url }}" target="_blank" rel="noopener" class="text-xs text-gold-accent hover:underline truncate block">{{ $social->url }}</a>
                            </div>
                            <form method="POST" action="{{ route('owner.data-toko.sosmed.destroy', $social) }}" onsubmit="return confirm('Hapus media sosial ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-4 py-2 border border-muted-border rounded-lg text-xs font-semibold text-error hover:border-error transition-colors">Hapus</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-on-surface-variant border border-dashed border-muted-border rounded-lg px-4 py-3 mb-6">Belum ada media sosial. Tambahkan yang pertama di bawah.</p>
            @endif

            <form method="POST" action="{{ route('owner.data-toko.sosmed.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label for="sosmed-platform" class="block raliva-label mb-2">Platform</label>
                    <select id="sosmed-platform" name="sosmed_platform_id" class="raliva-input">
                        <option value="">-- Custom / pilih --</option>
                        @foreach($sosmedPlatforms ?? [] as $platform)
                            <option value="{{ $platform->sosmed_platform_id }}" {{ old('sosmed_platform_id') == $platform->sosmed_platform_id ? 'selected' : '' }}>{{ $platform->nama_platform }}</option>
                        @endforeach
                    </select>
                    @error('sosmed_platform_id') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="sosmed-custom" class="block raliva-label mb-2">Nama Custom <span class="font-normal normal-case">(bila platform belum ada)</span></label>
                    <input id="sosmed-custom" name="nama_custom" type="text" maxlength="50" value="{{ old('nama_custom') }}" placeholder="cth: Threads" class="raliva-input" />
                    @error('nama_custom') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="sosmed-url" class="block raliva-label mb-2">Link / URL</label>
                    <input id="sosmed-url" name="url" type="url" maxlength="255" value="{{ old('url') }}" placeholder="https://..." required class="raliva-input" />
                    @error('url') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Tambah Media Sosial</button>
                </div>
            </form>
        </section>

        @if(!empty($updatePending))
            <p class="text-xs flex items-start gap-2 bg-gold-accent/10 border border-gold-accent/30 rounded-lg px-4 py-3">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">schedule</span>
                <span class="text-on-surface">Perubahan data dikirim {{ $updatePending->created_at?->translatedFormat('d M Y H:i') ?? '' }} dan menunggu verifikasi Super Admin. Form dikunci sementara.</span>
            </p>
        @else
            <p class="text-xs text-on-surface-variant flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
                Toko buka 24 jam — siapa pun boleh memesan kapan pun. Setiap perubahan data akan menunggu verifikasi Super Admin sebelum berlaku.
            </p>
        @endif

        @error('logo') <p class="text-error text-xs flex items-start gap-2"><span class="material-symbols-outlined text-[16px] mt-0.5">error</span>{{ $message }}</p> @enderror
        <p id="store-logo-chip" class="hidden items-center gap-2 text-xs text-on-surface-variant">
            <span class="material-symbols-outlined text-[16px] text-gold-accent">image</span>
            <span>Logo baru: <strong id="store-logo-name" class="text-on-surface"></strong> (<span id="store-logo-size"></span>) — ikut diajukan saat klik Ajukan Perubahan.</span>
        </p>
        <p id="store-logo-chip-error" class="hidden items-center gap-2 text-xs text-error">
            <span class="material-symbols-outlined text-[16px]">error</span>
            <span id="store-logo-error-text">Logo melebihi 2 MB — pilih file lain agar ikut terkirim.</span>
        </p>

        <div data-reveal class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter sticky bottom-20 md:bottom-4 z-30">
            <button type="button" data-modal-open="modal-atur-ulang" @if(!empty($updatePending)) disabled title="Terkunci — menunggu verifikasi" @endif class="py-3 px-6 bg-surface-container-lowest border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">Atur Ulang</button>
            <button type="submit" @if(!empty($updatePending)) disabled title="Terkunci — menunggu verifikasi" @endif class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-[16px]">save</span>Ajukan Perubahan
            </button>
        </div>
    </form>

    {{-- Modal konfirmasi Atur Ulang --}}
    <div id="modal-atur-ulang" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto w-[calc(100%-2rem)] max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-gold-accent">restart_alt</span>
                <h3 class="font-title-md text-title-md text-on-surface">Atur Ulang Formulir?</h3>
            </div>
            <p class="text-on-surface-variant text-sm mb-6">Semua perubahan yang belum disimpan akan dikembalikan ke data terakhir yang tersimpan. Tindakan ini tidak menghapus data toko Anda.</p>
            <div class="flex gap-3">
                <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="reset" form="form-data-toko" data-modal-close class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Ya, Atur Ulang</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const lockedForm = document.querySelector('#form-data-toko[data-locked]');
    if (lockedForm) {
        lockedForm.querySelectorAll('input, select, textarea').forEach((el) => {
            el.setAttribute('disabled', '');
        });
    }
  if (!document.querySelector('[data-real]')) return;
  // Check if no store banner exists (means no store)
  const noStore = document.querySelector('[data-no-store-banner]');
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

  // Logo toko: preview + chip persisten; ikut terkirim bersama form data (perlu verifikasi SA).
  var logoInput = document.getElementById('store-logo-input');
  var logoChip = document.getElementById('store-logo-chip');
  var logoChipErr = document.getElementById('store-logo-chip-error');
  function showChip(el, show) {
    if (!el) return;
    el.classList.toggle('hidden', !show);
    el.classList.toggle('flex', show);
  }
  if (logoInput) {
    logoInput.addEventListener('change', function () {
      var file = this.files && this.files[0];
      if (!file) return;
      logoInput.dataset.picked = '1';
      if (file.size > 2 * 1024 * 1024) {
        this.value = '';
        showChip(logoChip, false);
        showChip(logoChipErr, true);
        showRalivaToast('Ukuran logo maksimal 2 MB.', 'error');
        return;
      }
      showChip(logoChipErr, false);
      var preview = document.getElementById('store-logo-preview');
      if (preview) preview.src = URL.createObjectURL(file);
      var nm = document.getElementById('store-logo-name');
      var sz = document.getElementById('store-logo-size');
      if (nm) nm.textContent = file.name;
      if (sz) sz.textContent = (file.size / 1024).toFixed(0) + ' KB';
      showChip(logoChip, true);
    });
    var mainForm = document.getElementById('form-data-toko');
    if (mainForm) {
      mainForm.addEventListener('submit', function (e) {
        if (logoInput.dataset.picked === '1' && (!logoInput.files || logoInput.files.length === 0)) {
          e.preventDefault();
          showChip(logoChip, false);
          showChip(logoChipErr, true);
          showRalivaToast('File logo tidak terbawa. Pilih ulang logo lalu ajukan kembali.', 'error');
          logoInput.focus();
        }
      });
    }
  }
});
</script>
@endpush

@endsection
