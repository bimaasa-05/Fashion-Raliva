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
@php
    /* ===== DUMMY DATA (sementara) â€” ganti sumber DB hingga backend siap ===== */
    $statuses = [
        'pending_payment' => 'Menunggu Bayar',
        'dibayar' => 'Baru',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
        'refund' => 'Refund',
    ];
    $activeStatus = 'semua';
    $dummyOrders = [
        ['nomor_order' => '#RLV-2093', 'status' => 'dibayar', 'grand_total' => 1250000, 'customer' => 'Maya Rossi', 'toko' => 'Raliva Atelier Jakarta', 'items' => ['Trench Coat Signature', 'Silk Scarf Monogram']],
        ['nomor_order' => '#RLV-2088', 'status' => 'diproses', 'grand_total' => 870000, 'customer' => 'Dewi Lestari', 'toko' => 'Raliva Atelier Jakarta', 'items' => ['Knit Sweater']],
        ['nomor_order' => '#RLV-2081', 'status' => 'dikirim', 'grand_total' => 2150000, 'customer' => 'Sarah Jenkins', 'toko' => 'Raliva Outlet Senayan', 'items' => ['Evening Gown Custom', 'Clutch Bag']],
        ['nomor_order' => '#RLV-2079', 'status' => 'selesai', 'grand_total' => 540000, 'customer' => 'Andi Pratama', 'toko' => 'Raliva Outlet Senayan', 'items' => ['Linen Shirt']],
        ['nomor_order' => '#RLV-2075', 'status' => 'pending_payment', 'grand_total' => 1500000, 'customer' => 'Rina Maharani', 'toko' => 'Raliva Atelier Jakarta', 'items' => ['Blazer Wool Premium']],
        ['nomor_order' => '#RLV-2070', 'status' => 'refund', 'grand_total' => 980000, 'customer' => 'Putra Wijaya', 'toko' => 'Raliva Outlet Senayan', 'items' => ['Satin Dress']],
        ['nomor_order' => '#RLV-2068', 'status' => 'dibatalkan', 'grand_total' => 430000, 'customer' => 'Sarah Jenkins', 'toko' => 'Raliva Atelier Jakarta', 'items' => ['Cotton Tee']],
    ];
    $orders = collect(array_map(function ($d) {
        return (object) [
            'order_id' => rand(1000, 9999),
            'nomor_order' => $d['nomor_order'],
            'status' => $d['status'],
            'grand_total' => $d['grand_total'],
            'checkout' => (object) ['user' => (object) ['nama_lengkap' => $d['customer']]],
            'store' => (object) ['nama_toko' => $d['toko']],
            'items' => collect(array_map(fn ($n) => (object) ['nama_produk_snapshot' => $n], $d['items'])),
        ];
    }, $dummyOrders));
@endphp
@include('partials.flash-toast')

<section data-table-scope class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Pesanan Toko</h2>
        <span class="text-on-surface-variant font-label-sm text-[11px] uppercase tracking-wider">Scope: {{ \Illuminate\Support\Str::limit(\App\Models\Store::whereIn('store_id', \App\Support\AdminContext::assignedStoreIds())->pluck('nama_toko')->implode(', '), 60) }}</span>
    </div>

    <div class="mb-6 bg-surface-container-low border border-muted-border rounded-lg p-4 flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="flex items-center gap-2 shrink-0">
            <span class="material-symbols-outlined text-[18px] text-gold-accent">tune</span>
            <span class="font-label-sm text-[10px] uppercase tracking-widest text-on-surface-variant">Filter Status</span>
        </div>
        <div class="hidden lg:block w-px h-6 bg-muted-border"></div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.pesanan') }}"
                class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $activeStatus === 'semua'
                    ? 'bg-deep-onyx text-on-primary border border-deep-onyx'
                    : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">Semua</a>
            @foreach ($statuses as $key => $label)
                <a href="{{ route('admin.pesanan', ['status' => $key]) }}"
                    class="px-4 py-2 rounded-lg font-label-sm text-[11px] uppercase tracking-wider transition-all duration-200 {{ $activeStatus === $key
                        ? 'bg-deep-onyx text-on-primary border border-deep-onyx'
                        : 'border border-muted-border text-on-surface-variant hover:bg-surface-container-high' }}">{{ $label }}</a>
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
                    @endphp
                    <tr class="border-b border-muted-border hover:bg-surface-container-low transition-colors"
                        data-id="{{ $pesanan->order_id }}"
                        data-nomor="{{ $pesanan->nomor_order }}">
                        <td class="p-4 font-mono text-on-surface">{{ $pesanan->nomor_order }}</td>
                        <td class="p-4">
                            <p class="text-on-surface">{{ $pesanan->checkout?->user?->nama_lengkap ?? '-' }}</p>
                            <p class="text-on-surface-variant text-xs">{{ $pesanan->store?->nama_toko }}</p>
                        </td>
                        <td class="p-4 text-on-surface" title="{{ $pesanan->items->pluck('nama_produk_snapshot')->implode(', ') }}">{{ $pesanan->items->count() }} produk &#8226; {{ \Illuminate\Support\Str::limit($pesanan->items->pluck('nama_produk_snapshot')->first(), 28) }}</td>
                        <td class="p-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format((float) $pesanan->grand_total, 0, ',', '.') }}</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            @if ($pesanan->status === \App\Models\Order::STATUS_DIBAYAR)
                                <form method="POST" action="{{ route('admin.pesanan.proses', $pesanan->order_id) }}" class="inline-block" onsubmit="return confirm('Proses pesanan {{ $pesanan->nomor_order }}?');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Proses</button>
                                </form>
                            @endif
                            @if (in_array($pesanan->status, [\App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES], true))
                                <button type="button" onclick="openBatalkanPesanan(this.closest('tr'))"
                                    class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Batalkan</button>
                            @endif
                            <button type="button" data-detail-open="detail-pesanan"
                                data-d-nomor="{{ $pesanan->nomor_order }}"
                                data-d-pelanggan="{{ $pesanan->checkout?->user?->nama_lengkap ?? '-' }}"
                                data-d-produk="{{ $pesanan->items->pluck('nama_produk_snapshot')->implode(', ') }}"
                                data-d-total="Rp {{ number_format((float) $pesanan->grand_total, 0, ',', '.') }}"
                                data-d-status="{{ $badge['label'] }}"
                                class="px-3 py-1.5 ml-1 border border-muted-border text-on-surface font-label-sm text-[10px] uppercase rounded hover:bg-surface-container-low transition-colors">Detail</button>
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
    <div class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm" id="batalkanPesananModal" onclick="if (event.target === this) closeBatalkanPesanan()">
        <div class="bg-surface-container-lowest w-full max-w-md rounded-lg border border-muted-border shadow-2xl overflow-hidden">
            <div class="p-8">
                <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
                    <span class="material-symbols-outlined text-error text-[28px]">cancel</span>
                </div>
                <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Batalkan Pesanan</h3>
                <p class="text-on-surface-variant text-sm text-center mb-4">Pesanan <span id="batalkan-nomor" class="font-mono font-bold text-on-surface">-</span> akan dibatalkan dan Customer dinotifikasi.</p>
                <textarea name="alasan" required minlength="10" maxlength="1000" rows="3"
                    class="raliva-textarea"
                    placeholder="Alasan pembatalan... (minimal 10 karakter)"></textarea>
                <div class="flex space-x-3">
                    <button type="button" class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg" onclick="closeBatalkanPesanan()">Batal</button>
                    <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="detail-pesanan" data-modal class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto mt-16 md:mt-24 w-[calc(100%-2rem)] max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-6 max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Detail Pesanan</h3>
                <p class="text-on-surface-variant font-mono text-xs uppercase tracking-wider mt-1"><span data-slot="nomor"></span></p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <dl class="space-y-4 font-body-md text-sm">
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Pelanggan</dt><dd class="text-on-surface text-right"><span data-slot="pelanggan"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Produk</dt><dd class="text-on-surface text-right max-w-[240px]"><span data-slot="produk"></span></dd></div>
            <div class="flex justify-between gap-4 pb-4 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Total</dt><dd class="text-gold-accent font-bold text-right"><span data-slot="total"></span></dd></div>
            <div class="flex justify-between gap-4 items-start"><dt class="text-on-surface-variant shrink-0">Status</dt><dd class="text-on-surface text-right"><span data-slot="status"></span></dd></div>
        </dl>
        <button type="button" data-modal-close class="w-full mt-6 py-3 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Tutup</button>
    </div>
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
