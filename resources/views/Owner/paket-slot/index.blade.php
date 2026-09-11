@extends('layouts.owner')

@php
/** @var array{nama:string,harga:string,total:int,used:int,sisa:int,progress:int} $active */
/** @var \Illuminate\Database\Eloquent\Collection<int,\App\Models\StoreSlotSubscription> $riwayat */
/** @var \Illuminate\Database\Eloquent\Collection<int,\App\Models\ProductSlotPackage> $packages */
/** @var \Illuminate\Database\Eloquent\Collection<int,\App\Models\PaymentMethod> $metode */
/** @var int $hargaPerSlot */
@endphp

@section('title', 'Paket Slot Produk')

@section('header-title', 'Paket Slot Produk')
@section('header-badge', 'Paket Aktif')
@section('header-subtitle', 'Pantau kapasitas slot produk dan beli paket sesuai kebutuhan.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        @for ($i = 0; $i < 3; $i++)
            <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
        @endfor
    </div>
    <div class="h-64 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if (session('success') || session('error'))
        <div class="rounded-lg border px-4 py-3 text-sm font-body-md {{ session('success') ? 'border-secondary/30 bg-secondary-container/15 text-secondary' : 'border-error/30 bg-error/10 text-error' }}">{{ session('success') ?? session('error') }}</div>
    @endif
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    {{-- Penanda halaman: Kelola Slot / Paket Slot --}}
    <div data-reveal class="inline-flex bg-surface-container-lowest border border-muted-border rounded-lg p-1 gap-1 max-w-full overflow-x-auto">
        <a href="{{ route('owner.kelola-slot') }}" class="px-4 py-2 rounded-md text-xs font-medium transition-colors whitespace-nowrap {{ request()->routeIs('owner.kelola-slot') ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Kelola Slot</a>
        <a href="{{ route('owner.paket-slot') }}" class="px-4 py-2 rounded-md text-xs font-medium transition-colors whitespace-nowrap {{ request()->routeIs('owner.paket-slot') ? 'bg-deep-onyx text-on-primary' : 'text-on-surface-variant hover:text-on-surface' }}">Paket Slot</a>
    </div>
    {{-- Paket Aktif --}}
    <section data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 md:p-8 relative overflow-hidden">
        <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[140px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">workspace_premium</span>
        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <p class="text-xs font-semibold text-gold-accent">Paket Aktif</p>
                <h2 class="raliva-figure text-[30px] mt-2">{{ $active['nama'] }}</h2>
                <p class="font-body-md text-sm text-inverse-on-surface/70 mt-2">{{ $active['harga'] }} {{ $active['nama'] === 'Fleksibel' ? '/ slot' : '/ bulan' }}</p>
            </div>
            <div class="w-full max-w-md">
                <div class="flex items-end justify-between mb-2">
                    <span class="text-xs font-mediumr text-inverse-on-surface/60">Slot Terpakai</span>
                    <span class="font-title-md text-title-md"><span>{{ $active['used'] }}</span> <span class="text-inverse-on-surface/50">/ {{ $active['total'] }}</span></span>
                </div>
                <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                    <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="{{ $active['progress'] }}"></div>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <p class="font-label-sm text-[11px] text-inverse-on-surface/60">{{ $active['sisa'] }} slot tersedia</p>
                    @if ($active['progress'] >= 80)
                        <span class="text-xs font-semibold text-secondary">Segera Upgrade</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Pilihan Paket --}}
    <section>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Pilihan Paket</h2>
        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-section-gap items-stretch">
            @forelse ($packages as $pkg)
                @php
                    $slot = $pkg->jumlah_slot;
                    $isActive = $active['total'] == $slot;
                    $fitur = $slot <= 100
                        ? [['check','100 slot produk aktif'],['check','Moderasi prioritas standar'],['close','Tanpa banner toko'],['close','Tanpa analitik lanjutan']]
                        : ($slot <= 200
                            ? [['check',$slot.' slot produk aktif'],['check','Moderasi prioritas standar'],['check','Banner promo toko'],['check','Dukungan chat 12 jam']]
                            : [['check',$slot.' slot produk aktif'],['check','Moderasi prioritas tinggi'],['check','Banner promo + highlight produk'],['check','Analitik penjualan lanjutan'],['check','Dukungan chat prioritas 24 jam']]);
                @endphp
                <article data-reveal class="bg-surface-container-lowest border {{ $isActive ? 'border-2 border-gold-accent shadow-xl relative' : 'border-muted-border' }} rounded-lg p-6 flex flex-col card-premium {{ $isActive ? '' : 'relative overflow-hidden' }}">
                    @if ($isActive)
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gold-accent text-white dark:text-[#111] text-xs font-semibold">Paket Anda</span>
                    @endif
                    <p class="font-title-md text-title-md text-on-surface">{{ $pkg->nama_paket }}</p>
                    <div class="mt-3 flex items-baseline gap-1">
                        <span class="raliva-figure text-[26px] text-{{ $isActive ? 'gold-accent' : 'on-surface' }}">Rp {{ number_format($pkg->harga,0,',','.') }}</span>
                        <span class="text-on-surface-variant font-body-md text-sm">/bulan</span>
                    </div>
                    <p class="raliva-label mt-2">{{ $slot }} slot produk</p>
                    <ul class="mt-6 space-y-3 flex-1 font-body-md text-sm text-on-surface">
                        @foreach ($fitur as $f)
                            <li class="flex items-start gap-3 {{ $f[0] === 'check' ? '' : 'text-on-surface-variant' }}">
                                <span class="material-symbols-outlined text-[18px] {{ $f[0] === 'check' ? 'text-secondary' : 'text-error' }} shrink-0">{{ $f[0] === 'check' ? 'check_circle' : 'cancel' }}</span>{{ $f[1] }}
                            </li>
                        @endforeach
                    </ul>
                    @if ($isActive)
                        <button type="button" disabled class="mt-8 w-full py-3 bg-surface-container-high text-on-surface-variant rounded-lg text-sm font-semibold cursor-default">Sedang Digunakan</button>
                    @else
                        <button type="button" data-beli-paket data-paket-id="{{ $pkg->slot_package_id }}" data-paket-nama="{{ $pkg->nama_paket }}" data-slot-count="{{ $slot }}" data-harga="{{ number_format($pkg->harga,0,',','.') }}" class="mt-8 w-full py-3 bg-deep-onyx text-on-primary rounded-lg text-sm font-semibold btn-premium flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">shopping_cart</span>Beli Paket
                        </button>
                    @endif
                </article>
            @empty
                <p class="col-span-full text-on-surface-variant text-sm py-8 text-center">Belum ada paket slot tersedia.</p>
            @endforelse
        </div>

        <p data-reveal class="text-xs text-on-surface-variant mt-6 flex items-start gap-2 max-w-3xl">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            Peningkatan paket berlaku segera dengan pembayaran prorata. Penurunan paket hanya berlaku pada periode tagihan berikutnya dan memastikan jumlah produk Anda tidak melebihi slot baru.
        </p>
    </section>

    {{-- Riwayat Pembelian --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <h2 class="font-title-md text-title-md mb-6 text-on-surface premium-heading">Riwayat Pembelian Paket</h2>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[720px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Paket</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Periode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Nominal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayat as $r)
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $r->package?->nama_paket ?? 'Paket #'.$r->slot_package_id }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $r->tanggal_mulai?->translatedFormat('d M Y') }} — {{ $r->tanggal_berakhir?->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format($r->package?->harga ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ ucfirst($r->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-on-surface-variant text-sm">Belum ada pembelian paket. Kuota aktif saat ini dari slot fleksibel/gratis.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border border-muted-border rounded-lg p-4 bg-surface-container-low">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">tune</span>
                <div>
                    <p class="font-bold text-on-surface text-sm">Butuh jumlah fleksibel?</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Beli slot fleksibel mulai 1 slot dengan harga per slot Rp {{ number_format($hargaPerSlot ?? 2000, 0, ',', '.') }}.</p>
                </div>
            </div>
            <a href="{{ route('owner.kelola-slot') }}" class="inline-flex items-center justify-center gap-2 py-2.5 px-5 rounded-lg border border-gold-accent/40 text-gold-accent text-xs font-semibold hover:bg-gold-accent/10 transition-colors shrink-0">
                <span class="material-symbols-outlined text-[16px]">add</span>Beli Fleksibel
            </a>
        </div>
    </section>
</div>

{{-- Modal Konfirmasi Beli Paket --}}
<div id="modal-beli-paket" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeBeliModal()"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Beli Paket Slot</p>
                <h3 id="beli-paket-nama" class="font-title-md text-title-md text-on-surface premium-heading mt-1">-</h3>
            </div>
            <button type="button" onclick="closeBeliModal()" class="text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">close</span></button>
        </div>
        <form id="beli-paket-form" method="POST" action="" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="border border-muted-border rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Jumlah Slot</p>
                    <p id="beli-paket-slot" class="font-title-md text-title-md text-on-surface mt-1">-</p>
                </div>
                <div class="border border-deep-onyx/20 bg-deep-onyx/[0.04] rounded-lg px-4 py-3">
                    <p class="text-xs text-on-surface-variant">Total Bayar</p>
                    <p id="beli-paket-harga" class="font-title-md text-title-md text-deep-onyx mt-1">-</p>
                </div>
            </div>
            <div>
                <label for="beli-metode" class="block raliva-label mb-2">Metode Pembayaran</label>
                <select id="beli-metode" name="metode_pembayaran" required class="raliva-select">
                    <option value="" disabled selected>Pilih metode...</option>
                    @forelse ($metode ?? [] as $m)
                        @php /** @var \App\Models\PaymentMethod $m */ @endphp
                        <option value="{{ $m->payment_method_id }}">{{ $m->nama_metode }}</option>
                    @empty
                        <option value="" disabled>Tidak ada metode tersedia</option>
                    @endforelse
                </select>
            </div>
            <div>
                <label for="beli-bukti" class="block raliva-label mb-2">Bukti Pembayaran</label>
                <input id="beli-bukti" name="file_bukti" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="raliva-input" />
                <p class="text-xs text-on-surface-variant mt-1.5">JPG, PNG, atau PDF. Maksimal 4 MB. Paket aktif segera setelah bukti divertifikasi.</p>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-gutter pt-2">
                <button type="button" onclick="closeBeliModal()" class="py-3 px-6 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Batal</button>
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[16px]">shopping_cart</span>Beli Paket</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  function openBeliModal(el) {
    document.getElementById('beli-paket-nama').textContent = el.dataset.paketNama;
    document.getElementById('beli-paket-slot').textContent = el.dataset.slotCount + ' slot';
    document.getElementById('beli-paket-harga').textContent = 'Rp ' + el.dataset.harga;
    document.getElementById('beli-paket-form').action = '{{ route('owner.paket-slot.beli', ':id:') }}'.replace(':id:', el.dataset.paketId);
    document.getElementById('modal-beli-paket').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }
  function closeBeliModal() {
    document.getElementById('modal-beli-paket').classList.add('hidden');
    document.body.style.overflow = '';
  }
  window.closeBeliModal = closeBeliModal;
  document.querySelectorAll('[data-beli-paket]').forEach(btn => {
    btn.addEventListener('click', () => openBeliModal(btn));
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeBeliModal();
  });

  if (!document.querySelector('[data-real]')) return;
  // Check if no store banner exists (means no store)
  const noStore = document.querySelector('[data-no-store-banner]');
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
