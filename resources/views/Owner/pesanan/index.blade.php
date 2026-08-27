@extends('layouts.owner')

@section('title', 'Data Pesanan')

@section('header-title', 'Data Pesanan')
@section('header-badge', '18 Baru')
@section('header-subtitle', 'Pantau pesanan toko dan perkembangan statusnya.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-24 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-16 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    {{-- Ringkasan Status --}}
    <section data-reveal-group class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-gutter">
        @foreach ([['Semua', 248, 'on-surface'], ['Baru', 18, 'gold-accent'], ['Diproses', 32, 'secondary'], ['Dikirim', 45, 'on-surface'], ['Selesai', 148, 'secondary'], ['Dibatalkan', 5, 'error']] as $stat)
            <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-1 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider">{{ $stat[0] }}</span>
                <span class="raliva-figure text-2xl text-{{ $stat[2] }}">{{ $stat[1] }}</span>
            </div>
        @endforeach
    </section>

    {{-- Tabel Pesanan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex items-center gap-3 flex-wrap mb-6">
            <div class="relative flex-1 min-w-[220px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari kode pesanan atau customer..." data-table-search class="raliva-search" />
            </div>
            <select data-table-filter="status" class="raliva-select">
                <option value="">Semua Status</option>
                <option value="baru">Baru</option>
                <option value="diproses">Diproses</option>
                <option value="dikirim">Dikirim</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[920px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Customer</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Item</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Total</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pembayaran</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusPill = [
                            'baru' => 'bg-gold-accent/10 text-gold-accent border-gold-accent/30',
                            'diproses' => 'bg-secondary-container/20 text-secondary border-secondary/20',
                            'dikirim' => 'bg-surface-container-high text-on-surface-variant border-outline-variant',
                            'selesai' => 'bg-deep-onyx text-on-primary border-transparent',
                            'dibatalkan' => 'bg-error/10 text-error border-error/20',
                        ];
                    @endphp
                    @foreach ([
                        ['kode' => '#RLV-2093', 'tgl' => '22 Agu, 14:32', 'customer' => 'Sarah Jenkins', 'item' => '3 produk', 'total' => 'Rp 1.240.000', 'bayar' => 'Transfer Bank', 'status' => 'Baru', 'key' => 'baru'],
                        ['kode' => '#RLV-2092', 'tgl' => '22 Agu, 13:05', 'customer' => 'Dimas Anggara', 'item' => '1 produk', 'total' => 'Rp 689.000', 'bayar' => 'Raliva Pay', 'status' => 'Diproses', 'key' => 'diproses'],
                        ['kode' => '#RLV-2091', 'tgl' => '21 Agu, 19:48', 'customer' => 'Aulia Rahma', 'item' => '5 produk', 'total' => 'Rp 2.150.000', 'bayar' => 'Kartu Kredit', 'status' => 'Dikirim', 'key' => 'dikirim'],
                        ['kode' => '#RLV-2090', 'tgl' => '21 Agu, 11:20', 'customer' => 'Kevin Sanjaya', 'item' => '2 produk', 'total' => 'Rp 459.000', 'bayar' => 'QRIS', 'status' => 'Diproses', 'key' => 'diproses'],
                        ['kode' => '#RLV-2089', 'tgl' => '20 Agu, 16:02', 'customer' => 'Nadia Putri', 'item' => '4 produk', 'total' => 'Rp 1.890.000', 'bayar' => 'Transfer Bank', 'status' => 'Selesai', 'key' => 'selesai'],
                        ['kode' => '#RLV-2088', 'tgl' => '20 Agu, 09:41', 'customer' => 'Raka Aditya', 'item' => '1 produk', 'total' => 'Rp 320.000', 'bayar' => 'COD', 'status' => 'Dibatalkan', 'key' => 'dibatalkan'],
                        ['kode' => '#RLV-2087', 'tgl' => '19 Agu, 20:15', 'customer' => 'Bella Safira', 'item' => '6 produk', 'total' => 'Rp 3.420.000', 'bayar' => 'Raliva Pay', 'status' => 'Dikirim', 'key' => 'dikirim'],
                    ] as $o)
                        <tr data-table-row data-status="{{ $o['key'] }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $o['kode'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $o['tgl'] }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface">{{ $o['customer'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $o['item'] }}</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">{{ $o['total'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $o['bayar'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full {{ $statusPill[$o['key']] }} text-[10px] font-bold uppercase">{{ $o['status'] }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" data-drawer-open="drawer-detail-pesanan" onclick="window.currentOrder = '{{ $o['kode'] }}'" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Detail</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pesanan yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>
    </section>
</div>

{{-- Drawer Detail Pesanan --}}
<div id="drawer-overlay" class="fixed inset-0 bg-black/50 z-[70] hidden opacity-0 transition-opacity duration-300"></div>
<div id="drawer-detail-pesanan" data-drawer-panel class="fixed inset-y-0 right-0 z-[80] w-full max-w-lg bg-surface-container-lowest border-l border-muted-border shadow-xl translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <div class="flex items-start justify-between px-6 py-5 border-b border-muted-border shrink-0">
        <div>
            <p class="text-xs font-medium text-on-surface-variant">Detail Pesanan</p>
            <h3 class="font-title-md text-title-md text-on-surface mt-1" id="drawer-order-code">#RLV-2093</h3>
        </div>
        <button type="button" data-drawer-close class="text-on-surface-variant hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6 space-y-7">
        {{-- Timeline --}}
        <section>
            <p class="text-xs font-mediumr text-gold-accent mb-4">Perkembangan Pesanan</p>
            <ol class="space-y-0">
                @foreach ([['Pesanan Diterima', '22 Agu, 14:32', true], ['Pembayaran Terverifikasi', '22 Agu, 14:35', true], ['Dikemas oleh Gudang', '—', false], ['Dikirim', '—', false], ['Selesai', '—', false]] as $i => $step)
                    <li class="relative flex gap-4 pb-6 last:pb-0">
                        @if (! $loop->last)
                            <span class="absolute left-[13px] top-7 bottom-0 w-[2px] {{ $step[2] ? 'bg-gold-accent/50' : 'bg-muted-border' }}"></span>
                        @endif
                        <span class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 z-10 {{ $step[2] ? 'bg-deep-onyx text-on-primary ring-4 ring-surface-container-lowest' : 'border-2 border-outline-variant bg-surface-container-lowest text-transparent' }}">
                            <span class="material-symbols-outlined fill text-[14px]">{{ $step[2] ? 'check' : 'radio_button_unchecked' }}</span>
                        </span>
                        <div class="pt-0.5">
                            <p class="font-title-md text-sm {{ $step[2] ? 'text-on-surface' : 'text-on-surface-variant' }}">{{ $step[0] }}</p>
                            @if ($step[1] !== '—')<p class="text-xs text-on-surface-variant mt-0.5">{{ $step[1] }}</p>@endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- Item --}}
        <section>
            <p class="text-xs font-mediumr text-gold-accent mb-4">Produk Dipesan</p>
            <ul class="space-y-3">
                @foreach ([['Trench Coat Signature', 'M • Krem × 1', 'Rp 1.290.000'], ['Silk Scarf Monogram', 'One Size • Gold × 2', 'Rp 518.000']] as $item)
                    <li class="flex items-center gap-3 border border-muted-border rounded-lg p-3 bg-surface-container-low">
                        <div class="w-11 aspect-[4/5] rounded-md bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 overflow-hidden">
                            <span class="material-symbols-outlined text-[20px] text-on-surface-variant">checkroom</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-title-md text-sm text-on-surface truncate">{{ $item[0] }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $item[1] }}</p>
                        </div>
                        <span class="font-bold text-sm text-on-surface whitespace-nowrap">{{ $item[2] }}</span>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Alamat --}}
        <section>
            <p class="text-xs font-mediumr text-gold-accent mb-3">Alamat Pengiriman</p>
            <div class="border border-muted-border rounded-lg p-4 bg-surface-container-low font-body-md text-sm">
                <p class="font-bold text-on-surface">Sarah Jenkins — +62 812-9911-2233</p>
                <p class="text-on-surface-variant mt-1">Jl. Senopati No. 88 Apt. Cendana Lantai 3, Kebayoran Baru, Jakarta Selatan, 12190</p>
                <p class="text-xs text-on-surface-variant mt-2 flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-gold-accent">local_shipping</span>JNE Reguler — JNE0239845122</p>
            </div>
        </section>

        {{-- Pembayaran --}}
        <section>
            <p class="text-xs font-mediumr text-gold-accent mb-3">Ringkasan Pembayaran</p>
            <dl class="space-y-2.5 font-body-md text-sm border border-muted-border rounded-lg p-4 bg-surface-container-low">
                <div class="flex justify-between"><dt class="text-on-surface-variant">Subtotal Produk</dt><dd class="text-on-surface">Rp 1.808.000</dd></div>
                <div class="flex justify-between"><dt class="text-on-surface-variant">Diskon Promo GAJIAN25</dt><dd class="text-secondary">− Rp 568.000</dd></div>
                <div class="flex justify-between"><dt class="text-on-surface-variant">Ongkos Kirim</dt><dd class="text-on-surface">Gratis</dd></div>
                <div class="flex justify-between pt-2.5 border-t border-muted-border"><dt class="font-bold text-on-surface">Total Bayar</dt><dd class="font-bold text-gold-accent text-base">Rp 1.240.000</dd></div>
            </dl>
        </section>
    </div>
    <div class="shrink-0 border-t border-muted-border p-4 flex flex-col-reverse sm:flex-row gap-gutter">
        <button type="button" onclick="showRalivaToast('Invoice sedang disiapkan (demo).', 'download')" class="flex-1 py-3 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[16px]">download</span>Unduh Invoice
        </button>
        <button type="button" onclick="showRalivaToast('Pesanan diteruskan ke Admin untuk diproses (demo).', 'forward_to_inbox')" class="flex-1 py-3 bg-deep-onyx text-on-primary rounded-lg text-sm font-semibold btn-premium">Teruskan ke Admin</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-drawer-open="drawer-detail-pesanan"]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const code = window.currentOrder || '#RLV-2093';
            const el = document.getElementById('drawer-order-code');
            if (el) el.textContent = code;
        });
    });
</script>
@endpush
