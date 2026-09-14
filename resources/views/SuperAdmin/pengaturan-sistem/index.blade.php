@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem')

@section('header-title', 'Pengaturan Sistem')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Konfigurasi aturan global platform Raliva, terpisah dari pengaturan toko.')

@section('content')
@include('partials.flash-toast')

<style>
    .banner-gradient { background-image: linear-gradient(118deg, #141414 0%, #1f0c10 55%, #421329 100%); }
    .banner-glow { position: absolute; border-radius: 9999px; pointer-events: none; }
    .banner-glow-1 { top: -90px; right: -50px; width: 260px; height: 260px; background: rgba(139, 30, 63, 0.4); }
    .banner-glow-2 { bottom: -120px; left: -60px; width: 220px; height: 220px; background: rgba(139, 30, 63, 0.24); }
    .banner-desc { font-size: 14px; color: rgba(255, 255, 255, 0.72); }
    .banner-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 9999px; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: #fff; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.18); }
    .banner-badge .dot { width: 7px; height: 7px; border-radius: 9999px; animation: beat 1.6s ease-in-out infinite; }
    .banner-badge.is-off .dot { background: #4ade80; }
    .banner-badge.is-on .dot { background: #fbbf24; }
    @keyframes beat { 0%, 100% { opacity: 1; } 50% { opacity: 0.35; } }
    .stat-chip { display: flex; flex-direction: column; gap: 2px; min-width: 104px; padding: 10px 14px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.16); background: rgba(255, 255, 255, 0.08); }
    .stat-chip-label { font-size: 10px; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(255, 255, 255, 0.55); }
    .stat-chip-value { font-size: 16px; font-weight: 700; color: #fff; font-variant-numeric: tabular-nums; }

    .quick-nav { position: sticky; top: 64px; z-index: 30; }
    @media (min-width: 768px) { .quick-nav { top: 84px; } }
    .quick-nav-pill { display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 9999px; white-space: nowrap; border: 1px solid var(--color-muted-border); background: var(--color-surface-container-lowest); color: var(--color-on-surface-variant); font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; transition: all 0.18s ease; }
    .quick-nav-pill:hover { border-color: var(--color-gold-accent); color: var(--color-gold-accent); }
    .quick-nav-pill.is-active { border-color: var(--color-gold-accent); background: rgba(139, 30, 63, 0.12); background: color-mix(in srgb, var(--color-gold-accent) 12%, transparent); color: var(--color-gold-accent); }

    .snap-anchor { scroll-margin-top: 128px; }
    @media (min-width: 768px) { .snap-anchor { scroll-margin-top: 144px; } }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    @keyframes spin360 { to { transform: rotate(360deg); } }
    .spin { display: inline-block; animation: spin360 1s linear infinite; }
</style>

@php
    $maintenanceOn = ($settings['mode_maintenance'] ?? '0') === '1';
@endphp

<div class="space-y-section-gap w-full">
    {{--=== Banner ===--}}
    <section class="banner-gradient relative overflow-hidden rounded-2xl border border-muted-border p-6 md:p-8 card-premium">
        <span class="banner-glow banner-glow-1"></span>
        <span class="banner-glow banner-glow-2"></span>
        <div class="relative flex flex-col lg:flex-row lg:items-center gap-6">
            <div class="flex items-start gap-4 min-w-0">
                <div class="w-12 h-12 rounded-xl bg-secondary-container/20 border border-secondary/20 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[26px] text-white">settings</span>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="font-headline-md text-headline-md text-white tracking-wide">Kelola Platform Raliva</h2>
                        <span class="banner-badge {{ $maintenanceOn ? 'is-on' : 'is-off' }}"><span class="dot"></span>{{ $maintenanceOn ? 'Mode Pemeliharaan Aktif' : 'Semua Sistem Aktif' }}</span>
                    </div>
                    <p class="banner-desc mt-2 max-w-2xl">Konfigurasi aturan global platform — tarif, ambang pencairan, moderasi, tier peringkat iklan, hingga dokumen legal. Terpisah dari pengaturan tiap toko.</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3 lg:ml-auto shrink-0">
                <div class="stat-chip">
                    <span class="stat-chip-label">Komisi Default</span>
                    <span class="stat-chip-value">{{ $settings['komisi_persen_default'] }}%</span>
                </div>
                <div class="stat-chip">
                    <span class="stat-chip-label">Biaya Layanan</span>
                    <span class="stat-chip-value">Rp {{ number_format((int) $settings['biaya_layanan']) }}</span>
                </div>
                <div class="stat-chip">
                    <span class="stat-chip-label">Tier Peringkat</span>
                    <span class="stat-chip-value">{{ count($tiers) }}</span>
                </div>
            </div>
        </div>
    </section>

    {{--=== Quick Nav ===--}}
    <nav class="quick-nav min-w-0" aria-label="Navigasi cepat pengaturan">
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar rounded-xl border border-muted-border bg-surface-container-lowest shadow-sm p-2 -mx-4 px-4 md:mx-0 md:px-0">
            <a href="#kartu-umum" data-scroll-link="umum" class="quick-nav-pill"><span class="material-symbols-outlined text-[16px]">tune</span> Umum</a>
            <a href="#kartu-keuangan" data-scroll-link="keuangan" class="quick-nav-pill"><span class="material-symbols-outlined text-[16px]">payments</span> Keuangan</a>
            <a href="#kartu-moderasi" data-scroll-link="moderasi" class="quick-nav-pill"><span class="material-symbols-outlined text-[16px]">fact_check</span> Moderasi</a>
            <a href="#kartu-batas" data-scroll-link="batas" class="quick-nav-pill"><span class="material-symbols-outlined text-[16px]">speed</span> Batas</a>
            <a href="#kartu-tier" data-scroll-link="tier" class="quick-nav-pill"><span class="material-symbols-outlined text-[16px]">military_tech</span> Tier Peringkat</a>
            <a href="#kartu-legal" data-scroll-link="legal" class="quick-nav-pill"><span class="material-symbols-outlined text-[16px]">verified_user</span> Dokumen Legal</a>
        </div>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-gutter items-start">
        {{--=== Umum ===--}}
        <form id="kartu-umum" data-scroll-section="umum" method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}" class="snap-anchor bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium" data-save-form>
            @csrf @method('PUT')
            <div>
                <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">tune</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Umum</h2></div>
                <p class="font-body-md text-sm text-on-surface-variant mt-2">Identitas platform dan kontak dukungan customer.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nama_platform">Nama Platform</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="nama_platform" name="nama_platform" type="text" value="{{ $settings['nama_platform'] }}" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="email_support">Email Dukungan</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="email_support" name="email_support" type="email" value="{{ $settings['email_support'] }}" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="whatsapp_support">WhatsApp Dukungan</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="whatsapp_support" name="whatsapp_support" type="text" maxlength="20" inputmode="tel" value="{{ $settings['whatsapp_support'] }}" placeholder="6281234567890" />
                    <p class="font-body-md text-xs text-on-surface-variant mt-2">Format internasional tanpa tanda +. Ditampilkan sebagai tombol hubungi saat toko ditangguhkan.</p>
                </div>
            </div>
            <div class="flex items-center justify-between gap-4 p-4 border border-muted-border rounded-lg hover:bg-surface-container-low transition-colors">
                <div>
                    <p class="font-title-md text-sm text-on-surface">Mode Pemeliharaan</p>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Nonaktifkan akses publik sementara saat perbaikan sistem.</p>
                </div>
                <label class="raliva-toggle">
                    <input type="hidden" name="mode_maintenance" value="0" />
                    <input type="checkbox" name="mode_maintenance" value="1" class="sr-only peer" {{ $maintenanceOn ? 'checked' : '' }} />
                    <span class="raliva-toggle-track"></span>
                    <span class="raliva-toggle-knob"></span>
                </label>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" data-save-button class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Pengaturan Umum</button>
            </div>
        </form>

        {{--=== Keuangan ===--}}
        <form id="kartu-keuangan" data-scroll-section="keuangan" method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}" class="snap-anchor bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium" data-save-form>
            @csrf @method('PUT')
            <div>
                <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">payments</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Keuangan</h2></div>
                <p class="font-body-md text-sm text-on-surface-variant mt-2">Sumber pendapatan Raliva dan ambang pencairan dana Owner.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
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
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="min_pencairan">Minimal Pencairan (Rp)</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="min_pencairan" name="min_pencairan" type="number" min="0" value="{{ $settings['min_pencairan'] }}" required />
                <p class="font-body-md text-xs text-on-surface-variant mt-2">Batas minimal saldo Owner untuk mengajukan pencairan dana.</p>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" data-save-button class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Pengaturan Keuangan</button>
            </div>
        </form>

        {{--=== Moderasi & Konten ===--}}
        <form id="kartu-moderasi" data-scroll-section="moderasi" method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}" class="snap-anchor bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium" data-save-form>
            @csrf @method('PUT')
            <div>
                <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">fact_check</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Moderasi &amp; Konten</h2></div>
                <p class="font-body-md text-sm text-on-surface-variant mt-2">Alur persetujuan produk baru sebelum tampil ke publik.</p>
            </div>
            <div class="flex items-center justify-between gap-4 p-4 border border-muted-border rounded-lg hover:bg-surface-container-low transition-colors">
                <div>
                    <p class="font-title-md text-sm text-on-surface">Moderasi Otomatis</p>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Tandai produk baru sebagai "pending" sebelum ditinjau Super Admin.</p>
                </div>
                <label class="raliva-toggle">
                    <input type="hidden" name="moderasi_otomatis" value="0" />
                    <input type="checkbox" name="moderasi_otomatis" value="1" class="sr-only peer" {{ ($settings['moderasi_otomatis'] ?? '1') === '1' ? 'checked' : '' }} />
                    <span class="raliva-toggle-track"></span>
                    <span class="raliva-toggle-knob"></span>
                </label>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" data-save-button class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Pengaturan Moderasi</button>
            </div>
        </form>

        {{--=== Batas & Limit ===--}}
        <form id="kartu-batas" data-scroll-section="batas" method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}" class="snap-anchor bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium" data-save-form>
            @csrf @method('PUT')
            <div>
                <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">speed</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Batas &amp; Limit</h2></div>
                <p class="font-body-md text-sm text-on-surface-variant mt-2">Ambang operasional harian dan jendela refund transaksi.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="maks_pengajuan_pencairan">Maks Pengajuan Pencairan per Hari</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="maks_pengajuan_pencairan" name="maks_pengajuan_pencairan" type="number" min="1" value="{{ $settings['maks_pengajuan_pencairan'] }}" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="batas_waktu_refund">Batas Waktu Refund (hari)</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="batas_waktu_refund" name="batas_waktu_refund" type="number" min="1" value="{{ $settings['batas_waktu_refund'] }}" />
                </div>
            </div>
            <div class="flex justify-end pt-gutter border-t border-muted-border">
                <button type="submit" data-save-button class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Pengaturan Batas</button>
            </div>
        </form>
    </div>

    {{--=== Tier Peringkat Iklan ===--}}
    <section id="kartu-tier" data-scroll-section="tier" class="snap-anchor bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">military_tech</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Tier Peringkat Iklan</h2></div>
                <p class="font-body-md text-sm text-on-surface-variant mt-2">Struktur nominal ↔ durasi peringkat produk iklan.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-tier-tambah').classList.remove('hidden'); document.getElementById('modal-tier-tambah').classList.add('flex')" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">+ Tambah Tier</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] premium-table">
                <thead>
                    <tr class="border-b bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-3 text-center w-12">No</th>
                        <th class="p-3 text-left">Min (Rp)</th>
                        <th class="p-3 text-left">Max (Rp)</th>
                        <th class="p-3 text-center">Hari</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tiers as $i => $t)
                        <tr class="border-b border-muted-border hover:bg-surface-container-low">
                            <td class="p-3 text-center font-mono text-sm">{{ $i + 1 }}</td>
                            <td class="p-3 font-mono text-sm">Rp {{ number_format($t['min'], 0, ',', '.') }}</td>
                            <td class="p-3 font-mono text-sm">{{ $t['max'] ? 'Rp '.number_format($t['max'], 0, ',', '.') : '∞' }}</td>
                            <td class="p-3 text-center"><span class="inline-flex px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent border border-gold-accent/20 text-xs font-bold">{{ $t['hari'] }} hari</span></td>
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" onclick="openTierEdit({{ $i }}, {{ $t['min'] }}, '{{ $t['max'] ?? '' }}', {{ $t['hari'] }})" class="px-2.5 py-1.5 border border-gold-accent/40 rounded-lg text-[11px] font-bold uppercase text-gold-accent hover:bg-gold-accent/10">Edit</button>
                                    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.tier.destroy', $i) }}" onsubmit="return confirm('Hapus tier ini?')" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-on-surface-variant">Belum ada tier.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="font-body-md text-xs text-on-surface-variant">Periode dihitung <span class="font-bold text-on-surface">sejak disetujui</span>. Kosongkan Max = ∞. Hanya tier terakhir yang boleh Max kosong.</p>
    </section>

    {{--=== Syarat & Ketentuan & Kebijakan Privasi ===--}}
    <form id="kartu-legal" data-scroll-section="legal" method="POST" action="{{ route('superadmin.pengaturan-sistem.legal') }}" class="snap-anchor bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium" data-save-form>
        @csrf
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">verified_user</span></div>
                    <h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Syarat &amp; Ketentuan &amp; Kebijakan Privasi</h2>
                </div>
                <p class="font-body-md text-sm text-on-surface-variant mt-2">Dokumen yang tampil saat Customer mendaftar. Gunakan baris baru untuk memisahkan paragraf.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-secondary-container/20 text-secondary border border-secondary/20 font-label-sm text-[10px] uppercase tracking-wider">Ditampilkan saat Customer mendaftar</span>
        </div>

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
            <button type="submit" data-save-button class="bg-deep-onyx text-on-primary px-8 py-3 font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Konten Legal</button>
        </div>
    </form>
</div>

<!-- Modal Tambah Tier -->
<div id="modal-tier-tambah" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('modal-tier-tambah').classList.add('hidden')"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-muted-border">
            <h3 class="font-title-md text-title-md text-on-surface">Tambah Tier</h3>
            <button type="button" onclick="document.getElementById('modal-tier-tambah').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form method="POST" action="{{ route('superadmin.pengaturan-sistem.tier.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Min (Rp)</label>
                <input type="number" name="min" min="100000" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent" placeholder="100000" />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Max (Rp, kosong=∞)</label>
                <input type="number" name="max" min="100000" class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent" placeholder="∞" />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Hari</label>
                <input type="number" name="hari" min="1" max="365" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent" placeholder="7" />
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-tier-tambah').classList.add('hidden')" class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Tambah</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Tier -->
<div id="modal-tier-edit" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('modal-tier-edit').classList.add('hidden')"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-muted-border">
            <h3 class="font-title-md text-title-md text-on-surface">Edit Tier</h3>
            <button type="button" onclick="document.getElementById('modal-tier-edit').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="form-tier-edit" method="POST" action="" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Min (Rp)</label>
                <input type="number" name="min" id="edit-tier-min" min="100000" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent" />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Max (Rp, kosong=∞)</label>
                <input type="number" name="max" id="edit-tier-max" min="100000" class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent" placeholder="∞" />
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Hari</label>
                <input type="number" name="hari" id="edit-tier-hari" min="1" max="365" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent" />
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-tier-edit').classList.add('hidden')" class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTierEdit(index, min, max, hari) {
        document.getElementById('edit-tier-min').value = min;
        document.getElementById('edit-tier-max').value = max || '';
        document.getElementById('edit-tier-hari').value = hari;
        document.getElementById('form-tier-edit').action = '{{ url('superadmin/pengaturan-sistem/tier') }}/' + index;
        document.getElementById('modal-tier-edit').classList.remove('hidden');
        document.getElementById('modal-tier-edit').classList.add('flex');
    }

    (function () {
        var links = Array.from(document.querySelectorAll('[data-scroll-link]'));
        var sections = Array.from(document.querySelectorAll('[data-scroll-section]'));
        if (!links.length || !sections.length) return;

        /* Garis deteksi sedikit di bawah quick-nav sticky (scroll-margin 144px). */
        var spyLine = 160;
        var activeKey = null;

        function setActive(key) {
            activeKey = key;
            links.forEach(function (link) {
                link.classList.toggle('is-active', link.getAttribute('data-scroll-link') === key);
            });
        }

        function currentSection() {
            var bestKey = null;
            var bestTop = -Infinity;
            sections.forEach(function (s) {
                var top = s.getBoundingClientRect().top;
                if (top <= spyLine && top > bestTop) {
                    bestTop = top;
                    bestKey = s.getAttribute('data-scroll-section');
                }
            });

            if (!bestKey) {
                return sections[0].getAttribute('data-scroll-section');
            }

            /* Tie-break: kartu dalam satu baris (grid 2 kolom) punya posisi sama.
               Bila yang sedang aktif ikut di posisi puncak, pertahankan dia agar
               pill yang diklik pengguna tidak kebalik ke kartu sebelahnya. */
            var contending = sections.some(function (s) {
                return s.getAttribute('data-scroll-section') === activeKey &&
                    Math.abs(s.getBoundingClientRect().top - bestTop) < 0.5;
            });

            return contending ? activeKey : bestKey;
        }

        var ticking = false;
        function onScroll() {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(function () {
                ticking = false;
                setActive(currentSection());
            });
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);

        links.forEach(function (link) {
            link.addEventListener('click', function (e) {
                var target = document.getElementById(link.getAttribute('href').slice(1));
                if (!target) return;
                e.preventDefault();
                setActive(link.getAttribute('data-scroll-link'));
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                onScroll();
            });
        });

        onScroll();
    })();

    document.querySelectorAll('form[data-save-form]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('[data-save-button]');
            if (!btn || btn.disabled) return;
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';
            btn.innerHTML = '<span class="inline-flex items-center gap-2"><span class="material-symbols-outlined text-[16px] spin">progress_activity</span> Menyimpan&hellip;</span>';
        });
    });
</script>
@endpush
@endsection