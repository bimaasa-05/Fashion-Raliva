@extends('layouts.gudang')

@section('title', __('Kekurangan Produksi'))

@section('header-title', __('Kekurangan Produksi'))
@section('header-badge', $orders->total() . ' ' . __('Pesanan'))
@section('header-subtitle', __('Kekurangan hasil produksi — siapkan manual dari stok gudang, lalu tandai selesai.'))

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <section data-reveal class="bg-surface-container-lowest p-5 border border-gold-accent/30 rounded-xl flex items-center gap-4 relative overflow-hidden card-premium">
        <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">inventory</span>
        <div class="relative">
            <span class="text-on-surface-variant font-label-sm text-[10px] uppercase">{{ __('Total Kekurangan') }}</span>
            <p class="raliva-figure text-[26px] text-gold-accent">{{ $totalKekurangan }} pcs</p>
            <p class="text-xs text-on-surface-variant">{{ __('Potong stok gudang utama dulu, lalu tandai selesai.') }}</p>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 md:p-6 card-premium">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading mb-4">{{ __('Daftar Kekurangan') }}</h2>

        {{-- Desktop --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full min-w-[760px] premium-table font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">{{ __('No. Pesanan') }}</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">{{ __('Produk') }}</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">{{ __('Kekurangan') }}</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">{{ __('Tanggal QC') }}</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">{{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        <tr class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $o->nomor_order }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->store?->nama_toko ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                @foreach ($o->items as $item)
                                    <p class="text-on-surface">{{ $item->nama_produk_snapshot }}
                                        <span class="text-on-surface-variant">× {{ $item->quantity }}</span></p>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-error/10 text-error text-[11px] font-bold uppercase border border-error/30">{{ $o->kekurangan_gudang }} pcs</span>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $o->tanggal_qc?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-right">
                                <form method="POST" action="{{ route('gudang.kekurangan.siapkan', $o->order_id) }}" onsubmit="return confirm('{{ sprintf(__('Tandai kekurangan pesanan %s sebagai sudah disiapkan dari gudang?'), $o->nomor_order) }}');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-deep-onyx text-on-primary text-[10px] font-bold uppercase rounded hover:opacity-90 transition-opacity btn-premium">{{ __('Tandai Sudah Disiapkan') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-on-surface-variant">{{ __('Tidak ada kekurangan produksi. Semua pesanan lengkap.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile --}}
        <div class="md:hidden grid grid-cols-1 gap-gutter">
            @forelse ($orders as $o)
                <article class="bg-surface-container-low border border-muted-border rounded-xl p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-mono font-bold text-on-surface">{{ $o->nomor_order }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->store?->nama_toko ?? '-' }}</p>
                            <p class="text-xs text-on-surface-variant">{{ $o->tanggal_qc?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full bg-error/10 text-error text-[11px] font-bold uppercase border border-error/30">{{ $o->kekurangan_gudang }} pcs</span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-muted-border space-y-1">
                        @foreach ($o->items as $item)
                            <p class="text-sm text-on-surface">{{ $item->nama_produk_snapshot }}
                                <span class="text-on-surface-variant">× {{ $item->quantity }}</span></p>
                        @endforeach
                    </div>
                    <form method="POST" action="{{ route('gudang.kekurangan.siapkan', $o->order_id) }}" class="mt-3 pt-3 border-t border-muted-border" onsubmit="return confirm('{{ sprintf(__('Tandai kekurangan pesanan %s sebagai sudah disiapkan dari gudang?'), $o->nomor_order) }}');">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-deep-onyx text-on-primary text-[11px] font-bold uppercase rounded btn-premium">{{ __('Tandai Sudah Disiapkan') }}</button>
                    </form>
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-6 text-center">{{ __('Tidak ada kekurangan produksi. Semua pesanan lengkap.') }}</p>
            @endforelse
        </div>

        {{ $orders->links() }}
    </section>
</div>
@endsection
