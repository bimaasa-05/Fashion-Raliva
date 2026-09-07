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

<section data-reveal-group class="grid grid-cols-2 lg:grid-cols-4 gap-gutter mb-6">
    <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
        <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">shopping_bag</span>
        <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Total Pesanan</span>
        <span class="raliva-figure text-[26px] text-on-surface relative">{{ $orders->count() }}</span>
    </div>
    <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
        <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">payments</span>
        <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Menunggu / Baru</span>
        <span class="raliva-figure text-[26px] text-gold-accent relative">{{ $orders->whereIn('status', ['pending_payment','dibayar'])->count() }}</span>
    </div>
    <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
        <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Diproses / Dikirim</span>
        <span class="raliva-figure text-[26px] text-secondary relative">{{ $orders->whereIn('status', ['diproses','dikirim'])->count() }}</span>
    </div>
    <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-xl flex flex-col gap-1 relative overflow-hidden card-premium">
        <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">task_alt</span>
        <span class="text-on-surface-variant font-label-sm text-[10px] uppercase relative">Selesai</span>
        <span class="raliva-figure text-[26px] text-secondary relative">{{ $orders->where('status', 'selesai')->count() }}</span>
    </div>
</section>

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
                        <td class="p-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format((float) ($pesanan->grand_total ?? 0), 0, ',', '.') }}</td>
                        <td class="p-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span></td>
                        <td class="p-4 text-right whitespace-nowrap">
                            @if ($pesanan->status === \App\Models\Order::STATUS_DIBAYAR)
                                <button type="button" data-modal-open="modal-proses-{{ $pesanan->order_id }}" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Proses</button>
                            @endif
                            @if (in_array($pesanan->status, [\App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES], true))
                                <button type="button" data-modal-open="modal-batalkan-{{ $pesanan->order_id }}" class="px-3 py-1.5 ml-1 bg-error/10 border border-error/20 text-error font-label-sm text-[10px] uppercase rounded hover:bg-error/20 transition-colors">Batalkan</button>
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
            <div class="flex justify-between gap-4 pt-3 border-t border-muted-border"><dt class="text-on-surface-variant shrink-0">Total</dt><dd class="text-gold-accent font-bold text-right">Rp {{ number_format((float) ($pesanan->grand_total ?? 0), 0, ',', '.') }}</dd></div>
            @if($pesanan->checkout?->payment?->proofs && $pesanan->checkout->payment->proofs->isNotEmpty())
                <div class="pt-3 border-t border-muted-border">
                    <p class="text-[10px] uppercase text-on-surface-variant mb-2">Bukti Bayar</p>
                    @foreach($pesanan->checkout->payment->proofs as $proof)
                        <a href="{{ asset('storage/'.$proof->file_bukti) }}" target="_blank" class="flex items-center gap-3 p-3 bg-surface-container-low rounded-lg border border-muted-border hover:border-gold-accent transition-colors">
                            <span class="material-symbols-outlined text-gold-accent">receipt_long</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-on-surface truncate">{{ \Illuminate\Support\Str::afterLast($proof->file_bukti, '/') }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $proof->uploaded_at?->translatedFormat('d M Y H:i') ?? '-' }} • {{ $pesanan->checkout->payment->paymentMethod->nama_metode ?? 'Transfer' }} • Rp {{ number_format((float) ($pesanan->checkout->payment->jumlah ?? 0),0,',','.') }}</p>
                            </div>
                            <span class="material-symbols-outlined text-on-surface-variant">open_in_new</span>
                        </a>
                    @endforeach
                </div>
            @endif
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
@if ($pesanan->status === \App\Models\Order::STATUS_DIBAYAR)
<div id="modal-proses-{{ $pesanan->order_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-8 text-center">
        <div class="w-14 h-14 rounded-full bg-secondary-container/20 border border-secondary/25 flex items-center justify-center mx-auto mb-5">
            <span class="material-symbols-outlined text-secondary text-[28px]">task_alt</span>
        </div>
        <h3 class="font-title-md text-title-md text-on-surface mb-2">Proses Pesanan</h3>
        <p class="text-on-surface-variant text-sm mb-6">Pesanan <span class="font-mono font-bold text-on-surface">{{ $pesanan->nomor_order ?? ('#'.$pesanan->order_id) }}</span> akan diproses?</p>
        <div class="flex space-x-3">
            <button type="button" data-modal-close class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
            <form method="POST" action="{{ route('admin.pesanan.proses', $pesanan->order_id) }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full bg-deep-onyx text-on-primary font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-black transition-colors rounded-lg btn-premium">Konfirmasi</button>
            </form>
        </div>
    </div>
</div>
@endif
@if (in_array($pesanan->status, [\App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES], true))
<div id="modal-batalkan-{{ $pesanan->order_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.pesanan.batalkan', $pesanan->order_id) }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-8">
        @csrf
        <div class="w-14 h-14 rounded-full bg-error/10 border border-error/25 flex items-center justify-center mx-auto mb-5">
            <span class="material-symbols-outlined text-error text-[28px]">cancel</span>
        </div>
        <h3 class="font-title-md text-title-md text-on-surface mb-2 text-center">Batalkan Pesanan</h3>
        <p class="text-on-surface-variant text-sm text-center mb-4">Pesanan <span class="font-mono font-bold text-on-surface">{{ $pesanan->nomor_order ?? ('#'.$pesanan->order_id) }}</span> akan dibatalkan dan Customer dinotifikasi.</p>
        <textarea name="alasan" required minlength="10" maxlength="1000" rows="3" class="raliva-textarea" placeholder="Alasan pembatalan... (minimal 10 karakter)"></textarea>
        <div class="flex space-x-3 mt-4">
            <button type="button" data-modal-close class="flex-1 bg-transparent border border-outline text-on-surface font-label-sm text-label-sm py-3 uppercase tracking-widest hover:bg-surface-container-low transition-colors rounded-lg">Batal</button>
            <button type="submit" class="flex-1 bg-error text-on-error font-label-sm text-label-sm py-3 uppercase tracking-widest hover:opacity-90 transition-opacity rounded-lg btn-premium">Konfirmasi</button>
        </div>
    </form>
</div>
@endif
@endforeach

{{-- Modal Tambah Pesanan (mode Baru / Salin, tanpa JS) --}}
<style>
    #modal-tambah-pesanan #mode-salin-fields { display: none; }
    #modal-tambah-pesanan:has(input[name="mode"][value="salin"]:checked) #mode-salin-fields { display: block; }
    #modal-tambah-pesanan:has(input[name="mode"][value="salin"]:checked) #mode-baru-fields { display: none; }
</style>
<div id="modal-tambah-pesanan" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.pesanan.store') }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
        @csrf
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Pesanan</h3>
                <p class="text-on-surface-variant text-sm mt-1">Buat pesanan baru atau salin dari pesanan sebelumnya (status Menunggu Pembayaran).</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label class="raliva-label" for="tp-cust">Customer <span class="text-error">*</span></label>
                <select id="tp-cust" name="user_id" class="raliva-select" required>
                    <option value="">— Pilih Customer —</option>
                    @foreach (\App\Models\User::where('role_id', 6)->orderByDesc('created_at')->limit(50)->get() as $c)
                        <option value="{{ $c->user_id }}">{{ $c->nama_lengkap }} ({{ $c->email }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <span class="raliva-label">Mode Pesanan</span>
                <div class="grid grid-cols-2 gap-3 mt-2">
                    <label class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="mode" value="baru" checked /> Baru
                    </label>
                    <label class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="mode" value="salin" /> Salin Pesanan
                    </label>
                </div>
            </div>
            <div id="mode-baru-fields" class="space-y-4">
                <p class="text-xs font-medium text-gold-accent pt-2 border-t border-muted-border">Item Baru (isi minimal 1 baris)</p>
                @for ($i = 0; $i < 3; $i++)
                    <div class="grid grid-cols-[1fr_110px] gap-3">
                        <div>
                            <label class="raliva-label" for="tp-item-{{ $i }}">Produk {{ $i + 1 }}</label>
                            <select id="tp-item-{{ $i }}" name="items[{{ $i }}][product_variant_id]" class="raliva-select">
                                <option value="">— Pilih Varian —</option>
                                @foreach (($variants ?? collect()) as $v)
                                    <option value="{{ $v->product_variant_id }}">{{ $v->product?->nama_produk ?? 'Produk' }} — {{ trim(($v->ukuran ?? '').' '.($v->warna ?? '')) }} (stok {{ $v->warehouseStocks->sum('jumlah_stok') }}) — Rp {{ number_format((float) ($v->harga ?? 0), 0, ',', '.') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="raliva-label" for="tp-qty-{{ $i }}">Qty</label>
                            <input id="tp-qty-{{ $i }}" name="items[{{ $i }}][quantity]" type="number" min="1" max="100" value="{{ $i === 0 ? 1 : '' }}" placeholder="1" class="raliva-input" />
                        </div>
                    </div>
                @endfor
            </div>
            <div id="mode-salin-fields" class="space-y-4">
                <p class="text-xs font-medium text-gold-accent pt-2 border-t border-muted-border">Salin dari Pesanan</p>
                <div>
                    <label class="raliva-label" for="tp-order">Pesanan Sumber</label>
                    <select id="tp-order" name="order_id" class="raliva-select">
                        <option value="">— Pilih Pesanan —</option>
                        @foreach (($recentOrders ?? collect()) as $o)
                            <option value="{{ $o->order_id }}">{{ $o->nomor_order }} • {{ $o->checkout?->user?->nama_lengkap ?? '-' }} • {{ $o->items->count() }} item • Rp {{ number_format((float) $o->grand_total, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>
                <p class="text-xs text-on-surface-variant">Item disalin persis dari pesanan sumber milik customer yang sama.</p>
            </div>
            <p class="text-xs text-on-surface-variant">Subtotal, ongkir (Rp 0), dan grand total dihitung server. Status awal Menunggu Pembayaran.</p>
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
    const lockScroll = () => {
        const w = window.innerWidth - document.documentElement.clientWidth;
        if (w > 0) { document.body.style.paddingRight = w + 'px'; document.documentElement.style.paddingRight = w + 'px'; }
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    };
    const unlockScroll = () => {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        document.documentElement.style.overflow = '';
        document.documentElement.style.paddingRight = '';
    };
    // Patch all data-modal on this page to use lockScroll with padding (anti geser)
    document.querySelectorAll('[data-modal-open]').forEach(btn=>{
        btn.addEventListener('click', ()=> setTimeout(lockScroll, 0));
    });
    document.querySelectorAll('[data-modal-close]').forEach(el=>{
        el.addEventListener('click', ()=>{
            setTimeout(()=>{
                if (!document.querySelector('[data-modal]:not(.hidden)')) unlockScroll();
            }, 50);
        });
    });
    document.addEventListener('click', (e)=>{
        if (e.target.matches('[data-modal]')) setTimeout(()=>{
            if (!document.querySelector('[data-modal]:not(.hidden)')) unlockScroll();
        }, 50);
    });
</script>
@endpush
