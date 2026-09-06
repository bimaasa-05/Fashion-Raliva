@extends('layouts.owner')

@section('title', 'Paket Slot Produk')

@section('header-title', 'Paket Slot Produk')
@section('header-badge', 'Paket Growth')
@section('header-subtitle', 'Pantau kapasitas slot produk dan upgrade paket sesuai kebutuhan.')

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
    @if(! \App\Support\OwnerContext::currentStore())
        <div class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    {{-- Paket Aktif --}}
    <section data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 md:p-8 relative overflow-hidden">
        <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[140px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">workspace_premium</span>
        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <p class="text-xs font-semibold text-gold-accent">Paket Aktif</p>
                <h2 class="raliva-figure text-[30px] mt-2">{{ $active['nama'] }}</h2>
                <p class="font-body-md text-sm text-inverse-on-surface/70 mt-2">{{ $active['harga'] }} / bulan &bull; Berlaku s.d. 12 Feb 2027</p>
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
                        <button type="button" disabled class="mt-8 w-full py-3 border border-muted-border rounded-lg text-sm font-semibold text-on-surface-variant cursor-default">Upgrade (read-only)</button>
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
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Invoice</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Paket</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Periode</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Nominal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['inv' => 'INV-PKT-202602-014', 'paket' => 'Growth — Bulanan', 'periode' => '12 Jan — 12 Feb 2027', 'nominal' => 'Rp 199.000', 'status' => 'Lunas'],
                        ['inv' => 'INV-PKT-202601-009', 'paket' => 'Growth — Bulanan', 'periode' => '12 Des — 12 Jan 2027', 'nominal' => 'Rp 199.000', 'status' => 'Lunas'],
                        ['inv' => 'INV-PKT-202512-031', 'paket' => 'Upgrade Basic → Growth', 'periode' => '12 Nov — 12 Des 2026', 'nominal' => 'Rp 132.000', 'status' => 'Lunas'],
                        ['inv' => 'INV-PKT-202511-002', 'paket' => 'Basic — Bulanan', 'periode' => '12 Okt — 12 Nov 2026', 'nominal' => 'Rp 99.000', 'status' => 'Lunas'],
                    ] as $row)
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4 font-bold text-on-surface">{{ $row['inv'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface">{{ $row['paket'] }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['periode'] }}</td>
                            <td class="py-3.5 px-4 font-bold text-gold-accent whitespace-nowrap">{{ $row['nominal'] }}</td>
                            <td class="py-3.5 px-4 text-center"><span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">{{ $row['status'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

{{-- Modal Konfirmasi Upgrade --}}


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
