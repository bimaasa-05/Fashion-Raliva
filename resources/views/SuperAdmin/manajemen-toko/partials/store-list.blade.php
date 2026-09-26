@php
    $badgeMap = [
        \App\Models\Store::STATUS_AKTIF => ['label' => 'Aktif', 'class' => \App\Support\StatusStyle::badgeClass('aktif')],
        \App\Models\Store::STATUS_PENDING => ['label' => 'Menunggu', 'class' => \App\Support\StatusStyle::badgeClass('pending')],
        \App\Models\Store::STATUS_NONAKTIF => ['label' => 'Ditangguhkan', 'class' => \App\Support\StatusStyle::badgeClass('nonaktif')],
        \App\Models\Store::STATUS_DITOLAK => ['label' => 'Ditolak', 'class' => \App\Support\StatusStyle::badgeClass('ditolak')],
    ];

    $isSuspendedRow = fn ($s) => $s->model->status === \App\Models\Store::STATUS_NONAKTIF;
@endphp

<span data-store-total="{{ $stores->total() }}" hidden></span>

<div id="store-cards-view" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse ($stores as $item)
        @php
            $store = $item->model;
            $badge = $badgeMap[$store->status] ?? $badgeMap[\App\Models\Store::STATUS_PENDING];
            $isSuspended = $isSuspendedRow($item);
            $isPending = $store->status === \App\Models\Store::STATUS_PENDING;
        @endphp
        <article
            data-table-row
            data-search="{{ strtolower($store->nama_toko.' '.($item->owner_nama ?? '').' '.($item->location ?? '').' '.($store->deskripsi ?? '').' '.($store->nomor_telepon ?? '')) }}"
            data-id="{{ $store->store_id }}"
            data-status="{{ $store->status }}"
            data-name="{{ $store->nama_toko }}"
            data-initial="{{ $item->initial }}"
            data-owner="{{ $item->owner_nama }}"
            data-joined="{{ $item->joined }}"
            data-location="{{ $item->location }}"
            data-products="{{ $item->products_count }}"
            data-orders="{{ $item->orders_count }}"
            data-rating="{{ $item->rating ?? '--' }}"
            data-desc="{{ $store->deskripsi }}"
            data-reason="{{ $store->alasan_penolakan }}"
            data-phone="{{ $store->nomor_telepon ?? '-' }}"
            data-sampai="{{ $item->ditangguhkan_sampai ?? '' }}"
            data-dokumen='{{ $item->dokumen->map(fn ($d) => ["id" => $d->store_document_id, "jenis" => $d->jenis, "status" => $d->status, "path" => $d->path, "catatan" => $d->catatan])->toJson() }}'
            onclick="openStoreModal(this)"
            class="toko-card group bg-surface-container-lowest border border-muted-border rounded-xl overflow-hidden cursor-pointer card-premium">
            <div class="h-1 w-full {{ $isSuspended || $store->status === \App\Models\Store::STATUS_DITOLAK
                ? 'bg-gradient-to-r from-error/50 via-error/20 to-transparent'
                : 'bg-gradient-to-r from-gold-accent via-gold-accent/40 to-transparent' }}"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden ring-2 ring-gold-accent/25 flex-shrink-0 {{ $isSuspended ? 'bg-surface-container-high ring-error/25 grayscale flex items-center justify-center' : ($store->logo ? '' : 'bg-surface-container-high ring-gold-accent/25 flex items-center justify-center') }}">
                            @if ($store->logo)
                                <img class="w-full h-full object-cover" alt="Logo {{ $store->nama_toko }}" src="{{ photo_url($store->logo) }}" onerror="this.style.display='none'" />
                            @else
                                <span class="font-title-md text-on-surface-variant">{{ $item->initial }}</span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-title-md text-title-md text-on-surface truncate {{ $isSuspended ? 'line-through decoration-on-surface-variant' : '' }}">{{ $store->nama_toko }}</h3>
                            <p class="text-label-sm font-label-sm text-on-surface-variant uppercase mt-1 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $isPending ? 'bg-gold-accent animate-pulse' : ($isSuspended ? 'bg-error' : 'bg-secondary') }}"></span>{{ $item->owner_nama }}
                            </p>
                        </div>
                    </div>
                    <span data-badge class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold tracking-widest uppercase border rounded-full shrink-0 {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                </div>
                @if ($isSuspended)
                    <div class="flex items-start gap-2 mb-4 rounded-lg px-3 py-2 bg-error/5 border border-error/20 text-error">
                        <span class="material-symbols-outlined text-[16px] mt-0.5 shrink-0">{{ $item->ditangguhkan_sampai ? 'schedule' : 'block' }}</span>
                        <p class="text-[11px] font-label-sm leading-snug">
                            @if ($item->ditangguhkan_sampai)
                                Ditangguhkan sementara · aktif kembali <span class="font-bold">{{ $item->ditangguhkan_sampai }}</span>
                            @else
                                Ditangguhkan tanpa batas waktu
                            @endif
                        </p>
                    </div>
                @endif
                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md {{ $isSuspended ? 'text-on-surface-variant' : 'text-on-surface' }}">{{ $item->products_count }}</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Produk</span>
                    </div>
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md {{ $isSuspended ? 'text-on-surface-variant' : 'text-on-surface' }}">{{ $item->orders_count }}</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Pesanan</span>
                    </div>
                    <div class="bg-surface-container-low rounded-lg py-3 text-center">
                        <span class="block font-title-md {{ $isSuspended ? 'text-on-surface-variant' : 'text-on-surface' }} flex items-center justify-center gap-1">{{ $item->rating ?? '--' }} @if($item->rating)<span class="material-symbols-outlined text-[14px] filled text-secondary">star</span>@endif</span>
                        <span class="block text-[9px] font-label-sm text-on-surface-variant uppercase tracking-widest mt-0.5">Rating</span>
                    </div>
                </div>
                @if ($item->update_request)
                    <button type="button" onclick="event.stopPropagation()" data-modal-open="modal-perubahan-{{ $item->update_request->store_update_request_id }}" class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-gold-accent text-white font-label-sm text-[11px] uppercase tracking-widest hover:brightness-110 transition btn-premium shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">edit_note</span>Perubahan Data Menunggu
                    </button>
                @endif
                <div class="flex items-center justify-between pt-4 border-t border-muted-border">
                    <span class="toko-detail-hint font-label-sm text-[11px] uppercase tracking-widest text-gold-accent inline-flex items-center gap-1">Lihat Detail <span class="material-symbols-outlined text-[14px]">arrow_forward</span></span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">place</span>{{ $item->location }}</span>
                    <span class="font-label-sm text-[10px] text-on-surface-variant uppercase tracking-wider inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">call</span>{{ $item->model->nomor_telepon ?? '-' }}</span>
                </div>
            </div>
        </article>
    @empty
        <p id="toko-kosong" class="col-span-full text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada toko pada status ini.</p>
        <div id="store-table-view" class="hidden"></div>
    @endforelse
</div>

@if ($stores->isNotEmpty())
    <div id="store-table-view" class="hidden overflow-x-auto mt-6 border border-muted-border rounded-xl card-premium">
        <table class="w-full min-w-[860px] text-left bg-surface-container-lowest rounded-xl">
            <thead>
                <tr class="text-[10px] font-label-sm uppercase tracking-widest text-on-surface-variant border-b border-muted-border">
                    <th class="px-5 py-3.5 font-bold">Toko</th>
                    <th class="px-5 py-3.5 font-bold">Status</th>
                    <th class="px-5 py-3.5 font-bold">Lokasi</th>
                    <th class="px-5 py-3.5 text-center font-bold">Produk</th>
                    <th class="px-5 py-3.5 text-center font-bold">Pesanan</th>
                    <th class="px-5 py-3.5 text-center font-bold">Rating</th>
                    <th class="px-5 py-3.5 text-right font-bold">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-muted-border">
                @foreach ($stores as $item)
                    @php
                        $store = $item->model;
                        $badge = $badgeMap[$store->status] ?? $badgeMap[\App\Models\Store::STATUS_PENDING];
                        $isSuspended = $isSuspendedRow($item);
                    @endphp
                    <tr
                        data-table-row
                        data-search="{{ strtolower($store->nama_toko.' '.($item->owner_nama ?? '').' '.($item->location ?? '').' '.($store->deskripsi ?? '').' '.($store->nomor_telepon ?? '')) }}"
                        data-id="{{ $store->store_id }}"
                        data-status="{{ $store->status }}"
                        data-name="{{ $store->nama_toko }}"
                        data-initial="{{ $item->initial }}"
                        data-owner="{{ $item->owner_nama }}"
                        data-joined="{{ $item->joined }}"
                        data-location="{{ $item->location }}"
                        data-products="{{ $item->products_count }}"
                        data-orders="{{ $item->orders_count }}"
                        data-rating="{{ $item->rating ?? '--' }}"
                        data-desc="{{ $store->deskripsi }}"
                        data-reason="{{ $store->alasan_penolakan }}"
                        data-phone="{{ $store->nomor_telepon ?? '-' }}"
                        data-sampai="{{ $item->ditangguhkan_sampai ?? '' }}"
                        data-dokumen='{{ $item->dokumen->map(fn ($d) => ["id" => $d->store_document_id, "jenis" => $d->jenis, "status" => $d->status, "path" => $d->path, "catatan" => $d->catatan])->toJson() }}'
                        onclick="openStoreModal(this)"
                        class="cursor-pointer hover:bg-surface-container-low transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl overflow-hidden ring-2 ring-gold-accent/25 flex-shrink-0 {{ $isSuspended ? 'bg-surface-container-high ring-error/25 grayscale flex items-center justify-center' : ($store->logo ? '' : 'bg-surface-container-high ring-gold-accent/25 flex items-center justify-center') }}">
                                    @if ($store->logo)
                                        <img class="w-full h-full object-cover" alt="Logo {{ $store->nama_toko }}" src="{{ photo_url($store->logo) }}" onerror="this.style.display='none'" />
                                    @else
                                        <span class="font-title-md text-on-surface-variant text-sm">{{ $item->initial }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-title-md text-title-md text-on-surface truncate max-w-[240px] {{ $isSuspended ? 'line-through decoration-on-surface-variant' : '' }}">{{ $store->nama_toko }}</p>
                                    <p class="text-[10px] font-label-sm text-on-surface-variant uppercase tracking-wider mt-0.5">{{ $item->owner_nama }}</p>
                                    @if ($item->update_request)
                                        <button type="button" onclick="event.stopPropagation()" data-modal-open="modal-perubahan-{{ $item->update_request->store_update_request_id }}" class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-gold-accent text-white text-[9px] font-bold uppercase tracking-widest hover:brightness-110 transition">
                                            <span class="material-symbols-outlined text-[12px]">edit_note</span>Perubahan menunggu
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold tracking-widest uppercase border rounded-full shrink-0 {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </td>
                        <td class="px-5 py-4 text-sm text-on-surface-variant whitespace-nowrap max-w-[220px] truncate">{{ $item->location }}</td>
                        <td class="px-5 py-4 text-center font-title-md text-on-surface">{{ $item->products_count }}</td>
                        <td class="px-5 py-4 text-center font-title-md text-on-surface">{{ $item->orders_count }}</td>
                        <td class="px-5 py-4 text-center font-title-md text-on-surface flex items-center justify-center gap-1">{{ $item->rating ?? '--' }} @if($item->rating)<span class="material-symbols-outlined text-[14px] filled text-secondary">star</span>@endif</td>
                        <td class="px-5 py-4 text-right">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-widest text-gold-accent">Lihat <span class="material-symbols-outlined text-[16px]">chevron_right</span></span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<p id="toko-empty-search" class="hidden text-center text-on-surface-variant font-body-md text-sm py-12">Tidak ada toko yang cocok.</p>

@if ($stores->hasPages())
    <div class="mt-6 flex justify-center">{{ $stores->links() }}</div>
@endif

{{-- Modal perubahan data toko (dirender di dalam holder agar tetap hidup saat filter/paginasi AJAX) --}}
@foreach ($stores as $item)
    @if ($item->update_request)
        @php $pr = $item->update_request; $st = $item->model; @endphp
        <div id="modal-perubahan-{{ $pr->store_update_request_id }}" data-modal class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/50" data-modal-close></div>
            <div class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[85vh] overflow-y-auto">
                <div class="sticky top-0 bg-surface-container-lowest flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
                    <div>
                        <p class="raliva-label text-gold-accent">Perubahan Data Toko</p>
                        <h3 class="font-title-md text-title-md text-on-surface premium-heading mt-1">{{ $st->nama_toko }}</h3>
                        <p class="text-on-surface-variant text-xs mt-1">Diajukan {{ $pr->created_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                    </div>
                    <button type="button" data-modal-close class="text-on-surface-variant hover:text-on-surface transition-colors shrink-0"><span class="material-symbols-outlined">close</span></button>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    @foreach ([['Nama Toko', $st->nama_toko, $pr->nama_toko], ['Kategori', $st->kategori ?? '-', $pr->kategori ?? '-'], ['Alamat', $st->alamat, $pr->alamat], ['Telepon', $st->nomor_telepon, $pr->nomor_telepon]] as $row)
                        <div class="grid grid-cols-2 gap-3 border border-muted-border rounded-lg p-3 {{ $row[1] != $row[2] ? 'border-gold-accent/40 bg-gold-accent/5' : '' }}">
                            <div><p class="text-[10px] uppercase text-on-surface-variant">{{ $row[0] }} (lama)</p><p class="text-on-surface mt-0.5">{{ $row[1] }}</p></div>
                            <div><p class="text-[10px] uppercase text-on-surface-variant">{{ $row[0] }} (baru)</p><p class="font-bold text-on-surface mt-0.5">{{ $row[2] }}</p></div>
                        </div>
                    @endforeach
                    <div class="border border-muted-border rounded-lg p-3">
                        <p class="text-[10px] uppercase text-on-surface-variant">Deskripsi (baru)</p>
                        <p class="text-on-surface mt-0.5">{{ $pr->deskripsi ?? '-' }}</p>
                    </div>
                </div>
                <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex gap-3">
                    <form method="POST" action="{{ route('superadmin.manajemen-toko.perubahan.tolak', [$st->store_id, $pr->store_update_request_id]) }}" class="flex-1 flex gap-2">
                        @csrf
                        <input type="text" name="alasan" required minlength="3" maxlength="1000" placeholder="Alasan penolakan..." class="raliva-input flex-1 text-sm" />
                        <button type="submit" class="px-5 py-2.5 bg-error/10 border border-error/20 text-error text-xs font-semibold rounded-lg hover:bg-error hover:text-white transition-colors shrink-0">Tolak</button>
                    </form>
                    <form method="POST" action="{{ route('superadmin.manajemen-toko.perubahan.setujui', [$st->store_id, $pr->store_update_request_id]) }}" class="shrink-0">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium h-full">Setujui</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach