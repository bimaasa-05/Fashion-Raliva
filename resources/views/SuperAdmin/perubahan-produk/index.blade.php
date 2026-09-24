@extends('layouts.superadmin')

@section('title', 'Perubahan Produk')

@section('header-title', 'Perubahan Produk')
@section('header-badge', 'Review')

@section('header-subtitle', 'Bandingkan pengajuan Admin sebelum menyetujui atau menolak perubahan produk.')

@section('content')
@include('partials.flash-toast')

@php
    $tabs = [
        \App\Models\ProductUpdateRequest::STATUS_PENDING => 'Menunggu',
        \App\Models\ProductUpdateRequest::STATUS_DISETUJUI => 'Disetujui',
        \App\Models\ProductUpdateRequest::STATUS_DITOLAK => 'Ditolak',
    ];
@endphp

<div class="space-y-section-gap">
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
        <div class="flex flex-wrap gap-2">
            @foreach ($tabs as $key => $label)
                <a href="{{ route('superadmin.perubahan-produk', ['status' => $key]) }}" class="px-4 py-2 rounded-lg text-xs font-semibold uppercase tracking-widest border transition-colors {{ $activeStatus === $key ? 'bg-deep-onyx text-on-primary border-deep-onyx' : 'border-muted-border text-on-surface-variant hover:border-gold-accent hover:text-on-surface' }}">
                    {{ $label }} ({{ $stats[$key] ?? 0 }})
                </a>
            @endforeach
        </div>
    </section>

    @forelse ($requests as $item)
        @php $diff = $item->getAttribute('perbandingan'); @endphp
        <article class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium">
            <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-3">
                <div>
                    <p class="text-xs text-on-surface-variant uppercase tracking-widest">{{ $item->product?->store?->nama_toko ?? '-' }} • {{ $item->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $item->product?->nama_produk ?? 'Produk dihapus' }}</h2>
                    <p class="text-xs text-on-surface-variant mt-1">Diajukan oleh {{ $item->requester?->nama_lengkap ?? '-' }} • Produk #{{ $item->product_id }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase border {{ $item->status === 'pending' ? 'bg-gold-accent/10 text-gold-accent border-gold-accent/30' : ($item->status === 'disetujui' ? 'bg-success/10 text-success border-success/20' : 'bg-error/10 text-error border-error/20') }}">{{ $tabs[$item->status] ?? $item->status }}</span>
            </div>

            <details class="mt-4 border border-muted-border rounded-lg bg-surface-container-low" open>
                <summary class="cursor-pointer px-4 py-3 text-xs font-bold uppercase tracking-widest text-on-surface">Perbandingan data utama</summary>
                <div class="overflow-x-auto border-t border-muted-border">
                    <table class="w-full min-w-[640px] text-sm">
                        <thead>
                            <tr class="text-left text-[10px] uppercase tracking-widest text-on-surface-variant">
                                <th class="px-4 py-2">Field</th>
                                <th class="px-4 py-2">Sebelum</th>
                                <th class="px-4 py-2">Sesudah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($diff['fields'] as $field)
                                <tr class="border-t border-muted-border {{ $field['lama'] !== $field['baru'] ? 'bg-gold-accent/5' : '' }}">
                                    <td class="px-4 py-2 font-semibold text-on-surface">{{ $field['label'] }}</td>
                                    <td class="px-4 py-2 text-on-surface-variant">{{ \Illuminate\Support\Str::limit((string) $field['lama'], 160) }}</td>
                                    <td class="px-4 py-2 text-on-surface">{{ \Illuminate\Support\Str::limit((string) $field['baru'], 160) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>

            <details class="mt-4 border border-muted-border rounded-lg bg-surface-container-low" open>
                <summary class="cursor-pointer px-4 py-3 text-xs font-bold uppercase tracking-widest text-on-surface">Perbandingan resep dan modal</summary>
                <div class="overflow-x-auto border-t border-muted-border">
                    @php
                        $resepLama = collect($diff['recipes']['before']['rows'] ?? [])->mapWithKeys(fn ($row) => [($row['material_id'] ?? '').'|'.trim($row['nama_bahan'] ?? '').'|'.trim($row['satuan'] ?? '') => $row]);
                        $resepBaru = collect($diff['recipes']['after']['rows'] ?? [])->mapWithKeys(fn ($row) => [($row['material_id'] ?? '').'|'.trim($row['nama_bahan'] ?? '').'|'.trim($row['satuan'] ?? '') => $row]);
                        $resepKeys = $resepLama->keys()->merge($resepBaru->keys())->unique()->values();
                    @endphp
                    @if ($resepKeys->isEmpty())
                        <p class="px-4 py-3 text-xs text-on-surface-variant">Tidak ada resep yang diusulkan.</p>
                    @else
                        <table class="w-full min-w-[720px] text-sm">
                            <thead>
                                <tr class="text-left text-[10px] uppercase tracking-widest text-on-surface-variant">
                                    <th class="px-4 py-2">Bahan</th>
                                    <th class="px-4 py-2">Sebelum</th>
                                    <th class="px-4 py-2">Sesudah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resepKeys as $resepKey)
                                    @php
                                        $resepSebelum = $resepLama[$resepKey] ?? null;
                                        $resepSesudah = $resepBaru[$resepKey] ?? null;
                                        $resepBerubah = json_encode($resepSebelum) !== json_encode($resepSesudah);
                                    @endphp
                                    <tr class="border-t border-muted-border {{ $resepBerubah ? 'bg-gold-accent/5' : '' }}">
                                        <td class="px-4 py-2 font-semibold text-on-surface">{{ $resepSesudah['nama_bahan'] ?? $resepSebelum['nama_bahan'] }} • {{ $resepSesudah['satuan'] ?? $resepSebelum['satuan'] }}</td>
                                        <td class="px-4 py-2 text-on-surface-variant">{{ $resepSebelum ? number_format($resepSebelum['jumlah_per_unit'], 3, ',', '.').' × Rp '.number_format($resepSebelum['biaya_per_unit'], 2, ',', '.') : 'Tidak ada' }}</td>
                                        <td class="px-4 py-2 text-on-surface">{{ $resepSesudah ? number_format($resepSesudah['jumlah_per_unit'], 3, ',', '.').' × Rp '.number_format($resepSesudah['biaya_per_unit'], 2, ',', '.') : 'Tidak ada' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </details>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                <div class="border border-muted-border rounded-lg p-4">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface mb-3">Foto sebelum ({{ count($diff['beforeImages']) }})</h3>
                    <div class="grid grid-cols-3 gap-2">
                        @forelse ($diff['beforeImages'] as $foto)
                            <img src="{{ photo_url($foto) }}" alt="Foto lama" class="w-full aspect-[3/4] object-cover rounded-lg border border-muted-border" loading="lazy" />
                        @empty
                            <p class="text-xs text-on-surface-variant">Tidak ada foto lama.</p>
                        @endforelse
                    </div>
                </div>
                <div class="border border-muted-border rounded-lg p-4">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface mb-3">Foto sesudah ({{ count($diff['afterImages']) }})</h3>
                    <div class="grid grid-cols-3 gap-2">
                        @forelse ($diff['afterImages'] as $foto)
                            <img src="{{ photo_url($foto) }}" alt="Foto usulan" class="w-full aspect-[3/4] object-cover rounded-lg border border-gold-accent/40" loading="lazy" />
                        @empty
                            <p class="text-xs text-on-surface-variant">Semua foto dihapus atau tidak ada foto baru.</p>
                        @endforelse
                    </div>
                    @if (count($diff['removed'] ?? []) > 0)
                        <p class="text-xs text-error mt-3">{{ count($diff['removed']) }} foto lama diusulkan untuk dihapus.</p>
                    @endif
                </div>
            </div>

            <details class="mt-4 border border-muted-border rounded-lg bg-surface-container-low">
                <summary class="cursor-pointer px-4 py-3 text-xs font-bold uppercase tracking-widest text-on-surface">Perbandingan varian dan stok</summary>
                <div class="overflow-x-auto border-t border-muted-border">
                    @php $keys = collect($diff['beforeVariants'])->keys()->merge(collect($diff['afterVariants'])->keys())->unique()->values(); @endphp
                    @if ($keys->isEmpty())
                        <p class="px-4 py-3 text-xs text-on-surface-variant">Tidak ada perubahan varian yang diusulkan.</p>
                    @else
                        <table class="w-full min-w-[720px] text-sm">
                            <thead>
                                <tr class="text-left text-[10px] uppercase tracking-widest text-on-surface-variant">
                                    <th class="px-4 py-2">Varian</th>
                                    <th class="px-4 py-2">Sebelum</th>
                                    <th class="px-4 py-2">Sesudah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($keys as $key)
                                    @php
                                        $lama = $diff['beforeVariants'][$key] ?? null;
                                        $baru = $diff['afterVariants'][$key] ?? null;
                                        $berubah = json_encode($lama) !== json_encode($baru);
                                        $bagianKunci = explode('|', $key);
                                        $labelVarian = trim($bagianKunci[0] ?? '').(trim($bagianKunci[1] ?? '') !== '' ? ' • '.trim($bagianKunci[1]) : ' • Tanpa warna');
                                    @endphp
                                    <tr class="border-t border-muted-border {{ $berubah ? 'bg-gold-accent/5' : '' }}">
                                        <td class="px-4 py-2 font-semibold text-on-surface">{{ $labelVarian }}</td>
                                        <td class="px-4 py-2 text-on-surface-variant">{{ $lama ? ((($lama['warna'] ?? null) ? ($lama['warna_hex'] ?? '').' • ' : 'Tanpa warna • ').'Stok '.($lama['stok'] ?? 0).' • Min '.($lama['stok_minimum'] ?? 0)) : 'Tidak ada' }}</td>
                                        <td class="px-4 py-2 text-on-surface">{{ $baru ? ((($baru['warna'] ?? null) ? ($baru['warna_hex'] ?? '').' • ' : 'Tanpa warna • ').'Stok '.($baru['stok'] ?? 0).' • Min '.($baru['stok_minimum'] ?? 0)) : 'Tidak ada' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </details>

            @if ($item->status === 'pending')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mt-4">
                    <form method="POST" action="{{ route('superadmin.perubahan-produk.setujui', [$item->product_id, $item->product_update_request_id]) }}" class="flex items-center justify-between gap-3 border border-success/25 bg-success/5 rounded-lg px-4 py-3">
                        @csrf
                        <p class="text-xs text-on-surface">Setujui dan berlakukan semua perubahan di atas.</p>
                        <button type="submit" class="px-4 py-2 bg-deep-onyx text-on-primary text-xs font-bold uppercase tracking-widest rounded btn-premium">Setujui</button>
                    </form>
                    <form method="POST" action="{{ route('superadmin.perubahan-produk.tolak', [$item->product_id, $item->product_update_request_id]) }}" class="border border-error/25 bg-error/5 rounded-lg px-4 py-3 space-y-2">
                        @csrf
                        <label class="block text-xs font-bold uppercase tracking-widest text-error" for="alasan-{{ $item->product_update_request_id }}">Tolak beserta alasan</label>
                        <textarea id="alasan-{{ $item->product_update_request_id }}" name="alasan" required minlength="10" maxlength="1000" rows="2" class="raliva-textarea w-full text-sm" placeholder="Minimal 10 karakter"></textarea>
                        <button type="submit" class="w-full px-4 py-2 border border-error/40 text-error text-xs font-bold uppercase tracking-widest rounded hover:bg-error/10">Tolak</button>
                    </form>
                </div>
            @else
                <p class="text-xs text-on-surface-variant mt-4">Diputuskan oleh {{ $item->reviewer?->nama_lengkap ?? '-' }} pada {{ $item->updated_at?->translatedFormat('d M Y H:i') ?? '-' }}.{{ $item->review_note ? ' Alasan: '.$item->review_note : '' }}</p>
            @endif
        </article>
    @empty
        <p class="text-center text-on-surface-variant text-sm py-12">Belum ada pengajuan pada status ini.</p>
    @endforelse
</div>
@endsection
