@extends('layouts.produksi')

@section('title', 'Riwayat Produksi')

@section('header-title', 'Riwayat Produksi')
@section('header-badge', $stats['selesai'] . ' Selesai')
@section('header-subtitle', 'Pesanan yang sudah melewati tahap produksi: siap kirim, dikirim, hingga selesai.')

@section('content')
@include('partials.flash-toast')

<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter">
        @for ($i = 0; $i < 4; $i++)
            <div class="h-28 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-[420px] bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Siap Kirim</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['siap_kirim'] }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">lolos QC + packing</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Dikirim</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $stats['dikirim'] }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">dalam perjalanan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Selesai</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $stats['selesai'] }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">diterima customer</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">check_circle</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Unit Berhasil</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ number_format($stats['unit_berhasil'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">total hasil produksi</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">verified</span>
        </div>
    </section>

    {{-- Filter + Cari --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="inline-flex bg-surface-container-low border border-muted-border rounded-lg p-1 gap-1 flex-wrap">
                @foreach ([
                    'semua' => 'Semua',
                    'siap_kirim' => 'Siap Kirim',
                    'dikirim' => 'Dikirim',
                    'selesai' => 'Selesai',
                ] as $key => $label)
                    <a href="{{ route('produksi.riwayat-produksi', array_merge(request()->except(['status', 'page']), ['status' => $key])) }}"
                       class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $status === $key ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">{{ $label }}</a>
                @endforeach
            </div>
            <form method="GET" action="{{ route('produksi.riwayat-produksi') }}" class="flex items-center gap-3 w-full lg:w-auto">
                <input type="hidden" name="status" value="{{ $status }}" />
                <div class="relative flex-1 min-w-[200px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari nomor pesanan / nama pelanggan..." class="raliva-search !pl-10" />
                </div>
                <button type="submit" class="px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0">Cari</button>
            </form>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">No. Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pelanggan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk &amp; Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Hasil Produksi</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal QC</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        @php
                            $qc = $o->qualityChecks->first();
                            $berhasil = $qc?->jumlah_lulus ?? $o->jumlah_berhasil ?? 0;
                            $gagal = $qc?->jumlah_gagal ?? $o->jumlah_gagal ?? 0;
                            $statusBadge = match ($o->status) {
                                'siap_kirim' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                                'dikirim' => 'bg-sky-500/10 text-sky-600 border-sky-500/30',
                                'selesai' => 'bg-success/10 text-success border-success/20',
                                default => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                            };
                            $statusLabel = match ($o->status) {
                                'siap_kirim' => 'Siap Kirim',
                                'dikirim' => 'Dikirim',
                                'selesai' => 'Selesai',
                                default => ucfirst($o->status),
                            };
                        @endphp
                        <tr class="border-b border-muted-border last:border-0 align-top hover:bg-surface-container-low transition-colors">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $o->nomor_order }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->created_at?->translatedFormat('d M Y') ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="text-on-surface">{{ $o->checkout?->nama_penerima ?? $o->checkout?->user?->nama_lengkap ?? '-' }}</p>
                                @if ($o->checkout?->nomor_telepon)
                                    <p class="text-xs text-on-surface-variant">{{ $o->checkout->nomor_telepon }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @foreach ($o->items as $item)
                                    <p class="text-on-surface">{{ $item->nama_produk_snapshot }} <span class="text-on-surface-variant">× {{ $item->quantity }}</span></p>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <p class="text-secondary font-bold">{{ number_format($berhasil, 0, ',', '.') }} berhasil</p>
                                <p class="text-error">{{ number_format($gagal, 0, ',', '.') }} gagal</p>
                                @if (($o->kekurangan_gudang ?? 0) > 0)
                                    <p class="text-gold-accent text-xs">+{{ $o->kekurangan_gudang }} dari Gudang</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $o->tanggal_qc?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border whitespace-nowrap {{ $statusBadge }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openDetailProduksi('{{ $o->order_id }}')" title="Detail produksi" class="inline-flex items-center justify-center px-2.5 py-2 border border-muted-border text-on-surface-variant rounded hover:border-gold-accent hover:text-gold-accent transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">timeline</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[28px] text-on-surface-variant">history</span>
                                    </div>
                                    <p class="text-on-surface-variant font-body-md text-sm">
                                        @if ($cari !== '')
                                            Tidak ada hasil untuk pencarian "{{ $cari }}".
                                        @elseif ($status !== 'semua')
                                            Tidak ada pesanan dengan status "{{ $status }}".
                                        @else
                                            Belum ada riwayat produksi.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="mt-6 flex justify-center">{{ $orders->withQueryString()->links() }}</div>
        @endif
    </section>
</div>

{{-- Modal Detail Produksi (timeline) per order --}}
@foreach ($orders as $o)
    @include('partials.modal-produksi-detail', ['o' => $o])
@endforeach
@endsection