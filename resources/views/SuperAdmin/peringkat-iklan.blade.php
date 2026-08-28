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
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">payments</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Total Pendapatan Iklan</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-gradient-gold leading-tight">Rp {{ number_format($totalPendapatan / 1000, 0, ',', '.') }}JT</span>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">ads_click</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Slot Aktif</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">{{ $slotAktif }} slot</span>
        </div>
        <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-5 flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">trending_up</span>
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-widest">Rata-rata Bid</span>
            <span class="font-headline-lg-mobile text-headline-lg-mobile text-on-surface leading-tight">Rp {{ number_format($rataRataBid, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Podium Top 3 -->
    @php $top3 = $slots->take(3); @endphp
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
                <span class="w-12 h-12 rounded-full bg-gold-accent text-deep-onyx flex items-center justify-center font-title-md text-title-md font-bold shadow-lg">1</span>
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
    <section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg overflow-hidden card-premium">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 flex-wrap gap-3">
            <h2 class="font-title-md text-title-md uppercase tracking-wider text-on-surface premium-heading">Daftar Peringkat Lengkap</h2>
            <button type="button" data-modal-open="modal-slot-baru" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span> Daftarkan Slot Iklan
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
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
                            <td class="p-4">
                                @if($rank <= 3)
                                    <span class="inline-flex w-8 h-8 rounded-full {{ $rank === 1 ? 'bg-gold-accent text-deep-onyx' : 'bg-surface-container-high border border-outline-variant text-on-surface' }} items-center justify-center font-bold">{{ $rank }}</span>
                                @else
                                    <span class="inline-flex w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface items-center justify-center font-bold text-sm">{{ $rank }}</span>
                                @endif
                            </td>
                            <td class="p-4 font-medium text-on-surface">{{ $slot->product->nama_produk ?? '-' }}</td>
                            <td class="p-4 text-on-surface-variant">{{ $slot->store->nama_toko ?? '-' }}</td>
                            <td class="p-4 text-right font-title-md text-sm {{ $rank === 1 ? 'text-gold-accent' : 'text-on-surface' }} font-bold">Rp {{ number_format((float)$slot->nominal_bid, 0, ',', '.') }}</td>
                            <td class="p-4 text-center text-on-surface-variant whitespace-nowrap">{{ \Carbon\Carbon::parse($slot->tanggal_mulai)->locale('id')->translatedFormat('d M') }} – {{ \Carbon\Carbon::parse($slot->tanggal_selesai)->locale('id')->translatedFormat('d M Y') }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center gap-1 px-2 py-1 rounded-full {{ $st[1] }} text-[10px] font-bold uppercase border">{{ $st[0] }}</span></td>
                            <td class="p-4 text-right">
                                <form action="{{ route('superadmin.peringkat-iklan.hapus', $slot) }}" method="POST" onsubmit="return confirm('Hapus slot iklan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 border border-error/30 rounded-lg text-[11px] font-label-sm uppercase tracking-wider text-error hover:bg-error/10 transition-colors">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-on-surface-variant">Belum ada slot iklan terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                        <p class="text-xs text-on-surface-variant mt-1.5">Minimal Rp 100.000 — nominal tertinggi menduduki peringkat 1.</p>
                        @error('nominal_bid')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="tanggal_mulai">Mulai Berlaku</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                        @error('tanggal_mulai')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2" for="tanggal_selesai">Berakhir</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm text-on-surface focus:outline-none focus:border-gold-accent" />
                    @error('tanggal_selesai')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/5 rounded-lg">
                    <span class="material-symbols-outlined text-gold-accent mt-0.5 text-[20px]">info</span>
                    <p class="font-body-md text-xs text-on-surface-variant">Setelah disimpan, produk langsung naik ke peringkat sesuai urutan nominal dan tampil teratas di katalog pelanggan.</p>
                </div>
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                    <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg font-label-sm text-[11px] uppercase tracking-widest text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Slot</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
