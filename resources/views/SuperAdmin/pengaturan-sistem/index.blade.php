@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem')

@section('header-title', 'Pengaturan Sistem')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Konfigurasi aturan global platform Raliva, terpisah dari pengaturan toko.')

@section('content')
@include('partials.flash-toast')

<style>
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
            <a href="#kartu-help" data-scroll-link="help" class="quick-nav-pill"><span class="material-symbols-outlined text-[16px]">help_center</span> Pusat Bantuan</a>
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
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="biaya_penarikan_saldo">Biaya Penarikan Saldo Customer (Rp, flat)</label>
                <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="biaya_penarikan_saldo" name="biaya_penarikan_saldo" type="number" min="0" value="{{ $settings['biaya_penarikan_saldo'] ?? 0 }}" required />
                <p class="font-body-md text-xs text-on-surface-variant mt-2">Fee flat per penarikan saldo customer; diterima bersih = nominal − fee.</p>
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
                <p class="font-body-md text-sm text-on-surface-variant mt-2">Ambang operasional harian, jendela refund transaksi, dan auto-konfirmasi pesanan selesai.</p>
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
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="konfirmasi_selesai_hari">Auto Konfirmasi Selesai (hari)</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="konfirmasi_selesai_hari" name="konfirmasi_selesai_hari" type="number" min="1" value="{{ $settings['konfirmasi_selesai_hari'] }}" />
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
                        <th class="px-3 py-3 text-center w-12 text-[10px] font-semibold tracking-widest">No</th>
                        <th class="px-3 py-3 text-left text-[10px] font-semibold tracking-widest">Min (Rp)</th>
                        <th class="px-3 py-3 text-left text-[10px] font-semibold tracking-widest">Max (Rp)</th>
                        <th class="px-3 py-3 text-center text-[10px] font-semibold tracking-widest">Hari</th>
                        <th class="px-3 py-3 text-right text-[10px] font-semibold tracking-widest">Aksi</th>
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

    {{--=== Pusat Bantuan ===--}}
    <section id="kartu-help" data-scroll-section="help" class="snap-anchor bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">help_center</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Pusat Bantuan</h2></div>
                <p class="font-body-md text-sm text-on-surface-variant mt-2">Konten halaman Pusat Bantuan Customer: hero, kategori, dan FAQ. Info kontak memakai Email &amp; WhatsApp dari kartu Umum.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-secondary-container/20 text-secondary border border-secondary/20 font-label-sm text-[10px] uppercase tracking-wider">Tampil di halaman Help Center</span>
        </div>

        {{-- Hero + jam WhatsApp --}}
        <form method="POST" action="{{ route('superadmin.pengaturan-sistem.help') }}" class="space-y-gutter border border-muted-border rounded-lg p-4" data-save-form>
            @csrf @method('PUT')
            <p class="font-title-md text-sm text-on-surface">Teks Hero &amp; Jam Layanan</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="help_hero_title">Judul Hero</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="help_hero_title" name="help_hero_title" type="text" maxlength="150" value="{{ $helpHero['title'] }}" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="help_hero_subtitle">Subjudul Hero</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="help_hero_subtitle" name="help_hero_subtitle" type="text" maxlength="255" value="{{ $helpHero['subtitle'] }}" required />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="help_hero_search">Placeholder Pencarian</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="help_hero_search" name="help_hero_search" type="text" maxlength="100" value="{{ $helpHero['search'] }}" />
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="help_whatsapp_hours">Jam Operasional WhatsApp</label>
                    <input class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-body-md focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" id="help_whatsapp_hours" name="help_whatsapp_hours" type="text" maxlength="100" value="{{ $helpWhatsappHours }}" placeholder="Mon–Fri, 09.00–17.00 WIB" />
                </div>
            </div>
            <div class="flex justify-end border-t border-muted-border pt-gutter">
                <button type="submit" data-save-button class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Hero &amp; Kontak</button>
            </div>
        </form>

        {{-- Kategori --}}
        <div class="space-y-gutter border border-muted-border rounded-lg p-4">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <p class="font-title-md text-sm text-on-surface">Kategori Bantuan</p>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Kartu kategori yang tampil di bawah hero.</p>
                </div>
                <button type="button" onclick="openHelpModal('modal-help-cat-tambah')" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">+ Tambah Kategori</button>
            </div>
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full min-w-[640px] premium-table">
                    <thead>
                        <tr class="border-b bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                            <th class="px-3 py-3 text-center w-12 text-[10px] font-semibold tracking-widest">No</th>
                            <th class="px-3 py-3 text-center w-16 text-[10px] font-semibold tracking-widest">Ikon</th>
                            <th class="px-3 py-3 text-left text-[10px] font-semibold tracking-widest">Judul</th>
                            <th class="px-3 py-3 text-left text-[10px] font-semibold tracking-widest">Subjudul</th>
                            <th class="px-3 py-3 text-center w-16 text-[10px] font-semibold tracking-widest">Urutan</th>
                            <th class="px-3 py-3 text-center text-[10px] font-semibold tracking-widest">Status</th>
                            <th class="px-3 py-3 text-right text-[10px] font-semibold tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($helpCategories as $i => $c)
                            <tr class="border-b border-muted-border hover:bg-surface-container-low">
                                <td class="p-3 text-center font-mono text-sm">{{ $i + 1 }}</td>
                                <td class="p-3 text-center"><span class="material-symbols-outlined text-[20px] text-gold-accent">{{ $c->icon }}</span></td>
                                <td class="p-3 text-sm font-semibold">{{ $c->judul }}</td>
                                <td class="p-3 text-sm text-on-surface-variant">{{ $c->subjudul ?: '—' }}</td>
                                <td class="p-3 text-center font-mono text-sm">{{ $c->urutan }}</td>
                                <td class="p-3 text-center"><span class="inline-flex px-2 py-1 rounded-full text-[11px] font-bold border {{ $c->is_active ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container text-on-surface-variant border-muted-border' }}">{{ $c->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td class="p-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" onclick="openHelpCategoryEdit({{ $c->help_category_id }}, @js($c->icon), @js($c->judul), @js($c->subjudul), {{ $c->is_active ? 'true' : 'false' }})" class="px-2.5 py-1.5 border border-gold-accent/40 rounded-lg text-[11px] font-bold uppercase text-gold-accent hover:bg-gold-accent/10">Edit</button>
<button type="button" onclick="openHelpCategoryDelete({{ $c->help_category_id }}, @js($c->judul))" class="px-2.5 py-1.5 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="hidden md:table-row"><td colspan="7" class="p-8 text-center text-on-surface-variant">Belum ada kategori bantuan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="md:hidden space-y-gutter">
                @forelse($helpCategories as $c)
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px] text-gold-accent">{{ $c->icon }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-title-md text-sm text-on-surface">{{ $c->judul }}</p>
                                    @if ($c->subjudul)
                                        <p class="font-body-md text-xs text-on-surface-variant mt-0.5 line-clamp-1">{{ $c->subjudul }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex px-2 py-1 rounded-full text-[10px] font-bold border shrink-0 {{ $c->is_active ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container text-on-surface-variant border-muted-border' }}">{{ $c->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3 mt-3">
                            <span class="font-label-sm text-xs text-on-surface-variant">Urutan <span class="font-mono text-on-surface font-bold">{{ $c->urutan }}</span></span>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="openHelpCategoryEdit({{ $c->help_category_id }}, @js($c->icon), @js($c->judul), @js($c->subjudul), {{ $c->is_active ? 'true' : 'false' }})" class="px-3 py-1.5 border border-gold-accent/40 rounded-lg text-[11px] font-bold uppercase text-gold-accent hover:bg-gold-accent/10">Edit</button>
                                <button type="button" onclick="openHelpCategoryDelete({{ $c->help_category_id }}, @js($c->judul))" class="px-3 py-1.5 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">Hapus</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center font-body-md text-sm text-on-surface-variant py-6 border border-dashed border-muted-border rounded-lg">Belum ada kategori bantuan.</p>
                @endforelse
            </div>
        </div>

        {{-- FAQ --}}
        <div class="space-y-gutter border border-muted-border rounded-lg p-4">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <p class="font-title-md text-sm text-on-surface">Daftar FAQ</p>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Pertanyaan dan jawaban yang tampil di bagian Frequently Asked Questions.</p>
                </div>
                <button type="button" onclick="openHelpModal('modal-help-faq-tambah')" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">+ Tambah FAQ</button>
            </div>
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full min-w-[820px] premium-table">
                    <thead>
                        <tr class="border-b bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                            <th class="px-3 py-3 text-center w-12 text-[10px] font-semibold tracking-widest">No</th>
                            <th class="px-3 py-3 text-left text-[10px] font-semibold tracking-widest">Kategori</th>
                            <th class="px-3 py-3 text-left text-[10px] font-semibold tracking-widest">Pertanyaan</th>
                            <th class="px-3 py-3 text-left text-[10px] font-semibold tracking-widest">Jawaban</th>
                            <th class="px-3 py-3 text-center w-16 text-[10px] font-semibold tracking-widest">Urutan</th>
                            <th class="px-3 py-3 text-center text-[10px] font-semibold tracking-widest">Status</th>
                            <th class="px-3 py-3 text-right text-[10px] font-semibold tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($helpFaqs as $i => $f)
                            <tr class="border-b border-muted-border hover:bg-surface-container-low">
                                <td class="p-3 text-center font-mono text-sm">{{ $i + 1 }}</td>
                                <td class="p-3 text-sm"><span class="inline-flex items-center gap-1.5 text-on-surface-variant"><span class="material-symbols-outlined text-[15px]">{{ $f->category?->icon ?: 'help' }}</span>{{ $f->category?->judul ?: 'Tanpa Kategori' }}</span></td>
                                <td class="p-3 text-sm font-semibold max-w-[280px]">{{ $f->pertanyaan }}</td>
                                <td class="p-3 text-sm text-on-surface-variant max-w-[360px]">{{ \Illuminate\Support\Str::limit($f->jawaban, 90) }}</td>
                                <td class="p-3 text-center font-mono text-sm">{{ $f->urutan }}</td>
                                <td class="p-3 text-center"><span class="inline-flex px-2 py-1 rounded-full text-[11px] font-bold border {{ $f->is_active ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container text-on-surface-variant border-muted-border' }}">{{ $f->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td class="p-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" onclick="openHelpFaqEdit({{ $f->help_faq_id }}, {{ (int) $f->help_category_id }}, @js($f->pertanyaan), @js($f->jawaban), {{ $f->is_active ? 'true' : 'false' }})" class="px-2.5 py-1.5 border border-gold-accent/40 rounded-lg text-[11px] font-bold uppercase text-gold-accent hover:bg-gold-accent/10">Edit</button>
                                        <button type="button" onclick="openHelpFaqDelete({{ $f->help_faq_id }}, @js($f->pertanyaan))" class="px-2.5 py-1.5 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="hidden md:table-row"><td colspan="7" class="p-8 text-center text-on-surface-variant">Belum ada FAQ.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="md:hidden space-y-gutter">
                @forelse($helpFaqs as $f)
                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                        <div class="flex items-start justify-between gap-3">
                            <p class="font-title-md text-sm text-on-surface min-w-0">{{ $f->pertanyaan }}</p>
                            <span class="inline-flex px-2 py-1 rounded-full text-[10px] font-bold border shrink-0 {{ $f->is_active ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-surface-container text-on-surface-variant border-muted-border' }}">{{ $f->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-on-surface-variant mt-1.5"><span class="material-symbols-outlined text-[14px]">{{ $f->category?->icon ?: 'help' }}</span>{{ $f->category?->judul ?: 'Tanpa Kategori' }}</span>
                        <p class="font-body-md text-xs text-on-surface-variant leading-relaxed mt-1.5 line-clamp-2">{{ $f->jawaban }}</p>
                        <div class="flex items-center justify-between gap-3 mt-3">
                            <span class="font-label-sm text-xs text-on-surface-variant">Urutan <span class="font-mono text-on-surface font-bold">{{ $f->urutan }}</span></span>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="openHelpFaqEdit({{ $f->help_faq_id }}, {{ (int) $f->help_category_id }}, @js($f->pertanyaan), @js($f->jawaban), {{ $f->is_active ? 'true' : 'false' }})" class="px-3 py-1.5 border border-gold-accent/40 rounded-lg text-[11px] font-bold uppercase text-gold-accent hover:bg-gold-accent/10">Edit</button>
                                <button type="button" onclick="openHelpFaqDelete({{ $f->help_faq_id }}, @js($f->pertanyaan))" class="px-3 py-1.5 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">Hapus</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center font-body-md text-sm text-on-surface-variant py-6 border border-dashed border-muted-border rounded-lg">Belum ada FAQ.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>

<datalist id="help-icon-list">
    <option value="local_shipping"></option>
    <option value="assignment_return"></option>
    <option value="payments"></option>
    <option value="support_agent"></option>
    <option value="chat"></option>
    <option value="mail"></option>
    <option value="help"></option>
    <option value="help_center"></option>
    <option value="shopping_bag"></option>
    <option value="inventory_2"></option>
    <option value="verified_user"></option>
    <option value="credit_card"></option>
    <option value="storefront"></option>
    <option value="person"></option>
    <option value="lock"></option>
    <option value="local_offer"></option>
    <option value="replay"></option>
    <option value="receipt_long"></option>
</datalist>

<!-- Modal Tambah Kategori -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-help-cat-tambah',
    'dataModal' => true,
    'icon' => 'category',
    'title' => 'Tambah Kategori Bantuan',
    'subtitle' => 'Kartu kategori yang tampil di halaman Pusat Bantuan Customer.',
])
    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.help.kategori.store') }}" id="form-help-cat-tambah" class="space-y-4">
        @csrf
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Ikon (Material Symbols)</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gold-accent text-[18px] pointer-events-none">list</span>
                <input type="text" name="icon" list="help-icon-list" required maxlength="50" class="w-full bg-surface-container-low border border-muted-border rounded-xl pl-11 pr-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="local_shipping" />
            </div>
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Judul</label>
            <input type="text" name="judul" required maxlength="100" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="Shipping" />
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Subjudul</label>
            <input type="text" name="subjudul" maxlength="150" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="Track & delivery" />
        </div>
        <div class="flex items-center justified gap-4 p-4 border border-gold-accent/25 rounded-xl bg-gold-accent/5">
            <p class="font-label-sm text-xs text-on-surface-variant inline-flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">auto_awesome</span>Urutan diatur otomatis sesuai urutan pembuatan.</p>
        </div>
        @slot('footer')
            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
                <button type="submit" form="form-help-cat-tambah" class="btn-modal btn-modal-primary"><span class="material-symbols-outlined text-[18px]">add</span>Tambah</button>
            </div>
        @endslot
    </form>
@endcomponent

<!-- Modal Edit Kategori -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-help-cat-edit',
    'dataModal' => true,
    'icon' => 'edit',
    'title' => 'Edit Kategori Bantuan',
    'subtitle' => 'Ubah detail kategori bantuan Customer.',
])
    <form id="form-help-cat-edit" method="POST" action="" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Ikon (Material Symbols)</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gold-accent text-[18px] pointer-events-none">list</span>
                <input type="text" name="icon" id="edit-help-cat-icon" list="help-icon-list" required maxlength="50" class="w-full bg-surface-container-low border border-muted-border rounded-xl pl-11 pr-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
            </div>
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Judul</label>
            <input type="text" name="judul" id="edit-help-cat-judul" required maxlength="100" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Subjudul</label>
            <input type="text" name="subjudul" id="edit-help-cat-subjudul" maxlength="150" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
        </div>
        <div class="flex items-center justify-between gap-4 p-4 border border-muted-border rounded-xl bg-surface-container-low">
            <p class="font-title-md text-sm text-on-surface">Tampilkan kategori</p>
            <label class="raliva-toggle">
                <input type="hidden" name="is_active" value="0" />
                <input type="checkbox" name="is_active" id="edit-help-cat-active" value="1" class="sr-only peer" />
                <span class="raliva-toggle-track"></span>
                <span class="raliva-toggle-knob"></span>
            </label>
        </div>
        @slot('footer')
            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
                <button type="submit" form="form-help-cat-edit" class="btn-modal btn-modal-primary"><span class="material-symbols-outlined text-[18px]">save</span>Simpan</button>
            </div>
        @endslot
    </form>
@endcomponent

<!-- Modal Tambah FAQ -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-help-faq-tambah',
    'dataModal' => true,
    'icon' => 'quiz',
    'title' => 'Tambah FAQ',
    'subtitle' => 'Atur pertanyaan bantuan Customer dalam satu kategori.',
    'size' => 'lg',
])
    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.help.faq.store') }}" id="form-help-faq-tambah" class="space-y-4">
        @csrf
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Kategori</label>
            <div class="relative" id="helpAddCat-dd">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gold-accent text-[18px] pointer-events-none z-10">category</span>
                <button type="button" data-dd-trigger id="helpAddCat-trigger" onclick="toggleDropdown('helpAddCat')" aria-haspopup="listbox" aria-expanded="false"
                    class="w-full flex items-center justify-between gap-2 appearance-none bg-surface-container-low border border-muted-border rounded-xl pl-11 pr-4 py-3.5 font-body-md text-sm text-on-surface-variant focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors cursor-pointer text-left">
                    <span id="helpAddCat-label" class="truncate">Pilih kategori...</span>
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="helpAddCat-chevron">expand_more</span>
                </button>
                <div id="helpAddCat-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                    class="hidden absolute left-0 top-full mt-2 w-full min-w-[220px] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl z-50 overflow-y-auto max-h-64 py-1">
                    @foreach ($helpCategories as $cat)
                        <button type="button" role="option" aria-selected="false" data-dd-option="{{ $cat->help_category_id }}" onclick="selectHelpAddCat('{{ $cat->help_category_id }}')" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                            {{ $cat->judul }}<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="help_category_id" id="helpAddCategory" value="" />
            </div>
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Pertanyaan</label>
            <input type="text" name="pertanyaan" required maxlength="255" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="How do I track my order?" />
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Jawaban</label>
            <textarea name="jawaban" rows="4" required minlength="3" class="w-full bg-surface-container-low border border-muted-border rounded-xl p-4 font-body-md text-sm leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" placeholder="Tuliskan jawaban..."></textarea>
        </div>
        <div class="flex items-center justify-between gap-4 p-4 border border-gold-accent/25 rounded-xl bg-gold-accent/5">
            <p class="font-label-sm text-xs text-on-surface-variant inline-flex items-center gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent">auto_awesome</span>Urutan diatur otomatis sesuai urutan pembuatan.</p>
        </div>
        @slot('footer')
            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
                <button type="submit" form="form-help-faq-tambah" class="btn-modal btn-modal-primary"><span class="material-symbols-outlined text-[18px]">add</span>Tambah</button>
            </div>
        @endslot
    </form>
@endcomponent

<!-- Modal Edit FAQ -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-help-faq-edit',
    'dataModal' => true,
    'icon' => 'edit_note',
    'title' => 'Edit FAQ',
    'subtitle' => 'Perbarui pertanyaan, jawaban, dan kategori bantuan.',
    'size' => 'lg',
])
    <form id="form-help-faq-edit" method="POST" action="" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Kategori</label>
            <div class="relative" id="helpEditCat-dd">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gold-accent text-[18px] pointer-events-none z-10">category</span>
                <button type="button" data-dd-trigger id="helpEditCat-trigger" onclick="toggleDropdown('helpEditCat')" aria-haspopup="listbox" aria-expanded="false"
                    class="w-full flex items-center justify-between gap-2 appearance-none bg-surface-container-low border border-muted-border rounded-xl pl-11 pr-4 py-3.5 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors cursor-pointer text-left">
                    <span id="helpEditCat-label" class="truncate">Pilih kategori...</span>
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant transition-transform duration-200" data-dd-chevron id="helpEditCat-chevron">expand_more</span>
                </button>
                <div id="helpEditCat-menu" data-dropdown-menu role="listbox" style="transform-origin: top left"
                    class="hidden absolute left-0 top-full mt-2 w-full min-w-[220px] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl z-50 overflow-y-auto max-h-64 py-1">
                    @foreach ($helpCategories as $cat)
                        <button type="button" role="option" aria-selected="false" data-dd-option="{{ $cat->help_category_id }}" onclick="selectHelpEditCat('{{ $cat->help_category_id }}')" class="w-full flex items-center justify-between gap-2 text-left px-4 py-2.5 font-body-md text-sm text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                            {{ $cat->judul }}<span data-dd-check class="material-symbols-outlined text-[18px] text-gold-accent hidden">check</span>
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="help_category_id" id="edit-help-faq-category" value="" />
            </div>
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Pertanyaan</label>
            <input type="text" name="pertanyaan" id="edit-help-faq-pertanyaan" required maxlength="255" class="w-full bg-surface-container-low border border-muted-border rounded-xl px-4 py-3.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" />
        </div>
        <div>
            <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mb-2">Jawaban</label>
            <textarea name="jawaban" id="edit-help-faq-jawaban" rows="4" required minlength="3" class="w-full bg-surface-container-low border border-muted-border rounded-xl p-4 font-body-md text-sm leading-relaxed focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors"></textarea>
        </div>
        <div class="flex items-center justify-between gap-4 p-4 border border-muted-border rounded-xl bg-surface-container-low">
            <p class="font-title-md text-sm text-on-surface">Tampilkan FAQ</p>
            <label class="raliva-toggle">
                <input type="hidden" name="is_active" value="0" />
                <input type="checkbox" name="is_active" id="edit-help-faq-active" value="1" class="sr-only peer" />
                <span class="raliva-toggle-track"></span>
                <span class="raliva-toggle-knob"></span>
            </label>
        </div>
        @slot('footer')
            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
                <button type="submit" form="form-help-faq-edit" class="btn-modal btn-modal-primary"><span class="material-symbols-outlined text-[18px]">save</span>Simpan</button>
            </div>
        @endslot
    </form>
@endcomponent

<!-- Modal Hapus Kategori -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-help-kat-hapus',
    'dataModal' => true,
    'icon' => 'delete_forever',
    'title' => 'Hapus Kategori Bantuan',
    'subtitle' => 'Tindakan ini tidak bisa dibatalkan.',
])
    <form id="form-help-kat-hapus" method="POST" action="" class="space-y-4">
        @csrf @method('DELETE')
        <div class="p-4 border border-error/25 rounded-xl bg-error/5">
            <p class="font-body-md text-sm text-on-surface">Hapus kategori <span id="help-kat-hapus-nama" class="font-bold text-error">…</span>?</p>
        </div>
        <p class="font-body-md text-xs text-on-surface-variant inline-flex items-start gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent mt-[1px]">info</span>Kategori yang masih dipakai oleh FAQ tidak bisa dihapus.</p>
        @slot('footer')
            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
                <button type="submit" form="form-help-kat-hapus" class="btn-modal btn-modal-danger"><span class="material-symbols-outlined text-[18px]">delete</span>Hapus</button>
            </div>
        @endslot
    </form>
@endcomponent

<!-- Modal Hapus FAQ -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-help-faq-hapus',
    'dataModal' => true,
    'icon' => 'delete_forever',
    'title' => 'Hapus FAQ',
    'subtitle' => 'Tindakan ini tidak bisa dibatalkan.',
])
    <form id="form-help-faq-hapus" method="POST" action="" class="space-y-4">
        @csrf @method('DELETE')
        <div class="p-4 border border-error/25 rounded-xl bg-error/5">
            <p class="font-body-md text-sm text-on-surface">Hapus FAQ <span id="help-faq-hapus-teks" class="font-bold text-error">…</span>?</p>
        </div>
        <p class="font-body-md text-xs text-on-surface-variant inline-flex items-start gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent mt-[1px]">info</span>FAQ yang sudah dihapus tidak bisa dikembalikan.</p>
        @slot('footer')
            <div class="flex items-center justify-end gap-3">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
                <button type="submit" form="form-help-faq-hapus" class="btn-modal btn-modal-danger"><span class="material-symbols-outlined text-[18px]">delete</span>Hapus</button>
            </div>
        @endslot
    </form>
@endcomponent

<!-- Modal Tambah Tier -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-tier-tambah',
    'dataModal' => true,
    'icon' => 'workspace_premium',
    'title' => 'Tambah Tier',
])
    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.tier.store') }}" id="form-tier-tambah" class="space-y-4">
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
        @slot('footer')
            <div class="flex justify-end gap-2">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
                <button type="submit" form="form-tier-tambah" class="btn-modal btn-modal-primary">Tambah</button>
            </div>
        @endslot
    </form>
@endcomponent

<!-- Modal Edit Tier -->
@component('SuperAdmin.partials.premium-modal', [
    'id' => 'modal-tier-edit',
    'dataModal' => true,
    'icon' => 'edit',
    'title' => 'Edit Tier',
])
    <form id="form-tier-edit" method="POST" action="" class="space-y-4">
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
        @slot('footer')
            <div class="flex justify-end gap-2">
                <button type="button" data-modal-close class="btn-modal btn-modal-ghost">Batal</button>
                <button type="submit" form="form-tier-edit" class="btn-modal btn-modal-primary">Simpan</button>
            </div>
        @endslot
    </form>
@endcomponent

@push('scripts')
@include('SuperAdmin.partials.dd-helpers')
<script>
    const helpCatLabelMap = @json($helpCategories->pluck('judul', 'help_category_id'));

    function selectHelpAddCat(v) {
        ddSet('helpAddCat', v, helpCatLabelMap[v] ?? 'Pilih kategori...');
        const label = document.getElementById('helpAddCat-label');
        label.classList.toggle('text-on-surface-variant', !v);
        label.classList.toggle('text-on-surface', !!v);
    }
    function selectHelpEditCat(v) {
        ddSet('helpEditCat', v, helpCatLabelMap[v] ?? 'Pilih kategori...');
        const label = document.getElementById('helpEditCat-label');
        label.classList.toggle('text-on-surface-variant', !v);
        label.classList.toggle('text-on-surface', !!v);
    }
    function syncHelpEditCat() {
        selectHelpEditCat(document.getElementById('edit-help-faq-category').value);
    }
    document.getElementById('form-help-faq-tambah')?.addEventListener('submit', (e) => {
        if (!document.getElementById('helpAddCategory').value) {
            e.preventDefault();
            window.showRalivaToast?.('Pilih kategori bantuan terlebih dahulu.', 'error');
        }
    });

    function openTierEdit(index, min, max, hari) {
        document.getElementById('edit-tier-min').value = min;
        document.getElementById('edit-tier-max').value = max || '';
        document.getElementById('edit-tier-hari').value = hari;
        document.getElementById('form-tier-edit').action = '{{ url('superadmin/pengaturan-sistem/tier') }}/' + index;
        document.getElementById('modal-tier-edit').classList.remove('hidden');
        document.getElementById('modal-tier-edit').classList.add('flex');
    }

    function openHelpModal(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
    }

    function closeHelpModal(id) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
    }

    function openHelpCategoryEdit(id, icon, judul, subjudul, isActive) {
        document.getElementById('edit-help-cat-icon').value = icon || '';
        document.getElementById('edit-help-cat-judul').value = judul || '';
        document.getElementById('edit-help-cat-subjudul').value = subjudul || '';
        document.getElementById('edit-help-cat-active').checked = !!isActive;
        document.getElementById('form-help-cat-edit').action = '{{ url('superadmin/pengaturan-sistem/help/kategori') }}/' + id;
        openHelpModal('modal-help-cat-edit');
    }

    function openHelpFaqEdit(id, categoryId, pertanyaan, jawaban, isActive) {
        document.getElementById('edit-help-faq-category').value = categoryId || '';
        syncHelpEditCat();
        document.getElementById('edit-help-faq-pertanyaan').value = pertanyaan || '';
        document.getElementById('edit-help-faq-jawaban').value = jawaban || '';
        document.getElementById('edit-help-faq-active').checked = !!isActive;
        document.getElementById('form-help-faq-edit').action = '{{ url('superadmin/pengaturan-sistem/help/faq') }}/' + id;
        openHelpModal('modal-help-faq-edit');
    }

    function openHelpCategoryDelete(id, judul) {
        document.getElementById('form-help-kat-hapus').action = '{{ url('superadmin/pengaturan-sistem/help/kategori') }}/' + id;
        document.getElementById('help-kat-hapus-nama').textContent = judul || '';
        openHelpModal('modal-help-kat-hapus');
    }

    function openHelpFaqDelete(id, pertanyaan) {
        document.getElementById('form-help-faq-hapus').action = '{{ url('superadmin/pengaturan-sistem/help/faq') }}/' + id;
        document.getElementById('help-faq-hapus-teks').textContent = pertanyaan || '';
        openHelpModal('modal-help-faq-hapus');
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