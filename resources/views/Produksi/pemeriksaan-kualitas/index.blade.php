@extends('layouts.produksi')

@section('title', 'Pemeriksaan Kualitas')

@section('header-title', 'Pemeriksaan Kualitas')
@section('header-badge', $antrian->count().' Menunggu QC')
@section('header-subtitle', 'Catat hasil QC — unit layak masuk gudang produk jadi, unit gagal tercatat sebagai barang rusak dan diproduksi ulang.')

@section('content')
@php
    $layakRate = $stats['diperiksa'] > 0 ? round($stats['layak'] / $stats['diperiksa'] * 100) : 0;
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
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Diperiksa Bulan Ini</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ number_format($stats['diperiksa'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">unit diperiksa</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">fact_check</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Layak Jual</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ number_format($stats['layak'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-secondary">{{ $layakRate }}% lolos QC</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">verified</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-3 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Barang Rusak</span>
            <span class="raliva-figure text-[26px] text-error">{{ number_format($stats['gagal'], 0, ',', '.') }}</span>
            <div class="h-2 bg-surface-container-high rounded-full overflow-hidden">
                <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="{{ $stats['diperiksa'] > 0 ? round($stats['gagal'] / $stats['diperiksa'] * 100) : 0 }}"></div>
            </div>
            <span class="font-label-sm text-[11px] text-on-surface-variant">perlu produksi ulang</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">report</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Antrian QC</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $antrian->count() }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu pemeriksaan</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">pending</span>
        </div>
    </section>

    {{-- Tabel Riwayat QC --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Riwayat Pemeriksaan</h2>
            <form method="GET" action="{{ route('produksi.pemeriksaan-kualitas') }}" class="flex items-center gap-3 w-full lg:w-auto">
                <div class="relative flex-1 min-w-[180px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari kode produksi..." class="raliva-search !pl-10" />
                </div>
                <select name="status" class="raliva-select">
                    <option value="semua" @selected($status === 'semua')>Semua Status</option>
                    <option value="lulus" @selected($status === 'lulus')>Lulus</option>
                    <option value="sebagian" @selected($status === 'sebagian')>Sebagian</option>
                    <option value="gagal" @selected($status === 'gagal')>Gagal</option>
                </select>
                <button type="submit" class="px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors shrink-0">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produksi / Produk</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Diperiksa</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Layak</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Defect</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Catatan Defect</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pemeriksa</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($checks as $check)
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $check->productionOrder?->nomor_produksi ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">
                                    {{ $check->productionOrder?->items->pluck('productVariant.product.nama_produk')->unique()->implode(', ') }}
                                </p>
                            </td>
                            <td class="py-3.5 px-4 text-center text-on-surface">{{ number_format($check->jumlah_lulus + $check->jumlah_gagal, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-secondary">{{ number_format($check->jumlah_lulus, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center font-bold {{ $check->jumlah_gagal > 0 ? 'text-error' : 'text-on-surface-variant' }}">{{ number_format($check->jumlah_gagal, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant max-w-[240px]">{{ $check->catatan ?: '—' }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $check->checker?->nama_lengkap ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($check->status === 'lulus')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Lulus</span>
                                @elseif ($check->status === 'gagal')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/20">Gagal</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Sebagian</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
                                    </div>
                                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pemeriksaan yang cocok.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($checks->hasPages())
            <div class="mt-6 flex justify-center">{{ $checks->links() }}</div>
        @endif
    </section>
</div>

{{-- Modal Catat QC --}}
<div id="modal-catat-qc" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-10 md:mt-12 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border z-10">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Catat Hasil QC</h3>
                <p class="text-on-surface-variant font-body-md text-xs mt-1">Layak masuk gudang, gagal tercatat sebagai barang rusak & diproduksi ulang.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="{{ route('produksi.pemeriksaan-kualitas.store') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Produksi (Menunggu QC)</label>
                <select name="production_order_id" class="raliva-select" required>
                    @forelse ($antrian as $order)
                        <option value="{{ $order->production_order_id }}">
                            {{ $order->nomor_produksi }} • {{ $order->items->pluck('productVariant.product.nama_produk')->unique()->implode(', ') }} • {{ $order->items->sum('jumlah_diminta') }} unit • sisa {{ $order->sisa_target }}
                        </option>
                    @empty
                        <option value="" disabled>Tidak ada produksi dalam antrian QC</option>
                    @endforelse
                </select>
            </div>
            <div class="grid grid-cols-2 gap-gutter">
                <div>
                    <label for="qc-layak" class="block raliva-label mb-2">Unit Layak</label>
                    <input id="qc-layak" name="jumlah_lulus" type="number" min="0" value="" placeholder="0" required class="raliva-input" />
                </div>
                <div>
                    <label for="qc-gagal" class="block raliva-label mb-2">Unit Gagal</label>
                    <input id="qc-gagal" name="jumlah_gagal" type="number" min="0" value="" placeholder="0" required class="raliva-input" />
                </div>
            </div>
            <div>
                <label for="qc-catatan" class="block raliva-label mb-2">Catatan Defect <span class="text-on-surface-variant/60">(opsional)</span></label>
                <textarea id="qc-catatan" name="catatan" rows="3" placeholder="cth. Jahitan kancing kurang kuat pada 2 unit..." class="raliva-textarea"></textarea>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" data-modal-close class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" {{ $antrian->isEmpty() ? 'disabled' : '' }} class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">fact_check</span>Simpan Hasil QC
                </button>
            </div>
        </form>
    </div>
</div>

@if ($antrian->isNotEmpty())
    <div class="fixed bottom-24 md:bottom-8 right-4 md:right-8 z-[60]">
        <button type="button" data-modal-open="modal-catat-qc" class="flex items-center gap-2 px-5 py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded-full shadow-xl btn-premium">
            <span class="material-symbols-outlined text-[18px]">add_task</span>Catat QC
        </button>
    </div>
@endif
@endsection