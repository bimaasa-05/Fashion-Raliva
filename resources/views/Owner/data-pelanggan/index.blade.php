@extends('layouts.owner')

@section('title', 'Data Pelanggan')

@section('header-title', 'Data Pelanggan')
@section('header-badge', number_format($summary['total'] ?? 0, 0, ',', '.') . ' Pelanggan')
@section('header-subtitle', 'Daftar pelanggan yang membeli di toko Anda — lihat riwayat dan Top Leader.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-section-gap">
        <div class="h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="lg:col-span-2 h-48 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
    <div class="h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
</div>

<div data-real class="hidden space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    {{-- Podium Top Leader --}}
    @if ($top3->count() >= 1)
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 md:p-8">
        <div class="flex items-center justify-between mb-8">
            <div class="border-l-4 border-[#821E36] pl-3">
                <p class="text-[10px] md:text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Papan Peringkat Pembeli</p>
                <h2 class="font-title-md text-title-md text-on-surface mt-1">Top Customer</h2>
            </div>
            <span class="material-symbols-outlined text-[32px] md:text-[40px] text-on-surface-variant/30">workspace_premium</span>
        </div>

        {{-- Podium Flat --}}
        <style>
            .podium { position: relative; border:2px solid rgba(0,0,0,0.12); box-shadow: 0 8px 24px rgba(0,0,0,0.12); display:flex; align-items:center; justify-content:center; }
            .crown-gold { background: linear-gradient(135deg, #FFF5C0 0%, #FBBF24 25%, #D97706 50%, #FEF08A 75%, #B45309 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }
            .crown-container { position: relative; display: inline-flex; align-items: center; justify-content: center; animation: crownFloat 3s ease-in-out infinite, crownGlowPulse 2s ease-in-out infinite; }
            .crown-container::before { content: ''; position: absolute; top: -10%; bottom: -10%; left: -80%; width: 45%; background: rgba(255, 255, 255, 0.55); transform: skewX(-24deg); pointer-events: none; z-index: 10; animation: crownShimmer 2.4s linear infinite; }
            .crown-sparkle { position: absolute; top: 8px; right: 2px; width: 12px; height: 12px; background: #FFF; clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%); animation: sparkleTwinkle 1.8s ease-in-out infinite; pointer-events: none; }
            @keyframes crownFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
            @keyframes crownGlowPulse { 0%, 100% { filter: drop-shadow(0 0 8px rgba(251, 191, 36, 0.6)); } 50% { filter: drop-shadow(0 0 20px rgba(253, 224, 71, 0.95)); } }
            @keyframes crownShimmer { 0% { left: -80%; } 65% { left: 180%; } 100% { left: 180%; } }
            @keyframes sparkleTwinkle { 0%, 100% { transform: scale(0) rotate(0deg); opacity: 0; } 50% { transform: scale(1.3) rotate(90deg); opacity: 1; } }
            @media (prefers-reduced-motion: reduce) { .crown-container, .crown-container::before, .crown-sparkle { animation: none; } }
        </style>
        
        <div class="flex items-end justify-center gap-3 md:gap-6 pt-6 pb-4">
            @foreach($top3->sortByDesc('total_belanja')->values() as $idx => $c)
                @php
                    $rank = $idx + 1;
                    $isTop = $rank === 1;
                    $isSecond = $rank === 2;
                    $h = $isTop ? 'h-[160px] md:h-[190px]' : ($isSecond ? 'h-[115px] md:h-[145px]' : 'h-[90px] md:h-[115px]');
                    $frontBg = $isTop ? 'bg-[#821E36]' : ($isSecond ? 'bg-[#9CA3AF]' : 'bg-[#B45309]');
                @endphp
                <div class="flex-1 max-w-[150px] flex flex-col items-center {{ $isTop ? 'order-2' : ($isSecond ? 'order-1' : 'order-3') }}">
                    @if ($isTop)
                        <span class="crown-container mb-1"><span class="crown-gold material-symbols-outlined text-[28px] md:text-[34px]">crown</span><span class="crown-sparkle"></span></span>
                    @endif
                    <div class="w-10 h-10 md:w-14 md:h-14 rounded-full {{ $isTop ? 'bg-gold-accent text-white ring-4 ring-gold-accent/20' : 'bg-surface-container-high border border-outline-variant text-on-surface' }} flex items-center justify-center font-bold text-xs md:text-sm shadow-md">{{ $c->initials }}</div>
                    <p class="font-bold text-[11px] md:text-sm mt-2 truncate max-w-[90px] md:max-w-[110px] text-center">{{ $c->name }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ $c->jumlah_order }} pesanan</p>
                    <p class="font-bold text-[11px] text-[#821E36] mt-1">Rp {{ number_format($c->total_belanja,0,',','.') }}</p>
                    <div class="podium w-full {{ $h }} mt-3 {{ $frontBg }} text-white rounded-xl flex flex-col items-center justify-center ring-2 {{ $isTop ? 'ring-[#821E36]/30' : 'ring-black/5' }} shadow-lg">
                        <span class="{{ $isTop ? 'text-3xl md:text-4xl' : 'text-2xl md:text-3xl' }} font-black drop-shadow-lg">#{{ $rank }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @else
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 md:p-8 text-center flex flex-col items-center justify-center min-h-[300px]">
        <span class="material-symbols-outlined text-[48px] text-on-surface-variant mb-4">group</span>
        <h2 class="font-title-md text-title-md text-on-surface">Belum ada pembeli</h2>
        <p class="text-on-surface-variant text-sm mt-2">Data Top Customer akan muncul setelah ada transaksi pada toko ini.</p>
    </section>
    @endif

    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Pelanggan</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ number_format($summary['total'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">unik pernah belanja</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">groups</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
                <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Pelanggan Baru ({{ now()->translatedFormat('M') }})</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $summary['baru'] }}</span>
            <span class="font-label-sm text-[11px] text-secondary">bulan ini</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">person_add</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Repeat Buyer</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $summary['total'] > 0 ? round($summary['repeat'] / $summary['total'] * 100) : 0 }}%</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">beli ≥2 kali</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">repeat</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-5 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Rata Belanja</span>
            <span class="raliva-figure text-[26px] text-gold-accent">Rp {{ number_format($summary['rata'], 0, ',', '.') }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">per pelanggan</span>
            <span class="material-symbols-outlined absolute right-2 bottom-2 text-[72px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">receipt_long</span>
        </div>
    </section>

    {{-- Tabel Pelanggan --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Pelanggan</h2>
                <p class="text-xs text-on-surface-variant mt-1">Semua pembeli yang pernah bertransaksi di toko Anda.</p>
            </div>
        </div>

        {{-- Toolbar: 1 baris rapi — search kiri, filter kanan --}}
        <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
            <div class="relative flex-1 min-w-[220px] max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                <input type="text" placeholder="Cari nama atau email..." data-table-search class="raliva-search" />
            </div>
            <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                <select data-table-filter="segment" class="raliva-select lg:w-44">
                    <option value="">Semua Segmen</option>
                    <option value="leader">Top Leader</option>
                    <option value="setia">Pelanggan Setia</option>
                    <option value="baru">Baru</option>
                </select>
                <button type="button" data-filter-reset class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</button>
            </div>
        </div>

        <div class="hidden md:block">
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[900px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pelanggan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kontak</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Barang Dibeli</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Total Belanja</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Terakhir Belanja</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Segmen</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr data-table-row data-segment="{{ $row->segment }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 font-title-md text-xs text-on-surface">{{ $row->initials }}</div>
                                    <span class="font-bold text-on-surface">{{ $row->name }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface-variant text-xs">{{ $row->email }}</td>
                            <td class="py-3.5 px-4 text-on-surface text-xs max-w-[220px]">
                                @if ($row->items->isNotEmpty())
                                    <ul class="space-y-0.5">
                                        @foreach ($row->items as $item)
                                            <li class="truncate">{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-on-surface-variant">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center text-on-surface">{{ $row->jumlah_order }}</td>
                            <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format($row->total_belanja, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ optional(\Carbon\Carbon::parse($row->last_order))->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($row->segment === 'leader')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent text-deep-onyx text-[10px] font-bold uppercase"><span class="material-symbols-outlined text-[12px]">crown</span>Top Leader</span>
                                @elseif ($row->segment === 'setia')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20">Setia</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant">Baru</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" data-modal-open="modal-histori-{{ $row->id }}" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Lihat</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-6 text-center text-on-surface-variant">Belum ada pelanggan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        </div>
        <div class="md:hidden space-y-3">
            @forelse ($rows as $row)
                <article data-table-row data-segment="{{ $row->segment }}" class="bg-surface-container-lowest border border-muted-border rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0 font-title-md text-xs text-on-surface">{{ $row->initials }}</div>
                        <div class="flex-grow min-w-0">
                            <p class="font-bold text-on-surface truncate">{{ $row->name }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ $row->email }}</p>
                        </div>
                        @if ($row->segment === 'leader')
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent text-deep-onyx text-[10px] font-bold uppercase shrink-0"><span class="material-symbols-outlined text-[12px]">crown</span>Top</span>
                        @elseif ($row->segment === 'setia')
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20 shrink-0">Setia</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase border border-outline-variant shrink-0">Baru</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-muted-border text-sm text-center">
                        <div><p class="text-[11px] text-on-surface-variant">Pesanan</p><p class="font-bold text-on-surface">{{ $row->jumlah_order }}</p></div>
                        <div><p class="text-[11px] text-on-surface-variant">Total</p><p class="font-bold text-gold-accent whitespace-nowrap">Rp {{ number_format($row->total_belanja, 0, ',', '.') }}</p></div>
                        <div><p class="text-[11px] text-on-surface-variant">Terakhir</p><p class="font-semibold text-on-surface-variant whitespace-nowrap">{{ optional(\Carbon\Carbon::parse($row->last_order))->translatedFormat('d M Y') }}</p></div>
                    </div>
                    <button type="button" data-modal-open="modal-histori-{{ $row->id }}" class="mt-3 w-full py-2 rounded-lg border border-muted-border text-xs font-semibold text-gold-accent hover:border-gold-accent transition-colors">Lihat Riwayat</button>
                </article>
            @empty
                <p class="py-6 text-center text-on-surface-variant">Belum ada pelanggan.</p>
            @endforelse
        </div>

        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
            </div>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pelanggan yang cocok.</p>
            <button type="button" data-filter-reset class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
        </div>

        <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
            <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
            Data pelanggan hanya dapat dilihat (read-only) untuk menjaga privasi. Top Leader diperbarui otomatis bulanan.
        </p>
    </section>
</div>

{{-- Modal Histori Pesanan per pelanggan --}}
@foreach ($rows as $row)
<div id="modal-histori-{{ $row->id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Histori Pesanan</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $row->name }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ $row->email }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-3">
            @forelse ($row->ordersList as $ord)
            <div class="border border-muted-border rounded-lg p-4 bg-surface-container-low">
                <div class="flex items-center justify-between gap-3">
                    <span class="font-mono text-sm text-on-surface">{{ $ord->nomor_order }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full border {{ \App\Support\StatusStyle::badgeClass($ord->status) }} text-[10px] font-bold uppercase">{{ $ord->status }}</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">{{ \Carbon\Carbon::parse($ord->created_at)->translatedFormat('d M Y') }}</p>
                <ul class="mt-2 space-y-0.5 text-sm text-on-surface">
                    @foreach ($ord->items as $it)
                    <li class="flex items-start gap-2"><span class="material-symbols-outlined text-[16px] text-on-surface-variant">check_box_outline_blank</span>{{ $it }}</li>
                    @endforeach
                </ul>
                <p class="text-right font-bold text-gold-accent text-sm mt-2">Rp {{ number_format($ord->grand_total, 0, ',', '.') }}</p>
            </div>
            @empty
            <p class="text-center text-on-surface-variant text-sm py-6">Belum ada pesanan.</p>
            @endforelse
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
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
