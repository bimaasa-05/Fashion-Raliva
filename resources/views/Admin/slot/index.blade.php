@extends('layouts.admin')

@section('title', 'Beli Slot')
@section('header-title', 'Beli Slot')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Ajukan pembelian slot produk untuk toko yang kamu tugaskan.')

@section('content')
@include('partials.flash-toast')
@if (session('success'))
    <div class="bg-secondary-container/15 border border-secondary/30 text-secondary rounded-lg px-4 py-3 text-sm font-body-md">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="bg-error/10 border border-error/30 text-error rounded-lg px-4 py-3 text-sm font-body-md">{{ session('error') }}</div>
@endif

<div class="space-y-section-gap">
    @forelse ($stores as $st)
        @php $k = $kuota[$st->store_id] ?? ['total' => 0, 'used' => 0, 'sisa' => 0, 'progress' => 0]; @endphp
        <section data-reveal class="bg-deep-onyx text-on-primary rounded-lg px-6 py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden">
            <span class="material-symbols-outlined absolute -right-4 -bottom-5 text-[96px] text-on-primary/5 pointer-events-none select-none" aria-hidden="true">storage</span>
            <div class="relative">
                <p class="raliva-label text-gold-accent">{{ $st->nama_toko }}</p>
                <p class="font-body-md text-sm text-inverse-on-surface/70 mt-1">Sisa {{ $k['sisa'] }} dari Maksimal {{ $k['total'] }} ({{ $k['used'] }} terpakai, {{ $k['progress'] }}%)</p>
                <div class="h-2 w-full max-w-xs bg-white/10 rounded-full overflow-hidden mt-3">
                    <div class="progress-fill h-full rounded-full" data-progress-mode="quota" data-progress="{{ $k['progress'] }}"></div>
                </div>
            </div>
        </section>
    @empty
        <p class="text-center text-on-surface-variant text-sm py-8">Admin belum ditugaskan ke toko mana pun.</p>
    @endforelse

    @if ($stores->isNotEmpty())
        <section data-reveal class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <h2 class="font-title-md text-title-md text-on-surface premium-heading">Ajukan Pembelian Slot Fleksibel</h2>
            <p class="text-on-surface-variant font-body-md text-xs mt-1">Harga Rp {{ number_format($hargaPerSlot ?? 2000, 0, ',', '.') }} / slot. Menunggu persetujuan SuperAdmin.</p>
            <form method="POST" action="{{ route('admin.slot.request') }}" enctype="multipart/form-data" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block raliva-label mb-2">Toko *</label>
                    <select name="store_id" required class="raliva-select">
                        @foreach ($stores as $st)
                            <option value="{{ $st->store_id }}">{{ $st->nama_toko }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Jumlah Slot (1–1000) *</label>
                    <input name="jumlah_slot" type="number" value="50" min="1" max="1000" step="1" required class="raliva-input" data-slot-qty />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Metode Pembayaran *</label>
                    <select name="metode_pembayaran" required class="raliva-select">
                        <option value="" disabled selected>Pilih metode...</option>
                        @foreach ($metode ?? [] as $m)
                            <option value="{{ $m->payment_method_id }}">{{ $m->nama_metode }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block raliva-label mb-2">Bukti Pembayaran *</label>
                    <input name="file_bukti" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="raliva-input" />
                    <p class="text-xs text-on-surface-variant mt-1.5">JPG, PNG, atau PDF. Maksimal 4 MB.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block raliva-label mb-2">Alasan / Keterangan (opsional)</label>
                    <textarea name="alasan" rows="2" placeholder="cth. Menambah koleksi musim baru..." class="raliva-textarea"></textarea>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="py-3 px-8 bg-deep-onyx text-on-primary text-sm font-semibold rounded btn-premium flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">send</span>Ajukan Pembelian
                    </button>
                </div>
            </form>
        </section>
    @endif
</div>
@endsection
