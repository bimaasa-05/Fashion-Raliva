@extends('layouts.owner')

@section('title', 'Pencairan Dana')
@section('header-title', 'Pencairan Dana')
@section('header-subtitle', 'Kelola penarikan saldo toko Anda.')

@section('content')
<div data-real class="space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="raliva-label text-gold-accent">Saldo Tersedia</p>
                <p class="raliva-figure text-[28px] mt-1">Rp {{ number_format($wallet?->saldo_tersedia ?? 0,0,',','.') }}</p>
                <p class="text-xs text-on-surface-variant mt-1">{{ $store?->nama_toko ?? '-' }} • {{ $bankAccounts->count() }} rekening</p>
            </div>
            <button type="button" data-modal-open="modal-cair" class="px-6 py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium">Ajukan Pencairan</button>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <h2 class="font-title-md text-title-md premium-heading">Riwayat Pencairan</h2>
        <div data-table-wrap class="overflow-x-auto mt-6">
            <table class="premium-table w-full min-w-[700px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Rekening</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">{{ $w->diajukan_pada?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-bold text-on-surface">Rp {{ number_format($w->jumlah,0,',','.') }}</td>
                            <td class="py-3.5 px-4">{{ $w->bankAccount->bank->nama_bank ?? '-' }} • {{ $w->bankAccount->nomor_rekening ?? '' }}</td>
                            <td class="py-3.5 px-4"><span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $w->status==='dibayar' ? 'bg-secondary-container/20 text-secondary border-secondary/20' : ($w->status==='pending' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-error/10 text-error border-error/20') }}">{{ $w->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-on-surface-variant">Belum ada pencairan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            @if($withdrawals instanceof \Illuminate\Pagination\AbstractPaginator)
                {{ $withdrawals->links() }}
            @endif
        </div>
    </section>
</div>

<div id="modal-cair" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
        <h3 class="font-title-md text-title-md premium-heading">Ajukan Pencairan</h3>
        <p class="text-xs text-on-surface-variant mt-1">Minimal Rp 100.000 • Saldo tersedia Rp {{ number_format($wallet?->saldo_tersedia ?? 0,0,',','.') }}</p>
        <form method="POST" action="{{ route('owner.pencairan-dana.store') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Jumlah Pencairan</label>
                <input name="jumlah" type="number" min="100000" required class="raliva-input" placeholder="100000" />
            </div>
            <div>
                <label class="block raliva-label mb-2">Rekening Tujuan</label>
                <select name="bank_account_id" required class="raliva-select">
                    <option value="">Pilih rekening</option>
                    @foreach($bankAccounts as $ba)
                        <option value="{{ $ba->store_bank_account_id }}">{{ $ba->bank->nama_bank }} • {{ $ba->nomor_rekening }} ({{ $ba->nama_pemilik }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block raliva-label mb-2">Catatan</label>
                <textarea name="catatan" rows="2" class="raliva-textarea" placeholder="opsional"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-modal-close class="py-2.5 px-6 border border-muted-border rounded-lg text-sm font-semibold">Batal</button>
                <button type="submit" class="py-2.5 px-6 bg-deep-onyx text-on-primary rounded-lg text-sm font-semibold btn-premium">Ajukan</button>
            </div>
        </form>
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
