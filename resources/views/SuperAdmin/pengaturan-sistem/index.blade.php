@extends('layouts.superadmin')

@section('title', 'Pengaturan Sistem')

@section('header-title', 'Pengaturan Sistem')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Konfigurasi aturan global platform Raliva, terpisah dari pengaturan toko.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap w-full">
    {{--=== Umum ===--}}
    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}" class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
        @csrf @method('PUT')
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
            <span class="shrink-0">
                <input type="hidden" name="mode_maintenance" value="0" />
                <input type="checkbox" name="mode_maintenance" value="1" class="w-5 h-5 accent-gold-accent shrink-0" {{ ($settings['mode_maintenance'] ?? '0') === '1' ? 'checked' : '' }} />
            </span>
        </label>
        <div class="flex justify-end pt-gutter border-t border-muted-border">
            <button type="submit" class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Pengaturan Umum</button>
        </div>
    </form>

    {{--=== Keuangan ===--}}
    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}" class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
        @csrf @method('PUT')
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
        <div class="flex justify-end pt-gutter border-t border-muted-border">
            <button type="submit" class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Pengaturan Keuangan</button>
        </div>
    </form>

    {{--=== Moderasi & Konten ===--}}
    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}" class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
        @csrf @method('PUT')
        <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">fact_check</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Moderasi & Konten</h2></div>
        <label class="flex items-center justify-between gap-4 p-4 border border-muted-border rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors">
            <span>
                <span class="block font-title-md text-on-surface">Moderasi Otomatis</span>
                <span class="block font-body-md text-sm text-on-surface-variant mt-1">Tandai produk baru sebagai "pending" sebelum ditinjau Super Admin.</span>
            </span>
            <span class="shrink-0">
                <input type="hidden" name="moderasi_otomatis" value="0" />
                <input type="checkbox" name="moderasi_otomatis" value="1" class="w-5 h-5 accent-gold-accent shrink-0" {{ ($settings['moderasi_otomatis'] ?? '1') === '1' ? 'checked' : '' }} />
            </span>
        </label>
        <div class="flex justify-end pt-gutter border-t border-muted-border">
            <button type="submit" class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Pengaturan Moderasi</button>
        </div>
    </form>

    {{--=== Batas & Limit ===--}}
    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}" class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
        @csrf @method('PUT')
        <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">speed</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Batas & Limit</h2></div>
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
            <button type="submit" class="px-8 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest hover:bg-tertiary-container transition-colors btn-premium">Simpan Pengaturan Batas</button>
        </div>
    </form>

    {{--=== Tier Peringkat Iklan ===--}}
    <section class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-lg bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-gold-accent text-[20px]">military_tech</span></div><h2 class="font-title-md text-title-md text-on-surface uppercase tracking-wider premium-heading">Tier Peringkat Iklan</h2></div>
            <button type="button" onclick="document.getElementById('modal-tier-tambah').classList.remove('hidden'); document.getElementById('modal-tier-tambah').classList.add('flex')" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">+ Tambah Tier</button>
        </div>
        <p class="font-body-md text-sm text-on-surface-variant">Atur nominal ↔ durasi. Periode dihitung <span class="font-bold text-on-surface">sejak disetujui</span>. Kosongkan Max = ∞.</p>
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
    </section>

    {{--=== Syarat & Ketentuan & Kebijakan Privasi ===--}}
    <form method="POST" action="{{ route('superadmin.pengaturan-sistem.legal') }}" class="bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 p-6 space-y-gutter card-premium">
        @csrf
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
</script>
@endpush
@endsection