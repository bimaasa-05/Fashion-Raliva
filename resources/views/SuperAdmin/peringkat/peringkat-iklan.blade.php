@extends('layouts.superadmin')

@section('title', 'Peringkat Produk Iklan')

@section('header-title', 'Peringkat Produk Iklan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Slot iklan berbayar — owner membayar agar produknya tampil paling atas di katalog pelanggan.')

@section('content')
<div class="space-y-6">
    <!-- Penjelasan Cara Kerja -->
    <div data-reveal class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gradient-to-r from-gold-accent/10 via-gold-accent/5 to-transparent rounded-lg">
        <span class="material-symbols-outlined text-gold-accent mt-0.5">campaign</span>
        <div>
            <p class="font-body-md text-sm font-bold text-on-surface">Cara kerja slot iklan</p>
            <p class="text-on-surface-variant text-sm mt-0.5">Owner menghubungi admin &amp; membayar agar produknya tampil di posisi teratas katalog pelanggan. <strong class="text-on-surface">Semakin besar pembayaran, semakin tinggi peringkatnya.</strong></p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">payments</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Total Pendapatan Iklan</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold leading-tight">Rp {{ number_format($totalPendapatan / 1000, 0, ',', '.') }}JT</span>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">ads_click</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Slot Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">{{ $slotAktif }} slot</span>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">trending_up</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Rata-rata Bid</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">Rp {{ number_format($rataRataBid, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Podium Top 3 -->
    @php // $top3 sudah difilter di controller: hanya aktif + periode berlaku
         $top3 = $top3 ?? collect(); @endphp
    @if($top3->count() >= 1)
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
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

    <div class="flex items-center justify-between gap-4 p-4 border border-gold-accent/20 bg-gold-accent/5 rounded-lg">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5 text-[20px]">tune</span>
            <div>
                <p class="font-bold text-sm text-on-surface">Tier Peringkat Diatur di Pengaturan Sistem</p>
                <p class="text-xs text-on-surface-variant mt-0.5">Nominal ↔ durasi dihitung sejak disetujui (fair). Kelola di Pengaturan → Tier Peringkat Iklan.</p>
            </div>
        </div>
        <a href="{{ route('superadmin.pengaturan-sistem') }}" class="px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">Kelola Tier</a>
    </div>

    <!-- Tabel Peringkat -->
    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 flex-wrap gap-3">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Peringkat Lengkap</h2>
            <button type="button" data-modal-open="modal-slot-baru" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span> Daftarkan Slot Iklan
            </button>
        </div>
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-center w-12">No.</th>
                        <th class="p-4 text-left">Posisi</th>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-left">Toko</th>
                        <th class="p-4 text-right">Bayaran (Bid)</th>
                        <th class="p-4 text-center">Periode Aktif</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse($slots as $i => $slot)
                        @php
                            $statusMap = [
                                'aktif' => ['Aktif', 'bg-secondary-container/20 text-secondary border-secondary/20'],
                                'nonaktif' => ['Nonaktif', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                                'ditunda' => ['Ditunda', 'bg-error/10 text-error border-error/20'],
                            ];
                            $st = $statusMap[$slot->status] ?? [$slot->status, 'bg-surface-container-high text-on-surface-variant'];
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
                            <td class="p-4 text-right">
                                @if($slot->status === 'ditunda' && $slot->payment_status === 'menunggu_verifikasi')
                                    <div class="flex items-center justify-end gap-1 flex-wrap">
                                        <form action="{{ route('superadmin.peringkat-iklan.verifikasi', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Verifikasi pembayaran iklan ini?', 'verifikasi')" class="inline-block">
                                            @csrf
                                            <button type="submit" class="px-2 py-1.5 border border-gold-accent/40 rounded-lg text-[10px] font-bold uppercase text-gold-accent hover:bg-gold-accent/10">Verifikasi</button>
                                        </form>
                                        <button type="button" onclick="openTolakIklan({{ $slot->ad_slot_id }})" class="px-2 py-1.5 border border-error/30 rounded-lg text-[10px] font-bold uppercase text-error hover:bg-error/10">Tolak</button>
                                    </div>
                                @elseif($slot->status === 'ditunda' && $slot->payment_status === 'terverifikasi')
                                    <div class="flex items-center justify-end gap-1 flex-wrap">
                                        <form action="{{ route('superadmin.peringkat-iklan.setujui', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Setujui dan aktifkan iklan ini? Biaya Rp {{ number_format((float) $slot->nominal_bid, 0, ',', '.') }} akan dicatat.', 'setujui')" class="inline-block">
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-on-surface-variant">Belum ada slot iklan terdaftar.</td>
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
                        'aktif' => ['Aktif', 'bg-secondary-container/20 text-secondary border-secondary/20'],
                        'nonaktif' => ['Nonaktif', 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                        'ditunda' => ['Ditunda', 'bg-error/10 text-error border-error/20'],
                    ];
                    $st = $statusMap[$slot->status] ?? [$slot->status, 'bg-surface-container-high text-on-surface-variant'];
                    $rank = 0;
                    foreach ($slots as $k => $item) { if ($item->slot_id === $slot->slot_id) { $rank = $k + 1; break; } }
                @endphp
                <article class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
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
                    @if($slot->status === 'ditunda' && $slot->payment_status === 'menunggu_verifikasi')
                        <div class="grid grid-cols-2 gap-2">
                            <form action="{{ route('superadmin.peringkat-iklan.verifikasi', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Verifikasi pembayaran iklan ini?', 'verifikasi')" class="inline-block">
                                @csrf
                                <button type="submit" class="w-full min-h-11 inline-flex items-center justify-center gap-2 border border-gold-accent/40 rounded-lg text-[11px] font-bold uppercase text-gold-accent hover:bg-gold-accent/10">Verifikasi</button>
                            </form>
                            <button type="button" onclick="openTolakIklan({{ $slot->ad_slot_id }})" class="w-full min-h-11 inline-flex items-center justify-center gap-2 border border-error/30 rounded-lg text-[11px] font-bold uppercase text-error hover:bg-error/10">Tolak</button>
                        </div>
                    @elseif($slot->status === 'ditunda' && $slot->payment_status === 'terverifikasi')
                        <div class="grid grid-cols-2 gap-2">
                            <form action="{{ route('superadmin.peringkat-iklan.setujui', $slot) }}" method="POST" onsubmit="return openConfirmPeringkat(event, 'Setujui dan aktifkan iklan ini?', 'setujui')" class="inline-block">
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
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">Belum ada slot iklan terdaftar.</p>
            @endforelse
        </div>
        @if ($slots->hasPages())
            <div class="mt-6 flex justify-center">{{ $slots->links() }}</div>
        @endif
    </section>

    <!-- Modal Daftarkan Slot Iklan -->
    <div id="modal-slot-baru" data-modal class="fixed inset-0 z-[70] hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]" data-modal-close></div>
        <div class="relative mx-auto mt-10 md:mt-16 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl border-t-4 border-t-gold-accent/70 shadow-xl max-h-[85vh] overflow-y-auto">
            <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface premium-heading">Daftarkan Slot Iklan Baru</h3>
                    <p class="text-on-surface-variant font-body-md text-sm mt-1">Peringkat otomatis mengikuti besaran bayaran tertinggi.</p>
                </div>
                <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
            </div>
            <form action="{{ route('superadmin.peringkat-iklan.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="product_id">Produk</label>
                    <select required id="product_id" name="product_id" class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent">
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->product_id }}">{{ $product->nama_produk }} ({{ $product->store->nama_toko ?? '-' }})</option>
                        @endforeach
                    </select>
                    @error('product_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="nominal_bid">Nominal Bayaran (Rp)</label>
                        <input type="number" min="100000" step="50000" id="nominal_bid" name="nominal_bid" value="{{ old('nominal_bid', 1000000) }}" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                        <p class="text-xs text-on-surface-variant mt-1.5">Minimal Rp 100.000 — nominal tertinggi menduduki peringkat 1. <span id="sa-preview-hari" class="font-bold text-gold-accent"></span></p>
                        @error('nominal_bid')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="tanggal_mulai">Mulai Berlaku <span class="text-xs normal-case text-on-surface-variant/60">(opsional, auto jika kosong)</span></label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                        @error('tanggal_mulai')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="tanggal_selesai">Berakhir <span class="text-xs normal-case text-on-surface-variant/60">(opsional)</span></label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                    @error('tanggal_selesai')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/5 rounded-lg">
                    <span class="material-symbols-outlined text-gold-accent mt-0.5 text-[20px]">schedule</span>
                    <div class="flex-1">
                        <p class="font-label-sm text-label-sm text-on-surface uppercase">Durasi Otomatis (Fair)</p>
                        <p class="text-xs text-on-surface-variant mt-1">Periode aktif dihitung <span class="font-bold text-on-surface">sejak disetujui</span>. Tier: 100k-499k→7 hari, 500k-999k→14 hari, 1jt-1,99jt→30 hari, ≥2jt→60 hari.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/5 rounded-lg">
                    <span class="material-symbols-outlined text-gold-accent mt-0.5 text-[20px]">info</span>
                    <p class="font-body-md text-xs text-on-surface-variant">Setelah disimpan, produk langsung naik ke peringkat sesuai urutan nominal dan tampil teratas di katalog pelanggan.</p>
                </div>
                <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/5 rounded-lg">
                    <span class="material-symbols-outlined text-gold-accent mt-0.5 text-[20px]">account_balance</span>
                    <div class="flex-1 min-w-0">
                        <p class="font-label-sm text-label-sm text-on-surface uppercase">Rekening Tujuan Transfer</p>
                        @forelse($rekenings ?? [] as $rek)
                            <div class="mt-2 flex items-center gap-2 text-sm"><span class="material-symbols-outlined text-[16px] text-on-surface-variant">credit_card</span><span class="font-mono">{{ $rek->bank->nama_bank ?? $rek->bank_id }} • {{ $rek->nomor_rekening }}</span></div>
                            <div class="flex items-center gap-2 text-sm text-on-surface-variant"><span class="material-symbols-outlined text-[16px]">person</span><span>a.n. {{ $rek->nama_pemilik }}</span></div>
                        @empty
                            <p class="text-xs text-on-surface-variant/60 italic mt-1">Belum ada rekening platform — isi di Data Bank.</p>
                        @endforelse
                        <p class="text-xs text-on-surface-variant mt-2">Transfer sesuai nominal bid, bukti diverifikasi di Tahap 2.</p>
                    </div>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Slot</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
        if (aksi === 'verifikasi') {
            if (titleEl) titleEl.textContent = 'Verifikasi pembayaran iklan ini?';
            if (iconWrap) iconWrap.className = 'w-14 h-14 rounded-full bg-gold-accent/10 border border-gold-accent/25 flex items-center justify-center mx-auto mb-5';
            if (iconSym) { iconSym.className = 'material-symbols-outlined text-gold-accent text-[28px]'; iconSym.textContent = 'verified'; }
            if (btn) { btn.textContent = 'Ya, Verifikasi'; btn.className = 'flex-1 bg-gold-accent text-deep-onyx font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-colors rounded-lg btn-premium'; }
        } else if (aksi === 'setujui') {
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
    function peringkatHariSA(nominal) {
        nominal = parseInt(nominal) || 0;
        const tiers = @json($tiers ?? []);
        for (const t of tiers) {
            const min = parseInt(t.min) || 0;
            const max = t.max === null || t.max === '' ? null : parseInt(t.max);
            const hari = parseInt(t.hari) || 7;
            if (nominal >= min && (max === null || nominal <= max)) return hari;
        }
        if (nominal >= 2000000) return 60;
        if (nominal >= 1000000) return 30;
        if (nominal >= 500000) return 14;
        if (nominal >= 100000) return 7;
        return 7;
    }
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('nominal_bid');
        const preview = document.getElementById('sa-preview-hari');
        function updatePreview() {
            const h = peringkatHariSA(input?.value);
            if (preview) preview.textContent = '→ ' + h + ' hari (sejak disetujui)';
        }
        if (input) { input.addEventListener('input', updatePreview); updatePreview(); }
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { closeConfirmPeringkat(); closeTolakIklan(); } });
</script>
@endpush
