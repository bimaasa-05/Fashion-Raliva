@extends('layouts.produksi')

@section('title', 'Pemeriksaan Kualitas')

@section('header-title', 'Pemeriksaan Kualitas')
@section('header-badge', ($stats['menunggu_qc'] ?? 0) . ' Menunggu')
@section('header-subtitle', 'Periksa hasil produksi, input jumlah lulus/gagal, dan packing.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    {{-- Stats --}}
    <section class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu QC</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['menunggu_qc'] ?? 0 }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">fact_check</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Siap Kirim</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $stats['siap_kirim'] ?? 0 }}</span>
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
                    @forelse ($orders as $o)
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
                            <td class="py-3.5 px-4 text-center">
                                <p class="text-green-600 font-bold">{{ $o->jumlah_berhasil ?? 0 }} berhasil</p>
                                <p class="text-error">{{ $o->jumlah_gagal ?? 0 }} gagal</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-bold uppercase border border-amber-500/30">Menunggu QC</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openDetailProduksi('{{ $o->order_id }}')" title="Detail produksi" class="inline-flex items-center justify-center px-2.5 py-2 border border-muted-border text-on-surface-variant rounded hover:border-gold-accent hover:text-gold-accent transition-colors mr-1 align-top">
                                    <span class="material-symbols-outlined text-[16px]">timeline</span>
                                </button>
                                <button type="button" onclick="openModalQC('{{ $o->order_id }}')" class="px-3 py-1.5 bg-deep-onyx text-on-primary text-[10px] font-bold uppercase rounded hover:opacity-90 transition-opacity">QC + Packing</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-on-surface-variant">Tidak ada pesanan menunggu QC.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
            </div>
            <button type="button" onclick="closeModalQC('{{ $o->order_id }}')" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
        </div>
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
@endforeach

{{-- Modal Detail Produksi (timeline) per order --}}
@foreach ($orders as $o)
    @include('partials.modal-produksi-detail', ['o' => $o])
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
