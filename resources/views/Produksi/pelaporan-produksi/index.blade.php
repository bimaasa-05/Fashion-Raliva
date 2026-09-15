@extends('layouts.produksi')

@section('title', 'Pelaporan Produksi')

@section('header-title', 'Pelaporan Produksi')
@section('header-badge', $stats['selesai'].' Selesai')
@section('header-subtitle', 'Detail produksi: target vs hasil, bahan terpakai, hasil QC, dan barang rusak.')

@section('content')
@php
    $jelasRate = $stats['unit_diminta'] > 0 ? round($stats['unit_layak'] / $stats['unit_diminta'] * 100) : 0;
@endphp
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Unit Diminta</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ number_format($stats['unit_diminta'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">target produksi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">track_changes</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Layak Masuk Gudang</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ number_format($stats['unit_layak'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-secondary">{{ $jelasRate }}% target</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">verified</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Barang Rusak</span>
            <span class="raliva-figure text-[26px] text-error">{{ number_format($stats['unit_gagal'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">gagal QC — detail per produksi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">report</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Order</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['selesai'] }}<span class="text-[14px] font-normal text-on-surface-variant"> / {{ $stats['selesai'] + $stats['dibatalkan'] }}</span></span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">{{ $stats['dibatalkan'] }} dibatalkan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">summarize</span>
        </div>
    </section>

    {{-- Tabel Pelaporan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="inline-flex bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1">
                @foreach ([
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                    'semua' => 'Semua',
                ] as $key => $label)
                    <a href="{{ route('produksi.pelaporan-produksi', array_merge(request()->except(['status', 'page']), ['status' => $key])) }}"
                       class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $status === $key ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">{{ $label }}</a>
                @endforeach
            </div>
            <form method="GET" action="{{ route('produksi.pelaporan-produksi') }}" class="flex items-center gap-3 w-full lg:w-auto">
                <input type="hidden" name="status" value="{{ $status }}" />
                <div class="relative flex-1 min-w-[200px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari kode / produk..." class="raliva-search !pl-10" />
                </div>
                <button type="submit" class="px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk / Target</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Layak</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Rusak</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Tercapai</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Selesai</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="border-b border-muted-border">
                            <td class="py-3.5 px-4 font-bold text-on-surface whitespace-nowrap">{{ $order->nomor_produksi }}</td>
                            <td class="py-3.5 px-4">
                                @foreach ($order->items as $item)
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-on-surface font-bold">{{ $item->productVariant?->product?->nama_produk ?? '-' }}</span>
                                        <span class="text-xs text-on-surface-variant">{{ $item->jumlah_diminta }} unit</span>
                                    </div>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-secondary">{{ number_format($order->layak_total, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center font-bold {{ $order->gagal_total > 0 ? 'text-error' : 'text-on-surface-variant' }}">{{ number_format($order->gagal_total, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-label-sm text-[11px] font-bold {{ $order->diminta_total > 0 && $order->layak_total >= $order->diminta_total ? 'text-secondary' : 'text-gold-accent' }}">
                                    {{ $order->diminta_total > 0 ? round($order->layak_total / $order->diminta_total * 100) : 0 }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $order->selesai_pada?->format('d M Y') ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" data-detail-row="{{ $order->production_order_id }}" class="inline-flex items-center gap-1 text-xs font-semibold text-gold-accent hover:underline">
                                    Detail<span class="material-symbols-outlined text-[16px]">expand_more</span>
                                </button>
                            </td>
                        </tr>
                        <tr id="detail-{{ $order->production_order_id }}" class="hidden">
                            <td colspan="7" class="px-4 pb-6 pt-2">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                    {{-- Bahan terpakai --}}
                                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                                        <h4 class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-3">Bahan Terpakai</h4>
                                        @if ($order->materials->isEmpty())
                                            <p class="text-xs text-on-surface-variant/60">Tidak ada bahan tercatat.</p>
                                        @else
                                            <ul class="space-y-2">
                                                @foreach ($order->materials as $mat)
                                                    <li class="flex items-center justify-between gap-3 text-sm">
                                                        <span class="text-on-surface">{{ $mat->material?->nama_bahan ?? '-' }}</span>
                                                        <span class="text-on-surface-variant whitespace-nowrap">{{ number_format($mat->jumlah_pakai, 0, ',', '.') }} {{ $mat->material?->satuan }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    {{-- Hasil QC --}}
                                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                                        <h4 class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-3">Hasil QC</h4>
                                        @if ($order->qualityChecks->isEmpty())
                                            <p class="text-xs text-on-surface-variant/60">Belum ada pemeriksaan.</p>
                                        @else
                                            <ul class="space-y-2">
                                                @foreach ($order->qualityChecks as $qc)
                                                    <li class="flex items-center justify-between gap-3 text-sm">
                                                        <span class="text-on-surface-variant">{{ $qc->diperiksa_pada?->format('d M H:i') }} — {{ $qc->checker?->nama_lengkap ?? '—' }}</span>
                                                        <span class="whitespace-nowrap text-xs font-bold">
                                                            <span class="text-secondary">{{ $qc->jumlah_lulus }} layak</span>
                                                            @if ($qc->jumlah_gagal > 0)
                                                                <span class="text-error"> / {{ $qc->jumlah_gagal }} gagal</span>
                                                            @endif
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if ($order->results->isNotEmpty())
                                            <div class="mt-4 pt-3 border-t border-muted-border space-y-2">
                                                @foreach ($order->results as $res)
                                                    <p class="text-xs text-on-surface-variant flex items-center justify-between">
                                                        <span>Produksi {{ $res->jumlah_diproduksi }} unit</span>
                                                        @if ($res->jumlah_gagal > 0)
                                                            <span class="text-error font-bold">{{ $res->jumlah_gagal }} rusak</span>
                                                        @endif
                                                    </p>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Barang rusak --}}
                                    <div class="bg-surface-container-low border border-muted-border rounded-lg p-4">
                                        <h4 class="font-label-sm text-[11px] uppercase tracking-wider text-on-surface-variant mb-3">Barang Rusak</h4>
                                        @if ($order->barang_rusak->isEmpty())
                                            <p class="text-xs text-secondary">Tidak ada defect — semua unit layak.</p>
                                        @else
                                            <ul class="space-y-2">
                                                @foreach ($order->barang_rusak as $qc)
                                                    <li class="text-sm">
                                                        <p class="flex items-center justify-between gap-2">
                                                            <span class="font-bold text-error">{{ $qc->jumlah_gagal }} unit</span>
                                                            <span class="text-on-surface-variant text-xs">{{ $qc->diperiksa_pada?->format('d M H:i') }}</span>
                                                        </p>
                                                        <p class="text-xs text-on-surface-variant/80 mt-1">{{ $qc->catatan ?: 'Tanpa catatan defect.' }}</p>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
                                    </div>
                                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada laporan produksi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="mt-6 flex justify-center">{{ $orders->links() }}</div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-detail-row]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const row = document.getElementById('detail-' + btn.getAttribute('data-detail-row'));
            const expanded = row.classList.contains('hidden');
            row.classList.toggle('hidden', !expanded);
            btn.innerHTML = expanded
                ? 'Tutup<span class="material-symbols-outlined text-[16px]">expand_less</span>'
                : 'Detail<span class="material-symbols-outlined text-[16px]">expand_more</span>';
        });
    });
</script>
@endpush