@extends('layouts.owner')

@section('title', 'Kelola Slot')

@section('header-title', 'Kelola Slot')
@section('header-badge', '{{ $used ?? 0 }} / {{ $total ?? 0 }} Terpakai')
@section('header-subtitle', 'Kelola kuota slot produk toko Anda — tambah slot via Super Admin.')

@section('content')
<div data-skeleton class="space-y-section-gap">
    <div class="h-44 bg-surface-container-high rounded-lg animate-pulse"></div>
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-section-gap">
        <div class="lg:col-span-2 h-96 bg-surface-container-high rounded-lg animate-pulse"></div>
        <div class="lg:col-span-3 h-80 bg-surface-container-high rounded-lg animate-pulse"></div>
    </div>
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
        {{-- Form Ajukan Tambah Slot --}}
        <section data-reveal class="lg:col-span-2 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium lg:sticky lg:top-24">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Ajukan Tambah Slot</h2>
            <p class="text-on-surface-variant font-body-md text-xs mt-1">Permintaan akan diteruskan ke SuperAdmin untuk persetujuan.</p>

            <form method="POST" action="{{ route('owner.kelola-slot.request') }}" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="slot-jumlah" class="block raliva-label mb-2">Jumlah Slot Tambahan</label>
                    <input id="slot-jumlah" name="jumlah_slot" type="number" value="50" min="10" max="500" step="10" required class="raliva-input" />
                    <p class="text-xs text-on-surface-variant mt-1.5">Kelipatan 10 disarankan. Maksimal 500 per pengajuan.</p>
                    @error('jumlah_slot') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slot-alasan" class="block raliva-label mb-2">Alasan / Catatan</label>
                    <textarea id="slot-alasan" name="catatan" rows="3" placeholder="cth. Menambah koleksi musim baru 40 SKU..." class="raliva-textarea"></textarea>
                </div>
                <div class="border border-gold-accent/20 bg-gold-accent/5 rounded-lg px-4 py-3 flex items-start gap-3">
                    <span class="material-symbols-outlined text-[20px] text-gold-accent mt-0.5">info</span>
                    <p class="text-on-surface-variant font-body-md text-xs leading-relaxed">Slot ditambah manual oleh SuperAdmin setelah menyetujui permintaan Anda.</p>
                </div>
                <button type="submit" class="w-full py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">send</span>Ajukan ke SuperAdmin
                </button>
            </form>
        </section>

        {{-- Log Penambahan Slot --}}
        <section data-reveal class="lg:col-span-3 bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Riwayat Slot</h2>
            <p class="text-on-surface-variant font-body-md text-xs mt-1">Audit trail penambahan kuota — transparan untuk Owner & SuperAdmin.</p>

            <div data-table-wrap class="overflow-x-auto mt-6">
                <table class="premium-table w-full min-w-[640px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border text-left">
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Oleh</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Tambahan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Kuota Baru</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Catatan</th>
                            <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $row)
                            <tr class="border-b border-muted-border last:border-0">
                                <td class="py-3.5 px-4 text-on-surface-variant whitespace-nowrap">{{ $row->created_at?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-on-surface whitespace-nowrap">Owner</td>
                                <td class="py-3.5 px-4 text-right font-bold text-gold-accent whitespace-nowrap">+{{ $row->jumlah_slot }}</td>
                                <td class="py-3.5 px-4 text-right text-on-surface whitespace-nowrap">—</td>
                                <td class="py-3.5 px-4 text-on-surface-variant max-w-[180px]">{{ $row->catatan ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full {{ $row->status==='aktif' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' }} text-[10px] font-bold uppercase border">{{ $row->status==='aktif' ? 'Disetujui' : ucfirst($row->status) }}</span>
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
