@extends('layouts.produksi')

@section('title', __('Produk Selesai'))

@section('header-title', __('Produk Selesai'))
@section('header-badge', $stats['siap_kirim'] . ' ' . __('Siap Kirim'))
@section('header-subtitle', __('Produk yang sudah lulus QC + packing, siap dikirim oleh Admin.'))

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    {{-- Stats --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Siap Dikirim') }}</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $stats['siap_kirim'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">{{ __('Sudah Dikirim') }}</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $stats['dikirim'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">check_circle</span>
        </div>
    </section>

    {{-- Tabel --}}
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="overflow-x-auto hidden md:block">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">No. Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk &amp; Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Lulus QC</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Gagal QC</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Catatan QC</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal QC</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        @php
                            $qc = $o->qualityChecks->first();
                        @endphp
                        <tr class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $o->nomor_order }}</p>
                                <p class="text-xs text-on-surface mt-0.5">{{ $o->checkout?->nama_penerima ?? $o->checkout?->user?->nama_lengkap ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->created_at?->translatedFormat('d M Y') ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                @foreach ($o->items as $item)
                                    <p class="text-on-surface">{{ $item->nama_produk_snapshot }} <span class="text-on-surface-variant">× {{ $item->quantity }}</span></p>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-green-600">{{ $o->hasil_qc_lulus ?? $qc?->jumlah_lulus ?? $o->jumlah_berhasil ?? 0 }}
                                @if (($o->kekurangan_gudang ?? 0) > 0)
                                    <span class="block text-[10px] font-normal text-gold-accent">+{{ $o->kekurangan_gudang }} dari Gudang</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center text-error">{{ $o->hasil_qc_gagal ?? $qc?->jumlah_gagal ?? $o->jumlah_gagal ?? 0 }}</td>
                            <td class="py-3.5 px-4 text-xs text-on-surface-variant" style="max-width: 220px">{{ \Illuminate\Support\Str::limit($qc?->catatan ?? $o->qc_perlu_admin_catatan ?? $o->qc_admin_catatan ?? '-', 80) }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant">{{ $o->tanggal_qc?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">{{ __('Siap Kirim') }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openDetailProduksi('{{ $o->order_id }}')" title="{{ __('Detail produksi') }}" class="inline-flex items-center justify-center px-2.5 py-2 border border-muted-border text-on-surface-variant rounded hover:border-gold-accent hover:text-gold-accent transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">timeline</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-12 text-center text-on-surface-variant">{{ __('Belum ada produk siap dikirim.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($orders as $o)
                @php
                    $qc = $o->qualityChecks->first();
                @endphp
                <article class="bg-surface-container-low border border-muted-border rounded-xl p-4 relative">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-bold text-on-surface">{{ $o->nomor_order }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->checkout?->nama_penerima ?? $o->checkout?->user?->nama_lengkap ?? '-' }}</p>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $o->created_at?->translatedFormat('d M Y') ?? '-' }}</p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30 shrink-0">{{ __('Siap Kirim') }}</span>
                    </div>
                    <div class="mt-2">
                        @foreach ($o->items as $item)
                            <p class="text-sm text-on-surface">{{ $item->nama_produk_snapshot }} <span class="text-on-surface-variant">× {{ $item->quantity }}</span></p>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-2 gap-3 mt-3 pt-3 border-t border-muted-border text-sm">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">{{ __('Lulus QC') }}</p>
                            <p class="font-bold text-green-600 mt-0.5">{{ $qc?->jumlah_lulus ?? $o->jumlah_berhasil ?? 0 }}@if (($o->kekurangan_gudang ?? 0) > 0) <span class="block text-[10px] font-normal text-gold-accent">+{{ $o->kekurangan_gudang }} dari Gudang</span>@endif</p>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">{{ __('Gagal QC') }}</p>
                            <p class="font-bold text-error mt-0.5">{{ $qc?->jumlah_gagal ?? $o->jumlah_gagal ?? 0 }}</p>
                        </div>
                        <div class="col-span-2 flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">{{ __('Tanggal QC') }}</p>
                                <p class="text-on-surface-variant text-xs mt-0.5">{{ $o->tanggal_qc?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                            </div>
                            <button type="button" onclick="openDetailProduksi('{{ $o->order_id }}')" class="inline-flex items-center justify-center px-3 py-2 border border-muted-border text-on-surface-variant rounded-lg hover:border-gold-accent hover:text-gold-accent transition-colors shrink-0">
                                <span class="material-symbols-outlined text-[16px]">timeline</span>
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-center text-on-surface-variant py-10">{{ __('Belum ada produk siap dikirim.') }}</p>
            @endforelse
        </div>
        {{ $orders->withQueryString()->links() }}
    </section>
</div>

{{-- Modal Detail Produksi (timeline) per order --}}
@foreach ($orders as $o)
    @include('partials.modal-produksi-detail', ['o' => $o])
@endforeach
@endsection
