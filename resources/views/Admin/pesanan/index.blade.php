@extends('layouts.admin')

@section('title', 'Data Pesanan')
@section('header-title', 'Data Pesanan')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Lihat detail dan proses pesanan sesuai alur status.')

@php
    $badgeMap = [
        \App\Models\Order::STATUS_PENDING_PAYMENT => ['label' => 'Menunggu Bayar', 'class' => 'bg-surface-container-high text-on-surface-variant border-outline-variant'],
        \App\Models\Order::STATUS_MENUNGGU_PRODUKSI => ['label' => 'Menunggu Produksi', 'class' => 'bg-indigo-500/10 text-indigo-600 border-indigo-500/30'],
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
                        $custName = $pesanan->checkout?->nama_penerima ?? $pesanan->checkout?->user?->nama_lengkap ?? '-';
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
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            @if ($pesanan->checkout?->payment?->status === \App\Models\Payment::STATUS_DITOLAK)
                                <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase border bg-error/10 border-error/20 text-error">Bayar Ditolak</span>
                            @endif
                        </td>
                        <td class="p-4 text-right whitespace-nowrap">
                            @if ($pesanan->status === \App\Models\Order::STATUS_MENUNGGU_PRODUKSI)
                                <button type="button" data-modal-open="modal-proses-{{ $pesanan->order_id }}" class="px-3 py-1.5 bg-deep-onyx text-on-primary font-label-sm text-[10px] uppercase rounded hover:bg-black transition-colors btn-premium">Proses</button>
                            @endif
                            @if (in_array($pesanan->status, [\App\Models\Order::STATUS_MENUNGGU_PRODUKSI, \App\Models\Order::STATUS_DIPROSES], true))
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
            <div class="flex justify-between gap-4 pb-3 border-b border-muted-border"><dt class="text-on-surface-variant shrink-0">Pelanggan</dt><dd class="text-on-surface text-right">{{ $pesanan->checkout?->nama_penerima ?? $pesanan->checkout?->user?->nama_lengkap ?? '-' }}@if($pesanan->checkout?->nomor_telepon)<br><span class="text-xs text-on-surface-variant">{{ $pesanan->checkout->nomor_telepon }}</span>@endif</dd></div>
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
        </div>
    </div>
</div>
@if ($pesanan->status === \App\Models\Order::STATUS_MENUNGGU_PRODUKSI)
<div id="modal-proses-{{ $pesanan->order_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.pesanan.proses', $pesanan->order_id) }}" class="relative mx-auto w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
        @csrf
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Input Bahan Produksi</h3>
                <p class="text-on-surface-variant font-mono text-xs uppercase tracking-wider mt-1">{{ $pesanan->nomor_order ?? ('#'.$pesanan->order_id) }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0" aria-label="Tutup">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-xs text-on-surface-variant">Pilih bahan dari katalog atau ketik manual beserta jumlah &amp; satuan. Tambah baris sesuai kebutuhan.</p>
            <div id="bahan-container-{{ $pesanan->order_id }}" class="space-y-3"></div>
            <button type="button" onclick="addBahanRow('{{ $pesanan->order_id }}')" class="w-full py-2.5 border border-dashed border-outline-variant rounded-lg text-xs font-semibold text-on-surface-variant hover:border-gold-accent hover:text-gold-accent transition-colors flex items-center justify-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">add</span> Tambah Bahan
            </button>
            <div class="pt-3 border-t border-muted-border">
                <p class="text-xs font-medium text-gold-accent mb-2">Jadwal Produksi</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Tgl Mulai Produksi *</label>
                        <input type="datetime-local" name="tgl_mulai_produksi" required class="raliva-input w-full" />
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Tgl Berakhir Produksi *</label>
                        <input type="datetime-local" name="tgl_berakhir_produksi" required class="raliva-input w-full" />
                    </div>
                </div>
            </div>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex gap-3">
            <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
            <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Proses Pesanan</button>
        </div>
    </form>
</div>
@endif
@if (in_array($pesanan->status, [\App\Models\Order::STATUS_MENUNGGU_PRODUKSI, \App\Models\Order::STATUS_DIPROSES], true))
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

{{-- Modal Tambah Pesanan --}}
<div id="modal-tambah-pesanan" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <form method="POST" action="{{ route('admin.pesanan.store') }}" enctype="multipart/form-data" class="relative mx-auto w-[calc(100%-2rem)] max-w-xl bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
        @csrf
        <div class="sticky top-0 z-10 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Tambah Pesanan</h3>
                <p class="text-on-surface-variant text-sm mt-1">Buat pesanan Online (dari customer) atau Offline (walk-in).</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="p-6 space-y-5">
            {{-- Tipe pesanan: Online / Offline --}}
            <div>
                <span class="raliva-label">Tipe Pesanan</span>
                <div class="grid grid-cols-2 gap-3 mt-2">
                    <label class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="tipe_pesanan" value="online" checked onchange="toggleTipePesanan()" /> Online
                    </label>
                    <label class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                        <input type="radio" class="sr-only" name="tipe_pesanan" value="offline" onchange="toggleTipePesanan()" /> Offline
                    </label>
                </div>
            </div>

            {{-- Online: pilih customer --}}
            <div id="online-fields" class="space-y-4">
                <div>
                    <label class="raliva-label" for="tp-cust">Customer <span class="text-error">*</span></label>
                    <select id="tp-cust" name="user_id" class="raliva-select" required>
                        <option value="">— Pilih Customer —</option>
                        @foreach ($customers as $c)
                            <option value="{{ $c->user_id }}">{{ $c->nama_lengkap }} ({{ $c->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Offline: data penerima --}}
            <div id="offline-fields" class="space-y-4 hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="raliva-label" for="tp-nama">Nama Penerima <span class="text-error">*</span></label>
                        <input id="tp-nama" name="nama_penerima" type="text" class="raliva-input" placeholder="Nama lengkap penerima" />
                    </div>
                    <div>
                        <label class="raliva-label" for="tp-telp">Nomor Telepon <span class="text-error">*</span></label>
                        <input id="tp-telp" name="nomor_telepon" type="text" class="raliva-input" placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
                <div>
                    <label class="raliva-label" for="tp-email">Email Pelanggan</label>
                    <input id="tp-email" name="email_pelanggan" type="email" class="raliva-input" placeholder="email@contoh.com (opsional)" />
                </div>
                <div>
                    <label class="raliva-label" for="tp-alamat">Alamat <span class="text-error">*</span></label>
                    <textarea id="tp-alamat" name="alamat" rows="2" class="raliva-textarea" placeholder="Alamat lengkap penerima"></textarea>
                </div>

                {{-- Pembayaran offline --}}
                <div class="pt-3 border-t border-muted-border">
                    <span class="raliva-label">Metode Pembayaran</span>
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <label class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                            <input type="radio" class="sr-only" name="metode_bayar" value="tunai" checked onchange="toggleMetodeBayar()" /> Tunai
                        </label>
                        <label class="flex items-center justify-center px-3 py-3 border border-muted-border rounded-lg text-on-surface-variant font-label-sm text-[11px] uppercase cursor-pointer hover:bg-surface-container-low hover:border-gold-accent hover:text-gold-accent transition-all has-[:checked]:border-gold-accent has-[:checked]:bg-gold-accent/10 has-[:checked]:text-gold-accent">
                            <input type="radio" class="sr-only" name="metode_bayar" value="transfer" onchange="toggleMetodeBayar()" /> Transfer
                        </label>
                    </div>
                </div>
                <div id="transfer-fields" class="space-y-3 hidden">
                    <div>
                        <label class="raliva-label" for="tp-acc">Rekening Tujuan <span class="text-error">*</span></label>
                        <select id="tp-acc" name="payment_account_id" class="raliva-select">
                            <option value="">— Pilih Rekening —</option>
                            @foreach ($paymentAccounts as $acc)
                                <option value="{{ $acc->platform_bank_account_id }}">{{ $acc->nama }} • {{ $acc->nomor_rekening }} ({{ $acc->nama_pemilik }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="raliva-label" for="tp-bukti">Bukti Transfer <span class="text-error">*</span></label>
                        <input id="tp-bukti" name="bukti" type="file" accept="image/jpeg,image/png,image/jpg" class="raliva-input" />
                        <p class="text-xs text-on-surface-variant mt-1">Format jpg/png, maks 4MB.</p>
                    </div>
                </div>
            </div>

            {{-- Item produk --}}
            <div class="pt-3 border-t border-muted-border">
                <p class="text-xs font-medium text-gold-accent">Item Produk (minimal 1 baris)</p>
                <div id="item-container" class="space-y-3 mt-3"></div>
                <button type="button" onclick="addItemRow()" class="mt-2 w-full py-2.5 border border-dashed border-outline-variant rounded-lg text-xs font-semibold text-on-surface-variant hover:border-gold-accent hover:text-gold-accent transition-colors flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">add</span> Tambah Produk
                </button>
            </div>

            <div class="pt-3 border-t border-muted-border flex justify-between gap-4">
                <dt class="text-on-surface-variant shrink-0">Total</dt>
                <dd class="text-gold-accent font-bold text-right">Rp <span id="grand-total">0</span></dd>
            </div>
            <p class="text-xs text-on-surface-variant">Subtotal, ongkir (Rp 0), dan grand total dihitung ulang otomatis. Status awal Menunggu Pembayaran.</p>
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

@push('scripts')
<script>
    /* ====================================================================
       MODAL PROSES — Input Bahan Produksi (per pesanan)
       ==================================================================== */
    @php
        $bahanMasterJson = $bahanList->map(function ($b) {
            return [
                'bahan_id' => $b->bahan_id,
                'nama_bahan' => $b->nama_bahan,
                'satuan' => $b->satuan,
                'stok' => $b->stok,
            ];
        })->toJson();
    @endphp
    const bahanMaster = {!! $bahanMasterJson !!};

    function addBahanRow(orderId) {
        const container = document.getElementById('bahan-container-' + orderId);
        if (!container) return;
        const idx = container.querySelectorAll('[data-bahan-row]').length;
        const row = document.createElement('div');
        row.setAttribute('data-bahan-row', '');
        row.className = 'border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low space-y-2.5';
        const dlId = 'bahan-datalist-' + orderId + '-' + idx;
        row.innerHTML = `
            <div class="flex items-start justify-between gap-3">
                <input type="text" list="${dlId}" autocomplete="off" placeholder="Ketik / pilih bahan..." oninput="onBahanInput(this)" class="raliva-input flex-1 min-w-0" />
                <input type="hidden" name="bahan[${idx}][bahan_id]" value="" />
                <datalist id="${dlId}">
                    ${bahanMaster.map(b => `<option value="${b.bahan_id}" label="${b.nama_bahan} (stok ${b.stok} ${b.satuan})">${b.nama_bahan}</option>`).join('')}
                </datalist>
                <button type="button" onclick="removeBahanRow(this)" class="shrink-0 px-2.5 py-2.5 rounded-lg border border-error/20 text-error hover:bg-error/10 transition-colors" title="Hapus baris">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
            <div class="grid grid-cols-[1fr_110px] gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Nama Bahan</label>
                    <input type="text" name="bahan[${idx}][nama_bahan]" required class="raliva-input w-full" placeholder="Nama bahan produksi" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Jumlah</label>
                    <input type="number" name="bahan[${idx}][jumlah]" required min="0.01" step="0.01" class="raliva-input w-full py-2 text-center" placeholder="0" />
                </div>
            </div>
            <div class="grid grid-cols-[110px_1fr] gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Satuan</label>
                    <input type="text" name="bahan[${idx}][satuan]" required class="raliva-input w-full" placeholder="m, kg, pcs" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Catatan</label>
                    <input type="text" name="bahan[${idx}][catatan]" class="raliva-input w-full" placeholder="opsional" />
                </div>
            </div>
        `;
        container.appendChild(row);
    }

    function removeBahanRow(btn) {
        const row = btn.closest('[data-bahan-row]');
        if (row) row.remove();
    }

    function onBahanInput(textInput) {
        const row = textInput.closest('[data-bahan-row]');
        if (!row) return;
        const hiddenInput = row.querySelector('input[name$="[bahan_id]"]');
        if (!hiddenInput) return;
        const b = bahanMaster.find(x => x.bahan_id == textInput.value || x.nama_bahan == textInput.value);
        if (b) {
            hiddenInput.value = b.bahan_id;
            textInput.value = b.bahan_id;
            const namaInput = row.querySelector('input[name$="[nama_bahan]"]');
            if (namaInput && !namaInput.value) namaInput.value = b.nama_bahan;
            const satuanInput = row.querySelector('input[name$="[satuan]"]');
            if (satuanInput && !satuanInput.value) satuanInput.value = b.satuan;
        } else {
            hiddenInput.value = '';
        }
    }

    // Auto-seed first bahan row when proses modal opens
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-modal-open');
            if (!id || !id.startsWith('modal-proses-')) return;
            const orderId = id.replace('modal-proses-', '');
            const container = document.getElementById('bahan-container-' + orderId);
            if (container && container.querySelectorAll('[data-bahan-row]').length === 0) {
                addBahanRow(orderId);
            }
        });
    });

    /* ====================================================================
       MODAL TAMBAH PESANAN — Online/Offline + dynamic product rows
       ==================================================================== */
    @php
        $itemVariantsJson = $variants->map(function ($v) {
            return [
                'product_variant_id' => $v->product_variant_id,
                'nama_produk' => $v->product?->nama_produk ?? 'Produk',
                'warna' => $v->warna,
                'ukuran' => $v->ukuran,
                'stok' => $v->warehouseStocks->sum('jumlah_stok'),
                'harga' => (float) ($v->harga ?? 0),
            ];
        })->toJson();
    @endphp
    const itemVariants = {!! $itemVariantsJson !!};

    let itemIdx = 0;

    function toggleTipePesanan() {
        const online = document.querySelector('#modal-tambah-pesanan input[name="tipe_pesanan"]:checked')?.value === 'online';
        document.getElementById('online-fields').classList.toggle('hidden', !online);
        document.getElementById('offline-fields').classList.toggle('hidden', online);
        // field required toggling
        document.querySelector('#tp-cust')?.toggleAttribute('required', online);
        ['nama_penerima','nomor_telepon','alamat'].forEach(n => {
            const el = document.querySelector(`[name="${n}"]`);
            if (el) el.toggleAttribute('required', !online);
        });
        recalculateTotal();
    }

    function toggleMetodeBayar() {
        const transfer = document.querySelector('#modal-tambah-pesanan input[name="metode_bayar"]:checked')?.value === 'transfer';
        document.getElementById('transfer-fields').classList.toggle('hidden', !transfer);
        const acc = document.querySelector('#tp-acc');
        const bukti = document.querySelector('#tp-bukti');
        if (acc) acc.toggleAttribute('required', transfer);
        if (bukti) bukti.toggleAttribute('required', transfer);
    }

    function addItemRow() {
        const container = document.getElementById('item-container');
        if (!container) return;
        const i = itemIdx++;
        const row = document.createElement('div');
        row.setAttribute('data-item-row', '');
        row.className = 'grid grid-cols-[1fr_110px_140px] gap-3 items-end';
        const dlId = 'variant-datalist-' + i;
        row.innerHTML = `
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Produk</label>
                <input type="text" list="${dlId}" autocomplete="off" placeholder="Ketik nama produk / pilih varian..." oninput="onVariantInput(this)" class="raliva-input w-full" />
                <input type="hidden" name="items[${i}][product_variant_id]" value="" />
                <datalist id="${dlId}">
                    ${itemVariants.map(v => `<option value="${v.product_variant_id}" label="${v.nama_produk} — ${[v.ukuran, v.warna].filter(Boolean).join(' ')} (stok ${v.stok}) — Rp ${v.harga.toLocaleString('id-ID')}">${v.nama_produk} — ${[v.ukuran, v.warna].filter(Boolean).join(' ')}</option>`).join('')}
                </datalist>
            </div>
            <div>
                <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Qty</label>
                <input type="number" name="items[${i}][quantity]" min="1" max="100" value="1" oninput="onQtyChange(this)" class="raliva-input w-full py-2 text-center" />
            </div>
            <div class="flex items-end justify-between gap-2">
                <div class="text-right flex-1 min-w-0">
                    <span class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Subtotal</span>
                    <span class="item-subtotal text-sm font-bold text-gold-accent whitespace-nowrap">Rp 0</span>
                </div>
                <button type="button" onclick="removeItemRow(this)" class="shrink-0 mb-0.5 px-2 py-2 rounded-lg border border-error/20 text-error hover:bg-error/10 transition-colors" title="Hapus baris">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
        `;
        container.appendChild(row);
        recalculateTotal();
    }

    function removeItemRow(btn) {
        const row = btn.closest('[data-item-row]');
        if (row) row.remove();
        recalculateTotal();
    }

    function onVariantInput(textInput) {
        const row = textInput.closest('[data-item-row]');
        if (!row) return;
        const hiddenInput = row.querySelector('input[name$="[product_variant_id]"]');
        if (!hiddenInput) return;
        const v = itemVariants.find(x => x.product_variant_id == textInput.value || x.nama_produk == textInput.value);
        if (v) {
            hiddenInput.value = v.product_variant_id;
            textInput.value = v.product_variant_id;
            recalculateRow(row);
            recalculateTotal();
        } else {
            hiddenInput.value = '';
            recalculateRow(row);
            recalculateTotal();
        }
    }

    function onQtyChange(input) {
        const row = input.closest('[data-item-row]');
        if (!row) return;
        recalculateRow(row);
        recalculateTotal();
    }

    function recalculateRow(row) {
        const hiddenInput = row.querySelector('input[name$="[product_variant_id]"]');
        const qtyInput = row.querySelector('input[name$="[quantity]"]');
        const subtotalEl = row.querySelector('.item-subtotal');
        if (!hiddenInput || !qtyInput || !subtotalEl) return;
        const v = itemVariants.find(x => x.product_variant_id == hiddenInput.value);
        const harga = v ? parseFloat(v.harga) : 0;
        const qty = Math.max(1, parseInt(qtyInput.value || 1, 10));
        const sub = harga * qty;
        subtotalEl.textContent = 'Rp ' + sub.toLocaleString('id-ID');
    }

    function recalculateTotal() {
        let total = 0;
        document.querySelectorAll('#item-container [data-item-row]').forEach(row => {
            const hiddenInput = row.querySelector('input[name$="[product_variant_id]"]');
            const qtyInput = row.querySelector('input[name$="[quantity]"]');
            const v = itemVariants.find(x => x.product_variant_id == hiddenInput?.value);
            const harga = v ? parseFloat(v.harga) : 0;
            const qty = Math.max(0, parseInt(qtyInput?.value || 0, 10));
            total += harga * qty;
            const subtotalEl = row.querySelector('.item-subtotal');
            if (subtotalEl) subtotalEl.textContent = 'Rp ' + (harga * qty).toLocaleString('id-ID');
        });
        const grand = document.getElementById('grand-total');
        if (grand) grand.textContent = total.toLocaleString('id-ID');
    }

    // Seed first item row when tambah modal opens
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-modal-open');
            if (id !== 'modal-tambah-pesanan') return;
            const container = document.getElementById('item-container');
            if (container && container.querySelectorAll('[data-item-row]').length === 0) {
                addItemRow();
            }
            toggleTipePesanan();
            toggleMetodeBayar();
        });
    });
</script>
@endpush
