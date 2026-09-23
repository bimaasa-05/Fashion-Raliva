@extends('layouts.admin')

@section('title', 'Pengiriman')

@section('header-title', 'Pengiriman')
@section('header-badge', 'Kelola')

@section('header-subtitle', 'Kelola pengiriman kurir dan pesanan offline yang siap diambil pelanggan.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <div class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Kelola Pengiriman &amp; Penyerahan</h2>
                <p class="font-body-md text-xs text-on-surface-variant mt-1">Input resi kurir untuk pesanan online atau konfirmasi serah terima barang untuk pesanan offline.</p>
            </div>
            <div class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 overflow-x-auto shrink-0">
                <button type="button" data-ship-tab="semua" class="ship-tab px-4 py-2 rounded-md text-xs font-medium transition-colors bg-deep-onyx text-on-primary whitespace-nowrap">Semua</button>
                <button type="button" data-ship-tab="online" class="ship-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Online (Kurir)</button>
                <button type="button" data-ship-tab="offline" class="ship-tab px-4 py-2 rounded-md text-xs font-medium transition-colors text-on-surface-variant hover:text-on-surface whitespace-nowrap">Offline (Ambil di Toko)</button>
            </div>
        </div>

        {{-- Antrian gabungan: satu list, input menyesuaikan tipe pesanan --}}
        @php
            $antrian = collect()
                ->merge($siapDiambil->map(fn ($o) => ['tipe' => 'offline', 'order' => $o]))
                ->merge($siapDikirim->map(fn ($o) => ['tipe' => 'online', 'order' => $o]))
                ->sortByDesc(fn ($x) => optional($x['order']->created_at)->timestamp ?? 0)
                ->values();
        @endphp
        <div class="space-y-4" data-ship-queue>
            <div class="flex items-center gap-2 pb-2 border-b border-muted-border">
                <span class="material-symbols-outlined text-gold-accent text-[20px]">pending_actions</span>
                <h3 class="font-title-md text-sm font-bold uppercase tracking-wider text-on-surface">Antrian Penyerahan</h3>
                <span class="px-2 py-0.5 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold">{{ $antrian->count() }} Paket</span>
                <span class="text-[11px] text-on-surface-variant">• Online = input resi kurir • Offline = konfirmasi diambil</span>
            </div>
            @forelse ($antrian as $item)
                @php $pesanan = $item['order']; @endphp
                <div data-ship-type="{{ $item['tipe'] }}" class="border border-muted-border rounded-lg p-5 bg-surface-container-low/50">
                    @if ($item['tipe'] === 'offline')
                    <form method="POST" action="{{ route('admin.pesanan.selesai', $pesanan->order_id) }}">
                        @csrf
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded bg-surface-container-high text-on-surface-variant font-mono text-[10px] font-bold">OFFLINE</span>
                                    <p class="font-mono text-sm text-on-surface-variant">{{ $pesanan->nomor_order }} &#8226; {{ $pesanan->checkout?->nama_penerima ?? $pesanan->checkout?->user?->nama_lengkap ?? '-' }}</p>
                                </div>
                                <p class="font-title-md text-title-md text-on-surface mt-1">{{ \Illuminate\Support\Str::limit($pesanan->items->pluck('nama_produk_snapshot')->implode(', '), 60) }}</p>
                                <p class="font-body-md text-sm text-on-surface-variant mt-1">
                                    {{ $pesanan->checkout?->user?->nama_lengkap ?? $pesanan->checkout?->nama_penerima ?? '-' }}
                                    {{ $pesanan->checkout?->nomor_telepon ? '• '.$pesanan->checkout->nomor_telepon : '' }}
                                    &#8226; {{ $pesanan->checkout?->payment?->payment_account_id === null ? 'Tunai' : 'Transfer' }}
                                    &#8226; Total: Rp {{ number_format((float) $pesanan->grand_total, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 shrink-0 items-end">
                                <input name="catatan" maxlength="500" placeholder="Catatan pengambilan (opsional)" class="raliva-input w-full sm:w-52 text-xs" type="text" />
                                <button type="submit" class="px-5 py-2.5 bg-secondary-container/20 text-secondary border border-secondary/20 font-label-sm text-xs uppercase tracking-widest rounded hover:bg-secondary-container/30 transition-colors btn-premium whitespace-nowrap" onclick="return confirm('Konfirmasi pesanan {{ $pesanan->nomor_order }} selesai & diambil customer?');">Selesai (Diambil)</button>
                            </div>
                        </div>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.pengiriman.resi', $pesanan->order_id) }}">
                        @csrf
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded bg-secondary-container/20 text-secondary font-mono text-[10px] font-bold">ONLINE</span>
                                    <p class="font-mono text-sm text-on-surface-variant">{{ $pesanan->nomor_order }} &#8226; {{ $pesanan->checkout?->nama_penerima ?? $pesanan->checkout?->user?->nama_lengkap ?? '-' }}</p>
                                </div>
                                <p class="font-title-md text-title-md text-on-surface mt-1">{{ \Illuminate\Support\Str::limit($pesanan->items->pluck('nama_produk_snapshot')->implode(', '), 60) }}</p>
                                <p class="font-body-md text-sm text-on-surface-variant mt-1">Penerima: {{ $pesanan->checkout?->nama_penerima ?? $pesanan->checkout?->user?->nama_lengkap ?? '-' }} {{ $pesanan->checkout?->nomor_telepon ? '• '.$pesanan->checkout->nomor_telepon : '' }} &#8226; Ongkir: Rp {{ number_format((float) $pesanan->total_ongkir, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2.5 shrink-0">
                                @php $kurirAktif = $kurirPerToko[$pesanan->store_id] ?? $couriers->pluck('courier_id')->all(); @endphp
                                <select name="courier_id" required data-kurir-select class="raliva-select text-xs">
                                    <option value="">Pilih Kurir</option>
                                    @foreach ($couriers->whereIn('courier_id', $kurirAktif) as $courier)
                                        <option value="{{ $courier->courier_id }}">{{ $courier->nama_kurir }}</option>
                                    @endforeach
                                </select>
                                <select name="shipping_service_id" data-layanan-select class="raliva-select text-xs">
                                    <option value="">Layanan (opsional)</option>
                                    @foreach ($couriers as $courier)
                                        @foreach ($courier->services as $service)
                                            <option value="{{ $service->shipping_service_id }}" data-courier-id="{{ $courier->courier_id }}">{{ $courier->nama_kurir }} {{ $service->nama_layanan }} (~{{ $service->estimasi_hari }} hari)</option>
                                        @endforeach
                                    @endforeach
                                </select>
                                <input required name="nomor_resi" minlength="4" maxlength="50" class="raliva-input w-full sm:w-44 text-xs" type="text" placeholder="Masukkan No. Resi" />
                                <input name="estimasi_tiba" type="date" min="{{ date('Y-m-d') }}" class="raliva-input w-full sm:w-40 text-xs" title="Estimasi tiba (opsional)" />
                                <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-xs uppercase tracking-widest rounded hover:bg-black transition-colors btn-premium whitespace-nowrap">Simpan Resi</button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            @empty
                <p class="text-center text-on-surface-variant font-body-md text-sm py-4">Tidak ada pesanan menunggu penyerahan.</p>
            @endforelse
            <p class="text-[11px] text-on-surface-variant" data-ship-empty-hint hidden>Tidak ada paket pada filter ini.</p>
        </div>
    </div>

    <section data-ship-type="online" class="space-y-gutter">
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
                            $badge = $badgeMap[$shipment->status] ?? ['label' => ucfirst($shipment->status), 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
                        @endphp
                        <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors">
                            <td class="p-4">
                                <p class="font-mono text-on-surface">{{ $shipment->order?->nomor_order }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $shipment->order?->checkout?->nama_penerima ?? $shipment->order?->checkout?->user?->nama_lengkap ?? '-' }}@if($shipment->order?->checkout?->nomor_telepon) • {{ $shipment->order->checkout->nomor_telepon }}@endif</p>
                            </td>
                            <td class="p-4 text-on-surface">
                                {{ $shipment->courier?->nama_kurir ?? '-' }}
                                <span class="block text-xs text-on-surface-variant">{{ $shipment->shippingService?->nama_layanan ?? '-' }}</span>
                            </td>
                            <td class="p-4 font-mono text-on-surface">{{ $shipment->nomor_resi ?? '-' }}</td>
                            <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span></td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-1.5 flex-wrap">
                                    @if (in_array($shipment->status, [\App\Models\Shipment::STATUS_PENDING, \App\Models\Shipment::STATUS_DIPROSES], true) && $shipment->order?->status === \App\Models\Order::STATUS_SIAP_KIRIM)
                                        <button type="button" data-modal-open="modal-edit-resi-{{ $shipment->shipment_id }}" class="px-3 py-1.5 border border-gold-accent/40 text-gold-accent font-label-sm text-[10px] uppercase rounded hover:bg-gold-accent/10 transition-colors">Edit Resi</button>
                                    @endif
                                    @if (in_array($shipment->status, [\App\Models\Shipment::STATUS_PENDING, \App\Models\Shipment::STATUS_DIPROSES], true) && $shipment->nomor_resi)
                                        <form method="POST" action="{{ route('admin.pengiriman.kirim', $shipment->shipment_id) }}" onsubmit="return confirm('Tandai pesanan {{ $shipment->order?->nomor_order }} sudah dikirim dengan resi {{ $shipment->nomor_resi }}?');">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Tandai Dikirim</button>
                                        </form>
                                    @elseif ($shipment->status === \App\Models\Shipment::STATUS_DIKIRIM && ! $shipment->nomor_resi)
                                        <span class="text-error text-[10px] uppercase">Resi belum diisi</span>
                                    @elseif (! in_array($shipment->status, [\App\Models\Shipment::STATUS_PENDING, \App\Models\Shipment::STATUS_DIPROSES], true))
                                        <span class="text-on-surface-variant text-xs uppercase">&mdash;</span>
                                    @endif
                                </div>
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

{{-- Modal Edit Resi per shipment --}}
@foreach ($shipments as $shipment)
    @if (in_array($shipment->status, [\App\Models\Shipment::STATUS_PENDING, \App\Models\Shipment::STATUS_DIPROSES], true) && $shipment->order?->status === \App\Models\Order::STATUS_SIAP_KIRIM)
        <div id="modal-edit-resi-{{ $shipment->shipment_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <form method="POST" action="{{ route('admin.pengiriman.resi', $shipment->order_id) }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-6 max-h-[85vh] overflow-y-auto">
                @csrf
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Edit Resi</h3>
                <p class="text-on-surface-variant font-mono text-xs mt-1">{{ $shipment->order?->nomor_order }}</p>
                <div class="space-y-4 mt-5">
                    <div>
                        <label class="block raliva-label mb-2">Kurir *</label>
                        @php $kurirAktifEdit = $kurirPerToko[$shipment->order?->store_id] ?? $couriers->pluck('courier_id')->all(); @endphp
                        <select name="courier_id" required class="raliva-select">
                            @foreach ($couriers->whereIn('courier_id', $kurirAktifEdit) as $courier)
                                <option value="{{ $courier->courier_id }}" @selected((int) $shipment->courier_id === (int) $courier->courier_id)>{{ $courier->nama_kurir }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Layanan (opsional)</label>
                        <select name="shipping_service_id" class="raliva-select">
                            <option value="">— Tanpa layanan khusus —</option>
                            @foreach ($couriers as $courier)
                                @foreach ($courier->services as $service)
                                    <option value="{{ $service->shipping_service_id }}" @selected((int) $shipment->shipping_service_id === (int) $service->shipping_service_id)>{{ $courier->nama_kurir }} {{ $service->nama_layanan }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Nomor Resi *</label>
                        <input name="nomor_resi" required minlength="4" maxlength="50" value="{{ $shipment->nomor_resi }}" class="raliva-input" type="text" />
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Estimasi Tiba (opsional)</label>
                        <input name="estimasi_tiba" type="date" min="{{ date('Y-m-d') }}" value="{{ $shipment->estimasi_tiba?->format('Y-m-d') }}" class="raliva-input" />
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Simpan Resi</button>
                </div>
            </form>
        </div>
    @endif
@endforeach
@endsection

@push('scripts')
<script>
    // Tab Filter (Semua / Online / Offline)
    document.querySelectorAll('[data-ship-tab]').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('[data-ship-tab]').forEach(t => {
                t.classList.remove('bg-deep-onyx', 'text-on-primary');
                t.classList.add('text-on-surface-variant', 'hover:text-on-surface');
            });
            tab.classList.add('bg-deep-onyx', 'text-on-primary');
            tab.classList.remove('text-on-surface-variant', 'hover:text-on-surface');

            const mode = tab.dataset.shipTab;
            document.querySelectorAll('[data-ship-type]').forEach(el => {
                if (mode === 'semua' || el.dataset.shipType === mode) {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
            const queue = document.querySelector('[data-ship-queue]');
            const hint = queue?.querySelector('[data-ship-empty-hint]');
            if (queue && hint) {
                const visible = queue.querySelectorAll('[data-ship-type]:not(.hidden)').length;
                const hasData = queue.querySelectorAll('[data-ship-type]').length > 0;
                hint.hidden = !(hasData && visible === 0);
            }
        });
    });

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
