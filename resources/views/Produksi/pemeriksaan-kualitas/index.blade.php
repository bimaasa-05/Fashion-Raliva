@extends('layouts.produksi')

@section('title', 'Pemeriksaan Kualitas')

@section('header-title', 'Pemeriksaan Kualitas')
@section('header-badge', ($stats['menunggu_qc'] ?? 0) . ' Menunggu')
@section('header-subtitle', 'Periksa hasil produksi, input jumlah lulus, dan packing.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    {{-- Stats --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter">
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

    {{-- Tab --}}
    <div class="flex flex-wrap gap-2 border-b border-muted-border">
        <a href="{{ route('produksi.pemeriksaan-kualitas', ['tab' => 'menunggu']) }}"
            class="px-4 py-2.5 font-label-sm text-[11px] uppercase tracking-wider border-b-2 -mb-px transition-all {{ $tab !== 'siap' ? 'border-gold-accent text-gold-accent' : 'border-transparent text-on-surface-variant hover:text-on-surface' }}">Menunggu
            <span
                class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold {{ $tab !== 'siap' ? 'bg-gold-accent/15 text-gold-accent' : 'bg-surface-container-high text-on-surface-variant' }}">{{ $stats['menunggu_qc'] ?? 0 }}</span></a>
        <a href="{{ route('produksi.pemeriksaan-kualitas', ['tab' => 'siap']) }}"
            class="px-4 py-2.5 font-label-sm text-[11px] uppercase tracking-wider border-b-2 -mb-px transition-all {{ $tab === 'siap' ? 'border-gold-accent text-gold-accent' : 'border-transparent text-on-surface-variant hover:text-on-surface' }}">Siap Untuk Dikirim <span
                class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold {{ $tab === 'siap' ? 'bg-gold-accent/15 text-gold-accent' : 'bg-surface-container-high text-on-surface-variant' }}">{{ $stats['siap_kirim'] ?? 0 }}</span></a>
    </div>

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
                                @if ($o->catatan)
                                    <p class="text-xs text-on-surface mt-1 italic">“{{ \Illuminate\Support\Str::limit($o->catatan, 80) }}”</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @foreach ($o->items as $item)
                                    <p class="text-on-surface">{{ $item->nama_produk_snapshot }} <span class="text-on-surface-variant">× {{ $item->quantity }}</span></p>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <p class="text-green-600 font-bold">{{ $o->jumlah_berhasil ?? 0 }} berhasil</p>
                                <p class="text-error">{{ $o->jumlah_gagal ?? 0 }} gagal</p>
                                @if ($tab === 'siap' && (int) ($o->kekurangan_gudang ?? 0) > 0)
                                    <p class="text-xs text-gold-accent font-bold mt-0.5">+{{ $o->kekurangan_gudang }} dari Gudang</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($tab === 'siap')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-500/10 text-green-600 text-[10px] font-bold uppercase border border-green-500/30">Siap Kirim</span>
                                @elseif ($o->qc_perlu_admin_pada)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase border border-error/30">Menunggu Admin</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-bold uppercase border border-amber-500/30">Menunggu QC</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openDetailProduksi('{{ $o->order_id }}')" title="Detail produksi" class="inline-flex items-center justify-center px-2.5 py-2 border border-muted-border text-on-surface-variant rounded hover:border-gold-accent hover:text-gold-accent transition-colors mr-1 align-top">
                                    <span class="material-symbols-outlined text-[16px]">timeline</span>
                                </button>
                                @if ($tab !== 'siap')
                                    <button type="button" onclick="openModalQC('{{ $o->order_id }}')" class="px-3 py-1.5 bg-deep-onyx text-on-primary text-[10px] font-bold uppercase rounded hover:opacity-90 transition-opacity">QC + Packing</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-on-surface-variant">{{ $tab === 'siap' ? 'Belum ada pesanan siap untuk dikirim.' : 'Tidak ada pesanan menunggu QC.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $orders->withQueryString()->links() }}
    </section>
</div>

{{-- Modal QC + Packing per order (hanya tab menunggu) --}}
@if ($tab !== 'siap')
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
                <p class="text-sm"><span class="text-green-600 font-bold">{{ $o->jumlah_berhasil ?? 0 }} berhasil</span> • <span class="text-error">{{ $o->jumlah_gagal ?? 0 }} gagal</span> • <span class="text-on-surface-variant">total {{ $o->items->sum('quantity') }} pcs</span></p>
                @if ($o->catatan)
                    <p class="text-xs text-on-surface mt-1 italic">Catatan customer: “{{ $o->catatan }}”</p>
                @endif
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Jumlah Lulus QC *</label>
                <input type="number" name="jumlah_lulus" required min="0" max="{{ $o->items->sum('quantity') }}" value="{{ $o->items->sum('quantity') }}" class="raliva-input w-full" />
                <p class="text-[11px] text-on-surface-variant mt-1">Gagal dihitung otomatis (total − lulus). Kekurangan lulus diambil dari Gudang.</p>
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Catatan (wajib bila Gagal)</label>
                <textarea name="catatan" rows="2" minlength="10" class="raliva-textarea" placeholder="Catatan QC... (minimal 10 karakter bila Gagal)"></textarea>
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex gap-3 flex-wrap">
            <button type="button" onclick="closeModalQC('{{ $o->order_id }}')" class="flex-1 min-w-[5rem] py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface">Batal</button>
            <button type="submit" formaction="{{ route('produksi.pemeriksaan-kualitas.gagal', $o) }}" class="flex-1 min-w-[7rem] py-2.5 bg-error/10 border border-error/30 text-error text-xs font-semibold rounded-lg">Gagal — Hubungi Admin</button>
            <button type="submit" class="flex-1 min-w-[7rem] py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Selesai QC + Packing</button>
        </div>
    </form>
</div>
@endforeach
@endif

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
