{{-- Popup Tambah Slot Owner: pilih cara menambah kuota, lalu Kelola Slot di dalam popup. --}}
<div id="modal-pilih-slot" data-modal class="fixed inset-0 z-[70] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/35 backdrop-blur-sm" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">{{ __('Tambah Slot') }}</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ __('Pilih Cara Menambah Slot') }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ sprintf(__('Sisa %d dari Maksimal %d slot'), $sisaSlot ?? 0, $totalSlot ?? 0) }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors" aria-label="{{ __('Tutup') }}">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-3">
            <div class="rounded-lg border {{ ($dokLegalOk ?? false) ? 'border-success/30 bg-success/10' : 'border-error/30 bg-error/10' }} px-4 py-3 flex items-start gap-3">
                <span class="material-symbols-outlined text-[20px] {{ ($dokLegalOk ?? false) ? 'text-success' : 'text-error' }}">{{ ($dokLegalOk ?? false) ? 'verified' : 'gpp_bad' }}</span>
                <p class="text-xs leading-relaxed {{ ($dokLegalOk ?? false) ? 'text-on-surface' : 'text-error' }}">
                    {{ __('Syarat wajib: minimal salah satu dari') }} <b>KTP, NIB, atau NPWP</b> {{ __('sudah terverifikasi.') }}
                    @if (($dokLegalOk ?? false))
                        {{ __('Terpenuhi:') }} <b>{{ $dokLegalAda ?? '' }}</b>.
                    @else
                        {{ __('Belum terpenuhi —') }} <a href="{{ route('owner.pengajuan-toko') }}" class="underline font-bold">{{ __('lengkapi dokumen di Pengajuan Toko') }}</a>.
                    @endif
                </p>
            </div>
            <a href="{{ route('owner.paket-slot') }}" class="block rounded-lg border border-gold-accent/30 bg-gold-accent/5 hover:bg-gold-accent/10 transition-colors p-4">
                <p class="flex items-center gap-2 font-bold text-sm text-on-surface">
                    <span class="material-symbols-outlined text-gold-accent">card_membership</span>{{ __('Beli Paket Slot') }}
                </p>
                <p class="text-xs text-on-surface-variant mt-1 ml-8">{{ __('Paket hemat pilihan, kuota langsung aktif di halaman Paket Slot.') }}</p>
            </a>
            <button type="button" data-modal-open="modal-kelola-slot" data-modal-close class="block w-full text-left rounded-lg border border-muted-border hover:border-gold-accent/40 transition-colors p-4">
                <p class="flex items-center gap-2 font-bold text-sm text-on-surface">
                    <span class="material-symbols-outlined text-gold-accent">storage</span>{{ __('Kelola Slot') }}
                </p>
                <p class="text-xs text-on-surface-variant mt-1 ml-8">{{ __('Beli slot fleksibel per jumlah — form dibuka di popup ini.') }}</p>
            </button>
        </div>
    </div>
</div>

{{-- Popup Kelola Slot Owner: form Beli Slot Fleksibel. --}}
<div id="modal-kelola-slot" data-modal class="fixed inset-0 z-[75] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/35 backdrop-blur-sm" data-modal-close></div>
    <form method="POST" action="{{ route('owner.kelola-slot.request') }}" enctype="multipart/form-data"
          class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        @csrf
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">{{ __('Kelola Slot') }}</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ __('Beli Slot Fleksibel') }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ sprintf(__('Rp %s / slot • verifikasi SuperAdmin maks. 1×24 jam'), number_format($hargaPerSlot ?? 2000, 0, ',', '.')) }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors" aria-label="{{ __('Tutup') }}">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-5">
            @if (! ($dokLegalOk ?? false))
                <div class="rounded-lg border border-error/30 bg-error/10 px-4 py-3 flex items-start gap-3">
                    <span class="material-symbols-outlined text-[20px] text-error">gpp_bad</span>
                    <p class="text-xs leading-relaxed text-error">{{ __('Syarat belum terpenuhi: minimal salah satu') }} <b>KTP, NIB, atau NPWP</b> {{ __('harus terverifikasi.') }} <a href="{{ route('owner.pengajuan-toko') }}" class="underline font-bold">{{ __('Lengkapi di Pengajuan Toko') }}</a>.</p>
                </div>
            @endif
            <div>
                <label for="ms-jumlah" class="block raliva-label mb-2">{{ __('Jumlah Slot (1–1000)') }} *</label>
                <input id="ms-jumlah" name="jumlah_slot" type="number" value="{{ old('jumlah_slot', 50) }}" min="1" max="1000" step="1" required class="raliva-input" {{ ($dokLegalOk ?? false) ? '' : 'disabled' }} />
                @error('jumlah_slot') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="border border-deep-onyx/20 bg-deep-onyx/[0.04] rounded-lg px-4 py-3 flex items-center justify-between">
                <span class="text-on-surface-variant font-body-md text-xs">{{ __('Total yang harus dibayar') }}</span>
                <span id="ms-total" data-harga="{{ (int) ($hargaPerSlot ?? 2000) }}" class="font-title-md text-title-md text-deep-onyx">Rp {{ number_format(50 * ((int) ($hargaPerSlot ?? 2000)), 0, ',', '.') }}</span>
            </div>
            <div>
                <label for="ms-metode" class="block raliva-label mb-2">{{ __('Metode Pembayaran') }} *</label>
                <select id="ms-metode" name="metode_pembayaran" required class="raliva-select" {{ ($dokLegalOk ?? false) ? '' : 'disabled' }}>
                    <option value="" disabled selected>{{ __('Pilih metode...') }}</option>
                    @foreach ($metodeSlot ?? [] as $m)
                        <option value="{{ $m->payment_method_id }}">{{ $m->nama_metode }}</option>
                    @endforeach
                </select>
                @error('metode_pembayaran') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="ms-bukti" class="block raliva-label mb-2">{{ __('Bukti Pembayaran') }} *</label>
                <input id="ms-bukti" name="file_bukti" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="raliva-input" {{ ($dokLegalOk ?? false) ? '' : 'disabled' }} />
                <p class="text-xs text-on-surface-variant mt-1.5">{{ __('JPG, PNG, atau PDF. Maksimal 4 MB.') }}</p>
                @error('file_bukti') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="ms-alasan" class="block raliva-label mb-2">{{ __('Alasan / Keterangan') }} <span class="text-on-surface-variant">({{ __('opsional') }})</span></label>
                <textarea id="ms-alasan" name="alasan" rows="2" class="raliva-textarea" {{ ($dokLegalOk ?? false) ? '' : 'disabled' }}>{{ old('alasan') }}</textarea>
            </div>
            <button type="submit" {{ ($dokLegalOk ?? false) ? '' : 'disabled' }} class="w-full py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2 {{ ($dokLegalOk ?? false) ? '' : 'opacity-60 cursor-not-allowed' }}">
                <span class="material-symbols-outlined text-[16px]">send</span>{{ __('Bayar & Ajukan') }}
            </button>
        </div>
    </form>
</div>

<script>
(function () {
    var qty = document.getElementById('ms-jumlah');
    var total = document.getElementById('ms-total');
    if (!qty || !total) return;
    var harga = parseInt(total.getAttribute('data-harga') || '2000', 10);
    var hitung = function () {
        var n = Math.max(0, parseInt(qty.value || '0', 10) || 0);
        total.textContent = 'Rp ' + (n * harga).toLocaleString('id-ID');
    };
    qty.addEventListener('input', hitung);
    hitung();
})();
</script>
