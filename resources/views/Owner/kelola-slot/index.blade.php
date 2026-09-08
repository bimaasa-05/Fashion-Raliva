@extends('layouts.owner')

@php $badgeSlot = ($used ?? 0) . ' / ' . ($total ?? 0) . ' Terpakai'; @endphp

@section('title', 'Kelola Slot')

@section('header-title', 'Kelola Slot')
@section('header-badge', $badgeSlot)
@section('header-subtitle', 'Kelola kuota slot produk toko Anda — beli slot fleksibel atau paket berbayar.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <div class="lg:col-span-2 h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="lg:col-span-3 h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
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
    {{-- Kuota Saat Ini — real --}}
    <section data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 md:p-8 relative overflow-hidden">
        <span class="material-symbols-outlined absolute -right-6 -bottom-8 text-[160px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">storage</span>
        <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <p class="raliva-label text-gold-accent">Kuota Aktif</p>
                <p class="raliva-figure text-[34px] md:text-[42px] mt-2">{{ $used ?? 0 }} <span class="text-on-primary/50 text-[22px] font-normal">/ {{ $total ?? 0 }}</span> <span class="text-sm font-normal text-on-primary/60">slot terpakai</span></p>
                <p class="font-body-md text-sm text-inverse-on-surface/60 mt-2">Sisa {{ $sisa ?? 0 }} slot • Kelola penuh oleh SuperAdmin</p>
            </div>
            <div class="w-full max-w-md">
                <div class="h-3 bg-white/10 rounded-full overflow-hidden">
                    <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="{{ $pct ?? 0 }}"></div>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-xs text-inverse-on-surface/60">{{ $pct ?? 0 }}% terpakai</span>
                    <span class="text-xs font-bold text-gold-accent">{{ $sisa ?? 0 }} tersedia</span>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap items-start">
        {{-- Form Beli Slot Fleksibel --}}
        <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium lg:sticky lg:top-24">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Beli Slot Fleksibel</h2>
            <p class="text-on-surface-variant font-body-md text-xs mt-1">Pilih jumlah slot bebas, bayar sesuai harga per slot, upload bukti transfer. Verifikasi oleh SuperAdmin maksimal 1×24 jam.</p>

            <form method="POST" action="{{ route('owner.kelola-slot.request') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="slot-jumlah" class="block raliva-label mb-2">Jumlah Slot</label>
                    <input id="slot-jumlah" name="jumlah_slot" type="number" value="50" min="1" max="1000" step="1" required class="raliva-input" data-slot-qty />
                    <p class="text-xs text-on-surface-variant mt-1.5">Bebas mulai 1 slot, maksimal 1000 per pembelian.</p>
                    @error('jumlah_slot') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="border border-gold-accent/20 bg-gold-accent/5 rounded-lg px-4 py-3 flex items-center justify-between">
                    <span class="text-on-surface-variant font-body-md text-xs">Harga per slot</span>
                    <span class="font-bold text-gold-accent text-sm">Rp {{ number_format($hargaPerSlot ?? 2000, 0, ',', '.') }}</span>
                </div>
                <div class="border border-deep-onyx/20 bg-deep-onyx/[0.04] rounded-lg px-4 py-3 flex items-center justify-between">
                    <span class="text-on-surface-variant font-body-md text-xs">Total yang harus dibayar</span>
                    <span id="slot-total" class="font-title-md text-title-md text-deep-onyx">Rp {{ number_format(50 * ($hargaPerSlot ?? 2000), 0, ',', '.') }}</span>
                </div>
                <div>
                    <label for="slot-metode" class="block raliva-label mb-2">Metode Pembayaran</label>
                    <select id="slot-metode" name="metode_pembayaran" required class="raliva-select">
                        <option value="" disabled selected>Pilih metode...</option>
                        @forelse ($metode ?? [] as $m)
                            <option value="{{ $m->payment_method_id }}">{{ $m->nama_metode }}</option>
                        @empty
                            <option value="" disabled>Tidak ada metode tersedia</option>
                        @endforelse
                    </select>
                    @error('metode_pembayaran') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slot-bukti" class="block raliva-label mb-2">Bukti Pembayaran</label>
                    <input id="slot-bukti" name="file_bukti" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="raliva-input" />
                    <p class="text-xs text-on-surface-variant mt-1.5">JPG, PNG, atau PDF. Maksimal 4 MB.</p>
                    @error('file_bukti') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slot-alasan" class="block raliva-label mb-2">Alasan / Keterangan <span class="text-on-surface-variant">(opsional)</span></label>
                    <textarea id="slot-alasan" name="alasan" rows="2" placeholder="cth. Menambah koleksi musim baru 40 SKU..." class="raliva-textarea"></textarea>
                    @error('alasan') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="border border-gold-accent/20 bg-gold-accent/5 rounded-lg px-4 py-3 flex items-start gap-3">
                    <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">info</span>
                    <p class="text-on-surface-variant font-body-md text-xs leading-relaxed">Slots hanya ditambahkan setelah bukti pembayaran diverifikasi dan disetujui oleh SuperAdmin.</p>
                </div>
                <button type="submit" class="w-full py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">send</span>Bayar & Ajukan
                </button>
            </form>
        </section>

        {{-- Log Penambahan Slot --}}
        <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Riwayat Slot</h2>
            <p class="text-on-surface-variant font-body-md text-xs mt-1">Audit trail penambahan kuota — transparan untuk Owner & SuperAdmin.</p>

            <div data-table-wrap class="overflow-x-auto mt-6">
                <table class="premium-table w-full min-w-[720px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tipe</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Tambahan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Total Bayar</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Catatan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $row)
                            <tr class="border-b border-muted-border last:border-0">
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row['tanggal']?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">{{ $row['tipe'] === 'permintaan' ? 'Beli Fleksibel' : 'Grant ('.$row['tipe'].')' }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">+{{ $row['jumlah_slot'] }}</td>
                                <td class="py-3.5 px-4 text-right text-on-surface whitespace-nowrap">{{ $row['total_harga'] !== null ? 'Rp '.number_format($row['total_harga'], 0, ',', '.') : '—' }}</td>
                                <td class="py-3.5 px-4 text-on-surface-variant max-w-[200px]">{{ $row['catatan'] ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if ($row['payment_status'] !== null)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full {{ $row['payment_status'] === \App\Models\SlotPurchaseRequest::PEMBAYARAN_TERVERIFIKASI ? 'bg-success/10 text-success border-success/20' : ($row['status'] === 'ditolak' ? 'bg-error/10 text-error border-error/30' : 'bg-gold-accent/10 text-gold-accent border-gold-accent/30') }} text-[10px] font-bold uppercase border">{{ str_replace('_', ' ', $row['payment_status']) }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-success/10 text-success border-success/20 text-[10px] font-bold uppercase border">Disetujui</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-8 text-center text-on-surface-variant text-sm">Belum ada riwayat slot.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 border border-muted-border rounded-lg p-4 bg-surface-container-low flex items-start gap-3">
                <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">history</span>
                <p class="text-on-surface-variant font-body-md text-xs leading-relaxed">Semua penambahan tercatat permanen. Jika ditolak, SuperAdmin akan menyertakan alasan pada kolom catatan.</p>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const qty = document.querySelector('[data-slot-qty]');
  if (qty) {
    const harga = {{ $hargaPerSlot ?? 2000 }};
    const totalEl = document.getElementById('slot-total');
    const fmt = n => 'Rp ' + Number(n).toLocaleString('id-ID');
    const render = () => {
      const n = Math.max(0, parseInt(qty.value || '0', 10));
      totalEl.textContent = fmt(n * harga);
    };
    qty.addEventListener('input', render);
  }
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
