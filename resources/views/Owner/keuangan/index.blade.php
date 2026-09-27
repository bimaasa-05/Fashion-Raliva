@extends('layouts.owner')

@php
    $fmt = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $isMasuk = fn($jenis) => in_array($jenis, [
        \App\Models\WalletTransaction::JENIS_PENJUALAN_MASUK,
        \App\Models\WalletTransaction::JENIS_KOMISI_MASUK,
        \App\Models\WalletTransaction::JENIS_PEMASUKAN,
    ]);
@endphp

@section('title', 'Keuangan')
@section('header-title', 'Keuangan')
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
        @if (!isset($store) || !$store)
            <div data-no-store-banner
                class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
                <span class="material-symbols-outlined text-gold-accent mt-0.5">info</span>
                <div>
                    <p class="font-bold text-sm text-on-surface">Belum punya toko</p>
                    <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}"
                            class="underline text-gold-accent font-semibold">ajukan toko</a> untuk mulai kelola keuangan.
                        Data di bawah ini contoh 0.</p>
                </div>
            </div>
        @elseif (!$wallet)
            <div class="bg-surface-container-lowest border border-gold-accent/30 rounded-lg p-4 flex items-start gap-3">
                <span class="material-symbols-outlined text-gold-accent">account_balance_wallet</span>
                <p class="text-sm text-on-surface-variant">Dompet akan dibuat otomatis saat transaksi pertama. Keuangan
                    tetap bisa dicatat manual (Pemasukan/Pengeluaran).</p>
            </div>
        @endif
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div
                class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 overflow-x-auto max-w-full">
                <button type="button" data-saldo-tab="ringkasan"
                    class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Ringkasan</button>
                <button type="button" data-saldo-tab="pemasukan"
                    class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Pemasukan</button>
                <button type="button" data-saldo-tab="pengeluaran"
                    class="saldo-tab px-4 py-2 rounded-md text-xs font-semibold transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Pengeluaran</button>
            </div>
        </div>

        {{-- ============ PANEL: RINGKASAN ============ --}}
        <div data-saldo-panel="ringkasan" class="space-y-section-gap">
            {{-- Estimasi Margin (5 lapis) — bahasa awam --}}
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
                    <div>
                        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Perkiraan Keuntungan Toko
                        </h2>
                        <p class="text-xs text-on-surface-variant mt-1">Estimasi laba dari total penjualan, setelah potong
                            HPP dan pajak.</p>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span
                            class="text-[10px] uppercase tracking-wider text-on-surface-variant bg-surface-container-low px-2 py-1 rounded">Asumsi:
                            HPP 60% · Pajak 25%</span>
                        <form method="GET" action="{{ route('owner.keuangan') }}" class="flex items-center gap-2">
                            <select name="period" onchange="this.form.submit()" class="raliva-select text-xs py-2">
                                <option value="7" {{ ($period ?? 30) == 7 ? 'selected' : '' }}>7 Hari</option>
                                <option value="30" {{ ($period ?? 30) == 30 ? 'selected' : '' }}>30 Hari</option>
                                <option value="90" {{ ($period ?? 30) == 90 ? 'selected' : '' }}>90 Hari</option>
                                <option value="365" {{ ($period ?? 30) == 365 ? 'selected' : '' }}>1 Tahun</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div data-reveal-group class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
                    <div data-reveal
                        class="bg-surface-container-low p-5 rounded-lg flex flex-col gap-3 relative overflow-hidden">
                        <span class="text-on-surface-variant font-label-sm text-[12px] uppercase">Total Omzet <span
                                class="normal-case text-[10px] italic text-gold-accent/70">Revenue</span></span>
                        <span class="raliva-figure text-[24px] text-on-surface">{{ $fmt($margin['revenue']) }}</span>
                    </div>
                    <div data-reveal
                        class="bg-surface-container-low p-5 rounded-lg flex flex-col gap-3 relative overflow-hidden">
                        <span class="text-on-surface-variant font-label-sm text-[12px] uppercase">Laba Kotor <span
                                class="normal-case text-[10px] italic text-gold-accent/70">Gross Profit</span></span>
                        <span class="raliva-figure text-[24px] text-secondary">{{ $fmt($margin['gross']) }}</span>
                    </div>
                    <div data-reveal
                        class="bg-surface-container-low p-5 rounded-lg flex flex-col gap-3 relative overflow-hidden">
                        <span class="text-on-surface-variant font-label-sm text-[12px] uppercase">Laba Operasional <span
                                class="normal-case text-[10px] italic text-gold-accent/70">EBITDA</span></span>
                        <span class="raliva-figure text-[24px] text-on-surface">{{ $fmt($margin['ebitda']) }}</span>
                    </div>

                    <div data-reveal
                        class="bg-surface-container-low p-5 rounded-lg flex flex-col gap-3 relative overflow-hidden">
                        <span class="text-on-surface-variant font-label-sm text-[12px] uppercase">Laba Bersih <span
                                class="normal-case text-[10px] italic text-gold-accent/70">Net Profit</span></span>
                        <span class="raliva-figure text-[24px] text-gold-accent">{{ $fmt($margin['net']) }}</span>
                    </div>
                    <div data-reveal
                        class="bg-surface-container-low p-5 rounded-lg flex flex-col gap-3 relative overflow-hidden">
                        <span class="text-on-surface-variant font-label-sm text-[12px] uppercase">ROI <span
                                class="normal-case text-[10px] italic text-gold-accent/70">Return on Investment</span></span>
                        <span class="raliva-figure text-[24px] text-on-surface">{{ ($roiKeuangan ?? null) === null ? '-' : number_format($roiKeuangan, 2, ',', '.') . '%' }}</span>
                    </div>
                </div>
            </section>

            {{-- Grafik Tren Saldo + Info --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
                <section data-reveal
                    class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Tren Saldo — {{ ['7hari' => '7 Hari', '30hari' => '30 Hari', '90hari' => '90 Hari'][$grafik ?? '30hari'] ?? '30 Hari' }} Terakhir</h2>
                        <form method="GET" action="{{ route('owner.keuangan') }}" class="flex items-center gap-2">
                            @if(request('period'))<input type="hidden" name="period" value="{{ request('period') }}" />@endif
                            <select name="grafik" onchange="this.form.submit()" class="raliva-select text-xs py-2 w-auto">
                                <option value="7hari" @selected(($grafik ?? '30hari') === '7hari')>7 Hari</option>
                                <option value="30hari" @selected(($grafik ?? '30hari') === '30hari')>30 Hari</option>
                                <option value="90hari" @selected(($grafik ?? '') === '90hari')>90 Hari</option>
                            </select>
                        </form>
                    </div>
                    <div id="chart-wrap" class="relative h-64 md:h-72"><canvas id="saldo-chart"></canvas></div>
                </section>

                <section data-reveal-group
                    class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                    <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Ringkasan Bulan Ini</h2>
                    <ul class="space-y-4 font-body-md text-sm">
                        <li data-reveal
                            class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                            <span class="flex items-center gap-3 text-on-surface-variant">
                                <span
                                    class="material-symbols-outlined text-[18px] text-secondary fill">add_circle</span>Pemasukan
                            </span>
                            <span
                                class="text-secondary font-bold whitespace-nowrap">{{ $fmt($summary['pemasukan']) }}</span>
                        </li>
                        <li data-reveal
                            class="flex items-center justify-between gap-3 pb-4 border-b border-muted-border last:border-0 last:pb-0">
                            <span class="flex items-center gap-3 text-on-surface-variant">
                                <span
                                    class="material-symbols-outlined text-[18px] text-error fill">remove_circle</span>Pengeluaran
                            </span>
                            <span class="text-error font-bold whitespace-nowrap">−
                                {{ $fmt($summary['pengeluaran']) }}</span>
                        </li>
                    </ul>
                    <div class="mt-6 pt-5 border-t border-muted-border flex items-center justify-between">
                        <span class="font-title-md text-sm text-on-surface">Perubahan Bersih</span>
                        <span
                            class="font-title-md text-base {{ $summary['bersih'] >= 0 ? 'text-secondary' : 'text-error' }}">
                            {{ $summary['bersih'] >= 0 ? '+' : '−' }} {{ $fmt(abs($summary['bersih'])) }}
                        </span>
                    </div>
                </section>
            </div>

            {{-- Riwayat Perubahan Saldo --}}
            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium"
                data-table-scope>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Riwayat Perubahan Saldo</h2>
                    <form method="GET" action="{{ route('owner.keuangan') }}" class="flex flex-wrap items-center gap-2">
                        @if(request('period'))<input type="hidden" name="period" value="{{ request('period') }}" />@endif
                        <select name="kategori" onchange="this.form.submit()" class="raliva-select text-xs py-2 w-auto">
                            <option value="">Semua Kategori</option>
                            @foreach (($kategoriList ?? []) as $kat)
                                <option value="{{ $kat }}" @selected(($filterKategori ?? '') === $kat)>{{ $kat }}</option>
                            @endforeach
                        </select>
                        <select name="jenis" onchange="this.form.submit()" class="raliva-select text-xs py-2 w-auto">
                            <option value="">Semua Jenis</option>
                            @foreach (($jenisList ?? []) as $jen)
                                <option value="{{ $jen }}" @selected(($filterJenis ?? '') === $jen)>{{ $jen }}</option>
                            @endforeach
                        </select>
                        @if(($filterKategori ?? '') !== '' || ($filterJenis ?? '') !== '')
                            <a href="{{ route('owner.keuangan', array_filter(['period' => request('period')])) }}" class="text-xs text-on-surface-variant hover:text-gold-accent underline">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="hidden md:block">
                <div data-table-wrap class="overflow-x-auto">
                    <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                        <thead>
                            <tr class="border-b border-muted-border text-left">
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Waktu</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kategori</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Keterangan</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Perubahan</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Saldo Akhir
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mutations as $row)
                                @php $masuk = $isMasuk($row->jenis_transaksi); @endphp
                                <tr class="border-b border-muted-border last:border-0">
                                    <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">
                                        {{ $row->created_at->format('d M Y, H:i') }}</td>
                                    <td class="py-3.5 px-4 text-on-surface">{{ $row->kategori ?? 'Lainnya' }}</td>
                                    <td class="py-3.5 px-4 text-on-surface">{{ $row->keterangan }}</td>
                                    <td
                                        class="py-3.5 px-4 text-right font-bold whitespace-nowrap {{ $masuk ? 'text-secondary' : 'text-error' }}">
                                        {{ $masuk ? '+' : '−' }} {{ $fmt(abs($row->jumlah)) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right text-on-surface whitespace-nowrap">
                                        {{ $fmt($row->saldo_sesudah) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-on-surface-variant">Belum ada mutasi
                                        saldo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="md:hidden space-y-3">
                    @forelse ($mutations as $row)
                        @php $masuk = $isMasuk($row->jenis_transaksi); @endphp
                        <article class="bg-surface-container-lowest border border-muted-border rounded-xl p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-bold text-on-surface truncate">{{ $row->kategori ?? 'Lainnya' }}</p>
                                    <p class="text-xs text-on-surface-variant mt-0.5 line-clamp-2">{{ $row->keterangan }}</p>
                                </div>
                                <p class="font-bold whitespace-nowrap shrink-0 {{ $masuk ? 'text-secondary' : 'text-error' }}">{{ $masuk ? '+' : '−' }} {{ $fmt(abs($row->jumlah)) }}</p>
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-muted-border text-xs text-on-surface-variant">
                                <span class="whitespace-nowrap">{{ $row->created_at->format('d M Y, H:i') }}</span>
                                <span>Saldo <strong class="text-on-surface">{{ $fmt($row->saldo_sesudah) }}</strong></span>
                            </div>
                        </article>
                    @empty
                        <p class="py-8 text-center text-on-surface-variant">Belum ada mutasi saldo.</p>
                    @endforelse
                </div>
                <div class="flex items-center justify-between pt-6 mt-2 border-t border-muted-border">
                    <p class="text-xs text-on-surface-variant">Menampilkan {{ $mutations->count() }} dari
                        {{ $mutations instanceof \Illuminate\Pagination\AbstractPaginator ? $mutations->total() : $mutations->count() }}
                        mutasi</p>
                    @if ($mutations instanceof \Illuminate\Pagination\AbstractPaginator)
                        {{ $mutations->links() }}
                    @endif
                </div>
            </section>
        </div>

        {{-- ============ PANEL: PEMASUKAN ============ --}}
        <div data-saldo-panel="pemasukan" class="hidden space-y-section-gap">
            <section data-reveal
                class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Catat Pemasukan (Investor /
                    Modal)</h2>
                <form method="POST" action="{{ route('owner.keuangan.pemasukan.store') }}"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block raliva-label mb-2">Sumber Dana</label>
                        <input name="sumber" type="text" required placeholder="cth. Investor A / Modal Pribadi"
                            class="raliva-input" />
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Kategori</label>
                        <select name="kategori" required class="raliva-select">
                            @foreach (['Penjualan', 'Investor', 'Modal', 'Komisi', 'Lainnya'] as $kat)
                                <option value="{{ $kat }}">{{ $kat }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-on-surface-variant mt-1">Investor/Modal tercatat sebagai omzet (tidak masuk saldo tarik).</p>
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Nominal (Rp)</label>
                        <div class="flex items-stretch">
                            <span
                                class="inline-flex items-center px-4 text-sm font-bold text-on-surface-variant bg-surface-container-low border border-muted-border rounded-l-lg border-r-0 select-none">Rp</span>
                            <input name="nominal" type="text" inputmode="numeric" data-rupiah required
                                placeholder="5.000.000,-" class="raliva-input"
                                style="border-top-left-radius:0;border-bottom-left-radius:0;" />
                        </div>
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Tanggal</label>
                        <input name="tanggal" type="date" required value="{{ date('Y-m-d') }}"
                            class="raliva-input" />
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit"
                            class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">add</span>Catat Pemasukan
                        </button>
                    </div>
                </form>
            </section>

            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium"
                data-table-scope>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Pemasukan</h2>
                    <form method="GET" action="{{ route('owner.keuangan') }}#pemasukan" class="flex items-center gap-2">
                        <select name="kat_in" onchange="this.form.submit()" class="raliva-select text-xs py-2 w-auto">
                            <option value="">Semua Kategori</option>
                            @foreach (($katInList ?? []) as $kat)
                                <option value="{{ $kat }}" @selected(($filterKatIn ?? '') === $kat)>{{ $kat }}</option>
                            @endforeach
                        </select>
                        @if(($filterKatIn ?? '') !== '')
                            <a href="{{ route('owner.keuangan') }}#pemasukan" class="text-xs text-on-surface-variant hover:text-gold-accent underline">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="hidden md:block">
                <div data-table-wrap class="overflow-x-auto">
                    <table class="premium-table w-full min-w-[720px] font-body-md text-sm">
                        <thead>
                            <tr class="border-b border-muted-border text-left">
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Keterangan</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kategori</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (($pemasukanList ?? collect()) as $m)
                                <tr data-table-row class="border-b border-muted-border last:border-0">
                                    <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">
                                        {{ $m->created_at?->translatedFormat('d M Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-on-surface">{{ $m->keterangan ?? $m->jenis_transaksi }}</td>
                                    <td class="py-3.5 px-4 text-on-surface-variant">{{ $m->kategori ?? '-' }}</td>
                                    <td class="py-3.5 px-4 text-right font-bold text-secondary whitespace-nowrap">+
                                        {{ $fmt($m->jumlah) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada
                                        pemasukan tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="md:hidden space-y-3">
                    @forelse (($pemasukanList ?? collect()) as $m)
                        <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-bold text-on-surface truncate">{{ $m->keterangan ?? $m->jenis_transaksi }}</p>
                                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $m->kategori ?? '-' }} • {{ $m->created_at?->translatedFormat('d M Y') ?? '-' }}</p>
                                </div>
                                <p class="font-bold text-secondary whitespace-nowrap shrink-0">+ {{ $fmt($m->jumlah) }}</p>
                            </div>
                        </article>
                    @empty
                        <p class="py-8 text-center text-on-surface-variant">Belum ada pemasukan tercatat.</p>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- ============ PANEL: PENGELUARAN ============ --}}
        <div data-saldo-panel="pengeluaran" class="hidden space-y-section-gap">
            <section data-reveal
                class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
                <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Catat Pengeluaran Toko</h2>
                <form method="POST" action="{{ route('owner.keuangan.pengeluaran.store') }}"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block raliva-label mb-2">Nama Pengeluaran</label>
                        <input name="nama" type="text" required placeholder="cth. Listrik Toko"
                            class="raliva-input" />
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Kategori</label>
                        <input name="kategori" type="text" list="kategori-list" required
                            placeholder="cth. Operasional" class="raliva-input" />
                        <datalist id="kategori-list">
                            @foreach ($expenses->pluck('kategori')->unique() as $k)
                                <option value="{{ $k }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Nominal (Rp)</label>
                        <div class="flex items-stretch">
                            <span
                                class="inline-flex items-center px-4 text-sm font-bold text-on-surface-variant bg-surface-container-low border border-muted-border rounded-l-lg border-r-0 select-none">Rp</span>
                            <input name="nominal" type="text" inputmode="numeric" data-rupiah required
                                placeholder="500.000" class="raliva-input"
                                style="border-top-left-radius:0;border-bottom-left-radius:0;" />
                        </div>
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Tanggal</label>
                        <input name="tanggal" type="date" required value="{{ date('Y-m-d') }}"
                            class="raliva-input" />
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit"
                            class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">add</span>Catat Pengeluaran
                        </button>
                    </div>
                </form>
            </section>

            <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium"
                data-table-scope>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Pengeluaran</h2>
                    <form method="GET" action="{{ route('owner.keuangan') }}#pengeluaran" class="flex items-center gap-2">
                        <select name="kat_exp" onchange="this.form.submit()" class="raliva-select text-xs py-2 w-auto">
                            <option value="">Semua Kategori</option>
                            @foreach (($katExpList ?? []) as $kat)
                                <option value="{{ $kat }}" @selected(($filterKatExp ?? '') === $kat)>{{ $kat }}</option>
                            @endforeach
                        </select>
                        @if(($filterKatExp ?? '') !== '')
                            <a href="{{ route('owner.keuangan') }}#pengeluaran" class="text-xs text-on-surface-variant hover:text-gold-accent underline">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="hidden md:block">
                <div data-table-wrap class="overflow-x-auto">
                    <table class="premium-table w-full min-w-[720px] font-body-md text-sm">
                        <thead>
                            <tr class="border-b border-muted-border text-left">
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Nama</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kategori</th>
                                <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenses as $ex)
                                <tr data-table-row class="border-b border-muted-border last:border-0">
                                    <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">
                                        {{ $ex->tanggal->format('d M Y') }}</td>
                                    <td class="py-3.5 px-4 text-on-surface">{{ $ex->nama }}</td>
                                    <td class="py-3.5 px-4 text-on-surface-variant">{{ $ex->kategori }}</td>
                                    <td class="py-3.5 px-4 text-right font-bold text-error whitespace-nowrap">-
                                        {{ $fmt($ex->nominal) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada
                                        pengeluaran tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="md:hidden space-y-3">
                    @forelse ($expenses as $ex)
                        <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-bold text-on-surface truncate">{{ $ex->nama }}</p>
                                    <p class="text-xs text-on-surface-variant mt-0.5">{{ $ex->kategori }} • {{ $ex->tanggal->format('d M Y') }}</p>
                                </div>
                                <p class="font-bold text-error whitespace-nowrap shrink-0">- {{ $fmt($ex->nominal) }}</p>
                            </div>
                        </article>
                    @empty
                        <p class="py-8 text-center text-on-surface-variant">Belum ada pengeluaran tercatat.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (!document.querySelector('[data-real]')) return;
            // Check if no store banner exists (means no store)
            const noStore = document.querySelector('[data-no-store-banner]');
            if (!noStore) return;
            // Keep tab buttons working (Ringkasan/Pemasukan/Pengeluaran) — only disable action buttons
            document.querySelectorAll('[data-saldo-tab]').forEach(el => {
                el.classList.remove('pointer-events-none');
            });
            document.querySelectorAll('[data-real] button, [data-real] a.btn-premium').forEach(el => {
                if (el.closest('[data-modal]')) return;
                if (el.hasAttribute('data-saldo-tab')) return;
                if (el.textContent.trim().includes('Ajukan')) return;
                el.setAttribute('disabled', '');
                el.classList.add('opacity-60', 'cursor-not-allowed', 'pointer-events-none');
            });
        });
    </script>
    <script>
        /* ===== Tab Saldo: Ringkasan | Pemasukan | Pengeluaran | Pencairan ===== */
        const setSaldoTab = (name) => {
            document.querySelectorAll('[data-saldo-tab]').forEach((b) => {
                const isActive = b.getAttribute('data-saldo-tab') === name;
                b.classList.toggle('bg-deep-onyx', isActive);
                b.classList.toggle('text-on-primary', isActive);
                b.classList.toggle('text-on-surface-variant', !isActive);
                b.classList.toggle('hover:text-on-surface', !isActive);
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
            if (['ringkasan', 'pemasukan', 'pengeluaran', 'pencairan'].includes(h)) setSaldoTab(h);
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

                const chartLabels = @json($chart->pluck('label'));
                const chartData = @json($chart->pluck('saldo')->map(fn($v) => (float) $v));

                const drawAnim = {
                    x: {
                        type: 'number',
                        duration: 950,
                        easing: 'easeOutQuart',
                        from: (ctx) => (ctx.chart && ctx.chart.chartArea ? ctx.chart.chartArea.left : 0)
                    },
                    y: {
                        type: 'number',
                        duration: 950,
                        easing: 'easeOutQuart'
                    }
                };

                new Chart(document.getElementById('saldo-chart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Saldo Akhir Bulan',
                            data: chartData,
                            borderColor: '#8B1E3F',
                            backgroundColor: 'rgba(139, 30, 63, 0.12)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2,
                            pointBackgroundColor: '#8B1E3F',
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: drawAnim,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    color: tickColor,
                                    font: {
                                        family: 'Manrope',
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: tooltipBg,
                                titleColor: tooltipText,
                                bodyColor: tooltipText,
                                titleFont: {
                                    family: 'Manrope',
                                    size: 12,
                                    weight: '700'
                                },
                                bodyFont: {
                                    family: 'Manrope',
                                    size: 14
                                },
                                padding: 12,
                                cornerRadius: 0,
                                callbacks: {
                                    label: (ctx) => ' Saldo: Rp ' + new Intl.NumberFormat('id-ID').format(
                                        Number(ctx.raw))
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: tickColor,
                                    font: {
                                        family: 'Manrope',
                                        size: 11
                                    },
                                    callback: (v) => window.ralivaShortRp ? window.ralivaShortRp(v) : ((v /
                                        1000000).toLocaleString('id-ID', {
                                        maximumFractionDigits: 1
                                    }) + ' jt')
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: tickColor,
                                    font: {
                                        family: 'Manrope',
                                        size: 11
                                    }
                                }
                            }
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

        /* Format ribuan live untuk input nominal (ketik 1000000 → 1.000.000 + hint Rp).
           Robust: lewati saat komposisi IME, jaga posisi kursor, cegah double-format. */
        const formatRupiahField = (el) => {
            const raw = el.value;
            const selStart = el.selectionStart ?? raw.length;
            const digitsBefore = raw.slice(0, selStart).replace(/\D/g, '').length;
            const digits = raw.replace(/\D/g, '').slice(0, 15).replace(/^0+(?=\d)/, '');
            el.value = digits ? new Intl.NumberFormat('id-ID').format(digits) : '';
            let pos = 0,
                seen = 0;
            while (pos < el.value.length && seen < digitsBefore) {
                if (/\d/.test(el.value[pos])) seen++;
                pos++;
            }
            try {
                el.setSelectionRange(pos, pos);
            } catch (err) {}
        };
        document.addEventListener('input', (e) => {
            if (e.isComposing) return;
            const el = e.target?.closest?.('[data-rupiah]');
            if (!el || el.dataset.rupiahBound === 'live') return;
            formatRupiahField(el);
        });
        document.querySelectorAll('[data-rupiah]').forEach((el) => {
            if (el.dataset.rupiahBound) return;
            el.dataset.rupiahBound = 'live';
            el.setAttribute('autocomplete', 'off');
            el.setAttribute('spellcheck', 'false');
            el.addEventListener('input', (e) => {
                if (e.isComposing) return;
                e.stopImmediatePropagation();
                formatRupiahField(el);
            });
            if (el.value) formatRupiahField(el);
        });
        document.addEventListener('submit', (e) => {
            if (!(e.target instanceof HTMLFormElement)) return;
            e.target.querySelectorAll('[data-rupiah]').forEach((el) => {
                el.value = el.value.replace(/\./g, '');
            });
        });
    </script>
@endpush
