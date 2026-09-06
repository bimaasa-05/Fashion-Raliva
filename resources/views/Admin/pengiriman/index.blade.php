@extends('layouts.admin')

@section('title', 'Pengiriman')

@section('header-title', 'Pengiriman')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Siapkan pengiriman, pilih kurir, dan masukkan nomor resi.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Siap Kirim</h2>
        <div class="space-y-gutter">
            @forelse ($siapDikirim as $pesanan)
                <div class="border border-muted-border rounded-lg p-5">
                    <form method="POST" action="{{ route('admin.pengiriman.resi', $pesanan->order_id) }}">
                        @csrf
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                            <div>
                                <p class="font-mono text-sm text-on-surface-variant">{{ $pesanan->nomor_order }} &#8226; {{ $pesanan->checkout?->user?->nama_lengkap ?? '-' }}</p>
                                <p class="font-title-md text-title-md text-on-surface mt-1">{{ \Illuminate\Support\Str::limit($pesanan->items->pluck('nama_produk_snapshot')->implode(', '), 60) }}</p>
                                <p class="font-body-md text-sm text-on-surface-variant mt-1">Tujuan: {{ $pesanan->store?->nama_toko }} &#8226; Ongkir: Rp {{ number_format((float) $pesanan->total_ongkir, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                                <select name="courier_id" required data-kurir-select
                                    class="raliva-select">
                                    <option value="">Pilih Kurir</option>
                                    @foreach ($couriers as $courier)
                                        <option value="{{ $courier->courier_id }}">{{ $courier->nama_kurir }}</option>
                                    @endforeach
                                </select>
                                <select name="shipping_service_id" data-layanan-select
                                    class="raliva-select">
                                    <option value="">Layanan (opsional)</option>
                                    @foreach ($couriers as $courier)
                                        @foreach ($courier->services as $service)
                                            <option value="{{ $service->shipping_service_id }}" data-courier-id="{{ $courier->courier_id }}">{{ $courier->nama_kurir }} {{ $service->nama_layanan }} (~{{ $service->estimasi_hari }} hari)</option>
                                        @endforeach
                                    @endforeach
                                </select>
                                <input required name="nomor_resi" minlength="4" maxlength="50"
                                    class="raliva-input w-full sm:w-44"
                                    type="text" placeholder="Masukkan No. Resi" />
                                <button type="submit" class="px-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-label-sm uppercase tracking-widest rounded hover:bg-black transition-colors btn-premium whitespace-nowrap">Simpan Resi</button>
                            </div>
                        </div>
                    </form>
                </div>
            @empty
                <p class="text-center text-on-surface-variant font-body-md text-sm py-8">Tidak ada pesanan menunggu pengiriman.</p>
            @endforelse
        </div>
    </section>

    <section class="space-y-gutter">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Dalam Pengiriman &amp; Riwayat</h2>
        <div class="overflow-x-auto bg-surface-container-lowest border border-muted-border rounded-lg card-premium">
            <table class="w-full min-w-[850px] premium-table">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                        <th class="p-4 text-left">Pesanan</th>
                        <th class="p-4 text-left">Kurir / Layanan</th>
                        <th class="p-4 text-left">Resi</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-md text-sm">
                    @forelse ($shipments as $shipment)
                        @php
                            $badgeMap = [
                                \App\Models\Shipment::STATUS_PENDING => ['label' => 'Menunggu Resi', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
                                \App\Models\Shipment::STATUS_DIPROSES => ['label' => 'Siap Kirim', 'class' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30'],
                                \App\Models\Shipment::STATUS_DIKIRIM => ['label' => 'Dikirim', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
                                \App\Models\Shipment::STATUS_DITERIMA => ['label' => 'Diterima', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
                                \App\Models\Shipment::STATUS_GAGAL => ['label' => 'Gagal', 'class' => 'bg-error/10 text-error border-error/20'],
                            ];
                            $badge = $badgeMap[$shipment->status];
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4">
                                <p class="font-mono text-on-surface">{{ $shipment->order?->nomor_order }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $shipment->order?->checkout?->user?->nama_lengkap ?? '-' }}</p>
                            </td>
                            <td class="p-4 text-on-surface">
                                {{ $shipment->courier?->nama_kurir ?? '-' }}
                                <span class="block text-xs text-on-surface-variant">{{ $shipment->shippingService?->nama_layanan ?? '-' }}</span>
                            </td>
                            <td class="p-4 font-mono text-on-surface">{{ $shipment->nomor_resi ?? '-' }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span></td>
                            <td class="p-4 text-right">
                                @if (in_array($shipment->status, [\App\Models\Shipment::STATUS_PENDING, \App\Models\Shipment::STATUS_DIPROSES], true) && $shipment->nomor_resi)
                                    <form method="POST" action="{{ route('admin.pengiriman.kirim', $shipment->shipment_id) }}" onsubmit="return confirm('Tandai pesanan {{ $shipment->order?->nomor_order }} sudah dikirim dengan resi {{ $shipment->nomor_resi }}?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Tandai Dikirim</button>
                                    </form>
                                @elseif ($shipment->status === \App\Models\Shipment::STATUS_DIKIRIM && ! $shipment->nomor_resi)
                                    <span class="text-error text-[10px] uppercase">Resi belum diisi</span>
                                @else
                                    <span class="text-on-surface-variant text-xs uppercase">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-on-surface-variant">Belum ada data pengiriman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-kurir-select]').forEach((kurirSelect) => {
        const layananSelect = kurirSelect.closest('form')?.querySelector('[data-layanan-select]');

        if (!layananSelect) return;

        const allOptions = Array.from(layananSelect.options);

        kurirSelect.addEventListener('change', () => {
            layananSelect.value = '';
            allOptions.forEach((option) => {
                if (!option.value) return;
                option.hidden = String(option.dataset.courierId) !== kurirSelect.value;
            });
        });
    });
</script>
@endpush
