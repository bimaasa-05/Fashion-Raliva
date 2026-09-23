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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <form method="POST" action="{{ route('admin.kurir.courier.store') }}" class="border border-muted-border rounded-lg p-5 bg-surface-container-low/50">
                    @csrf
                    <input type="hidden" name="store_id" value="{{ $storeId }}" />
                    <h3 class="font-title-md text-title-md text-on-surface mb-1">Tambah Kurir Baru</h3>
                    <p class="text-xs text-on-surface-variant mb-4">Kurir milik toko ini (kurir global hanya read-only).</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Nama Kurir *</label>
                            <input type="text" name="nama_kurir" required maxlength="100" placeholder="cth. Kurir Toko" class="raliva-input w-full text-sm" />
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Kode</label>
                            <input type="text" name="kode_kurir" maxlength="30" placeholder="opsional" class="raliva-input w-full text-sm" />
                        </div>
                    </div>
                    <button type="submit" class="mt-4 px-5 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium">Tambah Kurir</button>
                </form>
                <form method="POST" action="{{ route('admin.kurir.layanan.store') }}" class="border border-muted-border rounded-lg p-5 bg-surface-container-low/50">
                    @csrf
                    <input type="hidden" name="store_id" value="{{ $storeId }}" />
                    <h3 class="font-title-md text-title-md text-on-surface mb-1">Tambah Layanan</h3>
                    <p class="text-xs text-on-surface-variant mb-4">Layanan milik toko pada kurir global / milik toko.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Kurir *</label>
                            <select name="courier_id" required class="raliva-select w-full text-sm">
                                <option value="">— Pilih Kurir —</option>
                                @foreach ($couriers as $courier)
                                    <option value="{{ $courier->courier_id }}">{{ $courier->nama_kurir }}{{ $courier->store_id ? ' (toko)' : ' (global)' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Nama Layanan *</label>
                            <input type="text" name="nama_layanan" required maxlength="100" placeholder="cth. Same Day" class="raliva-input w-full text-sm" />
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Estimasi (hari) *</label>
                            <input type="number" name="estimasi_hari" required min="1" max="60" value="2" class="raliva-input w-full text-sm" />
                        </div>
                    </div>
                    <button type="submit" class="mt-4 px-5 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded btn-premium">Tambah Layanan</button>
                </form>
            </div>
            <form method="POST" action="{{ route('admin.kurir.sync') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="store_id" value="{{ $storeId }}" />
                @forelse ($couriers as $courier)
                    @php $setKurir = $settings[$courier->courier_id.'|0'] ?? null; @endphp
                    <div class="border border-muted-border rounded-lg p-5 bg-surface-container-low/50">
                        <div class="flex items-center gap-3 flex-wrap">
                            <label class="flex items-center gap-3 cursor-pointer min-w-0 flex-1">
                                <input type="hidden" name="pengaturan[{{ $courier->courier_id }}|0][is_aktif]" value="0" /><input type="checkbox" name="pengaturan[{{ $courier->courier_id }}|0][is_aktif]" value="1" @checked($setKurir?->is_aktif ?? true) class="w-5 h-5 accent-[#8B1E3F]" />
                                <span class="font-title-md text-title-md text-on-surface">{{ $courier->nama_kurir }}</span>
                                <span class="text-xs text-on-surface-variant">({{ $courier->services->count() }} layanan)</span>
                            </label>
                            @if ($courier->store_id)
                                <button type="button" data-modal-open="modal-edit-kurir-{{ $courier->courier_id }}" class="px-3 py-1.5 border border-gold-accent/40 text-gold-accent text-[10px] font-bold uppercase rounded hover:bg-gold-accent/10 transition-colors">Edit</button>
                                <form method="POST" action="{{ route('admin.kurir.courier.destroy', $courier->courier_id) }}" onsubmit="return confirm('Hapus kurir {{ $courier->nama_kurir }}?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-error/10 border border-error/20 text-error text-[10px] font-bold uppercase rounded hover:bg-error/20 transition-colors">Hapus</button>
                                </form>
                            @else
                                <span class="text-[10px] uppercase text-on-surface-variant border border-muted-border rounded px-2 py-1">Global</span>
                            @endif
                        </div>
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
                                        @if ($service->store_id)
                                            <div class="sm:col-span-3 flex gap-2">
                                                <button type="button" data-modal-open="modal-edit-layanan-{{ $service->shipping_service_id }}" class="px-3 py-1.5 border border-gold-accent/40 text-gold-accent text-[10px] font-bold uppercase rounded hover:bg-gold-accent/10 transition-colors">Edit Layanan</button>
                                                <form method="POST" action="{{ route('admin.kurir.layanan.destroy', $service->shipping_service_id) }}" onsubmit="return confirm('Hapus layanan {{ $service->nama_layanan }}?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-error/10 border border-error/20 text-error text-[10px] font-bold uppercase rounded hover:bg-error/20 transition-colors">Hapus</button>
                                                </form>
                                            </div>
                                        @endif
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

@foreach ($couriers->whereNotNull('store_id') as $courier)
    <div id="modal-edit-kurir-{{ $courier->courier_id }}" data-modal class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <form method="POST" action="{{ route('admin.kurir.courier.update', $courier->courier_id) }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
            @csrf @method('PUT')
            <h3 class="font-title-md text-title-md text-on-surface premium-heading">Edit Kurir</h3>
            <div class="space-y-4 mt-5">
                <div>
                    <label class="block raliva-label mb-2">Nama Kurir *</label>
                    <input type="text" name="nama_kurir" value="{{ $courier->nama_kurir }}" required maxlength="100" class="raliva-input w-full" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Kode</label>
                    <input type="text" name="kode_kurir" value="{{ $courier->kode_kurir }}" maxlength="30" class="raliva-input w-full" />
                </div>
                <div>
                    <label class="block raliva-label mb-2">Status *</label>
                    <select name="status" required class="raliva-select w-full">
                        <option value="aktif" @selected($courier->status === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected($courier->status === 'nonaktif')>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Simpan</button>
            </div>
        </form>
    </div>
    @foreach ($courier->services->whereNotNull('store_id') as $service)
        <div id="modal-edit-layanan-{{ $service->shipping_service_id }}" data-modal class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <form method="POST" action="{{ route('admin.kurir.layanan.update', $service->shipping_service_id) }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl p-6">
                @csrf @method('PUT')
                <h3 class="font-title-md text-title-md text-on-surface premium-heading">Edit Layanan</h3>
                <p class="text-xs text-on-surface-variant mt-1">{{ $courier->nama_kurir }}</p>
                <div class="space-y-4 mt-5">
                    <div>
                        <label class="block raliva-label mb-2">Nama Layanan *</label>
                        <input type="text" name="nama_layanan" value="{{ $service->nama_layanan }}" required maxlength="100" class="raliva-input w-full" />
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Estimasi (hari) *</label>
                        <input type="number" name="estimasi_hari" value="{{ $service->estimasi_hari }}" required min="1" max="60" class="raliva-input w-full" />
                    </div>
                    <div>
                        <label class="block raliva-label mb-2">Status *</label>
                        <select name="status" required class="raliva-select w-full">
                            <option value="aktif" @selected($service->status === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected($service->status === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" data-modal-close class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Simpan</button>
                </div>
            </form>
        </div>
    @endforeach
@endforeach
@endsection
