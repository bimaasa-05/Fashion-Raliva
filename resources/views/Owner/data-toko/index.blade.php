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
    {{-- Hero Identitas --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="relative shrink-0">
                <div class="w-24 h-24 rounded-xl overflow-hidden border border-outline-variant bg-surface-container-high flex items-center justify-center">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo Toko" class="w-full h-full object-cover" />
                </div>
                <button type="button" onclick="showRalivaToast('Silakan pilih logo baru.', 'image')" class="absolute -bottom-2 -right-2 w-9 h-9 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center btn-premium shadow-md" aria-label="Ubah Logo">
                    <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                </button>
            </div>
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center sm:justify-start">
                    <h2 class="raliva-figure text-[26px] text-on-surface">{{ $store?->nama_toko ?? 'Toko' }}</h2>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full {{ $store?->status === 'aktif' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container-high text-on-surface-variant border-outline-variant' }} text-[10px] font-bold uppercase border w-fit mx-auto sm:mx-0">
                        <span class="material-symbols-outlined fill text-[12px]">{{ $store?->status === 'aktif' ? 'verified' : 'schedule' }}</span>{{ $store?->status === 'aktif' ? 'Terverifikasi' : ucfirst($store?->status ?? 'Menunggu') }}
                    </span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Fashion &mdash; ID Toko: RLV-TOKO-{{ str_pad($store?->store_id ?? 0, 4, '0', STR_PAD_LEFT) }} &bull; Bergabung {{ $store?->created_at?->translatedFormat('M Y') ?? '-' }}</p>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Rating toko <span class="font-bold text-gold-accent">{{ number_format($rating, 1, ',', '.') }}/5,0</span> &bull; {{ $reviewCount }} ulasan</p>
            </div>
        </div>
    </section>

    <form method="POST" action="{{ route('owner.data-toko.update') }}" class="space-y-section-gap">
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
                        <select id="kategori-toko" class="raliva-select">
                            <option selected>Fashion & Lifestyle</option>
                            <option>Pakaian Wanita</option>
                            <option>Pakaian Pria</option>
                            <option>Aksesoris</option>
                        </select>
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
                        <label for="instagram-toko" class="block raliva-label mb-2">Instagram</label>
                        <input id="instagram-toko" type="text" value="@raliva.atelier" class="raliva-input" />
                    </div>
                    <div>
                        <label for="alamat-toko" class="block raliva-label mb-2">Alamat Lengkap</label>
                        <textarea id="alamat-toko" name="alamat" rows="3" required class="raliva-textarea">{{ old('alamat', $store?->alamat ?? '') }}</textarea>
                    </div>
                </div>
            </section>
        </div>

        <p class="text-xs text-on-surface-variant flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
            Toko buka 24 jam — siapa pun boleh memesan kapan pun. Perubahan nama atau alamat akan diverifikasi Super Admin.
        </p>

        <div data-reveal class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter sticky bottom-20 md:bottom-4 z-30">
            <button type="button" data-modal-open="modal-atur-ulang" class="py-3 px-6 bg-surface-container-lowest border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors shadow-sm">Atur Ulang</button>
            <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">save</span>Simpan Perubahan
            </button>
        </div>
    </form>

    {{-- Modal konfirmasi Atur Ulang --}}
    <div id="modal-atur-ulang" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto mt-24 w-[calc(100%-2rem)] max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-gold-accent">restart_alt</span>
                <h3 class="font-title-md text-title-md text-on-surface">Atur Ulang Formulir?</h3>
            </div>
            <p class="text-on-surface-variant text-sm mb-6">Semua perubahan yang belum disimpan akan dikembalikan ke data terakhir yang tersimpan. Tindakan ini tidak menghapus data toko Anda.</p>
            <div class="flex gap-3">
                <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="button" onclick="document.querySelector('form[action=\'{{ route('owner.data-toko.update') }}\']').reset(); document.querySelector('[data-modal-close]').click();" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Ya, Atur Ulang</button>
            </div>
        </div>
    </div>
</div>
@endsection
