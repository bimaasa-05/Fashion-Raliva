@extends('layouts.owner')

@section('title', 'Pengaturan Toko')

@section('header-title', 'Pengaturan Toko')
@section('header-subtitle', 'Konfigurasi khusus yang hanya berlaku pada toko Anda.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
        <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
    <div class="h-64 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    <form data-toast-message="Pengaturan toko berhasil disimpan." data-reveal-group>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-section-gap">
            {{-- Operasional --}}
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Operasional</h2>
                <ul class="space-y-5">
                    <li class="flex items-center justify-between gap-4 pb-5 border-b border-muted-border last:border-0">
                        <div>
                            <p class="font-title-md text-sm text-on-surface">Terima Pesanan Otomatis</p>
                            <p class="text-on-surface-variant text-xs mt-1">Pesanan baru langsung berstatus Diproses tanpa konfirmasi manual.</p>
                        </div>
                        <label class="raliva-toggle">
                            <input type="checkbox" class="sr-only peer" checked />
                            <span class="raliva-toggle-track"></span>
                            <span class="raliva-toggle-knob"></span>
                        </label>
                    </li>
                    <li class="flex items-center justify-between gap-4 pb-5 border-b border-muted-border">
                        <div>
                            <p class="font-title-md text-sm text-on-surface">Izinkan Pesanan di Luar Jam Operasional</p>
                            <p class="text-on-surface-variant text-xs mt-1">Customer dapat memesan kapan pun, diproses saat toko buka.</p>
                        </div>
                        <label class="raliva-toggle">
                            <input type="checkbox" class="sr-only peer" checked />
                            <span class="raliva-toggle-track"></span>
                            <span class="raliva-toggle-knob"></span>
                        </label>
                    </li>
                    <li class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-title-md text-sm text-on-surface">Batas Pesanan per Hari</p>
                            <p class="text-on-surface-variant text-xs mt-1">Membatasi jumlah pesanan agar kapasitas produksi terjaga.</p>
                        </div>
                        <input type="number" value="150" min="0" class="raliva-input !w-24 py-2 text-center shrink-0" />
                    </li>
                </ul>
            </section>

            {{-- Katalog & Tampilan --}}
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Katalog &amp; Tampilan</h2>
                <ul class="space-y-5">
                    <li class="flex items-center justify-between gap-4 pb-5 border-b border-muted-border">
                        <div>
                            <p class="font-title-md text-sm text-on-surface">Tampilkan Sisa Stok</p>
                            <p class="text-on-surface-variant text-xs mt-1">Customer melihat sisa stok produk (misal: "tersisa 8").</p>
                        </div>
                        <label class="raliva-toggle">
                            <input type="checkbox" class="sr-only peer" checked />
                            <span class="raliva-toggle-track"></span>
                            <span class="raliva-toggle-knob"></span>
                        </label>
                    </li>
                    <li class="flex items-center justify-between gap-4 pb-5 border-b border-muted-border">
                        <div>
                            <p class="font-title-md text-sm text-on-surface">Mode Pre-Order Global</p>
                            <p class="text-on-surface-variant text-xs mt-1">Seluruh produk otomatis diberi label pre-order 7 hari kerja.</p>
                        </div>
                        <label class="raliva-toggle">
                            <input type="checkbox" class="sr-only peer" />
                            <span class="raliva-toggle-track"></span>
                            <span class="raliva-toggle-knob"></span>
                        </label>
                    </li>
                    <li>
                        <label for="kurir-default" class="block raliva-label mb-2">Kurir Utama Toko</label>
                        <select id="kurir-default" class="raliva-select">
                            <option selected>Kurir Platform Raliva Express</option>
                            <option>JNE Reguler</option>
                            <option>SiCepat YES</option>
                            <option>Kurir Toko Sendiri</option>
                        </select>
                    </li>
                </ul>
            </section>
        </div>

        {{-- Notifikasi --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium mt-section-gap">
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Notifikasi</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                @foreach ([['shopping_bag', 'Pesanan Baru', 'Email & push saat ada pesanan masuk.', true], ['account_balance_wallet', 'Perubahan Saldo', 'Info saldo masuk, tertunda, dan pencairan.', true], ['star', 'Ulasan Baru', 'Push notifikasi untuk ulasan 1–2 bintang.', false], ['groups', 'Pelanggan Baru', 'Info pelanggan pertama kali berbelanja.', false], ['inventory_2', 'Stok Menipis', 'Peringatan ketika stok < 10 unit.', false], ['storage', 'Slot Disetujui/Ditolak', 'Kabarnya pengajuan slot dari SuperAdmin.', true]] as $notif)
                    <div class="border border-muted-border rounded-lg px-4 py-4 flex items-start justify-between gap-3 bg-surface-container-low hover:border-gold-accent/40 transition-colors">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">{{ $notif[0] }}</span>
                            <div>
                                <p class="font-title-md text-sm text-on-surface leading-snug">{{ $notif[1] }}</p>
                                <p class="text-on-surface-variant text-xs mt-1">{{ $notif[2] }}</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                            <input type="checkbox" class="sr-only peer" {{ $notif[3] ? 'checked' : '' }} />
                            <span class="raliva-toggle-track"></span>
                            <span class="raliva-toggle-knob"></span>
                        </label>
                    </div>
                @endforeach
            </div>
            <p class="text-xs text-on-surface-variant mt-5 flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">lock</span>
                Pengaturan ini hanya berlaku untuk toko Anda dan tidak memengaruhi konfigurasi platform.
            </p>
        </section>

        {{-- Zona Berbahaya --}}
        <section data-reveal class="border border-error/30 bg-error/5 rounded-lg p-6 mt-section-gap">
            <h2 class="font-title-md text-title-md mb-4 text-error premium-heading">Area Sensitif</h2>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <p class="font-title-md text-sm text-on-surface">Nonaktifkan Toko Sementara</p>
                    <p class="text-on-surface-variant text-xs mt-1 max-w-xl">Toko akan disembunyikan dari pencarian dan tidak dapat menerima pesanan. Data pesanan berjalan tetap aman.</p>
                </div>
                <button type="button" onclick="showRalivaToast('Fitur nonaktif sementara dalam demo ini.', 'lock')" class="shrink-0 py-2.5 px-6 border border-error/40 text-error rounded-lg text-xs font-semibold hover:bg-error hover:text-on-error transition-colors">Nonaktifkan Toko</button>
            </div>
        </section>

        <div data-reveal class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter sticky bottom-20 md:bottom-4 z-30 pt-2">
            <button type="reset" class="py-3 px-6 bg-surface-container-lowest border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors shadow-sm">Atur Ulang</button>
            <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">save</span>Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
