@extends('layouts.owner')

@section('title', 'Paket Slot Produk')

@section('header-title', 'Paket Slot Produk')
@section('header-badge', 'Paket Growth')
@section('header-subtitle', 'Pantau kapasitas slot produk dan upgrade paket sesuai kebutuhan.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-64 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Paket Aktif --}}
    <section data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 md:p-8 relative overflow-hidden">
        <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[140px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">workspace_premium</span>
        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <p class="text-xs font-semibold text-gold-accent">Paket Aktif</p>
                <h2 class="raliva-figure text-[30px] mt-2">Growth</h2>
                <p class="font-body-md text-sm text-inverse-on-surface/70 mt-2">Rp 199.000 / bulan &bull; Berlaku s.d. 12 Feb 2027</p>
            </div>
            <div class="w-full max-w-md">
                <div class="flex items-end justify-between mb-2">
                    <span class="text-xs font-mediumr text-inverse-on-surface/60">Slot Terpakai</span>
                    <span class="font-title-md text-title-md"><span>142</span> <span class="text-inverse-on-surface/50">/ 200</span></span>
                </div>
                <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                    <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="71"></div>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <p class="font-label-sm text-[11px] text-inverse-on-surface/60">58 slot tersedia</p>
                    @if (71 >= 80)
                        <span class="text-xs font-semibold text-secondary">Segera Upgrade</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Pilihan Paket --}}
    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Pilihan Paket</h2>
        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-section-gap items-stretch">
            {{-- Basic --}}
            <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium">
                <p class="font-title-md text-title-md text-on-surface">Basic</p>
                <div class="mt-3 flex items-baseline gap-1">
                    <span class="raliva-figure text-[26px] text-on-surface">Rp 99rb</span>
                    <span class="text-on-surface-variant font-body-md text-sm">/bulan</span>
                </div>
                <p class="raliva-label mt-2">100 slot produk</p>
                <ul class="mt-6 space-y-3 flex-1 font-body-md text-sm text-on-surface">
                    @foreach ([['check', '100 slot produk aktif'], ['check', 'Moderasi prioritas standar'], ['close', 'Tanpa banner toko'], ['close', 'Tanpa analitik lanjutan']] as $f)
                        <li class="flex items-start gap-3 {{ $f[0] === 'check' ? '' : 'text-on-surface-variant' }}">
                            <span class="material-symbols-outlined text-[18px] {{ $f[0] === 'check' ? 'text-secondary' : 'text-error' }} shrink-0">{{ $f[0] === 'check' ? 'check_circle' : 'cancel' }}</span>{{ $f[1] }}
                        </li>
                    @endforeach
                </ul>
                <button type="button" onclick="showRalivaToast('Menurunkan paket akan berlaku pada periode berikutnya (demo).', 'swap_vert')" class="mt-8 w-full py-3 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Turunkan ke Basic</button>
            </article>

            {{-- Growth (Aktif) --}}
            <article data-reveal class="bg-surface-container-lowest border-2 border-gold-accent rounded-lg p-6 flex flex-col card-premium relative shadow-xl">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gold-accent text-white dark:text-[#111] text-xs font-semibold">Paket Anda</span>
                <p class="font-title-md text-title-md text-on-surface">Growth</p>
                <div class="mt-3 flex items-baseline gap-1">
                    <span class="raliva-figure text-[26px] text-gold-accent">Rp 199rb</span>
                    <span class="text-on-surface-variant font-body-md text-sm">/bulan</span>
                </div>
                <p class="raliva-label mt-2">200 slot produk</p>
                <ul class="mt-6 space-y-3 flex-1 font-body-md text-sm text-on-surface">
                    @foreach ([['check', '200 slot produk aktif'], ['check', 'Moderasi prioritas standar'], ['check', 'Banner promo toko'], ['close', 'Tanpa analitik lanjutan'], ['check', 'Dukungan chat 12 jam']] as $f)
                        <li class="flex items-start gap-3 {{ $f[0] === 'check' ? '' : 'text-on-surface-variant' }}">
                            <span class="material-symbols-outlined text-[18px] {{ $f[0] === 'check' ? 'text-secondary' : 'text-error' }} shrink-0">{{ $f[0] === 'check' ? 'check_circle' : 'cancel' }}</span>{{ $f[1] }}
                        </li>
                    @endforeach
                </ul>
                <button type="button" disabled class="mt-8 w-full py-3 bg-surface-container-high text-on-surface-variant rounded-lg text-sm font-semibold cursor-default">Sedang Digunakan</button>
            </article>

            {{-- Pro --}}
            <article data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 flex flex-col card-premium relative overflow-hidden">
                <div class="absolute top-0 right-0 px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[9px] uppercase tracking-widest rounded-bl-lg">Hemat 20% Tahunan</div>
                <p class="font-title-md text-title-md text-on-surface">Pro</p>
                <div class="mt-3 flex items-baseline gap-1">
                    <span class="raliva-figure text-[26px] text-on-surface">Rp 399rb</span>
                    <span class="text-on-surface-variant font-body-md text-sm">/bulan</span>
                </div>
                <p class="raliva-label mt-2">500 slot produk</p>
                <ul class="mt-6 space-y-3 flex-1 font-body-md text-sm text-on-surface">
                    @foreach ([['check', '500 slot produk aktif'], ['check', 'Moderasi prioritas tinggi'], ['check', 'Banner promo + highlight produk'], ['check', 'Analitik penjualan lanjutan'], ['check', 'Dukungan chat prioritas 24 jam']] as $f)
                        <li class="flex items-start gap-3 {{ $f[0] === 'check' ? '' : 'text-on-surface-variant' }}">
                            <span class="material-symbols-outlined text-[18px] {{ $f[0] === 'check' ? 'text-secondary' : 'text-error' }} shrink-0">{{ $f[0] === 'check' ? 'check_circle' : 'cancel' }}</span>{{ $f[1] }}
                        </li>
                    @endforeach
                </ul>
                <button type="button" data-modal-open="modal-upgrade" class="mt-8 w-full py-3 bg-deep-onyx text-on-primary rounded-lg text-sm font-semibold btn-premium">Upgrade ke Pro</button>
            </article>
        </div>

        <p data-reveal class="text-xs text-on-surface-variant mt-6 flex items-start gap-2 max-w-3xl">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            Peningkatan paket berlaku segera dengan pembayaran prorata. Penurunan paket hanya berlaku pada periode tagihan berikutnya dan memastikan jumlah produk Anda tidak melebihi slot baru.
        </p>
    </section>

    {{-- Riwayat Pembelian --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Riwayat Pembelian Paket</h2>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[720px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Invoice</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Paket</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Periode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Nominal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['inv' => 'INV-PKT-202602-014', 'paket' => 'Growth — Bulanan', 'periode' => '12 Jan — 12 Feb 2027', 'nominal' => 'Rp 199.000', 'status' => 'Lunas'],
                        ['inv' => 'INV-PKT-202601-009', 'paket' => 'Growth — Bulanan', 'periode' => '12 Des — 12 Jan 2027', 'nominal' => 'Rp 199.000', 'status' => 'Lunas'],
                        ['inv' => 'INV-PKT-202512-031', 'paket' => 'Upgrade Basic → Growth', 'periode' => '12 Nov — 12 Des 2026', 'nominal' => 'Rp 132.000', 'status' => 'Lunas'],
                        ['inv' => 'INV-PKT-202511-002', 'paket' => 'Basic — Bulanan', 'periode' => '12 Okt — 12 Nov 2026', 'nominal' => 'Rp 99.000', 'status' => 'Lunas'],
                    ] as $row)
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $row['inv'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface">{{ $row['paket'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['periode'] }}</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">{{ $row['nominal'] }}</td>
                            <td class="py-3.5 px-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $row['status'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

{{-- Modal Konfirmasi Upgrade --}}
<div id="modal-upgrade" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Konfirmasi Upgrade</h3>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form data-toast-message="Permintaan upgrade ke Paket Pro berhasil dikirim." class="p-6 space-y-5">
            <dl class="space-y-4 font-body-md text-sm">
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Paket Saat Ini</dt><dd class="text-on-surface font-bold">Growth</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Paket Tujuan</dt><dd class="text-gold-accent font-bold">Pro — 500 slot</dd></div>
                <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant">Biaya Prorata</dt><dd class="text-on-surface">Rp 266.000</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-on-surface-variant">Metode Pembayaran</dt><dd class="text-on-surface font-bold">Saldo Toko</dd></div>
            </dl>
            <div class="border border-gold-accent/25 bg-gold-accent/5 rounded-lg px-4 py-3 flex items-start gap-3">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">info</span>
                <p class="text-on-surface-variant font-body-md text-sm">Saldo tersedia Anda saat ini Rp 32.500.000 — cukup untuk biaya upgrade.</p>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">rocket_launch</span>Upgrade Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
