@extends('layouts.superadmin')

@section('title', 'Pengiriman')

@section('header-title', 'Pengiriman')
@section('header-badge', 'Kelola')
@section('header-subtitle', 'Pantau dan ubah status pengiriman dari seluruh toko di platform.')

@push('styles')
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .filter-chip { transition: all 0.2s ease; }
    .filter-chip:hover { border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; transform: translateY(-1px); }
    .filter-chip.active { background-color: rgba(201, 162, 77, 0.15); border-color: rgba(201, 162, 77, 0.5); color: #C9A24D; }
</style>
@endpush

@section('content')
@include('partials.flash-toast')

<div class="space-y-section-gap">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-surface-container-lowest border border-muted-border rounded-xl card-premium hero-glow">
        <span class="material-symbols-outlined fill absolute -right-6 -bottom-10 text-[220px] text-gold-accent/[0.06] pointer-events-none select-none" aria-hidden="true">local_shipping</span>
        <div class="relative z-10 p-8 md:p-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                            {{ $stats['semua'] }} Total
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wider border border-outline-variant">
                            {{ $stats['pending'] }} Pending
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-info/10 text-info text-[10px] font-bold uppercase tracking-wider border border-info/20">
                            {{ $stats['diproses'] }} Diproses
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-container/20 text-secondary text-[10px] font-bold uppercase tracking-wider border border-secondary/20">
                            {{ $stats['dikirim'] }} Dikirim
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-success/10 text-success text-[10px] font-bold uppercase tracking-wider border border-success/20">
                            {{ $stats['diterima'] }} Diterima
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-error/10 text-error text-[10px] font-bold uppercase tracking-wider border border-error/20">
                            {{ $stats['gagal'] }} Gagal
                        </span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-lg">Pantau dan kelola pengiriman dari seluruh toko. SA dapat mengubah status pengiriman untuk keperluan darurat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Toolbar -->
    <section class="rise rise-d1">
        <div class="bg-surface-container-lowest border border-muted-border rounded-xl p-6 card-premium">
            <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
                <div>
                    <h2 class="font-title-md text-title-md text-on-surface premium-heading">Daftar Pengiriman</h2>
                    <p class="text-xs text-on-surface-variant mt-1">Semua pengiriman dari seluruh toko di platform.</p>
                </div>
                <button type="button" data-filter-toggle data-filter-target="#pengiriman-filter" class="md:hidden inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">
                    <span class="material-symbols-outlined text-[18px]">tune</span>
                    Filter
                    <span class="material-symbols-outlined text-[18px] transition-transform duration-300" data-filter-chevron>expand_more</span>
                </button>
            </div>

            <!-- Filters -->
            <div id="pengiriman-filter" data-filter-panel class="hidden md:block flex flex-col lg:flex-row lg:items-center gap-3 mb-6">
                <div class="relative flex-1 min-w-[220px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
                    <input type="text" id="searchInput" placeholder="Cari nomor pesanan, toko, kurir, atau resi..." class="w-full bg-transparent border border-muted-border rounded-lg pl-10 pr-4 py-2.5 font-body-md text-sm focus:outline-none focus:border-gold-accent focus:ring-1 focus:ring-gold-accent transition-colors" oninput="applyFilter()" />
                </div>
                <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                    <select id="filterStatus" class="raliva-select lg:w-40" onchange="applyFilter()">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="diproses">Diproses</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="diterima">Diterima</option>
                        <option value="gagal">Gagal</option>
                    </select>
                    <button type="button" onclick="resetFilter()" class="py-2.5 px-4 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors whitespace-nowrap">Reset</button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full min-w-[1000px] font-body-md text-sm">
                    <thead>
                        <tr class="border-b border-muted-border bg-surface-container-low text-on-surface-variant font-label-sm text-label-sm uppercase">
                            <th class="p-4 text-center w-12">No</th>
                            <th class="p-4 text-left">ID Pesanan</th>
                            <th class="p-4 text-left">Toko</th>
                            <th class="p-4 text-left">Kurir</th>
                            <th class="p-4 text-left">No. Resi</th>
                            <th class="p-4 text-left">Ongkir</th>
                            <th class="p-4 text-left">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @forelse($shipments as $s)
                            @php
                                $pelanggan = $s->order?->checkout?->user;
                            @endphp
                            <tr class="border-b border-muted-border last:border-0"
                                data-status="{{ $s->status }}"
                                data-search="{{ strtolower(($s->order->nomor_order ?? '').' '.($s->order->store->nama_toko ?? '').' '.($s->courier->nama_kurir ?? '').' '.($s->nomor_resi ?? '')) }}">
                                <td class="py-3.5 px-4 text-on-surface-variant">{{ $loop->iteration }}</td>
                                <td class="py-3.5 px-4 font-mono text-on-surface">{{ $s->order->nomor_order ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-on-surface">{{ $s->order->store->nama_toko ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-on-surface">{{ $s->courier->nama_kurir ?? '-' }}</td>
                                <td class="py-3.5 px-4 font-mono text-on-surface-variant text-xs">{{ $s->nomor_resi ?? '-' }}</td>
                                <td class="py-3.5 px-4 text-right text-on-surface">Rp {{ number_format((float) $s->ongkir, 0, ',', '.') }}</td>
                                <td class="py-3.5 px-4">
                                    <form method="POST" action="{{ route('superadmin.pengiriman.status', $s->shipment_id) }}" class="inline-flex">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" onchange="if(confirm('Ubah status pengiriman ini?')) this.form.submit(); else this.value='{{ $s->status }}';" class="bg-transparent border border-muted-border rounded-lg px-2 py-1 text-[10px] font-bold uppercase focus:outline-none focus:border-gold-accent cursor-pointer {{ match($s->status) { 'diterima' => 'text-success border-success/30', 'dikirim' => 'text-secondary border-secondary/30', 'diproses' => 'text-info border-info/30', 'gagal' => 'text-error border-error/30', default => 'text-on-surface-variant border-outline-variant', } }}">
                                            <option value="pending" {{ $s->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="diproses" {{ $s->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                            <option value="dikirim" {{ $s->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                            <option value="diterima" {{ $s->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                                            <option value="gagal" {{ $s->status === 'gagal' ? 'selected' : '' }}>Gagal</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" onclick="openDetail(this)" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors"
                                        data-order="{{ $s->order->nomor_order ?? '-' }}"
                                        data-toko="{{ $s->order->store->nama_toko ?? '-' }}"
                                        data-kurir="{{ $s->courier->nama_kurir ?? '-' }}"
                                        data-resi="{{ $s->nomor_resi ?? '-' }}"
                                        data-ongkir="Rp {{ number_format((float) $s->ongkir, 0, ',', '.') }}"
                                        data-estimasi="{{ $s->estimasi_tiba ? \Carbon\Carbon::parse($s->estimasi_tiba)->locale('id')->translatedFormat('d M Y') : '-' }}"
                                        data-dikirim="{{ $s->dikirim_pada ? \Carbon\Carbon::parse($s->dikirim_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}"
                                        data-diterima="{{ $s->diterima_pada ? \Carbon\Carbon::parse($s->diterima_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}"
                                        data-status="{{ ucfirst($s->status) }}"
                                        data-pelanggan="{{ $pelanggan->nama_lengkap ?? '-' }}">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-on-surface-variant">Belum ada data pengiriman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile: kartu per pengiriman -->
            <div id="card-grid" class="md:hidden grid grid-cols-1 gap-gutter">
                @forelse($shipments as $s)
                    @php
                        $pelanggan = $s->order?->checkout?->user;
                    @endphp
                    <article data-status="{{ $s->status }}" data-search="{{ strtolower(($s->order->nomor_order ?? '').' '.($s->order->store->nama_toko ?? '').' '.($s->courier->nama_kurir ?? '').' '.($s->nomor_resi ?? '')) }}" class="bg-surface-container-lowest border border-muted-border rounded-lg p-4 card-premium">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="min-w-0">
                                <p class="font-mono font-bold text-on-surface leading-tight">{{ $s->order->nomor_order ?? '-' }}</p>
                                <p class="text-xs text-on-surface-variant mt-0.5">{{ $s->order->store->nama_toko ?? '-' }}</p>
                            </div>
                            <form method="POST" action="{{ route('superadmin.pengiriman.status', $s->shipment_id) }}" class="shrink-0">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="if(confirm('Ubah status pengiriman ini?')) this.form.submit(); else this.value='{{ $s->status }}';" class="bg-transparent border border-muted-border rounded-lg px-2 py-1 text-[10px] font-bold uppercase focus:outline-none focus:border-gold-accent cursor-pointer {{ match($s->status) { 'diterima' => 'text-success border-success/30', 'dikirim' => 'text-secondary border-secondary/30', 'diproses' => 'text-info border-info/30', 'gagal' => 'text-error border-error/30', default => 'text-on-surface-variant border-outline-variant', } }}">
                                    <option value="pending" {{ $s->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diproses" {{ $s->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="dikirim" {{ $s->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="diterima" {{ $s->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="gagal" {{ $s->status === 'gagal' ? 'selected' : '' }}>Gagal</option>
                                </select>
                            </form>
                        </div>

                        <dl class="space-y-2 font-body-md text-sm mb-4">
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Kurir</dt>
                                <dd class="text-on-surface text-right">{{ $s->courier->nama_kurir ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">No. Resi</dt>
                                <dd class="font-mono text-on-surface-variant text-right text-xs">{{ $s->nomor_resi ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Ongkir</dt>
                                <dd class="text-on-surface text-right">Rp {{ number_format((float) $s->ongkir, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between gap-3">
                                <dt class="text-on-surface-variant">Pelanggan</dt>
                                <dd class="text-on-surface text-right">{{ $pelanggan->nama_lengkap ?? '-' }}</dd>
                            </div>
                        </dl>

                        <button type="button" onclick="openDetail(this)" class="w-full min-h-11 inline-flex items-center justify-center gap-2 rounded-lg border border-muted-border text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors"
                            data-order="{{ $s->order->nomor_order ?? '-' }}"
                            data-toko="{{ $s->order->store->nama_toko ?? '-' }}"
                            data-kurir="{{ $s->courier->nama_kurir ?? '-' }}"
                            data-resi="{{ $s->nomor_resi ?? '-' }}"
                            data-ongkir="Rp {{ number_format((float) $s->ongkir, 0, ',', '.') }}"
                            data-estimasi="{{ $s->estimasi_tiba ? \Carbon\Carbon::parse($s->estimasi_tiba)->locale('id')->translatedFormat('d M Y') : '-' }}"
                            data-dikirim="{{ $s->dikirim_pada ? \Carbon\Carbon::parse($s->dikirim_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}"
                            data-diterima="{{ $s->diterima_pada ? \Carbon\Carbon::parse($s->diterima_pada)->locale('id')->translatedFormat('d M Y H:i') : '-' }}"
                            data-status="{{ ucfirst($s->status) }}"
                            data-pelanggan="{{ $pelanggan->nama_lengkap ?? '-' }}">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>Detail
                        </button>
                    </article>
                @empty
                    <p class="text-center text-on-surface-variant py-10">Belum ada data pengiriman.</p>
                @endforelse
                <p id="empty-search-mobile" class="hidden text-center text-on-surface-variant py-10">Tidak ada pengiriman yang cocok.</p>
            </div>

            <!-- Empty Search State -->
            <div id="empty-search" class="hidden flex-col items-center py-12 text-center gap-3">
                <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center">
                    <span class="material-symbols-outlined text-[28px] text-on-surface-variant">search_off</span>
                </div>
                <p class="text-on-surface-variant font-body-md text-sm">Tidak ada pengiriman yang cocok.</p>
                <button type="button" onclick="resetFilter()" class="mt-1 px-5 py-2.5 border border-muted-border rounded-lg text-xs font-semibold text-on-surface hover:border-gold-accent transition-colors">Reset Filter</button>
            </div>

            <p class="text-xs text-on-surface-variant mt-6 pt-5 border-t border-muted-border flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-gold-accent mt-0.5 shrink-0">info</span>
                SA dapat mengubah status pengiriman untuk keperluan darurat. Perubahan status tercatat di riwayat aktivitas.
            </p>
        </div>
    </section>
</div>

<!-- Detail Modal -->
<div id="detail-modal" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="relative mx-auto w-full max-w-md mt-[10vh] bg-surface-container-lowest border border-muted-border rounded-xl shadow-xl max-h-[80vh] overflow-y-auto">
        <div class="flex items-start justify-between gap-4 px-6 pt-6 pb-4 border-b border-muted-border">
            <div>
                <p class="raliva-label text-gold-accent">Detail Pengiriman</p>
                <h3 id="d-order" class="font-title-md text-title-md text-on-surface premium-heading mt-1">-</h3>
            </div>
            <button type="button" onclick="closeModal()" class="text-on-surface-variant hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Toko</label>
                    <p id="d-toko" class="text-sm font-semibold text-on-surface">-</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Pelanggan</label>
                    <p id="d-pelanggan" class="text-sm font-semibold text-on-surface">-</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Kurir</label>
                    <p id="d-kurir" class="text-sm font-semibold text-on-surface">-</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">No. Resi</label>
                    <p id="d-resi" class="text-sm font-semibold text-on-surface font-mono">-</p>
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-on-surface-variant mb-1">Ongkir</label>
                <p id="d-ongkir" class="text-sm font-bold text-gold-accent">-</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Estimasi Tiba</label>
                    <p id="d-estimasi" class="text-sm font-semibold text-on-surface">-</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Status</label>
                    <p id="d-status" class="text-sm font-semibold">-</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Dikirim Pada</label>
                    <p id="d-dikirim" class="text-sm font-semibold text-on-surface">-</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-on-surface-variant mb-1">Diterima Pada</label>
                    <p id="d-diterima" class="text-sm font-semibold text-on-surface">-</p>
                </div>
            </div>
        </div>
        <div class="px-6 pb-6">
            <button type="button" onclick="closeModal()" class="w-full py-3 border border-muted-border rounded-lg text-sm font-semibold text-on-surface hover:border-gold-accent transition-colors">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // === FILTER & SEARCH ===
    function applyFilter() {
        const search = document.getElementById('searchInput').value.toLowerCase().trim();
        const status = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('#table-body tr[data-status]');
        const cards = document.querySelectorAll('#card-grid article[data-status]');
        let visible = 0;

        const match = (el) => {
            const rowStatus = el.dataset.status;
            const rowSearch = el.dataset.search;
            let show = true;
            if (status && rowStatus !== status) show = false;
            if (search && !rowSearch.includes(search)) show = false;
            el.style.display = show ? '' : 'none';
            if (show) visible++;
        };

        rows.forEach(match);
        cards.forEach(match);

        document.getElementById('empty-search').style.display = visible === 0 ? 'flex' : 'none';
        const em = document.getElementById('empty-search-mobile');
        if (em) em.style.display = visible === 0 ? 'block' : 'none';
    }

    function resetFilter() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterStatus').value = '';
        applyFilter();
    }

    // === DETAIL MODAL ===
    function openDetail(btn) {
        const d = btn.dataset;
        document.getElementById('d-order').textContent = d.order;
        document.getElementById('d-toko').textContent = d.toko;
        document.getElementById('d-pelanggan').textContent = d.pelanggan;
        document.getElementById('d-kurir').textContent = d.kurir;
        document.getElementById('d-resi').textContent = d.resi;
        document.getElementById('d-ongkir').textContent = d.ongkir;
        document.getElementById('d-estimasi').textContent = d.estimasi;
        document.getElementById('d-dikirim').textContent = d.dikirim;
        document.getElementById('d-diterima').textContent = d.diterima;
        const statusEl = document.getElementById('d-status');
        statusEl.textContent = d.status;
        const statusColors = { 'Diterima': 'text-success', 'Dikirim': 'text-secondary', 'Diproses': 'text-info', 'Gagal': 'text-error', 'Pending': 'text-on-surface-variant' };
        statusEl.className = 'text-sm font-semibold ' + (statusColors[d.status] || 'text-on-surface');
        document.getElementById('detail-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('detail-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endpush
