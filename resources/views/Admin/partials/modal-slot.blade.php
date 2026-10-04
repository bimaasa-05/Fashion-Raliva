{{-- Popup gate kuota Admin: kuota habis = pilih Beli Slot (form di popup) atau Beli Paket (ke halaman). --}}
<div id="modal-slot-habis" data-modal class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/35 backdrop-blur-sm" data-modal-close></div>
    <div class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 py-5 border-b border-muted-border shrink-0">
            <div>
                <p class="raliva-label text-gold-accent">{{ __('Kuota Habis') }}</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ __('Slot Produk Penuh') }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ sprintf(__('Terpakai %d dari %d slot'), $slotKuota[$slotStoreAktif]['used'] ?? 0, $slotKuota[$slotStoreAktif]['total'] ?? 0) }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors" aria-label="{{ __('Tutup') }}">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-3">
            <p class="text-sm text-on-surface-variant">{{ __('Form tambah produk dikunci. Pilih salah satu cara di bawah untuk menambah kuota, lalu kembali tambah produk.') }}</p>
            <button type="button" data-modal-open="modal-slot-beli" data-modal-close class="block w-full text-left rounded-lg border border-gold-accent/30 bg-gold-accent/5 hover:bg-gold-accent/10 transition-colors p-4">
                <p class="flex items-center gap-2 font-bold text-sm text-on-surface">
                    <span class="material-symbols-outlined text-gold-accent">storage</span>{{ __('Beli Slot') }}
                </p>
                <p class="text-xs text-on-surface-variant mt-1 ml-8">{{ __('Ajukan slot fleksibel per jumlah — form dibuka di popup ini.') }}</p>
            </button>
            <a href="{{ route('admin.slot') }}#paket" class="block rounded-lg border border-muted-border hover:border-gold-accent/40 transition-colors p-4">
                <p class="flex items-center gap-2 font-bold text-sm text-on-surface">
                    <span class="material-symbols-outlined text-gold-accent">card_membership</span>{{ __('Beli Paket') }}
                </p>
                <p class="text-xs text-on-surface-variant mt-1 ml-8">{{ __('Buka halaman Beli Slot bagian paket — kuota langsung aktif.') }}</p>
            </a>
        </div>
    </div>
</div>

{{-- Popup Beli Slot Admin: form Ajukan Pembelian Slot Fleksibel. --}}
<div id="modal-slot-beli" data-modal class="fixed inset-0 z-[75] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/35 backdrop-blur-sm" data-modal-close></div>
    <form id="form-slot-beli" method="POST" action="{{ route('admin.slot.request') }}" enctype="multipart/form-data"
          class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[90vh] overflow-y-auto">
        @csrf
        <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 py-5 border-b border-muted-border shrink-0">
            <div>
                <p class="raliva-label text-gold-accent">{{ __('Beli Slot') }}</p>
                <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ __('Ajukan Slot Fleksibel') }}</h3>
                <p class="text-xs text-on-surface-variant mt-0.5">{{ sprintf(__('Rp %s / slot • menunggu persetujuan SuperAdmin'), number_format($slotHarga ?? 2000, 0, ',', '.')) }}</p>
            </div>
            <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors" aria-label="{{ __('Tutup') }}">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label for="as-toko" class="block raliva-label mb-2">{{ __('Toko') }} *</label>
                <select id="as-toko" name="store_id" required class="raliva-select">
                    @foreach ($slotStores ?? [] as $st)
                        <option value="{{ $st->store_id }}" {{ (int) old('store_id', $slotStoreAktif ?? 0) === (int) $st->store_id ? 'selected' : '' }}>{{ $st->nama_toko }}</option>
                    @endforeach
                </select>
                @error('store_id') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="as-jumlah" class="block raliva-label mb-2">{{ __('Jumlah Slot (1–1000)') }} *</label>
                <input id="as-jumlah" name="jumlah_slot" type="number" value="{{ old('jumlah_slot', 50) }}" min="1" max="1000" step="1" required class="raliva-input" />
                @error('jumlah_slot') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="border border-deep-onyx/20 bg-deep-onyx/[0.04] rounded-lg px-4 py-3 flex items-center justify-between">
                <span class="text-on-surface-variant font-body-md text-xs">{{ __('Total yang harus dibayar') }}</span>
                <span id="as-total" data-harga="{{ (int) ($slotHarga ?? 2000) }}" class="font-title-md text-title-md text-deep-onyx">Rp {{ number_format(50 * ((int) ($slotHarga ?? 2000)), 0, ',', '.') }}</span>
            </div>
            <div>
                <label for="as-metode" class="block raliva-label mb-2">{{ __('Metode Pembayaran') }} *</label>
                <select id="as-metode" name="metode_pembayaran" required class="raliva-select">
                    <option value="" disabled selected>{{ __('Pilih metode...') }}</option>
                    @foreach ($slotMetode ?? [] as $m)
                        <option value="{{ $m->payment_method_id }}">{{ $m->nama_metode }}</option>
                    @endforeach
                </select>
                @error('metode_pembayaran') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="as-bukti" class="block raliva-label mb-2">{{ __('Bukti Pembayaran') }} *</label>
                <input id="as-bukti" name="file_bukti" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="raliva-input" />
                <p class="text-xs text-on-surface-variant mt-1.5">{{ __('JPG, PNG, atau PDF. Maksimal 4 MB.') }}</p>
                @error('file_bukti') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="as-alasan" class="block raliva-label mb-2">{{ __('Alasan / Keterangan (opsional)') }}</label>
                <textarea id="as-alasan" name="alasan" rows="2" class="raliva-textarea">{{ old('alasan') }}</textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[16px]">send</span>{{ __('Ajukan Pembelian') }}
            </button>
        </div>
    </form>
</div>

<script>
(function () {
    var qty = document.getElementById('as-jumlah');
    var total = document.getElementById('as-total');
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
