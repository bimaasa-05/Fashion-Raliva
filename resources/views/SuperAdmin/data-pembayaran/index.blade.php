@extends('layouts.superadmin')

@section('title', 'Data Pembayaran')

@section('header-title', 'Data Pembayaran')
@section('header-badge', 'Kelola & Lihat')
@section('header-subtitle', 'Monitor pembayaran platform dan status verifikasi untuk audit transaksi.')

@section('content')
<div class="space-y-section-gap">
    <section>
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Ringkasan Pembayaran</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-gutter">
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Hari Ini</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">payments</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">Rp 842,5JT</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">312 transaksi</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Verifikasi</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-secondary">18</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">perlu ditinjau</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Berhasil</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">check_circle</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface">1.204</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">30 hari terakhir</span>
            </div>
            <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Gagal / Dibatalkan</span>
                <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">cancel</span>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-error">27</span>
                <span class="font-label-sm text-[10px] uppercase text-on-surface-variant">30 hari terakhir</span>
            </div>
        </div>
    </section>

    <section data-table-scope class="space-y-gutter">
        <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Transaksi Pembayaran</h2>

        <div data-chip-group data-chip-key="status" class="flex flex-wrap gap-2">
            <button type="button" data-chip="semua" class="px-4 py-2 bg-deep-onyx text-on-primary border border-deep-onyx font-label-sm text-label-sm uppercase rounded transition-colors">Semua</button>
            <button type="button" data-chip="terverifikasi" class="px-4 py-2 border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-label-sm uppercase rounded transition-colors">Terverifikasi</button>
            <button type="button" data-chip="menunggu" class="px-4 py-2 border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-label-sm uppercase rounded transition-colors">Menunggu</button>
            <button type="button" data-chip="gagal" class="px-4 py-2 border border-muted-border text-on-surface-variant hover:bg-surface-container-high font-label-sm text-label-sm uppercase rounded transition-colors">Gagal</button>
        </div>

        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">ID Pembayaran</th>
                        <th class="p-4 text-left">Metode</th>
                        <th class="p-4 text-left">Pelanggan</th>
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-right">Jumlah</th>
                        <th class="p-4 text-center">Status Verifikasi</th>
                        <th class="p-4 text-left">Tanggal</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    <tr data-table-row data-status="terverifikasi" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">PAY-2026082101</td>
                        <td class="p-4 text-on-surface">Transfer Bank BCA</td>
                        <td class="p-4">
                            <p class="text-on-surface">Sarah Jenkins</p>
                            <p class="text-on-surface-variant text-xs">sarah@email.com</p>
                        </td>
                        <td class="p-4 text-on-surface">Lunara Fashion</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 2.550.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Terverifikasi</span>
                        </td>
                        <td class="p-4 text-on-surface-variant">21 Agu 2026 • 10.15</td>
                        <td class="p-4 text-right"><button type="button" data-detail-open="detail-pembayaran" data-d-nomor="PAY-2026082101" data-d-metode="Transfer Bank BCA" data-d-pelanggan="Sarah Jenkins (sarah@email.com)" data-d-toko="Lunara Fashion" data-d-jumlah="Rp 2.550.000" data-d-status="Terverifikasi" data-d-tanggal="21 Agu 2026 • 10.15" class="text-gold-accent hover:underline uppercase font-label-sm text-[10px]">Detail</button></td>
                    </tr>
                    <tr data-table-row data-status="menunggu" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">PAY-2026082102</td>
                        <td class="p-4 text-on-surface">E-Wallet</td>
                        <td class="p-4">
                            <p class="text-on-surface">Andi Pratama</p>
                            <p class="text-on-surface-variant text-xs">andi.p@email.com</p>
                        </td>
                        <td class="p-4 text-on-surface">Velvet Closet</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 780.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Menunggu</span>
                        </td>
                        <td class="p-4 text-on-surface-variant">21 Agu 2026 • 09.42</td>
                        <td class="p-4 text-right"><button type="button" onclick="showRalivaToast('Pembayaran PAY-2026082102 berhasil diverifikasi.', 'task_alt')" class="text-gold-accent hover:underline uppercase font-label-sm text-[10px]">Verifikasi</button></td>
                    </tr>
                    <tr data-table-row data-status="gagal" class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">PAY-2026082103</td>
                        <td class="p-4 text-on-surface">Virtual Account Mandiri</td>
                        <td class="p-4">
                            <p class="text-on-surface">Dewi Lestari</p>
                            <p class="text-on-surface-variant text-xs">dewi.l@email.com</p>
                        </td>
                        <td class="p-4 text-on-surface">Atelier Rina</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 1.320.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Gagal</span>
                        </td>
                        <td class="p-4 text-on-surface-variant">20 Agu 2026 • 22.08</td>
                        <td class="p-4 text-right"><button type="button" data-detail-open="detail-pembayaran" data-d-nomor="PAY-2026082103" data-d-metode="Virtual Account Mandiri" data-d-pelanggan="Dewi Lestari (dewi.l@email.com)" data-d-toko="Atelier Rina" data-d-jumlah="Rp 1.320.000" data-d-status="Gagal" data-d-tanggal="20 Agu 2026 • 22.08" class="text-gold-accent hover:underline uppercase font-label-sm text-[10px]">Detail</button></td>
                    </tr>
                    <tr data-table-row data-status="terverifikasi" class="hover:bg-surface-container-low transition-colors">
                        <td class="p-4 font-mono text-on-surface">PAY-2026082104</td>
                        <td class="p-4 text-on-surface">Kartu Kredit</td>
                        <td class="p-4">
                            <p class="text-on-surface">Budi Santoso</p>
                            <p class="text-on-surface-variant text-xs">budi.s@email.com</p>
                        </td>
                        <td class="p-4 text-on-surface">Lunara Fashion</td>
                        <td class="p-4 text-right font-bold text-gold-accent">Rp 455.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Terverifikasi</span>
                        </td>
                        <td class="p-4 text-on-surface-variant">20 Agu 2026 • 16.30</td>
                        <td class="p-4 text-right"><button type="button" data-detail-open="detail-pembayaran" data-d-nomor="PAY-2026082104" data-d-metode="Kartu Kredit" data-d-pelanggan="Budi Santoso (budi.s@email.com)" data-d-toko="Lunara Fashion" data-d-jumlah="Rp 455.000" data-d-status="Terverifikasi" data-d-tanggal="20 Agu 2026 • 16.30" class="text-gold-accent hover:underline uppercase font-label-sm text-[10px]">Detail</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<div id="detail-pembayaran" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6 max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Pembayaran</h3>
                <p class="text-on-surface-variant font-label-sm text-xs uppercase tracking-wider mt-1"><span data-slot="nomor"></span></p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <dl class="space-y-4 font-body-md text-sm">
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Metode</dt><dd class="text-on-surface text-right"><span data-slot="metode"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Pelanggan</dt><dd class="text-on-surface text-right"><span data-slot="pelanggan"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface text-right"><span data-slot="toko"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Jumlah</dt><dd class="font-bold text-gold-accent text-right"><span data-slot="jumlah"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Status Verifikasi</dt><dd class="text-on-surface text-right"><span data-slot="status"></span></dd></div>
            <div class="flex justify-between gap-4"><dt class="text-on-surface-variant shrink-0">Tanggal</dt><dd class="text-on-surface text-right"><span data-slot="tanggal"></span></dd></div>
        </dl>
        <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
    </div>
</div>
@endsection
