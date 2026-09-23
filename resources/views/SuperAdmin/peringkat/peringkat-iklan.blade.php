@extends('layouts.superadmin')

@section('title', 'Peringkat Produk Iklan')

@section('header-title', 'Peringkat Produk Iklan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Slot iklan berbayar — owner membayar agar produknya tampil paling atas di katalog pelanggan.')

@section('content')
<div class="space-y-6">
    <div data-reveal class="flex flex-wrap items-center gap-3 -mt-2">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-accent/10 border border-gold-accent/30 font-label-sm text-[11px] uppercase tracking-wider text-gold-accent">
            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
        </span>
        <span class="font-label-sm text-[11px] uppercase tracking-widest text-on-surface-variant inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
            Data iklan diperbarui real-time
        </span>
    </div>
    <!-- Penjelasan Cara Kerja -->
    <div data-reveal class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gradient-to-r from-gold-accent/10 via-gold-accent/5 to-transparent rounded-lg">
        <span class="material-symbols-outlined text-gold-accent mt-0.5">campaign</span>
        <div>
            <p class="font-body-md text-sm font-bold text-on-surface">Cara kerja slot iklan</p>
            <p class="text-on-surface-variant text-sm mt-0.5">Owner menghubungi admin &amp; membayar agar produknya tampil di posisi teratas katalog pelanggan. <strong class="text-on-surface">Semakin besar pembayaran, semakin tinggi peringkatnya.</strong></p>
        </div>
    </div>

    <!-- Statistik -->
    <div data-reveal-group class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Total Pendapatan Iklan</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold leading-tight break-words">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">payments</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Slot Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight break-words">{{ $slotAktif }} slot</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">ads_click</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Menunggu Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight break-words">{{ $slotTerjadwal }} slot</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">schedule</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-5 flex flex-col gap-2 relative overflow-hidden min-w-0 card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Rata-rata Bid</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight break-words">Rp {{ number_format($rataRataBid, 0, ',', '.') }}</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">trending_up</span>
        </div>
    </div>

    <!-- Podium Top 3 -->
    @php // $top3 sudah difilter di controller: hanya aktif + periode berlaku
         $top3 = $top3 ?? collect(); @endphp
    @if($top3->count() >= 1)
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 uppercase tracking-wider text-on-surface premium-heading">Podium Peringkat Saat Ini</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter items-end">
            @if(isset($top3[1]))
            <div class="md:order-1 bg-surface-container-low border border-muted-border rounded-xl p-6 flex flex-col items-center text-center gap-3">
                <span class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant text-on-surface flex items-center justify-center font-title-md font-bold">2</span>
                <div>
                    <p class="font-title-md text-sm text-on-surface leading-snug">{{ $top3[1]->product->nama_produk ?? '-' }}</p>
                    <p class="text-on-surface-variant text-xs mt-0.5">{{ $top3[1]->store->nama_toko ?? '-' }}</p>
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-none">Rp {{ number_format((float)$top3[1]->nominal_bid, 0, ',', '.') }}</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-muted-border">Aktif</span>
            </div>
            @endif
            @if(isset($top3[0]))
            <div class="md:order-2 border-2 border-gold-accent rounded-xl p-6 flex flex-col items-center text-center gap-3 relative overflow-hidden bg-gradient-to-b from-gold-accent/10 to-transparent hero-glow">
                <span class="absolute top-3 right-3 material-symbols-outlined text-gold-accent fill text-[28px]">workspace_premium</span>
                <span class="w-12 h-12 rounded-full bg-gold-accent text-white flex items-center justify-center font-title-md text-title-md font-bold shadow-lg">1</span>
                <div>
                    <p class="font-title-md text-title-md text-on-surface leading-snug">{{ $top3[0]->product->nama_produk ?? '-' }}</p>
                    <p class="text-on-surface-variant text-xs mt-0.5">{{ $top3[0]->store->nama_toko ?? '-' }}</p>
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold leading-none">Rp {{ number_format((float)$top3[0]->nominal_bid, 0, ',', '.') }}</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gold-accent/15 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30"><span class="material-symbols-outlined text-[12px]">check_circle</span>Posisi Teratas</span>
            </div>
            @endif
            @if(isset($top3[2]))
            <div class="md:order-3 bg-surface-container-low border border-muted-border rounded-xl p-6 flex flex-col items-center text-center gap-3">
                <span class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant text-on-surface flex items-center justify-center font-title-md font-bold">3</span>
                <div>
                    <p class="font-title-md text-sm text-on-surface leading-snug">{{ $top3[2]->product->nama_produk ?? '-' }}</p>
                    <p class="text-on-surface-variant text-xs mt-0.5">{{ $top3[2]->store->nama_toko ?? '-' }}</p>
                </div>
                <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-none">Rp {{ number_format((float)$top3[2]->nominal_bid, 0, ',', '.') }}</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-muted-border">Aktif</span>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Tabel Peringkat -->
    <section data-table-scope data-reveal class="bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden card-premium">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">{{ ($tab ?? 'daftar') === 'pengajuan' ? 'Pengajuan Iklan' : (($tab ?? 'daftar') === 'daftar' ? 'Daftar Peringkat Lengkap' : 'Riwayat Iklan') }}</h2>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border">{{ ($tab ?? 'daftar') === 'pengajuan' ? 'Pengajuan' : (($tab ?? 'daftar') === 'daftar' ? 'Aktif + Terjadwal' : 'Riwayat') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('superadmin.peringkat-iklan', ['tab' => 'pengajuan']) }}" class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-widest border transition-colors {{ ($tab ?? 'daftar') === 'pengajuan' ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:border-gold-accent' }}">Pengajuan</a>
                <a href="{{ route('superadmin.peringkat-iklan', ['tab' => 'daftar']) }}" class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-widest border transition-colors {{ ($tab ?? 'daftar') === 'daftar' ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:border-gold-accent' }}">Daftar</a>
                <a href="{{ route('superadmin.peringkat-iklan', ['tab' => 'riwayat']) }}" class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-widest border transition-colors {{ ($tab ?? 'daftar') === 'riwayat' ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:border-gold-accent' }}">Riwayat</a>
            </div>
        </div>
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="px-4 py-4 text-center w-12 text-[10px] font-semibold tracking-widest">No.</th>
                        <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Posisi</th>
                        <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Produk</th>
                        <th class="px-4 py-4 text-left text-[10px] font-semibold tracking-widest">Toko</th>
                        <th class="px-4 py-4 text-right text-[10px] font-semibold tracking-widest">Bayaran (Bid)</th>
                        <th class="px-4 py-4 text-center text-[10px] font-semibold tracking-widest">Periode Aktif</th>
                        <th class="px-4 py-4 text-center text-[10px] font-semibold tracking-widest">Status</th>
                        @if(($tab ?? 'daftar') === 'pengajuan')
                            <th class="px-4 py-4 text-right text-[10px] font-semibold tracking-widest">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse($slots as $i => $slot)
                        @php
                            $statusMap = [
                                'aktif' => ['Aktif', \App\Support\StatusStyle::badgeClass('aktif')],
                                'terjadwal' => ['Menunggu Aktif', \App\Support\StatusStyle::badgeClass('terjadwal')],
                                'nonaktif' => ['Nonaktif', \App\Support\StatusStyle::badgeClass('nonaktif')],
                                'ditunda' => ['Ditunda', \App\Support\StatusStyle::badgeClass('ditunda')],
                            ];
                            $st = $statusMap[$slot->status] ?? [$slot->status, \App\Support\StatusStyle::CLASS_NEUTRAL];
                            $rank = $i + 1;
                        @endphp
                        <tr data-table-row class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-center text-on-surface-variant font-mono">{{ $loop->iteration }}</td>
                            <td class="p-4">
                                @if($rank <= 3)
                                    <span class="inline-flex w-8 h-8 rounded-full {{ $rank === 1 ? 'bg-gold-accent text-white' : 'bg-surface-container-high border border-outline-variant text-on-surface' }} items-center justify-center font-bold">{{ $rank }}</span>
                                @else
                                    <span class="inline-flex w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface items-center justify-center font-bold text-sm">{{ $rank }}</span>
                                @endif
                            </td>
                            <td class="p-4 font-medium text-on-surface">{{ $slot->product->nama_produk ?? '-' }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $slot->store->nama_toko ?? '-' }}</td>
                            <td class="p-4 text-right font-title-md text-sm {{ $rank === 1 ? 'text-gold-accent' : 'text-on-surface' }} font-bold">Rp {{ number_format((float)$slot->nominal_bid, 0, ',', '.') }}</td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ $slot->tanggal_mulai ? \Carbon\Carbon::parse($slot->tanggal_mulai)->locale('id')->translatedFormat('d M') : '-' }} – {{ $slot->tanggal_selesai ? \Carbon\Carbon::parse($slot->tanggal_selesai)->locale('id')->translatedFormat('d M Y') : 'Menunggu' }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase border">{{ $st[0] }}</span>@if($slot->status === 'ditunda')<div class="mt-1"><span class="inline-flex px-2 py-0.5 rounded-full text-[9px] font-bold uppercase border {{ $slot->payment_status === 'terverifikasi' ? 'bg-success/10 text-success border-success/20' : ($slot->payment_status === 'ditolak' ? 'bg-error/10 text-error border-error/20' : 'bg-gold-accent/10 text-gold-accent border-gold-accent/20') }}">{{ $slot->payment_status ?? 'menunggu_verifikasi' }}</span></div>@if($slot->bankAccount)<div class="text-[10px] text-on-surface-variant mt-1">{{ $slot->bankAccount->bank->nama_bank ?? '' }} • {{ $slot->bankAccount->nomor_rekening }}</div>@endif @if($slot->file_bukti)<div class="mt-1"><a href="{{ asset('storage/' . $slot->file_bukti) }}" target="_blank" class="text-[10px] text-gold-accent hover:underline">Lihat Bukti</a></div>@endif @endif</td>
                            @if(($tab ?? 'daftar') === 'pengajuan')
                                <td class="p-4 text-right">
                                    @if($slot->status === 'ditunda')
                                        <div class="flex items-center justify-end gap-1 flex-wrap">
                                            <form action="{{ route('superadmin.peringkat-iklan.setujui', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Setujui iklan ini? Biaya Rp {{ number_format((float) $slot->nominal_bid, 0, ',', '.') }} akan didebit dari wallet toko.', 'setujui')" class="inline-block">
                                                @csrf
                                                <button type="submit" class="px-2 py-1.5 bg-deep-onyx text-on-primary rounded-lg text-[10px] font-bold uppercase hover:bg-black">Setujui</button>
                                            </form>
                                            <button type="button" onclick="openTolakIklan({{ $slot->ad_slot_id }})" class="px-2 py-1.5 border border-error/30 rounded-lg text-[10px] font-bold uppercase text-error hover:bg-error/10">Tolak</button>
                                        </div>
                                    @else
                                        <form action="{{ route('superadmin.peringkat-iklan.hapus', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Hapus slot iklan ini?', 'hapus')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 border border-error/30 rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-error hover:bg-error/10 transition-colors">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ ($tab ?? 'daftar') === 'pengajuan' ? 8 : 7 }}" class="p-8 text-center text-on-surface-variant">Belum ada slot iklan terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile: kartu peringkat -->
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse($slots as $slot)
                @php
$statusMap = [
                        'aktif' => ['Aktif', \App\Support\StatusStyle::badgeClass('aktif')],
                        'terjadwal' => ['Menunggu Aktif', \App\Support\StatusStyle::badgeClass('terjadwal')],
                        'nonaktif' => ['Nonaktif', \App\Support\StatusStyle::badgeClass('nonaktif')],
                        'ditunda' => ['Ditunda', \App\Support\StatusStyle::badgeClass('ditunda')],
                    ];
                    $st = $statusMap[$slot->status] ?? [$slot->status, \App\Support\StatusStyle::CLASS_NEUTRAL];
                    $rank = 0;
                    foreach ($slots as $k => $item) { if ($item->slot_id === $slot->slot_id) { $rank = $k + 1; break; } }
                @endphp
                <article class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <span class="material-symbols-outlined absolute right-1 bottom-1 text-[64px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">leaderboard</span>
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex w-9 h-9 rounded-full {{ $rank <= 3 ? ($rank === 1 ? 'bg-gold-accent text-white' : 'bg-surface-container-high border border-outline-variant text-on-surface') : 'bg-surface-container-high border border-outline-variant text-on-surface' }} items-center justify-center font-bold shrink-0">{{ $rank }}</span>
                            <div class="min-w-0">
                                <p class="font-title-md text-title-md text-on-surface truncate">{{ $slot->product->nama_produk ?? '-' }}</p>
                                <p class="text-on-surface-variant text-xs truncate">{{ $slot->store->nama_toko ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase border shrink-0">{{ $st[0] }}</span>
                    </div>
                    <dl class="space-y-2 font-body-md text-sm mb-4">
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Bayaran (Bid)</dt>
                            <dd class="font-bold {{ $rank === 1 ? 'text-gold-accent' : 'text-on-surface' }} text-right">Rp {{ number_format((float)$slot->nominal_bid, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-on-surface-variant">Periode</dt>
                            <dd class="text-on-surface-variant text-xs text-right whitespace-nowrap">{{ $slot->tanggal_mulai ? \Carbon\Carbon::parse($slot->tanggal_mulai)->locale('id')->translatedFormat('d M') : '-' }} – {{ $slot->tanggal_selesai ? \Carbon\Carbon::parse($slot->tanggal_selesai)->locale('id')->translatedFormat('d M Y') : 'Menunggu' }}</dd>
                        </div>
                        @if($slot->status === 'ditunda')
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Pembayaran</dt>
                                <dd class="text-right"><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $slot->payment_status === 'terverifikasi' ? 'bg-success/10 text-success border-success/20' : ($slot->payment_status === 'ditolak' ? 'bg-error/10 text-error border-error/20' : 'bg-gold-accent/10 text-gold-accent border-gold-accent/20') }}">{{ $slot->payment_status ?? 'menunggu_verifikasi' }}</span></dd>
                            </div>
                            @if($slot->bankAccount)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-on-surface-variant">Rekening</dt>
                                    <dd class="text-xs text-right">{{ $slot->bankAccount->bank->nama_bank ?? '' }} {{ $slot->bankAccount->nomor_rekening }}</dd>
                                </div>
                            @endif
                            @if($slot->file_bukti)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-on-surface-variant">Bukti</dt>
                                    <dd class="text-right"><a href="{{ asset('storage/' . $slot->file_bukti) }}" target="_blank" class="text-xs text-gold-accent hover:underline">Lihat Bukti</a></dd>
                                </div>
                            @endif
                        @endif
                    </dl>
                    @if(($tab ?? 'daftar') === 'pengajuan')
                        @if($slot->status === 'ditunda')
                        <div class="grid grid-cols-2 gap-2">
                            <form action="{{ route('superadmin.peringkat-iklan.setujui', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Setujui iklan ini? Biaya Rp {{ number_format((float) $slot->nominal_bid, 0, ',', '.') }} akan didebit dari wallet toko.', 'setujui')" class="inline-block">
                                @csrf
                                <button type="submit" class="w-full min-h-11 inline-flex items-center justify-center gap-2 bg-deep-onyx text-on-primary rounded-lg text-[11px] font-bold uppercase">Setujui</button>
                            </form>
                            <button type="button" onclick="openTolakIklan({{ $slot->ad_slot_id }})" class="w-full min-h-11 inline-flex items-center justify-center gap-2 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">Tolak</button>
                        </div>
                    @else
                        <form action="{{ route('superadmin.peringkat-iklan.hapus', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Hapus slot iklan ini?', 'hapus')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full min-h-11 inline-flex items-center justify-center gap-2 border border-error/30 rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-error hover:bg-error/10 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">delete</span>Hapus
                        </button>
                    </form>
                    @endif
                    @endif
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Belum ada slot iklan terdaftar.</p>
            @endforelse
        </div>
        @if ($slots->hasPages())
            <div class="mt-6 flex justify-center">{{ $slots->links() }}</div>
        @endif
    </section>

    <!-- Modal Konfirmasi Hapus Slot Iklan -->
<div id="confirmPeringkatModal" class="fixed inset-0 z-[75] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" onclick="if (event.target === this) closeConfirmPeringkat()">
    <div class="bg-surface-container-lowest w-full max-w-md rounded-xl border border-muted-border shadow-2xl overflow-hidden">
        <div class="p-8">
            <div id="confirm-peringkat-icon" class="w-14 h-14 rounded-full bg-error/10 border border-error/20 flex items-center justify-center mx-auto mb-5">
                <span id="confirm-peringkat-icon-sym" class="material-symbols-outlined text-error text-[28px]">delete</span>
            </div>
            <h3 id="confirm-peringkat-title" class="font-title-md text-title-md text-on-surface mb-2 text-center">Hapus slot iklan?</h3>
            <p id="confirm-peringkat-desc" class="text-on-surface-variant text-sm text-center mb-4">Hapus slot iklan ini?</p>
            <div class="flex space-x-3">
                <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeConfirmPeringkat()">Batal</button>
                <button type="button" id="confirm-peringkat-submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-error/90 transition-colors rounded-lg">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak Iklan -->
<div id="tolakIklanModal" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeTolakIklan()"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-error">Tolak Pengajuan</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">Tolak Iklan</h3>
            </div>
            <button type="button" onclick="closeTolakIklan()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="tolak-iklan-form" method="POST" action="" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Alasan Penolakan</label>
                <textarea name="alasan" rows="3" minlength="10" maxlength="1000" required class="w-full bg-transparent border border-muted-border rounded-lg px-4 py-3 font-body-md text-sm focus:outline-none focus:border-error focus:ring-1 focus:ring-error transition-colors" placeholder="Minimal 10 karakter"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" onclick="closeTolakIklan()" class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-error text-on-error text-sm font-semibold rounded flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[16px]">block</span>Tolak</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let _pendingPeringkatForm = null;
    function openConfirmPeringkat(e, msg, aksi) {
        e.preventDefault();
        _pendingPeringkatForm = e.target;
        document.getElementById('confirm-peringkat-desc').textContent = msg || 'Hapus slot iklan ini?';
        const titleEl = document.getElementById('confirm-peringkat-title');
        const iconWrap = document.getElementById('confirm-peringkat-icon');
        const iconSym = document.getElementById('confirm-peringkat-icon-sym');
        const btn = document.getElementById('confirm-peringkat-submit');
        if (aksi === 'setujui') {
            if (titleEl) titleEl.textContent = 'Setujui dan aktifkan iklan?';
            if (iconWrap) iconWrap.className = 'w-14 h-14 rounded-full bg-success/10 border border-success/25 flex items-center justify-center mx-auto mb-5';
            if (iconSym) { iconSym.className = 'material-symbols-outlined text-success text-[28px]'; iconSym.textContent = 'check_circle'; }
            if (btn) { btn.textContent = 'Ya, Setujui'; btn.className = 'flex-1 bg-deep-onyx text-on-primary font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium'; }
        } else {
            if (titleEl) titleEl.textContent = 'Hapus slot iklan?';
            if (iconWrap) iconWrap.className = 'w-14 h-14 rounded-full bg-error/10 border border-error/20 flex items-center justify-center mx-auto mb-5';
            if (iconSym) { iconSym.className = 'material-symbols-outlined text-error text-[28px]'; iconSym.textContent = 'delete'; }
            if (btn) { btn.textContent = 'Ya, Hapus'; btn.className = 'flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-error/90 transition-colors rounded-lg'; }
        }
        const m = document.getElementById('confirmPeringkatModal');
        m.classList.remove('hidden'); m.classList.add('flex');
        document.body.style.overflow = 'hidden';
        return false;
    }
    function closeConfirmPeringkat() {
        const m = document.getElementById('confirmPeringkatModal');
        if (m) { m.classList.add('hidden'); m.classList.remove('flex'); document.body.style.overflow = ''; }
        _pendingPeringkatForm = null;
    }
    document.getElementById('confirm-peringkat-submit')?.addEventListener('click', () => {
        if (_pendingPeringkatForm) _pendingPeringkatForm.submit();
        closeConfirmPeringkat();
    });
    function openTolakIklan(id) {
        const form = document.getElementById('tolak-iklan-form');
        form.action = '{{ route('superadmin.peringkat-iklan.tolak', ':id:') }}'.replace(':id:', id);
        document.getElementById('tolakIklanModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeTolakIklan() {
        document.getElementById('tolakIklanModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeConfirmPeringkat(); closeTolakIklan(); } });
</script>
@endpush
