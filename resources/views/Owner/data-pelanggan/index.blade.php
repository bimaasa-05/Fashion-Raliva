@extends('layouts.owner')

@section('title', 'Data Pelanggan')

@section('header-title', 'Data Pelanggan')
@section('header-badge', '1.284 Pelanggan')
@section('header-subtitle', 'Daftar pelanggan yang membeli di toko Anda — lihat riwayat dan Top Leader.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-section-gap">
        <div class="h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="lg:col-span-2 h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Podium Top Leader --}}
    @if ($top3->count() >= 3)
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 md:p-8 card-premium">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="raliva-label text-gold-accent">Papan Peringkat Pembeli</p>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading mt-1">Top Customer</h2>
            </div>
            <span class="material-symbols-outlined text-[40px] text-gold-accent/20">workspace_premium</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 items-end gap-gutter md:gap-4">
            {{-- #2 Kiri --}}
            <div class="order-2 md:order-1 bg-surface-container-low border border-muted-border rounded-xl p-5 flex flex-col items-center text-center md:mb-8">
                <div class="w-16 h-16 rounded-full bg-surface-container-high border-2 border-outline-variant flex items-center justify-center font-title-md text-lg text-on-surface">{{ $top3[1]->initials }}</div>
                <p class="font-title-md text-sm text-on-surface mt-3">{{ $top3[1]->name }}</p>
                <p class="text-gold-accent font-bold text-sm mt-1">Rp {{ number_format($top3[1]->total_belanja, 0, ',', '.') }}</p>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ $top3[1]->jumlah_order }} pesanan</p>
                <span class="mt-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase">#2</span>
            </div>

            {{-- #1 Tengah (tinggi) --}}
            <div class="order-1 md:order-2 bg-deep-onyx text-on-primary rounded-xl p-6 flex flex-col items-center text-center md:-mt-4 shadow-xl relative overflow-hidden">
                <span class="material-symbols-outlined absolute -right-4 -top-4 text-[120px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">military_tech</span>
                <div class="w-20 h-20 rounded-full bg-gold-accent text-deep-onyx flex items-center justify-center font-bold text-xl relative z-10">{{ $top3[0]->initials }}</div>
                <p class="raliva-figure text-[20px] text-on-primary mt-3 relative z-10">{{ $top3[0]->name }}</p>
                <p class="text-inverse-on-surface/70 text-xs mt-1 relative z-10">{{ $top3[0]->jumlah_order }} pesanan</p>
                <span class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gold-accent text-deep-onyx text-xs font-bold relative z-10"><span class="material-symbols-outlined text-[16px]">payments</span>Rp {{ number_format($top3[0]->total_belanja, 0, ',', '.') }}</span>
            </div>

            {{-- #3 Kanan --}}
            <div class="order-3 bg-surface-container-low border border-muted-border rounded-xl p-5 flex flex-col items-center text-center md:mb-4">
                <div class="w-16 h-16 rounded-full bg-surface-container-high border-2 border-outline-variant flex items-center justify-center font-title-md text-lg text-on-surface">{{ $top3[2]->initials }}</div>
                <p class="font-title-md text-sm text-on-surface mt-3">{{ $top3[2]->name }}</p>
                <p class="text-gold-accent font-bold text-sm mt-1">Rp {{ number_format($top3[2]->total_belanja, 0, ',', '.') }}</p>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ $top3[2]->jumlah_order }} pesanan</p>
                <span class="mt-3 inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase">#3</span>
            </div>
        </div>
    </section>
    @else
    <section data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 md:p-8 relative overflow-hidden">
        <span class="material-symbols-outlined absolute -right-6 -bottom-8 text-[160px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">workspace_premium</span>
        <div class="relative">
            <p class="raliva-label text-gold-accent">Top Leader Bulan Ini</p>
            <p class="raliva-figure text-[26px] text-on-primary mt-4">{{ $topLeader->name ?? '-' }}</p>
            <p class="text-inverse-on-surface/60 font-body-md text-sm mt-1">{{ $topLeader->email ?? '' }} • {{ $topLeader->jumlah_order ?? 0 }} pesanan</p>
        </div>
    </section>
    @endif

    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pelanggan</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ number_format($summary['total'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">unik pernah belanja</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">groups</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pelanggan Baru (Agu)</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $summary['baru'] }}</span>
            <span class="font-label-sm text-[11px] text-secondary">bulan ini</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">person_add</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Repeat Buyer</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $summary['total'] > 0 ? round($summary['repeat'] / $summary['total'] * 100) : 0 }}%</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">beli ≥2 kali</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">repeat</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Rata Belanja</span>
            <span class="raliva-figure text-[26px] text-gold-accent">Rp {{ number_format($summary['rata'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">per pelanggan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/10 pointer-events-none select-none" aria-hidden="true">receipt_long</span>
        </div>
    </section>

    {{-- Tabel Pelanggan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="relative flex-1 min-w-[220px] max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari nama atau email..." data-table-search class="raliva-search" />
            </div>
            <select data-table-filter="segment" class="raliva-select">
                <option value="">Semua Segmen</option>
                <option value="leader">Top Leader</option>
                <option value="setia">Pelanggan Setia</option>
                <option value="baru">Baru</option>
            </select>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pelanggan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kontak</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Barang Dibeli</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Total Belanja</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Terakhir Belanja</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Segmen</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr data-table-row data-segment="{{ $row->segment }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 font-title-md text-xs text-on-surface">{{ $row->initials }}</div>
                                    <span class="font-bold text-on-surface">{{ $row->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant text-xs">{{ $row->email }}</td>
                            <td class="py-3.5 px-4 text-on-surface text-xs max-w-[220px]">
                                @if ($row->items->isNotEmpty())
                                    <ul class="space-y-0.5">
                                        @foreach ($row->items as $item)
                                            <li class="truncate">{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-on-surface-variant">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center text-on-surface">{{ $row->jumlah_order }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format($row->total_belanja, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ optional(\Carbon\Carbon::parse($row->last_order))->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row->segment === 'leader')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent text-deep-onyx text-[10px] font-bold uppercase"><span class="material-symbols-outlined text-[12px]">military_tech</span>Top Leader</span>
                                @elseif ($row->segment === 'setia')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Setia</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Baru</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="showRalivaToast('Histori pesanan {{ $row->name }} dibuka (demo).', 'visibility')" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Lihat</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-on-surface-variant">Belum ada pelanggan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pelanggan yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            Data pelanggan hanya dapat dilihat (read-only) untuk menjaga privasi. Top Leader diperbarui otomatis bulanan.
        </p>
    </section>
</div>
@endsection
