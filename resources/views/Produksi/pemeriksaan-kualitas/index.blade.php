@extends('layouts.produksi')

@section('title', 'Pemeriksaan Kualitas')

@section('header-title', 'Pemeriksaan Kualitas')
@section('header-badge', $stats['menunggu_qc'] . ' Menunggu')
@section('header-subtitle', 'Periksa hasil produksi, input jumlah lulus/gagal, dan packing.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    {{-- Stats --}}
    <section class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu QC</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['menunggu_qc'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">fact_check</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Siap Kirim</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $stats['siap_kirim'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
    </section>

    {{-- Tabel --}}
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="overflow-x-auto">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">No. Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk &amp; Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Hasil Produksi</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
<<<<<<< HEAD
                    @forelse ($orders as $o)
                        <tr class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $o->nomor_order }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->created_at?->translatedFormat('d M Y') ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                @foreach ($o->items as $item)
                                    <p class="text-on-surface">{{ $item->nama_produk_snapshot }} <span class="text-on-surface-variant">× {{ $item->quantity }}</span></p>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <p class="text-green-600 font-bold">{{ $o->jumlah_berhasil ?? 0 }} berhasil</p>
                                <p class="text-error">{{ $o->jumlah_gagal ?? 0 }} gagal</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Menunggu QC</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openModalQC('{{ $o->order_id }}')" class="px-3 py-1.5 bg-deep-onyx text-on-primary text-[10px] font-bold uppercase rounded hover:opacity-90 transition-opacity">QC + Packing</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-on-surface-variant">Tidak ada pesanan menunggu QC.</td></tr>
=======
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
                                        <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
                                    </div>
                                    <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pemeriksaan yang cocok.</p>
<<<<<<< HEAD
        {{ $orders->withQueryString()->links() }}
    </section>
</div>

{{-- Modal QC + Packing per order (di luar table) --}}
@foreach ($orders as $o)
<div id="modal-qc-{{ $o->order_id }}" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeModalQC('{{ $o->order_id }}')"></div>
    <form method="POST" action="{{ route('produksi.pemeriksaan-kualitas.store', $o) }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
        @csrf
        <div class="sticky top-0 bg-surface-container-lowest border-b border-muted-border px-6 py-4 flex justify-between items-center">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface">QC + Packing</h3>
                <p class="text-xs text-on-surface-variant">{{ $o->nomor_order }}</p>
=======

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
>>>>>>> 805af2ec7afd202e60685487b80cc6e85225bde2
            </div>
            <button type="button" onclick="closeModalQC('{{ $o->order_id }}')" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
        </div>
<<<<<<< HEAD
        <div class="p-6 space-y-4">
            <div class="bg-surface-container-low rounded-lg p-3 space-y-1">
                <p class="text-xs text-on-surface-variant uppercase tracking-wider">Hasil Produksi</p>
                <p class="text-sm"><span class="text-green-600 font-bold">{{ $o->jumlah_berhasil ?? 0 }} berhasil</span> • <span class="text-error">{{ $o->jumlah_gagal ?? 0 }} gagal</span></p>
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Jumlah Lulus QC *</label>
                <input type="number" name="jumlah_lulus" required min="0" value="{{ $o->jumlah_berhasil ?? 0 }}" class="raliva-input w-full" />
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Jumlah Gagal QC</label>
                <input type="number" name="jumlah_gagal" min="0" value="{{ $o->jumlah_gagal ?? 0 }}" class="raliva-input w-full" />
=======
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
>>>>>>> 805af2ec7afd202e60685487b80cc6e85225bde2
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Catatan (opsional)</label>
                <textarea name="catatan" rows="2" class="raliva-textarea" placeholder="Catatan QC..."></textarea>
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex gap-3">
            <button type="button" onclick="closeModalQC('{{ $o->order_id }}')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface">Batal</button>
            <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Selesai QC + Packing</button>
        </div>
    </form>
</div>
<<<<<<< HEAD
@endforeach

@push('scripts')
<script>
    function openModalQC(orderId) {
        const modal = document.getElementById('modal-qc-' + orderId);
        if (modal) modal.classList.remove('hidden');
    }
    function closeModalQC(orderId) {
        const modal = document.getElementById('modal-qc-' + orderId);
        if (modal) modal.classList.add('hidden');
    }
</script>
@endpush
@endsection
=======

@if ($antrian->isNotEmpty())
    <div class="fixed bottom-24 md:bottom-8 right-4 md:right-8 z-[60]">
        <button type="button" data-modal-open="modal-catat-qc" class="flex items-center gap-2 px-5 py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded-full shadow-xl btn-premium">
            <span class="material-symbols-outlined text-[18px]">add_task</span>Catat QC
        </button>
    </div>
@endif
@endsection
>>>>>>> 805af2ec7afd202e60685487b80cc6e85225bde2
