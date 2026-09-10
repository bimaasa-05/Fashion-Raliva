@extends('layouts.owner')

@section('title', 'Iklan Peringkat')

@section('header-title', 'Iklan Peringkat')
@section('header-badge', 'Promo')
@section('header-subtitle', 'Ajukan peringkat iklan agar produk tampil teratas — pilih produk, nominal, periode, rekening tujuan & bukti transfer.')

@section('content')
<div class="space-y-section-gap">
    @if (session('success'))
        <div class="rounded-lg border border-secondary/30 bg-secondary-container/15 text-secondary px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-lg border border-error/30 bg-error/10 text-error px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif
    @if (! $store)
        <div class="rounded-lg border border-gold-accent/30 bg-gold-accent/10 px-4 py-3 flex items-start gap-3">
            <span class="material-symbols-outlined text-gold-accent mt-0.5">storefront</span>
            <div>
                <p class="font-bold text-sm">Belum punya toko</p>
                <p class="text-sm text-on-surface-variant mt-1">Silakan <a href="{{ route('owner.pengajuan-toko') }}" class="underline text-gold-accent font-semibold">ajukan toko</a> untuk akses iklan.</p>
            </div>
        </div>
    @endif

    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading mb-2">Ajukan Iklan Peringkat</h2>
        <p class="text-xs text-on-surface-variant mb-5">Pilih produk, nominal bid (min Rp 100.000), periode tayang, rekening tujuan (dari Data Bank Super Admin), metode & bukti transfer. Menunggu verifikasi Super Admin.</p>

        <form method="POST" action="{{ route('owner.peringkat-iklan.request') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Produk</label>
                <select name="product_id" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent">
                    <option value="">Pilih Produk</option>
                    @foreach($products as $p)
                        <option value="{{ $p->product_id }}">{{ $p->nama_produk }}</option>
                    @endforeach
                </select>
                @error('product_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Nominal Bid (Rp)</label>
                    <input type="number" name="nominal_bid" min="100000" step="50000" value="{{ old('nominal_bid', 500000) }}" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent" />
                    @error('nominal_bid')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Metode Pembayaran</label>
                    <select name="metode_pembayaran" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent">
                        <option value="">Pilih metode</option>
                        @foreach($metode as $m)
                            <option value="{{ $m->payment_method_id }}">{{ $m->nama_metode }}</option>
                        @endforeach
                    </select>
                    @error('metode_pembayaran')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Rekening Tujuan Transfer</label>
                <select name="platform_bank_account_id" required class="w-full bg-transparent border border-muted-border rounded-lg p-4 font-body-md text-sm focus:outline-none focus:border-gold-accent">
                    <option value="">Pilih rekening tujuan</option>
                    @forelse($rekenings as $rek)
                        <option value="{{ $rek->platform_bank_account_id }}">{{ $rek->bank->nama_bank ?? '-' }} • {{ $rek->nomor_rekening }} a.n. {{ $rek->nama_pemilik }}</option>
                    @empty
                        <option value="" disabled>Belum ada rekening platform — hubungi Super Admin</option>
                    @endforelse
                </select>
                @error('platform_bank_account_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/5 rounded-lg">
                <span class="material-symbols-outlined text-gold-accent mt-0.5 text-[20px]">schedule</span>
                <div class="flex-1">
                    <p class="font-label-sm text-label-sm text-on-surface uppercase">Durasi Otomatis (Fair)</p>
                    <p class="text-sm text-on-surface-variant mt-1">Periode aktif dihitung <span class="font-bold text-on-surface">sejak disetujui Super Admin</span>, bukan sejak ajukan. <span id="ad-preview-hari" class="font-bold text-gold-accent">—</span></p>
                    <p class="text-xs text-on-surface-variant/70 mt-1">Tier: 100k-499k → 7 hari, 500k-999k → 14 hari, 1jt-1,99jt → 30 hari, ≥2jt → 60 hari.</p>
                </div>
            </div>
            <div>
                <label class="block font-label-sm text-label-sm text-on-surface-variant uppercase mb-2">Bukti Pembayaran</label>
                <input type="file" name="file_bukti" accept=".jpg,.jpeg,.png,.pdf" required class="block w-full text-xs text-on-surface-variant file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-deep-onyx file:text-on-primary file:font-label-sm" />
                <p class="text-xs text-on-surface-variant mt-1.5">JPG/PNG/PDF maks 5MB. Transfer sesuai nominal ke rekening tujuan.</p>
                @error('file_bukti')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="py-3 px-6 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Ajukan Iklan</button>
            </div>
        </form>
    </section>

    <section class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
        <h2 class="font-title-md text-title-md text-on-surface premium-heading mb-4">Riwayat Pengajuan Iklan</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-muted-border bg-surface-container-low/50 text-on-surface-variant text-xs uppercase">
                        <th class="p-3">Produk</th>
                        <th class="p-3">Nominal</th>
                        <th class="p-3">Periode</th>
                        <th class="p-3">Rekening</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slots as $s)
                        <tr class="border-b border-muted-border hover:bg-surface-container-low">
                            <td class="p-3 text-sm">{{ $s->product->nama_produk ?? '-' }}</td>
                            <td class="p-3 text-sm font-mono">Rp {{ number_format((float) $s->nominal_bid, 0, ',', '.') }}</td>
                            <td class="p-3 text-xs">{{ $s->tanggal_mulai?->format('d M Y') }} - {{ $s->tanggal_selesai?->format('d M Y') }}</td>
                            <td class="p-3 text-xs">{{ $s->bankAccount?->bank->nama_bank ?? '-' }} {{ $s->bankAccount?->nomor_rekening ?? '' }}</td>
                            <td class="p-3"><span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $s->status === 'aktif' ? 'bg-success/10 text-success border-success/20' : ($s->status === 'ditunda' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/20' : 'bg-error/10 text-error border-error/20') }}">{{ $s->status }}</span></td>
                            <td class="p-3 text-xs">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $s->payment_status === 'terverifikasi' ? 'bg-success/10 text-success' : ($s->payment_status === 'ditolak' ? 'bg-error/10 text-error' : 'bg-surface-container-high text-on-surface-variant') }}">{{ $s->payment_status }}</span>
                                @if($s->file_bukti)
                                    <a href="{{ asset('storage/' . $s->file_bukti) }}" target="_blank" class="ml-2 text-gold-accent hover:underline">Bukti</a>
                                @endif
                                @if($s->alasan_penolakan)
                                    <div class="text-error text-[11px] mt-1">Tolak: {{ $s->alasan_penolakan }}</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center text-on-surface-variant text-sm">Belum ada pengajuan iklan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($slots->hasPages())
            <div class="mt-6 flex justify-center">{{ $slots->links() }}</div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    const peringkatTiers = @json($tiers ?? []);
    function peringkatHari(nominal) {
        nominal = parseInt(nominal) || 0;
        for (const t of peringkatTiers) {
            const min = parseInt(t.min) || 0;
            const max = t.max === null || t.max === '' ? null : parseInt(t.max);
            const hari = parseInt(t.hari) || 7;
            if (nominal >= min && (max === null || nominal <= max)) return hari;
        }
        if (nominal >= 2000000) return 60;
        if (nominal >= 1000000) return 30;
        if (nominal >= 500000) return 14;
        if (nominal >= 100000) return 7;
        return 7;
    }
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.querySelector('input[name="nominal_bid"]');
        const preview = document.getElementById('ad-preview-hari');
        function updatePreview() {
            const h = peringkatHari(input?.value);
            if (preview) preview.textContent = h + ' hari';
        }
        if (input) { input.addEventListener('input', updatePreview); updatePreview(); }
    });
</script>
@endpush