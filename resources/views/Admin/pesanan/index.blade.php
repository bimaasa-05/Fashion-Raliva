@extends('layouts.admin')

@section('title', 'Data Pesanan')
@section('header-title', 'Data Pesanan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Lihat detail dan proses pesanan sesuai alur status.')

@php
    $badgeMap = [
        \App\Models\Order::STATUS_PENDING_PAYMENT => ['label' => 'Menunggu Bayar', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
        \App\Models\Order::STATUS_DIBAYAR => ['label' => 'Baru', 'class' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30'],
        \App\Models\Order::STATUS_DIPROSES => ['label' => 'Diproses', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        \App\Models\Order::STATUS_DIKIRIM => ['label' => 'Dikirim', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        \App\Models\Order::STATUS_SELESAI => ['label' => 'Selesai', 'class' => 'bg-secondary-container/20 text-secondary border-secondary/20'],
        \App\Models\Order::STATUS_DIBATALKAN => ['label' => 'Dibatalkan', 'class' => 'bg-error/10 text-error border-error/20'],
        \App\Models\Order::STATUS_REFUND => ['label' => 'Refund', 'class' => 'bg-error/10 text-error border-error/20'],
    ];
@endphp

@section('content')
@include('partials.flash-toast')

<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Pesanan Toko</h2>
        <button type="button" data-modal-open="modal-tambah-pesanan" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Pesanan
        </button>
    </div>

    <div class="mb-6 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
        </div>
        <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.pesanan') }}"
                class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $activeStatus === 'semua' ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">Semua</a>
            @foreach ($statuses as $key => $label)
                <a href="{{ route('admin.pesanan', ['status' => $key]) }}"
                    class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $activeStatus === $key ? 'bg-deep-onyx text-on-primary border border-deep-onyx' : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] premium-table">
            <thead>
                <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                    <th class="p-4 text-left">ID Pesanan</th>
                    <th class="p-4 text-left">Pelanggan</th>
                    <th class="p-4 text-left">Produk</th>
                    <th class="p-4 text-right">Total</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="font-body-md text-sm">
                @forelse ($orders as $pesanan)
                    @php
                        $badge = $badgeMap[$pesanan->status] ?? ['label' => ucfirst($pesanan->status), 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'];
                        $custName = $pesanan->checkout?->user?->nama_lengkap ?? '-';
                        $custId = $pesanan->checkout?->user_id;
                    @endphp
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors"
                        data-id="{{ $pesanan->order_id }}"
                        data-nomor="{{ $pesanan->nomor_order ?? ('#'.$pesanan->order_id) }}"
                        data-cust="{{ $custName }}"
                        data-custid="{{ $custId }}">
                        <td class="p-4 font-mono text-on-surface">{{ $pesanan->nomor_order ?? ('#'.$pesanan->order_id) }}</td>
                        <td class="p-4">
                            <p class="text-on-surface">{{ $custName }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $pesanan->store?->nama_toko }}</p>
                        </td>
                        <td class="p-4 text-on-surface" title="{{ $pesanan->items->pluck('nama_produk_snapshot')->implode(', ') }}">{{ $pesanan->items->count() }} produk &#8226; {{ \Illuminate\Support\Str::limit($pesanan->items->pluck('nama_produk_snapshot')->first(), 28) }}</td>
                        <td class="p-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format((float) ($pesanan->total_harga ?? 0), 0, ',', '.') }}</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            @if ($pesanan->status === \App\Models\Order::STATUS_DIBAYAR)
                                <form method="POST" action="{{ route('admin.pesanan.proses', $pesanan->order_id) }}" class="inline-block" onsubmit="return confirm('Proses pesanan {{ $pesanan->nomor_order ?? '#'.$pesanan->order_id }}?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Proses</button>
                                </form>
                            @endif
                            @if (in_array($pesanan->status, [\App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES], true))
                                <button type="button" onclick="openBatalkanPesanan(this.closest('tr'))" class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Batalkan</button>
                            @endif
                            <button type="button" data-modal-open="modal-detail-{{ $pesanan->order_id }}" class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-12 text-center text-on-surface-variant">Tidak ada pesanan pada filter ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<form method="POST" action="" id="batalkan-pesanan-form" onsubmit="closeBatalkanPesanan()">
    @csrf
    <div id="batalkanPesananModal" class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-lg border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">cancel</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Batalkan Pesanan</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Pesanan <span id="batalkan-nomor" class="font-mono font-bold text-on-surface">-</span> akan dibatalkan dan Customer dinotifikasi.</p>
                <textarea name="alasan" required minlength="10" maxlength="1000" rows="3" class="raliva-textarea" placeholder="Alasan pembatalan... (minimal 10 karakter)"></textarea>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeBatalkanPesanan()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Modal detail per pesanan --}}
@foreach ($orders as $pesanan)
<div id="modal-detail-{{ $pesanan->order_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Pesanan</h3>
                <p class="text-on-surface-variant font-mono text-xs uppercase tracking-wider mt-1">{{ $pesanan->nomor_order ?? ('#'.$pesanan->order_id) }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 space-y-4 font-body-md text-sm">
            <div class="flex justify-between gap-4 pb-3 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Pelanggan</dt><dd class="text-on-surface text-right">{{ $pesanan->checkout?->user?->nama_lengkap ?? '-' }}</dd></div>
            <div class="flex justify-between gap-4 pb-3 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Toko</dt><dd class="text-on-surface text-right">{{ $pesanan->store?->nama_toko ?? '-' }}</dd></div>
            <div>
                <p class="text-[10px] uppercase text-on-surface-variant mb-2">Item Pesanan</p>
                <ul class="space-y-2">
                    @foreach ($pesanan->items as $it)
                        <li class="flex justify-between gap-3 bg-surface-container-low rounded-lg p-3">
                            <span class="text-on-surface">{{ $it->nama_produk_snapshot }}</span>
                            <span class="text-on-surface-variant shrink-0">{{ $it->quantity }} × Rp {{ number_format((float) ($it->harga_snapshot ?? 0), 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="flex justify-between gap-4 pt-3 border-t border-muted-border"><dt class="text-on-surface-variant shrink-0">Total</dt><dd class="text-gold-accent font-bold text-right">Rp {{ number_format((float) ($pesanan->total_harga ?? 0), 0, ',', '.') }}</dd></div>
            <div class="flex justify-between gap-4"><dt class="text-on-surface-variant shrink-0">Status</dt><dd class="text-on-surface text-right">{{ $badgeMap[$pesanan->status]['label'] ?? ucfirst($pesanan->status) }}</dd></div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end gap-3">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
            <form method="POST" action="{{ route('admin.pesanan.store') }}">
                @csrf
                <input type="hidden" name="order_id" value="{{ $pesanan->order_id }}" />
                <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Buat Ulang untuk Customer</button>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Modal Tambah Pesanan (pilih customer lalu buat ulang dari pesanan terakhir) --}}
<div id="modal-tambah-pesanan" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.pesanan.store') }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
        @csrf
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Pesanan</h3>
                <p class="text-on-surface-variant text-sm mt-1">Pilih customer, lalu buat ulang pesanan terakhirnya.</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="raliva-label" for="tp-cust">Customer</label>
                <select id="tp-cust" name="user_id" class="raliva-select" required>
                    <option value="">— Pilih Customer —</option>
                    @foreach (\App\Models\User::where('role_id', 6)->orderByDesc('created_at')->limit(50)->get() as $c)
                        <option value="{{ $c->user_id }}">{{ $c->nama_lengkap }} ({{ $c->email }})</option>
                    @endforeach
                </select>
            </div>
            <p class="text-xs text-on-surface-variant">Sistem akan menyalin item dari pesanan terakhir customer tersebut ke pesanan baru (status Menunggu Pembayaran).</p>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end gap-3">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
            <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Buat Pesanan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const batalkanPesananUrl = '{{ route('admin.pesanan.batalkan', ':id:') }}';

    function openBatalkanPesanan(row) {
        document.getElementById('batalkan-nomor').textContent = row.dataset.nomor;
        document.getElementById('batalkan-pesanan-form').action = batalkanPesananUrl.replace(':id:', row.dataset.id);
        document.querySelector('#batalkan-pesanan-form textarea').value = '';
        const modal = document.getElementById('batalkanPesananModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeBatalkanPesanan() {
        const modal = document.getElementById('batalkanPesananModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeBatalkanPesanan();
    });
</script>
@endpush
