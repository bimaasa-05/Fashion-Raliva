@extends('layouts.produksi')

@section('title', 'Data Produksi')

@section('header-title', 'Data Produksi')
@section('header-badge', ($stats['diproses'] ?? 0) . ' Berjalan')
@section('header-subtitle', 'Pesanan yang sedang menunggu produksi atau sedang diproses. Lihat bahan, tambah bahan, dan kelola status produksi.')

@push('styles')
<style>
    .progress-track { background: var(--surface-container-high); border-radius: 999px; height: 8px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 999px; transition: width 0.6s ease-out; }
    .countdown-badge { font-variant-numeric: tabular-nums; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    {{-- Stats --}}
    <section class="grid grid-cols-2 xl:grid-cols-4 gap-gutter">
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Menunggu Produksi</span>
            <span class="raliva-figure text-[26px] text-gold-accent">{{ $stats['menunggu'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">menunggu input bahan Admin</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">pending_actions</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Sedang Diproses</span>
            <span class="raliva-figure text-[26px] text-on-surface">{{ $stats['diproses'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">tahap jahit &amp; finishing</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">precision_manufacturing</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Terlambat</span>
            <span class="raliva-figure text-[26px] text-error">{{ $stats['terlambat'] ?? 0 }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">melebihi deadline</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-error/15 fill pointer-events-none select-none" aria-hidden="true">warning</span>
        </div>
        <div class="bg-surface-container-lowest p-4 border border-muted-border rounded-lg flex flex-col gap-2 relative overflow-hidden card-premium">
            <span class="text-on-surface-variant font-label-sm text-label-sm uppercase">Total Bahan Tersedia</span>
            <span class="raliva-figure text-[26px] text-secondary">{{ ($bahanList ?? collect())->count() }}</span>
            <span class="font-label-sm text-[11px] text-on-surface-variant">bahan aktif di toko</span>
            <span class="material-symbols-outlined absolute -right-2 -bottom-4 text-[72px] text-gold-accent/15 fill pointer-events-none select-none" aria-hidden="true">science</span>
        </div>
    </section>

    {{-- Tabel --}}
    <section class="bg-surface-container-lowest border border-muted-border rounded-lg p-6 card-premium" data-table-scope>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap w-full lg:w-auto">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" placeholder="Cari nomor pesanan..." data-table-search class="raliva-search" />
                </div>
                <select data-table-filter="status-produksi" class="raliva-select">
                    <option value="">Semua Status</option>
                    <option value="menunggu_produksi">Menunggu Produksi</option>
                    <option value="diproses">Diproses</option>
                    <option value="menunggu_qc">Menunggu QC</option>
                </select>
            </div>
        </div>

        <div data-table-wrap class="overflow-x-auto">
            <table class="premium-table w-full min-w-[1000px] font-body-md text-sm">
                <thead>
                    <tr class="border-b border-muted-border text-left">
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Pesanan</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Produk &amp; Jumlah</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Bahan dari Admin</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant">Jadwal &amp; Progress</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-center">Status</th>
                        <th class="py-3 px-4 text-xs font-medium text-on-surface-variant text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        @php
                            $isDiproses = $o->status === \App\Models\Order::STATUS_DIPROSES;
                            $isMenunggu = $o->status === \App\Models\Order::STATUS_MENUNGGU_PRODUKSI;
                            $hasDates = $o->tgl_mulai_produksi && $o->tgl_berakhir_produksi;
                            $totalSeconds = 0;
                            $elapsedSeconds = 0;
                            $progressPct = 0;
                            $isTerlambat = false;
                            $daysLeft = null;
                            if ($hasDates) {
                                $start = $o->tgl_mulai_produksi->timestamp;
                                $end = $o->tgl_berakhir_produksi->timestamp;
                                $now = now()->timestamp;
                                $totalSeconds = max(1, $end - $start);
                                $elapsedSeconds = max(0, min($totalSeconds, $now - $start));
                                $progressPct = min(100, round(($elapsedSeconds / $totalSeconds) * 100));
                                $isTerlambat = $now > $end;
                                $daysLeft = (int) round(($end - $now) / 86400);
                            }
                            $accepted = (bool) $o->produksi_dimulai_pada;
                            $rejectedNote = $o->produksi_catatan_tolak;
                        @endphp
                        <tr data-table-row data-status-produksi="{{ $o->status }}" class="border-b border-muted-border last:border-0 align-top">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-on-surface">{{ $o->nomor_order }}</p>
                                <p class="text-xs text-on-surface mt-0.5">{{ $o->checkout?->nama_penerima ?? $o->checkout?->user?->nama_lengkap ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $o->created_at?->translatedFormat('d M Y') ?? '-' }}</p>
                                @if ($rejectedNote)
                                    <p class="text-xs text-error mt-1" title="{{ $rejectedNote }}">⚠ Ditolak: {{ \Illuminate\Support\Str::limit($rejectedNote, 30) }}</p>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @foreach ($o->items as $item)
                                    <p class="text-on-surface">{{ $item->nama_produk_snapshot }} <span class="text-on-surface-variant">× {{ $item->quantity }}</span></p>
                                @endforeach
                            </td>
                            <td class="py-3.5 px-4 max-w-[220px]">
                                @if ($o->bahanList->isNotEmpty())
                                    @php
                                        $bahanAdmin = $o->bahanList->reject(fn ($b) => $b->isDariProduksi());
                                        $bahanTambahan = $o->bahanList->filter(fn ($b) => $b->isDariProduksi());
                                    @endphp
                                    @if ($bahanAdmin->isNotEmpty())
                                        <p class="text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Bahan dari Admin ({{ $bahanAdmin->count() }})</p>
                                        @foreach ($bahanAdmin as $bahan)
                                            <p class="text-xs text-on-surface-variant">{{ $bahan->nama_bahan }}: {{ $bahan->jumlah }} {{ $bahan->satuan }}</p>
                                        @endforeach
                                    @endif
                                    @if ($bahanTambahan->isNotEmpty())
                                        <p class="text-[10px] uppercase tracking-wider text-secondary mt-1.5 mb-1">Tambahan Produksi ({{ $bahanTambahan->count() }})</p>
                                        @foreach ($bahanTambahan as $bahan)
                                            <p class="text-xs text-secondary">{{ $bahan->nama_bahan }}: {{ $bahan->jumlah }} {{ $bahan->satuan }} <span>(Produksi)</span></p>
                                        @endforeach
                                    @endif
                                @else
                                    <span class="text-on-surface-variant text-xs">Belum ada bahan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($hasDates)
                                    <p class="text-xs text-on-surface-variant">{{ $o->tgl_mulai_produksi?->translatedFormat('d M H:i') }} → {{ $o->tgl_berakhir_produksi?->translatedFormat('d M H:i') }}</p>
                                    <div class="progress-track mt-1.5">
                                        <div class="progress-bar-fill {{ $isTerlambat ? 'bg-error' : ($progressPct >= 100 ? 'bg-secondary' : 'bg-gold-accent') }}" style="width: {{ $progressPct }}%"></div>
                                    </div>
                                    @if ($progressPct >= 100)
                                        <p class="text-xs mt-1 countdown-badge text-on-surface-variant">Selesai tepat waktu</p>
                                    @else
                                        <p class="text-xs mt-1 countdown-badge {{ $isTerlambat ? 'text-error font-bold' : 'text-on-surface-variant' }}"
                                           data-countdown-deadline="{{ $o->tgl_berakhir_produksi->timestamp }}"
                                           data-countdown-progress="{{ $progressPct }}">
                                            Memuat...
                                        </p>
                                    @endif
                                @else
                                    <span class="text-on-surface-variant text-xs">Belum dijadwalkan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if ($isMenunggu)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-bold uppercase border border-amber-500/30">Menunggu</span>
                                @elseif ($isDiproses)
                                    @if ($accepted)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-bold uppercase border border-amber-500/30">Diproses</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-gold-accent/10 text-gold-accent text-[10px] font-bold uppercase border border-gold-accent/30">Menunggu Accept</span>
                                    @endif
                                @elseif ($o->status === \App\Models\Order::STATUS_MENUNGGU_QC)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-bold uppercase border border-amber-500/30">Menunggu QC</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button type="button" onclick="openDetailProduksi('{{ $o->order_id }}')" title="Detail produksi" class="inline-flex items-center justify-center px-2.5 py-2 border border-muted-border text-on-surface-variant rounded hover:border-gold-accent hover:text-gold-accent transition-colors mr-1 align-top">
                                    <span class="material-symbols-outlined text-[16px]">timeline</span>
                                </button>
                                @if ($isDiproses)
                                    @if (! $accepted)
                                        <div class="flex gap-1 justify-end">
                                            <form method="POST" action="{{ route('produksi.data-produksi.accept', $o) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1.5 bg-secondary text-on-secondary text-[10px] font-bold uppercase rounded hover:opacity-90 transition-opacity">Accept</button>
                                            </form>
                                            <button type="button" onclick="document.getElementById('modal-tolak-{{ $o->order_id }}').classList.remove('hidden')" class="px-2.5 py-1.5 bg-error/10 border border-error/20 text-error text-[10px] font-bold uppercase rounded hover:bg-error/20 transition-colors">Tolak</button>
                                        </div>
                                    @else
                                        <div class="flex gap-1 justify-end">
                                            <button type="button" onclick="openModalBahan('{{ $o->order_id }}')" class="px-2.5 py-1.5 border border-gold-accent/40 text-gold-accent text-[10px] font-bold uppercase rounded hover:bg-gold-accent/10 transition-colors">+ Bahan</button>
                                            <button type="button" onclick="openModalSelesai('{{ $o->order_id }}')" class="px-2.5 py-1.5 bg-deep-onyx text-on-primary text-[10px] font-bold uppercase rounded hover:opacity-90 transition-opacity">Selesai</button>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-on-surface-variant text-xs">Menunggu Admin proses</span>
                                @endif
                            </td>
                        </tr>

                        @empty
                            <tr><td colspan="6" class="py-12 text-center text-on-surface-variant">Tidak ada pesanan dalam produksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $orders->withQueryString()->links() }}
        </section>
    </div>
</div>

{{-- Modals — dirender di luar table --}}
@foreach ($orders as $o)
    @php
        $isDiproses = $o->status === \App\Models\Order::STATUS_DIPROSES;
        $accepted = (bool) $o->produksi_dimulai_pada;
    @endphp

    {{-- Modal Tolak --}}
    @if ($isDiproses && ! $accepted)
    <div id="modal-tolak-{{ $o->order_id }}" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeModalTolak('{{ $o->order_id }}')"></div>
        <form method="POST" action="{{ route('produksi.data-produksi.reject', $o) }}" class="relative mx-auto w-full max-w-md bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl p-6">
            @csrf
            <h3 class="font-title-md text-title-md text-on-surface mb-2">Tolak Produksi</h3>
            <p class="text-on-surface-variant text-sm mb-3">Pesanan <span class="font-mono font-bold text-on-surface">{{ $o->nomor_order }}</span></p>
            <textarea name="catatan" required minlength="10" maxlength="500" rows="3" class="raliva-textarea" placeholder="Alasan penolakan... (minimal 10 karakter)"></textarea>
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="closeModalTolak('{{ $o->order_id }}')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-error text-on-error text-xs font-semibold rounded-lg">Tolak</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Modal Tambah Bahan --}}
    @if ($isDiproses && $accepted)
    <div id="modal-bahan-{{ $o->order_id }}" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeModalBahan('{{ $o->order_id }}')"></div>
        <form method="POST" action="{{ route('produksi.data-produksi.bahan', $o) }}" class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
            @csrf
            <div class="sticky top-0 bg-surface-container-lowest border-b border-muted-border px-6 py-4 flex justify-between items-center">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface">Tambah Bahan Produksi</h3>
                    <p class="text-xs text-on-surface-variant">{{ $o->nomor_order }}</p>
                </div>
                <button type="button" onclick="closeModalBahan('{{ $o->order_id }}')" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-3">
                <p class="text-xs text-on-surface-variant">Tambah bahan yang belum diinput Admin. Pilih dari katalog atau ketik manual.</p>
                <div id="bahan-container-produksi-{{ $o->order_id }}" class="space-y-3"></div>
                <button type="button" onclick="addBahanProduksiRow('{{ $o->order_id }}')" class="w-full py-2.5 border border-dashed border-outline-variant rounded-lg text-xs font-semibold text-on-surface-variant hover:border-gold-accent hover:text-gold-accent transition-colors flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">add</span> Tambah Bahan
                </button>
            </div>
            <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex gap-3">
                <button type="button" onclick="closeModalBahan('{{ $o->order_id }}')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Simpan Bahan</button>
            </div>
        </form>
    </div>
    @endif

    {{-- Modal Selesai Produksi --}}
    @if ($isDiproses && $accepted)
    <div id="modal-selesai-{{ $o->order_id }}" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" onclick="closeModalSelesai('{{ $o->order_id }}')"></div>
        <form method="POST" action="{{ route('produksi.data-produksi.status', $o) }}" class="relative mx-auto w-full max-w-lg bg-surface-container-lowest border border-muted-border rounded-lg shadow-xl max-h-[85vh] overflow-y-auto">
            @csrf
            <div class="sticky top-0 bg-surface-container-lowest border-b border-muted-border px-6 py-4 flex justify-between items-center">
                <div>
                    <h3 class="font-title-md text-title-md text-on-surface">Selesai Produksi</h3>
                    <p class="text-xs text-on-surface-variant">{{ $o->nomor_order }}</p>
                </div>
                <button type="button" onclick="closeModalSelesai('{{ $o->order_id }}')" class="text-on-surface-variant"><span class="material-symbols-outlined">close</span></button>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-xs text-on-surface-variant">Input hasil produksi. Pesanan akan masuk ke tahap QC.</p>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Jumlah Berhasil *</label>
                    <input type="number" name="jumlah_berhasil" required min="0" class="raliva-input w-full" placeholder="0" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Jumlah Gagal</label>
                    <input type="number" name="jumlah_gagal" min="0" value="0" class="raliva-input w-full" />
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-on-surface-variant mb-1">Catatan (opsional)</label>
                    <textarea name="catatan" rows="2" class="raliva-textarea" placeholder="Catatan produksi..."></textarea>
                </div>
            </div>
            <div class="sticky bottom-0 bg-surface-container-lowest border-t border-muted-border p-4 flex gap-3">
                <button type="button" onclick="closeModalSelesai('{{ $o->order_id }}')" class="flex-1 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-deep-onyx text-on-primary text-xs font-semibold rounded-lg btn-premium">Selesai Produksi</button>
            </div>
        </form>
    </div>
    @endif
@endforeach

{{-- Modal Detail Produksi (timeline) per order --}}
@foreach ($orders as $o)
    @include('partials.modal-produksi-detail', ['o' => $o])
@endforeach

@php
    $bahanJson = ($bahanList ?? collect())->map(function ($b) {
        return ['bahan_id' => $b->bahan_id, 'nama_bahan' => $b->nama_bahan, 'satuan' => $b->satuan, 'stok' => $b->stok];
    })->toJson();
@endphp

@push('scripts')
<script>
    const bahanProduksiData = {!! $bahanJson !!};
    let bahanProduksiIdx = {};

    function openModalBahan(orderId) {
        const modal = document.getElementById('modal-bahan-' + orderId);
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        const container = document.getElementById('bahan-container-produksi-' + orderId);
        if (container && container.children.length === 0) {
            addBahanProduksiRow(orderId);
        }
    }

    function closeModalBahan(orderId) {
        const modal = document.getElementById('modal-bahan-' + orderId);
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function closeModalTolak(orderId) {
        const modal = document.getElementById('modal-tolak-' + orderId);
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openModalSelesai(orderId) {
        const modal = document.getElementById('modal-selesai-' + orderId);
        if (modal) modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModalSelesai(orderId) {
        const modal = document.getElementById('modal-selesai-' + orderId);
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function addBahanProduksiRow(orderId) {
        if (!bahanProduksiIdx[orderId]) bahanProduksiIdx[orderId] = 0;
        const container = document.getElementById('bahan-container-produksi-' + orderId);
        if (!container) return;
        const idx = bahanProduksiIdx[orderId]++;
        const row = document.createElement('div');
        row.setAttribute('data-bahan-row', '');
        row.className = 'bahan-row border border-muted-border rounded-lg px-4 py-3 bg-surface-container-low space-y-2.5';
        row.innerHTML = `
            <div class="flex items-start justify-between gap-3">
                <select name="bahan[${idx}][bahan_id]" class="raliva-select flex-1 min-w-0" onchange="onBahanSelectChange(this)">
                    <option value="">— Pilih bahan / ketik manual —</option>
                    ${bahanProduksiData.map(b => `<option value="${b.bahan_id}" data-nama="${b.nama_bahan}" data-satuan="${b.satuan}">${b.nama_bahan} (Stok: ${b.stok} ${b.satuan})</option>`).join('')}
                </select>
                <button type="button" onclick="removeBahanRow(this)" class="shrink-0 px-2.5 py-2.5 rounded-lg border border-error/20 text-error hover:bg-error/10">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
            <div class="grid grid-cols-[1fr_110px] gap-3">
                <input type="text" name="bahan[${idx}][nama_bahan]" required class="raliva-input w-full" placeholder="Nama bahan" />
                <input type="number" name="bahan[${idx}][jumlah]" required min="0.01" step="0.01" class="raliva-input w-full py-2 text-center" placeholder="Jumlah" />
            </div>
            <div class="grid grid-cols-[110px_1fr] gap-3">
                <select name="bahan[${idx}][satuan]" required class="raliva-select w-full">
                    <option value="">— Satuan —</option>
                    @foreach (\App\Models\ProductionOrderBahan::SATUAN as $st)
                        <option value="{{ $st }}">{{ $st }}</option>
                    @endforeach
                </select>
                <input type="text" name="bahan[${idx}][catatan]" class="raliva-input w-full" placeholder="Catatan (opsional)" />
            </div>
        `;
        container.appendChild(row);
    }

    function removeBahanRow(btn) {
        const row = btn.closest('[data-bahan-row]');
        if (row) row.remove();
    }

    function onBahanSelectChange(select) {
        const opt = select.options[select.selectedIndex];
        const row = select.closest('[data-bahan-row]');
        if (!row) return;
        if (opt.value) {
            row.querySelector('input[name*="[nama_bahan]"]').value = opt.dataset.nama;
            const sat = row.querySelector('[name*="[satuan]"]');
            if (sat && sat.querySelector(`option[value="${opt.dataset.satuan}"]`)) sat.value = opt.dataset.satuan;
        }
    }

    // Table search + filter
    document.querySelectorAll('[data-table-search]').forEach(input => {
        input.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            const filterVal = document.querySelector('[data-table-filter]')?.value || '';
            document.querySelectorAll('[data-table-row]').forEach(row => {
                const text = row.textContent.toLowerCase();
                const status = row.dataset.statusProduksi || '';
                const matchSearch = text.includes(term);
                const matchFilter = !filterVal || status === filterVal;
                row.style.display = (matchSearch && matchFilter) ? '' : 'none';
            });
        });
    });
    document.querySelectorAll('[data-table-filter]').forEach(select => {
        select.addEventListener('change', function() {
            const searchInput = document.querySelector('[data-table-search]');
            if (searchInput) searchInput.dispatchEvent(new Event('input'));
        });
    });
    // === COUNTDOWN TIMER REAL-TIME ===
    function formatCountdown(seconds) {
        const abs = Math.abs(seconds);
        const d = Math.floor(abs / 86400);
        const h = Math.floor((abs % 86400) / 3600);
        const m = Math.floor((abs % 3600) / 60);
        const s = abs % 60;
        let parts = [];
        if (d > 0) parts.push(d + 'j');
        parts.push(h + 'j');
        parts.push(m + 'm');
        parts.push(s + 'd');
        return parts.join(' ');
    }

    function updateCountdowns() {
        document.querySelectorAll('[data-countdown-deadline]').forEach(el => {
            const deadline = parseInt(el.dataset.countdownDeadline) * 1000;
            const progress = el.dataset.countdownProgress || '0';
            const now = Date.now();
            const diff = Math.floor((deadline - now) / 1000);
            if (diff < 0) {
                el.textContent = 'Terlambat ' + formatCountdown(diff);
                el.classList.add('text-error', 'font-bold');
                el.classList.remove('text-on-surface-variant');
            } else {
                el.textContent = 'Sisa ' + formatCountdown(diff) + ' (' + progress + '%)';
            }
        });
    }
    setInterval(updateCountdowns, 1000);
    updateCountdowns();
</script>
@endpush
@endsection
