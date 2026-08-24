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
                <button type="button" onclick="showRalivaToast('Silakan pilih logo baru (demo).', 'image')" class="absolute -bottom-2 -right-2 w-9 h-9 rounded-full bg-deep-onyx text-on-primary flex items-center justify-center btn-premium shadow-md" aria-label="Ubah Logo">
                    <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                </button>
            </div>
            <div class="flex-1 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center sm:justify-start">
                    <h2 class="raliva-figure text-[26px] text-on-surface">Raliva Atelier Jakarta</h2>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20 w-fit mx-auto sm:mx-0">
                        <span class="material-symbols-outlined fill text-[12px]">verified</span>Terverifikasi
                    </span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm mt-1">Fashion &mdash; ID Toko: RLV-TOKO-0021 &bull; Bergabung Mar 2024</p>
                <p class="text-on-surface-variant font-body-md text-sm mt-0.5">Rating toko <span class="font-bold text-gold-accent">4,9/5,0</span> &bull; 1.284 ulasan</p>
            </div>
        </div>
    </section>

    <form data-toast-message="Data toko berhasil disimpan." class="space-y-section-gap">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
            {{-- Informasi Umum --}}
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Informasi Umum</h2>
                <div class="space-y-5">
                    <div>
                        <label for="nama-toko" class="block raliva-label mb-2">Nama Toko</label>
                        <input id="nama-toko" type="text" value="Raliva Atelier Jakarta" required class="raliva-input" />
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
                        <textarea id="deskripsi-toko" rows="4" class="raliva-textarea">Butik fashion lokal yang menghadirkan koleksi premium dengan sentuhan elegan khas Indonesia. Setiap produk dibuat dari bahan pilihan terbaik.</textarea>
                    </div>
                </div>
            </section>

            {{-- Kontak & Alamat --}}
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Kontak & Alamat</h2>
                <div class="space-y-5">
                    <div>
                        <label for="telepon-toko" class="block raliva-label mb-2">Nomor Telepon</label>
                        <input id="telepon-toko" type="text" value="+62 21 7280 1122" required class="raliva-input" />
                    </div>
                    <div>
                        <label for="email-toko" class="block raliva-label mb-2">Email Toko</label>
                        <input id="email-toko" type="email" value="halo@ralivaatelier.id" required class="raliva-input" />
                    </div>
                    <div>
                        <label for="instagram-toko" class="block raliva-label mb-2">Instagram</label>
                        <input id="instagram-toko" type="text" value="@raliva.atelier" class="raliva-input" />
                    </div>
                    <div>
                        <label for="alamat-toko" class="block raliva-label mb-2">Alamat Lengkap</label>
                        <textarea id="alamat-toko" rows="3" required class="raliva-textarea">Jl. Kemang Raya No. 21, RT 04/RW 02, Bangka, Mampang Prapatan, Jakarta Selatan, DKI Jakarta 12730</textarea>
                    </div>
                </div>
            </section>
        </div>

        {{-- Jam Operasional --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Jam Operasional</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter">
                @foreach ([['Senin - Jumat', '09:00 - 21:00', true], ['Sabtu', '10:00 - 22:00', true], ['Minggu', '10:00 - 18:00', true], ['Hari Libur Nasional', 'Tutup', false]] as $jam)
                    <div class="border border-muted-border rounded-lg px-4 py-3.5 flex items-center justify-between gap-3 bg-surface-container-low">
                        <div>
                            <p class="font-title-md text-sm text-on-surface">{{ $jam[0] }}</p>
                            <p class="text-on-surface-variant text-xs mt-0.5">{{ $jam[2] ? $jam[1] : 'Libur' }}</p>
                        </div>
                        <span class="material-symbols-outlined {{ $jam[2] ? 'text-secondary fill' : 'text-error' }}">{{ $jam[2] ? 'check_circle' : 'cancel' }}</span>
                    </div>
                @endforeach
            </div>
            <p class="text-xs text-on-surface-variant mt-5 flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>
                Perubahan nama toko atau alamat akan memicu verifikasi ulang oleh Super Admin sebelum diterapkan.
            </p>
        </section>

        <div data-reveal class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter sticky bottom-20 md:bottom-4 z-30">
            <button type="reset" class="py-3 px-6 bg-surface-container-lowest border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors shadow-sm">Atur Ulang</button>
            <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">save</span>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
