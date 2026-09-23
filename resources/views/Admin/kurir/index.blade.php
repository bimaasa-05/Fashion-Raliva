@extends('layouts.admin')

@section('title', 'Metode Pengiriman')
@section('header-title', 'Metode Pengiriman')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Atur kurir & layanan yang aktif untuk setiap toko yang kamu tugaskan.')

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <div class="flex items-start gap-3 p-4 border border-gold-accent/30 bg-gold-accent/10 rounded-lg">
        <span class="material-symbols-outlined text-gold-accent text-[20px] mt-0.5">lock</span>
        <p class="font-body-md text-sm text-on-surface">Hanya kurir yang diaktifkan di sini yang muncul di form input resi toko tersebut. Kosongkan override bila mengikuti tarif & estimasi bawaan kurir.</p>
    </div>

    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
            <label class="raliva-label shrink-0" for="kurir-toko">Toko</label>
            <select id="kurir-toko" name="store_id" onchange="this.form.submit()" class="raliva-select sm:w-72">
                @foreach ($stores as $st)
                    <option value="{{ $st->store_id }}" @selected((int) $storeId === (int) $st->store_id)>{{ $st->nama_toko }}</option>
                @endforeach
            </select>
        </form>

        @if (! $storeId)
            <p class="text-center text-on-surface-variant text-sm py-8">Admin belum ditugaskan ke toko mana pun.</p>
        @else
            <form method="POST" action="{{ route('admin.kurir.sync') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="store_id" value="{{ $storeId }}" />
                @forelse ($couriers as $courier)
                    @php $setKurir = $settings[$courier->courier_id.'|0'] ?? null; @endphp
                    <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low/50">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="pengaturan[{{ $courier->courier_id }}|0][is_aktif]" value="0" /><input type="checkbox" name="pengaturan[{{ $courier->courier_id }}|0][is_aktif]" value="1" @checked($setKurir?->is_aktif ?? true) class="w-5 h-5 accent-[#8B1E3F]" />
                            <span class="font-title-md text-title-md text-on-surface">{{ $courier->nama_kurir }}</span>
                            <span class="text-xs text-on-surface-variant">({{ $courier->services->count() }} layanan)</span>
                        </label>
                        @if ($courier->services->isNotEmpty())
                            <div class="mt-3 space-y-2">
                                @foreach ($courier->services as $service)
                                    @php $set = $settings[$courier->courier_id.'|'.$service->shipping_service_id] ?? null; @endphp
                                    <div class="grid grid-cols-1 sm:grid-cols-[1fr_140px_140px] gap-2.5 items-end bg-surface-container-lowest border border-muted-border rounded-lg p-3">
                                        <label class="flex items-center gap-2.5 cursor-pointer min-w-0">
                                            <input type="hidden" name="pengaturan[{{ $courier->courier_id }}|{{ $service->shipping_service_id }}][is_aktif]" value="0" /><input type="checkbox" name="pengaturan[{{ $courier->courier_id }}|{{ $service->shipping_service_id }}][is_aktif]" value="1" @checked($set?->is_aktif ?? true) class="w-4 h-4 accent-[#8B1E3F] shrink-0" />
                                            <span class="text-sm text-on-surface truncate">{{ $service->nama_layanan }} <span class="text-on-surface-variant">(~{{ $service->estimasi_hari }} hari)</span></span>
                                        </label>
                                        <div>
                                            <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Ongkir (Rp)</label>
                                            <input type="number" name="pengaturan[{{ $courier->courier_id }}|{{ $service->shipping_service_id }}][ongkir_override]" value="{{ $set?->ongkir_override }}" min="0" placeholder="Bawaan" class="raliva-input w-full text-sm" />
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Estimasi (hari)</label>
                                            <input type="number" name="pengaturan[{{ $courier->courier_id }}|{{ $service->shipping_service_id }}][estimasi_override]" value="{{ $set?->estimasi_override }}" min="1" max="60" placeholder="Bawaan" class="raliva-input w-full text-sm" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-on-surface-variant text-sm py-8">Belum ada kurir aktif dari platform.</p>
                @endforelse
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-deep-onyx text-on-primary font-label-sm text-[11px] uppercase tracking-widest rounded btn-premium">Simpan Pengaturan</button>
                </div>
            </form>
        @endif
    </section>
</div>
@endsection
