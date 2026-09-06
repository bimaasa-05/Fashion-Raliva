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
    @if(! \App\Support\OwnerContext::currentStore())
        <div class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    {{-- Ringkasan Status — tambah icon watermark agar tidak polos --}}
    <section data-reveal-group class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-gutter">
        @foreach ([['Semua', $counts['semua'], 'on-surface', 'inventory_2'], ['Baru', $counts['baru'], 'gold-accent', 'shopping_cart'], ['Diproses', $counts['diproses'], 'secondary', 'precision_manufacturing'], ['Dikirim', $counts['dikirim'], 'on-surface', 'local_shipping'], ['Selesai', $counts['selesai'], 'secondary', 'task_alt'], ['Dibatalkan', $counts['dibatalkan'], 'error', 'cancel']] as $stat)
            <div data-reveal class="bg-surface-container-lowest p-5 md:p-6 border border-muted-border rounded-xl flex flex-col gap-1.5 relative overflow-hidden card-premium">
                <span class="material-symbols-outlined absolute -right-2 -bottom-3 text-[72px] text-gold-accent/15 fill pointer-events-none select-none fill" aria-hidden="true">{{ $stat[3] }}</span>
                <span class="text-on-surface-variant font-label-sm text-[10px] uppercase tracking-wider relative z-10">{{ $stat[0] }}</span>
                <span class="raliva-figure text-2xl md:text-[26px] text-{{ $stat[2] }} relative z-10">{{ $stat[1] }}</span>
            </div>
        @endforeach
    </section>

    {{-- Tabel Pesanan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Pesanan</h2>
                <p class="text-xs text-on-surface-variant mt-1">Pantau seluruh pesanan masuk, status, dan detail pembayarannya.</p>
            </div>
        </div>

        {{-- Toolbar: 1 baris rapi — search kiri, filter kanan --}}
        <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
            <div class="relative flex-1 min-w-[220px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari kode pesanan atau customer..." data-table-search class="raliva-search" />
            </div>
            <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                <select data-table-filter="status" class="raliva-select lg:w-44">
                    <option value="">Semua Status</option>
                    <option value="baru" @selected($status === 'baru')>Baru</option>
                    <option value="diproses" @selected($status === 'diproses')>Diproses</option>
                    <option value="dikirim" @selected($status === 'dikirim')>Dikirim</option>
                    <option value="selesai" @selected($status === 'selesai')>Selesai</option>
                    <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
                </select>
                <select data-table-filter="period" class="raliva-select lg:w-44">
                    <option value="">Semua Waktu</option>
                    <option value="today" @selected($period === 'today')>Hari Ini</option>
                    <option value="week" @selected($period === 'week')>Minggu Ini</option>
                    <option value="month" @selected($period === 'month')>Bulan Ini</option>
                </select>
                <button type="button" data-filter-reset class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</button>
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto min-h-[380px]">
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
                    @forelse ($orders as $o)
                        @php
                            $key = match($o->status) {
                                'selesai' => 'selesai',
                                'dibatalkan' => 'dibatalkan',
                                'dikirim' => 'dikirim',
                                'diproses' => 'diproses',
                                default => 'baru',
                            };
                            $customer = $o->checkout?->user;
                            $itemCount = $o->items?->count() ?? 0;
                        @endphp
                        <tr data-table-row data-status="{{ $key }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $o->nomor_order }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">#{{ $o->order_id }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->nomor_order }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface">{{ $customer?->nama_lengkap ?? 'Customer' }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $itemCount }} produk</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">{{ 'Rp ' . number_format($o->grand_total, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $o->checkout?->paymentMethod?->nama_metode ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full {{ $statusPill[$key] }} text-[10px] font-bold uppercase">{{ $o->status }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" data-modal-open="modal-order-{{ $o->order_id }}" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-on-surface-variant">Belum ada pesanan.</td></tr>
                    @endforelse
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

{{-- Modal Detail Pesanan (centered, dinamis per order) --}}
@foreach ($orders as $o)
<?php
    $oKey = match($o->status) {
        'selesai' => 'selesai',
        'dibatalkan' => 'dibatalkan',
        'dikirim' => 'dikirim',
        'diproses' => 'diproses',
        default => 'baru',
    };
    $oCustomer = $o->checkout?->user;
    $oPayment = $o->checkout?->payment;
?>
<div id="modal-order-{{ $o->order_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
    <div class="flex items-start justify-between px-6 py-5 border-b border-muted-border shrink-0">
        <div>
            <p class="text-xs font-medium text-on-surface-variant">Detail Pesanan</p>
            <h3 class="font-title-md text-title-md text-on-surface mt-1">{{ $o->nomor_order }}</h3>
        </div>
        <button type="button" data-drawer-close class="text-on-surface-variant hover:text-on-surface transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6 space-y-7">
        <section>
            <p class="text-xs font-medium text-gold-accent mb-4">Perkembangan Pesanan</p>
            <ol class="space-y-0">
                @php
                    $steps = [
                        ['Verifikasi Pembayaran', 'payments', in_array($o->status, ['dibayar','diproses','dikirim','selesai'])],
                        ['Pesanan Diterima', 'fact_check', $oKey !== 'baru' || $o->status === 'pending_payment'],
                        ['Diproses / Dikemas', 'inventory_2', in_array($o->status, ['diproses','dikirim','selesai'])],
                        ['Dikirim', 'local_shipping', in_array($o->status, ['dikirim','selesai'])],
                        ['Selesai', 'task_alt', $o->status === 'selesai'],
                    ];
                @endphp
                @foreach ($steps as $i => $step)
                    <li class="relative flex gap-4 pb-6 last:pb-0">
                        @if (! $loop->last)
                            <span class="absolute left-[13px] top-7 bottom-0 w-[2px] {{ $step[2] ? 'bg-gold-accent/50' : 'bg-muted-border' }}"></span>
                        @endif
                        <span class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 z-10 {{ $step[2] ? 'bg-gold-accent text-white ring-4 ring-surface-container-lowest shadow-sm' : 'border-2 border-outline-variant bg-surface-container-lowest text-transparent' }}">
                            <span class="material-symbols-outlined text-[16px] {{ $step[2] ? 'fill' : '' }}">{{ $step[2] ? $step[1] : 'radio_button_unchecked' }}</span>
                        </span>
                        <div class="pt-0.5">
                            <p class="font-title-md text-sm {{ $step[2] ? 'text-on-surface' : 'text-on-surface-variant' }}">{{ $step[0] }}</p>
                            @if ($i === 1 && $o->created_at)
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->created_at->translatedFormat('d M, H:i') }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        <section>
            <p class="text-xs font-medium text-gold-accent mb-4">Produk Dipesan</p>
            <ul class="space-y-3">
                @foreach ($o->items as $it)
                    <li class="flex items-center gap-3 border border-muted-border rounded-lg p-3 bg-surface-container-low">
                        <div class="w-11 aspect-[4/5] rounded-md bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 overflow-hidden">
                            <span class="material-symbols-outlined text-[20px] text-on-surface-variant">checkroom</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-title-md text-sm text-on-surface truncate">{{ $it->variant?->product?->nama_produk ?? '-' }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $it->variant?->ukuran ?? '-' }} • {{ $it->variant?->warna ?? '-' }} × {{ $it->quantity }}</p>
                        </div>
                        <span class="font-bold text-sm text-on-surface whitespace-nowrap">Rp {{ number_format($it->harga_satuan * $it->quantity, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </section>

        <section>
            <p class="text-xs font-medium text-gold-accent mb-3">Ringkasan Pembayaran</p>
            <dl class="space-y-2.5 font-body-md text-sm border border-muted-border rounded-lg p-4 bg-surface-container-low">
                <div class="flex justify-between"><dt class="text-on-surface-variant">Subtotal Produk</dt><dd class="text-on-surface">Rp {{ number_format($o->subtotal, 0, ',', '.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-on-surface-variant">Diskon</dt><dd class="text-secondary">− Rp {{ number_format($o->total_diskon, 0, ',', '.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-on-surface-variant">Ongkos Kirim</dt><dd class="text-on-surface">Rp {{ number_format($o->total_ongkir, 0, ',', '.') }}</dd></div>
                <div class="flex justify-between pt-2.5 border-t border-muted-border"><dt class="font-bold text-on-surface">Total Bayar</dt><dd class="font-bold text-gold-accent text-base">Rp {{ number_format($o->grand_total, 0, ',', '.') }}</dd></div>
            </dl>
            <p class="text-xs text-on-surface-variant mt-3 flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-gold-accent">payments</span>Metode: {{ $oPayment?->nama_metode ?? '—' }} • Customer: {{ $oCustomer?->nama_lengkap ?? '-' }}</p>
        </section>
    </div>
    <div class="shrink-0 border-t border-muted-border p-4 flex flex-col-reverse sm:flex-row gap-gutter">
        <button type="button" onclick="window.print()" class="flex-1 py-3 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[16px]">download</span>Unduh Invoice
        </button>
        <button type="button" data-modal-open="modal-forward-{{ $o->order_id }}" class="flex-1 py-3 bg-deep-onyx text-on-primary rounded-lg text-sm font-semibold btn-premium">Teruskan ke Admin</button>
    </div>
    </div>{{-- /inner --}}
</div>{{-- /modal root --}}
    <div id="modal-forward-{{ $o->order_id }}" data-modal class="fixed inset-0 z-[75] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
            <div class="flex items-center gap-3 mb-4">
                <span class="material-symbols-outlined text-gold-accent">forward_to_inbox</span>
                <h3 class="font-title-md text-title-md text-on-surface">Teruskan ke Admin?</h3>
            </div>
            <p class="text-on-surface-variant text-sm mb-6">Pesanan <span class="font-mono text-on-surface">{{ $o->nomor_order }}</span> akan diteruskan ke Admin Produksi untuk diproses. Status berubah menjadi <b>Diproses</b>.</p>
            <form method="POST" action="{{ route('owner.pesanan.forward', $o->order_id) }}" class="flex gap-3">
                @csrf
                <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Teruskan</button>
            </form>
        </div>
    </div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  if (!document.querySelector('[data-real]')) return;
  // Check if no store banner exists (means no store)
  const noStore = document.body.innerHTML.includes('Belum punya toko');
  if (!noStore) return;
  // Disable all primary action buttons except Ajukan Toko
  document.querySelectorAll('[data-modal-open], button[type="submit"], a[href*="pengajuan-toko"]:not([href*="ajukan"])').forEach(el=>{
    // Keep Ajukan Toko enabled
    if (el.textContent.includes('Ajukan Toko') || el.getAttribute('data-modal-open')?.includes('modal-tambah')) {
      // For tambah buttons, disable if no store
      el.setAttribute('disabled','');
      el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
      el.title = 'Ajukan toko dulu';
    }
  });
  // More generic: disable all buttons in data-real except those inside pengajuan
  document.querySelectorAll('[data-real] button, [data-real] a.btn-premium').forEach(el=>{
    if (el.closest('[data-modal]')) return;
    if (el.textContent.trim().includes('Ajukan')) return;
    el.setAttribute('disabled','');
    el.classList.add('opacity-60','cursor-not-allowed','pointer-events-none');
  });
});
</script>
@endpush

@endsection
