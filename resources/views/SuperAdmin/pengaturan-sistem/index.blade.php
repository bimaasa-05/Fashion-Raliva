@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem')

@section('header-title', 'Pengaturan Sistem')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Konfigurasi aturan global platform Raliva, terpisah dari pengaturan toko.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap w-full">
    <form class="space-y-section-gap" id="pengaturan-form" method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}">
        @csrf @method('PUT')

        <section class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
            <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">tune</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Umum</h2></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama_platform">Nama Platform</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="nama_platform" name="nama_platform" type="text" value="{{ $settings['nama_platform'] }}" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="email_support">Email Dukungan</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="email_support" name="email_support" type="email" value="{{ $settings['email_support'] }}" required />
                </div>
            </div>
            <label class="flex items-center justify-between gap-4 p-4 border border-muted-border rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors">
                <span>
                    <span class="block font-title-md text-on-surface">Mode Pemeliharaan</span>
                    <span class="block font-body-md text-sm text-on-surface-variant mt-1">Nonaktifkan akses publik sementara saat perbaikan sistem.</span>
                </span>
                <input type="checkbox" name="mode_maintenance" value="1" class="w-5 h-5 accent-gold-accent shrink-0" {{ ($settings['mode_maintenance'] ?? '0') === '1' ? 'checked' : '' }} />
            </label>
        </section>

        <section class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
            <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">payments</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Keuangan</h2></div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="komisi_persen_default">Komisi Default (%)</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="komisi_persen_default" name="komisi_persen_default" type="number" min="0" max="100" step="0.5" value="{{ $settings['komisi_persen_default'] }}" required />
                    <p class="font-body-md text-xs text-on-surface-variant mt-2">Sumber pendapatan Raliva dari setiap transaksi berhasil.</p>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="biaya_layanan">Biaya Layanan Default (Rp)</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="biaya_layanan" name="biaya_layanan" type="number" min="0" value="{{ $settings['biaya_layanan'] }}" required />
                    <p class="font-body-md text-xs text-on-surface-variant mt-2">Ditampilkan ke customer secara transparan saat checkout.</p>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="min_pencairan">Minimal Pencairan (Rp)</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="min_pencairan" name="min_pencairan" type="number" min="0" value="{{ $settings['min_pencairan'] }}" required />
                    <p class="font-body-md text-xs text-on-surface-variant mt-2">Batas minimal saldo Owner untuk mengajukan pencairan dana.</p>
                </div>
            </div>
        </section>

        <section class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
            <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">fact_check</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Moderasi & Konten</h2></div>
            <label class="flex items-center justify-between gap-4 p-4 border border-muted-border rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors">
                <span>
                    <span class="block font-title-md text-on-surface">Moderasi Otomatis</span>
                    <span class="block font-body-md text-sm text-on-surface-variant mt-1">Tandai produk baru sebagai "pending" sebelum ditinjau Super Admin.</span>
                </span>
                <input type="checkbox" name="moderasi_otomatis" value="1" class="w-5 h-5 accent-gold-accent shrink-0" {{ ($settings['moderasi_otomatis'] ?? '1') === '1' ? 'checked' : '' }} />
            </label>
        </section>

        <section class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
            <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">speed</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Batas & Limit</h2></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="maks_pengajuan_pencairan">Maks Pengajuan Pencairan per Hari</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="maks_pengajuan_pencairan" name="maks_pengajuan_pencairan" type="number" min="1" value="{{ \App\Models\Setting::get('maks_pengajuan_pencairan', '3') }}" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="batas_waktu_refund">Batas Waktu Refund (hari)</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="batas_waktu_refund" name="batas_waktu_refund" type="number" min="1" value="{{ \App\Models\Setting::get('batas_waktu_refund', '7') }}" />
                </div>
            </div>
        </section>

        <div class="flex gap-gutter">
            <button type="button" class="flex-1 md:flex-none md:px-12 border border-muted-border text-deep-onyx font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-surface-container-low transition-colors" onclick="document.getElementById('pengaturan-form').reset()">Batalkan Perubahan</button>
            <button type="submit" class="flex-1 md:flex-none md:px-12 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase py-4 tracking-widest hover:bg-tertiary-container transition-colors">Simpan Pengaturan</button>
        </div>
    </form>

    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.legal') }}" class="space-y-section-gap">
        @csrf
        <section class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">verified_user</span></div>
                    <h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Syarat &amp; Ketentuan &amp; Kebijakan Privasi</h2>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-secondary-container/20 text-secondary border border-secondary/20 font-label-sm text-[10px] uppercase tracking-wider">Ditampilkan saat Customer mendaftar</span>
            </div>
            <p class="font-body-md text-sm text-on-surface-variant">Konten di bawah akan ditampilkan kepada Customer pada formulir pendaftaran. Gunakan baris baru untuk memisahkan paragraf.</p>

            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="syaratKetentuan">Syarat &amp; Ketentuan</label>
                <textarea name="syarat_ketentuan" id="syaratKetentuan" rows="8" required minlength="10"
                    class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"
                    placeholder="Tuliskan syarat dan ketentuan penggunaan platform Raliva...">{{ old('syarat_ketentuan', $syaratKetentuan) }}</textarea>
                @error('syarat_ketentuan')<p class="font-body-md text-xs text-error mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="kebijakanPrivasi">Kebijakan Privasi</label>
                <textarea name="kebijakan_privasi" id="kebijakanPrivasi" rows="8" required minlength="10"
                    class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"
                    placeholder="Tuliskan kebijakan privasi platform Raliva...">{{ old('kebijakan_privasi', $kebijakanPrivasi) }}</textarea>
                @error('kebijakan_privasi')<p class="font-body-md text-xs text-error mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-between gap-4 pt-gutter border-t border-muted-border flex-wrap">
                <p class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[14px] text-gold-accent">history</span>
                    Perubahan tercatat di riwayat aktivitas
                </p>
                <button type="submit" class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Konten Legal</button>
            </div>
        </section>
    </form>
</div>
@endsection
