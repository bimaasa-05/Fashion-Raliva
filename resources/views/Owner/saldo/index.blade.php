@extends('layouts.owner')

@section('title', 'Saldo Toko')

@section('header-title', 'Saldo Toko')
@section('header-badge', 'Keuangan')
@section('header-subtitle', 'Kelola saldo, pencairan dana, dan pengembalian dana toko Anda dalam satu tempat.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-12 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Tab Switcher --}}
    <div class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 overflow-x-auto max-w-full">
        <button type="button" data-saldo-tab="ringkasan" class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Ringkasan</button>
        <button type="button" data-saldo-tab="pencairan" class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Pencairan Dana</button>
        <button type="button" data-saldo-tab="pengembalian" class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Pengembalian Dana</button>
    </div>

    {{-- ============ PANEL: RINGKASAN ============ --}}
    <div data-saldo-panel="ringkasan" class="space-y-section-gap">
        {{-- Kartu Saldo --}}
        <section data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
            {{-- Saldo Tersedia --}}
            <div data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 relative overflow-hidden flex flex-col">
                <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
                <p class="raliva-label text-gold-accent relative">Saldo Tersedia</p>
                <p class="raliva-figure text-[34px] md:text-[42px] mt-4 relative">Rp 32.500.000</p>
                <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                    <p class="font-body-md text-xs text-inverse-on-surface/60">Siap dicairkan kapan saja</p>
                    <a href="#pencairan" class="py-2.5 px-5 bg-gold-accent text-[#111] text-xs font-semibold rounded btn-premium shrink-0">Cairkan</a>
                </div>
            </div>

            {{-- Saldo Tertunda --}}
            <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
                <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
                <p class="raliva-label relative">Saldo Tertunda</p>
                <p class="raliva-figure text-[26px] mt-4 text-on-surface relative">Rp 7.100.000</p>
                <p class="text-on-surface-variant font-body-md text-xs mt-auto pt-6 relative">Dana dilepas otomatis menjadi saldo tersedia H+2 setelah pesanan selesai.</p>
            </div>

            {{-- Total Dicairkan --}}
            <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
                <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">savings</span>
                <p class="raliva-label relative">Total Dicairkan</p>
                <p class="raliva-figure text-[26px] mt-4 text-secondary relative">Rp 184.500.000</p>
                <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                    <p class="font-body-md text-xs text-on-surface-variant">92 pencairan sejak Mar 2026</p>
                    <a href="#pencairan" class="py-2.5 px-5 border border-muted-border text-xs font-semibold rounded-lg hover:border-gold-accent transition-colors shrink-0">Riwayat</a>
                </div>
            </div>
        </section>

        {{-- Grafik Tren Saldo + Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
            <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Tren Saldo — 6 Bulan Terakhir</h2>
                <div id="chart-wrap" class="relative h-64 md:h-72"><canvas id="saldo-chart"></canvas></div>
            </section>

            <section data-reveal-group class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Bulan Ini</h2>
                <ul class="space-y-4 font-body-md text-sm">
                    @foreach ([['Pesanan Selesai', 'Rp 41.250.000', 'add_circle', 'secondary'], ['Diskon & Komisi Platform', '− Rp 4.950.000', 'remove_circle', 'error'], ['Refund ke Customer', '− Rp 1.890.000', 'remove_circle', 'error'], ['Biaya Pencairan', '− Rp 15.000', 'remove_circle', 'error']] as $item)
                        <li data-reveal class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                            <span class="flex items-center gap-3 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px] {{ $item[3] === 'secondary' ? 'text-secondary fill' : 'text-error fill' }}">{{ $item[2] }}</span>{{ $item[0] }}
                            </span>
                            <span class="{{ $item[3] === 'secondary' ? 'text-secondary' : 'text-error' }} font-bold whitespace-nowrap">{{ $item[1] }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-6 pt-5 border-t border-muted-border flex items-center justify-between">
                    <span class="font-title-md text-sm text-on-surface">Perubahan Bersih</span>
                    <span class="font-title-md text-base text-secondary">+ Rp 34.395.000</span>
                </div>
            </section>
        </div>

        {{-- Riwayat Perubahan Saldo --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Riwayat Perubahan Saldo</h2>
            <div data-table-wrap class="overflow-x-auto">
                <table class="premium-table w-full min-w-[820px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Waktu</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Keterangan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Ref</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Perubahan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['tgl' => '22 Agu 2026, 15:02', 'ket' => 'Pencairan dana ke BCA ****8821', 'ref' => 'WD-0092', 'mutasi' => '-25.000.000', 'masuk' => false, 'akhir' => '32.500.000'],
                            ['tgl' => '22 Agu 2026, 11:40', 'ket' => 'Pesanan selesai — Nadia Putri', 'ref' => '#RLV-2089', 'mutasi' => '+1.890.000', 'masuk' => true, 'akhir' => '57.500.000'],
                            ['tgl' => '21 Agu 2026, 19:55', 'ket' => 'Komplain selesai — refund parsial', 'ref' => 'CMP-0034', 'mutasi' => '-450.000', 'masuk' => false, 'akhir' => '55.610.000'],
                            ['tgl' => '21 Agu 2026, 08:20', 'ket' => 'Pesanan selesai — Kevin Sanjaya', 'ref' => '#RLV-2090', 'mutasi' => '+459.000', 'masuk' => true, 'akhir' => '56.060.000'],
                            ['tgl' => '20 Agu 2026, 17:33', 'ket' => 'Pesanan selesai — Raka Aditya', 'ref' => '#RLV-2087', 'mutasi' => '+3.420.000', 'masuk' => true, 'akhir' => '55.601.000'],
                            ['tgl' => '19 Agu 2026, 14:10', 'ket' => 'Biaya layanan platform Agustus', 'ref' => 'INV-BIAYA-08', 'mutasi' => '-1.240.000', 'masuk' => false, 'akhir' => '52.181.000'],
                        ] as $row)
                            <tr class="border-b border-muted-border last:border-0">
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['tgl'] }}</td>
                                <td class="py-3.5 px-4 text-on-surface">{{ $row['ket'] }}</td>
                                <td class="py-3.5 px-4 font-bold text-on-surface whitespace-nowrap">{{ $row['ref'] }}</td>
                                <td class="py-3.5 px-4 text-right font-bold whitespace-nowrap {{ $row['masuk'] ? 'text-secondary' : 'text-error' }}">{{ $row['mutasi'] }}</td>
                                <td class="py-3.5 px-4 text-right text-on-surface whitespace-nowrap">{{ $row['akhir'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div data-pagination class="flex items-center justify-between pt-6 mt-2 border-t border-muted-border">
                <p class="text-xs text-on-surface-variant">Menampilkan 6 dari 214 mutasi</p>
                <button type="button" onclick="showRalivaToast('Memuat mutasi berikutnya (demo).')" class="text-xs font-semibold text-gold-accent hover:underline">Muat Lebih Banyak</button>
            </div>
        </section>
    </div>

    {{-- ============ PANEL: PENCAIRAN DANA ============ --}}
    <div data-saldo-panel="pencairan" class="hidden space-y-section-gap">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap items-start">
            {{-- Form Pengajuan --}}
            <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading mb-6">Ajukan Pencairan</h2>

                <div class="bg-surface-container-low border border-muted-border rounded-lg p-4 mb-6 flex items-center justify-between">
                    <div>
                        <p class="raliva-label">Saldo Tersedia</p>
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
                            <li class="flex items-start gap-2"><span class="material-symbols-outlined text-[14px] text-gold-accent mt-0.5">check</span>Biaya admin Rp 15.000 per transaksi.</li>
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

    {{-- ============ PANEL: PENGEMBALIAN DANA ============ --}}
    <div data-saldo-panel="pengembalian" class="hidden space-y-section-gap">
        {{-- Ringkasan --}}
        <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Kasus Berjalan</span>
                <span class="raliva-figure text-[26px] text-gold-accent">2</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">assignment_return</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai Bulan Ini</span>
                <span class="raliva-figure text-[26px] text-on-surface">5</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">task_alt</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Nilai Refund (Agu)</span>
                <span class="raliva-figure text-[26px] text-error">Rp 1.890.000</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">money_off</span>
            </div>
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Rasio Refund</span>
                <span class="raliva-figure text-[26px] text-secondary">1%</span>
                <span class="font-label-sm text-[11px] text-on-surface-variant">dari 389 pesanan bulan ini</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">percent</span>
            </div>
        </section>

        {{-- Tabel Kasus Refund --}}
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Daftar Kasus Pengembalian Dana</h2>
                <select data-table-filter="status-refund" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="diminta">Diminta</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Refund Selesai</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>

            <div data-table-wrap class="overflow-x-auto">
                <table class="premium-table w-full min-w-[940px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pesanan / Tanggal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Customer</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Alasan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Nominal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ([
                            ['kode' => '#RLV-2085', 'tgl' => '22 Agu 2026', 'customer' => 'Tania Kusuma', 'alasan' => 'Barang tidak sesuai deskripsi — warna berbeda', 'nominal' => 'Rp 459.000', 'status' => 'Diminta', 'key' => 'diminta'],
                            ['kode' => '#RLV-2079', 'tgl' => '21 Agu 2026', 'customer' => 'Hendra Wijaya', 'alasan' => 'Paket hilang dalam pengiriman', 'nominal' => 'Rp 789.000', 'status' => 'Diproses', 'key' => 'diproses'],
                            ['kode' => '#RLV-2076', 'tgl' => '18 Agu 2026', 'customer' => 'Maya Sari', 'alasan' => 'Ukuran tidak pas', 'nominal' => 'Rp 320.000', 'status' => 'Refund Selesai', 'key' => 'selesai'],
                            ['kode' => '#RLV-2071', 'tgl' => '15 Agu 2026', 'customer' => 'Yoga Pratama', 'alasan' => 'Batal sebelum dikirim', 'nominal' => 'Rp 529.000', 'status' => 'Refund Selesai', 'key' => 'selesai'],
                            ['kode' => '#RLV-2068', 'tgl' => '12 Agu 2026', 'customer' => 'Lina Marlina', 'alasan' => 'Menyesal membeli (tanpa cacat produk)', 'nominal' => 'Rp 259.000', 'status' => 'Ditolak', 'key' => 'ditolak'],
                        ] as $row)
                            <tr data-table-row data-status-refund="{{ $row['key'] }}" class="border-b border-muted-border last:border-0 align-top">
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-on-surface whitespace-nowrap">{{ $row['kode'] }}</p>
                                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $row['tgl'] }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $row['customer'] }}</td>
                                <td class="py-3.5 px-4 text-on-surface-variant max-w-[260px]">{{ $row['alasan'] }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">{{ $row['nominal'] }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if ($row['key'] === 'selesai')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $row['status'] }}</span>
                                    @elseif ($row['key'] === 'ditolak')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">{{ $row['status'] }}</span>
                                    @elseif ($row['key'] === 'diproses')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ $row['status'] }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">{{ $row['status'] }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" onclick="showRalivaToast('Detail kasus refund dibuka (demo).', 'visibility')" class="text-xs font-semibold {{ $row['key'] !== 'ditolak' && $row['key'] !== 'selesai' ? 'text-gold-accent' : 'text-on-surface-variant' }} hover:underline whitespace-nowrap">{{ $row['key'] !== 'ditolak' && $row['key'] !== 'selesai' ? 'Tinjau' : 'Detail' }}</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
                <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada kasus refund pada status ini.</p>
            </div>

            <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
                Keputusan akhir refund ditentukan Super Admin sesuai kebijakan platform. Nilai refund yang disetujui akan otomatis dipotong dari saldo toko.
            </p>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    /* ===== Tab Saldo: Ringkasan | Pencairan | Pengembalian ===== */
    const setSaldoTab = (name) => {
        document.querySelectorAll('[data-saldo-tab]').forEach((b) => {
            const isActive = b.getAttribute('data-saldo-tab') === name;
            b.classList.toggle('bg-deep-onyx', isActive);
            b.classList.toggle('text-on-primary', isActive);
            b.classList.toggle('text-on-surface-variant', !isActive);
        });
        document.querySelectorAll('[data-saldo-panel]').forEach((p) => {
            p.classList.toggle('hidden', p.getAttribute('data-saldo-panel') !== name);
        });
    };

    document.querySelectorAll('[data-saldo-tab]').forEach((b) => {
        b.addEventListener('click', () => {
            history.replaceState(null, '', '#' + b.getAttribute('data-saldo-tab'));
            setSaldoTab(b.getAttribute('data-saldo-tab'));
        });
    });

    const initSaldoFromHash = () => {
        const h = location.hash.replace('#', '');
        if (['pencairan', 'pengembalian'].includes(h)) setSaldoTab(h);
    };
    window.addEventListener('hashchange', initSaldoFromHash);

    window.ralivaOnReady(() => {
        initSaldoFromHash();
        try {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#333333' : '#E9E8E7';
            const tickColor = isDark ? '#BAB8B8' : '#747878';
            const tooltipBg = isDark ? '#F0EEEE' : '#1b1c1c';
            const tooltipText = isDark ? '#111111' : '#ffffff';

            /* Seluruh titik meluncur serentak dari kiri -> garis terbuka mulus tanpa patah */
            const drawAnim = {
                x: { type: 'number', duration: 950, easing: 'easeOutQuart', from: (ctx) => (ctx.chart && ctx.chart.chartArea ? ctx.chart.chartArea.left : 0) },
                y: { type: 'number', duration: 950, easing: 'easeOutQuart' }
            };

            new Chart(document.getElementById('saldo-chart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu'],
                    datasets: [
                        { label: 'Saldo Akhir Bulan', data: [18200000, 24600000, 28900000, 34200000, 38100000, 32500000], borderColor: '#C9A24D', backgroundColor: 'rgba(201, 162, 77, 0.12)', fill: true, tension: 0.35, borderWidth: 2, pointBackgroundColor: '#C9A24D', pointRadius: 3 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: drawAnim,
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, color: tickColor, font: { family: 'Manrope', size: 12 } } },
                        tooltip: {
                            backgroundColor: tooltipBg, titleColor: tooltipText, bodyColor: tooltipText,
                            titleFont: { family: 'Manrope', size: 12, weight: '700' }, bodyFont: { family: 'Manrope', size: 14 },
                            padding: 12, cornerRadius: 0,
                            callbacks: { label: (ctx) => ' Saldo: Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, font: { family: 'Manrope', size: 11 }, callback: (v) => (v / 1000000) + ' jt' } },
                        x: { grid: { display: false }, ticks: { color: tickColor, font: { family: 'Manrope', size: 11 } } }
                    }
                }
            });
        } catch (e) {}
    });

    document.querySelectorAll('[data-quick]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = document.getElementById('wd-nominal');
            if (input) input.value = btn.getAttribute('data-quick');
        });
    });
</script>
@endpush
