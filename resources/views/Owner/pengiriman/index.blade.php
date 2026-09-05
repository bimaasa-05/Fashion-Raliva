@extends('layouts.owner')

@section('title', 'Pengiriman')

@section('header-title', 'Pengiriman')
@section('header-badge', '45 Berjalan')
@section('header-subtitle', 'Atur pilihan kurir toko dan pantau proses pengiriman.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-56 bg-surface-container-high rounded-lg animate-pulse"></div>
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
    {{-- Pilihan Kurir --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading mb-6">Pilihan Pengiriman Toko</h2>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border border-muted-border rounded-lg px-5 py-4 mb-gutter bg-surface-container-low">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-deep-onyx text-on-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">rocket_launch</span>
                </div>
                <div>
                    <p class="font-title-md text-sm text-on-surface">Kurir Platform — Raliva Express</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Tarif terintegrasi, asuransi otomatis, resi realtime.</p>
                </div>
            </div>
            <label class="raliva-toggle">
                <input type="checkbox" disabled class="sr-only peer" checked />
                <span class="raliva-toggle-track"></span>
                <span class="raliva-toggle-knob"></span>
            </label>
        </div>

        <div data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-gutter mt-gutter">
            @foreach ([['JNE Reguler & YES', '1–3 hari kerja • Rp 12rb–28rb', true], ['SiCepat REG & BEST', '1–2 hari kerja • Rp 14rb–32rb', false], ['GoSend Instant', 'Same day • Rp 18rb+', false]] as $kurir)
                <div data-reveal class="border border-muted-border rounded-lg px-4 py-3.5 flex items-center justify-between gap-3 hover:border-gold-accent/40 transition-colors">
                    <div>
                        <p class="font-title-md text-sm text-on-surface">{{ $kurir[0] }}</p>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $kurir[1] }}</p>
                    </div>
                    <label class="raliva-toggle">
                        <input type="checkbox" disabled class="sr-only peer" {{ $kurir[2] ? 'checked' : '' }} />
                        <span class="raliva-toggle-track"></span>
                        <span class="raliva-toggle-knob"></span>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="mt-gutter border border-muted-border rounded-lg px-5 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-gold-accent/10 border border-gold-accent/30 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-gold-accent">store</span>
                </div>
                <div>
                    <p class="font-title-md text-sm text-on-surface">Kurir Toko Sendiri / Instan Lain</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Aktifkan jika ingin mengatur pengantaran secara mandiri.</p>
                </div>
            </div>
            <label class="raliva-toggle">
                <input type="checkbox" disabled class="sr-only peer" />
                <span class="raliva-toggle-track"></span>
                <span class="raliva-toggle-knob"></span>
            </label>
        </div>
    </section>

    {{-- Ringkasan --}}
    <section data-reveal-group class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Dalam Pengiriman</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $summary['dikirim'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Terkirim Hari Ini</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ $summary['terkirim'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">where_to_vote</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Kurir</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $summary['menunggu'] }}</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">schedule_send</span>
        </div>
        <div data-reveal class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Rata-rata Waktu Kirim</span>
            <span class="raliva-figure text-[26px] text-on-surface"><span>2</span>,3 hari</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">timer</span>
        </div>
    </section>

    {{-- Tabel Pengiriman --}}
    <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading whitespace-nowrap">Proses Pengiriman</h2>
            <div class="flex items-center gap-gutter w-full sm:w-auto">
                <select data-table-filter="status-kirim" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="pickup">Menunggu Kurir</option>
                    <option value="jalan">Dalam Pengiriman</option>
                    <option value="sampai">Terkirim</option>
                </select>
            </div>
        </div>
        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[880px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tujuan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Kurir & Resi</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shipments as $ship)
                        @php
                            $key = $ship->status === 'selesai' ? 'sampai' : 'jalan';
                            $tgl = $ship->updated_at->translatedFormat('d M Y');
                        @endphp
                        <tr data-table-row data-status-kirim="{{ $key }}" class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">#{{ $ship->order_id }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $tgl }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">—</td>
                            <td class="py-3.5 px-4">
                                <p class="text-on-surface">—</p>
                                <p class="text-xs text-on-surface-variant mt-0.5 font-mono">—</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($key === 'sampai')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase border border-secondary/20"><span class="material-symbols-outlined fill text-[12px]">check_circle</span>Terkirim</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30"><span class="material-symbols-outlined fill text-[12px]">local_shipping</span>Dalam Perjalanan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" data-modal-open="modal-kirim-{{ $ship->order_id }}" class="text-xs font-semibold text-gold-accent hover:underline whitespace-nowrap">Lacak</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-on-surface-variant text-sm">Tidak ada pengiriman aktif.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div data-empty-state class="hidden flex-col items-center py-12 text-center gap-3">
            <span class="material-symbols-outlined text-[40px] text-on-surface-variant">inbox</span>
            <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pengiriman pada status ini.</p>
        </div>
    </section>
</div>

{{-- Modal Lacak Pengiriman --}}
@foreach ($shipments as $ship)
<div id="modal-kirim-{{ $ship->order_id }}" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-sm bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Lacak Pengiriman</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">#{{ $ship->order_id }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ $ship->nomor_order }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-3">
            <div class="flex items-center justify-between bg-surface-container-low rounded-lg px-4 py-3">
                <span class="text-[10px] uppercase text-on-surface-variant">Status</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase">{{ $ship->status }}</span>
            </div>
            <div class="bg-surface-container-low rounded-lg p-4 space-y-1.5 text-sm">
                <div class="flex justify-between"><span class="text-on-surface-variant">Customer</span><span class="text-on-surface">{{ $ship->checkout?->user?->nama_lengkap ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-on-surface-variant">Total</span><span class="text-on-surface">Rp {{ number_format($ship->grand_total, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-on-surface-variant">Tanggal</span><span class="text-on-surface">{{ optional($ship->created_at)->translatedFormat('d M Y') }}</span></div>
            </div>
            <p class="text-xs text-on-surface-variant flex items-start gap-2"><span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5">info</span>Resi otomatis terbit saat pesanan dikirim oleh Admin Produksi.</p>
        </div>
        <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex justify-end">
            <button type="button" data-modal-close class="px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
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
