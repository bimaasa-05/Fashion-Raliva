@extends('layouts.owner')

@section('title', 'Pencairan Dana')
@section('header-title', 'Pencairan Dana')
@section('header-subtitle', 'Kelola penarikan saldo toko Anda.')

@section('content')
<div data-real class="space-y-section-gap">
    @if(! \App\Support\OwnerContext::currentStore())
        <div data-no-store-banner class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses fitur ini.</p>
            </div>
        </div>
    @endif
    <section data-reveal-group class="grid grid-cols-1 md:grid-cols-3 gap-section-gap">
        <div data-reveal class="bg-deep-onyx text-on-primary rounded-lg p-6 relative overflow-hidden flex flex-col">
            <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">account_balance_wallet</span>
            <p class="raliva-label text-gold-accent relative">Saldo Tersedia</p>
            <p class="raliva-figure text-[34px] md:text-[42px] mt-4 relative">Rp {{ number_format($wallet?->saldo_tersedia ?? 0,0,',','.') }}</p>
            <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                <p class="font-body-md text-xs text-inverse-on-surface/60">{{ $store?->nama_toko ?? '-' }}</p>
                <button type="button" data-modal-open="modal-cair" class="py-2.5 px-5 bg-gold-accent text-[#111] text-xs font-semibold rounded btn-premium shrink-0">Cairkan</button>
            </div>
        </div>

        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">hourglass_top</span>
            <p class="raliva-label relative">Saldo Tertahan</p>
            <p class="raliva-figure text-[26px] mt-4 text-on-surface relative">Rp {{ number_format($wallet?->saldo_tertahan ?? 0,0,',','.') }}</p>
            <p class="text-on-surface-variant font-body-md text-xs mt-auto pt-6 relative">Dana yang terkunci saat pencairan disetujui dan sedang diproses.</p>
        </div>

        <div data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium flex flex-col relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-4 -bottom-6 text-[130px] text-gold-accent/25 fill drop-shadow-[0_0_6px_rgba(201,162,77,0.35)] pointer-events-none select-none" aria-hidden="true">savings</span>
            <p class="raliva-label relative">Total DiCairkan</p>
            <p class="raliva-figure text-[26px] mt-4 text-secondary relative">Rp {{ number_format($totalDicairkan ?? 0,0,',','.') }}</p>
            <div class="flex items-center justify-between mt-auto pt-6 relative gap-gutter flex-wrap">
                <p class="font-body-md text-xs text-on-surface-variant">{{ $withdrawals->count() }} pencairan tercatat</p>
                <a href="#riwayat" class="py-2.5 px-5 border border-muted-border text-xs font-semibold rounded-lg hover:border-gold-accent transition-colors shrink-0">Riwayat</a>
            </div>
        </div>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope id="riwayat">
        <h2 class="font-title-md text-title-md premium-heading">Riwayat Pencairan</h2>
        <div data-table-wrap class="overflow-x-auto hidden md:block mt-6">
            <table class="premium-table w-full min-w-[700px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tanggal</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Tujuan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bukti</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                        <tr class="border-b border-muted-border last:border-0">
                            <td class="py-3.5 px-4">{{ $w->diajukan_pada?->translatedFormat('d M Y') ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-bold text-on-surface">Rp {{ number_format($w->jumlah,0,',','.') }}</td>
                            <td class="py-3.5 px-4">
                                <p>{{ $w->tujuan_jenis_label }} • {{ $w->tujuan_penyedia }} • {{ $w->tujuan_nomor }}</p>
                            </td>
                            <td class="py-3.5 px-4"><span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $w->status==='dibayar' ? 'bg-amber-500/10 text-amber-600 border-amber-500/30' : ($w->status==='pending' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-error/10 text-error border-error/20') }}">{{ $w->status }}</span></td>
                            <td class="py-3.5 px-4">
                                @if ($w->status === 'dibayar' && $w->file_bukti)
                                    @php
                                        $buktiUrl = asset('storage/' . ltrim($w->file_bukti, '/'));
                                        $buktiExt = strtolower(pathinfo($w->file_bukti, PATHINFO_EXTENSION));
                                        $buktiNama = \Illuminate\Support\Str::afterLast($w->file_bukti, '/');
                                    @endphp
                                    @if (in_array($buktiExt, ['jpg', 'jpeg', 'png'], true))
                                        <a href="{{ $buktiUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-gold-accent hover:underline">
                                            <img src="{{ $buktiUrl }}" alt="{{ $buktiNama }}" class="w-8 h-8 object-cover rounded" loading="lazy" />
                                            <span class="text-xs font-semibold">Bukti</span>
                                        </a>
                                    @else
                                        <a href="{{ $buktiUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-semibold text-gold-accent hover:underline">
                                            <span class="material-symbols-outlined text-[14px]">description</span>Bukti
                                        </a>
                                    @endif
                                    @if ($w->deskripsi_bukti)
                                        <p class="text-xs text-on-surface-variant mt-1">{{ $w->deskripsi_bukti }}</p>
                                    @endif
                                @else
                                    <span class="text-on-surface-variant text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-on-surface-variant">Belum ada pencairan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="md:hidden grid grid-cols-1 gap-gutter mt-6">
            @forelse($withdrawals as $w)
                <article data-table-row class="bg-surface-container-lowest border border-muted-border rounded-xl p-4 card-premium relative overflow-hidden">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-xs text-on-surface-variant">{{ $w->diajukan_pada?->translatedFormat('d M Y') ?? '-' }}</p>
                            <p class="font-bold text-on-surface mt-0.5">Rp {{ number_format($w->jumlah,0,',','.') }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $w->status==='dibayar' ? 'bg-amber-500/10 text-amber-600 border-amber-500/30' : ($w->status==='pending' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : 'bg-error/10 text-error border-error/20') }}">{{ $w->status }}</span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-muted-border">
                        <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Tujuan</p>
                        <p class="text-sm text-on-surface mt-0.5">{{ $w->tujuan_jenis_label }} • {{ $w->tujuan_penyedia }} • {{ $w->tujuan_nomor }}</p>
                    </div>
                    @if ($w->status === 'dibayar' && $w->file_bukti)
                        @php
                            $buktiMUrl = asset('storage/' . ltrim($w->file_bukti, '/'));
                            $buktiMExt = strtolower(pathinfo($w->file_bukti, PATHINFO_EXTENSION));
                        @endphp
                        <div class="mt-3 pt-3 border-t border-muted-border">
                            <p class="text-[10px] uppercase tracking-wider text-on-surface-variant font-medium">Bukti transfer</p>
                            @if (in_array($buktiMExt, ['jpg', 'jpeg', 'png'], true))
                                <a href="{{ $buktiMUrl }}" target="_blank" rel="noopener" class="block mt-2 hover:opacity-90 transition-opacity">
                                    <img src="{{ $buktiMUrl }}" alt="bukti transfer" class="w-full max-h-48 h-auto object-contain rounded-lg" loading="lazy" />
                                </a>
                            @else
                                <a href="{{ $buktiMUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gold-accent hover:underline mt-2">
                                    <span class="material-symbols-outlined text-[14px]">description</span>
                                    <span class="truncate">{{ \Illuminate\Support\Str::afterLast($w->file_bukti, '/') }}</span>
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </a>
                            @endif
                            @if ($w->deskripsi_bukti)
                                <p class="text-xs text-on-surface-variant mt-1.5">{{ $w->deskripsi_bukti }}</p>
                            @endif
                        </div>
                    @endif
                </article>
            @empty
                <p class="text-on-surface-variant text-sm py-6 text-center">Belum ada pencairan.</p>
            @endforelse
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
        <p class="text-xs text-on-surface-variant mt-1">Minimal Rp 100.000 • Maksimal Rp {{ number_format((float) $available, 0, ',', '.') }}</p>
        <form method="POST" action="{{ route('owner.pencairan-dana.store') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block raliva-label mb-2">Jumlah Pencairan</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-on-surface-variant pointer-events-none">Rp</span>
                    <input name="jumlah" type="text" inputmode="numeric" data-rupiah required class="raliva-input" style="padding-left:2.75rem" placeholder="100.000" />
                </div>
                <div class="flex flex-wrap gap-1.5 mt-2">
                    @foreach([5 => '5%', 10 => '10%', 25 => '25%', 50 => '50%', 75 => '75%', 100 => 'Maksimal'] as $p => $label)
                        <button type="button" data-persentase="{{ $p }}" class="quick-cair-btn text-xs px-2.5 py-1 rounded-md border border-muted-border text-on-surface-variant hover:border-gold-accent/40 hover:text-gold-accent transition-colors">{{ $label }}</button>
                    @endforeach
                </div>
                <div id="fail-cair-warning" class="hidden items-center gap-2 bg-error/10 border border-error/25 text-error rounded-lg px-3 py-2 text-xs font-body-md mt-2">
                    <span class="material-symbols-outlined text-[16px] shrink-0">info</span>
                    <span id="fail-cair-warning-text">Saldo Anda tidak segitu.</span>
                </div>
            </div>
            <div>
                <label class="block raliva-label mb-2">Tipe Tujuan</label>
                <input type="hidden" name="tipe_tujuan" id="tujuan-tipe" value="{{ old('tipe_tujuan') === 'e-wallet' ? 'e-wallet' : 'bank' }}" />
                <div class="grid grid-cols-2 gap-2 p-1 bg-surface-container-low border border-muted-border rounded-xl">
                    <button type="button" data-tujuan-tipe="bank" class="tujuan-tipe-btn py-2.5 rounded-lg text-sm font-semibold transition-colors">Bank</button>
                    <button type="button" data-tujuan-tipe="e-wallet" class="tujuan-tipe-btn py-2.5 rounded-lg text-sm font-semibold transition-colors">E-wallet</button>
                </div>
            </div>
            <div id="tujuan-block-bank" class="space-y-4">
                <div>
                    <label class="block raliva-label mb-2">Bank Tujuan</label>
                    <select name="bank_id" class="raliva-select">
                        <option value="">Pilih bank</option>
                        @foreach($banks as $b)
                            <option value="{{ $b->bank_id }}">{{ $b->nama_bank }} ({{ $b->kode_bank }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">No. Rekening</label>
                    <input name="nomor_tujuan" type="text" inputmode="numeric" value="{{ old('nomor_tujuan') }}" required maxlength="50" class="raliva-input" placeholder="1234567890" />
                </div>
            </div>
            <div id="tujuan-block-ewallet" class="space-y-4">
                <div>
                    <label class="block raliva-label mb-2">Penyedia E-wallet</label>
                    <select name="penyedia" class="raliva-select" disabled>
                        <option value="">Pilih penyedia</option>
                        <option value="OVO">OVO</option>
                        <option value="GoPay">GoPay</option>
                        <option value="DANA">DANA</option>
                        <option value="ShopeePay">ShopeePay</option>
                        <option value="LinkAja">LinkAja</option>
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Nomor E-wallet</label>
                    <input name="nomor_tujuan" type="text" inputmode="tel" value="{{ old('nomor_tujuan') }}" maxlength="50" class="raliva-input" placeholder="0812xxxxxxxx" disabled />
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="raliva-label">Catatan</label>
                    <span data-char-count class="text-[11px] text-on-surface-variant/70">0 / 500</span>
                </div>
                <textarea name="catatan" id="catatan-cair" rows="2" maxlength="500" class="raliva-textarea" placeholder="opsional"></textarea>
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

/* Format ribuan live untuk input nominal (ketik 1000000 → 1.000.000 + hint Rp). */
const MAX_CAIR = {{ (int) round($available) }};
const LOCKED_CAIR = {{ (int) round($locked) }};
const jumlahInput = document.querySelector('[data-modal][id="modal-cair"] input[name="jumlah"][data-rupiah]');
const failWarning = document.getElementById('fail-cair-warning');
const failWarningText = document.getElementById('fail-cair-warning-text');
const catatanCair = document.getElementById('catatan-cair');
const catatanCounter = document.querySelector('[data-char-count]');

function numericCairValue(el) {
    return parseInt(String(el?.value || '').replace(/\D/g, ''), 10) || 0;
}

function setJumlahOverState(on) {
    const submitBtn = document.querySelector('#modal-cair button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = on;
        submitBtn.classList.toggle('opacity-50', on);
        submitBtn.classList.toggle('cursor-not-allowed', on);
    }
}

function showPendingCairWarning() {
    failWarning.classList.remove('hidden');
    failWarning.classList.add('flex');
    failWarningText.textContent = 'Saldo Anda masih dalam proses pencairan (pending) sebesar Rp ' + new Intl.NumberFormat('id-ID').format(LOCKED_CAIR) + '. Anda tidak dapat mengajukan lagi sampai pengajuan selesai.';
    jumlahInput.style.borderColor = '#ef4444';
    setJumlahOverState(true);
}

function syncCairState() {
    if (!failWarning || !jumlahInput) return;
    const val = numericCairValue(jumlahInput);
    const over = val > MAX_CAIR;
    const allPending = MAX_CAIR === 0 && LOCKED_CAIR > 0;
    if (over && allPending) {
        showPendingCairWarning();
        return;
    }
    failWarning.classList.toggle('hidden', !over);
    failWarning.classList.toggle('flex', over);
    if (over) {
        failWarningText.textContent = 'Saldo Anda tidak segitu — maksimal Rp ' + new Intl.NumberFormat('id-ID').format(MAX_CAIR) + '.';
    }
    jumlahInput.style.borderColor = over ? '#ef4444' : '';
    const catatanOver = (catatanCair?.value.length ?? 0) > 500;
    setJumlahOverState(over || catatanOver);
}

function syncCatatanCount() {
    if (!catatanCair || !catatanCounter) return;
    const len = catatanCair.value.length;
    const over = len > 500;
    catatanCounter.textContent = len + ' / 500';
    catatanCounter.classList.toggle('text-error', over || len > 475);
    catatanCounter.classList.toggle('text-on-surface-variant/70', !over && len <= 475);
    catatanCair.style.borderColor = over ? '#ef4444' : '';
}

document.addEventListener('input', (e) => {
    const el = e.target?.closest?.('[data-rupiah]');
    if (!el) return;
    const digits = el.value.replace(/\D/g, '').slice(0, 15);
        el.value = digits ? new Intl.NumberFormat('id-ID').format(digits) : '';
    if (el === jumlahInput) syncCairState();
});
document.addEventListener('submit', (e) => {
    if (!(e.target instanceof HTMLFormElement)) return;
    e.target.querySelectorAll('[data-rupiah]').forEach((el) => { el.value = el.value.replace(/\./g, ''); });
});

/* Toggle tipe tujuan: Bank / E-wallet (satu nama field nomor_tujuan per blok aktif). */
function syncTujuanTipe(tipe) {
    const hidden = document.getElementById('tujuan-tipe');
    if (!hidden) return;
    hidden.value = tipe;
    document.querySelectorAll('[data-tujuan-tipe]').forEach((btn) => {
        const on = btn.dataset.tujuanTipe === tipe;
        btn.classList.toggle('bg-deep-onyx', on);
        btn.classList.toggle('text-on-primary', on);
        btn.classList.toggle('btn-premium', on);
        btn.classList.toggle('text-on-surface-variant', !on);
    });
    const bank = document.getElementById('tujuan-block-bank');
    const ew = document.getElementById('tujuan-block-ewallet');
    if (bank) bank.classList.toggle('hidden', tipe !== 'bank');
    if (ew) ew.classList.toggle('hidden', tipe !== 'e-wallet');
    if (bank) bank.querySelectorAll('select, input').forEach((el) => { el.disabled = tipe !== 'bank'; });
    if (ew) ew.querySelectorAll('select, input').forEach((el) => { el.disabled = tipe !== 'e-wallet'; });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-tujuan-tipe]').forEach((btn) => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            syncTujuanTipe(this.getAttribute('data-tujuan-tipe'));
        });
    });
    const initial = document.getElementById('tujuan-tipe')?.value || 'bank';
    syncTujuanTipe(initial);

    document.querySelectorAll('[data-persentase]').forEach((btn) => {
        btn.addEventListener('click', function () {
            if (!jumlahInput) return;
            if (MAX_CAIR === 0 && LOCKED_CAIR > 0) {
                jumlahInput.value = '';
                document.querySelectorAll('[data-persentase]').forEach((b) => {
                    b.classList.remove('bg-gold-accent/10', 'text-gold-accent', 'border-gold-accent/40');
                    b.classList.add('border-muted-border', 'text-on-surface-variant');
                });
                showPendingCairWarning();
                return;
            }
            const pct = (parseFloat(this.dataset.persentase || '0') || 0) / 100;
            const amount = Math.floor(pct * MAX_CAIR);
            jumlahInput.value = amount ? new Intl.NumberFormat('id-ID').format(amount) : '';
            document.querySelectorAll('[data-persentase]').forEach((b) => {
                const on = b === this;
                b.classList.toggle('bg-gold-accent/10', on && amount > 0);
                b.classList.toggle('text-gold-accent', on && amount > 0);
                b.classList.toggle('border-gold-accent/40', on && amount > 0);
                b.classList.toggle('border-muted-border', !on || amount === 0);
                b.classList.toggle('text-on-surface-variant', !on || amount === 0);
            });
            syncCairState();
        });
    });
    syncCairState();
    if (catatanCair) catatanCair.addEventListener('input', syncCatatanCount);
    syncCatatanCount();
});
</script>
@endpush

@endsection
