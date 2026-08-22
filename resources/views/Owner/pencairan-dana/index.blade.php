@extends('layouts.owner')

@section('title', 'Pencairan Dana')

@section('header-title', 'Pencairan Dana')
@section('header-badge', 'Saldo Siap Cair')
@section('header-subtitle', 'Ajukan pencairan saldo toko dan pantau statusnya.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <div class="lg:col-span-2 h-[520px] bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="lg:col-span-3 h-[520px] bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
</div>

<div data-real class="hidden space-y-section-gap">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap items-start">
        {{-- Form Pengajuan --}}
        <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading mb-6">Ajukan Pencairan</h2>

            <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6 flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-on-surface-variant">Saldo Tersedia</p>
                    <p class="font-title-md text-title-md text-secondary mt-1">Rp 32.500.000</p>
                </div>
                <span class="material-symbols-outlined fill text-[28px] text-gold-accent">account_balance_wallet</span>
            </div>

            <form data-toast-message="Permintaan pencairan berhasil diajukan." class="space-y-5">
                <div>
                    <label for="wd-nominal" class="block raliva-label mb-2">Nominal Pencairan</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-body-md text-sm text-on-surface-variant">Rp</span>
                        <input id="wd-nominal" type="number" value="25000000" min="100000" step="50000" required class="raliva-input pl-10 pr-4 py-3 font-title-md text-base" />
                    </div>
                    <div class="flex gap-gutter mt-3">
                        @foreach ([['Semua', '32500000'], ['10 jt', '10000000'], ['5 jt', '5000000'], ['1 jt', '1000000']] as $quick)
                            <button type="button" data-quick="{{ $quick[1] }}" class="flex-1 py-2 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent hover:text-gold-accent transition-colors">{{ $quick[0] }}</button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="wd-rekening" class="block raliva-label mb-2">Rekening Tujuan</label>
                    <select id="wd-rekening" required class="raliva-select">
                        <option selected>BCA — 8120****21 • Bima Prasetya</option>
                        <option>Mandiri — 1300****77 • Bima Prasetya</option>
                        <option>BSI — 7120****05 • PT Raliva Atelier</option>
                    </select>
                    <button type="button" onclick="showRalivaToast('Form tambah rekening dibuka (demo).', 'add_card')" class="mt-2 text-xs font-semibold text-gold-accent hover:underline">+ Tambah Rekening Baru</button>
                </div>

                <div>
                    <label for="wd-catatan" class="block raliva-label mb-2">Catatan (opsional)</label>
                    <textarea id="wd-catatan" rows="2" placeholder="cth. Pencairan untuk operasional bulan September..." class="raliva-textarea"></textarea>
                </div>

                <div class="border border-gold-accent/25 bg-gold-accent/5 rounded-lg px-4 py-4 space-y-2.5">
                    <p class="text-xs font-semibold text-gold-accent">Syarat & Ketentuan Pencairan</p>
                    <ul class="space-y-1.5 text-xs text-on-surface-variant">
                        <li class="flex items-start gap-2"><span class="material-symbols-outlined text-[14px] text-gold-accent mt-0.5">check</span>Minimum pencairan Rp 100.000 per pengajuan.</li>
                        <li class="flex items-start gap-2"><span class="material-symbols-outlined text-[14px] text-gold-accent mt-0.5">check</span>Biaya admin Rp 15.000 per transaksi (gratis untuk Paket Pro).</li>
                        <li class="flex items-start gap-2"><span class="material-symbols-outlined text-[14px] text-gold-accent mt-0.5">check</span>Dana diproses maksimal 2 hari kerja setelah verifikasi.</li>
                    </ul>
                </div>

                <button type="submit" class="w-full py-3.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">payments</span>Ajukan Sekarang
                </button>
            </form>
        </section>

        {{-- Riwayat Pencairan --}}
        <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Riwayat Pencairan</h2>
                <select data-table-filter="status-cair" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="dibayar">Dibayar</option>
                    <option value="diproses">Diproses</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <div data-table-wrap class="overflow-x-auto">
                <table class="premium-table w-full min-w-[760px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kode</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Nominal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Rekening</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['kode' => 'WD-0092', 'tgl' => '22 Agu 2026', 'nominal' => 'Rp 25.000.000', 'bank' => 'BCA ****8821', 'status' => 'Diproses', 'key' => 'diproses'],
                            ['kode' => 'WD-0091', 'tgl' => '08 Agu 2026', 'nominal' => 'Rp 20.000.000', 'bank' => 'BCA ****8821', 'status' => 'Dibayar', 'key' => 'dibayar'],
                            ['kode' => 'WD-0090', 'tgl' => '24 Jul 2026', 'nominal' => 'Rp 15.500.000', 'bank' => 'Mandiri ****0077', 'status' => 'Dibayar', 'key' => 'dibayar'],
                            ['kode' => 'WD-0089', 'tgl' => '10 Jul 2026', 'nominal' => 'Rp 30.000.000', 'bank' => 'BCA ****8821', 'status' => 'Ditolak', 'key' => 'ditolak'],
                            ['kode' => 'WD-0088', 'tgl' => '26 Jun 2026', 'nominal' => 'Rp 18.000.000', 'bank' => 'BCA ****8821', 'status' => 'Dibayar', 'key' => 'dibayar'],
                        ] as $row)
                            <tr data-table-row data-status-cair="{{ $row['key'] }}" class="border-b border-muted-border last:border-0">
                                <td class="py-3.5 px-4 font-bold text-on-surface whitespace-nowrap">{{ $row['kode'] }}</td>
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['tgl'] }}</td>
                                <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">{{ $row['nominal'] }}</td>
                                <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $row['bank'] }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if ($row['key'] === 'dibayar')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="material-symbols-outlined fill text-[12px]">check_circle</span>{{ $row['status'] }}</span>
                                    @elseif ($row['key'] === 'ditolak')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20" title="Rekening tujuan tidak cocok dengan identitas Owner">{{ $row['status'] }}</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30"><span class="material-symbols-outlined fill text-[12px]">schedule</span>{{ $row['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
                <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pencairan pada status ini.</p>
            </div>

            <div class="mt-6 pt-5 border-t border-muted-border grid grid-cols-3 gap-gutter text-center">
                <div><p class="font-title-md text-base text-on-surface">92×</p><p class="text-[11px] text-on-surface-variant mt-1">Total Pencairan</p></div>
                <div class="border-x border-muted-border"><p class="font-title-md text-base text-on-surface">&le; 1 hari</p><p class="text-[11px] text-on-surface-variant mt-1">Rata-rata Proses</p></div>
                <div><p class="font-title-md text-base text-secondary">99%</p><p class="text-[11px] text-on-surface-variant mt-1">Berhasil Dibayar</p></div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-quick]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = document.getElementById('wd-nominal');
            if (input) input.value = btn.getAttribute('data-quick');
        });
    });
</script>
@endpush
